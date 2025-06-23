<?php
include(__DIR__ . '/../config/database.php');       // FUNCIONA
require_once __DIR__ . '/../../vendor/autoload.php'; // 2x '../' para alcançar vendor/

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

// Chave secreta do access token
// É crucial que esta chave seja a mesma usada para gerar o token no seu processo de login/autenticação.
$accessTokenSecret = 'seu_segredo_do_access_token';

// Recebe os dados JSON do body da requisição
$dados = json_decode(file_get_contents('php://input'), true);

// Pega o token do header Authorization
$headers = getallheaders();

// 1. Validação inicial: Verifica se o token de autorização foi enviado
if (!isset($headers['Authorization']) || empty($headers['Authorization'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['erro' => 'Token de autorização não enviado.']);
    exit;
}

// Remove o prefixo 'Bearer ' do token
$token = str_replace('Bearer ', '', $headers['Authorization']);

// 2. Decodifica o token JWT para pegar o id do usuário
try {
    // Certifique-se de que a chave usada aqui é a mesma que gerou o JWT
    $decoded = JWT::decode($token, new Key($accessTokenSecret, 'HS256'));
    $id_usuario = $decoded->id_usuario;
} catch (Exception $e) {
    http_response_code(401); // Unauthorized
    echo json_encode(['erro' => 'Token inválido ou expirado. Detalhes: ' . $e->getMessage()]);
    exit;
}

// 3. Valida se o array de contagens das turmas foi enviado e não está vazio
if (!isset($dados['contagens_turmas']) || !is_array($dados['contagens_turmas']) || count($dados['contagens_turmas']) == 0) {
    http_response_code(400); // Bad Request
    echo json_encode(['erro' => 'Campo contagens_turmas é obrigatório e deve ser um array não vazio.']);
    exit;
}

// Opcional: Pegar a data principal enviada do frontend (se for diferente da data atual)
// Você mencionou em uma interação anterior que o frontend envia 'data_principal'.
// Adapte o nome da variável conforme o que o frontend realmente envia para a data geral.
$dataPrincipalContagem = $dados['data_principal'] ?? date('Y-m-d'); // Usa a data enviada ou a data atual como fallback

// 4. Insere uma nova linha na tabela `contagem` com `quant_contagem = 0` temporariamente
// O campo `criacao_contagem` e `update_contagem` serão preenchidos pelo `NOW()` do MySQL.
$stmt = $mysqli->prepare(
    "INSERT INTO contagem (quant_contagem, criacao_contagem, update_contagem, usuarios_id_usuario)
     VALUES (0, NOW(), NOW(), ?)"
);
if (!$stmt) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['erro' => 'Erro interno do servidor ao preparar a query de inserção da contagem principal: ' . $mysqli->error]);
    exit;
}
$stmt->bind_param("i", $id_usuario);
$executado = $stmt->execute();

if (!$executado) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['erro' => 'Erro interno do servidor ao executar a inserção da contagem principal: ' . $stmt->error]);
    exit;
}

$id_contagem = $mysqli->insert_id; // Pega o ID da contagem recém-inserida
$stmt->close(); // Fecha o statement após o uso

// 5. Itera e insere as contagens individuais nas tabelas `contagens_turmas`
$total = 0; // Inicializa o contador total de alunos para a contagem principal
foreach ($dados['contagens_turmas'] as $ct) {
    // Garante que os valores são inteiros para segurança
    $turma_id = intval($ct['turma_id']);
    $quantidade = intval($ct['quantidade']);
    // Pega a data e hora formatada exata do frontend, ou gera uma nova se ausente
    // O frontend está enviando 'data_contagem_turma' no formato YYYY-MM-DD HH:MM:SS
    $dataContagemTurma = $ct['data_contagem_turma'] ?? date('Y-m-d H:i:s');

    $stmtTurma = $mysqli->prepare(
        "INSERT INTO contagens_turmas (turmas_id_turma, contagem_id_contagem, quantidade_turma, data_contagem_turma)
         VALUES (?, ?, ?, ?)"
    );
    if (!$stmtTurma) {
        // Se houver um erro aqui, pode ser um problema de sintaxe SQL ou conexão
        http_response_code(500); // Internal Server Error
        echo json_encode(['erro' => 'Erro interno do servidor ao preparar a query para inserir contagem por turma: ' . $mysqli->error]);
        exit;
    }
    // CORREÇÃO AQUI: Adicionado 's' para o quarto parâmetro (data string)
    $stmtTurma->bind_param("iiis", $turma_id, $id_contagem, $quantidade, $dataContagemTurma);

    $executadoTurma = $stmtTurma->execute();
    if (!$executadoTurma) {
        http_response_code(500); // Internal Server Error
        echo json_encode(['erro' => 'Erro interno do servidor ao executar a inserção de contagem por turma: ' . $stmtTurma->error]);
        exit;
    }
    $stmtTurma->close();

    $total += $quantidade; // Soma a quantidade para o total geral
}

// 6. Atualiza a tabela `contagem` principal com o total somado das turmas
$stmtAtualiza = $mysqli->prepare(
    "UPDATE contagem SET quant_contagem = ?, update_contagem = NOW() WHERE id_contagem = ?"
);
if (!$stmtAtualiza) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['erro' => 'Erro interno do servidor ao preparar a query de atualização do total: ' . $mysqli->error]);
    exit;
}
$stmtAtualiza->bind_param("ii", $total, $id_contagem);
$executadoAtualiza = $stmtAtualiza->execute();

if (!$executadoAtualiza) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['erro' => 'Erro interno do servidor ao atualizar o total da contagem principal: ' . $stmtAtualiza->error]);
    exit;
}
$stmtAtualiza->close(); // Fecha o statement

// 7. Retorna a resposta de sucesso em JSON
http_response_code(200); // OK
echo json_encode([
    'mensagem' => 'Contagem inserida com sucesso!',
    'id_contagem' => $id_contagem,
    'total_contagem' => $total
]);
exit; // Garante que nenhuma outra saída acidental ocorra após o JSON

<?php
include(__DIR__ . '/../config/database.php'); // Certifique-se que este arquivo conecta ao $mysqli

header('Content-Type: application/json');

// Query para pegar as contagens por turma no dia atual e agrupar por categoria
$data = $_GET['data'] ;

$sql = "SELECT 
            ct.turmas_id_turma AS id_turma,
            t.nome_turma AS nome_turma,
            cat.nome_categoria AS nome_categoria,
            SUM(ct.quantidade_turma) AS total_quantidade
        FROM 
            contagens_turmas ct
        INNER JOIN 
            turmas t ON ct.turmas_id_turma = t.id_turma
        INNER JOIN 
            categorias cat ON t.categorias_id_categoria = cat.id_categoria
        WHERE 
            DATE(ct.data_contagem_turma) = '$data'
        GROUP BY 
            cat.nome_categoria, ct.turmas_id_turma, t.nome_turma
        ORDER BY 
            cat.nome_categoria, t.nome_turma"; // Ordena para garantir que a saída seja consistente

$result = $mysqli->query($sql);

$dadosAgrupadosPorCategoria = [];

if ($result && $result->num_rows > 0) {
    while ($linha = $result->fetch_assoc()) {
        $nomeCategoria = $linha['nome_categoria'];
        $idTurma = $linha['id_turma'];
        $nomeTurma = $linha['nome_turma'];
        $totalQuantidade = (int)$linha['total_quantidade'];

        // Se a categoria ainda não existe no nosso array agrupado, cria ela
        if (!isset($dadosAgrupadosPorCategoria[$nomeCategoria])) {
            $dadosAgrupadosPorCategoria[$nomeCategoria] = [
                'categoria' => $nomeCategoria,
                'turmas' => []
            ];
        }

        // Adiciona os dados da turma dentro da sua categoria
        $dadosAgrupadosPorCategoria[$nomeCategoria]['turmas'][] = [
            'id_turma' => $idTurma,
            'nome_turma' => $nomeTurma,
            'total_quantidade' => $totalQuantidade
        ];
    }
}

// Convertemos o array associativo em um array indexado se preferir,
// caso contrário, o JSON terá as categorias como chaves.
// array_values() "reseta" as chaves numéricas do array principal.
echo json_encode(array_values($dadosAgrupadosPorCategoria));

// Boa prática: Fechar a conexão com o banco de dados
$mysqli->close();

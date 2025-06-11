<?php

include(__DIR__ . '/../config/database.php');
header('Content-Type: application/json');

$dados = json_decode(file_get_contents('php://input'), true);

if (isset($dados['nome'], $dados['senha'])) {
    $nome = trim($dados['nome']);
    $senha = $dados['senha'];

    $stmt = $mysqli->prepare("SELECT * FROM usuarios WHERE nome_usuario = ?");
    $stmt->bind_param("s", $nome);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado && $resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($senha, $usuario['senha_usuario'])) {

            $data = date('dmY');
            $ano = date('Y');
            $token = "{$data}_{$nome}_{$ano}_devtheblaze";
            $tokencodificado = hash('sha256', $token);

            $stmtSession = $mysqli->prepare("INSERT INTO session (id_session, token, criado_em, expira_em, status, user368_id_user368) VALUES (NULL, ?, NOW(), 'nunca', '1', ?)");
            $stmtSession->bind_param("ss", $tokencodificado, $usuario['id_usuario']);
            $executado = $stmtSession->execute();

            if ($executado) {
                echo json_encode([
                    'usuario' => $usuario,
                    'token' => $tokencodificado,
                    'mensagem' => 'Login realizado com sucesso!'
                ]);
            } else {
                echo json_encode(['erro' => 'Dados Incorretos']);
            }

            $stmtSession->close();
        } else {
            echo json_encode(['erro' => 'Dados Incorretos']);
        }
    } else {
        echo json_encode(['erro' => 'Dados Incorretos']);
    }

    $stmt->close();
} else {
    echo json_encode(['erro' => 'Dados incompletos']);
}
<?php
$mensagem = null;
$icone = 'error';
$titulo = 'Erro';
$redirecionar = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['txtnome'];
    $senha = $_POST['txtsenha'];

    $dados = [
        'nome' => $nome,
        'senha' => $senha,
    ];

    $json = json_encode($dados);

    $opts = [
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/json',
            'content' => $json
        ]
    ];

    $context = stream_context_create($opts);
    $result = file_get_contents('http://localhost/inspetora/back-end/endpoints/login_user.php', false, $context);

    if ($result === false) {
        $mensagem = 'Erro ao conectar com o servidor.';
    } else {
        $user = json_decode($result, true);

        if (isset($user['erro'])) {
            $mensagem = $user['erro'];
        } else {
            session_start();

            var_dump($result);
exit;
            $_SESSION['id'] = $user['usuario']['id_usuario'];
            $_SESSION['nome'] = $user['usuario']['nome_usuario'];
            $_SESSION['nivel'] = $user['usuario']['nivel_usuario'];
            $_SESSION['senha'] = $senha;
            unset($senha);

            $mensagem = $user['mensagem'] ?? 'Login realizado com sucesso!';
            $icone = 'success';
            $titulo = 'Sucesso';
            $redirecionar = true;
        }
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <title>Login</title>
</head>
<body>
    <div class="background">
        <img src="../assets/FundoDasTelas.png" alt="">
    </div>


    <header></header>

    <main>
        <section id="section_form">
            <div id="logo_formulario">
                <img src="../assets/DevTheBlaze.png" alt="Logo DevTheBlaze">
            </div>
            <!-- <div>
                <h1>Login</h1>
            </div> -->
            <form method="POST">
                <div class="input-container">
                    <i class="bi bi-person-fill"></i>
                    <input type="text" name="txtnome" id="txtlogin" placeholder="Usuário">
                </div>

                <div class="input-container">
                    <i class="bi bi-lock-fill"></i>
                    <input type="password" name="txtsenha" id="txtsenha" placeholder="Senha">
                </div>
                <button type="submit">Acessar</button>

                <a href="cadastro.php">Cadastre-se</a>
            </form>
        </section>
    </main>
    <section class="onda">
        <div class="wave wave1"></div>
        <div class="wave wave2"></div>
        <div class="wave wave3"></div>
        <div class="wave wave4"></div>
    </section>

    <footer></footer>
    <?php if ($mensagem): ?>
        <script>
            Swal.fire({
                icon: '<?= $icone ?>',
                title: '<?= $titulo ?>',
                text: <?= json_encode($mensagem) ?>
            }).then(() => {
                <?php if ($redirecionar): ?>
                    window.location.href = './calendario.php';
                <?php endif; ?>
            });
        </script>
    <?php endif; ?>
    
 
</body>
</html>
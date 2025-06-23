<?php
$mensagem = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['txtnome'];
    $senha = $_POST['txtsenha'];
    $confirmacao = $_POST['txtconfirmacao'];

    $dados = [
        'nome' => $nome,
        'senha' => $senha,
        'confirmacao' => $confirmacao
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
    $result = json_decode(file_get_contents('http://localhost/ContadorInspetoraV2-main/back-end/endpoints/post_user.php', false, $context), true);

    $mensagem = $result['mensagem'] ?? $result['erro'] ?? null;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../front-end/css/style.css?v=1.5">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <title>Cadastro</title>
</head>
<body>
<body>
    <div class="background">
        <img src="../assets/FundoDasTelas.png" alt="">
    </div>


    <header></header>

    <main>
        <section id="section_form_cadastro">
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

                <div class="input-container">
                <i class="bi bi-lock-fill"></i>
                    <input type="password" name="txtconfirmacao" id="txtconfirmacao" placeholder="Confirmar Senha">
                </div>

                <button type="submit">Cadastrar</button>

                <a href="index.php">Faça seu Login</a>
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

    <?php if ($mensagem):
        $icone = strpos($mensagem, 'sucesso') !== false ? 'success' : 'error';
        $titulo = strpos($mensagem, 'sucesso') !== false ? 'Sucesso' : 'Erro';
    ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: '<?= $icone ?>',
                title: '<?= $titulo ?>',
                text: <?= json_encode($mensagem) ?>
            });
        </script>
    <?php endif; ?>
</body>
</html>
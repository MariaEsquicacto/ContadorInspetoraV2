<?php
session_start();

// Pega a data selecionada do calendário via parâmetro GET
$dataSelecionada = $_GET['data'] ?? ''; // Formato esperado:YYYY-MM-DD

// Opcional: Validar e formatar a data para exibição se necessário
$dataParaExibicao = '';
if (!empty($dataSelecionada)) {
    try {
        $dateObj = new DateTime($dataSelecionada);
        $dataParaExibicao = $dateObj->format('d/m/Y');
    } catch (Exception $e) {
        $dataParaExibicao = 'Data inválida'; // Em caso de formato incorreto
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../front-end/css/contagem2.css?v=1.5">
    <title>Contagem</title>
</head>
<body>
    <div class="background">
        <img src="../assets/FundoDasTelas.png" alt="">
    </div>

    <div class="menu">
        <button class="hamburguer">
            <div id="barra1" class="barra"></div>
            <div id="barra2" class="barra"></div>
            <div id="barra3" class="barra"></div>
        </button>

        <nav>
            <ul>
                <!-- <li><a href="estoque.php">Estoque</a></li> -->
                <li><a href="calendario.php">Calendário</a></li>
                <li><a href="relatorio.php">Relatório</a></li>
                <li><a href="index.php">Sair</a></li>
            </ul>
        </nav>
    </div>

    <main>
        <section class="conjunto-categorias">
        <h1>Contagem <?php echo (!empty($dataParaExibicao) ? 'para ' . htmlspecialchars($dataParaExibicao) : ''); ?></h1>   
            <div class="btns-categorias">
                <a href="#" class="btn_contagens">Contagem Ensino Fundamental 1A</a>
                <a href="#" class="btn_contagens">Contagem Ensino Fundamental 1B</a>
                <a href="#" class="btn_contagens">Contagem Ensino Fundamental 2</a>
                <a href="#" class="btn_contagens">Ensino Médio</a>
            </div>
        </section>
    </main>

    <script>
        const abrir_menu = document.querySelector('.hamburguer');
        const menu = document.querySelector('.menu');

        abrir_menu.addEventListener('click', () => {
            abrir_menu.classList.toggle('aberto');
            menu.classList.toggle('ativo');
        });


         // --- Lógica de Redirecionamento dos Botões de Categoria ---
            // Data selecionada vinda do PHP (precisa ser codificada para URL)
            const dataSelecionadaPHP = "<?php echo urlencode($dataSelecionada); ?>";

            const botoesCategorias = document.querySelectorAll('.btn_contagens');

            botoesCategorias.forEach(botao => {
                botao.addEventListener('click', function(event) {
                    event.preventDefault(); // Impede o comportamento padrão do link (#)

                    const categoriaSelecionada = this.textContent.trim();
                    console.log("Categoria selecionada:", categoriaSelecionada);

                    // Codifica a categoria para URL
                    const categoriaEncoded = encodeURIComponent(categoriaSelecionada);

                    // Redireciona para esc_sala.php passando a data e a categoria como parâmetros GET
                    window.location.href = `esc_sala.php?data=${dataSelecionadaPHP}&categoria=${categoriaEncoded}`;
                });
            });

            // --- Lógica do botão de logout ---
            const logoutBtn = document.getElementById('logout-btn');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', async function(e) {
                    e.preventDefault();
                    const currentRefreshToken = sessionStorage.getItem('refresh_token');

                    if (!currentRefreshToken) {
                        alert('Não há token para logout.');
                        sessionStorage.clear();
                        window.location.href = 'index.php';
                        return;
                    }

                    try {
                        const response = await fetch('../../back-end/endpoints/logout.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                refreshToken: currentRefreshToken
                            })
                        });
                        const data = await response.json();

                        if (data.mensagem || response.ok) {
                            alert(data.mensagem || "Logout realizado com sucesso.");
                        } else {
                            alert('Erro ao fazer logout: ' + (data.erro || "Erro desconhecido"));
                            console.error("Erro de logout:", data);
                        }
                    } catch (err) {
                        alert('Erro na requisição de logout: ' + err.message);
                        console.error("Erro de rede no logout:", err);
                    } finally {
                        sessionStorage.clear();
                        window.location.href = 'index.php';
                    }
                });
            }
    </script>
</body>
</html>
<?php
session_start();

// Pega os tokens da sessão PHP
// Usamos (string) para garantir que sejam strings ou vazias, para evitar notices.
$refreshToken = (string)($_SESSION['refresh_token'] ?? '');
$accessToken = (string)($_SESSION['access_token'] ?? '');

// Limpa os tokens da sessão PHP IMEDIATAMENTE após a primeira leitura
// Isso é importante para segurança: os tokens JWT devem ser gerenciados pelo JS no navegador
unset($_SESSION['refresh_token']);
unset($_SESSION['access_token']);

// --- Debugging de Sessão PHP no Servidor ---
error_log("DEBUG CALENDARIO PHP: Access Token lido da SESSION PHP: " . ($accessToken === '' ? 'NÃO ENCONTRADO' : $accessToken));
error_log("DEBUG CALENDARIO PHP: Refresh Token lido da SESSION PHP: " . ($refreshToken === '' ? 'NÃO ENCONTRADO' : $refreshToken));
// --- Fim Debugging de Sessão PHP ---

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../front-end/css/calendario.css?v=1.5">
    <title>Calendário</title>
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
      <!-- <section id="section_form">
        <div id="logo_formulario">
            <img src="../assets/DevTheBlaze.png" alt="Logo DevTheBlaze">
        </div> -->

      <div class="calendar-container">
        <div class="calendar-header">
            <button id="prev-month">❮</button>
            <div class="month">Outubro 2024</div>
            <button id="next-month">❯</button>
        </div>
        <table class="calendar">
            <thead>
                <tr>
                    <th>Dom</th>
                    <th>Seg</th>
                    <th>Ter</th>
                    <th>Qua</th>
                    <th>Qui</th>
                    <th>Sex</th>
                    <th>Sáb</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

        
    </div>
    <div class="selected-date-container">
      <p id="selected-date-message"></p>
      <div class="botoes">
      <button  id="btn-contagem" onclick="toggleContagem()" style="display: none;">Contagem</button>
      <button  id="btn-relatorio" onclick="toggleRelatorio()" style="display: none;">Relatório</button>
      </div>
    </div>


    <div id="view-semanal" style="display: none;"></div>
    <div id="view-mensal" style="display: flex;"></div>

    </main>
        
    <script src="../script.js"></script>
    <script>
        const abrir_menu = document.querySelector('.hamburguer');
        const menu = document.querySelector('.menu');

        abrir_menu.addEventListener('click', () => {
            abrir_menu.classList.toggle('aberto');
            menu.classList.toggle('ativo');
        });

         // Transfere os tokens da sessão PHP para o sessionStorage do navegador
        // json_encode já retorna uma string JS válida (ex: "meutoken" ou null)
        const accessTokenFromPHP = <?= json_encode($accessToken) ?>;
        const refreshTokenFromPHP = <?= json_encode($refreshToken) ?>;

        console.log(accessTokenFromPHP)
        if (accessTokenFromPHP) { // Verifica se é null ou uma string vazia PHP
            sessionStorage.setItem('access_token', accessTokenFromPHP);
            console.log("DEBUG CALENDARIO JS: Access Token salvo no sessionStorage:", accessTokenFromPHP);
        } else {
            console.log("DEBUG CALENDARIO JS: Access Token NÃO encontrado na sessão PHP para salvar ou era vazio/nulo.");
            sessionStorage.removeItem('access_token'); // Garante que não há lixo no sessionStorage
        }
        if (refreshTokenFromPHP) { // Verifica se é null ou uma string vazia PHP
            sessionStorage.setItem('refresh_token', refreshTokenFromPHP);
            console.log("DEBUG CALENDARIO JS: Refresh Token salvo no sessionStorage:", refreshTokenFromPHP);
        } else {
            console.log("DEBUG CALENDARIO JS: Refresh Token NÃO encontrado na sessão PHP para salvar ou era vazio/nulo.");
            sessionStorage.removeItem('refresh_token'); // Garante que não há lixo no sessionStorage
        }

        // Lógica do botão de logout
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
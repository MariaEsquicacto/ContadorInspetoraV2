<?php
session_start(); // Inicia a sessão se precisar de variáveis de sessão ou tokens.
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../front-end/css/relatorio.css?v=1.5">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Relatório de Contagem</title>
    <style>
        /* --- Estilos Gerais da Main (Ajustados para o espaçamento lateral da imagem) --- */
        main {
            display: flex;
            justify-content: center;
            padding: 10px;
            /* background-color: #f0f2f5; */
            min-height: calc(100vh - 150px);
            box-sizing: border-box;
        }

        /* --- Estilos para a Seção Principal do Relatório com Tabelas (Quadrado Azul - Ajustado para altura e largura da imagem) --- */
        #relatorio-contagens-tabelas {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            max-width: 1050px;
            padding: 10px 15px;
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        /* --- Estilos para o Cabeçalho do Relatório (Ajustes para alinhamento e espaçamento) --- */
        .header-relatorio {
            text-align: center;
            margin-bottom: 20px;
            width: 100%;
        }

        .header-relatorio h1 {
            font-size: 2.5em;
            color: #333;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .header-relatorio p {
            font-size: 1.2em;
            color: #666;
            margin: 0;
        }

        /* --- Controles de Filtro de Data (NOVOS ESTILOS) --- */
        .filtro-data-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .filtro-data-container label {
            font-size: 1.1em;
            color: #555;
            font-weight: 500;
        }

        .filtro-data-container input[type="date"] {
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1em;
            outline: none;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .filtro-data-container input[type="date"]:focus {
            border-color: #ff9900;
            box-shadow: 0 0 5px rgba(255, 153, 0, 0.3);
        }

        .filtro-data-container button {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filtro-data-container button:hover {
            background-color: #0056b3;
            transform: translateY(-1px);
        }

        /* --- FIM DOS NOVOS ESTILOS DO FILTRO --- */

        /* --- Contêiner das Tabelas de Categoria (Layout de Grid Fino - Quadrado Verde) --- */
        #tabelas-contagem-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            width: 100%;
            margin-bottom: 25px;
        }

        /* --- Wrapper para cada Tabela de Categoria (Card de Tabela) --- */
        .tabela-categoria-wrapper {
            background-color: #fdfdfd;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-sizing: border-box;
        }

        .tabela-categoria-wrapper:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

        .tabela-categoria-wrapper h3 {
            font-size: 1.6em;
            color: #222;
            margin-bottom: 10px;
            font-weight: 600;
            border-bottom: 2px solid #ff9900;
            padding-bottom: 8px;
            width: 90%;
            text-align: center;
        }

        /* --- Estilos para as Tabelas Internas --- */
        .tabela-contagem {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 1em;
        }

        .tabela-contagem thead {
            background-color: transparent;
            color: #333;
            border-top: 5px solid #E44D26;
            border-radius: 5px 5px 0 0;
            overflow: hidden;
        }

        .tabela-contagem th {
            padding: 10px 8px;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.9em;
            color: #fff;
            background-color: #E44D26;
            border-bottom: none;
        }

        /* Primeiro th e último th para bordas arredondadas e que não vaze o background */
        .tabela-contagem th:first-child {
            border-top-left-radius: 5px;
        }

        .tabela-contagem th:last-child {
            border-top-right-radius: 5px;
        }

        .tabela-contagem td {
            padding: 8px 8px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }

        .tabela-contagem tbody tr:nth-child(even) {
            background-color: #fcfcfc;
        }

        .tabela-contagem tbody tr:hover {
            background-color: #f5f5f5;
        }

        /* Última linha da tabela sem borda inferior */
        .tabela-contagem tbody tr:last-child td {
            border-bottom: none;
        }

        /* --- Estilos para o Total dentro de cada Tabela de Categoria --- */
        .total-categoria-wrapper {
            margin-top: auto;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding-top: 10px;
            border-top: none;
        }

        .total-categoria-wrapper span:first-child {
            font-size: 1.2em;
            font-weight: 600;
            color: #444;
            margin-right: 10px;
        }

        .valor-total-categoria {
            background-color: #ff9900;
            color: #fff;
            font-size: 1.6em;
            font-weight: bold;
            border-radius: 50px;
            padding: 6px 18px;
            display: inline-block;
            min-width: 60px;
            text-align: center;
            box-shadow: 0 2px 6px rgba(255, 153, 0, 0.4);
        }

        /* --- Estilos para o Total Geral Final --- */
        #total-geral-final {
            background-color: #ff6600;
            color: #fff;
            padding: 18px 50px;
            border-radius: 60px;
            font-size: 2.5em;
            font-weight: bold;
            box-shadow: 0 6px 20px rgba(255, 102, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            margin-top: 30px;
        }

        #total-geral-final h2 {
            margin: 0;
            font-size: 1em;
            display: flex;
            align-items: baseline;
            gap: 10px;
        }

        #valor-total-geral {
            font-size: 1em;
        }

        /* --- Mensagens de Estado (Mantido) --- */
        .mensagem-sem-dados,
        .mensagem-erro-dados {
            text-align: center;
            font-size: 1.3em;
            color: #888;
            margin: 30px auto;
            padding: 20px;
            border: 1px solid #eee;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .mensagem-erro-dados {
            color: #d9534f;
            border-color: #d9534f;
            background-color: #fcf8f8;
        }

        /* --- Estilos Responsivos (Ajustados para o novo design e proporções) --- */
        @media (max-width: 1050px) {
            #tabelas-contagem-container {
                gap: 10px;
                grid-template-columns: repeat(4, 1fr);
            }

            .tabela-categoria-wrapper {
                padding: 10px;
            }

            .tabela-contagem th,
            .tabela-contagem td {
                padding: 6px 5px;
                font-size: 0.85em;
            }

            .valor-total-categoria {
                font-size: 1.4em;
                padding: 5px 15px;
            }
        }

        @media (max-width: 900px) {
            #tabelas-contagem-container {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }

            #relatorio-contagens-tabelas {
                max-width: 700px;
            }

            .tabela-categoria-wrapper {
                padding: 15px;
            }

            .tabela-contagem th,
            .tabela-contagem td {
                padding: 8px 8px;
                font-size: 1em;
            }

            .valor-total-categoria {
                font-size: 1.6em;
                padding: 6px 18px;
            }
        }

        @media (max-width: 600px) {
            #tabelas-contagem-container {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            #relatorio-contagens-tabelas {
                max-width: 380px;
                padding: 15px 10px;
            }

            main {
                padding: 10px;
            }

            .tabela-categoria-wrapper {
                padding: 10px;
            }

            .tabela-contagem th,
            .tabela-contagem td {
                padding: 6px;
                font-size: 0.9em;
            }

            .valor-total-categoria {
                font-size: 1.4em;
                padding: 5px 12px;
            }

            #total-geral-final {
                font-size: 1.8em;
                padding: 15px 30px;
            }

            .filtro-data-container {
                flex-direction: column;
                gap: 10px;
            }
        }

        /* Ajustes gerais para fontes e padding em telas muito pequenas */
        @media (max-width: 400px) {
            .header-relatorio h1 {
                font-size: 1.5em;
            }

            .header-relatorio p {
                font-size: 0.8em;
            }

            #total-geral-final {
                font-size: 1.2em;
                padding: 10px 20px;
            }
        }
    </style>
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
                <!-- <li><a href="estoque.php">Estoque</a></li>
                <li><a href="calendario.php">Calendário</a></li>
                <li><a href="relatorio.php">Contagem</a></li>
                <li><a href="#" id="logout-btn">Sair</a></li> -->
                <!-- <li><a href="estoque.php">Estoque</a></li> -->
                <li><a href="calendario.php">Calendário</a></li>
                <li><a href="relatorio.php">Relatório</a></li>
                <li><a href="index.php">Sair</a></li>
            </ul>
        </nav>
    </div>

    <main>
        <section id="relatorio-contagens-tabelas">
            <div class="header-relatorio">
                <h1>Relatório de Contagem</h1>
                <p>Data: <span id="data-relatorio">Carregando...</span></p>
            </div>

            <div class="filtro-data-container">
                <label for="data-filtro">Filtrar por data:</label>
                <input type="date" id="data-filtro">
                <button id="btn-filtrar-data"><i class="fas fa-search"></i> Filtrar</button>
            </div>
            <div id="tabelas-contagem-container">
                <p>Carregando dados das contagens...</p>
            </div>

            <div id="total-geral-final">
                <h2>Total Geral: <span id="valor-total-geral">0</span></h2>
            </div>
        </section>
    </main>

    

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // --- Funções do Menu Hambúrguer (Mantidas) ---
            const abrir_menu = document.querySelector('.hamburguer');
            const menu = document.querySelector('.menu');

            if (abrir_menu && menu) {
                abrir_menu.addEventListener('click', () => {
                    abrir_menu.classList.toggle('aberto');
                    menu.classList.toggle('ativo');
                });
            }

            // --- Lógica do botão de logout (Mantida) ---
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

            // --- Lógica para Carregar e Exibir os Dados do Relatório em Tabelas (COM ALTERAÇÕES) ---
            const tabelasContagemContainer = document.getElementById('tabelas-contagem-container');
            const valorTotalGeralElement = document.getElementById('valor-total-geral');
            const dataRelatorioElement = document.getElementById('data-relatorio');
            const dataFiltroInput = document.getElementById('data-filtro'); // Novo: input de data
            const btnFiltrarData = document.getElementById('btn-filtrar-data'); // Novo: botão de filtrar
            let totalGeralAcumulado = 0; // Usado para o total geral final

            // Função para formatar a data para exibição (ex: 2025-06-18 para 18/06/2025)
            function formatarDataParaExibicao(dataISO) {
                if (!dataISO) return 'N/A';
                try {
                    const [ano, mes, dia] = dataISO.split('-');
                    return `${dia}/${mes}/${ano}`;
                } catch (e) {
                    console.error("Erro ao formatar data:", e);
                    return dataISO; // Retorna original se houver erro
                }
            }

            // Função para obter a data no formato YYYY-MM-DD
            function getFormattedDate(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            // Configura a data inicial no input de filtro e no cabeçalho
            // Primeiro, verifica se há uma data na URL (se o usuário veio do calendário, por exemplo)
            const urlParams = new URLSearchParams(window.location.search);
            let dataParamURL = urlParams.get('data'); // Pega a data da URL se existir

            if (dataParamURL) {
                // Se a data vier da URL, usa-a para configurar o input e o cabeçalho
                dataFiltroInput.value = dataParamURL;
                dataRelatorioElement.textContent = formatarDataParaExibicao(dataParamURL);
            } else {
                // Se não houver data na URL, usa a data atual como padrão
                const hoje = new Date();
                const dataAtualFormatada = getFormattedDate(hoje);
                dataFiltroInput.value = dataAtualFormatada;
                dataRelatorioElement.textContent = formatarDataParaExibicao(dataAtualFormatada);
            }

            // Função assíncrona para buscar e renderizar os dados
            async function carregarRelatorio() {
                // Pega a data que está NO CAMPO DE INPUT
                const dataParaBuscar = dataFiltroInput.value;
                // Atualiza a data que aparece no cabeçalho do relatório
                dataRelatorioElement.textContent = formatarDataParaExibicao(dataParaBuscar);

                // Antes de fazer o fetch, exiba uma mensagem de carregamento
                tabelasContagemContainer.innerHTML = '<p>Carregando dados das contagens...</p>';
                valorTotalGeralElement.textContent = '...';

                try {
                    // Fetch dos dados do backend, passando a data selecionada como parâmetro GET
                    // A URL aqui é relativa ao seu 'relatorio.php'.
                    // Se 'relatorio.php' está em 'front/relatorio.php'
                    // e 'mostrar_contagem.php' está em 'back-end/endpoints/mostrar_contagem.php'
                    // então o caminho '../../back-end/endpoints/mostrar_contagem.php' está correto.
                    const response = await fetch(`../back-end/endpoints/mostrar_contagem.php?data=${dataParaBuscar}`);

                    if (!response.ok) {
                        const errorText = await response.text();
                        throw new Error(`HTTP error! Status: ${response.status} - ${errorText}`);
                    }

                    const dadosContagem = await response.json();

                    tabelasContagemContainer.innerHTML = ''; // Limpa o conteúdo de carregamento

                    if (dadosContagem.length === 0) {
                        tabelasContagemContainer.innerHTML = '<p class="mensagem-sem-dados">Nenhuma contagem encontrada para esta data.</p>';
                        valorTotalGeralElement.textContent = '0';
                        return; // Sai da função se não houver dados
                    }

                    totalGeralAcumulado = 0; // Reseta o total geral antes de somar novamente

                    // Loop para criar uma tabela para cada categoria
                    dadosContagem.forEach(categoriaData => {
                        const categoriaNome = categoriaData.categoria;
                        let totalCategoria = 0;

                        // Cria a estrutura da tabela para a categoria
                        const tabelaHtml = `
                            <div class="tabela-categoria-wrapper">
                                <h3>${categoriaNome}</h3>
                                <table class="tabela-contagem">
                                    <thead>
                                        <tr>
                                            <th>Ano/Turma</th>
                                            <th>Contagem</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${categoriaData.turmas.map(turma => {
                                            totalCategoria += turma.total_quantidade;
                                            return `
                                                <tr>
                                                    <td>${turma.nome_turma}</td>
                                                    <td>${turma.total_quantidade}</td>
                                                </tr>
                                            `;
                                        }).join('')}
                                    </tbody>
                                </table>
                                <div class="total-categoria-wrapper">
                                    <span>Total:</span>
                                    <span class="valor-total-categoria">${totalCategoria}</span>
                                </div>
                            </div>
                        `;
                        tabelasContagemContainer.insertAdjacentHTML('beforeend', tabelaHtml);
                        totalGeralAcumulado += totalCategoria; // Soma ao total geral acumulado
                    });

                    valorTotalGeralElement.textContent = totalGeralAcumulado; // Atualiza o total geral na tela

                } catch (error) {
                    console.error("Erro ao carregar dados da contagem:", error);
                    tabelasContagemContainer.innerHTML = `<p class="mensagem-erro-dados" style="color: red;">Erro ao carregar contagens: ${error.message}</p>`;
                    valorTotalGeralElement.textContent = "Erro";
                }
            }

            // Adiciona o event listener para o botão de filtrar
            btnFiltrarData.addEventListener('click', carregarRelatorio);

            // Chama a função para carregar o relatório quando a página carregar (na primeira vez)
            carregarRelatorio();
        });
    </script>
</body>

</html>
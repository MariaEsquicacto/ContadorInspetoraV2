<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css?v=1.5">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="../front-end/css/teste.css?v=1.5">
    <title>Estoque</title>
   
</head>

<body>
    <header>
        <div id="fig_top" role="img" aria-label="Top decoration"></div>
    </header>

    <div class="menu">
        <button class="hamburguer" aria-expanded="false" aria-controls="main-navigation-list">
            <span class="sr-only">Abrir/Fechar Menu</span>
            <div id="barra1" class="barra"></div>
            <div id="barra2" class="barra"></div>
            <div id="barra3" class="barra"></div>
        </button>

        <nav>
            <ul id="main-navigation-list">
                <li><a href="estoque.php">Estoque</a></li>
                <li><a href="calendario.php">Calendário</a></li>
                <li><a href="relatorio.php">Contagem</a></li>
                <li><a href="index.php">Sair</a></li>
            </ul>
        </nav>
    </div>

    <main id="main-content">
        <section id="estoque-section">
            <h1>Gerenciamento de Estoque</h1>

            <div id="estoque-top-controls">
                <form id="estoque-search-form" action="estoque.php" method="GET" role="search">
                    <div id="estoque-input-group" class="input-container">
                        <label for="estoque-alimento-input" class="sr-only">Digite o alimento para buscar</label>
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                        <input type="search" name="alimento" id="estoque-alimento-input"
                            placeholder="Pesquisar item..."
                            value="<?php echo htmlspecialchars($nome_item_busca); ?>">
                    </div>
                    <button type="submit" id="estoque-search-button">Buscar</button>
                </form>

                <a href="cadastro_item.php" id="estoque-add-button" class="btn-cadastro">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i> Novo Item
                </a>
            </div>

            <?php if ($erro_php): ?>
                <p id="estoque-error-message" class="mensagem-erro" role="alert" aria-live="assertive">
                    <?php echo $erro_php; ?>
                </p>
            <?php endif; ?>

            <div id="estoque-results-container">
                <?php if (!empty($dados)): ?>
                    <h2>Itens no Estoque:</h2>
                    <table id="estoque-data-table">
                        <thead>
                            <tr>
                                <th scope="col">Nome do Item</th>
                                <th scope="col">Tipo de Movimentação</th>
                                <th scope="col">Quantidade</th>
                                <th scope="col">Unidade</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dados as $item): ?>
                                <tr id="item-<?php echo htmlspecialchars($item['id_estoque']); ?>"> ?>">
                                    <td><?php echo htmlspecialchars($item['nome_item_estoque']); ?></td>
                                    <td><?php echo htmlspecialchars($item['tipo_movimentacao_estoque']); ?></td>
                                    <td><?php echo htmlspecialchars($item['quantidade_estoque']); ?></td>
                                    <td><?php echo htmlspecialchars($item['unidade_estoque']); ?></td>
                                    <td>
                                        <a href="editar_item.php?id=<?php echo htmlspecialchars($item['id_estoque']); ?>" class="action-button edit-button" title="Editar Item">
                                            <i class="fa-solid fa-pencil"></i>
                                        </a>
                                        <button class="action-button delete-button" data-id="<?php echo htmlspecialchars($item['id_estoque']); ?>" title="Excluir Item">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php elseif ($nome_item_busca !== ''): ?>
                    <p id="estoque-no-results-message" class="mensagem-info">Nenhum item encontrado com o nome "<?php echo htmlspecialchars($nome_item_busca); ?>".</p>
                <?php else: /* Se a busca estiver vazia e não houver dados ou na carga inicial */ ?>
                    <p id="estoque-initial-message" class="mensagem-info">Não há itens no estoque. Comece buscando ou adicionando um item.</p>
                <?php endif; ?>
            </div>

        </section>
    </main>

    <footer>
        <div id="fig_bottom" role="img" aria-label="Bottom decoration"></div>
        <img src="../assets/losangos_bottom.png" alt="Padrão decorativo de losangos" id="losangos">
    </footer>

    <div id="delete-modal-overlay" class="modal-overlay" aria-hidden="true" role="dialog" aria-labelledby="modal-title" aria-describedby="modal-description">
        <div class="modal-content">
            <h3 id="modal-title">Confirmar Exclusão</h3>
            <p id="modal-description">Tem certeza de que deseja excluir este item do estoque?</p>
            <div class="modal-buttons">
                <button id="confirm-delete-button" class="modal-button confirm">Excluir</button>
                <button id="cancel-delete-button" class="modal-button cancel">Cancelar</button>
            </div>
        </div>
    </div>
    <script>
        const abrir_menu = document.getElementsByClassName('hamburguer')[0];
        const menu = document.getElementsByClassName('menu')[0];
        abrir_menu.addEventListener('click', () => {
            abrir_menu.classList.toggle('aberto');
            menu.classList.toggle('ativo');
        });

        // Lógica do Modal de Confirmação de Exclusão
        const deleteModalOverlay = document.getElementById('delete-modal-overlay');
        const confirmDeleteButton = document.getElementById('confirm-delete-button');
        const cancelDeleteButton = document.getElementById('cancel-delete-button');
        let itemIdToDelete = null; // Variável para armazenar o ID do item a ser excluído

        // Função para exibir o modal
        function showDeleteModal(itemId) {
            itemIdToDelete = itemId;
            deleteModalOverlay.classList.add('active');
            deleteModalOverlay.setAttribute('aria-hidden', 'false');
            // Foca no botão de cancelar para acessibilidade
            cancelDeleteButton.focus();
        }

        // Função para ocultar o modal
        function hideDeleteModal() {
            deleteModalOverlay.classList.remove('active');
            deleteModalOverlay.setAttribute('aria-hidden', 'true');
            itemIdToDelete = null; // Limpa o ID
        }

        // Adiciona event listeners a todos os botões de exclusão
        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.dataset.id; // Pega o ID do atributo data-id
                showDeleteModal(itemId);
            });
        });

        // Event listener para o botão "Cancelar" do modal
        cancelDeleteButton.addEventListener('click', hideDeleteModal);

        // Event listener para clicar fora do modal (no overlay) para fechar
        deleteModalOverlay.addEventListener('click', function(event) {
            if (event.target === deleteModalOverlay) {
                hideDeleteModal();
            }
        });

        // Event listener para o botão "Excluir" do modal
        confirmDeleteButton.addEventListener('click', async () => {
            if (itemIdToDelete) {
                try {
                    // Prepara os dados para enviar via POST
                    const formData = new FormData();
                    formData.append('id', itemIdToDelete);

                    // ATENÇÃO: Verifique este caminho para o seu arquivo de exclusão
                    // O caminho abaixo assume que estoque.php está em 'front-end/pages/'
                    // e excluir_item.php está em 'back-end/api/'
                    const response = await fetch('../../back-end/api/excluir_item.php', {
                        method: 'POST',
                        body: formData // Envia o FormData
                    });

                    if (!response.ok) {
                        throw new Error(`Erro HTTP! Status: ${response.status}`);
                    }

                    const result = await response.json();

                    if (result.success) {
                        alert(result.message); // Ou exiba uma mensagem de sucesso mais elegante
                        // Remove a linha da tabela se a exclusão for bem-sucedida
                        const rowToRemove = document.getElementById(`item-${itemIdToDelete}`);
                        if (rowToRemove) {
                            rowToRemove.remove();
                        }
                        // Se não houver mais itens, exibe a mensagem de "não há itens"
                        const tableBody = document.getElementById('estoque-data-table').querySelector('tbody');
                        if (tableBody && tableBody.children.length === 0) {
                            const resultsContainer = document.getElementById('estoque-results-container');
                            resultsContainer.innerHTML = '<p id="estoque-initial-message" class="mensagem-info">Não há itens no estoque. Comece buscando ou adicionando um item.</p>';
                        }
                    } else {
                        alert('Erro ao excluir item: ' + result.message); // Exibe a mensagem de erro do PHP
                    }
                } catch (error) {
                    console.error('Erro ao fazer a requisição de exclusão:', error);
                    alert('Ocorreu um erro ao tentar excluir o item. Por favor, tente novamente.');
                } finally {
                    hideDeleteModal(); // Sempre oculta o modal após a tentativa de exclusão
                }
            }
        });
    </script>
</body>

</html>
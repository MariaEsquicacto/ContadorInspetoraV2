const calendarContainer = document.querySelector('.calendar-container');
let selectedDateElement = null;
let currentDate = new Date();

function atualizarMesAno() {
    const nomesMeses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
    const elementoMes = document.querySelector('.month');
    elementoMes.textContent = `${nomesMeses[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
}

function gerarCalendario() {
    const corpoCalendario = document.querySelector('.calendar tbody');
    corpoCalendario.innerHTML = '';

    const hoje = new Date(); // ✅ Adicionado aqui
    const primeiroDia = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1).getDay();
    const ultimoDia = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();

    let data = 1;
    for (let i = 0; i < 6; i++) {
        let linha = document.createElement('tr');

        for (let j = 0; j < 7; j++) {
            let celula = document.createElement('td');

            if (i === 0 && j < primeiroDia) {
                celula.classList.add('inactive');
            } else if (data > ultimoDia) {
                celula.classList.add('inactive');
            } else {
                let diaSelecionado = data;
                celula.textContent = data;

                // ✅ Marca o dia de hoje
                if (
                    data === hoje.getDate() &&
                    currentDate.getMonth() === hoje.getMonth() &&
                    currentDate.getFullYear() === hoje.getFullYear()
                ) {
                    celula.classList.add('hoje');
                }

                celula.addEventListener('click', function () {
                    if (selectedDateElement) {
                        selectedDateElement.classList.remove('selected');
                    }
                    celula.classList.add('selected');
                    selectedDateElement = celula;

                    const mensagemDataSelecionada = document.getElementById("selected-date-message");
                    mensagemDataSelecionada.textContent = `Você selecionou: ${diaSelecionado}/${currentDate.getMonth() + 1}/${currentDate.getFullYear()}`;

                    document.getElementById("btn-contagem").style.display = "block";
                    document.getElementById("btn-relatorio").style.display = "block";
                });

                data++;
            }

            linha.appendChild(celula);
        }

        corpoCalendario.appendChild(linha);
    }
}

// Navegação entre meses
document.getElementById('prev-month').addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    atualizarMesAno();
    gerarCalendario();
});

document.getElementById('next-month').addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    atualizarMesAno();
    gerarCalendario();
});

// Inicialização
atualizarMesAno();
gerarCalendario();

function toggleContagem(){
    window.location.href = 'contagem.html'
}

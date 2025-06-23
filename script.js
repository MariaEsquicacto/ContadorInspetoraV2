const calendarContainer = document.querySelector('.calendar-container');
let selectedDateElement = null;
let selectedDate = ''; // 👉 NOVA variável global para guardar a data no formato YYYY-MM-DD
let currentDate = new Date();

function atualizarMesAno() {
    const nomesMeses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
    const elementoMes = document.querySelector('.month');
    elementoMes.textContent = `${nomesMeses[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
}

function gerarCalendario() {
    const corpoCalendario = document.querySelector('.calendar tbody');
    corpoCalendario.innerHTML = '';

    const hoje = new Date();
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

                    // 👉 Formata a data para YYYY-MM-DD e salva na variável global selectedDate
                    const mes = String(currentDate.getMonth() + 1).padStart(2, '0');
                    const dia = String(diaSelecionado).padStart(2, '0');
                    selectedDate = `${currentDate.getFullYear()}-${mes}-${dia}`;

                    const mensagemDataSelecionada = document.getElementById("selected-date-message");
                    mensagemDataSelecionada.textContent = `Você selecionou: ${dia}/${mes}/${currentDate.getFullYear()}`;

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

// ⚠️ 👉 CORREÇÃO DA FUNÇÃO toggleContagem():
function toggleContagem() {
    if (selectedDate) {
        window.location.href = `contagem2.php?data=${selectedDate}`;
    } else {
        alert("Por favor, selecione uma data no calendário primeiro.");
    }
}

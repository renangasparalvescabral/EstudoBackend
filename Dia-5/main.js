document.addEventListener('DOMContentLoaded', () => {

    // Elementos do DOM
    const body = document.body;
    const btnTema = document.getElementById('btnTema');
    const contador = document.getElementById('contador');
    const formTarefa = document.getElementById('formTarefa');
    const inputTarefa = document.getElementById('novaTarefa');
    const listaTarefas = document.getElementById('listaTarefas');
    const btnLimpar = document.getElementById('btnLimpar');
    const storageView = document.getElementById('storageView');

    // ==========================================
    // 1. CONTADOR DE VISITAS
    // ==========================================
    function iniciarContador() {
        let visitas = localStorage.getItem('visitas');

        if (visitas === null) {
            visitas = 1;
        } else {
            visitas = parseInt(visitas) + 1;
        }

        localStorage.setItem('visitas', visitas);
        contador.textContent = visitas;
    }

    // ==========================================
    // 2. TEMA CLARO/ESCURO
    // ==========================================
    function iniciarTema() {
        const temaSalvo = localStorage.getItem('tema') || 'light';
        body.className = temaSalvo;
        atualizarBotaoTema();
    }

    function alternarTema() {
        const novoTema = body.classList.contains('light') ? 'dark' : 'light';
        body.className = novoTema;
        localStorage.setItem('tema', novoTema);
        atualizarBotaoTema();
        atualizarStorageView();
    }

    function atualizarBotaoTema() {
        const temaAtual = body.classList.contains('dark') ? 'escuro' : 'claro';
        btnTema.textContent = `Tema: ${temaAtual}`;
    }

    btnTema.addEventListener('click', alternarTema);

    // ==========================================
    // 3. LISTA DE TAREFAS
    // ==========================================
    function carregarTarefas() {
        const tarefasSalvas = localStorage.getItem('tarefas');

        if (tarefasSalvas) {
            return JSON.parse(tarefasSalvas);
        }

        return [];
    }

    function salvarTarefas(tarefas) {
        localStorage.setItem('tarefas', JSON.stringify(tarefas));
        atualizarStorageView();
    }

    function renderizarTarefas() {
        const tarefas = carregarTarefas();

        if (tarefas.length === 0) {
            listaTarefas.innerHTML = '<li class="lista-vazia">Nenhuma tarefa adicionada</li>';
            return;
        }

        listaTarefas.innerHTML = '';

        tarefas.forEach((tarefa, index) => {
            const li = document.createElement('li');
            li.className = 'tarefa' + (tarefa.concluida ? ' concluida' : '');

            li.innerHTML = `
                <input type="checkbox" ${tarefa.concluida ? 'checked' : ''} data-index="${index}">
                <span>${tarefa.texto}</span>
                <button data-index="${index}">Excluir</button>
            `;

            listaTarefas.appendChild(li);
        });
    }

    function adicionarTarefa(texto) {
        const tarefas = carregarTarefas();

        tarefas.push({
            texto: texto,
            concluida: false,
            criadaEm: new Date().toISOString()
        });

        salvarTarefas(tarefas);
        renderizarTarefas();
    }

    function alternarConclusao(index) {
        const tarefas = carregarTarefas();
        tarefas[index].concluida = !tarefas[index].concluida;
        salvarTarefas(tarefas);
        renderizarTarefas();
    }

    function excluirTarefa(index) {
        const tarefas = carregarTarefas();
        tarefas.splice(index, 1);
        salvarTarefas(tarefas);
        renderizarTarefas();
    }

    function limparTarefas() {
        if (confirm('Tem certeza que deseja excluir todas as tarefas?')) {
            localStorage.removeItem('tarefas');
            renderizarTarefas();
            atualizarStorageView();
        }
    }

    // Event Listeners para tarefas
    formTarefa.addEventListener('submit', (e) => {
        e.preventDefault();
        const texto = inputTarefa.value.trim();

        if (texto) {
            adicionarTarefa(texto);
            inputTarefa.value = '';
            inputTarefa.focus();
        }
    });

    listaTarefas.addEventListener('click', (e) => {
        const index = e.target.dataset.index;

        if (e.target.type === 'checkbox') {
            alternarConclusao(parseInt(index));
        } else if (e.target.tagName === 'BUTTON') {
            excluirTarefa(parseInt(index));
        }
    });

    btnLimpar.addEventListener('click', limparTarefas);

    // ==========================================
    // 4. VISUALIZADOR DO LOCALSTORAGE
    // ==========================================
    function atualizarStorageView() {
        const dados = {
            visitas: localStorage.getItem('visitas'),
            tema: localStorage.getItem('tema'),
            tarefas: JSON.parse(localStorage.getItem('tarefas') || '[]')
        };

        storageView.textContent = JSON.stringify(dados, null, 2);
    }

    // ==========================================
    // INICIALIZACAO
    // ==========================================
    iniciarContador();
    iniciarTema();
    renderizarTarefas();
    atualizarStorageView();

});

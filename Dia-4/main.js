document.addEventListener('DOMContentLoaded', () => {

    const form = document.querySelector('.pesquisa');
    const loading = document.querySelector('.ajaxloading');
    const resultadosContainer = document.getElementById('resultados');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const term = form.busca.value.trim();

        // Validacao simples
        if (!term) {
            new jBox('Notice', { content: 'O campo esta vazio!', color: 'red' });
            return;
        }

        // Mostra o loading e limpa resultados anteriores
        loading.style.display = 'block';
        resultadosContainer.innerHTML = '';

        try {
            const response = await fetch('api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ busca: term })
            });

            const data = await response.json();

            // Se o servidor devolveu erro (status 400+)
            if (!response.ok) {
                throw new Error(data.error || 'Erro ao processar a busca');
            }

            // Exibe notificacao de sucesso
            new jBox('Notice', { content: data.message, color: 'green' });

            // Exibe os resultados
            exibirResultados(data);

        } catch (err) {
            new jBox('Notice', { content: 'Erro: ' + err.message, color: 'red' });
            resultadosContainer.innerHTML = '<p class="erro">Erro ao buscar resultados</p>';
        } finally {
            loading.style.display = 'none';
        }

    });

    function exibirResultados(data) {
        if (data.total === 0) {
            resultadosContainer.innerHTML = `
                <div class="sem-resultados">
                    <p>Nenhum produto encontrado para "<strong>${data.termo}</strong>"</p>
                    <p>Tente: notebook, mouse, teclado, monitor, headset, webcam, ssd, memoria</p>
                </div>
            `;
            return;
        }

        let html = `<h2>Resultados para "${data.termo}" (${data.total})</h2>`;
        html += '<ul class="lista-produtos">';

        data.resultados.forEach(produto => {
            const precoFormatado = produto.preco.toLocaleString('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            });
            html += `
                <li class="produto">
                    <span class="produto-nome">${produto.nome}</span>
                    <span class="produto-preco">${precoFormatado}</span>
                </li>
            `;
        });

        html += '</ul>';
        resultadosContainer.innerHTML = html;
    }

});

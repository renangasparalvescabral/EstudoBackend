document.addEventListener('DOMContentLoaded', () => {

    const form = document.querySelector('.pesquisa');
    const loading = document.querySelector('.ajaxloading');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const term = form.busca.value.trim();

        // Validação simples
        if (!term) {
            new jBox('Notice', { content: 'O campo está vazio!', color: 'red' });
            return;
        }

        // Mostra o loading
        loading.style.display = 'block';

        try {
            // FAKE URL — altere depois quando tiver a rota real
            const response = await fetch('/Dia2/EstudoBackend/Dia-4/', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ busca: term })
            });

            const data = await response.json().catch(() => null);

            // Se o servidor devolveu erro (status 400+)
            if (!response.ok) {
                const msg = data?.error || data?.message || 'Erro ao processar a busca';
                throw new Error(msg);
            }

            // Se chegou aqui → sucesso ✔️
            const msg = data?.message || 'Busca realizada com sucesso!';
            new jBox('Notice', { content: msg, color: 'green' });

        } catch (err) {
            new jBox('Notice', { content: 'Erro: ' + err.message, color: 'red' });
        } finally {
            loading.style.display = 'none';
        }

    });
});

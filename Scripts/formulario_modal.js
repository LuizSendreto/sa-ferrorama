'use strict';
// Carrega o formulário existente para conservar campos, opções e validações do PHP.
(() => {
    const modal = document.getElementById('modal-formulario');
    if (!modal) return;
    const conteudo = document.getElementById('conteudo-formulario');
    const erro = modal.querySelector('.strain-erro');
    let carregamento = null;
    let salvando = false;
    let origem = '';
    const exibirErro = (texto) => { erro.textContent = texto; erro.hidden = false; };
    const bloquearFechamento = (evento) => { if (salvando) evento.preventDefault(); };
    modal.addEventListener('cancel', bloquearFechamento);
    modal.addEventListener('close', () => { if (carregamento && !salvando) carregamento.abort(); });

    document.addEventListener('click', async (evento) => {
        const link = evento.target.closest('[data-formulario-modal]');
        if (!link || evento.ctrlKey || evento.metaKey || evento.shiftKey || evento.altKey) return;
        evento.preventDefault();
        origem = link.href;
        if (carregamento) carregamento.abort();
        carregamento = new AbortController();
        const controle = carregamento;
        document.getElementById('titulo-formulario').textContent = link.dataset.titulo;
        erro.hidden = true;
        conteudo.textContent = 'Carregando formulário…';
        modal.showModal();
        try {
            const resposta = await fetch(origem, { credentials:'same-origin', signal:controle.signal });
            if (resposta.redirected || !resposta.ok) throw new Error('Não foi possível abrir o formulário. Confira sua sessão e tente novamente.');
            const doc = new DOMParser().parseFromString(await resposta.text(), 'text/html');
            const form = doc.querySelector('form.formulario_strain');
            if (!form) throw new Error('Formulário indisponível. Recarregue a página e confira sua sessão.');
            form.action = new URL(form.getAttribute('action') || origem, origem).href;
            form.dataset.remoto = '1';
            conteudo.replaceChildren(document.importNode(form, true));
            const mensagem = doc.querySelector('.mensagem_erro_formulario');
            if (mensagem) exibirErro(mensagem.textContent.trim());
            conteudo.querySelector('input:not([type=hidden]),select,textarea')?.focus();
        } catch (falha) {
            if (falha.name === 'AbortError') return;
            conteudo.textContent = '';
            exibirErro(falha.message);
        }
    });

    conteudo.addEventListener('submit', async (evento) => {
        const form = evento.target.closest('form[data-remoto]');
        if (!form) return;
        evento.preventDefault();
        if (salvando || !form.reportValidity()) return;
        salvando = true;
        erro.hidden = true;
        const botoes = [...modal.querySelectorAll('button')];
        const dados = new FormData(form);
        botoes.forEach(b => { b.disabled = true; });
        form.setAttribute('aria-busy', 'true');
        try {
            const resposta = await fetch(form.action, {method:'POST',body:dados,credentials:'same-origin'});
            const destino = new URL(resposta.url);
            const listagem = new URL(window.location.href);
            if (resposta.ok && resposta.redirected && destino.origin === listagem.origin && destino.pathname === listagem.pathname) {
                listagem.searchParams.set('sucesso','1');
                window.location.assign(listagem.href);
                return;
            }
            if (resposta.redirected) throw new Error('Sua sessão pode ter expirado. Recarregue a página antes de tentar novamente.');
            const texto = await resposta.text();
            const doc = new DOMParser().parseFromString(texto, 'text/html');
            const mensagem = doc.querySelector('.mensagem_erro_formulario');
            exibirErro(mensagem ? mensagem.textContent.trim() : 'Não foi possível confirmar a operação. Confira sua sessão e a listagem antes de tentar novamente.');
        } catch (falha) {
            exibirErro(falha instanceof TypeError ? 'Falha na conexão. Confira a listagem antes de tentar novamente.' : falha.message);
        } finally {
            salvando = false;
            botoes.forEach(b => { b.disabled = false; });
            form.removeAttribute('aria-busy');
        }
    });
})();

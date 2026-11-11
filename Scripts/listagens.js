'use strict';

// Diálogos nativos mantêm o foco dentro do modal e permitem fechar com Escape.
const abrirModal = (nome) => {
    const modal = document.getElementById(`modal-${nome}`);
    if (!modal) return null;
    const erro = modal.querySelector('.strain-erro');
    if (erro) { erro.hidden = true; erro.textContent = ''; }
    modal.showModal();
    return modal;
};
const normalizar = (valor) => String(valor ?? '').normalize('NFD').replace(/[\u0300-\u036f]/g, '').trim().toLowerCase();

document.addEventListener('click', (evento) => {
    const botao = evento.target.closest('button, a');
    if (!botao) return;
    if (botao.hasAttribute('data-abrir')) {
        document.querySelector('#modal-cadastro form').reset();
        abrirModal('cadastro');
    }
    if (botao.hasAttribute('data-fechar')) botao.closest('dialog').close();
    if (botao.hasAttribute('data-editar')) {
        const dados = JSON.parse(botao.dataset.editar);
        const form = document.querySelector('#modal-edicao form');
        form.reset();
        for (const campo of form.elements) {
            const chave = campo.name === 'tipo_sensor' ? 'tipo' : campo.name;
            if (!Object.hasOwn(dados, chave)) continue;
            if (campo instanceof HTMLSelectElement) {
                const opcao = Array.from(campo.options).find((o) => normalizar(o.value) === normalizar(dados[chave]));
                campo.value = opcao ? opcao.value : '';
            } else campo.value = dados[chave] ?? '';
        }
        abrirModal('edicao');
    }
    if (botao.hasAttribute('data-excluir')) {
        const modal = document.getElementById('modal-excluir');
        modal.querySelector('[name="id"]').value = botao.dataset.excluir;
        document.getElementById('nome-exclusao').textContent = botao.dataset.nome;
        abrirModal('excluir');
    }
    if (botao.hasAttribute('data-logout')) {
        evento.preventDefault();
        abrirModal('logout');
    }
});

for (const form of document.querySelectorAll('[data-form-modal]')) {
    form.addEventListener('submit', async (evento) => {
        evento.preventDefault();
        if (form.dataset.enviando === '1' || !form.reportValidity()) return;
        const erro = form.querySelector('.strain-erro');
        erro.hidden = true;
        if (form.elements.senha && form.elements.senha.value !== form.elements.confirmarSenha.value) {
            erro.textContent = 'As senhas não são iguais.';
            erro.hidden = false;
            form.elements.confirmarSenha.focus();
            return;
        }
        const modal = form.closest('dialog');
        const impedirFechar = (e) => e.preventDefault();
        const botoes = Array.from(form.querySelectorAll('button'));
        const submit = form.querySelector('[type="submit"]');
        const texto = submit.textContent;
        const dados = new FormData(form);
        form.dataset.enviando = '1';
        botoes.forEach((b) => { b.disabled = true; });
        modal.addEventListener('cancel', impedirFechar);
        submit.textContent = 'Salvando…';
        try {
            const resposta = await fetch(form.action, {
                method: 'POST', body: dados, credentials: 'same-origin',
                headers: { Accept: 'application/json' }
            });
            if (resposta.redirected || !(resposta.headers.get('content-type') || '').includes('application/json')) {
                throw new Error('Não foi possível confirmar a operação. Recarregue a página e confira sua sessão e a listagem antes de tentar novamente.');
            }
            const resultado = await resposta.json();
            if (!resposta.ok || !resultado.ok) throw new Error(resultado.mensagem || 'Não foi possível salvar.');
            // Recarrega a mesma página; o PHP ajusta o número se a última linha for excluída.
            const destino = new URL(window.location.href);
            destino.searchParams.set('sucesso', '1');
            window.location.assign(destino.href);
        } catch (falha) {
            erro.textContent = falha instanceof TypeError
                ? 'Falha na conexão. Confira a listagem antes de tentar novamente.' : falha.message;
            erro.hidden = false;
            erro.scrollIntoView({ block: 'nearest' });
        } finally {
            form.dataset.enviando = '0';
            botoes.forEach((b) => { b.disabled = false; });
            submit.textContent = texto;
            modal.removeEventListener('cancel', impedirFechar);
        }
    });
}
const enderecoAtual = new URL(window.location.href);
if (enderecoAtual.searchParams.get('sucesso') === '1') {
    const aviso = document.getElementById('aviso-listagem');
    if (aviso) { aviso.textContent = 'Operação concluída com sucesso.'; aviso.hidden = false; }
    enderecoAtual.searchParams.delete('sucesso');
    window.history.replaceState(null, '', enderecoAtual);
}

// Confirmação explícita para as exclusões de trens e rotas.
document.addEventListener('submit', (evento) => {
    const form = evento.target.closest('[data-confirmar-exclusao]');
    if (form && !window.confirm(form.dataset.confirmarExclusao)) evento.preventDefault();
});

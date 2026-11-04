
/* API ViaCEP - Projeto Strain
   Consulta, salva e recupera o CEP do usuário
*/

const secaoCep = document.getElementById("strain-cep");

if (secaoCep) {

    const formCep = document.getElementById("strain-cep-form");
    const campoCep = document.getElementById("strain-cep-input");
    const mensagemCep = document.getElementById("strain-cep-mensagem");

    const botaoCep = document.getElementById("strain-cep-consultar");
    const botaoSalvar = document.getElementById("strain-cep-salvar");

    const apiCep = secaoCep.dataset.api;
    const tokenCep = secaoCep.dataset.csrf;

    let cepValidado = "";

    // Exibir mensagens
    function mostrarMensagem(texto, tipo = "") {
        mensagemCep.textContent = texto;
        mensagemCep.dataset.tipo = tipo;
    }

    // Limpar campos do endereço
    function limparEndereco() {

        document.getElementById("strain-cep-logradouro").value = "";
        document.getElementById("strain-cep-bairro").value = "";
        document.getElementById("strain-cep-cidade").value = "";
        document.getElementById("strain-cep-uf").value = "";

        cepValidado = "";
        botaoSalvar.disabled = true;
    }

    // Preencher endereço automaticamente
    function preencherEndereco(dados) {

        campoCep.value = dados.cep.replace(
            /^(\d{5})(\d{3})$/, "$1-$2"
        );

        document.getElementById("strain-cep-logradouro").value =
            dados.logradouro || "";

        document.getElementById("strain-cep-bairro").value =
            dados.bairro || "";

        document.getElementById("strain-cep-cidade").value =
            dados.cidade || "";

        document.getElementById("strain-cep-uf").value =
            dados.uf || "";
    }

    // Comunicação com o PHP
    async function requisitar(url, opcoes = {}) {

        const controlador = new AbortController();

        const limite = setTimeout(() => {
            controlador.abort();
        }, 15000);

        try {

            const resposta = await fetch(url, {
                ...opcoes,
                credentials: "same-origin",
                signal: controlador.signal
            });

            const dados = await resposta.json();

            if (!resposta.ok || !dados.ok) {
                throw new Error(
                    dados.mensagem || "Erro ao consultar o CEP."
                );
            }

            return dados;

        } catch (erro) {

            if (erro.name === "AbortError") {
                throw new Error("A consulta demorou muito.");
            }

            if (erro instanceof TypeError ||
                erro instanceof SyntaxError) {
                throw new Error("Falha na comunicação com o servidor.");
            }

            throw erro;

        } finally {
            clearTimeout(limite);
        }
    }

    // Formatar CEP ao digitar
    campoCep.addEventListener("input", function() {

        let cep = campoCep.value.replace(/\D/g, "").slice(0, 8);

        if (cep.length > 5) {
            cep = cep.slice(0, 5) + "-" + cep.slice(5);
        }

        campoCep.value = cep;

        limparEndereco();
        mostrarMensagem("");
    });

    // CONSULTAR CEP
    formCep.addEventListener("submit", async function(evento) {

        evento.preventDefault();

        const cep = campoCep.value.replace(/\D/g, "");

        limparEndereco();

        // Validar formato
        if (!/^\d{8}$/.test(cep)) {
            mostrarMensagem(
                "CEP inválido! Digite 8 números.",
                "erro"
            );
            return;
        }

        botaoCep.disabled = true;
        mostrarMensagem("Consultando CEP...");

        try {

            // PHP consulta a API ViaCEP
            const dados = await requisitar(
                `${apiCep}?acao=consultar&cep=${cep}`
            );

            // Evita preencher após o usuário alterar o CEP
            if (campoCep.value.replace(/\D/g, "") !== cep) {
                return;
            }

            preencherEndereco(dados.endereco);

            cepValidado = cep;
            botaoSalvar.disabled = false;

            mostrarMensagem(
                "Endereço encontrado! Clique em Salvar endereço.",
                "sucesso"
            );

        } catch (erro) {

            mostrarMensagem(erro.message, "erro");
            console.error(erro);

        } finally {

            botaoCep.disabled = false;
        }
    });

    // SALVAR NO BANCO DE DADOS
    botaoSalvar.addEventListener("click", async function() {

        if (!cepValidado ||
            campoCep.value.replace(/\D/g, "") !== cepValidado) {
            return;
        }

        botaoSalvar.disabled = true;

        mostrarMensagem("Salvando endereço...");

        try {

            const formulario = new URLSearchParams();

            formulario.append("cep", cepValidado);

            const dados = await requisitar(apiCep, {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                    "X-CSRF-Token": tokenCep
                },
                body: formulario
            });

            preencherEndereco(dados.endereco);

            mostrarMensagem(
                "Endereço salvo com sucesso!",
                "sucesso"
            );

        } catch (erro) {

            mostrarMensagem(erro.message, "erro");
            botaoSalvar.disabled = false;
            console.error(erro);
        }
    });

    // RECUPERAR ENDEREÇO SALVO DO USUÁRIO
    async function carregarEndereco() {

        try {

            const dados = await requisitar(
                `${apiCep}?acao=salvo`
            );

            if (dados.endereco && !campoCep.value) {

                preencherEndereco(dados.endereco);

                mostrarMensagem(
                    "Endereço cadastrado na sua conta.",
                    "sucesso"
                );
            }

        } catch (erro) {

            mostrarMensagem(erro.message, "erro");
            console.error(erro);
        }
    }

    // Executa quando a página abre
    carregarEndereco();
}


/* API ViaCEP - Projeto Strain
   Um único arquivo para cadastro de usuários, cadastro de funcionários e Dashboard.
   Consulta real ao ViaCEP; o salvamento no Dashboard é opcional e usa o PHP existente.
*/

document.addEventListener("DOMContentLoaded", () => {
    const porId = (id) => document.getElementById(id);
    const somenteNumeros = (valor) => String(valor || "").replace(/\D/g, "");
    const formatarCep = (valor) => {
        const digitos = somenteNumeros(valor).slice(0, 8);
        return digitos.length > 5
            ? digitos.slice(0, 5) + "-" + digitos.slice(5)
            : digitos;
    };

    function iniciarConsulta({ input, campos, mensagem, botaoConsultar, formulario, botaoSalvar }) {
        if (!input || !mensagem || Object.values(campos).some((campo) => !campo)) {
            return null;
        }

        let temporizador = null;
        let controlador = null;
        let consultaAtual = 0;
        let cepValidado = "";

        function avisar(texto, tipo = "") {
            mensagem.textContent = texto;
            mensagem.dataset.tipo = tipo;
        }

        function limpar() {
            Object.values(campos).forEach((campo) => { campo.value = ""; });
            cepValidado = "";
            if (botaoSalvar) botaoSalvar.disabled = true;
        }

        function preencher(dados) {
            input.value = formatarCep(dados.cep || input.value);
            campos.logradouro.value = dados.logradouro || "";
            campos.bairro.value = dados.bairro || "";
            campos.cidade.value = dados.localidade || dados.cidade || "";
            campos.uf.value = dados.uf || "";
        }

        function cancelarConsulta() {
            clearTimeout(temporizador);
            consultaAtual++;

            if (controlador) {
                controlador.abort();
                controlador = null;
            }

            if (botaoConsultar) botaoConsultar.disabled = false;
        }

        // CONSULTAR O CEP NA API REAL
        async function consultar() {
            cancelarConsulta();
            limpar();

            const cep = somenteNumeros(input.value);

            if (!/^\d{8}$/.test(cep)) {
                avisar("CEP inválido. Digite exatamente 8 números.", "erro");
                return;
            }

            const id = consultaAtual;
            const requisicao = new AbortController();

            controlador = requisicao;

            if (botaoConsultar) botaoConsultar.disabled = true;

            avisar("Consultando CEP...");

            const limite = setTimeout(() => requisicao.abort(), 10000);

            try {
                const resposta = await fetch(
                    `https://viacep.com.br/ws/${cep}/json/`,
                    { signal: requisicao.signal }
                );

                if (!resposta.ok) {
                    throw new Error("Erro de comunicação com o ViaCEP.");
                }

                const dados = await resposta.json();

                // Não preencher com o resultado de uma consulta antiga
                if (
                    id !== consultaAtual ||
                    somenteNumeros(input.value) !== cep
                ) {
                    return;
                }

                // CEP inexistente
                if (dados.erro) {
                    avisar("CEP não encontrado.", "erro");
                    return;
                }

                if (!dados.localidade || !dados.uf) {
                    avisar("A API retornou um endereço incompleto.", "erro");
                    return;
                }

                // Preencher os campos automaticamente
                preencher(dados);
                cepValidado = cep;

                if (botaoSalvar && !botaoSalvar.hidden) {
                    botaoSalvar.disabled = false;
                }

                avisar("Endereço encontrado com sucesso!", "sucesso");

            } catch (erro) {
                if (id !== consultaAtual) return;

                avisar(
                    erro.name === "AbortError"
                        ? "Tempo de consulta esgotado. Tente novamente."
                        : "Não foi possível consultar o ViaCEP. Verifique sua internet.",
                    "erro"
                );

                console.error("ViaCEP:", erro);

            } finally {
                clearTimeout(limite);

                if (id === consultaAtual) {
                    controlador = null;

                    if (botaoConsultar) {
                        botaoConsultar.disabled = false;
                    }
                }
            }
        }

        // CONSULTAR AUTOMATICAMENTE AO DIGITAR OU COLAR
        input.addEventListener("input", () => {
            cancelarConsulta();

            input.value = formatarCep(input.value);

            limpar();
            avisar("");

            if (somenteNumeros(input.value).length === 8) {
                temporizador = setTimeout(consultar, 450);
            }
        });

        // FORMULÁRIO DE CONSULTA DO DASHBOARD
        if (formulario) {
            formulario.addEventListener("submit", (evento) => {
                evento.preventDefault();
                consultar();
            });
        }

        // BOTÃO CONSULTAR DOS CADASTROS
        if (
            botaoConsultar &&
            (!formulario || botaoConsultar.type !== "submit")
        ) {
            botaoConsultar.addEventListener("click", (evento) => {
                evento.preventDefault();
                consultar();
            });
        }

        // Consultar CEP recuperado após erro de cadastro
        if (somenteNumeros(input.value).length === 8) {
            consultar();
        }

        return {
            consultar,
            preencher,
            avisar,
            obterCepValidado: () => cepValidado
        };
    }

    // =====================================
    // CADASTRO DE CLIENTES E FUNCIONÁRIOS
    // =====================================

    iniciarConsulta({
        input: porId("cep"),

        campos: {
            logradouro: porId("logradouro"),
            bairro: porId("bairro"),
            cidade: porId("cidade"),
            uf: porId("uf")
        },

        mensagem:
            porId("mensagem_cep_cadastro") ||
            porId("mensagem_cep"),

        botaoConsultar:
            porId("consultar_cep") ||
            porId("btn_consultar_cep"),

        formulario: null,
        botaoSalvar: null
    });

    // =====================================
    // DASHBOARD DO STRAIN
    // =====================================

    const secao = porId("strain-cep");

    // Se não estiver no Dashboard, não executar esta parte
    if (!secao) return;

    const botaoSalvar = porId("strain-cep-salvar");
    const apiPhp = secao.dataset.api;
    const token = secao.dataset.csrf;

    const cepRecebido = new URLSearchParams(
        window.location.search
    ).get("consultar_cep");

    // Consultar CEP da tabela sem alterar endereço da conta
    if (botaoSalvar && cepRecebido !== null) {
        botaoSalvar.hidden = true;
    }

    const consultaDashboard = iniciarConsulta({
        input: porId("strain-cep-input"),

        campos: {
            logradouro: porId("strain-cep-logradouro"),
            bairro: porId("strain-cep-bairro"),
            cidade: porId("strain-cep-cidade"),
            uf: porId("strain-cep-uf")
        },

        mensagem: porId("strain-cep-mensagem"),
        botaoConsultar: porId("strain-cep-consultar"),
        formulario: porId("strain-cep-form"),
        botaoSalvar
    });

    if (!consultaDashboard) return;

    // Comunicação com o PHP, usada somente para salvar ou recuperar
    async function requisitarPhp(url, opcoes = {}) {
        const controlador = new AbortController();
        const limite = setTimeout(() => controlador.abort(), 10000);

        try {
            const resposta = await fetch(url, {
                ...opcoes,
                credentials: "same-origin",
                signal: controlador.signal
            });

            const dados = await resposta.json();

            if (!resposta.ok || !dados.ok) {
                throw new Error(
                    dados.mensagem || "Erro ao acessar o servidor."
                );
            }

            return dados;

        } catch (erro) {
            if (erro.name === "AbortError") {
                throw new Error("Servidor demorou a responder.");
            }

            if (
                erro instanceof SyntaxError ||
                erro instanceof TypeError
            ) {
                throw new Error("Falha de comunicação com o PHP.");
            }

            throw erro;

        } finally {
            clearTimeout(limite);
        }
    }

    // SALVAR ENDEREÇO NO DASHBOARD (OPCIONAL)
    if (botaoSalvar) {
        botaoSalvar.addEventListener("click", async () => {
            const cep = consultaDashboard.obterCepValidado();

            if (!cep || !apiPhp || !token || botaoSalvar.hidden) {
                consultaDashboard.avisar(
                    "Não foi possível salvar: consulta ou configuração inválida.",
                    "erro"
                );
                return;
            }

            botaoSalvar.disabled = true;
            consultaDashboard.avisar("Salvando endereço...");

            try {
                const formulario = new URLSearchParams({ cep });

                const dados = await requisitarPhp(apiPhp, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                        "X-CSRF-Token": token
                    },
                    body: formulario
                });

                if (
                    porId("strain-cep-input").value.replace(/\D/g, "") !== cep
                ) {
                    return;
                }

                if (dados.endereco) {
                    consultaDashboard.preencher(dados.endereco);
                }

                consultaDashboard.avisar(
                    "Endereço salvo com sucesso!",
                    "sucesso"
                );

            } catch (erro) {
                consultaDashboard.avisar(erro.message, "erro");
                console.error("Salvamento do CEP:", erro);

            } finally {
                botaoSalvar.disabled =
                    !consultaDashboard.obterCepValidado() ||
                    botaoSalvar.hidden;
            }
        });
    }

    // CONSULTAR CEP RECEBIDO DA TABELA DE USUÁRIOS
    if (cepRecebido !== null) {
        const campo = porId("strain-cep-input");

        campo.value = formatarCep(cepRecebido);

        if (/^\d{8}$/.test(somenteNumeros(cepRecebido))) {
            consultaDashboard.consultar();
        } else {
            consultaDashboard.avisar(
                "CEP recebido da tabela é inválido.",
                "erro"
            );
        }

    } else if (apiPhp) {

        // Recuperação opcional do endereço salvo
        requisitarPhp(`${apiPhp}?acao=salvo`)
            .then((dados) => {
                if (
                    dados.endereco &&
                    !porId("strain-cep-input").value
                ) {
                    consultaDashboard.preencher(dados.endereco);

                    consultaDashboard.avisar(
                        "Endereço cadastrado na sua conta.",
                        "sucesso"
                    );
                }
            })
            .catch((erro) => {
                console.warn(
                    "Não foi possível recuperar endereço salvo:",
                    erro.message
                );
            });
    }
});

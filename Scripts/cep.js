
// API ViaCEP - Projeto Strain

const formCep = document.getElementById("form_cep");
const campoCep = document.getElementById("cep");
const mensagemCep = document.getElementById("mensagem_cep");
const botaoCep = document.getElementById("btn_cep");

function limparEndereco() {
    document.getElementById("logradouro").value = "";
    document.getElementById("bairro").value = "";
    document.getElementById("cidade").value = "";
    document.getElementById("uf").value = "";
}

formCep.addEventListener("submit", async function(evento) {

    evento.preventDefault();

    const cepDigitado = campoCep.value.trim();
    const cep = cepDigitado.replace("-", "");

    limparEndereco();
    mensagemCep.textContent = "";

    // Validar o formato do CEP
    if (!/^\d{5}-?\d{3}$/.test(cepDigitado)) {
        mensagemCep.textContent = "CEP inválido! Digite 8 números.";
        mensagemCep.style.color = "#b00020";
        return;
    }

    botaoCep.disabled = true;
    botaoCep.textContent = "Consultando...";
    mensagemCep.textContent = "Buscando endereço...";
    mensagemCep.style.color = "#555";

    const controlador = new AbortController();
    const limite = setTimeout(() => controlador.abort(), 10000);

    try {

        // Consultar a API
        const resposta = await fetch(
            `https://viacep.com.br/ws/${cep}/json/`,
            { signal: controlador.signal }
        );

        if (!resposta.ok) {
            throw new Error("Falha na consulta");
        }

        const dados = await resposta.json();

        // CEP não encontrado
        if (dados.erro) {
            mensagemCep.textContent = "CEP não encontrado!";
            mensagemCep.style.color = "#b00020";
            return;
        }

        // Preencher automaticamente
        campoCep.value = dados.cep || cepDigitado;

        document.getElementById("logradouro").value =
            dados.logradouro || "";

        document.getElementById("bairro").value =
            dados.bairro || "";

        document.getElementById("cidade").value =
            dados.localidade || "";

        document.getElementById("uf").value =
            dados.uf || "";

        mensagemCep.textContent = "Endereço encontrado com sucesso!";
        mensagemCep.style.color = "#16723a";

    } catch (erro) {

        mensagemCep.textContent =
            "Erro ao consultar o CEP. Tente novamente.";

        mensagemCep.style.color = "#b00020";
        console.error(erro);

    } finally {
        clearTimeout(limite);
        botaoCep.disabled = false;
        botaoCep.textContent = "Consultar";
    }
});

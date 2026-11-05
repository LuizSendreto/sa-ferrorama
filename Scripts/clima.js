
async function buscarClima() {

    // API Open-Meteo - Joinville SC
    const url = "https://api.open-meteo.com/v1/forecast" +
        "?latitude=-26.3044" +
        "&longitude=-48.8487" +
        "&current=temperature_2m,weather_code" +
        "&timezone=America%2FSao_Paulo";

    try {

        const resposta = await fetch(url);

        if (!resposta.ok) {
            throw new Error("Erro ao consultar a API");
        }

        const dados = await resposta.json();

        // Dados retornados pela API
        const temperatura = dados.current.temperature_2m;
        const codigo = dados.current.weather_code;
        const horario = dados.current.time;

        let condicao = "";

        // Identificar a condição climática
        if (codigo === 0) {
            condicao = "☀️ Céu limpo";
        }
        else if (codigo >= 1 && codigo <= 3) {
            condicao = "⛅ Nublado";
        }
        else if (codigo === 45 || codigo === 48) {
            condicao = "🌫️ Neblina";
        }
        else if (codigo >= 51 && codigo <= 67) {
            condicao = "🌧️ Chuva ou garoa";
        }
        else if (codigo >= 71 && codigo <= 77) {
            condicao = "❄️ Neve";
        }
        else if (codigo >= 80 && codigo <= 82) {
            condicao = "🌧️ Pancadas de chuva";
        }
        else if (codigo >= 95 && codigo <= 99) {
            condicao = "⛈️ Tempestade";
        }
        else {
            condicao = "🌤️ Outras condições";
        }

        // Formatar data e horário dos dados
        const [data, hora] = horario.split("T");
        const [ano, mes, dia] = data.split("-");

        const dataFormatada =
            dia + "/" + mes + "/" + ano;

        // Exibir na página
        document.getElementById("temperatura_clima")
            .textContent = temperatura + "°C";

        document.getElementById("condicao_clima")
            .textContent = condicao;

        document.getElementById("data_clima")
            .textContent = "Dados de " +
            dataFormatada + " às " + hora.substring(0, 5);

    } catch (erro) {

        document.getElementById("temperatura_clima")
            .textContent = "Indisponível";

        document.getElementById("condicao_clima")
            .textContent = "Erro ao consultar o clima.";

        document.getElementById("data_clima")
            .textContent = "Tente novamente mais tarde.";

        console.error(erro);
    }
}

// Buscar clima ao abrir a página
buscarClima();

// Atualizar a consulta a cada 10 minutos
setInterval(buscarClima, 600000);

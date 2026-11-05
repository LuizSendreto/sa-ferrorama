
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Strain - Monitoramento Ferroviário</title>

    <link rel="stylesheet" href="./assets/style/style.css">
</head>

<body class="body_index">

    <!-- NAVBAR -->
    <nav class="navbar">

        <div class="logo-navbar" id="logoNavbar">
            <img src="assets/img/image.png" alt="Logo Strain">
        </div>

        <button class="btn"
            onclick="window.location.href='public/usuarios/login.php'">
            Entrar
        </button>

    </nav>

    <!-- BANNER PRINCIPAL -->
    <section class="banner">

        <img class="Strain"
            src="assets/img/Logo_index.png"
            alt="Strain">

        <p id="frase1">
            Olá, seja bem-vindo ao Strain, o sistema de
            transporte público de Joinville!
        </p>

    </section>

    <hr class="linha">

    <main class="blue.index" id="landing-main">

        <!-- APRESENTAÇÃO DO PROJETO -->
        <section class="Introducao1">

            <h2 class="titulo_landing">
                O que é o Strain?
            </h2>

            <p class="texto_landing">
                O STRAIN nasceu do desafio de levar a inteligência
                de dados para os trilhos. Criamos um sistema que
                vai além do básico, usando sensores IoT para
                monitorar tudo o que importa em tempo real,
                da velocidade ao consumo de energia.

                Por trás dessa solução, estamos nós, Luiz Felipe,
                Rafael Tironi, Nicole Jordan e Juana Franciscao,
                trabalhando para transformar números brutos
                em dashboards práticos e relatórios de
                manutenção preditiva.

                Nosso foco é unir tecnologia e mobilidade urbana
                para garantir que a gestão ferroviária seja
                mais segura, eficiente e conectada, sempre
                com o pé no chão e o código em dia.
            </p>

        </section>

        <!-- PROBLEMA QUE O SISTEMA RESOLVE -->
        <section class="secao_landing">

            <h2 class="titulo_secao_landing">
                Por que o Strain foi criado?
            </h2>

            <div class="caixa_landing">

                <h3>
                    Tecnologia para um transporte mais seguro
                </h3>

                <p>
                    O monitoramento ferroviário envolve diferentes
                    informações sobre os trens, sensores e condições
                    de funcionamento.
                </p>

                <p>
                    Quando essas informações não estão organizadas
                    em um único sistema, identificar falhas,
                    acompanhar o desempenho e planejar a manutenção
                    pode se tornar mais difícil.
                </p>

                <p>
                    O Strain foi desenvolvido para reunir essas
                    informações em uma plataforma, facilitando
                    o acompanhamento das operações ferroviárias.
                </p>

            </div>

        </section>

        <!-- FUNCIONALIDADES -->
        <section class="secao_landing">

            <h2 class="titulo_secao_landing">
                Conheça nossas funcionalidades
            </h2>

            <div class="grid_funcionalidades">

                <div class="card_funcionalidade">

                    <div class="icone_funcionalidade">🚆</div>

                    <h3>Monitoramento dos trens</h3>

                    <p>
                        Acompanhamento das informações dos trens,
                        incluindo velocidade, estado e dados
                        operacionais.
                    </p>

                </div>

                <div class="card_funcionalidade">

                    <div class="icone_funcionalidade">🌡️</div>

                    <h3>Sensores IoT</h3>

                    <p>
                        Visualização de informações dos sensores
                        para acompanhar as condições de
                        funcionamento dos trens.
                    </p>

                </div>

                <div class="card_funcionalidade">

                    <div class="icone_funcionalidade">📊</div>

                    <h3>Relatórios</h3>

                    <p>
                        Apresentação de dados e gráficos que
                        facilitam a análise das informações
                        ferroviárias.
                    </p>

                </div>

                <div class="card_funcionalidade">

                    <div class="icone_funcionalidade">⚠️</div>

                    <h3>Alertas</h3>

                    <p>
                        Visualização de avisos sobre possíveis
                        falhas, manutenção e condições de
                        operação dos trens.
                    </p>

                </div>

            </div>

        </section>

        <!-- BENEFÍCIOS -->
        <section class="secao_landing">

            <h2 class="titulo_secao_landing">
                Benefícios do Strain
            </h2>

            <div class="grid_beneficios">

                <div class="card_beneficio">
                    <h3>Segurança</h3>
                    <p>
                        Facilita a identificação de possíveis
                        problemas nas operações ferroviárias.
                    </p>
                </div>

                <div class="card_beneficio">
                    <h3>Eficiência</h3>
                    <p>
                        Organiza informações que auxiliam
                        o acompanhamento e a gestão dos trens.
                    </p>
                </div>

                <div class="card_beneficio">
                    <h3>Organização</h3>
                    <p>
                        Centraliza informações operacionais
                        em uma única plataforma.
                    </p>
                </div>

            </div>

        </section>

        <hr class="linha1">

        <!-- LINHAS DISPONÍVEIS E MAPA -->
        <section class="container_index landing_linhas_layout">

            <div class="flex2">

                <h3 class="titulo_linhas">
                    Linhas disponíveis em Joinville SC
                </h3>

                <div class="landing_linhas_conteudo">

                    <div class="landing_icone_linhas">

                        <img class="foto_linhas"
                            src="assets/img/Captura de tela 2026-05-12 072739.png"
                            alt="Identificação das linhas">
                    </div>

                    <div class="linhas">

                        <button class="btn btn-outline2"
                            onclick="abrirPopup('Linha Azul', ['06:00 - Trem 1010', '07:00 - Trem 1534', '11:30 - Trem 6521'])">
                            Linha Azul (Sul)
                        </button>

                        <button class="btn btn-outline2"
                            onclick="abrirPopup('Linha Verde', ['08:00 - Trem 2201', '12:00 - Trem 4456'])">
                            Linha Verde (Centro)
                        </button>

                        <button class="btn btn-outline2"
                            onclick="abrirPopup('Linha Amarela', ['09:30 - Trem 7890'])">
                            Linha Amarela (Itaum)
                        </button>

                        <button class="btn btn-outline2"
                            onclick="abrirPopup('Linha Vermelha', ['10:00 - Trem 9999'])">
                            Linha Vermelha (Villa Nova)
                        </button>

                    </div>

                </div>

            </div>

            <!-- MAPA -->
            <div class="landing_mapa">

                <img class="Mapa"
                    src="assets/img/Mockup- SA Juana, Luiz, Nicole, Rafael.png"
                    alt="Mapa das linhas do Strain em Joinville">

            </div>

        </section>


    </main>

    <!-- POPUP DOS HORÁRIOS -->
    <div id="popup" class="popup">

        <div class="popup-conteudo">

            <button type="button" class="fechar"
                onclick="fecharPopup()"
                aria-label="Fechar horários">
                ✕
            </button>

            <h2 id="tituloLinha"></h2>

            <div id="horarios"></div>

        </div>

    </div>

    <!-- RODAPÉ -->
    <footer class="footer">

        <div class="footer1">

            <h3>STRAIN</h3>

            <p>
                © 2026 Strain. Todos os direitos reservados.
            </p>

            <p>
                Desenvolvido por Nicole Jordan,
                Juana Franciscao, Luiz Felipe
                e Rafael Tironi.
            </p>

        </div>

        <div class="footer3">

            <h3>Sobre o projeto</h3>

            <p>
                O Strain é um projeto de monitoramento
                ferroviário desenvolvido para facilitar
                a visualização das informações dos trens
                e sensores.
            </p>

            <p>
                A plataforma reúne ferramentas de
                monitoramento, relatórios e alertas,
                contribuindo para uma gestão ferroviária
                mais organizada, segura e eficiente.
            </p>

        </div>

    </footer>

    <!-- LOGO DA NAVBAR -->
    <script>
        window.addEventListener("scroll", function () {

            const logoNavbar = document.getElementById("logoNavbar");

            if (window.scrollY > 300) {
                logoNavbar.classList.add("show");
            } else {
                logoNavbar.classList.remove("show");
            }

        });
    </script>

    <!-- FUNCIONALIDADE DOS BOTÕES -->
    <script src="Scripts/botoes.js"></script>

</body>
</html>

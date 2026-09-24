<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strain</title>

    <link rel="stylesheet" href="./assets/style/style.css">

</head>

<body class="body_index">
    <nav class="navbar">

        <div class="logo-navbar" id="logoNavbar">
            <img src="assets/img/image.png" alt="Logo Strain">
        </div>

        <button class="btn" onclick="window.location.href='public/usuarios/login.php'">Entrar</button>

    </nav>

    <section class="banner">
        
        <img class="Strain" src="assets/img/Logo_index.png" alt="Strain">
   
        <p id="frase1"> Olá, seja bem-vindo ao Strain, o sistema de <br> transporte público de Joinville! </p>

    </section>
    <main class="blue.index">
        <div class="Introducao1">

            <h2><b class="Introducao">Bem-vindo ao Strain, o sistema de transporte público de Joinville!</b></h2>
            <h4 class="Introducao">O STRAIN nasceu do desafio de levar a inteligência de dados para os trilhos. Criamos
                um sistema <br> que vai além do básico, usando sensores IoT
                para monitorar tudo o que importa em tempo <br> real — da velocidade ao consumo de energia. Por trás
                dessa solução, estamos nós, Luiz Felipe, <br>
                Rafael Tironi, Nicole Jordan e Juana Franciscao, trabalhando para transformar números brutos em <br>
                dashboards práticos e relatórios de manutenção
                preditiva. Nosso foco é unir tecnologia e mobilidade <br> urbana para garantir que a gestão ferroviária
                seja mais segura, eficiente e conectada, sempre <br>
                com o pé no chão e o código em dia.</h4>
        </div>



        <div class="container_index">

            <div class="flex2">

                <div>
                    <H3><b class="titulo_linhas">Linhas disponíveis em Joinville SC </b></H3>
                </div>

                <br>
                <div class="container_index">
                    <div class="linhas">

                        <div class="linhas">
                            <button id="botaoindex" type="submit" class="btn btn-outline2"><img class="foto_linhas"
                                    src="assets/img/Captura de tela 2026-05-12 072739.png"
                                    alt="Foto dos trens"></button>
                        </div>
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

                        <button class="btn btn-outline2" onclick="abrirPopup('Linha Amarela', ['09:30 - Trem 7890'])">
                            Linha Amarela (Itaum)
                        </button>

                        <button class="btn btn-outline2" onclick="abrirPopup('Linha Vermelha', ['10:00 - Trem 9999'])">
                            Linha Vermelha (Villa Nova)
                        </button>

                    </div>

                    <div id="popup" class="popup">

                        <div class="popup-conteudo">

                            <span class="fechar" onclick="fecharPopup()">✕</span>

                            <h2 id="tituloLinha"></h2>

                            <div id="horarios"></div>

                        </div>

                    </div>

                </div>
            </div>




            <div>
                <img class="Mapa" src="assets/img/Mockup- SA Juana, Luiz, Nicole, Rafael.png" alt="Mapa dos trens">

            </div>
    </main>



        <footer class="footer">
            <div class="footer1">
                <p>© 2026 Strain. Todos os direitos reservados.</p> 
                <br>
                <p >Desenvolvido por Nicole Jordan, Juana Franciscao, Luiz Felipe e Rafael Tironi.</p>
            </div>



            <div class="footer2">

                <a href="#"> <b>Trens e estações de Joinville SC </b> </a>
                <a href="#"> <b>Cadastro de usuários </b> </a>
                <a href="#"> <b>Sou administrador </b> </a>

                </div>

            

    <script>

        window.addEventListener("scroll", function () {

            const logoNavbar = document.getElementById("logoNavbar");

            if (window.scrollY > 200) {
                logoNavbar.classList.add("show");
            } else {
                logoNavbar.classList.remove("show");
            }

        });

    </script>

</body>

</html>
</footer>
<script src="Scripts/botoes.js"></script>
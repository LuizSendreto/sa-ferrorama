<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuarioLogadoIndex = !empty($_SESSION['usuario_id']);
$nomeUsuarioIndex = $_SESSION['usuario_nome'] ?? 'usuário';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Conheça o Strain: um projeto que conecta tecnologia, dados e mobilidade para facilitar o monitoramento ferroviário.">
    <meta name="theme-color" content="#850000">

    <title>Strain | Tecnologia que move os trilhos</title>

    <link rel="stylesheet" href="assets/style/landing.css">
    <script src="Scripts/landing.js" defer></script>
</head>

<body>

    <a class="skip-link" href="#conteudo">Pular para o conteúdo</a>

    <!-- CABEÇALHO -->
    <header class="header">

        <nav class="wrap nav" aria-label="Navegação principal">

            <a class="brand" href="#inicio" aria-label="Strain — início">
                <img src="assets/img/image.png" alt="Strain" width="136" height="72">
            </a>

            <div class="nav-links">
                <a href="#sobre">O projeto</a>
                <a href="#recursos">Funcionalidades</a>
                <a href="#linhas">Linhas e horários</a>
            </div>

            <?php if ($usuarioLogadoIndex): ?>
                <div style="display: flex; align-items: center; justify-content: flex-end; gap: 12px; flex-wrap: wrap; margin-left: auto;">
                    <span style="color: #fff; font-weight: 600; font-size: 14px;">
                        Olá, <?= htmlspecialchars((string) $nomeUsuarioIndex, ENT_QUOTES, 'UTF-8') ?>!
                    </span>
                    <a class="button button-light nav-login" href="public/usuarios/logout.php">
                        Sair
                    </a>
                </div>
            <?php else: ?>
                <a class="button button-light nav-login" href="public/usuarios/login.php">
                    Entrar <span aria-hidden="true">↗</span>
                </a>
            <?php endif; ?>

        </nav>

    </header>


    <!-- CONTEÚDO PRINCIPAL -->
    <main id="conteudo">

        <!-- SEÇÃO INICIAL -->
        <section class="hero" id="inicio" aria-labelledby="hero-title">

            <div class="wrap hero-content">

                <p class="eyebrow">
                    <span class="small-track" aria-hidden="true"></span>
                    TECNOLOGIA & MOBILIDADE
                </p>

                <h1 id="hero-title">
                    Bem-vindo<br>
                    ao <em>Strain <svg xmlns="http://www.w3.org/2000/svg" width="69" height="69" fill="currentColor" class="bi bi-train-freight-front-fill" viewBox="0 0 16 16">
                    <path d="M5.736 0a1.5 1.5 0 0 0-.67.158L1.828 1.776A1.5 1.5 0 0 0 1 3.118v5.51l2-.6V5a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v3.028l2 .6v-5.51a1.5 1.5 0 0 0-.83-1.342L10.936.158A1.5 1.5 0 0 0 10.264 0zM15 9.672l-5.503-1.65A.5.5 0 0 0 9.353 8H8.5v8h4a2.5 2.5 0 0 0 2.5-2.5zM7.5 16V8h-.853a.5.5 0 0 0-.144.021L1 9.672V13.5A2.5 2.5 0 0 0 3.5 16zm-1-14h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1 0-1M12 5v2.728l-2.216-.665A1.5 1.5 0 0 0 9.354 7H8.5V5zM7.5 5v2h-.853a1.5 1.5 0 0 0-.431.063L4 7.728V5zm-4 5a.5.5 0 1 1 0 1 .5.5 0 0 1 0-1m9 0a.5.5 0 1 1 0 1 .5.5 0 0 1 0-1M5 13a1 1 0 1 1-2 0 1 1 0 0 1 2 0m7 1a1 1 0 1 1 0-2 1 1 0 0 1 0 2"/>
                    </svg></em>
                </h1>

                <p class="hero-description">
                    Conectamos dados, trens e pessoas para uma gestão ferroviária
                    mais segura, organizada e inteligente.
                </p>

                <div class="actions">

                    <a class="button button-primary" href="public/usuarios/login.php">
                        Acessar o Strain <span aria-hidden="true">↗</span>
                    </a>

                    <a class="button button-outline" href="#recursos">
                        Conhecer o projeto <span aria-hidden="true">↓</span>
                    </a>

                </div>

                <p class="hero-caption">
                    Monitoramento ferroviário · Projeto desenvolvido em Joinville, SC
                </p>

            </div>

            <div class="hero-bottom">

                <div class="wrap">

                    <span>INFORMAÇÃO QUE CONECTA.</span>

                    <a href="#sobre">
                        Explore o Strain <span aria-hidden="true">↓</span>
                    </a>

                </div>

            </div>

        </section>


        <!-- SOBRE O PROJETO -->
        <section class="section wrap about" id="sobre" aria-labelledby="about-title">

            <div>

                <p class="eyebrow">01 / O PROJETO</p>

                <h2 id="about-title">
                    Mais clareza para<br>
                    cada <span>trajeto.</span>
                </h2>

            </div>

            <div class="about-copy">

                <p class="lead">
                    A operação ferroviária gera muitos dados.
                    O Strain ajuda a dar sentido a eles.
                </p>

                <p>
                    Nosso projeto reúne informações de trens, rotas e sensores
                    em uma única plataforma. Uma maneira mais simples de
                    acompanhar a operação, visualizar alertas e consultar relatórios.
                </p>

                <a class="text-link" href="#recursos">
                    Veja o que você encontra na plataforma
                    <span aria-hidden="true">↗</span>
                </a>

            </div>

        </section>


        <!-- FUNCIONALIDADES -->
        <section class="features-section" id="recursos" aria-labelledby="features-title">

            <div class="wrap section">

                <div class="section-heading">

                    <div>

                        <p class="eyebrow">02 / FUNCIONALIDADES</p>

                        <h2 id="features-title">
                            Tudo conectado.<br>
                            Tudo mais <span>simples.</span>
                        </h2>

                    </div>

                    <p>
                        Da visão geral aos detalhes da operação,
                        as informações que importam no mesmo lugar.
                    </p>

                </div>


                <!-- CARTÕES DE FUNCIONALIDADES -->
                <div class="features">

                    <!-- TREN -->
                    <article class="feature">

                        <span class="feature-number">
                            01 <span aria-hidden="true">↗</span>
                        </span>

                        <svg viewBox="0 0 32 32" aria-hidden="true">
                            <rect x="7" y="3" width="18" height="23" rx="5"/>
                            <path d="M7 16h18M16 3v13M10 26l-3 4m15-4 3 4M11 21h1m8 0h1"/>
                        </svg>

                        <h3>Trens em foco</h3>

                        <p>
                            Consulte os trens e acompanhe informações de velocidade,
                            estado e funcionamento.
                        </p>

                        <span class="feature-tag">MONITORAMENTO</span>

                    </article>


                    <!-- SENSORES -->
                    <article class="feature">

                        <span class="feature-number">
                            02 <span aria-hidden="true">↗</span>
                        </span>

                        <svg viewBox="0 0 32 32" aria-hidden="true">
                            <circle cx="16" cy="16" r="3"/>
                            <path d="M10 9a10 10 0 0 0 0 14m12-14a10 10 0 0 1 0 14M6 5a16 16 0 0 0 0 22M26 5a16 16 0 0 1 0 22"/>
                        </svg>

                        <h3>Sensores conectados</h3>

                        <p>
                            Organize os sensores IoT e visualize os dados
                            que ajudam a entender as condições dos trens.
                        </p>

                        <span class="feature-tag">TECNOLOGIA IoT</span>

                    </article>


                    <!-- RELATÓRIOS -->
                    <article class="feature">

                        <span class="feature-number">
                            03 <span aria-hidden="true">↗</span>
                        </span>

                        <svg viewBox="0 0 32 32" aria-hidden="true">
                            <path d="M5 3v25h24M11 22v-8m7 8V6m7 16V11"/>
                        </svg>

                        <h3>Dados que orientam</h3>

                        <p>
                            Explore gráficos e relatórios para apoiar
                            a análise do desempenho ferroviário.
                        </p>

                        <span class="feature-tag">RELATÓRIOS</span>

                    </article>


                    <!-- ALERTAS -->
                    <article class="feature">

                        <span class="feature-number">
                            04 <span aria-hidden="true">↗</span>
                        </span>

                        <svg viewBox="0 0 32 32" aria-hidden="true">
                            <path d="m14 5-11 20a2 2 0 0 0 2 3h22a2 2 0 0 0 2-3L18 5a2 2 0 0 0-4 0ZM16 12v7m0 4v1"/>
                        </svg>

                        <h3>Atenção ao essencial</h3>

                        <p>
                            Visualize avisos de falhas, manutenção e condições
                            que precisam de acompanhamento.
                        </p>

                        <span class="feature-tag">ALERTAS</span>

                    </article>

                </div>


                <!-- BENEFÍCIOS -->
                <div class="benefits">

                    <p>
                        <strong>Mais segurança</strong>
                        <span>Informações para identificar possíveis falhas.</span>
                    </p>

                    <p>
                        <strong>Mais eficiência</strong>
                        <span>Uma visão organizada para apoiar decisões.</span>
                    </p>

                    <p>
                        <strong>Mais controle</strong>
                        <span>Dados operacionais em uma só plataforma.</span>
                    </p>

                </div>

            </div>

        </section>


        <!-- LINHAS E HORÁRIOS -->
        <section class="section wrap routes" id="linhas" aria-labelledby="routes-title">

            <div class="routes-copy">

                <p class="eyebrow">03 / EXPLORE AS LINHAS</p>

                <h2 id="routes-title">
                    Cada linha,<br>
                    uma <span>conexão.</span>
                </h2>

                <p>
                    Conheça os trajetos representados no projeto
                    e selecione uma linha para consultar seus horários.
                </p>


                <!-- LISTA DE LINHAS -->
                <div class="route-list">

                    <!-- LINHA AZUL -->
                    <button class="route" data-line="azul">

                        <span class="route-dot blue"></span>

                        <span>
                            <strong>Linha Azul</strong>
                            <small>Sul</small>
                        </span>

                        <span aria-hidden="true">↗</span>

                    </button>


                    <!-- LINHA VERDE -->
                    <button class="route" data-line="verde">

                        <span class="route-dot green"></span>

                        <span>
                            <strong>Linha Verde</strong>
                            <small>Centro</small>
                        </span>

                        <span aria-hidden="true">↗</span>

                    </button>


                    <!-- LINHA AMARELA -->
                    <button class="route" data-line="amarela">

                        <span class="route-dot yellow"></span>

                        <span>
                            <strong>Linha Amarela</strong>
                            <small>Itaum</small>
                        </span>

                        <span aria-hidden="true">↗</span>

                    </button>


                    <!-- LINHA VERMELHA -->
                    <button class="route" data-line="vermelha">

                        <span class="route-dot red"></span>

                        <span>
                            <strong>Linha Vermelha</strong>
                            <small>Villa Nova</small>
                        </span>

                        <span aria-hidden="true">↗</span>

                    </button>

                </div>

                <p class="demo-note">
                    Mapa e horários ilustrativos do projeto acadêmico.
                </p>

            </div>


            <!-- MAPA DAS LINHAS -->
            <figure class="map">

                <div class="map-heading">
                    <span>MAPA DAS LINHAS</span>
                    <span>JOINVILLE / SC</span>
                </div>

                <img
                    src="assets/img/Mockup- SA Juana, Luiz, Nicole, Rafael.png"
                    alt="Mapa ilustrativo dos trajetos das quatro linhas do projeto Strain"
                    loading="lazy"
                    width="1000"
                    height="700"
                >

                <figcaption>
                    <span class="map-pin" aria-hidden="true">◎</span>
                    Mobilidade conectada, do início ao destino.
                </figcaption>

            </figure>

        </section>


        <!-- ACESSO À PLATAFORMA -->
        <section class="wrap cta" aria-labelledby="cta-title">

            <div>

                <p class="eyebrow">SEU PRÓXIMO DESTINO</p>

                <h2 id="cta-title">
                    A operação começa<br>
                    com uma boa visão.
                </h2>

                <p>
                    Acesse sua conta e explore as ferramentas do Strain.
                </p>

            </div>

            <a class="button button-light" href="public/usuarios/login.php">
                Entrar na plataforma <span aria-hidden="true">↗</span>
            </a>

        </section>

    </main>


    <!-- RODAPÉ -->
    <footer class="wrap footer">

        <div class="footer-top">

            <div>

                <a class="footer-brand" href="#inicio">
                    STRAIN<span>®</span>
                </a>

                <p>Tecnologia que move os trilhos.</p>

            </div>

            <div>

                <h3>Feito em equipe. Pensado para conectar.</h3>

                <p>
                    Luiz Felipe · Rafael Tironi <br>
                    Nicole Jordan · Juana Franciscão
                </p>

            </div>

            <a class="text-link" href="#inicio">
                Voltar ao topo ↑
            </a>

        </div>


        <div class="footer-bottom">

            <span>
                © 2026 Strain. Todos os direitos reservados.
            </span>

            <span>
                Projeto acadêmico · Joinville, SC
            </span>

        </div>

    </footer>


    <!-- JANELA DE HORÁRIOS -->
    <dialog id="schedule" aria-labelledby="schedule-title">

        <div class="dialog-top">

            <p class="eyebrow">HORÁRIOS DA LINHA</p>

            <button
                class="close-dialog"
                type="button"
                aria-label="Fechar horários"
                autofocus
            >
                ×
            </button>

        </div>

        <h2 id="schedule-title"></h2>

        <p id="schedule-region"></p>


        <!-- TABELA DE HORÁRIOS -->
        <table>

            <thead>

                <tr>
                    <th scope="col">Saída</th>
                    <th scope="col">Trem</th>
                </tr>

            </thead>

            <tbody id="schedule-times"></tbody>

        </table>

        <p class="demo-note">
            Horários ilustrativos do projeto acadêmico.
        </p>

    </dialog>

</body>

</html>

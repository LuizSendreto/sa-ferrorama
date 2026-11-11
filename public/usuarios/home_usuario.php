<?php
require_once '../../infra/verificar_acesso.php';
verificarLogin();

// Funcionários utilizam o Dashboard administrativo.
if (($_SESSION['tipo_usuario'] ?? '') === 'funcionario') {
    header('Location: ../dashboard.php');
    exit;
}

// Esta página é exclusiva para clientes autenticados.
if (($_SESSION['tipo_usuario'] ?? '') !== 'cliente') {
    header('Location: ../../index.php');
    exit;
}

$nomeCliente = trim((string) ($_SESSION['usuario_nome'] ?? 'Cliente'));
$primeiroNome = explode(' ', $nomeCliente)[0] ?: 'Cliente';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área do Cliente ● Strain</title>

    <!-- Estilo geral + estilo exclusivo da área do cliente -->
    <link rel="stylesheet" href="../../assets/style/style.css">
    <link rel="stylesheet" href="../../assets/style/home_usuario.css?v=1">
</head>
<body class="body_dashboard home_cliente">

    <!-- NAVBAR PADRÃO STRAIN -->
    <header>
        <nav class="navbar" aria-label="Navegação principal">
            <a class="marca-strain" href="../../index.php" aria-label="Strain - página inicial">
                <i data-lucide="train-front" aria-hidden="true"></i>
                <span>STRAIN</span>
            </a>

            <div class="acoes_nav_home">
                <span class="saudacao_nav_home">Olá, <?= htmlspecialchars($primeiroNome, ENT_QUOTES, 'UTF-8') ?>!</span>
                <a href="logout.php" class="btn botao_sair_home">
                    <i data-lucide="log-out" aria-hidden="true"></i>
                    Sair
                </a>
            </div>
        </nav>
    </header>

    <main class="area_home_cliente">

        <!-- BOAS-VINDAS -->
        <section class="hero_home_cliente" aria-labelledby="titulo-home">
            <div class="hero_texto_home">
                <span class="etiqueta_home">ÁREA DO PASSAGEIRO</span>
                <h1 id="titulo-home">Bem-vindo ao Strain, <?= htmlspecialchars($primeiroNome, ENT_QUOTES, 'UTF-8') ?>!</h1>
                <p>
                    Encontre as linhas disponíveis em Joinville e consulte os painéis
                    informativos da nossa rede ferroviária.
                </p>
                <a href="#linhas-home" class="botao_hero_home">
                    Consultar linhas
                    <i data-lucide="arrow-down-right" aria-hidden="true"></i>
                </a>
            </div>
            <div class="hero_marca_home">
                <img src="../../assets/img/Logo_index.png" alt="Logo do Strain">
            </div>
        </section>

        <!-- LINHAS -->
        <section id="linhas-home" class="painel_home_cliente" aria-labelledby="titulo-linhas">
            <div class="cabecalho_painel_home">
                <div>
                    <h2 id="titulo-linhas">Linhas disponíveis</h2>
                    <p>Selecione uma linha para consultar os horários apresentados.</p>
                </div>
                <i data-lucide="route" aria-hidden="true"></i>
            </div>

            <div class="grade_linhas_home">
                <button type="button" class="botao_linha_home linha_azul_home"
                    onclick="abrirPopup('Linha Azul (Sul)', ['06:00 - Trem 1010', '07:00 - Trem 1534', '11:30 - Trem 6521'])">
                    <span class="identificacao_linha_home"><i data-lucide="train-front" aria-hidden="true"></i> Linha Azul</span>
                    <span class="destino_linha_home">Sul</span>
                    <span class="consultar_linha_home">Ver horários <i data-lucide="arrow-right" aria-hidden="true"></i></span>
                </button>

                <button type="button" class="botao_linha_home linha_verde_home"
                    onclick="abrirPopup('Linha Verde (Centro)', ['08:00 - Trem 2201', '12:00 - Trem 4456'])">
                    <span class="identificacao_linha_home"><i data-lucide="train-front" aria-hidden="true"></i> Linha Verde</span>
                    <span class="destino_linha_home">Centro</span>
                    <span class="consultar_linha_home">Ver horários <i data-lucide="arrow-right" aria-hidden="true"></i></span>
                </button>

                <button type="button" class="botao_linha_home linha_amarela_home"
                    onclick="abrirPopup('Linha Amarela (Itaum)', ['09:30 - Trem 7890'])">
                    <span class="identificacao_linha_home"><i data-lucide="train-front" aria-hidden="true"></i> Linha Amarela</span>
                    <span class="destino_linha_home">Itaum</span>
                    <span class="consultar_linha_home">Ver horários <i data-lucide="arrow-right" aria-hidden="true"></i></span>
                </button>

                <button type="button" class="botao_linha_home linha_vermelha_home"
                    onclick="abrirPopup('Linha Vermelha (Villa Nova)', ['10:00 - Trem 9999'])">
                    <span class="identificacao_linha_home"><i data-lucide="train-front" aria-hidden="true"></i> Linha Vermelha</span>
                    <span class="destino_linha_home">Villa Nova</span>
                    <span class="consultar_linha_home">Ver horários <i data-lucide="arrow-right" aria-hidden="true"></i></span>
                </button>
            </div>

            <p class="observacao_linhas_home">
                <i data-lucide="info" aria-hidden="true"></i>
                Os horários acima são exemplos do protótipo e ainda não estão conectados a uma tabela de horários no banco de dados.
            </p>
        </section>

        <!-- INFORMAÇÕES E IMAGENS DO PROJETO -->
        <section class="painel_home_cliente" aria-labelledby="titulo-informacoes">
            <div class="cabecalho_painel_home">
                <div>
                    <h2 id="titulo-informacoes">Informações da viagem</h2>
                    <p>Consulte os materiais visuais e informativos do Strain.</p>
                </div>
                <i data-lucide="map-pinned" aria-hidden="true"></i>
            </div>

            <div class="grade_informacoes_home">
                <article class="cartao_informacao_home cartao_mapa_home">
                    <div class="titulo_cartao_home">
                        <i data-lucide="map" aria-hidden="true"></i>
                        <h3>Mapa e localização</h3>
                    </div>
                    <img src="../../assets/img/Mockup- SA Juana, Luiz, Nicole, Rafael.png"
                         alt="Mapa ilustrativo de localização dos trens do Strain" loading="lazy">
                    <p>Representação visual do mapa do projeto. Não exibe localização em tempo real.</p>
                </article>

                <article class="cartao_informacao_home">
                    <div class="titulo_cartao_home">
                        <i data-lucide="triangle-alert" aria-hidden="true"></i>
                        <h3>Avisos e alertas</h3>
                    </div>
                    <img src="../../assets/img/alertas.png"
                         alt="Ilustração do painel de alertas do Strain" loading="lazy">
                    <p>Visualização ilustrativa dos avisos da plataforma.</p>
                </article>

                <article class="cartao_informacao_home">
                    <div class="titulo_cartao_home">
                        <i data-lucide="train-front" aria-hidden="true"></i>
                        <h3>Trens da rede</h3>
                    </div>
                    <img src="../../assets/img/trens.png"
                         alt="Ilustração do painel de trens do Strain" loading="lazy">
                    <p>Conheça a apresentação visual dos trens do sistema.</p>
                </article>
            </div>
        </section>
    </main>

    <!-- POP-UP DAS LINHAS -->
    <div id="popup" class="popup" role="dialog" aria-modal="true"
         aria-labelledby="tituloLinha" aria-hidden="true">
        <div class="popup-conteudo" tabindex="-1">
            <button type="button" class="fechar" onclick="fecharPopup()" aria-label="Fechar horários">
                <i data-lucide="x" aria-hidden="true"></i>
            </button>
            <h2 id="tituloLinha"></h2>
            <p class="descricao_popup_home">Horários ilustrativos da linha selecionada</p>
            <ul id="horarios" class="lista_horarios_home"></ul>
        </div>
    </div>

    <footer class="rodape_home_cliente">
        <p>© 2026 Strain. Todos os direitos reservados.</p>
        <p>Desenvolvido por Nicole Jordan, Juana Franciscao, Luiz Felipe e Rafael Tironi.</p>
    </footer>

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <script>
        if (window.lucide) lucide.createIcons();

        // Mantém os pop-ups funcionando sem depender de scripts de outras páginas.
        const popupLinhas = document.getElementById('popup');
        const tituloPopup = document.getElementById('tituloLinha');
        const listaHorarios = document.getElementById('horarios');
        const fecharPopupBotao = popupLinhas.querySelector('.fechar');
        let ultimoBotaoAtivado = null;

        function abrirPopup(linha, horarios) {
            ultimoBotaoAtivado = document.activeElement;
            tituloPopup.textContent = linha;
            listaHorarios.replaceChildren();

            horarios.forEach((horario) => {
                const item = document.createElement('li');
                item.textContent = horario;
                listaHorarios.appendChild(item);
            });

            popupLinhas.style.display = 'flex';
            popupLinhas.setAttribute('aria-hidden', 'false');
            document.body.classList.add('popup_home_aberto');
            fecharPopupBotao.focus();
        }

        function fecharPopup() {
            popupLinhas.style.display = 'none';
            popupLinhas.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('popup_home_aberto');
            if (ultimoBotaoAtivado) ultimoBotaoAtivado.focus();
        }

        popupLinhas.addEventListener('click', (evento) => {
            if (evento.target === popupLinhas) fecharPopup();
        });

        document.addEventListener('keydown', (evento) => {
            if (evento.key === 'Escape' && popupLinhas.style.display === 'flex') {
                fecharPopup();
            }
        });
    </script>
</body>
</html>

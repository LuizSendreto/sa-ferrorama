<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Alertas ● Strain</title>

    <link
        rel="stylesheet"
        href="../assets/style/style.css?v=sidebar7"
    >
</head>

<body class="body_dashboard">

    <!-- NAVBAR -->
    <header id="header_alertas">

    <nav class="navbar">
        <div class="marca-strain">
            <i data-lucide="train-front"></i>
            <span>STRAIN</span>
        </div>

        <div class="saudacao-navbar">
            <p>
                Olá, <?php echo htmlspecialchars($nome, ENT_QUOTES, 'UTF-8'); ?>!
            </p>
        </div>
    </nav>

    </header>

    <!-- SIDEBAR -->
    <aside id="sidebar_dashboard">

        <h2 id="titulo_sidebar">Dashboard</h2>

        <ul class="menu_sidebar">

            <li>
                <a href="dashboard.php">Dashboard</a>
            </li>

            <li>
                <a href="Trens/visualizar_trens.php">Trens</a>
            </li>

            <li>
                <a href="rotas/rotas.php">Rotas</a>
            </li>

            <li>
                <a href="monitoramento.php">Monitoramento</a>
            </li>

            <li>
                <a href="alertas.php" class="active_sidebar">Alertas</a>
            </li>

            <li>
                <a href="relatorios.php">Relatórios</a>
            </li>

            <?php if ($admin) { ?>
                <li>
                    <a href="usuarios/visu_usuarios.php">
                        Usuários
                    </a>
                </li>
            <?php } ?>

            <li>
                <a href="sensores/visu_sensores.php">
                    Sensores
                </a>
            </li>

            <li>
                <a href="usuarios/logout.php">Logout</a>
            </li>

        </ul>

    </aside>

    <!-- CONTEÚDO PRINCIPAL -->
    <main id="main_alertas">

        <div class="fotos">
            <img
                src="../assets/img/alertas.png"
                alt="Painel de alertas do Strain"
            >
        </div>

    </main>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
<script>
    lucide.createIcons();
</script>
</body>

</html>

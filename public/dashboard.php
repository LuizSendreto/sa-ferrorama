<?php

session_start();

if (!isset($_SESSION['funcionario_id'])) {
    header("Location: usuarios/login.php");
    exit;
}

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link rel="stylesheet" href="../assets/style/style.css">
</head>

<body class="body_dashboard">
    <header id="header_dashboard">
        <nav class="navbar">

            <div class="logo-navbar">
                <img src="../assets/img/logo_navbar2.png" alt="Logo Strain">
            </div>

        </nav>

    </header>

    <main id="main_dashboard">

        <aside id="sidebar_dashboard">

            <h2 id="titulo_sidebar">Dashboard</h2>

            <ul class="menu_sidebar">
                <li><a href="../index.php">Início</a></li>
                <li><a href="dashboard.php" class="active_sidebar">Dashboard</a></li>
                <li><a href="trens/cadastrar_trens.php">Trens</a></li>
                <li><a href="monitoramento.php">Monitoramento</a></li>
                <li><a href="alertas.php">Alertas</a></li>
                <li><a href="relatorios.php">Relatórios</a></li>
                <li><a href="usuarios/visu_usuarios.php">Visualização de Usuarios</a></li>
                <li><a href="sensores/visu_sensores.php">Visualização de Sensores</a></li>
                <li><a href="usuarios/logout.php">Logout</a></li>
            </ul>
        </aside>

        <div class="fotos_dashboard">
            <img id="fotos_dashboard" src="../assets/img/dashboard_certo.png" alt="Trens">
            
        </div>

    </main>

</body>

</html>
<?php
require_once '../infra/verificar_acesso.php';
verificarFuncionario();

$admin = ($_SESSION['nivel_acesso'] ?? '') === 'admin';
$nome = $_SESSION['usuario_nome'] ?? 'Funcionário';
?>
  
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios ● Strain</title>

    <link rel="stylesheet" href="../assets/style/style.css">

</head>

<body class ="body_dashboard">

    <header id="header_relatorios">
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

    <main id="main_relatorios">



        <aside id="sidebar_dashboard">

            <h2 id="titulo_sidebar">Dashboard</h2>

            <ul class="menu_sidebar">
               
                <li><a href="../public/dashboard.php" >Dashboard</a></li>
                <li><a href="../public/Trens/visualizar_trens.php">Trens</a></li>
                <li><a href="rotas/rotas.php">Rotas</a></li>
                <li><a href="../public/monitoramento.php">Monitoramento</a></li>
                <li><a href="../public/alertas.php">Alertas</a></li>
                <li><a href="../public/relatorios.php" class="active_sidebar">Relatórios</a></li>
                <li><a href="../public/usuarios/visu_usuarios.php">Usuarios</a></li>
                <li><a href="../public/sensores/visu_sensores.php">Sensores</a></li>
                <li><a href="../public/usuarios/logout.php">Logout</a></li>
            </ul>

        </aside>

        <div class="fotos">
            <img src="../assets/img/relatorios.png" alt="Trens">
        </div>

    </main>

    <footer>

    </footer>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
<script>
    lucide.createIcons();
</script>
</body>

</html>
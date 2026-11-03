
<?php
require_once '../infra/verificar_acesso.php';
verificarFuncionario();

$admin = ($_SESSION['nivel_acesso'] ?? '') === 'admin';
$nome = $_SESSION['usuario_nome'] ?? 'Funcionário';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard • Strain</title>

    <link
        rel="stylesheet"
        href="../assets/style/style.css?v=sidebar7"
    >
</head>

<body class="body_dashboard">

    <header id="header_dashboard">
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

    <!-- SIDEBAR PADRONIZADA -->
    <aside id="sidebar_dashboard">

        <h2 id="titulo_sidebar">Dashboard</h2>

        <ul class="menu_sidebar">

            <li>
                <a href="dashboard.php" class="active_sidebar">
                    Dashboard
                </a>
            </li>

            <li>
                <a href="Trens/visualizar_trens.php">
                    Trens
                </a>
            </li>

            <li>
                <a href="rotas/rotas.php">
                    Rotas
                </a>
            </li>

            <li>
                <a href="monitoramento.php">
                    Monitoramento
                </a>
            </li>

            <li>
                <a href="alertas.php">
                    Alertas
                </a>
            </li>

            <li>
                <a href="relatorios.php">
                    Relatórios
                </a>
            </li>

            <?php if ($admin) { ?>
                <li>
                    <a href="usuarios/visu_usuarios.php">
                        Visualização de Usuários
                    </a>
                </li>
            <?php } ?>

            <li>
                <a href="sensores/visu_sensores.php">
                    Visualização de Sensores
                </a>
            </li>

            <li>
                <a href="usuarios/logout.php">
                    Logout
                </a>
            </li>

        </ul>

    </aside>

    <!-- CONTEÚDO DO DASHBOARD -->
    <main id="main_dashboard">

        <div class="fotos_dashboard">
            <img
                id="fotos_dashboard"
                src="../assets/img/dashboard_certo.png"
                alt="Dashboard Strain"
            >
        </div>

    </main>


    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
<script>
    lucide.createIcons();
</script>
</body>
</html>

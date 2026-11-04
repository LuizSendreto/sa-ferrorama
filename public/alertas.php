
<?php

require_once '../infra/verificar_acesso.php';
verificarFuncionario();

// Informações do usuário logado
$nome = $_SESSION['usuario_nome'] ?? 'Usuário';
$admin = ($_SESSION['nivel_acesso'] ?? '') === 'admin';

?>

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

      <div class="table_alertas">
                    <h1 id="table_trens">alertas</h1>

                    <section id="tabela_visualizar_trens">
                        

                        <table class="table_t">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome do alerta</th>
                                    <th>Descriçao</th>
                                    <th>Funcionário responsável</th>
                                
                                </tr>
                            </thead>
                        </table>
                                     <div class="div_button">
                            <button class="button-container"
                                    onclick="location.href='trens_cadastro.php'">
                                <b>Cadastrar alerta</b>
                            </button>
                        </div>
</section>
    <!-- CONTEÚDO PRINCIPAL -->
    <main id="main_alertas">

        

    </main>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
<script>
    lucide.createIcons();
</script>
</body>

</html>

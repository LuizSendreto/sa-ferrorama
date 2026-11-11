
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

    <link rel="stylesheet" href="../assets/style/style.css?v=sidebar7">
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

        <section class="container_alertas">

            <h1>Alertas recentes</h1>

            <div class="caixa_alertas">

                <!-- ALERTA DE ERRO -->
                <div class="alerta alerta_erro">

                    <span class="icone_alerta">
                       <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#850000" class="bi bi-exclamation-octagon-fill" viewBox="0 0 16 16">
                            <path d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353zM8 4c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995A.905.905 0 0 1 8 4m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                        </svg>
                    </span>

                    <div class="texto_alerta">
                        <strong>
                            Falha no sensor de temperatura - Trem 6521
                        </strong>

                        <p>Sensor localizado no vagão 3</p>
                    </div>

                    <span class="hora_alerta">10:21</span>

                </div>

                <!-- ALERTA DE AVISO -->
                <div class="alerta alerta_aviso">

                    <span class="icone_alerta">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#FFC107" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                        </svg>  
                    </span>

                    <div class="texto_alerta">
                        <strong>
                            Velocidade abaixo do esperado - Trem 1534
                        </strong>

                        <p>
                            Velocidade média abaixo do limite estabelecido
                        </p>
                    </div>

                    <span class="hora_alerta">10:18</span>

                </div>

                <!-- ALERTA DE INFORMAÇÃO -->
                <div class="alerta alerta_info">

                    <span class="icone_alerta"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#17A2B8 " class="bi bi-info-square-fill" viewBox="0 0 16 16">
                        <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm8.93 4.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM8 5.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                    </svg>

                    </span>

                    <div class="texto_alerta">
                        <strong>
                            Manutenção programada - Trem 9287
                        </strong>

                        <p>
                            Manutenção preventiva agendada para hoje às 14:00
                        </p>
                    </div>

                    <span class="hora_alerta">09:52</span>

                </div>

            </div>

        </section>

    </main>

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

    <script>
        lucide.createIcons();
    </script>

</body>
</html>

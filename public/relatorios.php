
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

    <title>Relatórios ● Strain</title>

    <link rel="stylesheet" href="../assets/style/style.css?v=relatorios1">
</head>

<body class="body_dashboard">

    <!-- NAVBAR -->
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
                <a href="alertas.php">Alertas</a>
            </li>

            <li>
                <a href="relatorios.php" class="active_sidebar">
                    Relatórios
                </a>
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
    <main id="main_relatorios">

        <section class="container_relatorios">

            <h1>Relatórios dos trens</h1>

            <!-- SENSORES IOT -->
            <div class="caixa_monitoramento">

                <h2>Sensores IoT - Trem 1010</h2>

                <div class="grid_sensores">

                    <!-- TEMPERATURA -->
                    <div class="card_sensor">

                        <span class="sensor_icone">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#850000" class="bi bi-thermometer-half" viewBox="0 0 16 16">
                                <path d="M9.5 12.5a1.5 1.5 0 1 1-2-1.415V6.5a.5.5 0 0 1 1 0v4.585a1.5 1.5 0 0 1 1 1.415"/>
                                <path d="M5.5 2.5a2.5 2.5 0 0 1 5 0v7.55a3.5 3.5 0 1 1-5 0zM8 1a1.5 1.5 0 0 0-1.5 1.5v7.987l-.167.15a2.5 2.5 0 1 0 3.333 0l-.166-.15V2.5A1.5 1.5 0 0 0 8 1"/>
                            </svg>
                        </span>

                        <h3>Temperatura</h3>

                        <strong>75 °C</strong>

                        <p>Normal</p>

                    </div>

                    <!-- VIBRAÇÃO -->
                    <div class="card_sensor">

                        <span class="sensor_icone">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#850000" class="bi bi-broadcast" viewBox="0 0 16 16">
                                <path d="M3.05 3.05a7 7 0 0 0 0 9.9.5.5 0 0 1-.707.707 8 8 0 0 1 0-11.314.5.5 0 0 1 .707.707m2.122 2.122a4 4 0 0 0 0 5.656.5.5 0 1 1-.708.708 5 5 0 0 1 0-7.072.5.5 0 0 1 .708.708m5.656-.708a.5.5 0 0 1 .708 0 5 5 0 0 1 0 7.072.5.5 0 1 1-.708-.708 4 4 0 0 0 0-5.656.5.5 0 0 1 0-.708m2.122-2.12a.5.5 0 0 1 .707 0 8 8 0 0 1 0 11.313.5.5 0 0 1-.707-.707 7 7 0 0 0 0-9.9.5.5 0 0 1 0-.707zM10 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0"/>
                            </svg>
                        </span>

                        <h3>Vibração</h3>

                        <strong>2.3 mm/s</strong>

                        <p>Normal</p>

                    </div>

                    <!-- CONSUMO DE ENERGIA -->
                    <div class="card_sensor">

                        <span class="sensor_icone">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#850000" class="bi bi-lightning-charge-fill" viewBox="0 0 16 16">
                                <path d="M11.251.068a.5.5 0 0 1 .227.58L9.677 6.5H13a.5.5 0 0 1 .364.843l-8 8.5a.5.5 0 0 1-.842-.49L6.323 9.5H3a.5.5 0 0 1-.364-.843l8-8.5a.5.5 0 0 1 .615-.09z"/>
                            </svg>
                        </span>

                        <h3>Consumo de energia</h3>

                        <strong>320 kWh</strong>

                        <p>Normal</p>

                    </div>

                    <!-- FRENAGEM -->
                    <div class="card_sensor">

                        <span class="sensor_icone">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#850000" class="bi bi-train-front-fill" viewBox="0 0 16 16">
                                <path d="M10.621.515C8.647.02 7.353.02 5.38.515c-.924.23-1.982.766-2.78 1.22C1.566 2.322 1 3.432 1 4.582V13.5A2.5 2.5 0 0 0 3.5 16h9a2.5 2.5 0 0 0 2.5-2.5V4.583c0-1.15-.565-2.26-1.6-2.849-.797-.453-1.855-.988-2.779-1.22ZM6.5 2h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1 0-1m-2 2h7A1.5 1.5 0 0 1 13 5.5v2A1.5 1.5 0 0 1 11.5 9h-7A1.5 1.5 0 0 1 3 7.5v-2A1.5 1.5 0 0 1 4.5 4m.5 9a1 1 0 1 1-2 0 1 1 0 0 1 2 0m0 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0m8 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0m-3-1a1 1 0 1 1 0 2 1 1 0 0 1 0-2M4 5.5a.5.5 0 0 1 .5-.5h3v3h-3a.5.5 0 0 1-.5-.5zM8.5 8V5h3a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5z"/>
                            </svg>
                        </span>

                        <h3>Frenagem</h3>

                        <strong>85%</strong>

                        <p>Normal</p>

                    </div>

                </div>

            </div>

            <!-- GRÁFICO -->
            <div class="caixa_grafico">

                <h2>Velocidade dos trens (km/h)</h2>

                <div class="grafico_trens">
                    <canvas id="graficoVelocidade"></canvas>
                </div>

            </div>

        </section>

    </main>

    <!-- ÍCONES LUCIDE -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

    <script>
        lucide.createIcons();
    </script>

    <!-- BIBLIOTECA DO GRÁFICO -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('graficoVelocidade');

        if (ctx && typeof Chart !== 'undefined') {
            new Chart(ctx, {
                type: 'line',

                data: {
                    labels: [
                        '09:55',
                        '10:00',
                        '10:05',
                        '10:10',
                        '10:15',
                        '10:20'
                    ],

                    datasets: [
                        {
                            label: 'Trem 1010',
                            data: [85, 80, 90, 78, 88, 86],
                            borderColor: '#3984f5',
                            backgroundColor: '#3984f5',
                            tension: 0.3
                        },
                        {
                            label: 'Trem 1534',
                            data: [58, 64, 49, 62, 50, 60],
                            borderColor: '#32a96d',
                            backgroundColor: '#32a96d',
                            tension: 0.3
                        },
                        {
                            label: 'Trem 6521',
                            data: [15, 12, 14, 10, 9, 12],
                            borderColor: '#ef4444',
                            backgroundColor: '#ef4444',
                            tension: 0.3
                        },
                        {
                            label: 'Trem 9287',
                            data: [38, 42, 50, 45, 39, 50],
                            borderColor: '#f7b733',
                            backgroundColor: '#f7b733',
                            tension: 0.3
                        }
                    ]
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },

                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });
        }
    </script>

</body>
</html>
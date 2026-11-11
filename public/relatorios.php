
<?php

require_once '../infra/verificar_acesso.php';
verificarFuncionario();
require_once __DIR__ . '/componentes/visual.php';

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

    
<link rel="stylesheet" href="../assets/style/listagens.css?v=4">
<link rel="stylesheet" href="../assets/style/admin_paginas.css?v=4">
<script src="../Scripts/listagens.js?v=2" defer></script>
</head>

<body class="strain-listagem strain-admin">

    <!-- NAVBAR -->
    <?php strainNavegacao('Relatórios', '', $nome, $admin); ?>

    <!-- SIDEBAR -->
    

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="strain-conteudo" id="main_relatorios">
<section class="strain-cabecalho"><div><h1>Relatórios</h1><p>Indicadores e desempenho dos trens</p></div></section>

        <section class="container_relatorios">

            <h1>Relatórios dos trens</h1>

            <!-- SENSORES IOT -->
            <div class="caixa_monitoramento">

                <h2>Sensores IoT - Trem 1010</h2>

                <div class="grid_sensores">

                    <!-- TEMPERATURA -->
                    <div class="card_sensor">

                        <span class="sensor_icone"><?= icone('thermometer') ?></span>

                        <h3>Temperatura</h3>

                        <strong>75 °C</strong>

                        <p>Normal</p>

                    </div>

                    <!-- VIBRAÇÃO -->
                    <div class="card_sensor">

                        <span class="sensor_icone"><?= icone('activity') ?></span>

                        <h3>Vibração</h3>

                        <strong>2.3 mm/s</strong>

                        <p>Normal</p>

                    </div>

                    <!-- CONSUMO DE ENERGIA -->
                    <div class="card_sensor">

                        <span class="sensor_icone"><?= icone('zap') ?></span>

                        <h3>Consumo de energia</h3>

                        <strong>320 kWh</strong>

                        <p>Normal</p>

                    </div>

                    <!-- FRENAGEM -->
                    <div class="card_sensor">

                        <span class="sensor_icone"><?= icone('disc') ?></span>

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
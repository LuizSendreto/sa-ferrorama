
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

    <title>Alertas ● Strain</title>

    
<link rel="stylesheet" href="../assets/style/listagens.css?v=4">
<link rel="stylesheet" href="../assets/style/admin_paginas.css?v=4">
<script src="../Scripts/listagens.js?v=2" defer></script>
</head>

<body class="strain-listagem strain-admin">

    <!-- NAVBAR -->
    <?php strainNavegacao('Alertas', '', $nome, $admin); ?>

    <!-- SIDEBAR -->
    

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="strain-conteudo" id="main_alertas">
<section class="strain-cabecalho"><div><h1>Alertas</h1><p>Acompanhe os avisos do sistema</p></div></section>

        <section class="container_alertas">

            <h1>Alertas recentes</h1>

            <div class="caixa_alertas">

                <!-- ALERTA DE ERRO -->
                <div class="alerta alerta_erro">

                    <span class="icone_alerta"><?= icone('bell') ?></span>

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

                    <span class="icone_alerta"><?= icone('bell') ?></span>

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

                    <span class="icone_alerta"><?= icone('file') ?></span>

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

    

    

</body>
</html>

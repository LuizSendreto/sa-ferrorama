<?php
require_once '../infra/verificar_acesso.php';
verificarFuncionario();
require_once __DIR__ . '/componentes/visual.php';

$admin = ($_SESSION['nivel_acesso'] ?? '') === 'admin';
$nome = $_SESSION['usuario_nome'] ?? 'Funcionário';

?>
  
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoramento ● Strain</title>

    
<link rel="stylesheet" href="../assets/style/listagens.css?v=4">
<link rel="stylesheet" href="../assets/style/admin_paginas.css?v=4">
<script src="../Scripts/listagens.js?v=2" defer></script>
</head>

<body class="strain-listagem strain-admin">

    <?php strainNavegacao('Monitoramento', '', $nome, $admin); ?>

    <main class="strain-conteudo" id="main_monitoramento">
<section class="strain-cabecalho"><div><h1>Monitoramento</h1><p>Localização dos trens e resumo do sistema</p></div></section>

        

        <div class="container_monitoramento">

            <div class="div_monitoramento">
                <h1 class="titulo_monitoramento">Localização dos trens</h1>
                <p class="titulo_monitoramento2">
                    Esta página é dedicada ao monitoramento em tempo real dos trens e
                    sensores. Aqui você pode visualizar dados atualizados, gráficos de
                    desempenho e alertas importantes para garantir a operação segura e
                    eficiente do sistema ferroviário.
                </p>
            </div>

        </div>

        <div class="container_monitoramento">

            <div class="div_monitoramento">
               

                <h1 class="titulo_monitoramento">Resumo do sistema</h1>
            </div>

            <div class="trensMonitoramento">
                <p class="titulo_monitoramento3">Trens</p>
            </div>

            <div class="status">
                <p class="titulo_monitoramento3">Status</p>
            </div>

            <div class="alerta">
                <p class="titulo_monitoramento3">Alertas</p>
            </div>

            <div class="falha">
                <p class="titulo_monitoramento3">Falhas</p>
            </div>
             
        </div>

    </main>


</body>

</html>
<?php

include "../../infra/conexao.php";

$sql = "SELECT * FROM sensores";

$resultado = mysqli_query($conn, $sql);

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualização dos Sensores Cadastrados</title>
    <link rel="stylesheet" href="../../assets/style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="body_dashboard">

    <header>
        <nav class="navbar">

            <div class="logo-navbar">
                <img src="../../assets/img/logo_navbar2.png" alt="Logo Strain">
            </div>

        </nav>

    </header>

    <main>


    <style>
        #conteudo_sensores {
            position: fixed;
            top: 100px;
            left: 250px;
            right: 0;
            bottom: 0;

            display: flex;
            flex-direction: column;
            align-items: center;

            padding: 40px;
            box-sizing: border-box;

            overflow-y: auto;
        }

        /* Botão de cadastrar */
        #conteudo_sensores button {
            background-color: #940404;
            color: white;

            border: none;
            border-radius: 5px;

            padding: 12px 25px;

            font-size: 16px;
            font-weight: bold;

            cursor: pointer;

            margin-bottom: 30px;
        }

        #conteudo_sensores button:hover {
            background-color: #700303;
        }

        /* Tabela */
        #tabela_registro_sensores {
            width: 90%;
            border-collapse: collapse;
        }

        #tabela_registro_sensores th,
        #tabela_registro_sensores td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
        }

        #tabela_registro_sensores th {
            background-color: #940404;
            color: white;
        }
    </style>

        <!-- Parte do menu de navegação lateral -->
        <aside id="sidebar_dashboard">

            <h2 id="titulo_sidebar">Dashboard</h2>

            <ul class="menu_sidebar">
                <li><a href="../../index.php">Início</a></li>
                <li><a href="../dashboard.php" >Dashboard</a></li>
                <li><a href="../trens/cadastrar_trens.php">Trens</a></li>
                <li><a href="../monitoramento.php">Monitoramento</a></li>
                <li><a href="../alertas.php">Alertas</a></li>
                <li><a href="../relatorios.php">Relatórios</a></li>
                <li><a href="../usuarios/visu_usuario.php">Visualização de Usuarios</a></li>
                <li><a href="../sensores/visu_sensores.php" class="active_sidebar">Visualização de Sensores</a></li>
                <li><a href="../usuarios/login.php">Logout</a></li>
            </ul>

        </aside>
        <!-- finalização do menu de navegação lateral -->
     <div id="conteudo_sensores">

    <button onclick="location.href='cadastro_sensor.php'">
        Cadastrar Sensor
    </button>

    <table id="tabela_registro_sensores">
        <tr>
            <th>ID</th>
            <th>Tipo</th>
            <th>Localização</th>
            <th>Posição</th>
        </tr>

        <?php while ($sensor = mysqli_fetch_assoc($resultado)) { ?>

            <tr>
                <td><?php echo $sensor["id"]; ?></td>
                <td><?php echo $sensor["tipo"]; ?></td>
                <td><?php echo $sensor["localizacao"]; ?></td>
                <td><?php echo $sensor["posicao"]; ?></td>
            </tr>

        <?php } ?>

    </table>

</div>

    </main>

</body>

</html>
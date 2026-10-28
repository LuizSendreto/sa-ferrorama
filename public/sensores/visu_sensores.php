<?php

require_once '../../infra/verificar_acesso.php';
verificarFuncionario();

include "../../infra/conexao.php";

// Verificar se o usuário é administrador
$admin = ($_SESSION['nivel_acesso'] ?? '') == 'admin';

// Criar token de segurança para exclusões
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Buscar sensores cadastrados
$sql = "SELECT * FROM sensores";

$resultado = mysqli_query($conn, $sql);

if (!$resultado) {
    die("Erro ao consultar sensores.");
}

?>

<html lang="pt-br">

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

            /* Botão de excluir dentro da tabela */
            #tabela_registro_sensores button {
                margin-bottom: 0;
                padding: 6px 12px;
                font-size: 14px;
            }
        </style>

        <!-- Parte do menu de navegação lateral -->
        <aside id="sidebar_dashboard">

            <h2 id="titulo_sidebar">Dashboard</h2>

            <ul class="menu_sidebar">
               
                <li><a href="../dashboard.php">Dashboard</a></li>
                <li><a href="../Trens/visualizar_trens.php">Trens</a></li>
                <li><a href="../rotas/rotas.php">Rotas</a></li>
                <li><a href="../monitoramento.php">Monitoramento</a></li>
                <li><a href="../alertas.php">Alertas</a></li>
                <li><a href="../relatorios.php">Relatórios</a></li>
                <li><a href="../usuarios/visu_usuarios.php">Visualização de Usuarios</a></li>
                <li><a href="../sensores/visu_sensores.php" class="active_sidebar">Visualização de Sensores</a></li>
                <li><a href="../usuarios/logout.php">Logout</a></li>
            </ul>

        </aside>
        <!-- Finalização do menu de navegação lateral -->

        <div id="conteudo_sensores">

            <?php if ($admin) { ?>
                <button onclick="location.href='cadastro_sensor.php'">
                    Cadastrar Sensor
                </button>
            <?php } ?>

            <table id="tabela_registro_sensores">
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Localização</th>
                    <th>Posição</th>

                    <?php if ($admin) { ?>
                        <th>Ações</th>
                    <?php } ?>
                </tr>

                <?php while ($sensor = mysqli_fetch_assoc($resultado)) { ?>

                    <tr>
                        <td><?php echo (int) $sensor["id"]; ?></td>
                        <td><?php echo htmlspecialchars((string) $sensor["tipo"]); ?></td>
                        <td><?php echo htmlspecialchars((string) $sensor["localizacao"]); ?></td>
                        <td><?php echo htmlspecialchars((string) $sensor["posicao"]); ?></td>

                        <?php if ($admin) { ?>
                            <td>
                                <form action="excluir_sensor.php" method="POST"
                                      onsubmit="return confirm('Tem certeza que deseja excluir este sensor?');">

                                    <input type="hidden" name="id"
                                           value="<?php echo (int) $sensor['id']; ?>">

                                    <input type="hidden" name="csrf_token"
                                           value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">

                                    <button type="submit">Excluir</button>
                                </form>
                            </td>
                        <?php } ?>

                    </tr>

                <?php } ?>

            </table>

        </div>

    </main>

</body>

</html>
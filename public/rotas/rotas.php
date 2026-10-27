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

// Buscar rotas e seus respectivos trens
$sql = "SELECT rotas.*, trens.nome_linha, trens.numero_serie
        FROM rotas
        LEFT JOIN trens ON rotas.trem_id = trens.id
        ORDER BY rotas.id";

$resultado = mysqli_query($conn, $sql);

if (!$resultado) {
    die("Erro ao consultar rotas: " . mysqli_error($conn));
}

?>

<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rotas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/style/style.css">
</head>

<body class="body_dashboard">

    <header>
        <nav class="navbar">

            <div class="logo-navbar">
                <img src="../../assets/img/logo_navbar2.png" alt="Logo Strain">
            </div>

        </nav>
    </header>

    <main id="main_dashboard">

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
                <li><a href="../dashboard.php">Dashboard</a></li>
                <li><a href="../Trens/visualizar_trens.php">Trens</a></li>
                <li><a href="rotas.php" class="active_sidebar">Rotas</a></li>
                <li><a href="../monitoramento.php">Monitoramento</a></li>
                <li><a href="../alertas.php">Alertas</a></li>
                <li><a href="../relatorios.php">Relatórios</a></li>
                <li><a href="../usuarios/visu_usuarios.php">Visualização de Usuarios</a></li>
                <li><a href="../sensores/visu_sensores.php">Visualização de Sensores</a></li>
                <li><a href="../usuarios/logout.php">Logout</a></li>
            </ul>

        </aside>
        <!-- Finalização do menu de navegação lateral -->

        <div id="conteudo_sensores">

            <?php if ($admin) { ?>
                <button
                    type="button"
                    onclick="window.location.href='cadastrar_rotas.php'">
                    Cadastrar Rota
                </button>
            <?php } ?>

            <table id="tabela_registro_sensores">
                <tr>
                    <th>ID</th>
                    <th>Nome da Rota</th>
                    <th>Descrição</th>
                    <th>Distância</th>
                    <th>Trem vinculado</th>
                    <th>Tempo previsto</th>
                    <th>Data de Criação</th>

                    <?php if ($admin) { ?>
                        <th>Ações</th>
                    <?php } ?>
                </tr>

                <?php while ($rota = mysqli_fetch_assoc($resultado)) { ?>

                    <tr>
                        <td><?php echo (int) $rota["id"]; ?></td>
                        <td><?php echo htmlspecialchars($rota["nome_rota"]); ?></td>
                        <td><?php echo htmlspecialchars($rota["descricao"]); ?></td>
                        <td><?php echo htmlspecialchars($rota["distancia"]); ?> km</td>

                        <td>
                            <?php
                            if ($rota["nome_linha"]) {
                                echo htmlspecialchars($rota["numero_serie"] . " - " . $rota["nome_linha"]);
                            } else {
                                echo "Sem trem";
                            }
                            ?>
                        </td>

                        <td><?php echo (int) $rota["tempo_estimado"]; ?> min</td>
                        <td><?php echo date("d/m/Y", strtotime($rota["data_criacao"])); ?></td>

                        <?php if ($admin) { ?>
                            <td>
                                <a href="editar_rotas.php?id=<?php echo (int) $rota["id"]; ?>">
                                    Editar
                                </a>

                                <form action="excluir_rotas.php" method="POST"
                                      onsubmit="return confirm('Tem certeza que deseja excluir esta rota?');">

                                    <input type="hidden" name="id"
                                           value="<?php echo (int) $rota['id']; ?>">

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
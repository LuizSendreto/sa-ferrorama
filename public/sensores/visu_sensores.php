
<?php

require_once '../../infra/verificar_acesso.php';
verificarFuncionario();

require_once '../../infra/conexao.php';

// Verificar se o usuário é administrador
$admin = ($_SESSION['nivel_acesso'] ?? '') === 'admin';

// Criar token de segurança para exclusões
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Buscar sensores cadastrados
$sql = "SELECT id, tipo, localizacao, posicao
        FROM sensores
        ORDER BY id ASC";

$resultado = mysqli_query($conn, $sql);

if (!$resultado) {
    die("Erro ao consultar sensores.");
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Visualização de Sensores • Strain</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- CSS principal do Strain -->
    <link
        rel="stylesheet"
        href="../../assets/style/style.css?v=6"
    >
</head>

<body class="body_dashboard">

    <!-- NAVBAR -->
    <header>
        <nav class="navbar">

            <div class="logo-navbar">
                <img
                    src="../../assets/img/logo_navbar2.png"
                    alt="Logo Strain"
                >
            </div>

        </nav>
    </header>

    <!-- ÁREA PRINCIPAL PADRONIZADA -->
    <main class="area_registros">

        <!-- MENU LATERAL -->
        <aside id="sidebar_dashboard">

            <h2 id="titulo_sidebar">Dashboard</h2>

            <ul class="menu_sidebar">

                <li>
                    <a href="../dashboard.php">
                        Dashboard
                    </a>
                </li>

                <li>
                    <a href="../Trens/visualizar_trens.php">
                        Trens
                    </a>
                </li>

                <li>
                    <a href="../rotas/rotas.php">
                        Rotas
                    </a>
                </li>

                <li>
                    <a href="../monitoramento.php">
                        Monitoramento
                    </a>
                </li>

                <li>
                    <a href="../alertas.php">
                        Alertas
                    </a>
                </li>

                <li>
                    <a href="../relatorios.php">
                        Relatórios
                    </a>
                </li>

                <?php if ($admin) { ?>

                    <li>
                        <a href="../usuarios/visu_usuarios.php">
                            Visualização de Usuários
                        </a>
                    </li>

                <?php } ?>

                <li>
                    <a
                        href="visu_sensores.php"
                        class="active_sidebar"
                    >
                        Visualização de Sensores
                    </a>
                </li>

                <li>
                    <a href="../usuarios/logout.php">
                        Logout
                    </a>
                </li>

            </ul>

        </aside>

        <!-- PAINEL VERMELHO PADRONIZADO -->
        <section class="painel_registros">

            <!-- TÍTULOS -->
            <h1 class="titulo_registros">
                Visualização de Sensores
            </h1>

            <h2 class="subtitulo_registros">
                Sensores Cadastrados
            </h2>

            <!-- CONTAINER DA TABELA -->
            <div class="container_tabela">

                <table class="tabela_registros">

                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Tipo</th>
                            <th scope="col">Localização</th>
                            <th scope="col">Posição</th>

                            <?php if ($admin) { ?>
                                <th scope="col">Ações</th>
                            <?php } ?>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (mysqli_num_rows($resultado) > 0) { ?>

                            <?php while ($sensor = mysqli_fetch_assoc($resultado)) { ?>

                                <tr>

                                    <td>
                                        <?php echo (int) $sensor['id']; ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            (string) $sensor['tipo'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            (string) $sensor['localizacao'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            (string) $sensor['posicao'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );
                                        ?>
                                    </td>

                                    <!-- AÇÕES: SOMENTE ADMIN -->
                                    <?php if ($admin) { ?>

                                        <td>

                                            <div class="acoes_registros">

                                                <form
                                                    action="excluir_sensor.php"
                                                    method="POST"
                                                    onsubmit="return confirm('Tem certeza que deseja excluir este sensor?');"
                                                >

                                                    <input
                                                        type="hidden"
                                                        name="id"
                                                        value="<?php echo (int) $sensor['id']; ?>"
                                                    >

                                                    <input
                                                        type="hidden"
                                                        name="csrf_token"
                                                        value="<?php
                                                        echo htmlspecialchars(
                                                            $_SESSION['csrf_token'],
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        );
                                                        ?>"
                                                    >

                                                    <button
                                                        type="submit"
                                                        class="botao_excluir"
                                                    >
                                                        Excluir
                                                    </button>

                                                </form>

                                            </div>

                                        </td>

                                    <?php } ?>

                                </tr>

                            <?php } ?>

                        <?php } else { ?>

                            <tr>
                                <td colspan="<?php echo $admin ? 5 : 4; ?>">
                                    Nenhum sensor cadastrado.
                                </td>
                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

            <!-- CADASTRO: SOMENTE ADMIN -->
            <?php if ($admin) { ?>

                <button
                    type="button"
                    class="botao_cadastrar"
                    onclick="location.href='cadastro_sensor.php'"
                >
                    Cadastrar Sensor
                </button>

            <?php } ?>

        </section>

    </main>

</body>

</html>

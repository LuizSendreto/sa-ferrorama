
<?php

require_once '../../infra/verificar_acesso.php';
verificarFuncionario();

include "../../infra/conexao.php";

// Verificar se o usuário é administrador
$admin = ($_SESSION['nivel_acesso'] ?? '') === 'admin';
$nome = $_SESSION['usuario_nome'] ?? 'Funcionário';

// Criar token de segurança
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Buscar rotas e trens vinculados
$sql = "SELECT rotas.*, trens.nome_linha, trens.numero_serie
        FROM rotas
        LEFT JOIN trens ON rotas.trem_id = trens.id
        ORDER BY rotas.id";

$resultado = mysqli_query($conn, $sql);

if (!$resultado) {
    error_log(mysqli_error($conn));
    http_response_code(500);
    exit("Erro ao consultar rotas.");
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Rotas ● Strain</title>

    <link rel="stylesheet" href="../../assets/style/style.css?v=rotas3">
</head>

<body class="body_dashboard">

    <!-- NAVBAR -->
    <header>
        <nav class="navbar">

            <div class="marca-strain">
                <i data-lucide="train-front"></i>
                <span>STRAIN</span>
            </div>

            <div class="saudacao-navbar">
                <p>
                    Olá, <?= htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') ?>!
                </p>
            </div>

        </nav>
    </header>

    <!-- CONTEÚDO -->
    <main class="area_registros">

        <!-- SIDEBAR -->
        <aside id="sidebar_dashboard">

            <h2 id="titulo_sidebar">Dashboard</h2>

            <ul class="menu_sidebar">

                <li>
                    <a href="../dashboard.php">Dashboard</a>
                </li>

                <li>
                    <a href="../Trens/visualizar_trens.php">Trens</a>
                </li>

                <li>
                    <a href="rotas.php" class="active_sidebar">Rotas</a>
                </li>

                <li>
                    <a href="../monitoramento.php">Monitoramento</a>
                </li>

                <li>
                    <a href="../alertas.php">Alertas</a>
                </li>

                <li>
                    <a href="../relatorios.php">Relatórios</a>
                </li>

                <li>
                    <a href="../usuarios/visu_usuarios.php">Usuários</a>
                </li>

                <li>
                    <a href="../sensores/visu_sensores.php">Sensores</a>
                </li>

                <li>
                    <a href="../usuarios/logout.php">Logout</a>
                </li>

            </ul>

        </aside>

        <!-- PAINEL DE ROTAS -->
        <section class="painel_registros">

            <h1 class="titulo_registros">
                Visualização de Rotas
            </h1>

            <h2 class="subtitulo_registros">
                Rotas Cadastradas
            </h2>

            <!-- TABELA -->
            <div class="container_tabela">

                <table class="tabela_registros">

                    <thead>
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
                    </thead>

                    <tbody>

                        <?php while ($rota = mysqli_fetch_assoc($resultado)) { ?>

                            <tr>

                                <td>
                                    <?= (int) $rota['id'] ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($rota['nome_rota'], ENT_QUOTES, 'UTF-8') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($rota['descricao'], ENT_QUOTES, 'UTF-8') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($rota['distancia'], ENT_QUOTES, 'UTF-8') ?> km
                                </td>

                                <td>
                                    <?php
                                    if ($rota['nome_linha']) {
                                        echo htmlspecialchars(
                                            $rota['numero_serie'] . ' - ' . $rota['nome_linha'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );
                                    } else {
                                        echo 'Sem trem';
                                    }
                                    ?>
                                </td>

                                <td>
                                    <?= (int) $rota['tempo_estimado'] ?> min
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        date('d/m/Y', strtotime($rota['data_criacao'])),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </td>

                                <?php if ($admin) { ?>

                                    <td>

                                        <div class="acoes_registros">

                                            <!-- EDITAR -->
                                            <a
                                                href="editar_rotas.php?id=<?= (int) $rota['id'] ?>"
                                                class="botao_editar"
                                            >
                                                Editar
                                            </a>

                                            <!-- EXCLUIR -->
                                            <form
                                                action="excluir_rotas.php"
                                                method="POST"
                                                onsubmit="return confirm('Tem certeza que deseja excluir esta rota?');"
                                            >

                                                <input
                                                    type="hidden"
                                                    name="id"
                                                    value="<?= (int) $rota['id'] ?>"
                                                >

                                                <input
                                                    type="hidden"
                                                    name="csrf_token"
                                                    value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>"
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

                    </tbody>

                </table>

            </div>

            <!-- BOTÃO CADASTRAR -->
            <?php if ($admin) { ?>

                <div class="div_button">

                    <a
                        href="cadastrar_rotas.php"
                        class="botao_cadastrar"
                    >
                        Cadastrar Rota
                    </a>

                </div>

            <?php } ?>

        </section>

    </main>

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

    <script>
        lucide.createIcons();
    </script>

</body>
</html>

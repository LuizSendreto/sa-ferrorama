<?php
session_start();

if (!isset($_SESSION['funcionario_id'])) {
    header("Location: ../usuarios/login.php");
    exit;
}

include "../../infra/conexao.php";

// Busca os trens cadastrados e seus funcionários responsáveis
$sql = "SELECT
            trens.id,
            trens.nome_linha,
            trens.numero_serie,
            trens.velocidade,
            trens.estado,
            trens.funcionario_id,
            funcionarios.nome AS funcionario_responsavel
        FROM trens
        LEFT JOIN funcionarios ON trens.funcionario_id = funcionarios.id
        ORDER BY trens.id";

$resultado = mysqli_query($conn, $sql);

if (!$resultado) {
    die("Erro ao consultar trens: " . mysqli_error($conn));
}

$lista_trens = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trens</title>

    <link rel="stylesheet" href="../../assets/style/style.css">
</head>

<body class="body_dashboard">

    <header id="header_trens">
        <nav class="navbar">

            <div class="logo-navbar">
                <img src="../../assets/img/logo_navbar2.png" alt="Logo Strain">
            </div>

            <div class="logo-navbar">
                <img src="../../assets/img/icone_da_navbar.png" alt="logo usuario logado">
            </div>

        </nav>
    </header>

    <main id="main_trens">
        <aside id="sidebar_dashboard">

            <h2 id="titulo_sidebar">Dashboard</h2>

            <ul class="menu_sidebar">
                <li><a href="../../index.php">Início</a></li>
                <li><a href="../dashboard.php">Dashboard</a></li>
                <li><a href="../trens/visualizar_trens.php" class="active_sidebar">Trens</a></li>
                <li>
    <a href="../rotas/rotas.php">Rotas</a>
</li>
                <li><a href="../monitoramento.php">Monitoramento</a></li>
                <li><a href="../alertas.php">Alertas</a></li>
                <li><a href="../relatorios.php">Relatórios</a></li>
                <li><a href="../usuarios/visu_usuarios.php">Visualização de Usuarios</a></li>
                <li><a href="../sensores/visu_sensores.php">Visualização de Sensores</a></li>
                <li><a href="../usuarios/logout.php">Logout</a></li>
            </ul>

        </aside>

        <div class="container_trens">
            <div class="fotos">

                <?php
                $classes_titulo = ["titulo1", "titulo2", "titulo3", "titulo4"];
                foreach ($lista_trens as $i => $t) {
                    $classe = $classes_titulo[$i % 4];
                ?>
                    <div class="trens">
                        <div class="<?php echo $classe; ?>">
                            <h2>Trem <?php echo htmlspecialchars($t["numero_serie"]); ?> - <?php echo htmlspecialchars($t["nome_linha"]); ?></h2>
                        </div>
                        <img class="trem-img" src="../../assets/img/Purple and Pink Modern Illustration Happy Halloween Circle Sticker (1).png" alt="Trem">
                        <h4 class="trem-info">Velocidade atual: <b><?php echo (int) $t["velocidade"]; ?>km/h</b></h4>
                        <h4>Status: <button><?php echo htmlspecialchars($t["estado"]); ?></button></h4>
                    </div>
                <?php } ?>

                <div class="table_trens" style="width:100%;">
                    <h1 id="table_trens">Visualização de trens</h1>

                    <section id="tabela_visualizar_trens">
                        <h2 class="titulo_tabela">Trens Cadastrados</h2>

                        <table class="table_t">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Linha</th>
                                    <th>Número de série</th>
                                    <th>Velocidade</th>
                                    <th>Estado</th>
                                    <th>Funcionário responsável</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($lista_trens as $t) { ?>
                                    <tr>
                                        <td><?php echo (int) $t["id"]; ?></td>
                                        <td><?php echo htmlspecialchars($t["nome_linha"]); ?></td>
                                        <td><?php echo htmlspecialchars($t["numero_serie"]); ?></td>
                                        <td><?php echo (int) $t["velocidade"]; ?> km/h</td>
                                        <td><?php echo htmlspecialchars($t["estado"]); ?></td>
                                        <td><?php echo htmlspecialchars($t["funcionario_responsavel"] ?? "Não atribuído"); ?></td>
                                        <td>
                                            <a href="editar_trens.php?id=<?php echo (int) $t["id"]; ?>">Editar</a>
                                            <a href="excluir_trens.php?id=<?php echo (int) $t["id"]; ?>"
                                               onclick="return confirm('Tem certeza que deseja excluir este trem?');">
                                                Excluir
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </section>

                    <div class="div_button">
                        <button class="button-container"
                                onclick="location.href='trens_cadastro.php'">
                            <b>Cadastrar trem</b>
                        </button>
                    </div>
                </div>

            </div>
        </div>

    </main>
</body>

</html>
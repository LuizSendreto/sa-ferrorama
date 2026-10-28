
<?php

require_once '../../infra/verificar_acesso.php';
verificarAdmin();

require_once '../../infra/conexao.php';

// Criar token de segurança
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Consultar usuários
$sql = "SELECT id, nome, email, telefone, tipo, nivel_acesso
        FROM usuarios
        ORDER BY nome ASC, id ASC";

$usuarios = mysqli_query($conn, $sql);

if (!$usuarios) {
    die("Erro ao consultar usuários.");
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Visualização de Usuários • Strain</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- CSS do Strain -->
    <link
        rel="stylesheet"
        href="../../assets/style/style.css?v=5"
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

            <div class="logo-navbar">
                <img
                    src="../../assets/img/icone_da_navbar.png"
                    alt="Usuário logado"
                >
            </div>

        </nav>
    </header>

    <!-- ÁREA PRINCIPAL PADRONIZADA -->
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
                    <a href="../rotas/rotas.php">Rotas</a>
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
                    <a
                        href="visu_usuarios.php"
                        class="active_sidebar"
                    >
                        Visualização de Usuários
                    </a>
                </li>

                <li>
                    <a href="../sensores/visu_sensores.php">
                        Visualização de Sensores
                    </a>
                </li>

                <li>
                    <a href="../usuarios/logout.php">Logout</a>
                </li>

            </ul>

        </aside>

        <!-- PAINEL VERMELHO -->
        <section class="painel_registros">

            <h1 class="titulo_registros">
                Visualização de Usuários
            </h1>

            <h2 class="subtitulo_registros">
                Usuários Cadastrados
            </h2>

            <!-- TABELA PADRONIZADA -->
            <div class="container_tabela">

                <table class="tabela_registros">

                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Email</th>
                            <th scope="col">Telefone</th>
                            <th scope="col">Tipo</th>
                            <th scope="col">Nível de acesso</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (mysqli_num_rows($usuarios) > 0) { ?>

                            <?php while ($usuario = mysqli_fetch_assoc($usuarios)) { ?>

                                <tr>

                                    <td>
                                        <?php echo (int) $usuario['id']; ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            (string) $usuario['nome'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            (string) $usuario['email'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            (string) $usuario['telefone'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo $usuario['tipo'] === 'funcionario'
                                            ? 'Funcionário'
                                            : 'Cliente';
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo $usuario['nivel_acesso'] === 'admin'
                                            ? 'Administrador'
                                            : 'Comum';
                                        ?>
                                    </td>

                                    <!-- AÇÕES -->
                                    <td>

                                        <div class="acoes_registros">

                                            <a
                                                class="botao_editar"
                                                href="editar_usuarios.php?id=<?php echo (int) $usuario['id']; ?>"
                                            >
                                                Editar
                                            </a>

                                            <?php if ((int) $usuario['id'] !== (int) $_SESSION['usuario_id']) { ?>

                                                <form
                                                    action="excluir_usuarios.php"
                                                    method="POST"
                                                    onsubmit="return confirm('Tem certeza que deseja excluir este usuário?');"
                                                >

                                                    <input
                                                        type="hidden"
                                                        name="id"
                                                        value="<?php echo (int) $usuario['id']; ?>"
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

                                            <?php } ?>

                                        </div>

                                    </td>

                                </tr>

                            <?php } ?>

                        <?php } else { ?>

                            <tr>
                                <td colspan="7">
                                    Nenhum usuário cadastrado.
                                </td>
                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

            <!-- CADASTRO DE FUNCIONÁRIOS -->
            <button
                type="button"
                class="botao_cadastrar"
                onclick="location.href='../funcionarios/cadastro_funcionarios.php'"
            >
                Cadastrar Funcionário
            </button>

        </section>

    </main>

</body>

</html>

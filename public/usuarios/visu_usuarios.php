<?php

require_once '../../infra/verificar_acesso.php';
verificarAdmin();

include '../../infra/conexao.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Buscar todos os usuários
$sql = "SELECT id, nome, email, telefone, tipo, nivel_acesso
        FROM usuarios
        ORDER BY nome ASC";

$usuarios = mysqli_query($conn, $sql);

if (!$usuarios) {
    die("Erro ao consultar usuários.");
}

?>


<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualização de Usuário</title>

    <link rel="stylesheet" href="../../assets/style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>

            <!--Campos verificado(adicionado o campo de senhas)-->

<body class="body_dashboard">

    <header>
        <nav class="navbar">

            <div class="logo-navbar">
                <img src="../../assets/img/logo_navbar2.png" alt="Logo Strain">
            </div>

            <div class="logo-navbar">
                <img src="../../assets/img/icone_da_navbar.png" alt="logo usuario logado">
            </div>

        </nav>
    </header>

    <main class="main_dashboard">
        <aside id="sidebar_dashboard">

            <h2 id="titulo_sidebar">Dashboard</h2>

            <ul class="menu_sidebar">
               
                <li><a href="../dashboard.php" >Dashboard</a></li>
                <li><a href="../trens/visualizar_trens.php">Trens</a></li>
                <li><a href="../rotas/rotas.php">Rotas</a></li>
                <li><a href="../monitoramento.php" >Monitoramento</a></li>
                <li><a href="../alertas.php">Alertas</a></li>
                <li><a href="../relatorios.php" >Relatórios</a></li>
                <li><a href="../usuarios/visu_usuarios.php" class="active_sidebar">Visualização de Usuarios</a></li>
                <li><a href="../sensores/visu_sensores.php">Visualização de Sensores</a></li>
                <li><a href="../usuarios/logout.php">Logout</a></li>
            </ul>

        </aside>

        
        <div class="container_dashboard">

            <h1 id="titulo_cadastro">Visualização de Usuários</h1>
    
<section id="tabela_cadastro_clientes">

    <div class="text-center">

        <h2 class="titulo_tabela">Usuários Cadastrados</h2>

        <table class="table table-bordered mx-auto" style="width: 90%;">

            <tr>
                <th class="text-center">ID</th>
                <th class="text-center">Nome</th>
                <th class="text-center">Email</th>
                <th class="text-center">Telefone</th>
                <th class="text-center">Tipo</th>
                <th class="text-center">Nível de acesso</th>
                <th class="text-center">Ações</th>
            </tr>

            <?php while ($usuario = mysqli_fetch_assoc($usuarios)) { ?>

                <tr>
                    <td><?php echo (int) $usuario["id"]; ?></td>

                    <td><?php echo htmlspecialchars($usuario["nome"]); ?></td>

                    <td><?php echo htmlspecialchars($usuario["email"]); ?></td>

                    <td><?php echo htmlspecialchars($usuario["telefone"]); ?></td>

                    <td>
                        <?php
                        if ($usuario["tipo"] == "funcionario") {
                            echo "Funcionário";
                        } else {
                            echo "Cliente";
                        }
                        ?>
                    </td>

                    <td>
                        <?php
                        if ($usuario["nivel_acesso"] == "admin") {
                            echo "Administrador";
                        } else {
                            echo "Comum";
                        }
                        ?>
                    </td>

                    <td>
                        <a href="editar_usuarios.php?id=<?php echo (int) $usuario["id"]; ?>">
                            Editar
                        </a>

                        <?php if ((int) $usuario["id"] !== (int) $_SESSION["usuario_id"]) { ?>

                            <form action="excluir_usuarios.php" method="POST"
                                  onsubmit="return confirm('Tem certeza que deseja excluir este usuário?');">

                                <input type="hidden" name="id"
                                       value="<?php echo (int) $usuario["id"]; ?>">

                                <input type="hidden" name="csrf_token"
                                       value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">

                                <button type="submit" class="btn btn-danger btn-sm">
                                    Excluir
                                </button>

                            </form>

                        <?php } ?>
                    </td>
                </tr>

            <?php } ?>

        </table>

    </div>

    <div class="div_button">
        <button class="button-container"
                onclick="location.href='../funcionarios/cadastro_funcionarios.php'">
            <b>Cadastrar Funcionário</b>
        </button>
    </div>

</section>


    </main>

    <footer></footer>

</body>

</html>
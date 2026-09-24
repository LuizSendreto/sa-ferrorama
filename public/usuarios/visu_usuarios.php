<?php

include '../../infra/conexao.php';

$sql = "SELECT id, nome, email, telefone FROM clientes";
$clientes = mysqli_query($conn, $sql);

$sql_func = "SELECT id, nome, email, telefone FROM funcionarios";
$funcionarios = mysqli_query($conn, $sql_func);
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
                <img src="../assets/img/logo_navbar2.png" alt="Logo Strain">
            </div>

            <div class="logo-navbar">
                <img src="../assets/img/icone_da_navbar.png" alt="logo usuario logado">
            </div>

        </nav>
    </header>

    <main>
        <aside id="sidebar_dashboard">

            <h2 id="titulo_sidebar">Dashboard</h2>

            <ul class="menu_sidebar">
                <li><a href="../../index.php">Início</a></li>
                <li><a href="../dashboard.php" >Dashboard</a></li>
                <li><a href="../trens/cadastrar_trens.php">Trens</a></li>
                <li><a href="../monitoramento.php" >Monitoramento</a></li>
                <li><a href="../alertas.php">Alertas</a></li>
                <li><a href="../relatorios.php" >Relatórios</a></li>
                <li><a href="../usuarios/visu_usuarios.php" class="active_sidebar">Visualização de Usuarios</a></li>
                <li><a href="../sensores/visu_sensores.php">Visualização de Sensores</a></li>
                <li><a href="../usuarios/login.php">Logout</a></li>
            </ul>

        </aside>

        

    <section id="tabela_cadastro_clientes">

        <div class="text-center">

            <h2>Clientes Cadastrados</h2>

            <table class="table table-bordered mx-auto" style="width: 80%;">

                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Ações</th>
                </tr>

                <?php while ($cliente = mysqli_fetch_assoc($clientes)) { ?>
                        <tr>
                            <td><?php echo $cliente["id"]; ?></td>
                            <td><?php echo $cliente["nome"]; ?></td>
                            <td><?php echo $cliente["email"]; ?></td>
                            <td><?php echo $cliente["telefone"]; ?></td>
                            <td>
                                <a href="public/editar_clientes.php?id=<?php echo $cliente["id"]; ?>">Editar</a>
                                <a href="public/excluir_clientes.php?id=<?php echo $cliente["id"]; ?>">Excluir</a>
                            </td>
                        </tr>
                <?php } ?>
            </table>
        </div>
    </section>

    <section id="tabela_cadastro_funcionarios">

        <div class="text-center">
            <h2>Funcionários Cadastrados</h2>

            <table class="table table-bordered mx-auto" style="width: 80%;">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Ações</th>
                </tr>

                <?php while ($funcionario = mysqli_fetch_assoc($funcionarios)) { ?>
                        <tr>
                            <td><?php echo $funcionario["id"]; ?></td>
                            <td><?php echo $funcionario["nome"]; ?></td>
                            <td><?php echo $funcionario["email"]; ?></td>
                            <td><?php echo $funcionario["telefone"]; ?></td>
                            <td>
                                <a href="public/editar_funcionarios.php?id=<?php echo $funcionario["id"]; ?>">Editar</a>
                                <a href="public/excluir_funcionarios.php?id=<?php echo $funcionario["id"]; ?>">Excluir</a>
                            </td>
                        </tr>
                <?php } ?>
            </table>
        </div>

        <div class="text-center">
            <button onclick="location.href='cadastro_funcionarios.php'">Cadastrar Funcionário</button>
        </div>
        
    </section>

    </main>

    <footer></footer>

</body>

</html>
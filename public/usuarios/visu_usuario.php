<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualização de Usuário</title>

    <link rel="stylesheet" href="../../assets/style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>

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
                <li><a href="../usuarios/visu_usuario.php" class="active_sidebar">Visualização de Usuarios</a></li>
                <li><a href="../sensores/visu_sensores.php">Visualização de Sensores</a></li>
                <li><a href="../usuarios/login.php">Logout</a></li>
            </ul>

        </aside>

        <section id="tabela_registro_sensores">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Nome</th>
                        <th scope="col">Email</th>
                        <th scope="col">Telefone</th>
                        <th scope="col">Tipo de usuario</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Miguel</td>
                        <td>miguel@gmail.com</td>
                        <td>(11) 99999-9999</td>
                        <td>Usuário Comum</td>
                    </tr>
                    <tr>
                        <td>Juana</td>
                        <td>juana@gmail.com</td>
                        <td>(11) 88888-8888</td>
                        <td>Administrador</td>
                    </tr>
                    <tr>
                        <td>Zonta</td>
                        <td>zonta@gmail.com</td>
                        <td>(11) 77777-7777</td>
                        <td>Usuário Comum</td>
                    </tr>
                </tbody>

            </table>
        </section>

    </main>

    <footer></footer>

</body>

</html>
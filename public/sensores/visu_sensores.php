<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualização dos Sensores Cadastrados</title>
    <link rel="stylesheet" href="../assets/style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="body_dashboard">

    <header>
        <nav class="navbar">

            <div class="logo-navbar">
                <img src="../assets/img/logo_navbar2.png" alt="Logo Strain">
            </div>

        </nav>

    </header>

    <main>

        <!-- Parte do menu de navegação lateral -->
        <aside id="sidebar_dashboard">

            <h2 id="titulo_sidebar">Dashboard</h2>

            <ul class="menu_sidebar">
                <li><a href="../index.html">Início</a></li>
                <li><a href="../public/dashboard.html">Dashboard</a></li>
                <li><a href="../public/trens.html">Trens</a></li>
                <li><a href="../public/monitoramento.html">Monitoramento</a></li>
                <li><a href="../public/alertas.html">Alertas</a></li>
                <li><a href="../public/relatorios.html">Relatórios</a></li>
                <li><a href="../public/visu_usuario.html">Visualização dos Usuarios</a></li>
                <li><a href="../public/visu_sensores.html" class="active_sidebar">Visualização dos Sensores</a></li>
                <li><a href="">Logout</a></li>
            </ul>

        </aside>
        <!-- finalização do menu de navegação lateral -->

        <section id="tabela_registro_sensores">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">ID do Sensor</th>
                        <th scope="col">Tipo de Sensor</th>
                        <th scope="col">Localização</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1.1.1</td>
                        <td>Temperatura</td>
                        <td>Locomotiva 1</td>
                        <td>Ativo</td>
                    </tr>
                    <tr>
                        <td>2.2.2</td>
                        <td>Pressão</td>
                        <td>Vagão 3</td>
                        <td>Inativo</td>
                    </tr>
                    <tr>
                        <td>3.3.3</td>
                        <td>Vibração</td>
                        <td>Locomotiva 2</td>
                        <td>Ativo</td>
                    </tr>
                </tbody>

            </table>
        </section>

        <button id="botao_sensor" type="submit" onclick="location.href='../public/cadastro_sensor.html'">Cadastrar Sensor</button>

    </main>

</body>

</html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoramento</title>

    <link rel="stylesheet" href="../assets/style/style.css">
</head>

<body class="body_dashboard">
    <header id="header_monitoramento">
        <nav class="navbar">

            <div class="logo-navbar">
                <img src="../assets/img/logo_navbar2.png" alt="Logo Strain">
            </div>

        </nav>
    </header>


    <main id="main_monitoramento">
        <aside id="sidebar_dashboard">

            <h2 id="titulo_sidebar">Dashboard</h2>

            <ul class="menu_sidebar">
                <li><a href="../index.php">Início</a></li>
                <li><a href="../public/dashboard.php" >Dashboard</a></li>
                <li><a href="../public/trens/cadastrar_trens.php">Trens</a></li>
                <li><a href="../public/monitoramento.php" class="active_sidebar">Monitoramento</a></li>
                <li><a href="../public/alertas.php" >Alertas</a></li>
                <li><a href="../public/relatorios.php" >Relatórios</a></li>
                <li><a href="../public/usuarios/visu_usuarios.php">Visualização de Usuarios</a></li>
                <li><a href="../public/sensores/visu_sensores.php">Visualização de Sensores</a></li>
                <li><a href="../public/usuarios/login.php">Logout</a></li>
            </ul>
            
        </aside>


        <container class="container_monitoramento">
        <div class="div_monitoramento">
            <img class= "imgLocalizacao"src="../assets/img/Purple and Pink Modern Illustration Happy Halloween Circle Sticker (2).png" alt="Imagem de monitoramento" class="img_monitoramento">
            <h1 class="titulo_monitoramento">Localização dos trens</h1>
            <p class="titulo_monitoramento2">Esta página é dedicada ao monitoramento em tempo real dos trens e <br> sensores. Aqui você pode visualizar dados atualizados, gráficos de desempenho e alertas importantes para garantir a operação segura e eficiente do sistema ferroviário.</p>
            
        </div>
        </container>

        <container class="container_monitoramento">
        <div class="div_monitoramento">
            <img class= "imgLocalizacao"src="../assets/img/Purple and Pink Modern Illustration Happy Halloween Circle Sticker (3).png" alt="Imagem de monitoramento" class="img_monitoramento">
            <h1 class="titulo_monitoramento">Resumo do sistema</h1>
</div>
            <div class="trensMonitoramento">
                <p class="titulo_monitoramento3">Trens</p>
                </div>
              
                 </div>

                 <div class="status">
                <p class="titulo_monitoramento3">status</p>
                </div>
               
                 </div>

                 <div class="alerta">
                <p class="titulo_monitoramento3">Alertas</p>
                </div>
              
                 </div>

                 <div class="falha">
                <p class="titulo_monitoramento3">Falhas</p>
                </div>
               
                 </div>
            
                 </div>
        </div>
        </container>

        
    </main>

</body>

</html>
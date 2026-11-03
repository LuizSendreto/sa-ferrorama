<?php
require_once '../infra/verificar_acesso.php';
verificarFuncionario();
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link rel="stylesheet" href="../assets/style/style.css">
    <link rel="stylesheet" href="../assets/style/clima.css">
    <script src="../Scripts/clima.js"></script>
    <script src="../Scripts/cep.js"></script>
    <link rel="stylesheet" href="../assets/style/cep.css">
</head>

<body class="body_dashboard">
    <header id="header_dashboard">
        <nav class="navbar">

            <div class="logo-navbar">
                <img src="../assets/img/logo_navbar2.png" alt="Logo Strain">
            </div>

        </nav>

    </header>

    <main id="main_dashboard">

        <aside id="sidebar_dashboard">

            <h2 id="titulo_sidebar">Dashboard</h2>

            <ul class="menu_sidebar">
                
                <li><a href="dashboard.php" class="active_sidebar">Dashboard</a></li>
                <li><a href="Trens/visualizar_trens.php">Trens</a></li>
                <li><a href="rotas/rotas.php">Rotas</a></li>
                <li><a href="monitoramento.php">Monitoramento</a></li>
                <li><a href="alertas.php">Alertas</a></li>
                <li><a href="relatorios.php">Relatórios</a></li>
                <li><a href="usuarios/visu_usuarios.php">Visualização de Usuarios</a></li>
                <li><a href="sensores/visu_sensores.php">Visualização de Sensores</a></li>
                <li><a href="usuarios/logout.php">Logout</a></li>
            </ul>
        </aside>

        <div class="fotos_dashboard">
            <img id="fotos_dashboard" src="../assets/img/dashboard_certo.png" alt="Trens">
            
        </div>

        <!-- API DE CLIMA  NA TELA INDEX  -->

        <section class="secao_clima">

            <h2>Clima em Joinville</h2>

            <div class="card_clima">

                <h3 id="local_clima">📍 Joinville - SC</h3>

                <p id="temperatura_clima">Carregando...</p>

                <p id="condicao_clima">Consultando clima...</p>

                <p id="data_clima">Aguardando atualização...</p>

            </div>

        </section>

        
    <!-- FIM Clima -->
        
    <!-- CONSULTA DE CEP -->
    <section class="secao_cep_dashboard">

        <h2>📍 Consulta de CEP</h2>
        <p>Digite um CEP para localizar o endereço.</p>

        <form id="form_cep">

        <div class="busca_cep">
                    <input
                        type="text"
                        id="cep"
                        placeholder="00000-000"
                        maxlength="9"
                        inputmode="numeric"
                        required
                    >

                <button type="submit" id="btn_cep">
                    Consultar
                </button>
        </div>

            <p  id="mensagem_cep"
            role="status"
            aria-live="polite">
            </p>

            <div class="campos_endereco">

                <div>
                    <label for="logradouro">Logradouro:</label>
                    <input type="text" id="logradouro">
                </div>

                <div>
                    <label for="bairro">Bairro:</label>
                    <input type="text" id="bairro">
                </div>

                <div>
                    <label for="cidade">Cidade:</label>
                    <input type="text" id="cidade">
                </div>

                <div>
                    <label for="uf">Estado (UF):</label>
                    <input type="text" id="uf" maxlength="2">
                </div>

            </div>

        </form>

    </section>

<!-- FIM CONSULTA DE CEP -->


    </main>

    
</body>

</html>
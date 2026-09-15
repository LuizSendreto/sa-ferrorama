<?php

include '../infra/conexao.php';


?>



<html class="login" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fazer Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">


    <link rel="stylesheet" href="../assets/style/style.css">

</head>

<body class="body_telalogin">
    <header>
        <nav></nav>
    </header>
    <div class="Strain">
        <img class="Strain" src="../assets/img/Tela de login atualizada.png" alt="Strain">
    </div>
    <div class="flex">
        <main class="blue">



            <div class="container_login">
                <h2 id="titulo_login">Fazer Login</h2>
                <form id="form_login">
                    <div class="conjunto">
                        <label for="email" class="form-label"></label>
                        <input type="email" id="email" name="email" placeholder="Digite seu email" required>
                    </div>
                    <div class="conjunto">
                        <label for="senha" class="form-label"></label>
                        <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
                    </div>
                    <button id="botao_login" type="submit" class="btn btn-outline-danger" onclick="window.location.href='../public/dashboard.php';">Entrar</button>
                </form>
                <div class="Cadastro">
                    <p id="texto_login"> Você ainda não possui cadastro?</p>
                    <a class="link1" href="cadastro.php">Cadastrar-se!</a>
                </div>


                
            </div>
        </main>
    </div>


    <footer></footer>

    <script src="script.js"></script>

</body>

</html>
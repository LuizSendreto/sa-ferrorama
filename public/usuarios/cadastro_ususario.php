<?php

include '../infra/conexao.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];

    $sql = "INSERT INTO clientes (nome, email, telefone) values (?,?,?)";
    $stmt = $conn-> prepare($sql);
    $stmt -> bind_param('sss', $nome,$email,$telefone);


    if($stmt->execute()){
        echo "Cliente cadastrado ";
        echo '<a href="../index.php">voltar</a>';
    }else{
        echo "Erro ao cadastrar" . $stmt->error;
    }
   $stmt->close();
   exit;

   

}

?>

<html class="login" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar</title>
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
                <h2 id="titulo_cadastro">Cadastrar</h2>
                <form id="form_login">
                    <div class="conjunto">
                        <label for="email" class="form-label"></label>
                        <input type="email" id="email" name="email" placeholder="Digite seu email" required>
                    </div>
                    <div class="conjunto">
                        <label for="nomeUsuario" class="form-label"></label>
                        <input type="text" id="nomeUsuario" name="nomeUsuario" placeholder="Digite seu nome de usuário" required>
                    </div>

                    <div class="conjunto">
                        <label for="senha" class="form-label"></label>
                        <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
                    </div>


                    <div class="conjunto">
                        <label for="senha" class="form-label"></label>
                        <input type="password" id="senha" name="senha" placeholder="Confirme a sua senha" required>
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input " type="checkbox" id="gridCheck">
                            <label class="form-check-label" for="gridCheck">
                                Permito o compartilhamento da minha localização
                            </label>
                        </div>
                    </div>

                    <button id="botao_login" type="submit" class="btn btn-outline-danger">Cadastrar-se</button>
                </form>


            </div>
        </main>
    </div>


    <footer></footer>

    <script src="script.js"></script>

</body>

</html>
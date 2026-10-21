<?php
session_start();

if (!isset($_SESSION['funcionario_id'])) {
    header("Location: login.php");
    exit;
}

include "../../infra/conexao.php";

$erro = "";
$enviado = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id       = (int) ($_POST["id"] ?? 0);
    $nome     = trim($_POST["nome"] ?? "");
    $email    = trim($_POST["email"] ?? "");
    $telefone = trim($_POST["telefone"] ?? "");

    $enviado = ["nome" => $nome, "email" => $email, "telefone" => $telefone];

    if ($nome === "" || $email === "" || $telefone === "") {
        $erro = "Preencha todos os campos.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "E-mail inválido.";
    } else {
        try {
            // Atualiza os dados do usuário
            $sql = "UPDATE clientes
                    SET nome = ?, email = ?, telefone = ?
                    WHERE id = ?";

            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssi", $nome, $email, $telefone, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            // Volta para a visualização dos usuários
            header("Location: visu_usuarios.php");
            exit();
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                $erro = "Já existe um usuário com esse e-mail.";
            } else {
                $erro = "Erro no banco de dados: " . $e->getMessage();
            }
        }
    }
} else {
    $id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;
}

if ($id <= 0) {
    die("O ID do usuário não foi informado.");
}

// Busca o usuário no banco
$stmt = mysqli_prepare($conn, "SELECT * FROM clientes WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$cliente = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

// Verifica se o usuário foi encontrado
if (!$cliente) {
    die("Usuário não encontrado.");
}

$titulo_nome = $cliente["nome"];

if ($enviado !== null) {
    $cliente = array_merge($cliente, $enviado);
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
    <link rel="stylesheet" href="../../assets/style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

    <header>
        <h1>Editar Usuário</h1>
    </header>

    <main>
        <h2>Editando o usuário <?php echo htmlspecialchars($titulo_nome); ?>!</h2>

        <?php if ($erro != "") { ?>
            <p style="color:red;"><?php echo htmlspecialchars($erro); ?></p>
        <?php } ?>

        <form action="editar_usuarios.php" method="POST">
            <input type="hidden" name="id" value="<?php echo (int) $id; ?>">

            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($cliente["nome"]); ?>" required>
            <br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($cliente["email"]); ?>" required>
            <br>

            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($cliente["telefone"]); ?>" required>
            <br>

            <button type="submit">Atualizar</button>
            <a href="visu_usuarios.php">Cancelar</a>

        </form>

    </main>

    <footer>

    </footer>

</body>

</html>
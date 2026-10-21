<?php
session_start();

if (!isset($_SESSION['funcionario_id'])) {
    header("Location: ../usuarios/login.php");
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
            $sql = "UPDATE funcionarios
                    SET nome = ?, email = ?, telefone = ?
                    WHERE id = ?";

            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssi", $nome, $email, $telefone, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            header("Location: ../usuarios/visu_usuarios.php");
            exit;
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                $erro = "Já existe um funcionário com esse e-mail.";
            } else {
                $erro = "Erro no banco de dados: " . $e->getMessage();
            }
        }
    }
} else {
    $id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;
}

if ($id <= 0) {
    die("ID do funcionário não informado.");
}

$stmt = mysqli_prepare($conn, "SELECT * FROM funcionarios WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$funcionario = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$funcionario) {
    die("Funcionário não encontrado.");
}

$titulo_nome = $funcionario["nome"];

if ($enviado !== null) {
    $funcionario = array_merge($funcionario, $enviado);
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strain</title>
    <link rel="stylesheet" href="../../assets/style/style.css">
</head>

<body>

    <header>
        <h1>Strain</h1>
    </header>

    <main>

        <h2>Editando o funcionário <?php echo htmlspecialchars($titulo_nome); ?>!</h2>

        <?php if ($erro != "") { ?>
            <p style="color:red;"><?php echo htmlspecialchars($erro); ?></p>
        <?php } ?>

        <form action="editar_funcionarios.php" method="POST">

            <input type="hidden" name="id" value="<?php echo (int) $id; ?>">

            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($funcionario["nome"]); ?>" required>
            <br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($funcionario["email"]); ?>" required>
            <br>

            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($funcionario["telefone"]); ?>" required>
            <br>

            <button type="submit">Atualizar</button>
            <a href="../usuarios/visu_usuarios.php">Cancelar</a>

        </form>

    </main>

    <footer></footer>

</body>

</html>
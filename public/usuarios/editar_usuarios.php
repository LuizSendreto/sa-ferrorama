<?php

require_once '../../infra/verificar_acesso.php';
verificarAdmin();

include "../../infra/conexao.php";

$erro = "";
$enviado = null;

// Criar token de segurança
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Receber o ID do usuário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = (int) ($_POST["id"] ?? 0);
} else {
    $id = (int) ($_GET["id"] ?? 0);
}

if ($id <= 0) {
    header("Location: visu_usuarios.php");
    exit;
}

// Buscar o usuário na nova tabela
$stmt = mysqli_prepare(
    $conn,
    "SELECT id, nome, email, telefone, tipo, nivel_acesso
     FROM usuarios WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$usuario = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$usuario) {
    die("Usuário não encontrado.");
}

$titulo_nome = $usuario["nome"];

// Processar edição
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validar token CSRF
    if (
        !isset($_POST['csrf_token']) ||
        !is_string($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        http_response_code(403);
        exit("Solicitação inválida.");
    }

    $nome = trim($_POST["nome"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $telefone = trim($_POST["telefone"] ?? "");
    $tipo = $_POST["tipo"] ?? "";
    $nivel_acesso = $_POST["nivel_acesso"] ?? "";

    $enviado = [
        "nome" => $nome,
        "email" => $email,
        "telefone" => $telefone,
        "tipo" => $tipo,
        "nivel_acesso" => $nivel_acesso
    ];

    if ($nome === "" || $email === "" || $telefone === "") {
        $erro = "Preencha todos os campos.";

    } elseif (mb_strlen($nome) > 100) {
        $erro = "O nome pode ter no máximo 100 caracteres.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
        $erro = "E-mail inválido.";

    } elseif (strlen($telefone) > 20) {
        $erro = "Telefone inválido.";

    } elseif (!in_array($tipo, ["cliente", "funcionario"], true)) {
        $erro = "Tipo de usuário inválido.";

    } elseif (!in_array($nivel_acesso, ["comum", "admin"], true)) {
        $erro = "Nível de acesso inválido.";

    } elseif ($tipo == "cliente" && $nivel_acesso == "admin") {
        $erro = "Clientes não podem possuir acesso administrativo.";

    } elseif (
        $id == (int) $_SESSION['usuario_id'] &&
        ($tipo != "funcionario" || $nivel_acesso != "admin")
    ) {
        $erro = "Você não pode remover sua própria permissão administrativa.";

    } else {

        try {

            // Verificar se o usuário é responsável por algum trem
            $stmt = mysqli_prepare(
                $conn,
                "SELECT id FROM trens WHERE funcionario_id = ? LIMIT 1"
            );

            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);

            $possui_trem = mysqli_num_rows(
                mysqli_stmt_get_result($stmt)
            ) > 0;

            mysqli_stmt_close($stmt);

            if ($possui_trem && $tipo != "funcionario") {
                $erro = "Este usuário é responsável por um trem e não pode ser alterado para cliente.";

            } else {

                // Atualizar dados na tabela usuarios
                $sql = "UPDATE usuarios
                        SET nome = ?,
                            email = ?,
                            telefone = ?,
                            tipo = ?,
                            nivel_acesso = ?
                        WHERE id = ?";

                $stmt = mysqli_prepare($conn, $sql);

                mysqli_stmt_bind_param(
                    $stmt,
                    "sssssi",
                    $nome,
                    $email,
                    $telefone,
                    $tipo,
                    $nivel_acesso,
                    $id
                );

                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                // Atualizar sessão se editar a própria conta
                if ($id == (int) $_SESSION['usuario_id']) {
                    $_SESSION['usuario_nome'] = $nome;
                    $_SESSION['usuario_email'] = $email;
                }

                header("Location: visu_usuarios.php");
                exit;
            }

        } catch (mysqli_sql_exception $e) {

            if ($e->getCode() == 1062) {
                $erro = "Já existe um usuário com esse e-mail.";
            } else {
                error_log($e->getMessage());
                $erro = "Erro ao atualizar o usuário.";
            }
        }
    }
}

// Manter os dados digitados em caso de erro
if ($enviado !== null) {
    $usuario = array_merge($usuario, $enviado);
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
        <h2>
            Editando o usuário
            <?php echo htmlspecialchars($titulo_nome); ?>!
        </h2>

        <?php if ($erro != "") { ?>
            <p style="color:red;">
                <?php echo htmlspecialchars($erro); ?>
            </p>
        <?php } ?>

        <form action="editar_usuarios.php" method="POST">

            <input type="hidden" name="id"
                   value="<?php echo (int) $id; ?>">

            <input type="hidden" name="csrf_token"
                   value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">

            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome"
                   maxlength="100"
                   value="<?php echo htmlspecialchars($usuario["nome"], ENT_QUOTES, 'UTF-8'); ?>"
                   required>
            <br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email"
                   maxlength="100"
                   value="<?php echo htmlspecialchars($usuario["email"], ENT_QUOTES, 'UTF-8'); ?>"
                   required>
            <br>

            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone"
                   maxlength="20"
                   value="<?php echo htmlspecialchars($usuario["telefone"], ENT_QUOTES, 'UTF-8'); ?>"
                   required>
            <br>

            <label for="tipo">Tipo de usuário:</label>
            <select name="tipo" id="tipo" required>

                <option value="cliente"
                    <?php if ($usuario["tipo"] == "cliente") echo "selected"; ?>>
                    Cliente
                </option>

                <option value="funcionario"
                    <?php if ($usuario["tipo"] == "funcionario") echo "selected"; ?>>
                    Funcionário
                </option>

            </select>
            <br>

            <label for="nivel_acesso">Nível de acesso:</label>
            <select name="nivel_acesso" id="nivel_acesso" required>

                <option value="comum"
                    <?php if ($usuario["nivel_acesso"] == "comum") echo "selected"; ?>>
                    Comum
                </option>

                <option value="admin"
                    <?php if ($usuario["nivel_acesso"] == "admin") echo "selected"; ?>>
                    Administrador
                </option>

            </select>
            <br>

            <button type="submit">Atualizar</button>

            <a href="visu_usuarios.php">Cancelar</a>

        </form>

    </main>

    <footer></footer>

</body>

</html>
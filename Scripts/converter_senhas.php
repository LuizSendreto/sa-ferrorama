
<?php

require_once '../infra/conexao.php';

// Buscar usuários
$resultado = mysqli_query(
    $conn,
    "SELECT id, senha FROM usuarios"
);

$convertidas = 0;

while ($usuario = mysqli_fetch_assoc($resultado)) {

    $id = (int) $usuario['id'];
    $senha = $usuario['senha'];

    // Não converter senhas que já possuem hash
    if (password_get_info($senha)['algoName'] !== 'unknown') {
        continue;
    }

    // Gerar hash da senha antiga
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt = mysqli_prepare(
        $conn,
        "UPDATE usuarios SET senha = ? WHERE id = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "si",
        $senha_hash,
        $id
    );

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $convertidas++;
}

echo "Senhas convertidas: " . $convertidas;

?>

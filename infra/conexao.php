<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "banco_exemplo";

// Faz o mysqli lançar exceções em qualquer versão do PHP.
// Sem isso, em versões antigas um INSERT que falha NÃO dá erro nenhum
// (a página só redireciona e nada é salvo).
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $user, $password, $database, 3306);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    die("Erro na conexão com o banco: " . htmlspecialchars($e->getMessage()));
}

?>

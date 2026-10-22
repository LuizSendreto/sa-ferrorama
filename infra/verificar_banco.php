<?php
session_start();
if (!isset($_SESSION['funcionario_id'])) {
    die("Faça login primeiro.");
}
include __DIR__ . "/conexao.php";

$esperado = [
    "trens" => [
        "nome_linha"     => "VARCHAR(100) NOT NULL DEFAULT ''",
        "numero_serie"   => "VARCHAR(100) NOT NULL DEFAULT ''",
        "velocidade"     => "INT NOT NULL DEFAULT 0",
        "estado"         => "VARCHAR(20) NOT NULL DEFAULT 'Ativo'",
        "funcionario_id" => "INT NULL",
    ],
    "rotas" => [
        "nome_rota"      => "VARCHAR(100) NOT NULL DEFAULT ''",
        "descricao"      => "VARCHAR(255) NOT NULL DEFAULT ''",
        "distancia"      => "DECIMAL(6,2) NOT NULL DEFAULT 0",
        "tempo_estimado" => "INT NOT NULL DEFAULT 0",
        "data_criacao"   => "DATE NOT NULL DEFAULT '2000-01-01'",
        "trem_id"        => "INT NULL",
    ],
];

echo "<pre>";
foreach ($esperado as $tabela => $colunas) {
    echo "Tabela $tabela:\n";
    $r = mysqli_query($conn, "SHOW COLUMNS FROM `$tabela`");
    $existentes = [];
    while ($c = mysqli_fetch_assoc($r)) {
        $existentes[$c["Field"]] = $c;
    }
    if (!isset($existentes["id"])) {
        echo "  ERRO: a tabela não tem a coluna 'id' (chave primária). Recrie a tabela com o database/db.sql.\n";
    } else {
        echo "  id: OK" . (stripos($existentes["id"]["Extra"], "auto_increment") !== false ? " (auto_increment)" : " — ATENÇÃO: sem AUTO_INCREMENT") . "\n";
    }
    foreach ($colunas as $nome => $def) {
        if (isset($existentes[$nome])) {
            echo "  $nome: OK\n";
        } else {
            mysqli_query($conn, "ALTER TABLE `$tabela` ADD COLUMN `$nome` $def");
            echo "  $nome: FALTAVA — coluna criada\n";
        }
    }
    echo "\n";
}
echo "Pronto.";
echo "</pre>";

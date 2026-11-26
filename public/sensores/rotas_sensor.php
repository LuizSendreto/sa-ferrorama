<?php
// Utilidades para garantir que cada sensor aponte para uma rota cadastrada.
function listarRotasSensor(mysqli $conn): array
{
    $resultado = $conn->query('SELECT id, nome_rota FROM rotas ORDER BY nome_rota ASC, id ASC');
    return $resultado->fetch_all(MYSQLI_ASSOC);
}

function rotaSensorExiste(mysqli $conn, int $rotaId): bool
{
    if ($rotaId <= 0) {
        return false;
    }
    $stmt = $conn->prepare('SELECT id FROM rotas WHERE id = ? LIMIT 1');
    $stmt->bind_param('i', $rotaId);
    $stmt->execute();
    $existe = $stmt->get_result()->fetch_assoc() !== null;
    $stmt->close();
    return $existe;
}

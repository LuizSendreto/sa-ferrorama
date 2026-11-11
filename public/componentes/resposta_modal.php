<?php
// Resposta opcional: os formulários antigos continuam funcionando sem JavaScript.
function strainRespostaModal(bool $ok, string $mensagem, int $status = 200): void
{
    if (($_POST['modal'] ?? '') !== '1') {
        return;
    }
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['ok' => $ok, 'mensagem' => $mensagem], JSON_UNESCAPED_UNICODE);
    exit;
}

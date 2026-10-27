<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Caminhos principais do sistema
$inicio = "/tironi_2026_m4/sa-ferrorama/index.php";
$homeUsuario = "/tironi_2026_m4/sa-ferrorama/public/usuarios/home_usuario.php";
$dashboard = "/tironi_2026_m4/sa-ferrorama/public/dashboard.php";

// Verificar se está logado
function verificarLogin() {
    global $inicio;

    if (!isset($_SESSION['usuario_id'])) {
        header("Location: " . $inicio);
        exit;
    }
}

// Permitir somente funcionários
function verificarFuncionario() {
    global $homeUsuario;

    verificarLogin();

    if (($_SESSION['tipo_usuario'] ?? '') != 'funcionario') {
        header("Location: " . $homeUsuario);
        exit;
    }
}

// Permitir somente administradores
function verificarAdmin() {
    global $dashboard;

    verificarFuncionario();

    if (($_SESSION['nivel_acesso'] ?? '') != 'admin') {
        header("Location: " . $dashboard);
        exit;
    }
}

?>
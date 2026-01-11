<?php
session_start();
include('../server/connection.php');

// Tempo máximo de inatividade em segundos (10 minutos)
$tempoLimite = 10 * 60; // 600 segundos

// Logout automático se ultrapassar o tempo de inatividade
if (isset($_SESSION['ultimo_acesso'])) {
    $inatividade = time() - $_SESSION['ultimo_acesso'];
    if ($inatividade > $tempoLimite) {
        session_unset();
        session_destroy();
        header("Location: login.php?timeout=1");
        exit;
    }
}

// Atualiza último acesso
$_SESSION['ultimo_acesso'] = time();

// Verifica se o administrador está logado
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>

<!-- Bootstrap CSS -->
<link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/dashboard/">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
body { font-size: .875rem; }
.sidebar { position: fixed; top: 0; bottom: 0; left: 0; z-index: 100; padding-top: 56px; width: 250px; background-color: #f8f9fa; }
.main-content { margin-left: 250px; padding: 20px; }
.navbar-brand { font-weight: bold; }
</style>
</head>
<body>

<!-- Navbar superior -->
<nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="index.php">
        <i class="bi bi-speedometer2"></i> Admin Dashboard
    </a>
    <div class="navbar-nav">
        <div class="nav-item text-nowrap">
            <!-- Botão Sair agora aponta para logout.php -->
            <a class="nav-link px-3 text-danger" href="logout.php">
                <i class="bi bi-box-arrow-right"></i> Sair
            </a>
        </div>
    </div>
</nav>

<!-- Menu lateral -->
<?php include('sidemenu.php'); ?>

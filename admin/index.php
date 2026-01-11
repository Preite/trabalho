

<?php
include('header.php'); // topo e conexão

// Verifica se a sessão ainda está ativa
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
?>

<div class="container-fluid">
    <div class="row">
        <?php include('sidemenu.php'); // menu lateral ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <span>Bem-vindo, <?= $_SESSION['admin_name'] ?>!</span>
                </div>
            </div>

            <p>Este é o painel administrativo. Aqui você pode gerenciar pedidos, produtos e usuários.</p>
        </main>
    </div>
</div>

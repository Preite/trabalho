<?php
include('header.php'); // Inclui sessão e conexão

// ===============================
// VALIDAÇÃO DE SESSÃO
// ===============================
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$error = "";
$success = "";

// ===============================
// EXCLUSÃO DE USUÁRIO
// ===============================
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $del = mysqli_query($conn, "DELETE FROM users WHERE user_id=$delete_id");
    if ($del) {
        $success = "Usuário #$delete_id excluído com sucesso!";
    } else {
        $error = "Erro ao excluir usuário #$delete_id.";
    }
}

// ===============================
// BUSCA DOS USUÁRIOS
// ===============================
$sql = "SELECT user_id, user_name, user_email FROM users ORDER BY user_id DESC";
$result = mysqli_query($conn, $sql);
?>

<div class="container-fluid">
    <div class="row">

        <!-- MENU LATERAL -->
        <?php include('sidemenu.php'); ?>

        <!-- CONTEÚDO PRINCIPAL -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h2 class="mb-4">Usuários Cadastrados</h2>

            <!-- MENSAGENS -->
            <?php if($error != ""): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <?php if($success != ""): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <?php if(mysqli_num_rows($result) == 0): ?>
                <div class="alert alert-info">Nenhum usuário encontrado.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($user = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?= $user['user_id'] ?></td>
                                    <td><?= htmlspecialchars($user['user_name']) ?></td>
                                    <td><?= htmlspecialchars($user['user_email']) ?></td>
                                    <td>
                                        <a href="users.php?delete=<?= $user['user_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este usuário?');">
                                            <i class="bi bi-trash"></i> Excluir
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

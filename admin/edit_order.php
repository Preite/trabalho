<?php
include('header.php'); // Inclui sessão e conexão

// Verifica se o admin está logado
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Verifica se o ID do pedido foi passado via GET
if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit;
}

$order_id = intval($_GET['id']); // Segurança: garante que é número

// Busca o pedido no banco, junto com dados do usuário
$sql = "SELECT o.*, u.user_name, u.user_email FROM orders o LEFT JOIN users u ON o.user_id = u.user_id WHERE o.order_id = $order_id";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) == 0) {
    echo "<p class='text-center mt-5'>Pedido não encontrado.</p>";
    exit;
}

$order = mysqli_fetch_assoc($result);

$error = "";
$success = "";

// Atualiza status quando o formulário é enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['order_status'];

    $allowed_status = ['on_hold','paid','shipped','delivered']; // Status permitidos

    if (in_array($status, $allowed_status)) {
        $update = mysqli_query($conn, "UPDATE orders SET order_status='$status' WHERE order_id=$order_id");

        if ($update) {
            $success = "Status atualizado com sucesso!";
            $order['order_status'] = $status; // Atualiza para exibir no form
        } else {
            $error = "Erro ao atualizar status.";
        }
    } else {
        $error = "Status inválido.";
    }
}

// Função para badges coloridas
function statusBadge($status){
    switch($status){
        case 'on_hold': return '<span class="badge bg-warning text-dark">Em análise</span>';
        case 'paid': return '<span class="badge bg-success">Pago</span>';
        case 'shipped': return '<span class="badge bg-primary">Enviado</span>';
        case 'delivered': return '<span class="badge bg-info text-dark">Entregue</span>';
        default: return '<span class="badge bg-secondary">Desconhecido</span>';
    }
}
?>

<div class="container mt-5">
    <div class="card shadow-lg mx-auto" style="max-width: 600px;">
        <div class="card-header bg-dark text-white">
            <h4 class="mb-0">Editar Pedido #<?= $order_id ?></h4>
        </div>
        <div class="card-body">

            <!-- Mensagens de erro ou sucesso -->
            <?php if($error != ""): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <?php if($success != ""): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <!-- Informações do pedido -->
            <div class="mb-4">
                <p><strong>Cliente:</strong> <?= $order['user_name'] ?> (<?= $order['user_email'] ?>)</p>
                <p><strong>Endereço:</strong> <?= $order['shipping_address'] ?> - <?= $order['shipping_city'] ?>/<?= $order['shipping_uf'] ?></p>
                <p><strong>Valor:</strong> R$ <?= number_format($order['order_cost'],2,',','.') ?></p>
                <p><strong>Data do pedido:</strong> <?= date('d/m/Y H:i',strtotime($order['order_date'])) ?></p>
                <p><strong>Status atual:</strong> <?= statusBadge($order['order_status']) ?></p>
            </div>

            <!-- Formulário para atualizar status -->
            <form method="POST">
                <div class="mb-3">
                    <label for="order_status" class="form-label">Alterar Status do Pedido</label>
                    <select name="order_status" id="order_status" class="form-select form-select-lg" required>
                        <option value="on_hold" <?= $order['order_status']=='on_hold'?'selected':'' ?>>Em análise</option>
                        <option value="paid" <?= $order['order_status']=='paid'?'selected':'' ?>>Pago</option>
                        <option value="shipped" <?= $order['order_status']=='shipped'?'selected':'' ?>>Enviado</option>
                        <option value="delivered" <?= $order['order_status']=='delivered'?'selected':'' ?>>Entregue</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success w-100 mb-2"><i class="bi bi-check-circle"></i> Atualizar Status</button>
                <a href="orders.php" class="btn btn-secondary w-100"><i class="bi bi-arrow-left-circle"></i> Voltar</a>
            </form>

        </div>
    </div>
</div>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

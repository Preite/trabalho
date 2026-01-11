<?php include('header.php'); ?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
<h2 class="mt-4">Pedidos</h2>

<?php
// Função para traduzir status e adicionar badge
function statusBadge($status){
    switch($status){
        case 'on_hold': return '<span class="badge bg-warning text-dark">Em análise</span>';
        case 'paid': return '<span class="badge bg-success">Pago</span>';
        case 'shipped': return '<span class="badge bg-primary">Enviado</span>';
        case 'delivered': return '<span class="badge bg-info text-dark">Entregue</span>';
        default: return '<span class="badge bg-secondary">Desconhecido</span>';
    }
}

// Exclusão de pedidos
if(isset($_GET['delete'])){
    $delete_id = intval($_GET['delete']);
    $del = mysqli_query($conn, "DELETE FROM orders WHERE order_id=$delete_id");
    if($del){
        echo "<div class='alert alert-success'>Pedido #$delete_id excluído com sucesso!</div>";
    } else {
        echo "<div class='alert alert-danger'>Erro ao excluir pedido #$delete_id.</div>";
    }
}

// PAGINAÇÃO
$itensPorPagina = 5;
$paginaAtual = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$paginaAtual = max($paginaAtual, 1);
$offset = ($paginaAtual - 1) * $itensPorPagina;

// Contar total de pedidos
$totalResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders");
$totalRow = mysqli_fetch_assoc($totalResult);
$totalPedidos = $totalRow['total'];
$totalPaginas = ceil($totalPedidos / $itensPorPagina);

// Buscar pedidos da página atual
$sql = "SELECT o.order_id, o.order_cost, o.order_status, o.shipping_city, o.shipping_uf, o.shipping_address, o.order_date,
       u.user_name, u.user_email
FROM orders o
LEFT JOIN users u ON o.user_id = u.user_id
ORDER BY o.order_date DESC
LIMIT $itensPorPagina OFFSET $offset";
$result = mysqli_query($conn, $sql);
?>

<div class="table-responsive">
<table class="table table-striped table-bordered mt-3">
<thead class="table-dark">
<tr>
<th>ID</th><th>Cliente</th><th>Email</th><th>Cidade</th><th>UF</th><th>Endereço</th><th>Status</th><th>Valor</th><th>Data</th><th>Ações</th>
</tr>
</thead>
<tbody>
<?php if(mysqli_num_rows($result) > 0): ?>
<?php while($order=mysqli_fetch_assoc($result)): ?>
<tr>
<td><?= $order['order_id'] ?></td>
<td><?= $order['user_name'] ?></td>
<td><?= $order['user_email'] ?></td>
<td><?= $order['shipping_city'] ?></td>
<td><?= $order['shipping_uf'] ?></td>
<td><?= $order['shipping_address'] ?></td>
<td><?= statusBadge($order['order_status']) ?></td> <!-- Aqui a mudança -->
<td>R$ <?= number_format($order['order_cost'],2,',','.') ?></td>
<td><?= date('d/m/Y H:i',strtotime($order['order_date'])) ?></td>
<td>
<a href="edit_order.php?id=<?= $order['order_id'] ?>" class="btn btn-sm btn-primary">Editar</a>
<a href="orders.php?delete=<?= $order['order_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Excluir</a>
</td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
<td colspan="10" class="text-center">Nenhum pedido encontrado.</td>
</tr>
<?php endif; ?>
</tbody>
</table>
</div>

<!-- Paginação -->
<nav>
    <ul class="pagination justify-content-center">
        <!-- Botão Anterior -->
        <li class="page-item <?= ($paginaAtual <= 1) ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $paginaAtual-1 ?>">Anterior</a>
        </li>

        <!-- Links das páginas -->
        <?php for($i = 1; $i <= $totalPaginas; $i++): ?>
            <li class="page-item <?= ($i == $paginaAtual) ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <!-- Botão Próximo -->
        <li class="page-item <?= ($paginaAtual >= $totalPaginas) ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $paginaAtual+1 ?>">Próximo</a>
        </li>
    </ul>
</nav>
</main>

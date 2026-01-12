<?php
session_start();

// Verifica se o ID do pedido está salvo na sessão
if (!isset($_SESSION['order_id'])) {
    header("Location: index.php");  // Se não houver ID do pedido, redireciona para a página inicial
    exit;
}

// Recupera o ID do pedido
$order_id = $_SESSION['order_id'];

// Inclui a conexão com o banco de dados
include('server/connection.php');

// Recupera os dados do pedido
$query = "SELECT * FROM orders WHERE order_id = '$order_id' LIMIT 1";
$result = mysqli_query($conn, $query);
$order = mysqli_fetch_assoc($result);

// Recupera os itens do pedido
$item_query = "SELECT * FROM order_items WHERE order_id = '$order_id'";
$item_result = mysqli_query($conn, $item_query);

// Inclui o topo (header)
include('layouts/header.php');
?>

<section class="container my-5">
    <h2 class="mb-4 text-center">Pedido Confirmado</h2>

    <div class="alert alert-success text-center mb-4">
        <h5>Seu pedido foi confirmado com sucesso!</h5>
        <p>Detalhes do Pedido: #<?= $order['order_id'] ?></p>
        <p>Total: R$ <?= number_format($order['order_total'], 2, ',', '.') ?></p>
    </div>

    <h4>Detalhes de Entrega</h4>
    <p><strong>UF:</strong> <?= $order['order_uf'] ?></p>
    <p><strong>Cidade:</strong> <?= $order['order_city'] ?></p>
    <p><strong>Endereço:</strong> <?= $order['order_address'] ?></p>

    <h4>Itens do Pedido</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Produto</th>
                <th>Preço</th>
                <th>Quantidade</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($item = mysqli_fetch_assoc($item_result)): ?>
                <tr>
                    <td><?= $item['product_name'] ?></td>
                    <td>R$ <?= number_format($item['product_price'], 2, ',', '.') ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>R$ <?= number_format($item['product_price'] * $item['quantity'], 2, ',', '.') ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</section>

<!-- Incluindo o rodapé (footer) -->
<?php include('layouts/footer.php'); ?>


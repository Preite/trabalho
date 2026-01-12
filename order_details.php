<?php
session_start();  // Inicia a sessão
include('layouts/header.php');  // Inclui o topo da página

// Verifica se o pedido está sendo passado via POST
if (!isset($_POST['order_id'])) {
    echo "<p>Pedido não encontrado.</p>";
    exit;
}

$order_id = $_POST['order_id'];  // Obtém o ID do pedido passado por POST

// Conectando com o banco de dados
include('server/connection.php');

// Consulta para obter os detalhes do pedido
$sql = "SELECT o.order_id, o.order_status, o.order_cost, o.shipping_city, o.shipping_uf, o.shipping_address, p.product_name, p.product_price, oi.qnt 
        FROM orders o
        JOIN order_items oi ON o.order_id = oi.order_id
        JOIN products p ON oi.product_id = p.product_id
        WHERE o.order_id = $order_id";

$result = mysqli_query($conn, $sql);

// Verifica se o pedido existe
if (mysqli_num_rows($result) == 0) {
    echo "<p>Detalhes do pedido não encontrados.</p>";
    exit;
}

$order_details = [];
while ($row = mysqli_fetch_assoc($result)) {
    $order_details[] = $row;  // Preenche o array com os dados do pedido
}

// Verificar o status do pedido (para exibir o botão de pagamento se for "not paid")
$order_status = $order_details[0]['order_status'];

?>

<!-- Página de Detalhes do Pedido -->
<section class="container my-5">
    <h2 class="mb-4 text-center">Detalhes do Pedido</h2>

    <!-- Exibe os detalhes do pedido -->
    <div class="alert alert-info text-center mb-4">
        <h5>Pedido #<?= $order_id ?></h5>
        <p>Status do Pedido: <strong><?= $order_status == 'not paid' ? 'Não Pago' : 'Pago' ?></strong></p>
        <p>Cidade: <?= $order_details[0]['shipping_city'] ?></p>
        <p>UF: <?= $order_details[0]['shipping_uf'] ?></p>
        <p>Endereço: <?= $order_details[0]['shipping_address'] ?></p>
        <p><strong>Total do Pedido: R$ <?= number_format($order_details[0]['order_cost'], 2, ',', '.') ?></strong></p>
    </div>

    <!-- Tabela com os itens do pedido -->
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
            <?php
            $total_price = 0;
            foreach ($order_details as $item) {
                $subtotal = $item['product_price'] * $item['qnt'];
                $total_price += $subtotal;
                ?>
                <tr>
                    <td><?= $item['product_name'] ?></td>
                    <td>R$ <?= number_format($item['product_price'], 2, ',', '.') ?></td>
                    <td><?= $item['qnt'] ?></td>
                    <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Se o pedido não foi pago, exibe o formulário para pagamento -->
    <?php if ($order_status == 'not paid'): ?>
        <form action="payment.php" method="POST">
            <!-- Formulário oculto com os dados do pedido -->
            <input type="hidden" name="order_id" value="<?= $order_id ?>">
            <input type="hidden" name="order_cost" value="<?= $total_price ?>">
            <button type="submit" class="btn btn-success w-100 mb-2">Pague Agora</button>
        </form>
    <?php endif; ?>
</section>

<!-- Incluindo o rodapé (footer) -->
<?php include('layouts/footer.php'); ?>

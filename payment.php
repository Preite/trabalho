<?php
session_start();  // Inicia a sessão

// Verificar se o usuário está logado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");  // Redireciona para login se não estiver logado
    exit;
}

// Verificar se há um pedido em andamento
if (!isset($_SESSION['order_id']) || $_SESSION['order_id'] == "") {
    echo "<p>Não há pedido em andamento. Por favor, adicione itens ao carrinho e finalize a compra.</p>";
    exit;
}

// Recupera o ID do pedido
$order_id = $_SESSION['order_id'];

// Conecta com o banco de dados
include('server/connection.php');

// Recupera os dados do pedido
$sql = "SELECT order_total FROM orders WHERE order_id = $order_id AND order_status = 'not paid'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    echo "<p>Pedido não encontrado ou já pago.</p>";
    exit;
}

$order = mysqli_fetch_assoc($result);
$amount = $order['order_total'];  // Total do pedido
?>

<!-- Incluindo o topo (header) -->
<?php include('layouts/header.php'); ?>

<!-- Página de pagamento -->
<section class="container my-5">
    <h2 class="text-center mb-4">Pagamento</h2>
    
    <!-- Exibe o total do pedido -->
    <div class="alert alert-info text-center mb-4">
        <h5>Total a Pagar: R$ <?= number_format($amount, 2, ',', '.') ?></h5>
    </div>

    <!-- Botões do PayPal -->
    <div id="paypal-button-container" class="text-center"></div>

</section>

<!-- PayPal SDK -->
<script src="https://www.paypal.com/sdk/js?client-id=AZcFI4rZeHJ8Pcz_IZOQJkJEAaVjXQ2Qa_X8yDICm32_SKBcJpf00TFXC0fenmpY5PH7xs7LgB75lUgA&currency=BRL"></script>

<script>
paypal.Buttons({
    // Configura a transação quando o botão de pagamento é clicado
    createOrder: function(data, actions) {
        return actions.order.create({
            purchase_units: [{
                amount: {
                    value: '<?php echo $amount; ?>'
                }
            }]
        });
    },
    // Finaliza a transação após a aprovação do pagador
    onApprove: function(data, actions) {
        return actions.order.capture().then(function(orderData) {
            // Exibe detalhes da transação (útil para teste)
            console.log('Resultado da transação', orderData, JSON.stringify(orderData, null, 2));
            var transaction = orderData.purchase_units[0].payments.captures[0];
            alert('Transação '+ transaction.status + ': ' + transaction.id + '\n\nVeja o console para detalhes.');
            window.location.href = "server/complete_payment.php?transaction_id=" + transaction.id + "&order_id=" + <?php echo $order_id; ?>;
        });
    }
}).render('#paypal-button-container');
</script>

<!-- Incluindo o rodapé (footer) -->
<?php include('layouts/footer.php'); ?>

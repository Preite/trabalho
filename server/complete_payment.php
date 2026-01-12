<?php
session_start();  // Inicia a sessão

// Verificar se os parâmetros necessários estão presentes na URL
if (!isset($_GET['transaction_id']) || !isset($_GET['order_id'])) {
    echo "<p>Erro: Dados da transação não recebidos corretamente.</p>";
    exit;
}

$transaction_id = $_GET['transaction_id'];
$order_id = $_GET['order_id'];

// Incluir a conexão com o banco de dados
include('connection.php');  // Ajuste o caminho conforme necessário

// Verificar se o pedido existe e se ainda está marcado como 'not paid'
$sql = "SELECT order_status FROM orders WHERE order_id = $order_id AND order_status = 'not paid'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    echo "<p>Pedido não encontrado ou já foi pago.</p>";
    exit;
}

// Atualizar o status do pedido para 'paid'
$update_order_query = "UPDATE orders SET order_status = 'paid' WHERE order_id = $order_id";
if (mysqli_query($conn, $update_order_query)) {
    // Inserir um registro de pagamento na tabela 'payments'
    $user_id = $_SESSION['user_id'];  // Certifique-se de que o user_id está na sessão
    $insert_payment_query = "INSERT INTO payments (order_id, user_id, transaction_id) 
                             VALUES ($order_id, $user_id, '$transaction_id')";
    if (mysqli_query($conn, $insert_payment_query)) {
        echo "<p>Pagamento confirmado! Seu pedido foi realizado com sucesso.</p>";
    } else {
        echo "<p>Erro ao registrar o pagamento.</p>";
    }
} else {
    echo "<p>Erro ao atualizar o status do pedido.</p>";
}

?>

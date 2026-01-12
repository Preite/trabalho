<?php
session_start();  // Inicia a sessão

// Conectar com o banco de dados
include('server/connection.php');

// Verifica se o carrinho está vazio
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: index.php");  // Redireciona para a página inicial
    exit;
}

// Calcula o total do pedido
$order_total = 0;
foreach ($_SESSION['cart'] as $product) {
    $order_total += $product['product_price'] * $product['product_quantity'];
}

// Recebe as informações do formulário de entrega
$uf = $_POST['uf'];
$city = $_POST['city'];
$address = $_POST['address'];

// Verifica se as informações estão preenchidas corretamente
if (empty($uf) || empty($city) || empty($address)) {
    $_SESSION['error_msg'] = "Por favor, preencha todos os campos!";
    header("Location: checkout.php");
    exit;
}

// Inserir o pedido na tabela 'orders'
$query = "INSERT INTO orders (order_cost, order_status, user_id, shipping_city, shipping_uf, shipping_address, order_date, order_total)
          VALUES ('$order_total', 'not paid', '{$_SESSION['user_id']}', '$city', '$uf', '$address', NOW(), '$order_total')";

if (mysqli_query($conn, $query)) {
    $order_id = mysqli_insert_id($conn);  // Pega o ID do pedido recém-criado

    // Inserir itens do pedido na tabela 'order_items'
    foreach ($_SESSION['cart'] as $product) {
        $product_id = $product['product_id'];
        $quantity = $product['product_quantity'];

        $insert_item_query = "INSERT INTO order_items (order_id, product_id, user_id, qnt, order_date)
                              VALUES ('$order_id', '$product_id', '{$_SESSION['user_id']}', '$quantity', NOW())";
        mysqli_query($conn, $insert_item_query);
    }

    // Após inserir, limpa o carrinho e redireciona para a página de pagamento
    unset($_SESSION['cart']);
    $_SESSION['order_id'] = $order_id;  // Armazena o ID do pedido na sessão
    header("Location: payment.php");  // Redireciona para a página de pagamento
    exit;

} else {
    $_SESSION['error_msg'] = "Erro ao processar o pedido. Tente novamente mais tarde.";
    header("Location: checkout.php");
    exit;
}
?>

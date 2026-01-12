<?php
session_start();  // Inicia a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $_SESSION['error_msg'] = "Você precisa estar logado para realizar a compra.";
    header("Location: checkout.php");  // Redireciona para a página de checkout
    exit;
}

// Verifica se o carrinho está vazio
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    $_SESSION['error_msg'] = "Seu carrinho está vazio.";
    header("Location: checkout.php");
    exit;
}

// Inclui a conexão com o banco de dados
include('server/connection.php');

// Recupera as informações do carrinho e do usuário
$user_id = $_SESSION['user_id'];
$order_info = $_SESSION['order_info'];
$cart = $_SESSION['cart'];

// Calcula o total do pedido
$total = 0;
foreach ($cart as $product) {
    $total += $product['product_price'] * $product['product_quantity'];
}

// Insere o pedido na tabela "orders"
$query = "INSERT INTO orders (user_id, order_total, order_uf, order_city, order_address, order_date) 
          VALUES ('$user_id', '$total', '{$order_info['uf']}', '{$order_info['city']}', '{$order_info['address']}', NOW())";

if (mysqli_query($conn, $query)) {
    // Recupera o ID do pedido recém inserido
    $order_id = mysqli_insert_id($conn);

    // Insere os itens do pedido na tabela "order_items"
    foreach ($cart as $product) {
        $product_id = $product['product_id'];
        $product_name = $product['product_name'];
        $product_price = $product['product_price'];
        $product_quantity = $product['product_quantity'];

        $order_item_query = "INSERT INTO order_items (order_id, product_id, product_name, product_price, quantity)
                             VALUES ('$order_id', '$product_id', '$product_name', '$product_price', '$product_quantity')";
        mysqli_query($conn, $order_item_query);
    }

    // Salva o ID do pedido na sessão
    $_SESSION['order_id'] = $order_id;

    // Limpa o carrinho após o pedido ser salvo
    unset($_SESSION['cart']);
    unset($_SESSION['order_info']);

    // Redireciona para a página de confirmação do pedido
    header("Location: order_confirmation.php");  // Página de confirmação
    exit;

} else {
    $_SESSION['error_msg'] = "Erro ao processar o pedido. Tente novamente mais tarde.";
    header("Location: checkout.php");
    exit;
}


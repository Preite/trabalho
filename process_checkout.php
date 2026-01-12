<?php
session_start();  // Inicia a sessão

// Verifica se o carrinho está vazio, caso contrário, redireciona para a página inicial
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: index.php");  // Redireciona para a página inicial
    exit;
}

// Verifica se o formulário foi enviado corretamente
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recebe as informações do formulário
    $uf = $_POST['uf'];
    $city = $_POST['city'];
    $address = $_POST['address'];

    // Verifica se as informações estão preenchidas corretamente
    if (empty($uf) || empty($city) || empty($address)) {
        $_SESSION['error_msg'] = "Por favor, preencha todos os campos!";
        header("Location: checkout.php");
        exit;
    }

    // Salva as informações de entrega na sessão (para uso futuro, como em um sistema de pedidos)
    $_SESSION['order_info'] = [
        'uf' => $uf,
        'city' => $city,
        'address' => $address
    ];

    // Redireciona para a página de confirmação ou onde você desejar
    header("Location: order_confirmation.php");  // Exemplo de redirecionamento
    exit;
} else {
    $_SESSION['error_msg'] = "Erro no envio do formulário.";
    header("Location: checkout.php");
    exit;
}


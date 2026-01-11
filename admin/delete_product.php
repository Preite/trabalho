<?php
include('header.php'); // Inclui sessão e conexão

// ===============================
// VALIDAÇÃO DE SESSÃO (ADMIN)
// ===============================
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// ===============================
// VERIFICA ID DO PRODUTO
// ===============================ss
if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit;
}

$product_id = intval($_GET['id']); // Segurança: garante que seja número

// ===============================
// EXCLUSÃO DO PRODUTO
// ===============================
$delete = mysqli_query($conn, "DELETE FROM products WHERE product_id=$product_id");

if ($delete) {
    $_SESSION['success'] = "Produto #$product_id excluído com sucesso!";
} else {
    $_SESSION['error'] = "Erro ao excluir o produto #$product_id.";
}

// Redireciona de volta para a página de produtos
header("Location: products.php");
exit;

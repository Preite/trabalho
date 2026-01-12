<?php
session_start();  // Inicia a sessão para usar o carrinho

// Incluindo o topo (header)
include('layouts/header.php');

// Conectando com o banco de dados
include('server/connection.php');

// Inicializando o carrinho se não existir
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Adicionar produto ao carrinho (se o produto for enviado pelo link)
if (isset($_GET['add_to_cart'])) {
    $product_id = $_GET['add_to_cart'];
    $quantity = isset($_GET['quantity']) ? $_GET['quantity'] : 1; // Pega a quantidade, se não definida coloca 1

    // Consultar o produto no banco de dados
    $query = "SELECT * FROM products WHERE product_id = '$product_id' LIMIT 1";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);

        // Criar array do produto
        $product_array = array(
            'product_id' => $product['product_id'],
            'product_name' => $product['product_name'],
            'product_price' => $product['product_price'],
            'product_image' => $product['product_image'],
            'product_quantity' => $quantity  // Usando a quantidade enviada pelo formulário
        );

        // Se o produto já existe no carrinho, incrementar a quantidade
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['product_quantity'] += $quantity;
        } else {
            // Caso contrário, adicionar o produto ao carrinho
            $_SESSION['cart'][$product_id] = $product_array;
        }

        echo "<script>alert('Produto adicionado ao carrinho!'); window.location.href='cart.php';</script>";
    }
}

// Lógica para remover um produto do carrinho
if (isset($_GET['remove_from_cart'])) {
    $product_id = $_GET['remove_from_cart'];

    // Verifica se o produto está no carrinho
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);  // Remove o produto
        echo "<script>alert('Produto removido do carrinho.'); window.location.href='cart.php';</script>";
    }
}

// Atualizar a quantidade do produto no carrinho
if (isset($_POST['update_quantity'])) {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['product_quantity'] = max(1, intval($quantity)); // Garantir quantidade mínima de 1
        }
    }
    echo "<script>window.location.href='cart.php';</script>";
}

// Função para calcular o total do carrinho
function calcularTotal() {
    $total = 0;
    $total_quantity = 0;
    foreach ($_SESSION['cart'] as $product) {
        $subtotal = $product['product_price'] * $product['product_quantity'];
        $total += $subtotal; // Subtotal de cada produto
        $total_quantity += $product['product_quantity']; // Total de produtos no carrinho
    }
    
    // Atualizando as variáveis de total e quantidade no carrinho
    $_SESSION['total'] = $total;  // Total do carrinho
    $_SESSION['quantity'] = $total_quantity;  // Quantidade total de itens no carrinho
    
    return $total;
}

?>

<!-- Página do Carrinho -->
<section class="container my-5">
    <h2 class="mb-4 text-center">Carrinho de Compras</h2>
    
    <!-- Verifica se o carrinho está vazio -->
    <?php if (empty($_SESSION['cart'])): ?>
        <p class="text-center">Seu carrinho está vazio.</p>
    <?php else: ?>
        <!-- Formulário para atualizar as quantidades -->
        <form method="POST" action="cart.php">
            <!-- Lista dos produtos no carrinho -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Imagem</th>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Subtotal</th>
                        <th>Editar</th>
                        <th>Excluir</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_price = 0;
                    foreach ($_SESSION['cart'] as $product) {
                        $subtotal = $product['product_price'] * $product['product_quantity'];
                        $total_price += $subtotal;
                    ?>
                        <tr>
                            <td><img src="assets/imgs/<?= $product['product_image'] ?>" alt="<?= $product['product_name'] ?>" width="100"></td>
                            <td><?= $product['product_name'] ?></td>
                            <td>
                                <!-- Campo de quantidade editável -->
                                <input type="number" name="quantity[<?= $product['product_id'] ?>]" value="<?= $product['product_quantity'] ?>" min="1" class="form-control" style="width: 60px;">
                            </td>
                            <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                            <td>
                                <!-- Botão para editar (mantém a funcionalidade da quantidade editável) -->
                                <a href="cart.php?edit_quantity=<?= $product['product_id'] ?>" class="btn btn-primary btn-sm">Editar Quantidade</a>
                            </td>
                            <td>
                                <!-- Botão de exclusão -->
                                <a href="cart.php?remove_from_cart=<?= $product['product_id'] ?>" class="btn btn-danger btn-sm">Excluir</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            
            <!-- Botão para atualizar as quantidades -->
            <button type="submit" name="update_quantity" class="btn btn-warning">Atualizar Quantidades</button>
        </form>

        <!-- Total e Botões -->
        <div class="d-flex justify-content-between">
            <h4>Total: R$ <?= number_format(calcularTotal(), 2, ',', '.') ?></h4>
            <a href="checkout.php" class="btn btn-success">Finalizar Compra</a>
        </div>
    <?php endif; ?>
</section>

<!-- Incluindo o rodapé (footer) -->
<?php include('layouts/footer.php'); ?> 

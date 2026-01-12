<?php
session_start();  // Inicia a sessão

// Verifica se o carrinho está vazio, caso contrário, redireciona para a página inicial
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: index.php");  // Redireciona para a página inicial
    exit;
}

// Calcula o total do pedido
$total = 0;
foreach ($_SESSION['cart'] as $product) {
    $total += $product['product_price'] * $product['product_quantity'];
}

?>

<!-- Incluindo o topo (header) -->
<?php include('layouts/header.php'); ?>

<!-- Formulário de Checkout -->
<section class="container my-5">
    <h2 class="mb-4 text-center">Finalizar Compra</h2>

    <!-- Exibe o total do pedido -->
    <div class="alert alert-info text-center mb-4">
        <h5>Total do Pedido: R$ <?= number_format($total, 2, ',', '.') ?></h5>
    </div>

    <div class="card shadow-lg mx-auto" style="max-width: 600px;">
        <div class="card-header bg-dark text-white text-center">
            <h4>Informações de Entrega</h4>
        </div>
        <div class="card-body">
            <!-- Formulário de entrega -->
            <form method="POST" action="process_checkout.php">

                <!-- Campo UF -->
                <div class="mb-3">
                    <label for="uf" class="form-label">UF</label>
                    <select class="form-control" id="uf" name="uf" required>
                        <option value="">Selecione o Estado</option>
                        <option value="SP">São Paulo</option>
                        <option value="RJ">Rio de Janeiro</option>
                        <option value="MG">Minas Gerais</option>
                        <option value="RS">Rio Grande do Sul</option>
                        <option value="PR">Paraná</option>
                        <option value="SC">Santa Catarina</option>
                    </select>
                </div>

                <!-- Campo Cidade -->
                <div class="mb-3">
                    <label for="city" class="form-label">Cidade</label>
                    <input type="text" class="form-control" id="city" name="city" required>
                </div>

                <!-- Campo Endereço -->
                <div class="mb-3">
                    <label for="address" class="form-label">Endereço</label>
                    <input type="text" class="form-control" id="address" name="address" required>
                </div>

                <!-- Botão de finalizar -->
                <button type="submit" class="btn btn-success w-100 mb-2">Finalizar Compra</button>
            </form>
        </div>
    </div>
</section>

<!-- Incluindo o rodapé (footer) -->
<?php include('layouts/footer.php'); ?>

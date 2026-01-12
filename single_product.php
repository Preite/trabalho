<?php
include('layouts/header.php');  // Inclui o topo da página
include('server/connection.php');  // Inclui a conexão com o banco de dados

// Verificar se o ID do produto foi passado na URL
if (!isset($_GET['id'])) {
    echo "<p>Produto não encontrado.</p>";
    exit;
}

$product_id = intval($_GET['id']);  // Garantir que o ID seja um número inteiro

// Consulta para obter os dados do produto
$sql = "SELECT * FROM products WHERE product_id = $product_id";
$result = mysqli_query($conn, $sql);

// Verificar se o produto existe
if (mysqli_num_rows($result) == 0) {
    echo "<p>Produto não encontrado.</p>";
    exit;
}

$product = mysqli_fetch_assoc($result);
?>

<section class="container my-5">
    <div class="row">
        <!-- Coluna da imagem principal -->
        <div class="col-md-6">
            <div class="main-image">
                <!-- Exibindo a imagem principal do produto -->
                <img id="mainImage" src="<?= 'assets/imgs/' . htmlspecialchars($product['product_image']) ?>" 
                     alt="<?= htmlspecialchars($product['product_name']) ?>" class="img-fluid mb-4" style="height: 450px; object-fit: cover;">
            </div>

            <!-- Exibindo as imagens em miniatura -->
            <div class="row mt-2">
                <div class="col-3">
                    <img src="<?= 'assets/imgs/' . htmlspecialchars($product['product_image']) ?>" 
                         alt="Imagem Principal" class="img-thumbnail" onclick="changeImage('<?= 'assets/imgs/' . htmlspecialchars($product['product_image']) ?>')">
                </div>
                <div class="col-3">
                    <img src="<?= 'assets/imgs/' . htmlspecialchars($product['product_image2']) ?>" 
                         alt="Imagem 2" class="img-thumbnail" onclick="changeImage('<?= 'assets/imgs/' . htmlspecialchars($product['product_image2']) ?>')">
                </div>
                <div class="col-3">
                    <img src="<?= 'assets/imgs/' . htmlspecialchars($product['product_image3']) ?>" 
                         alt="Imagem 3" class="img-thumbnail" onclick="changeImage('<?= 'assets/imgs/' . htmlspecialchars($product['product_image3']) ?>')">
                </div>
                <div class="col-3">
                    <img src="<?= 'assets/imgs/' . htmlspecialchars($product['product_image4']) ?>" 
                         alt="Imagem 4" class="img-thumbnail" onclick="changeImage('<?= 'assets/imgs/' . htmlspecialchars($product['product_image4']) ?>')">
                </div>
            </div>
        </div>

        <!-- Coluna de detalhes do produto -->
        <div class="col-md-6">
            <div class="product-details">
                <h3><?= htmlspecialchars($product['product_name']) ?></h3>
                <p class="product-description"><?= nl2br(htmlspecialchars($product['product_description'])) ?></p>
                <p class="product-price">R$ <?= number_format($product['product_price'], 2, ',', '.') ?></p>

                <!-- Formulário para adicionar ao carrinho (alterado para enviar para cart.php) -->
                <form action="cart.php" method="GET">
                    <input type="hidden" name="add_to_cart" value="<?= $product['product_id'] ?>">
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantidade</label>
                        <input type="number" id="quantity" name="quantity" class="form-control" min="1" value="1" required>
                    </div>
                    <button type="submit" class="btn btn-danger">Adicionar ao Carrinho</button>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    // Função para trocar a imagem principal ao clicar nas miniaturas
    function changeImage(imageSrc) {
        const mainImage = document.getElementById('mainImage');  // Acesso à imagem principal
        mainImage.src = imageSrc;  // Atualiza a imagem principal com a nova imagem
    }
</script>

<?php
include('layouts/footer.php');  // Inclui o rodapé da página
?>

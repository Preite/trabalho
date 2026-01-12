<?php
include('layouts/header.php');  // Inclui o topo da página
include('server/connection.php');  // Inclui a conexão com o banco de dados

// Definir o número de itens por página
$itensPorPagina = 8;

// Obter a página atual da URL, se não existir, definir como página 1
$paginaAtual = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$paginaAtual = max($paginaAtual, 1);

// Calcular o OFFSET para a consulta SQL (onde começar a buscar os produtos)
$offset = ($paginaAtual - 1) * $itensPorPagina;

// Consulta para obter todos os produtos com LIMIT e OFFSET para paginação
$sql = "SELECT product_id, product_name, product_price, product_image FROM products ORDER BY product_name LIMIT $itensPorPagina OFFSET $offset";
$result = mysqli_query($conn, $sql);

// Verificar se a consulta foi bem-sucedida
if ($result === false) {
    die('Erro na consulta: ' . mysqli_error($conn));
}

// Contar o número total de produtos para calcular o número de páginas
$totalProdutosQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM products");
$totalProdutosResult = mysqli_fetch_assoc($totalProdutosQuery);
$totalProdutos = $totalProdutosResult['total'];
$totalPaginas = ceil($totalProdutos / $itensPorPagina);
?>

<section class="container my-5">
    <h1 class="mb-4">Produtos</h1>
    <div class="row g-4">

        <?php if (mysqli_num_rows($result) == 0): ?>
            <p>Nenhum produto encontrado.</p>
        <?php else: ?>
            <?php while ($product = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-3">
                    <div class="product-card border rounded p-3 cursor-pointer" 
                         onclick="window.location.href='single_product.php?id=<?= $product['product_id'] ?>'">
                        <img src="<?= !empty($product['product_image']) ? 'assets/imgs/' . htmlspecialchars($product['product_image']) : 'https://via.placeholder.com/250x250?text=Sem+Imagem' ?>" 
                             alt="<?= htmlspecialchars($product['product_name']) ?>" class="img-fluid mb-3" style="height: 250px; object-fit: cover;">
                        <h5 class="product-name"><?= htmlspecialchars($product['product_name']) ?></h5>
                        <p class="product-price text-primary fw-bold">R$ <?= number_format($product['product_price'], 2, ',', '.') ?></p>
                        <a href="single_product.php?id=<?= $product['product_id'] ?>" class="btn btn-sm btn-outline-primary w-100">Ver Detalhes</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>

    </div>

    <!-- Paginação -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <!-- Botão "Anterior" -->
            <li class="page-item <?= ($paginaAtual <= 1) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $paginaAtual - 1 ?>">Anterior</a>
            </li>

            <!-- Links das páginas -->
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <li class="page-item <?= ($i == $paginaAtual) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <!-- Botão "Próxima" -->
            <li class="page-item <?= ($paginaAtual >= $totalPaginas) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $paginaAtual + 1 ?>">Próxima</a>
            </li>
        </ul>
    </nav>

</section>

<?php
include('layouts/footer.php');  // Inclui o rodapé da página
?>

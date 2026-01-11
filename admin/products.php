<?php
include('header.php');


// VALIDAÇÃO DE SESSÃO (ADMIN)

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}


// EXCLUSÃO DE PRODUTO

if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $del = mysqli_query($conn, "DELETE FROM products WHERE product_id=$delete_id");
    if ($del) {
        $_SESSION['success'] = "Produto #$delete_id excluído com sucesso!";
    } else {
        $_SESSION['error'] = "Erro ao excluir o produto #$delete_id.";
    }
    header("Location: products.php");
    exit;
}


// PAGINAÇÃO

$itensPorPagina = 5;
$paginaAtual = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$paginaAtual = max($paginaAtual, 1);
$offset = ($paginaAtual - 1) * $itensPorPagina;

// Contar total de produtos
$totalResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM products");
$totalRow = mysqli_fetch_assoc($totalResult);
$totalProdutos = $totalRow['total'];
$totalPaginas = ceil($totalProdutos / $itensPorPagina);

// Buscar produtos da página atual
$sql = "SELECT * FROM products ORDER BY product_id ASC LIMIT $itensPorPagina OFFSET $offset";
$result = mysqli_query($conn, $sql);
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
    <h2 class="mt-4">Produtos</h2>

    <!-- Mensagens de sucesso ou erro -->
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-striped table-bordered mt-3 align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Imagem</th>
                    <th>Nome</th>
                    <th>Preço</th>
                    <th>Oferta</th>
                    <th>Categoria</th>
                    <th>Cor</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($product = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $product['product_id']; ?></td>
                            <td>
                                <img src="../assets/imgs/<?= htmlspecialchars($product['product_image']); ?>" width="50" alt="<?= htmlspecialchars($product['product_name']); ?>">
                            </td>
                            <td><?= htmlspecialchars($product['product_name']); ?></td>
                            <td>R$ <?= number_format($product['product_price'], 2, '.', ','); ?></td>
                            <td><?= $product['product_special_offer'] ? 'Sim' : 'Não'; ?></td>
                            <td><?= htmlspecialchars($product['product_category']); ?></td>
                            <td><?= htmlspecialchars($product['product_color']); ?></td>
                            <td>
                                <a href="edit_images.php?id=<?= $product['product_id']; ?>" class="btn btn-sm btn-warning">Editar imagens</a>
                                <a href="edit_products.php?id=<?= $product['product_id']; ?>" class="btn btn-sm btn-primary">Editar produto</a>
                                <a href="products.php?delete=<?= $product['product_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este produto?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">Nenhum produto encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    <nav>
        <ul class="pagination justify-content-center">
            <li class="page-item <?= ($paginaAtual <= 1) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $paginaAtual - 1 ?>">Anterior</a>
            </li>

            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <li class="page-item <?= ($i == $paginaAtual) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <li class="page-item <?= ($paginaAtual >= $totalPaginas) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $paginaAtual + 1 ?>">Próximo</a>
            </li>
        </ul>
    </nav>
</main>

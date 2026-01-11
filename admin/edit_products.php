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
// ===============================
if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit;
}

$product_id = intval($_GET['id']); // Segurança

// ===============================
// BUSCA O PRODUTO NO BANCO
// ===============================
$sql = "SELECT * FROM products WHERE product_id = $product_id";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) == 0){
    echo "<p class='text-center mt-5'>Produto não encontrado.</p>";
    exit;
}

$product = mysqli_fetch_assoc($result);

$error = "";
$success = "";

// ===============================
// ATUALIZA PRODUTO QUANDO FORMULÁRIO É ENVIADO
// ===============================
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name        = mysqli_real_escape_string($conn, $_POST['product_name']);
    $description = mysqli_real_escape_string($conn, $_POST['product_description']);
    $price       = floatval($_POST['product_price']);
    $category    = mysqli_real_escape_string($conn, $_POST['product_category']);
    $color       = mysqli_real_escape_string($conn, $_POST['product_color']);
    $special     = isset($_POST['product_special_offer']) ? 1 : 0;

    $update = mysqli_query($conn, "UPDATE products SET 
        product_name='$name', 
        product_description='$description', 
        product_price=$price, 
        product_category='$category', 
        product_color='$color', 
        product_special_offer=$special 
        WHERE product_id=$product_id");

    if($update){
        $success = "Produto atualizado com sucesso!";
        // Atualiza variáveis para exibir no formulário
        $product['product_name'] = $name;
        $product['product_description'] = $description;
        $product['product_price'] = $price;
        $product['product_category'] = $category;
        $product['product_color'] = $color;
        $product['product_special_offer'] = $special;
    } else {
        $error = "Erro ao atualizar o produto.";
    }
}
?>

<div class="container-fluid">
    <div class="row">

        <!-- ===============================
             MENU LATERAL
        ================================ -->
        <?php include('sidemenu.php'); ?>

        <!-- ===============================
             CONTEÚDO PRINCIPAL
        ================================ -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
            <div class="card shadow-lg mt-4 mx-auto" style="max-width: 700px;">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">Editar Produto #<?= $product_id ?></h4>
                </div>
                <div class="card-body">

                    <!-- Mensagens -->
                    <?php if($error != ""): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    <?php if($success != ""): ?>
                        <div class="alert alert-success"><?= $success ?></div>
                    <?php endif; ?>

                    <!-- Formulário -->
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nome do Produto</label>
                            <input type="text" name="product_name" class="form-control" value="<?= htmlspecialchars($product['product_name']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descrição</label>
                            <textarea name="product_description" class="form-control" rows="3"><?= htmlspecialchars($product['product_description']) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Preço</label>
                            <input type="number" step="0.01" name="product_price" class="form-control" value="<?= $product['product_price'] ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Categoria</label>
                            <input type="text" name="product_category" class="form-control" value="<?= htmlspecialchars($product['product_category']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Cor</label>
                            <input type="text" name="product_color" class="form-control" value="<?= htmlspecialchars($product['product_color']) ?>">
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" name="product_special_offer" class="form-check-input" id="special" <?= $product['product_special_offer'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="special">Produto em oferta</label>
                        </div>

                        <button type="submit" class="btn btn-success w-100 mb-2">
                            <i class="bi bi-check-circle"></i> Atualizar Produto
                        </button>
                        <a href="products.php" class="btn btn-secondary w-100">
                            <i class="bi bi-arrow-left-circle"></i> Voltar
                        </a>
                    </form>

                </div>
            </div>
        </main>
    </div>
</div>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<?php
include('header.php'); // Inclui sessão e conexão

// ===============================
// VALIDAÇÃO DE SESSÃO
// ===============================
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// ===============================
// VERIFICAÇÃO DO ID DO PRODUTO
// ===============================
if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit;
}

$product_id = intval($_GET['id']);

// ===============================
// BUSCA DO PRODUTO
// ===============================
$sql = "SELECT * FROM products WHERE product_id = $product_id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    echo "<p class='text-center mt-5'>Produto não encontrado.</p>";
    exit;
}

$product = mysqli_fetch_assoc($result);

$error = "";
$success = "";

// ===============================
// EXCLUSÃO DE IMAGEM
// ===============================
if (isset($_GET['delete_img']) && isset($_GET['col'])) {
    $col = $_GET['col'];
    if (in_array($col, ['product_image','product_image2','product_image3','product_image4'])) {
        $imgFile = "../assets/imgs/".$product[$col];
        if (file_exists($imgFile)) unlink($imgFile); // remove do servidor
        mysqli_query($conn, "UPDATE products SET $col='' WHERE product_id=$product_id");
        $success = "Imagem removida com sucesso!";
        // Atualiza o produto
        $result = mysqli_query($conn, "SELECT * FROM products WHERE product_id = $product_id");
        $product = mysqli_fetch_assoc($result);
    }
}

// ===============================
// UPLOAD DE NOVAS IMAGENS
// ===============================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDir = "../assets/imgs/"; // Pasta onde as imagens serão salvas
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    
    for ($i = 1; $i <= 4; $i++) {
        if (!empty($_FILES["image$i"]['name'])) {
            $fileName = basename($_FILES["image$i"]['name']);
            $targetFile = $uploadDir . $fileName;
            $fileType = $_FILES["image$i"]['type'];
            
            if (in_array($fileType, $allowedTypes)) {
                if (move_uploaded_file($_FILES["image$i"]['tmp_name'], $targetFile)) {
                    $col = "product_image" . ($i == 1 ? "" : $i);
                    mysqli_query($conn, "UPDATE products SET $col='$fileName' WHERE product_id=$product_id");
                    $success = "Imagens atualizadas com sucesso!";
                } else {
                    $error = "Erro ao enviar a imagem $i.";
                }
            } else {
                $error = "Formato inválido para a imagem $i. Apenas JPEG, PNG ou GIF são permitidos.";
            }
        }
    }

    // Atualiza novamente as informações do produto
    $result = mysqli_query($conn, "SELECT * FROM products WHERE product_id = $product_id");
    $product = mysqli_fetch_assoc($result);
}
?>

<div class="container-fluid">
    <div class="row">

        <!-- MENU LATERAL -->
        <?php include('sidemenu.php'); ?>

        <!-- CONTEÚDO PRINCIPAL -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h2 class="mb-4">Editar Imagens do Produto #<?= $product['product_id'] ?></h2>

            <!-- Mensagens -->
            <?php if($error != ""): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <?php if($success != ""): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <!-- Formulário de upload -->
            <form method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <?php for($i=1; $i<=4; $i++): 
                        $col = "product_image" . ($i == 1 ? "" : $i);
                        $imgSrc = $product[$col] ? "../assets/imgs/".$product[$col] : "https://via.placeholder.com/150";
                    ?>
                        <div class="col-md-3 text-center mb-3">
                            <label class="form-label">Imagem <?= $i ?></label><br>
                            <div class="position-relative">
                                <img src="<?= $imgSrc ?>" alt="Imagem <?= $i ?>" class="img-thumbnail mb-2" width="150">
                                <?php if($product[$col]): ?>
                                    <a href="edit_images.php?id=<?= $product_id ?>&delete_img=1&col=<?= $col ?>" class="btn btn-sm btn-warning position-absolute top-0 end-0" onclick="return confirm('Tem certeza que deseja excluir esta imagem?');">
                                        <i class="bi bi-x-circle"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <input type="file" name="image<?= $i ?>" class="form-control form-control-sm mt-1">
                        </div>
                    <?php endfor; ?>
                </div>
                <button type="submit" class="btn btn-success w-100 mb-2">
                    <i class="bi bi-check-circle"></i> Atualizar Imagens
                </button>
                <a href="products.php" class="btn btn-secondary w-100">
                    <i class="bi bi-arrow-left-circle"></i> Voltar
                </a>
            </form>
        </main>
    </div>
</div>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

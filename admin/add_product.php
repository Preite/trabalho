<?php
include('header.php'); // Inclui sessão e conexão

// ===============================
// VALIDAÇÃO DE SESSÃO
// ===============================
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$error = "";
$success = "";

// ===============================
// ENVIO DO FORMULÁRIO
// ===============================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receber campos
    $name = trim($_POST['product_name']);
    $category = trim($_POST['product_category']);
    $description = trim($_POST['product_description']);
    $price = floatval($_POST['product_price']);
    $special_offer = isset($_POST['product_special_offer']) ? 1 : 0;
    $color = trim($_POST['product_color']);

    // Validação simples
    if (empty($name) || empty($category) || empty($description) || $price <= 0 || empty($color)) {
        $error = "Por favor, preencha todos os campos corretamente.";
    } else {
        // Inserir produto sem imagens primeiro
        $insert = mysqli_query($conn, "INSERT INTO products 
            (product_name, product_category, product_description, product_price, product_special_offer, product_color) 
            VALUES ('$name', '$category', '$description', $price, $special_offer, '$color')");
        
        if ($insert) {
            $product_id = mysqli_insert_id($conn); // Pega o ID do produto recém criado

            // ===============================
            // UPLOAD DAS IMAGENS
            // ===============================
            $uploadDir = "../assets/imgs/";
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
                        } else {
                            $error = "Erro ao enviar a imagem $i.";
                        }
                    } else {
                        $error = "Formato inválido para a imagem $i. Apenas JPEG, PNG ou GIF são permitidos.";
                    }
                }
            }

            if (!$error) {
                $success = "Produto criado com sucesso!";
            }

        } else {
            $error = "Erro ao criar produto.";
        }
    }
}
?>

<div class="container-fluid">
    <div class="row">

        <!-- MENU LATERAL -->
        <?php include('sidemenu.php'); ?>

        <!-- CONTEÚDO PRINCIPAL -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h2 class="mb-4">Adicionar Novo Produto</h2>

            <!-- Mensagens -->
            <?php if($error != ""): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <?php if($success != ""): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <!-- Formulário -->
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Nome do Produto</label>
                    <input type="text" name="product_name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Categoria</label>
                    <input type="text" name="product_category" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descrição</label>
                    <textarea name="product_description" class="form-control" rows="4" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Preço</label>
                    <input type="number" step="0.01" name="product_price" class="form-control" required>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="product_special_offer" class="form-check-input" id="specialOffer">
                    <label class="form-check-label" for="specialOffer">Produto em Oferta</label>
                </div>

                <div class="mb-3">
                    <label class="form-label">Cor</label>
                    <input type="text" name="product_color" class="form-control" required>
                </div>

                <div class="row mb-3">
                    <?php for($i=1;$i<=4;$i++): ?>
                        <div class="col-md-3 text-center mb-3">
                            <label class="form-label">Imagem <?= $i ?></label><br>
                            <div class="position-relative">
                                <img id="preview<?= $i ?>" src="https://via.placeholder.com/150" class="img-thumbnail mb-2" width="150">
                            </div>
                            <input type="file" name="image<?= $i ?>" class="form-control form-control-sm mt-1" onchange="previewImage(event, <?= $i ?>)">
                        </div>
                    <?php endfor; ?>
                </div>

                <button type="submit" class="btn btn-success w-100 mb-2">
                    <i class="bi bi-check-circle"></i> Criar Produto
                </button>
                <a href="products.php" class="btn btn-secondary w-100">
                    <i class="bi bi-arrow-left-circle"></i> Voltar</a>
            </form>
        </main>
    </div>
</div>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<script>
function previewImage(event, index) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('preview'+index);
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>

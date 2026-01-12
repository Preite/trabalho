

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GenovaSetups</title>

    <!-- Incluindo o Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" 
        integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

    <!-- Incluindo o Font Awesome para os ícones -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Incluindo seu arquivo de CSS personalizado -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light navbar-back">
  <div class="container">

    <!-- Logo da loja -->
    <img src="assets/imgs/STORE.png" width="100px" alt="Logo da loja" class="navbar-logo" />

    <!-- Nome da loja -->
    <a class="navbar-brand navbar-font ms-2" href="index.php">GenovaSetups</a>

    <!-- Botão para menu responsivo (aparece em telas menores) -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Itens de navegação -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto nav-buttons">
        <li class="nav-item">
          <a class="nav-link active navbar-menu" href="index.php">Home</a>  
        </li>
        <li class="nav-item">
          <a class="nav-link navbar-menu" href="products.php">Produtos</a>  
        </li>
        <li class="nav-item">
          <a class="nav-link navbar-menu" href="#">Blog</a> 
        </li>
        <li class="nav-item">
          <a class="nav-link navbar-menu" href="#">Fale Conosco</a> 
        </li>
      </ul>

      <!-- Ícones de Carrinho e Login -->
      <div class="d-flex align-items-center gap-3 navbar-icon ms-3">
        <!-- Carrinho de Compras com a quantidade -->
        <a href="cart.php">
          <i class="fas fa-shopping-bag" style="font-size: 1.5rem;">
            <?php if(isset($_SESSION['quantity']) && $_SESSION['quantity'] != 0) { ?>
              <span class="cart-quantity"><?= $_SESSION['quantity']; ?></span>
            <?php } ?>
          </i>
        </a>
        
        <!-- Login Link -->
        <a href="login.php">
          <i class="fa fa-user" style="font-size: 1.5rem;"></i>  
        </a>
      </div>

    </div>
  </div>
</nav>

<!-- JS do Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

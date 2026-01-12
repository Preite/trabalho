<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

<link href="assets/css/style.css" rel="stylesheet">

<nav class="navbar navbar-expand-lg navbar-light navbar-back">
  <div class="container">

    <!-- Logo da loja -->
    <img src="assets/imgs/STORE.png" width="100px" alt="Logo da loja" />

    <!-- Nome da loja -->
    <a class="navbar-brand navbar-font ms-2" href="#">GenovaSetups</a>

    <!-- Botão para menu responsivo (aparece em telas menores) -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Itens de navegação -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto nav-buttons">
        <li class="nav-item">
          <a class="nav-link active navbar-menu" href="index.php">Home</a> <!-- Redirecionamento para a página Home -->
        </li>
        <li class="nav-item">
          <a class="nav-link navbar-menu" href="products.php">Produtos</a> <!-- Redirecionamento para a lista de Produtos -->
        </li>
        <li class="nav-item">
          <a class="nav-link navbar-menu" href="#">Blog</a>
        </li>
        <li class="nav-item">
          <a class="nav-link navbar-menu" href="#">Fale Conosco</a>
        </li>
      </ul>

      <!-- Ícones do carrinho e usuário -->
      <div class="d-flex align-items-center gap-3 navbar-icon ms-3">
        <i class="fa fa-shopping-cart" style="font-size: 1.5rem;"></i>
        <i class="fa fa-user" style="font-size: 1.5rem;"></i>
      </div>

    </div>
  </div>
</nav>

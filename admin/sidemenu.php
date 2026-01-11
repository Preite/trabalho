<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
  <div class="position-sticky pt-3">
    <ul class="nav flex-column">

      <li class="nav-item mb-1">
        <a class="nav-link d-flex align-items-center <?= $currentPage=='index.php'?'active':'' ?>" href="index.php">
          <i class="bi bi-speedometer2 me-2"></i>
          Dashboard
        </a>
      </li>

      <li class="nav-item mb-1">
        <a class="nav-link d-flex align-items-center <?= $currentPage=='orders.php'?'active':'' ?>" href="orders.php">
          <i class="bi bi-basket me-2"></i>
          Orders
        </a>
      </li>

      <li class="nav-item mb-1">
        <a class="nav-link d-flex align-items-center <?= $currentPage=='products.php'?'active':'' ?>" href="products.php">
          <i class="bi bi-box-seam me-2"></i>
          Products
        </a>
      </li>

      <li class="nav-item mb-1">
        <a class="nav-link d-flex align-items-center <?= $currentPage=='account.php'?'active':'' ?>" href="account.php">
          <i class="bi bi-person-circle me-2"></i>
          Account
        </a>
      </li>

      <li class="nav-item mb-1">
        <a class="nav-link d-flex align-items-center <?= $currentPage=='add_product.php'?'active':'' ?>" href="add_product.php">
          <i class="bi bi-plus-circle me-2"></i>
          Add New Product
        </a>
      </li>

    </ul>
  </div>
</nav>

<style>
/* Sidebar Styling */
#sidebarMenu {
    height: 100vh;
    background-color: #1f2937; /* tom escuro moderno */
    color: #fff;
}

#sidebarMenu .nav-link {
    color: #cbd5e1; /* cinza claro */
    padding: 12px 20px;
    border-radius: 8px;
    transition: all 0.2s ease;
}

#sidebarMenu .nav-link:hover {
    background-color: #374151; /* hover escuro */
    color: #fff;
}

#sidebarMenu .nav-link.active {
    background-color: #2563eb; /* azul vibrante para ativo */
    color: #fff;
    font-weight: bold;
}

#sidebarMenu i {
    font-size: 1.2rem;
}
</style>

<!-- Não esqueça de incluir os ícones do Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">


<?php

session_start();
include('../server/connection.php'); // Ajuste o caminho conforme sua pasta

// Se admin já estiver logado, redireciona para dashboard
if(isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

$error = "";

if(isset($_POST['login'])) {
    // Limpa entrada para evitar SQL injection
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Consulta SQL usando admin_name
    $query = "SELECT * FROM admins WHERE admin_name='$name' AND admin_password='$password'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) == 1) {
        $admin = mysqli_fetch_assoc($result);

        // Cria sessão
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['admin_name'] = $admin['admin_name'];

        // Redireciona para o dashboard
        header("Location: index.php");
        exit;
    } else {
        $error = "Nome ou senha incorretos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h3 class="card-title mb-4 text-center">Login Admin</h3>

                    <?php if($error != ""): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome do Admin</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Senha</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary w-100">Entrar</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>

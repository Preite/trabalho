<?php
session_start();  // Inicia a sessão

// Verifica se o usuário já está logado
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
    header("Location: account.php");  // Redireciona para a conta se já estiver logado
    exit;
}

// Conecta ao banco de dados
include('server/connection.php');

// Definir variáveis para o login
$email = $password = "";
$emailErr = $passwordErr = $errorMsg = $successMsg = "";

// Processa o formulário quando enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validando o e-mail
    if (empty($_POST["email"])) {
        $emailErr = "E-mail é obrigatório!";
    } else {
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
    }

    // Validando a senha
    if (empty($_POST["password"])) {
        $passwordErr = "Senha é obrigatória!";
    } else {
        $password = mysqli_real_escape_string($conn, $_POST["password"]);
    }

    // Se não houver erros, tenta fazer login
    if (empty($emailErr) && empty($passwordErr)) {
        // Verifica se o e-mail existe no banco de dados
        $query = "SELECT * FROM users WHERE user_email = '$email' LIMIT 1";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            // Verifica se a senha digitada corresponde ao hash armazenado
            if (password_verify($password, $user['user_password'])) {
                $_SESSION['logged_in'] = true;  // Marca como logado
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_name'] = $user['user_name'];
                header("Location: account.php");  // Redireciona para a página da conta
                exit;
            } else {
                $errorMsg = "Senha incorreta.";
            }
        } else {
            $errorMsg = "Usuário não encontrado.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GenovaSetups</title>

    <!-- Incluindo o Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<?php include('layouts/header.php'); ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-lg">
                <div class="card-header bg-dark text-white text-center">
                    <h4>Login</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($errorMsg)) : ?>
                        <div class="alert alert-danger"><?= $errorMsg ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <!-- E-mail -->
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                            <div class="form-text text-danger"><?= $emailErr ?></div>
                        </div>

                        <!-- Senha -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="form-text text-danger"><?= $passwordErr ?></div>
                        </div>

                        <!-- Botão de Login -->
                        <button type="submit" class="btn btn-primary w-100">Entrar</button>
                    </form>

                    <!-- Link para Cadastro -->
                    <div class="mt-3 text-center">
                        <p>Não tem uma conta? <a href="register.php">Cadastre-se aqui</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<?php include('layouts/footer.php'); ?>

<!-- JS do Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
session_start();  // Inicia a sessão

// Inclui a conexão com o banco de dados
include('server/connection.php');

// Verifica se o usuário já está logado, se sim, redireciona para a conta
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
    header("Location: account.php");
    exit;
}

// Definindo variáveis para o formulário
$name = $email = $password = $confirm_password = "";
$nameErr = $emailErr = $passwordErr = $confirm_passwordErr = $errorMsg = $successMsg = "";

// Processa o formulário quando enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Valida o nome
    if (empty($_POST["name"])) {
        $nameErr = "Nome é obrigatório!";
    } else {
        $name = mysqli_real_escape_string($conn, $_POST["name"]);
    }

    // Valida o e-mail
    if (empty($_POST["email"])) {
        $emailErr = "E-mail é obrigatório!";
    } else {
        $email = mysqli_real_escape_string($conn, $_POST["email"]);

        // Verifica se o e-mail já existe no banco
        $query = "SELECT * FROM users WHERE user_email = '$email' LIMIT 1";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            $emailErr = "Este e-mail já está em uso.";
        }
    }

    // Valida a senha
    if (empty($_POST["password"])) {
        $passwordErr = "Senha é obrigatória!";
    } else {
        $password = $_POST["password"];
    }

    // Valida a confirmação de senha
    if (empty($_POST["confirm_password"])) {
        $confirm_passwordErr = "A confirmação de senha é obrigatória!";
    } else {
        $confirm_password = $_POST["confirm_password"];
        if ($password !== $confirm_password) {
            $confirm_passwordErr = "As senhas não coincidem.";
        }
    }

    // Se não houver erros, realiza o cadastro
    if (empty($nameErr) && empty($emailErr) && empty($passwordErr) && empty($confirm_passwordErr)) {
        // Gera o hash da senha
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insere o novo usuário no banco
        $query = "INSERT INTO users (user_name, user_email, user_password) VALUES ('$name', '$email', '$hashed_password')";
        if (mysqli_query($conn, $query)) {
            $successMsg = "Cadastro realizado com sucesso! Agora você pode fazer login.";
        } else {
            $errorMsg = "Erro ao realizar o cadastro. Tente novamente mais tarde.";
        }
    }
}
?>

<!-- Incluindo o topo (header) -->
<?php include('layouts/header.php'); ?>

<!-- Formulário de Cadastro -->
<section class="container my-5">
    <h2 class="mb-4 text-center">Cadastro</h2>
    
    <?php if ($successMsg != ""): ?>
        <div class="alert alert-success text-center">
            <?= $successMsg ?>
        </div>
    <?php endif; ?>

    <?php if ($errorMsg != ""): ?>
        <div class="alert alert-danger text-center">
            <?= $errorMsg ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-lg mx-auto" style="max-width: 500px;">
        <div class="card-header bg-dark text-white text-center">
            <h4>Crie sua conta</h4>
        </div>
        <div class="card-body">

            <form method="POST" action="register.php">
                <!-- Campo nome -->
                <div class="mb-3">
                    <label for="name" class="form-label">Nome Completo</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= $name ?>" required>
                    <small class="text-danger"><?= $nameErr ?></small>
                </div>

                <!-- Campo e-mail -->
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= $email ?>" required>
                    <small class="text-danger"><?= $emailErr ?></small>
                </div>

                <!-- Campo senha -->
                <div class="mb-3">
                    <label for="password" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <small class="text-danger"><?= $passwordErr ?></small>
                </div>

                <!-- Campo confirmação de senha -->
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirmar Senha</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    <small class="text-danger"><?= $confirm_passwordErr ?></small>
                </div>

                <!-- Botão de cadastro -->
                <button type="submit" class="btn btn-primary w-100 mb-3">Cadastrar-se</button>
            </form>

            <div class="text-center">
                <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
            </div>
        </div>
    </div>
</section>

<!-- Incluindo o rodapé (footer) -->
<?php include('layouts/footer.php'); ?>


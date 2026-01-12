<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado, se não, redireciona para o login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Conectar ao banco de dados
include('server/connection.php');

// Definindo variáveis para a troca de senha
$current_password = $new_password = $confirm_new_password = "";
$current_password_err = $new_password_err = $confirm_new_password_err = $errorMsg = $successMsg = "";

// Verifica se o usuário está logado e obtém o nome do usuário
if (isset($_SESSION['user_name'])) {
    $user_name = $_SESSION['user_name']; // Nome do usuário na sessão
    $user_id = $_SESSION['user_id']; // ID do usuário na sessão
} else {
    header("Location: login.php");
    exit;
}

// Processa o formulário de troca de senha
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Valida a senha atual
    if (empty($_POST["current_password"])) {
        $current_password_err = "A senha atual é obrigatória!";
    } else {
        $current_password = mysqli_real_escape_string($conn, $_POST["current_password"]);
    }

    // Valida a nova senha
    if (empty($_POST["new_password"])) {
        $new_password_err = "A nova senha é obrigatória!";
    } else {
        $new_password = mysqli_real_escape_string($conn, $_POST["new_password"]);
    }

    // Valida a confirmação da nova senha
    if (empty($_POST["confirm_new_password"])) {
        $confirm_new_password_err = "A confirmação da nova senha é obrigatória!";
    } else {
        $confirm_new_password = mysqli_real_escape_string($conn, $_POST["confirm_new_password"]);
        if ($new_password !== $confirm_new_password) {
            $confirm_new_password_err = "As senhas não coincidem.";
        }
    }

    // Se não houver erros, tenta atualizar a senha
    if (empty($current_password_err) && empty($new_password_err) && empty($confirm_new_password_err)) {
        // Verifica se a senha atual está correta
        $query = "SELECT user_password FROM users WHERE user_id = '$user_id' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            // Verifica se a senha atual corresponde ao hash armazenado
            if (password_verify($current_password, $user['user_password'])) {
                // Gera o hash da nova senha
                $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                // Atualiza a senha no banco de dados
                $update_query = "UPDATE users SET user_password = '$new_password_hash' WHERE user_id = '$user_id'";
                if (mysqli_query($conn, $update_query)) {
                    $successMsg = "Senha alterada com sucesso!";
                } else {
                    $errorMsg = "Erro ao atualizar a senha. Tente novamente mais tarde.";
                }
            } else {
                $errorMsg = "A senha atual está incorreta.";
            }
        } else {
            $errorMsg = "Usuário não encontrado.";
        }
    }
}

// Consulta para obter os pedidos do usuário
$order_query = "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY order_date DESC";
$order_result = mysqli_query($conn, $order_query);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Conta - GenovaSetups</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<?php include('layouts/header.php'); ?>

<!-- Conteúdo da Conta -->
<section class="container my-5">
    <h2 class="mb-4 text-center">Minha Conta</h2>

    <div class="card shadow-lg mx-auto" style="max-width: 600px;">
        <div class="card-header bg-dark text-white text-center">
            <h4>Olá, <?= htmlspecialchars($user_name) ?>!</h4>
        </div>
        <div class="card-body">
            <p>Bem-vindo ao seu painel de controle. Aqui você pode visualizar suas informações e fazer alterações, se necessário.</p>

            <h4>Alterar Senha</h4>
            <!-- Exibe as mensagens de sucesso e erro -->
            <?php if ($successMsg != ""): ?>
                <div class="alert alert-success">
                    <?= $successMsg ?>
                </div>
            <?php endif; ?>
            <?php if ($errorMsg != ""): ?>
                <div class="alert alert-danger">
                    <?= $errorMsg ?>
                </div>
            <?php endif; ?>

            <!-- Formulário de Alteração de Senha -->
            <form method="POST" action="account.php">
                <!-- Campo para senha atual -->
                <div class="mb-3">
                    <label for="current_password" class="form-label">Senha Atual</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                    <small class="text-danger"><?= $current_password_err ?></small>
                </div>

                <!-- Campo para nova senha -->
                <div class="mb-3">
                    <label for="new_password" class="form-label">Nova Senha</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                    <small class="text-danger"><?= $new_password_err ?></small>
                </div>

                <!-- Campo para confirmação da nova senha -->
                <div class="mb-3">
                    <label for="confirm_new_password" class="form-label">Confirmar Nova Senha</label>
                    <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" required>
                    <small class="text-danger"><?= $confirm_new_password_err ?></small>
                </div>

                <!-- Botão para atualizar a senha -->
                <button type="submit" class="btn btn-primary w-100 mb-3">Alterar Senha</button>
            </form>

            <!-- Histórico de Compras -->
            <h4>Histórico de Compras</h4>
            <?php if (mysqli_num_rows($order_result) > 0): ?>
                <table class="table table-striped mt-3">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Data</th>
                            <th>Status</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($order = mysqli_fetch_assoc($order_result)): ?>
                            <tr>
                                <td><?= $order['order_id'] ?></td>
                                <td><?= date('d/m/Y', strtotime($order['order_date'])) ?></td>
                                <td><?= ucfirst($order['order_status']) ?></td>
                                <td>R$ <?= number_format($order['order_cost'], 2, ',', '.') ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Você ainda não fez nenhuma compra.</p>
            <?php endif; ?>

            <!-- Botão de Logout -->
            <a href="logout.php" class="btn btn-danger w-100">Sair</a>
        </div>
    </div>
</section>

<!-- Incluindo o rodapé -->
<?php include('layouts/footer.php'); ?>

<!-- JS do Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

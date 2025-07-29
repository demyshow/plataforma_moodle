<?php
/**
 * Página de Login - Sistema Moodle
 * Desenvolvido por: Edemir de Carvalho Rodrigues
 */

session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Redirecionar se já estiver logado
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitizeInput($_POST['email']);
    $senha = $_POST['senha'];
    
    if (empty($email) || empty($senha)) {
        $error_message = 'Por favor, preencha todos os campos.';
    } else {
        $user = verifyLogin($email, $senha);
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nome'];
            $_SESSION['user_type'] = $user['tipo'];
            
            // Atualizar último acesso
            updateData('usuarios', ['ultimo_acesso' => date('Y-m-d H:i:s')], 'id = :id', ['id' => $user['id']]);
            
            // Log da atividade
            logActivity($user['id'], 'login', 'Usuário fez login no sistema');
            
            header('Location: ../index.php');
            exit();
        } else {
            $error_message = 'Email ou senha incorretos.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Moodle LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <h2 class="text-primary">Moodle LMS</h2>
                            <p class="text-muted">Sistema de Aprendizagem Online</p>
                        </div>
                        
                        <?php if ($error_message): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha</label>
                                <input type="password" class="form-control" id="senha" name="senha" required>
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="lembrar">
                                <label class="form-check-label" for="lembrar">
                                    Lembrar de mim
                                </label>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">Entrar</button>
                        </form>
                        
                        <div class="text-center mt-3">
                            <a href="register.php" class="text-decoration-none">Criar nova conta</a>
                            <br>
                            <a href="forgot-password.php" class="text-decoration-none">Esqueci minha senha</a>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <small class="text-muted">
                        Desenvolvido por Edemir de Carvalho Rodrigues
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


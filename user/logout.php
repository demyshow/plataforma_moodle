<?php
/**
 * Logout - Sistema Moodle
 * Desenvolvido por: Edemir de Carvalho Rodrigues
 */

session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Log da atividade antes de destruir a sessão
if (isset($_SESSION['user_id'])) {
    logActivity($_SESSION['user_id'], 'logout', 'Usuário fez logout do sistema');
}

// Destruir todas as variáveis de sessão
$_SESSION = array();

// Destruir o cookie de sessão
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destruir a sessão
session_destroy();

// Redirecionar para a página de login
header('Location: login.php');
exit();
?>


<?php
/**
 * Sistema Moodle - Plataforma de Aprendizagem Online
 * Desenvolvido por: Edemir de Carvalho Rodrigues
 * Data: 2025
 */

session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: user/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$user_info = getUserInfo($user_id);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moodle - Sistema de Aprendizagem</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="assets/images/logo.png" alt="Moodle" height="30" class="me-2">
                Moodle LMS
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">Bem-vindo, <?php echo htmlspecialchars($user_info['nome']); ?>!</span>
                <a class="nav-link" href="user/logout.php">Sair</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h5>Menu Principal</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li><a href="course/list.php" class="text-decoration-none">Meus Cursos</a></li>
                            <li><a href="user/profile.php" class="text-decoration-none">Meu Perfil</a></li>
                            <li><a href="modules/grades.php" class="text-decoration-none">Notas</a></li>
                            <li><a href="modules/calendar.php" class="text-decoration-none">Calendário</a></li>
                            <?php if ($user_info['tipo'] == 'admin'): ?>
                            <li><a href="admin/dashboard.php" class="text-decoration-none">Administração</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Dashboard</h2>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card text-white bg-info mb-3">
                                    <div class="card-header">Cursos Ativos</div>
                                    <div class="card-body">
                                        <h4 class="card-title"><?php echo getActiveCourses($user_id); ?></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-white bg-success mb-3">
                                    <div class="card-header">Atividades Pendentes</div>
                                    <div class="card-body">
                                        <h4 class="card-title"><?php echo getPendingActivities($user_id); ?></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-white bg-warning mb-3">
                                    <div class="card-header">Mensagens</div>
                                    <div class="card-body">
                                        <h4 class="card-title"><?php echo getUnreadMessages($user_id); ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Últimas Atividades</h5>
                            </div>
                            <div class="card-body">
                                <?php
                                $recent_activities = getRecentActivities($user_id);
                                if (!empty($recent_activities)):
                                ?>
                                <ul class="list-group list-group-flush">
                                    <?php foreach ($recent_activities as $activity): ?>
                                    <li class="list-group-item">
                                        <strong><?php echo htmlspecialchars($activity['titulo']); ?></strong>
                                        <br>
                                        <small class="text-muted"><?php echo $activity['data_criacao']; ?></small>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php else: ?>
                                <p>Nenhuma atividade recente encontrada.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-light mt-5 py-3">
        <div class="container text-center">
            <p>&copy; 2025 Moodle LMS - Desenvolvido por Edemir de Carvalho Rodrigues</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>


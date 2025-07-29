<?php
/**
 * Funções Principais do Sistema Moodle
 * Desenvolvido por: Edemir de Carvalho Rodrigues
 */

// Função para obter informações do usuário
function getUserInfo($user_id) {
    $sql = "SELECT * FROM usuarios WHERE id = :user_id";
    return fetchOne($sql, ['user_id' => $user_id]);
}

// Função para verificar login
function verifyLogin($email, $senha) {
    $sql = "SELECT id, nome, email, senha, tipo FROM usuarios WHERE email = :email AND ativo = 1";
    $user = fetchOne($sql, ['email' => $email]);
    
    if ($user && password_verify($senha, $user['senha'])) {
        return $user;
    }
    return false;
}

// Função para registrar usuário
function registerUser($nome, $email, $senha, $tipo = 'estudante') {
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    
    $data = [
        'nome' => $nome,
        'email' => $email,
        'senha' => $senha_hash,
        'tipo' => $tipo,
        'data_criacao' => date('Y-m-d H:i:s'),
        'ativo' => 1
    ];
    
    return insertData('usuarios', $data);
}

// Função para obter cursos ativos do usuário
function getActiveCourses($user_id) {
    $sql = "SELECT COUNT(*) as total FROM inscricoes i 
            JOIN cursos c ON i.curso_id = c.id 
            WHERE i.usuario_id = :user_id AND c.ativo = 1";
    $result = fetchOne($sql, ['user_id' => $user_id]);
    return $result['total'] ?? 0;
}

// Função para obter atividades pendentes
function getPendingActivities($user_id) {
    $sql = "SELECT COUNT(*) as total FROM atividades a
            JOIN inscricoes i ON a.curso_id = i.curso_id
            WHERE i.usuario_id = :user_id 
            AND a.data_entrega >= NOW() 
            AND a.id NOT IN (
                SELECT atividade_id FROM entregas 
                WHERE usuario_id = :user_id
            )";
    $result = fetchOne($sql, ['user_id' => $user_id]);
    return $result['total'] ?? 0;
}

// Função para obter mensagens não lidas
function getUnreadMessages($user_id) {
    $sql = "SELECT COUNT(*) as total FROM mensagens 
            WHERE destinatario_id = :user_id AND lida = 0";
    $result = fetchOne($sql, ['user_id' => $user_id]);
    return $result['total'] ?? 0;
}

// Função para obter atividades recentes
function getRecentActivities($user_id, $limit = 5) {
    $sql = "SELECT a.titulo, a.data_criacao, c.nome as curso_nome
            FROM atividades a
            JOIN cursos c ON a.curso_id = c.id
            JOIN inscricoes i ON c.id = i.curso_id
            WHERE i.usuario_id = :user_id
            ORDER BY a.data_criacao DESC
            LIMIT :limit";
    
    return fetchAll($sql, ['user_id' => $user_id, 'limit' => $limit]);
}

// Função para criar curso
function createCourse($nome, $descricao, $professor_id) {
    $data = [
        'nome' => $nome,
        'descricao' => $descricao,
        'professor_id' => $professor_id,
        'data_criacao' => date('Y-m-d H:i:s'),
        'ativo' => 1
    ];
    
    return insertData('cursos', $data);
}

// Função para inscrever usuário em curso
function enrollUser($user_id, $course_id) {
    $data = [
        'usuario_id' => $user_id,
        'curso_id' => $course_id,
        'data_inscricao' => date('Y-m-d H:i:s')
    ];
    
    return insertData('inscricoes', $data);
}

// Função para criar atividade
function createActivity($curso_id, $titulo, $descricao, $data_entrega) {
    $data = [
        'curso_id' => $curso_id,
        'titulo' => $titulo,
        'descricao' => $descricao,
        'data_entrega' => $data_entrega,
        'data_criacao' => date('Y-m-d H:i:s')
    ];
    
    return insertData('atividades', $data);
}

// Função para enviar mensagem
function sendMessage($remetente_id, $destinatario_id, $assunto, $conteudo) {
    $data = [
        'remetente_id' => $remetente_id,
        'destinatario_id' => $destinatario_id,
        'assunto' => $assunto,
        'conteudo' => $conteudo,
        'data_envio' => date('Y-m-d H:i:s'),
        'lida' => 0
    ];
    
    return insertData('mensagens', $data);
}

// Função para validar email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Função para sanitizar entrada
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Função para gerar token CSRF
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Função para verificar token CSRF
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Função para log de atividades
function logActivity($user_id, $action, $description) {
    $data = [
        'usuario_id' => $user_id,
        'acao' => $action,
        'descricao' => $description,
        'data_hora' => date('Y-m-d H:i:s'),
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
    ];
    
    return insertData('logs_atividade', $data);
}
?>


<?php
/**
 * Configuração do Banco de Dados MySQL
 * Sistema Moodle - Desenvolvido por Edemir de Carvalho Rodrigues
 */

// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'moodle_db');
define('DB_USER', 'moodle_user');
define('DB_PASS', 'moodle_password');
define('DB_CHARSET', 'utf8mb4');

// Configurações do sistema
define('SITE_URL', 'http://localhost/moodle-system');
define('SITE_NAME', 'Moodle LMS');
define('ADMIN_EMAIL', 'admin@moodle.local');

// Configurações de segurança
define('HASH_ALGORITHM', 'sha256');
define('SESSION_TIMEOUT', 3600); // 1 hora

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

// Função para executar queries
function executeQuery($sql, $params = []) {
    global $pdo;
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        error_log("Erro na query: " . $e->getMessage());
        return false;
    }
}

// Função para obter uma linha
function fetchOne($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt ? $stmt->fetch() : false;
}

// Função para obter múltiplas linhas
function fetchAll($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt ? $stmt->fetchAll() : false;
}

// Função para inserir dados
function insertData($table, $data) {
    global $pdo;
    $columns = implode(',', array_keys($data));
    $placeholders = ':' . implode(', :', array_keys($data));
    
    $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log("Erro ao inserir dados: " . $e->getMessage());
        return false;
    }
}

// Função para atualizar dados
function updateData($table, $data, $where, $whereParams = []) {
    global $pdo;
    $setClause = [];
    foreach (array_keys($data) as $key) {
        $setClause[] = "{$key} = :{$key}";
    }
    $setClause = implode(', ', $setClause);
    
    $sql = "UPDATE {$table} SET {$setClause} WHERE {$where}";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_merge($data, $whereParams));
        return $stmt->rowCount();
    } catch (PDOException $e) {
        error_log("Erro ao atualizar dados: " . $e->getMessage());
        return false;
    }
}
?>


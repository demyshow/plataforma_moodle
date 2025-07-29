-- Script de Criação do Banco de Dados Moodle
-- Desenvolvido por: Edemir de Carvalho Rodrigues
-- Data: 2025

CREATE DATABASE IF NOT EXISTS moodle_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE moodle_db;

-- Tabela de usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('estudante', 'professor', 'admin') DEFAULT 'estudante',
    data_nascimento DATE,
    telefone VARCHAR(20),
    endereco TEXT,
    foto_perfil VARCHAR(255),
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    ultimo_acesso TIMESTAMP NULL,
    ativo BOOLEAN DEFAULT TRUE,
    INDEX idx_email (email),
    INDEX idx_tipo (tipo)
);

-- Tabela de cursos
CREATE TABLE cursos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(200) NOT NULL,
    descricao TEXT,
    codigo VARCHAR(20) UNIQUE,
    professor_id INT NOT NULL,
    categoria VARCHAR(100),
    carga_horaria INT,
    data_inicio DATE,
    data_fim DATE,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    ativo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (professor_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_professor (professor_id),
    INDEX idx_categoria (categoria)
);

-- Tabela de inscrições
CREATE TABLE inscricoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    curso_id INT NOT NULL,
    data_inscricao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('ativo', 'concluido', 'cancelado') DEFAULT 'ativo',
    nota_final DECIMAL(5,2),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE CASCADE,
    UNIQUE KEY unique_inscricao (usuario_id, curso_id),
    INDEX idx_usuario (usuario_id),
    INDEX idx_curso (curso_id)
);

-- Tabela de módulos/lições
CREATE TABLE modulos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    curso_id INT NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    descricao TEXT,
    conteudo LONGTEXT,
    ordem INT DEFAULT 0,
    tipo ENUM('texto', 'video', 'arquivo', 'quiz') DEFAULT 'texto',
    arquivo_url VARCHAR(500),
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    ativo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE CASCADE,
    INDEX idx_curso (curso_id),
    INDEX idx_ordem (ordem)
);

-- Tabela de atividades
CREATE TABLE atividades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    curso_id INT NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    descricao TEXT,
    instrucoes LONGTEXT,
    data_entrega DATETIME,
    nota_maxima DECIMAL(5,2) DEFAULT 10.00,
    tipo ENUM('tarefa', 'quiz', 'forum', 'projeto') DEFAULT 'tarefa',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    ativo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE CASCADE,
    INDEX idx_curso (curso_id),
    INDEX idx_data_entrega (data_entrega)
);

-- Tabela de entregas
CREATE TABLE entregas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    atividade_id INT NOT NULL,
    usuario_id INT NOT NULL,
    conteudo LONGTEXT,
    arquivo_url VARCHAR(500),
    data_entrega TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    nota DECIMAL(5,2),
    feedback TEXT,
    status ENUM('enviado', 'avaliado', 'reenvio') DEFAULT 'enviado',
    FOREIGN KEY (atividade_id) REFERENCES atividades(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    UNIQUE KEY unique_entrega (atividade_id, usuario_id),
    INDEX idx_atividade (atividade_id),
    INDEX idx_usuario (usuario_id)
);

-- Tabela de mensagens
CREATE TABLE mensagens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    remetente_id INT NOT NULL,
    destinatario_id INT NOT NULL,
    assunto VARCHAR(200) NOT NULL,
    conteudo LONGTEXT NOT NULL,
    data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    lida BOOLEAN DEFAULT FALSE,
    data_leitura TIMESTAMP NULL,
    FOREIGN KEY (remetente_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (destinatario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_remetente (remetente_id),
    INDEX idx_destinatario (destinatario_id),
    INDEX idx_lida (lida)
);

-- Tabela de fóruns
CREATE TABLE foruns (
    id INT AUTO_INCREMENT PRIMARY KEY,
    curso_id INT NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    descricao TEXT,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ativo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE CASCADE,
    INDEX idx_curso (curso_id)
);

-- Tabela de tópicos do fórum
CREATE TABLE topicos_forum (
    id INT AUTO_INCREMENT PRIMARY KEY,
    forum_id INT NOT NULL,
    usuario_id INT NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    conteudo LONGTEXT NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    fixado BOOLEAN DEFAULT FALSE,
    fechado BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (forum_id) REFERENCES foruns(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_forum (forum_id),
    INDEX idx_usuario (usuario_id)
);

-- Tabela de respostas do fórum
CREATE TABLE respostas_forum (
    id INT AUTO_INCREMENT PRIMARY KEY,
    topico_id INT NOT NULL,
    usuario_id INT NOT NULL,
    conteudo LONGTEXT NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (topico_id) REFERENCES topicos_forum(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_topico (topico_id),
    INDEX idx_usuario (usuario_id)
);

-- Tabela de logs de atividade
CREATE TABLE logs_atividade (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    acao VARCHAR(100) NOT NULL,
    descricao TEXT,
    data_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    user_agent TEXT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_usuario (usuario_id),
    INDEX idx_data_hora (data_hora)
);

-- Tabela de configurações do sistema
CREATE TABLE configuracoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chave VARCHAR(100) UNIQUE NOT NULL,
    valor TEXT,
    descricao TEXT,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Inserir dados iniciais
INSERT INTO usuarios (nome, email, senha, tipo) VALUES 
('Administrador', 'admin@moodle.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Professor Demo', 'professor@moodle.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'professor'),
('Estudante Demo', 'estudante@moodle.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'estudante');

INSERT INTO configuracoes (chave, valor, descricao) VALUES
('site_name', 'Moodle LMS', 'Nome do site'),
('site_description', 'Sistema de Aprendizagem Online', 'Descrição do site'),
('admin_email', 'admin@moodle.local', 'Email do administrador'),
('timezone', 'America/Sao_Paulo', 'Fuso horário do sistema'),
('max_file_size', '10485760', 'Tamanho máximo de arquivo em bytes (10MB)');

-- Criar usuário do banco de dados
CREATE USER IF NOT EXISTS 'moodle_user'@'localhost' IDENTIFIED BY 'moodle_password';
GRANT ALL PRIVILEGES ON moodle_db.* TO 'moodle_user'@'localhost';
FLUSH PRIVILEGES;


# Sistema Moodle LMS

## 📚 Plataforma de Aprendizagem Online

Um sistema completo de gerenciamento de aprendizagem (LMS) desenvolvido em PHP e MySQL, inspirado no Moodle, oferecendo uma solução robusta para educação online.

---

## 👨‍💻 Desenvolvedor

**Edemir de Carvalho Rodrigues**

*Sistema desenvolvido com dedicação e expertise em tecnologias web*

---

## 🚀 Características Principais

### ✨ Funcionalidades do Sistema

- **Gestão de Usuários**: Sistema completo de autenticação e autorização
- **Cursos Online**: Criação e gerenciamento de cursos estruturados
- **Atividades e Avaliações**: Sistema de tarefas, quizzes e projetos
- **Fóruns de Discussão**: Comunicação entre estudantes e professores
- **Sistema de Mensagens**: Comunicação privada entre usuários
- **Dashboard Interativo**: Painel de controle com estatísticas em tempo real
- **Gestão de Notas**: Sistema completo de avaliação e feedback
- **Logs de Atividade**: Monitoramento completo das ações do sistema

### 🎯 Tipos de Usuário

1. **Administrador**: Controle total do sistema
2. **Professor**: Criação e gestão de cursos e atividades
3. **Estudante**: Acesso aos cursos e participação em atividades

---

## 🛠️ Tecnologias Utilizadas

### Backend
- **PHP 7.4+**: Linguagem principal do servidor
- **MySQL 8.0+**: Sistema de gerenciamento de banco de dados
- **PDO**: Interface de acesso ao banco de dados

### Frontend
- **HTML5**: Estrutura das páginas
- **CSS3**: Estilização responsiva
- **Bootstrap 5**: Framework CSS para interface moderna
- **JavaScript**: Interatividade do lado cliente

### Segurança
- **Password Hashing**: Criptografia segura de senhas
- **CSRF Protection**: Proteção contra ataques CSRF
- **SQL Injection Prevention**: Prepared statements
- **XSS Protection**: Sanitização de entradas

---

## 📋 Pré-requisitos

### Servidor Web
- Apache 2.4+ ou Nginx 1.18+
- PHP 7.4 ou superior
- MySQL 8.0 ou superior
- Extensões PHP necessárias:
  - PDO
  - PDO_MySQL
  - mbstring
  - openssl
  - session

### Configurações Recomendadas
```ini
memory_limit = 256M
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300
```

---

## 🔧 Instalação

### 1. Preparação do Ambiente

```bash
# Clone ou extraia os arquivos do projeto
cd /var/www/html/
unzip moodle-system.zip
```

### 2. Configuração do Banco de Dados

```bash
# Acesse o MySQL
mysql -u root -p

# Execute o script de criação
source /caminho/para/moodle-system/database/schema.sql
```

### 3. Configuração do Sistema

Edite o arquivo `config/database.php` com suas configurações:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'moodle_db');
define('DB_USER', 'moodle_user');
define('DB_PASS', 'sua_senha_aqui');
```

### 4. Permissões de Arquivos

```bash
# Definir permissões adequadas
chmod 755 moodle-system/
chmod 644 moodle-system/*.php
chmod 755 moodle-system/assets/
```

### 5. Configuração do Servidor Web

#### Apache (.htaccess)
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]

# Segurança
<Files "config/*">
    Deny from all
</Files>
```

#### Nginx
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location ~ /config/ {
    deny all;
}
```

---

## 🎮 Uso do Sistema

### Primeiro Acesso

1. **Acesse o sistema**: `http://seu-dominio.com/moodle-system`
2. **Login inicial**:
   - **Admin**: admin@moodle.local / password
   - **Professor**: professor@moodle.local / password
   - **Estudante**: estudante@moodle.local / password

### Funcionalidades Principais

#### Para Administradores
- Gestão completa de usuários
- Configuração do sistema
- Monitoramento de atividades
- Backup e manutenção

#### Para Professores
- Criação de cursos
- Desenvolvimento de atividades
- Avaliação de estudantes
- Comunicação com alunos

#### Para Estudantes
- Inscrição em cursos
- Participação em atividades
- Visualização de notas
- Interação em fóruns

---

## 📁 Estrutura do Projeto

```
moodle-system/
├── admin/                  # Área administrativa
├── assets/                 # Recursos estáticos
│   ├── css/               # Folhas de estilo
│   ├── js/                # Scripts JavaScript
│   └── images/            # Imagens do sistema
├── config/                # Configurações
│   └── database.php       # Configuração do BD
├── course/                # Gestão de cursos
├── database/              # Scripts SQL
│   └── schema.sql         # Estrutura do banco
├── includes/              # Arquivos de inclusão
│   └── functions.php      # Funções principais
├── modules/               # Módulos do sistema
├── user/                  # Gestão de usuários
│   ├── login.php         # Página de login
│   └── logout.php        # Script de logout
├── index.php             # Página principal
└── README.md             # Este arquivo
```

---

## 🔒 Segurança

### Medidas Implementadas

1. **Autenticação Segura**
   - Hash de senhas com `password_hash()`
   - Verificação com `password_verify()`
   - Controle de sessões

2. **Proteção contra Ataques**
   - Prepared statements (SQL Injection)
   - CSRF tokens
   - Sanitização de entradas (XSS)
   - Validação de dados

3. **Controle de Acesso**
   - Sistema de permissões por tipo de usuário
   - Verificação de autenticação em todas as páginas
   - Logs de atividade

### Recomendações de Segurança

- Altere as senhas padrão imediatamente
- Use HTTPS em produção
- Mantenha o PHP e MySQL atualizados
- Configure backups regulares
- Monitore os logs de atividade

---

## 🚀 Funcionalidades Avançadas

### Sistema de Logs
- Registro completo de atividades
- Monitoramento de acessos
- Auditoria de segurança

### Comunicação
- Sistema de mensagens privadas
- Fóruns de discussão por curso
- Notificações em tempo real

### Avaliação
- Múltiplos tipos de atividades
- Sistema de notas flexível
- Feedback detalhado

---

## 🔧 Manutenção

### Backup Regular
```bash
# Backup do banco de dados
mysqldump -u moodle_user -p moodle_db > backup_$(date +%Y%m%d).sql

# Backup dos arquivos
tar -czf moodle_files_$(date +%Y%m%d).tar.gz moodle-system/
```

### Monitoramento
- Verificar logs de erro regularmente
- Monitorar espaço em disco
- Acompanhar performance do banco

### Atualizações
- Manter PHP atualizado
- Aplicar patches de segurança
- Testar em ambiente de desenvolvimento

---

## 🐛 Solução de Problemas

### Problemas Comuns

1. **Erro de Conexão com Banco**
   - Verificar credenciais em `config/database.php`
   - Confirmar se o MySQL está rodando
   - Testar conectividade

2. **Problemas de Permissão**
   - Verificar permissões de arquivos
   - Confirmar configuração do servidor web
   - Checar logs de erro

3. **Sessões não Funcionam**
   - Verificar configuração de sessões no PHP
   - Confirmar permissões da pasta de sessões
   - Testar em navegador privado

---

## 📞 Suporte

### Contato do Desenvolvedor
**Edemir de Carvalho Rodrigues**

Para suporte técnico, melhorias ou customizações, entre em contato através dos canais oficiais.

### Documentação Adicional
- Consulte os comentários no código
- Verifique os logs do sistema
- Analise a estrutura do banco de dados

---

## 📄 Licença

Este projeto foi desenvolvido por **Edemir de Carvalho Rodrigues** e está disponível para uso educacional e comercial.

### Termos de Uso
- Mantenha os créditos do desenvolvedor
- Use responsavelmente
- Contribua com melhorias quando possível

---

## 🎯 Roadmap Futuro

### Próximas Funcionalidades
- [ ] API REST completa
- [ ] Aplicativo mobile
- [ ] Integração com videoconferência
- [ ] Sistema de certificados
- [ ] Analytics avançado
- [ ] Integração com redes sociais

### Melhorias Planejadas
- [ ] Interface mais moderna
- [ ] Performance otimizada
- [ ] Suporte a múltiplos idiomas
- [ ] Sistema de plugins
- [ ] Backup automático

---



---

*Sistema Moodle LMS - Transformando a educação através da tecnologia*




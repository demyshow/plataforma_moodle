/**
 * JavaScript Principal - Sistema Moodle
 * Desenvolvido por: Edemir de Carvalho Rodrigues
 */

// Inicialização quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', function() {
    initializeSystem();
});

// Função principal de inicialização
function initializeSystem() {
    // Adicionar animações aos cards
    addCardAnimations();
    
    // Configurar tooltips
    initializeTooltips();
    
    // Configurar confirmações
    setupConfirmations();
    
    // Auto-hide alerts
    autoHideAlerts();
    
    console.log('Sistema Moodle inicializado - Desenvolvido por Edemir de Carvalho Rodrigues');
}

// Animações para cards
function addCardAnimations() {
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.classList.add('fade-in');
    });
}

// Inicializar tooltips do Bootstrap
function initializeTooltips() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

// Configurar confirmações para ações importantes
function setupConfirmations() {
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Tem certeza que deseja excluir este item?')) {
                e.preventDefault();
            }
        });
    });
}

// Auto-hide para alertas
function autoHideAlerts() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        if (alert.classList.contains('alert-success')) {
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }, 3000);
        }
    });
}

// Função para mostrar loading
function showLoading() {
    const loadingHtml = `
        <div id="loading-overlay" class="position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center" style="background: rgba(0,0,0,0.5); z-index: 9999;">
            <div class="spinner"></div>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', loadingHtml);
}

// Função para esconder loading
function hideLoading() {
    const overlay = document.getElementById('loading-overlay');
    if (overlay) {
        overlay.remove();
    }
}

// Função para validar formulários
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
        }
    });
    
    return isValid;
}

// Função para formatar datas
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('pt-BR');
}

// Função para AJAX requests
function makeAjaxRequest(url, method = 'GET', data = null) {
    return fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: data ? JSON.stringify(data) : null
    })
    .then(response => response.json())
    .catch(error => {
        console.error('Erro na requisição:', error);
        throw error;
    });
}

// Função para mostrar notificações
function showNotification(message, type = 'info') {
    const alertClass = `alert-${type}`;
    const notificationHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 1050;">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', notificationHtml);
    
    // Auto-remove após 5 segundos
    setTimeout(() => {
        const notification = document.querySelector('.alert.position-fixed');
        if (notification) {
            notification.remove();
        }
    }, 5000);
}

// Função para confirmar ações
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

// Utilitários para localStorage
const Storage = {
    set: function(key, value) {
        localStorage.setItem(key, JSON.stringify(value));
    },
    
    get: function(key) {
        const item = localStorage.getItem(key);
        return item ? JSON.parse(item) : null;
    },
    
    remove: function(key) {
        localStorage.removeItem(key);
    }
};

// Função para debounce (útil para pesquisas)
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Exportar funções globais
window.MoodleSystem = {
    showLoading,
    hideLoading,
    validateForm,
    formatDate,
    makeAjaxRequest,
    showNotification,
    confirmAction,
    Storage,
    debounce
};


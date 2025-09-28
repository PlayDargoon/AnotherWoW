document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    const captchaInput = document.getElementById('captcha_answer');
    
    // Функция показа/скрытия пароля
    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁' : '🔓';
        });
        
        togglePassword.addEventListener('mouseenter', function() {
            this.style.color = '#ff6600';
        });
        
        togglePassword.addEventListener('mouseleave', function() {
            this.style.color = 'yellow';
        });
    }
    
    // Проверка чекбоксов перед отправкой формы
    if (form) {
        form.addEventListener('submit', function(e) {
            const agreePrivacy = document.getElementById('agree_privacy');
            const agreeTerms = document.getElementById('agree_terms');
            const agreeRules = document.getElementById('agree_rules');
            
            if (agreePrivacy && !agreePrivacy.checked) {
                alert('Необходимо согласиться с Политикой конфиденциальности');
                e.preventDefault();
                return false;
            }
            
            if (agreeTerms && !agreeTerms.checked) {
                alert('Необходимо принять Пользовательское соглашение');
                e.preventDefault();
                return false;
            }
            
            if (agreeRules && !agreeRules.checked) {
                alert('Необходимо согласиться с Правилами пользования');
                e.preventDefault();
                return false;
            }
        });
    }
    
    // Автофокус на поле капчи
    if (captchaInput && passwordInput) {
        passwordInput.addEventListener('blur', function() {
            if (this.value.length > 0) {
                setTimeout(() => captchaInput.focus(), 100);
            }
        });
    }
});
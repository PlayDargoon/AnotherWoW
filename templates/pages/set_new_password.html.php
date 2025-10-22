<!-- Контент страницы установки нового пароля -->

<!-- Хлебные крошки -->
<nav class="breadcrumbs">
    <span class="breadcrumb-item">
        <a href="/">Главная</a>
    </span>
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item">
        <a href="/restore-password">Восстановление пароля</a>
    </span>
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">Новый пароль</span>
</nav>

<!-- Заголовок -->
<div class="login-header">
    <h1 class="login-title">Новый пароль</h1>
    <p class="login-subtitle">Установи надёжный пароль для своего аккаунта</p>
</div>

<!-- Сообщения об ошибках -->
<?php if (!empty($error)): ?>
    <div class="login-error">
        <img src="/images/icons/attention_gold.png" alt="!" class="error-icon">
        <span><?= htmlspecialchars($error) ?></span>
    </div>
<?php endif; ?>

<!-- Успешное сообщение -->
<?php if (!empty($message)): ?>
    <div class="login-success">
        <img src="/images/icons/tick.png" alt="✓" class="success-icon">
        <span><?= htmlspecialchars($message) ?></span>
    </div>
<?php endif; ?>

<!-- Информация -->
<div class="restore-info">
    <p>🔐 Создайте надёжный пароль для защиты вашего аккаунта</p>
    <p class="restore-hint">Минимум 8 символов. Используйте буквы и цифры</p>
</div>

<!-- Форма установки пароля -->
<form id="setPasswordForm" class="login-form" action="/set-new-password" method="post">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token()) ?>">
    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
    
    <div class="form-group">
        <label for="password" class="form-label">
            Новый пароль
        </label>
        <div class="password-input-wrapper">
            <input id="password" type="password" name="password" class="form-input" placeholder="Введите новый пароль" required autofocus>
            <span id="togglePassword" class="password-toggle">👁</span>
        </div>
        <span class="form-hint">Минимум 8 символов</span>
    </div>

    <div class="form-group">
        <label for="confirm_password" class="form-label">
            Подтвердите пароль
        </label>
        <div class="password-input-wrapper">
            <input id="confirm_password" type="password" name="confirm_password" class="form-input" placeholder="Повторите пароль" required>
            <span id="toggleConfirmPassword" class="password-toggle">👁</span>
        </div>
        <span class="form-hint">Введите пароль ещё раз</span>
    </div>

    <button id="submit" type="submit" class="login-button restore-button">
        <span class="button-text">Сменить пароль</span>
    </button>
</form>

<!-- Дополнительные ссылки -->
<div class="login-links">
    <a href="/login" class="login-link">
        <img src="/images/icons/arr1.png" alt="" style="transform: scaleX(-1);">
        Войти в аккаунт
    </a>
    <span class="link-separator">•</span>
    <a href="/" class="login-link">
        <img src="/images/icons/home.png" alt="">
        На главную
    </a>
</div>

<script>
// Показать/скрыть пароль
document.getElementById('togglePassword')?.addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    this.textContent = type === 'password' ? '👁' : '👁‍🗨';
});

document.getElementById('toggleConfirmPassword')?.addEventListener('click', function() {
    const passwordInput = document.getElementById('confirm_password');
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    this.textContent = type === 'password' ? '👁' : '👁‍🗨';
});
</script>
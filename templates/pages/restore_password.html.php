<!-- Контент страницы восстановления пароля -->

<!-- Заголовок -->
<div class="login-header">
    <h1 class="login-title">Восстановление пароля</h1>
    <p class="login-subtitle">Верни доступ к своему аккаунту</p>
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

<!-- Изображение персонажа -->
<div class="login-character">
    <img src="/images/kollekzioner_310_blue.jpg" alt="Character">
</div>

<!-- Информация -->
<div class="restore-info">
    <p>Чтобы восстановить пароль, введите email, указанный при регистрации.</p>
    <p class="restore-hint">💡 Если письма нет во входящих, проверьте папку "Спам"</p>
</div>

<!-- Форма восстановления -->
<form id="restoreForm" class="login-form" action="/restore-password" method="post">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token()) ?>">
    
    <div class="form-group">
        <label for="email" class="form-label">
            <img src="/images/icons/a009.png" alt="" class="label-icon">
            Email адрес
        </label>
        <input id="email" type="email" name="email" class="form-input" placeholder="example@example.com" required autofocus>
    </div>

    <button id="submit" type="submit" class="login-button restore-button">
        <span class="button-text">Восстановить пароль</span>
        <span class="button-icon">📧</span>
    </button>
</form>

<!-- Дополнительные ссылки -->
<div class="login-links">
    <a href="/login" class="login-link">
        <img src="/images/icons/a001.png" alt="">
        Вспомнили пароль?
    </a>
    <span class="link-separator">•</span>
    <a href="/register" class="login-link register-link">
        Регистрация
    </a>
</div>
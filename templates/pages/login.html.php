<!-- Контент страницы входа для центрального блока -->

<!-- Заголовок -->
<div class="login-header">
    <h1 class="login-title">Вход в аккаунт</h1>
    <p class="login-subtitle">Добро пожаловать обратно в мир Azeroth</p>
</div>

<!-- Сообщения об ошибках -->
<?php if (!empty($message)): ?>
    <div class="login-error">
        <img src="/images/icons/attention_gold.png" alt="!" class="error-icon">
        <span><?= htmlspecialchars($message) ?></span>
    </div>
<?php endif; ?>

<!-- Изображение персонажа -->
<div class="login-character">
    <img src="/images/rasporyaditel_310.jpg" alt="Character">
</div>

<!-- Форма входа -->
<form id="loginForm" class="login-form" action="/login" method="post">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token()) ?>">
        
        <div class="form-group">
            <label for="username" class="form-label">
                <img src="/images/icons/a001.png" alt="" class="label-icon">
                Логин
            </label>
            <input id="username" type="text" name="username" class="form-input" placeholder="Введите ваш логин" required autofocus>
        </div>

        <div class="form-group">
            <label for="password" class="form-label">
                <img src="/images/icons/a008.png" alt="" class="label-icon">
                Пароль
            </label>
            <input id="password" type="password" name="password" class="form-input" placeholder="Введите ваш пароль" required>
        </div>

        <button id="submit" type="submit" class="login-button">
            <span class="button-text">Войти</span>
            <span class="button-icon">⚔️</span>
        </button>
    </form>

<!-- Дополнительные ссылки -->
<div class="login-links">
    <a href="/restore-password" class="login-link">
        <img src="/images/icons/book_red.png" alt="">
        Забыли пароль?
    </a>
    <span class="link-separator">•</span>
    <a href="/register" class="login-link register-link">
        Регистрация
    </a>
</div>
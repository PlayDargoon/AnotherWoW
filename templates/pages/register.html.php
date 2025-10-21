<!-- Контент страницы регистрации для центрального блока -->

<!-- Заголовок -->
<div class="login-header">
    <h1 class="login-title">Создать аккаунт</h1>
    <p class="login-subtitle">Присоединяйся к миру Azeroth</p>
</div>

<!-- Сообщения об ошибках -->
<?php if (!empty($error)): ?>
    <div class="login-error">
        <img src="/images/icons/attention_gold.png" alt="!" class="error-icon">
        <span><?= htmlspecialchars($error) ?></span>
    </div>
<?php endif; ?>

<!-- Изображение персонажа -->
<div class="login-character">
    <img src="/images/rasporyaditel_310.jpg" alt="Character">
</div>

<!-- Форма регистрации -->
<form id="registerForm" class="login-form" action="/register" method="post">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token()) ?>">
    
    <div class="form-group">
        <label for="username" class="form-label">
            <img src="/images/icons/a001.png" alt="" class="label-icon">
            Логин
        </label>
        <input id="username" type="text" name="username" class="form-input" placeholder="Используйте A-z символы" required autofocus>
        <span class="form-hint">Только латинские буквы (A-z)</span>
    </div>

    <div class="form-group">
        <label for="email" class="form-label">
            <img src="/images/icons/a009.png" alt="" class="label-icon">
            Email
        </label>
        <input id="email" type="email" name="email" class="form-input" placeholder="example@example.com" required>
        <span class="form-hint">Для восстановления доступа</span>
    </div>

    <div class="form-group">
        <label for="password" class="form-label">
            <img src="/images/icons/a008.png" alt="" class="label-icon">
            Пароль
        </label>
        <div class="password-input-wrapper">
            <input id="password" type="password" name="password" class="form-input" placeholder="Минимум 6 символов" required>
            <span id="togglePassword" class="password-toggle">👁</span>
        </div>
        <span class="form-hint">Минимум 6 символов</span>
    </div>

    <div class="form-group">
        <label for="captcha_answer" class="form-label">
            <img src="/images/icons/a004.png" alt="" class="label-icon">
            Капча
        </label>
        <div class="captcha-question">
            <?= htmlspecialchars($captchaQuestion ?? CaptchaService::getCaptchaQuestion() ?? '2 + 2 = ?') ?>
        </div>
        <input id="captcha_answer" type="number" name="captcha_answer" class="form-input" placeholder="Введите ответ" required style="max-width: 200px;">
    </div>
    
    <!-- Соглашения -->
    <div class="register-agreements">
        <label class="agreement-item">
            <input type="checkbox" id="agree_privacy" name="agree_privacy" required>
            <span>Я согласен с <a href="/privacy" target="_blank" class="agreement-link">Политикой конфиденциальности</a></span>
        </label>
        <label class="agreement-item">
            <input type="checkbox" id="agree_terms" name="agree_terms" required>
            <span>Я принимаю <a href="/terms" target="_blank" class="agreement-link">Пользовательское соглашение</a></span>
        </label>
        <label class="agreement-item">
            <input type="checkbox" id="agree_rules" name="agree_rules" required>
            <span>Я согласен с <a href="/rules" target="_blank" class="agreement-link">Правилами пользования</a></span>
        </label>
    </div>

    <button id="submit" type="submit" class="login-button register-button">
        <span class="button-text">Зарегистрироваться</span>
        <span class="button-icon">🛡️</span>
    </button>
</form>

<!-- Дополнительные ссылки -->
<div class="login-links">
    <a href="/login" class="login-link">
        <img src="/images/icons/a001.png" alt="">
        Уже есть аккаунт?
    </a>
    <span class="link-separator">•</span>
    <a href="/" class="login-link">
        На главную
    </a>
</div>

<script src="/js/password-toggle.js"></script>
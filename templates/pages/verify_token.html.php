<!-- templates/pages/verify_token.html.php -->

<div class="cabinet-page">
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
        <span class="breadcrumb-item active">Проверка токена</span>
    </nav>
    
    <h1>Проверка токена</h1>

    <?php if (!empty($error)): ?>
        <div class="login-error" style="margin-bottom:10px;">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($message)): ?>
        <div class="login-success" style="margin-bottom:10px;">
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <!-- Информация -->
    <div class="restore-info">
        <p>Пожалуйста, введите 10-значный токен, который был отправлен на ваш email.</p>
        <p class="restore-hint">💡 Если письма нет во входящих, проверьте папку "Спам"</p>
    </div>

    <!-- Форма проверки токена -->
    <form action="/verify-token" method="post" class="login-form">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token(), ENT_QUOTES, 'UTF-8') ?>">

        <div class="form-group">
            <label for="token" class="form-label">Токен</label>
            <input id="token" class="form-input" type="text" name="token" inputmode="latin" pattern="[A-Fa-f0-9]{10}" maxlength="10" placeholder="Например: a1b2c3d4e5" required>
            <span class="form-hint">Токен содержит 10 шестнадцатеричных символов (0-9, A-F).</span>
        </div>

        <button type="submit" class="login-button restore-button">
            <span class="button-text">Проверить токен</span>
        </button>
    </form>

    <div class="login-links">
        <a href="/" class="login-link">
            <img src="/images/icons/home.png" alt="">
            На главную
        </a>
        <span class="link-separator">•</span>
        <a href="/login" class="login-link">
            <img src="/images/icons/arr1.png" alt="" style="transform: scaleX(-1);">
            Вход
        </a>
        <span class="link-separator">•</span>
        <a href="/help" class="login-link">
            <img src="/images/icons/arr_left.png" alt="">
            Помощь
        </a>
    </div>
</div>
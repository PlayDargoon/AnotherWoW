<!-- templates/pages/verify_token.html.php -->

<div class="cabinet-page">
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

    <div class="cabinet-card">
        <div class="cabinet-card-title">
            <img src="/images/icons/question_blue.png" width="24" height="24" alt="*">
            Введите токен восстановления
        </div>

        <div class="restore-info" style="margin-bottom:10px">
            Пожалуйста, введите 10-значный токен, который был отправлен на ваш email.
        </div>

        <form action="/verify-token" method="post">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token(), ENT_QUOTES, 'UTF-8') ?>">

            <label for="token" class="form-label" style="display:block; margin-bottom:6px; color:#c9d1ff;">Токен</label>
            <input id="token" class="form-input" type="text" name="token" inputmode="latin" pattern="[A-Fa-f0-9]{10}" maxlength="10" placeholder="Например: a1b2c3d4e5" required>
            <div class="form-hint">Токен содержит 10 шестнадцатеричных символов (0-9, A-F).</div>

            <button type="submit" class="restore-button" style="margin-top:10px;">Проверить токен</button>
        </form>
    </div>

    <div class="login-links" style="margin-top:16px">
        <a href="/" class="link-item"><img class="i12img" src="/images/icons/home.png" alt="." width="12" height="12"> На главную</a>
        <a href="/restore-password" class="link-item"><img class="i12img" src="/images/icons/arr_left.png" alt="." width="12" height="12"> Назад к восстановлению</a>
        <a href="/help" class="link-item"><img class="i12img" src="/images/icons/question_blue.png" alt="." width="12" height="12"> Помощь</a>
    </div>
</div>
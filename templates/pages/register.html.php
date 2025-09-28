<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <link rel="stylesheet" href="/css/style.css">
    <script src="/js/captcha.js" defer></script>
</head>
<body>


   <div class="small">
<ul class="feedbackPanel">
<li class="feedbackPanelERROR">
<span class="feedbackPanelERROR">

<?php if (!empty($error)): ?>
        
            <?= htmlspecialchars($error) ?>
        
    <?php endif; ?>

</span>
</li>
</ul>
</div>

<div class="body">

    <h1>Регистрация</h1>

    <div class="pt" style="text-align:center">
        <img src="/images/rasporyaditel_310.jpg" width="310" height="103" alt="?">
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" style="margin-top: 20px;">
            <p><?= htmlspecialchars($error) ?></p>
        </div>
    <?php endif; ?>

    <form id="loginForm" action="/register" method="post">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token()) ?>">
        <div>
            <label for="username"><span class="info">Логин</span>:</label><br>
            <div style="display: flex; align-items: center; gap: 10px;">
                <input id="username" type="text" value="" name="username" required>
                <span style="color: #cccccc; font-size: 12px; white-space: nowrap;">A-z</span>
            </div>
        </div>
        <div>
            <label for="email"><span class="info">Email</span>:</label><br>
            <div style="display: flex; align-items: center; gap: 10px;">
                <input id="email" type="email" value="" name="email" required>
                <span style="color: #cccccc; font-size: 12px; white-space: nowrap;">example@example.com</span>
            </div>
        </div>
        <div class="pt">
            <label for="password"><span class="info">Пароль</span>:</label><br>
            <div style="display: flex; align-items: center; gap: 8px;">
                <input id="password" type="password" value="" name="password" required>
                <span id="togglePassword" style="cursor: pointer; color: yellow; font-size: 14px; user-select: none; min-width: 20px;">👁</span>
                <span style="color: #cccccc; font-size: 12px; white-space: nowrap; margin-left: 5px;">минимум 6 символов</span>
            </div>
        </div>
        <div class="pt">
            <label for="captcha_answer"><span class="info">Капча</span>:</label><br>
            <div class="captcha-question">
                <?= htmlspecialchars($captchaQuestion ?? CaptchaService::getCaptchaQuestion() ?? '2 + 2 = ?') ?>
            </div>
            <input id="captcha_answer" type="number" name="captcha_answer" placeholder="Введите ответ" required style="width: 150px; margin-top: 5px;">
        </div>
        
        <div class="pt" style="margin-top: 15px;">
            <div style="margin-bottom: 10px;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" id="agree_privacy" name="agree_privacy" required style="margin-right: 8px;">
                    <span style="color: #ffffff;">Я согласен с <a href="/privacy" target="_blank" style="color: #ffff33;">Политикой конфиденциальности</a></span>
                </label>
            </div>
            <div style="margin-bottom: 10px;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" id="agree_terms" name="agree_terms" required style="margin-right: 8px;">
                    <span style="color: #ffffff;">Я принимаю <a href="/terms" target="_blank" style="color: #ffff33;">Пользовательское соглашение</a></span>
                </label>
            </div>
            <div style="margin-bottom: 10px;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" id="agree_rules" name="agree_rules" required style="margin-right: 8px;">
                    <span style="color: #ffffff;">Я согласен с <a href="/rules" target="_blank" style="color: #ffff33;">Правилами пользования</a></span>
                </label>
            </div>
        </div>
        
        <div class="pt">
            <input id="submit" type="submit" class="headerButton _c-pointer" name="p::submit" value="Зарегистрироваться">
        </div>
    </form>

    <div class="pt">
        <span class="small">
            Нажимая кнопку "Зарегистрироваться", Вы подтверждаете своё согласие с условиями выше.
        </span>
    </div>

</div>

<div class="footer nav block-border-top">
    <ol>
        <li>
            <img class="i12img" src="/images/icons/home.png" alt="." width="12px" height="12px"> <a href="/">На главную</a>
        </li>
        <li>
            <img class="i12img" src="/images/icons/question_blue.png" alt="." width="12px" height="12px"> <a href="#">Первая помощь</a>
        </li>
    </ol>
</div>

</body>
</html>
<!-- templates/pages/set_new_password.html.php -->

<div class="touch-influenced block-border">

<div class="exp-head-out">
    <div>
        <div class="exp-head-in" ></div>
    </div>
</div>

<?php if (!empty($error)): ?>
    <div class="small">
        <ul class="feedbackPanel">
            <li class="feedbackPanelERROR">
                <span class="feedbackPanelERROR"><?= $error ?></span>
            </li>
        </ul>
    </div>
<?php endif; ?>

<?php if (!empty($message)): ?>
    <div class="small">
        <ul class="feedbackPanel">
            <li class="feedbackPanelINFO">
                <span class="feedbackPanelINFO"><?= $message ?></span>
            </li>
        </ul>
    </div>
<?php endif; ?>

<div class="body">
    <h1>Установка нового пароля</h1>
</div>

<div class="body">
    <div class="pb">
        Введите новый пароль и подтвердите его.
    </div>
    <div class="small minor p2">
        Пароль должен быть не менее 8 символов.
    </div>
    <div class="pt">
        <form action="/set-new-password" method="post">
            <input type="hidden" name="token" value="<?= $token ?>">
            <label for="password"><span class="info">Новый пароль</span>:</label><br>
            <input id="password" type="password" name="password" required>
            <br>
            <label for="confirm_password"><span class="info">Подтвердите пароль</span>:</label><br>
            <input id="confirm_password" type="password" name="confirm_password" required>
            <br>
            <input type="submit" class="_c-pointer" name="p::submit" value="Сменить пароль">
        </form>
    </div>
</div>

<div class="footer nav block-border-top">
    <ol>
        <li>
            <img class="i12img" src="/images/icons/home.png" alt="." width="12px" height="12px">
            <a href="/">На главную</a>
        </li>
        <li>
            <img class="i12img" src="/images/icons/question_blue.png" alt="." width="12px" height="12px">
            <a href="#">Первая помощь</a>
        </li>
    </ol>
</div>

</div>
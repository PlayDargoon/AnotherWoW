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
    <h1>Забыли пароль?</h1>
</div>

<div class="body">
    <div class="pb">
        Чтобы восстановить пароль, введите email, указанный при регистрации.<br>
        Если данные верны, на указанный email будет отправлена ссылка для восстановления пароля.
    </div>
    <div class="small minor p2">
        Подсказка: если письма нет во входящих, проверьте папку "Спам" или "Нежелательную почту".
    </div>
    <div class="pt">
        <form action="/restore-password" method="post">
            <label for="email"><span class="info">Email адрес</span>:</label><br>
            <input id="email" type="email" name="email" required>
            <br>
            <input type="submit" class="_c-pointer" name="p::submit" value="Восстановить пароль">
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
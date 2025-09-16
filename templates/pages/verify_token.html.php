<!-- templates/pages/verify_token.html.php -->

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
                <span class="feedbackPanelERROR"><?= htmlspecialchars($error) ?></span>
            </li>
        </ul>
    </div>
<?php endif; ?>

<?php if (!empty($message)): ?>
    <div class="small">
        <ul class="feedbackPanel">
            <li class="feedbackPanelINFO">
                <span class="feedbackPanelINFO"><?= htmlspecialchars($message) ?></span>
            </li>
        </ul>
    </div>
<?php endif; ?>

<div class="body">
    <h1>Введите токен восстановления</h1>
</div>

<div class="body">
    <div class="pb">
        Введите токен, который был отправлен на ваш email.
    </div>
    <div class="small minor p2">
        Токен должен быть длиной 10 символов.
    </div>
    <div class="pt">
        <form action="/verify-token" method="post">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token()) ?>">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
            <label for="token"><span class="info">Токен</span>:</label><br>
            <input id="token" type="text" name="token" required>
            <br>
            <input type="submit" class="_c-pointer" name="p::submit" value="Проверить токен">
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
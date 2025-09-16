<!-- templates/pages/admin_panel.html.php -->



<?php
$userId = $_SESSION['user_id'] ?? null;
$accessLevel = 0;
if ($userId && class_exists('User')) {
    $userModel = new User(DatabaseConnection::getAuthConnection());
    $accessLevel = $userModel->getUserAccessLevel($userId);
}
?>
<div class="body">
    <h1>Административная панель</h1>
</div>

<?php if ($accessLevel >= 4): ?>
<div class="body">
    <div class="pt">
        <div class="small">
            Добро пожаловать в административную панель!<br/>
            Здесь вы можете управлять основными функциями сайта и контролировать его активность.
        </div>
    </div>

    <div class="pt">
        <div>
            <a href="#">
                <img src="/images/icons/wrench.png" width="12" height="12" alt="*">
                Управление контентом
            </a>
        </div>
        <div>
            <a href="#">
                <img src="/images/icons/users.png" width="12" height="12" alt="*">
                Управление пользователями
            </a>
        </div>
        <div>
            <a href="#">
                <img src="/images/icons/settings.png" width="12" height="12" alt="*">
                Настройки сайта
            </a>
        </div>
        <div>
            <a href="/admin-online">
                <img src="/images/icons/health.png" width="12" height="12" alt="*">
                Игроки онлайн
            </a>
        </div>
        <div>
            <a href="/news/manage">
                <img src="/images/icons/book_red.png" width="12" height="12" alt="*">
                Управление новостями
            </a>
        </div>
    </div>

    <div class="pt">
        <div>
            <img src="/images/icons/question_blue.png" width="12" height="12" alt="*">
            <a href="#">Справка и поддержка</a>
        </div>
    </div>
</div>
<?php else: ?>
<div class="body">
    <div class="small" style="color:red;">У вас нет прав для доступа к административной панели.</div>
</div>
<?php endif; ?>

<div class="footer nav block-border-top">
    <ol>
        <li>
            <img class="i12img" src="/images/icons/home.png" alt="." width="12px" height="12px">
            <a href="/">На главную</a>
        </li>
        <li>
            <img src="/images/icons/cross.png" alt="." width="12" height="12">
            <a href="/logout">Выйти из аккаунта</a>
        </li>
        <li>
<img src="/images/icons/home.png" width="12" height="12">
            <a href="/cabinet">В кабинет</a>
</li>
    </ol>
</div>


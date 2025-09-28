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


<div class="section-sep"></div>

    <div class="pt">
        <ol>
             <li><img src="/images/icons/health.png" width="12" height="12" alt="*"> <a href="/admin-online">Игроки онлайн</a></li>
             <li><img src="/images/icons/book_red.png" width="12" height="12" alt="*"> <a href="/news/manage">Управление новостями</a></li>
             <li><img src="/images/icons/coins.png" width="12" height="12" alt="*"> <a href="/admin/coins">Начисление бонусов</a></li>
        </ol>
    </div>
<div class="section-sep"></div>
   
</div>
<?php else: ?>
<div class="body">
    <div class="small" style="color:red;">У вас нет прав для доступа к административной панели.</div>
</div>
<?php endif; ?>

<div class="footer nav block-border-top">
    <ol>
        <li><img class="i12img" src="/images/icons/home.png" alt="." width="12px" height="12px"> <a href="/">На главную</a></li>
        <li><img src="/images/icons/arr_left.png" width="12" height="12"> <a href="/cabinet">В кабинет</a></li>
    </ol>
</div>


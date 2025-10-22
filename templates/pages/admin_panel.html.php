<!-- templates/pages/admin_panel.html.php (premium) -->
<?php
$userId = $_SESSION['user_id'] ?? null;
$accessLevel = 0;
if ($userId && class_exists('User')) {
    $userModel = new User(DatabaseConnection::getAuthConnection());
    $accessLevel = $userModel->getUserAccessLevel($userId);
}
?>

<div class="cabinet-page">
    <!-- Хлебные крошки -->
    <nav class="breadcrumbs">
        <span class="breadcrumb-item">
            <a href="/">Главная</a>
        </span>
        <span class="breadcrumb-separator">›</span>
        <span class="breadcrumb-item">
            <a href="/cabinet">Личный кабинет</a>
        </span>
        <span class="breadcrumb-separator">›</span>
        <span class="breadcrumb-item active">Админ-панель</span>
    </nav>
    
    <h1>Административная панель</h1>

    <?php if ($accessLevel >= 4): ?>
        <div class="cabinet-card" style="margin-bottom:12px;">
            <div class="cabinet-card-title">
                <img src="/images/icons/settings.png" width="20" height="20" alt="*">
                Доступные разделы
            </div>
            <ul class="action-list" style="margin-top:6px;">
                <li><a class="link-item" href="/admin-online"><img src="/images/icons/health.png" width="12" height="12" alt="*"> Игроки онлайн</a></li>
                <li><a class="link-item" href="/news/manage"><img src="/images/icons/book_red.png" width="12" height="12" alt="*"> Управление новостями</a></li>
                <li><a class="link-item" href="/admin/coins"><img src="/images/icons/coins.png" width="12" height="12" alt="*"> Начисление бонусов</a></li>
            </ul>
        </div>
    <?php else: ?>
        <div class="cabinet-card" style="border-left: 3px solid #ff6b6b;">
            <div class="cabinet-card-title">
                <img src="/images/icons/lock.png" width="20" height="20" alt="!">
                Нет доступа
            </div>
            <div class="info-main-text">У вас нет прав для доступа к административной панели.</div>
        </div>
    <?php endif; ?>

    <div class="login-links" style="margin-top:12px;">
        <a class="link-item" href="/"><img class="i12img" src="/images/icons/home.png" alt="." width="12" height="12"> На главную</a>
        <a class="link-item" href="/cabinet"><img class="i12img" src="/images/icons/arr_left.png" alt="." width="12" height="12"> В кабинет</a></div>
</div>


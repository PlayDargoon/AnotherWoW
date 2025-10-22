<div class="cabinet-page">
    
    <!-- Хлебные крошки -->
    <nav class="breadcrumbs">
        <a href="/" class="breadcrumb-item">Главная</a>
        <span class="breadcrumb-separator">›</span>
        <a href="/cabinet" class="breadcrumb-item">Личный кабинет</a>
        <span class="breadcrumb-separator">›</span>
        <a href="/shop" class="breadcrumb-item">Магазин</a>
        <span class="breadcrumb-separator">›</span>
        <span class="breadcrumb-item active">История покупок</span>
    </nav>

    <h1>История покупок</h1>

    <div class="cabinet-card">
        <div class="cabinet-card-title">
            <img src="/images/icons/journal_12.png" width="24" height="24" alt="*">
            Все транзакции магазина
        </div>

        <?php if (!empty($history)): ?>
            <div class="table-responsive">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th style="text-align:center"><img src="/images/icons/clock.png" width="12" height="12" alt="*"> Дата и время</th>
                            <th><img src="/images/icons/forum_scroll.png" width="12" height="12" alt="*"> Операция</th>
                            <th style="text-align:center"><img src="/images/icons/money.png" width="12" height="12" alt="*"> Сумма</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($history as $row): ?>
                            <tr>
                                <td class="tc"><span class="minor"><?= date('d.m.Y H:i:s', strtotime($row['created_at'])) ?></span></td>
                                <td><?= htmlspecialchars($row['reason'], ENT_QUOTES, 'UTF-8') ?></td>
                                <?php $color = ((int)$row['coins'] < 0) ? '#ff6b6b' : '#79e27d'; ?>
                                <td class="tc"><strong style="color: <?= $color ?>;"><?= (int)$row['coins'] ?> бонусов</strong></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div style="padding: 20px; text-align: center;">
                <img src="/images/icons/question_blue.png" width="32" height="32" alt="*">
                <p class="minor">У вас пока нет покупок в магазине</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Дополнительные ссылки -->
    <div class="login-links">
        <a href="/" class="login-link">
            <img src="/images/icons/home.png" alt="">
            На главную
        </a>
        <span class="link-separator">•</span>
        <a href="/cabinet" class="login-link">
            <img src="/images/icons/arr_left.png" alt="">
            В кабинет
        </a>
        <span class="link-separator">•</span>
        <a href="/shop" class="login-link">
            <img src="/images/icons/arr1.png" alt="" style="transform: scaleX(-1);">
            Назад в магазин
        </a>
    </div>

</div>
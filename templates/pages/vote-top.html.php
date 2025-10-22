<?php
// templates/pages/vote-top.html.php (premium)

// Массив русских названий месяцев
$months = [
    'January' => 'Январь',
    'February' => 'Февраль',
    'March' => 'Март',
    'April' => 'Апрель',
    'May' => 'Май',
    'June' => 'Июнь',
    'July' => 'Июль',
    'August' => 'Август',
    'September' => 'Сентябрь',
    'October' => 'Октябрь',
    'November' => 'Ноябрь',
    'December' => 'Декабрь'
];
$currentMonthEn = date('F');
$currentMonthRu = $months[$currentMonthEn] ?? $currentMonthEn;
$currentYear = date('Y');
?>

<div class="cabinet-page">

    <!-- Хлебные крошки -->
    <nav class="breadcrumbs">
        <a href="/" class="breadcrumb-item">Главная</a>
        <span class="breadcrumb-separator">›</span>
        <a href="/vote" class="breadcrumb-item">Голосование</a>
        <span class="breadcrumb-separator">›</span>
        <span class="breadcrumb-item active">Топ голосующих</span>
    </nav>

    <h1>Топ голосующих — <?= htmlspecialchars($currentMonthRu) ?> <?= htmlspecialchars($currentYear) ?></h1>

    <?php if (empty($topVoters)): ?>
        <div class="cabinet-card">
            <div class="cabinet-card-title">
                <img src="/images/icons/Gold.webp" width="20" height="20" alt="*">
                Пока пусто
            </div>
            <div class="info-main-text" style="margin-top:6px;">
                Пока никто не голосовал за сервер. Станьте первым!
            </div>
            <div class="login-links" style="margin-top:10px;">
                <a href="/vote" class="login-link">
                    <img src="/images/icons/arr1.png" alt="" style="transform: scaleX(-1);">
                    Перейти к голосованию
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="cabinet-card" style="margin-bottom:12px;">
            <div class="cabinet-card-title">

                Статистика за <?= htmlspecialchars($currentMonthRu) ?>
            </div>
            <div class="cabinet-info-list">
                <div class="info-row">
                    <span class="info-label">Голосующих игроков</span>
                    <span class="info-value"><?= (int)($monthlyStats['total_voters'] ?? 0) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Голосований</span>
                    <span class="info-value"><?= (int)($monthlyStats['total_vote_records'] ?? 0) ?></span>
                </div>
                <?php if (!empty($monthlyStats['last_vote'])): ?>
                    <div class="info-row">
                        <span class="info-label">Последний голос</span>
                        <span class="info-value"><?= date('d.m.Y H:i', strtotime($monthlyStats['last_vote'])) ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="cabinet-card">
            <div class="cabinet-card-title">

                Текущий рейтинг
            </div>
            <div class="table-responsive">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th>Место</th>
                            <th>Игрок</th>
                            <th style="text-align:center;">Голосований</th>
                            <th style="text-align:center;">Последний голос</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($topVoters as $index => $voter): ?>
                            <?php
                            $place = $index + 1;
                            $placeDisplay = $place . '.';
                            $medalImg = '';
                            $rowStyle = '';
                            $nameStyle = '';
                            
                            switch ($place) {
                                case 1:
                                    $medalImg = ' <img src="/images/icons/Gold.webp" width="16" height="16" alt="Золото" style="vertical-align: middle;">';
                                    $rowStyle = 'background: linear-gradient(135deg, rgba(255, 215, 0, 0.15), rgba(255, 215, 0, 0.05));';
                                    $nameStyle = 'color: #ffd700; text-shadow: 0 0 10px rgba(255, 215, 0, 0.5);';
                                    break;
                                case 2:
                                    $medalImg = ' <img src="/images/icons/Silver.webp" width="16" height="16" alt="Серебро" style="vertical-align: middle;">';
                                    $rowStyle = 'background: linear-gradient(135deg, rgba(192, 192, 192, 0.15), rgba(192, 192, 192, 0.05));';
                                    $nameStyle = 'color: #c0c0c0; text-shadow: 0 0 10px rgba(192, 192, 192, 0.5);';
                                    break;
                                case 3:
                                    $medalImg = ' <img src="/images/icons/Copper.webp" width="16" height="16" alt="Бронза" style="vertical-align: middle;">';
                                    $rowStyle = 'background: linear-gradient(135deg, rgba(205, 127, 50, 0.15), rgba(205, 127, 50, 0.05));';
                                    $nameStyle = 'color: #cd7f32; text-shadow: 0 0 10px rgba(205, 127, 50, 0.5);';
                                    break;
                            }
                            ?>
                            <tr style="<?= $rowStyle ?>">
                                <td><strong><?= $placeDisplay . $medalImg ?></strong></td>
                                <td><strong style="<?= $nameStyle ?>"><?= htmlspecialchars($voter['username']) ?></strong></td>
                                <td style="text-align:center" class="gold"><strong><?= (int)$voter['vote_count'] ?></strong></td>
                                <td style="text-align:center" class="minor">
                                    <?= $voter['last_vote'] ? date('d.m.Y H:i', strtotime($voter['last_vote'])) : '-' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="cabinet-card" style="margin-top:12px;">
            <div class="cabinet-card-title">
                <img src="/images/icons/attention_gold.png" width="20" height="20" alt="!">
                Как попасть в топ
            </div>
            <ul class="document-list" style="margin-top:6px;">
                <li>Голосуйте за сервер на <a href="https://wow.mmotop.ru/servers/36327/votes/new" target="_blank">MMOTOP</a> каждые 16 часов.</li>
                <li>За каждое голосование: 1 голос = 1 монета (бонус).</li>
                <li>Чем больше голосов за месяц — тем выше ваше место в рейтинге.</li>
                <li><strong class="gold">Рейтинг сбрасывается 1 числа каждого месяца.</strong></li>
                <li>Рейтинг обновляется в реальном времени.</li>
            </ul>
            <div class="login-links" style="margin-top:10px;">
                <a href="/vote" class="login-link">
                    <img src="/images/icons/arr1.png" alt="" style="transform: scaleX(-1);">
                    Перейти к голосованию
                </a>
            </div>
        </div>
    <?php endif; ?>

    <!-- Дополнительные ссылки -->
    <div class="login-links" style="margin-top:16px">
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
        <a href="/vote" class="login-link">
            <img src="/images/icons/arr1.png" alt="" style="transform: scaleX(-1);">
            К голосованию
        </a>
    </div>
</div>
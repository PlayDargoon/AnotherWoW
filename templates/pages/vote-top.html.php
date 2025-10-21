<?php
// templates/pages/vote-top.html.php (premium)

// Массив русских названий месяцев
$months = [
    'January' => 'Январь', 'February' => 'Февраль', 'March' => 'Март',
    'April' => 'Апрель', 'May' => 'Май', 'June' => 'Июнь',
    'July' => 'Июль', 'August' => 'Август', 'September' => 'Сентябрь',
    'October' => 'Октябрь', 'November' => 'Ноябрь', 'December' => 'Декабрь'
];
$currentMonthEn = date('F');
$currentMonthRu = $months[$currentMonthEn] ?? $currentMonthEn;
$currentYear = date('Y');
?>

<div class="cabinet-page">
    <h1>🏆 Топ голосующих — <?= htmlspecialchars($currentMonthRu) ?> <?= htmlspecialchars($currentYear) ?></h1>

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
                <a class="link-item" href="/vote">Перейти к голосованию</a>
            </div>
        </div>
    <?php else: ?>
        <div class="cabinet-card" style="margin-bottom:12px;">
            <div class="cabinet-card-title">
                <img src="/images/icons/journal_12.png" width="20" height="20" alt="*">
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
                <img src="/images/icons/Gold.webp" width="20" height="20" alt="*">
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
                                $placeIcon = '';
                                switch ($place) {
                                    case 1: $placeIcon = '🥇'; break;
                                    case 2: $placeIcon = '🥈'; break;
                                    case 3: $placeIcon = '🥉'; break;
                                    default: $placeIcon = '🏅 ' . $place; break;
                                }
                            ?>
                            <tr>
                                <td><strong><?= $placeIcon ?></strong></td>
                                <td><strong><?= htmlspecialchars($voter['username']) ?></strong></td>
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
                <a class="link-item" href="/vote">Перейти к голосованию</a>
            </div>
        </div>
    <?php endif; ?>

    <div class="login-links" style="margin-top:16px">
        <a class="link-item" href="/"><img class="i12img" src="/images/icons/home.png" alt="." width="12" height="12"> На главную</a>
        <a class="link-item" href="/cabinet"><img class="i12img" src="/images/icons/arr_left.png" alt="." width="12" height="12"> В кабинет</a>
    </div>
</div>
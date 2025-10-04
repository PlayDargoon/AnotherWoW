<?php
// templates/pages/vote-top.html.php

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
<div class="body">
    <h2>🏆 Топ 10 голосующих за <?= $currentMonthRu ?> <?= $currentYear ?></h2>
    
    <div class="pt">
        <div class="info">
            <img src="/images/icons/Gold.webp" width="16" height="16" alt="*">
            <strong>Рейтинг самых активных голосующих игроков Azeroth за текущий месяц</strong>
            <br>
            <small class="minor">Обновляется в реальном времени • Счетчик сбрасывается 1 числа каждого месяца</small>
        </div>
    </div>

    <br>

    <?php if (empty($topVoters)): ?>
        <div class="bluepost">
            <p>Пока что никто не голосовал за сервер. Станьте первым!</p>
            <p><a href="/vote" class="btn-link">Перейти к голосованию</a></p>
        </div>
    <?php else: ?>
        
        <div class="pt">
            <div class="minor" style="margin-bottom: 10px;">
                📊 <strong>Статистика за <?= $currentMonthRu ?>:</strong>
                Голосующих игроков: <strong class="info"><?= $monthlyStats['total_voters'] ?? 0 ?></strong> •
                Голосований: <strong class="info"><?= $monthlyStats['total_vote_records'] ?? 0 ?></strong> 🗳️ •
                Всего очков: <strong class="gold"><?= $monthlyStats['total_votes'] ?? 0 ?></strong>
                <?php if (!empty($monthlyStats['last_vote'])): ?>
                    • Последний голос: <span class="minor"><?= date('d.m.Y H:i', strtotime($monthlyStats['last_vote'])) ?></span>
                <?php endif; ?>
            </div>
            
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid #999999;">
                        <th style="padding: 8px; text-align: left; color: #ff6600;">Место</th>
                        <th style="padding: 8px; text-align: left; color: #ff6600;">Игрок</th>
                        <th style="padding: 8px; text-align: center; color: #ff6600;">Голосований</th>
                        <th style="padding: 8px; text-align: center; color: #ff6600;">Очков</th>
                        <th style="padding: 8px; text-align: center; color: #ff6600;">Последний голос</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($topVoters as $index => $voter): ?>
                        <?php 
                        $place = $index + 1;
                        $placeClass = '';
                        $placeIcon = '';
                        
                        // Определяем стили для мест
                        switch ($place) {
                            case 1:
                                $placeClass = 'iLegendary'; // Оранжевый
                                $placeIcon = '🥇';
                                break;
                            case 2:
                                $placeClass = 'iEpic'; // Фиолетовый  
                                $placeIcon = '🥈';
                                break;
                            case 3:
                                $placeClass = 'iSuperior'; // Синий
                                $placeIcon = '🥉';
                                break;
                            case 4:
                            case 5:
                                $placeClass = 'iGood'; // Зеленый
                                $placeIcon = '🎖️ ' . $place;
                                break;
                            case 6:
                            case 7:
                            case 8:
                            case 9:
                            case 10:
                                $placeClass = 'info'; // Базовый цвет
                                $placeIcon = '🏅 ' . $place;
                                break;
                            default:
                                $placeClass = 'minor'; // Серый цвет
                                $placeIcon = $place;
                                break;
                        }
                        ?>
                        
                        <tr style="border-bottom: 1px solid #333333;">
                            <td style="padding: 8px; font-weight: bold;" class="<?= $placeClass ?>">
                                <?= $placeIcon ?>
                            </td>
                            <td style="padding: 8px;">
                                <strong><?= htmlspecialchars($voter['username']) ?></strong>
                            </td>
                            <td style="padding: 8px; text-align: center;" class="gold">
                                <strong><?= $voter['vote_count'] ?></strong> 🗳️
                            </td>
                            <td style="padding: 8px; text-align: center;" class="info">
                                <?= (int)($voter['total_coins'] ?? 0) ?>
                            </td>
                            <td style="padding: 8px; text-align: center; font-size: small;" class="minor">
                                <?= $voter['last_vote'] ? date('d.m.Y H:i', strtotime($voter['last_vote'])) : '-' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <br>
        
        <div class="bluepost">
            <h3>🏅 Как попасть в топ?</h3>
            <ul>
                <li>Голосуйте за сервер на <a href="https://wow.mmotop.ru/servers/36327/votes/new" target="_blank">MMOTOP</a> каждые 16 часов</li>
                <li>За каждое голосование вы получите:</li>
                <li style="margin-left: 20px;">• Обычный код: 1 голос = 1 монета</li>
                <li style="margin-left: 20px;">• Премиум код: 1 голосование = 100 голосов = 100 монет</li>
                <li>Чем больше голосов за месяц - тем выше ваше место в рейтинге</li>
                <li><strong class="gold">Рейтинг сбрасывается 1 числа каждого месяца!</strong></li>
                <li>Рейтинг обновляется в реальном времени!</li>
            </ul>
            
            <p><strong>Начните голосовать прямо сейчас:</strong> <a href="/vote" class="btn-link">Перейти к голосованию</a></p>
        </div>

    <?php endif; ?>

    <br>
    
    
</div>

<div class="footer nav block-border-top">
    <ol>
        <li>
            <img src="/images/icons/home.png" alt="." width="12" height="12" class="i12img"> <a href="/" class=""><span>На главную</span></a>
        </li>
        <li>
            <img src="/images/icons/arr_left.png" alt="." width="12" height="12" class="i12img"> <a href="/cabinet" class=""><span>В кабинет</span></a>
        </li>
        
    </ol>
</div>
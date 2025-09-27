<?php
// templates/pages/vote-top.html.php
?>
<div class="body">
    <h2>🏆 Топ голосующих за сервер</h2>
    
    <div class="pt">
        <div class="info">
            <img src="/images/icons/Gold.webp" width="16" height="16" alt="*">
            <strong>Рейтинг самых активных голосующих игроков Azeroth</strong>
            <br>
            <small class="minor">Обновляется в реальном времени на основе начислений за голосование</small>
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
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid #999999;">
                        <th style="padding: 8px; text-align: left; color: #ff6600;">Место</th>
                        <th style="padding: 8px; text-align: left; color: #ff6600;">Игрок</th>
                        <th style="padding: 8px; text-align: center; color: #ff6600;">Количество голосов</th>
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
                            default:
                                $placeClass = 'iGood'; // Зеленый
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
                            <td style="padding: 8px; text-align: center;" class="info">
                                <strong><?= $voter['vote_count'] ?></strong>
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
                <li>За каждый голос вы получите 1 монету</li>
                <li>Чем больше голосов - тем выше ваше место в рейтинге</li>
                <li>Рейтинг обновляется в реальном времени!</li>
            </ul>
            
            <p><strong>Начните голосовать прямо сейчас:</strong> <a href="/vote" class="btn-link">Перейти к голосованию</a></p>
        </div>

    <?php endif; ?>

    <br>
    
    <div class="pt" style="text-align: center;">
        <a href="/vote" class="btn-link">← Вернуться к голосованию</a>
    </div>
</div>
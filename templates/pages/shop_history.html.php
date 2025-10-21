<div class="cabinet-page">
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
                            <th><img src="/images/icons/service.png" width="12" height="12" alt="*"> Операция</th>
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

    <div class="login-links" style="margin-top:16px">
        <a class="link-item" href="/"><img class="i12img" src="/images/icons/home.png" alt="." width="12" height="12"> На главную</a>
        <a class="link-item" href="/cabinet"><img class="i12img" src="/images/icons/menialo.png" alt="." width="12" height="12"> В кабинет</a>
        <a class="link-item" href="/shop"><img class="i12img" src="/images/icons/shop.png" alt="." width="12" height="12"> Назад в магазин</a>
    </div>
</div>
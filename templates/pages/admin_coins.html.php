<!-- templates/pages/admin_coins.html.php -->
<div class="body">
    <div class="pt">
        <h2>Начисление/списание бонусов</h2>
        <div class="section-sep"></div>
        <?php if (!empty($message)): ?>
            <div class="pt" style="color:#d33; font-weight:bold;"> <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?> </div>
        <?php endif; ?>
        <form method="post" action="/admin/coins" style="margin-top:10px;">
            <div class="pt">
                <label for="login">Логин аккаунта:</label><br>
                <input type="text" id="login" name="login" required value="<?= htmlspecialchars($form['login'] ?? '', ENT_QUOTES, 'UTF-8') ?>" style="width:220px;">
            </div>
            <div class="pt">
                <label for="amount">Сумма (можно отрицательную):</label><br>
                <input type="number" id="amount" name="amount" required value="<?= htmlspecialchars($form['amount'] ?? '', ENT_QUOTES, 'UTF-8') ?>" style="width:120px;">
            </div>
            <div class="pt">
                <label for="reason">Причина (опционально):</label><br>
                <input type="text" id="reason" name="reason" value="<?= htmlspecialchars($form['reason'] ?? '', ENT_QUOTES, 'UTF-8') ?>" style="width:320px;">
            </div>
            <div class="pt">
                <button type="submit" class="btn">Выполнить</button>
                <a class="btn" href="/admin-panel" style="margin-left:8px;">Назад</a>
            </div>
        </form>
    </div>

    <?php if (!empty($history)): ?>
    <div class="pt">
        <h3>Последние операции для логина: <span style="color:#0070dd; font-weight:normal;"><?= htmlspecialchars($historyLogin, ENT_QUOTES, 'UTF-8') ?></span></h3>
        <div class="section-sep"></div>
        <table style="width:100%; border-collapse:collapse; margin-top:8px;">
            <thead>
                <tr>
                    <td style="padding:6px; font-weight:bold;">Дата</td>
                    <td style="padding:6px; font-weight:bold; text-align:center;">Сумма</td>
                    <td style="padding:6px; font-weight:bold;">Причина</td>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($history as $row): ?>
                <?php
                    $raw = $row['created_at'] ?? '';
                    $ts = is_numeric($raw) ? (int)$raw : strtotime((string)$raw);
                    $dateText = $ts ? date('d.m.Y H:i:s', $ts) : htmlspecialchars((string)$raw, ENT_QUOTES, 'UTF-8');
                    $amount = (int)($row['coins'] ?? 0);
                    $color = $amount >= 0 ? '#2c7' : '#d33';
                    $reason = htmlspecialchars((string)($row['reason'] ?? ''), ENT_QUOTES, 'UTF-8');
                ?>
                <tr>
                    <td style="padding:6px;"><?= $dateText ?></td>
                    <td style="padding:6px; text-align:center;"><strong style="color:<?= $color ?>;"> <?= $amount >= 0 ? '+' : '' ?><?= $amount ?> </strong></td>
                    <td style="padding:6px;"><?= $reason ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<div class="footer nav block-border-top">
    <ol>
        <li><img class="i12img" src="/images/icons/home.png" alt="." width="12" height="12"> <a href="/">На главную</a></li>
        <li><img class="i12img" src="/images/icons/home.png" alt="." width="12" height="12"> <a href="/cabinet">В кабинет</a></li>
    </ol>
</div>

<!-- templates/pages/admin_coins.html.php (premium) -->
<div class="cabinet-page">
    <div class="cabinet-card">
        <div class="cabinet-card-title">
            <img src="/images/icons/gold.png" width="20" height="20" alt="*">
            Начисление/списание бонусов
        </div>

        <?php if (!empty($message)): ?>
            <div class="info-main-text" style="color:#d33; font-weight:600; margin-top:8px;">
                <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form method="post" action="/admin/coins" class="login-form" style="margin-top:12px;">
            <div class="input-group">
                <label for="login">Логин аккаунта</label>
                <input type="text" id="login" name="login" required value="<?= htmlspecialchars($form['login'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Введите логин">
            </div>
            <div class="input-group">
                <label for="amount">Сумма</label>
                <input type="number" id="amount" name="amount" required value="<?= htmlspecialchars($form['amount'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Можно отрицательную для списания">
                <div class="minor">Можно отрицательную — для списания</div>
            </div>
            <div class="input-group">
                <label for="reason">Причина</label>
                <select id="reason" name="reason">
                    <option value="За активность на форуме" <?= ($form['reason'] ?? '') === 'За активность на форуме' ? 'selected' : '' ?>>За активность на форуме</option>
                    <option value="За участие в ивенте" <?= ($form['reason'] ?? '') === 'За участие в ивенте' ? 'selected' : '' ?>>За участие в ивенте</option>
                    <option value="За помощь игрокам" <?= ($form['reason'] ?? '') === 'За помощь игрокам' ? 'selected' : '' ?>>За помощь игрокам</option>
                    <option value="За баг-репорт" <?= ($form['reason'] ?? '') === 'За баг-репорт' ? 'selected' : '' ?>>За баг-репорт</option>
                    <option value="Компенсация" <?= ($form['reason'] ?? '') === 'Компенсация' ? 'selected' : '' ?>>Компенсация</option>
                    <option value="Бонус от администрации" <?= ($form['reason'] ?? '') === 'Бонус от администрации' ? 'selected' : '' ?>>Бонус от администрации</option>
                    <option value="Корректировка баланса" <?= ($form['reason'] ?? '') === 'Корректировка баланса' ? 'selected' : '' ?>>Корректировка баланса</option>
                    <option value="Другое" <?= ($form['reason'] ?? '') === 'Другое' ? 'selected' : '' ?>>Другое</option>
                </select>
            </div>
            <div class="restore-button" style="margin-top:8px;">
                <button type="submit">Выполнить</button>
            </div>
        </form>
    </div>

    <?php if (!empty($history)): ?>
        <div class="cabinet-card" style="margin-top:12px;">
            <div class="cabinet-card-title">
                <img src="/images/icons/scroll_gold.png" width="20" height="20" alt="#">
                Последние операции для логина: <span style="color:#4da3ff; font-weight:600; margin-left:6px;"><?= htmlspecialchars($historyLogin, ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <div class="table-responsive">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th>Дата</th>
                            <th style="text-align:center;">Сумма</th>
                            <th>Причина</th>
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
                                <td><?= $dateText ?></td>
                                <td style="text-align:center;"><strong style="color:<?= $color; ?>;"> <?= $amount >= 0 ? '+' : '' ?><?= $amount ?> </strong></td>
                                <td><?= $reason ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <div class="login-links" style="margin-top:12px;">
        <a class="link-item" href="/admin-panel"><img src="/images/icons/arr_left.png" width="12" height="12" alt="*"> Назад в админ-панель</a>
        <a class="link-item" href="/cabinet"><img src="/images/icons/user.png" width="12" height="12" alt="*"> В кабинет</a>
        <a class="link-item" href="/"><img src="/images/icons/home.png" width="12" height="12" alt="*"> На главную</a>
    </div>
</div>

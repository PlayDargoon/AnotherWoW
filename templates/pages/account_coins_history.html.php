<?php
// templates/pages/account_coins_history.html.php
/** @var array $history */
/** @var string $backUrl */
?>
<div class="body">
    <div class="mt20 p2 small i">
        <strong class="yellow">История начислений монет</strong>
    </div>
    <div class="b-page-bg">
        <img src="/images/cabinet_310_blue.jpg" width="310" height="103">
    </div>
    <div class="pt">
        <table class="coins-history-table" style="width:100%; background:#222; color:#fff; border-radius:8px; border:1px solid #444;">
            <thead>
                <tr style="background:#333; color:gold;">
                    <th style="padding:6px 8px;">Дата</th>
                    <th style="padding:6px 8px;">Сумма</th>
                    <th style="padding:6px 8px;">Причина</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($history)): ?>
                <tr><td colspan="3" style="text-align:center; color:#aaa;">Нет начислений</td></tr>
            <?php else: ?>
                <?php foreach ($history as $row): ?>
                    <tr style="border-bottom:1px solid #333;">
                        <td style="padding:6px 8px; color:#fff;"><?= htmlspecialchars($row['created_at']) ?></td>
                        <td style="padding:6px 8px; color:gold; font-weight:bold; text-align:center;">+<?= (int)$row['coins'] ?></td>
                        <td style="padding:6px 8px; color:#ccc;"><?= htmlspecialchars($row['reason']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
        <div class="pt">
            <a href="<?= htmlspecialchars($backUrl) ?>" class="btn" style="background:#444; color:#fff; border-radius:6px; padding:8px 18px; text-decoration:none;">&larr; Назад в кабинет</a>
        </div>
    </div>
</div>

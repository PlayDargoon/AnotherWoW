<?php
// templates/pages/account_coins_history.html.php
/** @var array $history */
/** @var string $backUrl */
/** @var int $pageIndex */
/** @var int $totalPages */
/** @var int $perPage */
/** @var int $totalCount */
?>

<div class="cabinet-page">
    <h1>История начислений</h1>

    <div class="cabinet-card">
        <div class="cabinet-card-title">
            <img src="/images/icons/journal_12.png" width="24" height="24" alt="*">
            История пополнений
        </div>

        <div class="table-responsive">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th>Дата</th>
                        <th style="text-align:center">Сумма</th>
                        <th>Причина</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($history)): ?>
                    <tr>
                        <td colspan="3" style="text-align:center; color:#9aa3ff; padding:12px">Нет начислений</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($history as $row): ?>
                        <?php
                            $raw = $row['created_at'] ?? '';
                            $ts = is_numeric($raw) ? (int)$raw : strtotime((string)$raw);
                            $dateText = $ts ? date('d.m.Y H:i:s', $ts) : htmlspecialchars((string)$raw, ENT_QUOTES, 'UTF-8');
                            $rawReason = (string)($row['reason'] ?? '');
                            $safeReason = htmlspecialchars($rawReason, ENT_QUOTES, 'UTF-8');
                            $lower = function_exists('mb_strtolower') ? mb_strtolower($rawReason, 'UTF-8') : strtolower($rawReason);
                            if (strpos($lower, 'голос') !== false || strpos($lower, 'vote') !== false || strpos($lower, 'mmotop') !== false) {
                                $reasonText = 'Голосование MMOTOP';
                            } else {
                                $reasonText = $safeReason;
                            }
                        ?>
                        <tr>
                            <td><?= $dateText ?></td>
                            <?php $amount = (int)($row['coins'] ?? 0); $color = $amount >= 0 ? '#79e27d' : '#ff6b6b'; ?>
                            <td style="text-align:center"><strong style="color:<?= $color ?>;"><?= $amount >= 0 ? '+' : '' ?><?= $amount ?></strong></td>
                            <td><?= $reasonText ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (($totalPages ?? 1) > 1): ?>
            <?php
                $shownFrom = ($pageIndex - 1) * $perPage + (empty($history) ? 0 : 1);
                $shownTo = ($pageIndex - 1) * $perPage + count($history);
            ?>
            <div class="minor" style="margin:10px 0 6px;">Показано <?= $shownFrom ?>–<?= $shownTo ?> из <?= (int)$totalCount ?></div>
            <div class="login-links" style="justify-content:flex-start; gap:10px; flex-wrap:wrap;">
                <?php if ($pageIndex > 1): ?>
                    <a class="link-item" href="?pageIndex=<?= $pageIndex - 1 ?>">&lt; Предыдущая</a>
                <?php endif; ?>
                <?php if ($pageIndex < $totalPages): ?>
                    <a class="link-item" href="?pageIndex=<?= $pageIndex + 1 ?>">Следующая &gt;</a>
                <?php else: ?>
                    <span class="link-item" style="opacity:.6; cursor: default;">Следующая &gt;</span>
                <?php endif; ?>
            </div>
            <div class="login-links" style="justify-content:flex-start; gap:6px; flex-wrap:wrap;">
                <span class="minor">Страницы:</span>
                <?php
                    $window = 5;
                    $start = max(1, $pageIndex - 2);
                    $end = min($totalPages, max($start + $window - 1, $pageIndex + 2));
                    if ($end - $start + 1 < $window) {
                        $start = max(1, $end - $window + 1);
                    }
                    for ($i = $start; $i <= $end; $i++):
                ?>
                    <?php if ($i == $pageIndex): ?>
                        <span class="link-item" style="opacity:.9; cursor: default;">[<?= $i ?>]</span>
                    <?php else: ?>
                        <a href="?pageIndex=<?= $i ?>" class="link-item"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="login-links" style="margin-top:16px">
        <a class="link-item" href="/"><img class="i12img" src="/images/icons/home.png" alt="." width="12" height="12"> На главную</a>
        <a class="link-item" href="/cabinet"><img class="i12img" src="/images/icons/arr_left.png" alt="." width="12" height="12"> В кабинет</a>
    </div>
</div>
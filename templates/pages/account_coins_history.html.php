<?php
// templates/pages/account_coins_history.html.php
/** @var array $history */
/** @var string $backUrl */
/** @var int $pageIndex */
/** @var int $totalPages */
/** @var int $perPage */
/** @var int $totalCount */
?>

<div class="body">
    <div class="pt">
        <h2>История пополнений</h2>
        <div class="section-sep"></div>

        <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
            <thead>
                <tr>
                    <td style="padding: 10px; font-weight: bold; font-size: 14px;">Дата</td>
                    <td style="padding: 10px; font-weight: bold; text-align: center; font-size: 14px;">Сумма</td>
                    <td style="padding: 10px; font-weight: bold; font-size: 14px;">Причина</td>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($history)): ?>
                <tr>
                    <td colspan="3" style="padding: 12px; text-align: center; color: #777;">Нет начислений</td>
                </tr>
            <?php else: ?>
                <?php foreach ($history as $row): ?>
                    <?php
                        $raw = $row['created_at'] ?? '';
                        $ts = is_numeric($raw) ? (int)$raw : strtotime((string)$raw);
                        $dateText = $ts ? date('d.m.Y H:i:s', $ts) : htmlspecialchars((string)$raw, ENT_QUOTES, 'UTF-8');
                        // Нормализуем причину к дружелюбному виду
                        $rawReason = (string)($row['reason'] ?? '');
                        $safeReason = htmlspecialchars($rawReason, ENT_QUOTES, 'UTF-8');
                        $lower = function_exists('mb_strtolower') ? mb_strtolower($rawReason, 'UTF-8') : strtolower($rawReason);
                        if (strpos($lower, 'голос') !== false || strpos($lower, 'vote') !== false || strpos($lower, 'mmotop') !== false) {
                            $reasonText = 'Голосование MMOTOP';
                        } else {
                            // Просто отображаем полную причину без изменений
                            $reasonText = $safeReason;
                        }
                    ?>
                    <tr>
                        <td style="padding: 8px;"><?= $dateText ?></td>
                        <?php $amount = (int)($row['coins'] ?? 0); $color = $amount >= 0 ? '#2c7' : '#d33'; ?>
                        <td style="padding: 8px; text-align: center;"><strong style="color:<?= $color ?>;"><?= $amount >= 0 ? '+' : '' ?><?= $amount ?></strong></td>
                        <td style="padding: 8px;"><?= $reasonText ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>

        <?php if (($totalPages ?? 1) > 1): ?>
        <div class="pt">
            <?php
                $shownFrom = ($pageIndex - 1) * $perPage + (empty($history) ? 0 : 1);
                $shownTo = ($pageIndex - 1) * $perPage + count($history);
            ?>
            <div style="margin-bottom:6px; color:#777; font-size:12px;">Показано <?= $shownFrom ?>–<?= $shownTo ?> из <?= (int)$totalCount ?></div>
            <div>
                <?php if ($pageIndex > 1): ?>
                    <a class="pag" href="?pageIndex=<?= $pageIndex - 1 ?>" data-on-click-sound="ui profile-open">&lt;Предыдущая</a>
                    &nbsp;
                <?php endif; ?>
                <?php if ($pageIndex < $totalPages): ?>
                    <a class="pag" href="?pageIndex=<?= $pageIndex + 1 ?>" data-on-click-sound="ui profile-open">Следующая&gt;</a>
                <?php else: ?>
                    <span class="pag" data-on-click-sound="ui profile-open" style="opacity:.5; cursor: default;">Следующая&gt;</span>
                <?php endif; ?>
            </div>
            <div style="margin-top:6px;">
                <?php if ($pageIndex > 1): ?>
                    <a class="pag" href="?pageIndex=1" data-on-click-sound="ui profile-open" title="Перейти на первую">&lt;&lt;</a>
                <?php else: ?>
                    <span class="pag" data-on-click-sound="ui profile-open" style="opacity:.5; cursor: default;">&lt;&lt;</span>
                <?php endif; ?>
                |
                <?php
                    // Окно страниц вокруг текущей (до 5 номеров)
                    $window = 5;
                    $start = max(1, $pageIndex - 2);
                    $end = min($totalPages, max($start + $window - 1, $pageIndex + 2));
                    if ($end - $start + 1 < $window) {
                        $start = max(1, $end - $window + 1);
                    }
                    for ($i = $start; $i <= $end; $i++):
                ?>
                    <span>
                        <?php if ($i == $pageIndex): ?>
                            <span class="pag" data-on-click-sound="ui profile-open" title="Текущая страница"><span><?= $i ?></span></span>
                        <?php else: ?>
                            <a href="?pageIndex=<?= $i ?>" class="pag" data-on-click-sound="ui profile-open" title="Перейти на страницу <?= $i ?>"><span><?= $i ?></span></a>
                        <?php endif; ?>
                    </span><?= $i < $end ? '|' : '' ?>
                <?php endfor; ?>
                |
                <?php if ($pageIndex < $totalPages): ?>
                    <a class="pag" href="?pageIndex=<?= $totalPages ?>" data-on-click-sound="ui profile-open" title="Перейти на последнюю">&gt;&gt;</a>
                <?php else: ?>
                    <span class="pag" data-on-click-sound="ui profile-open" style="opacity:.5; cursor: default;">&gt;&gt;</span>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="footer nav block-border-top">
    <ol>
        <li><img class="i12img" src="/images/icons/home.png" alt="." width="12" height="12"> <a href="/">На главную</a></li>
        <li><img class="i12img" src="/images/icons/arr_left.png" alt="." width="12" height="12"> <a href="/cabinet">В кабинет</a></li>
    </ol>
</div>
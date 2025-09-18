<!-- templates/partials/header.html.php -->
<?php /* Старый блок закомментирован для замены на новый */ ?>
<?php /*
<?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
    <span>
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tbody>
                <tr>
                    <td>
                        <img class="i12img" src="/images/icons/health.png" alt="хп" width="12px" height="12px">
                        <span class="info">
                            <?= isset($character['health']) ? htmlspecialchars($character['health']) : '-' ?>
                        </span>
                    </td>
                    <td align="right">
                        <span>
                            <img src="/images/icons/gold.webp" class="i12img" width="12" height="12">
                            <?= isset($currency['gold']) ? (int)$currency['gold'] : 0 ?>
                            <img src="/images/icons/silver.webp" class="i12img" width="12" height="12">
                            <?= isset($currency['silver']) ? (int)$currency['silver'] : 0 ?>
                            <img src="/images/icons/copper.webp" class="i12img" width="12" height="12">
                            <?= isset($currency['copper']) ? (int)$currency['copper'] : 0 ?>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </span>
<?php endif; ?>
*/ ?>


    <div class="header small block-border-bottom">
        <span>
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tbody>
                    <tr>
                        <td>
                            <span class="info">
                             <strong class="yellow"><?= htmlspecialchars($userInfo['username']) ?></strong>
                            </span>
                        </td>
                        <td align="right">
                            <span>
                                Баланс: <strong><?= isset($coins) ? (int)$coins : 0 ?></strong>
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </span>
    </div>

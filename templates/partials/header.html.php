<!-- templates/partials/header.html.php -->
<?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>

    <span>
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tbody>
                <tr>
                    <td>
                        <img class="i12img" src="/images/icons/health.png" alt="хп" width="12px" height="12px">
                        <span class="info"><?= $player['vitality'] ?></span>

                    </td>

                    <td align="right">
                        <span>
                            <img src="/images/icons/money.png" class="i12img" width="12" height="12">
                            <?= $gold ?>

                            <img src="/images/icons/money_grey.png" class="i12img" width="12" height="12">
                            <?= $silver ?>
                        </span>
                    </td>

                </tr>
            </tbody>
        </table>
    </span>


<?php endif; ?>
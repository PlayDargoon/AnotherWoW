<!-- templates/partials/header.html.php -->
<?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>

    <span>
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tbody>
                <tr>
                    <td>
                        <img class="i12img" src="/images/icons/health.png" alt="хп" width="12px" height="12px">
                        <span class="info"><?= $character['health'] ?></span>
                    </td>

                    <td align="right">
                        <span>
                            <img src="/images/icons/gold.webp" class="i12img" width="12" height="12">
                             <?= $currency['gold'] ?> <!-- Используем переменную currency -->

                            <img src="/images/icons/silver.webp" class="i12img" width="12" height="12">
                             <?= $currency['silver'] ?> <!-- Используем переменную currency -->

                            <img src="/images/icons/copper.webp" class="i12img" width="12" height="12">
                             <?= $currency['copper'] ?> <!-- Используем переменную currency -->
                        </span>
                    </td>

                </tr>
            </tbody>
        </table>
    </span>

<?php endif; ?>
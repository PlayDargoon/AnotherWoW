<div class="body">
    <h2>История покупок</h2>
    <div class="section-sep"></div>

    <div class="pt">
        <div class="small">
            <img src="/images/icons/journal_12.png" alt="*" width="16" height="16" style="float:left;margin-right:8px;">
            История всех ваших покупок в игровом магазине.<br/>
            Здесь отображаются все транзакции и списания бонусов.
        </div>
        <br>

        <div class="pt">
            <?php if (!empty($history)): ?>
                <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                    <thead>
                        <tr>
                            <td style="padding: 10px; font-weight: bold; text-align: center; font-size: 14px;">
                                <img src="/images/icons/clock.png" width="12" height="12" alt="*"> Дата и время
                            </td>
                            <td style="padding: 10px; font-weight: bold; font-size: 14px;">
                                <img src="/images/icons/service.png" width="12" height="12" alt="*"> Операция
                            </td>
                            <td style="padding: 10px; font-weight: bold; text-align: center; font-size: 14px;">
                                <img src="/images/icons/money.png" width="12" height="12" alt="*"> Сумма
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($history as $row): ?>
                            <tr>
                                <td style="padding: 8px; text-align: center;">
                                    <span class="minor"><?= date('d.m.Y H:i:s', strtotime($row['created_at'])) ?></span>
                                </td>
                                <td style="padding: 8px;">
                                    <?= htmlspecialchars($row['reason'], ENT_QUOTES, 'UTF-8') ?>
                                </td>
                                <td style="padding: 8px; text-align: center;">
                                    <strong style="color: <?= $row['coins'] < 0 ? '#ff6b6b' : '#51cf66' ?>;">
                                        <?= $row['coins'] ?> бонусов
                                    </strong>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div style="padding: 20px; text-align: center;">
                    <img src="/images/icons/question_blue.png" width="32" height="32" alt="*">
                    <p class="minor">У вас пока нет покупок в магазине</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="section-sep"></div>
</div>

<div class="footer nav block-border-top">
    <ol>
       <li><img class="i12img" src="/images/icons/home.png" alt="." width="12px" height="12px"> <a href="/">На главную</a></li>
       <li><img src="/images/icons/cross.png" alt="." width="12" height="12"> <a ignorewebview="true" href="/cabinet">В кабинет</a></li>
       <li><img src="/images/icons/shop.png" alt="." width="12" height="12"> <a href="/shop">Назад в магазин</a></li>
    </ol>
</div>
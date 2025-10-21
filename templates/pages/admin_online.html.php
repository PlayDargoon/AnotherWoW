<!-- templates/pages/admin_online.html.php (premium) -->
<div class="cabinet-page">
    <h1>Игроки онлайн</h1>

    <?php if (!empty($accountsOnline)): ?>
        <?php foreach ($accountsOnline as $acc): ?>
            <div class="cabinet-card" style="margin-bottom:12px;">
                <div class="cabinet-card-title">
                    <img src="/images/icons/guild_light.png" width="20" height="20" alt="*">
                    Аккаунт: <strong><?= htmlspecialchars($acc['username']) ?></strong>
                </div>
                <div class="cabinet-info-list">
                    <div class="info-row">
                        <span class="info-label">ID</span>
                        <span class="info-value"><?= htmlspecialchars($acc['id']) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email</span>
                        <span class="info-value"><?= htmlspecialchars($acc['email']) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Последний IP</span>
                        <span class="info-value"><?= htmlspecialchars($acc['last_ip']) ?></span>
                    </div>
                </div>
                <div style="margin-top:8px;">
                    <div class="minor" style="margin-bottom:6px;">Персонажи онлайн:</div>
                    <?php if (!empty($acc['characters'])): ?>
                        <div class="table-responsive">
                            <table class="premium-table">
                                <thead>
                                    <tr>
                                        <th>GUID</th>
                                        <th>Имя</th>
                                        <th>Фракция</th>
                                        <th>Раса</th>
                                        <th>Класс</th>
                                        <th>Пол</th>
                                        <th>Уровень</th>
                                        <th>Плейтайм</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($acc['characters'] as $char): ?>
                                        <tr>
                                            <td class="minor"><?= (int)$char['guid'] ?></td>
                                            <td><strong style="color:<?= $char['classColor'] ?>;"><?= htmlspecialchars($char['name']) ?></strong></td>
                                            <td><img src="<?= getFactionImage($char['race']) ?>" alt="Фракция" width="16" height="16"></td>
                                            <td><img src="/images/small/<?= $char['race'] . '-' . $char['gender'] ?>.gif" alt="Раса" width="16" height="16"></td>
                                            <td><img src="/images/small/<?= $char['class'] ?>.gif" alt="Класс" width="16" height="16"></td>
                                            <td><img src="/images/icons/sex_<?= $char['gender'] == 1 ? 'female' : 'male' ?>.png" alt="Пол" width="14" height="14"></td>
                                            <td class="minor"><?= (int)$char['level'] ?></td>
                                            <td class="minor"><?= htmlspecialchars($char['playtime']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="minor">Нет онлайн-персонажей</div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="cabinet-card">
            <div class="cabinet-card-title">
                <img src="/images/icons/attention_gold.png" width="20" height="20" alt="!">
                Пусто
            </div>
            <div class="info-main-text">Нет игроков онлайн, подходящих под условия.</div>
        </div>
    <?php endif; ?>

    <div class="login-links" style="margin-top:12px;">
        <a class="link-item" href="/admin-panel"><img src="/images/icons/arr_left.png" width="12" height="12" alt="*"> Назад в админ-панель</a>
    </div>
</div>


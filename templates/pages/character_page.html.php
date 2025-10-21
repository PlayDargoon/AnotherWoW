<div class="cabinet-page">
    <h1>Профиль персонажа</h1>

    <div class="cabinet-card" style="margin-bottom:12px;">
        <div class="cabinet-card-title">
            <img src="<?= htmlspecialchars($factionImage) ?>" alt="Фракция" width="20" height="20" style="border-radius:3px;">
            <?= htmlspecialchars($serverName) ?> — <strong class="gold"><?= htmlspecialchars($character['name']) ?></strong>
        </div>
        <div class="cabinet-info-list">
            <div class="info-row">
                <span class="info-label">Раса / Класс</span>
                <span class="info-value">
                    <?= htmlspecialchars($races[$character['race']]) ?> · <?= htmlspecialchars($classes[$character['class']]) ?>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Уровень</span>
                <span class="info-value"><?= (int)$character['level'] ?></span>
            </div>
            <?php if (!empty($roleText)): ?>
            <div class="info-row">
                <span class="info-label">Роль</span>
                <span class="info-value">
                    <img src="/images/icons/guild_moderate.png" alt="*" width="14" height="14" style="vertical-align:middle; margin-right:4px;">
                    <?= htmlspecialchars($roleText) ?>
                </span>
            </div>
            <?php endif; ?>
            <div class="info-row">
                <span class="info-label">Статус</span>
                <span class="info-value <?= $character['online'] ? 'status-ok' : 'status-bad' ?>">
                    <?= $character['online'] ? 'Онлайн' : 'Оффлайн' ?>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Игровое время</span>
                <span class="info-value"><?= htmlspecialchars(gmdate("H:i:s", $character['totaltime'])) ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Создан</span>
                <span class="info-value"><?= htmlspecialchars(formatCreationDate($character['creation_date'])) ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">GUID</span>
                <span class="info-value"><?= htmlspecialchars($character['guid']) ?></span>
            </div>
        </div>
    </div>

    <div class="cabinet-card">
        <div class="cabinet-card-title">
            <img src="/images/icons/journal_12.png" width="20" height="20" alt="*">
            Характеристики
        </div>
        <div class="table-responsive">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th>Параметр</th>
                        <th>Значение</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <img src="/images/icons/health.png" alt="." width="14" height="14" style="vertical-align:middle; margin-right:4px;"> Выносливость
                        </td>
                        <td><?= htmlspecialchars($stats['stamina']) ?> <span class="minor">(<?= htmlspecialchars($stats['maxhealth']) ?> HP)</span></td>
                    </tr>
                    <tr>
                        <td>
                            <img src="/images/icons/strength.png" alt="." width="14" height="14" style="vertical-align:middle; margin-right:4px;"> Сила
                        </td>
                        <td><?= htmlspecialchars($stats['strength']) ?> <span class="minor">(~<?= htmlspecialchars($stats['attackPower']) ?> AP)</span></td>
                    </tr>
                    <tr>
                        <td>
                            <img src="/images/icons/crit.png" alt="." width="14" height="14" style="vertical-align:middle; margin-right:4px;"> Критический удар
                        </td>
                        <td><?= htmlspecialchars($stats['critPct']) ?>%</td>
                    </tr>
                    <tr>
                        <td>
                            <img src="/images/icons/armor.png" alt="." width="14" height="14" style="vertical-align:middle; margin-right:4px;"> Броня
                        </td>
                        <td><?= htmlspecialchars($stats['armor']) ?></td>
                    </tr>
                    <tr>
                        <td>
                            <img src="/images/icons/effectEvade.png" alt="." width="14" height="14" style="vertical-align:middle; margin-right:4px;"> Ловкость
                        </td>
                        <td><?= htmlspecialchars($stats['agility']) ?></td>
                    </tr>
                    <tr>
                        <td>
                            <img src="/images/icons/experience_stroke.png" alt="." width="14" height="14" style="vertical-align:middle; margin-right:4px;"> Опыт
                        </td>
                        <td><?= htmlspecialchars($character['xp']) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="login-links" style="margin-top:16px">
        <a class="link-item" href="/cabinet"><img class="i12img" src="/images/icons/arr_left.png" alt="." width="12" height="12"> В кабинет</a>
        <a class="link-item" href="/"><img class="i12img" src="/images/icons/home.png" alt="." width="12" height="12"> На главную</a>
    </div>
</div>
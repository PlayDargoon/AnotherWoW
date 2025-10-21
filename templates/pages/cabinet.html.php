<div class="cabinet-page">
    <h1>Личный кабинет — <?= htmlspecialchars($userInfo['username']) ?></h1>

    <div class="cabinet-hero block-border" style="text-align:center">
        <img src="/images/taverna.jpg" width="725" height="227" alt="Таверна">
    </div>

    <?php if ($userAccessLevel >= 4): ?>
        <div class="info-alert" style="margin-top:15px">
            <img src="/images/icons/settings.png" width="12" height="12" alt="*"> 
            У вас есть доступ в <a href="/admin-panel">админ-панель</a>.
        </div>
    <?php endif; ?>

    <div class="cabinet-card">
        <div class="cabinet-card-title">
            <img src="/images/icons/menialo.png" width="24" height="24" alt="*">
            Информация учетной записи
        </div>
        <div class="cabinet-info-list">
            <div class="info-row">
                <div class="label"><img src="/images/icons/message_incoming.png" width="12" height="12" alt="*"> Email</div>
                <div class="value"><?= htmlspecialchars($userInfo['email'] ?? 'Не указан', ENT_QUOTES, 'UTF-8') ?></div>
            </div>
            <div class="info-row">
                <div class="label"><img src="/images/icons/clock.png" width="12" height="12" alt="*"> Дата регистрации</div>
                <div class="value"><?= $userInfo['joindate'] ? date('d.m.Y H:i:s', strtotime($userInfo['joindate'])) : 'Не указана' ?></div>
            </div>
            <div class="info-row">
                <div class="label"><img src="/images/icons/portal_green.png" width="12" height="12" alt="*"> Последний вход</div>
                <div class="value"><?= $userInfo['last_login'] ? date('d.m.Y H:i:s', strtotime($userInfo['last_login'])) : 'Никогда не входил' ?></div>
            </div>
            <div class="info-row">
                <div class="label"><img src="/images/icons/eye.png" width="12" height="12" alt="*"> Последний IP</div>
                <div class="value"><?= htmlspecialchars($userInfo['last_ip'] ?? 'Не определён', ENT_QUOTES, 'UTF-8') ?: 'Не определён' ?></div>
            </div>
            <div class="info-row">
                <div class="label"><img src="/images/icons/skull_blue.png" width="12" height="12" alt="*"> Статус бана</div>
                <div class="value">
                    <?php if ($banInfo): ?>
                        <span class="status-bad">Забанен</span>
                        <?php if (!empty($banInfo['banreason'])): ?>
                            <span class="minor">Причина: <?= htmlspecialchars($banInfo['banreason'], ENT_QUOTES, 'UTF-8') ?></span>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="status-ok">Не забанен</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="info-row">
                <div class="label"><img src="/images/icons/cross.png" width="12" height="12" alt="*"> Статус мута</div>
                <div class="value">
                    <?php if ($muteInfo): ?>
                        <span class="status-bad">Заглушен</span> 
                        <span class="minor">(до <?= date('d.m.Y H:i:s', $muteInfo['mute_end_time']) ?>)</span>
                        <?php if (!empty($muteInfo['mutereason'])): ?>
                            <br><span class="minor">Причина: <?= htmlspecialchars($muteInfo['mutereason'], ENT_QUOTES, 'UTF-8') ?></span>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="status-ok">Не заглушен</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="info-row">
                <div class="label"><img src="/images/icons/health.png" width="12" height="12" alt="*"> В игре</div>
                <div class="value">
                    <?php if (!empty($userInfo['online']) && $userInfo['online'] > 0): ?>
                        <span class="status-ok">онлайн</span>
                    <?php else: ?>
                        <span class="status-bad">оффлайн</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="info-row">
                <div class="label"><img src="/images/icons/money.png" width="12" height="12" alt="*"> Баланс</div>
                <div class="value">
                    <strong><?= (int)$bonusBalance ?> бонусов</strong>
                    <a class="cabinet-small-button" href="/payment/create">Пополнить</a>
                </div>
            </div>
        </div>
    </div>

    <div class="cabinet-card">
        <div class="cabinet-card-title">
            <img src="/images/icons/addfriends.png" width="24" height="24" alt="*">
            Ваши персонажи
        </div>
        <?php if (empty($characters)): ?>
            <p class="minor">У вас нет созданных персонажей.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th style="text-align:center">Фракция</th>
                            <th style="text-align:center">Расса</th>
                            <th style="text-align:center">Класс</th>
                            <th>Имя персонажа</th>
                            <th style="text-align:center">Уровень</th>
                            <th style="text-align:center">Время игры</th>
                            <th style="text-align:center">Действие</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($characters as $char): ?>
                            <tr>
                                <td class="tc"><img src="<?= $char['factionImage'] ?>" alt="Фракция"></td>
                                <td class="tc"><img src="/images/small/<?= $char['race'].'-'.$char['gender'].'.gif' ?>" alt="расса" class="u12img"></td>
                                <td class="tc"><img src="/images/small/<?= $char['class'].'.gif' ?>" alt="класс"></td>
                                <td>
                                    <strong style="color: <?= $char['classColor'] ?>;"><?= htmlspecialchars($char['name'], ENT_QUOTES, 'UTF-8') ?></strong>
                                    <?php if (!empty($char['roleTextShort'])): ?>
                                        <br><span class="bluepost">[<?= htmlspecialchars($char['roleTextShort'], ENT_QUOTES, 'UTF-8') ?>]</span>
                                    <?php endif; ?>
                                </td>
                                <td class="tc"><strong><?= (int)$char['level'] ?></strong> ур.</td>
                                <td class="tc"><span class="minor"><?= htmlspecialchars($char['playtime'], ENT_QUOTES, 'UTF-8') ?></span></td>
                                <td class="tc"><a class="cabinet-small-button" href="<?= '/play?id='.$char['guid'] ?>">Управлять</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <div class="cabinet-grid">
        <div class="cabinet-card">
            <div class="cabinet-card-title">
                <img src="/images/icons/feather.png" width="24" height="24" alt="*">
                Голосование и бонусы
            </div>
            <ul class="action-list">
                <li><img src="/images/icons/feather.png" width="12" height="12" alt="*"> <a href="/vote">Голосовать за проект</a></li>
                <li><img class="i12img" src="/images/icons/journal_12.png" alt="." width="12" height="12"> <a href="/cabinet/coins-history">История начислений</a></li>
                <li><img src="/images/icons/bazaar.png" width="12" height="12" alt="*"> <a href="/shop">Магазин</a></li>
                <li><img src="/images/icons/vip_icon.png" width="12" height="12" alt="*"> <a href="/vote/top">Топ голосующих</a></li>
            </ul>
        </div>

        <div class="cabinet-card">
            <div class="cabinet-card-title">
                <img src="/images/icons/question_blue.png" width="24" height="24" alt="*">
                Настройки аккаунта
            </div>
            <ul class="action-list">
                <li><img src="/images/icons/arr.png" width="12" height="12" alt="*"> <a href="#">Изменить email</a></li>
                <li><img src="/images/icons/arr.png" width="12" height="12" alt="*"> <a href="#">Изменить телефон</a></li>
                <li><img src="/images/icons/question_blue.png" width="12" height="12" alt="*"> <a href="#">Сменить пароль</a></li>
            </ul>
        </div>
    </div>

    <div class="login-links" style="margin-top:20px">
        <a href="/" class="link-item"><img class="i12img" src="/images/icons/home.png" alt="." width="12" height="12"> На главную</a>
        <a href="/logout" class="link-item" ignorewebview="true"><img src="/images/icons/cross.png" alt="." width="12" height="12"> Выйти</a>
        <a href="/help" class="link-item"><img class="i12img" src="/images/icons/question_blue.png" alt="." width="12" height="12"> Помощь</a>
        <a href="https://yoomoney.ru/fundraise/1D220FUHMKN.250928" target="_blank" class="link-item"><img src="/images/icons/addfriends.png" alt="." width="12" height="12"> Поддержать сервер</a>
    </div>
</div>


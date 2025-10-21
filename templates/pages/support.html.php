<!-- templates/pages/support.html.php (premium) -->
<div class="cabinet-page">
    <h1>Поддержка игроков</h1>

    <?php if (!empty($message)): ?>
        <div class="cabinet-card" style="border-left:3px solid #4da3ff; margin-bottom:12px;">
            <div class="info-main-text"><?= $message ?></div>
        </div>
    <?php endif; ?>

    <div class="cabinet-card">
        <div class="cabinet-card-title">
            <img src="/images/icons/message_outgoing.png" width="20" height="20" alt="*">
            Свяжитесь с нами
        </div>
        <div class="info-main-text" style="margin-bottom:12px;">
            Возникли проблемы с игрой или аккаунтом? Заполните форму ниже, и мы ответим в течение 24 часов.
        </div>

        <form method="POST" action="/support" class="login-form">
            <div class="input-group">
                <label for="name">Ваше имя *</label>
                <input type="text" id="name" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? ($_GET['name'] ?? '')) ?>" placeholder="Введите имя">
            </div>

            <div class="input-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? ($_GET['email'] ?? '')) ?>" placeholder="example@mail.com">
            </div>

            <div class="input-group">
                <label for="subject">Тема *</label>
                <select id="subject" name="subject" required>
                    <option value="">Выберите тему...</option>
                    <?php $sel = $_POST['subject'] ?? ($_GET['subject'] ?? ''); ?>
                    <option value="Проблемы с входом" <?= $sel === 'Проблемы с входом' ? 'selected' : '' ?>>Проблемы с входом</option>
                    <option value="Баги в игре" <?= $sel === 'Баги в игре' ? 'selected' : '' ?>>Баги в игре</option>
                    <option value="Проблемы с персонажем" <?= $sel === 'Проблемы с персонажем' ? 'selected' : '' ?>>Проблемы с персонажем</option>
                    <option value="Жалоба на игрока" <?= $sel === 'Жалоба на игрока' ? 'selected' : '' ?>>Жалоба на игрока</option>
                    <option value="Технические проблемы" <?= $sel === 'Технические проблемы' ? 'selected' : '' ?>>Технические проблемы</option>
                    <option value="Ошибка оплаты" <?= $sel === 'Ошибка оплаты' ? 'selected' : '' ?>>Ошибка оплаты</option>
                    <option value="Другое" <?= $sel === 'Другое' ? 'selected' : '' ?>>Другое</option>
                </select>
            </div>

            <div class="input-group">
                <label for="message">Сообщение *</label>
                <textarea id="message" name="message" rows="6" required placeholder="Опишите вашу проблему подробно..."><?= htmlspecialchars($_POST['message'] ?? ($_GET['message'] ?? '')) ?></textarea>
            </div>

            <div class="restore-button">
                <button type="submit">Отправить сообщение</button>
            </div>
        </form>
    </div>

    <div class="cabinet-card">
        <div class="cabinet-card-title">
            <img src="/images/icons/comment_blue.png" width="20" height="20" alt="*">
            Альтернативные способы связи
        </div>
        <div class="cabinet-info-list">
            <div class="info-row">
                <span class="info-label">Telegram</span>
                <span class="info-value"><a href="https://t.me/+Y6-arC5q8WliNGRi" target="_blank" style="color:#4da3ff;">группа в Telegram</a></span>
            </div>
            <div class="info-row">
                <span class="info-label">Email</span>
                <span class="info-value">support@azeroth.su</span>
            </div>
            <div class="info-row">
                <span class="info-label">Время работы</span>
                <span class="info-value">01:00 - 13:00 (МСК)</span>
            </div>
        </div>

        <div style="margin-top:16px; padding:12px; background:rgba(255,255,255,0.03); border-radius:4px;">
            <div class="minor" style="margin-bottom:8px; font-weight:600;">Важно: при обращении в поддержку всегда указывайте:</div>
            <ul class="minor" style="margin:0; padding-left:20px;">
                <li>Ваш логин (не пароль!)</li>
                <li>Имя персонажа (если проблема связана с ним)</li>
                <li>Подробное описание проблемы</li>
                <li>Шаги для воспроизведения бага (если применимо)</li>
            </ul>
        </div>
    </div>

    <div class="cabinet-card">
        <div class="cabinet-card-title">
            <img src="/images/icons/question_gold.png" width="20" height="20" alt="?">
            Часто задаваемые вопросы
        </div>

        <div style="padding:8px 0;">
            <div class="document-section">
                <div class="minor" style="font-weight:600; margin-bottom:4px;">Не могу войти в игру, что делать?</div>
                <div class="minor">Проверьте правильность логина и пароля. Убедитесь, что ваш аккаунт не заблокирован. Если проблема сохраняется, очистите кеш/WTF и проверьте realmlist. При необходимости напишите нам через форму поддержки.</div>
            </div>

            <div class="document-section">
                <div class="minor" style="font-weight:600; margin-bottom:4px;">Как восстановить удаленного персонажа?</div>
                <div class="minor">Напишите в поддержку имя персонажа, ориентировочную дату удаления и причину восстановления. Заявка рассматривается индивидуально и может занять до 48 часов.</div>
            </div>

            <div class="document-section">
                <div class="minor" style="font-weight:600; margin-bottom:4px;">Обнаружил баг в игре, куда сообщить?</div>
                <div class="minor">Опишите баг максимально подробно: локация, шаги воспроизведения, что ожидалось и что произошло. Приложите скрин/видео, если есть. Выберите тему «Баги в игре» в форме выше.</div>
            </div>

            <div class="document-section">
                <div class="minor" style="font-weight:600; margin-bottom:4px;">Как пожаловаться на игрока?</div>
                <div class="minor">Укажите ник нарушителя, время, локацию и приложите доказательства (скрины/видео). Выберите тему «Жалоба на игрока».</div>
            </div>
        </div>
    </div>

    <div class="login-links">
        <a class="link-item" href="/"><img src="/images/icons/home.png" width="12" height="12" alt="*"> На главную</a>
        <a class="link-item" href="/rules"><img src="/images/icons/question_blue.png" width="12" height="12" alt="*"> Правила пользования</a>
    </div>
</div>
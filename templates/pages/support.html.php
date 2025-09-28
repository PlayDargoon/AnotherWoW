<!-- templates/pages/support.html.php -->
<div class="body">

    <h2 class="section-title">Поддержка игроков</h2>

    <?php if (!empty($message)): ?>
        <div class="bluepost">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <div class="bluepost">
        <h3 style="color: #ffff33;">Свяжитесь с нами</h3>
        <p>Возникли проблемы с игрой или аккаунтом? Заполните форму ниже, и мы ответим в течение 24 часов.</p>

        <form method="POST" action="/support">
            <div class="pt">
                <label for="name"><span class="info">Ваше имя</span> *</label><br>
                <input type="text" id="name" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
            </div>

            <div class="pt">
                <label for="email"><span class="info">Email</span> *</label><br>
                <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>

            <div class="pt">
                <label for="subject"><span class="info">Тема</span> *</label><br>
                <select id="subject" name="subject" required>
                    <option value="">Выберите тему...</option>
                    <option value="Проблемы с входом" <?= ($_POST['subject'] ?? '') === 'Проблемы с входом' ? 'selected' : '' ?>>Проблемы с входом</option>
                    <option value="Баги в игре" <?= ($_POST['subject'] ?? '') === 'Баги в игре' ? 'selected' : '' ?>>Баги в игре</option>
                    <option value="Проблемы с персонажем" <?= ($_POST['subject'] ?? '') === 'Проблемы с персонажем' ? 'selected' : '' ?>>Проблемы с персонажем</option>
                    <option value="Жалоба на игрока" <?= ($_POST['subject'] ?? '') === 'Жалоба на игрока' ? 'selected' : '' ?>>Жалоба на игрока</option>
                    <option value="Технические проблемы" <?= ($_POST['subject'] ?? '') === 'Технические проблемы' ? 'selected' : '' ?>>Технические проблемы</option>
                    <option value="Другое" <?= ($_POST['subject'] ?? '') === 'Другое' ? 'selected' : '' ?>>Другое</option>
                </select>
            </div>

            <div class="pt">
                <label for="message"><span class="info">Сообщение</span> *</label><br>
                <textarea id="message" name="message" rows="6" required placeholder="Опишите вашу проблему подробно..."><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
            </div>

            <div class="pt">
                <input type="submit" class="headerButton _c-pointer" value="Отправить сообщение">
            </div>
        </form>
    </div>

    <div class="bluepost">
        <h3 style="color: #ffff33;">Альтернативные способы связи</h3>
        <p><strong class="gold">Telegram:</strong> <a href="https://t.me/+Y6-arC5q8WliNGRi" target="_blank">группа в Telegram</a></p>
        <p><strong class="gold">Email:</strong> <span>support@azeroth.su</span></p>
        <p><strong class="gold">Время работы:</strong> <span>01:00 - 13:00 (МСК)</span></p>

        <div class="pt">
            <p><strong class="gold">Важно:</strong> при обращении в поддержку всегда указывайте:</p>
            <ul>
                <li>Ваш логин (не пароль!)</li>
                <li>Имя персонажа (если проблема связана с ним)</li>
                <li>Подробное описание проблемы</li>
                <li>Шаги для воспроизведения бага (если применимо)</li>
            </ul>
        </div>
    </div>

    <!-- FAQ Block -->
    <div class="bluepost">
        <h3 style="color: #ffff33;">Часто задаваемые вопросы</h3>

        <div class="pt">
            <strong class="gold">Не могу войти в игру, что делать?</strong>
            <p>Проверьте правильность логина и пароля. Убедитесь, что ваш аккаунт не заблокирован. Если проблема сохраняется, очистите кеш/WTF и проверьте realmlist. При необходимости напишите нам через форму поддержки.</p>
        </div>

        <div class="pt">
            <strong class="gold">Как восстановить удаленного персонажа?</strong>
            <p>Напишите в поддержку имя персонажа, ориентировочную дату удаления и причину восстановления. Заявка рассматривается индивидуально и может занять до 48 часов.</p>
        </div>

        <div class="pt">
            <strong class="gold">Обнаружил баг в игре, куда сообщить?</strong>
            <p>Опишите баг максимально подробно: локация, шаги воспроизведения, что ожидалось и что произошло. Приложите скрин/видео, если есть. Выберите тему «Баги в игре» в форме выше.</p>
        </div>

        <div class="pt">
            <strong class="gold">Как пожаловаться на игрока?</strong>
            <p>Укажите ник нарушителя, время, локацию и приложите доказательства (скрины/видео). Выберите тему «Жалоба на игрока».</p>
        </div>
    </div>

    <!-- Куда пойти дальше -->
    

</div>

<div class="footer nav block-border-top">
        <ol>
            <li>
                <img src="/images/icons/home.png" alt="." width="12" height="12" class="i12img">
                <a href="/">На главную</a>
            </li>
            
            <li>
                <img src="/images/icons/question_blue.png" alt="." width="12" height="12" class="i12img">
                <a href="/rules">Правила пользования</a>
            </li>
            
        </ol>
    </div>
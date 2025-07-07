<?php
// templates/pages/profile.html.php

?>

<div class="body">
    <h1>Кабинет пользователя</h1>

    <?php if (isset($userInfo)): ?>
        <p>Привет, <?= $userInfo['username'] ?>!</p>
        <ul>
            <li>ID: <?= $userInfo['id'] ?></li>
            <li>Электронная почта: <?= $userInfo['email'] ?></li>
            <!-- Остальные поля, если нужно -->
        </ul>
        <a href="/logout">Выйти</a>
    <?php else: ?>
        <p>Информация о пользователе не найдена.</p>
    <?php endif; ?>
</div>
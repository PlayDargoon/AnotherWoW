<!-- templates/pages/reset_password.html.php -->

<div class="body">
    <h2>Сброс пароля</h2>
    <form method="POST" action="/reset-password">
        <input type="hidden" name="userId" value="<?php echo $data['userId']; ?>">
        <label for="newPassword">Новый пароль:</label>
        <input type="password" name="newPassword" required>
        <button type="submit">Сменить пароль</button>
    </form>
</div>
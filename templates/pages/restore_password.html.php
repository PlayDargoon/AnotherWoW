<!-- templates/pages/restore_password.html.php -->
<div class="body">
    <form method="POST" action="/restore-password">
        <h2>Восстановление пароля</h2>
        <label for="email">Email:</label>
        <input type="email" name="email" placeholder="Введите Email" required />
        <button type="submit">Отправить письмо</button>
    </form>
</div>
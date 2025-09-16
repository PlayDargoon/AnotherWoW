<div class="body">
    <h2>Управление новостями</h2>
    <?php if (!empty($newsList)): ?>
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr>
                    <th style="text-align:left;">Заголовок</th>
                    <th style="text-align:left;">Дата</th>
                    <th style="text-align:left;">Действия</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($newsList as $news): ?>
                <tr>
                    <td><?= htmlspecialchars($news['title']) ?></td>
                    <td><?= htmlspecialchars($news['created_at']) ?></td>
                    <td>
                        <form method="post" action="/news/delete" style="display:inline;">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token()) ?>">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($news['id']) ?>">
                            <button type="submit" onclick="return confirm('Удалить новость?')">Удалить</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="small">Новостей нет.</div>
    <?php endif; ?>
    <div class="pt">
        <a href="/news/create">Добавить новость</a>
        &nbsp;|&nbsp;
        <a href="/admin-panel" style="color:#888;">Назад</a>
    </div>
</div>

<div class="cabinet-page">
    <h1>Управление новостями</h1>

    <div class="cabinet-card">
        <?php if (!empty($newsList)): ?>
            <div class="table-responsive">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th>Заголовок</th>
                            <th>Дата</th>
                            <th>Действия</th>
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
                                        <button type="submit" class="cabinet-small-button" onclick="return confirm('Удалить новость?')">Удалить</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="info-main-text">Новостей нет.</div>
        <?php endif; ?>
    </div>

    <div class="login-links" style="margin-top:12px;">
        <a href="/news/create" class="link-item"><img src="/images/icons/add.png" width="12" height="12" alt="*"> Добавить новость</a>
        <a href="/admin-panel" class="link-item"><img src="/images/icons/arr_left.png" width="12" height="12" alt="*"> Назад</a>
    </div>
</div>

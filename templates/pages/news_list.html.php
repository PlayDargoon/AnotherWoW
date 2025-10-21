<?php
$cacheFile = __DIR__ . '/../../cache/news_list.cache.html';
if (file_exists($cacheFile) && filemtime($cacheFile) > time() - 60) {
    readfile($cacheFile);
    return;
}
ob_start();
?>

<div class="cabinet-page">
    <h1>Новости</h1>

    <?php if (!empty($newsList)): ?>
        <?php foreach ($newsList as $news): ?>
            <div class="cabinet-card" style="margin-bottom:12px;">
                <div class="cabinet-card-title">
                    <img src="/images/icons/journal_12.png" width="20" height="20" alt="*">
                    <?= htmlspecialchars($news['title']) ?>
                </div>
                <div class="cabinet-info-list">
                    <div class="info-row">
                        <span class="info-label">Автор</span>
                        <span class="info-value"><?= htmlspecialchars($news['author'] ?? 'Администрация') ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Дата</span>
                        <span class="info-value"><?= htmlspecialchars($news['created_at']) ?></span>
                    </div>
                </div>
                <div class="document-content" style="margin-top:8px;">
                    <?= nl2br($news['content']) ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="cabinet-card">
            <div class="cabinet-card-title">
                <img src="/images/icons/attention_gold.png" width="20" height="20" alt="!">
                Пока новостей нет
            </div>
            <div class="info-main-text" style="margin-top:6px;">Загляните позже — скоро что-нибудь объявим.</div>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
file_put_contents($cacheFile, $content);
echo $content;
?>

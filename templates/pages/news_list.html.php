<?php
$cacheFile = __DIR__ . '/../../cache/news_list.cache.html';
if (file_exists($cacheFile) && filemtime($cacheFile) > time() - 60) {
    readfile($cacheFile);
    return;
}
ob_start();
?>

<div class="body">
    <h2 class="pb10 _font-art font14">Новости</h2>
    <?php if (!empty($newsList)): ?>
        <?php foreach ($newsList as $news): ?>
            <div class="block-border" style="margin-bottom:18px;">
                <div style="display:flex; align-items:center;">
                    <span class="ctx_userlink">
                        <img src="/images/icons/user_2_1_off.png" class="u12img" width="12" height="12" style="vertical-align:middle;">&nbsp;
                        <span><?= htmlspecialchars($news['author'] ?? 'Администрация') ?></span>
                        <span class="bluepost">[a]</span>
                    </span>
                    <span style="margin-left:10px; color:#888; font-size:12px;">
                        <?= htmlspecialchars($news['created_at']) ?>
                    </span>
                </div>
                <div class="section-sep"></div>
                <div class="yellow" style="font-weight:bold; font-size:18px; margin-bottom:7px;">
                    <?= htmlspecialchars($news['title']) ?>
                </div>
                <div class="small" style="margin-top:8px; word-wrap:break-word;">
                    <?= nl2br($news['content']) ?> <!-- Оставляем HTML, т.к. используется визуальный редактор -->
                </div>
                <div class="section-sep"></div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="small">Новостей пока нет.</div>
    <?php endif; ?>
</div>
<?php
$content = ob_get_clean();
file_put_contents($cacheFile, $content);
echo $content;
?>

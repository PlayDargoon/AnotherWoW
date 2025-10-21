<?php
// templates/pages/progression.html.php

// Вспомогательная функция: отрисовать блок строк с поддержкой списков "- "
if (!function_exists('renderParagraphsAndLists')) {
    function renderParagraphsAndLists(array $lines): void {
        $inList = false;
        $leadShown = false; // первый абзац-описание подсвечиваем как yellow
        foreach ($lines as $l) {
            if (strpos($l, '- ') === 0) {
                if (!$inList) {
                    echo "<ul>\n";
                    $inList = true;
                }
                // До применения #0066FF пункты списка были синими через класс,
                // а названия подземелий/рейдов — жирными и золотыми.
                // Выделим название до разделителя " — " или " (" и обернём в <strong class="gold">.
                $text = substr($l, 2);
                $name = $text;
                $rest = '';
                $pos = strpos($text, ' — ');
                if ($pos === false) {
                    $pos = strpos($text, ' (');
                }
                if ($pos !== false) {
                    $name = substr($text, 0, $pos);
                    $rest = substr($text, $pos); // включая разделитель
                }
                echo '<li><strong class="gold">' . htmlspecialchars(trim($name)) . '</strong>' . htmlspecialchars($rest) . "</li>\n";
            } else {
                if ($inList) {
                    echo "</ul>\n";
                    $inList = false;
                }
                // Первый текстовый абзац фазы подсвечиваем жёлтым
                $classes = $leadShown ? 'small' : 'small yellow';
                echo '<p class="' . $classes . '">' . htmlspecialchars($l) . "</p>\n";
                $leadShown = true;
            }
        }
        if ($inList) {
            echo "</ul>\n";
        }
    }
}

// Заголовок страницы
$pageTitle = $pageTitle ?? 'Прогрессия контента';
$updatedAt = null;
?>

<div class="cabinet-page" id="top">
    <h2>📜 Прогрессия контента (Classic → TBC → WotLK)</h2>

        <?php if (!empty($intro ?? [])): ?>
            <div class="cabinet-card">
                <div class="info">
                    <?php foreach ($intro as $p): ?>
                        <p class="small"><?= htmlspecialchars($p) ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($phases ?? [])): ?>
            <div class="cabinet-card">
                <div class="minor" style="margin-bottom: 8px;">
                    🔗 Быстрые ссылки по фазам:
                </div>
                <ul>
                    <?php foreach ($phases as $ph): ?>
                        <li><a class="btn-link" href="#phase-<?= (int)$ph['number'] ?>">Фаза <?= (int)$ph['number'] ?> — <?= htmlspecialchars($ph['title']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
                <?php if ($updatedAt): ?>
                    <div class="minor mt5">Обновлено: <?= $updatedAt ?></div>
                <?php endif; ?>
            </div>

            <div class="cabinet-card" style="margin-top:8px;">
                <div class="minor" style="margin-bottom: 6px;">Кнопки управления:</div>
                <div>
                    <a href="#" class="headerButtonMy mr5" data-action="filter" data-era="all">Показать все</a>
                    <a href="#" class="headerButtonMy mr5" data-action="filter" data-era="classic">Classic</a>
                    <a href="#" class="headerButtonMy mr5" data-action="filter" data-era="tbc">TBC</a>
                    <a href="#" class="headerButtonMy mr5" data-action="filter" data-era="wotlk">WotLK</a>
                    <a href="#" class="headerButtonMy mr5" data-action="expand" data-mode="all">Развернуть все</a>
                    <a href="#" class="headerButtonMy" data-action="collapse" data-mode="all">Свернуть все</a>
                </div>
            </div>

            <br>

            <?php foreach ($phases as $ph): ?>
                <div class="cabinet-card phase-block" id="phase-<?= (int)$ph['number'] ?>" data-era="<?= htmlspecialchars($ph['era'] ?? 'unknown') ?>">
                    <div style="display:flex; align-items:center; justify-content:space-between; gap:6px;">
                        <h3 style="color:#ff6600; margin-right:8px;">Фаза <?= (int)$ph['number'] ?> — <?= htmlspecialchars($ph['title']) ?></h3>
                        <div style="white-space:nowrap;">
                            <a href="#top" class="headerButtonMy mr5">Наверх ↑</a>
                            <a href="#" class="headerButtonMy js-toggle" data-target="phase-<?= (int)$ph['number'] ?>">Свернуть</a>
                        </div>
                    </div>
                    <div class="phase-body">
                        <?php renderParagraphsAndLists($ph['lines']); ?>
                    </div>
                    <div class="b-expa"></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    <br>
</div>

<div class="login-links" style="margin-top: 20px;">
    <a href="/" class="login-link">
        <img src="/images/icons/home.png" alt="">
        На главную
    </a>
    <span class="link-separator">•</span>
    <a href="/news" class="login-link">
        К новостям
    </a>
    <?php if ($updatedAt): ?>
        <span class="link-separator">•</span>
        <span style="opacity: 0.7;">Версия: <?= $updatedAt ?></span>
    <?php endif; ?>
</div>

<script>
(function(){
    function qsAll(sel, root){ return Array.prototype.slice.call((root||document).querySelectorAll(sel)); }
    function byId(id){ return document.getElementById(id); }
    function setDisplay(el, show){ el.style.display = show ? '' : 'none'; }
    function togglePhaseBody(container){
        var body = container.querySelector('.phase-body');
        if (!body) return;
        var link = container.querySelector('.js-toggle');
        var hidden = body.style.display === 'none';
        body.style.display = hidden ? '' : 'none';
        if (link) link.textContent = hidden ? 'Свернуть' : 'Развернуть';
    }

    // Фильтр эпох
    function applyEraFilter(era){
        qsAll('.phase-block').forEach(function(block){
            var bEra = block.getAttribute('data-era') || 'unknown';
            setDisplay(block, era === 'all' || bEra === era);
        });
    }

    // Развернуть/Свернуть все
    function setAllExpanded(expand){
        qsAll('.phase-block .phase-body').forEach(function(body){
            body.style.display = expand ? '' : 'none';
        });
        qsAll('.phase-block .js-toggle').forEach(function(a){
            a.textContent = expand ? 'Свернуть' : 'Развернуть';
        });
    }

    // Навешиваем обработчики
    qsAll('a.headerButtonMy[data-action]').forEach(function(btn){
        btn.addEventListener('click', function(e){
            e.preventDefault();
            var action = btn.getAttribute('data-action');
            if (action === 'filter') {
                applyEraFilter(btn.getAttribute('data-era') || 'all');
            } else if (action === 'expand') {
                setAllExpanded(true);
            } else if (action === 'collapse') {
                setAllExpanded(false);
            }
        });
    });

    qsAll('.phase-block .js-toggle').forEach(function(a){
        a.addEventListener('click', function(e){
            e.preventDefault();
            var id = a.getAttribute('data-target');
            var container = byId(id);
            if (container) togglePhaseBody(container);
        });
    });
})();
</script>

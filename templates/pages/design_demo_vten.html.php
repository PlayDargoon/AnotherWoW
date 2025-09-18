<?php // templates/pages/design_demo_vten.html.php ?>

<div class="body">
    <h1>Достижения — демо</h1>

    <div class="event block-border-bottom pt">
        <img src="/images/icons/a003.png" alt="*" class="ic32" width="32" height="32" style="float:left;margin-right:8px;">
        <div>
            <div><span class="yellow">Профиль:</span> <b>Ложь</b></div>
            <div class="minor"><img src="/images/icons/skull.png" alt="." width="12" height="12"> Убийств: 5924, смертей: 7301</div>
        </div>
        <div style="clear:both"></div>
    </div>

    <div class="pt">
        <div>Выполнено достижений: <b>168 / 385</b> <img class="icon" src="/images/icons/ruby_round.png" alt="."></div>
        <div>Получено рейтинга: <b>108566 / 331583</b> <img class="icon" src="/images/icons/ruby_round.png" alt="."></div>
        <div class="minor">Достижений в среднем за неделю: 0,22</div>
    </div>
</div>

<div class="body">
    <h2>Категории</h2>
    <?php
        $cats = [
            ['name' => 'Общие', 'done' => 17, 'total' => 23, 'desc' => 'Ты уверенно двигаешься в зал славы.'],
            ['name' => 'Приключения', 'done' => 21, 'total' => 24, 'desc' => 'Твои странствия увенчались успехом.'],
            ['name' => 'Подземелья', 'done' => 72, 'total' => 79, 'desc' => 'Ты покрыла себя славой в походах.'],
            ['name' => 'Рейды', 'done' => 15, 'total' => 20, 'desc' => 'Великолепные победы над боссами.'],
            ['name' => 'PvP', 'done' => 9, 'total' => 30, 'desc' => 'На пути к славе в сражениях.'],
        ];
        foreach ($cats as $c) {
            $pct = $c['total'] > 0 ? round(($c['done'] / $c['total']) * 100) : 0;
    ?>
        <div class="block-border pt pb">
            <div>
                <b><?= htmlspecialchars($c['name']) ?></b>
                <span class="minor">[<?= (int)$c['done'] ?> из <?= (int)$c['total'] ?>]</span>
            </div>
            <div class="minor"><?= htmlspecialchars($c['desc']) ?></div>
            <div class="pt" style="border:1px solid #444; height:8px; background:#2b2b2b;">
                <div style="height:100%; width:<?= $pct ?>%; background:#7fbf3f;"></div>
            </div>
        </div>
    <?php } ?>
</div>

<div class="body">
    <h2>Последние достижения</h2>
    <?php
        $last = [
            ['title' => 'Победитель арены', 'when' => 'сегодня', 'icon' => '/images/icons/a003.png'],
            ['title' => 'Покоритель подземелий', 'when' => 'вчера', 'icon' => '/images/icons/a005.png'],
            ['title' => 'Истребитель нежити', 'when' => '2 дн. назад', 'icon' => '/images/icons/a006.png'],
            ['title' => 'Путешественник', 'when' => 'неделю назад', 'icon' => '/images/icons/question_blue.png'],
        ];
        foreach ($last as $a) { ?>
            <div class="block-border pt pb">
                <img src="<?= htmlspecialchars($a['icon']) ?>" alt="." width="24" height="24" style="float:left;margin-right:8px;">
                <div>
                    <div><b><?= htmlspecialchars($a['title']) ?></b></div>
                    <div class="minor"><?= htmlspecialchars($a['when']) ?></div>
                </div>
                <div style="clear:both"></div>
            </div>
    <?php } ?>
</div>

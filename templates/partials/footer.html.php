<!-- templates/partials/footer.html.php -->
<div class="b-mt-footer">
    <div class="mb10">
        <a class="minor" ignorewebview="true" target="_blank" href="#">Лицензионное соглашение</a>
        <a class="minor" ignorewebview="true" target="_blank" href="#">Политика конфиденциальности</a>
    </div>
    <div class="mb10">

        <a class="warn" href="#">Помощь</a>
        <a class="minor" ignorewebview="true" href="#">Поддержка</a>
    </div>

    <div ignorewebview="true">


        <div class="mt-footer-inner">

            <div><span>Игра "Иной Мир" © 2025, <span class="minor">PlayDragon</span></span>
                12+
            </div>

            <!-- Дата и время в Сахалинской области -->

            <?php
            date_default_timezone_set('Asia/Sakhalin');
            echo '' . date('H:i') . ',  ' . date('d.m.Y') . '';
            ?>

            <!-- Скорость загрузки страницы -->
            <?php
            global $loadStart;
            $loadEnd = microtime(true);
            $loadingTimeSeconds = number_format(($loadEnd - $loadStart), 3, ',', '');
            echo '| ' . $loadingTimeSeconds . ' сек.<br>';
            ?>

        </div>
    </div>
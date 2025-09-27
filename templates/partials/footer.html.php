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

            <div><span>Совместный проект "Azeroth" и "SakhWoW" © 2018-2025, <span class="minor">by PlayDragon</span></span>
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
            $loadingTimeSeconds = sprintf('%05.2f', $loadEnd - $loadStart);
            $loadingTimeSeconds = str_replace('.', ',', $loadingTimeSeconds);
            echo '| ' . $loadingTimeSeconds . ' сек.<br>';
            ?>


<div style='display:inline-block; position:fixed; bottom:0; right:0;'><a href="https://wow.mmotop.ru/servers/36327/votes/new" target="_blank">
  <img src="https://mmotop.ru/uploads/rating_img/mmo_36327.png" border="0" id="mmotopratingimg" alt="Рейтинг серверов mmotop">
</a></div>
<!--LiveInternet counter--><a href="https://www.liveinternet.ru/click"
target="_blank"><img id="licnt3C87" width="88" height="15" style="border:0" 
title="LiveInternet: показано число посетителей за сегодня"
src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAEALAAAAAABAAEAAAIBTAA7"
alt=""/></a><script>(function(d,s){d.getElementById("licnt3C87").src=
"https://counter.yadro.ru/hit?t24.6;r"+escape(d.referrer)+
((typeof(s)=="undefined")?"":";s"+s.width+"*"+s.height+"*"+
(s.colorDepth?s.colorDepth:s.pixelDepth))+";u"+escape(d.URL)+
";h"+escape(d.title.substring(0,150))+";"+Math.random()})
(document,screen)</script><!--/LiveInternet-->




        </div>
    </div>

    
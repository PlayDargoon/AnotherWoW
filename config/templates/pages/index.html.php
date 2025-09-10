<!-- templates/pages/home.html.php -->



<div class="body">



    <div style="text-align:center;" class="p2">
        <img src="/images/logo.jpg" width="310" height="103" alt="Логотип"> <!-- ВАШ ЛОГОТИП -->
    </div>
    <h1 style="text-align: center;">Добро пожаловать на игровой сервер World of Warcraft 3.3.5!</h1>



    <div class="smail pt">
        <img src="/images/icons/menialo.png" alt="*" style="float:left;margin-right:8px;" class="ic32" width="32" height="32">
        Перед тобой открывается дверь в мир, полный опасностей и сокровищ. Смелее, герой, твой путь начинается здесь!
        <div style="clear: both;"></div>
    </div>

    <img src="/images/icons/xswords.png" width="12" height="12" alt=".">
    <span class="yellow">В игре уже <?= $playerCount ?>  игроков</span>!<br />

    <!-- ОПИСАНИЕ ИГРОВЫХ ЦЕННОСТЕЙ -->
    <h2 class="_font-art font16">Тебя ждет:</h2><br />

    <div class="section-sep">
        <img src="/images/icons/a002.png" style="float: left; margin-right: 10px;">
        Более сотни захватывающих квестов, которые погрузят тебя в мир приключений
    </div><br />

    <div class="section-sep">
        <img src="/images/icons/a006.png" style="float: left; margin-right: 10px;">
        Более 500 уникальных предметов экипировки, которые помогут тебе стать непобедимым
    </div><br />

    <div class="section-sep">
        <img src="/images/icons/a005.png" style="float: left; margin-right: 10px;">
        Опасные сражения с могущественными врагами, требующие стратегии и мастерства
    </div><br />

    <div class="section-sep">
        <img src="/images/icons/a003.png" style="float: left; margin-right: 10px;">
        Походы в таинственные подземелья, полные сокровищ и опасностей
    </div><br />

    <a href="/register" class="btn-link">Начать приключение</a><br /><!-- Новое: ссылка на страницу регистрации -->
    <a href="/login" class="btn-link" style="margin-top: 20px;">Войти</a><!-- Новое: ссылка на страницу входа -->
</div>
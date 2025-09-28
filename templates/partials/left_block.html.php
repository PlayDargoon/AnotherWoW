
<div class="body">

<h1 style="text-align: center;">Меню</h1></br>


<?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
  <div class="info">
    <img src="/images/icons/arr.png" alt="." width="12" height="12" class="i12img">
    <a href="/cabinet" class=""><span>Личный кабинет</span></a>
  </div>
<?php else: ?>
  <div class="info">
    <img src="/images/icons/arr.png" alt="." width="12" height="12" class="i12img">
    <a href="/login" class=""><span>Вход</span></a>
  </div>
  <div class="info">
    <img src="/images/icons/arr.png" alt="." width="12" height="12" class="i12img">
    <a href="/register" class=""><span>Регистрация</span></a>
  </div>
<?php endif; ?>

<div class="info">
                  <img src="/images/icons/arr.png" alt="." width="12" height="12" class="i12img">
                <a href="/vote" class=""><span>Голосовать за сервер</span></a>
</div>


<div class="info">
                  <img src="/images/icons/arr.png" alt="." width="12" height="12" class="i12img">
                <a href="/#" class=""><span>Форум</span></a>
</div>

<div class="info">
                  <img src="/images/icons/arr.png" alt="." width="12" height="12" class="i12img">
                <a href="/#" class=""><span>Как подключиться?</span></a>
</div>


<div class="info">
                  <img src="/images/icons/arr.png" alt="." width="12" height="12" class="i12img">
                <a href="/#" class=""><span>Новости</span></a>
</div>



<div class="info">
    <img src="/images/icons/arr.png" alt="." width="12" height="12" class="i12img">
    <a href="/about" class=""><span>О проекте</span></a>
</div>



<div class="info">
    <img src="/images/icons/addfriends.png" alt="." width="12" height="12" class="i12img">
    <a href="https://yoomoney.ru/fundraise/1D220FUHMKN.250928" class=""><span>Поддержать сервер</span></a>
</div>

</div>


<h1 style="text-align: center;">Статус сервера</h1>

<?php if (!empty($serverInfo) && is_array($serverInfo)): ?>
<div class="yellow pt">Сервер:
<img width="12" height="12" alt="в" src="<?php echo $serverInfo['iconPath']; ?>">
    <span class="<?php echo $serverInfo['statusClass']; ?>" id="id1e"><span><?php echo $serverInfo['serverStatus']; ?></span></span>
</div>

<div class="yellow pt">Онлайн:
    <img src="/images/icons/guilds.png" alt="." width="12" height="12"> <span class="info"><?php echo $serverInfo['playerCounts']['online_players']; ?></span>
    <span class="minor">  <img src="/images/small/alliance.png" alt="." width="15" height="15">  
     <?php echo $serverInfo['playerCountsByFaction']['alliance_players']; ?> </span>
    <span> vs </span>
     <span class="minor"> <img src="/images/small/orda.png" alt="." width="15" height="15"> 
     <?php echo $serverInfo['playerCountsByFaction']['horde_players']; ?> </span>
 </div>   

<div class="yellow pt">UPtime: 
        <img src="/images/icons/clock.png" alt=".">  
        <span class="minor"><?php echo $serverInfo['uptime']['days'] . ' д. ' . $serverInfo['uptime']['hours'] . ' ч. ' . $serverInfo['uptime']['minutes'] . ' м. ' . $serverInfo['uptime']['seconds'] . ' с.'; ?></span>
</div>       
<div class="yellow pt">Мир:
        <img src="/images/icons/home.png" alt=".">  
        <span class="minor"><?php echo $serverInfo['realmInfo']['name']; ?></span>
</div>

       <div class="yellow pt">Realm:
        <img src="/images/icons/settings.png" alt=".">  
        <span class="minor"> set realmlist <?php echo $serverInfo['realmInfo']['address']; ?> </span>
</div>
   <?php else: ?>
   <div class="yellow pt">Статус сервера недоступен</div>
   <?php endif; ?>


    
    <div class="body">
        <h1 style="text-align: center; pt">Статус сервера</h1>

        <div class="yellow pt">Сервер:
        <img width="12" height="12" alt="в" src="<?php echo $iconPath; ?>">
            <span class="<?php echo $statusClass; ?>" id="id1e"><span><?php echo $serverStatus; ?></span></span>
</div>

    <p>  <div class="yellow pt">Онлайн:
            <img src="/images/icons/guilds.png" alt="." width="12" height="12"> <span class="info"><?php echo $playerCounts['online_players']; ?></span>
            <span class="minor">  <img src="/images/small/alliance.png" alt="." width="15" height="15">  
           <?php echo $playerCountsByFaction['alliance_players']; ?> </span>
          <span> vs </span>
           <span class="minor"> <img src="/images/small/orda.png" alt="." width="15" height="15"> 
           <?php echo $playerCountsByFaction['horde_players']; ?> </span>
     </div>   

          <p>    <div class="yellow pt">UPtime: 
            <img src="/images/icons/clock.png" alt=".">  
            <span class="minor"><?php echo $uptime['days'] . ' д. ' . $uptime['hours'] . ' ч. ' . $uptime['minutes'] . ' м. ' . $uptime['seconds'] . ' с.'; ?></span>
</div>       
         <p>     <div class="yellow pt">Мир:
            <img src="/images/icons/home.png" alt=".">  
            <span class="minor"><?php echo $realmInfo['name']; ?></span>
</div>

             <div class="yellow pt">Realm:
            <img src="/images/icons/settings.png" alt=".">  
            <span class="minor"> set realmlist <?php echo $realmInfo['address']; ?> </span>
</div>
</div>
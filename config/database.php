<?php
// config/database.php

return [
    'driver' => 'mysql',
    'host' => '185.135.81.201',
    'port' => [
        'acore_auth' => 8085,
        'acore_world' => 3306,
        'acore_characters' => 3306,
        'acore_site' => 3306,
        
    ],
    'username' => 'azeroth_server',
    'password' => 'Ja1YDYNCMdQifZB',
    'databases' => [
        'acore_auth',
        'acore_world',
        'acore_characters',
        'acore_site',
    ],
];
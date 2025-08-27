<?php
// config/database.php

return [
    'driver' => 'mysql',
    'host' => '188.113.169.185',
    'port' => [
        'acore_auth' => 8085,
        'acore_world' => 3306,
        'acore_characters' => 3306
    ],
    'username' => 'sakhwow_server',
    'password' => 'Ja1YDYNCMdQifZB',
    'databases' => [
        'acore_auth',
        'acore_world',
        'acore_characters'
    ],
];
<?php
// SOAP configuration for worldserver remote console
// NOTE: Restrict access to this host/port via firewall. Keep credentials secure.
return [
    'enabled' => true,
    'host' => '185.135.81.201',
    'port' => 7878,
    // Core identifier URI: 'urn:AC' for AzerothCore, 'urn:TC' for TrinityCore
    'uri' => 'urn:AC',
    'username' => 'gmmaster',
    'password' => 'vongola53',
    'timeout' => 8, // seconds

    // Command whitelist for safeExecute (format strings use sprintf semantics)
    'whitelist' => [
        'server_info' => 'server info',
        'announce' => 'announce %s',
        // send mail: player, subject, body
        'send_mail' => 'send mail %s "%s" "%s"',
        // send items: player, subject, body, items string like: 12345:1 67890:2
        'send_items' => '.send items %s "%s" "%s" %s',
        // character rename: player name
        'character_rename' => '.character rename %s',
        // titles add for targeted/selected player is not suitable from console; prefer mail/items.
    ],
];

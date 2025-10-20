<?php
// Универсальный SOAP-хелпер для AzerothCore/TrinityCore
// Используйте для интеграции сайта/панели с worldserver через SOAP
// Пример: php tools/soap_helper.php --safe=announce "Тестовое объявление"

$soap_connection_info = [
    'soap_uri' => 'urn:AC',
    'soap_host' => '185.135.81.201',
    'soap_port' => '7878',
    'account_name' => 'gmmaster',
    'account_password' => 'vongola53',
    'timeout' => 8,
    // Белый список команд (ключ => шаблон для sprintf)
    'whitelist' => [
        'server_info' => 'server info',
        'announce' => 'announce %s',
        'send_mail' => 'send mail %s "%s" "%s"',
        'send_items' => '.send items %s "%s" "%s" %s',
    ],
    'logfile' => __DIR__ . '/soap_helper.log',
];

function log_soap($msg) {
    global $soap_connection_info;
    $line = date('Y-m-d H:i:s') . ' ' . $msg . "\n";
    file_put_contents($soap_connection_info['logfile'], $line, FILE_APPEND);
}

function RemoteCommandWithSOAP($username, $password, $COMMAND) {
    global $soap_connection_info;
    $result = '';
    try {
        $conn = new SoapClient(NULL, [
            'location' => 'http://' . $soap_connection_info['soap_host'] . ':' . $soap_connection_info['soap_port'] . '/',
            'uri' => $soap_connection_info['soap_uri'],
            'style' => SOAP_RPC,
            'login' => $username,
            'password' => $password,
            'connection_timeout' => $soap_connection_info['timeout'],
        ]);
        $result = $conn->executeCommand(new SoapParam($COMMAND, 'command'));
        unset($conn);
    } catch (Exception $e) {
        $result = "[SOAP ERROR] " . $e->getMessage();
    }
    log_soap("CMD: $COMMAND | RESULT: " . (is_string($result) ? $result : json_encode($result, JSON_UNESCAPED_UNICODE)));
    return $result;
}

function safe_soap($key, ...$args) {
    global $soap_connection_info;
    if (!isset($soap_connection_info['whitelist'][$key])) {
        throw new Exception('Команда не разрешена: ' . $key);
    }
    $cmd = @vsprintf($soap_connection_info['whitelist'][$key], $args);
    if ($cmd === false) throw new Exception('Ошибка формата команды');
    return RemoteCommandWithSOAP($soap_connection_info['account_name'], $soap_connection_info['account_password'], $cmd);
}

// CLI usage
if (php_sapi_name() === 'cli' && basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    $args = $argv;
    array_shift($args);
    $safeKey = null;
    foreach ($args as $i => $a) {
        if (strpos($a, '--safe=') === 0) {
            $safeKey = substr($a, 7);
            unset($args[$i]);
        }
    }
    $args = array_values($args);
    try {
        if ($safeKey) {
            $out = safe_soap($safeKey, ...$args);
        } else {
            if (empty($args)) {
                echo "Использование: php soap_helper.php --safe=announce 'текст'\n";
                exit(1);
            }
            $out = RemoteCommandWithSOAP($soap_connection_info['account_name'], $soap_connection_info['account_password'], $args[0]);
        }
        echo (is_string($out) ? $out : json_encode($out, JSON_UNESCAPED_UNICODE)) . "\n";
    } catch (Throwable $e) {
        echo '[ERROR] ' . $e->getMessage() . "\n";
        exit(2);
    }
}

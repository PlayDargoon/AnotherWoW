<?php
// tools/soap_reset_achievements.php
// Usage: php soap_reset_achievements.php --names=Gnofypack,Selonian

$soapConfig = require __DIR__ . '/../config/soap.php';

if (!$soapConfig['enabled']) {
    fwrite(STDERR, "SOAP не включен в конфигурации.\n");
    exit(1);
}

function parseArgs(array $argv) {
    $out = ['names' => []];
    foreach ($argv as $arg) {
        if (strpos($arg, '--names=') === 0) {
            $val = substr($arg, 8);
            $parts = preg_split('/[,;:\s]+/', $val);
            foreach ($parts as $p) if (strlen($p)) $out['names'][] = $p;
        }
    }
    return $out;
}

$opts = parseArgs($argv);
if (empty($opts['names'])) {
    echo "Использование: php tools/soap_reset_achievements.php --names=Gnofypack,Selonian\n";
    exit(1);
}

try {
    $client = new SoapClient(null, [
        'location' => "http://{$soapConfig['host']}:{$soapConfig['port']}/",
        'uri' => $soapConfig['uri'],
        'login' => $soapConfig['username'],
        'password' => $soapConfig['password'],
        'connection_timeout' => $soapConfig['timeout'],
        'exceptions' => true,
        'trace' => true,
    ]);

    foreach ($opts['names'] as $name) {
        $command = "reset achievements {$name}";
        echo "Выполняю: {$command}\n";
        
        try {
            $result = $client->executeCommand(new SoapParam($command, 'command'));
            echo "Результат для {$name}:\n{$result}\n\n";
        } catch (SoapFault $e) {
            fwrite(STDERR, "Ошибка для {$name}: " . $e->getMessage() . "\n");
        }
    }
    
    echo "Готово.\n";
} catch (SoapFault $e) {
    fwrite(STDERR, "Ошибка подключения SOAP: " . $e->getMessage() . "\n");
    exit(2);
}

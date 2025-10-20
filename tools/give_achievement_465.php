<?php
// tools/give_achievement_465.php
require_once __DIR__ . '/../src/services/SoapClientService.php';

$soapCfg = require __DIR__ . '/../config/soap.php';

if (!class_exists('SoapClient')) {
    die("SOAP extension не загружен\n");
}

$soapService = new \Services\SoapClientService($soapCfg);

$characterName = 'Коллапс';
$achievementId = 465;

echo "Выдаю достижение {$achievementId} персонажу {$characterName}...\n\n";

try {
    // Команда для выдачи достижения: .achieve add <player_name> <achievement_id>
    $command = ".achieve add {$characterName} {$achievementId}";
    
    echo "SOAP команда: {$command}\n\n";
    
    // Выполняем команду через SOAP
    $client = new SoapClient(null, [
        'location' => "http://{$soapCfg['host']}:{$soapCfg['port']}/",
        'uri' => $soapCfg['uri'],
        'style' => SOAP_RPC,
        'login' => $soapCfg['username'],
        'password' => $soapCfg['password'],
        'connection_timeout' => $soapCfg['timeout'],
    ]);
    
    $result = $client->executeCommand(new SoapParam($command, 'command'));
    
    echo "Результат выполнения:\n";
    echo $result . "\n\n";
    
    if (stripos($result, 'Achievement') !== false || stripos($result, 'added') !== false) {
        echo "✅ Достижение успешно выдано!\n";
    } else {
        echo "⚠️ Возможно, возникла ошибка. Проверьте результат выше.\n";
    }
    
} catch (Exception $e) {
    echo "❌ Ошибка SOAP: " . $e->getMessage() . "\n";
}

?>

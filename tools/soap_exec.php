<?php
// tools/soap_exec.php
// Usage examples:
// php tools/soap_exec.php "server info"
// php tools/soap_exec.php --safe=announce "Сервер приветствует героев!"

require_once __DIR__ . '/../src/services/SoapClientService.php';

use Services\SoapClientService;

$cfg = require __DIR__ . '/../config/soap.php';
$svc = new SoapClientService($cfg);

// Parse args
$safeKey = null;
$args = $argv;
array_shift($args);
foreach ($args as $i => $a) {
    if (strpos($a, '--safe=') === 0) {
        $safeKey = substr($a, 7);
        unset($args[$i]);
    }
}
$args = array_values($args);

try {
    if ($safeKey) {
        $out = $svc->safeExecute($safeKey, ...$args);
    } else {
        if (empty($args)) {
            fwrite(STDERR, "Provide command string or use --safe=key args...\n");
            exit(1);
        }
        $cmd = $args[0];
        // Allow spaces via quoted input, rest of args ignored for raw
        $out = $svc->execute($cmd);
    }
    echo $out . (substr($out, -1) === "\n" ? '' : "\n");
} catch (Throwable $e) {
    fwrite(STDERR, '[ERROR] ' . $e->getMessage() . "\n");
    exit(2);
}

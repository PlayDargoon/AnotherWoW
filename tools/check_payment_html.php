<?php
// tools/check_payment_html.php
// Проверка содержимого HTML от Selfwork

require_once __DIR__ . '/../bootstrap.php';

$service = new SelfworkService();
$result = $service->createPayment(1, 50.00, 'Проверка домена');

if (isset($result['error'])) {
    echo "Ошибка: {$result['error']}\n";
    exit(1);
}

$html = $result['html_content'];

// Ищем smzInitError
if (preg_match('/window\.smzInitError\s*=\s*({[^}]+})/', $html, $matches)) {
    echo "Найден smzInitError:\n";
    $json = $matches[1];
    $error = json_decode($json, true);
    print_r($error);
    
    if (!empty($error['errorMessage'])) {
        echo "\nОшибка: " . $error['errorMessage'] . "\n";
    }
} else {
    echo "smzInitError не найден - всё ОК!\n";
}

// Ищем smzInitPayment
if (preg_match('/window\.smzInitPayment\s*=\s*({[^;]+});/', $html, $matches)) {
    echo "\nНайден smzInitPayment:\n";
    $json = $matches[1];
    $payment = json_decode($json, true);
    print_r($payment);
}

// Сохраняем для просмотра
file_put_contents(__DIR__ . '/../cache/last_payment_html.html', $html);
echo "\nHTML сохранён в cache/last_payment_html.html\n";

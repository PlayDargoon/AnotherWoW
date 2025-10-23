<?php
// tools/test_payment_flow.php
// Проверка полного flow создания платежа

require_once __DIR__ . '/../bootstrap.php';

echo "=== Тест создания платежа (PROD mode) ===\n\n";

// Эмулируем сессию (без safeSessionStart для CLI)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['user_id'] = 1; // Ваш реальный user_id

// Эмулируем POST-запрос
$_POST['amount'] = 100.00;

try {
    $service = new SelfworkService();
    $result = $service->createPayment(
        userId: $_SESSION['user_id'],
        amount: 100.00,
        description: 'Тестовое пополнение баланса'
    );
    
    if (isset($result['error'])) {
        echo "❌ ОШИБКА: {$result['error']}\n";
        exit(1);
    }
    
    echo "✅ Платёж создан успешно!\n";
    echo "Order ID: {$result['order_id']}\n\n";
    
    // Проверяем HTML
    if (!empty($result['html_content'])) {
        echo "✅ HTML-контент получен (" . strlen($result['html_content']) . " байт)\n";
        
        // Ищем ошибку в HTML
        if (strpos($result['html_content'], 'errorMessage') !== false) {
            preg_match('/errorMessage":"([^"]+)"/', $result['html_content'], $matches);
            if (!empty($matches[1])) {
                $error = json_decode('"' . $matches[1] . '"'); // декодируем unicode
                echo "\n⚠️  ОШИБКА В HTML: $error\n";
                echo "\nВероятная причина: домен не добавлен в настройках магазина Selfwork\n";
                echo "Решение: добавьте 'azeroth.su' в разрешённые домены магазина\n";
            }
        } else {
            echo "✅ Ошибок в HTML не обнаружено - страница оплаты корректна\n";
        }
    }
    
    // Сохраняем в БД
    $siteDb = DatabaseConnection::getSiteConnection();
    $paymentModel = new Payment($siteDb);
    $paymentModel->create([
        'yk_id' => $result['order_id'],
        'user_id' => $_SESSION['user_id'],
        'amount' => 100.00,
        'currency' => 'RUB',
        'status' => 'pending',
        'description' => 'Тестовое пополнение',
    ]);
    
    echo "✅ Платёж сохранён в БД\n\n";
    
    echo "Проверьте в личном кабинете Selfwork:\n";
    echo "1. Раздел 'Параметры магазина'\n";
    echo "2. Найдите поле 'Разрешённые домены' или 'Домены магазина'\n";
    echo "3. Добавьте: azeroth.su\n";
    echo "4. После добавления попробуйте создать платёж через форму на сайте\n";
    
} catch (Throwable $e) {
    echo "❌ ИСКЛЮЧЕНИЕ: {$e->getMessage()}\n";
    echo "Файл: {$e->getFile()}:{$e->getLine()}\n";
}

echo "\n=== Тест завершён ===\n";

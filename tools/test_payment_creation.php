<?php
// tools/test_payment_creation.php
// Тест создания платежа через SelfworkService (localhost)

require_once __DIR__ . '/../bootstrap.php';

echo "=== Тест создания платежа Selfwork (localhost) ===\n\n";

// Показываем текущие настройки
$config = require __DIR__ . '/../config/payment_selfwork.php';
echo "Настройки для PROD:\n";
echo "- Site base: {$config['site_base']}\n";
echo "- Webhook: {$config['webhook_path']}\n";
echo "- Rate: {$config['rub_to_coins_rate']} RUB = 1 bonus\n\n";

echo "⚠️  ВНИМАНИЕ: Тестируем на localhost:8000\n";
echo "   Return URL в запросе будет использовать http://localhost:8000\n";
echo "   На проде автоматически будет https://azeroth.su\n\n";

// Временно меняем site_base для локального теста
$configPath = __DIR__ . '/../config/payment_selfwork.php';
$originalContent = file_get_contents($configPath);
$testConfig = $config;
$testConfig['site_base'] = 'http://localhost:8000';
file_put_contents($configPath, '<?php return ' . var_export($testConfig, true) . ';');

try {
    $service = new SelfworkService();
    echo "✓ SelfworkService инициализирован\n\n";
    
    // Тест: Создание платежа на 250 RUB
    echo "Создаём платёж на 250 RUB...\n";
    $amount = 250.00;
    $result = $service->createPayment(123, $amount, 'Тест localhost');
    
    if (isset($result['error'])) {
        echo "\n❌ ОШИБКА API:\n";
        echo "   {$result['error']}\n\n";
        
        echo "Возможные причины:\n";
        echo "1. Неверный API endpoint (проверьте документацию Selfwork)\n";
        echo "2. Неверный формат запроса или обязательные поля\n";
        echo "3. API ключ неактивен или неверный\n\n";
        
        echo "Что проверить:\n";
        echo "- Личный кабинет: https://pro.selfwork.ru\n";
        echo "- Документация API: https://docs.selfwork.ru\n";
        echo "- Текущий endpoint: {$config['base_url']}/v1/invoices\n";
    } else {
        echo "\n✅ УСПЕХ! Платёж создан\n";
        echo "Order ID: {$result['order_id']}\n";
        echo "Payment URL: {$result['payment_url']}\n\n";
        
        // Сохраняем в БД
        $siteDb = DatabaseConnection::getSiteConnection();
        $model = new Payment($siteDb);
        $model->create([
            'yk_id' => $result['order_id'],
            'user_id' => 123,
            'amount' => $amount,
            'currency' => 'RUB',
            'status' => 'pending',
            'description' => 'Тест localhost',
        ]);
        
        echo "✓ Платёж сохранён в БД\n";
        echo "\nСсылки (для localhost):\n";
        echo "- Payment page: {$result['payment_url']}\n";
        echo "- Webhook: http://localhost:8000/selfwork/webhook\n";
        echo "\nНа проде будут:\n";
        echo "- Payment page: https://azeroth.su/cache/selfwork_payment_{$result['order_id']}.html\n";
        echo "- Webhook: https://azeroth.su/selfwork/webhook\n";
    }
    
} catch (Throwable $e) {
    echo "\n❌ ИСКЛЮЧЕНИЕ: {$e->getMessage()}\n";
    echo "{$e->getTraceAsString()}\n";
} finally {
    // Восстанавливаем prod конфиг
    file_put_contents($configPath, $originalContent);
    echo "\n✓ Конфиг восстановлен (site_base = https://azeroth.su)\n";
}

echo "\n=== Тест завершён ===\n";


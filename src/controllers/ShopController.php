<?php

class ShopController
{
    private $userModel;
    private $characterModel;

    public function __construct($userModel, $characterModel)
    {
        $this->userModel = $userModel;
        $this->characterModel = $characterModel;
    }

    private function shop_log(string $msg): void
    {
        $line = date('Y-m-d H:i:s') . ' [SHOP] ' . $msg . "\n";
        @file_put_contents(__DIR__ . '/../../cache/shop_buy.log', $line, FILE_APPEND);
    }

    // Обработчик покупки предмета (Эмблема триумфа 47241) за 10 бонусов
    public function buy()
    {
        header('Content-Type: application/json; charset=utf-8');

        if (empty($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['ok' => false, 'error' => 'Требуется авторизация']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['ok' => false, 'error' => 'Неверный метод']);
            return;
        }

        // Данные запроса
        $itemId = $_POST['item_id'] ?? '';
        $price = (int)($_POST['price'] ?? 0);
        $charGuid = (int)($_POST['char_guid'] ?? 0);

        // Определяем доступные товары
        $availableItems = [
            'rename' => ['price' => 50, 'type' => 'service', 'name' => 'Смена имени'],
            '47241' => ['price' => 10, 'type' => 'item', 'name' => 'Эмблема триумфа']
        ];

        if (!isset($availableItems[$itemId]) || $availableItems[$itemId]['price'] !== $price) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'Неверные параметры покупки']);
            return;
        }

        $item = $availableItems[$itemId];

        try {
            $this->shop_log("POST /shop/buy item={$itemId} price={$price} guid={$charGuid} user_id=" . ($_SESSION['user_id'] ?? 'n/a'));

            // Получаем информацию о пользователе
            $userModel = new User(DatabaseConnection::getAuthConnection());
            $userInfo = $userModel->getUserInfoByUsername($_SESSION['username'] ?? '');
            if (empty($userInfo['id'])) {
                throw new Exception('Пользователь не найден');
            }

            // Проверяем, что выбранный персонаж принадлежит пользователю
            $chars = $this->characterModel->getCharactersByUserId($userInfo['id']);
            $char = null;
            foreach ($chars as $c) {
                if ((int)$c['guid'] === $charGuid) { $char = $c; break; }
            }
            if (!$char) {
                http_response_code(403);
                echo json_encode(['ok' => false, 'error' => 'Персонаж не найден или не принадлежит вам']);
                return;
            }

            // Проверяем баланс
            require_once __DIR__ . '/../models/CachedAccountCoins.php';
            $coinsModel = new CachedAccountCoins(DatabaseConnection::getSiteConnection());
            $balance = $coinsModel->getBalance($userInfo['id']);
            if ($balance < $price) {
                echo json_encode([
                    'ok' => false,
                    'error' => 'Недостаточно бонусов для покупки',
                    'need_topup' => true,
                    'balance' => (int)$balance,
                    'price' => (int)$price,
                    'topup_url' => '/payment/create'
                ]);
                return;
            }

            // Обработка покупки в зависимости от типа товара
            if ($item['type'] === 'service') {
                // Услуги (смена имени и т.д.)
                if ($itemId === 'rename') {
                    // Проверяем наличие SOAP
                    if (!class_exists('SoapClient')) {
                        $this->shop_log('SOAP extension is not loaded in web SAPI');
                        http_response_code(500);
                        echo json_encode(['ok' => false, 'error' => 'SOAP расширение не включено на веб-сервере']);
                        return;
                    }
                    
                    require_once __DIR__ . '/../../src/services/SoapClientService.php';
                    $soapCfg = require __DIR__ . '/../../config/soap.php';
                    $soapService = new \Services\SoapClientService($soapCfg);

                    // Выполняем команду .character rename для выбранного персонажа
                    $this->shop_log("SOAP character rename for {$char['name']}");
                    $soapResult = $soapService->safeExecute('character_rename', $char['name']);

                    if (!is_string($soapResult)) {
                        $this->shop_log('SOAP character_rename failed: ' . json_encode($soapResult, JSON_UNESCAPED_UNICODE));
                        http_response_code(500);
                        echo json_encode(['ok' => false, 'error' => 'Не удалось выполнить команду смены имени. Попробуйте позже.']);
                        return;
                    }

                    // Проверяем успешность выполнения команды
                    $this->shop_log('SOAP character_rename result: ' . $soapResult);

                    // Списываем бонусы
                    $reason = sprintf('shop: %s for %s', $item['name'], $char['name']);
                    $coinsModel->add((int)$userInfo['id'], -$price, $reason);
                    $this->shop_log('Coins deducted and character rename activated for ' . $char['name']);

                    echo json_encode([
                        'ok' => true,
                        'message' => 'Покупка успешно совершена! При следующем входе персонажа в игру имя будет изменено.',
                        'balance' => $coinsModel->getBalance($userInfo['id'])
                    ]);
                    return;
                }
            } elseif ($item['type'] === 'item') {
                // Обычные предметы
                if (!class_exists('SoapClient')) {
                    $this->shop_log('SOAP extension is not loaded in web SAPI');
                    http_response_code(500);
                    echo json_encode(['ok' => false, 'error' => 'SOAP расширение не включено на веб-сервере']);
                    return;
                }
                
                require_once __DIR__ . '/../../src/services/SoapClientService.php';
                $soapCfg = require __DIR__ . '/../../config/soap.php';
                $soapService = new \Services\SoapClientService($soapCfg);

                $subject = 'Магазин: ' . $item['name'];
                $body = 'Спасибо за покупку!';
                $itemsArg = $itemId . ':1';
                
                $this->shop_log("SOAP send item {$itemId} to {$char['name']}");
                $soapResult = $soapService->safeExecute('send_items', $char['name'], $subject, $body, $itemsArg);

                if (!is_string($soapResult) || stripos($soapResult, 'Mail sent') === false) {
                    $this->shop_log('SOAP send_items failed: ' . (is_string($soapResult) ? $soapResult : json_encode($soapResult, JSON_UNESCAPED_UNICODE)));
                    http_response_code(500);
                    echo json_encode(['ok' => false, 'error' => 'Не удалось отправить предмет через почту. Попробуйте позже.']);
                    return;
                }

                // Списываем бонусы
                $reason = sprintf('shop: %s (%s) x1 for %s', $item['name'], $itemId, $char['name']);
                $coinsModel->add((int)$userInfo['id'], -$price, $reason);
                $this->shop_log('Coins deducted and item sent to ' . $char['name']);

                echo json_encode([
                    'ok' => true,
                    'message' => 'Покупка успешно совершена. Предмет ожидает на почте персонажа.',
                    'balance' => $coinsModel->getBalance($userInfo['id'])
                ]);
                return;
            }

        } catch (Throwable $e) {
            $this->shop_log('Exception: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['ok' => false, 'error' => 'Внутренняя ошибка: ' . $e->getMessage()]);
            return;
        }
    }
}

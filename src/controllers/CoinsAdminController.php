<?php
// src/controllers/CoinsAdminController.php

class CoinsAdminController
{
    private $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    public function index()
    {
        // Доступ только для GM >= 4
        $userId = $_SESSION['user_id'] ?? null;
        $accessLevel = $userId ? $this->userModel->getUserAccessLevel($userId) : 0;
        if ($accessLevel < 4) {
            renderTemplate('layout.html.php', [
                'contentFile' => 'pages/admin_panel_error.html.php',
            ]);
            return;
        }

        $message = null;
        $history = [];
        $form = [
            'login' => '',
            'amount' => '',
            'reason' => '',
        ];
        $historyLogin = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $form['login'] = trim($_POST['login'] ?? '');
            $form['amount'] = trim($_POST['amount'] ?? '');
            $form['reason'] = trim($_POST['reason'] ?? '');
            $historyLogin = $form['login'];

            if ($form['login'] === '' || $form['amount'] === '' || !is_numeric($form['amount'])) {
                $message = 'Укажите логин и корректную сумму.';
            } else {
                // Простой запрос напрямую к БД для избежания таймаутов
                $stmt = \DatabaseConnection::getAuthConnection()->prepare("SELECT id FROM account WHERE username = ? LIMIT 1");
                $stmt->execute([$form['login']]);
                $targetId = $stmt->fetchColumn();
                
                if (!$targetId) {
                    $message = 'Пользователь с таким логином не найден.';
                } else {
                    $amount = (int)$form['amount'];
                    if ($amount === 0) {
                        $message = 'Сумма не может быть нулевой.';
                    } else {
                        require_once __DIR__ . '/../models/Notification.php';
                        $notificationModel = new \Notification();
                        
                        // Получаем имя GM напрямую
                        $stmt = \DatabaseConnection::getAuthConnection()->prepare("SELECT username FROM account WHERE id = ? LIMIT 1");
                        $stmt->execute([$userId]);
                        $gmName = $stmt->fetchColumn() ?: 'Unknown';
                        
                        $reason = $form['reason'] !== ''
                            ? $form['reason'] . ' (GM: ' . $gmName . ')'
                            : 'Ручное начисление (GM: ' . $gmName . ')';
                        
                        // Добавляем запись через CachedAccountCoins для правильной инвалидации кеша
                        require_once __DIR__ . '/../models/CachedAccountCoins.php';
                        $coinsModel = new \CachedAccountCoins(\DatabaseConnection::getSiteConnection());
                        
                        $result = $coinsModel->add($targetId, $amount, $reason);
                        
                        if (!$result) {
                            $message = 'Ошибка при добавлении записи в базу данных.';
                        } else {
                            // Создаем уведомление только при начислении (положительные суммы)
                            if ($amount > 0) {
                                $coinsText = $amount . ' ' . $this->coinsDeclension($amount);
                                $verb = ($amount == 1) ? 'начислен' : 'начислено';
                                $notificationMessage = "Вам {$verb} {$coinsText}. {$reason}";
                                $notificationModel->create($targetId, 'admin_coins', $notificationMessage);
                            }
                            
                            $message = 'Операция успешно выполнена.';
                            
                            // Принудительно показываем историю после успешной операции
                            $historyLogin = $form['login'];
                        }
                    }
                }
            }
        }

        // История по логину (если был ввод или выполнена операция)
        $loginForHistory = !empty($historyLogin) ? $historyLogin : (!empty($form['login']) ? $form['login'] : '');
        
        if (!empty($loginForHistory)) {
            // Прямой запрос для получения ID пользователя
            $stmt = \DatabaseConnection::getAuthConnection()->prepare("SELECT id FROM account WHERE username = ? LIMIT 1");
            $stmt->execute([$loginForHistory]);
            $historyTargetId = $stmt->fetchColumn();
            
            if ($historyTargetId) {
                // Прямой запрос истории с принудительным обновлением
                $stmt = \DatabaseConnection::getSiteConnection()->prepare("
                    SELECT * FROM account_coins 
                    WHERE account_id = ? 
                    ORDER BY created_at DESC, id DESC 
                    LIMIT 20
                ");
                $stmt->execute([$historyTargetId]);
                $history = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Устанавливаем historyLogin для отображения в шаблоне
                $historyLogin = $loginForHistory;
            }
        }

        renderTemplate('layout.html.php', [
            'contentFile' => 'pages/admin_coins.html.php',
            'pageTitle' => 'Начисление бонусов',
            'message' => $message,
            'form' => $form,
            'history' => $history,
            'historyLogin' => $historyLogin,
        ]);
    }

    private function coinsDeclension($count) {
        $count = abs($count);
        if ($count % 100 >= 11 && $count % 100 <= 14) {
            return 'бонусов';
        }
        switch ($count % 10) {
            case 1: return 'бонус';
            case 2: case 3: case 4: return 'бонуса';
            default: return 'бонусов';
        }
    }
}

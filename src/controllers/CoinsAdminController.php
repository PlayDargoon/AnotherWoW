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
                        $coinsModel->add($targetId, $amount, $reason);

                        // Создаем уведомление только при начислении (положительные суммы)
                        if ($amount > 0) {
                            $coinsText = $amount . ' ' . $this->coinsDeclension($amount);
                            $notificationMessage = "Вам начислено {$coinsText}. Причина: {$reason}";
                            $notificationModel->create($targetId, 'admin_coins', $notificationMessage);
                        }
                        
                        $message = 'Операция успешно выполнена.';
                    }
                }
            }
        }

        // История по логину (если был ввод)
        if (!empty($historyLogin)) {
            // Прямой запрос для получения ID пользователя
            $stmt = \DatabaseConnection::getAuthConnection()->prepare("SELECT id FROM account WHERE username = ? LIMIT 1");
            $stmt->execute([$historyLogin]);
            $targetId = $stmt->fetchColumn();
            
            if ($targetId) {
                // Прямой запрос истории, избегая кешированной модели
                $stmt = \DatabaseConnection::getSiteConnection()->prepare("SELECT * FROM account_coins WHERE account_id = ? ORDER BY created_at DESC LIMIT 20");
                $stmt->execute([$targetId]);
                $history = $stmt->fetchAll();
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
            return 'монет';
        }
        switch ($count % 10) {
            case 1: return 'монета';
            case 2: case 3: case 4: return 'монеты';
            default: return 'монет';
        }
    }
}

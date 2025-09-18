<?php
// src/controllers/CabinetController.php

class CabinetController
{
    // Экшен: история начислений монет
    public function coinsHistory()
    {
        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            header('Location: /login');
            exit;
        }
        $username = $_SESSION['username'];
        $userInfo = $this->userModel->getUserInfoByUsername($username);
        require_once __DIR__ . '/../models/AccountCoins.php';
        $coinsModel = new \AccountCoins(\DatabaseConnection::getSiteConnection());
        $history = $coinsModel->getHistory($userInfo['id'], 50);
        // Если начислений нет — пробуем получить историю из mmotop-файла
        if (empty($history)) {
            require_once __DIR__ . '/../helpers/mmotop_history.php';
            $mmotopFile = __DIR__ . '/../../../mmotop.txt'; // путь к файлу, скорректируйте при необходимости
            $mmotopHistory = getMmotopHistoryForUser($username, $mmotopFile, 50);
            // Преобразуем к формату для шаблона
            $history = array_map(function($row) {
                return [
                    'created_at' => $row['date'],
                    'coins' => 1,
                    'reason' => 'Голос (mmotop.txt)',
                ];
            }, $mmotopHistory);
        }
        renderTemplate('layout.html.php', [
    'contentFile' => 'pages/account_coins_history.html.php',
    'history' => $history,
    'backUrl' => '/cabinet',
    'pageTitle' => 'История начислений',
        ]);
    }
    private $userModel;
    private $characterModel;

    public function __construct(User $userModel, Character $characterModel)
    {
        $this->userModel = $userModel;
        $this->characterModel = $characterModel;
    }

    public function index()
    {
        // Проверяем, вошёл ли пользователь в систему
        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            // Если пользователь не авторизован, перенаправляем на страницу входа
            header('Location: /login');
            exit;
        }

        // Если авторизованы, получаем данные пользователя
        $username = $_SESSION['username'];
        $userInfo = $this->userModel->getUserInfoByUsername($username);

        // Получаем персонажей пользователя
        $characters = $this->characterModel->getCharactersByUserId($userInfo['id']);

        // Формируем массив персонажей с указанием фракции и ролей
        $formattedCharacters = [];
        foreach ($characters as $char) {
            $formattedChar = $char;
            $formattedChar['factionImage'] = getFactionImage($char['race']); // Добавляем изображение фракции
            $gmLevel = $this->userModel->getGmLevelForCharacter($char['name']); // Проверяем по имени персонажа
            $roleText = getGMRole($gmLevel); // Добавляем текст роли
            $formattedChar['roleText'] = $roleText;
            // Выделяем текст в скобках, если есть
            if (!empty($roleText) && strpos($roleText, '[') !== false) {
                $start = strpos($roleText, '[') + 1;
                $end = strpos($roleText, ']', $start);
                if ($end !== false) {
                    $formattedChar['roleTextShort'] = substr($roleText, $start, $end - $start);
                } else {
                    $formattedChar['roleTextShort'] = $roleText;
                }
            } else {
                $formattedChar['roleTextShort'] = '';
            }
            $formattedCharacters[] = $formattedChar;
        }

        // Получаем уровень доступа пользователя
        $userAccessLevel = $this->userModel->getUserAccessLevel($userInfo['id']);

    // Получаем баланс монет (coins) из account_coins
    require_once __DIR__ . '/../models/AccountCoins.php';
    $coinsModel = new \AccountCoins(\DatabaseConnection::getSiteConnection());
    $coins = $coinsModel->getBalance($userInfo['id']);
        $data = [
    'contentFile' => 'pages/cabinet.html.php',
    'userInfo' => $userInfo,
    'characters' => $formattedCharacters,
    'characterModel' => $this->characterModel,
    'userAccessLevel' => $userAccessLevel,
    'coins' => $coins,
    'pageTitle' => 'Личный кабинет',
        ];
        renderTemplate('layout.html.php', $data);
    }
}
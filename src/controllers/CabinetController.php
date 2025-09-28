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
        $coinsModel = new \CachedAccountCoins(\DatabaseConnection::getSiteConnection());
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
                    'reason' => 'Бонус за голосование (mmotop.txt)',
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

        // Получаем GM уровни для всех персонажей одним запросом
        $characterNames = array_column($characters, 'name');
        $gmLevels = [];
        if (!empty($characterNames)) {
            $gmLevels = $this->userModel->getGmLevelsForCharacters($characterNames);
        }

        // Цвета классов для отображения имен персонажей
        $classColors = [
            1 => '#C69B6D', // Воин
            2 => '#F48CBA', // Паладин
            3 => '#AAD372', // Охотник
            4 => '#FFF468', // Разбойник
            5 => '#FFFFFF', // Жрец
            6 => '#C41E3A', // Рыцарь Смерти
            7 => '#0070DD', // Шаман
            8 => '#3FC7EB', // Маг
            9 => '#8788EE', // Чернокнижник
            10 => '#00FF98', // Монах
            11 => '#FF7C0A', // Друид
            12 => '#A330C9', // Охотник на Демонов
            13 => '#33937F', // Пробуждающий (Evoker)
        ];

        // Формируем массив персонажей с указанием фракции и ролей
        $formattedCharacters = [];
        foreach ($characters as $char) {
            $formattedChar = $char;
            $formattedChar['factionImage'] = getFactionImage($char['race']); // Добавляем изображение фракции
            $formattedChar['classColor'] = isset($classColors[$char['class']]) ? $classColors[$char['class']] : '#FFF'; // Добавляем цвет класса
            $gmLevel = $gmLevels[$char['name']] ?? 0; // Получаем из предварительно загруженных данных
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
            
            // Форматируем время игры
            $tt = isset($char['totaltime']) ? (int)$char['totaltime'] : 0;
            $days = floor($tt / 86400);
            $hours = floor(($tt % 86400) / 3600);
            $minutes = floor(($tt % 3600) / 60);
            $formattedChar['playtime'] = ($days > 0 ? $days.'д ' : '') . ($hours > 0 ? $hours.'ч ' : '') . $minutes.'м';
            
            $formattedCharacters[] = $formattedChar;
        }

        // Получаем уровень доступа пользователя
        $userAccessLevel = $this->userModel->getUserAccessLevel($userInfo['id']);

        // Получаем информацию о банах и мутах
        $banInfo = $this->userModel->isBanned($userInfo['id']);
        $muteInfo = $this->userModel->isMuted($userInfo['id']);

        // Получаем баланс бонусов из правильной таблицы
        $coinsModel = new \CachedAccountCoins(\DatabaseConnection::getSiteConnection());
        $bonusBalance = $coinsModel->getBalance($userInfo['id']);

        $data = [
    'contentFile' => 'pages/cabinet.html.php',
    'userInfo' => $userInfo,
    'characters' => $formattedCharacters,
    'characterModel' => $this->characterModel,
    'userAccessLevel' => $userAccessLevel,
    'banInfo' => $banInfo,
    'muteInfo' => $muteInfo,
    'bonusBalance' => $bonusBalance,
    'pageTitle' => 'Личный кабинет',
        ];
        renderTemplate('layout.html.php', $data);
    }
}
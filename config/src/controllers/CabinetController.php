<?php
// src/controllers/CabinetController.php

class CabinetController
{
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
            $formattedChar['roleText'] = getGMRole($gmLevel); // Добавляем текст роли
            $formattedCharacters[] = $formattedChar;
        }

        // Получаем уровень доступа пользователя
        $userAccessLevel = $this->userModel->getUserAccessLevel($userInfo['id']);

        // Передаем данные в шаблон
        $data = [
            'contentFile' => 'pages/cabinet.html.php', // Передаем путь к шаблону
            'userInfo' => $userInfo,
            'characters' => $formattedCharacters, // Передаем массив персонажей с дополнительными данными
            'characterModel' => $this->characterModel, // Передаем модель персонажей
            'userAccessLevel' => $userAccessLevel, // Передаем уровень доступа пользователя
        ];

        // Рендерим шаблон
        renderTemplate('layout.html.php', $data);
    }
}
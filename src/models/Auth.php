<?php
// src/models/Auth.php

class Auth
{
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Авторизует пользователя
     */
    public function authorizeUser($username, $password)
    {
        // Получаем пользователя по имени
        $user = (new User($this->pdo))->getUserInfoByUsername($username);

        if (!$user) {
            return false;
        }

        // Проверяем пароль
        return VerifySRP6Login($username, $password, $user['salt'], $user['verifier']);
    }
}
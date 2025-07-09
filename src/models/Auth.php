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
     * Проверяет, существует ли пользователь с данным логином
     */
    public function existsUsername($username)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM account WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Извлекает данные пользователя (соль и верификатор)
     */
    public function retrieveUserData($username)
    {
        $stmt = $this->pdo->prepare("SELECT salt, verifier FROM account WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Авторизует пользователя по логину
     */
    public function authorizeUser($username, $password)
    {
        // Проверяем, существует ли пользователь с данным логином
        if (!$this->existsUsername($username)) {
            return false;
        }

        // Получаем соль и верификатор
        $userData = $this->retrieveUserData($username);

        if (!$userData) {
            return false;
        }

        // Проверяем пароль
        return VerifySRP6Login($username, $password, $userData['salt'], $userData['verifier']);
    }
}
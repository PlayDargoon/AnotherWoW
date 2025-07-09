<?php
// src/models/User.php

class User
{
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Проверяет, существует ли пользователь с данным именем
     */
    public function existsUsername($username)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM account WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Создаёт нового пользователя
     */
    public function createNewUser($username, $email, $salt, $verifier)
    {
        $stmt = $this->pdo->prepare("INSERT INTO account (username, email, salt, verifier) VALUES (:username, :email, :salt, :verifier)");
        $stmt->execute([
            'username' => $username,
            'email' => $email,
            'salt' => $salt,
            'verifier' => $verifier
        ]);
        return $this->pdo->lastInsertId();
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
     * Авторизует пользователя
     */
    public function authorizeUser($username, $password)
    {
        // Проверяем, существует ли пользователь
        if (!$this->existsUsername($username)) {
            return false;
        }

        // Получаем соль и верификатор
        $userData = $this->retrieveUserData($username);

        if (!$userData) {
            return false;
        }

        // Рассчитываем хэш для сравнения
        $calculatedVerifier = calculateSRP6Verifier($username, $password, $userData['salt']);

        // Сравниваем верификаторы
        return $calculatedVerifier === $userData['verifier'];
    }

    /**
     * Получает информацию о пользователе по имени
     */
    public function getUserInfoByUsername($username)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM account WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Получает ID пользователя по имени
     */
    public function getUserIdByUsername($username)
    {
        $stmt = $this->pdo->prepare("SELECT id FROM account WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetchColumn();
    }
}
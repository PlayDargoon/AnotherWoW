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
     * Авторизует пользователя
     */
    public function authorizeUser($username, $password)
    {
        // Проверяем, существует ли пользователь
        if (!$this->existsUsername($username)) {
            return false;
        }

        // Получаем соль и верификатор
        $stmt = $this->pdo->prepare("SELECT salt, verifier FROM account WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        // Если нет данных, прекращаем авторизацию
        if (!$result) {
            return false;
        }

        // Рассчитываем хэш для сравнения
        $calculatedVerifier = calculateSRP6Verifier($username, $password, $result['salt']);

        // Сравниваем верификаторы
        return $calculatedVerifier === $result['verifier'];
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
}
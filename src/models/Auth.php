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
     * Авторизует пользователя по email
     */
    public function authorizeUser($email, $password)
    {
        // Проверяем, существует ли пользователь с данным email
        $stmt = $this->pdo->prepare("SELECT salt, verifier FROM account WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$result) {
            return false;
        }

        // Рассчитываем хэш для сравнения
        $calculatedVerifier = calculateSRP6Verifier($email, $password, $result['salt']);

        // Сравниваем верификаторы
        return $calculatedVerifier === $result['verifier'];
    }
}
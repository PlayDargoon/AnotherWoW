<?php
// src/models/Site.php

class Site
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Сохраняет временный токен для восстановления пароля
     */
    public function saveResetToken($userId, $token)
    {
        // Удаляем старые токены для этого пользователя
        $this->pdo->exec("DELETE FROM password_reset_tokens WHERE user_id = '$userId'");

        // Сохраняем новый токен
        $stmt = $this->pdo->prepare("INSERT INTO password_reset_tokens (user_id, token, expires_at) VALUES (:user_id, :token, DATE_ADD(NOW(), INTERVAL 1 HOUR))");
        $stmt->execute(['user_id' => $userId, 'token' => $token]);
    }

    /**
     * Проверяет токен восстановления пароля и возвращает пользователя, если токен верный
     */
    public function validateResetToken($token)
    {
        $stmt = $this->pdo->prepare("SELECT u.*, t.expires_at FROM acore_auth.account u INNER JOIN password_reset_tokens t ON u.id = t.user_id WHERE t.token = :token AND t.expires_at > NOW()");
        $stmt->execute(['token' => $token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Удаляет токен восстановления пароля
     */
    public function clearResetToken($userId)
    {
        $this->pdo->exec("DELETE FROM password_reset_tokens WHERE user_id = '$userId'");
    }
}
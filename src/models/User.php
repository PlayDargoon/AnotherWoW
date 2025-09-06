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
     * Получает название сервера по его ID
     */
    public function getRealmNameById($realmId)
    {
        $stmt = $this->pdo->prepare("SELECT name FROM realmlist WHERE id = :realmId");
        $stmt->execute(['realmId' => $realmId]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['name'] ?? '';
    }

    
    /**
     * Получает уровень GM для указанного персонажа
     */
    public function getGmLevelForCharacter($characterName)
    {
        $stmt = $this->pdo->prepare("SELECT gmlevel FROM account_access WHERE comment = :characterName AND gmlevel > 1");
        $stmt->execute(['characterName' => $characterName]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['gmlevel'] ?? 0;
    }
    
 /**
     * Получает название первого сервера из таблицы realmlist
     */
    public function getDefaultRealmName()
    {
        $stmt = $this->pdo->prepare("SELECT name FROM realmlist LIMIT 1");
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['name'] ?? ''; // Возвращаем название сервера или пустую строку, если сервер не найден
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

    /**
     * Поиск пользователя по email
     */
    public function findByEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM account WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Сохранение временного токена для сброса пароля
     */
    public function saveResetToken($userId, $token)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO password_reset_tokens (user_id, token, expires_at)
            VALUES (:userId, :token, :expiresAt)
        ");
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour')); // Токен действителен в течение 1 часа
        $stmt->execute([
            'userId' => $userId,
            'token' => $token,
            'expiresAt' => $expiresAt
        ]);
    }
     /**
     * Поиск пользователя по email
     */
   public function existsEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM account WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetchColumn() > 0;
    }


}
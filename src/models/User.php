<?php
require_once __DIR__ . '/../helpers/srp_helpers.php';
// src/models/User.php
class User
{
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Получить username по user_id
     */
    public function getUsernameById($userId)
    {
        $stmt = $this->pdo->prepare("SELECT username FROM account WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        return $stmt->fetchColumn();
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
     * Меняет пароль пользователя
     */
    public function changePassword($userId, $newPassword, $user)
    {
        // Генерация Salt и Verifier
        $salt = generateSalt();
        $verifier = calculateSRP6Verifier($user['username'], $newPassword, $salt);

        // Обновляем пароль пользователя
        $stmt = $this->pdo->prepare("UPDATE account SET salt = :salt, verifier = :verifier WHERE id = :user_id");
        $stmt->execute(['salt' => $salt, 'verifier' => $verifier, 'user_id' => $userId]);
    }

    /**
     * Проверяет уровень доступа пользователя (GM level)
     *
     * @param integer $userId ID пользователя
     * @return integer Уровень доступа (gmlevel) или 0, если не найден
     */
    public function getUserAccessLevel($userId)
    {
        $stmt = $this->pdo->prepare("SELECT gmlevel FROM account_access WHERE id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return isset($result['gmlevel']) ? intval($result['gmlevel']) : 0;
    }


}
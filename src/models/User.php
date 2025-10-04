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

    
    private $gmLevelCache = [];

    /**
     * Получает уровень GM для указанного персонажа с кешированием
     */
    public function getGmLevelForCharacter($characterName)
    {
        // Проверяем кеш
        if (isset($this->gmLevelCache[$characterName])) {
            return $this->gmLevelCache[$characterName];
        }
        
        try {
            $stmt = $this->pdo->prepare("SELECT gmlevel FROM account_access WHERE comment = :characterName AND gmlevel > 1 LIMIT 1");
            $stmt->bindParam(':characterName', $characterName, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            $gmLevel = $result ? (int)$result['gmlevel'] : 0;
            
            // Кешируем результат
            $this->gmLevelCache[$characterName] = $gmLevel;
            
            return $gmLevel;
        } catch (PDOException $e) {
            error_log("Ошибка получения GM уровня для персонажа {$characterName}: " . $e->getMessage());
            $this->gmLevelCache[$characterName] = 0; // Кешируем ошибку как 0
            return 0;
        }
    }

    /**
     * Получает GM уровни для нескольких персонажей одним запросом
     */
    public function getGmLevelsForCharacters($characterNames)
    {
        if (empty($characterNames)) {
            return [];
        }

        $result = [];
        $uncachedNames = [];

        // Сначала проверяем кеш
        foreach ($characterNames as $name) {
            if (isset($this->gmLevelCache[$name])) {
                $result[$name] = $this->gmLevelCache[$name];
            } else {
                $uncachedNames[] = $name;
            }
        }

        // Если есть некешированные имена, делаем запрос
        if (!empty($uncachedNames)) {
            try {
                $placeholders = str_repeat('?,', count($uncachedNames) - 1) . '?';
                $stmt = $this->pdo->prepare("SELECT comment, gmlevel FROM account_access WHERE comment IN ({$placeholders}) AND gmlevel > 1");
                $stmt->execute($uncachedNames);
                $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                // Обрабатываем результаты
                foreach ($uncachedNames as $name) {
                    $gmLevel = 0;
                    foreach ($rows as $row) {
                        if ($row['comment'] === $name) {
                            $gmLevel = (int)$row['gmlevel'];
                            break;
                        }
                    }
                    $result[$name] = $gmLevel;
                    $this->gmLevelCache[$name] = $gmLevel; // Кешируем
                }
            } catch (PDOException $e) {
                error_log("Ошибка получения GM уровней: " . $e->getMessage());
                // В случае ошибки устанавливаем 0 для всех некешированных
                foreach ($uncachedNames as $name) {
                    $result[$name] = 0;
                    $this->gmLevelCache[$name] = 0;
                }
            }
        }

        return $result;
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
     * Получает ID аккаунта по логину или имени персонажа
     * Сначала ищет по логину аккаунта, затем по имени персонажа
     */
    public function getAccountIdByUsernameOrCharacter($username)
    {
        // Сначала ищем по логину аккаунта (auth база)
        $stmt = $this->pdo->prepare("SELECT id FROM account WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $accountId = $stmt->fetchColumn();
        
        if ($accountId) {
            return [
                'account_id' => $accountId,
                'method' => 'account_login',
                'found_username' => $username
            ];
        }
        
        // Если не найден по логину, ищем по имени персонажа (characters база)
        try {
            $charactersDb = \DatabaseConnection::getCharactersConnection();
            $stmt = $charactersDb->prepare("SELECT account FROM characters WHERE name = :character_name");
            $stmt->execute(['character_name' => $username]);
            $accountId = $stmt->fetchColumn();
            
            if ($accountId) {
                // Получаем логин аккаунта для логирования
                $stmt = $this->pdo->prepare("SELECT username FROM account WHERE id = :account_id");
                $stmt->execute(['account_id' => $accountId]);
                $accountLogin = $stmt->fetchColumn();
                
                return [
                    'account_id' => $accountId,
                    'method' => 'character_name',
                    'found_username' => $accountLogin ?: 'unknown',
                    'character_name' => $username
                ];
            }
        } catch (Exception $e) {
            error_log("Ошибка поиска по имени персонажа: " . $e->getMessage());
        }
        
        // Не найден ни по логину, ни по персонажу
        return null;
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
        return $stmt->execute(['salt' => $salt, 'verifier' => $verifier, 'user_id' => $userId]);
    }

    /**
     * Проверяет, забанен ли аккаунт
     */
    public function isBanned($accountId)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT banreason, unbandate FROM account_banned WHERE id = :accountId AND active = 1 LIMIT 1");
            $stmt->bindParam(':accountId', $accountId, PDO::PARAM_INT);
            $stmt->execute();
            
            $ban = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($ban) {
                // Проверяем, не истёк ли бан
                if ($ban['unbandate'] > 0 && $ban['unbandate'] <= time()) {
                    return false; // Бан истёк
                }
                return $ban; // Возвращаем информацию о бане
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Ошибка проверки бана для аккаунта {$accountId}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Проверяет, замучен ли аккаунт
     */
    public function isMuted($accountId)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT mutereason, mutedate, mutetime FROM account_muted WHERE guid = :accountId LIMIT 1");
            $stmt->bindParam(':accountId', $accountId, PDO::PARAM_INT);
            $stmt->execute();
            
            $mute = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($mute) {
                $muteEndTime = $mute['mutedate'] + $mute['mutetime'];
                
                // Проверяем, не истёк ли мут
                if ($muteEndTime <= time()) {
                    return false; // Мут истёк
                }
                
                $mute['mute_end_time'] = $muteEndTime;
                return $mute; // Возвращаем информацию о муте
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Ошибка проверки мута для аккаунта {$accountId}: " . $e->getMessage());
            return false;
        }
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
<?php
// src/models/Character.php

class Character
{
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Получает персонажа по GUID
     */
    public function getCharacterByGuid($guid)
    {
        // Проверяем, что GUID является числом
        if (!is_numeric($guid)) {
            throw new InvalidArgumentException("Invalid GUID provided.");
        }

        $stmt = $this->pdo->prepare("SELECT * FROM characters WHERE guid = :guid");
        $stmt->execute(['guid' => $guid]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Получает количество персонажей
     */
    public function getPlayerCount()
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS count FROM characters");
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['count'];
    }


    /**
     * Получает статистику персонажа по GUID
     */
    public function getStatsByGuid($guid)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM character_stats WHERE guid = :guid");
        $stmt->execute(['guid' => $guid]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }


     
    
    /**
     * Получает персонажей пользователя по ID
     */
    public function getCharactersByUserId($userId)
    {
        // Проверяем, что userId является числом
        if (!is_numeric($userId)) {
            throw new InvalidArgumentException("Invalid user ID provided.");
        }

        $stmt = $this->pdo->prepare("SELECT guid, name, race, class, gender, level FROM characters WHERE account = :userId");
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Методы getRaceName() и getClassName() больше не нужны, так как мы будем отображать изображения.

    /**
     * Получает количество игроков онлайн
     */
    public function getOnlinePlayerCount()
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS count FROM characters WHERE online = 1");
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['count'];
    }

    /**
     * Получает количество всех игроков и количество игроков онлайн
     */
    public function getPlayerCounts()
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS total_players, SUM(online) AS online_players FROM characters");
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    
}
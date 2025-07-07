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
     * Получает количество персонажей
     */
    public function getPlayerCount()
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS count FROM characters");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
}
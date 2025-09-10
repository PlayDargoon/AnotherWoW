<?php
// src/models/Uptime.php

class Uptime
{
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Метод получения последнего значения starttime из таблицы uptime
     */
    public function getLastStartTime()
    {
        $query = "SELECT starttime FROM uptime ORDER BY starttime DESC LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['starttime'];
    }

    /**
     * Метод получения названия игрового мира и его адреса
     */
    public function getRealmInfo()
    {
        $query = "SELECT name, address FROM realmlist LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
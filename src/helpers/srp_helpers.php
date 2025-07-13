<?php
// src/helpers/srp_helpers.php

// Функция для генерации случайной соли
function generateSalt()
{
    return random_bytes(32);
}

// Функция для вычисления верификатора SRP-6
function calculateSRP6Verifier($username, $password, $salt)
{
    // Параметры SRP-6
    $g = gmp_init(7);
    $N = gmp_init('894B645E89E1535BBDAD5B8B290650530801B18EBFBF5E8FAB3C82872A3E9BB7', 16);

    // Первый этап хэширования
    $h1 = hash('sha1', strtoupper($username) . ':' . strtoupper($password), true);

    // Второй этап хэширования
    $h2 = hash('sha1', $salt . $h1, true);

    // Преобразуем хэш в большой целый тип
    $h2Int = gmp_import($h2, 1, GMP_LSW_FIRST);

    // Вычисляем верификатор
    $verifier = gmp_powm($g, $h2Int, $N);

    // Приводим к 32 байтам
    $verifierBin = gmp_export($verifier, 1, GMP_LSW_FIRST);
    $verifierPadded = str_pad($verifierBin, 32, chr(0), STR_PAD_RIGHT);

    return $verifierPadded;
}

// Функция VerifySRP6Login
function VerifySRP6Login($username, $password, $salt, $verifier)
{
    // Пересчитываем верификатор
    $checkVerifier = calculateSRP6Verifier($username, $password, $salt);

    // Сравниваем его с сохранённым верификатором
    return ($verifier === $checkVerifier);
}
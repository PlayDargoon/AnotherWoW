<?php
// src/helpers/shahash_helpers.php

/**
 * Проверяет, совпадает ли введённый пароль с хэшированным значением
 *
 * @param string $username Логин пользователя
 * @param string $password Введённый пароль
 * @param string $storedHash Хэшированное значение пароля, сохранённое в базе данных
 * @return bool True, если пароль совпадает, иначе False
 */
function VerifySHA1Login($username, $password, $storedHash)
{
    // Хэшируем введённый пароль
    $hashedPassword = hash('sha1', $password);

    // Сравниваем хэш с сохранённым значением
    return $hashedPassword === $storedHash;
}
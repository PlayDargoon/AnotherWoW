<?php
// src/helpers/getFactionImage.php

/**
 * Определяет изображение фракции по раце персонажа
 *
 * @param integer $race Идентификатор расы персонажа
 * @return string Путь к изображению фракции
 */
function getFactionImage($race)
{
    switch ($race) {
        case 1: // Человек
        case 3: // Дворф
        case 4: // Ночной Эльф
        case 7: // Гном
        case 11: // Дреней
            return '/images/small/alliance.png';
        case 2: // Орк
        case 5: // Нежить
        case 6: // Таурен
        case 8: // Тролль
        case 10: // Эльф Крови
            return '/images/small/orda.png';
        default:
            return ''; // Неопределённая фракция
    }
}
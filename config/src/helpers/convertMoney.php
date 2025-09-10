<?php
// src/helpers/convertMoney.php

/**
 * Конвертирует общую сумму монет (в меди) в золотые, серебряные и медные монеты.
 *
 * @param int $totalCopper общая сумма монет в меди
 * @return array массив с количеством золотых, серебряных и медных монет
 */
function convertMoney($totalCopper)
{
    // Константы для конвертации
    define('COPPER_PER_GOLD', 10000); // 100 серебряных = 1 золотой
    define('COPPER_PER_SILVER', 100); // 100 меди = 1 серебряная

    // Высчитываем количество золотых монет
    $gold = floor($totalCopper / COPPER_PER_GOLD);
    $remainingCopperAfterGold = $totalCopper % COPPER_PER_GOLD;

    // Высчитываем количество серебряных монет
    $silver = floor($remainingCopperAfterGold / COPPER_PER_SILVER);
    $copper = $remainingCopperAfterGold % COPPER_PER_SILVER;

    return [
        'gold' => $gold,
        'silver' => $silver,
        'copper' => $copper
    ];
}
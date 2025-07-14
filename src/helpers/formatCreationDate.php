<?php
// src/helpers/formatCreationDate.php

/**
 * Форматирует дату регистрации персонажа в русский формат с русским месяцем
 *
 * @param string $date Дата в формате Y-m-d H:i:s
 * @return string Отформатированная дата
 */
function formatCreationDate($date)
{
    $months = [
        'Jan' => 'янв.', 'Feb' => 'февр.', 'Mar' => 'мар.', 'Apr' => 'апр.',
        'May' => 'май', 'Jun' => 'июнь', 'Jul' => 'июль', 'Aug' => 'авг.',
        'Sep' => 'сен.', 'Oct' => 'окт.', 'Nov' => 'нояб.', 'Dec' => 'дек.'
    ];

    $timestamp = strtotime($date);
    $monthEng = date('M', $timestamp); // Английский месяц
    $monthRus = $months[$monthEng]; // Русская версия месяца

    return date('j', $timestamp) . ' ' . $monthRus . ' ' . date('Y', $timestamp);
}
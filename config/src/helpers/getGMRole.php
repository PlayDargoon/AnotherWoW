<?php
// src/helpers/getGMRole.php

/**
 * Возвращает текстовую строку с правами персонажа в зависимости от gmlevel
 *
 * @param integer $gmlevel Уровень GM
 * @return string Текстовая строка с правом персонажа
 */
function getGMRole($gmlevel)
{
    switch ($gmlevel) {
        case 2:
            return 'Модератор [m]';
        case 3:
            return 'Администратор [a]';
        case 4:
            return 'Гейм Мастер [g]';
        default:
            return '';
    }
}
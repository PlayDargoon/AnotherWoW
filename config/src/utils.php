<?php
// src/utils.php

/**
 * Функция рендеринга шаблонов с использованием общего макета
 *
 * @param string $templatePath - Путь к шаблону относительно папки templates/.
 * @param array $data - Массив данных, передаваемых в шаблон.
 */
function renderTemplate(string $templatePath, array $data = []): void
{
    try {
        // Формируем абсолютный путь к шаблону
        $fullTemplatePath = __DIR__ . '/../templates/' . $templatePath;

        // Преобразуем массив данных**: в переменные для использования в шаблонах
        extract($data);

        // Начинаем буферизацию вывода
        ob_start();

        // Подключаем сам шаблон
        include $fullTemplatePath;

        // Получаем содержимое буфера
        $content = ob_get_clean();

        // Передаем контент в общий шаблон layout.html.php
        include __DIR__ . '/../templates/layout.html.php';
    } catch (\Throwable $th) {
        // В случае ошибки выводим сообщение об ошибке
        die("Ошибка при рендеринге шаблона: {$th->getMessage()} в {$th->getFile()} на линии {$th->getLine()}");
    }
}
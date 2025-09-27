<?php
// config/cache.php
/**
 * Конфигурация системы кеширования
 */

return [
    // Общие настройки кеша
    'default_ttl' => 300, // 5 минут по умолчанию
    'cache_dir' => __DIR__ . '/../cache',
    
    // TTL для различных типов данных (в секундах)
    'ttl' => [
        // Пользовательские данные
        'user_info' => 600,           // 10 минут
        'user_characters' => 900,     // 15 минут
        'user_balance' => 300,        // 5 минут
        'user_history' => 300,        // 5 минут
        
        // Статистика и рейтинги
        'top_users' => 1800,          // 30 минут  
        'top_voters' => 900,          // 15 минут
        'server_stats' => 1800,       // 30 минут
        'global_stats' => 3600,       // 1 час
        
        // Системная информация
        'server_status' => 60,        // 1 минута
        'server_info' => 300,         // 5 минут
        'uptime_info' => 300,         // 5 минут
        
        // Контент
        'news_list' => 600,           // 10 минут
        'news_item' => 1800,          // 30 минут
        'main_page' => 300,           // 5 минут
        
        // Голосования
        'vote_content' => 120,        // 2 минуты
        'vote_stats' => 600,          // 10 минут
        
        // Запросы к базе данных
        'db_queries' => 300,          // 5 минут для обычных запросов
        'db_heavy_queries' => 1800,   // 30 минут для тяжелых запросов
        'db_stats_queries' => 3600,   // 1 час для статистических запросов
    ],
    
    // Настройки очистки кеша
    'cleanup' => [
        'auto_cleanup' => true,       // Автоматическая очистка устаревшего кеша
        'max_cache_size_mb' => 500,   // Максимальный размер кеша в MB
        'cleanup_probability' => 10,  // Вероятность запуска очистки в % (на каждый запрос)
        'max_file_age_hours' => 24,   // Максимальный возраст файлов кеша в часах
    ],
    
    // Режимы кеширования для разных окружений
    'environments' => [
        'development' => [
            'enabled' => false,       // Отключить кеш в разработке
            'debug' => true,          // Включить отладочную информацию
        ],
        'production' => [
            'enabled' => true,        // Включить кеш в продакшене
            'debug' => false,         // Отключить отладку
        ],
    ],
    
    // Исключения - данные, которые не должны кешироваться
    'no_cache' => [
        'csrf_tokens',
        'session_data',
        'notifications',
        'real_time_data',
    ],
    
    // Настройки для конкретных таблиц БД
    'database_cache' => [
        'account_coins' => [
            'ttl' => 300,            // 5 минут для финансовых данных
            'invalidate_on' => ['INSERT', 'UPDATE', 'DELETE'],
        ],
        'characters' => [
            'ttl' => 900,            // 15 минут для персонажей
            'invalidate_on' => ['UPDATE'],
        ],
        'vote_log' => [
            'ttl' => 120,            // 2 минуты для голосов
            'invalidate_on' => ['INSERT'],
        ],
        'news' => [
            'ttl' => 1800,           // 30 минут для новостей
            'invalidate_on' => ['INSERT', 'UPDATE', 'DELETE'],
        ],
    ],
];
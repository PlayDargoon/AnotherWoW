# Система кеширования и оптимизации базы данных

## 📋 Обзор

Реализована комплексная система кеширования для оптимизации производительности проекта AnotherWoW:

### 🔧 Компоненты системы

1. **CacheService** - универсальный сервис кеширования
2. **OptimizedDatabaseConnection** - оптимизированные подключения к БД
3. **CachedModel** - базовый класс для кешированных моделей
4. **CachedAccountCoins** - оптимизированная модель для работы с монетами
5. **Конфигурация** - настройка TTL и параметров кеширования

### 📁 Структура файлов

```
src/services/
├── CacheService.php              # Основной сервис кеширования
├── OptimizedDatabaseConnection.php # Оптимизированные DB подключения
├── CachedModel.php               # Базовый класс для кешированных моделей

src/models/
├── CachedAccountCoins.php        # Кешированная модель монет

config/
├── cache.php                     # Конфигурация кеширования

Утилиты:
├── cache_monitor.php             # Мониторинг производительности
├── cache_cleanup.php             # Очистка устаревшего кеша  
├── test_cache_performance.php    # Тест производительности
```

## 🚀 Использование

### Базовое кеширование

```php
$cache = CacheService::getInstance();

// Сохранить в кеш
$cache->set('user_data_123', $userData, 600); // 10 минут TTL

// Получить из кеша
$userData = $cache->get('user_data_123', 600);

// Кеширование с функцией обратного вызова
$data = $cache->remember('expensive_calculation', function() {
    return performExpensiveCalculation();
}, 1800); // 30 минут
```

### Кешированные запросы к БД

```php
// Кешированный запрос (автоматически кешируется на 5 минут)
$results = OptimizedDatabaseConnection::cachedQuery(
    $connection,
    "SELECT * FROM users WHERE status = ?",
    ['active'],
    600  // TTL в секундах
);

// Запрос с инвалидацией кеша
OptimizedDatabaseConnection::executeWithCacheInvalidation(
    $connection,
    "INSERT INTO users (name, email) VALUES (?, ?)",
    ['John', 'john@example.com'],
    ['users'] // таблицы для инвалидации
);
```

### Использование кешированных моделей

```php
$coinsModel = new CachedAccountCoins($connection);

// Все методы автоматически используют кеширование
$balance = $coinsModel->getBalance($accountId);       // Кеш на 10 минут
$history = $coinsModel->getHistory($accountId, 20);   // Кеш на 5 минут
$topUsers = $coinsModel->getTopUsers(10);             // Кеш на 30 минут

// Добавление с автоматической инвалидацией кеша
$coinsModel->add($accountId, 5, 'Награда за квест');
```

### Наследование от CachedModel

```php
class CachedNews extends CachedModel
{
    public function __construct($db) 
    {
        parent::__construct($db, 'news');
        $this->setCacheTtl(1800); // 30 минут для новостей
    }
    
    public function getLatestNews($limit = 10): array
    {
        return $this->getAllCached('news', 
            ['published' => 1], 
            'created_at DESC', 
            $limit,
            $this->getTtlForType('news_list')
        );
    }
    
    public function getNewsById($id): ?array
    {
        return $this->findCached('news', $id);
    }
}
```

## ⚙️ Конфигурация

Настройки в `config/cache.php`:

```php
return [
    'default_ttl' => 300,  // 5 минут по умолчанию
    
    'ttl' => [
        'user_balance' => 300,     // 5 минут для баланса
        'top_users' => 1800,       // 30 минут для топов
        'server_stats' => 3600,    // 1 час для статистики
    ],
    
    'cleanup' => [
        'auto_cleanup' => true,        // Автоочистка
        'cleanup_probability' => 10,   // 10% вероятность очистки
        'max_file_age_hours' => 24,    // Удалять файлы старше 24 часов
    ],
];
```

## 📊 Мониторинг и диагностика

### Веб-интерфейс мониторинга

Откройте `scripts/maintenance/cache_monitor.php` в браузере для просмотра:
- Статистики кеша (размер, количество файлов)
- Статистики запросов к БД  
- Тестов производительности
- Управления кешем

### Команды мониторинга

```bash
# Очистка устаревшего кеша
php scripts/maintenance/cache_cleanup.php

# Тест производительности
php scripts/tests/test_cache_performance.php
```

### Программный мониторинг

```php
// Статистика кеша
$stats = CacheService::getInstance()->getStats();
echo "Файлов кеша: {$stats['total_files']}\n";
echo "Размер: {$stats['total_size_mb']} MB\n";

// Статистика запросов
$queryStats = OptimizedDatabaseConnection::getQueryStats();
echo "Процент попаданий: {$queryStats['hit_ratio_percent']}%\n";
```

## 🔄 Автоматизация

### Cron задачи

Добавьте в crontab для автоматической очистки:

```bash
# Очистка кеша каждые 30 минут
*/30 * * * * /usr/bin/php /path/to/project/scripts/maintenance/cache_cleanup.php

# Мониторинг производительности каждый час  
0 * * * * /usr/bin/php /path/to/project/scripts/tests/test_cache_performance.php >> /var/log/cache_performance.log
```

### Интеграция с существующим кодом

Обновленные файлы автоматически используют кеширование:
- `router.php` - использует `CachedAccountCoins`
- `CabinetController` - кешированные запросы баланса
- `VoteService` - кеширование mmotop файлов
- `bootstrap.php` - подключение всех компонентов

## 📈 Ожидаемая производительность

- **Простые запросы**: ускорение в 10-50x при чтении из кеша
- **Сложные запросы**: ускорение в 5-20x  
- **Агрегированная статистика**: ускорение в 20-100x
- **Снижение нагрузки на БД**: до 80-90% при активном кешировании

## 🔧 Troubleshooting

### Проблема: Кеш не работает
```php
// Проверьте, включено ли кеширование
$cache = CacheService::getInstance();
if (!$cache->isEnabled()) {
    echo "Кеширование отключено в настройках";
}
```

### Проблема: Устаревшие данные
```php
// Принудительная инвалидация
$cache->delete('specific_cache_key');

// Или полная очистка
$cache->clear();
```

### Проблема: Большой размер кеша
```php
// Уменьшите TTL в config/cache.php или запустите очистку
php cache_cleanup.php
```

## 🎯 Лучшие практики

1. **TTL**: Используйте короткие TTL (5-10 мин) для часто изменяющихся данных
2. **Инвалидация**: Всегда инвалидируйте кеш при изменении данных
3. **Мониторинг**: Регулярно проверяйте hit ratio (должен быть > 70%)
4. **Очистка**: Настройте автоматическую очистку устаревшего кеша
5. **Тестирование**: Используйте `test_cache_performance.php` для оптимизации

## 📋 Чек-лист внедрения

- [x] Установка CacheService и OptimizedDatabaseConnection
- [x] Обновление bootstrap.php для подключения компонентов  
- [x] Замена AccountCoins на CachedAccountCoins в контроллерах
- [x] Настройка конфигурации кеширования
- [x] Настройка cron задач для очистки
- [x] Тестирование производительности
- [ ] Мониторинг в production среде
- [ ] Оптимизация TTL на основе реальных данных

Система готова к использованию и должна значительно улучшить производительность проекта!
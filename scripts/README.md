# 📁 Scripts Directory

Исполняемые скрипты для различных задач проекта.

## 🔄 Migrations (`migrations/`)

Скрипты для миграции и создания структуры базы данных:

- `migrate.php` - Основной скрипт миграций
- `create_notifications_table.php` - Создание таблицы уведомлений  
- `create_vote_tables.php` - Создание таблиц голосования
- `migrate_account_coins.php` - Миграция монет аккаунтов
- `migrate_vote_currency.php` - Миграция валюты голосования
- `migrate_vote_log.php` - Миграция логов голосования

### Использование:
```bash
php scripts/migrations/migrate.php
```

## 🧪 Tests (`tests/`)

Тестовые скрипты для проверки функциональности:

- `test_cache_performance.php` - Тест производительности кеширования
- `test_email_design.php` - Тест дизайна email
- `test_notify_admin.php` - Тест уведомлений администратора
- `test_restore_password.php` - Тест восстановления пароля
- `test_smtp_direct.php` - Тест SMTP подключения
- `test_vote_service.php` - Тест сервиса голосования
- `test_vote_top_final.php` - Тест топа голосующих

### Использование:
```bash
php scripts/tests/test_cache_performance.php
php scripts/tests/test_vote_service.php
```

## 🔄 Sync (`sync/`)

Скрипты синхронизации данных с внешними системами:

- `sync_mmotop_votes.php` - Синхронизация голосов с MMOTOP
- `sync_votes.php` - Общая синхронизация голосов

### Использование:
```bash
php scripts/sync/sync_mmotop_votes.php
```

## 🔧 Maintenance (`maintenance/`)

Скрипты обслуживания и поддержки системы:

- `cache_cleanup.php` - Очистка устаревшего кеша
- `cache_monitor.php` - Мониторинг производительности кеша
- `database_optimization.sql` - SQL скрипты оптимизации БД

### Использование:
```bash
php scripts/maintenance/cache_cleanup.php
```

### Автоматизация (crontab):
```bash
# Очистка кеша каждые 30 минут
*/30 * * * * php /path/to/scripts/maintenance/cache_cleanup.php

# Мониторинг каждый час
0 * * * * php /path/to/scripts/maintenance/cache_monitor.php
```

## 📝 Примечания

- Все скрипты должны запускаться из корня проекта
- Убедитесь, что `bootstrap.php` корректно подключен
- Для production используйте абсолютные пути в cron задачах
- Логи выполнения сохраняются в `/cache/` папке
# Структура системы голосования

## Контроллеры
Вся бизнес-логика вынесена в контроллеры:

### VoteSyncController (`src/controllers/VoteSyncController.php`)
Управляет синхронизацией голосов с mmotop.ru
- `syncFromCli()` - для запуска из командной строки
- `autoSync()` - для автоматической синхронизации из шаблонов
- `getStats()` - получение статистики голосований
- `getRecentVotes()` - последние голоса

### MigrationController (`src/controllers/MigrationController.php`)
Управляет миграциями базы данных
- `runAll()` - запуск всех миграций
- `runMigration($fileName)` - запуск конкретной миграции
- `checkDatabase()` - проверка состояния БД
- `createMigration($name)` - создание новой миграции

### VoteController (`src/controllers/VoteController.php`)
Обработка ручного голосования через веб-интерфейс
- `index()` - форма и обработка голосования

## Миграции
Все миграции вынесены в папку `database/migrations/`:

### Существующие миграции:
- `CreateVoteTables.php` - создание таблиц системы голосования
- `CheckDatabase.php` - проверка структуры БД

## Скрипты

### Для миграций:
```bash
# Запустить все миграции
php migrate.php run-all

# Запустить конкретную миграцию
php migrate.php run CreateVoteTables

# Проверить состояние БД
php migrate.php check

# Создать новую миграцию
php migrate.php create "Название миграции"
```

### Для синхронизации:
```bash
# Синхронизация голосов
php sync_votes.php
php sync_mmotop_votes.php

# Тестирование системы
php test_vote_service.php

# Создание таблиц (старый способ)
php create_vote_tables.php
```

## Структура файлов

```
src/
├── controllers/
│   ├── VoteSyncController.php     # Синхронизация голосов
│   ├── MigrationController.php    # Управление миграциями
│   └── VoteController.php         # Веб-интерфейс голосования
├── services/
│   └── VoteService.php            # Бизнес-логика голосования
└── models/
    ├── VoteLog.php                # Модель логов голосования
    ├── VoteReward.php             # Модель наград
    └── AccountCoins.php           # Модель монет

database/
└── migrations/
    ├── CreateVoteTables.php       # Создание таблиц
    └── CheckDatabase.php          # Проверка БД

templates/
└── layout.html.php               # Автосинхронизация через VoteSyncController
```

## Принципы

1. **Все новые данные только в `acore_site`** - базы AzerothCore не изменяются
2. **Логика в контроллерах** - скрипты вызывают методы контроллеров
3. **Миграции отдельно** - в `database/migrations/` с универсальным управлением
4. **Автосинхронизация** - через layout.html.php каждые 10 минут
5. **Защита от дублирования** - проверка существующих голосов

## Автоматические процессы

- **Синхронизация голосов**: каждые 10 минут через `layout.html.php`
- **Начисление монет**: автоматически при синхронизации
- **Логирование**: все операции записываются в `vote_log`
- **Cooldown**: 16 часов между ручными голосованиями
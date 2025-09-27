# 🛠️ Tools Directory

Утилиты для диагностики, отладки и проверки системы.

## 🔍 Диагностические инструменты

### `check-database.php`
Проверка подключения к базам данных и их статуса.

```bash
php tools/check-database.php
```

### `balance_check.php`
Проверка баланса конкретного аккаунта с детализацией.

```bash
php tools/balance_check.php
```

### `check_balance.php`
Альтернативная утилита проверки баланса.

### `check_vote_data.php`
Проверка данных голосования и их целостности.

### `check_vote_db.php`
Проверка базы данных голосований.

## 🐛 Отладка

### `debug_vote_accounts.php`
Отладка проблем с аккаунтами в системе голосования.

```bash
php tools/debug_vote_accounts.php
```

## 📧 Email инструменты

### `preview_email.php`
Предварительный просмотр email шаблонов.

```bash
php tools/preview_email.php
```

## 💡 Советы по использованию

### Быстрая диагностика системы:
```bash
# Проверить все соединения с БД
php tools/check-database.php

# Проверить баланс тестового аккаунта
php tools/balance_check.php

# Проверить систему голосования
php tools/check_vote_data.php
```

### Отладка проблем:
```bash
# Если проблемы с голосованием
php tools/debug_vote_accounts.php

# Если проблемы с email
php tools/preview_email.php
```

## 📋 Статус проверок

Инструменты возвращают следующие коды завершения:
- `0` - Успешно, проблем не найдено
- `1` - Найдены предупреждения  
- `2` - Критические ошибки

## 🔄 Интеграция с мониторингом

Эти инструменты можно использовать в системах мониторинга:

```bash
# Healthcheck для Docker/Kubernetes
php tools/check-database.php && echo "DB OK" || echo "DB FAILED"

# Проверка в Nagios/Zabbix
php tools/balance_check.php > /tmp/balance_status.txt
```
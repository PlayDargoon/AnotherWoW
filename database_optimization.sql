-- Рекомендации по индексам для оптимизации AdminOnlineController
-- Выполните эти SQL-команды в базах данных для улучшения производительности

-- ===== База данных auth =====
-- Индекс для быстрой выборки аккаунтов с email и фильтрацией по IP
CREATE INDEX idx_account_email_ip ON account (email, last_ip);

-- Если нужна дополнительная фильтрация по времени входа:
-- CREATE INDEX idx_account_login ON account (last_login);

-- ===== База данных characters =====
-- Составной индекс для быстрой выборки онлайн-персонажей по аккаунтам
CREATE INDEX idx_characters_online_account ON characters (online, account);

-- Дополнительный индекс для оптимизации сортировки/фильтрации:
-- CREATE INDEX idx_characters_level ON characters (level);
-- CREATE INDEX idx_characters_totaltime ON characters (totaltime);

-- ===== Проверка эффективности индексов =====
-- Используйте EXPLAIN для проверки планов выполнения запросов:

-- EXPLAIN SELECT id, username, email, last_ip 
-- FROM account 
-- WHERE email IS NOT NULL AND email != '' AND last_ip != '127.0.0.1';

-- EXPLAIN SELECT guid, name, race, class, gender, level, account, totaltime 
-- FROM characters 
-- WHERE online = 1 AND account IN (1,2,3,4,5);

-- ===== Мониторинг производительности =====
-- После создания индексов проверьте:
-- 1. Время выполнения запросов должно снизиться
-- 2. План выполнения должен показывать использование индексов
-- 3. Убедитесь, что индексы не замедляют INSERT/UPDATE операции
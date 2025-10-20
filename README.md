# AnotherWoW

Лёгкий MVC-проект на PHP для сайта сервера WoW 3.3.5: роутинг, личный кабинет, голосование (MMOTOP), уведомления и админ-панель.

- Точка входа: `public/index.php`
- Роутер: `router.php`
- Инициализация: `bootstrap.php`
- Архитектура/структура: см. `PROJECT_STRUCTURE.md`
- Документация: каталог `docs/`
- Утилиты и скрипты: `tools/` и `scripts/`

Быстрый старт:
1) Настройте `config/database.php`
2) Поднимите веб-сервер с корнем `public/`

Полезно:
- Голоса/топ из файла: см. `src/models/VoteTop.php` и `templates/pages/vote-top.html.php`
- Утилита учёта аккаунтов: `tools/count_real_accounts.php`

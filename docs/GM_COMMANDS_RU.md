# GM Команды AzerothCore (Русский перевод)

> Оригинальная документация: https://www.azerothcore.org/wiki/gm-commands

## Основная информация

GM команды могут быть введены двумя способами:
1. Напрямую в консоли сервера (точка необязательна, но можно использовать)
2. В игровом чате WoW клиента (все команды должны начинаться с точки, например: `.gm on`)

**Примечание:** Некоторые команды работают только при выборе игрока или существа. Эти команды нельзя использовать в консоли сервера.

Для выполнения некоторых команд требуется высокий уровень безопасности. Используйте команду `account set gmlevel` через консоль.

---

## Команды аккаунтов

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **account** | 0 | `.account` | Показать уровень доступа вашего аккаунта |
| **account addon** | 1 | `.account addon #addon` | Установить разрешенный уровень дополнения. Значения: 0 - обычный, 1 - tbc, 2 - wotlk |
| **account 2fa setup** | 0 | `.account 2fa setup` | Настроить двухфакторную аутентификацию. Используйте `.account 2fa setup 1` для получения токена |
| **account 2fa remove** | 0 | `.account 2fa remove` | Удалить двухфакторную аутентификацию |
| **account create** | 4 | `.account create $account $password [$email]` | Создать аккаунт и установить пароль, email необязателен |
| **account delete** | 4 | `.account delete $account` | Удалить аккаунт со всеми персонажами |
| **account lock** | 0 | `.account lock [on/off]` | Разрешить вход только с текущего IP или снять это ограничение |
| **account lock country** | 0 | `.account lock country [on/off]` | Разрешить вход только из текущей страны или снять ограничение |
| **account onlinelist** | 4 | `.account onlinelist` | Показать список онлайн аккаунтов |
| **account password** | 0 | `.account password $old_password $new_password $new_password` | Изменить пароль вашего аккаунта |
| **account set addon** | 2 | `.account set addon [$account] #addon` | Установить разрешенный уровень дополнения для пользователя. Значения: 0-2 |
| **account set gmlevel** | 4 | `.account set gmlevel [$account] #level [#realmid]` | Установить уровень безопасности. #level от 0 до 3, #realmid -1 для всех |
| **account set password** | 4 | `.account set password $account $password $password` | Установить пароль для аккаунта |

---

## Команды достижений

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **achievement add** | 2 | `.achievement add $achievement` | Добавить достижение выбранному игроку |
| **achievement checkall** | 3 | `.achievement checkall` | Проверить все критерии достижений выбранного игрока |

---

## Команды предметов

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **additem** | 2 | `.additem [playerName/playerGUID] #itemid #count` | Добавить предмет вам или указанному персонажу. Если count отрицательный, предмет удаляется |
| **additem set** | 2 | `.additem set #itemsetid` | Добавить предметы из набора в инвентарь |

---

## Объявления и сообщения

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **announce** | 2 | `.announce $MessageToBroadcast` | Отправить глобальное сообщение всем онлайн игрокам в чат |
| **gmannounce** | 2 | `.gmannounce $announcement` | Отправить объявление онлайн GM |
| **gmnameannounce** | 2 | `.gmnameannounce $announcement` | Отправить объявление всем GM с отображением имени отправителя |
| **gmnotify** | 2 | `.gmnotify $notification` | Показать уведомление на экране всех онлайн GM |
| **nameannounce** | 2 | `.nameannounce $announcement` | Отправить объявление всем игрокам с отображением имени отправителя |
| **notify** | 2 | `.notify $MessageToBroadcast` | Отправить глобальное сообщение всем игрокам на экран |

---

## Телепортация

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **appear** | 1 | `.appear [$charactername]` | Телепортироваться к указанному персонажу (может быть оффлайн) |
| **summon** | 2 | `.summon [$charactername]` | Телепортировать указанного персонажа к вам (может быть оффлайн) |
| **groupsummon** | 2 | `.groupsummon [$charactername]` | Телепортировать персонажа и его группу к вам |
| **teleport** | 1 | `.teleport #location` | Телепортировать игрока в указанную локацию |
| **teleport add** | 3 | `.teleport add $name` | Добавить текущую позицию в список локаций телепорта |
| **teleport del** | 3 | `.teleport del $name` | Удалить локацию из списка телепортации |
| **teleport name** | 2 | `.teleport name [#playername] #location` | Телепортировать указанного персонажа в локацию |
| **teleport group** | 2 | `.teleport group #location` | Телепортировать выбранного игрока и его группу в локацию |
| **recall** | 2 | `.recall [$playername]` | Телепортировать игрока в место до последней телепортации |

---

## Команды GO (переход)

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **go creature** | 1 | `.go creature $guid` | Телепортироваться к существу по GUID |
| **go creature id** | 1 | `.go creature id $entry [#spawn]` | Телепортироваться к существу по ID шаблона |
| **go creature name** | 1 | `.go creature name $name` | Телепортироваться к существу по имени |
| **go gameobject** | 1 | `.go gameobject $guid` | Телепортироваться к игровому объекту по GUID |
| **go gameobject id** | 1 | `.go gameobject id $entry [#spawn]` | Телепортироваться к объекту по ID |
| **go graveyard** | 1 | `.go graveyard #graveyardId` | Телепортироваться на кладбище |
| **go grid** | 1 | `.go grid #gridX #gridY [#mapId]` | Телепортироваться в центр грида |
| **go taxinode** | 1 | `.go taxinode #taxinode` | Телепортироваться к точке такси |
| **go ticket** | 2 | `.go ticket #ticketid` | Телепортироваться к месту создания тикета |
| **go trigger** | 1 | `.go trigger #trigger_id` | Телепортироваться к триггеру зоны |
| **go xyz** | 1 | `.go xyz #x #y [#z [#mapid [#orientation]]]` | Телепортироваться к координатам |
| **go zonexy** | 1 | `.go zonexy #x #y [#zone]` | Телепортироваться к координатам зоны |

---

## Команды арены

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **arena captain** | 3 | `.arena captain #TeamID $name` | Назначить нового капитана команды |
| **arena create** | 3 | `.arena create $name "arena name" [2/3/5]` | Создать арена-команду |
| **arena disband** | 3 | `.arena disband #TeamID` | Расформировать арена-команду |
| **arena info** | 2 | `.arena info #TeamID` | Показать информацию об арена-команде |
| **arena lookup** | 2 | `.arena lookup $name` | Найти арена-команды по имени |
| **arena rename** | 3 | `.arena rename "oldname" "newname"` | Переименовать арена-команду |
| **arena season start** | 3 | `.arena season start $season_id` | Начать новый арена-сезон |
| **arena season reward** | 3 | `.arena season reward $brackets` | Выдать награды по лестнице арены |
| **arena season deleteteams** | 3 | `.arena season deleteteams` | Удалить ВСЕ арена-команды |

---

## Команды банов

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **ban account** | 2 | `.ban account $account $bantime $reason` | Забанить аккаунт и кикнуть игрока. Отрицательное время = перманент |
| **ban character** | 2 | `.ban character $character $bantime $reason` | Забанить персонажа и кикнуть его. Отрицательное время = перманент |
| **ban ip** | 2 | `.ban ip $Ip $bantime $reason` | Забанить IP. Отрицательное время = перманент |
| **ban playeraccount** | 2 | `.ban playeraccount $character $bantime $reason` | Забанить аккаунт по имени персонажа |
| **unban account** | 3 | `.unban account $Name` | Разбанить аккаунты по шаблону имени |
| **unban character** | 3 | `.unban character $Name` | Разбанить по имени персонажа |
| **unban ip** | 3 | `.unban ip $Ip` | Разбанить IP |
| **unban playeraccount** | 3 | `.unban playeraccount $characterName` | Разбанить аккаунт, владеющий персонажем |
| **baninfo account** | 2 | `.baninfo account $accountid` | Показать полную информацию о бане аккаунта |
| **baninfo character** | 2 | `.baninfo character $charactername` | Показать информацию о бане персонажа |
| **baninfo ip** | 2 | `.baninfo ip $ip` | Показать информацию о бане IP |
| **banlist account** | 2 | `.banlist account [$Name]` | Поиск в банлисте по имени аккаунта |
| **banlist character** | 2 | `.banlist character $Name` | Поиск в банлисте по имени персонажа |
| **banlist ip** | 2 | `.banlist ip [$Ip]` | Поиск в банлисте по IP |

---

## Команды персонажей

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **character changeaccount** | 3 | `.character changeaccount $NewAccount $Name` | Переместить персонажа на другой аккаунт |
| **character changefaction** | 2 | `.character changefaction $name` | Изменить фракцию персонажа |
| **character changerace** | 2 | `.character changerace $name` | Изменить расу персонажа |
| **character customize** | 2 | `.character customize [$name]` | Отметить персонажа для кастомизации при следующем входе |
| **character deleted delete** | 4 | `.character deleted delete #guid\$name` | Полностью удалить выбранных персонажей |
| **character deleted list** | 3 | `.character deleted list [#guid\$name]` | Показать список удаленных персонажей |
| **character deleted restore** | 3 | `.character deleted restore #guid\$name [$newname]` | Восстановить удаленных персонажей |
| **character erase** | 4 | `.character erase $name` | Удалить персонажа окончательно |
| **character level** | 3 | `.character level [$playername] [#level]` | Установить уровень персонажа |
| **character rename** | 2 | `.character rename [$character_name]` | Отметить персонажа для переименования при входе |
| **character reputation** | 2 | `.character reputation [$player_name]` | Показать информацию о репутации |
| **character titles** | 2 | `.character titles [$player_name]` | Показать список известных титулов |

---

## Читы (Cheat)

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **cheat casttime** | 2 | `.cheat casttime [on/off]` | Включить/выключить время каста заклинаний |
| **cheat cooldown** | 2 | `.cheat cooldown [on/off]` | Включить/выключить кулдауны заклинаний |
| **cheat god** | 2 | `.cheat god [on/off]` | Включить/выключить режим неуязвимости |
| **cheat power** | 2 | `.cheat power [on/off]` | Включить/выключить стоимость заклинаний (мана и т.д.) |
| **cheat status** | 2 | `.cheat status` | Показать включенные читы |
| **cheat taxi** | 2 | `.cheat taxi on/off` | Временно дать доступ ко всем маршрутам такси |
| **cheat waterwalk** | 2 | `.cheat waterwalk on/off` | Включить/выключить хождение по воде |

---

## Боевые команды

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **combatstop** | 2 | `.combatstop [$playername]` | Остановить бой для выбранного персонажа |
| **die** | 2 | `.die` | Убить выбранного игрока. Если никто не выбран, убить себя |
| **damage** | 2 | `.damage $amount [$school [$spellid]]` | Нанести урон цели |
| **freeze** | 2 | `.freeze (#player)` | "Заморозить" игрока и отключить его чат |
| **unfreeze** | 2 | `.unfreeze (#player)` | "Разморозить" игрока и включить чат |

---

## Команды заклинаний

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **cast** | 2 | `.cast #spellid [triggered]` | Применить заклинание к выбранной цели |
| **cast back** | 2 | `.cast back #spellid [triggered]` | Цель применит заклинание к вам |
| **cast dest** | 2 | `.cast dest #spellid #x #y #z [triggered]` | Цель применит заклинание в указанную точку |
| **cast self** | 2 | `.cast self #spellid [triggered]` | Цель применит заклинание на себя |
| **cast target** | 2 | `.cast target #spellid [triggered]` | Цель применит заклинание на свою жертву |
| **aura** | 2 | `.aura #spellid` | Добавить ауру от заклинания выбранной цели |
| **unaura** | 2 | `.unaura #spellid` | Удалить ауру от заклинания |
| **learn** | 2 | `.learn #spell [all]` | Выучить заклинание. Если указан 'all', выучить все ранги |
| **unlearn** | 2 | `.unlearn #spell [all]` | Забыть заклинание |
| **cooldown** | 2 | `.cooldown [#spell_id]` | Убрать кулдаун заклинания (или всех заклинаний) |

---

## Команды обучения

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **learn all crafts** | 2 | `.learn all crafts` | Выучить все профессии и рецепты |
| **learn all default** | 2 | `.learn all default [$playername]` | Выучить все стандартные заклинания для расы/класса |
| **learn all gm** | 2 | `.learn all gm` | Выучить все стандартные заклинания для GM |
| **learn all lang** | 2 | `.learn all lang` | Выучить все языки |
| **learn all my class** | 2 | `.learn all my class` | Выучить все заклинания и таланты класса |
| **learn all my spells** | 2 | `.learn all my spells` | Выучить все заклинания класса (кроме талантов) |
| **learn all my talents** | 2 | `.learn all my talents` | Выучить все таланты класса |
| **learn all recipes** | 2 | `.learn all recipes [$profession]` | Выучить все рецепты профессии и максимальный уровень |

---

## GM режим

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **gm** | 1 | `.gm [on/off]` | Включить/выключить GM режим или показать текущее состояние |
| **gm chat** | 2 | `.gm chat [on/off]` | Включить/выключить GM значок в сообщениях |
| **gm fly** | 2 | `.gm fly [on/off]` | Включить/выключить режим полета |
| **gm ingame** | 0 | `.gm ingame` | Показать список GM в игре |
| **gm list** | 3 | `.gm list` | Показать список всех GM аккаунтов и уровней безопасности |
| **gm visible** | 2 | `.gm visible on/off` | Сделать GM видимым или невидимым для других игроков |
| **gm spectator** | 2 | `.gm spectator on/off` | Позволить GM следовать за членами противоположной фракции |

---

## Команды существ (NPC)

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **npc add** | 3 | `.npc add #creatureid` | Создать существо по ID шаблона |
| **npc delete** | 3 | `.npc delete [#guid]` | Удалить существо по GUID или выбранное |
| **npc guid** | 1 | `.npc guid` | Показать GUID, фракцию, флаги выбранного существа |
| **npc info** | 1 | `.npc info` | Показать детальную информацию о выбранном существе |
| **npc move** | 3 | `.npc move [#creature_guid]` | Переместить точку спавна существа к вашим координатам |
| **npc say** | 2 | `.npc say $message` | Заставить существо сказать сообщение |
| **npc yell** | 2 | `.npc yell $message` | Заставить существо крикнуть сообщение |
| **npc whisper** | 2 | `.npc whisper #playerguid #text` | Заставить NPC шепнуть игроку |
| **npc playemote** | 3 | `.npc playemote #emoteid` | Заставить существо выполнить эмоцию |
| **npc textemote** | 2 | `.npc textemote #emoteid` | Заставить существо выполнить текстовую эмоцию |
| **npc tame** | 2 | `.npc tame` | Приручить выбранное существо (если приручаемо) |
| **npc set level** | 3 | `.npc set level #level` | Изменить уровень существа |
| **npc set model** | 3 | `.npc set model #displayid` | Изменить модель существа |
| **npc set phase** | 3 | `.npc set phase #phasemask` | Изменить фазу существа |

---

## Игровые объекты

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **gobject add** | 3 | `.gobject add #id` | Добавить игровой объект в мир |
| **gobject delete** | 3 | `.gobject delete #go_guid` | Удалить игровой объект |
| **gobject info** | 1 | `.gobject info [$object_entry]` | Показать информацию об игровом объекте |
| **gobject move** | 3 | `.gobject move #goguid [#x #y #z]` | Переместить игровой объект |
| **gobject respawn** | 1 | `.gobject respawn #guid` | Респавнить игровой объект |
| **gobject turn** | 3 | `.gobject turn #goguid` | Повернуть объект к вашей ориентации |
| **gobject activate** | 2 | `.gobject activate #guid` | Активировать объект (дверь, кнопку) |

---

## Квесты

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **quest add** | 2 | `.quest add #quest_id` | Добавить квест в журнал персонажа |
| **quest complete** | 2 | `.quest complete #questid` | Отметить все цели квеста как выполненные |
| **quest remove** | 2 | `.quest remove #quest_id` | Убрать квест из журнала |
| **quest reward** | 2 | `.quest reward #questId` | Выдать награду за квест и убрать квест из журнала |

---

## Гильдии

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **guild create** | 2 | `.guild create [$GuildLeaderName] "$GuildName"` | Создать гильдию |
| **guild delete** | 2 | `.guild delete "$GuildName"` | Удалить гильдию |
| **guild info** | 2 | `.guild info` | Показать информацию о гильдии |
| **guild invite** | 2 | `.guild invite [$CharacterName] "$GuildName"` | Пригласить игрока в гильдию |
| **guild rank** | 2 | `.guild rank [$CharacterName] #RankNumber` | Установить ранг в гильдии |
| **guild rename** | 2 | `.guild rename "$GuildName" "$NewGuildName"` | Переименовать гильдию |
| **guild uninvite** | 2 | `.guild uninvite [$CharacterName]` | Удалить игрока из гильдии |

---

## Группы

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **group disband** | 2 | `.group disband [$characterName]` | Расформировать группу персонажа |
| **group join** | 2 | `.group join $AnyCharacter [$CharacterName]` | Добавить игрока в группу |
| **group leader** | 2 | `.group leader [$characterName]` | Назначить лидером группы |
| **group list** | 2 | `.group list [$CharacterName]` | Показать всех членов группы |
| **group remove** | 2 | `.group remove [$characterName]` | Удалить персонажа из группы |
| **group revive** | 2 | `.group revive $characterName` | Воскресить всех членов группы |

---

## Модификация

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **modify hp** | 2 | `.modify hp #newhp` | Изменить здоровье выбранного игрока |
| **modify mana** | 2 | `.modify mana #newmana` | Изменить ману выбранного игрока |
| **modify energy** | 2 | `.modify energy #energy` | Изменить энергию выбранного игрока |
| **modify rage** | 2 | `.modify rage #newrage` | Изменить ярость выбранного игрока |
| **modify money** | 2 | `.modify money #money` | Добавить или удалить деньги игроку (может быть отрицательным) |
| **modify speed** | 2 | `.modify speed $speedtype #rate` | Изменить скорость движения. Типы: fly, all, walk, backwalk, swim |
| **modify scale** | 2 | `.modify scale #scale` | Изменить размер игрока или существа (0.1 до 10) |
| **modify mount** | 2 | `.modify mount #id #speed` | Показать игрока на маунте |
| **modify gender** | 2 | `.modify gender male/female` | Изменить пол выбранного игрока |
| **modify honor** | 2 | `.modify honor $amount` | Добавить очки чести |
| **modify arenapoints** | 1 | `.modify arenapoints #value` | Добавить очки арены |
| **modify reputation** | 2 | `.modify reputation #repId #repvalue` | Установить репутацию с фракцией |
| **modify phase** | 2 | `.modify phase #phasemask` | Изменить фазу персонажа |
| **modify standstate** | 2 | `.modify standstate #emoteid` | Изменить эмоцию стойки |
| **modify drunk** | 2 | `.modify drunk #value` | Установить уровень опьянения (0-100) |
| **modify talentpoints** | 2 | `.modify talentpoints #amount` | Установить свободные очки талантов |

---

## Поиск (Lookup)

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **lookup area** | 1 | `.lookup area $namepart` | Найти зону по части имени |
| **lookup creature** | 1 | `.lookup creature $namepart` | Найти существо по части имени |
| **lookup event** | 1 | `.lookup event $name` | Найти ID события |
| **lookup faction** | 1 | `.lookup faction $name` | Найти ID фракции |
| **lookup gobject** | 1 | `.lookup gobject $objname` | Найти игровой объект по имени |
| **lookup item** | 1 | `.lookup item $itemname` | Найти предмет по имени |
| **lookup itemset** | 1 | `.lookup itemset $itemname` | Найти набор предметов |
| **lookup quest** | 1 | `.lookup quest $namepart` | Найти квест по части имени |
| **lookup skill** | 1 | `.lookup skill $namepart` | Найти навык по части имени |
| **lookup spell** | 1 | `.lookup spell $namepart` | Найти заклинание по части имени |
| **lookup spell id** | 1 | `.lookup spell id #spellid` | Найти заклинание по ID |
| **lookup taxinode** | 1 | `.lookup taxinode $substring` | Найти точку такси |
| **lookup teleport** | 1 | `.lookup teleport $substring` | Найти локацию телепорта |
| **lookup title** | 1 | `.lookup title $namepart` | Найти титул по части имени |
| **lookup player account** | 2 | `.lookup player account $account` | Найти игроков по имени аккаунта |
| **lookup player email** | 2 | `.lookup player email $email` | Найти игроков по email |
| **lookup player ip** | 2 | `.lookup player ip $ip` | Найти игроков по IP |

---

## Список (List)

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **list auras** | 1 | `.list auras` | Показать список аур выбранного существа или игрока |
| **list creature** | 1 | `.list creature #creature_id [#max_count]` | Найти существ по ID в мире |
| **list gobject** | 1 | `.list gobject #gameobject_id [#max_count]` | Найти игровые объекты по ID |
| **list item** | 1 | `.list item #item_id [#max_count]` | Найти предметы в инвентарях, почте, аукционах |

---

## Сервер

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **server info** | 0 | `.server info` | Показать версию сервера и количество подключенных игроков |
| **server motd** | 0 | `.server motd` | Показать сообщение дня |
| **server set motd** | 3 | `.server set motd [$realmId] $MOTD` | Установить сообщение дня |
| **server exit** | 4 | `.server exit` | Немедленно завершить работу сервера |
| **server restart** | 3 | `.server restart #delay [#exit_code]` | Перезапустить сервер через #delay секунд |
| **server shutdown** | 3 | `.server shutdown #delay [#exit_code]` | Выключить сервер через #delay секунд |
| **server idlerestart** | 4 | `.server idlerestart #delay` | Перезапустить при отсутствии игроков |
| **server idleshutdown** | 3 | `.server idleshutdown #delay` | Выключить при отсутствии игроков |
| **server set closed** | 4 | `.server set closed [on/off]` | Закрыть/открыть сервер для новых подключений |
| **server corpses** | 2 | `.server corpses` | Запустить проверку истечения трупов |
| **server debug** | 3 | `.server debug` | Показать детальную информацию о настройках сервера |

---

## Перезагрузка (Reload)

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **reload all** | 3 | `.reload all` | Перезагрузить все таблицы с поддержкой безопасной перезагрузки |
| **reload config** | 3 | `.reload config` | Перезагрузить настройки из worldserver.conf |
| **reload creature_template** | 3 | `.reload creature_template $entry` | Перезагрузить шаблон существа |
| **reload quest_template** | 3 | `.reload quest_template` | Перезагрузить шаблоны квестов |
| **reload all spell** | 3 | `.reload all spell` | Перезагрузить все таблицы заклинаний |
| **reload all loot** | 3 | `.reload all loot` | Перезагрузить все таблицы лута |
| **reload all npc** | 3 | `.reload all npc` | Перезагрузить все таблицы NPC |
| **reload all quest** | 3 | `.reload all quest` | Перезагрузить все таблицы квестов |

---

## Отладка (Debug)

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **debug play sound** | 1 | `.debug play sound #soundid` | Проиграть звук только для вас |
| **debug play cinematic** | 1 | `.debug play cinematic #cinematicid` | Проиграть ролик |
| **debug play movie** | 1 | `.debug play movie #movieid` | Проиграть видео |
| **debug bg** | 3 | `.debug bg` | Переключить режим отладки для полей боя |
| **debug arena** | 3 | `.debug arena` | Переключить режим отладки для арен |
| **debug getvalue** | 3 | `.debug getvalue #field` | Получить значение поля выбранной цели |
| **debug setvalue** | 3 | `.debug setvalue #field #value` | Установить значение поля |

---

## Тикеты

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **ticket list** | 2 | `.ticket list` | Показать список открытых тикетов |
| **ticket onlinelist** | 2 | `.ticket onlinelist` | Показать тикеты от онлайн игроков |
| **ticket closedlist** | 2 | `.ticket closedlist` | Показать закрытые тикеты |
| **ticket assign** | 2 | `.ticket assign $ticketid $gmname` | Назначить тикет GM |
| **ticket close** | 2 | `.ticket close $ticketid` | Закрыть тикет |
| **ticket delete** | 3 | `.ticket delete $ticketid` | Удалить тикет навсегда |
| **ticket viewid** | 2 | `.ticket viewid $ticketid` | Показать детали тикета |
| **ticket togglesystem** | 4 | `.ticket togglesystem` | Включить/выключить систему тикетов |

---

## Почта

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **send mail** | 2 | `.send mail #playername "#subject" "#text"` | Отправить письмо игроку |
| **send items** | 2 | `.send items #playername "#subject" "#text" itemid1:count1 ...` | Отправить предметы письмом |
| **send money** | 2 | `.send money #playername "#subject" "#text" #money` | Отправить деньги письмом |

---

## Сброс (Reset)

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **reset achievements** | 4 | `.reset achievements [$playername]` | Сбросить достижения персонажа |
| **reset honor** | 3 | `.reset honor [Playername]` | Сбросить всю информацию о чести |
| **reset level** | 3 | `.reset level [Playername]` | Сбросить уровень до 1 |
| **reset spells** | 3 | `.reset spells [Playername]` | Удалить все неоригинальные заклинания |
| **reset stats** | 3 | `.reset stats [Playername]` | Пересчитать все характеристики |
| **reset talents** | 3 | `.reset talents [Playername]` | Сбросить таланты |
| **reset all** | 4 | `.reset all [spells/talents]` | Сбросить заклинания или таланты всем персонажам при входе |

---

## Прочие команды

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **commands** | 0 | `.commands` | Показать список доступных команд |
| **help** | 0 | `.help [$command]` | Показать справку по команде |
| **save** | 0 | `.save` | Сохранить вашего персонажа |
| **saveall** | 2 | `.saveall` | Сохранить всех персонажей в игре |
| **kick** | 2 | `.kick [$charactername] [$reason]` | Кикнуть персонажа из мира |
| **revive** | 2 | `.revive` | Воскресить выбранного игрока |
| **dismount** | 0 | `.dismount` | Спешиться |
| **morph** | 1 | `.morph #displayid` | Изменить модель на #displayid |
| **morph reset** | 1 | `.morph reset` | Сбросить модель на оригинальную |
| **possess** | 2 | `.possess` | Овладеть выбранным существом |
| **unpossess** | 2 | `.unpossess` | Прекратить овладение |
| **bindsight** | 3 | `.bindsight` | Привязать зрение к выбранной цели |
| **unbindsight** | 3 | `.unbindsight` | Отвязать зрение |
| **guid** | 2 | `.guid` | Показать GUID выбранного персонажа |
| **gps** | 1 | `.gps` | Показать информацию о позиции |
| **mailbox** | 1 | `.mailbox` | Открыть почтовый ящик |
| **maxskill** | 2 | `.maxskill` | Установить все навыки на максимум |
| **setskill** | 2 | `.setskill #skill #level [#max]` | Установить навык |
| **levelup** | 2 | `.levelup [$playername] [#levels]` | Повысить уровень персонажа |
| **respawn** | 2 | `.respawn` | Респавнить выбранное существо или объект |
| **distance** | 3 | `.distance` | Показать расстояние до выбранного существа |
| **cometome** | 3 | `.cometome` | Заставить существо подойти к вам |
| **mute** | 2 | `.mute [$playerName] $timeInMinutes [$reason]` | Заглушить игрока в чате |
| **unmute** | 2 | `.unmute [$playerName]` | Снять заглушение |
| **pinfo** | 2 | `.pinfo [$player_name/#GUID]` | Показать информацию об аккаунте игрока |
| **unstuck** | 2 | `.unstuck $playername [inn/graveyard/startzone]` | Телепортировать застрявшего игрока |
| **dev** | 3 | `.dev [on/off]` | Включить/выключить DEV тег в игре |
| **playall** | 2 | `.playall #soundid` | Проиграть звук для всего сервера |

---

## Waypoints (Путевые точки)

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **wp add** | 3 | `.wp add` | Добавить путевую точку для выбранного существа |
| **wp load** | 3 | `.wp load $pathid` | Загрузить путь для выбранного существа |
| **wp unload** | 3 | `.wp unload` | Выгрузить путь для выбранного существа |
| **wp show** | 3 | `.wp show $option` | Показать/скрыть путевые точки |
| **wp modify** | 3 | `.wp modify $option` | Изменить путевую точку |

---

## Погода

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **wchange** | 3 | `.wchange #weathertype #grade` | Изменить погоду. Типы: 0-ясно, 1-дождь, 2-снег, 3-шторм |

---

## Титулы

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **titles add** | 2 | `.titles add #title` | Добавить титул игроку |
| **titles current** | 2 | `.titles current #title` | Установить текущий титул |
| **titles remove** | 2 | `.titles remove #title` | Удалить титул |
| **titles set mask** | 2 | `.titles set mask #mask` | Разрешить использовать все титулы из маски |

---

## Честь и PvP

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **honor add** | 2 | `.honor add $amount` | Добавить очки чести |
| **honor update** | 2 | `.honor update` | Обновить поля чести |
| **flusharenapoints** | 3 | `.flusharenapoints` | Распределить очки арены и начать новую неделю |

---

## Инстансы

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **instance listbinds** | 1 | `.instance listbinds` | Показать привязки выбранного игрока к инстансам |
| **instance unbind** | 2 | `.instance unbind <mapid\all> [difficulty]` | Очистить привязки игрока |
| **instance stats** | 1 | `.instance stats` | Показать статистику по инстансам |
| **instance savedata** | 3 | `.instance savedata` | Сохранить данные инстанса в БД |
| **instance setbossstate** | 2 | `.instance setbossstate $bossId $state` | Установить состояние босса |
| **instance getbossstate** | 1 | `.instance getbossstate $bossId` | Получить состояние босса |

---

## Дезертир

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **deserter bg add** | 3 | `.deserter bg add [$playerName] [$time]` | Добавить дебафф дезертира БГ |
| **deserter bg remove** | 3 | `.deserter bg remove [$playerName]` | Удалить дебафф дезертира БГ |
| **deserter bg remove all** | 3 | `.deserter bg remove all [$maxDuration]` | Удалить дебафф всем |
| **deserter instance add** | 3 | `.deserter instance add [$playerName] [$time]` | Добавить дебафф дезертира подземелья |
| **deserter instance remove** | 3 | `.deserter instance remove [$playerName]` | Удалить дебафф дезертира подземелья |

---

## LFG (Поиск группы)

| Команда | Уровень | Синтаксис | Описание |
|---------|---------|-----------|----------|
| **lfg clean** | 3 | `.lfg clean` | Очистить текущую очередь LFG |
| **lfg player** | 1 | `.lfg player` | Показать информацию об игроке в LFG |
| **lfg group** | 1 | `.lfg group` | Показать информацию о группе в LFG |
| **lfg queue** | 1 | `.lfg queue` | Показать информацию об очередях LFG |
| **lfg options** | 2 | `.lfg options [new value]` | Показать/установить опции LFG |

---

**Примечание:** Уровни доступа:
- 0 = Игрок
- 1 = Модератор
- 2 = Game Master
- 3 = Администратор
- 4 = Консоль/Высший администратор

Для изменения уровня используйте: `.account set gmlevel $account #level`

# Новая структура Layout - Три колонки

## Обзор изменений

Реорганизована структура `layout.html.php` для объединения левого, правого и центрального контента в единый контейнер с использованием современного Flexbox layout.

## Структура HTML

### Старая структура (до изменений):
```php
<div class="block-border">
    <div class="test3 block-border">
        <!-- Левое меню (position: absolute) -->
    </div>
    <div class="test2 block-border">
        <!-- Правая панель (position: absolute) -->
    </div>
    <!-- Центральный контент -->
</div>
```

### Новая структура (после изменений):
```php
<div class="block-border">
    <div class="layout-container">
        <aside class="layout-sidebar layout-sidebar-left test3">
            <!-- Левое меню -->
        </aside>
        
        <aside class="layout-sidebar layout-sidebar-right test2">
            <!-- Правая панель -->
        </aside>
        
        <main class="layout-content">
            <!-- Центральный контент -->
        </main>
    </div>
</div>
```

## Преимущества новой структуры

### 1. **Семантическая разметка**
- `<aside>` для боковых панелей
- `<main>` для основного контента
- Улучшенная доступность и SEO

### 2. **Flexbox вместо absolute positioning**
- Проще управление расположением
- Автоматическое выравнивание
- Нативная адаптивность

### 3. **Единый контейнер**
- Все три колонки внутри `.layout-container`
- Централизованное управление расположением
- Легче добавлять gap между элементами

### 4. **Адаптивность**
- Автоматическая перестройка на мобильных
- Media queries для разных экранов
- Плавные переходы между breakpoints

## CSS стили

### Layout Container
```css
.layout-container {
    position: relative;
    width: 100%;
    max-width: 1320px;  /* 280 + 740 + 300 */
    margin: 0 auto;
    display: flex;
    flex-wrap: nowrap;
    gap: 10px;
}
```

### Колонки

**Левая боковая панель:**
```css
.layout-sidebar-left {
    flex: 0 0 280px;
    width: 280px;
    min-height: 200px;
    order: 1;
}
```

**Центральный контент:**
```css
.layout-content {
    flex: 1 1 740px;
    max-width: 740px;
    order: 2;
}
```

**Правая боковая панель:**
```css
.layout-sidebar-right {
    flex: 0 0 280px;
    width: 280px;
    min-height: 200px;
    order: 3;
}
```

## Адаптивность

### Desktop (> 1320px)
```
┌────────────────────────────────────────────┐
│  [Левое]  [Центральное]  [Правое]         │
│   280px       740px        280px           │
└────────────────────────────────────────────┘
```

### Tablet (768px - 1320px)
```
┌────────────────────────────────┐
│  [Левое]  [Правое]            │
│        [Центральное]           │
└────────────────────────────────┘
Order: 1-Left, 2-Right, 3-Content
```

### Mobile (< 768px)
```
┌─────────────────┐
│  [Левое]       │
│  [Правое]      │
│  [Центральное] │
└─────────────────┘
Vertical stack
```

## Media Queries

### Средние экраны (до 1320px)
```css
@media (max-width: 1320px) {
    .layout-container {
        flex-wrap: wrap;
    }
    
    .layout-content {
        order: 3;
        flex: 1 1 100%;
        max-width: 100%;
    }
}
```

### Мобильные (до 768px)
```css
@media (max-width: 768px) {
    .layout-container {
        flex-direction: column;
    }
    
    .layout-sidebar-left,
    .layout-sidebar-right,
    .layout-content {
        width: 100%;
        max-width: 100%;
    }
}
```

## Premium дизайн

### Дополнительные стили для premium темы:

```css
.layout-container {
    gap: 15px !important;
}

.layout-sidebar-left,
.layout-sidebar-right {
    background: linear-gradient(135deg, 
        rgba(25, 25, 72, 0.95) 0%, 
        rgba(0, 0, 51, 0.95) 100%);
    padding: 15px;
}

.layout-sidebar-left {
    border: none !important;
    border-radius: 0 !important;
}

.layout-sidebar-right {
    border: 2px solid #4a4a70;
    border-radius: 12px !important;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
}

.layout-content {
    background: rgba(0, 0, 0, 0.3);
    padding: 15px;
    border-radius: 10px;
}
```

## Совместимость

### Обратная совместимость
Старые классы `.test2` и `.test3` сохранены:
```css
.test2 {
    background-color: #000033;
}

.test3 {
    background-color: #000033;
}
```

Это обеспечивает работу старых стилей, пока они не будут обновлены.

## Flexbox порядок (order)

Использование свойства `order` позволяет изменять визуальный порядок без изменения HTML:

```css
.layout-sidebar-left { order: 1; }   /* Первая */
.layout-content { order: 2; }        /* Вторая */
.layout-sidebar-right { order: 3; }  /* Третья */
```

На планшетах можно изменить:
```css
@media (max-width: 1320px) {
    .layout-sidebar-left { order: 1; }
    .layout-sidebar-right { order: 2; }
    .layout-content { order: 3; }      /* Контент вниз */
}
```

## Преимущества Flexbox

1. **Автоматическое выравнивание** - элементы выравниваются по высоте
2. **Гибкие размеры** - `flex: 1 1 auto` автоматически заполняет пространство
3. **Gap между элементами** - нативное свойство `gap` без margin
4. **Порядок элементов** - `order` без изменения HTML
5. **Адаптивность** - `flex-wrap` для переноса элементов

## Миграция

### Что изменилось:
1. ✅ Добавлен `.layout-container` - общий контейнер
2. ✅ Классы `.layout-sidebar-left/right` - семантические названия
3. ✅ Класс `.layout-content` - основной контент
4. ✅ Flexbox вместо absolute positioning
5. ✅ Media queries для адаптивности

### Что сохранилось:
- ✅ Классы `.test2` и `.test3` (для совместимости)
- ✅ Includes файлов partials (left_block, right_block)
- ✅ Переменная `$contentFile`
- ✅ Все функциональные возможности

## Файлы

- **Layout**: `templates/layout.html.php`
- **Стили**: `public/css/style.css` (строки 184-265)
- **Premium стили**: `public/css/premium-style.css` (строки 1-29)

## Будущие улучшения

- [ ] CSS Grid вместо Flexbox (более гибкий)
- [ ] Sticky sidebars (прикрепленные панели при прокрутке)
- [ ] Collapsible sidebars (сворачивающиеся панели)
- [ ] Drag & drop для изменения порядка
- [ ] Сохранение пользовательских настроек layout

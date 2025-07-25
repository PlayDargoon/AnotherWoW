<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AnotherWoW - <?= $pageTitle ?? 'Главная страница' ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="icon" href="/favicon.png" type="image/x-icon">
</head>

<body id="body_id" ignorewebview="true">

<div class="touch-influence block-border">

    <?php if (
        isset($_SESSION['logged_in']) &&
        $_SESSION['logged_in'] &&
        $contentFile != 'pages/cabinet.html.php' // Исключение кабинета
    ): ?>
        <div class="header small block-border-bottom">
            <?php include 'partials/header.html.php'; ?> <!-- Шапка -->
        </div>
    <?php endif; ?>

    <?php if (!empty($contentFile)): ?>
        <?php include $contentFile; ?> <!-- Здесь отображается контент конкретной страницы -->
    <?php endif; ?>

    <?php if (
        isset($_SESSION['logged_in']) &&
        $_SESSION['logged_in'] &&
        $contentFile != 'pages/cabinet.html.php' // Исключение кабинета
    ): ?>
        <div class="footer block-border-top"></div>   <!-- Специальный отступ -->
        <?php include 'partials/logged_footer.html.php'; ?> <!-- Логинный футер -->
    <?php endif; ?>
</div>

<div class="b-mt-footer">
    <?php include 'partials/footer.html.php'; ?>
</div>

</body>
</html>
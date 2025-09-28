<?php
// test_captcha.php - Тестовый файл для проверки капчи

require_once 'bootstrap.php';

echo "<h1>Тест капчи</h1>";

// Генерируем капчу
$captcha = CaptchaService::generateAndStoreCaptcha();
echo "<p>Сгенерированная капча: <strong>$captcha</strong></p>";

// Проверяем сессию
echo "<p>Вопрос в сессии: " . ($_SESSION['captcha_question'] ?? 'Не установлен') . "</p>";
echo "<p>Ответ в сессии: " . ($_SESSION['captcha_answer'] ?? 'Не установлен') . "</p>";
echo "<p>Время в сессии: " . ($_SESSION['captcha_time'] ?? 'Не установлено') . "</p>";

// Тестируем проверку
$testAnswer = $_SESSION['captcha_answer'] ?? 0;
echo "<p>Тестируем правильный ответ ($testAnswer): " . (CaptchaService::verifyCaptcha($testAnswer) ? 'ПРОЙДЕН' : 'НЕ ПРОЙДЕН') . "</p>";

// Повторно генерируем для второго теста
$captcha2 = CaptchaService::generateAndStoreCaptcha();
echo "<br><p>Вторая капча: <strong>$captcha2</strong></p>";
echo "<p>Тестируем неправильный ответ (999): " . (CaptchaService::verifyCaptcha(999) ? 'ПРОЙДЕН' : 'НЕ ПРОЙДЕН') . "</p>";

?>

<form method="post">
    <p>Введите ответ на: <?= CaptchaService::getCaptchaQuestion() ?></p>
    <input type="number" name="answer" required>
    <button type="submit">Проверить</button>
</form>

<?php
if (isset($_POST['answer'])) {
    $userAnswer = $_POST['answer'];
    $isValid = CaptchaService::verifyCaptcha($userAnswer);
    echo "<p>Ваш ответ: $userAnswer - " . ($isValid ? 'ПРАВИЛЬНО!' : 'НЕПРАВИЛЬНО!') . "</p>";
    
    if (!$isValid) {
        // Генерируем новую капчу при неправильном ответе
        CaptchaService::generateAndStoreCaptcha();
        echo "<p>Сгенерирована новая капча</p>";
    }
}
?>
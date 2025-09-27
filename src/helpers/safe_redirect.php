<?php
/**
 * Безопасный редирект, защищенный от ошибки "headers already sent"
 * 
 * @param string $url URL для редиректа
 * @param int $code HTTP код статуса (по умолчанию 302)
 */
function safeRedirect($url, $code = 302) {
    // Проверяем, не отправлены ли уже заголовки
    if (headers_sent($filename, $linenum)) {
        // Если заголовки уже отправлены, используем JavaScript и meta-теги
        echo "<script>window.location.href='" . htmlspecialchars($url, ENT_QUOTES) . "';</script>";
        echo '<meta http-equiv="refresh" content="0;url=' . htmlspecialchars($url, ENT_QUOTES) . '">';
        echo '<p><a href="' . htmlspecialchars($url, ENT_QUOTES) . '">Нажмите здесь, если страница не перенаправляется автоматически</a></p>';
        exit;
    }
    
    // Очищаем все буферы вывода
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    // Отправляем HTTP редирект
    header('Location: ' . $url, true, $code);
    exit;
}

/**
 * Безопасная инициализация сессии
 */
function safeSessionStart() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Безопасное уничтожение сессии
 */
function safeSessionDestroy() {
    // Запускаем сессию если нужно
    safeSessionStart();
    
    // Очищаем данные сессии
    $_SESSION = array();
    
    // Удаляем куки сессии
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Уничтожаем сессию
    session_destroy();
}
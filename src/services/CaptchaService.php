<?php
// src/services/CaptchaService.php

class CaptchaService
{
    /**
     * Генерирует математическое выражение для капчи
     * @return array ['question' => 'вопрос', 'answer' => правильный_ответ]
     */
    public static function generateMathCaptcha(): array
    {
        $num1 = rand(1, 10);
        $num2 = rand(1, 10);
        $operation = rand(0, 1) ? '+' : '-';
        
        if ($operation === '+') {
            $question = "$num1 + $num2 = ?";
            $answer = $num1 + $num2;
        } else {
            // Убеждаемся, что результат положительный
            if ($num1 < $num2) {
                $temp = $num1;
                $num1 = $num2;
                $num2 = $temp;
            }
            $question = "$num1 - $num2 = ?";
            $answer = $num1 - $num2;
        }
        
        return [
            'question' => $question,
            'answer' => $answer
        ];
    }
    
    /**
     * Сохраняет капчу в сессию
     * @param string $question
     * @param int $answer
     */
    public static function storeCaptchaInSession(string $question, int $answer): void
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['captcha_question'] = $question;
        $_SESSION['captcha_answer'] = $answer;
        $_SESSION['captcha_time'] = time();
    }
    
    /**
     * Проверяет ответ капчи
     * @param string|int $userAnswer
     * @return bool
     */
    public static function verifyCaptcha($userAnswer): bool
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        // Проверяем, что капча существует в сессии
        if (!isset($_SESSION['captcha_answer']) || !isset($_SESSION['captcha_time'])) {
            return false;
        }
        
        // Проверяем, что капча не истекла (5 минут)
        if (time() - $_SESSION['captcha_time'] > 300) {
            self::clearCaptcha();
            return false;
        }
        
        $isValid = (int)$userAnswer === $_SESSION['captcha_answer'];
        
        // Очищаем капчу после проверки
        if ($isValid) {
            self::clearCaptcha();
        }
        
        return $isValid;
    }
    
    /**
     * Очищает капчу из сессии
     */
    public static function clearCaptcha(): void
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        unset($_SESSION['captcha_question']);
        unset($_SESSION['captcha_answer']);
        unset($_SESSION['captcha_time']);
        unset($_SESSION['captcha_last_generation']);
    }
    
    /**
     * Получает вопрос капчи из сессии
     * @return string|null
     */
    public static function getCaptchaQuestion(): ?string
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        return $_SESSION['captcha_question'] ?? null;
    }
    
    /**
     * Генерирует новую капчу и сохраняет в сессию
     * @return string Вопрос капчи
     */
    public static function generateAndStoreCaptcha(): string
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        // Защита от слишком частой генерации капчи (не чаще раза в секунду)
        $lastGeneration = $_SESSION['captcha_last_generation'] ?? 0;
        $currentTime = time();
        
        if ($currentTime - $lastGeneration < 1) {
            // Возвращаем существующую капчу, если она есть
            if (isset($_SESSION['captcha_question'])) {
                return $_SESSION['captcha_question'];
            }
        }
        
        $captcha = self::generateMathCaptcha();
        self::storeCaptchaInSession($captcha['question'], $captcha['answer']);
        $_SESSION['captcha_last_generation'] = $currentTime;
        
        return $captcha['question'];
    }
}
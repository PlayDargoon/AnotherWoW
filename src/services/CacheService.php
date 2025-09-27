<?php
// src/services/CacheService.php
/**
 * Универсальный сервис для кеширования данных
 * Поддерживает файловое кеширование с настраиваемым TTL
 */
class CacheService
{
    private static $instance = null;
    private $cacheDir;
    private $defaultTtl = 300; // 5 минут по умолчанию
    private $memoryCache = []; // Кеш в памяти для текущего запроса
    private $config = [];
    private $enabled = true;
    
    private function __construct()
    {
        // Загружаем конфигурацию кеширования
        $configFile = __DIR__ . '/../../config/cache.php';
        if (file_exists($configFile)) {
            $this->config = require $configFile;
            $this->defaultTtl = $this->config['default_ttl'] ?? 300;
            $this->cacheDir = $this->config['cache_dir'] ?? __DIR__ . '/../../cache';
            
            // Определяем среду выполнения
            $environment = $_ENV['APP_ENV'] ?? 'production';
            if (isset($this->config['environments'][$environment])) {
                $this->enabled = $this->config['environments'][$environment]['enabled'] ?? true;
            }
        } else {
            $this->cacheDir = __DIR__ . '/../../cache';
        }
        
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }
        
        // Случайная очистка кеша
        if ($this->enabled && isset($this->config['cleanup']['auto_cleanup']) && 
            $this->config['cleanup']['auto_cleanup']) {
            $this->randomCleanup();
        }
    }
    
    /**
     * Получить экземпляр сервиса (Singleton)
     */
    public static function getInstance(): CacheService
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Получить данные из кеша
     * @param string $key - ключ кеша
     * @param int|null $ttl - время жизни в секундах (null = проверить существующий кеш)
     * @return mixed|null - данные или null если кеш не найден/устарел
     */
    public function get(string $key, ?int $ttl = null): mixed
    {
        if (!$this->enabled) {
            return null;
        }
        
        // Сначала проверяем кеш в памяти
        if (isset($this->memoryCache[$key])) {
            $cached = $this->memoryCache[$key];
            if ($cached['expires'] > time()) {
                return $cached['data'];
            }
            unset($this->memoryCache[$key]);
        }
        
        $filename = $this->getCacheFilename($key);
        if (!file_exists($filename)) {
            return null;
        }
        
        $cacheTime = $ttl ?? $this->defaultTtl;
        if (filemtime($filename) < time() - $cacheTime) {
            unlink($filename);
            return null;
        }
        
        $data = unserialize(file_get_contents($filename));
        
        // Сохраняем в кеш памяти
        $this->memoryCache[$key] = [
            'data' => $data,
            'expires' => time() + $cacheTime
        ];
        
        return $data;
    }
    
    /**
     * Сохранить данные в кеш
     * @param string $key - ключ кеша
     * @param mixed $data - данные для сохранения
     * @param int|null $ttl - время жизни в секундах
     */
    public function set(string $key, mixed $data, ?int $ttl = null): void
    {
        if (!$this->enabled) {
            return;
        }
        
        $filename = $this->getCacheFilename($key);
        file_put_contents($filename, serialize($data));
        
        // Сохраняем в кеш памяти
        $cacheTime = $ttl ?? $this->defaultTtl;
        $this->memoryCache[$key] = [
            'data' => $data,
            'expires' => time() + $cacheTime
        ];
    }
    
    /**
     * Удалить данные из кеша
     */
    public function delete(string $key): void
    {
        unset($this->memoryCache[$key]);
        
        $filename = $this->getCacheFilename($key);
        if (file_exists($filename)) {
            unlink($filename);
        }
    }
    
    /**
     * Очистить весь кеш
     */
    public function clear(): void
    {
        $this->memoryCache = [];
        
        $files = glob($this->cacheDir . '/cache_*.php');
        foreach ($files as $file) {
            unlink($file);
        }
    }
    
    /**
     * Кеширование с функцией обратного вызова
     * @param string $key - ключ кеша
     * @param callable $callback - функция для получения данных
     * @param int|null $ttl - время жизни в секундах
     * @return mixed
     */
    public function remember(string $key, callable $callback, ?int $ttl = null): mixed
    {
        $data = $this->get($key, $ttl);
        if ($data !== null) {
            return $data;
        }
        
        $data = $callback();
        $this->set($key, $data, $ttl);
        return $data;
    }
    
    /**
     * Получить имя файла кеша
     */
    private function getCacheFilename(string $key): string
    {
        $hashedKey = md5($key);
        return $this->cacheDir . '/cache_' . $hashedKey . '.php';
    }
    
    /**
     * Установить TTL по умолчанию
     */
    public function setDefaultTtl(int $ttl): void
    {
        $this->defaultTtl = $ttl;
    }
    
    /**
     * Получить статистику кеша
     */
    public function getStats(): array
    {
        $files = glob($this->cacheDir . '/cache_*.php');
        $totalSize = 0;
        $expiredCount = 0;
        
        foreach ($files as $file) {
            $totalSize += filesize($file);
            if (filemtime($file) < time() - $this->defaultTtl) {
                $expiredCount++;
            }
        }
        
        return [
            'total_files' => count($files),
            'total_size_bytes' => $totalSize,
            'total_size_mb' => round($totalSize / 1024 / 1024, 2),
            'expired_files' => $expiredCount,
            'memory_cache_items' => count($this->memoryCache),
            'enabled' => $this->enabled,
        ];
    }
    
    /**
     * Случайная очистка устаревшего кеша
     */
    private function randomCleanup(): void
    {
        $probability = $this->config['cleanup']['cleanup_probability'] ?? 10;
        
        if (rand(1, 100) <= $probability) {
            $maxAge = ($this->config['cleanup']['max_file_age_hours'] ?? 24) * 3600;
            $files = glob($this->cacheDir . '/cache_*.php');
            
            foreach ($files as $file) {
                if (filemtime($file) < time() - $maxAge) {
                    unlink($file);
                }
            }
        }
    }
    
    /**
     * Получить TTL для конкретного типа данных
     */
    public function getTtlForType(string $type): int
    {
        return $this->config['ttl'][$type] ?? $this->defaultTtl;
    }
    
    /**
     * Проверить, включено ли кеширование
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}
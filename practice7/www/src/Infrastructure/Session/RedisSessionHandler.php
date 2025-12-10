<?php

namespace App\Infrastructure\Session;

use Redis;
use SessionHandlerInterface;

/**
 * Обработчик сессий с использованием Redis
 */
class RedisSessionHandler implements SessionHandlerInterface
{
    private Redis $redis;
    private int $ttl;
    private string $prefix;

    public function __construct(array $config)
    {
        $this->redis = new Redis();
        $this->ttl = $config['ttl'] ?? 3600;
        $this->prefix = $config['prefix'] ?? 'PHPREDIS_SESSION:';
        
        $this->connect($config);
    }

    private function connect(array $config): void
    {
        $host = $config['host'] ?? 'localhost';
        $port = $config['port'] ?? 6379;
        $timeout = $config['timeout'] ?? 2.5;
        
        if (!$this->redis->connect($host, $port, $timeout)) {
            throw new \Exception("Cannot connect to Redis server");
        }
    }

    public function open($savePath, $sessionName): bool
    {
        return true;
    }

    public function close(): bool
    {
        return $this->redis->close();
    }

    public function read($sessionId): string
    {
        $key = $this->prefix . $sessionId;
        $data = $this->redis->get($key);
        
        return $data !== false ? $data : '';
    }

    public function write($sessionId, $sessionData): bool
    {
        $key = $this->prefix . $sessionId;
        return $this->redis->setex($key, $this->ttl, $sessionData);
    }

    public function destroy($sessionId): bool
    {
        $key = $this->prefix . $sessionId;
        return $this->redis->del($key) > 0;
    }

    public function gc($maxlifetime): int
    {
        // Redis автоматически удаляет истекшие ключи
        return 0;
    }

    /**
     * Получение всех активных сессий
     */
    public function getAllSessions(): array
    {
        $keys = $this->redis->keys($this->prefix . '*');
        $sessions = [];
        
        foreach ($keys as $key) {
            $sessionId = str_replace($this->prefix, '', $key);
            $data = $this->redis->get($key);
            $ttl = $this->redis->ttl($key);
            
            $sessions[] = [
                'session_id' => $sessionId,
                'data' => $data,
                'ttl' => $ttl,
            ];
        }
        
        return $sessions;
    }
}
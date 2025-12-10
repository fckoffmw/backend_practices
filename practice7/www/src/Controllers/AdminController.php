<?php

namespace App\Controllers;

use App\Core\Controller;

/**
 * Контроллер административных команд
 */
class AdminController extends Controller
{
    /**
     * Показать админ-панель
     */
    public function index(): void
    {
        $this->requireAuth();
        
        $user = $this->getCurrentUser();
        
        // Проверяем права администратора
        if ($user['role'] !== 'admin') {
            $_SESSION['error_message'] = 'Доступ запрещен. Требуются права администратора.';
            $this->redirect('/services');
        }

        $systemInfo = $this->getSystemInfo();
        
        $this->render('services/admin', [
            'title' => 'Административная панель',
            'user' => $user,
            'systemInfo' => $systemInfo,
        ]);
    }

    /**
     * Выполнение административной команды
     */
    public function execute(): void
    {
        $this->requireAuth();
        
        $user = $this->getCurrentUser();
        
        if ($user['role'] !== 'admin') {
            $this->json(['error' => 'Доступ запрещен'], 403);
            return;
        }

        $command = $_POST['command'] ?? '';
        
        if (empty($command)) {
            $this->json(['error' => 'Команда не указана'], 400);
            return;
        }

        $result = $this->executeCommand($command);
        
        $this->json([
            'success' => true,
            'command' => $command,
            'result' => $result,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Получение системной информации
     */
    private function getSystemInfo(): array
    {
        return [
            'php_version' => PHP_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'memory_usage' => $this->formatBytes(memory_get_usage(true)),
            'memory_peak' => $this->formatBytes(memory_get_peak_usage(true)),
            'uptime' => $this->getUptime(),
            'disk_usage' => $this->getDiskUsage(),
            'load_average' => $this->getLoadAverage(),
        ];
    }

    /**
     * Выполнение безопасной административной команды
     */
    private function executeCommand(string $command): string
    {
        // Список разрешенных команд для безопасности
        $allowedCommands = [
            'phpinfo' => 'php -v',
            'disk' => 'df -h',
            'memory' => 'free -h',
            'processes' => 'ps aux | head -10',
            'date' => 'date',
            'whoami' => 'whoami',
            'pwd' => 'pwd',
            'ls' => 'ls -la',
        ];

        if (!isset($allowedCommands[$command])) {
            return "Ошибка: Команда '$command' не разрешена по соображениям безопасности.\n\nРазрешенные команды: " . implode(', ', array_keys($allowedCommands));
        }

        $actualCommand = $allowedCommands[$command];
        
        // Выполняем команду безопасно
        $output = [];
        $returnCode = 0;
        exec($actualCommand . ' 2>&1', $output, $returnCode);
        
        $result = implode("\n", $output);
        
        if ($returnCode !== 0) {
            return "Ошибка выполнения команды (код: $returnCode):\n$result";
        }
        
        return $result;
    }

    /**
     * Форматирование байтов
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Получение времени работы системы
     */
    private function getUptime(): string
    {
        if (file_exists('/proc/uptime')) {
            $uptime = file_get_contents('/proc/uptime');
            $seconds = (int) explode(' ', $uptime)[0];
            
            $days = floor($seconds / 86400);
            $hours = floor(($seconds % 86400) / 3600);
            $minutes = floor(($seconds % 3600) / 60);
            
            return "{$days}d {$hours}h {$minutes}m";
        }
        
        return 'Unknown';
    }

    /**
     * Получение информации о диске
     */
    private function getDiskUsage(): array
    {
        $total = disk_total_space('/');
        $free = disk_free_space('/');
        $used = $total - $free;
        
        return [
            'total' => $this->formatBytes($total),
            'used' => $this->formatBytes($used),
            'free' => $this->formatBytes($free),
            'percent' => $total > 0 ? round(($used / $total) * 100, 1) : 0,
        ];
    }

    /**
     * Получение средней нагрузки
     */
    private function getLoadAverage(): string
    {
        if (function_exists('sys_getloadavg')) {
            $load = sys_getloadavg();
            return sprintf('%.2f %.2f %.2f', $load[0], $load[1], $load[2]);
        }
        
        return 'Unknown';
    }
}
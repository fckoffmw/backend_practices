<?php
/**
 * Библиотека админ-панели
 * Из Practice 2
 */

function allowed_commands(): array {
    return [
        'whoami' => 'whoami',
        'id' => 'id',
        'ps' => 'ps aux | head -n 10',
        'ls' => 'ls -la /var/www/html',
        'uptime' => 'uptime',
        'uname' => 'uname -a',
        'df' => 'df -h',
        'free' => 'free -m 2>/dev/null || echo "free command not available"',
        'redis-ping' => 'redis-cli -h redis ping 2>/dev/null || echo "Redis client not installed"'
    ];
}

function run_command(string $key): string {
    $cmds = allowed_commands();
    if (!isset($cmds[$key])) return "Command not allowed";
    return shell_exec($cmds[$key]) ?? '';
}

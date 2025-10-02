<?php
function allowed_commands(): array {
    return [
        'whoami' => 'whoami',
        'id' => 'id',
        'ps' => 'ps aux | head -n 10',
        'ls' => 'ls -la /var/www/html',
        'uptime' => 'uptime',
        'uname' => 'uname -a'
    ];
}

function run_command(string $key): string {
    $cmds = allowed_commands();
    if (!isset($cmds[$key])) return "Команда не разрешена";
    return shell_exec($cmds[$key]) ?? '';
}

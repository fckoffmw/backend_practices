<h1><?= htmlspecialchars($title) ?></h1>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin: 20px 0;">
    
    <!-- Системная информация -->
    <div>
        <h2>Системная информация</h2>
        <table class="table">
            <tr>
                <td><strong>PHP версия:</strong></td>
                <td><?= htmlspecialchars($systemInfo['php_version']) ?></td>
            </tr>
            <tr>
                <td><strong>Веб-сервер:</strong></td>
                <td><?= htmlspecialchars($systemInfo['server_software']) ?></td>
            </tr>
            <tr>
                <td><strong>Использование памяти:</strong></td>
                <td><?= htmlspecialchars($systemInfo['memory_usage']) ?></td>
            </tr>
            <tr>
                <td><strong>Пик памяти:</strong></td>
                <td><?= htmlspecialchars($systemInfo['memory_peak']) ?></td>
            </tr>
            <tr>
                <td><strong>Время работы:</strong></td>
                <td><?= htmlspecialchars($systemInfo['uptime']) ?></td>
            </tr>
            <tr>
                <td><strong>Средняя нагрузка:</strong></td>
                <td><?= htmlspecialchars($systemInfo['load_average']) ?></td>
            </tr>
        </table>

        <h3>Использование диска</h3>
        <div style="background: #f8f9fa; padding: 15px; border-radius: 4px; margin: 10px 0;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                <span>Всего: <?= htmlspecialchars($systemInfo['disk_usage']['total']) ?></span>
                <span>Свободно: <?= htmlspecialchars($systemInfo['disk_usage']['free']) ?></span>
            </div>
            <div style="background: #e9ecef; height: 20px; border-radius: 10px; overflow: hidden;">
                <div style="background: #007bff; height: 100%; width: <?= $systemInfo['disk_usage']['percent'] ?>%; transition: width 0.3s;"></div>
            </div>
            <div style="text-align: center; margin-top: 5px;">
                Использовано: <?= htmlspecialchars($systemInfo['disk_usage']['used']) ?> (<?= $systemInfo['disk_usage']['percent'] ?>%)
            </div>
        </div>
    </div>

    <!-- Выполнение команд -->
    <div>
        <h2>Выполнение команд</h2>
        <div style="margin-bottom: 20px;">
            <label for="command">Выберите команду:</label>
            <select id="command" class="form-control" style="margin: 10px 0;">
                <option value="">-- Выберите команду --</option>
                <option value="phpinfo">Информация о PHP</option>
                <option value="disk">Информация о дисках</option>
                <option value="memory">Информация о памяти</option>
                <option value="processes">Список процессов</option>
                <option value="date">Текущая дата и время</option>
                <option value="whoami">Текущий пользователь</option>
                <option value="pwd">Текущая директория</option>
                <option value="ls">Список файлов</option>
            </select>
            <button onclick="executeCommand()" class="btn">Выполнить</button>
        </div>

        <div id="commandResult" style="background: #f8f9fa; padding: 15px; border-radius: 4px; min-height: 200px; font-family: monospace; white-space: pre-wrap; display: none;"></div>
    </div>
</div>

<div style="background: #fff3cd; padding: 15px; border-radius: 4px; margin: 20px 0;">
    <h3>⚠️ Безопасность</h3>
    <p>Доступны только безопасные системные команды. Выполнение произвольных команд заблокировано по соображениям безопасности.</p>
</div>

<script>
async function executeCommand() {
    const command = document.getElementById('command').value;
    const resultDiv = document.getElementById('commandResult');
    
    if (!command) {
        alert('Выберите команду для выполнения');
        return;
    }
    
    resultDiv.style.display = 'block';
    resultDiv.textContent = 'Выполнение команды...';
    
    try {
        const response = await fetch('/services/admin/execute', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `command=${encodeURIComponent(command)}`,
            credentials: 'include'
        });
        
        const data = await response.json();
        
        if (data.success) {
            resultDiv.textContent = `Команда: ${data.command}\nВремя: ${data.timestamp}\n\nРезультат:\n${data.result}`;
        } else {
            resultDiv.textContent = `Ошибка: ${data.error}`;
        }
    } catch (error) {
        resultDiv.textContent = `Ошибка выполнения: ${error.message}`;
    }
}
</script>
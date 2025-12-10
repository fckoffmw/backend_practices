<h1><?= htmlspecialchars($title) ?></h1>

<div style="max-width: 600px;">
    <form method="POST" action="/settings">
        <div class="form-group">
            <label for="theme">Тема оформления:</label>
            <select id="theme" name="theme" class="form-control">
                <option value="light" <?= ($user['theme'] ?? 'light') === 'light' ? 'selected' : '' ?>>
                    Светлая
                </option>
                <option value="dark" <?= ($user['theme'] ?? 'light') === 'dark' ? 'selected' : '' ?>>
                    Тёмная
                </option>
                <option value="colorblind" <?= ($user['theme'] ?? 'light') === 'colorblind' ? 'selected' : '' ?>>
                    Для дальтоников
                </option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="language">Язык интерфейса:</label>
            <select id="language" name="language" class="form-control">
                <option value="ru" <?= ($user['language'] ?? 'ru') === 'ru' ? 'selected' : '' ?>>
                    Русский
                </option>
                <option value="en" <?= ($user['language'] ?? 'ru') === 'en' ? 'selected' : '' ?>>
                    English
                </option>
                <option value="de" <?= ($user['language'] ?? 'ru') === 'de' ? 'selected' : '' ?>>
                    Deutsch
                </option>
            </select>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn">Сохранить настройки</button>
            <a href="/" class="btn btn-secondary">Отмена</a>
        </div>
    </form>
</div>

<div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px;">
    <h3>Информация о пользователе</h3>
    <table class="table">
        <tr>
            <td><strong>ID:</strong></td>
            <td><?= htmlspecialchars($user['id']) ?></td>
        </tr>
        <tr>
            <td><strong>Логин:</strong></td>
            <td><?= htmlspecialchars($user['login']) ?></td>
        </tr>
        <tr>
            <td><strong>Имя:</strong></td>
            <td><?= htmlspecialchars($user['name']) ?></td>
        </tr>
        <tr>
            <td><strong>Роль:</strong></td>
            <td><?= htmlspecialchars($user['role']) ?></td>
        </tr>
        <tr>
            <td><strong>Текущая тема:</strong></td>
            <td><?= htmlspecialchars($user['theme'] ?? 'light') ?></td>
        </tr>
        <tr>
            <td><strong>Текущий язык:</strong></td>
            <td><?= htmlspecialchars($user['language'] ?? 'ru') ?></td>
        </tr>
    </table>
</div>
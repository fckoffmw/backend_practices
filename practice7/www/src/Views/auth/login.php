<h1><?= htmlspecialchars($title) ?></h1>

<?php if (isset($error)): ?>
    <div class="alert alert-error">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<div style="max-width: 400px; margin: 0 auto;">
    <form method="POST" action="/login">
        <div class="form-group">
            <label for="login">Логин:</label>
            <input type="text" id="login" name="login" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn" style="width: 100%;">Войти</button>
        </div>
    </form>
    
    <div style="margin-top: 20px; padding: 15px; background: #e9ecef; border-radius: 4px;">
        <h4>Тестовые учетные записи:</h4>
        <ul style="margin: 10px 0; padding-left: 20px;">
            <li><strong>admin</strong> / password123 (Администратор)</li>
            <li><strong>user1</strong> / password123 (Пользователь)</li>
            <li><strong>user2</strong> / password123 (Пользователь)</li>
        </ul>
    </div>
</div>
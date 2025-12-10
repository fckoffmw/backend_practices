<h1><?= htmlspecialchars($title) ?></h1>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value"><?= htmlspecialchars($appName) ?></div>
        <div class="stat-label">Название приложения</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= htmlspecialchars($version) ?></div>
        <div class="stat-label">Версия</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">Clean Architecture</div>
        <div class="stat-label">Архитектурный подход</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">MVC Pattern</div>
        <div class="stat-label">Паттерн проектирования</div>
    </div>
</div>

<h2>Добро пожаловать в рефакторированное приложение!</h2>

<p>Это приложение было полностью переписано с использованием принципов Clean Architecture и паттерна MVC.</p>

<h3>Ключевые улучшения:</h3>

<ul>
    <li><strong>Clean Architecture</strong> - разделение на слои (Entities, Use Cases, Interface Adapters, Infrastructure)</li>
    <li><strong>MVC Pattern</strong> - разделение логики представления, контроллеров и моделей</li>
    <li><strong>Dependency Injection</strong> - внедрение зависимостей через DI контейнер</li>
    <li><strong>Repository Pattern</strong> - абстракция доступа к данным</li>
    <li><strong>Single Responsibility</strong> - каждый класс имеет одну ответственность</li>
    <li><strong>PSR-4 Autoloading</strong> - автозагрузка классов по стандарту</li>
</ul>

<h3>Доступные разделы:</h3>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 20px 0;">
    <div class="stat-card">
        <h4><a href="/users" style="color: #007bff; text-decoration: none;">Пользователи</a></h4>
        <p>Управление пользователями системы</p>
    </div>
    
    <div class="stat-card">
        <h4><a href="/statistics" style="color: #007bff; text-decoration: none;">Статистика</a></h4>
        <p>Графики и аналитика продаж</p>
    </div>
    
    <div class="stat-card">
        <h4><a href="/services" style="color: #007bff; text-decoration: none;">Сервисы</a></h4>
        <p>Дополнительные сервисы системы</p>
    </div>
    
    <?php if ($user): ?>
    <div class="stat-card">
        <h4><a href="/settings" style="color: #007bff; text-decoration: none;">Настройки</a></h4>
        <p>Персональные настройки</p>
    </div>
    <?php endif; ?>
</div>

<?php if (!$user): ?>
<div class="alert alert-info" style="background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb;">
    <strong>Совет:</strong> Войдите в систему, чтобы получить доступ ко всем функциям. 
    Используйте логин <code>admin</code> и пароль <code>password123</code>.
</div>
<?php endif; ?>

<h3>Архитектура приложения:</h3>

<div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;">
    <pre style="margin: 0; font-family: 'Courier New', monospace; font-size: 14px;">
<strong>Clean Architecture Layers:</strong>

┌─────────────────────────────────────────┐
│           Frameworks & Drivers          │  ← Infrastructure Layer
│  (Database, Redis, External APIs)      │
├─────────────────────────────────────────┤
│         Interface Adapters              │  ← MVC Layer
│  (Controllers, Models, Views)          │
├─────────────────────────────────────────┤
│            Use Cases                    │  ← Business Logic
│  (AuthenticateUser, GenerateStats)     │
├─────────────────────────────────────────┤
│             Entities                    │  ← Business Objects
│  (User, SalesStatistic)                │
└─────────────────────────────────────────┘
    </pre>
</div>
<?php
/**
 * Главная страница
 * Демонстрация согласования контента: тема, язык, логин
 */

$pageTitle = 'Главная — Practice 5';
require_once __DIR__ . '/header.php';
?>

<div class="card">
    <h2><?= $t['welcome'] ?>, <?= htmlspecialchars($login) ?>!</h2>
    <p>
        <?php if ($lang === 'ru'): ?>
            Это практическая работа №5, объединяющая функциональность практик 1-4 
            с добавлением новых возможностей:
        <?php elseif ($lang === 'en'): ?>
            This is Practice 5, combining functionality from practices 1-4 
            with new features:
        <?php else: ?>
            Dies ist die praktische Arbeit Nr. 5, die die Funktionalität der Praktiken 1-4 
            mit neuen Möglichkeiten kombiniert:
        <?php endif; ?>
    </p>
    <ul>
        <li>
            <?php if ($lang === 'ru'): ?>
                📦 <strong>Хранение сессий в Redis</strong> — ваши данные сессии хранятся в быстрой in-memory БД
            <?php elseif ($lang === 'en'): ?>
                📦 <strong>Session storage in Redis</strong> — your session data is stored in a fast in-memory DB
            <?php else: ?>
                📦 <strong>Sitzungsspeicherung in Redis</strong> — Ihre Sitzungsdaten werden in einer schnellen In-Memory-DB gespeichert
            <?php endif; ?>
        </li>
        <li>
            <?php if ($lang === 'ru'): ?>
                🎨 <strong>Согласование контента</strong> — индивидуальные настройки (тема: <?= $theme ?>, язык: <?= $lang ?>)
            <?php elseif ($lang === 'en'): ?>
                🎨 <strong>Content negotiation</strong> — personalized settings (theme: <?= $theme ?>, language: <?= $lang ?>)
            <?php else: ?>
                🎨 <strong>Inhaltsverhandlung</strong> — persönliche Einstellungen (Thema: <?= $theme ?>, Sprache: <?= $lang ?>)
            <?php endif; ?>
        </li>
        <li>
            <?php if ($lang === 'ru'): ?>
                📄 <strong>Загрузка PDF</strong> — загружайте и скачивайте PDF файлы
            <?php elseif ($lang === 'en'): ?>
                📄 <strong>PDF upload</strong> — upload and download PDF files
            <?php else: ?>
                📄 <strong>PDF-Upload</strong> — PDF-Dateien hochladen und herunterladen
            <?php endif; ?>
        </li>
    </ul>
</div>

<div class="card">
    <h2>
        <?php if ($lang === 'ru'): ?>
            Текущие настройки согласования контента
        <?php elseif ($lang === 'en'): ?>
            Current Content Negotiation Settings
        <?php else: ?>
            Aktuelle Einstellungen für Inhaltsverhandlung
        <?php endif; ?>
    </h2>
    <table>
        <tr>
            <th>
                <?php if ($lang === 'ru'): ?>Параметр<?php elseif ($lang === 'en'): ?>Parameter<?php else: ?>Parameter<?php endif; ?>
            </th>
            <th>
                <?php if ($lang === 'ru'): ?>Значение<?php elseif ($lang === 'en'): ?>Value<?php else: ?>Wert<?php endif; ?>
            </th>
            <th>
                <?php if ($lang === 'ru'): ?>Источник<?php elseif ($lang === 'en'): ?>Source<?php else: ?>Quelle<?php endif; ?>
            </th>
        </tr>
        <tr>
            <td><?= $t['username'] ?></td>
            <td><strong><?= htmlspecialchars($login) ?></strong></td>
            <td><?= isLoggedIn() ? 'Session (Redis)' : 'Cookie / Guest' ?></td>
        </tr>
        <tr>
            <td><?= $t['theme'] ?></td>
            <td><strong><?= $t[$theme] ?? $theme ?></strong></td>
            <td><?= isLoggedIn() ? 'Database + Session' : 'Cookie' ?></td>
        </tr>
        <tr>
            <td><?= $t['language'] ?></td>
            <td><strong><?= strtoupper($lang) ?></strong></td>
            <td><?= isLoggedIn() ? 'Database + Session' : 'Cookie' ?></td>
        </tr>
    </table>
    <p style="margin-top: 1rem;">
        <a href="/settings.php" class="btn btn-primary"><?= $t['settings'] ?></a>
    </p>
</div>

<div class="card">
    <h2><?= $t['services'] ?></h2>
    <div class="services-grid">
        <a href="/api/users.php" class="service-card">
            <h3>👥 <?= $t['users'] ?></h3>
            <p>REST API</p>
        </a>
        <a href="/api/orders.php" class="service-card">
            <h3>📦 <?= $t['orders'] ?></h3>
            <p>REST API</p>
        </a>
        <a href="/services/drawer/drawer.php?num=12345" class="service-card">
            <h3>🎨 <?= $t['drawer'] ?></h3>
            <p>SVG Generator</p>
        </a>
        <a href="/services/sort/sort.php" class="service-card">
            <h3>🔢 <?= $t['sorter'] ?></h3>
            <p>Selection Sort</p>
        </a>
        <a href="/pdf/index.php" class="service-card">
            <h3>📄 PDF</h3>
            <p><?= $t['upload_pdf'] ?></p>
        </a>
        <?php if (isLoggedIn() && isAdmin()): ?>
        <a href="/admin.php" class="service-card">
            <h3>⚙️ <?= $t['admin_panel'] ?></h3>
            <p>Admin Tools</p>
        </a>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>


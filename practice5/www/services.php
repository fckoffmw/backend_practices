<?php
/**
 * Страница со списком всех сервисов
 */

$pageTitle = 'Сервисы — Practice 5';
require_once __DIR__ . '/header.php';
?>

<div class="card">
    <h2><?= $t['services'] ?></h2>
    <p>
        <?php if ($lang === 'ru'): ?>
            Все сервисы из практических работ 1-4, объединённые в одном проекте.
        <?php elseif ($lang === 'en'): ?>
            All services from practices 1-4, combined in one project.
        <?php else: ?>
            Alle Dienste aus den Praktiken 1-4 in einem Projekt zusammengefasst.
        <?php endif; ?>
    </p>
</div>

<div class="grid">
    <div class="card">
        <h2>👥 <?= $t['users'] ?> API</h2>
        <p>
            <?php if ($lang === 'ru'): ?>
                REST API для управления пользователями (CRUD операции).
            <?php elseif ($lang === 'en'): ?>
                REST API for user management (CRUD operations).
            <?php else: ?>
                REST API für Benutzerverwaltung (CRUD-Operationen).
            <?php endif; ?>
        </p>
        <a href="/api/users.php" class="btn btn-primary">GET /api/users.php</a>
    </div>
    
    <div class="card">
        <h2>📦 <?= $t['orders'] ?> API</h2>
        <p>
            <?php if ($lang === 'ru'): ?>
                REST API для управления заказами (CRUD операции).
            <?php elseif ($lang === 'en'): ?>
                REST API for order management (CRUD operations).
            <?php else: ?>
                REST API für Bestellungsverwaltung (CRUD-Operationen).
            <?php endif; ?>
        </p>
        <a href="/api/orders.php" class="btn btn-primary">GET /api/orders.php</a>
    </div>
    
    <div class="card">
        <h2>🎨 <?= $t['drawer'] ?></h2>
        <p>
            <?php if ($lang === 'ru'): ?>
                Генератор SVG фигур на основе числового параметра.
            <?php elseif ($lang === 'en'): ?>
                SVG shape generator based on numeric parameter.
            <?php else: ?>
                SVG-Formgenerator basierend auf numerischem Parameter.
            <?php endif; ?>
        </p>
        <div style="margin: 1rem 0;">
            <img src="/services/drawer/drawer.php?num=12345" alt="SVG Example" style="max-width: 200px;">
        </div>
        <a href="/services/drawer/drawer.php?num=<?= rand(1000, 100000) ?>" class="btn btn-primary">
            <?php if ($lang === 'ru'): ?>Случайная фигура<?php else: ?>Random Shape<?php endif; ?>
        </a>
    </div>
    
    <div class="card">
        <h2>🔢 <?= $t['sorter'] ?></h2>
        <p>
            <?php if ($lang === 'ru'): ?>
                Сортировка массива методом выбора (Selection Sort).
            <?php elseif ($lang === 'en'): ?>
                Array sorting using Selection Sort algorithm.
            <?php else: ?>
                Array-Sortierung mit Selection Sort Algorithmus.
            <?php endif; ?>
        </p>
        <a href="/services/sort/sort.php" class="btn btn-primary">
            <?php if ($lang === 'ru'): ?>Открыть<?php elseif ($lang === 'en'): ?>Open<?php else: ?>Öffnen<?php endif; ?>
        </a>
    </div>
    
    <div class="card">
        <h2>⚙️ <?= $t['admin_panel'] ?></h2>
        <p>
            <?php if ($lang === 'ru'): ?>
                Выполнение системных команд (whoami, ps, ls и др.).
            <?php elseif ($lang === 'en'): ?>
                Execute system commands (whoami, ps, ls, etc.).
            <?php else: ?>
                Systembefehle ausführen (whoami, ps, ls usw.).
            <?php endif; ?>
        </p>
        <a href="/services/admin/admin.php" class="btn btn-primary">
            <?php if ($lang === 'ru'): ?>Открыть<?php elseif ($lang === 'en'): ?>Open<?php else: ?>Öffnen<?php endif; ?>
        </a>
    </div>
    
    <div class="card">
        <h2>📄 PDF Files</h2>
        <p>
            <?php if ($lang === 'ru'): ?>
                Загрузка и скачивание PDF файлов. Хранение в MySQL.
            <?php elseif ($lang === 'en'): ?>
                Upload and download PDF files. Stored in MySQL.
            <?php else: ?>
                PDF-Dateien hochladen und herunterladen. Gespeichert in MySQL.
            <?php endif; ?>
        </p>
        <a href="/pdf/index.php" class="btn btn-primary">
            <?php if ($lang === 'ru'): ?>Открыть<?php elseif ($lang === 'en'): ?>Open<?php else: ?>Öffnen<?php endif; ?>
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>


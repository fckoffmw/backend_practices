<?php
/**
 * Сервис сортировки массивов
 * Из Practice 2
 */

require_once __DIR__ . '/sort_lib.php';
require_once __DIR__ . '/../../config.php';

$userSettings = getUserSettings();
$theme = $userSettings['theme'];
$lang = $userSettings['language'];
$t = getTranslations($lang);

$input = $_GET['arr'] ?? '';
$orig = $sorted = [];

if ($input !== '') {
    $orig = parse_number_list($input);
    if ($orig) {
        $sorted = selection_sort($orig);
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $t['sorter'] ?> — Practice 5</title>
    <link rel="stylesheet" href="/styles.php?theme=<?= urlencode($theme) ?>">
</head>
<body>
    <header class="header">
        <h1><?= $t['sorter'] ?></h1>
        <nav>
            <a href="/index.php"><?= $t['home'] ?></a>
            <a href="/services.php"><?= $t['services'] ?></a>
        </nav>
    </header>
    <main class="container">
        <div class="card">
            <h2>Selection Sort</h2>
            <form>
                <div class="form-group">
                    <label>
                        <?php if ($lang === 'ru'): ?>
                            Введите массив (через запятую):
                        <?php elseif ($lang === 'en'): ?>
                            Enter array (comma-separated):
                        <?php else: ?>
                            Array eingeben (durch Komma getrennt):
                        <?php endif; ?>
                    </label>
                    <input name="arr" size="50" value="<?= htmlspecialchars($input) ?>" 
                           placeholder="5, 3, 8, 1, 9, 2">
                </div>
                <button class="btn btn-primary">
                    <?php if ($lang === 'ru'): ?>Сортировать<?php elseif ($lang === 'en'): ?>Sort<?php else: ?>Sortieren<?php endif; ?>
                </button>
            </form>
        </div>

        <?php if ($orig): ?>
        <div class="card">
            <h2>
                <?php if ($lang === 'ru'): ?>Исходный массив<?php elseif ($lang === 'en'): ?>Original array<?php else: ?>Ursprüngliches Array<?php endif; ?>
            </h2>
            <pre style="font-size: 1.2rem;">[<?= implode(', ', $orig) ?>]</pre>
        </div>
        
        <div class="card">
            <h2>
                <?php if ($lang === 'ru'): ?>Отсортированный массив<?php elseif ($lang === 'en'): ?>Sorted array<?php else: ?>Sortiertes Array<?php endif; ?>
            </h2>
            <pre style="font-size: 1.2rem; color: var(--success);">[<?= implode(', ', $sorted) ?>]</pre>
        </div>
        <?php endif; ?>
    </main>
</body>
</html>


<?php
/**
 * Сортировка массивов
 * Из Practice 2
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/sort_lib.php';
require_once __DIR__ . '/../../header.php';

$input = $_POST['numbers'] ?? '';
$sorted = [];
$original = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $input !== '') {
    $original = parse_number_list($input);
    $sorted = selection_sort($original);
}
?>

<div class="card">
    <h1>Array Sorter (Selection Sort)</h1>
    <p>Enter comma-separated numbers to sort them.</p>
    
    <form method="POST">
        <label>Numbers (comma-separated):</label>
        <input type="text" name="numbers" value="<?= htmlspecialchars($input) ?>" 
               placeholder="5, 2, 8, 1, 9" required>
        <button type="submit" class="btn">Sort</button>
    </form>
    
    <?php if (!empty($original)): ?>
        <h3>Results:</h3>
        <p><strong>Original:</strong> <?= implode(', ', $original) ?></p>
        <p><strong>Sorted:</strong> <?= implode(', ', $sorted) ?></p>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../footer.php'; ?>

<?php
require_once __DIR__ . '/sort_lib.php';

$input = $_GET['arr'] ?? '';
$orig = $sorted = [];

if ($input !== '') {
    $orig = parse_number_list($input);
    if ($orig) {
        $sorted = selection_sort($orig);
    } else {
        echo "Ошибка: введите корректный массив чисел";
    }
}
?>

<form>
  <label>Введите массив (через запятую): <input name="arr" size="50" value="<?=htmlspecialchars($input)?>"></label>
  <button>Сортировать</button>
</form>

<?php if ($orig): ?>
<h3>Исходный массив:</h3>
<pre><?=implode(', ', $orig)?></pre>
<h3>Отсортированный массив:</h3>
<pre><?=implode(', ', $sorted)?></pre>
<?php endif; ?>

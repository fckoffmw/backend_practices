<?php
require_once __DIR__ . '/admin_lib.php';

$cmd = $_GET['cmd'] ?? null;
?>

<h2>Admin Panel</h2>
<ul>
<?php foreach (allowed_commands() as $k => $c): ?>
  <li><a href="?cmd=<?=$k?>"><?=$k?></a></li>
<?php endforeach; ?>
</ul>

<?php if ($cmd): ?>
<h3>Результат команды "<?=$cmd?>"</h3>
<pre><?=htmlspecialchars(run_command($cmd))?></pre>
<?php endif; ?>

<?php
require_once 'config.php';
require_once 'header.php';
?>

<div class="card">
    <h1><?= $t['services'] ?? 'Services' ?></h1>
    <p>Additional services from previous practices.</p>
</div>

<div class="card">
    <h2>SVG Drawer (Practice 2)</h2>
    <p>Generate SVG shapes from numbers using bitwise operations.</p>
    <p><strong>Example:</strong> <a href="services/drawer/drawer.php?num=12345" target="_blank">drawer.php?num=12345</a></p>
    
    <form action="services/drawer/drawer.php" method="GET" target="_blank">
        <label>Enter number:</label>
        <input type="number" name="num" value="12345" min="0" required>
        <button type="submit" class="btn">Generate SVG</button>
    </form>
</div>

<div class="card">
    <h2>Array Sorter (Practice 2)</h2>
    <p>Sort arrays using selection sort algorithm.</p>
    <p><a href="services/sort/sort.php" class="btn">Open Sorter</a></p>
</div>

<div class="card">
    <h2>Admin Commands (Practice 2)</h2>
    <p>Execute system commands (admin only).</p>
    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
        <p><a href="services/admin/admin.php" class="btn">Open Admin Panel</a></p>
    <?php else: ?>
        <p style="color: #999;">Administrator privileges required.</p>
    <?php endif; ?>
</div>

<?php require_once 'footer.php'; ?>

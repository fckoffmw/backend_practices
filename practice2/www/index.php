cat > www/index.php <<'EOF'
<?php
?><!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>Practice 2 — сервисы</title>
  <link rel="stylesheet" href="/style.css" type="text/css"/>
  <style>
    .services { text-align:center; margin:20px 0; }
    .services a { display:inline-block; margin:0 10px; padding:8px 12px; background:#f0f0f0; border-radius:6px; text-decoration:none; color:#000; }
    .hint { text-align:center; color:#666; font-size:90%; }
  </style>
</head>
<body>
  <h1>Practice 2 — сервисы</h1>
  <div class="services">
    <a href="/services/drawer/drawer.php">Drawer (SVG)</a>
    <a href="/services/sort/sort.php">Sorter (Merge)</a>
    <a href="/services/admin/admin.php">AdminPanel</a>
    <a href="/users.php">Users (из Практики 1)</a>
  </div>
  <div class="hint">Все сервисы тестируются локально: http://localhost:8080</div>
</body>
</html>
EOF

<?php
require_once __DIR__ . '/drawer_lib.php';

$num = $_GET['num'] ?? null;

if ($num === null || !is_numeric($num)) {
    die("Ошибка: параметр num обязателен и должен быть числом.");
}

$num = (int)$num;
$info = decode_num_to_shape($num);
$palette = color_palette();
$color = $palette[$info['colorIndex'] % count($palette)];

$w = $info['width'] * 4;  
$h = $info['height'] * 4;  
$svgW = $w + 80;          
$svgH = $h + 80;

$shapeHtml = '';
switch ($info['shape']) {
    case 0: // rect
        $shapeHtml = "<rect x='40' y='40' width='$w' height='$h' fill='$color' />";
        break;
    case 1: // circle
        $r = min($w, $h) / 2;
        $shapeHtml = "<circle cx='".($svgW/2)."' cy='".($svgH/2)."' r='$r' fill='$color' />";
        break;
    case 2: // ellipse
        $shapeHtml = "<ellipse cx='".($svgW/2)."' cy='".($svgH/2)."' rx='".($w/2)."' ry='".($h/2)."' fill='$color' />";
        break;
    case 3: // triangle
        $shapeHtml = "<polygon points='".($svgW/2).",40 40,".($svgH-40)." ".($svgW-40).",".($svgH-40)."' fill='$color' />";
        break;
}

header("Content-Type: image/svg+xml");
echo "<svg xmlns='http://www.w3.org/2000/svg' width='$svgW' height='$svgH'>$shapeHtml</svg>";

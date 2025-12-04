<?php
/**
 * SVG генератор фигур
 * Из Practice 2
 */

require_once __DIR__ . '/drawer_lib.php';

$num = isset($_GET['num']) ? (int)$_GET['num'] : 0;
$data = decode_num_to_shape($num);
$colors = color_palette();

$shape = $data['shape'];
$color = $colors[$data['colorIndex']];
$w = $data['width'];
$h = $data['height'];

header('Content-Type: image/svg+xml');

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<svg xmlns="http://www.w3.org/2000/svg" width="' . $w . '" height="' . $h . '">';

switch ($shape) {
    case 0: // Rectangle
        echo '<rect x="0" y="0" width="' . $w . '" height="' . $h . '" fill="' . $color . '"/>';
        break;
    case 1: // Circle
        $r = min($w, $h) / 2;
        $cx = $w / 2;
        $cy = $h / 2;
        echo '<circle cx="' . $cx . '" cy="' . $cy . '" r="' . $r . '" fill="' . $color . '"/>';
        break;
    case 2: // Triangle
        $points = "0,$h $w,$h " . ($w/2) . ",0";
        echo '<polygon points="' . $points . '" fill="' . $color . '"/>';
        break;
    case 3: // Ellipse
        $rx = $w / 2;
        $ry = $h / 2;
        $cx = $w / 2;
        $cy = $h / 2;
        echo '<ellipse cx="' . $cx . '" cy="' . $cy . '" rx="' . $rx . '" ry="' . $ry . '" fill="' . $color . '"/>';
        break;
}

echo '</svg>';

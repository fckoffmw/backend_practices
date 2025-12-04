<?php
/**
 * Библиотека для генерации SVG фигур
 * Из Practice 2
 */

function decode_num_to_shape(int $num): array {
    $shape = $num & 0b11;
    $colorIndex = ($num >> 2) & 0b1111;
    $widthField = ($num >> 6) & 0b111111;
    $heightField = ($num >> 12) & 0b111111;

    $width = 20 + $widthField * 5;
    $height = 20 + $heightField * 5;

    return [
        'shape' => $shape,
        'colorIndex' => $colorIndex,
        'width' => $width,
        'height' => $height
    ];
}

function color_palette(): array {
    return [
        '#000000','#7f7f7f','#ff0000','#00ff00','#0000ff',
        '#ffff00','#ff00ff','#00ffff','#800000','#008000',
        '#000080','#808000','#800080','#008080','#c0c0c0','#ffa500'
    ];
}

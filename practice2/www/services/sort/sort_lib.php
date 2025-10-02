<?php
function parse_number_list(string $s): array {
    $parts = array_map('trim', explode(',', $s));
    $res = [];
    foreach ($parts as $p) {
        if ($p !== '' && is_numeric($p)) {
            $res[] = (strpos($p, '.') !== false) ? (float)$p : (int)$p;
        }
    }
    return $res;
}

function selection_sort(array $arr): array {
    $n = count($arr);
    for ($i = 0; $i < $n - 1; $i++) {
        $min = $i;
        for ($j = $i + 1; $j < $n; $j++) {
            if ($arr[$j] < $arr[$min]) {
                $min = $j;
            }
        }
        if ($min !== $i) {
            [$arr[$i], $arr[$min]] = [$arr[$min], $arr[$i]];
        }
    }
    return $arr;
}

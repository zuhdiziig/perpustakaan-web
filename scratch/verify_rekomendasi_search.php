<?php

function lum($hex)
{
    $hex = str_replace('#', '', $hex);
    $r = hexdec(substr($hex, 0, 2)) / 255;
    $g = hexdec(substr($hex, 2, 2)) / 255;
    $b = hexdec(substr($hex, 4, 2)) / 255;
    $r = ($r <= 0.03928) ? $r / 12.92 : pow(($r + 0.055) / 1.055, 2.4);
    $g = ($g <= 0.03928) ? $g / 12.92 : pow(($g + 0.055) / 1.055, 2.4);
    $b = ($b <= 0.03928) ? $b / 12.92 : pow(($b + 0.055) / 1.055, 2.4);

    return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
}

function cr($c1, $c2)
{
    $l1 = lum($c1);
    $l2 = lum($c2);

    return ($l1 > $l2) ? ($l1 + 0.05) / ($l2 + 0.05) : ($l2 + 0.05) / ($l1 + 0.05);
}

$pairs = [
    'Rekomendasi Judul (#f6f0df on #14243b)' => ['#f6f0df', '#14243b'],
    'Rekomendasi Judul Hover (#fcd34d on #14243b)' => ['#fcd34d', '#14243b'],
    'Rekomendasi Author (#9fb3c8 on #14243b)' => ['#9fb3c8', '#14243b'],
    'Rekomendasi Meta (#cbd5e1 on #14243b)' => ['#cbd5e1', '#14243b'],
    'Rekomendasi Rating (#fcd34d on #14243b)' => ['#fcd34d', '#14243b'],
    'Rekomendasi Badge (#4ade80 on #14243b)' => ['#4ade80', '#14243b'],
    'Btn Pinjam (#ffffff on #245b8f)' => ['#ffffff', '#245b8f'],
    'Btn Pinjam Hover (#f6f0df on #1b456e)' => ['#f6f0df', '#1b456e'],
    'Search Label (#f6f0df on #0b1726)' => ['#f6f0df', '#0b1726'],
    'Search Input Text (#f6f0df on #0d1b2a)' => ['#f6f0df', '#0d1b2a'],
    'Search Input Placeholder (#8da4be on #0d1b2a)' => ['#8da4be', '#0d1b2a'],
    'Search Icon (#f59e0b on #0d1b2a)' => ['#f59e0b', '#0d1b2a'],
    'Search Btn (#ffffff on #245b8f)' => ['#ffffff', '#245b8f'],
    'Search Btn Hover (#f6f0df on #1b456e)' => ['#f6f0df', '#1b456e'],
];

foreach ($pairs as $label => $colors) {
    $ratio = cr($colors[0], $colors[1]);
    $pass = $ratio >= 4.5 ? 'PASS' : ($ratio >= 3.0 ? 'PASS (Large/UI)' : 'FAIL');
    echo sprintf('%-50s: %5.2f:1 [%s]'.PHP_EOL, $label, $ratio, $pass);
}

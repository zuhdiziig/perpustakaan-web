<?php

function getLuminance($hex)
{
    $hex = ltrim($hex, '#');
    if (strlen($hex) == 3) {
        $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
    }
    $r = hexdec(substr($hex, 0, 2)) / 255;
    $g = hexdec(substr($hex, 2, 2)) / 255;
    $b = hexdec(substr($hex, 4, 2)) / 255;

    $r = ($r <= 0.03928) ? $r / 12.92 : pow(($r + 0.055) / 1.055, 2.4);
    $g = ($g <= 0.03928) ? $g / 12.92 : pow(($g + 0.055) / 1.055, 2.4);
    $b = ($b <= 0.03928) ? $b / 12.92 : pow(($b + 0.055) / 1.055, 2.4);

    return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
}

function getContrastRatio($hex1, $hex2)
{
    $l1 = getLuminance($hex1);
    $l2 = getLuminance($hex2);
    $bright = max($l1, $l2);
    $dark = min($l1, $l2);

    return ($bright + 0.05) / ($dark + 0.05);
}

$colors = [
    'pale_gold' => '#e9b949',
    'bright_gold' => '#f59e0b',
    'golden_amber' => '#fbbf24',
    'amber_bright' => '#fcd34d',
    'dark_amber_text' => '#92400e', // Deep amber for light backgrounds
    'warm_ivory' => '#fbf8ee',      // Crisp warm ivory text
    'soft_starry_white' => '#e2e8f0',
    'muted_slate' => '#9fb3c8',
    'bg_navy_page' => '#0d1b2a',
    'bg_navy_card' => '#132238',
    'bg_navy_elevated' => '#162c47',
    'bg_cobalt_btn' => '#245b8f',
    'bg_cobalt_hover' => '#1b456e',
    'badge_gold_bg' => '#fef3c7',    // Light amber badge bg
];

echo "CONTRAST RATIOS:\n";
foreach (['bg_navy_page', 'bg_navy_card', 'bg_navy_elevated', 'bg_cobalt_btn', 'bg_cobalt_hover', 'badge_gold_bg'] as $bg) {
    foreach (['pale_gold', 'bright_gold', 'golden_amber', 'amber_bright', 'warm_ivory', 'soft_starry_white', 'muted_slate', 'dark_amber_text'] as $fg) {
        $ratio = getContrastRatio($colors[$fg], $colors[$bg]);
        $passAA = $ratio >= 4.5 ? 'PASS AA' : ($ratio >= 3.0 ? 'PASS Large' : 'FAIL');
        echo sprintf("%-18s on %-18s = %5.2f:1 [%s]\n", $fg, $bg, $ratio, $passAA);
    }
    echo "--------------------------------------------------------\n";
}

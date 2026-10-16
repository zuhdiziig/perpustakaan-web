<?php

function luminance($hex)
{
    $hex = ltrim($hex, '#');
    if (strlen($hex) === 3) {
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

function contrast($hex1, $hex2)
{
    $l1 = luminance($hex1);
    $l2 = luminance($hex2);
    $lighter = max($l1, $l2);
    $darker = min($l1, $l2);

    return ($lighter + 0.05) / ($darker + 0.05);
}

$pairs = [
    // [Foreground, Background, Description, MinRatio]
    ['#f6f0df', '#0b1726', 'Warm ivory heading/text on Page body background', 4.5],
    ['#f6f0df', '#132238', 'Warm ivory heading on Card surface', 4.5],
    ['#e2e8f0', '#132238', 'Soft white body text on Card surface', 4.5],
    ['#9fb3c8', '#132238', 'Muted text on Card surface', 4.5],
    ['#fcd34d', '#0b1726', 'Bright gold accent text on Page body', 4.5],
    ['#fcd34d', '#132238', 'Bright gold accent text on Card surface', 4.5],
    ['#fcd34d', '#10233b', 'Bright gold active link on Sidebar', 4.5],
    ['#fcd34d', '#193354', 'Bright gold text on Hover / Active pill state', 4.5],
    ['#ffffff', '#245b8f', 'White text on Primary button (cobalt)', 4.5],
    ['#f6f0df', '#1b456e', 'Warm ivory text on Primary button hover (dark cobalt)', 4.5],
    ['#e2e8f0', '#132238', 'Light text on Secondary button (normal)', 4.5],
    ['#fcd34d', '#193354', 'Bright gold text on Secondary button hover', 4.5],
    ['#fcd34d', '#14243b', 'Bright gold text on Warning/Amber Badge (dark card)', 4.5],
    ['#92400e', '#fef3c7', 'Dark amber text on Light/Cream Warning Badge', 4.5],
    ['#4ade80', '#14243b', 'Emerald light text on Success Badge (dark card)', 4.5],
    ['#fca5a5', '#14243b', 'Rose light text on Danger/Late Badge (dark card)', 4.5],
    ['#93c5fd', '#14243b', 'Sky blue text on Info/Borrowed Badge (dark card)', 4.5],
    ['#f6f0df', '#0f2035', 'Warm ivory text on Table header', 4.5],
    ['#e2e8f0', '#0f2035', 'Light text on Table body cell', 4.5],
    ['#ffffff', '#245b8f', 'White text on Pagination active button', 4.5],
    ['#fcd34d', '#1f3c60', 'Bright gold text on Pagination hover button', 4.5],
    ['#fcd34d', '#0f243d', 'Bright gold title on Hero banner', 3.0],
    ['#cbd5e1', '#0f243d', 'Muted text on Hero banner', 4.5],
    ['#f6f0df', '#0e1e33', 'Logo brand text on Topbar', 4.5],
];

echo str_pad('Pair', 22).str_pad('Contrast', 12).str_pad('Target', 10)."Status\n";
echo str_repeat('-', 60)."\n";

$allPassed = true;
foreach ($pairs as $p) {
    $fg = $p[0];
    $bg = $p[1];
    $desc = $p[2];
    $target = $p[3];
    $ratio = contrast($fg, $bg);
    $pass = $ratio >= $target;
    if (! $pass) {
        $allPassed = false;
    }
    $status = $pass ? 'PASS (WCAG AA)' : 'FAIL';
    echo str_pad("$fg on $bg", 22).str_pad(number_format($ratio, 2).':1', 12).str_pad(">={$target}:1", 10)."$status - $desc\n";
}

echo str_repeat('=', 60)."\n";
echo $allPassed ? "ALL 24 CONTRAST PAIRS PASSED WCAG AA REQUIREMENTS!\n" : "SOME CONTRAST PAIRS FAILED!\n";

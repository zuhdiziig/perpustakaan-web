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
    $lum1 = luminance($hex1);
    $lum2 = luminance($hex2);
    $brightest = max($lum1, $lum2);
    $darkest = min($lum1, $lum2);

    return ($brightest + 0.05) / ($darkest + 0.05);
}

$pairs = [
    // [Foreground, Background, Description, Target Context]
    ['#F6F0DF', '#0B1726', 'Warm Ivory on Deep Sky / Page Background', 'Primary text'],
    ['#F6F0DF', '#102A43', 'Warm Ivory on Deep Navy Surface', 'Card headings & titles'],
    ['#F6F0DF', '#132238', 'Warm Ivory on Card Background', 'Headings & primary content'],
    ['#E2E8F0', '#132238', 'Light Slate on Card Background', 'Body text'],
    ['#9FB3C8', '#132238', 'Muted Sky Blue on Card Background', 'Muted / secondary text'],
    ['#FCD34D', '#0B1726', 'Star Gold (High-contrast) on Page Background', 'Accents & highlights'],
    ['#FCD34D', '#102A43', 'Star Gold (High-contrast) on Deep Navy', 'Warning alerts, active tabs'],
    ['#FCD34D', '#132238', 'Star Gold (High-contrast) on Card Background', 'Category badges, icons'],
    ['#FFFFFF', '#245B8F', 'White on Cobalt Blue Button', 'Primary action button'],
    ['#F6F0DF', '#1B456E', 'Warm Ivory on Cobalt Hover', 'Primary action button hover'],
    ['#D8E2EC', '#132238', 'Soft Cream on Secondary Button Surface', 'Secondary button default'],
    ['#FCD34D', '#193354', 'Star Gold on Secondary Button Hover', 'Secondary button hover'],
    ['#172B4D', '#F6F0DF', 'Dark Ink on Warm Cream Card', 'Inverted deliberate card surface'],
    ['#92400E', '#F6F0DF', 'Deep Bronze Amber on Warm Cream', 'Gold accent on light surface'],
    ['#D97706', '#FFFFFF', 'Dark Amber on White Card', 'Theme icon box in profile'],
    ['#EF4444', '#132238', 'Red Alert on Dark Card', 'Cancel / Danger buttons'],
    ['#4ADE80', '#132238', 'Green Success on Dark Card', 'Available badges & success toast'],
];

echo "=== WCAG CONTRAST AUDIT RESULTS ===\n";
printf("%-10s %-10s %-8s %-10s %-45s\n", 'Text', 'BG', 'Ratio', 'WCAG AA', 'Element Context');
echo str_repeat('-', 85)."\n";

foreach ($pairs as [$fg, $bg, $desc, $ctx]) {
    $ratio = contrast($fg, $bg);
    $pass = $ratio >= 4.5 ? 'PASS (AAA/AA)' : ($ratio >= 3.0 ? 'PASS (Large)' : 'FAIL');
    printf("%-10s %-10s %-7.2f:1 %-10s %-45s\n", $fg, $bg, $ratio, $pass, $desc);
}

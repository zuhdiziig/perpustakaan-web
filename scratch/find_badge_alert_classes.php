<?php

$files = glob('resources/views/**/*.blade.php');
$badgeClasses = [];
$alertClasses = [];

foreach ($files as $f) {
    $content = file_get_contents($f);
    if (preg_match_all('/class="([^"]*badge[^"]*)"/i', $content, $m)) {
        foreach ($m[1] as $c) {
            foreach (explode(' ', $c) as $cls) {
                if (strpos($cls, 'badge') !== false) {
                    $badgeClasses[$cls] = ($badgeClasses[$cls] ?? 0) + 1;
                }
            }
        }
    }
    if (preg_match_all('/class="([^"]*alert[^"]*)"/i', $content, $m)) {
        foreach ($m[1] as $c) {
            foreach (explode(' ', $c) as $cls) {
                if (strpos($cls, 'alert') !== false) {
                    $alertClasses[$cls] = ($alertClasses[$cls] ?? 0) + 1;
                }
            }
        }
    }
}

echo "BADGE CLASSES:\n";
print_r($badgeClasses);
echo "ALERT CLASSES:\n";
print_r($alertClasses);

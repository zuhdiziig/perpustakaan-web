<?php

$targetFiles = [
    'resources/views/buku/index.blade.php',
    'resources/views/katalog/index.blade.php',
    'resources/views/pengembalian/member.blade.php',
    'resources/views/profile/edit.blade.php',
    'resources/views/sirkulasi/index.blade.php',
    'resources/views/barcode/scan.blade.php',
    'resources/views/denda/index.blade.php',
    'resources/views/member/index.blade.php',
    'resources/views/dashboard/admin.blade.php',
    'resources/views/dashboard/member.blade.php',
    'resources/views/dashboard/petugas.blade.php',
    'resources/views/layouts/partials/member_universal_search.blade.php',
    'resources/views/layouts/partials/member_booking_popup.blade.php',
];

foreach ($targetFiles as $file) {
    if (! file_exists($file)) {
        continue;
    }
    $content = file_get_contents($file);
    $lines = explode("\n", $content);
    echo "=== FILE: $file ===".PHP_EOL;
    foreach ($lines as $idx => $line) {
        $lineNum = $idx + 1;
        if (preg_match('/(background|background-color)\s*:\s*(#fff\b|#ffffff\b|white\b)/i', $line)) {
            echo sprintf("  Line %4d: %s\n", $lineNum, trim($line));
        }
    }
    echo PHP_EOL;
}

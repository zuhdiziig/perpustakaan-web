<?php

$files = [
    'resources/views/buku/index.blade.php',
    'resources/views/barcode/scan.blade.php',
    'resources/views/dashboard/member.blade.php',
    'resources/views/dashboard/admin.blade.php',
    'resources/views/dashboard/petugas.blade.php',
    'resources/views/petugas/index.blade.php',
    'resources/views/panduan_peminjaman.blade.php',
    'resources/views/qr/cetak_buku.blade.php',
    'resources/views/riwayat/index.blade.php',
    'resources/views/profile/edit.blade.php',
    'resources/views/layouts/partials/member_universal_search.blade.php',
    'resources/views/layouts/partials/member_booking_popup.blade.php',
    'resources/views/layouts/partials/member_feedback_toasts.blade.php',
    'resources/views/layouts/partials/member_realtime_notification.blade.php',
    'resources/views/layouts/partials/sidebar_styles.blade.php',
];

$failed = false;
foreach ($files as $f) {
    $full = __DIR__.'/../'.$f;
    $output = [];
    $returnVar = 0;
    exec('php -l '.escapeshellarg($full), $output, $returnVar);
    if ($returnVar !== 0) {
        echo "Error in $f:\n".implode("\n", $output)."\n";
        $failed = true;
    } else {
        echo "OK: $f\n";
    }
}

if (! $failed) {
    echo "\n=== ALL CHECKED FILES PASSED PHP SYNTAX CHECK ===\n";
} else {
    exit(1);
}

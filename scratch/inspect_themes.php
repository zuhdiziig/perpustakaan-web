<?php

$content = file_get_contents('resources/views/layouts/partials/sidebar_styles.blade.php');
preg_match_all('/\[data-theme=["\']([a-zA-Z0-9_-]+)["\']\]\s*\{([^}]+)\}/', $content, $matches, PREG_SET_ORDER);
foreach ($matches as $m) {
    echo 'THEME: '.$m[1].PHP_EOL;
    $vars = explode(';', $m[2]);
    foreach ($vars as $v) {
        $v = trim($v);
        if (str_starts_with($v, '--')) {
            echo '  '.$v.PHP_EOL;
        }
    }
}

<?php

$file = 'resources/views/layouts/partials/sidebar_styles.blade.php';
$content = file_get_contents($file);

preg_match_all('/\[data-theme="van-gogh"\][^{]*\{[^}]*\}/s', $content, $matches);
foreach ($matches[0] as $block) {
    if (preg_match('/color:\s*([^;!]+)/i', $block, $c)) {
        $val = trim($c[1]);
        if (preg_match('/e9b949|f59e0b|fbbf24|fcd34d|yellow|amber|gold/i', $val)) {
            // Check selector
            preg_match('/^([^{]+)\{/s', $block, $sel);
            echo "SELECTOR:\n".trim($sel[1])."\nCOLOR: ".$val."\n---\n";
        }
    }
}

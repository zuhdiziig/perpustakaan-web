<?php

function getBladeFiles($dir)
{
    $files = [];
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        $path = $dir.'/'.$item;
        if (is_dir($path)) {
            $files = array_merge($files, getBladeFiles($path));
        } elseif (str_ends_with($path, '.blade.php')) {
            $files[] = $path;
        }
    }

    return $files;
}

$bladeFiles = getBladeFiles('resources/views');
$inlineBg = [];

foreach ($bladeFiles as $f) {
    $relPath = str_replace('resources/views/', '', $f);
    $content = file_get_contents($f);
    $lines = explode("\n", $content);

    foreach ($lines as $idx => $line) {
        $lineNum = $idx + 1;
        // Check for style="..." containing background: #fff or background: white or background-color: #fff
        if (preg_match('/style=[\'"][^\'"]*background(-color)?\s*:\s*(#fff\b|#ffffff\b|white\b)[^\'"]*[\'"]/i', $line, $m)) {
            // Ignore mockups in profile settings
            if (! str_contains($line, 'theme-swatch') && ! str_contains($line, 'mockup')) {
                $inlineBg[] = [
                    'file' => $relPath,
                    'line' => $lineNum,
                    'match' => trim($line),
                ];
            }
        }
    }
}

echo '=== INLINE STYLE HARDCODED WHITE BACKGROUNDS ('.count($inlineBg).') ==='.PHP_EOL;
foreach ($inlineBg as $item) {
    echo sprintf("[%s:%d] %s\n", $item['file'], $item['line'], substr($item['match'], 0, 120));
}

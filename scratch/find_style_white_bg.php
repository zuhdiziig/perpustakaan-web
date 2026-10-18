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
$cssRules = [];

foreach ($bladeFiles as $f) {
    if (str_contains($f, 'sidebar_styles.blade.php')) {
        continue;
    }
    $relPath = str_replace('resources/views/', '', $f);
    $content = file_get_contents($f);

    // Find all <style> blocks
    if (preg_match_all('/<style\b[^>]*>(.*?)<\/style>/is', $content, $styleMatches)) {
        foreach ($styleMatches[1] as $block) {
            $lines = explode("\n", $block);
            $currentSelector = '';
            foreach ($lines as $idx => $line) {
                $trimmed = trim($line);
                if (str_contains($trimmed, '{')) {
                    $currentSelector = trim(explode('{', $trimmed)[0]);
                }
                if (preg_match('/(background|background-color)\s*:\s*(#fff\b|#ffffff\b|white\b)/i', $trimmed)) {
                    // Ignore if in [data-theme="light"]
                    if (! str_contains($currentSelector, 'light') && ! str_contains($currentSelector, 'swatch')) {
                        $cssRules[] = [
                            'file' => $relPath,
                            'selector' => $currentSelector,
                            'rule' => $trimmed,
                        ];
                    }
                }
            }
        }
    }
}

echo '=== CSS RULES IN <style> TAGS WITH HARDCODED WHITE BACKGROUNDS ('.count($cssRules).') ==='.PHP_EOL;
$bySelector = [];
foreach ($cssRules as $item) {
    $key = $item['file'].' -> '.$item['selector'];
    $bySelector[$key] = ($bySelector[$key] ?? 0) + 1;
}
foreach ($bySelector as $sel => $cnt) {
    echo "  $sel ($cnt)\n";
}

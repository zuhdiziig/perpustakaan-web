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

$hardcodedBg = [];
$hardcodedYellow = [];
$emptyStates = [];
$inlineStyles = [];

foreach ($bladeFiles as $f) {
    $relPath = str_replace('resources/views/', '', $f);
    $content = file_get_contents($f);
    $lines = explode("\n", $content);

    foreach ($lines as $idx => $line) {
        $lineNum = $idx + 1;
        $trimmed = trim($line);

        // 1. Hardcoded white backgrounds in inline styles or internal CSS
        if (preg_match('/(background|background-color)\s*:\s*(#fff\b|#ffffff\b|white\b)/i', $line)) {
            // Ignore if in pure light theme mockup or legacy theme definition
            if (! str_contains($line, 'theme-mockup-light') && ! str_contains($line, 'opening-mockup-light')) {
                $hardcodedBg[] = [
                    'file' => $relPath,
                    'line' => $lineNum,
                    'content' => $trimmed,
                ];
            }
        }

        // 2. Pale yellow text (#e9b949, #fef08a, etc) used for text color
        if (preg_match('/color\s*:\s*(#e9b949\b|#fef08a\b|#fde047\b)/i', $line)) {
            $hardcodedYellow[] = [
                'file' => $relPath,
                'line' => $lineNum,
                'content' => $trimmed,
            ];
        }

        // 3. Empty state inline styles with hardcoded backgrounds
        if (str_contains($line, '@empty') || str_contains($line, 'empty-state') || (str_contains($line, 'text-align: center') && str_contains($line, 'padding:'))) {
            if (str_contains($line, '#ffffff') || str_contains($line, 'white')) {
                $emptyStates[] = [
                    'file' => $relPath,
                    'line' => $lineNum,
                    'content' => $trimmed,
                ];
            }
        }
    }
}

echo '=== 1. HARDCODED WHITE BACKGROUNDS BY FILE ==='.PHP_EOL;
$bgByFile = [];
foreach ($hardcodedBg as $item) {
    $bgByFile[$item['file']] = ($bgByFile[$item['file']] ?? 0) + 1;
}
arsort($bgByFile);
foreach ($bgByFile as $file => $count) {
    echo sprintf("  %-50s: %d occurrences\n", $file, $count);
}

echo PHP_EOL.'=== 2. PALE YELLOW TEXT OCCURRENCES ==='.PHP_EOL;
$yByFile = [];
foreach ($hardcodedYellow as $item) {
    $yByFile[$item['file']] = ($yByFile[$item['file']] ?? 0) + 1;
}
arsort($yByFile);
foreach ($yByFile as $file => $count) {
    echo sprintf("  %-50s: %d occurrences\n", $file, $count);
}
foreach ($hardcodedYellow as $item) {
    echo sprintf("    [%s:%d] %s\n", $item['file'], $item['line'], substr($item['content'], 0, 100));
}

echo PHP_EOL.'=== 3. EMPTY STATE HARDCODED WHITE BACKGROUNDS ==='.PHP_EOL;
foreach ($emptyStates as $item) {
    echo sprintf("  [%s:%d] %s\n", $item['file'], $item['line'], substr($item['content'], 0, 100));
}

<?php
$dir = dirname(__DIR__);
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

$pattern = '/[\x{1F300}-\x{1F9FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}\x{1F1E6}-\x{1F1FF}]/u';

foreach ($rii as $file) {
    if ($file->isDir()) continue;
    $ext = pathinfo($file->getPathname(), PATHINFO_EXTENSION);
    if (!in_array($ext, ['php', 'js', 'html'])) continue;
    if (strpos($file->getPathname(), 'scratch') !== false) continue;
    
    $relPath = str_replace($dir . DIRECTORY_SEPARATOR, '', $file->getPathname());
    $lines = file($file->getPathname());
    $found = false;
    foreach ($lines as $num => $line) {
        if (preg_match_all($pattern, $line, $matches)) {
            if (!$found) {
                echo "=== " . $relPath . " ===" . PHP_EOL;
                $found = true;
            }
            echo ($num + 1) . ": [" . implode(',', $matches[0]) . "] " . trim($line) . PHP_EOL;
        }
    }
}
echo "SCAN FINISHED" . PHP_EOL;

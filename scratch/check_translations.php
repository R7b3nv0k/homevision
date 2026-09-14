<?php
$js = file_get_contents(__DIR__ . '/../js/script.js');

// Extract translations object
preg_match('/const translations = (\{.*?\n\};)/s', $js, $match);
if (!$match) {
    echo "Could not find translations in script.js\n";
    exit;
}

// Find all data-i18n in PHP files
$phpFiles = array_merge(glob(__DIR__ . '/../*.php'), glob(__DIR__ . '/../pages/*.php'));
$keysInHtml = [];
foreach ($phpFiles as $f) {
    $c = file_get_contents($f);
    if (preg_match_all('/data-i18n=["\']([^"\']+)["\']/', $c, $m)) {
        foreach ($m[1] as $k) {
            $keysInHtml[$k][] = basename($f);
        }
    }
}

echo "=== KEYS FOUND IN HTML/PHP (" . count($keysInHtml) . ") ===\n";
ksort($keysInHtml);
foreach ($keysInHtml as $k => $files) {
    echo "$k in [" . implode(', ', array_unique($files)) . "]\n";
}

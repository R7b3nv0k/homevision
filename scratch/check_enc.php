<?php
$content = file_get_contents(__DIR__ . '/../css/style.css');
echo "Length: " . strlen($content) . "\n";
echo "Has null bytes: " . (strpos($content, chr(0)) !== false ? "YES" : "NO") . "\n";
echo "BOM: " . (substr($content, 0, 3) === "\xEF\xBB\xBF" ? "UTF-8 BOM" : "NO BOM") . "\n";
if (strpos($content, chr(0)) !== false) {
    echo "First null byte at: " . strpos($content, chr(0)) . "\n";
}

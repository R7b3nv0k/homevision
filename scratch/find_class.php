<?php
$css = file_get_contents(__DIR__ . '/../css/style.css');
$pos = strpos($css, 'auth-choice-menu');
echo "auth-choice-menu pos: " . ($pos !== false ? $pos : "NOT FOUND") . "\n";
if ($pos !== false) {
    echo "Around pos:\n" . substr($css, $pos - 100, 400) . "\n";
}

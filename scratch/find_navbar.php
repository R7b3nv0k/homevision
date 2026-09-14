<?php
$css = file_get_contents(__DIR__ . '/../css/style.css');
preg_match_all('/(\.navbar[^{]*\{)/i', $css, $matches, PREG_OFFSET_CAPTURE);
foreach ($matches[0] as $m) {
    echo "Match: {$m[0]} at offset {$m[1]}\n";
}

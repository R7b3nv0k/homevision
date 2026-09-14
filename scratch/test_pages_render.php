<?php
$pages = [
    'http://localhost/Homevision/homevision1/index.php',
    'http://localhost/Homevision/homevision1/login.php',
    'http://localhost/Homevision/homevision1/register.php',
    'http://localhost/Homevision/homevision1/profile.php',
    'http://localhost/Homevision/homevision1/error.php',
    'http://localhost/Homevision/homevision1/pages/tervek.php',
    'http://localhost/Homevision/homevision1/pages/nordicfamily.php',
    'http://localhost/Homevision/homevision1/pages/megvalositas.php',
    'http://localhost/Homevision/homevision1/pages/media.php',
    'http://localhost/Homevision/homevision1/pages/rolunk.php',
    'http://localhost/Homevision/homevision1/pages/kapcsolat.php'
];

$emojiPattern = '/[\x{1F300}-\x{1F9FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}\x{1F1E6}-\x{1F1FF}]/u';

foreach ($pages as $url) {
    $html = @file_get_contents($url);
    $basename = basename(parse_url($url, PHP_URL_PATH));
    if ($html === false) {
        echo "[FAIL] $basename could not be fetched!" . PHP_EOL;
        continue;
    }
    
    // Check SVG icons
    $svgCount = substr_count($html, '<svg class="hv-icon');
    
    // Check data-i18n
    $i18nCount = substr_count($html, 'data-i18n=');
    
    // Check for emojis (excluding gold review star if in index)
    $lines = explode("\n", $html);
    $emojiMatches = [];
    foreach ($lines as $ln => $l) {
        // Allow ★ and ✦
        $lClean = str_replace(['★', '✦'], '', $l);
        if (preg_match_all($emojiPattern, $lClean, $m)) {
            $emojiMatches[] = ($ln + 1) . ": " . implode(',', $m[0]);
        }
    }
    
    $emojiStatus = empty($emojiMatches) ? "0 emojis (CLEAN)" : count($emojiMatches) . " emoji occurrences: " . implode('; ', $emojiMatches);
    echo "[OK] $basename -> SVGs: $svgCount | i18n tags: $i18nCount | Emojis: $emojiStatus" . PHP_EOL;
}

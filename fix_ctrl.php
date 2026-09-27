<?php
$content = file_get_contents('app/Http/Controllers/ApplicationController.php');
// Fix all mojibake in the whole string. 
// We use a regex to match the mojibake so we don't touch normal english text.
$content = preg_replace_callback('/[ØÙ][\x80-\xBF]+/', function($m) {
    // Wait, regex [ØÙ][\x80-\xBF] matches single bytes in ISO-8859-1 which correspond to valid UTF-8 Arabic bytes.
    // However, the string in PHP memory read from the file is UTF-8 (the mojibake itself is UTF-8).
    // Let's just convert the whole file. 
    return mb_convert_encoding($m[0], 'Windows-1252', 'UTF-8');
}, $content);
file_put_contents('app/Http/Controllers/ApplicationController_fixed.php', $content);
echo "Fixed controller.\n";

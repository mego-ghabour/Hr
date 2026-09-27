<?php
$files = [
    'resources/views/jobs/apply.blade.php',
    'app/Filament/Admin/Resources/JobPostingResource.php',
    'app/Http/Controllers/ApplicationController.php'
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    // Decode UTF-8 mojibake back to raw ISO-8859-1 bytes (which form the correct UTF-8 Arabic)
    $content = mb_convert_encoding($content, 'ISO-8859-1', 'UTF-8');
    file_put_contents($file, $content);
    echo "Fixed $file\n";
}

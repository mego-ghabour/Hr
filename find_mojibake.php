<?php
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('.'));
$files = [];
foreach($it as $file) {
    if($file->isFile() && in_array($file->getExtension(), ['php', 'blade.php'])) {
        // skip vendor
        if(strpos($file->getPathname(), 'vendor') !== false) continue;
        $content = file_get_contents($file->getPathname());
        if(strpos($content, 'Ø') !== false || strpos($content, 'Ù') !== false) {
            echo $file->getPathname() . "\n";
        }
    }
}

<?php
$f = 'app/Filament/Admin/Resources/JobPostingResource.php';
$c = file_get_contents($f);
preg_match_all('/\\\\Filament\\\\Schemas\\\\Components\\\\Tabs.*?::make/', $c, $m);
print_r($m);
preg_match_all('/\\\\Filament\\\\Forms\\\\Components\\\\Tabs.*?::make/', $c, $m2);
print_r($m2);

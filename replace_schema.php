<?php
$f = 'app/Filament/Admin/Resources/JobPostingResource.php';
$c = file_get_contents($f);
$c = str_replace('->schema([', '->components([', $c);
file_put_contents($f, $c);
echo "Replaced schema with components.";

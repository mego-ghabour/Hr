<?php
$f = 'vendor/filament/schemas/src/Components/Tabs/Tab.php';
$c = file_get_contents($f);
echo "Has schema: " . (strpos($c, 'schema') !== false ? 'YES' : 'NO') . "\n";
echo "Has components: " . (strpos($c, 'components') !== false ? 'YES' : 'NO') . "\n";

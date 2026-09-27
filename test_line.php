<?php
$lines = file('resources/views/jobs/apply.blade.php');
echo "Line: " . $lines[5] . "\n";
echo "Hex: " . bin2hex(trim($lines[5])) . "\n";

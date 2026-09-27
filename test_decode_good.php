<?php
$str = "التقديم";
echo "mb_convert on good text: " . mb_convert_encoding($str, 'Windows-1252', 'UTF-8') . "\n";

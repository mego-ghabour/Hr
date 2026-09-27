<?php
$f = 'resources/views/jobs/apply.blade.php';
$c = file_get_contents($f);

// Replace the hardcoded hex with the variable
$c = str_replace(
    "600: '#1b83bf'",
    "600: '{{ \$job->brand_color ?? \'#1b83bf\' }}'",
    $c
);

file_put_contents($f, $c);
echo "Dynamic branding added to apply.blade.php!";

<?php
$f = 'resources/views/jobs/apply.blade.php';
$c = file_get_contents($f);

$old = <<<EOT
                    colors: { brand: { 50: '#f1f8fc', 100: '#e0f0f8', 200: '#bae0f0', 300: '#83c9e5', 400: '#46aed7', 500: '#2695c4', 600: '{{ \$job->brand_color ?? \\'#1b83bf\\' }}', 700: '#18699d', 800: '#165882', 900: '#164a6d', 950: '#0f304a' },
                        secondary: { 50: '#fef6f0', 100: '#fdeddb', 200: '#fcd2b3', 300: '#fab382', 400: '#f88b48', 500: '#f58633', 600: '#e66a20', 700: '#bf501a', 800: '#98401a', 900: '#7a3517' } } }
EOT;

$new = <<<EOT
                    colors: { 
                        brand: { 50: '#f1f8fc', 100: '#e0f0f8', 200: '#bae0f0', 300: '#83c9e5', 400: '#46aed7', 500: '#2695c4', 600: '{{ \$job->brand_color ?? "#1b83bf" }}', 700: '#18699d', 800: '#165882', 900: '#164a6d', 950: '#0f304a' },
                        secondary: { 50: '#fef6f0', 100: '#fdeddb', 200: '#fcd2b3', 300: '#fab382', 400: '#f88b48', 500: '#f58633', 600: '#e66a20', 700: '#bf501a', 800: '#98401a', 900: '#7a3517' } 
                    }
EOT;

$c = str_replace($old, $new, $c);
file_put_contents($f, $c);
echo "Fixed JS config!";

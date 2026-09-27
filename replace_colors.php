<?php

$file = 'resources/views/jobs/apply.blade.php';
$content = file_get_contents($file);

// Replace the colors block in the config
$oldConfig = "colors: { brand: { 50: '#f0fdfa', 100: '#ccfbf1', 500: '#14b8a6', 600: '#0d9488', 900: '#134e4a' } }";
$newConfig = "colors: { brand: { 50: '#f1f8fc', 100: '#e0f0f8', 200: '#bae0f0', 300: '#83c9e5', 400: '#46aed7', 500: '#2695c4', 600: '#1b83bf', 700: '#18699d', 800: '#165882', 900: '#164a6d', 950: '#0f304a' } }";
$content = str_replace($oldConfig, $newConfig, $content);

// If the old config was already missing (since I used indigo instead of brand), let's just do a regex replace for the whole theme.extend.colors block.
$content = preg_replace(
    "/colors:\s*\{[^}]*\}/",
    "colors: { brand: { 50: '#f1f8fc', 100: '#e0f0f8', 200: '#bae0f0', 300: '#83c9e5', 400: '#46aed7', 500: '#2695c4', 600: '#1b83bf', 700: '#18699d', 800: '#165882', 900: '#164a6d', 950: '#0f304a' } }",
    $content
);

// Replace class names
$content = str_replace('indigo-', 'brand-', $content);
$content = str_replace('blue-900', 'brand-800', $content);
$content = str_replace('blue-600', 'brand-500', $content);
$content = str_replace('blue-700', 'brand-700', $content);
$content = str_replace('blue-400', 'brand-400', $content);

file_put_contents($file, $content);
echo "Colors updated successfully!";

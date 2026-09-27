<?php

$f = 'resources/views/jobs/apply.blade.php';
$c = file_get_contents($f);

// 1. Inject the Secondary color into the config
$c = str_replace(
    "brand: { 50: '#f1f8fc', 100: '#e0f0f8', 200: '#bae0f0', 300: '#83c9e5', 400: '#46aed7', 500: '#2695c4', 600: '#1b83bf', 700: '#18699d', 800: '#165882', 900: '#164a6d', 950: '#0f304a' }",
    "brand: { 50: '#f1f8fc', 100: '#e0f0f8', 200: '#bae0f0', 300: '#83c9e5', 400: '#46aed7', 500: '#2695c4', 600: '#1b83bf', 700: '#18699d', 800: '#165882', 900: '#164a6d', 950: '#0f304a' },\n                        secondary: { 50: '#fef6f0', 100: '#fdeddb', 200: '#fcd2b3', 300: '#fab382', 400: '#f88b48', 500: '#f58633', 600: '#e66a20', 700: '#bf501a', 800: '#98401a', 900: '#7a3517' }",
    $c
);

// 2. Make the Submit button secondary (Orange)
$c = str_replace(
    "bg-gradient-to-r from-brand-600 to-brand-500 px-10 py-4 text-base font-bold text-white shadow-lg shadow-brand-200 hover:from-brand-700 hover:to-brand-700 focus:outline-none focus:ring-4 focus:ring-brand-600/20",
    "bg-gradient-to-r from-secondary-500 to-secondary-600 px-10 py-4 text-base font-bold text-white shadow-lg shadow-secondary-200 hover:from-secondary-600 hover:to-secondary-700 focus:outline-none focus:ring-4 focus:ring-secondary-500/30",
    $c
);
// In case the submit button text was a bit different:
$c = str_replace(
    'bg-gradient-to-r from-brand-600 to-brand-500 px-10',
    'bg-gradient-to-r from-secondary-500 to-secondary-600 px-10',
    $c
);
$c = preg_replace(
    '/bg-gradient-to-r from-brand-\d+ to-brand-\d+ px-10 py-4 text-base font-bold text-white shadow-lg shadow-brand-\d+ hover:from-brand-\d+ hover:to-brand-\d+ focus:outline-none focus:ring-4 focus:ring-brand-\d+\/20/',
    'bg-gradient-to-r from-secondary-500 to-secondary-600 px-10 py-4 text-base font-bold text-white shadow-lg shadow-secondary-200 hover:from-secondary-600 hover:to-secondary-700 focus:outline-none focus:ring-4 focus:ring-secondary-500/30',
    $c
);

// 3. Make section numbers secondary
$c = str_replace(
    'bg-brand-600 text-white text-sm shadow-md shadow-brand-200',
    'bg-secondary-500 text-white text-sm shadow-md shadow-secondary-200',
    $c
);

// 4. Change required asterisks to secondary
$c = str_replace('text-red-500', 'text-secondary-500', $c);
$c = str_replace('text-red-400', 'text-secondary-500', $c);

// 5. Sidebar top badge (تقديم طلب توظيف)
$c = str_replace(
    'bg-brand-800/60 text-brand-100 text-xs font-bold rounded-lg mb-5 border border-brand-500/30 backdrop-blur-sm shadow-sm',
    'bg-secondary-500 text-white text-xs font-bold rounded-lg mb-5 border border-secondary-400 shadow-sm',
    $c
);

// 6. Sidebar link hover
$c = str_replace('hover:text-brand-600', 'hover:text-secondary-500', $c);

// 7. Make success screen button secondary
$c = str_replace(
    'bg-brand-600 hover:bg-brand-700 shadow-md',
    'bg-secondary-500 hover:bg-secondary-600 shadow-md',
    $c
);

file_put_contents($f, $c);
echo "Primary and secondary colors integrated!";

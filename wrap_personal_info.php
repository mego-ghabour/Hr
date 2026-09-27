<?php
$f = 'resources/views/jobs/apply.blade.php';
$c = file_get_contents($f);

// 1. full_name
$c = str_replace(
    '<div>
                                    <label for="full_name"',
    '@if($config[\'show_full_name\'] ?? true)
                                <div>
                                    <label for="full_name"',
    $c
);
$c = preg_replace(
    '/name="full_name".*?<\/div>/s',
    '$0
                                @endif',
    $c,
    1
);

// 2. email
$c = str_replace(
    '<div>
                                    <label for="email"',
    '@if($config[\'show_email\'] ?? true)
                                <div>
                                    <label for="email"',
    $c
);
$c = preg_replace(
    '/name="email".*?<\/div>/s',
    '$0
                                @endif',
    $c,
    1
);

// 3. phone
$c = str_replace(
    '<div>
                                    <label for="phone"',
    '@if($config[\'show_phone\'] ?? true)
                                <div>
                                    <label for="phone"',
    $c
);
$c = preg_replace(
    '/name="phone".*?<\/div>/s',
    '$0
                                @endif',
    $c,
    1
);

// 4. linkedin_url
$c = str_replace(
    '<div>
                                    <label for="linkedin_url"',
    '@if($config[\'show_linkedin\'] ?? true)
                                <div>
                                    <label for="linkedin_url"',
    $c
);
$c = preg_replace(
    '/name="linkedin_url".*?<\/div>/s',
    '$0
                                @endif',
    $c,
    1
);

// 5. portfolio_url
$c = str_replace(
    '<div>
                                    <label for="portfolio_url"',
    '@if($config[\'show_portfolio\'] ?? true)
                                <div>
                                    <label for="portfolio_url"',
    $c
);
$c = preg_replace(
    '/name="portfolio_url".*?<\/div>/s',
    '$0
                                @endif',
    $c,
    1
);

file_put_contents('test_apply.php', $c);
echo "Done";

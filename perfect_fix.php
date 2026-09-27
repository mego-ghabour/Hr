<?php
$f = 'resources/views/jobs/apply.blade.php';
$c = file_get_contents($f);

// 1. Initialize stepCounter
$c = str_replace(
    "@php \$config = \$job->primary_fields_config ?? []; @endphp",
    "@php \$config = \$job->primary_fields_config ?? []; \$stepCounter = 1; @endphp",
    $c
);

// 2. Section 1 Wrap
$s1_start = <<<EOT
                        <!-- Section 1 -->
                        @if(
                            (\$config['show_full_name'] ?? true) || (\$config['show_email'] ?? true) ||
                            (\$config['show_phone'] ?? true) || (\$config['show_linkedin'] ?? true) ||
                            (\$config['show_portfolio'] ?? true)
                        )
                        <div class="bg-brand-50/30 p-6 sm:p-8 rounded-2xl border border-brand-50 hover:border-brand-100 transition-colors">
                            <h2 class="text-xl font-extrabold text-brand-950 mb-6 flex items-center gap-3">
                                <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-secondary-500 text-white text-sm shadow-md shadow-secondary-200">{{ \$stepCounter++ }}</span>
EOT;
$c = preg_replace('/<!-- Section 1 -->\s*<div[^>]+>\s*<h2[^>]+>\s*<span[^>]+>1<\/span>/s', $s1_start, $c, 1);

// Close Section 1 before Section 2
$c = str_replace("<!-- Section 2 -->", "                        @endif\n\n                        <!-- Section 2 -->", $c);

// 3. Section 2 Step Counter
$c = preg_replace('/(<!-- Section 2 -->.*?<span[^>]+>)2(<\/span>)/s', '$1{{ $stepCounter++ }}$2', $c, 1);

// 4. Section 3 Wrap and Counter
$s3_start = <<<EOT
                        <!-- Section 3 -->
                        @if((\$config['show_resume'] ?? true) || (\$config['show_general_notes'] ?? true))
                        <div class="bg-brand-50/30 p-6 sm:p-8 rounded-2xl border border-brand-50 hover:border-brand-100 transition-colors">
                            <h2 class="text-xl font-extrabold text-brand-950 mb-6 flex items-center gap-3">
                                <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-secondary-500 text-white text-sm shadow-md shadow-secondary-200">{{ \$stepCounter++ }}</span>
EOT;
$c = preg_replace('/<!-- Section 3 -->\s*<div[^>]+>\s*<h2[^>]+>\s*<span[^>]+>3<\/span>/s', $s3_start, $c, 1);

// Close Section 3 before Section 4
$c = str_replace("@if(\$job->form_schema && count(\$job->form_schema) > 0)", "                        @endif\n\n                        @if(\$job->form_schema && count(\$job->form_schema) > 0)", $c);

// 5. Section 4 Step Counter
$c = preg_replace('/(<span[^>]+>)4(<\/span>)/s', '$1{{ $stepCounter++ }}$2', $c, 1);

// --- Now the inner fields of Section 1 (full_name, email, etc.) ---
$c = str_replace('<div class="sm:col-span-2">
                                    <label for="full_name"', '@if($config[\'show_full_name\'] ?? true)
                                <div class="sm:col-span-2">
                                    <label for="full_name"', $c);
$c = preg_replace('/name="full_name"[\s\S]*?<\/div>/', "$0\n                                @endif", $c, 1);

$c = str_replace('<div>
                                    <label for="email"', '@if($config[\'show_email\'] ?? true)
                                <div>
                                    <label for="email"', $c);
$c = preg_replace('/name="email"[\s\S]*?<\/div>/', "$0\n                                @endif", $c, 1);

$c = str_replace('<div>
                                    <label for="phone"', '@if($config[\'show_phone\'] ?? true)
                                <div>
                                    <label for="phone"', $c);
$c = preg_replace('/name="phone"[\s\S]*?<\/div>/', "$0\n                                @endif", $c, 1);

$c = str_replace('<div>
                                    <label for="linkedin_url"', '@if($config[\'show_linkedin\'] ?? true)
                                <div>
                                    <label for="linkedin_url"', $c);
$c = preg_replace('/name="linkedin_url"[\s\S]*?<\/div>/', "$0\n                                @endif", $c, 1);

$c = str_replace('<div>
                                    <label for="portfolio_url"', '@if($config[\'show_portfolio\'] ?? true)
                                <div>
                                    <label for="portfolio_url"', $c);
$c = preg_replace('/name="portfolio_url"[\s\S]*?<\/div>/', "$0\n                                @endif", $c, 1);

file_put_contents($f, $c);
echo "Perfectly updated apply.blade.php!";

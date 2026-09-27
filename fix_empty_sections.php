<?php
$f = 'resources/views/jobs/apply.blade.php';
$c = file_get_contents($f);

// 1. Initialize step counter
$c = str_replace(
    "@php \$config = \$job->primary_fields_config ?? []; @endphp",
    "@php \$config = \$job->primary_fields_config ?? []; \$stepCounter = 1; @endphp",
    $c
);

// 2. Section 1 Overarching IF
$section1_if = <<<EOT
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
$c = preg_replace(
    "/<!-- Section 1 -->\s*<div class=\"bg-brand-50\/30 p-6 sm:p-8 rounded-2xl border border-brand-50 hover:border-brand-100 transition-colors\">\s*<h2 class=\"text-xl font-extrabold text-brand-950 mb-6 flex items-center gap-3\">\s*<span class=\"flex items-center justify-center w-8 h-8 rounded-lg bg-secondary-500 text-white text-sm shadow-md shadow-secondary-200\">1<\/span>/",
    $section1_if,
    $c
);
// Close Section 1 IF
$c = preg_replace(
    "/(<div class=\"grid grid-cols-1 sm:grid-cols-2 gap-6\">\s*@if\(\\$config\['show_full_name'\].*?<\/div>\s*<\/div>)/s",
    "$1\n                        @endif\n",
    $c,
    1 // replace only the first occurrence (which is section 1)
);

// Wait, the above regex for closing section 1 might fail because the div structure is complex.
// Let's use a safer script: read the file, inject `@endif` before `<!-- Section 2 -->`.
$c = str_replace("<!-- Section 2 -->", "                        @endif\n\n                        <!-- Section 2 -->", $c);

// 3. Section 2 Dynamic Counter
$c = str_replace(
    '<span class="flex items-center justify-center w-8 h-8 rounded-lg bg-secondary-500 text-white text-sm shadow-md shadow-secondary-200">2</span>',
    '<span class="flex items-center justify-center w-8 h-8 rounded-lg bg-secondary-500 text-white text-sm shadow-md shadow-secondary-200">{{ $stepCounter++ }}</span>',
    $c
);

// 4. Section 3 Overarching IF & Counter
$section3_if = <<<EOT
                        <!-- Section 3 -->
                        @if(
                            (\$config['show_resume'] ?? true) || (\$config['show_general_notes'] ?? true)
                        )
                        <div class="bg-brand-50/30 p-6 sm:p-8 rounded-2xl border border-brand-50 hover:border-brand-100 transition-colors">
                            <h2 class="text-xl font-extrabold text-brand-950 mb-6 flex items-center gap-3">
                                <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-secondary-500 text-white text-sm shadow-md shadow-secondary-200">{{ \$stepCounter++ }}</span>
EOT;
$c = preg_replace(
    "/<!-- Section 3 -->\s*<div class=\"bg-brand-50\/30 p-6 sm:p-8 rounded-2xl border border-brand-50 hover:border-brand-100 transition-colors\">\s*<h2 class=\"text-xl font-extrabold text-brand-950 mb-6 flex items-center gap-3\">\s*<span class=\"flex items-center justify-center w-8 h-8 rounded-lg bg-secondary-500 text-white text-sm shadow-md shadow-secondary-200\">3<\/span>/",
    $section3_if,
    $c
);
// Close Section 3 IF
$c = str_replace("@if(\$job->form_schema && count(\$job->form_schema) > 0)", "                        @endif\n\n                        @if(\$job->form_schema && count(\$job->form_schema) > 0)", $c);


// 5. Section 4 Dynamic Counter
$c = str_replace(
    '<span class="flex items-center justify-center w-8 h-8 rounded-lg bg-secondary-500 text-white text-sm shadow-md shadow-secondary-200">4</span>',
    '<span class="flex items-center justify-center w-8 h-8 rounded-lg bg-secondary-500 text-white text-sm shadow-md shadow-secondary-200">{{ $stepCounter++ }}</span>',
    $c
);

file_put_contents('apply_sections_fix.php', $c);
echo "Script created!";

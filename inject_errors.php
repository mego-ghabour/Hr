<?php
$f = 'resources/views/jobs/apply.blade.php';
$c = file_get_contents($f);

// We want to find pattern:
// name="FIELD_NAME" ... > (could span multiple lines)
// and then insert the @error just after the element but before the closing </div>
// Actually, it's safer to just find `name="XYZ"` and append the error block right after the `>` of the input/select/textarea.

// Handle standard inputs/textareas/selects
$pattern = '/<(input|select|textarea)([^>]+name="([^"\[\]]+)(?:\[\])?"[^>]*)>(?:(.*?)<\/\1>)?/s';

$c = preg_replace_callback($pattern, function($matches) {
    $fullTag = $matches[0];
    $fieldName = $matches[3]; // e.g. full_name, email, custom_abc
    
    // Check if error block already exists right after (to avoid duplicates)
    // we can't easily check what comes after in this callback, but we assume it doesn't exist since we checked earlier.
    
    // Exception for hidden inputs or checkboxes which might have different layout
    if (strpos($matches[2], 'type="hidden"') !== false) {
        return $fullTag;
    }
    
    if (strpos($matches[2], 'type="checkbox"') !== false || strpos($matches[2], 'type="radio"') !== false) {
        // Errors for checkboxes are better placed at the end of the group, not after each checkbox
        return $fullTag;
    }

    $errorBlock = "\n                                    @error('" . $fieldName . "')\n                                        <p class=\"mt-1 text-sm font-bold text-red-500\">{{ \$message }}</p>\n                                    @enderror";
    
    return $fullTag . $errorBlock;
}, $c);

// For checkboxes that are in a grid (Custom Checkboxes), the name is custom_xyz[]
// The loop above ignored checkboxes. Let's manually add errors for custom fields at the end of their blocks if needed, 
// or just rely on standard fields for now. 
// Wait, the custom checkboxes are wrapped in a <div>, let's inject after the grid.
$c = preg_replace_callback('/<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">\s*@foreach\(\$data\[\'options\'\][^>]*>.*?<\/div>/s', function($matches) {
    // try to extract the field name from the checkbox inside
    if (preg_match('/name="([^"\[\]]+)(?:\[\])?"/', $matches[0], $nameMatch)) {
        $fieldName = $nameMatch[1];
        $errorBlock = "\n                                    @error('" . $fieldName . "')\n                                        <p class=\"mt-1 text-sm font-bold text-red-500\">{{ \$message }}</p>\n                                    @enderror";
        return $matches[0] . $errorBlock;
    }
    return $matches[0];
}, $c);

// Also add a global error banner at the top of the form
$globalError = <<<EOT
                @if(\$errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border-l-4 border-red-500 flex items-start gap-3">
                        <svg class="w-6 h-6 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <h3 class="text-sm font-bold text-red-800">يرجى تصحيح الأخطاء التالية قبل الإرسال:</h3>
                            <ul class="mt-1 list-disc list-inside text-sm text-red-700">
                                @foreach(\$errors->all() as \$error)
                                    <li>{{ \$error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
EOT;

$c = str_replace('<form action="{{ route(\'jobs.apply.submit\', $job->id) }}" method="POST" enctype="multipart/form-data">', '<form action="{{ route(\'jobs.apply.submit\', $job->id) }}" method="POST" enctype="multipart/form-data">' . "\n" . $globalError, $c);

// Add Alpine logic to auto-scroll to the first error if it exists
$c = str_replace('<div x-data="{ isSubmitting: false }">', '<div x-data="{ isSubmitting: false }" x-init="if (document.querySelector(\'.border-red-500\')) { document.querySelector(\'.border-red-500\').scrollIntoView({ behavior: \'smooth\', block: \'center\' }); }">', $c);

file_put_contents($f, $c);
echo "Errors injected successfully into apply.blade.php!";

<?php
$files = [
    'c:\Users\fkrtk\Desktop\HR\resources\views\jobs\apply.blade.php',
    'c:\Users\fkrtk\Desktop\HR\app\Filament\Admin\Resources\JobPostingResource.php',
    'c:\Users\fkrtk\Desktop\HR\app\Http\Controllers\ApplicationController.php',
    'c:\Users\fkrtk\Desktop\HR\app\Filament\Admin\Resources\Employees\Schemas\EmployeeForm.php',
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    
    $lines = file($file);
    $changed = false;
    
    foreach ($lines as $i => $line) {
        // If the line contains typical mojibake letters
        if (preg_match('/[ØÙ]/', $line)) {
            // we decode the whole line
            $lines[$i] = utf8_decode($line);
            $changed = true;
        }
    }
    
    if ($changed) {
        file_put_contents($file, implode("", $lines));
        echo "Fixed $file\n";
    }
}

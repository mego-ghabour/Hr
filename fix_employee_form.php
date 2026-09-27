<?php
$f = 'app/Filament/Admin/Resources/Employees/Schemas/EmployeeForm.php';
$c = file_get_contents($f);

// Add configure method
$configureMethod = <<<EOT
    public static function configure(\Filament\Schemas\Schema \$schema): \Filament\Schemas\Schema
    {
        return \$schema->components(static::schema());
    }
EOT;

// Insert it right after the class declaration
$c = preg_replace('/class EmployeeForm\s*\{/', "class EmployeeForm\n{\n$configureMethod\n", $c);

file_put_contents($f, $c);
echo "Added configure method to EmployeeForm!";

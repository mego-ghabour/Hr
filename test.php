<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::first();
if ($user) {
    try {
        \Filament\Notifications\Notification::make()
            ->title('TEST REAL')
            ->success()
            ->sendToDatabase($user);
        echo "Notification Sent successfully!\n";
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
} else {
    echo "No user found\n";
}

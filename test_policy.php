<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('email', '!=', 'admin@hr.com')->first();
if (!$user) {
    $user = App\Models\User::factory()->create();
}
$user->syncRoles([]); // Ensure no roles
echo "Has permission? " . ($user->can('viewAny', App\Models\Department::class) ? 'YES' : 'NO') . "\n";

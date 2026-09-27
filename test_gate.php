<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('email', 'megoghabour@gmail.com')->first();
Illuminate\Support\Facades\Auth::login($user);

echo "User: " . $user->email . "\n";
echo "Has Super Admin? " . ($user->hasRole('Super Admin') ? 'Yes' : 'No') . "\n";
echo "Can view Department? " . (Illuminate\Support\Facades\Gate::allows('viewAny', App\Models\Department::class) ? 'Yes' : 'No') . "\n";
echo "Can view User? " . (Illuminate\Support\Facades\Gate::allows('viewAny', App\Models\User::class) ? 'Yes' : 'No') . "\n";
echo "Can view Talent? " . (Illuminate\Support\Facades\Gate::allows('viewAny', App\Models\Talent::class) ? 'Yes' : 'No') . "\n";

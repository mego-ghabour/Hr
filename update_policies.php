<?php

$models = [
    "Department" => "department",
    "Location" => "location",
    "Source" => "source",
    "TalentStatus" => "status",
    "Duplicate" => "duplicate",
    "Role" => "roles",
    "User" => "users",
    "Talent" => "talent"
];

foreach ($models as $model => $key) {
    $file = __DIR__ . "/app/Policies/{$model}Policy.php";
    if (file_exists($file)) {
        $content = <<<PHP
<?php

namespace App\Policies;

use App\Models\\$model;
use App\Models\User;

class {$model}Policy
{
    public function viewAny(User \$user): bool
    {
        return \$user->hasPermissionTo('view_any_{$key}');
    }

    public function view(User \$user, $model \$model): bool
    {
        return \$user->hasPermissionTo('view_{$key}');
    }

    public function create(User \$user): bool
    {
        return \$user->hasPermissionTo('create_{$key}');
    }

    public function update(User \$user, $model \$model): bool
    {
        return \$user->hasPermissionTo('update_{$key}');
    }

    public function delete(User \$user, $model \$model): bool
    {
        return \$user->hasPermissionTo('delete_{$key}');
    }
}
PHP;
        if ($model === 'Role' || $model === 'User') {
            $content = str_replace('hasPermissionTo(\'view_any_roles\')', 'hasPermissionTo(\'view_roles\')', $content);
            $content = str_replace('hasPermissionTo(\'view_roles\')', 'hasPermissionTo(\'view_roles\')', $content);
            $content = str_replace('hasPermissionTo(\'create_roles\')', 'hasPermissionTo(\'manage_roles\')', $content);
            $content = str_replace('hasPermissionTo(\'update_roles\')', 'hasPermissionTo(\'manage_roles\')', $content);
            $content = str_replace('hasPermissionTo(\'delete_roles\')', 'hasPermissionTo(\'manage_roles\')', $content);

            $content = str_replace('hasPermissionTo(\'view_any_users\')', 'hasPermissionTo(\'view_users\')', $content);
            $content = str_replace('hasPermissionTo(\'view_users\')', 'hasPermissionTo(\'view_users\')', $content);
            $content = str_replace('hasPermissionTo(\'create_users\')', 'hasPermissionTo(\'manage_users\')', $content);
            $content = str_replace('hasPermissionTo(\'update_users\')', 'hasPermissionTo(\'manage_users\')', $content);
            $content = str_replace('hasPermissionTo(\'delete_users\')', 'hasPermissionTo(\'manage_users\')', $content);
        }
        
        if ($model === 'Role') {
             $content = str_replace('use App\Models\Role;', 'use Spatie\Permission\Models\Role;', $content);
        }
        file_put_contents($file, $content);
    }
}
echo "Done\n";

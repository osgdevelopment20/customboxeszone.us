<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class GrantDashboardAccess extends Command
{
    protected $signature = 'dashboard:grant {email : Account to give full dashboard access}';

    protected $description = 'Create the dashboard permissions if missing and grant all of them to one user';

    /**
     * Permission names enforced by routes/web.php (and shown by the sidebar).
     */
    public const PERMISSIONS = [
        'view-dashboard',
        'manage-users',
        'manage-roles',
        'manage-permissions',
        'manage-marketing',
        'manage-products',
        'manage-categories',
        'manage-banners',
        'manage-blogs',
        'manage-queries',
        'manage-orders',
        'manage-settings',
        'manage-reviews',
        'generate-sitemap',
        'clear-cache',
        'pull-code',
    ];

    public function handle()
    {
        $user = User::where('email', $this->argument('email'))->first();

        if (! $user) {
            $this->error('No user with that email.');

            return self::FAILURE;
        }

        foreach (self::PERMISSIONS as $name) {
            Permission::findOrCreate($name, 'web');
        }

        $user->givePermissionTo(self::PERMISSIONS);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->info("Granted all dashboard permissions to {$user->email}.");

        return self::SUCCESS;
    }
}

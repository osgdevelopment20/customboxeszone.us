<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class AuditDashboardUsers extends Command
{
    protected $signature = 'dashboard:users';

    protected $description = 'List every account with its roles and permissions, newest first';

    public function handle()
    {
        $rows = User::with('roles')->latest('id')->get()->map(function (User $user) {
            $permissions = $user->getAllPermissions()->pluck('name');

            return [
                $user->id,
                $user->email,
                optional($user->created_at)->toDateTimeString(),
                $user->roles->pluck('name')->implode(', ') ?: '-',
                $permissions->isEmpty() ? 'NONE (no dashboard access)' : $permissions->implode(', '),
            ];
        });

        $this->table(['ID', 'Email', 'Created', 'Roles', 'Permissions'], $rows);

        return self::SUCCESS;
    }
}

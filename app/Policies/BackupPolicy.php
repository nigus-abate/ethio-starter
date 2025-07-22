<?php

namespace App\Policies;

use App\Models\Backup;
use App\Models\User;

class BackupPolicy
{
    public function viewAny(User $user)
    {
        return $user->can('manage_backups');
    }

    public function create(User $user)
    {
        return $user->can('manage_backups');
    }

    public function delete(User $user, Backup $backup)
    {
        return $user->can('manage_backups');
    }

    public function download(User $user, Backup $backup)
    {
        return $user->can('manage_backups');
    }

    public function restore(User $user, Backup $backup)
    {
        return $user->can('manage_backups');
    }
}
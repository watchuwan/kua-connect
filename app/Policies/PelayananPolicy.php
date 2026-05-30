<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Pelayanan;
use Illuminate\Auth\Access\HandlesAuthorization;

class PelayananPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Pelayanan');
    }

    public function view(AuthUser $authUser, Pelayanan $pelayanan): bool
    {
        return $authUser->can('View:Pelayanan');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Pelayanan');
    }

    public function update(AuthUser $authUser, Pelayanan $pelayanan): bool
    {
        return $authUser->can('Update:Pelayanan');
    }

    public function delete(AuthUser $authUser, Pelayanan $pelayanan): bool
    {
        return $authUser->can('Delete:Pelayanan');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Pelayanan');
    }

    public function restore(AuthUser $authUser, Pelayanan $pelayanan): bool
    {
        return $authUser->can('Restore:Pelayanan');
    }

    public function forceDelete(AuthUser $authUser, Pelayanan $pelayanan): bool
    {
        return $authUser->can('ForceDelete:Pelayanan');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Pelayanan');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Pelayanan');
    }

    public function replicate(AuthUser $authUser, Pelayanan $pelayanan): bool
    {
        return $authUser->can('Replicate:Pelayanan');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Pelayanan');
    }

}
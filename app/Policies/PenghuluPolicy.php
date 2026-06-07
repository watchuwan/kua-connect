<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Penghulu;
use Illuminate\Auth\Access\HandlesAuthorization;

class PenghuluPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Penghulu');
    }

    public function view(AuthUser $authUser, Penghulu $penghulu): bool
    {
        return $authUser->can('View:Penghulu');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Penghulu');
    }

    public function update(AuthUser $authUser, Penghulu $penghulu): bool
    {
        return $authUser->can('Update:Penghulu');
    }

    public function delete(AuthUser $authUser, Penghulu $penghulu): bool
    {
        return $authUser->can('Delete:Penghulu');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Penghulu');
    }

    public function restore(AuthUser $authUser, Penghulu $penghulu): bool
    {
        return $authUser->can('Restore:Penghulu');
    }

    public function forceDelete(AuthUser $authUser, Penghulu $penghulu): bool
    {
        return $authUser->can('ForceDelete:Penghulu');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Penghulu');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Penghulu');
    }

    public function replicate(AuthUser $authUser, Penghulu $penghulu): bool
    {
        return $authUser->can('Replicate:Penghulu');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Penghulu');
    }

}
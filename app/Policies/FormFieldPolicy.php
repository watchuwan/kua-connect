<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\FormField;
use Illuminate\Auth\Access\HandlesAuthorization;

class FormFieldPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:FormField');
    }

    public function view(AuthUser $authUser, FormField $formField): bool
    {
        return $authUser->can('View:FormField');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:FormField');
    }

    public function update(AuthUser $authUser, FormField $formField): bool
    {
        return $authUser->can('Update:FormField');
    }

    public function delete(AuthUser $authUser, FormField $formField): bool
    {
        return $authUser->can('Delete:FormField');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:FormField');
    }

    public function restore(AuthUser $authUser, FormField $formField): bool
    {
        return $authUser->can('Restore:FormField');
    }

    public function forceDelete(AuthUser $authUser, FormField $formField): bool
    {
        return $authUser->can('ForceDelete:FormField');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:FormField');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:FormField');
    }

    public function replicate(AuthUser $authUser, FormField $formField): bool
    {
        return $authUser->can('Replicate:FormField');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:FormField');
    }

}
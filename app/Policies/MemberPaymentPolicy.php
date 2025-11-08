<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\MemberPayment;
use Illuminate\Auth\Access\HandlesAuthorization;

class MemberPaymentPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:MemberPayment');
    }

    public function view(AuthUser $authUser, MemberPayment $memberPayment): bool
    {
        return $authUser->can('View:MemberPayment');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MemberPayment');
    }

    public function update(AuthUser $authUser, MemberPayment $memberPayment): bool
    {
        return $authUser->can('Update:MemberPayment');
    }

    public function delete(AuthUser $authUser, MemberPayment $memberPayment): bool
    {
        return $authUser->can('Delete:MemberPayment');
    }

    public function restore(AuthUser $authUser, MemberPayment $memberPayment): bool
    {
        return $authUser->can('Restore:MemberPayment');
    }

    public function forceDelete(AuthUser $authUser, MemberPayment $memberPayment): bool
    {
        return $authUser->can('ForceDelete:MemberPayment');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:MemberPayment');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:MemberPayment');
    }

    public function replicate(AuthUser $authUser, MemberPayment $memberPayment): bool
    {
        return $authUser->can('Replicate:MemberPayment');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MemberPayment');
    }

}
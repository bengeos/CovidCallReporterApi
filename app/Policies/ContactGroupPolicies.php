<?php

namespace App\Policies;

use App\Models\ContactGroup;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContactGroupPolicies
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        return true;
    }

    /**
     * Determine whether the user can view any contact groups.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the contact group.
     *
     * @param User $user
     * @param ContactGroup $contactGroup
     * @return mixed
     */
    public function view(User $user, ContactGroup $contactGroup)
    {
        return true;
    }

    /**
     * Determine whether the user can create contact groups.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the contact group.
     *
     * @param User $user
     * @param ContactGroup $contactGroup
     * @return mixed
     */
    public function update(User $user, ContactGroup $contactGroup)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the contact group.
     *
     * @param User $user
     * @param ContactGroup $contactGroup
     * @return mixed
     */
    public function delete(User $user, ContactGroup $contactGroup)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the contact group.
     *
     * @param User $user
     * @param ContactGroup $contactGroup
     * @return mixed
     */
    public function restore(User $user, ContactGroup $contactGroup)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the contact group.
     *
     * @param User $user
     * @param ContactGroup $contactGroup
     * @return mixed
     */
    public function forceDelete(User $user, ContactGroup $contactGroup)
    {
        return true;
    }
}

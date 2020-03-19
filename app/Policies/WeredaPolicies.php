<?php

namespace App\Policies;

use App\Models\Wereda;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class WeredaPolicies
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->role_id == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can view any weredas.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the wereda.
     *
     * @param User $user
     * @param Wereda $wereda
     * @return mixed
     */
    public function view(User $user, Wereda $wereda)
    {
        return true;
    }

    /**
     * Determine whether the user can create weredas.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the wereda.
     *
     * @param User $user
     * @param Wereda $wereda
     * @return mixed
     */
    public function update(User $user, Wereda $wereda)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the wereda.
     *
     * @param User $user
     * @param Wereda $wereda
     * @return mixed
     */
    public function delete(User $user, Wereda $wereda)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the wereda.
     *
     * @param User $user
     * @param Wereda $wereda
     * @return mixed
     */
    public function restore(User $user, Wereda $wereda)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the wereda.
     *
     * @param User $user
     * @param Wereda $wereda
     * @return mixed
     */
    public function forceDelete(User $user, Wereda $wereda)
    {
        return true;
    }
}

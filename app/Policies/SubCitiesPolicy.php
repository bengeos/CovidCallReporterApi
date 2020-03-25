<?php

namespace App\Policies;

use App\Models\SubCity;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubCitiesPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        return true;
    }

    /**
     * Determine whether the user can view any sub cities.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the sub city.
     *
     * @param User $user
     * @param SubCity $subCity
     * @return mixed
     */
    public function view(User $user, SubCity $subCity)
    {
        return true;
    }

    /**
     * Determine whether the user can create sub cities.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the sub city.
     *
     * @param User $user
     * @param SubCity $subCity
     * @return mixed
     */
    public function update(User $user, SubCity $subCity)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the sub city.
     *
     * @param User $user
     * @param SubCity $subCity
     * @return mixed
     */
    public function delete(User $user, SubCity $subCity)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the sub city.
     *
     * @param User $user
     * @param SubCity $subCity
     * @return mixed
     */
    public function restore(User $user, SubCity $subCity)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the sub city.
     *
     * @param User $user
     * @param SubCity $subCity
     * @return mixed
     */
    public function forceDelete(User $user, SubCity $subCity)
    {
        return true;
    }
}

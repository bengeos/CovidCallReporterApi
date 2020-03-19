<?php

namespace App\Policies;

use App\Models\City;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CityPolicies
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
     * Determine whether the user can view any cities.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the city.
     *
     * @param User $user
     * @param City $city
     * @return mixed
     */
    public function view(User $user, City $city)
    {
        return true;
    }

    /**
     * Determine whether the user can create cities.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the city.
     *
     * @param User $user
     * @param City $city
     * @return mixed
     */
    public function update(User $user, City $city)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the city.
     *
     * @param User $user
     * @param City $city
     * @return mixed
     */
    public function delete(User $user, City $city)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the city.
     *
     * @param User $user
     * @param City $city
     * @return mixed
     */
    public function restore(User $user, City $city)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the city.
     *
     * @param User $user
     * @param City $city
     * @return mixed
     */
    public function forceDelete(User $user, City $city)
    {
        return true;
    }
}

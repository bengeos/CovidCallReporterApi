<?php

namespace App\Policies;

use App\Models\Region;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RegionsPolicies
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        return true;
    }

    /**
     * Determine whether the user can view any regions.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the region.
     *
     * @param User $user
     * @param Region $region
     * @return mixed
     */
    public function view(User $user, Region $region)
    {
        return true;
    }

    /**
     * Determine whether the user can create regions.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the region.
     *
     * @param User $user
     * @param Region $region
     * @return mixed
     */
    public function update(User $user, Region $region)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the region.
     *
     * @param User $user
     * @param Region $region
     * @return mixed
     */
    public function delete(User $user, Region $region)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the region.
     *
     * @param User $user
     * @param Region $region
     * @return mixed
     */
    public function restore(User $user, Region $region)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the region.
     *
     * @param User $user
     * @param Region $region
     * @return mixed
     */
    public function forceDelete(User $user, Region $region)
    {
        return true;
    }
}

<?php

namespace App\Policies;

use App\Models\Zone;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ZonePolicies
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        return true;
    }

    /**
     * Determine whether the user can view any zones.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the zone.
     *
     * @param User $user
     * @param Zone $zone
     * @return mixed
     */
    public function view(User $user, Zone $zone)
    {
        return true;
    }

    /**
     * Determine whether the user can create zones.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the zone.
     *
     * @param User $user
     * @param Zone $zone
     * @return mixed
     */
    public function update(User $user, Zone $zone)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the zone.
     *
     * @param User $user
     * @param Zone $zone
     * @return mixed
     */
    public function delete(User $user, Zone $zone)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the zone.
     *
     * @param User $user
     * @param Zone $zone
     * @return mixed
     */
    public function restore(User $user, Zone $zone)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the zone.
     *
     * @param User $user
     * @param Zone $zone
     * @return mixed
     */
    public function forceDelete(User $user, Zone $zone)
    {
        return true;
    }
}

<?php

namespace App\Policies;

use App\Models\CallReport;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CallReportPolicies
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
     * Determine whether the user can view any call reports.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the call report.
     *
     * @param User $user
     * @param CallReport $callReport
     * @return mixed
     */
    public function view(User $user, CallReport $callReport)
    {
        return true;
    }

    /**
     * Determine whether the user can create call reports.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the call report.
     *
     * @param User $user
     * @param CallReport $callReport
     * @return mixed
     */
    public function update(User $user, CallReport $callReport)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the call report.
     *
     * @param User $user
     * @param CallReport $callReport
     * @return mixed
     */
    public function delete(User $user, CallReport $callReport)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the call report.
     *
     * @param User $user
     * @param CallReport $callReport
     * @return mixed
     */
    public function restore(User $user, CallReport $callReport)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the call report.
     *
     * @param User $user
     * @param CallReport $callReport
     * @return mixed
     */
    public function forceDelete(User $user, CallReport $callReport)
    {
        return true;
    }
}

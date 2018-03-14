<?php

namespace App\Policies;

use App\Heritage;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HeritagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create heritages.
     *
     * @param \App\User $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can update the heritage.
     *
     * @param \App\User $user
     * @param \App\Heritage $heritage
     *
     * @return mixed
     */
    public function update(User $user, Heritage $heritage = null)
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can delete the heritage.
     *
     * @param \App\User $user
     * @param \App\Heritage $heritage
     *
     * @return mixed
     */
    public function delete(User $user, Heritage $heritage = null)
    {
        return $user->isSuperAdmin();
    }
}

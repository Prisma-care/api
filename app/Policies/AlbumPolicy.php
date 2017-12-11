<?php

namespace App\Policies;

use App\User;
use App\Patient;
use App\Album;
use Illuminate\Auth\Access\HandlesAuthorization;

class AlbumPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the album.
     *
     * @param \App\User  $user
     * @param \App\Album $album
     *
     * @return mixed
     */
    public function view(User $user, Album $album)
    {
        return true;
    }

    /**
     * Determine whether the user can create albums.
     *
     * @param \App\User $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the album.
     *
     * @param \App\User  $user
     * @param \App\Album $album
     *
     * @return mixed
     */
    public function update(User $user, Album $album)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the album.
     *
     * @param \App\User  $user
     * @param \App\Album $album
     *
     * @return mixed
     */
    public function delete(User $user, Album $album)
    {
        return true;
    }
}

<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class Policy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function before(User $user, $ability)
    {
        if ($user->isSuperAdmin()) {
//            \Log::info(__FUNCTION__ . ',ability:' .$ability.',user:'. json_encode($user));
            return true;
        }
    }
}

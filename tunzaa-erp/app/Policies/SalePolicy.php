<?php

namespace App\Policies;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalePolicy
{
    use HandlesAuthorization;

    public function view(User $user, Sale $sale)
    {
        return $user->id === $sale->user_id;
    }
}

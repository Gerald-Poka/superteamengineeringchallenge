<?php

namespace App\Policies;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

// Handles authorization for Sale-related actions
class SalePolicy
{
    use HandlesAuthorization;

    public function view(User $user, Sale $sale)
    {
        // Controls who can view a specific sale
        return $user->id === $sale->user_id;
    }
}

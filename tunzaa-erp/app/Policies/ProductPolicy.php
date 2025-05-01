<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

// Handles authorization for Product-related actions
class ProductPolicy
{
    use HandlesAuthorization;

    // Methods define what users can do with products:
    
    public function view(User $user, Product $product)
    {
        // Controls who can view a specific product
        return $user->id === $product->user_id;
    }

    public function update(User $user, Product $product)
    {
        // Controls who can edit a product
        return $user->id === $product->user_id;
    }

    public function delete(User $user, Product $product)
    {
        // Controls who can delete a product
        return $user->id === $product->user_id;
    }
}

<?php

namespace App\Policies;

use App\Models\Inventory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InventoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Inventory $inventory)
    {
        return $user->id === $inventory->user_id;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Inventory $inventory)
    {
        return $user->id === $inventory->user_id;
    }

    public function delete(User $user, Inventory $inventory)
    {
        return $user->id === $inventory->user_id;
    }
}

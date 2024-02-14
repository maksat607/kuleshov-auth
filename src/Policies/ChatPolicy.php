<?php

namespace Maksatsaparbekov\KuleshovAuth\Policies;
use App\Models\User;
class ChatPolicy
{
    public function viewAny(User $user)
    {
        // Default policy logic
        return true;
    }
}
<?php

namespace Maksatsaparbekov\KuleshovAuth\Observers;


use App\Models\User;
use Illuminate\Support\Facades\Log;
use Maksatsaparbekov\KuleshovAuth\Synchronization\RequestEndpoints;

class UserObserver
{

    public function created(User $user)
    {

    }


    public function updated(User $user)
    {
        \Log::info('User password updated for user with ID: ' . $user->id);
        if (str_contains(request()->url(), 'reset-password')) {
            RequestEndpoints::from('reset')->send($user);
        }
    }


    public function deleted(User $user)
    {
        //
    }


    public function restored(User $user)
    {
        //
    }


    public function forceDeleted(User $user)
    {
        //
    }

    public function saving(User $user)
    {

    }


}

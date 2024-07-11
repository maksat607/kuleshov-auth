<?php

namespace Maksatsaparbekov\KuleshovAuth\Observers;


use App\Models\User;
use Illuminate\Support\Facades\Log;
use Maksatsaparbekov\KuleshovAuth\Synchronization\RequestEndpoints;

class UserObserver
{

    public function created(User $user)
    {
//        Log::info('created');
//        request()->merge(['phone' => request()->input('phone', $user->phone)]);
//        RequestEndpoints::from('register')->send($user);
    }


    public function updated(User $user)
    {
        Log::info('reset');
        request()->merge(['phone' => request()->input('phone', $user->phone)]);
        RequestEndpoints::from('reset')->send($user);
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

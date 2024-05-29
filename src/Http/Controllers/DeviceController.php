<?php

namespace Maksatsaparbekov\KuleshovAuth\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Maksatsaparbekov\KuleshovAuth\Http\Resources\UserResource;


class DeviceController
{
    public function devices(Request $request)
    {
        $phones = $request->get('phones');
        $phones = explode($phones, ',');

        $users = User::with('firebaseTokens')
            ->whereIn('phone', $phones)
            ->select('phone')
            ->get();

        return UserResource::collection($users);
    }
}

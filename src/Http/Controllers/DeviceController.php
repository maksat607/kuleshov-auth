<?php

namespace Maksatsaparbekov\KuleshovAuth\Http\Controllers;

use App\Models\Application;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class DeviceController
{
    public function devices()
    {
        return request()->user()->firebaseTokens;
    }

}

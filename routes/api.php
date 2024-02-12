<?php
use Illuminate\Support\Facades\Route;





Route::group(['middleware' => ['auth.access_token','resolveModel']], function () {
    Route::post('/{model}/{id}/messages', [Maksatsaparbekov\KuleshovAuth\Http\Controllers\MessageController::class, 'create']);
});
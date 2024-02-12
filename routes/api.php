<?php
use Illuminate\Support\Facades\Route;

Route::get('/package', function() {
    return 'Hello from your package!';
});



Route::group(['middleware' => ['auth.access_token','resolveModel']], function () {
    Route::post('/model/{model}/messages', [Maksatsaparbekov\KuleshovAuth\Http\Controllers\MessageController::class, 'create']);
});
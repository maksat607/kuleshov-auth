<?php
use Illuminate\Support\Facades\Route;

Route::get('/package', function() {
    return 'Hello from your package!';
});



Route::group(['middleware' => ['auth.access_token']], function () {
    Route::post('/messages', [Maksatsaparbekov\KuleshovAuth\Http\Controllers\MessageController::class, 'create']);
});
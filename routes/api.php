<?php

use Illuminate\Support\Facades\Route;


Route::prefix('messages')->middleware(['auth.access_token', 'resolveModel'])->group(function () {
    Route::post('{model}/{id}', [Maksatsaparbekov\KuleshovAuth\Http\Controllers\MessageController::class, 'store'])
        ->where('id', '[0-9]+');
    Route::get('{model}/{id}', [Maksatsaparbekov\KuleshovAuth\Http\Controllers\MessageController::class, 'modelMessages'])
        ->where('id', '[0-9]+');
    Route::get('{model}/{id}/by-user', [Maksatsaparbekov\KuleshovAuth\Http\Controllers\MessageController::class, 'modelMessagesByUser'])
        ->where('id', '[0-9]+');
    Route::get('{model}/by-user', [Maksatsaparbekov\KuleshovAuth\Http\Controllers\MessageController::class, 'userMessages']);
});

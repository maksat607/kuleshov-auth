<?php

use Illuminate\Support\Facades\Route;
use Maksatsaparbekov\KuleshovAuth\Http\Controllers\ChatController;



//Route::middleware(['auth.access_token', 'resolveModel'])->group(function () {
Route::prefix('api')->middleware(config('kuleshov-auth.routes.middleware'))->group(function () {


    Route::post('/mydevices', [\Maksatsaparbekov\KuleshovAuth\Http\Controllers\DeviceController::class, 'devices'])
        ;

    Route::get('/chats/{chatRoom}/read', [ChatController::class, 'makeReadChatMessagesForGivenChatRoom'])
        ->name('makeReadChatMessagesForGivenChatRoom')
        ->where('chatRoom', '[0-9]+');

    Route::get('/chats/{chatRoom}/messages', [ChatController::class, 'viewChatMessagesForGivenChatRoom'])
        ->name('viewChatMessagesForGivenChatRoom')
        ->where('chatRoom', '[0-9]+');

    Route::post('/chats/{chatRoom}/messages', [ChatController::class, 'createMessageForGivenChatRoom'])
        ->name('createMessageForGivenChatRoom')
        ->where('chatRoom', '[0-9]+');


    Route::post('/{model}/{modelId}/messages', [ChatController::class, 'createChatOrMessageForGivenModel'])
        ->name('createChatOrMessageForGivenModel')
        ->where('model', '^(?!chats$).*')
        ->where('modelId', '[0-9]+');

    Route::get('/{model}/{modelId}/chats', [ChatController::class, 'viewChatsMessagesOfAllUsersForGivenModel'])
        ->where('model', '^(?!chats$).*')
        ->where('modelId', '[0-9]+')
        ->name('viewChatsMessagesOfAllUsersForGivenModel');

    Route::get('/{model}/{modelId}/chat', [ChatController::class, 'viewChatMessagesOfAuthUserForGiventModel'])
        ->name('viewChatMessagesOfAuthUserForGiventModel')
        ->where('model', '^(?!chats$).*')
        ->where('modelId', '[0-9]+');


    Route::get('{model}/auth-user-chats', [ChatController::class, 'viewChatMessagesOfAuthUser'])
        ->name('viewChatMessagesOfAuthUser');


    Route::get('/{model}/chats', [ChatController::class, 'viewAllChatMessagesForGivenModelType'])
        ->where('model', '^(?!chats$).*')
        ->name('viewAllChatMessagesForGivenModelType');


});

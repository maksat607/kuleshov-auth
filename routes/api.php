<?php

use Illuminate\Support\Facades\Route;
use Maksatsaparbekov\KuleshovAuth\Http\Controllers\ChatController;



Route::middleware(['auth.access_token', 'resolveModel'])->group(function () {

    Route::get('/chats/{chatRoom}/messages', [ChatController::class, 'viewChatMessagesForGivenChatRoom'])
        ->name('viewChatMessagesForGivenChatRoom')
        ->where('chatRoom', '[0-9]+');

    // Managers send a reply within a chat
    Route::post('/chats/{chatRoom}/messages', [ChatController::class, 'createMessageForGivenChatRoom'])
        ->name('createMessageForGivenChatRoom')
        ->where('chatRoom', '[0-9]+');


    Route::post('/{model}/{modelId}/messages', [ChatController::class, 'createChatOrMessageForGivenModel'])
        ->name('createChatOrMessageForGivenModel')
        ->where('model', '^(?!chats$).*')
        ->where('modelId', '[0-9]+');

    // Managers can see all the chats of the given model
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

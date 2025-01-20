<?php

use Illuminate\Support\Facades\Route;
use Maksatsaparbekov\KuleshovAuth\Http\Controllers\ChatController;

Route::prefix('api/v2')->middleware(config('kuleshov-auth.routes.middleware'))->group(function () {
    Route::post('/mydevices', [\Maksatsaparbekov\KuleshovAuth\Http\Controllers\DeviceController::class, 'devices'])
    ;

    Route::get('/chats/{chatRoom}/read', [\Maksatsaparbekov\KuleshovAuth\Http\v2\Controllers\ChatController::class, 'makeReadChatMessagesForGivenChatRoom'])
        ->name('makeReadChatMessagesForGivenChatRoom')
        ->where('chatRoom', '[0-9]+');

    Route::get('/chats/{chatRoom}/messages', [\Maksatsaparbekov\KuleshovAuth\Http\v2\Controllers\ChatController::class, 'viewChatMessagesForGivenChatRoom'])
        ->name('viewChatMessagesForGivenChatRoom')
        ->where('chatRoom', '[0-9]+');

    Route::post('/chats/{chatRoom}/messages', [\Maksatsaparbekov\KuleshovAuth\Http\v2\Controllers\ChatController::class, 'createMessageForGivenChatRoom'])
        ->name('createMessageForGivenChatRoom')
        ->where('chatRoom', '[0-9]+');


    Route::post('/{model}/{modelId}/messages', [\Maksatsaparbekov\KuleshovAuth\Http\v2\Controllers\ChatController::class, 'createChatOrMessageForGivenModel'])
        ->name('createChatOrMessageForGivenModel')
        ->where('model', '^(?!chats$).*')
        ->where('modelId', '[0-9]+');

    Route::get('/{model}/{modelId}/chats', [\Maksatsaparbekov\KuleshovAuth\Http\v2\Controllers\ChatController::class, 'viewChatsMessagesOfAllUsersForGivenModel'])
        ->where('model', '^(?!chats$).*')
        ->where('modelId', '[0-9]+')
        ->name('viewChatsMessagesOfAllUsersForGivenModel');

    Route::get('/{model}/{modelId}/chat', [\Maksatsaparbekov\KuleshovAuth\Http\v2\Controllers\ChatController::class, 'viewChatMessagesOfAuthUserForGiventModel'])
        ->name('viewChatMessagesOfAuthUserForGiventModel')
        ->where('model', '^(?!chats$).*')
        ->where('modelId', '[0-9]+');


    Route::get('{model}/auth-user-chats', [\Maksatsaparbekov\KuleshovAuth\Http\v2\Controllers\ChatController::class, 'viewChatMessagesOfAuthUser'])
        ->name('viewChatMessagesOfAuthUser');


    Route::get('/{model}/chats', [\Maksatsaparbekov\KuleshovAuth\Http\v2\Controllers\ChatController::class, 'viewAllChatMessagesForGivenModelType'])
        ->where('model', '^(?!chats$).*')
        ->name('viewAllChatMessagesForGivenModelType');

});


//Route::middleware(['auth.access_token', 'resolveModel'])->group(function () {
Route::prefix('api')->middleware(config('kuleshov-auth.routes.middleware'))->group(function () {


    Route::post('/mydevices', [\Maksatsaparbekov\KuleshovAuth\Http\Controllers\DeviceController::class, 'devices'])
        ;

    Route::get('/chats/{chatRoom}/read', [ChatController::class, 'makeReadChatMessagesForGivenChatRoom'])
        ->name('makeReadChatMessagesForGivenChatRoom1')
        ->where('chatRoom', '[0-9]+');

    Route::get('/chats/{chatRoom}/messages', [ChatController::class, 'viewChatMessagesForGivenChatRoom'])
        ->name('viewChatMessagesForGivenChatRoom1')
        ->where('chatRoom', '[0-9]+');

    Route::post('/chats/{chatRoom}/messages', [ChatController::class, 'createMessageForGivenChatRoom'])
        ->name('createMessageForGivenChatRoom1')
        ->where('chatRoom', '[0-9]+');


    Route::post('/{model}/{modelId}/messages', [ChatController::class, 'createChatOrMessageForGivenModel'])
        ->name('createChatOrMessageForGivenModel1')
        ->where('model', '^(?!chats$).*')
        ->where('modelId', '[0-9]+');

    Route::get('/{model}/{modelId}/chats', [ChatController::class, 'viewChatsMessagesOfAllUsersForGivenModel'])
        ->where('model', '^(?!chats$).*')
        ->where('modelId', '[0-9]+')
        ->name('viewChatsMessagesOfAllUsersForGivenModel1');

    Route::get('/{model}/{modelId}/chat', [ChatController::class, 'viewChatMessagesOfAuthUserForGiventModel'])
        ->name('viewChatMessagesOfAuthUserForGiventModel1')
        ->where('model', '^(?!chats$).*')
        ->where('modelId', '[0-9]+');


    Route::get('{model}/auth-user-chats', [ChatController::class, 'viewChatMessagesOfAuthUser'])
        ->name('viewChatMessagesOfAuthUser1');


    Route::get('/{model}/chats', [ChatController::class, 'viewAllChatMessagesForGivenModelType'])
        ->where('model', '^(?!chats$).*')
        ->name('viewAllChatMessagesForGivenModelType1');


});

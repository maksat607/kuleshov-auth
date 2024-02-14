<?php

use Illuminate\Support\Facades\Route;
use Maksatsaparbekov\KuleshovAuth\Http\Controllers\ChatController;

//Route::prefix('messages')->middleware(['auth.access_token', 'resolveModel'])->group(function () {
////    Route::post('{model}/{id}', [Maksatsaparbekov\KuleshovAuth\Http\Controllers\ChatController::class, 'store'])
////        ->where('id', '[0-9]+');
////    Route::get('{model}/{id}', [Maksatsaparbekov\KuleshovAuth\Http\Controllers\ChatController::class, 'modelMessages'])
////        ->where('id', '[0-9]+');
////    Route::get('{model}/{id}/by-user', [Maksatsaparbekov\KuleshovAuth\Http\Controllers\ChatController::class, 'modelMessagesByUser'])
////        ->where('id', '[0-9]+');
////    Route::get('{model}/by-user', [Maksatsaparbekov\KuleshovAuth\Http\Controllers\ChatController::class, 'userMessages']);
//});

Route::middleware(['auth.access_token', 'resolveModel'])->group(function () {
    Route::post('/{model}/{modelId}/messages', [ChatController::class, 'createMessage'])
        ->name('client.create_message')
        ->where('modelId', '[0-9]+');

    Route::get('/{model}/{modelId}/chats', [ChatController::class, 'modelUserChats'])
        ->name('client.view_chats')
        ->where('modelId', '[0-9]+');
    Route::get('/chats', [ChatController::class, 'userChats'])
        ->name('client.view_all_chats');


    Route::get('/{model}/chats', [ChatController::class, 'viewAllChatsForModel'])
        ->name('manager.view_all_chats');

    // Managers view all messages within a specific chat
    Route::get('/chats/{chatRoom}/messages', [ChatController::class, 'viewChatMessages'])
        ->name('manager.view_chat_messages')
        ->where('chatId', '[0-9]+');

    // Managers send a reply within a chat
    Route::post('/chats/{chatRoom}/messages', [ChatController::class, 'replyToChat'])
        ->name('manager.reply_to_chat')
        ->where('chatId', '[0-9]+');
});

<?php

namespace Maksatsaparbekov\KuleshovAuth\Http\Controllers;

use Maksatsaparbekov\KuleshovAuth\Http\Requests\ChatRequest;
use Maksatsaparbekov\KuleshovAuth\Http\Services\ChatService;
use Maksatsaparbekov\KuleshovAuth\Models\ChatRoom;

class ChatController
{
    protected $chatService;
    protected $model;
    protected $auth_user;

    public function __construct()
    {
        $this->model = request()->modelInstance;
        $this->chatService = new ChatService();
        $this->auth_user = auth()->user();
    }

    public function createMessage(ChatRequest $request)
    {
        $message = $this->chatService->create(
            $this->model,
            $this->auth_user->id,
            $request['content'],
            'text'
        );
        return response()->json(['message' => 'Message created successfully', 'data' => $message], 201);
    }

    public function replyToChat(ChatRequest $request, ChatRoom $chatRoom)
    {
        $message = $this->chatService->joinChat(
            $chatRoom,
            $this->auth_user->id,
            $request['content'],
            'text'
        );
        return response()->json(['message' => 'Message created successfully', 'data' => $message], 201);
    }

    public function modelUserChats()
    {
        return $this->model->chatRooms;
    }

    public function modelMessagesByUser()
    {
        return $this->model->senderChatRoom;
    }

    public function userChats()
    {
        return $this->auth_user->chatRooms;
    }

    public function viewAllChatsForModel()
    {
        return app(request()->modelNamespace)::where('user_id', $this->auth_user->id)->load('chatRooms')->get();
    }

    public function viewChatMessages(ChatRoom $chatRoom)
    {
        return $chatRoom;
    }

}
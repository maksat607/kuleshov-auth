<?php

namespace Maksatsaparbekov\KuleshovAuth\Http\Controllers;

use Maksatsaparbekov\KuleshovAuth\Http\Requests\ChatRequest;
use Maksatsaparbekov\KuleshovAuth\Http\Services\ChatService;

class MessageController
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

    public function store(ChatRequest $request)
    {
        $message = $this->chatService->create(
            $this->model,
            $this->auth_user->id,
            $request['content'],
            'text'
        );
        return response()->json(['message' => 'Message created successfully', 'data' => $message], 201);
    }

    public function modelMessages()
    {
        return $this->model->chatRooms;
    }

    public function modelMessagesByUser()
    {
        return $this->model->senderChatRoom;
    }

    public function userMessages()
    {
        return $this->auth_user->chatRooms;
    }

}
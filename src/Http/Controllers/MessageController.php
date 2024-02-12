<?php
namespace Maksatsaparbekov\KuleshovAuth\Http\Controllers;
use Maksatsaparbekov\KuleshovAuth\Http\Requests\ChatRequest;
use Maksatsaparbekov\KuleshovAuth\Http\Resources\ChatRoomResource;
use Maksatsaparbekov\KuleshovAuth\Http\Services\ChatService;

class MessageController
{
    protected $chatService;

    public function __construct()
    {
        $this->chatService = new ChatService();
    }

    public function store(ChatRequest $request)
    {
        $model = $request->modelInstance; // Now you can use the resolved model instance
        $validated = $request->validated();
        try {
            $message = $this->chatService->create(
                $model,
                auth()->id(),
                $validated['content'],
                'text'
            );

            return response()->json(['message' => 'Message created successfully', 'data' => $message], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create message', 'details' => $e->getMessage()], 500);
        }
    }
    public function index()
    {
        $model = request()->modelInstance;

        $chatRooms = $model->chatRooms->load('messages','messages.user');
        return ChatRoomResource::collection($chatRooms);

    }
}
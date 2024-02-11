<?php
namespace Maksatsaparbekov\KuleshovAuth\Http\Controllers;
use Maksatsaparbekov\KuleshovAuth\Http\Services\ChatService;

class MessageController
{
    protected $chatService;

    public function __construct()
    {
        $this->chatService = new ChatService();
    }

    public function create(ChatRequest $request)
    {
        $validated = $request->validated();

        try {
            $message = $this->chatService->create(
                request()->header('project-security-key'),
                $validated['object'],
                $validated['object_id'],
                $validated['user_id'],
                $validated['content']
            );

            return response()->json(['message' => 'Message created successfully', 'data' => $message], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create message', 'details' => $e->getMessage()], 500);
        }
    }
}
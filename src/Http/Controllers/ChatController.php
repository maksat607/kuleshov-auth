<?php

namespace Maksatsaparbekov\KuleshovAuth\Http\Controllers;

use App\Models\ChatMessages;
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
        $this->modelNamespace = request()->modelNamespace;
        $this->chatService = new ChatService();
        $this->auth_user = auth()->user();
    }


    /**
     * @OA\Get(
     *     path="/chats/{chatRoom}/messages",
     *     operationId="viewChatMessagesForGivenChatRoom",
     *     tags={"Chats"},
     *     summary="View all messages within a specific chat room",
     *     description="Retrieves the chat room details along with all messages from the specified chat room.",
     *     @OA\Parameter(
     *         name="chatRoom",
     *         in="path",
     *         required=true,
     *         description="The ID of the chat room",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/ChatRoom")
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */


    public function viewChatMessagesForGivenChatRoom($chatRoom)
    {

        return ChatRoom::findOrFail($chatRoom);
    }

    /**
     * @OA\Post(
     *     path="/chats/{chatRoom}/messages",
     *     operationId="createMessageForGivenChatRoom",
     *     tags={"Chats"},
     *     summary="Post a message to a specific chat room",
     *     description="Creates a new message in the given chat room and returns the message details.",
     *     @OA\Parameter(
     *         name="chatRoom",
     *         in="path",
     *         description="The ID of the chat room where the message will be posted",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Message content to be posted to the chat room",
     *         @OA\JsonContent(
     *             required={"content"},
     *             @OA\Property(property="content", type="string", description="The content of the message")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/ChatRoomMessage")
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */


    public function createMessageForGivenChatRoom(ChatRequest $request, ChatRoom $chatRoom)
    {
        $message = $this->chatService->joinChat(
            $chatRoom,
            $this->auth_user->id,
            $request['content'],
            'text'
        );
        return response()->json(['message' => 'Message created successfully', 'data' => $message], 201);
    }

    /**
     * @OA\Post(
     *     path="/{model}/{modelId}/messages",
     *     operationId="createChatOrMessageForGivenModel",
     *     tags={"Chats"},
     *     summary="Create a new chat message or chat room for a given model",
     *     description="Creates a new chat message or chat room for the specified model and returns the message details.",
     *     @OA\Parameter(
     *         name="model",
     *         in="path",
     *         required=true,
     *         description="The type of the Eloquent Model (e.g., 'User', 'Project','Application') that are chattable.",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="modelId",
     *         in="path",
     *         required=true,
     *         description="The ID of the model instance related to the chat.",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Message content to be posted to the chat room",
     *         @OA\JsonContent(
     *             required={"content"},
     *             @OA\Property(property="content", type="string", description="The content of the message")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Message created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="A success message"),
     *             @OA\Property(property="data", ref="#/components/schemas/ChatMessage")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */


    public function createChatOrMessageForGivenModel(ChatRequest $request)
    {

        $message = $this->chatService->create(
            $this->model,
            $this->auth_user->id,
            $request['content'],
            'text'
        );
        return response()->json(['message' => 'Message created successfully', 'data' => $message], 201);
    }



    /**
     * @OA\Get(
     *     path="/{model}/{modelId}/chats",
     *     operationId="viewChatsMessagesOfAllUsersForGivenModel",
     *     tags={"Chats"},
     *     summary="View all chats for a given model",
     *     description="Retrieves all chat rooms associated with a specific model and model ID, accessible by managers.",
     *     @OA\Parameter(
     *         name="model",
     *         in="path",
     *         required=true,
     *         description="The type of the Eloquent Model (e.g., 'User', 'Project','Application') that are chattable.",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="modelId",
     *         in="path",
     *         required=true,
     *         description="The unique identifier of the model instance",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ChatRoom")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */



    public function viewChatsMessagesOfAllUsersForGivenModel()
    {
        return $this->model->chatRooms;
    }


    /**
     * @OA\Get(
     *     path="/{model}/{modelId}/chat",
     *     operationId="viewChatMessagesOfAuthUserForGiventModel",
     *     tags={"Chats"},
     *     summary="View chat messages for the authenticated user for a given model",
     *     description="Retrieves chat room details along with messages where the authenticated user is the sender, based on the specified model type and model ID.",
     *     @OA\Parameter(
     *         name="model",
     *         in="path",
     *         required=true,
     *         description="The type of the Eloquent Model (e.g., 'User', 'Project','Application') that are chattable.",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="modelId",
     *         in="path",
     *         required=true,
     *         description="The unique identifier of the model instance.",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/ChatRoom"
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */


    public function viewChatMessagesOfAuthUserForGiventModel()
    {
        return $this->model->senderChatRoom;
    }

    /**
     * @OA\Get(
     *     path="/{model}/auth-user-chats",
     *     operationId="viewChatMessagesOfAuthUser",
     *     tags={"Chats"},
     *     summary="View chat messages for the authenticated user",
     *     description="Retrieves chat room details along with messages where the authenticated user is a participant.",
     *     @OA\Parameter(
     *         name="model",
     *         in="path",
     *         required=true,
     *         description="The type of the Eloquent Model (e.g., 'User', 'Project','Application') that are chattable.",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/ChatRoom"
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */


    public function viewChatMessagesOfAuthUser()
    {
        return $this->auth_user->chatRooms;
    }


    /**
     * @OA\Get(
     *     path="/{model}/chats",
     *     operationId="viewAllChatMessagesForGivenModelType",
     *     tags={"Chats"},
     *     summary="View all chat messages for a given model type",
     *         description="The type of the Eloquent Model (e.g., 'User', 'Project','Application') that are chattable.",
     *     @OA\Parameter(
     *         name="model",
     *         in="path",
     *         required=true,
     *         description="The type of the model (e.g., 'users', 'projects') specifying the context of the chats.",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ChatRoom")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */


    public function viewAllChatMessagesForGivenModelType()
    {
        return ChatRoom::where('chattable_type', $this->modelNamespace)->get();
    }

}
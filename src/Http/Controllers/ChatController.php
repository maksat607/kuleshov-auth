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
     *     summary="Извлекает детали чат-комнаты вместе со всеми сообщениями из указанной чат-комнаты",
     *     description="Это предназначено для приоритетных пользователей (ролей), таких как менеджер или администратор. Они могут просматривать детали любых чатов.",
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
        $this->authorize('viewChatMessagesForGivenChatRoom', $chatRoom);
        return ChatRoom::findOrFail($chatRoom);
    }

    /**
     * @OA\Post(
     *     path="/chats/{chatRoom}/messages",
     *     operationId="createMessageForGivenChatRoom",
     *     tags={"Chats"},
     *     summary="Отправить сообщение в определенный чат",
     *     description="Создает новое сообщение в указанной комнате чата и возвращает детали сообщения.",
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
     *         @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/ChatRoomMessage"
     *         )
     *     ),
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */


    public function createMessageForGivenChatRoom(ChatRequest $request, $chatRoom)
    {
        $chatRoom = ChatRoom::findOrFail($chatRoom);
        $this->authorize('viewChatMessagesForGivenChatRoom', $chatRoom);
        $message = $this->chatService->joinChat(
            $chatRoom,
            $this->auth_user->id,
            $request['content'],
            'text'
        );
        return response()->json($message, 201);
    }

    /**
     * @OA\Post(
     *     path="/{model}/{modelId}/messages",
     *     operationId="createChatOrMessageForGivenModel",
     *     tags={"Chats"},
     *     summary="Создать новое сообщение чата или комнату чата для указанной модели",
     *     description="Создает новую комнату чата и создает сообщение, если комнаты чата для указанной модели не существует. Если комната чата для данной модели и аутентифицированного пользователя уже существует, то просто создается сообщение для существующей комнаты чата.",
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
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/ChatRoomMessage"
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */


    public function createChatOrMessageForGivenModel(ChatRequest $request)
    {
        $this->authorize('viewChatMessagesForGivenChatRoom', ChatRoom::class);
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
     *     summary="Просмотр всех чатов для указанной модели",
     *     description="Извлекает все комнаты чата вместе с сообщениями, связанные с определенной моделью и идентификатором модели, доступные приоритетным пользователям (ролям)",
     *     @OA\Parameter(
     *         name="model",
     *         in="path",
     *         required=true,
     *         description="The type of the Eloquent Model e.g., 'User', 'Project','Application' that are chattable.",
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
        $this->authorize('viewChatMessagesForGivenChatRoom', ChatRoom::class);
        return $this->model->chatRooms;
    }


    /**
     * @OA\Get(
     *     path="/{model}/{modelId}/chat",
     *     operationId="viewChatMessagesOfAuthUserForGiventModel",
     *     tags={"Chats"},
     *     summary="Просмотр сообщений чата для аутентифицированного пользователя для указанной модели",
     *     description="Извлекает детали комнаты чата вместе с сообщениями аутентифицированного пользователя, на основе указанного типа модели и id модели.",
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
        $this->authorize('viewChatMessagesForGivenChatRoom', ChatRoom::class);
        return $this->model->senderChatRoom;
    }

    /**
     * @OA\Get(
     *     path="/{model}/auth-user-chats",
     *     operationId="viewChatMessagesOfAuthUser",
     *     tags={"Chats"},
     *     summary="Просмотр сообщений чата для аутентифицированного пользователя",
     *     description="Извлекает все чаты и детали вместе с сообщениями для указанного типа модели аутентифицированного пользователя.",
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
        $this->authorize('viewChatMessagesForGivenChatRoom', ChatRoom::class);
        return $this->auth_user->chatRooms;
    }


    /**
     * @OA\Get(
     *     path="/{model}/chats",
     *     operationId="viewAllChatMessagesForGivenModelType",
     *     tags={"Chats"},
     *     summary="Просмотр всех чатов с сообщениями для указанного типа модели",
     *     description="Тип Eloquent Model (например, 'User', 'Project', 'Application'), которые могут быть предметом чата. Доступно для приоритетных пользователей (ролей), таких как менеджеры или администраторы.",
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
        $this->authorize('viewChatMessagesForGivenChatRoom', ChatRoom::class);
        return ChatRoom::where('chattable_type', $this->modelNamespace)->get();
    }

}
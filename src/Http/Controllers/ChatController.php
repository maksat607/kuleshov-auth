<?php

namespace Maksatsaparbekov\KuleshovAuth\Http\Controllers;
use App\Models\Application;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use App\Models\ChatMessages;
use Maksatsaparbekov\KuleshovAuth\Http\Requests\ChatRequest;
use Maksatsaparbekov\KuleshovAuth\Http\Services\ChatService;
use Maksatsaparbekov\KuleshovAuth\Jobs\MessageReadJob;
use Maksatsaparbekov\KuleshovAuth\Models\ChatRoom;
use Maksatsaparbekov\KuleshovAuth\Models\ChatRoomMessage;


class ChatController
{
    use AuthorizesRequests;
    protected $chatService;
    protected $model;
    protected $auth_user;

    public function __construct()
    {

        $this->chatService = new ChatService();

    }



    public function makeReadChatMessagesForGivenChatRoom($chatRoom)
    {
        $chatRoom = ChatRoom::findOrFail($chatRoom);

        $this->authorize('viewChatMessagesForGivenChatRoom', $chatRoom);

        $userId = request()->user()->id;
        MessageReadJob::dispatch($userId, $chatRoom)->delay(now()->addSeconds(5));
        return $chatRoom;
    }


    /**
     * @OA\Get(
     *     path="/api/chats/{chatRoom}/messages",
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
        $chatRoom = ChatRoom::findOrFail($chatRoom);
        $this->authorize('viewChatMessagesForGivenChatRoom', $chatRoom);
        return $chatRoom;
    }

    /**
     * @OA\Post(
     *     path="/api/chats/{chatRoom}/messages",
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
     *     security={{"bearerAuth": {}}}
     * )
     */


    public function createMessageForGivenChatRoom(ChatRequest $request, $chatRoom)
    {
        $chatRoom = ChatRoom::findOrFail($chatRoom);
        $this->authorize('createMessageForGivenChatRoom', $chatRoom);
        $message = $this->chatService->joinChat(
            $chatRoom,
            request()->user()->id,
            $request['content'],
            'text'
        );

        $chatRoom->readables()->firstOrCreate(['role'=>'Manager']);

        $chatRoom->chattable->checkableStatuses()->firstOrCreate(['checked'=>0]);

        return response()->json($message, 201);
    }

    /**
     * @OA\Post(
     *     path="/api/{model}/{modelId}/messages",
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
        $this->authorize('createChatOrMessageForGivenModel', new ChatRoom());
        $message = $this->chatService->create(
            request()->modelInstance,
            request()->user()->id,
            $request['content'] ?? '',
            'text'
        );
        $message->chatRoom->readables()->firstOrCreate(['role'=>'Manager']);
        request()->modelInstance->checkableStatuses()->firstOrCreate(['checked'=>0]);

        return response()->json(['message' => 'Message created successfully', 'data' => $message], 201);
    }



    /**
     * @OA\Get(
     *     path="/api/{model}/{modelId}/chats",
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
        $this->authorize('viewChatsMessagesOfAllUsersForGivenModel', new ChatRoom());

        return request()->modelInstance->chatRooms;
    }


    /**
     * @OA\Get(
     *     path="/api/{model}/{modelId}/chat",
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
        $this->authorize('viewChatMessagesOfAuthUserForGiventModel', new ChatRoom());

        if(request()->user()->hasRole(['Admin', 'Manager']) && "vinz.ru" == env('APP_NAME')){
            return request()->modelInstance->chatRooms;
        }else{
            return request()->modelInstance->senderChatRoom;
        }

    }

    /**
     * @OA\Get(
     *     path="/api/{model}/auth-user-chats",
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

        $this->authorize('viewChatMessagesOfAuthUser', new ChatRoom());

        if(request()->user()->hasRole(['Admin', 'Manager']) && "vinz.ru" == env('APP_NAME')){
            $chatRooms = ChatRoom::orderByLatestMessage()
                ->get()
                ->sortByDesc('unread_count')
                ->values();
        }else{
            $chatRooms = request()->user()->chatRooms()->orderByLatestMessage()
                ->get()
                ->sortByDesc('unread_count')
                ->values()
            ;
        }

        $totalUnreadCount = $chatRooms->sum('unread_count');

        foreach ($chatRooms as $chatRoom) {
            $chatRoom->total_unread_count = $totalUnreadCount;
        }

        return $chatRooms;
    }



    /**
     * @OA\Get(
     *     path="/api/{model}/chats",
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
        $this->authorize('viewAllChatMessagesForGivenModelType', new ChatRoom());
        $chatRooms = ChatRoom::where('chattable_type', request()->modelNamespace)
            ->orderByLatestMessage()
            ->get()
            ->sortByDesc('unread_count')
            ->values()
        ;

        $totalUnreadCount = $chatRooms->sum('unread_count');

        foreach ($chatRooms as $chatRoom) {
            $chatRoom->total_unread_count = $totalUnreadCount;
        }

        return $chatRooms;
    }

}

<?php

namespace Maksatsaparbekov\KuleshovAuth\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Maksatsaparbekov\KuleshovAuth\Models\ChatRoom;

class ChatPolicy
{
    use HandlesAuthorization;


    public function viewChatMessagesForGivenChatRoom(User $user, ChatRoom $chatRoom)
    {
        return true;
        // Логика определения того, может ли пользователь просматривать сообщения чата для указанной комнаты чата
        // Например, проверка, является ли пользователь участником чата
        return $user->id === $chatRoom->user_id || $user->isAdmin(); // Замените на вашу логику
    }


    public function createMessageForGivenChatRoom(User $user, ChatRoom $chatRoom)
    {
        return true;
        // Логика определения того, может ли пользователь создать сообщение для указанной комнаты чата
        // Например, проверка, является ли пользователь участником чата
        return $user->id === $chatRoom->user_id; // Замените на вашу логику
    }


    public function createChatOrMessageForGivenModel(User $user, ChatRoom $chatRoom)
    {
        return true;
        // Логика определения того, может ли пользователь создать чат или сообщение для указанной модели
        // Например, проверка, есть ли у пользователя разрешение на создание чатов/сообщений для модели
        return $user->isAdmin() || $user->isManager(); // Замените на вашу логику
    }


    public function viewChatsMessagesOfAllUsersForGivenModel(User $user, ChatRoom $chatRoom)
    {
        return true;
        // Логика определения того, может ли пользователь просматривать сообщения всех пользователей для указанной модели
        // Например, проверка, является ли пользователь администратором или имеет определенные разрешения
        return $user->isAdmin() || $user->isManager(); // Замените на вашу логику
    }


    public function viewChatMessagesOfAuthUserForGiventModel(User $user, ChatRoom $chatRoom)
    {
        return true;
        // Логика определения того, может ли пользователь просматривать сообщения чата аутентифицированного пользователя для указанной модели
        // Например, проверка, является ли пользователь администратором или имеет определенные разрешения
        return $user->isAdmin() || $user->isManager(); // Замените на вашу логику
    }

    public function viewChatMessagesOfAuthUser(User $user, ChatRoom $chatRoom)
    {
        return true;
        // Логика определения того, может ли пользователь просматривать сообщения чата аутентифицированного пользователя
        // Например, проверка, является ли пользователь администратором или имеет определенные разрешения
        return true; // Замените на вашу логику
    }

    public function viewAllChatMessagesForGivenModelType(User $user, ChatRoom $chatRoom)
    {
        return true;
        // Логика определения того, может ли пользователь просматривать все сообщения чата для указанного типа модели
        // Например, проверка, является ли пользователь администратором или имеет определенные разрешения
        return $user->isAdmin() || $user->isManager(); // Замените на вашу логику
    }
}


# Kuleshov Auth

Пакет для Laravel, предназначенный для интеграции с внешними сервисами аутентификации, обеспечивающий бесперебойную синхронизацию с системами третьих сторон для входа в систему и регистрации пользователей, используя специальные токены для безопасного доступа.

## Установка

Через Composer:

####  composer require maksatsaparbekov/kuleshov-auth


## Установите детали вашего сервиса третьей стороны в вашем .env:
KULESHOV_AUTH_URL=https://example.com/api
KULESHOV_AUTH_SECURITY_KEY=SECURITY

Использование
Чтобы избежать конфликтов с трейтом HasApiTokens Sanctum в вашей модели User при интеграции с пакетом Kuleshov Auth, вы можете просто закомментировать или удалить трейт HasApiTokens, если он не нужен для функциональности вашего приложения. Вместо этого вы будете использовать трейт AuthService, предоставляемый Kuleshov Auth, для обработки аутентификации. 
Для использования AuthService в вашей модели User:

use Maksatsaparbekov\KuleshovAuth\Traits\AuthService;

class User extends Authenticatable
{
//       use HasApiTokens
    use AuthService;
}


## Миграции

Выполните миграции для создания необходимых таблиц:

php artisan migrate

## Последний шаг - использование событий модели Eloquent:
## используйте

$user = User::where('phone', $phone)->first();
$user->update([
'password' => Hash::make($password),
]);

вместо использования метода update напрямую на результате запроса:
$user = User::where('phone', $phone)->update([
'password' => Hash::make($password),
]);

Этот подход предпочтительнее, так как он позволяет сначала извлечь экземпляр модели, а затем обновить его. Это важно, потому что таким образом запускаются события модели Eloquent, такие как saving, saved, updating, и updated, что не происходит при использовании второго подхода.
# Kuleshov Auth

A Laravel package for integrating with external authentication services, providing seamless synchronization with third-party systems for user login and registration, leveraging custom tokens for secure access.

## Installation

Via Composer:

composer require maksatsaparbekov/kuleshov-auth


Configuration


Set your third-party service details in your .env:
AUTH_SERVICE_URL=https://example.com/api
PROJECT_SECURITY_KEY=SECURITY

Usage

To use the AuthService in your User model:

use Maksatsaparbekov\KuleshovAuth\Traits\UserTrait;

class User extends Authenticatable
{
    use AuthService;
}


Migrations

Run the migrations to create necessary tables:

php artisan migrate

last thing is to fire eloquent model events use:

$user = User::where('phone', $phone)->first();
$user->update([
'password' => Hash::make($password),
]);
rather then $user = User::where('phone', $phone)->update([
'password' => Hash::make($password),
]);

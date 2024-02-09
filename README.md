
# Kuleshov Auth

Пакет для Laravel, предназначенный для интеграции с внешними сервисами аутентификации, обеспечивающий бесперебойную синхронизацию с системами третьих сторон для входа в систему и регистрации пользователей, используя специальные токены для безопасного доступа.

## Установка

Через Composer:

####  composer require maksatsaparbekov/kuleshov-auth

## Конфигурация

### Опубликуйте файл конфигурации:
php artisan vendor:publish --provider="Maksatsaparbekov\KuleshovAuth\KuleshovAuthServiceProvider"

Установите детали вашего сервиса третьей стороны в вашем .env:
KULESHOV_AUTH_URL=https://example.com/api
KULESHOV_AUTH_SECURITY_KEY=SECURITY

Использование
Чтобы избежать конфликтов с трейтом HasApiTokens Sanctum в вашей модели User при интеграции с пакетом Kuleshov Auth, вы можете просто закомментировать или удалить трейт HasApiTokens, если он не нужен для функциональности вашего приложения. Вместо этого вы будете использовать трейт AuthService, предоставляемый Kuleshov Auth, для обработки аутентификации. 
Для использования UserTrait в вашей модели User:

use Maksatsaparbekov\KuleshovAuth\Traits\AuthService;

class User extends Authenticatable
{
//       use HasApiTokens
    use AuthService;
}


## Миграции

Выполните миграции для создания необходимых таблиц:

php artisan migrate

Последний шаг - использование событий модели Eloquent:

$user = User::where('phone', $phone)->first();
$user->update([
'password' => Hash::make($password),
]);
вместо $user = User::where('phone', $phone)->update([
'password' => Hash::make($password),
]);

# Kuleshov Auth

A Laravel package for integrating with external authentication services, providing seamless synchronization with third-party systems for user login and registration, leveraging custom tokens for secure access.

## Installation

Via Composer:

composer require maksatsaparbekov/kuleshov-auth


Configuration

Publish the configuration file:
php artisan vendor:publish --provider="Maksatsaparbekov\KuleshovAuth\KuleshovAuthServiceProvider"

Set your third-party service details in your .env:
AUTH_SERVICE_URL=https://example.com/api
PROJECT_SECURITY_KEY=SECURITY

Usage

To use the UserTrait in your User model:

use Maksatsaparbekov\KuleshovAuth\Traits\UserTrait;

class User extends Authenticatable
{
    use UserTrait;
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

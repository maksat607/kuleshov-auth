# Kuleshov Auth

A Laravel package for integrating with external authentication services, providing seamless synchronization with third-party systems for user login and registration, leveraging custom tokens for secure access.

## Installation

Via Composer:

```bash
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

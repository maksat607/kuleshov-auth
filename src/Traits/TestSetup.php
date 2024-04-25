<?php

namespace Maksatsaparbekov\KuleshovAuth\Traits;
use Illuminate\Support\Facades\Artisan;

Trait TestSetup
{
    private $user;
    private $tickets;
    private $authAccessToken;
    private $structure;
    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate', ['--database' => 'sqlite', '--path' => 'maksatsaparbekov/kuleshov-auth/database/migrations']);

    }

}
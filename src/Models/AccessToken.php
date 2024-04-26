<?php

namespace Maksatsaparbekov\KuleshovAuth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Maksatsaparbekov\KuleshovAuth\Database\Factories\AccessTokenFactory;


class AccessToken extends Model
{
    use HasFactory;

    protected $table = 'auth__access_tokens';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected static function newFactory()
    {
        return AccessTokenFactory::new();
    }
}


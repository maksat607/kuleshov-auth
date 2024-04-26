<?php

namespace Maksatsaparbekov\KuleshovAuth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Maksatsaparbekov\KuleshovAuth\Database\Factories\AccessTokenFactory;


class AccessToken extends Model
{
    use HasFactory;

    protected $table = 'auth__access_tokens';
    protected $guarded = [];

    public function user()
    {
        if (App::runningUnitTests()) {
            return $this->belongsTo(FakeUser::class);
        }
        return $this->belongsTo(User::class);
    }
    protected static function newFactory()
    {
        return AccessTokenFactory::new();
    }
}


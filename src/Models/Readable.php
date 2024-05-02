<?php

namespace Maksatsaparbekov\KuleshovAuth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Maksatsaparbekov\KuleshovAuth\Models\ChatRoom;

class Readable extends Model
{
    use HasFactory;
    protected $fillable = [
        'readable_id',
        'readable_type',
        'role',
    ];
    public function readable()
    {
        return $this->morphTo();
    }
}
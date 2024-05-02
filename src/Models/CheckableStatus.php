<?php

namespace Maksatsaparbekov\KuleshovAuth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckableStatus extends Model
{
    use HasFactory;
    protected $table = 'chat_room_checkable_statuses';
    protected $guarded = [];


}

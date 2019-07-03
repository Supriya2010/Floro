<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthenticationLog extends Model
{
    protected $fillable = [
        'id','user_id', 'ip_address', 'login_time','browser_agent','logout_time',
    ];
}

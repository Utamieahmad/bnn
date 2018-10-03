<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AuthAPI extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table    = 'auth';
    public $timestamps  = false;
    protected $fillable = [
                          'user_id', 'api_token',
                          'status', 'expired_time'
                          ];
    protected $hidden   = [
                          'api_token',
                          ];
}

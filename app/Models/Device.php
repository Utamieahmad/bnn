<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Device extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table    = 'device';
    public $timestamps  = false;
    protected $fillable = [
                          'user_id', 'device_id',
                          'status'
                          ];
}

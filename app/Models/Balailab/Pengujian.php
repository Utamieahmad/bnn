<?php

namespace App\Models\Balailab;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pengujian extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'balailab_pengujian';
    public $timestamps    = true;
    protected $guarded    = ['id'];

}

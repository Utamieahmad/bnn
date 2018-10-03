<?php

namespace App\Models\Arahan;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Arahan extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'arahan_pimpinan';
    protected $guarded    = ['id'];

}

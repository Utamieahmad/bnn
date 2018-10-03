<?php

namespace App\Models\Arahan;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class VArahan extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'v_arahan_pimpinan';
    protected $guarded    = ['id'];

}

<?php

namespace App\Models\BalaiBesar;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class BalaiBesar extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'balai_besar';
    protected $guarded    = ['id'];

}

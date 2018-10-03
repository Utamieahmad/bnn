<?php

namespace App\Models\Huker;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class RakorAudiensi extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'huker_rakoraudiensi';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

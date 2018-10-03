<?php

namespace App\Models\Irtama;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PenegakanDisiplin extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'irtama_penegakan_disiplin';
    public $timestamps    = true;
    protected $guarded    = ['id'];

}

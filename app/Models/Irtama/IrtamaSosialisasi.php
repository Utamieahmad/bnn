<?php

namespace App\Models\Irtama;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class IrtamaSosialisasi extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'irtama_sosialisasi';
    public $timestamps    = true;
    protected $guarded    = ['id'];

}

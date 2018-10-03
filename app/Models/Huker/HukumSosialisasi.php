<?php

namespace App\Models\Huker;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class HukumSosialisasi extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'hukerhukum_sosialisasi';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

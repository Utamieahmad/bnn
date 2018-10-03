<?php

namespace App\Models\Irtama;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class VIrtamaLHA extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'v_irtama_lha';
    public $timestamps    = true;
    protected $guarded    = ['id_lha'];

}

<?php

namespace App\Models\Cegah;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AdvokasiRakor extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'cegahadvokasi_rakor';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

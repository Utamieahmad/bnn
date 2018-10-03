<?php

namespace App\Models\Cegah;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AdvokasiJejaring extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'cegahadvokasi_jejaring';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

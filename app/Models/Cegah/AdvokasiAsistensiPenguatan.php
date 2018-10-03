<?php

namespace App\Models\Cegah;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AdvokasiAsistensiPenguatan extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'cegahadvokasi_asistensi_penguatan';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

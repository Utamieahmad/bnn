<?php

namespace App\Models\Cegah;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ViewAdvokasiAsistensiPenguatan extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'v_cegahadvokasi_asistensi_penguatan';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

<?php

namespace App\Models\Datin;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ViewDatinPekerjaanJaringan extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'v_datin_pekerjaan_jaringan';
    public $timestamps    = true;
    protected $guarded    = ['id'];

}

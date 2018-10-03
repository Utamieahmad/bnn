<?php

namespace App\Models\Datin;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ViewDatinPengadaanEmail extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'v_datin_pengadaan_email';
    public $timestamps    = true;
    protected $guarded    = ['id'];

}
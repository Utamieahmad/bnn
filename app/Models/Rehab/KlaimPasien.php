<?php

namespace App\Models\Rehab;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class KlaimPasien extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'rehab_klaim_pasien';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

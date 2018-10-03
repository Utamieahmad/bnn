<?php

namespace App\Models\Rehab;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PenilaianLembaga extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'rehab_penilaianlembaga';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

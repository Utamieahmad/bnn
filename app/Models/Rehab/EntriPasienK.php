<?php

namespace App\Models\Rehab;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class EntriPasienK extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $primaryKey = 'field1';
    protected $table      = 'rehabilitasi_entri_pasien_k';
    public $timestamps    = false;
    protected $guarded    = ['field1'];

}

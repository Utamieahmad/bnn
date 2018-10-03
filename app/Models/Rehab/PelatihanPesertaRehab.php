<?php

namespace App\Models\Rehab;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PelatihanPesertaRehab extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $primaryKey = 'id_detail';
    protected $table      = 'rehab_pelatihan_peserta';
    public $timestamps    = false;
    protected $guarded    = ['id_detail'];

}

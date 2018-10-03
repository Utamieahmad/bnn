<?php

namespace App\Models\Rehab;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PascaKlienRawatJalan extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $primaryKey = 'id_detail';
    protected $table      = 'rehab_pasca_klien_detail_rawatjalan';
    public $timestamps    = false;
    protected $guarded    = ['id_detail'];

}

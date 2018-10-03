<?php

namespace App\Models\Rehab;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PascaKlienHeader extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $primaryKey = 'id_header';
    protected $table      = 'rehab_pasca_klien_header';
    public $timestamps    = false;
    protected $guarded    = ['id_header'];

}

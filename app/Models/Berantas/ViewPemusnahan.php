<?php

namespace App\Models\Berantas;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ViewPemusnahan extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'v_berantas_pemusnahan_barangbukti_wilayah';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

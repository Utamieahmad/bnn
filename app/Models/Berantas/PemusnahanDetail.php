<?php

namespace App\Models\Berantas;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PemusnahanDetail extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'berantas_pemusnahan_barangbukti_detail';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

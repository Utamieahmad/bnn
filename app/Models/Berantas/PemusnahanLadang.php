<?php

namespace App\Models\Berantas;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PemusnahanLadang extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'berantas_narkotika_pemusnahan_ladang_ganja';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

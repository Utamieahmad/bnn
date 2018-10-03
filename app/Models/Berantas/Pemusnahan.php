<?php

namespace App\Models\Berantas;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pemusnahan extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'berantas_pemusnahan_barangbukti';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

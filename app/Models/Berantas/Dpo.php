<?php

namespace App\Models\Berantas;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Dpo extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'berantas_dpo';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

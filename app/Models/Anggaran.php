<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Anggaran extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'soa_monev_anggaran';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

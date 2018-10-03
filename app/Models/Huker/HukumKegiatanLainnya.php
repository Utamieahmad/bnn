<?php

namespace App\Models\Huker;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class HukumKegiatanLainnya extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'huker_hukumkegiatanlainnya';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

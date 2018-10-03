<?php

namespace App\Models\Huker;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class HukumPembelaan extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'hukerhukum_pembelaan_hukum';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

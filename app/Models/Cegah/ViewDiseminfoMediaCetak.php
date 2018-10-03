<?php

namespace App\Models\Cegah;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ViewDiseminfoMediaCetak extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'v_cegahdiseminfo_mediacetak';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

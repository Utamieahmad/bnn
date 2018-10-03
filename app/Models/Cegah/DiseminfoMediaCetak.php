<?php

namespace App\Models\Cegah;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class DiseminfoMediaCetak extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'cegahdiseminfo_mediacetak';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

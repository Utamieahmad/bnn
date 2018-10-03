<?php

namespace App\Models\Cegah;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class DiseminfoMediaPenyiaran extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'cegahdiseminfo_mediapenyiaran';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

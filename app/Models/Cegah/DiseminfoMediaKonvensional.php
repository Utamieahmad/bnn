<?php

namespace App\Models\Cegah;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class DiseminfoMediaKonvensional extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'cegahdiseminfo_mediakonvensional';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

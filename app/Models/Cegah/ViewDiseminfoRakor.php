<?php

namespace App\Models\Cegah;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ViewDiseminfoRakor extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'v_cegahdiseminfo_rakor';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

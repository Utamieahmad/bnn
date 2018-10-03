<?php

namespace App\Models\Cegah;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ViewDiseminfoVideotron extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'v_cegahdiseminfo_videotron';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

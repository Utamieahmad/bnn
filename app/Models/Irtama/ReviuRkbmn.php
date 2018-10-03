<?php

namespace App\Models\Irtama;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ReviuRkbmn extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'irtama_reviu_rkbmn';
    public $timestamps    = true;
    protected $guarded    = ['id'];

}

<?php

namespace App\Models\Irtama;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ReviuLk extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'irtama_reviu_lk';
    public $timestamps    = true;
    protected $guarded    = ['id'];

}

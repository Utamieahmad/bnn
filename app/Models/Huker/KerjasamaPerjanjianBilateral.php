<?php

namespace App\Models\Huker;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class KerjasamaPerjanjianBilateral extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'hukerkerjasama_perjanjianbilateral';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

<?php

namespace App\Models\Huker\Perka;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Finalisasi extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'huker_perka_finalisasi';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

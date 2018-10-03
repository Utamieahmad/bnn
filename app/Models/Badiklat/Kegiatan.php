<?php

namespace App\Models\Badiklat;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Kegiatan extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'badiklat_kegiatan';
    public $timestamps    = true;
    protected $guarded    = ['id'];

}

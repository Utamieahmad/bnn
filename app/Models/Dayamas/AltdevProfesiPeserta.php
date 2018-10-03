<?php

namespace App\Models\Dayamas;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AltdevProfesiPeserta extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'dayamas_altdev_alihfungsiprofesi_peserta';
    public $timestamps    = true;
    protected $guarded    = ['id'];

}

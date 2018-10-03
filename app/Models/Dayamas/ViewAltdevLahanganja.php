<?php

namespace App\Models\Dayamas;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ViewAltdevLahanganja extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'v_dayamas_altdev_alihfungsilahanganja';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

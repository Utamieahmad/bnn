<?php

namespace App\Models\Dayamas;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AltdevLahanganjaPetani extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'dayamas_altdev_alihfungsilahanganja_petani';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

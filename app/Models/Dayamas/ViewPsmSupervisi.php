<?php

namespace App\Models\Dayamas;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ViewPsmSupervisi extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'v_dayamas_psm_supervisi';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

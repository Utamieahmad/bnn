<?php

namespace App\Models\Dayamas;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PsmLsm extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'dayamas_psm_lsm';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

<?php

namespace App\Models\Dayamas;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PsmSinergitas extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'dayamas_psm_sinergitas';
    public $timestamps    = true;
    protected $guarded    = ['id'];

}

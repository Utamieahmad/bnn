<?php

namespace App\Models\Dayamas;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ViewMonevKawasanrawan extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'v_dayamas_altdev_monev_kawasanrawan';
    public $timestamps    = true;
    protected $guarded    = ['id'];

}

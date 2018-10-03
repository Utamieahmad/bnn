<?php

namespace App\Models\Datin;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ViewPengecekanJaringan extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'v_datin_pengecekan_jaringan';
    public $timestamps    = true;
    protected $guarded    = ['id'];

}

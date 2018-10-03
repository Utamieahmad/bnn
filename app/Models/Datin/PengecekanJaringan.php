<?php

namespace App\Models\Datin;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PengecekanJaringan extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'datin_pengecekan_jaringan';
    public $timestamps    = true;
    protected $guarded    = ['id'];

}

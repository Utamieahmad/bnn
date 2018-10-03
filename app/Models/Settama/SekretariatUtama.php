<?php

namespace App\Models\Settama;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SekretariatUtama extends Authenticatable
{

    use Notifiable;
    protected $table      = 'sekretariat_utama';
    protected $guarded    = ['id_settama'];
    protected $primaryKey = 'id_settama';

}
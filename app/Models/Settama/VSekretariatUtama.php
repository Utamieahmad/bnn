<?php

namespace App\Models\Settama;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class VSekretariatUtama extends Authenticatable
{
    use Notifiable;
    protected $table      = 'v_sekretariat_utama';
    protected $guarded    = ['id_settama'];

}
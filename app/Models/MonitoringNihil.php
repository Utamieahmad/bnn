<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class MonitoringNihil extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'monitoring_entri_aplikasi';
    public $timestamps    = false;
    protected $guarded    = ['id'];

    

}

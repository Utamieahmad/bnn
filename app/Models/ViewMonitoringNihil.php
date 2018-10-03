<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ViewMonitoringNihil extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'v_monitoring_entri_aplikasi';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

<?php

namespace App\Models\Irtama;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AuditBidangLha extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'irtama_lha_bidang';
    protected $primaryKey      = 'id_lha_bidang';
    protected $guarded    = ['id_lha_bidang'];

}

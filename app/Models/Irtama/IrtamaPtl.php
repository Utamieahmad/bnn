<?php

namespace App\Models\Irtama;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class IrtamaPtl extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'irtama_ptl';
    protected $guarded    = ['id_ptl'];
    protected $primaryKey    = 'id_ptl';

}

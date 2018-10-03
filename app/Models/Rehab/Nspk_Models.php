<?php

namespace App\Models\Rehab;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Nspk_Models extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'rehab_nspk';
    public $timestamps    = true;
    protected $guarded    = ['id'];

}

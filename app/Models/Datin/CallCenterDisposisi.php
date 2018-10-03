<?php

namespace App\Models\Datin;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CallCenterDisposisi extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'soa_callcenter_disposisi';
    public $timestamps    = false;
    protected $guarded    = ['rid'];

}

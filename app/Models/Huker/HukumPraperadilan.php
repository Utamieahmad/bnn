<?php

namespace App\Models\Huker;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class HukumPraperadilan extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table      = 'huker_hukumpraperadilan';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

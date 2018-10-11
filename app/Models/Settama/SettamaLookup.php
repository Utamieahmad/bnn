<?php

namespace App\Models\Settama;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class SettamaLookup extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table      = 'settama_lookup';
    protected $guarded    = ['id_settama_lookup'];
    protected $primaryKey = 'id_settama_lookup';

}

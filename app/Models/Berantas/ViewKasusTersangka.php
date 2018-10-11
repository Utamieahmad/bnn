<?php

namespace App\Models\Berantas;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ViewKasusTersangka extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $primaryKey = 'tersangka_id';
    protected $table      = 'v_berantas_kasus_tersangka';
    public $timestamps    = false;
    protected $guarded    = ['tersangka_id'];

}

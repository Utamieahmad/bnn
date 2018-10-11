<?php

namespace App\Models\Settama;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class SekretariatUtama extends Authenticatable
{

    use Notifiable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table      = 'sekretariat_utama';
    protected $guarded    = ['id_settama'];
    protected $primaryKey = 'id_settama';

}
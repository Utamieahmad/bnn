<?php

namespace App\Models\Badiklat;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kegiatan extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table      = 'badiklat_kegiatan';
    public $timestamps    = true;
    protected $guarded    = ['id'];

}

<?php

namespace App\Models\Huker\Perka;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Harmonisasi extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table      = 'huker_perka_harmonisasi';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

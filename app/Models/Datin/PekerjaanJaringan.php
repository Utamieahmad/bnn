<?php

namespace App\Models\Datin;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PekerjaanJaringan extends Authenticatable
{
    /* @author : Daniel Andi */
    use SoftDeletes;
    use Notifiable;
    protected $table      = 'datin_pekerjaan_jaringan';
    public $timestamps    = true;
    protected $guarded    = ['id'];

}

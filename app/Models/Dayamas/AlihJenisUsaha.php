<?php

namespace App\Models\Dayamas;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AlihJenisUsaha extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $primaryKey = 'usaha_id';
    protected $table      = 'dayamas_alih_jenis_usaha';
    public $timestamps    = false;
    protected $guarded    = ['usaha_id'];

}

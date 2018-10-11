<?php

namespace App\Models\Dayamas;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlihJenisUsaha extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $primaryKey = 'usaha_id';
    protected $table      = 'dayamas_alih_jenis_usaha';
    public $timestamps    = false;
    protected $guarded    = ['usaha_id'];

}

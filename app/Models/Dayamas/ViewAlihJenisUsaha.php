<?php

namespace App\Models\Dayamas;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ViewAlihJenisUsaha extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $primaryKey = 'usaha_id';
    protected $table      = 'v_dayamas_alih_jenis_usaha_instansi_wilayah';
    public $timestamps    = false;
    protected $guarded    = ['usaha_id'];

}

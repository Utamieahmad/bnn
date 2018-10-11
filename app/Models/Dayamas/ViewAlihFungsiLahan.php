<?php

namespace App\Models\Dayamas;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ViewAlihFungsiLahan extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $primaryKey = 'lahan_id';
    protected $table      = 'v_dayamas_alih_fungsi_lahan_instansi_wilayah';
    public $timestamps    = false;
    protected $guarded    = ['lahan_id'];

}

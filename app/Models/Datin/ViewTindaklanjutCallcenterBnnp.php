<?php

namespace App\Models\Datin;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ViewTindaklanjutCallcenterBnnp extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table      = 'v_datin_tindaklanjut_callcenter_bnnpbnnk_wilayah';
    public $timestamps    = false;
    protected $guarded    = ['id'];
    


}

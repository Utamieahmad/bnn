<?php

namespace App\Models\Datin;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ViewTindaklanjutCallcenterBnnp extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'v_datin_tindaklanjut_callcenter_bnnpbnnk_wilayah';
    public $timestamps    = false;
    protected $guarded    = ['id'];
    


}

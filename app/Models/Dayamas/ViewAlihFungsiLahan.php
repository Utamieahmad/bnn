<?php

namespace App\Models\Dayamas;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ViewAlihFungsiLahan extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $primaryKey = 'lahan_id';
    protected $table      = 'v_dayamas_alih_fungsi_lahan_instansi_wilayah';
    public $timestamps    = false;
    protected $guarded    = ['lahan_id'];

}

<?php

namespace App\Models\Dayamas;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ViewAlihJenisProfesi extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $primaryKey = 'profesi_id';
    protected $table      = 'v_dayamas_alih_jenis_profesi_instansi_wilayah';
    public $timestamps    = false;
    protected $guarded    = ['profesi_id'];

}

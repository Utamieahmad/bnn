<?php

namespace App\Models\Dayamas;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ViewTesUjiNarkoba extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $primaryKey = 'peserta_id';
    protected $table      = 'v_dayamas_test_uji_narkoba_instansi_wilayah';
    public $timestamps    = false;
    protected $guarded    = ['peserta_id'];

}

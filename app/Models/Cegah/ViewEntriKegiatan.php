<?php

namespace App\Models\Cegah;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ViewEntriKegiatan extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $primaryKey = 'kegiatan_id';
    protected $table      = 'v_dayamas_kegiatan_instansi_wilayah';
    public $timestamps    = false;
    protected $guarded    = ['kegiatan_id'];

}

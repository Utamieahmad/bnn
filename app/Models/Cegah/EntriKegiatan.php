<?php

namespace App\Models\Cegah;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class EntriKegiatan extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $primaryKey = 'kegiatan_id';
    protected $table      = 'pencegahan_entri_kegiatan';
    public $timestamps    = false;
    protected $guarded    = ['kegiatan_id'];

}

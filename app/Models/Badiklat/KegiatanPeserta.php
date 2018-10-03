<?php

namespace App\Models\Badiklat;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class KegiatanPeserta extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'badiklat_kegiatan_peserta';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

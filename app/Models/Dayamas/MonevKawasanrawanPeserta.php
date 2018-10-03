<?php

namespace App\Models\Dayamas;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class MonevKawasanrawanPeserta extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'dayamas_altdev_monev_kawasanrawan_peserta';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

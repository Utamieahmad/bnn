<?php

namespace App\Models\Dayamas;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TesUjiNarkobaPeserta extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $primaryKey = 'peserta_id';
    protected $table      = 'dayamas_test_uji_narkoba_peserta';
    public $timestamps    = false;
    protected $guarded    = ['peserta_id'];

}

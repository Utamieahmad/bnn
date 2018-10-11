<?php

namespace App\Models\Dayamas;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class TesUjiNarkobaPeserta extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $primaryKey = 'peserta_id';
    protected $table      = 'dayamas_test_uji_narkoba_peserta';
    public $timestamps    = false;
    protected $guarded    = ['peserta_id'];

}

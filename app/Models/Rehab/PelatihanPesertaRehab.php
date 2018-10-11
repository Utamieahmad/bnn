<?php

namespace App\Models\Rehab;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class PelatihanPesertaRehab extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $primaryKey = 'id_detail';
    protected $table      = 'rehab_pelatihan_peserta';
    public $timestamps    = false;
    protected $guarded    = ['id_detail'];

}

<?php

namespace App\Models\Rehab;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class EntriPasien extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $primaryKey = 'pasien_id';
    protected $table      = 'rehabilitasi_entri_pasien';
    public $timestamps    = false;
    protected $guarded    = ['pasien_id'];

}

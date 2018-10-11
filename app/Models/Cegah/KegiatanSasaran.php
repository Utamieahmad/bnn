<?php

namespace App\Models\Cegah;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class KegiatanSasaran extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $primaryKey = 'rel_sasaran_id';
    protected $table      = 'pencegahan_kegiatan_sasaran_rel';
    public $timestamps    = false;
    protected $guarded    = ['rel_sasaran_id'];

}

<?php

namespace App\Models\Berantas;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class KasusBrgBuktiNonnarkotika extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $primaryKey = 'id_aset';
    protected $table      = 'berantas_kasus_barang_bukti_nonnarkotika';
    public $timestamps    = false;
    protected $guarded    = ['id_aset'];

}

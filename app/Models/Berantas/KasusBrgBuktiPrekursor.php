<?php

namespace App\Models\Berantas;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class KasusBrgBuktiPrekursor extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $primaryKey = 'barangbukti_nonnarkotika_id';
    protected $table      = 'berantas_kasus_barang_bukti_prekursor';
    public $timestamps    = false;
    protected $guarded    = ['barangbukti_nonnarkotika_id'];

}

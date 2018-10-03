<?php

namespace App\Models\Berantas;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ViewKasusBrgBuktiPrekursor extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $primaryKey = 'barangbukti_nonnarkotika_id';
    protected $table      = 'v_berantas_kasus_barang_bukti_prekursor';
    public $timestamps    = false;
    protected $guarded    = ['barangbukti_nonnarkotika_id'];

}

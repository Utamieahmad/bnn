<?php

namespace App\Models\Berantas;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ViewKasusBrgBukti extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $primaryKey = 'kasus_barang_bukti_id';
    protected $table      = 'v_berantas_kasus_barang_bukti';
    public $timestamps    = false;
    protected $guarded    = ['kasus_barang_bukti_id'];

}

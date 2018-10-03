<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrgBukti extends Model
{
    //
    public $table       = "v_barang_bukti";
    public $timestamps  = false;
    protected $hidden   = ['id_brgbukti'];
}

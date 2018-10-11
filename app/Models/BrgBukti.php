<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BrgBukti extends Model
{
    //
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    public $table       = "v_barang_bukti";
    public $timestamps  = false;
    protected $hidden   = ['id_brgbukti'];
}

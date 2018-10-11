<?php

namespace App\Models\Dayamas;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ViewTesUjiNarkobaHeader extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $primaryKey = 'header_id';
    protected $table      = 'v_dayamas_test_uji_instansi_wilayah';
    public $timestamps    = false;
    protected $guarded    = ['header_id'];

}

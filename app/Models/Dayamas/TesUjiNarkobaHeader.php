<?php

namespace App\Models\Dayamas;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class TesUjiNarkobaHeader extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $primaryKey = 'header_id';
    protected $table      = 'dayamas_tes_uji_narkoba_header';
    public $timestamps    = false;
    protected $guarded    = ['header_id'];

}

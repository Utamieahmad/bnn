<?php

namespace App\Models\Dayamas;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AlihFungsiLahan extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $primaryKey = 'lahan_id';
    protected $table      = 'dayamas_alih_fungsi_lahan';
    public $timestamps    = false;
    protected $guarded    = ['lahan_id'];

}

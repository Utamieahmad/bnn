<?php

namespace App\Models\Dayamas;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AlihJenisProfesi extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $primaryKey = 'profesi_id';
    protected $table      = 'dayamas_alih_jenis_profesi';
    public $timestamps    = false;
    protected $guarded    = ['profesi_id'];

}

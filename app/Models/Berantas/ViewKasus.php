<?php

namespace App\Models\Berantas;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ViewKasus extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $primaryKey = 'kasus_id';
    protected $table      = 'v_berantas_kasus';
    public $timestamps    = false;
    protected $guarded    = ['kasus_id'];

}

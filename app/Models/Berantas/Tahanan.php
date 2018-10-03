<?php

namespace App\Models\Berantas;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Tahanan extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $primaryKey = 'tahanan_id';
    protected $table      = 'berantas_tahanan';
    public $timestamps    = false;
    protected $guarded    = ['tahanan_id'];

}

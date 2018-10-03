<?php

namespace App\Models\Datin;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ViewPuslitdatinRiset extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'v_puslitdatin_riset';
    public $timestamps    = true;
    protected $guarded    = ['id'];

}

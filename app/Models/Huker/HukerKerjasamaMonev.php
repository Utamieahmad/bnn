<?php

namespace App\Models\Huker;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class HukerKerjasamaMonev extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'huker_kerjasama_monev';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

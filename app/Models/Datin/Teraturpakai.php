<?php

namespace App\Models\Datin;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Teraturpakai extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'datin_research_penyalahguna_teraturpakai';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

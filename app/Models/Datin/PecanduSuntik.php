<?php

namespace App\Models\Datin;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PecanduSuntik extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'datin_research_pecandu_suntik';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

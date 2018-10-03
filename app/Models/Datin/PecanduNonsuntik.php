<?php

namespace App\Models\Datin;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PecanduNonsuntik extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'datin_research_pecandu_nonsuntik';
    public $timestamps    = false;
    protected $guarded    = ['id'];

}

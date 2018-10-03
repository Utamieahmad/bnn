<?php

namespace App\Models\Datin;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SurveyPenyalahgunaNarkoba extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'datin_survey_penyalahguna_narkoba';
    public $timestamps    = true;
    protected $guarded    = ['id'];

}

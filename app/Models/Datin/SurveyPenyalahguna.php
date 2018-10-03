<?php

namespace App\Models\Datin;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SurveyPenyalahguna extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'datin_research_survey_penyalahguna';
    public $timestamps    = true;
    protected $guarded    = ['id'];

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class ViewRapatKerjaPemetaan extends Model
{
    use Notifiable;

    protected $table = 'v_dayamas_rapat_kerja_pemetaan';
     protected $guarded    = ['id'];
}

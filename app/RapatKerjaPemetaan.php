<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class RapatKerjaPemetaan extends Model
{
    use Notifiable;

    protected $table = 'dayamas_rapat_kerja_pemetaan';
     protected $guarded    = ['id'];
}

<?php

namespace App\Models\Rehab;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PasienSirena extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $primaryKey = 'idx';
    protected $table      = 'sirena_t_pasien_assesment';
    public $timestamps    = false;
    protected $guarded    = ['idx'];

}

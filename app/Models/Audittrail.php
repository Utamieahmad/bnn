<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Audittrail extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'audittrail';
    public $timestamps    = false;
    protected $guarded    = ['id'];



}

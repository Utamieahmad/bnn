<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_roles extends Model
{
    /* @author : Daniel Andi */

    public $table       = "user_group";
    public $timestamps  = false;
    protected $hidden   = ['id'];

}

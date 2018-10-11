<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User_roles extends Model
{
    /* @author : Daniel Andi */
   use SoftDeletes;
   protected $dates = ['deleted_at'];
    public $table       = "user_group";
    public $timestamps  = false;
    protected $hidden   = ['id'];

}

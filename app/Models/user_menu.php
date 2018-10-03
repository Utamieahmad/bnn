<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_menu extends Model
{
    /* @author : Daniel Andi */

    public $table       = "v_user_menus";
    public $timestamps  = false;
    protected $hidden   = ['user_id', 'id'];
    
}

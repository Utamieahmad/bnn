<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User_menu extends Model
{
    /* @author : Daniel Andi */

    use SoftDeletes;
    protected $dates = ['deleted_at'];
    public $table       = "v_user_menus";
    public $timestamps  = false;
    protected $hidden   = ['user_id', 'id'];
    
}

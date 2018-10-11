<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group_menu extends Model
{
    /* @author : Daniel Andi */
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    public $table       = "v_group_menu";
    public $timestamps  = false;
    protected $hidden   = ['id'];

}

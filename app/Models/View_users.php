<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class View_users extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
   use SoftDeletes;
   protected $dates = ['deleted_at'];
    protected $primaryKey = 'user_id';
    protected $table      = 'v_rbac_users_groups';
    public $timestamps    = false;
    protected $guarded    = ['user_id'];

}

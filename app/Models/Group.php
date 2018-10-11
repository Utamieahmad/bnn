<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Authenticatable
{
    /* @author : Daniel Andi */
    use Notifiable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table      = 'rbac_groups';
    protected $primaryKey = 'group_id';
    protected $fillable   = ['group_name'];
    public $timestamps    = false;
}

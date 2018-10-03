<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Group extends Authenticatable
{
    /* @author : Daniel Andi */
    use Notifiable;
    protected $table      = 'rbac_groups';
    protected $primaryKey = 'group_id';
    protected $fillable   = ['group_name'];
    public $timestamps    = false;
}

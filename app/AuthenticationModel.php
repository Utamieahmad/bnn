<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AuthenticationModel extends Model
{
  function scopeUsers($user){ 
  $data_users= DB::table('rbac_users')->get();
  return $data_users;
}
}

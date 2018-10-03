<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tr_JnsBrgBukti extends Model
{
	protected $table = 'tr_jnsbrgbukti';
    public function collectBarangBukti(){
    	return $this->hasMany('App\Tr_BrgBukti','kd_jnsbrgbukti','kd_jnsbrgbukti');
    }
}

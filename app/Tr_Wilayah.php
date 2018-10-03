<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tr_Wilayah extends Model
{
    //
    protected $table = 'tr_wilayah';

    public function kabupaten(){
    	return $this->hasMany('App\Tr_Instansi','wil_id_wilayah','id_wilayah');
    }

    public function instansi(){
    	return $this->hasOne('App\Tr_Instansi','id_wilayah','id_wilayah')->where('kd_jnsinst','11');
    }

    public function jenis_wilayah(){
        return $this->hasOne('App\Tr_Jnswilayah','kd_jnswilayah','kd_jnswilayah');
    }
    
}

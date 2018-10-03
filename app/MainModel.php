<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use View_users;

class MainModel extends Model
{
    public function scopeGetPelaksana($query){
    	$query = DB::table('v_instansi') 
    			->join('tr_wilayah','tr_wilayah.id_wilayah','=','v_instansi.id_wilayah')
				->where('v_instansi.kd_jnsinst',11)
				->where('v_instansi.id_wilayah','!=',831)
				->whereNotIn('id_instansi',['1739','1740'])
				->orderBy('tr_wilayah.ordering','ASC')
				->get();
		return $query;
    }

    public function scopeGetUserDetail($query,$id_user){
    	$query = DB::table('v_rbac_users_groups')->where('user_id',$id_user)->first();
    	return $query;
    }

    public function scopeGetPropinsi($query){
    	$query = DB::table('tr_wilayah')->where('kd_jnswilayah','1')
    			->select('id_wilayah','nm_wilayah')
    			->orderBy('nm_wilayah','ASC')
    			->get();
    	return $query;
    }


    public function scopeGetListNegara($query){
    	$query = DB::table('tr_kodenegara')->orderBy('nama_negara','ASC')->get();
    	return $query;
    }
    public function scopeGetKabupaten($query,$id_propinsi = null){
    	// select *, id_wilayah, nm_wilayah from tr_wilayah where kd_jnswilayah = '2' and wil_id_wilayah = 64 order by nm_wilayah;
    	$query = DB::table('tr_wilayah')
    				->where('kd_jnswilayah','2')
    				->select('id_wilayah','nm_wilayah')
    				->where(function($q) use($id_propinsi){
    					if($id_propinsi){
    						return $q->where('wil_id_wilayah',$id_propinsi);
    					}
    				})
    				->orderBy('nm_wilayah','ASC')
    				->get();
    	return $query;
    }

    public function scopeProfile($query){
        $query = View_users::get();
        return $query;
    }
   
}


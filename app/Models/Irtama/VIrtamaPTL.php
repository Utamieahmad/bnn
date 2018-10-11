<?php

namespace App\Models\Irtama;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class VIrtamaPTL extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table      = 'v_irtama_ptl';
    public $timestamps    = false;
    protected $guarded    = ['id_ptl'];


    public $where = [];

    public function irtamaBidangLha($params= array()){

        foreach($params as $key=>$val){
            $this->where[$key] = trim($val);
        }
        return $this->hasMany('App\Models\Irtama\IrtamaLhaBidang','id_lha','id_lha')->where($this->where)->limit(10)->get();
    } 

    public function allIrtamaBidangLha($params= array()){

    	foreach($params as $key=>$val){
    		$this->where[$key] = trim($val);
    	}
    	return $this->hasMany('App\Models\Irtama\IrtamaLhaBidang','id_lha','id_lha')->where($this->where)->get();
    }
}

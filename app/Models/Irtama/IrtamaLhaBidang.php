<?php

namespace App\Models\Irtama;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class IrtamaLhaBidang extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'irtama_lha_bidang';
    protected $guarded    = ['id_lha_bidang'];
    protected $primaryKey    = 'id_lha_bidang';

    public function rekomendasiBidang(){
    	return $this->hasMany('App\Models\Irtama\IrtamaLhaRekomendasi','id_lha_bidang','id_lha_bidang')->limit(10);
    }
}

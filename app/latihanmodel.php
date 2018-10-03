<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class latihanmodel extends Model
{
  function scopeKasus($kasus){
  $isi_data=DB::table('berantas_kasus As a')
                          //->join('v_instansi As d', 'a.id_instansi','=','d.id_instansi')
                          ->join('tr_brgbukti As b', 'a.kasus_jenis','=','b.id_brgbukti')
                          ->join('tr_jnsbrgbukti As c', 'b.kd_jnsbrgbukti','=','c.kd_jnsbrgbukti')
                          ->join('tr_peran As e', 'a.kasus_peran','=','e.kd_peran')
                          ->join('tr_countries As f', 'a.kasus_negara','=','f.country_code')
                          ->join('rbac_users As ru', 'a.created_by','=','ru.user_id')
                          ->select('a.periode','a.periode','a.id_instansi', 'a.kasus_jenis', 'b.idxall', 'b.nm_brgbukti', 'b.kd_jnsbrgbukti')
                          ->limit(1)->first();

return $isi_data;
}
}

<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformers\Json;
use DB;
use App\Models\Settama\SettamaLookup ;
use App\Models\Settama\VSettamaLookup ;
use App\Models\Audittrail ;

class GlobalAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {
        //
    }

    public function lookupValues(Request $request, $type)
    {
        $data = [];
        try {
            $value = DB::table('v_lookup_values_type')
                     ->where('lookup_type', $type)
                     ->orderBy('lookup_value_id', 'asc')
                     ->get();
            foreach ($value as $row) {
              $data[$row->lookup_value] = $row->lookup_name;
            }

            if (!$data){
              return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
            } else {
              return response()->json(Json::response($data, 'sukses', null), 200);
            }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function getPropKab(Request $request)
    {
        try {
            $prop = DB::table('tr_wilayah')
                      ->where('kd_jnswilayah', 1)
                      ->get();
            //$data = array(array());
            foreach ($prop as $row) {
              $kab = DB::table('tr_wilayah')
                       ->leftJoin('tr_jnswilayah as t2', 'tr_wilayah.kd_jnswilayah', '=', 't2.kd_jnswilayah')
                       ->where('wil_id_wilayah', $row->id_wilayah)
                       ->get();

              if (count($kab)) {
                  $data[$row->nm_wilayah] = array();

                  foreach ($kab as $row2) {
                      $data[$row->nm_wilayah][$row2->id_wilayah] = $row2->nm_jnswilayah.' '.$row2->nm_wilayah;
                  }
              }
            }

            if (!$data){
              return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
            } else {
              return response()->json(Json::response($data, 'sukses', null), 200);
            }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function getWilayah(Request $request)
    {
        try {
            $data = array();
            $i = 0;

            $prop = DB::table('tr_wilayah')
                      ->where('kd_jnswilayah', 1)
                      ->get();

            foreach ($prop as $row) {
              $data[$i]['id_wilayah'] = $row->id_wilayah;
              $data[$i]['nm_wilayah'] = $row->nm_wilayah;
              $i += 1;

              $kab = DB::table('tr_wilayah')
                       ->where('wil_id_wilayah', $row->id_wilayah)
                       ->get();
              if (count($kab) > 0) {
                  foreach ($kab as $row2) {
                    $data[$i]['id_wilayah'] = $row2->id_wilayah;
                    $data[$i]['nm_wilayah'] = ' - '.$row2->nm_wilayah;
                    $i += 1;
                  }
              }
            }

             if (!$data){
               return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
             } else {
               return response()->json(Json::response($data, 'sukses', null), 200);
             }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function getSasaran(Request $request)
    {
        try {
            $data = DB::table('sin_sasaran_values')
                     ->get();

             if (!$data){
               return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
             } else {
               return response()->json(Json::response($data, 'sukses', null), 200);
             }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function getNegara(Request $request)
    {
        try {
            $data = DB::table('tr_kodenegara')
                     ->get();

             if (!$data){
               return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
             } else {
               return response()->json(Json::response($data, 'sukses', null), 200);
             }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function getJnsKasus(Request $request)
    {
        try {
            $data = DB::table('tr_jnskasus')
                     ->get();

             if (!$data){
               return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
             } else {
               return response()->json(Json::response($data, 'sukses', null), 200);
             }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function getJnsBrgBukti(Request $request)
    {
        try {
            if ($request->jenis != '') {
                $jns = DB::table('tr_jnsbrgbukti')
                          ->whereIn('kd_jnsbrgbukti', $request->jenis)
                          ->orderBy('kd_jnsbrgbukti')
                          ->get();
            } else {
                $jns = DB::table('tr_jnsbrgbukti')
                          ->orderBy('kd_jnsbrgbukti')
                          ->get();
            }
            //$data = array(array());
            foreach ($jns as $row) {
              $brg = DB::table('v_barang_bukti')
                        ->where('kd_jnsbrgbukti', $row->kd_jnsbrgbukti)
                        ->get();

              if (count($brg)) {
                  $data[$row->nm_jnsbrgbukti] = array();

                  foreach ($brg as $row2) {
                      $data[$row->nm_jnsbrgbukti][$row2->id_brgbukti] = $row2->nm_brgbukti;
                  }
              }
            }

            if (!$data){
              return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
            } else {
              return response()->json(Json::response($data, 'sukses', null), 200);
            }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function getJnsBrgBuktiMobile(Request $request)
    {
        try {
          if ($request->jenis != '') {
              $data = DB::table('v_barang_bukti')
                        ->whereIn('kd_jnsbrgbukti', $request->jenis)
                        ->orderBy('id_brgbukti')
                        ->get();
          } else {
              $data = DB::table('v_barang_bukti')
                        ->orderBy('id_brgbukti')
                        ->get();
          }

            if (!$data){
              return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
            } else {
              return response()->json(Json::response($data, 'sukses', null), 200);
            }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function getInstansi(Request $request)
    {
        try {
            if ($request->wilayah != ''){
                $data = DB::table('v_instansi')
                         ->where([
                                    ['kd_jnsinst', '=', '11'],
                                    ['id_wilayah', '=', $request->wilayah],
                                ])
                         ->orWhere([
                                      ['kd_jnsinst', '=', '11'],
                                      ['wil_id_wilayah', '=', $request->wilayah],
                                  ])
                         ->whereNull('deleted_at')
                         ->orderBy('posisi')
                         ->get();
            } else {
                $data = DB::table('v_instansi')
                         ->where('kd_jnsinst', '11')
                         ->whereNull('deleted_at')
                         ->orderBy('posisi')
                         ->get();
            }
             if (!$data){
               return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
             } else {
               return response()->json(Json::response($data, 'sukses', null), 200);
             }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function getPropinsi(Request $request)
    {
        try {
            $data = DB::table('tr_wilayah')
                     ->where('kd_jnswilayah', 1)
                     ->get();

             if (!$data){
               return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
             } else {
               return response()->json(Json::response($data, 'sukses', null), 200);
             }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function getKabupaten(Request $request)
    {
        try {
            $data = DB::table('tr_wilayah')
                     ->where('kd_jnswilayah', 2)
                     ->orWhere('kd_jnswilayah', 6)
                     ->select('id_wilayah', 'nm_wilayah')
                     ->get();

            // $data = [];
            // foreach ($kab as $row) {
            //   $data[$row->id_wilayah] = $row->nm_wilayah;
            // }
             if (!$data){
               return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
             } else {
               return response()->json(Json::response($data, 'sukses', null), 200);
             }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function getSatuan(Request $request)
    {
        try {
            $data = DB::table('tr_satuan')
                     ->get();

             if (!$data){
               return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
             } else {
               return response()->json(Json::response($data, 'sukses', null), 200);
             }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function getWilayahByParent(Request $request, $parent)
    {
        try {
            $data = DB::table('tr_wilayah')
                    ->leftJoin('tr_jnswilayah as t2', 'tr_wilayah.kd_jnswilayah', '=', 't2.kd_jnswilayah')
                     ->where('wil_id_wilayah', $parent)
                     ->get();

             if (!$data){
               return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
             } else {
               return response()->json(Json::response($data, 'sukses', null), 200);
             }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function getSatkerList(Request $request)
    {
        try {
            $data = DB::table('v_satker_list')->get();

            if (!$data){
              return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
            } else {
              return response()->json(Json::response($data, 'sukses', null), 200);
            }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function getSatkerById(Request $request, $id)
    {
        try {
            $data = DB::select("SELECT tbb.id_instansi as instansi_id,
                                tba.id AS satker_id,
                                tba.satker AS satker,
                                tba.satker_code AS satker_code
                                FROM tr_satker tba, tr_instansi tbb
                                WHERE tbb.id_instansi=".$id." AND (tba.satker = UPPER(REPLACE(REPLACE(REPLACE(REPLACE(tbb.nm_instansi,
                                'BNNP','BADAN NARKOTIKA NASIONAL PROVINSI'),
                                'BNN Kab','BADAN NARKOTIKA NASIONAL KABUPATEN'),
                                'BNN Kota', 'BADAN NARKOTIKA NASIONAL KOTA'),
                                'BNNK','BADAN NARKOTIKA NASIONAL KOTA/KABUPATEN')))");

            if (!$data){
              return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
            } else {
              return response()->json(Json::response($data, 'sukses', null), 200);
            }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function singleLookupValues($param = array()){
       try {
            $data = [];
            $where = json_decode($param,true);
            $value = DB::table('v_lookup_values_type')
                     ->where($where)
                     ->get();
            foreach ($value as $row) {
              $data[$row->lookup_value] = $row->lookup_name;
            }

            if (!$data){
              return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
            } else {
              return response()->json(Json::response($data, 'sukses', null), 200);
            }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function detailInstansi($param = array()){
       try {
            $data = [];
            $where = json_decode($param,true);
            $value = DB::table('v_instansi')
                    ->select('nm_instansi')
                     ->where($where)
                     ->first();
            // foreach ($value as $row) {
              $data[$row->lookup_value] = $value;
            // }

            if (!$data){
              return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
            } else {
              return response()->json(Json::response($data, 'sukses', null), 200);
            }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }


    public function pelaksanaSettama(Request $request){
      $data = [];
      try{
        $param = $request->all();
        $where = [];
        // $param = $request->id_parent;
        if(count($param)){
          foreach($param as $k => $val){
            $where[$k] = $val;
          }
        }
        $where['type'] = 'biro';
        $query = SettamaLookup::where($where)->select('slug','lookup_name', 'id_settama_lookup');

        if($query->count()>0){
          $result  = $query->get();
          return response()->json(Json::response($result, 'sukses', null), 200);
        }else{
          return response()->json(Json::response(null, 'error', $e->getMessage()), 200);
        }
      }catch(\Exception $e) {
          return response()->json(Json::response(null, 'error', $e->getMessage()), 200);
      }
    }
    public function settamaPelaksanaBagian(Request $request){
      $data = [];
      $where = [];
      $param = $request->all();
      if(count($param) > 0 ){
        foreach($param as $k => $val){
          $where[$k] = $val;
        }
      }
      try{

        if(count($where) > 0){
          $query = SettamaLookup::where($where)->select('slug','lookup_name', 'id_settama_lookup');
        }else{
          $query = SettamaLookup::select('slug','lookup_name', 'id_settama_lookup');
        }

        if($query->count()>0){
          $result  = $query->get();
          return response()->json(Json::response($result, 'sukses', null), 200);
        }else{
          return response()->json(Json::response(null, 'error', 'data kosong'), 200);
        }
      }catch(\Exception $e) {
          return response()->json(Json::response(null, 'error', $e->getMessage()), 200);
      }
    }

    public function settamaJenisKegiatan(Request $request){
      $data = [];
      $where = [];
      $param = $request->id_parent;
      if($param){
          $where['id_parent'] = $param;
      }
      try{
        if(count($where) > 0){
          $query = VSettamaLookup::where($where)->select('slug','lookup_name', 'id_lookup','id_parent');
        }else{
          $query = VSettamaLookup::select('slug','lookup_name', 'id_lookup','id_parent');
        }

        if($query->count()>0){
          $result  = $query->get();
          return response()->json(Json::response($result, 'sukses', null), 200);
        }else{
          return response()->json(Json::response(null, 'error', 'data kosong'), 200);
        }
      }catch(\Exception $e) {
          return response()->json(Json::response(null, 'error', $e->getMessage()), 200);
      }
    }

    public function getGroupList(Request $request)
    {
        try {
            $data = DB::table('rbac_groups')->select('group_id','group_name')->get();

            if (!$data){
              return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
            } else {
              return response()->json(Json::response($data, 'sukses', null), 200);
            }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function getKodeTemuan(Request $request)
    {
        try {
            $data = DB::table('irtama_temuan')->get();

            if (!$data){
              return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
            } else {
              return response()->json(Json::response($data, 'sukses', null), 200);
            }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function getKodeRekomendasi(Request $request)
    {
        try {
            $data = DB::table('irtama_rekomendasi')->get();

            if (!$data){
              return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
            } else {
              return response()->json(Json::response($data, 'sukses', null), 200);
            }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function getMedia(Request $request, $jenis, $parent=NULL)
    {
        try {
            $kondisi = array();
            array_push($kondisi, array('jenis', '=', $jenis));
            if ($parent != '') {
                array_push($kondisi, array('parent_id', '=', $parent));
            }
            $data = DB::table('tr_media')->where($kondisi)->get();

             if (!$data){
               return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
             } else {
               return response()->json(Json::response($data, 'sukses', null), 200);
             }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function getKomoditi(Request $request)
    {
        try {
            $data = DB::table('tr_komoditi')->get();

            if (!$data){
              return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
            } else {
              return response()->json(Json::response($data, 'sukses', null), 200);
            }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function inserttrail(Request $request){
      try {
          $request['created_at'] = date("Y-m-d H:i:s");
          $data = Audittrail::create($request->except('api_token'));
          $this->return['eventID'] = $data->id;
          if ($data){
            return response()->json(Json::response($this->return, 'sukses', $request->except('api_token')), 200);
          } else {
            return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
          }
      } catch(\Exception $e) {
          return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

      }
    }


    // public function getRole(Request $request, $menu, $user)
    // {
    //     try {
    //         $data = (new User_menuController())->get_candelete($user, 1)->original;
    //         $menulist2 = array();
    //         foreach ($login['data']['cancreate'] as $m) {
    //           array_push($menulist2, $m['menu_id']);
    //         }
    //         $request->session()->put('cancreate', $menulist2);
    //
    //     } catch(\Exception $e) {
    //         return response()->json(Json::response(null, 'error', $e->getMessage()), 200);
    //
    //     }
    // }

}

<?php

namespace App\Http\Controllers\API\Berantas;

use Illuminate\Http\Request;
use App\Models\Berantas\Kasus;
use App\Models\Berantas\ViewKasus;
use App\Models\Berantas\ViewKasusTersangka as Tersangka;
use App\Models\Berantas\ViewKasusBrgBukti as BrgBukti;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class KasusAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {
        /*
          Request value : tgl_from, tgl_to, lokasi, pelaksana, penyidik, jenis
        */

        $kondisi = array();
        array_push($kondisi, array('status', '=', '1'));
        if ($request->wilayah != '') {
            array_push($kondisi, array('nm_wilayah', 'ilike', '%'.$request->wilayah.'%'));
        }
        if ($request->pelaksana != '') {
            array_push($kondisi, array('nm_instansi', 'ilike', '%'.$request->pelaksana.'%'));
        }
        if ($request->jenis != '') {
            array_push($kondisi, array('nm_brgbukti', 'ilike', '%'.$request->jenis.'%'));
        }
        if ($request->penyidik != '') {
            array_push($kondisi, array('nm_penyidik', 'ilike', '%'.$request->penyidik.'%'));
        }
        if ($request->tgl_from != '') {
            array_push($kondisi, array('kasus_tanggal', '>=', $request->tgl_from));
        }
        if ($request->tgl_to != '' ) {
            array_push($kondisi, array('kasus_tanggal', '<=', $request->tgl_to));
        }
        if ($request->lokasi != '' ) {
            array_push($kondisi, array('kasus_tkp', 'ilike', '%'.$request->lokasi.'%'));
        }
        if ($request->no_lap != '' ) {
            array_push($kondisi, array('kasus_no', 'ilike', '%'.$request->no_lap.'%'));
        }
        if ($request->brgbukti != '' ) {
            array_push($kondisi, array('nm_brgbukti', 'ilike', '%'.$request->brgbukti.'%'));
        }
        if ($request->status_kelengkapan) {
            array_push($kondisi, array('status_kelengkapan', '=', $request->status_kelengkapan));
        }

        $response = array();

        // $total_results = ViewKasus::where($kondisi)->orWhere($kondisi2)->count();

        $qresults = ViewKasus::where($kondisi);
        if ($request->id_wilayah != '') {
          $qresults  = $qresults->where(function ($query) use ($request) {
              $query->where('id_wilayah', '=', $request->id_wilayah)->orWhere('wil_id_wilayah', '=', $request->id_wilayah);
          });
        }
        if ($request->kategori != '') {
          $qresults  = $qresults->where(function ($query) use ($request) {
              $query->where('kategori', '=', $request->kategori)->orWhere('kategori', '=', null);
          });
        }
        $total_results = $qresults->count();

        if ($request->limit==Null) {
            $limit = config('constant.LIMITPAGE');
        } else {
            $limit = $request->limit;
        }
        if ($request->page==Null) {
            $page = 1;
        } else {
            $page = $request->page;
        }
        $offset = ($page-1) * $limit;
        $totalpage = ceil($total_results / $limit);

        // $orderByKey="";
        // $orderByOrder="";
        // $tipe = $request->tipe;
        // $order = $request->order;
        // $tgl_from = $request->tgl_from;
        // $tgl_from = $request->tgl_from;
        // $tgl_to = $request->tgl_to;
        // $no_lap = $request->no_lap;

        // if($tipe && $order){
        //     if($tipe == 'nm_instansi' || $tipe == 'periode'|| $tipe == 'no_lap'){
        //         if($tipe == 'periode'){
        //             $orderByKey = 'kasus_tanggal';
        //             $orderByOrder = $order;
        //         }else{
        //             $orderByKey = $tipe;
        //             $orderByOrder = $order;
        //         }
        //     }else{
        //         $orderByKey = 'kasus_tanggal';
        //         $orderByOrder = $order;
        //     }
        // }else if($order){
        //     $orderByKey = 'kasus_tanggal';
        //     $orderByOrder = $order;
        // }else{
        //     $orderByKey = 'kasus_id';
        //     $orderByOrder = 'desc';

        // }

        if ($request->tipe=='pelaksana'){
          $sort = 'nm_instansi';
        } else if ($request->tipe=='no_lap'){
          $sort = 'kasus_no';
        } else if ($request->tipe=='periode'){
          $sort = 'kasus_tanggal';
        } else if ($request->tipe=='BrgBukti'){
          $sort = 'nm_brgbukti';
        } else if ($request->tipe=='status_kelengkapan'){
          $sort = 'status_kelengkapan';
        } else {
          $sort = 'kasus_id';
        }


        try {
            $kasus = $qresults->orderBy($sort, $request->order)->orderBy('kasus_id', $request->order)->offset($offset)->limit($limit)->get();
            // $kasus = $qresults->orderBy($orderByKey, $orderByOrder)->offset($offset)->limit($limit)->get();
            // $kasus = ViewKasus::where($kondisi)->orWhere($kondisi2)->orderBy('kasus_id', 'desc')->offset($offset)->limit($limit)->get();
            // $kasus = Kasus::where('status', 1)->orderBy('kasus_id', 'desc')->offset($offset)->limit($limit)->get();

            // $kasus = $qresults->orderBy($orderByKey, $orderByOrder)->offset($offset)->limit($limit)->get();
            // $kasus = $qresults->orderBy('kasus_id', 'desc')->offset($offset)->limit($limit)->get();

            $paginate['page']       = $page;
            $paginate['limit']      = $limit;
            $paginate['totalpage']  = $totalpage;
            $paginate['totaldata']  = $total_results;

            foreach ($kasus as $row) {
              $data['eventID']    = $row->kasus_id;
              $data['no_lap']     = $row->kasus_no;
              $data['instansi']   = $row->nm_instansi;
              $data['kasus_tanggal']   = $row->kasus_tanggal;
              // $data['periode']    = $row->periode_bulan.' '.$row->periode_tahun;
              $data['kasus_jenis']   = $row->nm_jnskasus;
              $data['kelompok']   = $row->nm_brgbukti;
              $data['tgl']        = $row->kasus_tanggal;
              $data['tkp']        = $row->kasus_tkp;
              $data['status_kelengkapan']        = $row->status_kelengkapan;
              $data['tersangka']  = Tersangka::select('tersangka_id', 'tersangka_nama', 'kode_jenis_kelamin', 'no_identitas', 'nama_negara', 'tersangka_tempat_lahir', 'tersangka_tanggal_lahir')->where('kasus_id', $row->kasus_id)->get();
              $data['BrgBukti']   = BrgBukti::select('kasus_barang_bukti_id', 'nm_brgbukti', 'jumlah_barang_bukti', 'nm_satuan', 'keterangan')->where('kasus_id', $row->kasus_id)->get();

              array_push($response, $data);
            }

            return response()->json(Json::response($response, 'sukses', null, 200, $paginate), 200);
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function show(Request $request, $id)
    {
        try {
            $data = Kasus::where([['status', 1], ['kasus_id', $id]])->first();

            if (!$data){
              return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
            } else {
              return response()->json(Json::response($data, 'sukses', null), 200);
            }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function store(Request $request)
    {

        try {
            // $request['created_by'] = $request->user_id_token;
            $request['create_date'] = date("Y-m-d H:i:s");
            $data = Kasus::create($request->except('api_token'));
            $response['eventID'] = $data->kasus_id;

            if (!$data){
              return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
            } else {
              return response()->json(Json::response($response, 'sukses', null), 200);
            }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function update(Request $request, $id)
    {

        try {
            $request['updated_by'] = $request->created_by;
            $request['update_date'] = date("Y-m-d H:i:s");
            $data = Kasus::findOrFail($id);
            $data->update($request->except(['api_token', 'kasus_id', 'created_by']));

            if (!$data){
              return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
            } else {
              return response()->json(Json::response(null, 'sukses', null), 200);
            }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $data = Kasus::findOrFail($id);
            $data->delete();
            // $data->update(['status' => 0]); //softdelete

            if (!$data){
              return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
            } else {
              return response()->json(Json::response(null, 'sukses', null), 200);
            }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function getByNoLKN(Request $request)
    {
        try {
            $data = ViewKasus::where('kasus_no', $request->kasus_no)->get();

            if (!$data){
              return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
            } else {
              return response()->json(Json::response($data, 'sukses', null), 200);
            }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function getListLKN(Request $request)
    {
        try {
            $kondisi = array();
            array_push($kondisi, array('kasus_no', '!=', ''));
            array_push($kondisi, array('kasus_no', 'ilike', 'LKN%'));

            $kondisi2 = $kondisi;
            if ($request->id_wilayah != '') {
                array_push($kondisi, array('id_wilayah', '=', $request->id_wilayah));
            }
            if ($request->id_wilayah != '') {
                array_push($kondisi2, array('wil_id_wilayah', '=', $request->id_wilayah));
            }

            $data = ViewKasus::select('kasus_id', 'kasus_no')->where($kondisi)->orWhere($kondisi2)->get();

            if (!$data){
              return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
            } else {
              return response()->json(Json::response($data, 'sukses', null), 200);
            }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

}

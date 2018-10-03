<?php

namespace App\Http\Controllers\API\Dayamas;

use Illuminate\Http\Request;
use App\RapatKerjaPemetaan;
use App\ViewRapatKerjaPemetaan;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class RapatKerjaPemetaanAPIController extends Controller
{
    public function index(Request $request)
    {
        $kondisi = [];
        $type = $request->type;
        if($request->type){
            $kondisi['type'] = strtolower(trim($type));
        }

        $qresults = new ViewRapatKerjaPemetaan;
        if ($request->id_wilayah != '') {
          $qresults  = $qresults->where(function ($query) use ($request) {
              $query->where('id_wilayah', '=', $request->id_wilayah)->orWhere('wil_id_wilayah', '=', $request->id_wilayah);
          });
        }

        if ($request->status != '') {
            array_push($kondisi, array('status', '=', $request->status));
        }
        if ($request->tgl_from != '') {
            array_push($kondisi, array('tanggal_pemetaan', '>=', $request->tgl_from));
        }
        if ($request->tgl_to != '' ) {
            array_push($kondisi, array('tanggal_pemetaan', '<=', $request->tgl_to));
        }
        if ($request->jml_from != '') {
            array_push($kondisi, array('jumlah_peserta', '>=', $request->jml_from));
        }
        if ($request->jml_to != '' ) {
            array_push($kondisi, array('jumlah_peserta', '<=', $request->jml_to));
        }

        if ($request->pelaksana != '' ) {
            array_push($kondisi, array('id_pelaksana', '=', $request->pelaksana));
        }
        if ($request->lokasi != '' ) {
            array_push($kondisi, array('id_lokasi_kegiatan', '=', $request->lokasi));
        }
        if ($request->sasaran != '' ) {
            array_push($kondisi, array('kode_sasaran', '=', $request->sasaran));
        }

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
        $qresults = $qresults->where($kondisi);
        $offset = ($page-1) * $limit;
        $orderByKey="";
        $orderByOrder="";
        $tipe = $request->tipe;
        $order = $request->order;

        if($tipe && $order){
            if(($tipe == 'periode')|| ($tipe == 'jml_peserta') ){
                if($tipe == 'periode'  ){
                    $orderByKey = 'tanggal_pemetaan';
                    $orderByOrder = $order;
                }else if($tipe == 'jml_peserta'  ){
                    $orderByKey = 'jumlah_peserta';
                    $orderByOrder = $order;
                }else{
                    $orderByKey = $tipe;
                    $orderByOrder = $order;
                }
            }else{
                $orderByKey = 'tanggal_pemetaan';
                $orderByOrder = $order;
            }
        }else if($order){
            $orderByKey = 'tanggal_pemetaan';
            $orderByOrder = $order;
        }else{
            $orderByKey = 'id';
            $orderByOrder = 'desc';

        }

        $qresults = $qresults->where($kondisi);
        $total_results = $qresults->count();
        $totalpage = ceil($total_results / $limit);

        if($type){
            try {
                $data = $qresults->orderBy($orderByKey, $orderByOrder)->offset($offset)->limit($limit)->get();
                $paginate['page']       = $page;
                $paginate['limit']      = $limit;
                $paginate['totalpage']  = $totalpage;

                return response()->json(Json::response($data, 'sukses', null, 200, $paginate), 200);
            } catch(\Exception $e) {
                return response()->json(Json::response(null, 'error', $e->getMessage()), 200);
            }
        }
        else{
            return response()->json(Json::response(null, 'error', null), 200);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $data = RapatKerjaPemetaan::where('id', $id)->first();
            // $data = KegiatanPeserta::where([['status', 1], ['tersangka_id', $id]])->first();

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
            $request['created_at'] = date("Y-m-d");
            $data = RapatKerjaPemetaan::create($request->except('api_token'));
            $response['eventID'] = $data->id;
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
            $request['updated_at'] = date("Y-m-d");
            $data = RapatKerjaPemetaan::findOrFail($id);
            $data->update($request->except(['api_token', 'id', 'created_by']));

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
            $data = RapatKerjaPemetaan::findOrFail($id);
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


}

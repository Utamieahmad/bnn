<?php

namespace App\Http\Controllers\API\Dayamas;

use Illuminate\Http\Request;
use App\Models\Dayamas\AltdevLahanganja;
use App\Models\Dayamas\ViewAltdevLahanganja;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class AltdevLahanganjaAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {
        $kondisi = array();
        //array_push($kondisi, array('status', '=', '1'));
        // if ($request->lokasi != '') {
        //     array_push($kondisi, array('lokasi', 'ilike', '%'.$request->lokasi.'%'));
        // }
        // if ($request->kejari != '') {
        //     array_push($kondisi, array('lahan_alamat', 'ilike', '%'.$request->area.'%'));
        // }
        // if ($request->penyidik != '') {
        //     array_push($kondisi, array('nm_jenis_lahan', 'ilike', '%'.$request->jenis.'%'));
        // }
        if($request->pelaksana) {
            array_push($kondisi, array('idpelaksana', 'ilike', '%'.$request->pelaksana.'%'));
        }
        if($request->komoditi) {
            array_push($kondisi, array('meta_kode_komoditi', 'ilike', '%'.$request->komoditi.'%'));
        }

        if($request->penyelenggara) {
            array_push($kondisi, array('meta_kode_penyelenggara', 'ilike', '%'.$request->penyelenggara.'%'));
        }
        if ($request->tgl_from != '') {
            array_push($kondisi, array('tgl_kegiatan', '>=', $request->tgl_from));
        }
        if ($request->tgl_to != '' ) {
            array_push($kondisi, array('tgl_kegiatan', '<=', $request->tgl_to));
        }
        if ($request->status != '' ) {
            array_push($kondisi, array('status', '=', $request->status));
        }

        $total_results = ViewAltdevLahanganja::where($kondisi)->count();

        //$total_results = ViewAltdevLahanganja::count();

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
        $orderByKey="";
        $orderByOrder="";
        $tipe = $request->tipe;
        $order = $request->order;

        if($tipe && $order){
            if($tipe == 'periode' ){
                if($tipe == 'periode' ){
                    $orderByKey = 'tgl_kegiatan';
                    $orderByOrder = $order;
                }else{
                    $orderByKey = $tipe;
                    $orderByOrder = $order;
                }
            }else{
                $orderByKey = 'tgl_kegiatan';
                $orderByOrder = $order;
            }
        }else if($order){
            $orderByKey = 'tgl_kegiatan';
            $orderByOrder = $order;
        }else{
            $orderByKey = 'id';
            $orderByOrder = 'desc';

        }
        $response = array();
        $data = array();
        try {
            $data = ViewAltdevLahanganja::where($kondisi)->orderBy($orderByKey, $orderByOrder)->offset($offset)->limit($limit)->get();
            // $data = AltdevLahanganja::where('status', 1)->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();;
            $paginate['page']       = $page;
            $paginate['limit']      = $limit;
            $paginate['totalpage']  = $totalpage;

            // foreach ($lahan as $row) {
            //   $data = $row;
            //   $metalokasi = json_decode($row->meta_lokasi_lahan,true);
            //   $data['luas_lahan']   = $metalokasi[0]['luas_lahan'];
            //   array_push($response, $data);
            // }
            return response()->json(Json::response($data, 'sukses', null, 200, $paginate), 200);
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function show(Request $request, $id)
    {
        try {
            $data = AltdevLahanganja::where('id', $id)->first();
            // $data = AltdevLahanganja::where([['status', 1], ['tersangka_id', $id]])->first();

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
            $data = AltdevLahanganja::create($request->except('api_token'));
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
            $data = AltdevLahanganja::findOrFail($id);
            $data->update($request->except(['api_token', 'id']));

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
            $data = AltdevLahanganja::findOrFail($id);
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

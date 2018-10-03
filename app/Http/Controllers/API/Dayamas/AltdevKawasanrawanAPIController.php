<?php

namespace App\Http\Controllers\API\Dayamas;

use Illuminate\Http\Request;
use App\Models\Dayamas\AltdevKawasanrawan;
use App\Models\Dayamas\ViewAltdevKawasanrawan;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class AltdevKawasanrawanAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {
        $kondisi = [];
        
        if ($request->tgl_from != '') {
            array_push($kondisi, array('tgl_pelaksanaan', '>=', $request->tgl_from));
        }
        if ($request->tgl_to != '' ) {
            array_push($kondisi, array('tgl_pelaksanaan', '<=', $request->tgl_to));
        }
        if ($request->status != '' ) {
            array_push($kondisi, array('status', '=', $request->status));
        }

        if ($request->pelaksana) {
            array_push($kondisi, array('idpelaksana', '=', $request->pelaksana));
        }
        if ($request->lokasi) {
            array_push($kondisi, array('lokasi_kawasan_rawan', 'ilike', '%'.$request->lokasi.'%'));
        }
        if ($request->geografis) {
            array_push($kondisi, array('kode_geografis', '=', $request->geografis));
        }
        if ($request->luas_from) {
            array_push($kondisi, array('luas_kawasan', '>=', $request->luas_from));
        }
        if ($request->luas_to) {
            array_push($kondisi, array('luas_kawasan', '<=', $request->luas_to));
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

        $orderByKey="";
        $orderByOrder="";
        $tipe = $request->tipe;
        $order = $request->order;

        if($tipe && $order){
            if(($tipe == 'periode') || ($tipe == 'luas' ) ){
                if($tipe == 'periode' ){
                    $orderByKey = 'tgl_pelaksanaan';
                    $orderByOrder = $order;
                }else if($tipe == 'luas' ){
                    $orderByKey = 'luas_kawasan';
                    $orderByOrder = $order;
                }else{
                    $orderByKey = $tipe;
                    $orderByOrder = $order;
                }
            }else{
                $orderByKey = 'tgl_pelaksanaan';
                $orderByOrder = $order;
            }
        }else if($order){
            $orderByKey = 'tgl_pelaksanaan';
            $orderByOrder = $order;
        }else{
            $orderByKey = 'id';
            $orderByOrder = 'desc';

        }

        $offset = ($page-1) * $limit;
        $query = ViewAltdevKawasanrawan::where($kondisi);
        $total_results = $query->count();
        $totalpage = ceil($total_results / $limit);

        try {
            $data = $query->orderBy($orderByKey, $orderByOrder)->offset($offset)->limit($limit)->get();
            // $data = AltdevKawasanrawan::where('status', 1)->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();;
            $paginate['page']       = $page;
            $paginate['limit']      = $limit;
            $paginate['totalpage']  = $totalpage;

            return response()->json(Json::response($data, 'sukses', null, 200, $paginate), 200);
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function show(Request $request, $id)
    {
        try {
            $data = AltdevKawasanrawan::where('id', $id)->first();
            // $data = AltdevKawasanrawan::where([['status', 1], ['tersangka_id', $id]])->first();

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
            $request['created_at'] = date("Y-m-d H:i:s");
            $data = AltdevKawasanrawan::create($request->except('api_token'));
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
            $request['updated_at'] = date("Y-m-d H:i:s");
            $data = AltdevKawasanrawan::findOrFail($id);
            $data->update($request->except(['api_token', 'id','created_by']));

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
            $data = AltdevKawasanrawan::findOrFail($id);
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

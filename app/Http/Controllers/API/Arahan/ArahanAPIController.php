<?php

namespace App\Http\Controllers\API\Arahan;

use Illuminate\Http\Request;
use App\Models\Arahan\Arahan;
use App\Models\Arahan\VArahan;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class ArahanAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {

        $kondisi = array();
        if ($request->satker) {
            array_push($kondisi, array('satker', 'ilike',   '%'.trim($request->satker).'%'));
        }
        if ($request->tgl_from) {
            array_push($kondisi, array('tgl_arahan', '>=', $request->tgl_from));
        }
        if ($request->tgl_to) {
            array_push($kondisi, array('tgl_arahan', '<=', $request->tgl_to));
        }
        if ($request->tgl_from_kadaluarsa) {
            array_push($kondisi, array('tgl_kadaluarsa', '>=', $request->tgl_from_kadaluarsa));
        }
        if ($request->tgl_to_kadaluarsa) {
            array_push($kondisi, array('tgl_kadaluarsa', '<=', $request->tgl_to_kadaluarsa));
        }
        if ($request->status) {
            array_push($kondisi, array('status', '=', $request->status));
        }

        $qresults = VArahan::where($kondisi);

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
        $orderByKey="";
        $orderByOrder="";
        $tipe = $request->tipe;
        $order = $request->order;
        $tgl_from = $request->tgl_from;
        $tgl_to = $request->tgl_to;

        if($tipe && $order){
            if($tipe == 'periode' || $tipe == 'periode_kadaluarsa'){
                if($tipe == 'periode'){
                    $orderByKey = 'tgl_arahan';
                    $orderByOrder = $order;
                }else if($tipe == 'periode_kadaluarsa'){
                    $orderByKey = 'tgl_kadaluarsa';
                    $orderByOrder = $order;
                }else{
                    $orderByKey = $tipe;
                    $orderByOrder = $order;
                }
            }else{
                $orderByKey = 'tgl_arahan';
                $orderByOrder = $order;
            }
        }else if($order){
            $orderByKey = 'tgl_arahan';
            $orderByOrder = $order;
        }else{
            $orderByKey = 'id';
            $orderByOrder = 'desc';
        }
        try {
            $qresults = $qresults->orderBy($orderByKey ,  $orderByOrder)->offset($offset)->limit($limit)->get();
            // $data = Kegiatan::where('status', 1)->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();;
            $paginate['page']       = $page;
            $paginate['limit']      = $limit;
            $paginate['totalpage']  = $totalpage;

            return response()->json(Json::response($qresults, 'sukses', null, 200, $paginate), 200);
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function show(Request $request, $id)
    {
        try {
            $data = VArahan::where('id', $id)->first();
            // $data = Kegiatan::where([['status', 1], ['tersangka_id', $id]])->first();

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
            $data = Arahan::create($request->except('api_token'));
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
            $data = Arahan::findOrFail($id);
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
            $data = Arahan::findOrFail($id);
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

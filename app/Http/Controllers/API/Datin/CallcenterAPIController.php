<?php

namespace App\Http\Controllers\API\Datin;

use Illuminate\Http\Request;
use App\Models\Datin\Callcenter;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class CallcenterAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {
        
        $kondisi = array();
       
        if ($request->tgl_from != '') {
            array_push($kondisi, array('tgl_dibuat', '>=', $request->tgl_from));
        }
        if ($request->tgl_to != '') {
            array_push($kondisi, array('tgl_dibuat', '<=', $request->tgl_to));
        }
        if ($request->agen != '') {
            array_push($kondisi, array('nama_agent', 'ilike', '%'.$request->agen.'%'));
        }
        if ($request->pelapor != '') {
            array_push($kondisi, array('nama_pelapor', 'ilike', '%'.trim($request->pelapor).'%'));
        }
        if ($request->media_contact != '') {
            array_push($kondisi, array('kodejenismedia', '=', trim($request->media_contact)));
        }
        if ($request->status != '') {
            array_push($kondisi, array('status', '=', $request->status));
        }

        $orderByKey="";
        $orderByOrder="";
        $tipe = $request->tipe;
        $order = $request->order;

        if($tipe && $order){
            if(($tipe == 'periode')||($tipe == 'nama_agent')||($tipe == 'nama_pelapor') ){
                if($tipe == 'periode' ){
                    $orderByKey = 'tgl_dibuat';
                    $orderByOrder = $order;
                }else{
                    $orderByKey = $tipe;
                    $orderByOrder = $order;
                }
            }else{
                $orderByKey = 'tgl_dibuat';
                $orderByOrder = $order;
            }
        }else if($order){
            $orderByKey = 'tgl_dibuat';
            $orderByOrder = $order;
        }else{
            $orderByKey = 'id';
            $orderByOrder = 'desc';

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

        $query= Callcenter::where($kondisi);
        $total_results = $query->count();
        $offset = ($page-1) * $limit;
        $totalpage = ceil($total_results / $limit);

        try {
            $data = $query->orderBy($orderByKey, $orderByOrder)->offset($offset)->limit($limit)->get();
            // $data = Callcenter::where('status', 1)->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();;
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
            $data = Callcenter::where('id', $id)->first();
            // $data = Callcenter::where([['status', 1], ['tersangka_id', $id]])->first();

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
            $data = Callcenter::create($request->except('api_token'));
            $response['eventID'] = $data->user_id;
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
            $data = Callcenter::findOrFail($id);
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
            $data = Callcenter::findOrFail($id);
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

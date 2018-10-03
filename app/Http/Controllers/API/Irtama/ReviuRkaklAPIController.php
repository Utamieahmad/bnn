<?php

namespace App\Http\Controllers\API\Irtama;

use Illuminate\Http\Request;
use App\Models\Irtama\ReviuRkakl;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class ReviuRkaklAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {
        $kondisi = array();
        if ($request->no_sprint != '') {
            array_push($kondisi, array('no_sprint', 'ilike', '%'.$request->no_sprint.'%'));
        }
       
        if ($request->tahun_from != '') {
            array_push($kondisi, array('tahun_anggaran', '>=', $request->tahun_from));
        }
        if ($request->tahun_to != '') {
            array_push($kondisi, array('tahun_anggaran', '<=', $request->tahun_to));
        }
        if ($request->ketua_tim != '') {
            array_push($kondisi, array('ketua_tim', 'ilike', '%'.$request->ketua_tim.'%'));
        }
        if ($request->status != '') {
            array_push($kondisi, array('status', '=' ,$request->status));
        }

        $qresults = ReviuRkakl::where($kondisi);

        $total_results = $qresults->count();
        //$total_results = ReviuRkakl::count();

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


        $tipe = $request->tipe;
        $order = $request->order;
        $tahun_from = $request->tahun_from;
        $tahun_to = $request->tahun_to;
        $ketua_tim = $request->ketua_tim;
        $status = $request->status;
        $no_sprint = $request->no_sprint;

        if($tipe && $order){
            if($tipe == 'tahun_anggaran' ){
                $orderByKey = 'tahun_anggaran';
                $orderByOrder = $order;
            }else{
                $orderByKey = $tipe;
                $orderByOrder = $order;
            }
        
            
        }else if($order){
            $orderByKey = 'tanggal_lap';
            $orderByOrder = $order;
        }else{
            $orderByKey = 'id';
            $orderByOrder = 'desc';

        }

        try {
            $data = $qresults->orderBy($orderByKey, $orderByOrder)->offset($offset)->limit($limit)->get();
            // $data = ReviuRkakl::where('status', 1)->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();;
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
            $data = ReviuRkakl::where('id', $id)->first();
            // $data = ReviuRkakl::where([['status', 1], ['tersangka_id', $id]])->first();

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
            $data = ReviuRkakl::create($request->except('api_token'));
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
            $data = ReviuRkakl::findOrFail($id);
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
            $data = ReviuRkakl::findOrFail($id);
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

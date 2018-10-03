<?php

namespace App\Http\Controllers\API\Irtama;

use Illuminate\Http\Request;
use App\Models\Irtama\ReviuLk;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class ReviuLkAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {
        $kondisi = array();
        if ($request->no_sprint != '') {
            array_push($kondisi, array('no_sprint', 'ilike', '%'.$request->no_sprint.'%'));
        }
        if ($request->semester != '') {
            array_push($kondisi, array('semester', '=', $request->semester));
        }
        if ($request->tahun_anggaran != '') {
            array_push($kondisi, array('tahun_anggaran', '=', $request->tahun_anggaran));
        }
        if ($request->ketua_tim != '') {
            array_push($kondisi, array('ketua_tim', 'ilike', '%'.$request->ketua_tim.'%'));
        }
        if ($request->hasil_reviu != '') {
            array_push($kondisi, array($request->hasil_reviu, 'ilike', '%'.$request->hasil_reviu_value.'%'));
        }
        if ($request->hasil_reviu != '') {
            array_push($kondisi, array($request->hasil_reviu, 'ilike', '%'.$request->hasil_reviu_value.'%'));
        }
        if ($request->objek_reviu != '') {
            array_push($kondisi, array($request->objek_reviu, 'ilike', '%'.$request->objek_value.'%'));
        }
         if ($request->status != '') {
            array_push($kondisi, array('status', '=', $request->status));
        }
        $qresults = ReviuLk::where($kondisi);

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
        $limit = $request->limit;
        $order = $request->order;

        if($tipe && $order){
            if($tipe == 'no_sprint'){
                $orderByKey = 'no_sprint';
                $orderByOrder = $order;
            }elseif($tipe == 'objek_reviu'){
                $orderByKey = $request->objek_reviu ;
                $orderByOrder = $order;
            }else{
                $orderByKey = 'tanggal_lap';
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
            $data = $qresults->orderBy($orderByKey, $orderByOrder)->orderBy('id', $orderByOrder)->offset($offset)->limit($limit)->get();
            // $data = ReviuLk::where('status', 1)->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();;
            $paginate['page']       = $page;
            $paginate['limit']      = $limit;
            $paginate['totalpage']  = $totalpage;

            return response()->json(Json::response($data, 'sukses', null, 200, $paginate), 200);
        } catch(\Exception $e) {
            return response()->json(Json::response('page '.$request->page , 'error', $e->getMessage()), 200);

        }
    }

    public function show(Request $request, $id)
    {
        try {
            $data = ReviuLk::where('id', $id)->first();
            // $data = ReviuLk::where([['status', 1], ['tersangka_id', $id]])->first();

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
            $data = ReviuLk::create($request->except('api_token'));
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
            $data = ReviuLk::findOrFail($id);
            $data->update($request->except(['api_token', 'id' ,'created_by']));

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
            $data = ReviuLk::findOrFail($id);
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

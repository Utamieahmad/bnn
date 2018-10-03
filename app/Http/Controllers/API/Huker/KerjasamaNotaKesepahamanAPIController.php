<?php

namespace App\Http\Controllers\API\Huker;

use Illuminate\Http\Request;
use App\Models\Huker\KerjasamaNotaKesepahaman;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class KerjasamaNotaKesepahamanAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {
        $tipe = $request->tipe;
        $order = $request->order;

        $kondisi = array();
        if ($request->jenis_kerjasama != '') {
            array_push($kondisi, array('jenis_kerjasama', 'ilike', '%'.$request->jenis_kerjasama.'%'));
        }
        if ($request->nama_instansi != '') {
            array_push($kondisi, array('nama_instansi', 'ilike' ,'%'.$request->nama_instansi.'%'));
        }
        if ($request->nomor_sprint != '') {
            array_push($kondisi, array('nomor_sprint', 'ilike' ,'%'.$request->nomor_sprint.'%'));
        }
        if ($request->status != '') {
            array_push($kondisi, array('status', '=', $request->status));
        }
        if ($request->tgl_from_ttd != '') {
            array_push($kondisi, array('tgl_ttd', '>=', $request->tgl_from_ttd));
        }
        if ($request->tgl_to_ttd != '') {
            array_push($kondisi, array('tgl_ttd', '<=', $request->tgl_to_ttd));
        }
         if ($request->tgl_from_berakhir != '') {
            array_push($kondisi, array('tgl_berakhir', '>=', $request->tgl_from_berakhir));
        }
        if ($request->tgl_to_berakhir != '') {
            array_push($kondisi, array('tgl_berakhir', '<=', $request->tgl_to_berakhir));
        }

        $qresults = KerjasamaNotaKesepahaman::where($kondisi);

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

        if($tipe && $order){
            $orderByKey = $tipe;
            $orderByOrder = $order;
        }else{
            $orderByKey = 'id';
            $orderByOrder = 'desc';
        }

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

    public function show(Request $request, $id)
    {
        try {
            $data = KerjasamaNotaKesepahaman::where('id', $id)->first();
            // $data = KerjasamaNotaKesepahaman::where([['status', 1], ['tersangka_id', $id]])->first();

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
            $data = KerjasamaNotaKesepahaman::create($request->except('api_token'));
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
            $data = KerjasamaNotaKesepahaman::findOrFail($id);
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
            $data = KerjasamaNotaKesepahaman::findOrFail($id);
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

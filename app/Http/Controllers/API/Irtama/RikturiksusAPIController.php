<?php

namespace App\Http\Controllers\API\Irtama;

use Illuminate\Http\Request;
use App\Models\Irtama\Rikturiksus;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class RikturiksusAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {
        $kondisi = array();
        if ($request->no_sprint != '') {
            array_push($kondisi, array('no_sprint', 'ilike', '%'.$request->no_sprint.'%'));
        }
        if ($request->tgl_from != '') {
            array_push($kondisi, array('tgl_hasil_laporan', '>=', $request->tgl_from));
        }
        if ($request->tgl_to != '') {
            array_push($kondisi, array('tgl_hasil_laporan', '<=', $request->tgl_to));
        }
        if ($request->satker != '') {
            // array_push($kondisi, array('satker', 'ilike', '%'.$request->satker.'%'));
            array_push($kondisi, array('tempatkejadian_idprovinsi', '=', trim($request->satker)));
        }
        if ($request->lokasi != '') {
            array_push($kondisi, array('tempatkejadian_idkabkota', '=', trim($request->lokasi)));
        }
        if ($request->status != '') {
            array_push($kondisi, array('status', '=', $request->status));
        }

        $qresults = Rikturiksus::where($kondisi);

        $total_results = $qresults->count();
        //$total_results = Rikturiksus::count();

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
        $tgl_from = $request->tgl_from;
        $tgl_to = $request->tgl_to;

        if($tipe == 'satker'){
            $tipe = 'tempatkejadian_idprovinsi';
        }else if($tipe == 'lokasi'){
            $tipe = 'tempatkejadian_idkabkota';
        }

        if($tipe && $order){
            if($tipe == 'no_sprint' || $tipe == 'periode'|| $tipe == 'lokasi' ){
                if($tipe == 'periode'){
                    $orderByKey = 'tgl_hasil_laporan';
                    $orderByOrder = $order;
                }else{
                    $orderByKey = $tipe;
                    $orderByOrder = $order;
                }
            }else{
                $orderByKey = 'tgl_hasil_laporan';
                $orderByOrder = $order;
            }
        }else if($order){
            $orderByKey = 'tgl_hasil_laporan';
            $orderByOrder = $order;
        }else{
            $orderByKey = 'id';
            $orderByOrder = 'desc';

        }

        try {
            $data = $qresults->orderBy($orderByKey, $orderByOrder)->offset($offset)->limit($limit)->get();
            // $data = Rikturiksus::where('status', 1)->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();;
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
            $data = Rikturiksus::where('id', $id)->first();
            // $data = Rikturiksus::where([['status', 1], ['tersangka_id', $id]])->first();

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
            $data = Rikturiksus::create($request->except('api_token'));

            if (!$data){
              return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
            } else {
              return response()->json(Json::response(['eventID'=>$data->id], 'sukses', null), 200);
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
            $data = Rikturiksus::findOrFail($id);
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
            $data = Rikturiksus::findOrFail($id);
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

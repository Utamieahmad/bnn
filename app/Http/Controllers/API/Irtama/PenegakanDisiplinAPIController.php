<?php

namespace App\Http\Controllers\API\Irtama;

use Illuminate\Http\Request;
use App\Models\Irtama\PenegakanDisiplin;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class PenegakanDisiplinAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {
        $kondisi = array();
        if ($request->no_laporan != '') {
            array_push($kondisi, array('no_laporan', 'ilike', '%'.$request->no_laporan.'%'));
        }
        if ($request->tgl_from != '') {
            array_push($kondisi, array('tgl_laporan', '>=', $request->tgl_from));
        }
        if ($request->tgl_to != '') {
            array_push($kondisi, array('tgl_laporan', '<=', $request->tgl_to));
        }
        if ($request->kode_satker != '') {
            array_push($kondisi, array('kode_satker', 'ilike', '%'.trim($request->kode_satker).'%'));
        }
        if ($request->status != '') {
            array_push($kondisi, array('status', '=', $request->status));
        }

        $qresults = PenegakanDisiplin::where($kondisi);

        $total_results = $qresults->count();
        //$total_results = PenegakanDisiplin::count();

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
          $key = [
            'no_laporan' => 'No Laporan',
            'periode' => 'Tanggal Laporan',
            'kode_satker' => 'Satker',
            'status' => 'Status',
          ];
        $tipe = $request->tipe;
        $order = $request->order;
        $tgl_from = $request->tgl_from;
        $tgl_from = $request->tgl_from;
        $tgl_to = $request->tgl_to;

        if($tipe && $order){
            if($tipe == 'no_laporan' || $tipe == 'periode'){
                if($tipe == 'periode'){
                    $orderByKey = 'tgl_laporan';
                    $orderByOrder = $order;
                }else{
                    $orderByKey = $tipe;
                    $orderByOrder = $order;
                }
            }else{
                $orderByKey = 'tgl_laporan';
                $orderByOrder = $order;
            }
        }else if($order){
            $orderByKey = 'tgl_laporan';
            $orderByOrder = $order;
        }else{
            $orderByKey = 'id';
            $orderByOrder = 'desc';

        }
        try {
            $data = $qresults->orderBy($orderByKey,$orderByOrder)->offset($offset)->limit($limit)->get();
            // $data = PenegakanDisiplin::where('status', 1)->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();;
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
            $data = PenegakanDisiplin::where('id', $id)->first();
            // $data = PenegakanDisiplin::where([['status', 1], ['tersangka_id', $id]])->first();

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
            $data = PenegakanDisiplin::create($request->except('api_token'));
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
            $data = PenegakanDisiplin::findOrFail($id);
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
            $data = PenegakanDisiplin::findOrFail($id);
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

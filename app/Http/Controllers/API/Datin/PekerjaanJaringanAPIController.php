<?php

namespace App\Http\Controllers\API\Datin;

use Illuminate\Http\Request;
use App\Models\Datin\PekerjaanJaringan;
use App\Models\Datin\ViewDatinPekerjaanJaringan;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class PekerjaanJaringanAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {
        $kondisi = array();
        if ($request->tgl_from != '') {
            array_push($kondisi, array('tgl_pelaporan', '>=', $request->tgl_from));
        }
        if ($request->tgl_to != '') {
            array_push($kondisi, array('tgl_pelaporan', '<=', $request->tgl_to));
        }
        if ($request->status != '') {
            array_push($kondisi, array('status', '=', $request->status));
        }
        if ($request->jns_kegiatan != '') {
            array_push($kondisi, array('jenis_kegiatan', '=', $request->jns_kegiatan));
        }
        if ($request->pelapor != '') {
            array_push($kondisi, array('nama_pelapor', 'ilike', '%'.$request->pelapor.'%'));
        }
        if ($request->satker != '') {
            array_push($kondisi, array('nm_instansi', 'ilike', '%'.$request->satker.'%'));
        }

        $orderByKey="";
        $orderByOrder="";
        $tipe = $request->tipe;
        $order = $request->order;

        if($tipe && $order){
            if(($tipe == 'periode')|| ($tipe == 'pelapor')  ){
                if($tipe == 'periode' ){
                    $orderByKey = 'tgl_pelaporan';
                    $orderByOrder = $order;
                }elseif($tipe == 'pelapor' ){
                    $orderByKey = 'nama_pelapor';
                    $orderByOrder = $order;
                }else{
                    $orderByKey = $tipe;
                    $orderByOrder = $order;
                }
            }else{
                $orderByKey = 'tgl_pelaporan';
                $orderByOrder = $order;
            }
        }else if($order){
            $orderByKey = 'tgl_pelaporan';
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

        $query = ViewDatinPekerjaanJaringan::where($kondisi)->whereNull('deleted_at');

        $total_results = $query->count();
        $offset = ($page-1) * $limit;
        $totalpage = ceil($total_results / $limit);

        try {
            $data = $query->orderBy($orderByKey,$orderByOrder)->offset($offset)->limit($limit)->get();
            // $data = SurveyPenyalahguna::where('status', 1)->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();;
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
            $data = PekerjaanJaringan::where('id', $id)->first();
            // $data = SurveyPenyalahguna::where([['status', 1], ['tersangka_id', $id]])->first();

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
            $data = PekerjaanJaringan::create($request->except('api_token'));
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
            $data = PekerjaanJaringan::findOrFail($id);
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
            $data = PekerjaanJaringan::findOrFail($id);
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

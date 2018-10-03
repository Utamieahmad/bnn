<?php

namespace App\Http\Controllers\API\Irtama;

use Illuminate\Http\Request;
use App\Models\Irtama\ApelUpacara;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class ApelUpacaraAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {
        $kondisi = array();
        if ($request->tgl_from != '') {
            array_push($kondisi, array('tanggal', '>=', $request->tgl_from));
        }
        if ($request->tgl_to != '') {
            array_push($kondisi, array('tanggal', '<=', $request->tgl_to));
        }
        if ($request->kode_satker != '') {
            array_push($kondisi, array('kode_satker', 'ilike', '%'.trim($request->kode_satker).'%'));
        }
        if ($request->hadir_from != '') {
            array_push($kondisi, array('jumlah_hadir', '>=', $request->hadir_from));
        }
        if ($request->hadir_to != '') {
            array_push($kondisi, array('jumlah_hadir', '<=', $request->hadir_to));
        }
        if ($request->absen_from != '') {
            array_push($kondisi, array('jumlah_tidak_hadir', '>=', $request->absen_from));
        }
        if ($request->absen_to != '') {
            array_push($kondisi, array('jumlah_tidak_hadir', '<=', $request->absen_to));
        }
        if ($request->status != '') {
            array_push($kondisi, array('status', '=', $request->status));
        }

        $qresults = ApelUpacara::where($kondisi);

        $total_results = $qresults->count();
        //$total_results = ApelUpacara::count();

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
            if($tipe == 'periode'){
                $orderByKey = 'tanggal';
                $orderByOrder = $order;
            }elseif( $tipe == 'jml_hadir' ){
                $orderByKey = 'jumlah_hadir';
                $orderByOrder = $order;
            }elseif( $tipe == 'jml_tdk_hadir' ){
                $orderByKey = 'jumlah_tidak_hadir';
                $orderByOrder = $order;
            }elseif( $tipe == 'status' ){
                $orderByKey = 'tanggal';
                $orderByOrder = $order;
            }else{
                $orderByKey = $tipe;
                $orderByOrder = $order;
            }
        }else if($order){
            $orderByKey = 'tanggal';
            $orderByOrder = $order;
        }else{
            $orderByKey = 'id';
            $orderByOrder = 'desc';

        }
        try {
            $data = $qresults->orderBy($orderByKey, $orderByOrder)->offset($offset)->limit($limit)->get();
            // $data = ApelUpacara::where('status', 1)->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();;
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
            $data = ApelUpacara::where('id', $id)->first();
            // $data = ApelUpacara::where([['status', 1], ['tersangka_id', $id]])->first();

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
            $data = ApelUpacara::create($request->except('api_token'));
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
            $data = ApelUpacara::findOrFail($id);
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
            $data = ApelUpacara::findOrFail($id);
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

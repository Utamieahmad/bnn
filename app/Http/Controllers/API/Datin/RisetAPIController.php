<?php

namespace App\Http\Controllers\API\Datin;

use Illuminate\Http\Request;
use App\Models\Datin\Riset;
use App\Models\Datin\ViewPuslitdatinRiset;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class RisetAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {
        $kondisi = array();
        if ($request->jml_from != '') {
            array_push($kondisi, array('jumlah_responden', '>=', $request->jml_from));
        }
        if ($request->jml_to != '') {
            array_push($kondisi, array('jumlah_responden', '<=', $request->jml_to));
        }
        if ($request->year_from != '') {
            array_push($kondisi, array('tahun', '>=', $request->year_from));
        }
        if ($request->year_to != '') {
            array_push($kondisi, array('tahun', '<=', $request->year_to));
        }
        if ($request->lokasi != '') {
            array_push($kondisi, array('lokasi', 'ilike', '%'.$request->lokasi.'%'));
        }
        if ($request->judul != '') {
            array_push($kondisi, array('judul', 'ilike', '%'.$request->judul.'%'));
        }
        if ($request->kabupaten != '') {
            array_push($kondisi, array('meta_lokasi', 'ilike', '%"'.$request->kabupaten.'"%'));
        }
        if ($request->status != '') {
            array_push($kondisi, array('status', '=', $request->status));
        }

        $orderByKey="";
        $orderByOrder="";
        $tipe = $request->tipe;
        $order = $request->order;

        if($tipe && $order){
            if(($tipe == 'periode') || ($tipe == 'jml_responden' )|| ($tipe == 'judul' ) || ($tipe == 'kabupaten' ) ){
                if($tipe == 'periode' ){
                    $orderByKey = 'tahun';
                    $orderByOrder = $order;
                }else if($tipe == 'jml_responden' ){
                    $orderByKey = 'jumlah_responden';
                    $orderByOrder = $order;
                }else if($tipe == 'judul' ){
                    $orderByKey = 'judul';
                    $orderByOrder = $order;
                }else if($tipe == 'kabupaten' ){
                    $orderByKey = 'lokasi_idkabkota';
                    $orderByOrder = $order;
                }else{
                    $orderByKey = $tipe;
                    $orderByOrder = $order;
                }
            }else{
                $orderByKey = 'tahun';
                $orderByOrder = $order;
            }
        }else if($order){
            $orderByKey = 'tahun';
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
        $query = ViewPuslitdatinRiset::where($kondisi);

        $total_results = $query->count();
        $offset = ($page-1) * $limit;
        $totalpage = ceil($total_results / $limit);
        try {
            $data = $query->orderBy($orderByKey,$orderByOrder)->offset($offset)->limit($limit)->get();
            // $data = Riset::where('status', 1)->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();;
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
            $data = Riset::where('id', $id)->first();
            // $data = Riset::where([['status', 1], ['tersangka_id', $id]])->first();

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
            $data = Riset::create($request->except('api_token'));
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
            $data = Riset::findOrFail($id);
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
            $data = Riset::findOrFail($id);
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

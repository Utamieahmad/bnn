<?php

namespace App\Http\Controllers\API\Datin;

use Illuminate\Http\Request;
use App\Models\Datin\SurveyPenyalahguna;
use App\Models\Datin\ViewSurveyPenyalahguna;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class SurveyPenyalahgunaAPIController extends Controller
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
        if ($request->judul != '') {
            array_push($kondisi, array('judul_penelitian', 'ilike', '%'.$request->judul.'%'));
        }
        if ($request->kelompok != '') {
            array_push($kondisi, array('kelompok_survey', '=', trim($request->kelompok)));
        }
        if ($request->status != '') {
            array_push($kondisi, array('status', '=', $request->status));
        }

        $orderByKey="";
        $orderByOrder="";
        $tipe = $request->tipe;
        $order = $request->order;

        if($tipe && $order){
            if(($tipe == 'periode') || ($tipe == 'jml_responden' )|| ($tipe == 'judul' ) ){
                if($tipe == 'periode' ){
                    $orderByKey = 'tahun';
                    $orderByOrder = $order;
                }else if($tipe == 'jml_responden' ){
                    $orderByKey = 'jumlah_responden';
                    $orderByOrder = $order;
                }else if($tipe == 'judul' ){
                    $orderByKey = 'judul_penelitian';
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

        $query = ViewSurveyPenyalahguna::where($kondisi);

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
            $data = SurveyPenyalahguna::where('id', $id)->first();
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
            $data = SurveyPenyalahguna::create($request->except('api_token'));
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
            $data = SurveyPenyalahguna::findOrFail($id);
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
            $data = SurveyPenyalahguna::findOrFail($id);
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

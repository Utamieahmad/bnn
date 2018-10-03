<?php

namespace App\Http\Controllers\API\Berantas;

use Illuminate\Http\Request;
use App\Models\Berantas\PemusnahanLadang;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class PemusnahanLadangAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {
        $kondisi = array();
        //array_push($kondisi, array('status', '=', '1'));
        if ($request->nomor_sprint_penyelidikan != '' ) {
            array_push($kondisi, array('nomor_sprint_penyelidikan', 'ilike', '%'.$request->nomor_sprint_penyelidikan.'%'));
        }
        if ($request->tgl_from != '') {
            array_push($kondisi, array('tgl_penyelidikan', '>=', $request->tgl_from));
        }
        if ($request->tgl_to != '' ) {
            array_push($kondisi, array('tgl_penyelidikan', '<=', $request->tgl_to));
        }
        if ($request->luas_from != '') {
            array_push($kondisi, array('luas_lahan_ganja', '>=', $request->luas_from));
        }
        if ($request->luas_to != '' ) {
            array_push($kondisi, array('luas_lahan_ganja', '<=', $request->luas_to));
        }
        if ($request->status != '' ) {
            array_push($kondisi, array('status', '=', $request->status));
        }

        $qresults = PemusnahanLadang::where($kondisi);

        $total_results = $qresults->count();


        // $total_results = PemusnahanLadang::count();

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

        if ($request->tipe=='nomor_sprint_penyelidikan'){
          $sort = 'nomor_sprint_penyelidikan';
        } else if ($request->tipe=='tgl_penyelidikan'){
          $sort = 'tgl_penyelidikan';
        } else if ($request->tipe=='luas_lahan'){
          $sort = 'luas_lahan_ganja';
        } else if ($request->tipe=='status'){
          $sort = 'status';
        } else {
          $sort = 'id';
        }

        try {
            $data = $qresults->orderBy($sort, $request->order)->orderBy('id', $request->order)->offset($offset)->limit($limit)->get();
            // $data = PemusnahanLadang::orderBy('id', 'desc')->offset($offset)->limit($limit)->get();
            // $data = PemusnahanLadang::where('status', 1)->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();;
            $paginate['page']       = $page;
            $paginate['limit']      = $limit;
            $paginate['totalpage']  = $totalpage;
            $paginate['totaldata']  = $total_results;

            return response()->json(Json::response($data, 'sukses', null, 200, $paginate), 200);
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function show(Request $request, $id)
    {
        try {
            $data = PemusnahanLadang::where('id', $id)->first();
            // $data = PemusnahanLadang::where([['status', 1], ['tersangka_id', $id]])->first();

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
            $data = PemusnahanLadang::create($request->except('api_token'));
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
            $data = PemusnahanLadang::findOrFail($id);
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
            $data = PemusnahanLadang::findOrFail($id);
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

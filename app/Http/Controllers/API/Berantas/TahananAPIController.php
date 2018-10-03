<?php

namespace App\Http\Controllers\API\Berantas;

use Illuminate\Http\Request;
use App\Models\Berantas\Tahanan;
use App\Models\Berantas\ViewTahanan;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class TahananAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {
        $kondisi = array();

        $kondisi2 = $kondisi;
        if ($request->id_wilayah != '') {
            array_push($kondisi, array('id_wilayah', '=', $request->id_wilayah));
        }
        if ($request->id_wilayah != '') {
            array_push($kondisi2, array('wil_id_wilayah', '=', $request->id_wilayah));
        }
        if ($request->jenistahanan != '' ) {
            array_push($kondisi, array('kode_jenistahanan', '=', $request->jenistahanan));
        }
        if ($request->nomor_kasus != '' ) {
            array_push($kondisi, array('nomor_kasus', 'ilike', '%'.$request->nomor_kasus.'%'));
        }
        if ($request->tgl_from != '') {
            array_push($kondisi, array('tgl_masuk', '>=', $request->tgl_from));
        }
        if ($request->tgl_to != '' ) {
            array_push($kondisi, array('tgl_masuk', '<=', $request->tgl_to));
        }
        if ($request->status) {
            array_push($kondisi, array('status', '=', $request->status));
        }

        // $total_results = ViewTahanan::where($kondisi)->orWhere($kondisi2)->count();

        $qresults = ViewTahanan::where($kondisi)->orWhere($kondisi2);

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

        if ($request->tipe=='jenistahanan'){
          $sort = 'kode_jenistahanan';
        } else if ($request->tipe=='nomor_kasus'){
          $sort = 'nomor_kasus';
        } else if ($request->tipe=='periode'){
          $sort = 'tgl_masuk';
        } else if ($request->tipe=='nomor_kasus'){
          $sort = 'nomor_kasus';
        } else if ($request->tipe=='status'){
          $sort = 'status';
        } else {
          $sort = 'tahanan_id';
        }

        try {
            $data = $qresults->orderBy($sort, $request->order)->orderBy('tahanan_id', $request->order)->offset($offset)->limit($limit)->get();
            // $data = ViewTahanan::where($kondisi)->orWhere($kondisi2)->orderBy('tahanan_id', 'desc')->offset($offset)->limit($limit)->get();
            // $data = Tahanan::where('status', 1)->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();;
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
            $data = Tahanan::where('tahanan_id', $id)->first();
            // $data = Tersangka::where([['status', 1], ['tersangka_id', $id]])->first();

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
            $request['create_date'] = date("Y-m-d H:i:s");
            $data = Tahanan::create($request->except('api_token'));
            $response['eventID'] = $data->tahanan_id;

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
            $request['update_date'] = date("Y-m-d H:i:s");
            $data = Tahanan::findOrFail($id);
            $data->update($request->except(['api_token', 'tahanan_id', 'created_by']));

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
            $data = Tahanan::findOrFail($id);
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

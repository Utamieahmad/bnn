<?php

namespace App\Http\Controllers\API\Berantas;

use Illuminate\Http\Request;
use App\Models\Berantas\IntelJaringan;
use App\Models\Berantas\ViewIntelJaringan;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class IntelJaringanAPIController extends Controller
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
        if ($request->nomor_lkn != '' ) {
            array_push($kondisi, array('nomor_lkn', 'ilike', '%'.$request->nomor_lkn.'%'));
        }
        if ($request->jenisjaringan != '') {
            array_push($kondisi, array('kode_jenisjaringan', '=', $request->jenisjaringan));
        }
        if ($request->keterlibatan_jaringan != '' ) {
            array_push($kondisi, array('keterlibatan_jaringan', '=', $request->keterlibatan_jaringan));
        }
        if ($request->nama_jaringan != '') {
            array_push($kondisi, array('nama_jaringan', 'ilike', '%'.$request->nama_jaringan.'%'));
        }
        if ($request->status) {
            array_push($kondisi, array('status', '=', $request->status));
        }

        $qresults = ViewIntelJaringan::where($kondisi);

        $total_results = $qresults->count();

        // $total_results = ViewIntelJaringan::where($kondisi)->orWhere($kondisi2)->count();


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

        if ($request->tipe=='nomor_lkn'){
          $sort = 'nomor_lkn';
        } else if ($request->tipe=='jenisjaringan'){
          $sort = 'kode_jenisjaringan';
        } else if ($request->tipe=='keterlibatan_jaringan'){
          $sort = 'keterlibatan_jaringan';
        } else if ($request->tipe=='nama_jaringan'){
          $sort = 'nama_jaringan';
        } else if ($request->tipe=='status'){
          $sort = 'status';
        } else {
          $sort = 'id';
        }

        try {
            $data = $qresults->orderBy($sort, $request->order)->orderBy('id', $request->order)->offset($offset)->limit($limit)->get();
            // $data = ViewIntelJaringan::where($kondisi)->orWhere($kondisi2)->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();
            // $data = IntelJaringan::where('status', 1)->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();
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
            $data = IntelJaringan::where('id', $id)->first();
            // $data = IntelJaringan::where([['status', 1], ['tersangka_id', $id]])->first();

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
            $data = IntelJaringan::create($request->except('api_token'));
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
            $data = IntelJaringan::findOrFail($id);
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
            $data = IntelJaringan::findOrFail($id);
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

    public function getIntelByLKN(Request $request)
    {
        try {
            $data = IntelJaringan::where('nomor_lkn', $request->kasus_no)->get();

            if (!$data){
              return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
            } else {
              return response()->json(Json::response($data, 'sukses', null), 200);
            }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

}

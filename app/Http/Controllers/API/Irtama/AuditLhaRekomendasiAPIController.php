<?php

namespace App\Http\Controllers\API\Irtama;

use Illuminate\Http\Request;
use App\Models\Irtama\AuditRekomendasiBidang;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class AuditLhaRekomendasiAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {
        $total_results = AuditRekomendasiBidang::count();

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

        try {
            $data = AuditRekomendasiBidang::orderBy('id_rekomendasi', 'desc')->offset($offset)->limit($limit)->get();
            // $data = AuditRekomendasiBidang::where('status', 1)->orderBy('id_rekomendasi', 'desc')->offset($offset)->limit($limit)->get();;
            $paginate['page']       = $page;
            $paginate['limit']      = $limit;
            $paginate['totalpage']  = $totalpage;

            return response()->json(Json::response($data, 'sukses', null, 200, $paginate), 200);
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function show(Request $request, $id_rekomendasi)
    {
        try {
            $data = AuditRekomendasiBidang::where('id_rekomendasi', $id_rekomendasi)->first();
            // $data = AuditRekomendasiBidang::where([['status', 1], ['tersangka_id_rekomendasi', $id_rekomendasi]])->first();

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
            $data = AuditRekomendasiBidang::create($request->except('api_token','created_by'));
            $response['eventid_rekomendasi'] = $data->id_rekomendasi;
            if (!$data){
              return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
            } else {
              return response()->json(Json::response($response, 'sukses', null), 200);
            }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function update(Request $request, $id_rekomendasi)
    {
        try {
            $data = AuditRekomendasiBidang::findOrFail($id_rekomendasi);
            $data->update($request->except(['api_token', 'id_rekomendasi','created_by']));
            $response['eventid_rekomendasi'] = $data->id_rekomendasi;
            if (!$data){
              return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
            } else {
              return response()->json(Json::response($response, 'sukses', null), 200);
            }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function destroy(Request $request, $id_rekomendasi)
    {
        try {
            $data = AuditRekomendasiBidang::findOrFail($id_rekomendasi);
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

    public function deleteLhaRekomendasi(Request $request,$id_lha){
        try {
            $data = AuditRekomendasiBidang::where('id_lha_bidang',$id_lha);
            $result = $data->delete();
            // $data->update(['status' => 0]); //softdelete

            if ($result){
              return response()->json(Json::response(null, 'sukses', null, 200));
            } else {
              return response()->json(Json::response(null, 'error', null), 200);
            }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }
}

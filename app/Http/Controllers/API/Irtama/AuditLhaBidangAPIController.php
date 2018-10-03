<?php

namespace App\Http\Controllers\API\Irtama;

use Illuminate\Http\Request;
use App\Models\Irtama\AuditBidangLha;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class AuditLhaBidangAPIController extends Controller
{

    public function index(Request $request)
    {
        $where   = [];
        $total_results = AuditBidangLha::count();

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

        if($request->tipe){
            try {
                $where['tipe'] = $request->tipe;
                $data = AuditBidangLha::where($where)->orderBy('id_lha_bidang', 'desc')->offset($offset)->limit($limit)->get();
                // $data = AuditLha::where('status', 1)->orderBy('id_lha_bidang', 'desc')->offset($offset)->limit($limit)->get();;
                $paginate['page']       = $page;
                $paginate['limit']      = $limit;
                $paginate['totalpage']  = $totalpage;

                return response()->json(Json::response($data, 'sukses', null, 200, $paginate), 200);
            } catch(\Exception $e) {
                return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

            }
        }else{
            return response()->json(Json::response(['message'=>'Tipe harus diisi'], 'error', null), 200);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $data = AuditBidangLha::where('id_lha_bidang', $id)->first();
            // $data = AuditLha::where([['status', 1], ['tersangka_id', $id]])->first();

            if (!$data){
              return response()->json(Json::response(null, 'error', "data kosong", null), 200);
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
            $data = AuditBidangLha::create($request->except('api_token','created_by'));
            $response['eventID'] = $data->id_lha_bidang;
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
            $data = AuditBidangLha::findOrFail($id);
            $data->update($request->except(['api_token', 'id_lha_bidang', 'created_by']));
            $response['eventID'] = $data->id_lha_bidang;
            if (!$data){
              return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
            } else {
              return response()->json(Json::response($response, 'sukses', null), 200);
            }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $data = AuditBidangLha::findOrFail($id);
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


    public function deleteLhaBidang(Request $request,$id_lha){
        try {
            $data = AuditRekomendasiBidang::where('id_lha',$id_lha);
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

    public function detailirtamalhabidang(Request $request){
        $id_lha = $request->id_lha;
        $tipe = $request->tipe;
        try {
            $data = AuditBidangLha::where(['id_lha'=>$id_lha,'tipe'=>$tipe])->get();
            // $data = AuditLha::where([['status', 1], ['tersangka_id', $id]])->first();

            if (!$data){
              return response()->json(Json::response(null, 'error', "data kosong", null), 200);
            } else {
              return response()->json(Json::response($data, 'sukses', null), 200);
            }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }
}

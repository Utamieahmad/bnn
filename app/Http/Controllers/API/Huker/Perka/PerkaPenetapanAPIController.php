<?php

namespace App\Http\Controllers\API\Huker\Perka;

use Illuminate\Http\Request;
use App\Models\Huker\Perka\Penetapan;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class PerkaPenetapanAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {
        $total_results = Penetapan::count();

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
            $data = Penetapan::orderBy('id', 'desc')->offset($offset)->limit($limit)->get();
            // $data = BrgBuktiPrekursor::where('status', 1)->orderBy('kasus_barang_bukti_id', 'desc')->offset($offset)->limit($limit)->get();;
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
            $data = Penetapan::where('id', $id)->first();

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
            $data = Penetapan::create($request->except('api_token'));
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
            $data = Penetapan::findOrFail($id);
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
            $data = Penetapan::findOrFail($id);
            $data->delete();

            if (!$data){
              return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
            } else {
              return response()->json(Json::response(null, 'sukses', null), 200);
            }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function getPenetapan(Request $request, $perka_id)
    {
        try {
            $data = Penetapan::where('id_perka', $perka_id)->first();

            // if($data == null)
            //     $data = [];

            // if (!$data){
            //   return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
            // } else {
              return response()->json(Json::response($data, 'sukses', null), 200);
            // }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

}

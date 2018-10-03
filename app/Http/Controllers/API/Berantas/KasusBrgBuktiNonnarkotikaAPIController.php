<?php

namespace App\Http\Controllers\API\Berantas;

use Illuminate\Http\Request;
use App\Models\Berantas\KasusBrgBuktiNonnarkotika as BrgBuktiNonnarkotika;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class KasusBrgBuktiNonnarkotikaAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {
        $total_results = BrgBuktiNonnarkotika::count();

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
            $data = BrgBuktiNonnarkotika::orderBy('id_aset', 'desc')->offset($offset)->limit($limit)->get();
            // $data = BrgBuktiNonnarkotika::where('status', 1)->orderBy('kasus_barang_bukti_id', 'desc')->offset($offset)->limit($limit)->get();;
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
            $data = BrgBuktiNonnarkotika::where('id_aset', $id)->first();

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
            $data = BrgBuktiNonnarkotika::create($request->except('api_token'));

            if (!$data){
              return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
            } else {
              return response()->json(Json::response(null, 'sukses', null), 200);
            }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function update(Request $request, $id)
    {

        try {
            $data = BrgBuktiNonnarkotika::findOrFail($id);
            $data->update($request->except(['api_token', 'id_aset']));

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
            $data = BrgBuktiNonnarkotika::findOrFail($id);
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

    public function getBrgBuktiAset(Request $request, $kasus_id)
    {
        try {
            if ($request->jenis!=''){
              $data = BrgBuktiNonnarkotika::where('kasus_id', $kasus_id)->where('kode_jenisaset', $request->jenis)->get();
            } else {
              $data = BrgBuktiNonnarkotika::where('kasus_id', $kasus_id)->get();
            }
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

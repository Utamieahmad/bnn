<?php

namespace App\Http\Controllers\API\Berantas;

use Illuminate\Http\Request;
use App\Models\Berantas\KasusBrgBukti as BrgBukti;
use App\Models\Berantas\ViewKasusBrgBukti as VBrgBukti;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class KasusBrgBuktiAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {
        $total_results = VBrgBukti::count();

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
            $data = VBrgBukti::orderBy('kasus_barang_bukti_id', 'desc')->offset($offset)->limit($limit)->get();
            // $data = BrgBukti::where('status', 1)->orderBy('kasus_barang_bukti_id', 'desc')->offset($offset)->limit($limit)->get();;
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
            $data = BrgBukti::where('kasus_barang_bukti_id', $id)->first();

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
            $data = BrgBukti::create($request->except('api_token'));

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
            $data = BrgBukti::findOrFail($id);
            $data->update($request->except(['api_token', 'kasus_barang_bukti_id']));

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
            $data = BrgBukti::findOrFail($id);
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

    public function getBrgBukti(Request $request, $kasus_id)
    {
        try {
            $data = VBrgBukti::where('kasus_id', $kasus_id)->where('id_brgbukti', "!=", null)->whereIn('kd_jnsbrgbukti', ['01', '02', '05'])->get();

            if (!$data){
              return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
            } else {
              return response()->json(Json::response($data, 'sukses', null), 200);
            }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function getBrgBuktiAdiktif(Request $request, $kasus_id)
    {
        try {
            $data = VBrgBukti::where('kasus_id', $kasus_id)->where('id_brgbukti', "!=", null)->whereIn('kd_jnsbrgbukti', ['03', '04', '07', '08', '09', '10', '11', '12', '13', '14'])->get();

            if (!$data){
              return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
            } else {
              return response()->json(Json::response($data, 'sukses', null), 200);
            }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function getBrgBuktiNonnarkotika(Request $request, $kasus_id)
    {
        try {
            $data = VBrgBukti::where('kasus_id', $kasus_id)->where('keterangan', "!=", "")->get();

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

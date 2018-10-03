<?php

namespace App\Http\Controllers\API\Badiklat;

use Illuminate\Http\Request;
use App\Models\Badiklat\KegiatanPeserta;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class KegiatanPesertaAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {
        $kondisi = [];
        if($request->header_id){
            $kondisi['header_id'] = trim($request->header_id);
        }

        if(count($kondisi)>0){
            $total_results = KegiatanPeserta::where($kondisi)->count();
        }else{
            $total_results = KegiatanPeserta::count();
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
        $offset = ($page-1) * $limit;
        $totalpage = ceil($total_results / $limit);
        //belum ada limit
        try {
            if(count($kondisi)>0){
                $data = KegiatanPeserta::where($kondisi)->orderBy('id', 'desc')->offset($offset)->get();
            }else{
                $data = KegiatanPeserta::orderBy('id', 'desc')->offset($offset)->get();
            }
            // $data = KegiatanPeserta::where('status', 1)->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();;
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
            $data = KegiatanPeserta::where('id', $id)->first();
            // $data = KegiatanPeserta::where([['status', 1], ['tersangka_id', $id]])->first();

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
            $data = KegiatanPeserta::create($request->except('api_token'));

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
            $data = KegiatanPeserta::findOrFail($id);
            $data->update($request->except(['api_token', 'id']));

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
            $data = KegiatanPeserta::findOrFail($id);
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
    public function singlePeserta($parent_id ){
         try {
            $data = KegiatanPeserta::where('header_id',$parent_id);
           
            // $data->update(['status' => 0]); //softdelete

            if ($data->count() >= 1){
              return response()->json(Json::response($data->get(),'sukses',null), 200);
            } else {
              return response()->json(Json::response(null, 'error', null), 404);
            }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

}

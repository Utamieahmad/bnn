<?php

namespace App\Http\Controllers\API\Rehab;

use Illuminate\Http\Request;
use App\Models\Rehab\InfoUmumLembaga;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class InfoUmumLembagaAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {
        $kondisi = array();
        if ($request->nama != '' ) {
            array_push($kondisi, array('nama', 'ilike', '%'.$request->nama.'%'));
        }
        if ($request->alamat != '' ) {
            array_push($kondisi, array('alamat', 'ilike', '%'.$request->alamat.'%'));
        }
        if ($request->cp_nama != '' ) {
            array_push($kondisi, array('cp_nama', 'ilike', '%'.$request->cp_nama.'%'));
        }
        if ($request->bentuk_layanan != '' ) {
            array_push($kondisi, array('bentuk_layanan', 'ilike', '%'.$request->bentuk_layanan.'%'));
        }
        if ($request->status != '') {
            array_push($kondisi, array('status', '=', $request->status));
        }

        $response = array();

        $qresults = InfoUmumLembaga::where($kondisi);

        if ($request->kategori != '') {
          $qresults  = $qresults->where(function ($query) use ($request) {
              $query->where('kategori', '=', $request->kategori)->orWhere('kategori', '=', null);
          });
        }

        $total_results = $qresults->count();


        // $qresults = InfoUmumLembaga::where($kondisi);

        // if($request->kategori){
        //     $total_results = InfoUmumLembaga::where(['kategori'=>$request->kategori])->count();
        // }else{
        //     $total_results = InfoUmumLembaga::count();
        // }


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

         if ($request->tipe=='nama'){
          $sort = 'nama';
        } else if ($request->tipe=='alamat'){
          $sort = 'alamat';
        } else if ($request->tipe=='cp_nama'){
          $sort = 'cp_nama';
        } else if ($request->tipe=='bentuk_layanan'){
          $sort = 'bentuk_layanan';
        } else if ($request->tipe=='status'){
          $sort = 'status';
        } else {
          $sort = 'id';
        }

        try {
            $data = $qresults->orderBy($sort, $request->order)->orderBy('id', $request->order)->offset($offset)->limit($limit)->get();
            // if($request->kategori){
            //     $data = InfoUmumLembaga::where(['kategori'=>$request->kategori])->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();
            // }else{
            //     $data = InfoUmumLembaga::orderBy('id', 'desc')->offset($offset)->limit($limit)->get();
            // }
            // $data = InfoUmumLembaga::where('status', 1)->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();;
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
            $data = InfoUmumLembaga::where('id', $id)->first();
            // $data = InfoUmumLembaga::where([['status', 1], ['tersangka_id', $id]])->first();

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
            $data = InfoUmumLembaga::create($request->except('api_token'));
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
            $data = InfoUmumLembaga::findOrFail($id);
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
            $data = InfoUmumLembaga::findOrFail($id);
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

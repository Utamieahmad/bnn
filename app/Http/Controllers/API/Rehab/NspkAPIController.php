<?php

namespace App\Http\Controllers\API\Rehab;

use Illuminate\Http\Request;
use App\Models\Rehab\Nspk_Models as Nspk;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class NspkAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {
        $kondisi = array();
        if ($request->tgl_from != '') {
            array_push($kondisi, array('tgl_pembuatan', '>=', $request->tgl_from));
        }
        if ($request->tgl_to != '' ) {
            array_push($kondisi, array('tgl_pembuatan', '<=', $request->tgl_to));
        }
        if ($request->nama_nspk != '' ) {
            array_push($kondisi, array('nama_nspk', 'ilike', '%'.$request->nama_nspk.'%'));
        }
        if ($request->nomor_nsdpk != '' ) {
            array_push($kondisi, array('nomor_nsdpk', 'ilike', '%'.$request->nomor_nsdpk.'%'));
        }
        if ($request->peruntukan != '' ) {
            array_push($kondisi, array('peruntukan', 'ilike', '%'.$request->peruntukan.'%'));
        }
        if ($request->status != '') {
            array_push($kondisi, array('status', '=', $request->status));
        }


        $response = array();

        $qresults = Nspk::where($kondisi);
        
        if ($request->kategori != '') {
          $qresults  = $qresults->where(function ($query) use ($request) {
              $query->where('kategori', '=', $request->kategori)->orWhere('kategori', '=', null);
          });
        }

        $total_results = $qresults->count();

        // if($request->kategori){
        //     $total_results = Nspk::where(['kategori'=>$request->kategori])->count();
        // }else{
        //     $total_results = Nspk::count();
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

        if ($request->tipe=='periode'){
          $sort = 'tgl_pembuatan';
        } else if ($request->tipe=='nama_nspk'){
          $sort = 'nama_nspk';
        } else if ($request->tipe=='nomor_nsdpk'){
          $sort = 'nomor_nsdpk';
        } else if ($request->tipe=='peruntukan'){
          $sort = 'peruntukan';
        } else if ($request->tipe=='status'){
          $sort = 'status';
        } else {
          $sort = 'id';
        }

        try {
            $data = $qresults->orderBy($sort, $request->order)->orderBy('id', $request->order)->offset($offset)->limit($limit)->get();
            // if($request->kategori){
            //     $data = Nspk::where(['kategori'=>$request->kategori])->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();
            // }else{
            //     $data = Nspk::orderBy('id', 'desc')->offset($offset)->limit($limit)->get();
            // }
            
            // $data = Nspk::where('status', 1)->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();;
            $paginate['page']       = $page;
            $paginate['limit']      = $limit;
            $paginate['totalpage']  = $totalpage;

            return response()->json(Json::response($data, 'sukses', $request->kategori, 200, $paginate), 200);
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function show(Request $request, $id)
    {
        try {
            $data = Nspk::where('id', $id)->first();
            // $data = Nspk::where([['status', 1], ['tersangka_id', $id]])->first();

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
            $data = Nspk::create($request->except('api_token'));
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
            $data = Nspk::findOrFail($id);
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
            $data = Nspk::findOrFail($id);
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

    public function dokumenNSPKRehabilitasi(Request $request)
    {
        $total_results = Nspk::where('kategori','pasca')->count();

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
            $data = Nspk::where('kategori','pasca')->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();
            // $data = Nspk::where('status', 1)->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();;
            $paginate['page']       = $page;
            $paginate['limit']      = $limit;
            $paginate['totalpage']  = $totalpage;

            return response()->json(Json::response($data, 'sukses', null, 200, $paginate), 200);
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }
}

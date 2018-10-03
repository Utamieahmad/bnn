<?php

namespace App\Http\Controllers\API\Rehab;

use Illuminate\Http\Request;
use App\Models\Rehab\Pelatihan;
use App\Models\Rehab\ViewPelatihan;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class pelatihanAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {
        $kondisi = array();
        if ($request->pelaksana != '') {
            array_push($kondisi, array('nm_instansi', '=', $request->pelaksana));
        }
        if ($request->start_from != '') {
            array_push($kondisi, array('tgl_dilaksanakan_mulai', '>=', $request->start_from));
        }
        if ($request->start_to != '' ) {
            array_push($kondisi, array('tgl_dilaksanakan_mulai', '<=', $request->start_to));
        }
        if ($request->end_from != '') {
            array_push($kondisi, array('tgl_dilaksanakan_selesai', '>=', $request->end_from));
        }
        if ($request->end_to != '' ) {
            array_push($kondisi, array('tgl_dilaksanakan_selesai', '<=', $request->end_to));
        }
        if ($request->jumlah_from != '') {
            array_push($kondisi, array('jumlah_peserta', '>=', $request->jumlah_from));
        }
        if ($request->jumlah_to != '' ) {
            array_push($kondisi, array('jumlah_peserta', '<=', $request->jumlah_to));
        }
        if ($request->tema != '' ) {
            array_push($kondisi, array('tema', 'ilike', '%'.$request->tema.'%'));
        }
        if ($request->nomor_sprint != '' ) {
            array_push($kondisi, array('nomor_sprint', 'ilike', '%'.$request->nomor_sprint.'%'));
        }
        if ($request->lokasi != '' ) {
            array_push($kondisi, array('tempat', 'ilike', '%'.$request->lokasi.'%'));
        }
        if ($request->status != '') {
            array_push($kondisi, array('status', '=', $request->status));
        }
        if ($request->kategori != '' ) {
            array_push($kondisi, array('kategori', '=', $request->kategori));
        }

        $qresults = Viewpelatihan::where($kondisi);
        if ($request->id_wilayah != '') {
          $qresults  = $qresults->where(function ($query) use ($request) {
              $query->where('id_wilayah', '=', $request->id_wilayah)->orWhere('wil_id_wilayah', '=', $request->id_wilayah);
          });
        }
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

        if ($request->tipe=='pelaksana'){
          $sort = 'nm_instansi';
        } else if ($request->tipe=='tema'){
          $sort = 'tema';
        } else if ($request->tipe=='nomor_sprint'){
          $sort = 'nomor_sprint';
        } else if ($request->tipe=='periode_start'){
          $sort = 'tgl_dilaksanakan_mulai';
        } else if ($request->tipe=='periode_end'){
          $sort = 'tgl_dilaksanakan_selesai';
        } else if ($request->tipe=='jumlah_peserta'){
          $sort = 'jumlah_peserta';
        } else if ($request->tipe=='status'){
          $sort = 'status';
        } else {
          $sort = 'id';
        }

        try {
            $data = $qresults->orderBy($sort, $request->order)->orderBy('id', $request->order)->offset($offset)->limit($limit)->get();
            // if($request->kategori){
            //     $data = Viewpelatihan::where('kategori',$request->kategori)->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();
            // }else{
                // $data = $qresults->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();
            //}
            // $data = pelatihan::where('status', 1)->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();;
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
            $data = pelatihan::where('id', $id)->first();
            // $data = pelatihan::where([['status', 1], ['tersangka_id', $id]])->first();

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
            $data = pelatihan::create($request->except('api_token'));

            if (!$data){
              return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
            } else {
              $return =['id'=>$data->id];
              return response()->json(Json::response($return, 'sukses', null), 200);
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
            $data = pelatihan::findOrFail($id);
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
            $data = pelatihan::findOrFail($id);
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

<?php

namespace App\Http\Controllers\API\Irtama;

use Illuminate\Http\Request;
use App\Models\Irtama\AuditLha;
use App\Models\Irtama\VIrtamaLHA;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class AuditLhaAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {
        $kondisi = array();
        if ($request->nomor_lha != '') {
            array_push($kondisi, array('nomor_lha', 'ilike' , '%'.$request->nomor_lha.'%'));
        }
        if ($request->tgl_from != '') {
            array_push($kondisi, array('tanggal_lha', '>=', $request->tgl_from));
        }
        if ($request->tgl_to != '') {
            array_push($kondisi, array('tanggal_lha', '<=', $request->tgl_to));
        }
        if ($request->nama_satker != '') {
            array_push($kondisi, array('nama_satker', 'ilike', '%'.$request->nama_satker.'%'));
        }
        if ($request->ketua_tim != '') {
            array_push($kondisi, array('ketua_tim', 'ilike', '%'.$request->ketua_tim.'%'));
        }
        if ($request->kelengkapan != '') {
            array_push($kondisi, array('status', '=', $request->kelengkapan));
        }

        $qresults = VIrtamaLHA::where($kondisi);

        $total_results = $qresults->count();
        //$total_results = VIrtamaLHA::count();

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

        if ($request->tipe=='nomor_lha'){
          $sort = 'nomor_lha';
        } else if ($request->tipe=='nama_satker'){
          $sort = 'nama_satker';
        } else if ($request->tipe=='ketua_tim'){
          $sort = 'ketua_tim';
        } else if ($request->tipe=='periode'){
          $sort = 'tanggal_lha';
        } else if ($request->tipe=='kelengkapan'){
          $sort = 'status';
        } else {
          $sort = 'id_lha';
        }

        try {
            $data = $qresults->orderBy($sort, $request->order)->orderBy('id_lha', $request->order)->offset($offset)->limit($limit)->get();
            // $data = AuditLha::where('status', 1)->orderBy('id_lha', 'desc')->offset($offset)->limit($limit)->get();;
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
            $data = AuditLha::where('id_lha', $id)->first();
            // $data = AuditLha::where([['status', 1], ['tersangka_id', $id]])->first();

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
            $data = AuditLha::create($request->except('api_token'));
            $response['eventID'] = $data->id_lha;
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
            $data = AuditLha::findOrFail($id);
            $data->update($request->except(['api_token', 'id_lha','created_by']));
            $response['eventID'] = $data->id_lha;
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
            $data = AuditLha::findOrFail($id);
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

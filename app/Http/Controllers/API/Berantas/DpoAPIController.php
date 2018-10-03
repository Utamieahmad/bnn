<?php

namespace App\Http\Controllers\API\Berantas;

use Illuminate\Http\Request;
use App\Models\Berantas\Dpo;
use App\Models\Berantas\ViewDpo;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class DpoAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {
        $kondisi = array();

        $kondisi2 = $kondisi;
        if ($request->id_wilayah != '') {
            array_push($kondisi, array('id_wilayah', '=', $request->id_wilayah));
        }
        if ($request->id_wilayah != '') {
            array_push($kondisi2, array('wil_id_wilayah', '=', $request->id_wilayah));
        }
        if ($request->nomor_sprint_dpo != '' ) {
            array_push($kondisi, array('nomor_sprint_dpo', 'ilike', '%'.$request->nomor_sprint_dpo.'%'));
        }
        if ($request->no_identitas != '' ) {
            array_push($kondisi, array('no_identitas', 'ilike', '%'.$request->no_identitas.'%'));
        }
        if ($request->alamat != '') {
            array_push($kondisi, array('alamat', 'ilike', '%'.$request->alamat.'%'));
        }
        if ($request->kode_jenis_kelamin != '' ) {
            array_push($kondisi, array('kode_jenis_kelamin', '=', $request->kode_jenis_kelamin));
        }
        if ($request->status) {
            array_push($kondisi, array('status', '=', $request->status));
        }

        // $total_results = ViewDpo::where($kondisi)->orWhere($kondisi2)->count();
        $qresults = ViewDpo::where($kondisi)->orWhere($kondisi2);

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

        if ($request->tipe=='nomor_sprint_dpo'){
          $sort = 'nomor_sprint_dpo';
        } else if ($request->tipe=='no_identitas'){
          $sort = 'no_identitas';
        } else if ($request->tipe=='alamat'){
          $sort = 'alamat';
        } else if ($request->tipe=='nomor_kasus'){
          $sort = 'nomor_kasus';
        } else if ($request->tipe=='kode_jenis_kelamin'){
          $sort = 'kode_jenis_kelamin';
        } else if ($request->tipe=='status'){
          $sort = 'status';
        } else {
          $sort = 'id';
        }

        try {
            $data = $qresults->orderBy($sort, $request->order)->orderBy('id', $request->order)->offset($offset)->limit($limit)->get();

            // $data = ViewDpo::where($kondisi)->orWhere($kondisi2)->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();
            // $data = Dpo::where('status', 1)->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();;
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
            $data = Dpo::where('id', $id)->first();
            // $data = Dpo::where([['status', 1], ['tersangka_id', $id]])->first();

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
            $request['created_date'] = date("Y-m-d H:i:s");
            $data = Dpo::create($request->except('api_token'));
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
            $request['updated_date'] = date("Y-m-d H:i:s");
            $data = Dpo::findOrFail($id);
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
            $data = Dpo::findOrFail($id);
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

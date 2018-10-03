<?php

namespace App\Http\Controllers\API\Settama;

use Illuminate\Http\Request;
use App\Models\Settama\SekretariatUtama;
use App\Models\Settama\VSekretariatUtama as VSekretariatUtama;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class SettamaAPIController extends Controller
{
    public $return;

    public function index(Request $request)
    {
        $kondisi = array();
        if ($request->biro != '') {
            array_push($kondisi, array('pelaksana', '=', $request->biro));
        }
        if ($request->no_rujukan != '') {
            array_push($kondisi, array('no_rujukan', 'ilike', '%'.$request->no_rujukan.'%'));
        }
        if ($request->jenis_kegiatan != '') {
            array_push($kondisi, array('nama_jenis_kegiatan', 'ilike' ,'%'.$request->jenis_kegiatan.'%'));
        }
        if ($request->sumber_anggaran != '') {
            array_push($kondisi, array('sumber_anggaran', '=', $request->sumber_anggaran));
        }
        if ($request->kelengkapan != '') {
            array_push($kondisi, array('status', '=', $request->kelengkapan));
        }

        $qresults = VSekretariatUtama::where($kondisi);
        if ($request->tgl_from != '') {
          $qresults  = $qresults->where(function ($query) use ($request) {
              $query->where('tgl_mulai', '>=', $request->tgl_from)->orWhere('tgl_selesai', '>=', $request->tgl_from);
          });
        }
        if ($request->tgl_to != '') {
          $qresults  = $qresults->where(function ($query) use ($request) {
              $query->where('tgl_mulai', '<=', $request->tgl_to)->orWhere('tgl_selesai', '<=', $request->tgl_to);
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

        if ($request->tipe=='no_rujukan'){
          $sort = 'no_rujukan';
        } else if ($request->tipe=='jenis_kegiatan'){
          $sort = 'nama_jenis_kegiatan';
        } else if ($request->tipe=='dipa_anggaran'){
          $sort = 'sumber_anggaran';
        } else if ($request->tipe=='periode'){
          $sort = 'tgl_mulai';
        } else if ($request->tipe=='kelengkapan'){
          $sort = 'status';
        } else {
          $sort = 'id_settama';
        }

        try {
            $data = $qresults->orderBy($sort, $request->order)->orderBy('id_settama', $request->order)->offset($offset)->limit($limit)->get();
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
            $data = SekretariatUtama::where('id_settama', $id)->first();
            // $data = SekretariatUtama::where([['status', 1], ['tersangka_id', $id]])->first();

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
            $data = SekretariatUtama::create($request->except('api_token'));
            $this->return['eventID'] = $data->id_settama;
            if ($data){
              return response()->json(Json::response($this->return, 'sukses', null), 200);
            } else {
              return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
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
            $data = SekretariatUtama::findOrFail($id);
            $data->update($request->except(['api_token', 'id_settama','created_by']));

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
            $data = SekretariatUtama::findOrFail($id);
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

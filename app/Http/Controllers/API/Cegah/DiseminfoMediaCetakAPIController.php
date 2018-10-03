<?php

namespace App\Http\Controllers\API\Cegah;

use Illuminate\Http\Request;
use App\Models\Cegah\DiseminfoMediaCetak;
use App\Models\Cegah\ViewDiseminfoMediaCetak;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class DiseminfoMediaCetakAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {
        $kondisi = array();
        //array_push($kondisi, array('status', '=', '1'));
        if ($request->tgl_from != '') {
            array_push($kondisi, array('tgl_pelaksanaan', '>=', $request->tgl_from));
        }
        if ($request->tgl_to != '' ) {
            array_push($kondisi, array('tgl_pelaksanaan', '<=', $request->tgl_to));
        }
        if ($request->lokasi != '' ) {
            array_push($kondisi, array('lokasi_kegiatan', 'ilike', '%'.$request->lokasi.'%'));
        }
        if ($request->jenis != '' ) {
            array_push($kondisi, array('kode_jenis_media', 'ilike', '%'.$request->jenis.'%'));
        }
        if ($request->pelaksana != '' ) {
            array_push($kondisi, array('nm_instansi', '=', $request->pelaksana));
        }
        if ($request->waktu_from != '') {
            array_push($kondisi, array('waktu_publish', '>=', $request->waktu_from));
        }
        if ($request->waktu_to != '' ) {
            array_push($kondisi, array('waktu_publish', '<=', $request->waktu_to));
        }
        if ($request->media != '' ) {
            array_push($kondisi, array('nama_media', 'ilike', '%'.$request->media.'%'));
        }
        if ($request->materi != '' ) {
            array_push($kondisi, array('materi', 'ilike', '%'.$request->materi.'%'));
        }
        if ($request->anggaran != '' ) {
            array_push($kondisi, array('kodesumberanggaran', '=', $request->anggaran));
        }
        if ($request->kelengkapan != '' ) {
            array_push($kondisi, array('status', '=', $request->kelengkapan));
        }

        // $total_results = ViewDiseminfoMediaCetak::where($kondisi)->count();
        $qresults = ViewDiseminfoMediaCetak::where($kondisi);
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
          $sort = 'idpelaksana';
        } else if ($request->tipe=='materi'){
          $sort = 'materi';
        } else if ($request->tipe=='media'){
          $sort = 'nama_media';
        } else if ($request->tipe=='jenis'){
          $sort = 'kode_jenis_media';
        } else if ($request->tipe=='anggaran'){
          $sort = 'kodesumberanggaran';
        } else if ($request->tipe=='periode'){
          $sort = 'waktu_publish';
        } else if ($request->tipe=='kelengkapan'){
          $sort = 'status';
        } else {
          $sort = 'id';
        }

        try {
            $data = $qresults->orderBy($sort, $request->order)->orderBy('id', $request->order)->offset($offset)->limit($limit)->get();
            // $data = DiseminfoMediaCetak::where('status', 1)->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();;
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
            $data = DiseminfoMediaCetak::where('id', $id)->first();
            // $data = DiseminfoMediaCetak::where([['status', 1], ['tersangka_id', $id]])->first();

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
            $data = DiseminfoMediaCetak::create($request->except('api_token'));
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
            $data = DiseminfoMediaCetak::findOrFail($id);
            $data->update($request->except(['api_token', 'id', 'created_by']));
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

    public function destroy(Request $request, $id)
    {
        try {
            $data = DiseminfoMediaCetak::findOrFail($id);
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

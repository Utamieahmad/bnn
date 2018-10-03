<?php

namespace App\Http\Controllers\API\Cegah;

use Illuminate\Http\Request;
use App\Models\Cegah\DiseminfoMediaKonvensional;
use App\Models\Cegah\ViewDiseminfoMediaKonvensional;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class DiseminfoMediaKonvensionalAPIController extends Controller
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
        if ($request->sasaran != '' ) {
            array_push($kondisi, array('kodesasaran', 'ilike', '%'.$request->sasaran.'%'));
        }
        if ($request->pelaksana != '' ) {
            array_push($kondisi, array('nm_instansi', '=', $request->pelaksana));
        }
        if ($request->materi != '' ) {
            array_push($kondisi, array('materi', 'ilike', '%'.$request->materi.'%'));
        }
        if ($request->narasumber != '' ) {
            array_push($kondisi, array('narasumber', 'ilike', '%'.$request->narasumber.'%'));
        }
        if ($request->anggaran != '' ) {
            array_push($kondisi, array('kodesumberanggaran', '=', $request->anggaran));
        }
        if ($request->kelengkapan != '' ) {
            array_push($kondisi, array('status', '=', $request->kelengkapan));
        }

        // $total_results = ViewDiseminfoMediaKonvensional::where($kondisi)->count();
        $qresults = ViewDiseminfoMediaKonvensional::where($kondisi);
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
        } else if ($request->tipe=='narasumber'){
          $sort = 'narasumber';
        } else if ($request->tipe=='sasaran'){
          $sort = 'kodesasaran';
        } else if ($request->tipe=='anggaran'){
          $sort = 'kodesumberanggaran';
        } else if ($request->tipe=='periode'){
          $sort = 'tgl_pelaksanaan';
        } else if ($request->tipe=='kelengkapan'){
          $sort = 'status';
        } else {
          $sort = 'id';
        }

        try {
            $data = $qresults->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();
            // $data = DiseminfoMediaKonvensional::where('status', 1)->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();;
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
            $data = DiseminfoMediaKonvensional::where('id', $id)->first();
            // $data = DiseminfoMediaKonvensional::where([['status', 1], ['tersangka_id', $id]])->first();

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
            $data = DiseminfoMediaKonvensional::create($request->except('api_token'));
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
            $data = DiseminfoMediaKonvensional::findOrFail($id);
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
            $data = DiseminfoMediaKonvensional::findOrFail($id);
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

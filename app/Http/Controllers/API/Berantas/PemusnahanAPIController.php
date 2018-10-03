<?php

namespace App\Http\Controllers\API\Berantas;

use Illuminate\Http\Request;
use App\Models\Berantas\Pemusnahan;
use App\Models\Berantas\ViewPemusnahan;
use App\Models\Berantas\ViewPemusnahanDetail;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class PemusnahanAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {
        $kondisi = array();
        //array_push($kondisi, array('status', '=', '1'));
        if ($request->lokasi != '') {
            array_push($kondisi, array('lokasi', 'ilike', '%'.$request->lokasi.'%'));
        }
        if ($request->kejari != '') {
            array_push($kondisi, array('nama_kejari', 'ilike', '%'.$request->kejari.'%'));
        }
        if ($request->nomor_lkn != '' ) {
            array_push($kondisi, array('nomor_lkn', 'ilike', '%'.$request->nomor_lkn.'%'));
        }
        if ($request->penyidik != '') {
            array_push($kondisi, array('nama_penyidik', 'ilike', '%'.$request->penyidik.'%'));
        }
        if ($request->tgl_from != '') {
            array_push($kondisi, array('tgl_pemusnahan', '>=', $request->tgl_from));
        }
        if ($request->tgl_to != '' ) {
            array_push($kondisi, array('tgl_pemusnahan', '<=', $request->tgl_to));
        }
        if ($request->nomor_tap != '' ) {
            array_push($kondisi, array('nomor_tap', 'ilike', '%'.$request->nomor_tap.'%'));
        }
        if ($request->status) {
            array_push($kondisi, array('status', '=', $request->status));
        }

        $qresults = ViewPemusnahan::where($kondisi);
        if ($request->id_wilayah != '') {
          $qresults  = $qresults->where(function ($query) use ($request) {
              $query->where('id_wilayah', '=', $request->id_wilayah)->orWhere('wil_id_wilayah', '=', $request->id_wilayah);
          });
        }
        $total_results = $qresults->count();

        //$total_results = ViewPemusnahan::where($kondisi)->count();
        //$total_results = Pemusnahan::count();

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

        $response = array();

        if ($request->tipe=='nomor_lkn'){
          $sort = 'nomor_lkn';
        } else if ($request->tipe=='nama_penyidik'){
          $sort = 'nama_penyidik';
        } else if ($request->tipe=='periode'){
          $sort = 'tgl_pemusnahan';
        } else if ($request->tipe=='nomor_tap'){
          $sort = 'nomor_tap';
        } else if ($request->tipe=='status'){
          $sort = 'status';
        } else {
          $sort = 'id';
        }

        try {
            $datamusnah = $qresults->orderBy($sort, $request->order)->orderBy('id', $request->order)->offset($offset)->limit($limit)->get();
            // $datamusnah = $qresults->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();
            // $data = Pemusnahan::where('status', 1)->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();;
            $paginate['page']       = $page;
            $paginate['limit']      = $limit;
            $paginate['totalpage']  = $totalpage;
            $paginate['totaldata']  = $total_results;

            foreach ($datamusnah as $row) {
              $data = $row;
              $data['bbdetail']  = ViewPemusnahanDetail::select('nm_brgbukti', 'jumlah_dimusnahkan', 'nm_satuan')->where('parent_id', $row->id)->get();

              array_push($response, $data);
            }
            return response()->json(Json::response($response, 'sukses', null, 200, $paginate), 200);
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function show(Request $request, $id)
    {
        try {
            $data = Pemusnahan::where('id', $id)->first();
            // $data = Pemusnahan::where([['status', 1], ['tersangka_id', $id]])->first();

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
            $data = Pemusnahan::create($request->except('api_token'));
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
            $data = Pemusnahan::findOrFail($id);
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
            $data = Pemusnahan::findOrFail($id);
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

    public function getPemusnahanByLKN(Request $request)
    {
        try {
            $data = Pemusnahan::where('nomor_lkn', $request->kasus_no)->get();

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

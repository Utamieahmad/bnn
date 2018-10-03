<?php

namespace App\Http\Controllers\API\Badiklat;

use Illuminate\Http\Request;
use App\Models\Badiklat\Kegiatan;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class KegiatanAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {
        $kondisi = [];

        if ($request->tgl_from != '') {
            array_push($kondisi, array('tgl_pelaksanaan', '>=', $request->tgl_from));
        }
        if ($request->tgl_to != '') {
            array_push($kondisi, array('tgl_selesai', '<=', $request->tgl_to));
        }
        if ($request->jml_from != '') {
            array_push($kondisi, array('total_peserta', '>=', $request->jml_from));
        }
        if ($request->jml_to != '') {
            array_push($kondisi, array('total_peserta', '<=', $request->jml_to));
        }
        if ($request->nama_kegiatan != '') {
            array_push($kondisi, array('nama_kegiatan', 'ilike', '%'.$request->nama_kegiatan.'%'));
        }
        if ($request->tempat != '') {
            array_push($kondisi, array('tempat', 'ilike', '%'.$request->tempat.'%'));
        }
        if ($request->tujuan_kegiatan != '') {
            array_push($kondisi, array('tujuan_kegiatan', 'ilike', '%'.$request->tujuan_kegiatan.'%'));
        }
        if ($request->status != '') {
            array_push($kondisi, array('status', '=', $request->status));
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

        $orderByKey="";
        $orderByOrder="";
        $tipe = $request->tipe;
        $order = $request->order;

        if($tipe && $order){
            if(($tipe == 'periode') || ($tipe == 'jml_peserta' )|| ($tipe == 'nama_kegiatan' )|| ($tipe == 'tempat' )|| ($tipe == 'tujuan_kegiatan' ) ){
                if($tipe == 'periode' ){
                    $orderByKey = 'tgl_pelaksanaan';
                }else if($tipe == 'jml_peserta' ){
                    $orderByKey = 'total_peserta';
                }else{
                    $orderByKey = $tipe;
                }
                $orderByOrder = $order;
            }else{
                $orderByKey = 'tgl_pelaksanaan';
                $orderByOrder = $order;
            }
        }else if($order){
            $orderByKey = 'tgl_pelaksanaan';
            $orderByOrder = $order;
        }else{
            $orderByKey = 'id';
            $orderByOrder = 'desc';

        }

        $query = Kegiatan::where($kondisi);

        $total_results = $query->count();
        $offset = ($page-1) * $limit;
        $totalpage = ceil($total_results / $limit);

        try {
            $data = $query->orderBy($orderByKey, $orderByOrder)->offset($offset)->limit($limit)->get();
            // $data = Kegiatan::where('status', 1)->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();;
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
            $data = Kegiatan::where('id', $id)->first();
            // $data = Kegiatan::where([['status', 1], ['tersangka_id', $id]])->first();

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
            $data = Kegiatan::create($request->except('api_token'));
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
            $data = Kegiatan::findOrFail($id);
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
            $data = Kegiatan::findOrFail($id);
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

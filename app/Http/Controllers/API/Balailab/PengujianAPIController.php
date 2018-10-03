<?php

namespace App\Http\Controllers\API\Balailab;

use Illuminate\Http\Request;
use App\Models\Balailab\Pengujian;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class PengujianAPIController extends Controller
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
        
        $query = Pengujian::where($kondisi);
        $total_results = $query->count();
        $offset = ($page-1) * $limit;
        $totalpage = ceil($total_results / $limit);
        $orderByKey="";
        $orderByOrder="";
        $tipe = $request->tipe;
        $order = $request->order;

        if($tipe && $order){
            if(($tipe == 'periode') || ($tipe == 'no_surat' )|| ($tipe == 'perihal' )|| ($tipe == 'pengirim' )|| ($tipe == 'instansi' ) ){
                if($tipe == 'periode' ){
                    $orderByKey = 'tgl_surat';
                }else if($tipe == 'no_surat' ){
                    $orderByKey = 'nomor_surat_permohonan_pengajuan';
                }else if($tipe == 'perihal' ){
                    $orderByKey = 'perihal_surat';
                }else if($tipe == 'pengirim' ){
                    $orderByKey = 'nama_pengirim';
                }else if($tipe == 'instansi' ){
                    $orderByKey = 'nama_instansi';
                }else{
                    $orderByKey = $tipe;
                }
                $orderByOrder = $order;
            }else{
                $orderByKey = 'tgl_surat';
                $orderByOrder = $order;
            }
        }else if($order){
            $orderByKey = 'tgl_surat';
            $orderByOrder = $order;
        }else{
            $orderByKey = 'id';
            $orderByOrder = 'desc';

        }
        try {
            $data = $query->orderBy($orderByKey, $orderByOrder)->offset($offset)->limit($limit)->get();
            // $data = Pengujian::where('status', 1)->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();;
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
            $data = Pengujian::where('id', $id)->first();
            // $data = Pengujian::where([['status', 1], ['tersangka_id', $id]])->first();

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
            $data = Pengujian::create($request->except('api_token'));
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
            $data = Pengujian::findOrFail($id);
            $data->update($request->except(['api_token', 'id']));

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
            $data = Pengujian::findOrFail($id);
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

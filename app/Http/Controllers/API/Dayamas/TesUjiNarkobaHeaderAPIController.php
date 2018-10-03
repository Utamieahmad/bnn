<?php

namespace App\Http\Controllers\API\Dayamas;

use Illuminate\Http\Request;
use App\Models\Dayamas\TesUjiNarkobaHeader;
use App\Models\Dayamas\ViewTesUjiNarkobaHeader;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class TesUjiNarkobaHeaderAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {
        $kondisi = array();
        //array_push($kondisi, array('status', '=', '1'));
        if ($request->lokasi != '') {
            array_push($kondisi, array('lokasi', 'ilike', '%'.$request->lokasi.'%'));
        }
        if ($request->instansi != '') {
            array_push($kondisi, array('nm_instansi', 'ilike', '%'.$request->instansi.'%'));
        }
        if ($request->kode_anggaran != '') {
            array_push($kondisi, array('kodesumberanggaran', 'ilike', '%'.$request->kode_anggaran.'%'));
        }
        if ($request->sasaran != '') {
            array_push($kondisi, array('sasaran_values', 'ilike', '%'.$request->sasaran.'%'));
        }
        if ($request->tgl_from != '') {
            array_push($kondisi, array('tgl_test', '>=', $request->tgl_from));
        }
        if ($request->tgl_to != '' ) {
            array_push($kondisi, array('tgl_test', '<=', $request->tgl_to));
        }

        if ($request->jml_to != '') {
            array_push($kondisi, array('jmlh_peserta', '>=', $request->jml_from));
        }
        if ($request->jml_from != '') {
            array_push($kondisi, array('jmlh_peserta', '<=', $request->jml_to));
        }
        if ($request->status != '') {
            array_push($kondisi, array('status', '=', $request->status));
        }

        // $total_results = ViewTesUjiNarkobaHeader::where($kondisi)->count();
        $qresults = ViewTesUjiNarkobaHeader::where($kondisi);
        if ($request->id_wilayah != '') {
          $qresults  = $qresults->where(function ($query) use ($request) {
              $query->where('id_wilayah', '=', $request->id_wilayah)->orWhere('wil_id_wilayah', '=', $request->id_wilayah);
          });
        }
        

        //$total_results = ViewTesUjiNarkobaHeader::count();

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
        

        $orderByKey="";
        $orderByOrder="";
        $tipe = $request->tipe;
        $order = $request->order;

        if($tipe && $order){
            if($tipe == 'instansi' || $tipe == 'periode'|| $tipe == 'jml_peserta' || $tipe == 'sasaran' ){
                if($tipe == 'instansi'){
                    $orderByKey = 'nm_instansi';
                    $orderByOrder = $order;
                }else if($tipe == 'periode' || $tipe == 'sasaran'){
                    $orderByKey = 'tgl_test';
                    $orderByOrder = $order;
                }else if($tipe == 'jml_peserta'){
                    $orderByKey = 'jmlh_peserta';
                    $orderByOrder = $order;
                }else{
                    $orderByKey = $tipe;
                    $orderByOrder = $order;
                }
            }else{
                $orderByKey = 'tgl_test';
                $orderByOrder = $order;
            }
        }else if($order){
            $orderByKey = 'tgl_test';
            $orderByOrder = $order;
        }else{
            $orderByKey = 'header_id';
            $orderByOrder = 'desc';

        }
        $total_results = $qresults->count();
        $totalpage = ceil($total_results / $limit);
        try {
            $data = $qresults->orderBy($orderByKey, $orderByOrder)->offset($offset)->limit($limit)->get();
            // $data = TesUjiNarkobaHeader::where('status', 1)->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();;
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
            $data = TesUjiNarkobaHeader::where('header_id', $id)->first();
            // $data = TesUjiNarkobaHeader::where([['status', 1], ['tersangka_id', $id]])->first();

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
            $data = TesUjiNarkobaHeader::create($request->except('api_token'));
            $response['eventID'] = $data->header_id;

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
            $data = TesUjiNarkobaHeader::findOrFail($id);
            $data->update($request->except(['api_token', 'header_id']));

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
            $data = TesUjiNarkobaHeader::findOrFail($id);
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

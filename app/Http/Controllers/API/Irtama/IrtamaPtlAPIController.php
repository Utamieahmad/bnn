<?php

namespace App\Http\Controllers\API\Irtama;

use Illuminate\Http\Request;
use App\Models\Irtama\IrtamaPtl;
use App\Models\Irtama\VIrtamaPTL;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class IrtamaPtlAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {
        $kondisi = array();
        if ($request->nomor_lha != '') {
            array_push($kondisi, array('nomor_lha', 'ilike', '%'.$request->nomor_lha.'%'));
        }
        if ($request->tgl_from != '') {
            array_push($kondisi, array('tanggal_lha', '>=', $request->tgl_from));
        }
        if ($request->tgl_to != '') {
            array_push($kondisi, array('tanggal_lha', '<=', $request->tgl_to));
        }
        if ($request->nama_satker != '') {
            array_push($kondisi, array('nama_satker', '=', $request->nama_satker));
        }
        if ($request->ketua_tim != '') {
            array_push($kondisi, array('ketua_tim', 'ilike', '%'.$request->ketua_tim.'%'));
        }

        $qresults = VIrtamaPTL::where($kondisi);

        $total_results = $qresults->count();
        //$total_results = VIrtamaPTL::count();

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

        $orderByKey="";
        $orderByOrder="";
        $tipe = $request->tipe;
        $order = $request->order;
        $tgl_from = $request->tgl_from;
        $tgl_from = $request->tgl_from;
        $tgl_to = $request->tgl_to;

        if($tipe && $order){
            if($tipe == 'nomor_lha' || $tipe == 'periode' ){
                if($tipe == 'periode'){
                    $orderByKey = 'tanggal_lha';
                    $orderByOrder = $order;
                }else{
                    $orderByKey = $tipe;
                    $orderByOrder = $order;
                }
            }else{
                $orderByKey = 'tanggal_lha';
                $orderByOrder = $order;
            }
        }else if($order){
            $orderByKey = 'tanggal_lha';
            $orderByOrder = $order;
        }else{
            $orderByKey = 'id_ptl';
            $orderByOrder = 'desc';

        }

        try {
            $data = VIrtamaPTL::orderBy($orderByKey,$orderByOrder)->offset($offset)->limit($limit)->get();
            // $data = IrtamaPtl::where('status', 1)->orderBy('id_ptl', 'desc')->offset($offset)->limit($limit)->get();;
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
            $bidang = [];
            $return = [];
            $rekomendasi = [];

            $data = VIrtamaPTL::where('id_ptl', $id);
            if($data->count()>0){
                $data = $data->first();
                $tipe = config('app.bidang_tipe');
                foreach($tipe as $t){
                    $temp_bidang = $data->irtamaBidangLha(['tipe'=>$t]);
                    if(count($temp_bidang) > 0 ){
                        foreach($temp_bidang as $b){
                            $rekomendasi[] = $b->rekomendasiBidang;
                        }
                    }
                    $bidang[$t] = $temp_bidang;
                }
                $return=$data;
                $return['bidang'] =$bidang;
            }
            if($data->count()>0){
              return response()->json(Json::response($return, 'sukses', null), 200);
            }else{
              return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
            }

        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);
        }
    }

    public function store(Request $request)
    {
        try {

            $data = IrtamaPtl::create($request->except('api_token'));
            $response['eventID'] = $data->id_ptl;
            if (!$data){
              return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
            } else {
              return response()->json(Json::response($response, 'sukses', null), 200);
            }
        } catch(\Exception $e) {
            return response()->json(Json::response(IrtamaPtl::create($request->except('api_token')), 'error', $e->getMessage()), 200);

        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data = IrtamaPtl::findOrFail($id);
            $data->update($request->except(['api_token', 'id_ptl']));
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
            $data = IrtamaPtl::findOrFail($id);
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

     public function ptlBidang(Request $request, $id)
    {
        try {
            $bidang = [];
            $return = [];
            $rekomendasi = [];

            $data = VIrtamaPTL::where('id_ptl', $id);
            if($data->count()>0){
                $data = $data->first();
                $tipe = config('app.bidang_tipe');
                foreach($tipe as $t){
                    $temp_bidang = $data->allIrtamaBidangLha(['tipe'=>$t]);
                    if(count($temp_bidang) > 0 ){
                        foreach($temp_bidang as $b){
                            $rekomendasi[] = $b->rekomendasiBidang;
                        }
                    }
                    $bidang[$t] = $temp_bidang;
                }
                $return=$data;
                $return['bidang'] =$bidang;
            }
            if($data->count()>0){
              return response()->json(Json::response($return, 'sukses', null), 200);
            }else{
              return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
            }

        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);
        }
    }

}

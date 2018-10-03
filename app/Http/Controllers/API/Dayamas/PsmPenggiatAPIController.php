<?php

namespace App\Http\Controllers\API\Dayamas;

use Illuminate\Http\Request;
use App\Models\Dayamas\PsmPenggiat;
use App\Models\Dayamas\ViewPsmPenggiat;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class PsmPenggiatAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {
        // $total_results = ViewPsmPenggiat::count();
        $kondisi = array();
        $qresults = ViewPsmPenggiat::where($kondisi);
        if ($request->id_wilayah != '') {
          $qresults  = $qresults->where(function ($query) use ($request) {
              $query->where('id_wilayah', '=', $request->id_wilayah)->orWhere('wil_id_wilayah', '=', $request->id_wilayah);
          });
        }

        if ($request->pelaksana != '') {
            array_push($kondisi, array('nm_instansi', 'ilike', '%'.$request->pelaksana.'%'));
        }
        if ($request->tgl_from != '') {
            array_push($kondisi, array('tgl_pelaksanaan', '>=', $request->tgl_from));
        }
        if ($request->tgl_to != '' ) {
            array_push($kondisi, array('tgl_pelaksanaan', '<=', $request->tgl_to));
        }
        if ($request->asal_penggiat != '' ) {
            array_push($kondisi, array('kodesasaran', 'ilike', '%'.$request->asal_penggiat.'%'));
        }
        if ($request->jenis_kegiatan != '' ) {
            array_push($kondisi, array('jenis_kegiatan', 'ilike', '%'.$request->jenis_kegiatan.'%'));
        }
        if ($request->materi != '' ) {
            array_push($kondisi, array('materi', 'ilike', '%'.$request->materi.'%'));
        }

        if ($request->jml_to != '') {
            array_push($kondisi, array('jumlah_peserta', '>=', $request->jml_from));
        }
        if ($request->jml_from != '') {
            array_push($kondisi, array('jumlah_peserta', '<=', $request->jml_to));
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
        $offset = ($page-1) * $limit;
        

        $orderByKey="";
        $orderByOrder="";
        $tipe = $request->tipe;
        $order = $request->order;

        if($tipe && $order){
            if( $tipe == 'periode'|| $tipe == 'jumlah_peserta' ){
                if($tipe == 'periode' || $tipe == 'sasaran'){
                    $orderByKey = 'tgl_pelaksanaan';
                    $orderByOrder = $order;
                }else if($tipe == 'jumlah_peserta'){
                    $orderByKey = 'jumlah_peserta';
                    $orderByOrder = $order;
                }else{
                    $orderByKey = $tipe;
                    $orderByOrder = $order;
                }
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
        $qresults = $qresults->where($kondisi);
        $total_results = $qresults->count();
        $totalpage = ceil($total_results / $limit);
        try {
            $data = $qresults->orderBy($orderByKey,$orderByOrder)->offset($offset)->limit($limit)->get();
            // $data = PsmPenggiat::where('status', 1)->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();;
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
            $data = PsmPenggiat::where('id', $id)->first();
            // $data = PsmPenggiat::where([['status', 1], ['tersangka_id', $id]])->first();

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
            $data = PsmPenggiat::create($request->except('api_token'));
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
            $data = PsmPenggiat::findOrFail($id);
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
            $data = PsmPenggiat::findOrFail($id);
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

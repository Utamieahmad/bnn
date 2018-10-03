<?php

namespace App\Http\Controllers\API\Dayamas;

use Illuminate\Http\Request;
use App\Models\Dayamas\PsmPelatihanPenggiat;
use App\Models\Dayamas\ViewPsmPelatihanPenggiat;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class PsmPelatihanPenggiatAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {
        // $total_results = ViewPsmPelatihanPenggiat::count();
        $kondisi = array();
        $qresults = ViewPsmPelatihanPenggiat::where($kondisi);
        if ($request->id_wilayah != '') {
          $qresults  = $qresults->where(function ($query) use ($request) {
              $query->where('id_wilayah', '=', $request->id_wilayah)->orWhere('wil_id_wilayah', '=', $request->id_wilayah);
          });
        }

        if ($request->status != '') {
            array_push($kondisi, array('status', '=', $request->status));
        }
        if ($request->tgl_from != '') {
            array_push($kondisi, array('tgl_pelaksanaan', '>=', $request->tgl_from));
        }
        if ($request->tgl_to != '' ) {
            array_push($kondisi, array('tgl_pelaksanaan', '<=', $request->tgl_to));
        }
        if ($request->sasaran != '' ) {
            array_push($kondisi, array('kodesasaran', '=', $request->sasaran));
        }
        if ($request->instansi != '' ) {
            array_push($kondisi, array('meta_instansi', 'ilike', '%'.$request->instansi.'%'));
        }
        if ($request->kode_anggaran != '' ) {
            array_push($kondisi, array('kodesumberanggaran', '=', $request->kode_anggaran));
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
            if($tipe == 'periode' ){
                if($tipe == 'periode' ){
                    $orderByKey = 'tgl_pelaksanaan';
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
            // $data = PsmPelatihanPenggiat::where('status', 1)->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();;
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
            $data = PsmPelatihanPenggiat::where('id', $id)->first();
            // $data = PsmPelatihanPenggiat::where([['status', 1], ['tersangka_id', $id]])->first();

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
            $data = PsmPelatihanPenggiat::create($request->except('api_token'));
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
            $data = PsmPelatihanPenggiat::findOrFail($id);
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
            $data = PsmPelatihanPenggiat::findOrFail($id);
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

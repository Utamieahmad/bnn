<?php

namespace App\Http\Controllers\API\Huker;

use Illuminate\Http\Request;
use App\Models\Huker\HukumKegiatanLainnya;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class HukumKegiatanLainnyaAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {

        $kondisi = array();
        if ($request->jenis_kegiatan != '') {
            array_push($kondisi, array('jenis_kegiatan', '=', $request->jenis_kegiatan));
        }
        if ($request->bagian != '') {
            array_push($kondisi, array('bagian', '=', $request->bagian));
        }
        if ($request->no_sprint_kepala != '') {
            array_push($kondisi, array('no_sprint_kepala', 'ilike', '%'.$request->no_sprint_kepala.'%'));
        }
        if ($request->tema != '') {
            array_push($kondisi, array('tema', 'ilike', '%'.$request->tema.'%'));
        }
        if ($request->status != '') {
            array_push($kondisi, array('status', '=', $request->status));
        }

        $qresults = HukumKegiatanLainnya::where($kondisi);

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
        $orderByKey="";
        $orderByOrder="";
        $tipe = $request->tipe;
        $order = $request->order;
        if($tipe && $order){

            if($tipe == 'periode')
                $tipe = "tgl_mulai";

            $orderByKey = $tipe;
            $orderByOrder = $order;

        }else{
            $orderByKey = 'id';
            $orderByOrder = 'desc';

        }

        try {
            $data = $qresults->orderBy($orderByKey, $orderByOrder)->offset($offset)->limit($limit)->get();

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
            $data = HukumKegiatanLainnya::where('id', $id)->first();
            // $data = HukumNonlitigasi::where([['status', 1], ['tersangka_id', $id]])->first();

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
            $data = HukumKegiatanLainnya::create($request->except('api_token'));
            $response['eventID'] = $data->id;
            if (!$data){
              return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
            } else {
              return response()->json(Json::response($response, 'sukses', null), 200);
            }
        } catch(\Exception $e) {
          // dd($e);
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request['updated_by'] = $request->created_by;
            $request['updated_at'] = date("Y-m-d H:i:s");
            $data = HukumKegiatanLainnya::findOrFail($id);
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
            $data = HukumKegiatanLainnya::findOrFail($id);
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

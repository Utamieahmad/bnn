<?php

namespace App\Http\Controllers\API\Huker;

use Illuminate\Http\Request;
use App\Models\Huker\KerjasamaLainnya;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class KerjasamaLainnyaAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {
        $kondisi = array();
        if ($request->nm_kegiatan != '') {
            array_push($kondisi, array('nm_kegiatan', 'ilike', '%'.$request->nm_kegiatan.'%'));
        }
        if ($request->tempat_pelaksanaan != '') {
            array_push($kondisi, array('tempat_pelaksanaan', 'ilike' ,'%'.$request->tempat_pelaksanaan.'%'));
        }
        if ($request->status != '') {
            array_push($kondisi, array('status', '=', $request->status));
        }
        if ($request->tgl_from != '') {
            array_push($kondisi, array('tgl_pelaksanaan', '>=', $request->tgl_from));
        }
        if ($request->tgl_to != '') {
            array_push($kondisi, array('tgl_pelaksanaan', '<=', $request->tgl_to));
        }

        $qresults = KerjasamaLainnya::where($kondisi);

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
            $orderByKey = $tipe;
            $orderByOrder = $order;

        }else if($order){
            $orderByKey = 'tgl_pelaksanaan';
            $orderByOrder = $order;
        }else{
            $orderByKey = 'id';
            $orderByOrder = 'desc';

        }

        try {
            $data = $qresults->orderBy($orderByKey, $orderByOrder)->offset($offset)->limit($limit)->get();
            // $data = KerjasamaPerjanjianBilateral::where('status', 1)->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();;
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
            $data = KerjasamaLainnya::where('id', $id)->first();
            // $data = HukerKerjasamaMonev::where([['status', 1], ['tersangka_id', $id]])->first();

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
            $data = KerjasamaLainnya::create($request->except('api_token'));
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
            $data = KerjasamaLainnya::findOrFail($id);
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
            $data = KerjasamaLainnya::findOrFail($id);
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

<?php

namespace App\Http\Controllers\API\Datin;

use Illuminate\Http\Request;
use App\Models\Datin\CallCenterDisposisi;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class CallCenterDisposisiAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {

        $kondisi = array();

        if ($request->tgl_from != '') {
            array_push($kondisi, array('waktuterima', '>=', $request->tgl_from));
        }
        if ($request->tgl_to != '') {
            array_push($kondisi, array('waktuterima', '<=', $request->tgl_to));
        }
        if ($request->tgl_input_from != '') {
            array_push($kondisi, array('waktuinput', '>=', $request->tgl_input_from));
        }
        if ($request->tgl_input_to != '') {
            array_push($kondisi, array('waktuinput', '<=', $request->tgl_input_to));
        }
        if ($request->pengirim != '') {
            array_push($kondisi, array('pengirim', 'ilike', '%'.$request->pengirim.'%'));
        }
        if ($request->penerima != '') {
            array_push($kondisi, array('penerima', 'ilike', '%'.$request->penerima.'%'));
        }
        if ($request->konten != '') {
            array_push($kondisi, array('konten', 'ilike', '%'.$request->konten.'%'));
        }
        if ($request->subyek != '') {
            array_push($kondisi, array('subjek', 'ilike', '%'.$request->subyek.'%'));
        }


        $orderByKey="";
        $orderByOrder="";
        $tipe = $request->tipe;
        $order = $request->order;

        if($tipe && $order){
            if($tipe == 'tgl_terima'){
                $orderByKey = 'waktuterima';
            }else if($tipe == 'tgl_input'){
                $orderByKey = 'waktuinput';
            }else if($tipe == 'konten'){
                $orderByKey = 'konten';
            }else if($tipe == 'subyek'){
                $orderByKey = 'subjek';
            }else if($tipe == 'penerima'){
                $orderByKey = 'penerima';
            }else if($tipe == 'pengirim'){
                $orderByKey = 'pengirim';
            }else{
                $orderByKey = 'waktuinput';
            }
            $orderByOrder = $order;
        }else if($order){
            $orderByKey = 'waktuinput';
            $orderByOrder = $order;
        }else{
            $orderByKey = 'rid';
            $orderByOrder = 'desc';

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

        $query= CallCenterDisposisi::where($kondisi);
        $total_results = $query->count();
        $offset = ($page-1) * $limit;
        $totalpage = ceil($total_results / $limit);

        try {
            $data = $query->orderBy($orderByKey, $orderByOrder)->offset($offset)->limit($limit)->get();
            // $data = Callcenter::where('status', 1)->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();;
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
            $data = CallCenterDisposisi::where('rid', $id)->first();
            // $data = Callcenter::where([['status', 1], ['tersangka_id', $id]])->first();

            if (!$data){
              return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
            } else {
              return response()->json(Json::response($data, 'sukses', null), 200);
            }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    // public function store(Request $request)
    // {
    //     try {
    //         $data = CallCenterDisposisi::create($request->except('api_token'));
    //         $response['eventID'] = $data->user_id;
    //         if (!$data){
    //           return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
    //         } else {
    //           return response()->json(Json::response($response, 'sukses', null), 200);
    //         }
    //     } catch(\Exception $e) {
    //         return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

    //     }
    // }

    // public function update(Request $request, $id)
    // {
    //     try {
    //         $data = CallCenterDisposisi::findOrFail($id);
    //         $data->update($request->except(['api_token', 'id']));

    //         if (!$data){
    //           return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
    //         } else {
    //           return response()->json(Json::response(null, 'sukses', null), 200);
    //         }
    //     } catch(\Exception $e) {
    //         return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

    //     }
    // }

    // public function destroy(Request $request, $id)
    // {
    //     try {
    //         $data = CallCenterDisposisi::findOrFail($id);
    //         $data->delete();
    //         // $data->update(['status' => 0]); //softdelete

    //         if (!$data){
    //           return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
    //         } else {
    //           return response()->json(Json::response(null, 'sukses', null), 200);
    //         }
    //     } catch(\Exception $e) {
    //         return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

    //     }
    // }

}

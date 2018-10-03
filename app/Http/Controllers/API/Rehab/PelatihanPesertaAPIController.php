<?php

namespace App\Http\Controllers\API\Rehab;

use Illuminate\Http\Request;
use App\Models\Rehab\PelatihanPesertaRehab;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class PelatihanPesertaAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {
        if($request->parent_id){
            $total_results = PelatihanPesertaRehab::where('id_header',$request->parent_id)->count();
        }else{
            $total_results = PelatihanPesertaRehab::count();
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
        $totalpage = ceil($total_results / $limit);

        try {

            if($request->parent_id){
                $data = PelatihanPesertaRehab::where('id_header',$request->parent_id)->orderBy('id_detail', 'desc')->offset($offset)->limit($limit)->get();
            }else{
                $data = PelatihanPesertaRehab::orderBy('id_detail', 'desc')->offset($offset)->limit($limit)->get();
            }

            // $data = PelatihanPesertaRehab::where('status', 1)->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();;
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

            $data = PelatihanPesertaRehab::where('id_detail', $id)->first();
            // $data = PelatihanPesertaRehab::where([['status', 1], ['tersangka_id', $id]])->first();


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
            $data = PelatihanPesertaRehab::create($request->except('api_token'));
            $id_parent = $request->id_header;
            if($id_parent){
                $count = PelatihanPesertaRehab::where('id_header',$id_parent)->count();
            }else{
                $count = 0;
            }
            if (!$data){
              return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
            } else {
                $return = ['id'=>$data->id_detail,'count'=>$count];
              return response()->json(Json::response($return, 'sukses', null), 200);
            }
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data = PelatihanPesertaRehab::findOrFail($id);
            $data->update($request->except(['api_token', 'id_detail']));

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
            $data = PelatihanPesertaRehab::findOrFail($id);
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


    public function singlePelatihanPeserta(Request $request, $id_parent){
        $total_results = PelatihanPesertaRehab::where('id_header',$id_parent)->count();


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

        try {

            $data = PelatihanPesertaRehab::where('id_header',$id_parent)->orderBy('id_detail', 'desc')->offset($offset)->limit($limit)->get();
            // $data = PelatihanPesertaRehab::where('status', 1)->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();;

            $paginate['page']       = $page;
            $paginate['limit']      = $limit;
            $paginate['totalpage']  = $totalpage;

            return response()->json(Json::response($data, 'sukses', null, 200, $paginate), 200);
        } catch(\Exception $e) {
            return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

        }
    }

}

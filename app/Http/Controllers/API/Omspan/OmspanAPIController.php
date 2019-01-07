<?php

namespace App\Http\Controllers\API\Omspan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformers\Json;

class OmspanAPIController extends Controller
{
    
    public function index(Request $request)
    {
        
    }

    public function getpengelolaanup(Request $request, $kdsatker, $periode)
    {
        try {
			echo $kdsatker;
            echo "<pre>";
			//print_r($request);
			echo "</pre>";
        } catch(\Exception $e) {
            echo "<pre>";
			print_r($e);
			echo "</pre>";
        }
    }

    public function store(Request $request)
    {
        //try {
        //    $request['created_at'] = date("Y-m-d H:i:s");
        //    $data = PenegakanDisiplin::create($request->except('api_token'));
        //    $response['eventID'] = $data->id;
        //    if (!$data){
        //      return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
        //    } else {
        //      return response()->json(Json::response($response, 'sukses', null), 200);
        //    }
        //} catch(\Exception $e) {
        //    return response()->json(Json::response(null, 'error', $e->getMessage()), 200);
        //
        //}
    }

    public function update(Request $request, $id)
    {
        //try {
        //    $request['updated_by'] = $request->created_by;
        //    $request['updated_at'] = date("Y-m-d H:i:s");
        //    $data = PenegakanDisiplin::findOrFail($id);
        //    $data->update($request->except(['api_token', 'id', 'created_by']));
        //
        //    if (!$data){
        //      return response()->json(Json::response(null, 'error', "data kosong", 404), 404);
        //    } else {
        //      return response()->json(Json::response(null, 'sukses', null), 200);
        //    }
        //} catch(\Exception $e) {
        //    return response()->json(Json::response(null, 'error', $e->getMessage()), 200);
        //
        //}
    }


}
<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Rbac_users;
use App\Models\User_roles;
use App\Http\Controllers\Controller;
use App\Transformers\Json;
use Hash;
use Validator;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class UserAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function index(Request $request)
    {
        $kondisi = array();
        if ($request->user_name != '') {
            array_push($kondisi, array('user_name', 'ilike', '%'.$request->user_name.'%'));
        }
        if ($request->email != '') {
            array_push($kondisi, array('email', 'ilike' ,'%'.$request->email.'%'));
        }
        if ($request->nip != '') {
            array_push($kondisi, array('nip', 'ilike' ,'%'.$request->nip.'%'));
        }
        if ($request->kepegawaian != '') {
            if($request->kepegawaian == 'PNS')
            {
                array_push($kondisi, array('nip', '<>', ''));
            }
            else if($request->kepegawaian == 'PHL')
            {
                array_push($kondisi, array('nip', '=', ''));
            }
        }
        if ($request->wilayah_id != '') {
            array_push($kondisi, array('wilayah_id', '=', $request->wilayah_id));
        }
        if ($request->active_flag != '') {
            array_push($kondisi, array('active_flag', '=', $request->active_flag));
        }

        $qresults = Rbac_users::where($kondisi);

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
            if($tipe == 'kepegawaian')
                $tipe = 'nip';

            $orderByKey = $tipe;
            $orderByOrder = $order;

        }
        else{
            $orderByKey = 'user_id';
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
            $data = Rbac_users::where('user_id', $id)->first();
            // $data = Rbac_users::where([['status', 1], ['tersangka_id', $id]])->first();

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
            $data = Rbac_users::create($request->except('api_token'));
            $response['eventID'] = $data->user_id;

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
            $data = Rbac_users::findOrFail($id);
            $data->update($request->except(['api_token', 'user_id']));

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
            //delete dari user_groups
            User_roles::where('user_id', $id)->delete();

            $data = Rbac_users::findOrFail($id);
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

    public function updatePass(Request $request)
    {
        /*
          Request value : email, user_id, old_password, new_password
        */
        $validator = Validator::make($request->all(), [
            'new_password' => 'required',
            'user_id' => 'required',
            'old_password' => 'required'
        ]);
        if (!$validator->fails()) {
            $users = Rbac_users::where('user_id', $request->user_id)->first();

            //LDAP
            $client = new Client();
            
            $requestChange = $client->request('POST', config('app.url_ldap').'/sso/changepass',
              [
              'headers' =>
                [
                'Content-Type' => 'application/json'
                ],
              'body' =>json_encode(
                [
                "userName" => $users->email,
                "oldPassword" => $request->old_password,
                "newPassword" => $request->new_password
                ])
              ]
            );
            $ldapChange = json_decode($requestChange->getBody()->getContents(), true);
            
            //LDAP

            // if (Hash::check($request->old_password, $users->password)){
            if($ldapChange['code']==200){
                try {
                    Rbac_users::where('user_id', $request->user_id)->update(['password' => Hash::make($request->new_password)]);
                    return response()->json(Json::response(null, 'sukses', "Password berhasil diubah"));
                } catch(\Exception $e) {
                    return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

                }
            } else {
                return response()->json(Json::response(null, 'error', "Password lama salah"));
            }

        }else{
            return response()->json(Json::response(null, 'error', $validator->errors()->all()));
        }
    }

    public function updatePass2(Request $request)
    {
        /*
          Request value : user_id, new_password
        */
        $validator = Validator::make($request->all(), [
            'new_password' => 'required',
            'user_id' => 'required'
        ]);
        if (!$validator->fails()) {
            $users = Rbac_users::where('user_id', $request->user_id)->first();
            //LDAP
            $client = new Client();
            
            $requestChange = $client->request('POST', config('app.url_ldap').'/sso/changepass/set',
              [
              'headers' =>
                [
                'Content-Type' => 'application/json'
                ],
              'body' =>json_encode(
                [
                "userName" => $users->email,
                "newPassword" => $request->new_password
                ])
              ]
            );
            $ldapChange = json_decode($requestChange->getBody()->getContents(), true);
            
            //LDAP

            // if (Hash::check($request->old_password, $users->password)){
            if($ldapChange['code']==200){
                try {
                    Rbac_users::where('user_id', $request->user_id)->update(['password' => Hash::make($request->new_password)]);
                    return response()->json(Json::response(null, 'sukses', "Password berhasil diubah"));
                } catch(\Exception $e) {
                    return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

                }
            } else {
                return response()->json(Json::response(null, 'error', "Password lama salah"));
            }
        }else{
            return response()->json(Json::response(null, 'error', $validator->errors()->all()));
        }
    }



}

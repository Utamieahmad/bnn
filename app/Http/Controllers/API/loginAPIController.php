<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Rbac_users;
use App\Models\View_users;
use App\Models\AuthAPI;
use App\Models\Device;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User_menuController;
use Hash;
use DateTime;
use App\Transformers\Json;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class LoginAPIController extends Controller
{
    /* @author : Daniel Andi */

    public function login(Request $request)
    {
      // dd('test');
        /*
          Request value : email, password, device_id
        */        

        $d        = new DateTime('+1day');
        $tomorrow = $d->format('Y-m-d H:i:s');
        $token    = str_random(255);

        //LDAP
        $client = new Client();
        $requestLogin = $client->request('POST', config('app.url_ldap').'/sso/users/login',
          [
          'headers' =>
            [
            'Content-Type' => 'application/json'
            ],
          'body' =>json_encode(
            [
            "userName" => $request->email,
            "password" => $request->password
            ])
          ]
        );
        $login = json_decode($requestLogin->getBody()->getContents(), true);

        if($login['code']==401 && isset($login['details'])){
          return response()->json(Json::loginresponse(null, 'Error', "Email/Password salah"));
        }

        //LDAP


        $u = View_users::where('email', $request->email)->first();

        if (!$u) {
            return response()->json(Json::loginresponse(null, 'Error', "Email/Password salah"));
        } else {
          if (Hash::check($request->password, $u->password)) { //cek password SIN tanpa LDAP

              //LDAP
              if($login['code']==401){
                $client = new Client();
                $name = ($u->user_name) ? $u->user_name : '-'; 
                $nip = ($u->nip) ? $u->nip : '0';
                
                $requestCreate = $client->request('POST', config('app.url_ldap').'/sso/users/create',
                  [
                  'headers' =>
                    [
                    'Content-Type' => 'application/json'
                    ],
                  'body' =>json_encode(
                    [
                    "userName" => $request->email,
                    "password" => $request->password,
                    "displayName" => $name,
                    "nip" => $nip
                    ])
                  ]
                );
                $ldapCreate = json_decode($requestCreate->getBody()->getContents(), true);
              }

              //LDAP


              AuthAPI::insert(['user_id'       => $u->user_id,
                               'api_token'     => $token,
                               'status'        => 1,
                               'expired_time'  => $tomorrow
                             ]);
              if ($request->device_id!=null){
                 Device::insert(['user_id'      => $u->user_id,
                                 'device_id'    => $request->device_id,
                                 'status'       => 1
                               ]);
              }

              $data['access_token'] = $token;
              $data['name']         = $u->user_name;
              $data['id']           = $u->user_id;
              $data['wilayah']      = $u->wilayah_id;
              $data['group']        = $u->group_name;
              $data['group_id']     = $u->group_id;
              $data['id_instansi']  = $u->id_instansi;
              $data['expires_in']   = $tomorrow;
              $test = new User_menuController();
              if ($request->device_id!=null){
                  $data['menu']         = $test->get_usermenu($u->user_id, 2)->original;
                  $data['cancreate']    = $test->get_cancreate($u->user_id, 2)->original;
                  $data['canedit']      = $test->get_canedit($u->user_id, 2)->original;
                  $data['candelete']    = $test->get_candelete($u->user_id, 2)->original;
              } else {
                  $data['menu']         = $test->get_usermenu($u->user_id, 1)->original;
                  $data['cancreate']    = $test->get_cancreate($u->user_id, 1)->original;
                  $data['canedit']      = $test->get_canedit($u->user_id, 1)->original;
                  $data['candelete']    = $test->get_candelete($u->user_id, 1)->original;
              }

              return response()->json(Json::loginresponse($data, 'sukses', null));
          } else {
              return response()->json(Json::loginresponse(null, 'Error', "Email/Password salah"));
          }
        }
    }

    public function logout(Request $request)
    {
        /*
          Request value : token, device_id
        */

        $logout = AuthAPI::where('api_token', $request->token)->update(['status' => FALSE]);

        if ($request->device_id!=null){
           Device::where('device_id', $request->device_id)->update(['status' => FALSE]);
        }

        if (!$logout) {
            return response()->json(Json::loginresponse(null, 'Error', "Terjadi kesalahan"));
        } else {
            return response()->json(Json::loginresponse(null, 'Sukses', null));
        }
    }

    public function get_id($token)
    {
        $data = AuthAPI::where('api_token', $token)->first();
        $response = $data->id;

        return $response;
    }


}

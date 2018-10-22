<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\AuthenticationModel;
use Hash;
use App\Models\Rbac_users;
use Validator;
use Auth;
use URL;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
class AuthenticationController extends Controller
{
  public $data = array();
  public $messages = array();
  public $client ;
  public $headers = ['headers'=>[ 'Accept' => 'application/json' ]];

  public function forgot_password(Request $request){

    if($request->isMethod('post')){
      $validator = Validator::make($request->all(), [
        'email' => 'required|email',
      ]);
      if (!$validator->fails()) {
        $this->client = new \GuzzleHttp\Client($this->headers);
        $email = $request->input('email');
        $user = Rbac_users::where(['email'=>$email])->count();
        if($user){
          try {
            $request = $this->client->request('POST',url('/api/password/email'),[$this->headers, 'form_params' => ['email' => $email ] ] );
            $this->data = json_decode($request->getBody()->getContents(), true);
            if($this->data['status'] == 'success' ){
              $this->messages['status'] = 'success';
              $this->messages['message'] = 'Link untuk mereset kata sandi sudah dikirimkan ke email Anda.';
            }else{
              $this->messages['status'] = 'error';
              $this->messages['message'] = 'Kata sandi Anda gagal untuk diupdate. Silahkan coba lagi.';
            }
          }catch (\GuzzleHttp\Exception\GuzzleException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Kata sandi Anda gagal diperbarui.';
            return back()->with('message',$this->messages);
          }
        }else{
          $this->messages['status'] = 'error';
          $this->messages['message'] = 'User tidak ditemukan.';
        }
      }else{
        $this->messages['status'] = 'error';
        $this->messages['message'] = $validator->errors()->all();
      }
      return back()->with('message',$this->messages);
    }else{
      return view('auth.passwords.forgot_password');
    }
  }

  public function reset_password(Request $request){
    if($request->isMethod('post')){
      $validator = Validator::make($request->all(), [
      'password' => 'required',
      'password_confirmation' => 'required',
      'email'=>'required',
      ]);
      if (!$validator->fails()) {
        $password = $request->input('password');
        $password_confirmation = $request->input('password_confirmation');
        $email = $request->input('email');
        $token = $request->input('token');
        $this->data['password'] = $password;
        $this->data['password_confirmation'] = $password_confirmation;
        $this->data['email'] = $email;
        $this->data['token'] = $token;
        if($password == $password_confirmation){
          $this->client = new \GuzzleHttp\Client($this->headers);
          $request = $this->client->request('POST',url('/api/password/reset'),[$this->headers, 'form_params' => $this->data ] );
          $result = json_decode($request->getBody()->getContents(), true);
          if($request->getStatusCode() == 200 ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Kata sandi Anda berhasil diupdate. Silahkan melakukan login ulang.';
            return redirect('login')->with('message',$this->messages);
          }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Kata sandi Anda gagal untuk diupdate. Silahkan coba lagi.';
            return back()->with('message',$this->messages);
          }
          //     $client = new Client();
          //     $baseUrl = URL::to('/');
          //     $request = $client->request('POST', $baseUrl.'/api/password/reset/'+$token,
          //         [
          //             'headers' =>
          //             [
          //                 'Accept' => 'application/json'
          //             ],
          //             'form_params' => [
          //                 'password' => $password
          //             ]
          //         ]
          //     );
          //     $setPassword = json_decode($request->getBody()->getContents(), true);
          //     dd($setPassword);
          //     $success = 'success';

          //     return view('auth.passwords.reset')->with($success);
        } else{
          $this->messages['status'] = 'error';
          $this->messages['message'] = 'Password tidak sama';
          return back()->with('message',$this->messages);
        }
      } else{
        $this->messages['status'] = 'error';
        $this->messages['message'] = $validator->errors()->all();
        return back()->with('message',$validator->errors());
      }

    } else{
      $token = $request->token;
      $this->data['token'] = $token;
      return view('auth.passwords.reset')->with($this->data);
    }

  }

  public function login_process(Request $request){
    $validator = Validator::make($request->all(), [
    'email' => 'required|email',
    'password' => 'required',
    ]);
    if (!$validator->fails()) {
      $email = $request->input('email');
      $password = $request->input('password');

      $credential = array('email'=>$email,'password'=>$password);

      // $baseUrl = URL::to('/');
      $baseUrl = URL::to($this->urlapi());
      // dd($request->all());

      $client = new Client();
      // dd($client);
      $login = $this->getLogin($email, $password);
      // dd($login);
      $user = Rbac_users::where('email', $email)->first();
      // dd($user);
      $request->session()->put('credential', $credential);
      $request->session()->put('token', $login['data']['access_token']);
      $request->session()->put('user_data', $login['data']);
      $request->session()->put('wilayah', $login['data']['wilayah']);
      $request->session()->put('user_group', $login['data']['group']);
      $request->session()->put('id', $login['data']['id']);

      $token = $request->session()->get('token');
      // dd($token);
      // $u = Rbac_users::where('email', $email)->first();
      if($login['status'] == "sukses"){

        $menulist = array();
        foreach ($login['data']['menu'] as $m) {
          array_push($menulist, $m['menu_id']);
        }
        $request->session()->put('menu', $menulist);

        $menulist2 = array();
        foreach ($login['data']['cancreate'] as $m) {
          array_push($menulist2, $m['menu_id']);
        }
        $request->session()->put('cancreate', $menulist2);

        $menulist3 = array();
        foreach ($login['data']['canedit'] as $m) {
          array_push($menulist3, $m['menu_id']);
        }
        $request->session()->put('canedit', $menulist3);

        $menulist4 = array();
        foreach ($login['data']['candelete'] as $m) {
          array_push($menulist4, $m['menu_id']);
        }
        $request->session()->put('candelete', $menulist4);

        if ($user->flag != false) {
          Auth::attempt($credential);
          $request->session()->forget('credential');
          // dd($user->nip);
          if ($user->nip != "") {
            $simpeg = $this->getSimpeg($user->nip);
            $simpegSatker = $this->getSimpegSatker($user->nip);
            $simpegPhoto = $this->getSimpegPhoto($user->nip);

            // dd($simpegPhoto);
            $request->session()->put('foto_pegawai', $simpegPhoto['data']);
            $request->session()->put('nama_pegawai', $simpeg['data']['nama']);
            $request->session()->put('jabatan_pegawai', $simpeg['data']['jabatan']);
            if ($simpegSatker['code'] == "200") {
                $request->session()->put('satker_simpeg', $simpegSatker['data']['id_satker']);
            } else {
                $request->session()->put('satker_simpeg', null);
            }
          } else {
            $request->session()->put('nama_pegawai', Auth::user()->user_name);
            $request->session()->put('jabatan_pegawai', Auth::user()->jabatan_pegawai);
            $request->session()->put('foto_pegawai', Auth::user()->foto_pegawai);
          }

          return redirect('/');
        }else {
          return view('auth.nip');
        }

      }else{
        $this->messages['status'] = 'error';
        $this->messages['message'] = 'Email / Kata sandi yang anda masukkan salah.';
      }

    }else{
      $this->messages['status'] = 'error';
      $this->messages['message'] = $validator->errors()->all();
    }
    return redirect('login')->with('message',$this->messages);
  }

  public function nip_process(Request $request)
  {
    // $baseUrl = URL::to('/');
    $baseUrl = URL::to($this->urlapi());
    $client = new Client();
    $credential = $request->session()->get('credential');
    $token = $request->session()->get('token');
    $nip = $request->input('nip');
    $statusPegawai = $request->input('status_pegawai');
    // dd($statusPegawai);
    if ($statusPegawai == "PNS") {
      // dd('test');
      $simpeg = $this->getSimpeg($nip);
      $simpegSatker = $this->getSimpegSatker($nip);
      $simpegPhoto = $this->getSimpegPhoto($nip);
      // dd($simpeg);
      if ($simpeg['code'] == "200") {
        Auth::attempt($credential);

        $request->session()->put('foto_pegawai', $simpegPhoto['data']);
        $request->session()->put('nama_pegawai', $simpeg['data']['nama']);
        $request->session()->put('jabatan_pegawai', $simpeg['data']['jabatan']);
        if ($simpegSatker['code'] == "200") {
            $request->session()->put('satker_simpeg', $simpegSatker['data']['id_satker']);
        } else {
            $request->session()->put('satker_simpeg', null);
        }
        $requestStoreNip = $client->request('PUT', $baseUrl.'/api/users/'.Auth::user()->user_id,
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json'
          ],
          'form_params' =>
          [
            "user_id" => Auth::user()->user_id,
            "group_id" => Auth::user()->group_id,
            "nip" => $nip,
            "flag" => true,
            "foto_pegawai" => $simpegPhoto['data'],
            "user_name" => $simpeg['data']['nama'],
            "jabatan_pegawai" => $simpeg['data']['jabatan']
          ]
        ]
        );
        $storeNip = json_decode($requestStoreNip->getBody()->getContents(), true);

      } else {
        $this->messages['status'] = 'error';
        $this->messages['message'] = 'Nip tidak ditemukan';
        return redirect('login')->with('message', $this->messages);
      }
    } else {
        if ($request->file('file_laporan_kegiatan_file') != ''){
          $image = file_get_contents($_FILES['file_laporan_kegiatan_file']['tmp_name']);
          $imageData = base64_encode($image);
        } else {
          $path = 'assets/images/default-image.png';
          $type = pathinfo($path, PATHINFO_EXTENSION);
          $data = file_get_contents($path);
          $imageData = base64_encode($data);
        }
        $request->session()->put('foto_pegawai', $imageData);
        $request->session()->put('nama_pegawai', $request->input('nama_pegawai'));
        $request->session()->put('jabatan_pegawai', $request->input('jabatan_pegawai'));
        Auth::attempt($credential);
        $requestStoreNip = $client->request('PUT', $baseUrl.'/api/users/'.Auth::user()->user_id,
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json'
          ],
          'form_params' =>
          [
            "user_id" => Auth::user()->user_id,
            "group_id" => Auth::user()->group_id,
            "nip" => "",
            "flag" => true,
            "foto_pegawai" => $imageData,
            "user_name" => $request->input('nama_pegawai'),
            "jabatan_pegawai" => $request->input('jabatan_pegawai'),
            "telp_pegawai" => $request->input('telp_pegawai'),
            "lokasi_kerja" => $request->input('lokasi_kerja')
          ]
        ]
        );
        $storeNip = json_decode($requestStoreNip->getBody()->getContents(), true);

      }
      $request->session()->forget('credential');

      return redirect('/');
    }

    public function userPelatihan(Request $request){
        $data['instansi'] = $this->globalinstansi('', 'token');

        return view('auth.createuser',$data);
    }

    public function register(Request $request){
      $validator = Validator::make($request->all(), [
      'email' => 'required|email',
      'instansi' => 'required',
      ]);
      if (!$validator->fails()) {
        $user = Rbac_users::where('email', $request->input('email'))->count();
        if ($user < 1) {
            $client = new Client();

            // $baseUrl = URL::to('/');
            $baseUrl = URL::to($this->urlapi());
            $token = 'token';
            // $token = $request->session()->get('token');

            $wilayah = DB::table('v_instansi')->where('id_instansi', $request->input('instansi'))->first();

            if($wilayah->id_wilayah=='831'){
              $wil = '';
            } else {
              $wil = $wilayah->id_wilayah;
            }

            $requestUser =$client->request('POST', $baseUrl.'/api/users',
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' =>
                    [
                        'email' => $request->input('email'),
                        'password' => '$2y$10$974mJ3fy1QgSLGkXPW4CCeYf.S0QdLfWB5Vca429cNsTTe/Cio87C',
                        'group_id' => "100",
                        'wilayah_id' => $wil
                    ]
                ]
            );
            $resultUser = json_decode($requestUser->getBody()->getContents(), true);
            $idUser = $resultUser['data']['eventID'];

            $insertdata = array();
            foreach ($request->input('meta_menu') as $key => $value) {
              array_push($insertdata, array('user_id' => $idUser, 'group_id' => $value));
            }

            DB::table('user_group')->insert($insertdata);

            return redirect('/userpelatihan');
        } else {
          $this->messages['status'] = 'error';
          $this->messages['message'] = 'Email sudah terdaftar';
          return redirect('/userpelatihan')->with('message',$this->messages);
        }
      } else {
        $this->messages['status'] = 'error';
        $this->messages['message'] = $validator->errors()->all();
        return redirect('/userpelatihan')->with('message',$this->messages);
      }
    }

  public function change_email(Request $request){
        $client = new Client();
        $id = Auth::user()->user_id;
        $user = Rbac_users::where(['user_id'=>$id]);
        $user_obj = $user->first();
        $email = $user_obj->email;
        $new_email = $request->input('new_email');
        $count = $user->count();
        $token = $request->session()->get('token');

        $email_exists = (Rbac_users::where(['email'=>$new_email])->count() > 0);

        if($email == $new_email)
        {
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Email yang digunakan sama dengan alamat email yang lama. Silahkan coba dengan alamat email yang lain.';
            return redirect('/profile/')->with('status',$this->messages);
        }

        if($email_exists)
        {
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Email sudah dipakai. Silahkan coba dengan alamat email yang lain.';
            return redirect('/profile/')->with('status',$this->messages);
        }

        if($count > 0){
          try {
            $request = $client->request('POST',url('/api/email/new_email'),
                [
                    'headers' =>
                    [
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' =>
                    [
                        'email' => $email,
                        'new_email' => $new_email
                    ]
            ] );
            $this->data = json_decode($request->getBody()->getContents(), true);

            if($this->data['status'] == 'success' ){
              $this->messages['status'] = 'success';
              $this->messages['message'] = 'Link untuk mereset email sudah dikirimkan ke email Anda.';
            }else{
              $this->messages['status'] = 'error';
              $this->messages['message'] = 'Email gagal untuk diupdate. Silahkan coba lagi.';
            }

            return redirect('/profile/')->with('status',$this->messages);

          }catch (GuzzleException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Email gagal diperbarui.';
            return redirect('/profile/')->with('status',$this->messages);
          }
        }else{
          $this->messages['status'] = 'error';
          $this->messages['message'] = 'User tidak ditemukan.';
          return redirect('/profile/')->with('status',$this->messages);
        }
  }

  public function change_nip(Request $request){
        $client = new Client();
        $id = Auth::user()->user_id;
        $user = Rbac_users::where(['user_id'=>$id]);
        $user_obj = $user->first();
        $nip = $request->input('nip');
        $count = $user->count();
        $token = $request->session()->get('token');

        try
        {
            $requestSimpeg = $client->request('GET', config('app.url_soa').'simpeg/getstaffprofile?nip='.$nip,
            [
            'form_params' =>
                [
                "nip" => $nip,
                ]
            ]
            );

            $simpegPhoto = $this->getSimpegPhoto($nip);

            $request->session()->put('foto_pegawai', $simpegPhoto['data']);
        }
        catch(GuzzleException $e)
        {
          $this->messages['status'] = 'error';
          $this->messages['message'] = 'NIP yang dimasukkan tidak valid.';

          return redirect('/profile/')->with('status',$this->messages);
        }

        $nip_has_been_used = (Rbac_users::where('nip', $nip)->count() > 0);

        if($nip_has_been_used)
        {
          $this->messages['status'] = 'error';
          $this->messages['message'] = 'NIP yang dimasukkan sudah dipakai.';

          return redirect('/profile/')->with('status',$this->messages);
        }


        if($count > 0){

          $simpeg = $this->getSimpeg($nip);

          Rbac_users::where('user_id', $id)->update(array(
            'nip' => $nip,
            'jabatan_pegawai' => $simpeg['data']['jabatan'],
            'user_name' => $simpeg['data']['nama'],
            'foto_pegawai' => null
          ));

          $this->messages['status'] = 'success';
          $this->messages['message'] = 'NIP Anda berhasil disimpan.';

          return redirect('/profile/')->with('status',$this->messages);

        }else{
          $this->messages['status'] = 'error';
          $this->messages['message'] = 'User tidak ditemukan.';
          return redirect('/profile/')->with('status',$this->messages);
        }
  }

  public function reset_email(Request $request){
    $token = $request->token;

    $this->client = new \GuzzleHttp\Client($this->headers);
    $request = $this->client->request('POST',url('/api/email/reset'),
      [
        'headers'=>[ 'Accept' => 'application/json' ],
        'form_params' => ['token' => $token ]
      ]
    );

    $result = json_decode($request->getBody()->getContents(), true);

    if($result['code'] == 200){
      $new_email = $result['data']['new_email'];
      $this->messages['status'] = 'success';
      $this->messages['message'] = 'Email Anda berhasil diupdate menjadi ' . $new_email . '. Silahkan melakukan login ulang.';
      Auth::logout();
      return redirect('/login')->with('message',$this->messages);
    }else{
      $this->messages['status'] = 'error';
      $this->messages['message'] = 'Email Anda gagal untuk diupdate. Silahkan coba lagi.';
      return redirect('/login')->with('message',$this->messages);
    }


  }

  public function downloadApp(Request $request){
      $data['title'] = config('constant.MOBTITLE');
      $data['url']   = config('constant.MOBURL');

      return view('auth.downloadapp',$data);
  }

  public function userManual(Request $request){
      $data['web']         = config('constant.MANUALWEB');
      $data['mobile']      = config('constant.MANUALMOBILE');
      $data['dashboard']   = config('constant.MANUALDASHBOARD');

      return view('auth.usermanual',$data);
  }

}

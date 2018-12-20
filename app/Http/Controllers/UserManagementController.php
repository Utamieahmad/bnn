<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\MainModel;
use App\Models\Group;
use App\Models\Rbac_users;
use App\Models\User_roles;
use URL;
use DateTime;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class UserManagementController extends Controller {

    public $data;
    public $selected;
    public $form_params;
    public $datamenu = [];
    public $datamobile = [];

    public function user(Request $request) {
        //filter
        $kondisi = '';
        if ($request->limit) {
            $this->limit = $request->limit;
        } else {
            $this->limit = config('app.limit');
        }

        if ($request->isMethod('get')) {
            $get = $request->all();
            $get = $request->except(['page']);
            $tipe = $request->tipe;
            if (count($get) > 0) {
                $this->selected['tipe'] = $tipe;
                foreach ($get as $key => $value) {
                    $kondisi .= "&" . $key . '=' . $value;
                    if (($key == 'user_name') || ($key == 'email') || ($key == 'nip')) {
                        $this->selected[$key] = $value;
                        $this->selected['keyword'] = $value;
                    }
                }

                $this->selected['order'] = $request->order;
                $this->selected['limit'] = $request->limit;
                $this->data['filter'] = $this->selected;
            }
        } else {
            $post = $request->all();
            $tipe = $request->tipe;
            $kata_kunci = $request->kata_kunci;
            $tgl_from = $request->tgl_from;
            $tgl_to = $request->tgl_to;
            $jenis_kegiatan = $request->jenis_kegiatan;
            $order = $request->order;
            $limit = $request->limit;
            $active_flag = $request->active_flag;
            $kepegawaian = $request->kepegawaian;
            $wilayah_id = $request->wilayah_id;

            if ($tipe == 'active_flag') {
                $kondisi .= '&active_flag=' . $active_flag;
                $this->selected['active_flag'] = $active_flag;
            } else if ($tipe == 'kepegawaian') {
                $kondisi .= '&kepegawaian=' . $kepegawaian;
                $this->selected['kepegawaian'] = $kepegawaian;
            } else if ($tipe == 'wilayah_id') {
                $kondisi .= '&wilayah_id=' . $wilayah_id;
                $this->selected['wilayah_id'] = $wilayah_id;
            } else {
                $kondisi .= '&' . $tipe . '=' . $kata_kunci;
                $this->selected['keyword'] = $kata_kunci;
            }

            if ($tipe) {
                $kondisi .= '&tipe=' . $tipe;
                $this->selected['tipe'] = $tipe;
            }
            $kondisi .= '&limit=' . $this->limit;
            $kondisi .= '&order=' . $order;
            $this->selected['limit'] = $this->limit;
            $this->selected['order'] = $order;
        }

        if ($request->page) {
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page - 1 )) + 1;
        } else {
            $current_page = 1;
            $start_number = $current_page;
        }
        $limit = 'limit=' . $this->limit;
        $offset = 'page=' . $current_page;
        $datas = execute_api_json('api/users?' . $limit . '&' . $offset . $kondisi, 'get');

        $total_item = 0;
        if ($datas->code == 200) {
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;
        } else {
            $this->data['data'] = [];
            $total_item = 0;
        }

        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');

        $requestWilayah = $client->request('GET', $baseUrl . '/api/wilayah');
        $wilayah = json_decode($requestWilayah->getBody()->getContents(), true);

        $this->data['wilayah'] = $wilayah;

        $nm_wilayah = array();

        foreach ($wilayah['data'] as $w_index => $w_value)
            $nm_wilayah[$w_value['id_wilayah']] = $w_value['nm_wilayah'];

        $this->data['nm_wilayah'] = $nm_wilayah;


        $this->data['filter'] = $this->selected;
        $this->data['kondisi'] = $kondisi;

        //end filter
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['title'] = "User";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));

        $this->data['delete_route'] = "delete_user";
        $this->data['path'] = $request->path();
        $this->data['route_name'] = $request->route()->getName();
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;

        $this->data['route'] = $request->route()->getName();

        $filtering = false;
        if ($kondisi) {
            $filter = $kondisi;
            $filtering = true;
            $this->data['kondisi'] = '?' . $offset . $kondisi;
        } else {
            $filter = '/';
            $filtering = false;
            $this->data['kondisi'] = $current_page;
        }

        $this->data['pagination'] = paginations($current_page, $total_item, $this->limit, config('app.page_ellipsis'), config('app.url') . "/" . $request->route()->getPrefix() . "/" . $request->route()->getName(), $filtering, $filter);
        $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());
        return view('user_management.user.index_user', $this->data);
    }

    public function addUser(Request $request) {
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');

        $requestWilayah = $client->request('GET', $baseUrl . '/api/wilayah');
        $wilayah = json_decode($requestWilayah->getBody()->getContents(), true);

        $requestGroup = $client->request('GET', $baseUrl . '/api/getgroup');
        $this->data['groups'] = json_decode($requestGroup->getBody()->getContents(), true);

        $this->data['instansi'] = $this->globalinstansi('', 'token');
        $this->data['wilayah'] = $wilayah;

        $this->data['title'] = "User";

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());
        return view('user_management.user.add_user', $this->data);
    }

    public function editUser(Request $request) {
        $id = $request->id;
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');

        $requestWilayah = $client->request('GET', $baseUrl . '/api/wilayah');
        $wilayah = json_decode($requestWilayah->getBody()->getContents(), true);

        $requestGroup = $client->request('GET', $baseUrl . '/api/getgroup');
        $this->data['groups'] = json_decode($requestGroup->getBody()->getContents(), true);

        $this->data['instansi'] = $this->globalinstansi('', 'token');

        $requestUser = $client->request('GET', $baseUrl . '/api/users/' . $id);
        $data_detail = json_decode($requestUser->getBody()->getContents(), true);

        $data_detail = $data_detail['data'];

        $nip = $data_detail['nip'];

        if ($data_detail['wilayah_id'] != '') {
            foreach ($wilayah['data'] as $w_index => $w_value) {
                if ($w_value['id_wilayah'] == $data_detail['wilayah_id']) {
                    $data_detail['nm_wilayah'] = $w_value['nm_wilayah'];

                    break;
                }
            }
        }

        if ($nip != '') {
            $simpeg = $this->getSimpeg($nip);

            $data['data_simpeg'] = $simpeg;
        }

        $groups_result = User_roles::where('user_id', $id)->get();

        $this->data['group_ids'] = array();

        foreach ($groups_result as $g) {
            $this->data['group_ids'][] = $g['group_id'];
        }

        $this->data['title'] = "User";

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $data_detail;
        $this->data['id'] = $id;
        $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());
        return view('user_management.user.edit_user', $this->data);
    }

    public function inputUser(Request $request) {
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $client = new Client();

        $email = $request->input('email');

        $user = Rbac_users::where('email', $email)->count();
        if ($user < 1) {
            $client = new Client();

//            $baseUrl = URL::to('/');
            $token = $request->session()->get('token');

            $wilayah = DB::table('v_instansi')->where('id_instansi', $request->input('instansi'))->first();

            if ($wilayah->id_wilayah == '831' || $wilayah->id_wilayah == '600') {
                $wil = '';
            } else {
                $wil = $wilayah->id_wilayah;
            }

            $groups = $request->input('meta_grup');

            if (count($groups) == 0) {
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Pilih minimal satu group';

                return back()->with('status', $this->messages);
            }

            $group_id = $groups[0]['id'];

            $requestUser = $client->request('POST', $baseUrl . '/api/users', [
                'headers' =>
                    [
                    'Authorization' => 'Bearer ' . $token
                ],
                'form_params' =>
                    [
                    'email' => $request->input('email'),
                    'password' => '$2y$10$974mJ3fy1QgSLGkXPW4CCeYf.S0QdLfWB5Vca429cNsTTe/Cio87C',
                    'group_id' => $group_id,
                    'wilayah_id' => $wil,
                    'active_flag' => 'Y'
                ]
                    ]
            );
            $resultUser = json_decode($requestUser->getBody()->getContents(), true);


            //LDAP
            $client = new Client();
            
            $requestCreate = $client->request('POST', config('app.url_ldap').'/sso/users/create',
              [
              'headers' =>
                [
                'Content-Type' => 'application/json'
                ],
              'body' =>json_encode(
                [
                "userName" => $request->input('email'),
                "password" => 'sin123',
                "displayName" => '-',
                "nip" => '0'
                ])
              ]
            );
            $ldapCreate = json_decode($requestCreate->getBody()->getContents(), true);
            //LDAP


            $this->form_params = array('email' => $request->input('email'),
                'group_id' => $group_id,
                'wilayah_id' => $wil,
                'active_flag' => 'Y');

            $trail['audit_menu'] = 'User Management - User';
            $trail['audit_event'] = 'post';
            $trail['audit_value'] = json_encode($this->form_params);
            $trail['audit_url'] = $request->url();
            $trail['audit_ip_address'] = $request->ip();
            $trail['audit_user_agent'] = $request->userAgent();
            $trail['audit_message'] = $resultUser['comment'];
            $trail['created_at'] = date("Y-m-d H:i:s");
            $trail['created_at'] = $request->session()->get('id');

            $qtrail = $this->inputtrail($request->session()->get('token'), $trail);

            $idUser = $resultUser['data']['eventID'];

            $insertdata = array();

            $prev_added_group_ids = array();

            foreach ($groups as $key => $value) {
                if (!in_array($value['id'], $prev_added_group_ids)) {
                    array_push($insertdata, array('user_id' => $idUser, 'group_id' => $value['id']));
                    $prev_added_group_ids[] = $value['id'];
                }
            }

            DB::table('user_group')->insert($insertdata);

            $requestPassword = $client->request('POST', url('/api/password/new_password'), [
                'headers' =>
                    [
                    'Accept' => 'application/json'
                ],
                'form_params' =>
                    [
                    'email' => $email
                ]
                    ]);

            $resultPassword = json_decode($requestPassword->getBody()->getContents(), true);

            if (($resultUser['code'] == 200) && ($resultUser['status'] != "error") && ($resultPassword['code'] == 200) && $resultPassword['status'] != "error") {
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Data User Berhasil Disimpan';

                return redirect('user_management/edit_user/' . $idUser)->with('status', $this->messages);
            }
        } else {
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Email sudah terdaftar';

            return back()->with('status', $this->messages);
        }
    }

    public function updateUser(Request $request) {
        $id = $request->input('id');
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $client = new Client();

        $groups = $request->input('meta_grup');

        if (count($groups) == 0) {
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Pilih minimal satu group';

            return back()->with('status', $this->messages);
        }

        $group_id = $groups[0]['id'];

        $requestUser = $client->request('PUT', $baseUrl . '/api/users/' . $id, [
            'headers' =>
                [
                'Authorization' => 'Bearer ' . $token
            ],
            'form_params' =>
                [
                'group_id' => $group_id,
                'active_flag' => ($request->input('active_flag') !== null) ? 'Y' : 'N'
            ]
                ]
        );
        $resultUser = json_decode($requestUser->getBody()->getContents(), true);

        $this->form_params = array('group_id' => $group_id,
            'active_flag' => ($request->input('active_flag') !== null) ? 'Y' : 'N');

        $trail['audit_menu'] = 'User Management - User';
        $trail['audit_event'] = 'put';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $resultUser['comment'];
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_at'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'), $trail);


        //clear the groups
        $groups_data = User_roles::where('user_id', $id);

        $groups_data->delete();

        $groups_result = $groups_data->get();

        $prev_added_group_ids = array();

        $insertdata = array();

        foreach ($groups as $key => $value) {
            if (!in_array($value['id'], $prev_added_group_ids)) {
                array_push($insertdata, array('user_id' => $id, 'group_id' => $value['id']));
                $prev_added_group_ids[] = $value['id'];
            }
        }

        DB::table('user_group')->insert($insertdata);

        if (($resultUser['code'] == 200) && ($resultUser['status'] != "error")) {
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data User Berhasil Diperbarui';

            return redirect('user_management/edit_user/' . $id)->with('status', $this->messages);
        }
    }

    public function printUser(Request $request) {
        $client = new Client();
        $token = $request->session()->get('token');
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');

        $get = $request->all();
        $kondisi = "";
        if (count($get) > 0) {
            foreach ($get as $key => $val) {
                $kondisi .= $key . '=' . $val . '&';
            }
            $kondisi = rtrim($kondisi, '&');
        }

        $requestPrintData = $client->request('GET', $baseUrl . '/api/users?' . $kondisi, [
            'headers' =>
                [
                'Authorization' => 'Bearer ' . $token
            ]
                ]
        );

        $requestWilayah = $client->request('GET', $baseUrl . '/api/wilayah');
        $wilayah = json_decode($requestWilayah->getBody()->getContents(), true);

        $nm_wilayah = array();

        foreach ($wilayah['data'] as $w_index => $w_value)
            $nm_wilayah[$w_value['id_wilayah']] = $w_value['nm_wilayah'];

        $PrintData = json_decode($requestPrintData->getBody()->getContents(), true);
        // dd($pemusnahanladang);
        $DataArray = [];

        $i = 1;
        foreach ($PrintData['data'] as $key => $value) {
            $DataArray[$key]['No'] = $i;
            $DataArray[$key]['Nama'] = $value['user_name'];
            $DataArray[$key]['Email'] = $value['email'];
            $DataArray[$key]['NIP'] = $value['nip'];
            $DataArray[$key]['Wilayah'] = ($value['wilayah_id'] != '') ? $nm_wilayah[$value['wilayah_id']] : '';
            $DataArray[$key]['Status Kepegawaian'] = ($value['nip'] != '') ? 'PNS' : 'PHL';
            $DataArray[$key]['Status'] = ($value['active_flag'] == 'Y') ? 'Aktif' : ($value['active_flag'] == 'N' ? 'Tidak Aktif' : '');

            $i = $i + 1;
        }
        //dd($DataArray);
        $data = $DataArray;
        $name = 'Data User' . Carbon::now()->format('Y-m-d H:i:s');
        $this->printData($data, $name);
    }

    public function deleteUser(Request $request) {
        $id = $request->input('id');
        if ($id) {
            if ($request->ajax()) {
                $id = $request->id;
                $data_request = execute_api('api/users/' . $id, 'DELETE');
                $this->form_params['delete_id'] = $id;
                $trail['audit_menu'] = 'User Management - User';
                $trail['audit_event'] = 'delete';
                $trail['audit_value'] = json_encode($this->form_params);
                $trail['audit_url'] = $request->url();
                $trail['audit_ip_address'] = $request->ip();
                $trail['audit_user_agent'] = $request->userAgent();
                $trail['audit_message'] = $data_request['comment'];
                $trail['created_at'] = date("Y-m-d H:i:s");
                $trail['created_at'] = $request->session()->get('id');

                $qtrail = $this->inputtrail($request->session()->get('token'), $trail);

                return $data_request;
            }
        } else {
            $data_request = ['status' => 'error', 'message' => 'Data User Gagal Dihapus'];
            return $data_request;
        }
    }

    public function resetPassword(Request $request) {
        $client = new Client();
        $id = $request->input('id');
        $user = Rbac_users::where(['user_id' => $id]);
        $user_obj = $user->first();
        $email = $user_obj->email;
        $count = $user->count();

        if ($count > 0) {
            try {
                $request = $client->request('POST', url('/api/password/email'), [
                    'headers' =>
                        [
                        'Accept' => 'application/json'
                    ],
                    'form_params' =>
                        [
                        'email' => $email
                    ]
                        ]);
                $this->data = json_decode($request->getBody()->getContents(), true);
                if ($this->data['status'] == 'success') {
                    $this->messages['status'] = 'success';
                    $this->messages['message'] = 'Link untuk mereset kata sandi sudah dikirimkan ke email pengguna.';
                } else {
                    $this->messages['status'] = 'error';
                    $this->messages['message'] = 'Kata sandi gagal untuk diupdate. Silahkan coba lagi.';
                }
                return redirect('user_management/edit_user/' . $id)->with('status', $this->messages);
            } catch (GuzzleException $e) {
                $response = $e->getResponse();
                $responseBodyAsString = $response->getBody()->getContents();
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Kata sandi gagal diperbarui.';
                return redirect('user_management/edit_user/' . $id)->with('status', $this->messages);
            }
        } else {
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'User tidak ditemukan.';
            return redirect('user_management/edit_user/' . $id)->with('status', $this->messages);
        }
    }

    public function resetEmail(Request $request) {
        $client = new Client();
        $id = $request->input('id');
        $user = Rbac_users::where(['user_id' => $id]);
        $user_obj = $user->first();
        $email = $user_obj->email;
        $new_email = $request->input('new_email');
        $count = $user->count();
        $token = $request->session()->get('token');

        $email_exists = (Rbac_users::where(['email' => $new_email])->count() > 0);

        if ($email == $new_email) {
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Email yang digunakan sama dengan alamat email yang lama. Silahkan coba dengan alamat email yang lain.';
            return redirect('user_management/edit_user/' . $id)->with('status', $this->messages);
        }

        if ($email_exists) {
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Email sudah dipakai. Silahkan coba dengan alamat email yang lain.';
            return redirect('user_management/edit_user/' . $id)->with('status', $this->messages);
        }

        if ($count > 0) {
            try {
                $request = $client->request('POST', url('/api/email/new_email'), [
                    'headers' =>
                        [
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer ' . $token
                    ],
                    'form_params' =>
                        [
                        'email' => $email,
                        'new_email' => $new_email
                    ]
                        ]);
                $this->data = json_decode($request->getBody()->getContents(), true);

                if ($this->data['status'] == 'success') {
                    $this->messages['status'] = 'success';
                    $this->messages['message'] = 'Link untuk mereset email sudah dikirimkan ke email pengguna.';
                } else {
                    $this->messages['status'] = 'error';
                    $this->messages['message'] = 'Email gagal untuk diupdate. Silahkan coba lagi.';
                }

                return redirect('user_management/edit_user/' . $id)->with('status', $this->messages);
            } catch (GuzzleException $e) {
                $response = $e->getResponse();
                $responseBodyAsString = $response->getBody()->getContents();
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Email gagal diperbarui.';
                return redirect('user_management/edit_user/' . $id)->with('status', $this->messages);
            }
        } else {
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'User tidak ditemukan.';
            return redirect('user_management/edit_user/' . $id)->with('status', $this->messages);
        }
    }

    public function updateNIP(Request $request) {
        $client = new Client();
        $id = $request->input('id');
        $user = Rbac_users::where(['user_id' => $id]);
        $user_obj = $user->first();
        $nip = $request->input('nip');
        $count = $user->count();
        $token = $request->session()->get('token');

        try {
            $requestSimpeg = $client->request('GET', config('app.url_soa') . 'simpeg/getstaffprofile?nip=' . $nip, [
                'form_params' =>
                    [
                    "nip" => $nip
                ]
                    ]
            );
        } catch (GuzzleException $e) {
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'NIP yang dimasukkan tidak valid.';

            return redirect('user_management/edit_user/' . $id)->with('status', $this->messages);
        }

        $nip_has_been_used = (Rbac_users::where('nip', $nip)->count() > 0);

        if ($nip_has_been_used) {
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'NIP yang dimasukkan sudah dipakai.';

            return redirect('user_management/edit_user/' . $id)->with('status', $this->messages);
        }

        if ($count > 0) {

            $simpeg = $this->getSimpeg($nip);

            Rbac_users::where('user_id', $id)->update(array(
                'nip' => $nip,
                'jabatan_pegawai' => $simpeg['data']['jabatan'],
                'user_name' => $simpeg['data']['nama'],
                'foto_pegawai' => null
            ));

            $this->messages['status'] = 'success';
            $this->messages['message'] = 'NIP Anda berhasil disimpan.';

            return redirect('user_management/edit_user/' . $id)->with('status', $this->messages);
        } else {
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'User tidak ditemukan.';
            return redirect('user_management/edit_user/' . $id)->with('status', $this->messages);
        }
    }

    public function dataGroup(Request $request) {
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
//	      $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $kondisi = '';
        if ($request->limit) {
            $limit = $request->limit;
        } else {
            $limit = config('app.limit');
        }
        if ($request->order == Null) {
            $order = 'asc';
        } else {
            $order = $request->order;
        }

        if ($request->limit == Null) {
            $limit = config('constant.LIMITPAGE');
        } else {
            $limit = $request->limit;
        }
        if ($request->page == Null) {
            $current_page = 1;
            $start_number = 1;
        } else {
            $current_page = $request->page;
            $start_number = ($limit * ($request->page - 1 )) + 1;
        }

        if ($request->isMethod('get')) {
            $get = $request->all();
            $get = $request->except(['page']);
            $tipe = $request->tipe;
            if (count($get) > 0) {
                $this->selected['tipe'] = $tipe;
                foreach ($get as $key => $value) {
                    $kondisi .= "&" . $key . '=' . $value;
                    if (($key == 'tgl_from') || ($key == 'tgl_to')) {
                        $this->selected[$key] = date('d/m/Y', strtotime($value));
                    } else {
                        $this->selected[$key] = $value;
                    }
                }
            }
        } else {
            $tipe = $request->tipe;
            $kata_kunci = $request->kata_kunci;

            $kondisi .= '&kata_kunci=' . $kata_kunci;
            $this->selected['kata_kunci'] = $kata_kunci;
            $kondisi .= '&tipe=' . $tipe;
            $this->selected['tipe'] = $tipe;

            $kondisi .= '&limit=' . $limit;
            $kondisi .= '&order=' . $order;
            $this->selected['limit'] = $limit;
            $this->selected['order'] = $order;
        }

        $kondisifilter = array();
        if ($request->kata_kunci != '') {
            array_push($kondisifilter, array('group_name', 'ilike', '%' . $request->kata_kunci . '%'));
        }

        $qresults = DB::table('rbac_groups')->where($kondisifilter);

        $total_results = $qresults->count();
        $offset = ($current_page - 1) * $limit;
        $totalpage = ceil($total_results / $limit);

        $datas = $qresults->orderBy('group_name', $order)->offset($offset)->limit($limit)->get();

        $this->data['datamaster'] = json_decode($datas, true);

        if ($kondisi) {
            $filter = $kondisi;
            $filtering = true;
        } else {
            $filter = '/';
            $filtering = false;
        }

        $requestwilayah = $client->request('GET', $baseUrl . '/api/wilayah');
        $wilayah = json_decode($requestwilayah->getBody()->getContents(), true);
        $this->data['wilayah'] = $wilayah['data'];

        $this->data['title'] = "Master Data Group";
        $this->data['delete_route'] = 'delete_dataGroup';
        $this->data['current_page'] = $current_page;
        $this->data['start_number'] = $start_number;
        $this->data['filter'] = $this->selected;
        $this->data['route'] = $request->route()->getName();
        $this->data['pagination'] = paginations($current_page, $total_results, $limit, config('app.page_ellipsis'), config('app.url') . "/user_management/group", $filtering, $filter);
        $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

        return view('user_management.group.index_dataGroup', $this->data);
    }

    public function adddataGroup(Request $request) {
        $client = new Client();
        $baseUrl = URL::to('/');

        $this->data['datamenu'] = $this->menu_by_parent(0, '', 1);
        $this->datamenu = [];
        $this->data['datamobile'] = $this->menu_by_parent(0, '', 2);

        $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());
        return view('user_management.group.add_dataGroup', $this->data);
    }

    public function editdataGroup(Request $request) {
        $id = $request->id;
        $client = new Client();
        $baseUrl = URL::to('/');

        $groups = DB::table('rbac_groups')->where('group_id', $id)->get();
        // dd($groups);
        $this->data['group'] = json_decode($groups, true);

        $datas = DB::table('rbac_role_group')->where('group_id', $id)->get();
        $datamaster = json_decode($datas, true);
        $role = [];
        foreach ($datamaster as $d) {
            $role[$d['menu_id']]['read'] = $d['can_read'];
            $role[$d['menu_id']]['create'] = $d['can_create'];
            $role[$d['menu_id']]['update'] = $d['can_edit'];
            $role[$d['menu_id']]['delete'] = $d['can_delete'];
        }
        $this->data['role'] = $role;

        $this->data['datamenu'] = $this->menu_by_parent(0, '', 1);
        $this->datamenu = [];
        $this->data['datamobile'] = $this->menu_by_parent(0, '', 2);

        $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());        
        return view('user_management.group.edit_dataGroup', $this->data);
    }

    public function inputdataGroup(Request $request) {
        $client = new Client();
        $baseUrl = URL::to('/');

        $input['group_name'] = $request->input('group_name');
        $data = Group::create($input);

        $trail['audit_menu'] = 'User Management - Group';
        $trail['audit_event'] = 'post';
        $trail['audit_value'] = json_encode($input);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = '';
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_at'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'), $trail);

        $insertID = $data->group_id;

        $menu = $this->menu_by_parent(0, '', 1);
        foreach ($menu as $m) {
            $inputData['group_id'] = $insertID;
            $inputData['menu_id'] = $m['menu_id'];
            // dd($request->input('read'));
            if ($request->input('read') != null) {
                (in_array($m['menu_id'], $request->input('read')) ? $inputData['can_read'] = TRUE : $inputData['can_read'] = FALSE);
            } else {
                $inputData['can_read'] = FALSE;
            }
            if ($request->input('create') != null) {
                (in_array($m['menu_id'], $request->input('create')) ? $inputData['can_create'] = TRUE : $inputData['can_create'] = FALSE);
            } else {
                $inputData['can_create'] = FALSE;
            }
            if ($request->input('update') != null) {
                (in_array($m['menu_id'], $request->input('update')) ? $inputData['can_edit'] = TRUE : $inputData['can_edit'] = FALSE);
            } else {
                $inputData['can_edit'] = FALSE;
            }
            if ($request->input('delete') != null) {
                (in_array($m['menu_id'], $request->input('delete')) ? $inputData['can_delete'] = TRUE : $inputData['can_delete'] = FALSE);
            } else {
                $inputData['can_delete'] = FALSE;
            }

            DB::table('rbac_role_group')->insert($inputData);
        }

        $mobile = $this->menu_by_parent(0, '', 2);
        foreach ($mobile as $o) {
            $inputData['group_id'] = $insertID;
            $inputData['menu_id'] = $o['menu_id'];
            if ($request->input('read') != null) {
                (in_array($o['menu_id'], $request->input('read')) ? $inputData['can_read'] = TRUE : $inputData['can_read'] = FALSE);
            } else {
                $inputData['can_read'] = FALSE;
            }
            if ($request->input('create') != null) {
                (in_array($o['menu_id'], $request->input('create')) ? $inputData['can_create'] = TRUE : $inputData['can_create'] = FALSE);
            } else {
                $inputData['can_create'] = FALSE;
            }
            if ($request->input('update') != null) {
                (in_array($o['menu_id'], $request->input('update')) ? $inputData['can_edit'] = TRUE : $inputData['can_edit'] = FALSE);
            } else {
                $inputData['can_edit'] = FALSE;
            }
            if ($request->input('delete') != null) {
                (in_array($o['menu_id'], $request->input('delete')) ? $inputData['can_delete'] = TRUE : $inputData['can_delete'] = FALSE);
            } else {
                $inputData['can_delete'] = FALSE;
            }

            DB::table('rbac_role_group')->insert($inputData);
        }

        return redirect('/user_management/group');
    }

    public function updatedataGroup(Request $request) {
        $id = $request->input('id');
        $client = new Client();
        $baseUrl = URL::to('/');

        $input['group_name'] = $request->input('group_name');
        $data = DB::table('rbac_groups')->where('group_id', $id)->update($input);

        $trail['audit_menu'] = 'User Management - Group';
        $trail['audit_event'] = 'put';
        $trail['audit_value'] = json_encode($input);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = '';
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_at'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'), $trail);


        $menu = $this->menu_by_parent(0, '', 1);
        foreach ($menu as $m) {
            $inputData = [];
            (in_array($m['menu_id'], $request->input('read')) ? $inputData['can_read'] = TRUE : $inputData['can_read'] = FALSE);
            (in_array($m['menu_id'], $request->input('create')) ? $inputData['can_create'] = TRUE : $inputData['can_create'] = FALSE);
            (in_array($m['menu_id'], $request->input('update')) ? $inputData['can_edit'] = TRUE : $inputData['can_edit'] = FALSE);
            (in_array($m['menu_id'], $request->input('delete')) ? $inputData['can_delete'] = TRUE : $inputData['can_delete'] = FALSE);

            $ins = DB::table('rbac_role_group')
                    ->where('group_id', $id)
                    ->where('menu_id', $m['menu_id'])
                    ->update($inputData);

            if ($ins == 0) {
                $inputData['group_id'] = $id;
                $inputData['menu_id'] = $m['menu_id'];
                DB::table('rbac_role_group')->insert($inputData);
            }
        }

        $mobile = $this->menu_by_parent(0, '', 2);
        foreach ($mobile as $o) {
            $inputData = [];
            (in_array($o['menu_id'], $request->input('read')) ? $inputData['can_read'] = TRUE : $inputData['can_read'] = FALSE);
            (in_array($o['menu_id'], $request->input('create')) ? $inputData['can_create'] = TRUE : $inputData['can_create'] = FALSE);
            (in_array($o['menu_id'], $request->input('update')) ? $inputData['can_edit'] = TRUE : $inputData['can_edit'] = FALSE);
            (in_array($o['menu_id'], $request->input('delete')) ? $inputData['can_delete'] = TRUE : $inputData['can_delete'] = FALSE);

            $ins = DB::table('rbac_role_group')
                    ->where('group_id', $id)
                    ->where('menu_id', $o['menu_id'])
                    ->update($inputData);

            if ($ins == 0) {
                $inputData['group_id'] = $id;
                $inputData['menu_id'] = $o['menu_id'];
                DB::table('rbac_role_group')->insert($inputData);
            }
        }

        $this->data['breadcrumps'] = breadcrumps_dir_advokasi($request->route()->getName());

        return redirect('/user_management/group');
    }

    public function deletedataGroup(Request $request) {
        if ($request->ajax()) {
            $id = $request->id;
            $del = DB::table('rbac_groups')->where('group_id', $id)->delete();
            $this->form_params['delete_id'] = $id;
            $trail['audit_menu'] = 'User Management - Group';
            $trail['audit_event'] = 'delete';
            $trail['audit_value'] = json_encode($this->form_params);
            $trail['audit_url'] = $request->url();
            $trail['audit_ip_address'] = $request->ip();
            $trail['audit_user_agent'] = $request->userAgent();
            $trail['audit_message'] = '';
            $trail['created_at'] = date("Y-m-d H:i:s");
            $trail['created_at'] = $request->session()->get('id');

            $qtrail = $this->inputtrail($request->session()->get('token'), $trail);

            if ($del) {
                DB::table('rbac_role_group')->where('group_id', $id)->delete();
                $data_request = ['code' => 200, 'status' => 'Sukses', 'message' => 'Data Group Berhasil Dihapus'];
                return $data_request;
            } else {
                $data_request = ['code' => 200, 'status' => 'error', 'message' => 'Data Group Gagal Dihapus'];
                return $data_request;
            }
        }
    }

    private function menu_by_parent($id, $space, $type) {
        $qresults = DB::table('rbac_menu_appl')->where('type', $type)->where('active', 'Y')->where('menu_id_parent', $id)->orderBy('menu_order', 'asc')->get();
        $result = json_decode($qresults, true);
        foreach ($result as $r) {
            $r['menu_title'] = $space . ' ' . $r['menu_title'];
            array_push($this->datamenu, $r);
            $this->menu_by_parent($r['menu_id'], $space . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $type);
        }

        return $this->datamenu;
    }

    public function loginLog(Request $request) {
        $client = new Client();
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $kondisi = '';
        if ($request->limit) {
            $limit = $request->limit;
        } else {
            $limit = config('app.limit');
        }
        if ($request->order == Null) {
            $order = 'desc';
        } else {
            $order = $request->order;
        }

        if ($request->limit == Null) {
            $limit = config('constant.LIMITPAGE');
        } else {
            $limit = $request->limit;
        }
        if ($request->page == Null) {
            $current_page = 1;
            $start_number = 1;
        } else {
            $current_page = $request->page;
            $start_number = ($limit * ($request->page - 1 )) + 1;
        }

        if ($request->isMethod('get')) {
            $get = $request->all();
            $get = $request->except(['page']);
            $tipe = $request->tipe;
            if (count($get) > 0) {
                $this->selected['tipe'] = $tipe;
                foreach ($get as $key => $value) {
                    $kondisi .= "&" . $key . '=' . $value;
                    if (($key == 'waktu_from') || ($key == 'waktu_to')) {
                        $this->selected[$key] = date('d/m/Y', strtotime($value));
                    } else {
                        $this->selected[$key] = $value;
                        // $this->selected['keyword'] = $value;
                    }
                }

                $this->selected['order'] = $request->order;
                $this->selected['limit'] = $request->limit;
            }
        } else {
            $tipe = $request->tipe;
            $kata_kunci = $request->kata_kunci;
            $pelaksana = $request->pelaksana;
            // $namapelaksana = $request->namapelaksana;
            $anggaran = $request->anggaran;
            $waktu_from = $request->waktu_from;
            $waktu_to = $request->waktu_to;
            $order = $request->order;
            $limit = $request->limit;
            $kelengkapan = $request->kelengkapan;

            if ($tipe == 'periode') {
                if ($waktu_from) {
                    $date = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $waktu_from)));
                    $kondisi .= '&waktu_from=' . $date;
                    $this->selected['waktu_from'] = $waktu_from;
                } else {
                    $kondisi .= '';
                }
                if ($waktu_to) {
                    $date = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $waktu_to)));
                    $kondisi .= '&waktu_to=' . $date;
                    $this->selected['waktu_to'] = $waktu_to;
                } else {
                    $kondisi .= '';
                }
            }

            $kondisi .= '&kata_kunci=' . $kata_kunci;
            $this->selected['kata_kunci'] = $kata_kunci;
            $kondisi .= '&tipe=' . $tipe;
            $this->selected['tipe'] = $tipe;

            $kondisi .= '&limit=' . $limit;
            $kondisi .= '&order=' . $order;
            $this->selected['limit'] = $limit;
            $this->selected['order'] = $order;
        }

        $kondisifilter = array();
        if ($tipe == 'periode') {
            if ($waktu_from) {
                array_push($kondisifilter, array('waktu_login', '>=', '%' . date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $waktu_from))) . '%'));
            }
            if ($waktu_to) {
                array_push($kondisifilter, array('waktu_login', '<=', '%' . date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $waktu_to))) . '%'));
            }
        }
        if ($request->kata_kunci != '') {
            array_push($kondisifilter, array('nama', 'ilike', '%' . $request->kata_kunci . '%'));
        }

        $qresults = DB::table('v_user_login')->where($kondisifilter);

        $total_results = $qresults->count();
        $offset = ($current_page - 1) * $limit;
        $totalpage = ceil($total_results / $limit);

        $datas = $qresults->orderBy('waktu_login', $order)->offset($offset)->limit($limit)->get();

        $this->data['datamaster'] = json_decode($datas, true);

        if ($kondisi) {
            $filter = $kondisi;
            $filtering = true;
        } else {
            $filter = '/';
            $filtering = false;
        }

        $this->data['title'] = "User Log";
        $this->data['delete_route'] = 'delete_dataGroup';
        $this->data['current_page'] = $current_page;
        $this->data['start_number'] = $start_number;
        $this->data['filter'] = $this->selected;
        $this->data['route'] = $request->route()->getName();
        $this->data['pagination'] = paginations($current_page, $total_results, $limit, config('app.page_ellipsis'), config('app.url') . "/user_management/loginlog", $filtering, $filter);
        $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

        return view('user_management.log.index_dataLog', $this->data);
    }

    public function userLog(Request $request) {
        $client = new Client();
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');
        
        $kondisi = '';
        if ($request->limit) {
            $limit = $request->limit;
        } else {
            $limit = config('app.limit');
        }
        if ($request->order == Null) {
            $order = 'desc';
        } else {
            $order = $request->order;
        }

        if ($request->limit == Null) {
            $limit = config('constant.LIMITPAGE');
        } else {
            $limit = $request->limit;
        }
        if ($request->page == Null) {
            $current_page = 1;
            $start_number = 1;
        } else {
            $current_page = $request->page;
            $start_number = ($limit * ($request->page - 1 )) + 1;
        }

        if ($request->isMethod('get')) {
            $get = $request->all();
            $get = $request->except(['page']);
            $tipe = $request->tipe;
            if (count($get) > 0) {
                $this->selected['tipe'] = $tipe;
                foreach ($get as $key => $value) {
                    $kondisi .= "&" . $key . '=' . $value;
                    if (($key == 'waktu_from') || ($key == 'waktu_to')) {
                        $this->selected[$key] = date('d/m/Y', strtotime($value));
                    } else {
                        $this->selected[$key] = $value;
                        // $this->selected['keyword'] = $value;
                    }
                }

                $this->selected['order'] = $request->order;
                $this->selected['limit'] = $request->limit;
            }
        } else {
            $tipe = $request->tipe;
            $kata_kunci = $request->kata_kunci;
            $pelaksana = $request->pelaksana;
            // $namapelaksana = $request->namapelaksana;
            $anggaran = $request->anggaran;
            $waktu_from = $request->waktu_from;
            $waktu_to = $request->waktu_to;
            $order = $request->order;
            $limit = $request->limit;
            $kelengkapan = $request->kelengkapan;

            if ($tipe == 'periode') {
                if ($waktu_from) {
                    $date = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $waktu_from)));
                    $kondisi .= '&waktu_from=' . $date;
                    $this->selected['waktu_from'] = $waktu_from;
                } else {
                    $kondisi .= '';
                }
                if ($waktu_to) {
                    $date = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $waktu_to)));
                    $kondisi .= '&waktu_to=' . $date;
                    $this->selected['waktu_to'] = $waktu_to;
                } else {
                    $kondisi .= '';
                }
            }
            // elseif($tipe == 'pelaksana'){
            //                 $kondisi .= '&pelaksana='.$pelaksana;
            //                 $this->selected['pelaksana'] = $pelaksana;
            //             }

            $kondisi .= '&kata_kunci=' . $kata_kunci;
            $this->selected['kata_kunci'] = $kata_kunci;
            $kondisi .= '&tipe=' . $tipe;
            $this->selected['tipe'] = $tipe;

            $kondisi .= '&limit=' . $limit;
            $kondisi .= '&order=' . $order;
            $this->selected['limit'] = $limit;
            $this->selected['order'] = $order;
        }

        $kondisifilter = array();
        if ($tipe == 'periode') {
            if ($waktu_from) {
                array_push($kondisifilter, array('created_at', '>=', '%' . date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $waktu_from))) . '%'));
            }
            if ($waktu_to) {
                array_push($kondisifilter, array('created_at', '<=', '%' . date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $waktu_to))) . '%'));
            }
        }
        if ($tipe == 'menu') {
            array_push($kondisifilter, array('audit_menu', 'ilike', '%' . $request->kata_kunci . '%'));
        }
        if ($tipe == 'event') {
            array_push($kondisifilter, array('audit_event', 'ilike', '%' . $request->kata_kunci . '%'));
        }
        if ($tipe == 'url') {
            array_push($kondisifilter, array('audit_url', 'ilike', '%' . $request->kata_kunci . '%'));
        }
        if ($tipe == 'ip') {
            array_push($kondisifilter, array('audit_ip_address', 'ilike', '%' . $request->kata_kunci . '%'));
        }
        if ($tipe == 'user') {
            array_push($kondisifilter, array('nama', 'ilike', '%' . $request->kata_kunci . '%'));
        }
        if ($tipe == 'pelaksana') {
            array_push($kondisifilter, array('satker', 'ilike', '%' . $request->kata_kunci . '%'));
        }

        $qresults = DB::table('v_audit')->where($kondisifilter);

        $total_results = $qresults->count();
        $offset = ($current_page - 1) * $limit;
        $totalpage = ceil($total_results / $limit);

        $datas = $qresults->orderBy('created_at', $order)->offset($offset)->limit($limit)->get();

        $this->data['datamaster'] = json_decode($datas, true);

        if ($kondisi) {
            $filter = $kondisi;
            $filtering = true;
        } else {
            $filter = '/';
            $filtering = false;
        }
        // $instansi = $this->globalinstansi($request->session()->get('wilayah'), $token);
        // $this->data['instansi'] = $instansi;
        // $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['title'] = "Activity Log";
        $this->data['delete_route'] = 'delete_dataGroup';
        $this->data['current_page'] = $current_page;
        $this->data['start_number'] = $start_number;
        $this->data['filter'] = $this->selected;
        $this->data['route'] = $request->route()->getName();
        $this->data['pagination'] = paginations($current_page, $total_results, $limit, config('app.page_ellipsis'), config('app.url') . "/user_management/userlog", $filtering, $filter);
        $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

        return view('user_management.userlog.index_dataLog', $this->data);
    }

}

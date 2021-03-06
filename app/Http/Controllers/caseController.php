<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\MainModel;
use App\Tr_BrgBukti;
use App\Models\BrgBukti;
use URL;
use DateTime;
use Carbon\Carbon;
use App\Models\Dayamas\AltdevLahanganjaPetani;
use App\Models\Dayamas\AltdevProfesiPeserta;
use App\Models\Dayamas\MonevKawasanrawanPeserta;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Storage;

class caseController extends Controller
{
    public $data;
    public $messages;
    public $limit;
    public $form_params = [];

    public function inputNihil(Request $request){
      $user = $request->session()->get('user_data');
      $client = new Client();
      $baseUrl = URL::to($this->urlapi());
//      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');
      $requestNihil = $client->request('POST', $baseUrl.'/api/monitoringnihil',
          [
              'headers' =>
              [
                  'Authorization' => 'Bearer '.$token
              ],
              'form_params' =>
              [
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'created_by' => $user['id'],
                'created_by_username' => $user['name'],
                'nama_kegiatan' => $request->input('uri_module'),
                'tgl_pelaksanaan' => Carbon::now()->format('Y-m-d'),
                'keterangan' => $request->input('keterangan_nihil'),
                'status_entri' => 'nihil',
                'idpelaksana' => $request->input('idpelaksana_nihil')
              ]
          ]
      );

      return redirect($request->input('uri_module'));
    }

    public function inputTersangka(Request $request){
        $id = $request->input('id');
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        // dd($request->all());

        $client = new Client();

        $request1 = $client->request('POST', $baseUrl.'/api/tersangka',
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' => [
                        "kasus_id" => $request->input('id'),
                        "pasal" => $request->input('pasal'),
                        "tersangka_nama" => $request->input('tersangka_nama'),
                        "kode_jenisidentitas" => $request->input('kode_jenisidentitas'),
                        "no_identitas" => $request->input('no_identitas'),
                        "tersangka_alamat" => $request->input('tersangka_alamat'),
                        "alamatktp_idkabkota" => $request->input('alamatktp_idkabkota'),
                        "alamatktp_kodepos" => $request->input('alamatktp_kodepos'),
                        "alamatdomisili" => $request->input('alamatdomisili'),
                        "alamatdomisili_idkabkota" => $request->input('alamatdomisili_idkabkota'),
                        "alamatdomisili_kodepos" => $request->input('alamatdomisili_kodepos'),
                        "alamatlainnya" => $request->input('alamatlainnya'),
                        "alamatlainnya_idkabkota" => $request->input('alamatlainnya_idkabkota'),
                        "alamatlainnya_kodepos" => $request->input('alamatlainnya_kodepos'),
                        "kode_jenis_kelamin" => $request->input('kode_jenis_kelamin'),
                        "tersangka_tempat_lahir" => $request->input('tersangka_tempat_lahir'),
                        "tersangka_tanggal_lahir" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tersangka_tanggal_lahir')))),
                        "tersangka_usia" => $request->input('tersangka_usia'),
                        "kode_pendidikan_akhir" => $request->input('kode_pendidikan_akhir'),
                        "kode_pekerjaan" => $request->input('kode_pekerjaan'),
                        "kode_warga_negara" => $request->input('kode_warga_negara'),
                        "kode_negara" => $request->input('kode_negara'),
                        "kode_peran_tersangka" => $request->input('kode_peran_tersangka'),
                        "created_by" => $request->session()->get('id'),
                        "create_date" => date("Y-m-d H:i:s"),
                    ]
                ]
            );

            $return  = json_decode($request1->getBody()->getContents(), false);

            $this->form_params = array("kasus_id" => $request->input('id'),
            "pasal" => $request->input('pasal'),
            "tersangka_nama" => $request->input('tersangka_nama'),
            "kode_jenisidentitas" => $request->input('kode_jenisidentitas'),
            "no_identitas" => $request->input('no_identitas'),
            "tersangka_alamat" => $request->input('tersangka_alamat'),
            "alamatktp_idkabkota" => $request->input('alamatktp_idkabkota'),
            "alamatktp_kodepos" => $request->input('alamatktp_kodepos'),
            "alamatdomisili" => $request->input('alamatdomisili'),
            "alamatdomisili_idkabkota" => $request->input('alamatdomisili_idkabkota'),
            "alamatdomisili_kodepos" => $request->input('alamatdomisili_kodepos'),
            "alamatlainnya" => $request->input('alamatlainnya'),
            "alamatlainnya_idkabkota" => $request->input('alamatlainnya_idkabkota'),
            "alamatlainnya_kodepos" => $request->input('alamatlainnya_kodepos'),
            "kode_jenis_kelamin" => $request->input('kode_jenis_kelamin'),
            "tersangka_tempat_lahir" => $request->input('tersangka_tempat_lahir'),
            'tersangka_tanggal_lahir' => ( $request->input('tersangka_tanggal_lahir') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tersangka_tanggal_lahir')))) : ''),
            "tersangka_usia" => $request->input('tersangka_usia'),
            "kode_pendidikan_akhir" => $request->input('kode_pendidikan_akhir'),
            "kode_pekerjaan" => $request->input('kode_pekerjaan'),
            "kode_warga_negara" => $request->input('kode_warga_negara'),
            "kode_negara" => $request->input('kode_negara'),
            "kode_peran_tersangka" => $request->input('kode_peran_tersangka'));

            $trail['audit_menu'] = 'Pemberantasan - Pendataan LKN - Tersangka';
            $trail['audit_event'] = 'post';
            $trail['audit_value'] = json_encode($this->form_params);
            $trail['audit_url'] = $request->url();
            $trail['audit_ip_address'] = $request->ip();
            $trail['audit_user_agent'] = $request->userAgent();
            $trail['audit_message'] = $return->comment;
            $trail['created_at'] = date("Y-m-d H:i:s");
            $trail['created_by'] = $request->session()->get('id');

            $qtrail = $this->inputtrail($token,$trail);


        if($request1){
            $client->request('PUT', $baseUrl.'/api/kasus/'.$id,
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ]
                ]
            );
            }

        // return redirect('pemberantasan/dir_narkotika/edit_pendataan_lkn/'.$id);
        // return redirect('pemberantasan/dir_psikotropika/edit_psi_pendataan_lkn/'.$id);
        return back()->with($id);
    }

    public function updateTersangka(Request $request){
        $id = $request->input('id');
        $id_tersangka = $request->input('tersangka_id');
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        // dd($request->all());

        $client = new Client();

        $request1 = $client->request('PUT', $baseUrl.'/api/tersangka/'.$id_tersangka,
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' => [
                        "kasus_id" => $request->input('id'),
                        "pasal" => $request->input('pasal'),
                        "tersangka_nama" => $request->input('tersangka_nama'),
                        "kode_jenisidentitas" => $request->input('kode_jenisidentitas'),
                        "no_identitas" => $request->input('no_identitas'),
                        "tersangka_alamat" => $request->input('tersangka_alamat'),
                        "alamatktp_idkabkota" => $request->input('alamatktp_idkabkota'),
                        "alamatktp_kodepos" => $request->input('alamatktp_kodepos'),
                        "alamatdomisili" => $request->input('alamatdomisili'),
                        "alamatdomisili_idkabkota" => $request->input('alamatdomisili_idkabkota'),
                        "alamatdomisili_kodepos" => $request->input('alamatdomisili_kodepos'),
                        "alamatlainnya" => $request->input('alamatlainnya'),
                        "alamatlainnya_idkabkota" => $request->input('alamatlainnya_idkabkota'),
                        "alamatlainnya_kodepos" => $request->input('alamatlainnya_kodepos'),
                        "kode_jenis_kelamin" => $request->input('kode_jenis_kelamin'),
                        "tersangka_tempat_lahir" => $request->input('tersangka_tempat_lahir'),
                        'tersangka_tanggal_lahir' => ( $request->input('tersangka_tanggal_lahir') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tersangka_tanggal_lahir')))) : ''),
                        "tersangka_usia" => $request->input('tersangka_usia'),
                        "kode_pendidikan_akhir" => $request->input('kode_pendidikan_akhir'),
                        "kode_pekerjaan" => $request->input('kode_pekerjaan'),
                        "kode_warga_negara" => $request->input('kode_warga_negara'),
                        "kode_negara" => $request->input('kode_negara'),
                        "kode_peran_tersangka" => $request->input('kode_peran_tersangka'),
                        "updated_by" => $request->session()->get('id'),
                        "create_date" => date("Y-m-d H:i:s"),
                    ]
                ]
            );

            $return  = json_decode($request1->getBody()->getContents(), false);


            $this->form_params = array("kasus_id" => $request->input('id'),
            "pasal" => $request->input('pasal'),
            "tersangka_nama" => $request->input('tersangka_nama'),
            "kode_jenisidentitas" => $request->input('kode_jenisidentitas'),
            "no_identitas" => $request->input('no_identitas'),
            "tersangka_alamat" => $request->input('tersangka_alamat'),
            "alamatktp_idkabkota" => $request->input('alamatktp_idkabkota'),
            "alamatktp_kodepos" => $request->input('alamatktp_kodepos'),
            "alamatdomisili" => $request->input('alamatdomisili'),
            "alamatdomisili_idkabkota" => $request->input('alamatdomisili_idkabkota'),
            "alamatdomisili_kodepos" => $request->input('alamatdomisili_kodepos'),
            "alamatlainnya" => $request->input('alamatlainnya'),
            "alamatlainnya_idkabkota" => $request->input('alamatlainnya_idkabkota'),
            "alamatlainnya_kodepos" => $request->input('alamatlainnya_kodepos'),
            "kode_jenis_kelamin" => $request->input('kode_jenis_kelamin'),
            "tersangka_tempat_lahir" => $request->input('tersangka_tempat_lahir'),
            "tersangka_tanggal_lahir" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tersangka_tanggal_lahir')))),
            "tersangka_usia" => $request->input('tersangka_usia'),
            "kode_pendidikan_akhir" => $request->input('kode_pendidikan_akhir'),
            "kode_pekerjaan" => $request->input('kode_pekerjaan'),
            "kode_warga_negara" => $request->input('kode_warga_negara'),
            "kode_negara" => $request->input('kode_negara'),
            "kode_peran_tersangka" => $request->input('kode_peran_tersangka'));

            $trail['audit_menu'] = 'Pemberantasan - Pendataan LKN - Tersangka';
            $trail['audit_event'] = 'put';
            $trail['audit_value'] = json_encode($this->form_params);
            $trail['audit_url'] = $request->url();
            $trail['audit_ip_address'] = $request->ip();
            $trail['audit_user_agent'] = $request->userAgent();
            $trail['audit_message'] = $return->comment;
            $trail['created_at'] = date("Y-m-d H:i:s");
            $trail['created_by'] = $request->session()->get('id');

            $qtrail = $this->inputtrail($token,$trail);


        if($request1){
            $client->request('PUT', $baseUrl.'/api/kasus/'.$id,
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ]
                ]
            );
            }

        // return redirect('pemberantasan/dir_narkotika/edit_pendataan_lkn/'.$id);
        // return redirect('pemberantasan/dir_psikotropika/edit_psi_pendataan_lkn/'.$id);
        return back()->with($id);
    }

    public function inputBrgBukti(Request $request){
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');
        $id = $request->input('id');

        // dd($request->all());

        $client = new Client();

        $request1 = $client->request('POST', $baseUrl.'/api/kasusbrgbukti',
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' => [
                      "kasus_id" => $request->input('id'),
                      "id_brgbukti" => $request->input('jenisKasus'),
                      "jumlah_barang_bukti" => $request->input('jumlah_barang_bukti'),
                      "kode_satuan_barang_bukti" => $request->input('kode_satuan_barang_bukti'),
                      "created_by" => $request->session()->get('id'),
                      "create_date" => date("Y-m-d H:i:s"),
                    ]
                ]
            );

            $return  = json_decode($request1->getBody()->getContents(), false);

            $this->form_params = array("kasus_id" => $request->input('id'),
            "id_brgbukti" => $request->input('jenisKasus'),
            "jumlah_barang_bukti" => $request->input('jumlah_barang_bukti'),
            "kode_satuan_barang_bukti" => $request->input('kode_satuan_barang_bukti'));

            $trail['audit_menu'] = 'Pemberantasan - Pendataan LKN - Barang Bukti Narkotika';
            $trail['audit_event'] = 'post';
            $trail['audit_value'] = json_encode($this->form_params);
            $trail['audit_url'] = $request->url();
            $trail['audit_ip_address'] = $request->ip();
            $trail['audit_user_agent'] = $request->userAgent();
            $trail['audit_message'] = $return->comment;
            $trail['create_date'] = date("Y-m-d H:i:s");
            $trail['created_by'] = $request->session()->get('id');

            $qtrail = $this->inputtrail($token,$trail);


            if($request1){
            $client->request('PUT', $baseUrl.'/api/kasus/'.$id,
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ]
                ]
            );
            }

            // dd(json_decode($request->getBody()->getContents(), true));

        // return redirect('pemberantasan/dir_narkotika/edit_pendataan_lkn/'.$id);
        // return redirect('pemberantasan/dir_psikotropika/edit_psi_pendataan_lkn/'.$id);
        return back()->with($id);
    }

    public function updateBrgBukti(Request $request){
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');
        $id = $request->input('id');
        $bbid = $request->input('bbId');


        // dd($request->all());

        $client = new Client();

      $request1 = $client->request('PUT', $baseUrl.'/api/kasusbrgbukti/'.$bbid,
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' => [
                      "kasus_id" => $request->input('id'),
                      "id_brgbukti" => $request->input('jenisKasus'),
                      "jumlah_barang_bukti" => $request->input('jumlah_barang_bukti'),
                      "kode_satuan_barang_bukti" => $request->input('kode_satuan_barang_bukti'),
                      "updated_by" => $request->session()->get('id'),
                      "update_date" => date("Y-m-d H:i:s"),
                    ]
                ]
            );

            $return  = json_decode($request1->getBody()->getContents(), false);

            $this->form_params = array("kasus_id" => $request->input('id'),
            "id_brgbukti" => $request->input('jenisKasus'),
            "jumlah_barang_bukti" => $request->input('jumlah_barang_bukti'),
            "kode_satuan_barang_bukti" => $request->input('kode_satuan_barang_bukti'));

            $trail['audit_menu'] = 'Pemberantasan - Pendataan LKN - Barang Bukti Narkotika';
            $trail['audit_event'] = 'put';
            $trail['audit_value'] = json_encode($this->form_params);
            $trail['audit_url'] = $request->url();
            $trail['audit_ip_address'] = $request->ip();
            $trail['audit_user_agent'] = $request->userAgent();
            $trail['audit_message'] = $return->comment;
            $trail['update_date'] = date("Y-m-d H:i:s");
            $trail['updated_by'] = $request->session()->get('id');

            $qtrail = $this->inputtrail($token,$trail);


            if($request1){
            $client->request('PUT', $baseUrl.'/api/kasus/'.$id,
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ]
                ]
            );
            }

            // dd(json_decode($request->getBody()->getContents(), true));

        // return redirect('pemberantasan/dir_narkotika/edit_pendataan_lkn/'.$id);
        // return redirect('pemberantasan/dir_psikotropika/edit_psi_pendataan_lkn/'.$id);
        return back()->with($id);
    }

    public function inputBrgBuktiPrekursor(Request $request){
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');
        $id = $request->input('id');

        // dd($request->all());

        $client = new Client();

        $request1 = $client->request('POST', $baseUrl.'/api/buktiprekursor',
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' => [
                      "kasus_id" => $request->input('id'),
                      "id_brgbukti" => $request->input('jenisKasus'),
                      // "id_brgbukti" => 63,
                      "jumlah_barang_bukti" => $request->input('jumlah_barang_bukti'),
                      "kode_satuan_barang_bukti" => $request->input('kode_satuan_barang_bukti'),
                    ]
                ]
            );

            $return  = json_decode($request1->getBody()->getContents(), false);

            $this->form_params = array("kasus_id" => $request->input('id'),
            "id_brgbukti" => $request->input('jenisKasus'),
            // "id_brgbukti" => 63,
            "jumlah_barang_bukti" => $request->input('jumlah_barang_bukti'),
            "kode_satuan_barang_bukti" => $request->input('kode_satuan_barang_bukti'));

            $trail['audit_menu'] = 'Pemberantasan - Pendataan LKN - Barang Bukti Prekursor';
            $trail['audit_event'] = 'post';
            $trail['audit_value'] = json_encode($this->form_params);
            $trail['audit_url'] = $request->url();
            $trail['audit_ip_address'] = $request->ip();
            $trail['audit_user_agent'] = $request->userAgent();
            $trail['audit_message'] = $return->comment;
            $trail['created_at'] = date("Y-m-d H:i:s");
            $trail['created_by'] = $request->session()->get('id');

            $qtrail = $this->inputtrail($token,$trail);


        if($request1){
            $client->request('PUT', $baseUrl.'/api/kasus/'.$id,
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ]
                ]
            );
            }

            // dd(json_decode($request->getBody()->getContents(), true));

        // return redirect('pemberantasan/dir_narkotika/edit_pendataan_lkn/'.$id);
        return back()->with($id);
    }

    public function updateBrgBuktiPrekursor(Request $request){
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');
        $id = $request->input('bbId');

        // dd($request->all());

        $client = new Client();

        $request1 = $client->request('PUT', $baseUrl.'/api/buktiprekursor/'.$id,
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' => [
                      "kasus_id" => $request->input('id'),
                      "id_brgbukti" => $request->input('jenisKasus'),
                      // "id_brgbukti" => 63,
                      "jumlah_barang_bukti" => $request->input('jumlah_barang_bukti'),
                      "kode_satuan_barang_bukti" => $request->input('kode_satuan_barang_bukti'),
                    ]
                ]
            );

            $return  = json_decode($request1->getBody()->getContents(), false);

            $this->form_params = array("kasus_id" => $request->input('id'),
            "id_brgbukti" => $request->input('jenisKasus'),
            // "id_brgbukti" => 63,
            "jumlah_barang_bukti" => $request->input('jumlah_barang_bukti'),
            "kode_satuan_barang_bukti" => $request->input('kode_satuan_barang_bukti'));

            $trail['audit_menu'] = 'Pemberantasan - Pendataan LKN - Barang Bukti Prekursor';
            $trail['audit_event'] = 'put';
            $trail['audit_value'] = json_encode($this->form_params);
            $trail['audit_url'] = $request->url();
            $trail['audit_ip_address'] = $request->ip();
            $trail['audit_user_agent'] = $request->userAgent();
            $trail['audit_message'] = $return->comment;
            $trail['created_at'] = date("Y-m-d H:i:s");
            $trail['created_by'] = $request->session()->get('id');

            $qtrail = $this->inputtrail($token,$trail);


        if($request1){
            $client->request('PUT', $baseUrl.'/api/kasus/'.$id,
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ]
                ]
            );
            }

            // dd(json_decode($request->getBody()->getContents(), true));

        // return redirect('pemberantasan/dir_narkotika/edit_pendataan_lkn/'.$id);
        return back()->with($id);
    }

    public function inputBrgBuktiAdiktif(Request $request){
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');
        $id = $request->input('id');

        // dd($request->all());

        $client = new Client();

        $request1 = $client->request('POST', $baseUrl.'/api/kasusbrgbukti',
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' => [
                      "kasus_id" => $request->input('id'),
                      "id_brgbukti" => $request->input('jenisKasus'),
                      "jumlah_barang_bukti" => $request->input('jumlah_barang_bukti'),
                      "kode_satuan_barang_bukti" => $request->input('kode_satuan_barang_bukti'),
                    ]
                ]
            );

            $return  = json_decode($request1->getBody()->getContents(), false);

            $this->form_params = array("kasus_id" => $request->input('id'),
            "id_brgbukti" => $request->input('jenisKasus'),
            "jumlah_barang_bukti" => $request->input('jumlah_barang_bukti'),
            "kode_satuan_barang_bukti" => $request->input('kode_satuan_barang_bukti'));

            $trail['audit_menu'] = 'Pemberantasan - Pendataan LKN - Barang Bukti Adiktif';
            $trail['audit_event'] = 'post';
            $trail['audit_value'] = json_encode($this->form_params);
            $trail['audit_url'] = $request->url();
            $trail['audit_ip_address'] = $request->ip();
            $trail['audit_user_agent'] = $request->userAgent();
            $trail['audit_message'] = $return->comment;
            $trail['created_at'] = date("Y-m-d H:i:s");
            $trail['created_by'] = $request->session()->get('id');

            $qtrail = $this->inputtrail($token,$trail);


            if($request1){
            $client->request('PUT', $baseUrl.'/api/kasus/'.$id,
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ]
                ]
            );
            }

            // dd(json_decode($request->getBody()->getContents(), true));

        // return redirect('pemberantasan/dir_narkotika/edit_pendataan_lkn/'.$id);
        // return redirect('pemberantasan/dir_psikotropika/edit_psi_pendataan_lkn/'.$id);
        return back()->with($id);
    }

    public function updateBrgBuktiAdiktif(Request $request){
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');
        $id = $request->input('id');
        $bbid = $request->input('bbId');


        // dd($request->all());

        $client = new Client();

      $request1 = $client->request('PUT', $baseUrl.'/api/kasusbrgbukti/'.$bbid,
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' => [
                      "kasus_id" => $request->input('id'),
                      "id_brgbukti" => $request->input('jenisKasus'),
                      "jumlah_barang_bukti" => $request->input('jumlah_barang_bukti'),
                      "kode_satuan_barang_bukti" => $request->input('kode_satuan_barang_bukti'),
                    ]
                ]
            );

            $return  = json_decode($request1->getBody()->getContents(), false);

            $this->form_params = array("kasus_id" => $request->input('id'),
            "id_brgbukti" => $request->input('jenisKasus'),
            "jumlah_barang_bukti" => $request->input('jumlah_barang_bukti'),
            "kode_satuan_barang_bukti" => $request->input('kode_satuan_barang_bukti'));

            $trail['audit_menu'] = 'Pemberantasan - Pendataan LKN - Barang Bukti Adiktif';
            $trail['audit_event'] = 'put';
            $trail['audit_value'] = json_encode($this->form_params);
            $trail['audit_url'] = $request->url();
            $trail['audit_ip_address'] = $request->ip();
            $trail['audit_user_agent'] = $request->userAgent();
            $trail['audit_message'] = $return->comment;
            $trail['created_at'] = date("Y-m-d H:i:s");
            $trail['created_by'] = $request->session()->get('id');

            $qtrail = $this->inputtrail($token,$trail);


            if($request1){
            $client->request('PUT', $baseUrl.'/api/kasus/'.$id,
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ]
                ]
            );
            }

            // dd(json_decode($request->getBody()->getContents(), true));

        // return redirect('pemberantasan/dir_narkotika/edit_pendataan_lkn/'.$id);
        // return redirect('pemberantasan/dir_psikotropika/edit_psi_pendataan_lkn/'.$id);
        return back()->with($id);
    }

    public function inputBrgBuktiNonNar(Request $request){
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');
        $id = $request->input('id');

        // dd($request->all());

        $client = new Client();

        $request1 = $client->request('POST', $baseUrl.'/api/kasusbrgbukti',
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' => [
                      "kasus_id" => $request->input('id'),
                      "keterangan" => $request->input('keterangan'),
                      // "id_brgbukti" => 63,
                      "jumlah_barang_bukti" => $request->input('jumlah_barang_bukti'),
                      "kode_satuan_barang_bukti" => $request->input('kode_satuan_barang_bukti'),
                    ]
                ]
            );

            $return  = json_decode($request1->getBody()->getContents(), false);

            $this->form_params = array("kasus_id" => $request->input('id'),
            "keterangan" => $request->input('keterangan'),
            // "id_brgbukti" => 63,
            "jumlah_barang_bukti" => $request->input('jumlah_barang_bukti'),
            "kode_satuan_barang_bukti" => $request->input('kode_satuan_barang_bukti'));

            $trail['audit_menu'] = 'Pemberantasan - Pendataan LKN - Barang Bukti Non Narkotika';
            $trail['audit_event'] = 'post';
            $trail['audit_value'] = json_encode($this->form_params);
            $trail['audit_url'] = $request->url();
            $trail['audit_ip_address'] = $request->ip();
            $trail['audit_user_agent'] = $request->userAgent();
            $trail['audit_message'] = $return->comment;
            $trail['created_at'] = date("Y-m-d H:i:s");
            $trail['created_by'] = $request->session()->get('id');

            $qtrail = $this->inputtrail($token,$trail);


        if($request1){
            $client->request('PUT', $baseUrl.'/api/kasus/'.$id,
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ]
                ]
            );
            }

            // dd(json_decode($request->getBody()->getContents(), true));

        // return redirect('pemberantasan/dir_narkotika/edit_pendataan_lkn/'.$id);
        return back()->with($id);
    }

    public function updateBrgBuktiNonNar(Request $request){
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');
        $id = $request->input('id');
        $bbid = $request->input('bbId');

        // dd($request->all());

        $client = new Client();

        $request1 = $client->request('PUT', $baseUrl.'/api/kasusbrgbukti/'.$bbid,
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' => [
                      "kasus_id" => $request->input('id'),
                      "keterangan" => $request->input('keterangan'),
                      // "id_brgbukti" => 63,
                      "jumlah_barang_bukti" => $request->input('jumlah_barang_bukti'),
                      "kode_satuan_barang_bukti" => $request->input('kode_satuan_barang_bukti'),
                    ]
                ]
            );

            $return  = json_decode($request1->getBody()->getContents(), false);

            $this->form_params = array("kasus_id" => $request->input('id'),
            "keterangan" => $request->input('keterangan'),
            // "id_brgbukti" => 63,
            "jumlah_barang_bukti" => $request->input('jumlah_barang_bukti'),
            "kode_satuan_barang_bukti" => $request->input('kode_satuan_barang_bukti'));

            $trail['audit_menu'] = 'Pemberantasan - Pendataan LKN - Barang Bukti Non Narkotika';
            $trail['audit_event'] = 'put';
            $trail['audit_value'] = json_encode($this->form_params);
            $trail['audit_url'] = $request->url();
            $trail['audit_ip_address'] = $request->ip();
            $trail['audit_user_agent'] = $request->userAgent();
            $trail['audit_message'] = $return->comment;
            $trail['created_at'] = date("Y-m-d H:i:s");
            $trail['created_by'] = $request->session()->get('id');

            $qtrail = $this->inputtrail($token,$trail);


        if($request1){
            $client->request('PUT', $baseUrl.'/api/kasus/'.$id,
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ]
                ]
            );
            }

            // dd(json_decode($request->getBody()->getContents(), true));

        // return redirect('pemberantasan/dir_narkotika/edit_pendataan_lkn/'.$id);
        return back()->with($id);
    }

    public function inputBrgBuktiAset(Request $request){
      $baseUrl = URL::to($this->urlapi());
//      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');
      $id = $request->input('id');
      $nilai_aset = str_replace(",", "", $request->input('nilai_aset'));
      // dd($request->all());

      $client = new Client();

      $request1 = $client->request('POST', $baseUrl.'/api/buktinonnarkotika',
              [
                  'headers' =>
                  [
                      'Authorization' => 'Bearer '.$token
                  ],
                  'form_params' => [
                    "kasus_id" => $request->input('id'),
                    "nama_aset" => $request->input('nama_aset'),
                    // "id_brgbukti" => 63,
                    "jumlah_barang_bukti_aset" => $request->input('jumlah_barang_bukti_aset'),
                    "kode_satuan_barang_bukti_aset" => $request->input('kode_satuan_barang_bukti_aset'),
                    "nilai_aset" => $nilai_aset,
                    "keterangan" => $request->input('keterangan'),
                    "kode_isaset" => $request->input('kode_isaset'),
                    "kode_jenisaset" => $request->input('kode_jenisaset'),
                    "kode_kelompokaset" => $request->input('kode_kelompokaset')
                  ]
              ]
          );

          $return  = json_decode($request1->getBody()->getContents(), false);

          $this->form_params = array("kasus_id" => $request->input('id'),
          "nama_aset" => $request->input('nama_aset'),
          // "id_brgbukti" => 63,
          "jumlah_barang_bukti_aset" => $request->input('jumlah_barang_bukti_aset'),
          "kode_satuan_barang_bukti_aset" => $request->input('kode_satuan_barang_bukti_aset'),
          "nilai_aset" => $nilai_aset,
          "keterangan" => $request->input('keterangan'),
          "kode_isaset" => $request->input('kode_isaset'),
          "kode_jenisaset" => $request->input('kode_jenisaset'),
          "kode_kelompokaset" => $request->input('kode_kelompokaset'));

          $trail['audit_menu'] = 'Pemberantasan - Pendataan LKN - Barang Bukti Aset';
          $trail['audit_event'] = 'post';
          $trail['audit_value'] = json_encode($this->form_params);
          $trail['audit_url'] = $request->url();
          $trail['audit_ip_address'] = $request->ip();
          $trail['audit_user_agent'] = $request->userAgent();
          $trail['audit_message'] = $return->comment;
          $trail['created_at'] = date("Y-m-d H:i:s");
          $trail['created_by'] = $request->session()->get('id');

          $qtrail = $this->inputtrail($token,$trail);


      if($request1){
            $client->request('PUT', $baseUrl.'/api/kasus/'.$id,
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ]
                ]
            );
            }

          // dd(json_decode($request->getBody()->getContents(), true));

      // return redirect('pemberantasan/dir_narkotika/edit_pendataan_lkn/'.$id);
      return back()->with($id);

    }

    public function updateBrgBuktiAset(Request $request){
      $baseUrl = URL::to($this->urlapi());
//      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');
      $id = $request->input('AsetId');
      $nilai_aset = str_replace(",", "", $request->input('nilai_aset'));
      // dd($request->all());

      $client = new Client();

      $request1 = $client->request('PUT', $baseUrl.'/api/buktinonnarkotika/'.$id,
              [
                  'headers' =>
                  [
                      'Authorization' => 'Bearer '.$token
                  ],
                  'form_params' => [
                    "kasus_id" => $request->input('id'),
                    "nama_aset" => $request->input('nama_aset'),
                    // "id_brgbukti" => 63,
                    "jumlah_barang_bukti_aset" => $request->input('jumlah_barang_bukti_aset'),
                    "kode_satuan_barang_bukti_aset" => $request->input('kode_satuan_barang_bukti_aset'),
                    "nilai_aset" => $nilai_aset,
                    "keterangan" => $request->input('keterangan'),
                    "kode_isaset" => $request->input('kode_isaset'),
                    "kode_jenisaset" => $request->input('kode_jenisaset'),
                    "kode_kelompokaset" => $request->input('kode_kelompokaset')
                  ]
              ]
          );

          $return  = json_decode($request1->getBody()->getContents(), false);

          $this->form_params = array("kasus_id" => $request->input('id'),
          "nama_aset" => $request->input('nama_aset'),
          // "id_brgbukti" => 63,
          "jumlah_barang_bukti_aset" => $request->input('jumlah_barang_bukti_aset'),
          "kode_satuan_barang_bukti_aset" => $request->input('kode_satuan_barang_bukti_aset'),
          "nilai_aset" => $nilai_aset,
          "keterangan" => $request->input('keterangan'),
          "kode_isaset" => $request->input('kode_isaset'),
          "kode_jenisaset" => $request->input('kode_jenisaset'),
          "kode_kelompokaset" => $request->input('kode_kelompokaset'));

          $trail['audit_menu'] = 'Pemberantasan - Pendataan LKN - Barang Bukti Aset';
          $trail['audit_event'] = 'put';
          $trail['audit_value'] = json_encode($this->form_params);
          $trail['audit_url'] = $request->url();
          $trail['audit_ip_address'] = $request->ip();
          $trail['audit_user_agent'] = $request->userAgent();
          $trail['audit_message'] = $return->comment;
          $trail['created_at'] = date("Y-m-d H:i:s");
          $trail['created_by'] = $request->session()->get('id');

          $qtrail = $this->inputtrail($token,$trail);


      if($request1){
            $client->request('PUT', $baseUrl.'/api/kasus/'.$id,
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ]
                ]
            );
            }

          // dd(json_decode($request->getBody()->getContents(), true));

      // return redirect('pemberantasan/dir_narkotika/edit_pendataan_lkn/'.$id);
      return back()->with($id);

    }

    // public function pendataanDpo(){
//
//     public function inputTersangka(Request $request){
//         $id = $request->input('id');
//         $baseUrl = URL::to('/');
//         $token = $request->session()->get('token');

//         // dd($request->all());

//         $client = new Client();

//         $request = $client->request('POST', $baseUrl.'/api/tersangka',
//                 [
//                     'headers' =>
//                     [
//                         'Authorization' => 'Bearer '.$token
//                     ],
//                     'form_params' => [
//                         "kasus_id" => $request->input('id'),
//                         "pasal" => $request->input('pasal'),
//                         "tersangka_nama" => $request->input('tersangka_nama'),
//                         "kode_jenisidentitas" => $request->input('kode_jenisidentitas'),
//                         "no_identitas" => $request->input('no_identitas'),
//                         "tersangka_alamat" => $request->input('tersangka_alamat'),
//                         "alamatktp_idkabkota" => $request->input('alamatktp_idkabkota'),
//                         "alamatktp_kodepos" => $request->input('alamatktp_kodepos'),
//                         "alamatdomisili" => $request->input('alamatdomisili'),
//                         "alamatdomisili_idkabkota" => $request->input('alamatdomisili_idkabkota'),
//                         "alamatdomisili_kodepos" => $request->input('alamatdomisili_kodepos'),
//                         "alamatlainnya" => $request->input('alamatlainnya'),
//                         "alamatlainnya_idkabkota" => $request->input('alamatlainnya_idkabkota'),
//                         "alamatlainnya_kodepos" => $request->input('alamatlainnya_kodepos'),
//                         "kode_jenis_kelamin" => $request->input('kode_jenis_kelamin'),
//                         "tersangka_tanggal_lahir" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tersangka_tanggal_lahir')))),
//                         "tersangka_usia" => $request->input('tersangka_usia'),
//                         "kode_pendidikan_akhir" => $request->input('kode_pendidikan_akhir'),
//                         "kode_pekerjaan" => $request->input('kode_pekerjaan'),
//                         "kode_warga_negara" => $request->input('kode_warga_negara'),
//                         "kode_negara" => $request->input('kode_negara'),
//                         "kode_peran_tersangka" => $request->input('kode_peran_tersangka')
//                     ]
//                 ]
//             );

//         return redirect('pemberantasan/dir_narkotika/edit_pendataan_lkn/'.$id);
     // }

    public function pendataanKoordinasi(Request $request){
        $client = new Client();
        if ($request->input('page')) {
          $page = $request->input('page');
        } else {
          $page = 1;
        }

        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestAdvorakor = $client->request('GET', $baseUrl.'/api/advorakor?page='.$page,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
        $advorakor = json_decode($requestAdvorakor->getBody()->getContents(), true);

        $this->data['data_advorakor'] = $advorakor['data'];
        $page = $advorakor['paginate'];

        $this->data['title'] = "Pemberantasan";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['token'] = $token;


        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['path'] = $request->path();
        $this->data['page'] = $page;
        return view('pencegahan.advokasi.koordinasi.index_pendataanKoordinasi',$this->data);
    }

     public function editPendataanKoordinasi(Request $request){
        $id = $request->id;
        // $id = '68950';
        $query =  DB::table('v_berantas_kasus')->join('berantas_kasus','berantas_kasus.kasus_id','=','v_berantas_kasus.kasus_id')->where('v_berantas_kasus.kasus_id',$id)->first();

        $prefix = explode('/',$request->route()->getPrefix());
        $route = $prefix[0];
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestDataDetail= $client->request('GET', $baseUrl.'/api/advorakor/'.$id,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );

        $dataDetail = json_decode($requestDataDetail->getBody()->getContents(), true);

        if ($dataDetail['data']['anggaran_id'] != '') {
           $this->data['data_anggaran'] = $this->globalGetAnggaran($token, $dataDetail['data']['anggaran_id']);
        }

        $this->data['data_detail'] = $dataDetail;

        return view('pencegahan.advokasi.koordinasi.edit_pendataanKoordinasi',$this->data);
    }

    public function addpendataanKoordinasi(Request $request){
        $this->data['title']="Pencegahan";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        return view('pencegahan.advokasi.koordinasi.add_pendataanKoordinasi',$this->data);
    }

    public function pendataanJejaring(Request $request){
        $client = new Client();
        if ($request->input('page')) {
          $page = $request->input('page');
        } else {
          $page = 1;
        }

        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestAdvojejaring = $client->request('GET', $baseUrl.'/api/advojejaring?page='.$page,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
        $advojejaring = json_decode($requestAdvojejaring->getBody()->getContents(), true);

        $this->data['data_advojejaring'] = $advojejaring['data'];
        $page = $advojejaring['paginate'];

        $this->data['title'] = "Pemberantasan";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['token'] = $token;


        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['path'] = $request->path();
        $this->data['page'] = $page;
        return view('pencegahan.advokasi.jejaring.index_pendataanJejaring',$this->data);
    }

     public function editPendataanJejaring(Request $request){
        $id = $request->id;
        // $id = '68950';
        $query =  DB::table('v_berantas_kasus')->join('berantas_kasus','berantas_kasus.kasus_id','=','v_berantas_kasus.kasus_id')->where('v_berantas_kasus.kasus_id',$id)->first();

        $prefix = explode('/',$request->route()->getPrefix());
        $route = $prefix[0];
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestDataDetail= $client->request('GET', $baseUrl.'/api/advojejaring/'.$id,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );

        $dataDetail = json_decode($requestDataDetail->getBody()->getContents(), true);

        if ($dataDetail['data']['anggaran_id'] != '') {
           $this->data['data_anggaran'] = $this->globalGetAnggaran($token, $dataDetail['data']['anggaran_id']);
        }

        $this->data['data_detail'] = $dataDetail;

        return view('pencegahan.advokasi.jejaring.edit_pendataanJejaring',$this->data);
    }

    public function addpendataanJejaring(Request $request){
        $this->data['title']="Pencegahan";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        return view('pencegahan.advokasi.jejaring.add_pendataanJejaring',$this->data);
    }

     public function pendataanAsistensi(Request $request){
        $client = new Client();
        if ($request->input('page')) {
          $page = $request->input('page');
        } else {
          $page = 1;
        }

        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestAdvoasistensi = $client->request('GET', $baseUrl.'/api/advoasistensi?page='.$page,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
        $advoasistensi = json_decode($requestAdvoasistensi->getBody()->getContents(), true);

        $this->data['data_advoasistensi'] = $advoasistensi['data'];
        $page = $advoasistensi['paginate'];

        $this->data['title'] = "Pemberantasan";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['token'] = $token;


        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['path'] = $request->path();
        $this->data['page'] = $page;
        return view('pencegahan.advokasi.asistensi.index_pendataanAsistensi',$this->data);
    }

     public function editPendataanAsistensi(Request $request){
        $id = $request->id;
        // $id = '68950';
        $query =  DB::table('v_berantas_kasus')->join('berantas_kasus','berantas_kasus.kasus_id','=','v_berantas_kasus.kasus_id')->where('v_berantas_kasus.kasus_id',$id)->first();

        $prefix = explode('/',$request->route()->getPrefix());
        $route = $prefix[0];
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestDataDetail= $client->request('GET', $baseUrl.'/api/advoasistensi/'.$id,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );

        $dataDetail = json_decode($requestDataDetail->getBody()->getContents(), true);

        if ($dataDetail['data']['anggaran_id'] != '') {
           $this->data['data_anggaran'] = $this->globalGetAnggaran($token, $dataDetail['data']['anggaran_id']);
        }

        $this->data['data_detail'] = $dataDetail;

        return view('pencegahan.advokasi.asistensi.edit_pendataanAsistensi',$this->data);
    }

    public function addpendataanAsistensi(Request $request){
        $this->data['title']="Pencegahan";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        return view('pencegahan.advokasi.asistensi.add_pendataanAsistensi',$this->data);
    }

    public function penguatanAsistensi(Request $request){
        $client = new Client();
        if ($request->input('page')) {
          $page = $request->input('page');
        } else {
          $page = 1;
        }

        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestAdvoasistensipenguatan = $client->request('GET', $baseUrl.'/api/advoasistensipenguatan?page='.$page,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
        $advoasistensipenguatan = json_decode($requestAdvoasistensipenguatan->getBody()->getContents(), true);

        $this->data['data_advoasistensipenguatan'] = $advoasistensipenguatan['data'];
        $page = $advoasistensipenguatan['paginate'];

        $this->data['title'] = "Pemberantasan";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['token'] = $token;


        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['path'] = $request->path();
        $this->data['page'] = $page;
        return view('pencegahan.advokasi.penguatan.index_penguatanAsistensi',$this->data);
    }

     public function editpenguatanAsistensi(Request $request){
        $id = $request->id;
        // $id = '68950';
        $query =  DB::table('v_berantas_kasus')->join('berantas_kasus','berantas_kasus.kasus_id','=','v_berantas_kasus.kasus_id')->where('v_berantas_kasus.kasus_id',$id)->first();

        $prefix = explode('/',$request->route()->getPrefix());
        $route = $prefix[0];
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestDataDetail= $client->request('GET', $baseUrl.'/api/advoasistensipenguatan/'.$id,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );

        $dataDetail = json_decode($requestDataDetail->getBody()->getContents(), true);

        if ($dataDetail['data']['anggaran_id'] != '') {
           $this->data['data_anggaran'] = $this->globalGetAnggaran($token, $dataDetail['data']['anggaran_id']);
        }

        $this->data['data_detail'] = $dataDetail;

        return view('pencegahan.advokasi.penguatan.edit_penguatanAsistensi',$this->data);
    }

    public function addpenguatanAsistensi(Request $request){
        $this->data['title']="Pencegahan";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        return view('pencegahan.advokasi.penguatan.add_penguatanAsistensi',$this->data);
    }

    public function pendataanIntervensi(Request $request){
        $client = new Client();
        if ($request->input('page')) {
          $page = $request->input('page');
        } else {
          $page = 1;
        }

        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestAdvointervensi = $client->request('GET', $baseUrl.'/api/advointervensi?page='.$page,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
        $advointervensi = json_decode($requestAdvointervensi->getBody()->getContents(), true);

        $this->data['data_advointervensi'] = $advointervensi['data'];
        $page = $advointervensi['paginate'];

        $this->data['title'] = "Pemberantasan";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['token'] = $token;


        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['path'] = $request->path();
        $this->data['page'] = $page;
        return view('pencegahan.advokasi.intervensi.index_pendataanIntervensi',$this->data);
    }

     public function editpendataanIntervensi(Request $request){
        $id = $request->id;
        // $id = '68950';
        $query =  DB::table('v_berantas_kasus')->join('berantas_kasus','berantas_kasus.kasus_id','=','v_berantas_kasus.kasus_id')->where('v_berantas_kasus.kasus_id',$id)->first();

        $prefix = explode('/',$request->route()->getPrefix());
        $route = $prefix[0];
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestDataDetail= $client->request('GET', $baseUrl.'/api/advointervensi/'.$id,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );

        $dataDetail = json_decode($requestDataDetail->getBody()->getContents(), true);

        if ($dataDetail['data']['anggaran_id'] != '') {
           $this->data['data_anggaran'] = $this->globalGetAnggaran($token, $dataDetail['data']['anggaran_id']);
        }

        $this->data['data_detail'] = $dataDetail;

        return view('pencegahan.advokasi.intervensi.edit_pendataanIntervensi',$this->data);
    }

    public function addpendataanIntervensi(Request $request){
        $this->data['title']="Pencegahan";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        return view('pencegahan.advokasi.intervensi.add_pendataanIntervensi',$this->data);
    }

    public function pendataanSupervisi(Request $request){
        $client = new Client();
        if ($request->input('page')) {
          $page = $request->input('page');
        } else {
          $page = 1;
        }

        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestAdvosupervisi = $client->request('GET', $baseUrl.'/api/advosupervisi?page='.$page,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
        $advosupervisi = json_decode($requestAdvosupervisi->getBody()->getContents(), true);

        $this->data['data_advosupervisi'] = $advosupervisi['data'];
        $page = $advosupervisi['paginate'];

        $this->data['title'] = "Pemberantasan";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['token'] = $token;


        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['path'] = $request->path();
        $this->data['page'] = $page;
        return view('pencegahan.advokasi.supervisi.index_pendataanSupervisi',$this->data);
    }

     public function editpendataanSupervisi(Request $request){
        $id = $request->id;
        // $id = '68950';
        $query =  DB::table('v_berantas_kasus')->join('berantas_kasus','berantas_kasus.kasus_id','=','v_berantas_kasus.kasus_id')->where('v_berantas_kasus.kasus_id',$id)->first();

        $prefix = explode('/',$request->route()->getPrefix());
        $route = $prefix[0];
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestDataDetail= $client->request('GET', $baseUrl.'/api/advosupervisi/'.$id,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );

        $dataDetail = json_decode($requestDataDetail->getBody()->getContents(), true);

        if ($dataDetail['data']['anggaran_id'] != '') {
           $this->data['data_anggaran'] = $this->globalGetAnggaran($token, $dataDetail['data']['anggaran_id']);
        }

        $this->data['data_detail'] = $dataDetail;

        return view('pencegahan.advokasi.supervisi.edit_pendataanSupervisi',$this->data);
    }

    public function addpendataanSupervisi(Request $request){
        $this->data['title']="Pencegahan";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        return view('pencegahan.advokasi.supervisi.add_pendataanSupervisi',$this->data);
    }

    public function pendataanMonitoring(Request $request){
        $client = new Client();
        if ($request->input('page')) {
          $page = $request->input('page');
        } else {
          $page = 1;
        }

        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestAdvomonev = $client->request('GET', $baseUrl.'/api/advomonev?page='.$page,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
        $advomonev = json_decode($requestAdvomonev->getBody()->getContents(), true);

        $this->data['data_advomonev'] = $advomonev['data'];
        $page = $advomonev['paginate'];
        $this->data['title'] = "Pemberantasan";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['token'] = $token;


        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['path'] = $request->path();
        $this->data['page'] = $page;
        return view('pencegahan.advokasi.monitoring.index_pendataanMonitoring',$this->data);
    }

     public function editpendataanMonitoring(Request $request){
        $id = $request->id;
        // $id = '68950';
        $query =  DB::table('v_berantas_kasus')->join('berantas_kasus','berantas_kasus.kasus_id','=','v_berantas_kasus.kasus_id')->where('v_berantas_kasus.kasus_id',$id)->first();

        $prefix = explode('/',$request->route()->getPrefix());
        $route = $prefix[0];
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestDataDetail= $client->request('GET', $baseUrl.'/api/advosupervisi/'.$id,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );

        $dataDetail = json_decode($requestDataDetail->getBody()->getContents(), true);

        if ($dataDetail['data']['anggaran_id'] != '') {
           $this->data['data_anggaran'] = $this->globalGetAnggaran($token, $dataDetail['data']['anggaran_id']);
        }

        $this->data['data_detail'] = $dataDetail;

        return view('pencegahan.advokasi.monitoring.edit_pendataanMonitoring',$this->data);
    }

    public function addpendataanMonitoring(Request $request){
        $this->data['title']="Pencegahan";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        return view('pencegahan.advokasi.monitoring.add_pendataanMonitoring',$this->data);
    }

    public function pendataanBimbingan(Request $request){
        $client = new Client();
        if ($request->input('page')) {
          $page = $request->input('page');
        } else {
          $page = 1;
        }

        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestAdvobimtek = $client->request('GET', $baseUrl.'/api/advobimtek?page='.$page,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
        $advobimtek = json_decode($requestAdvobimtek->getBody()->getContents(), true);

        $this->data['data_advobimtek'] = $advobimtek['data'];
        $page = $advobimtek['paginate'];
        $this->data['title'] = "Pemberantasan";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['token'] = $token;


        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['path'] = $request->path();
        $this->data['page'] = $page;
        return view('pencegahan.advokasi.Bimbingan.index_pendataanBimbingan',$this->data);
    }

     public function editpendataanBimbingan(Request $request){
        $id = $request->id;
        // $id = '68950';
        $query =  DB::table('v_berantas_kasus')->join('berantas_kasus','berantas_kasus.kasus_id','=','v_berantas_kasus.kasus_id')->where('v_berantas_kasus.kasus_id',$id)->first();

        $prefix = explode('/',$request->route()->getPrefix());
        $route = $prefix[0];
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestDataDetail= $client->request('GET', $baseUrl.'/api/advobimtek/'.$id,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );

        $dataDetail = json_decode($requestDataDetail->getBody()->getContents(), true);

        if ($dataDetail['data']['anggaran_id'] != '') {
           $this->data['data_anggaran'] = $this->globalGetAnggaran($token, $dataDetail['data']['anggaran_id']);
        }

        $this->data['data_detail'] = $dataDetail;

        return view('pencegahan.advokasi.bimbingan.edit_pendataanBimbingan',$this->data);
    }

    public function addpendataanBimbingan(Request $request){
        $this->data['title']="Pencegahan";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        return view('pencegahan.advokasi.bimbingan.add_pendataanBimbingan',$this->data);
    }

    public function pendataanSosialisasi(Request $request){
        $client = new Client();
        if ($request->input('page')) {
          $page = $request->input('page');
        } else {
          $page = 1;
        }

        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestAdvososialisasi = $client->request('GET', $baseUrl.'/api/disemsosialisasi?page='.$page,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
        $disemsosialisasi = json_decode($requestAdvososialisasi->getBody()->getContents(), true);

        $this->data['data_disemsosialisasi'] = $disemsosialisasi['data'];

        $page = $disemsosialisasi['paginate'];
        $this->data['title'] = "Pemberantasan";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['token'] = $token;


        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['path'] = $request->path();

        $this->data['page'] = $page;
        return view('pencegahan.advokasi.sosialisasi.index_pendataanSosialisasi',$this->data);
    }

     public function editpendataanSosialisasi(Request $request){
        $id = $request->id;
        // $id = '68950';
        $query =  DB::table('v_berantas_kasus')->join('berantas_kasus','berantas_kasus.kasus_id','=','v_berantas_kasus.kasus_id')->where('v_berantas_kasus.kasus_id',$id)->first();

        $prefix = explode('/',$request->route()->getPrefix());
        $route = $prefix[0];
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestDataDetail= $client->request('GET', $baseUrl.'/api/disemsosialisasi/'.$id,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );

        $dataDetail = json_decode($requestDataDetail->getBody()->getContents(), true);

        if ($dataDetail['data']['anggaran_id'] != '') {
           $this->data['data_anggaran'] = $this->globalGetAnggaran($token, $dataDetail['data']['anggaran_id']);
        }

        $this->data['data_detail'] = $dataDetail;

        return view('pencegahan.advokasi.sosialisasi.edit_pendataanSosialisasi',$this->data);
    }

    public function addpendataanSosialisasi(Request $request){
        $this->data['title']="Pencegahan";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        return view('pencegahan.advokasi.sosialisasi.add_pendataanSosialisasi',$this->data);
    }

    public function pendataanOnline(Request $request){
        $client = new Client();
        if ($request->input('page')) {
          $page = $request->input('page');
        } else {
          $page = 1;
        }

        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestDisemonline = $client->request('GET', $baseUrl.'/api/disemonline?page='.$page,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
        $disemonline = json_decode($requestDisemonline->getBody()->getContents(), true);

        $this->data['data_disemonline'] = $disemonline['data'];

        $page = $disemonline['paginate'];
        $this->data['title'] = "Pencegahan";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['token'] = $token;


        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['path'] = $request->path();
        $this->data['page'] = $page;
        return view('pencegahan.diseminasi.online.index_pendataanOnline',$this->data);
    }

     public function editpendataanOnline(Request $request){
        $id = $request->id;
        // $id = '68950';
        $query =  DB::table('v_berantas_kasus')->join('berantas_kasus','berantas_kasus.kasus_id','=','v_berantas_kasus.kasus_id')->where('v_berantas_kasus.kasus_id',$id)->first();

        $prefix = explode('/',$request->route()->getPrefix());
        $route = $prefix[0];
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestDataDetail= $client->request('GET', $baseUrl.'/api/disemonline/'.$id,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );

        $dataDetail = json_decode($requestDataDetail->getBody()->getContents(), true);

        if ($dataDetail['data']['anggaran_id'] != '') {
           $this->data['data_anggaran'] = $this->globalGetAnggaran($token, $dataDetail['data']['anggaran_id']);
        }

        $this->data['data_detail'] = $dataDetail;

        return view('pencegahan.diseminasi.online.edit_pendataanOnline',$this->data);
    }

    public function addpendataanOnline(Request $request){
        $this->data['title']="Pencegahan";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        return view('pencegahan.diseminasi.online.add_pendataanOnline',$this->data);
    }

    public function pendataanPenyiaran(Request $request){
        $client = new Client();
        if ($request->input('page')) {
          $page = $request->input('page');
        } else {
          $page = 1;
        }

        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestDisempenyiaran = $client->request('GET', $baseUrl.'/api/disempenyiaran?page='.$page,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
        $disemonline = json_decode($requestDisempenyiaran->getBody()->getContents(), true);

        $this->data['data_disempenyiaran'] = $disemonline['data'];

        $page = $disemonline['paginate'];
        $this->data['title'] = "Pencegahan";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['token'] = $token;


        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['path'] = $request->path();
        $this->data['page'] = $page;

        return view('pencegahan.diseminasi.penyiaran.index_pendataanPenyiaran',$this->data);
    }

     public function editpendataanPenyiaran(Request $request){
        $id = $request->id;
        // $id = '68950';
        $query =  DB::table('v_berantas_kasus')->join('berantas_kasus','berantas_kasus.kasus_id','=','v_berantas_kasus.kasus_id')->where('v_berantas_kasus.kasus_id',$id)->first();

        $prefix = explode('/',$request->route()->getPrefix());
        $route = $prefix[0];
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestDataDetail= $client->request('GET', $baseUrl.'/api/disempenyiaran/'.$id,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );

        $dataDetail = json_decode($requestDataDetail->getBody()->getContents(), true);

        if ($dataDetail['data']['anggaran_id'] != '') {
           $this->data['data_anggaran'] = $this->globalGetAnggaran($token, $dataDetail['data']['anggaran_id']);
        }

        $this->data['data_detail'] = $dataDetail;

        return view('pencegahan.diseminasi.penyiaran.edit_pendataanPenyiaran',$this->data);
    }

    public function addpendataanPenyiaran(Request $request){
        $this->data['title']="Pencegahan";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        return view('pencegahan.diseminasi.penyiaran.add_pendataanPenyiaran',$this->data);
    }

    public function pendataanCetak(Request $request){
        $client = new Client();
        if ($request->input('page')) {
          $page = $request->input('page');
        } else {
          $page = 1;
        }

        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestDisemcetak = $client->request('GET', $baseUrl.'/api/disemcetak?page='.$page,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
        $disemcetak = json_decode($requestDisemcetak->getBody()->getContents(), true);

        $this->data['data_disemcetak'] = $disemcetak['data'];

        $page = $disemcetak['paginate'];
        $this->data['title'] = "Pencegahan";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['token'] = $token;


        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['path'] = $request->path();
        $this->data['page'] = $page;
        return view('pencegahan.diseminasi.cetak.index_pendataanCetak',$this->data);
    }

     public function editpendataanCetak(Request $request){
        $id = $request->id;
        // $id = '68950';
        $query =  DB::table('v_berantas_kasus')->join('berantas_kasus','berantas_kasus.kasus_id','=','v_berantas_kasus.kasus_id')->where('v_berantas_kasus.kasus_id',$id)->first();

        $prefix = explode('/',$request->route()->getPrefix());
        $route = $prefix[0];
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestDataDetail= $client->request('GET', $baseUrl.'/api/disemcetak/'.$id,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );

        $dataDetail = json_decode($requestDataDetail->getBody()->getContents(), true);

        if ($dataDetail['data']['anggaran_id'] != '') {
           $this->data['data_anggaran'] = $this->globalGetAnggaran($token, $dataDetail['data']['anggaran_id']);
        }

        $this->data['data_detail'] = $dataDetail;

        return view('pencegahan.diseminasi.cetak.edit_pendataanCetak',$this->data);
    }

    public function addpendataanCetak(Request $request){
        $this->data['title']="Pencegahan";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        return view('pencegahan.diseminasi.cetak.add_pendataanCetak',$this->data);
    }

    public function pendataanKonvensional(Request $request){
        $client = new Client();
        if ($request->input('page')) {
          $page = $request->input('page');
        } else {
          $page = 1;
        }

        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestDisemkonven = $client->request('GET', $baseUrl.'/api/disemkonven?page='.$page,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
        $disemkonven = json_decode($requestDisemkonven->getBody()->getContents(), true);

        $this->data['data_disemkonven'] = $disemkonven['data'];

        $page = $disemkonven['paginate'];
        $this->data['title'] = "Pencegahan";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['token'] = $token;


        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['path'] = $request->path();
        $this->data['page'] = $page;
        return view('pencegahan.diseminasi.konvensional.index_pendataanKonvensional',$this->data);
    }

     public function editpendataanKonvensional(Request $request){
        $id = $request->id;
        // $id = '68950';
        $query =  DB::table('v_berantas_kasus')->join('berantas_kasus','berantas_kasus.kasus_id','=','v_berantas_kasus.kasus_id')->where('v_berantas_kasus.kasus_id',$id)->first();

        $prefix = explode('/',$request->route()->getPrefix());
        $route = $prefix[0];
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestDataDetail= $client->request('GET', $baseUrl.'/api/disemkonven/'.$id,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );

        $dataDetail = json_decode($requestDataDetail->getBody()->getContents(), true);

        if ($dataDetail['data']['anggaran_id'] != '') {
           $this->data['data_anggaran'] = $this->globalGetAnggaran($token, $dataDetail['data']['anggaran_id']);
        }

        $this->data['data_detail'] = $dataDetail;

        return view('pencegahan.diseminasi.konvensional.edit_pendataanKonvensional',$this->data);
    }

    public function addpendataanKonvensional(Request $request){
        $this->data['title']="Pencegahan";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        return view('pencegahan.diseminasi.konvensional.add_pendataanKonvensional',$this->data);
    }

    public function pendataanVideotron(Request $request){
        $client = new Client();
        if ($request->input('page')) {
          $page = $request->input('page');
        } else {
          $page = 1;
        }

        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestDisemvideotron = $client->request('GET', $baseUrl.'/api/disemvideotron?page='.$page,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
        $disemvideotron = json_decode($requestDisemvideotron->getBody()->getContents(), true);

        $this->data['data_disemvideotron'] = $disemvideotron['data'];
        $page = $disemvideotron['paginate'];
        $this->data['title'] = "Pencegahan";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['token'] = $token;


        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['path'] = $request->path();
        $this->data['page'] = $page;
        return view('pencegahan.diseminasi.videotron.index_pendataanVideotron',$this->data);
    }

     public function editpendataanVideotron(Request $request){
        $id = $request->id;
        // $id = '68950';
        $query =  DB::table('v_berantas_kasus')->join('berantas_kasus','berantas_kasus.kasus_id','=','v_berantas_kasus.kasus_id')->where('v_berantas_kasus.kasus_id',$id)->first();

        $prefix = explode('/',$request->route()->getPrefix());
        $route = $prefix[0];
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestDataDetail= $client->request('GET', $baseUrl.'/api/disemvideotron/'.$id,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );

        $dataDetail = json_decode($requestDataDetail->getBody()->getContents(), true);

        if ($dataDetail['data']['anggaran_id'] != '') {
           $this->data['data_anggaran'] = $this->globalGetAnggaran($token, $dataDetail['data']['anggaran_id']);
        }

        $this->data['data_detail'] = $dataDetail;

        return view('pencegahan.diseminasi.videotron.edit_pendataanVideotron',$this->data);
    }

    public function addpendataanVideotron(Request $request){
        $this->data['title']="Pencegahan";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        return view('pencegahan.diseminasi.videotron.add_pendataanVideotron',$this->data);
    }
    public function altdevLahanGanja(Request $request){
        $kondisi = '';
        if($request->limit) {
          $this->limit = $request->limit;
        } else {
          $this->limit = config('app.limit');
        }
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        if($request->isMethod('post')){
          $post = $request->except(['_token','tgl_from','tgl_to']);
          $tipe = $request->tipe;
          $tgl_from = $request->tgl_from;
          $tgl_from = $request->tgl_from;
          $tgl_to = $request->tgl_to;
          if($tipe){
            $kondisi .= '&tipe='.$tipe;
            $this->selected['tipe'] = $tipe;
          }
          if($tipe == 'periode'){
            if($tgl_from){
              $date = date('Y-m-d',strtotime(str_replace('/', '-', $tgl_from)));
              $kondisi .= '&tgl_from='.$date;
              $this->selected['tgl_from'] = $tgl_from;
            }else if(!$tgl_from){
                $kondisi .='';
            }
            if($tgl_to){
              $date = date('Y-m-d',strtotime(str_replace('/', '-', $tgl_to)));
              $kondisi .= '&tgl_to='.$date;
              $this->selected['tgl_to'] = $tgl_to;
            }else if(!$tgl_to){
              $kondisi .='';
            }
          }elseif($tipe == 'instansi'){
            $kondisi .= '&instansi='.$request->kata_kunci;
            $this->selected['instansi'] = $request->kata_kunci;
          }else {
            $kondisi .= '&'.$tipe.'='.$post[$tipe];
            $this->selected[$tipe] = $post[$tipe];
          }
          if($request->order){
            $kondisi .= '&order='.$request->order;
          }elseif(!$request->order){
            $kondisi .= '&order=desc';
          }
          $this->selected['order'] = $request->order;
          $this->selected['limit'] = $request->limit;
          $this->data['filter'] = $this->selected;
        }else{
          $get = $request->except(['page','tgl_from','tgl_to','limit']);
          $tipe = $request->tipe;
          if(count($get)>0){
            if($tipe){
              $this->selected['tipe']  = $tipe;
            }
            foreach ($get as $key => $value) {
              $kondisi .= "&".$key.'='.$value;
              if($value == 'periode'){
                if($request->tgl_from){
                    $this->selected['tgl_from'] = date('d/m/Y',strtotime($request->tgl_from));
                    $kondisi .= "&tgl_from".'='.$request->tgl_from;
                }
                if($request->tgl_to){
                  $this->selected['tgl_to'] = date('d/m/Y',strtotime($request->tgl_to));
                  $kondisi .= "&tgl_to".'='.$request->tgl_to;
                }
              }else {
                $this->selected[$key] = $value;
              }
            }

            $this->selected['order'] = $request->order;
            $this->selected['limit'] = $request->limit;
            $this->data['filter'] = $this->selected;
          }
        }
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
        $datas = execute_api_json('api/altdevlahan?'.$limit.'&'.$offset.$kondisi,'get');

        if($datas->code == 200 && $datas->status != 'error'){
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;
        }else{
            $this->data['data'] = [];
            $total_item = 0;
        }
        $filtering = false;
        if($kondisi){
          $filter = '&'.$limit.$kondisi;
          $filtering = true;
          $this->data['kondisi'] = '?'.$offset.$kondisi.'&'.$limit;
        }else{
          $filter = '/';
          $filtering = false;
          $this->data['kondisi'] = $current_page;
        }
        $this->data['route'] = $request->route()->getName();
        $this->data['title'] = "Alih Fungsi Lahan Ganja";
        $penyelenggara = execute_api('api/lookup/jenis_penyelenggara_kegiatan_alihfungsi_lahan','get');
        if($penyelenggara['code'] == 200 && $penyelenggara['status'] != 'error') {
            $this->data['penyelenggara'] = $penyelenggara['data'];
        }else{
            $this->data['penyelenggara'] = [];
        }
        $this->data['delete_route'] = 'delete_altdev_lahan_ganja';
        $datas = execute_api('api/getkomoditi','get');
        if(($datas['code'] == 200) && ($datas['code'] != 'error')){
            $this->data['lahan_komoditi'] = $datas['data'];
        }else{
            $this->data['lahan_komoditi'] = [];
        }
        $this->data['path'] = $request->path();
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;
        $this->data['breadcrumps'] = breadcrump_litbang($request->route()->getName());
        $this->data['token'] = $request->session()->get('token');
        $this->data['breadcrumps'] = breadcrumps_dir_alternative($request->route()->getName());
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
        return view('pemberdayaan.alternative_development.altdev_lahanganja.index_altdevLahanganja',$this->data);
    }

     public function editaltdevLahanGanja(Request $request){
        $id = $request->id;
        $datas = execute_api_json('api/lookup/jenis_penyelenggara_kegiatan_alihfungsi_lahan','get');
        if(($datas->code == 200) && ($datas->code != 'error')){
            $this->data['penyelenggara'] = $datas->data;
        }else{
            $this->data['penyelenggara'] = [];
        }
        $data_request = execute_api_json('api/altdevlahan/'.$id ,'GET');
        if(($data_request->code == 200) && ($data_request->status != 'error')){
            $this->data['data'] = $data_request->data;
        }else{
            $this->data['data'] = [];
        }
        $datas = execute_api('api/getkomoditi','get');
        if(($datas['code'] == 200) && ($datas['code'] != 'error')){
            $this->data['lahan_komoditi'] = $datas['data'];
        }else{
            $this->data['lahan_komoditi'] = [];
        }
        $this->data['breadcrumps'] = breadcrumps_dir_alternative($request->route()->getName());
        return view('pemberdayaan.alternative_development.altdev_lahanganja.edit_altdevLahanganja',$this->data);
    }

    public function addaltdevLahanGanja(Request $request){
      if($request->isMethod('post')){
        $this->form_params = $request->except(['_token','lokasi_lahan','kodepenyelenggara','kodekomoditi']);

        if($request->tgl_kegiatan){
          $date = str_replace('/', '-', $request->tgl_kegiatan);
          $this->form_params['tgl_kegiatan'] = date('Y-m-d',strtotime($date));
        }
        if(count($request->kodepenyelenggara)>0){
          $this->form_params['meta_kode_penyelenggara'] = json_encode($request->kodepenyelenggara);
        }
        if(count($request->kodekomoditi)>0){
          $this->form_params['meta_kode_komoditi'] = json_encode($request->kodekomoditi);
        }
        $lokasi_lahan = $request->lokasi_lahan;
        $json_lokasi_lahan = "";
        $array_lokasi_lahan = [];
        if(count($lokasi_lahan) > 0 ){
          for($i = 0 ; $i < count($lokasi_lahan) ; $i++){
            if( $lokasi_lahan[$i]['lokasilahan_idkabkota'] || $lokasi_lahan[$i]['luas_lahan']|| $lokasi_lahan[$i]['kodestatustanah'] ){
              $array_lokasi_lahan[] = array('lokasilahan_idkabkota'=> $lokasi_lahan[$i]['lokasilahan_idkabkota'] , 'luas_lahan' => str_replace(',', '', $lokasi_lahan[$i]['luas_lahan']),'kodestatustanah'=>$lokasi_lahan[$i]['kodestatustanah']);
            }
          }
        }
        if(count($array_lokasi_lahan)>0){
          $json_lokasi_lahan = json_encode($array_lokasi_lahan);
        }else{
          $json_lokasi_lahan = "";
        }
        $this->form_params['meta_lokasi_lahan'] =  $json_lokasi_lahan;
        $this->form_params['lokasi'] = $request->input('lokasi');
        $this->form_params['keterangan_lainnya'] = $request->input('keterangan_lainnya');

        //generate image base64
        if($request->hasFile('foto1')){
            $filenameWithExt = $request->file('foto1')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('foto1')->getClientOriginalExtension();
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            $path = $request->file('foto1')->storeAs('Pemberdayaan/DirektoratAlternativeDevelopment/alihFungsiLahanGanja', $fileNameToStore);
            $image = public_path('upload/Pemberdayaan/DirektoratAlternativeDevelopment/alihFungsiLahanGanja/'.$fileNameToStore);
            $data = file_get_contents($image);
            $image1 = base64_encode($data);
            Storage::delete('Pemberdayaan/DirektoratAlternativeDevelopment/alihFungsiLahanGanja/'.$fileNameToStore);
        }else{
          $image1 = null;
        }
        $this->form_params['foto1'] = $image1;

        if($request->hasFile('foto2')){
            $filenameWithExt = $request->file('foto2')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('foto2')->getClientOriginalExtension();
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            $path = $request->file('foto2')->storeAs('Pemberdayaan/DirektoratAlternativeDevelopment', $fileNameToStore);
            $image = public_path('upload/Pemberdayaan/DirektoratAlternativeDevelopment/'.$fileNameToStore);
            $data = file_get_contents($image);
            $image2 = base64_encode($data);
            Storage::delete('Pemberdayaan/DirektoratAlternativeDevelopment/'.$fileNameToStore);
        }else{
          $image2 = null;
        }
        $this->form_params['foto2'] = $image2;

        if($request->hasFile('foto3')){
            $filenameWithExt = $request->file('foto3')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('foto3')->getClientOriginalExtension();
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            $path = $request->file('foto3')->storeAs('Pemberdayaan/DirektoratAlternativeDevelopment', $fileNameToStore);
            $image = public_path('upload/Pemberdayaan/DirektoratAlternativeDevelopment/'.$fileNameToStore);
            $data = file_get_contents($image);
            $image3 = base64_encode($data);
            Storage::delete('Pemberdayaan/DirektoratAlternativeDevelopment/'.$fileNameToStore);
        }else{
          $image3 = null;
        }
        $this->form_params['foto3'] = $image3;

        $data_request = execute_api_json('api/altdevlahan','POST',$this->form_params);

        $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Alternative Development - Alih Fungsi Lahan Ganja';
        $trail['audit_event'] = 'post';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();

        $trail['audit_message'] = $data_request->comment;
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');
        $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

        if(($data_request->code == 200)&& ($data_request->status != "error") ){
            $id = $data_request->data->eventID;
            $this->kelengkapan_altdevlahanganja($id);
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Alih Fungsi Lahan Ganja Berhasil Ditambahkan';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Alih Fungsi Lahan Ganja Gagal Ditambahkan';
        }
        return redirect(route('altdev_lahan_ganja'))->with('status', $this->messages);
      }else{
        $datas = execute_api_json('api/lookup/jenis_penyelenggara_kegiatan_alihfungsi_lahan','get');
        if(($datas->code == 200) && ($datas->code != 'error')){
            $this->data['penyelenggara'] = $datas->data;
        }else{
            $this->data['penyelenggara'] = [];
        }
        $datas = execute_api('api/getkomoditi','get');
        if(($datas['code'] == 200) && ($datas['code'] != 'error')){
            $this->data['lahan_komoditi'] = $datas['data'];
        }else{
            $this->data['lahan_komoditi'] = [];
        }
        $this->data['breadcrumps'] = breadcrumps_dir_alternative($request->route()->getName());
        return view('pemberdayaan.alternative_development.altdev_lahanganja.add_altdevLahanganja',$this->data);
      }
    }

    public function updateAltdevLahanGanja(Request $request){
        $id = $request->id;
        $this->form_params = $request->except(['id','_token','lokasi_lahan','kodepenyelenggara','kodekomoditi', 'foto1_old', 'foto2_old', 'foto3_old']);
        if($request->tgl_kegiatan){
          $date = str_replace('/', '-', $request->tgl_kegiatan);
          $this->form_params['tgl_kegiatan'] = date('Y-m-d',strtotime($date));
        }
        if(count($request->kodepenyelenggara)>0){
          $this->form_params['meta_kode_penyelenggara'] = json_encode($request->kodepenyelenggara);
        }
        if(count($request->kodekomoditi)>0){
          $this->form_params['meta_kode_komoditi'] = json_encode($request->kodekomoditi);
        }
        $lokasi_lahan = $request->lokasi_lahan;
        $json_lokasi_lahan = "";
        $array_lokasi_lahan = [];
        if(count($lokasi_lahan) > 0 ){
          for($i = 0 ; $i < count($lokasi_lahan) ; $i++){
            if( $lokasi_lahan[$i]['lokasilahan_idkabkota'] || $lokasi_lahan[$i]['luas_lahan']|| $lokasi_lahan[$i]['kodestatustanah'] ){
              $array_lokasi_lahan[] = array('lokasilahan_idkabkota'=> $lokasi_lahan[$i]['lokasilahan_idkabkota'] , 'luas_lahan' => str_replace(',', '', $lokasi_lahan[$i]['luas_lahan']),'kodestatustanah'=>$lokasi_lahan[$i]['kodestatustanah']);
            }
          }
        }

        if(count($array_lokasi_lahan)>0){
          $json_lokasi_lahan = json_encode($array_lokasi_lahan);
        }else{
          $json_lokasi_lahan = "";
        }
        $this->form_params['meta_lokasi_lahan'] =  $json_lokasi_lahan;
        $this->form_params['lokasi'] = $request->input('lokasi');
        $this->form_params['keterangan_lainnya'] = $request->input('keterangan_lainnya');

        //generate image base64
        if($request->hasFile('foto1')){
            $filenameWithExt = $request->file('foto1')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('foto1')->getClientOriginalExtension();
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            $path = $request->file('foto1')->storeAs('Pemberdayaan/DirektoratAlternativeDevelopment/alihFungsiLahanGanja', $fileNameToStore);
            $image = public_path('upload/Pemberdayaan/DirektoratAlternativeDevelopment/alihFungsiLahanGanja/'.$fileNameToStore);
            $data = file_get_contents($image);
            $image1 = base64_encode($data);
            Storage::delete('Pemberdayaan/DirektoratAlternativeDevelopment/alihFungsiLahanGanja/'.$fileNameToStore);
        }else{
          $image1 = $request->input('foto1_old');
        }
        $this->form_params['foto1'] = $image1;

        if($request->hasFile('foto2')){
            $filenameWithExt = $request->file('foto2')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('foto2')->getClientOriginalExtension();
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            $path = $request->file('foto2')->storeAs('Pemberdayaan/DirektoratAlternativeDevelopment', $fileNameToStore);
            $image = public_path('upload/Pemberdayaan/DirektoratAlternativeDevelopment/'.$fileNameToStore);
            $data = file_get_contents($image);
            $image2 = base64_encode($data);
            Storage::delete('Pemberdayaan/DirektoratAlternativeDevelopment/'.$fileNameToStore);
        }else{
          $image2 = $request->input('foto2_old');
        }
        $this->form_params['foto2'] = $image2;

        if($request->hasFile('foto3')){
            $filenameWithExt = $request->file('foto3')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('foto3')->getClientOriginalExtension();
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            $path = $request->file('foto3')->storeAs('Pemberdayaan/DirektoratAlternativeDevelopment', $fileNameToStore);
            $image = public_path('upload/Pemberdayaan/DirektoratAlternativeDevelopment/'.$fileNameToStore);
            $data = file_get_contents($image);
            $image3 = base64_encode($data);
            Storage::delete('Pemberdayaan/DirektoratAlternativeDevelopment/'.$fileNameToStore);
        }else{
          $image3 = $request->input('foto3_old');
        }
        $this->form_params['foto3'] = $image3;

        $data_request = execute_api_json('api/altdevlahan/'.$id,'PUT',$this->form_params);

        $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Alternative Development - Alih Fungsi Lahan Ganja';
        $trail['audit_event'] = 'put';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $data_request->comment;
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

        if(($data_request->code == 200)&& ($data_request->status != "error") ){
            $this->kelengkapan_altdevlahanganja($id);
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Alih Fungsi Lahan Ganja Berhasil Diperbarui';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Alih Fungsi Lahan Ganja Gagal Diperbarui';
        }
        return back()->with('status', $this->messages);
    }
    public function deleteAltdevLahanGanja(Request $request){
          $id = $request->input('id');
          if($id){
            if ($request->ajax()) {
                $id = $request->id;
                $data_request = execute_api('api/altdevlahan/'.$id,'DELETE');
                $this->form_params['delete_id'] = $id;
                $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Alternative Development - Alih Fungsi Lahan Ganja';
                $trail['audit_event'] = 'delete';
                $trail['audit_value'] = json_encode($this->form_params);
                $trail['audit_url'] = $request->url();
                $trail['audit_ip_address'] = $request->ip();
                $trail['audit_user_agent'] = $request->userAgent();
                $trail['audit_message'] = $data_request['comment'];
                $trail['created_at'] = date("Y-m-d H:i:s");
                $trail['created_by'] = $request->session()->get('id');

                $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

                return $data_request;
            }
          }else{
            $data_request = ['status'=>'error','message'=>'Data Gagal Dihapus'];
            return $data_request;
          }
    }

    public function altdevAlihprofesi(Request $request){
       $kondisi = '';
        if($request->limit) {
          $this->limit = $request->limit;
        } else {
          $this->limit = config('app.limit');
        }
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        if($request->isMethod('post')){
          $post = $request->except(['_token','tgl_from','tgl_to']);
          $tipe = $request->tipe;
          $tgl_from = $request->tgl_from;
          $tgl_from = $request->tgl_from;
          $tgl_to = $request->tgl_to;
          if($tipe){
            $kondisi .= '&tipe='.$tipe;
            $this->selected['tipe'] = $tipe;
          }
          if($tipe == 'periode'){
            if($tgl_from){
              $date = date('Y-m-d',strtotime(str_replace('/', '-', $tgl_from)));
              $kondisi .= '&tgl_from='.$date;
              $this->selected['tgl_from'] = $tgl_from;
            }else if(!$tgl_from){
                $kondisi .='';
            }
            if($tgl_to){
              $date = date('Y-m-d',strtotime(str_replace('/', '-', $tgl_to)));
              $kondisi .= '&tgl_to='.$date;
              $this->selected['tgl_to'] = $tgl_to;
            }else if(!$tgl_to){
              $kondisi .='';
            }
          }elseif($tipe == 'lokasi'){
            $kondisi .= '&lokasi='.$request->kata_kunci;
            $this->selected['lokasi'] = $request->kata_kunci;
          }else {
            if(isset($post[$tipe])){
              $kondisi .= '&'.$tipe.'='.$post[$tipe];
              $this->selected[$tipe] = $post[$tipe];
            }
          }
          if($request->order){
            $kondisi .= '&order='.$request->order;
          }elseif(!$request->order){
            $kondisi .= '&order=desc';
          }
          $this->selected['order'] = $request->order;
          $this->selected['limit'] = $request->limit;
          $this->data['filter'] = $this->selected;
        }else{
          $get = $request->except(['page','tgl_from','tgl_to','limit']);
          $tipe = $request->tipe;
          if(count($get)>0){
            if($tipe){
              $this->selected['tipe']  = $tipe;
            }
            foreach ($get as $key => $value) {
              $kondisi .= "&".$key.'='.$value;
              if($value == 'periode'){
                if($request->tgl_from){
                    $this->selected['tgl_from'] = date('d/m/Y',strtotime($request->tgl_from));
                    $kondisi .= "&tgl_from".'='.$request->tgl_from;
                }
                if($request->tgl_to){
                  $this->selected['tgl_to'] = date('d/m/Y',strtotime($request->tgl_to));
                  $kondisi .= "&tgl_to".'='.$request->tgl_to;
                }
              }else {
                $this->selected[$key] = $value;
              }
            }

            $this->selected['order'] = $request->order;
            $this->selected['limit'] = $request->limit;
            $this->data['filter'] = $this->selected;
          }
        }
        $this->data['title'] = "Alih Jenis Profesi/Usaha";
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
        $datas = execute_api_json('api/altdevprofesi?'.$limit.'&'.$offset.$kondisi,'get');
        if(($datas->code == 200) && ($datas->status != 'error')){
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;
        }else{
            $this->data['data'] = [];
            $total_item = 0;
        }
        $filtering = false;
        if($kondisi){
          $filter = '&'.$limit.$kondisi;
          $filtering = true;
          $this->data['kondisi'] = '?'.$offset.$kondisi.'&'.$limit;
        }else{
          $filter = '/';
          $filtering = false;
          $this->data['kondisi'] = $current_page;
        }
        $this->data['penyelenggara'] = config('lookup.penyelenggara');
        $this->data['route'] = $request->route()->getName();
        $this->data['path'] = $request->path();
        $this->data['start_number'] = $start_number;
        $this->data['delete_route'] = 'delete_altdev_alih_profesi';
        $this->data['current_page'] = $current_page;
        $this->data['breadcrumps'] = breadcrump_litbang($request->route()->getName());
        $this->data['token'] = $request->session()->get('token');
        $this->data['breadcrumps'] = breadcrumps_dir_alternative($request->route()->getName());
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
        return view('pemberdayaan.alternative_development.altdev_alihprofesi.index_altdevAlihprofesi',$this->data);
    }
    public function updatealtdevAlihProfesi(Request $request){
      $id = $request->input('id');
      $token = $request->session()->get('token');

      $this->form_params = $request->except(['_token', 'id','kode_pelatihan']);
      if($request->tgl_kegiatan ) {
        $date = explode('/', $request->tgl_kegiatan);
        $this->form_params['tgl_kegiatan'] = $date[2].'-'.$date[1].'-'.$date[0];
      }
      $this->form_params['meta_kode_pelatihan'] = json_encode($request->kode_pelatihan);
      $data_request = execute_api_json('api/altdevprofesi/'.$id,'PUT',$this->form_params);

      $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Alternative Development - Alih Jenis Profesi/Usaha';
      $trail['audit_event'] = 'put';
      $trail['audit_value'] = json_encode($this->form_params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $data_request->comment;
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


      if(($data_request->code == 200)&& ($data_request->status != "error") ){
          $this->kelengkapan_altdev_alihprofesi($id);
          $this->messages['status'] = 'success';
          $this->messages['message'] = 'Data Alih Fungsi Lahan Ganja Berhasil Diperbarui';
      }else{
          $this->messages['status'] = 'error';
          $this->messages['message'] = 'Data Alih Fungsi Lahan Ganja BNN Gagal Diperbarui';
      }
      return back()->with('status', $this->messages);

    }
     public function editaltdevAlihprofesi(Request $request){
        $id = $request->id;
        $this->data['title'] = "Form Edit Alih Jenis Profesi/Usaha";
        $datas = execute_api_json('api/altdevprofesi/'.$id,'get');
        if(($datas->code == 200) && ($datas->code != 'error')){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }
        $datas = execute_api_json('api/singlePesertaProfesi/'.$id,'get');
        if( ($datas->code == 200) && ($datas->status != 'error' )) {
          $this->data['peserta'] = $datas->data;
        }else{
          $this->data['peserta']  = [];
        }
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        $this->data['start_number']  = $start_number;
        $this->data['delete_route']  = 'delete_peserta_alih_profesi';
        $this->data['penyelenggara'] = config('lookup.penyelenggara');
        $this->data['penghasilan_kotor'] = config('lookup.penghasilan_kotor');
        $this->data['profesi_pelatihan'] = config('lookup.profesi_pelatihan');
        $this->data['path'] = $request->path();
        $this->data['lahan_profesi'] = config('lookup.lahan_profesi');
        $datas = execute_api_json('api/lookup/jenis_identitas','get');
        if(($datas->code == 200) && ($datas->status != 'error')){
            $this->data['jenis_identitas'] = $datas->data;
        }else{
            $this->data['jenis_identitas'] = [];
        }
        $datas = execute_api_json('api/lookup/pendidikan','get');
        if(($datas->code == 200) && ($datas->status != 'error')){
            $this->data['pendidikan'] = $datas->data;
        }else{
            $this->data['pendidikan'] = [];
        }
        $this->kelengkapan_altdev_alihprofesi($id);
        $this->data['token'] = $request->session()->get('token');
        $this->data['breadcrumps'] = breadcrumps_dir_alternative($request->route()->getName());
        return view('pemberdayaan.alternative_development.altdev_alihprofesi.edit_altdevAlihprofesi',$this->data);
    }

    public function addaltdevAlihprofesi(Request $request){
      if($request->isMethod('post')){
        $token = $request->session()->get('token');
        $this->form_params = $request->except(['_token','kode_pelatihan']);
        if($request->tgl_kegiatan){
          $date = explode('/', $request->tgl_kegiatan);
          $this->form_params['tgl_kegiatan'] = $date[2].'-'.$date[1].'-'.$date[0];
        }
        $this->form_params['meta_kode_pelatihan'] = json_encode($request->kode_pelatihan);
        $data_request = execute_api_json('api/altdevprofesi','POST',$this->form_params);

        $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Alternative Development - Alih Jenis Profesi/Usaha';
        $trail['audit_event'] = 'post';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $data_request->comment;
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

        if(($data_request->code == 200)&& ($data_request->status != "error") ){
            $id = $data_request->data->eventID;
            $this->kelengkapan_altdevprofesi($id);
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Alih Fungsi Profesi Berhasil Ditambahkan';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Alih Fungsi Profesi Gagal Ditambahkan';
        }
        return redirect(route('altdev_alih_profesi'))->with('status', $this->messages);
      }else{
        $this->data['profesi_pelatihan'] = config('lookup.profesi_pelatihan');
        $this->data['penyelenggara'] = config('lookup.penyelenggara');
        $this->data['title'] = "Form Tambah Alih Jenis Profesi/Usaha";
        $this->data['path'] = $request->path();
        $this->data['token'] = $request->session()->get('token');
        $this->data['breadcrumps'] = breadcrumps_dir_alternative($request->route()->getName());
        return view('pemberdayaan.alternative_development.altdev_alihprofesi.add_altdevAlihprofesi',$this->data);
      }
    }
    public function deletealtdevAlihProfesi(Request $request){
      $id = $request->input('id');
      if($id){
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/altdevprofesi/'.$id,'DELETE');
            $this->form_params['delete_id'] = $id;
            $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Alternative Development - Alih Jenis Profesi/Usaha';
            $trail['audit_event'] = 'delete';
            $trail['audit_value'] = json_encode($this->form_params);
            $trail['audit_url'] = $request->url();
            $trail['audit_ip_address'] = $request->ip();
            $trail['audit_user_agent'] = $request->userAgent();
            $trail['audit_message'] = $data_request['comment'];
            $trail['created_at'] = date("Y-m-d H:i:s");
            $trail['created_by'] = $request->session()->get('id');

            $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

            return $data_request;
        }
      }else{
        $data_request = ['status'=>'error','message'=>'Data Gagal Dihapus'];
        return $data_request;
      }
    }
    public function altdevKawasanrawan(Request $request){
        $kondisi = '';
        if($request->limit) {
          $this->limit = $request->limit;
        } else {
          $this->limit = config('app.limit');
        }
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        if($request->isMethod('post')){
          $post = $request->except(['_token','tgl_from','tgl_to','luas_to','luas_from']);
          $tipe = $request->tipe;
          $tgl_from = $request->tgl_from;
          $tgl_from = $request->tgl_from;
          $tgl_to = $request->tgl_to;
          if($tipe){
            $kondisi .= '&tipe='.$tipe;
            $this->selected['tipe'] = $tipe;
          }
          if($tipe == 'periode'){
            if($tgl_from){
              $date = date('Y-m-d',strtotime(str_replace('/', '-', $tgl_from)));
              $kondisi .= '&tgl_from='.$date;
              $this->selected['tgl_from'] = $tgl_from;
            }else if(!$tgl_from){
                $kondisi .='';
            }
            if($tgl_to){
              $date = date('Y-m-d',strtotime(str_replace('/', '-', $tgl_to)));
              $kondisi .= '&tgl_to='.$date;
              $this->selected['tgl_to'] = $tgl_to;
            }else if(!$tgl_to){
              $kondisi .='';
            }
          }if($tipe == 'luas'){
            if($request->luas_from){
              $kondisi .= '&luas_from='.$request->luas_from;
              $this->selected['luas_from'] = $request->luas_from;
            }else if(!$request->luas_from){
                $kondisi .='';
            }
            if($request->luas_to){
              $kondisi .= '&luas_to='.$request->luas_to;
              $this->selected['luas_to'] = $request->luas_to;
            }else if(!$request->luas_to){
              $kondisi .='';
            }
          }elseif($tipe == 'lokasi'){
            $kondisi .= '&lokasi='.$request->kata_kunci;
            $this->selected['lokasi'] = $request->kata_kunci;
          }else {
            if( isset($post[$tipe]) ){
              $kondisi .= '&'.$tipe.'='.$post[$tipe];
              $this->selected[$tipe] = $post[$tipe];
            }
          }
          if($request->order){
            $kondisi .= '&order='.$request->order;
          }elseif(!$request->order){
            $kondisi .= '&order=desc';
          }
          $this->selected['order'] = $request->order;
          $this->selected['limit'] = $request->limit;
          $this->data['filter'] = $this->selected;
        }else{
          $get = $request->except(['page','tgl_from','tgl_to','limit','luas_to','luas_from']);
          $tipe = $request->tipe;
          if(count($get)>0){
            if($tipe){
              $this->selected['tipe']  = $tipe;
            }
            foreach ($get as $key => $value) {
              $kondisi .= "&".$key.'='.$value;
              if($value == 'periode'){
                if($request->tgl_from){
                    $this->selected['tgl_from'] = date('d/m/Y',strtotime($request->tgl_from));
                    $kondisi .= "&tgl_from".'='.$request->tgl_from;
                }
                if($request->tgl_to){
                  $this->selected['tgl_to'] = date('d/m/Y',strtotime($request->tgl_to));
                  $kondisi .= "&tgl_to".'='.$request->tgl_to;
                }
              }else if($value == 'luas'){
                if($request->luas_from){
                    $this->selected['luas_from'] = $request->luas_from;
                    $kondisi .= "&luas_from".'='.$request->luas_from;
                }
                if($request->luas_to){
                  $this->selected['luas_to'] = $request->luas_to;
                  $kondisi .= "&luas_to".'='.$request->luas_to;
                }
              }else {
                $this->selected[$key] = $value;
              }
            }

            $this->selected['order'] = $request->order;
            $this->selected['limit'] = $request->limit;
            $this->data['filter'] = $this->selected;
          }
        }
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
        $datas = execute_api_json('api/altdevkawasan?'.$limit.'&'.$offset.$kondisi,'get');
        if( ($datas->code == 200) && ($datas->status != 'error')){
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;
        }else{
            $this->data['data'] = [];
            $total_item = 0;
        }

        $filtering = false;
        if($kondisi){
          $filter = '&'.$limit.$kondisi;
          $filtering = true;
          $this->data['kondisi'] = '?'.$offset.$kondisi.'&'.$limit;
        }else{
          $filter = '/';
          $filtering = false;
          $this->data['kondisi'] = $current_page;
        }
        $this->data['route'] = $request->route()->getName();
        $this->data['delete_route'] ='delete_altdev_kawasan_rawan';

        $this->data['delete_route'] = "delete_altdev_kawasan_rawan";
        $this->data['title'] = "Data Kegiatan Pemetaan Kawasan Rawan Narkoba";
        $this->data['jenis_geografis'] = config('lookup.jenis_geografis');
        $this->data['path'] = $request->path();
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;
        $this->data['token'] = $request->session()->get('token');
        $this->data['breadcrumps'] = breadcrumps_dir_alternative($request->route()->getName());
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
        return view('pemberdayaan.alternative_development.altdev_kawasanrawan.index_altdevKawasanrawan',$this->data);
    }

     public function editaltdevKawasanrawan(Request $request){
        $this->data['title'] = "Ubah Data Kegiatan Pemetaan Kawasan Rawan Narkoba";
        $id = $request->id;
        $datas = execute_api_json('api/altdevkawasan/'.$id,'get');
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }
        $this->data['jenis_geografis'] = config('lookup.jenis_geografis');
        $this->data['kriminalitas'] = config('lookup.kriminalitas');
        $this->data['jumlah_tersangka'] = config('lookup.jumlah_tersangka');

        $this->data['path'] = $request->path();
        $this->data['token'] = $request->session()->get('token');
        $this->data['breadcrumps'] = breadcrumps_dir_alternative($request->route()->getName());
        return view('pemberdayaan.alternative_development.altdev_kawasanrawan.edit_altdevKawasanrawan',$this->data);
    }
     public function updatealtdevKawasanRawan(Request $request){
      $id = $request->input('id');
      $token = $request->session()->get('token');

      $this->form_params = $request->except(['_token', 'id']);
      if($request->tgl_pelaksanaan) {
        $date = explode('/', $request->tgl_pelaksanaan);
        $this->form_params['tgl_pelaksanaan'] = $date[2].'-'.$date[1].'-'.$date[0];
      }
      if(count($request->meta_kriminalitas)>= 1){
        $this->form_params['meta_kriminalitas'] = implode(',',$request->meta_kriminalitas);
      }
      $data_request = execute_api_json('api/altdevkawasan/'.$id,'PUT',$this->form_params);

      $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Alternative Development - Kawasan Rawan Narkoba';
      $trail['audit_event'] = 'put';
      $trail['audit_value'] = json_encode($this->form_params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $data_request->comment;
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

      if(($data_request->code == 200)&& ($data_request->status != "error") ){
          $this->kelengkapan_kawasanrawan($id);
          $this->messages['status'] = 'success';
          $this->messages['message'] = 'Data Alih Fungsi Lahan Ganja Berhasil Diperbarui';
      }else{
          $this->messages['status'] = 'error';
          $this->messages['message'] = 'Data Alih Fungsi Lahan Ganja BNN Gagal Diperbarui';
      }
      // exit();
      return back()->with('status', $this->messages);
    }

    public function addaltdevKawasanrawan(Request $request){
      if($request->isMethod('post')){
        $user = Auth::user();
        $id = $request->input('id');
        $token = $request->session()->get('token');

        $this->form_params = $request->except(['_token', 'id']);
        if($request->tgl_pelaksanaan) {
          $date = explode('/', $request->tgl_pelaksanaan);
          $this->form_params['tgl_pelaksanaan'] = $date[2].'-'.$date[1].'-'.$date[0];
        }
        $this->form_params['created_by'] = $user->user_id;
        if(count($request->meta_kriminalitas)>= 1){
          $this->form_params['meta_kriminalitas'] = implode(',',$request->meta_kriminalitas);
        }
        $data_request = execute_api_json('api/altdevkawasan','POST',$this->form_params);

        $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Alternative Development - Kawasan Rawan Narkoba';
        $trail['audit_event'] = 'post';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $data_request->comment;
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

        if(($data_request->code == 200)&& ($data_request->status != "error") ){
            $id = $data_request->data->eventID;
            $this->kelengkapan_kawasanrawan($id);
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Kegiatan Pemetaan Kawasan Rawan Narkoba Berhasil Ditambahkan';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Kegiatan Pemetaan Kawasan Rawan Narkoba Gagal Ditambahkan';
        }
        return redirect(route('altdev_kawasan_rawan'))->with('status', $this->messages);
      }else{
        $this->data['title'] = "Tambah Data Kegiatan Pemetaan Kawasan Rawan Narkoba";

        $this->data['jenis_geografis'] = config('lookup.jenis_geografis');
        $this->data['kriminalitas'] = config('lookup.kriminalitas');
        $this->data['jumlah_tersangka'] = config('lookup.jumlah_tersangka');

        $this->data['path'] = $request->path();
        $this->data['token'] = $request->session()->get('token');
        $this->data['breadcrumps'] = breadcrumps_dir_alternative($request->route()->getName());
        return view('pemberdayaan.alternative_development.altdev_kawasanrawan.add_altdevKawasanrawan',$this->data);
      }
    }

    public function deletealtdevKawasanRawan(Request $request){
      $id = $request->input('id');
      if($id){
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/altdevkawasan/'.$id,'DELETE');
            $this->form_params['delete_id'] = $id;
            $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Alternative Development - Kawasan Rawan Narkoba';
            $trail['audit_event'] = 'delete';
            $trail['audit_value'] = json_encode($this->form_params);
            $trail['audit_url'] = $request->url();
            $trail['audit_ip_address'] = $request->ip();
            $trail['audit_user_agent'] = $request->userAgent();
            $trail['audit_message'] = $data_request['comment'];
            $trail['created_at'] = date("Y-m-d H:i:s");
            $trail['created_by'] = $request->session()->get('id');

            $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

            return $data_request;
        }
      }else{
        $data_request = ['status'=>'error','message'=>'Data Gagal Dihapus'];
        return $data_request;
      }
    }


    public function altdevMonitoring(Request $request){
        $kondisi = '';
        if($request->limit) {
          $this->limit = $request->limit;
        } else {
          $this->limit = config('app.limit');
        }

        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }

        if($request->isMethod('post')){
          $post = $request->except(['_token','tgl_from','tgl_to']);
          $tipe = $request->tipe;
          $tgl_from = $request->tgl_from;
          $tgl_from = $request->tgl_from;
          $tgl_to = $request->tgl_to;
          if($tipe){
            $kondisi .= '&tipe='.$tipe;
            $this->selected['tipe'] = $tipe;
          }
          if($tipe == 'periode'){
            if($tgl_from){
              $date = date('Y-m-d',strtotime(str_replace('/', '-', $tgl_from)));
              $kondisi .= '&tgl_from='.$date;
              $this->selected['tgl_from'] = $tgl_from;
            }else if(!$tgl_from){
                $kondisi .='';
            }
            if($tgl_to){
              $date = date('Y-m-d',strtotime(str_replace('/', '-', $tgl_to)));
              $kondisi .= '&tgl_to='.$date;
              $this->selected['tgl_to'] = $tgl_to;
            }else if(!$tgl_to){
              $kondisi .='';
            }
          }elseif($tipe == 'lokasi'){
            $kondisi .= '&lokasi='.$request->kata_kunci;
            $this->selected['lokasi'] = $request->kata_kunci;
          }else {
            if( isset($post[$tipe]) ){
              $kondisi .= '&'.$tipe.'='.$post[$tipe];
              $this->selected[$tipe] = $post[$tipe];
            }
          }
          if($request->order){
            $kondisi .= '&order='.$request->order;
          }elseif(!$request->order){
            $kondisi .= '&order=desc';
          }
          $this->selected['order'] = $request->order;
          $this->selected['limit'] = $request->limit;
          $this->data['filter'] = $this->selected;
        }else{
          $get = $request->except(['page','tgl_from','tgl_to','limit']);
          $tipe = $request->tipe;
          if(count($get)>0){
            if($tipe){
              $this->selected['tipe']  = $tipe;
            }
            foreach ($get as $key => $value) {
              $kondisi .= "&".$key.'='.$value;
              if($value == 'periode'){
                if($request->tgl_from){
                    $this->selected['tgl_from'] = date('d/m/Y',strtotime($request->tgl_from));
                    $kondisi .= "&tgl_from".'='.$request->tgl_from;
                }
                if($request->tgl_to){
                  $this->selected['tgl_to'] = date('d/m/Y',strtotime($request->tgl_to));
                  $kondisi .= "&tgl_to".'='.$request->tgl_to;
                }
              }else {
                $this->selected[$key] = $value;
              }
            }

            $this->selected['order'] = $request->order;
            $this->selected['limit'] = $request->limit;
            $this->data['filter'] = $this->selected;
          }
        }

        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
        $datas = execute_api_json('api/monevkawasan?'.$limit.'&'.$offset.$kondisi,'get');
        if(($datas->code == 200)  && ($datas->code != 'error')  ){
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;
        }else{
            $this->data['data'] = [];
            $total_item = 0;
        }

        $filtering = false;
        if($kondisi){
          $filter = '&'.$limit.$kondisi;
          $filtering = true;
          $this->data['kondisi'] = '?'.$offset.$kondisi.'&'.$limit;
        }else{
          $filter = '/';
          $filtering = false;
          $this->data['kondisi'] = $current_page;
        }
        $this->data['route'] = $request->route()->getName();
        $this->data['delete_route'] = 'delete_altdev_kawasan_rawan';
        $this->data['penyelenggara'] =config('lookup.penyelenggara');
        $this->data['title'] = "Data Monev Kawasan Rawan Narkotika";
        $this->data['delete_route'] = 'delete_altdev_monitoring';
        $this->data['path'] = $request->path();
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;
        $this->data['token'] = $request->session()->get('token');
        $this->data['breadcrumps'] = breadcrumps_dir_alternative($request->route()->getName());
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
        return view('pemberdayaan.alternative_development.altdev_monitoring.index_altdevMonitoring',$this->data);
    }

     public function editaltdevMonitoring(Request $request){
        $this->data['title'] = "Data Monev Kawasan Rawan Narkotika";
        $id = $request->id;
        $datas = execute_api('api/monevkawasan/'.$id,'GET');
        $datas = json_decode(json_encode($datas), FALSE);
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }
        $datas = MonevKawasanrawanPeserta::where('idparent',$id);
        if($datas->count()){
          $this->data['peserta'] = $datas->get();
        }else{
          $this->data['peserta']  = [];
        }
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        $this->data['delete_route']  = 'delete_peserta_monev';
        $this->data['start_number']  = $start_number;
        $datas = execute_api_json('api/lookup/jenis_identitas','get');
        if(($datas->code == 200) && ($datas->status != 'error')){
            $this->data['jenis_identitas'] = $datas->data;
        }else{
            $this->data['jenis_identitas'] = [];
        }
        $this->data['penyelenggara'] =config('lookup.penyelenggara');
        $this->data['profesi_pelatihan'] =config('lookup.profesi_pelatihan');
        $this->data['monev_profesi'] =config('lookup.monev_profesi');
        $datas = execute_api_json('api/lookup/pendidikan','get');
        if($datas->code == 200){
            $this->data['pendidikan'] = $datas->data;
        }else{
            $this->data['pendidikan'] = [];
        }
        $this->kelengkapan_edit_altdev_monitoring($id);
        $this->data['token'] = $request->session()->get('token');
        $this->data['breadcrumps'] = breadcrumps_dir_alternative($request->route()->getName());
        return view('pemberdayaan.alternative_development.altdev_monitoring.edit_altdevMonitoring',$this->data);
    }

    public function addaltdevMonitoring(Request $request){
        if($request->isMethod('post')){
          $token = $request->session()->get('token');
          $this->form_params = $request->except(['_token','id']);
          if($request->tgl_kegiatan){
            $date = explode('/', $request->tgl_kegiatan);
            $this->form_params['tgl_kegiatan'] = $date[2].'-'.$date[1].'-'.$date[0];
          }
          $this->form_params['status'] = 'N';
          $data_request = execute_api_json('api/monevkawasan','POST',$this->form_params);

          $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Alternative Development - Monitoring dan Evaluasi Kawasan Rawan Narkotika';
          $trail['audit_event'] = 'post';
          $trail['audit_value'] = json_encode($this->form_params);
          $trail['audit_url'] = $request->url();
          $trail['audit_ip_address'] = $request->ip();
          $trail['audit_user_agent'] = $request->userAgent();
          $trail['audit_message'] = $data_request->comment;
          $trail['created_at'] = date("Y-m-d H:i:s");
          $trail['created_by'] = $request->session()->get('id');

          $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

          if(($data_request->code == 200)&& ($data_request->status != "error") ){
              $this->messages['status'] = 'success';
              $this->messages['message'] = 'Data Monev Kawasan Rawan Narkotika Berhasil Ditambahkan';
          }else{
              $this->messages['status'] = 'error';
              $this->messages['message'] = 'Data Monev Kawasan Rawan Narkotika Gagal Ditambahkan';
          }
          return redirect(route('altdev_monitoring'))->with('status', $this->messages);
        }else{
          $this->data['title'] = "Tambah Data Monev Kawasan Rawan Narkotika";
          $this->data['penyelenggara'] =config('lookup.penyelenggara');
          $this->data['token'] = $request->session()->get('token');
          $this->data['breadcrumps'] = breadcrumps_dir_alternative($request->route()->getName());
          return view('pemberdayaan.alternative_development.altdev_monitoring.add_altdevMonitoring',$this->data);
        }
    }

    public function deletealtdevMonitoring(Request $request){
      $id = $request->input('id');
      if ($request->ajax()) {
          $id = $request->id;
          $data_request = execute_api('api/monevkawasan/'.$id,'DELETE');
          $this->form_params['delete_id'] = $id;
          $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Alternative Development - Monitoring dan Evaluasi Kawasan Rawan Narkotika';
          $trail['audit_event'] = 'delete';
          $trail['audit_value'] = json_encode($this->form_params);
          $trail['audit_url'] = $request->url();
          $trail['audit_ip_address'] = $request->ip();
          $trail['audit_user_agent'] = $request->userAgent();
          $trail['audit_message'] = $data_request['comment'];
          $trail['created_at'] = date("Y-m-d H:i:s");
          $trail['created_by'] = $request->session()->get('id');

          $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

          return $data_request;
      }
    }
    public function updatealtdevMonitoring(Request $request){
      $id = $request->input('id');
      $token = $request->session()->get('token');

      $this->form_params = $request->except(['_token', 'id']);
      if($request->tgl_kegiatan) {
        $date = explode('/', $request->tgl_kegiatan);
        $this->form_params['tgl_kegiatan'] = $date[2].'-'.$date[1].'-'.$date[0];
      }
      $data_request = execute_api_json('api/monevkawasan/'.$id,'PUT',$this->form_params);

      $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Alternative Development - Monitoring dan Evaluasi Kawasan Rawan Narkotika';
      $trail['audit_event'] = 'put';
      $trail['audit_value'] = json_encode($this->form_params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $data_request->comment;
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

      if(($data_request->code == 200)&& ($data_request->status != "error") ){
          $this->kelengkapan_edit_altdev_monitoring($id);
          $this->messages['status'] = 'success';
          $this->messages['message'] = 'Data Monev Kawasan Rawan Narkotika Berhasil Diperbarui';
      }else{
          $this->messages['status'] = 'error';
          $this->messages['message'] = 'Data Monev Kawasan Rawan Narkotika Gagal Diperbarui';
      }
      return back()->with('status', $this->messages);
    }
    public function altdevSinergitas(Request $request){
        $kondisi = '';
        if($request->limit) {
          $this->limit = $request->limit;
        } else {
          $this->limit = config('app.limit');
        }

        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        if($request->isMethod('post')){
          $post = $request->except(['_token','tgl_from','tgl_to','jml_to','jml_from']);
          $tipe = $request->tipe;
          $tgl_from = $request->tgl_from;
          $tgl_to = $request->tgl_to;
          $jml_to = $request->jml_to;
          $jml_from = $request->jml_from;
          if($tipe){
            $kondisi .= '&tipe='.$tipe;
            $this->selected['tipe'] = $tipe;
          }
          if($tipe == 'periode'){
            if($tgl_from){
              $date = date('Y-m-d',strtotime(str_replace('/', '-', $tgl_from)));
              $kondisi .= '&tgl_from='.$date;
              $this->selected['tgl_from'] = $tgl_from;
            }else if(!$tgl_from){
                $kondisi .='';
            }
            if($tgl_to){
              $date = date('Y-m-d',strtotime(str_replace('/', '-', $tgl_to)));
              $kondisi .= '&tgl_to='.$date;
              $this->selected['tgl_to'] = $tgl_to;
            }else if(!$tgl_to){
              $kondisi .='';
            }
          }else if($tipe == 'jml_peserta'){
            if($jml_from){
              $kondisi .= '&jml_from='.$jml_from;
              $this->selected['jml_from'] = $jml_from;
            }else if(!$jml_from){
                $kondisi .='';
            }
            if($jml_to){
              $kondisi .= '&jml_to='.$jml_to;
              $this->selected['jml_to'] = $jml_to;
            }else if(!$jml_to){
              $kondisi .='';
            }
          }elseif($tipe == 'instansi'){
            $kondisi .= '&instansi='.$request->kata_kunci;
            $this->selected['instansi'] = $request->kata_kunci;
          }else {
            if( isset($post[$tipe]) ){
              $kondisi .= '&'.$tipe.'='.$post[$tipe];
              $this->selected[$tipe] = $post[$tipe];
            }
          }
          if($request->order){
            $kondisi .= '&order='.$request->order;
          }elseif(!$request->order){
            $kondisi .= '&order=desc';
          }
          $this->selected['order'] = $request->order;
          $this->selected['limit'] = $request->limit;
          $this->data['filter'] = $this->selected;
        }else{
          $get = $request->except(['page','tgl_from','tgl_to','limit','jml_to','jml_from']);
          $tipe = $request->tipe;
          $jml_to = $request->jml_to;
          $jml_from = $request->jml_from;
          if(count($get)>0){
            if($tipe){
              $this->selected['tipe']  = $tipe;
            }
            foreach ($get as $key => $value) {
              $kondisi .= "&".$key.'='.$value;
              if($value == 'periode'){
                if($request->tgl_from){
                    $this->selected['tgl_from'] = date('d/m/Y',strtotime($request->tgl_from));
                    $kondisi .= "&tgl_from".'='.$request->tgl_from;
                }
                if($request->tgl_to){
                  $this->selected['tgl_to'] = date('d/m/Y',strtotime($request->tgl_to));
                  $kondisi .= "&tgl_to".'='.$request->tgl_to;
                }
              }else if($value == 'jml_peserta'){
                if($jml_from){
                  $kondisi .= '&jml_from='.$jml_from;
                  $this->selected['jml_from'] = $jml_from;
                }else if(!$jml_from){
                    $kondisi .='';
                }
                if($jml_to){
                  $kondisi .= '&jml_to='.$jml_to;
                  $this->selected['jml_to'] = $jml_to;
                }else if(!$jml_to){
                  $kondisi .='';
                }
              }else {
                $this->selected[$key] = $value;
              }
            }

            $this->selected['order'] = $request->order;
            $this->selected['limit'] = $request->limit;
            $this->data['filter'] = $this->selected;
          }
        }

        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
        $datas = execute_api_json('api/psmsinergitas?'.$limit.'&'.$offset.$kondisi,'get');
        if(($datas->code == 200) && ($datas->code != 'error')){
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;
        }else{
            $this->data['data'] = [];
            $total_item = 0;
        }

        $filtering = false;
        if($kondisi){
          $filter = '&'.$limit.$kondisi;
          $filtering = true;
          $this->data['kondisi'] = '?'.$offset.$kondisi.'&'.$limit;
        }else{
          $filter = '/';
          $filtering = false;
          $this->data['kondisi'] = $current_page;
        }
        $this->data['route'] = $request->route()->getName();
        $this->data['delete_route'] = "delete_altdev_sinergitas";
        $this->data['title'] = "Data Sinergi";
        $this->data['bentuk_kegiatan'] = config('lookup.bentuk_kegiatan');
        $this->data['path'] = $request->path();
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;
        $this->data['token'] = $request->session()->get('token');
        $this->data['breadcrumps'] = breadcrumps_dir_alternative($request->route()->getName());
        $this->data['pagination'] = pagination($current_page,$total_item, $this->limit, config('app.page_ellipsis'), "/".$request->route()->getPrefix()."/".$request->route()->getName()."/%d");
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
        return view('pemberdayaan.alternative_development.altdev_sinergitas.index_altdevSinergitas',$this->data);
    }

     public function editaltdevSinergitas(Request $request){
        $this->data['title'] = "Edit Data Sinergi";
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        $id = $request->id;
        $datas = execute_api_json('api/psmsinergitas/'.$id,'get');
        if(($datas->code == 200) && ($datas->code != 'error')){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }
        $this->data['instansi'] = config('lookup.instansi');
        $this->data['bentuk_kegiatan'] = config('lookup.bentuk_kegiatan');
        $this->data['path'] = $request->path();
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;
        $this->data['token'] = $request->session()->get('token');
        $this->data['breadcrumps'] = breadcrumps_dir_alternative($request->route()->getName());
        return view('pemberdayaan.alternative_development.altdev_sinergitas.edit_altdevSinergitas',$this->data);
    }

    public function updatealtdevSinergitas(Request $request){
      $id = $request->input('id');
      $token = $request->session()->get('token');
      $this->form_params = $request->except(['_token', 'id']);
      if($request->tgl_pelaksanaan){
        $date = explode('/', $request->tgl_pelaksanaan);
        $this->form_params['tgl_pelaksanaan'] = $date[2].'-'.$date[1].'-'.$date[0];
      }
      $data_request = execute_api_json('api/psmsinergitas/'.$id,'PUT',$this->form_params);

      $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Alternative Development - Sinergi';
      $trail['audit_event'] = 'put';
      $trail['audit_value'] = json_encode($this->form_params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $data_request->comment;
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

      if(($data_request->code == 200)&& ($data_request->status != "error") ){
          $this->kelengkapan_sinergi($id);
          $this->messages['status'] = 'success';
          $this->messages['message'] = 'Data Sinergi Berhasil Diperbarui';
      }else{
          $this->messages['status'] = 'error';
          $this->messages['message'] = 'Data Sinergi Gagal Diperbarui';
      }
      return back()->with('status', $this->messages);
    }

    public function deletealtdevSinergitas(Request $request){
      $id = $request->input('id');
      if ($request->ajax()) {
          $id = $request->id;
          $data_request = execute_api('api/psmsinergitas/'.$id,'DELETE');
          $this->form_params['delete_id'] = $id;
          $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Alternative Development - Sinergi';
          $trail['audit_event'] = 'delete';
          $trail['audit_value'] = json_encode($this->form_params);
          $trail['audit_url'] = $request->url();
          $trail['audit_ip_address'] = $request->ip();
          $trail['audit_user_agent'] = $request->userAgent();
          $trail['audit_message'] = $data_request['comment'];
          $trail['created_at'] = date("Y-m-d H:i:s");
          $trail['created_by'] = $request->session()->get('id');

          $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

          return $data_request;
      }
    }
    public function addaltdevSinergitas(Request $request){
        if($request->isMethod('post')){
          $token = $request->session()->get('token');
          $this->form_params = $request->except(['_token', 'id']);
          if($request->tgl_pelaksanaan){
            $date = explode('/', $request->tgl_pelaksanaan);
            $this->form_params['tgl_pelaksanaan'] = $date[2].'-'.$date[1].'-'.$date[0];
          }
          $data_request = execute_api_json('api/psmsinergitas','POST',$this->form_params);

          $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Alternative Development - Sinergi';
          $trail['audit_event'] = 'post';
          $trail['audit_value'] = json_encode($this->form_params);
          $trail['audit_url'] = $request->url();
          $trail['audit_ip_address'] = $request->ip();
          $trail['audit_user_agent'] = $request->userAgent();
          $trail['audit_message'] = $data_request->comment;
          $trail['created_at'] = date("Y-m-d H:i:s");
          $trail['created_by'] = $request->session()->get('id');

          $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

          if(($data_request->code == 200)&& ($data_request->status != "error") ){
              $id = $data_request->data->eventID;
              $this->kelengkapan_sinergi($id);
              $this->messages['status'] = 'success';
              $this->messages['message'] = 'Data Sinergi Berhasil Diperbarui';
          }else{
              $this->messages['status'] = 'error';
              $this->messages['message'] = 'Data Sinergi Gagal Diperbarui';
          }
          return redirect(route('altdev_sinergi'))->with('status', $this->messages);
        }else{
          $this->data['instansi'] = config('lookup.instansi');
          $this->data['bentuk_kegiatan'] = config('lookup.bentuk_kegiatan');
          $this->data['path'] = $request->path();
          $this->data['token'] = $request->session()->get('token');
          $this->data['breadcrumps'] = breadcrumps_dir_alternative($request->route()->getName());
          return view('pemberdayaan.alternative_development.altdev_sinergitas.add_altdevSinergitas',$this->data);
        }
    }

    public function CallCenter(){
        $this->data['title']="Puslitdatin";
        $query = DB::table('soa_callcenter_disposisi')->limit(5)->orderBy('rid', 'desc')->get();
        $this->data['data_call_center'] = $query ;
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        return view('puslitdatin.call_center',$this->data);
    }

    public function pesertaAlihFungsi(Request $request){
        // $this->limit = config('app.limit');
        // $this->data['title'] = "Peserta Alih Fungsi Lahan Ganja";
        // if($request->page){
        //     $current_page = $request->page;
        //     $start_number = ($this->limit * ($request->page -1 )) + 1;
        // }else{
        //     $current_page = 1;
        //     $start_number = $current_page;
        // }
        // $limit = 'limit='.$this->limit;
        // $offset = 'page='.$current_page;
        // $datas = execute_api('api/altdevpetani?'.$limit.'&'.$offset,'get');
        // $datas = json_decode(json_encode($datas), FALSE);
        // if($datas->code == 200){
        //     $this->data['data'] = $datas->data;
        // }else{
        //     $this->data['data'] = [];
        // }
        // $this->data['path'] = $request->path();
        // $this->data['start_number'] = $start_number;
        // $this->data['current_page'] = $current_page;
        // $this->data['breadcrumps'] = breadcrump_litbang($request->route()->getName());
        // $this->data['token'] = $request->session()->get('token');
        // $this->data['breadcrumps'] = breadcrumps_dir_alternative($request->route()->getName());
        // return view('pemberdayaan.alternative_development.altdev_lahanganja.index_altdevLahanganja',$this->data);
    }

    public function editPesertaAlihFngsi(Request $request){
      if($request->ajax()){
        $token = $request->session()->get('token');
        $id = $request->id;
        $this->form_params = $request->except(['_token','id']);
        if($request->tgl_lahir){
          $date = explode('/', $request->tgl_lahir);
          $this->form_params['tgl_lahir'] = $date[2].'-'.$date[1].'-'.$date[0];
        }
        $data_request = execute_api_json('api/altdevpetani/'.$id,'GET');

        if(($data_request->code == 200)&& ($data_request->status != "error") ){
            $this->messages['status'] = 'success';
            $result_data = $data_request->data;
            if($result_data->tgl_lahir){
               $result_data->tgl_lahir = date('d/m/Y',strtotime($data_request->data->tgl_lahir));
            }
            $this->messages['data'] = $result_data;
        }else{
            $this->messages['status'] = 'error';
            $this->messages['data'] = [];
        }
        return response()->json($this->messages);

      }
    }
    public function addPesertaAlihFngsi(Request $request){
//        dd('peserta alih fungsi');
      if($request->ajax()){
        $token = $request->session()->get('token');
        $this->form_params = $request->except(['_token']);
        if($request->tgl_lahir){
          $date = explode('/', $request->tgl_lahir);
          $this->form_params['tgl_lahir'] = $date[2].'-'.$date[1].'-'.$date[0];
        }
        $data_request = execute_api('api/altdevpetani/','POST',$this->form_params);

        if(($data_request->code == 200)&& ($data_request->status != "error") ){
          $this->kelengkapan_altdev_alihprofesi($id);
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Alih Peserta Fungsi Lahan Ganja Berhasil Ditambahkan';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Alih Peserta Fungsi Lahan Ganja Gagal Ditambahkan';
        }
        return response()->json($this->messages);

      }
    }
    public function updatePesertaAlihFngsi(Request $request){
      if($request->ajax()){
        $token = $request->session()->get('token');
        $id = $request->id;
        $this->form_params = $request->except(['_token','id']);
        if($request->tgl_lahir){
          $date = explode('/', $request->tgl_lahir);
          $this->form_params['tgl_lahir'] = $date[2].'-'.$date[1].'-'.$date[0];
        }
        $data_request = execute_api_json('api/altdevpetani/'.$id,'PUT',$this->form_params);

        if(($data_request->code == 200)&& ($data_request->status != "error") ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Peserta Alih Fungsi Lahan Ganja Berhasil Diperbarui';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Peserta Alih Fungsi Lahan Ganja Gagal Diperbarui';
        }
        return response()->json($this->messages);

      }
    }
    public function deletePesertaAlihFngsi(Request $request){
      if($id){
        if ($request->ajax()) {
            $id = $request->id;
            $parent_id = $request->parent_id;
            $data_request = execute_api('api/altdevpetani/'.$id,'DELETE');
            return $data_request;
        }
      }else{
        $data_request = ['status'=>'error','message'=>'Data Gagal Dihapus'];
        return $data_request;
      }
    }

    public function pesertaAlihProfesi(Request $request){}
    public function editPesertaAlihProfesi(Request $request){
      if($request->ajax()){
        $token = $request->session()->get('token');
        $id = $request->id;
        $this->form_params = $request->except(['_token','id']);
        if($request->tgl_lahir){
          $date = explode('/', $request->tgl_lahir);
          $this->form_params['tgl_lahir'] = $date[2].'-'.$date[1].'-'.$date[0];
        }
        $data_request = execute_api_json('api/altdevprofesipeserta/'.$id,'GET');

        if(($data_request->code == 200)&& ($data_request->status != "error") ){
            $this->messages['status'] = 'success';
            $result_data = $data_request->data;
            if($result_data->tgl_lahir){
               $result_data->tgl_lahir = date('d/m/Y',strtotime($data_request->data->tgl_lahir));
            }
            $this->messages['data'] = $result_data;
        }else{
            $this->messages['status'] = 'error';
            $this->messages['data'] = [];
        }
        return response()->json($this->messages);

      }
    }
    public function addPesertaAlihProfesi(Request $request){
//        dd('peserta alih profesi');
        $token = $request->session()->get('token');
        $this->form_params = $request->except(['_token','index']);
        if($request->tgl_lahir){
          $date = explode('/', $request->tgl_lahir);
          $this->form_params['tgl_lahir'] = $date[2].'-'.$date[1].'-'.$date[0];
        }
        $data_request = execute_api_json('api/altdevprofesipeserta','POST',$this->form_params);

        $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Alternative Development - Alih Jenis Profesi/Usaha Peserta';
        $trail['audit_event'] = 'post';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $data_request->comment;
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

        if(($data_request->code == 200)&& ($data_request->status != "error") ){
            $this->kelengkapan_altdev_alihprofesi($request->idparent);
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Alih Peserta Fungsi Lahan Ganja Berhasil Ditambahkan';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Alih Peserta Fungsi Lahan Ganja Gagal Ditambahkan';
        }
        return back()->with('status',$this->messages);
    }
    public function updatePesertaAlihProfesi(Request $request){
      if($request->ajax()){
        $token = $request->session()->get('token');
        $this->form_params = $request->except(['_token','index']);
        $id = $request->id;
        if($request->tgl_lahir){
          $date = explode('/', $request->tgl_lahir);
          $this->form_params['tgl_lahir'] = $date[2].'-'.$date[1].'-'.$date[0];
        }
        $data_return ="";
        $data_request = execute_api_json('api/altdevprofesipeserta/'.$id,'PUT',$this->form_params);

        $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Alternative Development - Alih Jenis Profesi/Usaha Peserta';
        $trail['audit_event'] = 'put';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $data_request->comment;
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

        $data_peserta = execute_api_json('api/altdevprofesipeserta/'.$id,'GET',$this->form_params);
        if(($data_peserta->code == 200)&& ($data_peserta->status != "error") ){
            $p = $data_peserta->data;
            $data_return .= '<td></td>';
            $data_return .= '<td>'.$p->nama.'</td>';
            $data_return .= '<td>'.$p->no_identitas.'</td>';
            $data_return .= '<td>'.$p->kode_profesi.'</td>';
            $data_return .= '<td>'.($p->tgl_lahir ? date('d/m/Y',strtotime($p->tgl_lahir)) : "").'</td>';
            $data_return .= '<td>
                              <button type="button" class="btn btn-primary button-edit" data-target="'.$p->id.'" onClick="open_modalEditPesertaAlihProfesi(event,this,\'/pemberdayaan/dir_alternative/edit_peserta_alih_profesi/\',\'modal_edit_form\')"><i class="fa fa-pencil"></i></button>
                              <button type="button" class="btn btn-primary button-delete" data-target="'.$p->id.'" ><i class="fa fa-trash"></i></button>
                            </td>';
        }else{
          $data_return = '';
        }
        if(($data_request->code == 200) && ($data_request->status != "error") ){
            $this->messages['data_return'] = $data_return;
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Alih Peserta Alih Profesi Berhasil Ditambahkan';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Alih Peserta Alih Profesi Gagal Ditambahkan';
        }
        return response()->json($this->messages);
      }
    }
    public function deletePesertaAlihProfesi(Request $request){
      $id = $request->input('id');
      if($id){
        if ($request->ajax()) {
            $id = $request->id;
            $parent_id = $request->parent_id;
            $data_request = execute_api('api/altdevprofesipeserta/'.$id,'DELETE');
            $this->form_params['delete_id'] = $id;
            $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Alternative Development - Alih Jenis Profesi/Usaha Peserta';
            $trail['audit_event'] = 'delete';
            $trail['audit_value'] = json_encode($this->form_params);
            $trail['audit_url'] = $request->url();
            $trail['audit_ip_address'] = $request->ip();
            $trail['audit_user_agent'] = $request->userAgent();
            $trail['audit_message'] = $data_request['comment'];
            $trail['created_at'] = date("Y-m-d H:i:s");
            $trail['created_by'] = $request->session()->get('id');

            $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

            return $data_request;
        }
      }else{
        $data_request = ['status'=>'error','message'=>'Data Gagal Dihapus'];
        return $data_request;
      }
    }

    public function pesertaMonev(Request $request){}
    public function editPesertaMonev(Request $request){
      if($request->ajax()){
        $token = $request->session()->get('token');
        $id = $request->id;
        $this->form_params = $request->except(['_token','id']);
        if($request->tgl_lahir){
          $date = explode('/', $request->tgl_lahir);
          $this->form_params['tgl_lahir'] = $date[2].'-'.$date[1].'-'.$date[0];
        }
        $data_request = execute_api_json('api/monevkawasanpeserta/'.$id,'GET');

        if(($data_request->code == 200)&& ($data_request->status != "error") ){
            $this->messages['status'] = 'success';
            $result_data = $data_request->data;
            if($result_data->tgl_lahir){
               $result_data->tgl_lahir = date('d/m/Y',strtotime($data_request->data->tgl_lahir));
            }
            $this->messages['data'] = $result_data;
        }else{
            $this->messages['status'] = 'error';
            $this->messages['data'] = [];
        }
        return response()->json($this->messages);

      }
    }
    public function addPesertaMonev(Request $request){
//        dd('peserta monev');
        $token = $request->session()->get('token');
        $this->form_params = $request->except(['_token']);
        if($request->tgl_lahir){
          $date = explode('/', $request->tgl_lahir);
          $this->form_params['tgl_lahir'] = $date[2].'-'.$date[1].'-'.$date[0];
        }
        $data_request = execute_api_json('api/monevkawasanpeserta','POST',$this->form_params);

        $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Alternative Development - Monitoring dan Evaluasi Kawasan Rawan Narkotika Peserta';
        $trail['audit_event'] = 'post';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $data_request->comment;
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

        if(($data_request->code == 200)&& ($data_request->status != "error") ){
            $this->kelengkapan_edit_altdev_monitoring($request->idparent);
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Monev Kawasan Rawan Narkotika Berhasil Ditambahkan';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Monev Kawasan Rawan Narkotika Gagal Ditambahkan';
        }
        return back()->with('status',$this->messages);
    }
    public function updatePesertaMonev(Request $request){
      if($request->ajax()){
        $token = $request->session()->get('token');
        $id = $request->id;
        $this->form_params = $request->except(['_token','index']);
        if($request->tgl_lahir){
          $date = explode('/', $request->tgl_lahir);
          $this->form_params['tgl_lahir'] = $date[2].'-'.$date[1].'-'.$date[0];
        }
        $data_return = '';
        $data_request = execute_api_json('api/monevkawasanpeserta/'.$id,'PUT',$this->form_params);

        $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Alternative Development - Monitoring dan Evaluasi Kawasan Rawan Narkotika Peserta';
        $trail['audit_event'] = 'put';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $data_request->comment;
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

        $data_peserta = execute_api_json('api/monevkawasanpeserta/'.$id,'GET',$this->form_params);
        if(($data_peserta->code == 200)&& ($data_peserta->status != "error") ){
            $p = $data_peserta->data;
            $data_return .= '<td></td>';
            $data_return .= '<td>'.$p->nama.'</td>';
            $data_return .= '<td>'.$p->usia.'</td>';
            $data_return .= '<td>'.(($p->jenis_kelamin == 'L') ? 'Laki-Laki' : (($p->jenis_kelamin == 'P') ? 'Perempuan' : '')).'</td>';
            $data_return .= '<td>'.$p->tempat_lahir.'</td>';
            $data_return .= '<td>
                            <button type="button" class="btn btn-primary button-edit" data-target="'.$p->id.'" onClick="open_modalEditPesertaAlihProfesi(event,this,\'/pemberdayaan/dir_alternative/edit_peserta_monev/\',\'modal_edit_form\')"><i class="fa fa-pencil"></i></button>
                            <button type="button" class="btn btn-primary button-delete" data-target="'.$p->id.'" ><i class="fa fa-trash"></i></button>
                          </td>';
        }else{
          $data_return = '';
        }
        if(($data_request->code == 200)&& ($data_request->status != "error") ){
            $this->kelengkapan_edit_altdev_monitoring($request->idparent);
            $this->messages['status'] = 'success';
            $this->messages['data_return'] = $data_return;
            $this->messages['message'] = 'Data Monev Kawasan Rawan Narkotika Berhasil Diperbarui';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Monev Kawasan Rawan Narkotika Gagal Diperbarui';
        }
        return response()->json($this->messages);
      }
    }
    public function deletePesertaMonev(Request $request){
      $id = $request->input('id');
      if($id){
        if ($request->ajax()) {
            $id = $request->id;
            $parent_id = $request->parent_id;
            $data_request = execute_api('api/monevkawasanpeserta/'.$id,'DELETE');
            $this->form_params['delete_id'] = $id;
            $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Alternative Development - Monitoring dan Evaluasi Kawasan Rawan Narkotika Peserta';
            $trail['audit_event'] = 'delete';
            $trail['audit_value'] = json_encode($this->form_params);
            $trail['audit_url'] = $request->url();
            $trail['audit_ip_address'] = $request->ip();
            $trail['audit_user_agent'] = $request->userAgent();
            $trail['audit_message'] = $data_request['comment'];
            $trail['created_at'] = date("Y-m-d H:i:s");
            $trail['created_by'] = $request->session()->get('id');

            $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

            return $data_request;
        }
      }else{
        $data_request = ['status'=>'error','message'=>'Data Gagal Dihapus'];
        return $data_request;
      }
    }
    public function printPage(Request $request){
      $segment = $request->segment;
      $array_segments = [
            'altdev_lahan_ganja'=>'altdevlahan',
            'altdev_alih_profesi'=>'altdevprofesi',
            'altdev_kawasan_rawan'=>'altdevkawasan',
            'altdev_monitoring'=>'monevkawasan',
            'altdev_sinergi'=>'psmsinergitas'
        ];
        $array_titles=[
            'altdev_lahan_ganja'=>'Alih Fungsi Lahan Ganja',
            'altdev_alih_profesi'=>'Alih Fungsi Alih Profesi',
            'altdev_kawasan_rawan'=>'Kegiatan Pemetaan Kawasan Rawan Narkoba',
            'altdev_monitoring'=>'Monitoring dan Evaluasi Kawasan Rawan Narkotika',
            'altdev_sinergi'=>'Data Sinergi'
        ];
        // $url = 'api/'.$array_segments[$segment].'?page='.$page;
        // $data_request = execute_api($url,'GET');
        // $result= [];
        // $i = 1;
        $get = $request->all();
        $kondisi = "";
        if(count($get)>0){
          foreach($get as $key=>$val){
            $kondisi .= $key.'='.$val.'&';
          }
          $kondisi = rtrim($kondisi,'&');
          $kondisi = '?'.$kondisi;
        }else{
          $kondisi = '?page='.$request->page;
        }

        if($request->limit){
          $limit = $request->limit;
        }else{
          $limit = config('app.limit');
        }
        $page = $request->page;
        if($page){
          $start_number = ($limit * ($request->page -1 )) + 1;
        }else{
          $start_number = 1;
        }
        $segment = $request->segment;
        $url = 'api/'.$array_segments[$segment].$kondisi;
        $data_request = execute_api($url,'GET');

        $result= [];
        $i = $start_number;
        if($segment == 'altdev_lahan_ganja'){
            if(($data_request['code'] == 200) && ($data_request['status'] != 'error')){
                $data = $data_request['data'];
                $datas = execute_api('api/lookup/jenis_penyelenggara_kegiatan_alihfungsi_lahan','get');
                if( ($datas['code'] == 200) && $datas['status'] != 'error'){
                    $penyelenggara = $datas['data'];
                }else{
                    $penyelenggara = [];
                }

                $meta_penyelenggara = "";

                foreach($data as $key=>$value){
                    $array_peserta = [];
                    $data_peserta = [];
                    $this->data['lahan_komoditi'] = config('lookup.lahan_komoditi');
                    if($value['meta_kode_penyelenggara']){
                      $meta = json_decode($value['meta_kode_penyelenggara']);
                      foreach($meta as $j => $jval){
                        $meta_penyelenggara .= ( isset($penyelenggara[$jval]) ? $penyelenggara[$jval] : $jval )."\n";
                      }
                    }else{
                      $meta_penyelenggara ="";
                    }
                    $peserta = execute_api('api/singlePesertaLahan/'.$value['id'],'get');
                    if(isset($peserta['data'])){
                      $p =$peserta['data'];
                      if(count($p)) {
                        for($j = 0 ; $j < count($p); $j++) {
                          $data_peserta['No'] = $i;
                          $data_peserta['Nama'] = $p[$j]['nama'];
                          $data_peserta['No Identitas'] = $p[$j]['no_identitas'];
                          $data_peserta['Asal Profesi'] = $p[$j]['kodeasalprofesi'];
                          $data_peserta['Tanggal Lahir'] =date('d/m/Y',strtotime($p[$j]['tgl_lahir']));
                          $array_peserta[] = $data_peserta;
                        }
                      }else{
                        $array_peserta=[];
                      }
                    }else{
                      $array_peserta = [];
                    }

                    $meta_komoditi = "";
                    if($value['meta_kode_komoditi']){
                      $json = json_decode($value['meta_kode_komoditi']);
                      foreach($json as $k => $kval){
                        $meta_komoditi .= ( isset($lahan_komoditi[$kval]) ? $lahan_komoditi[$kval] : $kval )."\n";
                      }
                    }else{
                      $meta_komoditi = "";
                    }
                    $result[$key]['No'] = $i;
                    $result[$key]['Tanggal Kegiatan'] = date('d/m/Y',strtotime($value['tgl_kegiatan'])) ;
                    $result[$key]['Pelaksana'] = $value['idpelaksana'];
                    $result[$key]['Penyelenggara'] = $meta_penyelenggara;
                    $result[$key]['Komoditi'] = $meta_komoditi;
                    $result[$key]['Status'] = ( ($value['status'] == 'Y') ? 'Lengkap' : 'Belum Lengkap');
                    // $result[$key]['Peserta'] = $array_peserta;
                    $i = $i + 1;
                }
                $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
                $this->printData($result, $name);
            }else{
                return false;
            }
        }else if($segment == 'altdev_alih_profesi'){
          if(($data_request['code'] == 200) && ($data_request['status'] != 'error')){
              $data = $data_request['data'];
              $kode = "";
              $array_peserta = [];
              $penyelenggara= config('lookup.penyelenggara');
              foreach($data as $key=>$value){
                $data_peserta = [];
                $peserta = execute_api('api/singlePesertaProfesi/'.$value['id'],'get');
                if(isset($peserta['data'])){
                  $p =$peserta['data'];
                  if(count($p)){
                    for($j = 0 ; $j < count($p); $j++) {
                      $data_peserta['No'] = $j;
                      $data_peserta['No Identitas'] = $p[$j]['no_identitas'];
                      $data_peserta['Asal Profesi'] = $p[$j]['kode_profesi'];
                      $data_peserta['Tanggal Lahir'] =date('d/m/Y',strtotime($p[$j]['tgl_lahir']));
                      $array_peserta[] = $data_peserta;
                    }
                  }else{
                    $array_peserta = [];
                  }

                }else{
                  $array_peserta = [];
                }
                $result[$key]['No'] = $i;
                $result[$key]['Tanggal Kegiatan'] = date('d/m/Y',strtotime($value['tgl_kegiatan']));
                $result[$key]['Pelaksana'] = $value['nm_instansi'];
                $result[$key]['Penyelenggara'] = (isset($penyelenggara[$value['kodepenyelenggara']]) ?$penyelenggara[$value['kodepenyelenggara']]  :$value['kodepenyelenggara']) ;
                $result[$key]['Lokasi Kawasan Rawan'] = $value['nama_kegiatan'];
                $result[$key]['Status'] = ( ($value['status'] == 'Y') ? 'Lengkap' : 'Belum Lengkap');
                // $result[$key]['Peserta'] = $array_peserta;
              }
              $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
              $this->printData($result, $name);
          }else{
              return false;
          }
        }else if($segment == 'altdev_kawasan_rawan'){
          if(($data_request['code'] == 200) && ($data_request['status'] != 'error')){
            $data = $data_request['data'];
              foreach($data as $key=>$value){
                $result[$key]['No'] = $i;
                $result[$key]['Tanggal Kegiatan'] = ( $value['tgl_pelaksanaan'] ? date('d/m/Y',strtotime($value['tgl_pelaksanaan'])) : '' );
                $result[$key]['Pelaksana'] = $value['nm_instansi'];
                $result[$key]['Lokasi Kawasan Rawan'] = $value['lokasi_kawasan_rawan'];
                $result[$key]['Jenis Geografis'] = isset($jenis_geografis[$value['kode_geografis']]) ? $jenis_geografis[$value['kode_geografis']] :$value['kode_geografis'];
                $result[$key]['Luas Kawasan (m2)'] = $value['luas_kawasan'];
                $result[$key]['Status'] = ( ($value['status'] == 'Y') ? 'Lengkap' : 'Belum Lengkap');
                $i =$i + 1;
              }
              $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
              $this->printData($result, $name);
            }else{
                return false;
            }
        }else if($segment == 'altdev_monitoring'){
          if(($data_request['code'] == 200) && ($data_request['status'] != 'error')){
              $data = $data_request['data'];
              $array_peserta = [];
              $penyelenggara =config('lookup.penyelenggara');
              foreach($data as $key=>$value){
                $data_peserta = [];
                $peserta = execute_api('api/singlePesertaMonev/','get');
                if(isset($peserta['data'])){
                  $p =$peserta['data'];
                  if(count($p)>0){
                    for($j = 0 ; $j < count($p); $j++) {
                      $data_peserta['No'] = $j;
                      $data_peserta['Nama'] = $p[$j]['nama'];
                      $data_peserta['No Identitas'] = $p[$j]['no_identitas'];
                      $data_peserta['Asal Profesi'] = $p[$j]['kode_profesi'];
                      $data_peserta['Tanggal Lahir'] =date('d/m/Y',strtotime($p[$j]['tgl_lahir']));
                      $array_peserta[] = $data_peserta;
                    }
                  }else{
                    $array_peserta = [];
                  }
                }else{
                  $array_peserta = [];
                }
                $result[$key]['No'] = $i;
                $result[$key]['Tanggal Kegiatan'] = date('d/m/Y',strtotime($value['tgl_kegiatan']));
                $result[$key]['Pelaksana'] = $value['nm_instansi'];
                $result[$key]['Penyelenggara'] = ( isset($penyelenggara[$value['kodepenyelenggara']]) ? $penyelenggara[$value['kodepenyelenggara']] : "" );
                $result[$key]['Lokasi Kawasan Rawan'] = $value['nama_kegiatan'];
                $result[$key]['Status'] = ( ($value['status'] == 'Y') ? 'Lengkap' : 'Belum Lengkap');
                // $result[$key]['Peserta'] = $array_peserta;
                $i =$i + 1;
              }
            $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
            $this->printData($result, $name);
          }else{
              return false;
          }
      }else if($segment == 'altdev_sinergi'){
        if(($data_request['code'] == 200) && ($data_request['status'] != 'error')){
          $data = $data_request['data'];
          $this->data['bentuk_kegiatan'] = config('lookup.bentuk_kegiatan');
          foreach($data as $key=>$value){
            $result[$key]['No'] = $i;
            $result[$key]['Tanggal Pelaksanaan'] = ($value['tgl_pelaksanaan'] ? date('d/m/Y',strtotime($value['tgl_pelaksanaan'])) : '');
            $result[$key]['Pelaksana'] = $value['nm_instansi'];
            $result[$key]['Jenis Kegiatan'] = (isset($bentuk_kegiatan[$value['jenis_kegiatan']]) ?  $bentuk_kegiatan[$value['jenis_kegiatan']] : $value['jenis_kegiatan']);
            $result[$key]['Nama Instansi'] = $value['materi'];
            $result[$key]['Jumlah Peserta'] = $value['jumlah_peserta'];
            $result[$key]['Status'] = ( ($value['status'] == 'Y') ? 'Lengkap' : 'Belum Lengkap');
            $i =$i + 1;
          }
          $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
          $this->printData($result, $name);
        }else{
          return false;
        }
      }
    }

    private function kelengkapan_altdevlahanganja($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('dayamas_altdev_alihfungsilahanganja')->where('id',$id)
                  ->select('tgl_kegiatan','idpelaksana', 'meta_kode_penyelenggara' ,'meta_kode_komoditi' ,'bulan_tanam', 'meta_lokasi_lahan');

        if($query->count() > 0 ){
          $result = $query->first();
          foreach($result as $key=>$val){
              if(!$val){
                $status_kelengkapan=false;
                break;
              }else{
                continue;
              }
            }
        }
        if($status_kelengkapan== true){
           $kelengkapan = execute_api_json('api/altdevlahan/'.$id,'PUT',['status'=>'Y']);
        }else{
           $kelengkapan = execute_api_json('api/altdevlahan/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

    private function kelengkapan_altdevprofesi($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('dayamas_altdev_alihfungsiprofesi')->where('id',$id)
                  ->select('id_instansi','kodepenyelenggara', 'nama_kegiatan' ,'tgl_kegiatan' ,'lokasi_idkabkota', 'meta_kode_pelatihan');

        if($query->count() > 0 ){
          $result = $query->first();
          foreach($result as $key=>$val){
              if(!$val){
                $status_kelengkapan=false;
                break;
              }else{
                continue;
              }
            }
        }
        if($status_kelengkapan== true){
           $kelengkapan = execute_api_json('api/altdevprofesi/'.$id,'PUT',['status'=>'Y']);
        }else{
           $kelengkapan = execute_api_json('api/altdevprofesi/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }
    private function kelengkapan_kawasanrawan($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('dayamas_altdev_kawasanrawan')->where('id',$id)
                  ->select('tgl_pelaksanaan','idpelaksana','lokasi_kawasan_rawan','kode_desakampung','kode_kelurahan','kode_kecamatan','lokasi_idkabkota','lokasi_longitude','lokasi_latitude','pendidikan_sd','pendidikan_slp','pendidikan_sla','pendidikan_pt','pendidikan_putus_sekolah','pendidikan_tdk_sekolah','kerja_tni','kerja_polisi','kerja_swasta','kerja_wiraswasta','kerja_buruh','kerja_tani','kerja_mahasiswa','kerja_pelajar','kerja_irt','kerja_pengangguran','luas_kawasan','batas_timur','batas_barat','batas_utara','batas_selatan','narkoba_shabu','narkoba_ekstasi','narkoba_ganja','narkoba_putau','narkoba_heroin','narkoba_benzodiazephine','narkoba_dextromethorphan','narkoba_lainnya','barbuk_shabu','barbuk_ekstasi','barbuk_ganja','barbuk_putau','barbuk_heroin','barbuk_benzodiazephine','barbuk_dextromethorphan','barbuk_lainnya','barbuk_lainnya_jumlah','meta_kriminalitas');

        if($query->count() > 0 ){
          $result = $query->first();
          foreach($result as $key=>$val){
              if(!$val){
                $status_kelengkapan=false;
                break;
              }else{
                continue;
              }
            }
        }
        if($status_kelengkapan== true){
           $kelengkapan = execute_api_json('api/altdevkawasan/'.$id,'PUT',['status'=>'Y']);
        }else{
           $kelengkapan = execute_api_json('api/altdevkawasan/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

    private function kelengkapan_monev($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('dayamas_altdev_kawasanrawan')->where('id',$id)
                  ->select('tgl_pelaksanaan','idpelaksana','lokasi_kawasan_rawan','kode_desakampung','kode_kelurahan','kode_kecamatan','lokasi_idkabkota','lokasi_longitude','lokasi_latitude','pendidikan_sd','pendidikan_slp','pendidikan_sla','pendidikan_pt','pendidikan_putus_sekolah','pendidikan_tdk_sekolah','kerja_tni','kerja_polisi','kerja_swasta','kerja_wiraswasta','kerja_buruh','kerja_tani','kerja_mahasiswa','kerja_pelajar','kerja_irt','kerja_pengangguran','luas_kawasan','batas_timur','batas_barat','batas_utara','batas_selatan','narkoba_shabu','narkoba_ekstasi','narkoba_ganja','narkoba_putau','narkoba_heroin','narkoba_benzodiazephine','narkoba_dextromethorphan','narkoba_lainnya','barbuk_shabu','barbuk_ekstasi','barbuk_ganja','barbuk_putau','barbuk_heroin','barbuk_benzodiazephine','barbuk_dextromethorphan','barbuk_lainnya','barbuk_lainnya_jumlah','meta_kriminalitas');

        if($query->count() > 0 ){
          $result = $query->first();
          foreach($result as $key=>$val){
              if(!$val){
                $status_kelengkapan=false;
                break;
              }else{
                continue;
              }
            }
        }
        if($status_kelengkapan== true){
           $kelengkapan = execute_api_json('api/altdevkawasan/'.$id,'PUT',['status'=>'Y']);
        }else{
           $kelengkapan = execute_api_json('api/altdevkawasan/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

    private function kelengkapan_sinergi($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('dayamas_psm_sinergitas')->where('id',$id)
                  ->select('tgl_pelaksanaan','idpelaksana','lokasi_kegiatan','lokasi_kegiatan_idkabkota','kodesasaran','materi','narasumber','jenis_kegiatan','panitia_monev','jumlah_peserta');

        if($query->count() > 0 ){
          $result = $query->first();
          foreach($result as $key=>$val){
              if(!$val){
                $status_kelengkapan=false;
                break;
              }else{
                continue;
              }
            }
        }
        if($status_kelengkapan== true){
           $kelengkapan = execute_api_json('api/psmsinergitas/'.$id,'PUT',['status'=>'Y']);
        }else{
           $kelengkapan = execute_api_json('api/psmsinergitas/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

    private function kelengkapan_edit_altdev_monitoring($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('dayamas_altdev_monev_kawasanrawan')->where('id',$id)
                  ->select('id_instansi','kodepenyelenggara','nama_kegiatan','tgl_kegiatan','lokasi_idkabkota');
        $query_peserta = DB::table('dayamas_altdev_monev_kawasanrawan_peserta')->where('idparent',$id)
                      ->count();

        if($query_peserta > 0){
          if($query->count() > 0 ){
            $result = $query->first();
            foreach($result as $key=>$val){
                if(!$val){
                  $status_kelengkapan=false;
                  break;
                }else{
                  continue;
                }
              }
          }
        }else{
          $status_kelengkapan =false;
        }
        if($status_kelengkapan== true){
           $kelengkapan = execute_api_json('api/monevkawasan/'.$id,'PUT',['status'=>'Y']);
        }else{
           $kelengkapan = execute_api_json('api/monevkawasan/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

    private function kelengkapan_altdev_alihprofesi($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('dayamas_altdev_alihfungsiprofesi')->where('id',$id)
                  ->select( 'id_instansi','kodepenyelenggara','nama_kegiatan','tgl_kegiatan','lokasi_idkabkota','meta_kode_pelatihan');
        $query_peserta = DB::table('dayamas_altdev_alihfungsiprofesi_peserta')->where('idparent',$id)
                      ->count();

        if($query_peserta > 0){
          if($query->count() > 0 ){
            $result = $query->first();
            foreach($result as $key=>$val){
              if(!$val || $val == 'null' ){
                $status_kelengkapan=false;
                break;
              }else{
                continue;
              }
            }
          }
        }else{
          $status_kelengkapan =false;
        }
        if($status_kelengkapan== true){
           $kelengkapan = execute_api_json('api/altdevprofesi/'.$id,'PUT',['status'=>'Y']);
        }else{
           $kelengkapan = execute_api_json('api/altdevprofesi/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

    public function deletetrail($data)
    {
      $client = new Client();
      $baseUrl = URL::to('/');

      $trail['audit_menu'] = $data->menu;
      $trail['audit_event'] = 'delete';
      $trail['audit_value'] = array('delete_id' => $data->id);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $data->comment;
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

    }

    public function downloadLahanGanja(Request $request){
    
      $data = DB::table('v_dayamas_altdev_alihfungsilahanganja');
      if ($request->date_from != '') {
        $data->where('tgl_kegiatan', '>=', date('Y-m-d', strtotime(str_replace('/', '-', $request->date_from))));
      }
      if ($request->date_to != '' ) {
        $data->where('tgl_kegiatan', '<=', date('Y-m-d', strtotime(str_replace('/', '-', $request->date_to))));
      }

      $data = $data->orderBy('tgl_kegiatan', 'desc')->get();
      // dd($pemusnahanladang);
      $result = [];
      $i = 1;
          foreach($data as $key=>$value){
            $result[$key]['No'] = $i;
            $result[$key]['Tanggal Kegiatan'] = ( $value->tgl_kegiatan ? date('d/m/Y',strtotime($value->tgl_kegiatan)) : '');
            $result[$key]['Pelaksana'] = $value->idpelaksana;

            $peserta = "";
            $meta_peserta = $value->meta_kode_penyelenggara;
            if($meta_peserta){
                $meta = json_decode($meta_peserta,true);
                if(count($meta) > 0 ){
                    for($j = 0 ; $j < count($meta); $j ++ ){
                        $peserta .= str_replace('PENYELENGGARA_', '', $meta[$j]);
                        $peserta .= ". ";
                    }
                    $peserta = rtrim($peserta);
                }
            }
            $result[$key]['Penyelenggara']= $peserta;

            $peserta = "";
            $meta_peserta = $value->meta_kode_komoditi;
            if($meta_peserta){
                $meta = json_decode($meta_peserta,true);
                if(count($meta) > 0 ){
                    for($j = 0 ; $j < count($meta); $j ++ ){
                        $peserta .= $meta[$j];
                        $peserta .= ". ";
                    }
                    $peserta = rtrim($peserta);
                }
            }
            $result[$key]['Jenis Komoditi']= $peserta;

            $result[$key]['Masa Tanam'] =$value->bulan_tanam;
            $result[$key]['Keterangan Lainnya'] =$value->keterangan_lainnya;
            $result[$key]['Lokasi Peninjauan'] =$value->lokasi;

            $i = $i +1;
          }
        $name = 'Export Data Alih Fungsi Lahan Ganja '.date('Y-m-d H:i:s');

          $this->printData($result, $name);
      
    }

    public function downloadAlihProfesi(Request $request){
    
      $data = DB::table('v_dayamas_altdev_alihfungsiprofesi');
      if ($request->date_from != '') {
        $data->where('tgl_kegiatan', '>=', date('Y-m-d', strtotime(str_replace('/', '-', $request->date_from))));
      }
      if ($request->date_to != '' ) {
        $data->where('tgl_kegiatan', '<=', date('Y-m-d', strtotime(str_replace('/', '-', $request->date_to))));
      }

      $data = $data->orderBy('tgl_kegiatan', 'desc')->get();
      // dd($pemusnahanladang);
      $result = [];
      $i = 1;
          foreach($data as $key=>$value){
            $result[$key]['No'] = $i;
            $result[$key]['Pelaksana'] = $value->nm_instansi;
            $result[$key]['Penyelenggara'] = $value->kodepenyelenggara;
            $result[$key]['Lokasi'] = $value->nama_kegiatan;
            $result[$key]['Tanggal Kegiatan'] = ( $value->tgl_kegiatan ? date('d/m/Y',strtotime($value->tgl_kegiatan)) : '');
            $result[$key]['Lokasi Kabupaten'] = $value->lokasi_kegiatan_namakabkota;

            $peserta = "";
            $meta_peserta = $value->meta_kode_pelatihan;
            if($meta_peserta){
                $meta = json_decode($meta_peserta,true);
                if(count($meta) > 0 ){
                    for($j = 0 ; $j < count($meta); $j ++ ){
                        $peserta .= $meta[$j];
                        $peserta .= ". ";
                    }
                    $peserta = rtrim($peserta);
                }
            }
            if($value->pelatihan_lain){
                $peserta .= $value->pelatihan_lain;
            }
            $result[$key]['Pelatihan']= $peserta;

            $i = $i +1;
          }
        $name = 'Export Data Alih Fungsi Profesi '.date('Y-m-d H:i:s');

          $this->printData($result, $name);
      
    }

    public function downloadKawasanRawan(Request $request){
    
      $data = DB::table('v_dayamas_altdev_kawasanrawan');
      if ($request->date_from != '') {
        $data->where('tgl_pelaksanaan', '>=', date('Y-m-d', strtotime(str_replace('/', '-', $request->date_from))));
      }
      if ($request->date_to != '' ) {
        $data->where('tgl_pelaksanaan', '<=', date('Y-m-d', strtotime(str_replace('/', '-', $request->date_to))));
      }

      $data = $data->orderBy('tgl_pelaksanaan', 'desc')->get();
      // dd($pemusnahanladang);
      $result = [];
      $i = 1;
          foreach($data as $key=>$value){
            $result[$key]['No'] = $i;
            $result[$key]['Tanggal Kegiatan'] = ( $value->tgl_pelaksanaan ? date('d/m/Y',strtotime($value->tgl_pelaksanaan)) : '');
            $result[$key]['Pelaksana'] = $value->nm_instansi;
            $result[$key]['Nama Lokasi'] = $value->lokasi_kawasan_rawan;
            $result[$key]['Lokasi Desa'] = $value->kode_desakampung;
            $result[$key]['Lokasi Kelurahan'] = $value->kode_kelurahan;
            $result[$key]['Lokasi Kecamatan'] = $value->kode_kecamatan;
            $result[$key]['Lokasi Kabupaten'] = $value->lokasi_kegiatan_namakabkota;
            $result[$key]['Jenis Geografis'] = $value->kode_geografis;
            $result[$key]['Batas Utara'] = $value->batas_utara;
            $result[$key]['Batas Timur'] = $value->batas_timur;
            $result[$key]['Batas Selatan'] = $value->batas_selatan;
            $result[$key]['Batas Barat'] = $value->batas_barat;

            $peserta = "";
            $meta_peserta = $value->meta_kriminalitas;
            if($meta_peserta){
                $meta = json_decode($meta_peserta,true);
                if(count($meta) > 0 ){
                    for($j = 0 ; $j < count($meta); $j ++ ){
                        $peserta .= $meta[$j];
                        $peserta .= ". ";
                    }
                    $peserta = rtrim($peserta);
                }
            }
            $result[$key]['Kriminalitas']= $peserta;

            $i = $i +1;
          }
        $name = 'Export Data Kawasan Rawan '.date('Y-m-d H:i:s');

          $this->printData($result, $name);
      
    }

    public function downloadMonitoring(Request $request){
    
      $data = DB::table('v_dayamas_altdev_monev_kawasanrawan');
      if ($request->date_from != '') {
        $data->where('tgl_kegiatan', '>=', date('Y-m-d', strtotime(str_replace('/', '-', $request->date_from))));
      }
      if ($request->date_to != '' ) {
        $data->where('tgl_kegiatan', '<=', date('Y-m-d', strtotime(str_replace('/', '-', $request->date_to))));
      }

      $data = $data->orderBy('tgl_kegiatan', 'desc')->get();
      // dd($pemusnahanladang);
      $result = [];
      $i = 1;
          foreach($data as $key=>$value){
            $result[$key]['No'] = $i;
            $result[$key]['Pelaksana'] = $value->nm_instansi;
            $result[$key]['Penyelenggara'] = $value->kodepenyelenggara;
            $result[$key]['Lokasi'] = $value->nama_kegiatan;
            $result[$key]['Tanggal Kegiatan'] = ( $value->tgl_kegiatan ? date('d/m/Y',strtotime($value->tgl_kegiatan)) : '');
            $result[$key]['Lokasi Kabupaten'] = $value->lokasi_kegiatan_namakabkota;

            $i = $i +1;
          }
        $name = 'Export Data Monev Kawasan Rawan '.date('Y-m-d H:i:s');

          $this->printData($result, $name);
      
    }

    public function downloadSinergi(Request $request){
    
      $data = DB::table('v_dayamas_psm_sinergitas');
      if ($request->date_from != '') {
        $data->where('tgl_pelaksanaan', '>=', date('Y-m-d', strtotime(str_replace('/', '-', $request->date_from))));
      }
      if ($request->date_to != '' ) {
        $data->where('tgl_pelaksanaan', '<=', date('Y-m-d', strtotime(str_replace('/', '-', $request->date_to))));
      }

      $data = $data->orderBy('tgl_pelaksanaan', 'desc')->get();
      // dd($pemusnahanladang);
      $result = [];
      $i = 1;
          foreach($data as $key=>$value){
            $result[$key]['No'] = $i;
            $result[$key]['Tanggal Kegiatan'] = ( $value->tgl_pelaksanaan ? date('d/m/Y',strtotime($value->tgl_pelaksanaan)) : '');
            $result[$key]['Pelaksana'] = $value->nm_instansi;
            $result[$key]['Lokasi'] = $value->lokasi_kegiatan;
            $result[$key]['Lokasi Kabupaten'] = $value->lokasi_kegiatan_namakabkota;
            $result[$key]['Type Instansi'] = $value->kodesasaran;
            $result[$key]['Nama Instansi'] = $value->materi;
            $result[$key]['Alamat Instansi'] = $value->narasumber;
            $result[$key]['Bentuk Kegiatan'] = $value->jenis_kegiatan;
            $result[$key]['Lama Kegiatan'] = $value->panitia_monev;
            $result[$key]['Jumlah Peserta'] = $value->jumlah_peserta;

            $i = $i +1;
          }
        $name = 'Export Data Sinergitas '.date('Y-m-d H:i:s');

          $this->printData($result, $name);
      
    }

}

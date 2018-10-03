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

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class penindakanController extends Controller
{
    public $data;
    public $selected;
    public $form_params;
    public function pendataanDpo(Request $request){
        $client = new Client();
      $token = $request->session()->get('token');


      $kondisi = '';


      if($request->limit) {
        $this->limit = $request->limit;
      } else {
        $this->limit = config('app.limit');
      }
      if($request->isMethod('get')){
          $get = $request->except(['page']);
          $tipe = $request->tipe;
          if(count($get)>0){
            $this->selected['tipe']  = $tipe;
            foreach ($get as $key => $value) {
              $kondisi .= "&".$key.'='.$value;
              if( ($key == 'tgl_from') || ($key == 'tgl_to') ){
                $this->selected[$key] = date('d/m/Y',strtotime($value));
              }else if(($key == 'tgl_from') || ($key == 'tgl_to')){
                $this->selected[$key] = $value;
              }else if(($key == 'nomor_sprint_dpo') || ($key == 'no_identitas') || ($key == 'alamat') || ($key == 'kode_jenis_kelamin') || ($key == 'status') ){
                  $this->selected[$key] = $value;
                  $this->selected['keyword'] = $value;
              }
            }

            $this->selected['order'] = $request->order;
            $this->selected['limit'] = $request->limit;
            $this->data['filter'] = $this->selected;
          }
      }else{
        $post = $request->all();
        $tipe = $request->tipe;
        $kata_kunci = $request->kata_kunci;
        $kode_jenis_kelamin = $request->kode_jenis_kelamin;
        // $pelaksana = $request->pelaksana;
        $status = $request->status;
        // $BrgBukti = $request->BrgBukti;
        $tgl_from = $request->tgl_from;
        $tgl_to = $request->tgl_to;
        $order = $request->order;
        $limit = $request->limit;
        // dd($pelaksana);

        // if($tipe == 'periode'){
        //   if($tgl_from){
        //     $date = date('Y-m-d',strtotime(str_replace('/', '-', $tgl_from)));
        //     $kondisi .= '&tgl_from='.$date;
        //     $this->selected['tgl_from'] = $tgl_from;
        //   }else{
        //       $kondisi .='';
        //   }
        //   if($tgl_to){
        //     $date = date('Y-m-d',strtotime(str_replace('/', '-', $tgl_to)));
        //     $kondisi .= '&tgl_to='.$date;
        //     $this->selected['tgl_to'] = $tgl_to;
        //   }else{
        //     $kondisi .='';
        //   }
        // }else
        if($tipe == 'kode_jenis_kelamin'){
          $kondisi .= '&kode_jenis_kelamin='.$kode_jenis_kelamin;
          $this->selected['kode_jenis_kelamin'] = $kode_jenis_kelamin;
        }elseif($tipe == 'status'){
          $kondisi .= '&status='.$status_kelengkapan;
          $this->selected['status'] = $status_kelengkapan;
        }else{
          $kondisi .= '&'.$tipe.'='.$kata_kunci;
          $this->selected['keyword'] = $kata_kunci;
        }
        if($tipe){
          $kondisi .= '&tipe='.$tipe;
          $this->selected['tipe'] = $tipe;
        }
        $kondisi .='&limit='.$this->limit;
        $kondisi .='&order='.$order;
        $this->selected['limit'] = $this->limit;
        $this->selected['order'] =  $order;
      }
      $this->data['title'] = "Daftar Pencarian Orang (DPO)";
      if($request->page){
          $current_page = $request->page;
          $start_number = ($this->limit * ($request->page -1 )) + 1;
      }else{
          $current_page = 1;
          $start_number = $current_page;
      }

      $limit = 'limit='.$this->limit;
      $offset = 'page='.$current_page;
      $kondisi .='&id_wilayah='.$request->session()->get('wilayah');
      $datas = execute_api('api/dpo?'.$limit.'&'.$offset.$kondisi,'get');
      // print_r($datas);
      // echo '</pre>';
      // dd($datas);
      $this->data['kondisi'] ='?'.$offset.$kondisi;
      if( ($datas['code']= 200) && ($datas['code'] != 'error')){
        $this->data['data_dpo'] = $datas['data'];
        $total_item = $datas['paginate']['totalpage'] * $this->limit;
      }else{
        $this->data['data_dpo'] = [];
        $total_item =  0;
      }

      $instansi = $this->globalinstansi($request->session()->get('wilayah'), $token);
      $this->data['instansi'] = $instansi;

      // $instansi  =  execute_api('api/instansi',"GET");


      // if($instansi['code'] == 200 && ($instansi['status'] != 'error')){
      //   $this->data['instansi'] = $instansi['data'];
      // }else{
      //   $this->data['instansi'] = [];
      // }

      // $query  =  execute_api('api/lookup/irtama_satker',"GET");


      // if($query['code'] == 200 && ($query['status'] != 'error')){
      //   $this->data['satker'] = $query['data'];
      // }else{
      //   $this->data['satker'] = [];
      // }
      $this->data['current_page'] = $current_page;
      $this->data['start_number'] = $start_number;
      // $this->data['title'] = "Pemusnahan Barang Bukti Dir Wastahti";
      $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
      $this->data['token'] = $token;
      $this->data['path'] = $request->path();
      $this->data['delete_route'] = 'delete_pendataan_dpo';
      $this->data['route'] = $request->route()->getName();
      $this->data['filter'] = $this->selected;
      $filtering = false;
      if($kondisi){
        $filter = $kondisi;
        $filtering = true;
      }else{
        $filter = '/';
        $filtering = false;
      }
      $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'),config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
      $this->data['breadcrumps'] = breadcrumps_dir_penindakan($request->route()->getName());

        //old//

        // $client = new Client();
        // if ($request->input('page')) {
        //   $page = $request->input('page');
        // } else {
        //   $page = 1;
        // }

        // $baseUrl = URL::to('/');
        // $token = $request->session()->get('token');

        // $requestDpo = $client->request('GET', $baseUrl.'/api/dpo?page='.$page.'&id_wilayah='.$request->session()->get('wilayah'),
        //     [
        //         'headers' =>
        //         [
        //             'Authorization' => 'Bearer '.$token
        //         ]
        //     ]
        // );
        // $dpo = json_decode($requestDpo->getBody()->getContents(), true);

        // $this->data['data_dpo'] = $dpo['data'];
        // $page = $dpo['paginate'];

        // $this->data['title'] = "dpo";
        // $this->data['token'] = $token;


        // $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));

        // $user_id = Auth::user()->user_id;
        // $detail = MainModel::getUserDetail($user_id);
        // $this->data['data_detail'] = $detail;
        // $this->data['path'] = $request->path();
        // // $this->data['instansi'] = $instansi;
        // $this->data['page'] = $page;
        // $this->data['breadcrumps'] = breadcrumps_dir_penindakan($request->route()->getName());
        return view('pemberantasan.penindakan.index_pendataanDPO',$this->data);
    }

     public function editPendataanDpo(Request $request){
        $id = $request->id;
        $client = new Client();
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestDpo = $client->request('GET', $baseUrl.'/api/dpo/'.$id,
          [
            'headers' =>
            [
              'Authorization' => 'Bearer '.$token
            ]
          ]
        );

        $dpo = json_decode($requestDpo->getBody()->getContents(), true);
        // dd($dpo);
        $instansi = $this->globalinstansi($request->session()->get('wilayah'), $token);
        $negara = $this->globalNegara($token);
        $this->data['instansi'] = $instansi;
        $this->data['negara'] = $negara;
        $this->data['dpo'] = $dpo['data'];
        $this->data['breadcrumps'] = breadcrumps_dir_penindakan($request->route()->getName());
        return view('pemberantasan.penindakan.edit_pendataanDPO',$this->data);
    }

    public function addPendataanDpo(Request $request){
        $client = new Client();
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $instansi = $this->globalinstansi($request->session()->get('wilayah'), $token);
        $negara = $this->globalNegara($token);
        $this->data['instansi'] = $instansi;
        $this->data['negara'] = $negara;
        $this->data['breadcrumps'] = breadcrumps_dir_penindakan($request->route()->getName());
        return view('pemberantasan.penindakan.add_pendataanDPO', $this->data);
    }

    public function inputPendataanDpo(Request $request) {
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');
        $client = new Client();
        if ($request->input('tanggal_lahir') != '') {
          $tgl_lahir = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal_lahir'))));
        } else {
          $tgl_lahir = '';
        }

        $requestDpo = $client->request('POST', $baseUrl.'/api/dpo',
          [
            'headers' =>
            [
              'Authorization' => 'Bearer '.$token
            ],
            'form_params' =>
            [
              "nomor_sprint_dpo" => $request->input('nomor_sprint_dpo'),
              "nama" => $request->input('nama'),
              "alamat" => $request->input('alamat'),
              "kode_jenis_kelamin" => $request->input('kode_jenis_kelamin'),
              "tempat_lahir" => $request->input('tempat_lahir'),
              "usia" => $request->input('usia'),
              // "kode_kelompok_usia" => $request->input('kode_kelompok_usia'),
              "tanggal_lahir" => $tgl_lahir,
              "kode_pendidikan_akhir" => $request->input('kode_pendidikan_akhir'),
              "kode_pekerjaan" => $request->input('kode_pekerjaan'),
              "kode_warga_negara" => $request->input('kode_warga_negara'),
              "kode_peran_tersangka" => $request->input('kode_peran_tersangka'),
              "no_identitas" => $request->input('no_identitas'),
              "kode_negara" => $request->input('kode_negara'),
              "kode_jenisidentitas" => $request->input('kode_jenisidentitas'),
              // "alamatktp_idprovinsi" => $request->input('alamatktp_idprovinsi'),
              // "alamatktp_idkabkota" => $request->input('alamatktp_idkabkota'),
              "alamatktp_kodepos" => $request->input('alamatktp_kodepos'),
              "alamatdomisili" => $request->input('alamatdomisili'),
              // "alamatdomisili_idprovinsi" => $request->input('alamatdomisili_idprovinsi'),
              // "alamatdomisili_idkabkota" => $request->input('alamatdomisili_idkabkota'),
              "alamatdomisili_kodepos" => $request->input('alamatdomisili_kodepos'),
              "alamatlainnya" => $request->input('alamatlainnya'),
              // "alamatlainnya_idprovinsi" => $request->input('alamatlainnya_idprovinsi'),
              // "alamatlainnya_idkabkota" => $request->input('alamatlainnya_idkabkota'),
              "alamatlainnya_kodepos" => $request->input('alamatlainnya_kodepos'),
              "fisik_warna_rambut" => $request->input('fisik_warna_rambut'),
              "fisik_tipikal_rambut" => $request->input('fisik_tipikal_rambut'),
              "fisik_tinggi_badan" => $request->input('fisik_tinggi_badan'),
              "fisik_perawakan" => $request->input('fisik_perawakan'),
              "fisik_warna_kulit" => $request->input('fisik_warna_kulit'),
              "fisik_bentuk_mata" => $request->input('fisik_bentuk_mata'),
              "fisik_bentuk_wajah" => $request->input('fisik_bentuk_wajah'),
              "fisik_lohat_bahasa" => $request->input('fisik_lohat_bahasa'),
              "fisik_suku_ras" => $request->input('fisik_suku_ras'),
              "file_foto_tampak_depan" => $request->input('file_foto_tampak_depan'),
              "file_foto_tampak_sampingkanan" => $request->input('file_foto_tampak_sampingkanan'),
              "file_foto_tampak_sampingkiri" => $request->input('file_foto_tampak_sampingkiri'),
              // "file_laporan" => $request->input('file_laporan'),
              "id_instansi" => $request->input('id_instansi')
            ]
          ]
        );

        $inputId = "";
        $dpo = json_decode($requestDpo->getBody()->getContents(), true);

        $this->form_params = array("nomor_sprint_dpo" => $request->input('nomor_sprint_dpo'),
        "nama" => $request->input('nama'),
        "alamat" => $request->input('alamat'),
        "kode_jenis_kelamin" => $request->input('kode_jenis_kelamin'),
        "tempat_lahir" => $request->input('tempat_lahir'),
        "usia" => $request->input('usia'),
        // "kode_kelompok_usia" => $request->input('kode_kelompok_usia'),
        "tanggal_lahir" => $tgl_lahir,
        "kode_pendidikan_akhir" => $request->input('kode_pendidikan_akhir'),
        "kode_pekerjaan" => $request->input('kode_pekerjaan'),
        "kode_warga_negara" => $request->input('kode_warga_negara'),
        "kode_peran_tersangka" => $request->input('kode_peran_tersangka'),
        "no_identitas" => $request->input('no_identitas'),
        "kode_negara" => $request->input('kode_negara'),
        "kode_jenisidentitas" => $request->input('kode_jenisidentitas'),
        // "alamatktp_idprovinsi" => $request->input('alamatktp_idprovinsi'),
        // "alamatktp_idkabkota" => $request->input('alamatktp_idkabkota'),
        "alamatktp_kodepos" => $request->input('alamatktp_kodepos'),
        "alamatdomisili" => $request->input('alamatdomisili'),
        // "alamatdomisili_idprovinsi" => $request->input('alamatdomisili_idprovinsi'),
        // "alamatdomisili_idkabkota" => $request->input('alamatdomisili_idkabkota'),
        "alamatdomisili_kodepos" => $request->input('alamatdomisili_kodepos'),
        "alamatlainnya" => $request->input('alamatlainnya'),
        // "alamatlainnya_idprovinsi" => $request->input('alamatlainnya_idprovinsi'),
        // "alamatlainnya_idkabkota" => $request->input('alamatlainnya_idkabkota'),
        "alamatlainnya_kodepos" => $request->input('alamatlainnya_kodepos'),
        "fisik_warna_rambut" => $request->input('fisik_warna_rambut'),
        "fisik_tipikal_rambut" => $request->input('fisik_tipikal_rambut'),
        "fisik_tinggi_badan" => $request->input('fisik_tinggi_badan'),
        "fisik_perawakan" => $request->input('fisik_perawakan'),
        "fisik_warna_kulit" => $request->input('fisik_warna_kulit'),
        "fisik_bentuk_mata" => $request->input('fisik_bentuk_mata'),
        "fisik_bentuk_wajah" => $request->input('fisik_bentuk_wajah'),
        "fisik_lohat_bahasa" => $request->input('fisik_lohat_bahasa'),
        "fisik_suku_ras" => $request->input('fisik_suku_ras'),
        "file_foto_tampak_depan" => $request->input('file_foto_tampak_depan'),
        "file_foto_tampak_sampingkanan" => $request->input('file_foto_tampak_sampingkanan'),
        "file_foto_tampak_sampingkiri" => $request->input('file_foto_tampak_sampingkiri'),
        // "file_laporan" => $request->input('file_laporan'),
        "id_instansi" => $request->input('id_instansi'));

        $trail['audit_menu'] = 'Pemberantasan - Direktorat Penindakan dan Pengejaran - DPO';
        $trail['audit_event'] = 'post';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $dpo['comment'];
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($token,$trail);


        // $result = json_decode($requestData->getBody()->getContents(), true);
        // dd($result);
        if(($dpo['code'] == 200) && ($dpo['status'] != 'error')){
          $inputId = $dpo['data']['eventID'];

        }

        if($inputId){
          if ($request->file('file_foto_tampak_depan') != ''){
            $fileName = $inputId.'-'.$request->file('file_foto_tampak_depan')->getClientOriginalName();
            $request->file('file_foto_tampak_depan')->storeAs('penindakanDanPengejaran', $fileName);

            $requestfile = $client->request('PUT', $baseUrl.'/api/dpo/'.$inputId,
                    [
                        'headers' =>
                        [
                            'Authorization' => 'Bearer '.$token
                        ],
                        'form_params' => [
                            'file_foto_tampak_depan' => $fileName,
                        ]
                    ]
                );
            $result1 = json_decode($requestfile->getBody()->getContents(), true);
         }

         if ($request->file('file_foto_tampak_sampingkiri') != ''){
             $fileName = $inputId.'-'.$request->file('file_foto_tampak_sampingkiri')->getClientOriginalName();
             $request->file('file_foto_tampak_sampingkiri')->storeAs('penindakanDanPengejaran', $fileName);

             $requestfile = $client->request('PUT', $baseUrl.'/api/dpo/'.$inputId,
                     [
                         'headers' =>
                         [
                             'Authorization' => 'Bearer '.$token
                         ],
                         'form_params' => [
                             'file_foto_tampak_sampingkiri' => $fileName,
                         ]
                     ]
                 );
             $result2 = json_decode($requestfile->getBody()->getContents(), true);
          }

          if ($request->file('file_foto_tampak_sampingkanan') != ''){
              $fileName = $inputId.'-'.$request->file('file_foto_tampak_sampingkanan')->getClientOriginalName();
              $request->file('file_foto_tampak_sampingkanan')->storeAs('penindakanDanPengejaran', $fileName);

              $requestfile = $client->request('PUT', $baseUrl.'/api/dpo/'.$inputId,
                      [
                          'headers' =>
                          [
                              'Authorization' => 'Bearer '.$token
                          ],
                          'form_params' => [
                              'file_foto_tampak_sampingkanan' => $fileName,
                          ]
                      ]
                  );
              $result3 = json_decode($requestfile->getBody()->getContents(), true);
           }

          }
          if(($dpo['code'] == 200) && ($dpo['status'] != 'error')){
            $this->kelengkapan_PendataanDpo($inputId);
            $this->data['status'] = 'success';
            $this->data['message'] = 'Data Daftar Pencarian Orang Berhasil Ditambahkan.';
          }else{
            $this->data['status'] = 'error';
            $this->data['message'] = 'Data Daftar Pencarian Orang Gagal Ditambahkan.';
          }
         return redirect(route('pendataan_dpo'))->with('status',$this->data);

      // $client = new Client();
      // $baseUrl = URL::to('/');
      // $token = $request->session()->get('token');

      // $requestDpo = $client->request('POST', $baseUrl.'/api/dpo',
      // [
      //   'headers' =>
      //   [
      //     'Authorization' => 'Bearer '.$token
      //   ],
      //   'form_params' =>
      //   [
      //     "nomor_sprint_dpo" => $request->input('nomor_sprint_dpo'),
      //     "nama" => $request->input('nama'),
      //     "alamat" => $request->input('alamat'),
      //     "kode_jenis_kelamin" => $request->input('kode_jenis_kelamin'),
      //     "tempat_lahir" => $request->input('tempat_lahir'),
      //     "usia" => $request->input('usia'),
      //     "kode_kelompok_usia" => $request->input('kode_kelompok_usia'),
      //     "tanggal_lahir" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal_lahir')))),
      //     "kode_pendidikan_akhir" => $request->input('kode_pendidikan_akhir'),
      //     "kode_pekerjaan" => $request->input('kode_pekerjaan'),
      //     "kode_warga_negara" => $request->input('kode_warga_negara'),
      //     "kode_peran_tersangka" => $request->input('kode_peran_tersangka'),
      //     "no_identitas" => $request->input('no_identitas'),
      //     "kode_negara" => $request->input('kode_negara'),
      //     "kode_jenisidentitas" => $request->input('kode_jenisidentitas'),
      //     "alamatktp_idprovinsi" => $request->input('alamatktp_idprovinsi'),
      //     "alamatktp_idkabkota" => $request->input('alamatktp_idkabkota'),
      //     "alamatktp_kodepos" => $request->input('alamatktp_kodepos'),
      //     "alamatdomisili" => $request->input('alamatdomisili'),
      //     "alamatdomisili_idprovinsi" => $request->input('alamatdomisili_idprovinsi'),
      //     "alamatdomisili_idkabkota" => $request->input('alamatdomisili_idkabkota'),
      //     "alamatdomisili_kodepos" => $request->input('alamatdomisili_kodepos'),
      //     "alamatlainnya" => $request->input('alamatlainnya'),
      //     "alamatlainnya_idprovinsi" => $request->input('alamatlainnya_idprovinsi'),
      //     "alamatlainnya_idkabkota" => $request->input('alamatlainnya_idkabkota'),
      //     "alamatlainnya_kodepos" => $request->input('alamatlainnya_kodepos'),
      //     "fisik_warna_rambut" => $request->input('fisik_warna_rambut'),
      //     "fisik_tipikal_rambut" => $request->input('fisik_tipikal_rambut'),
      //     "fisik_tinggi_badan" => $request->input('fisik_tinggi_badan'),
      //     "fisik_perawakan" => $request->input('fisik_perawakan'),
      //     "fisik_warna_kulit" => $request->input('fisik_warna_kulit'),
      //     "fisik_bentuk_mata" => $request->input('fisik_bentuk_mata'),
      //     "fisik_bentuk_wajah" => $request->input('fisik_bentuk_wajah'),
      //     "fisik_lohat_bahasa" => $request->input('fisik_lohat_bahasa'),
      //     "fisik_suku_ras" => $request->input('fisik_suku_ras'),
      //     "file_foto_tampak_depan" => $request->input('file_foto_tampak_depan'),
      //     "file_foto_tampak_sampingkanan" => $request->input('file_foto_tampak_sampingkanan'),
      //     "file_foto_tampak_sampingkiri" => $request->input('file_foto_tampak_sampingkiri'),
      //     "file_laporan" => $request->input('file_laporan'),
      //     "id_instansi" => $request->input('id_instansi')
      //   ]
      //   ]
      //   );
      //   $dpo = json_decode($requestDpo->getBody()->getContents(), true);
      //   $inputId = $dpo['data']['eventID'];

      //   if ($request->file('file_foto_tampak_depan') != ''){
      //       $fileName = $inputId.'-'.$request->file('file_foto_tampak_depan')->getClientOriginalName();
      //       $request->file('file_foto_tampak_depan')->storeAs('penindakanDanPengejaran', $fileName);

      //       $requestfile = $client->request('PUT', $baseUrl.'/api/dpo/'.$inputId,
      //               [
      //                   'headers' =>
      //                   [
      //                       'Authorization' => 'Bearer '.$token
      //                   ],
      //                   'form_params' => [
      //                       'file_foto_tampak_depan' => $fileName,
      //                   ]
      //               ]
      //           );
      //    }

      //    if ($request->file('file_foto_tampak_sampingkiri') != ''){
      //        $fileName = $inputId.'-'.$request->file('file_foto_tampak_sampingkiri')->getClientOriginalName();
      //        $request->file('file_foto_tampak_sampingkiri')->storeAs('penindakanDanPengejaran', $fileName);

      //        $requestfile = $client->request('PUT', $baseUrl.'/api/dpo/'.$inputId,
      //                [
      //                    'headers' =>
      //                    [
      //                        'Authorization' => 'Bearer '.$token
      //                    ],
      //                    'form_params' => [
      //                        'file_foto_tampak_sampingkiri' => $fileName,
      //                    ]
      //                ]
      //            );
      //     }

      //     if ($request->file('file_foto_tampak_sampingkanan') != ''){
      //         $fileName = $inputId.'-'.$request->file('file_foto_tampak_sampingkanan')->getClientOriginalName();
      //         $request->file('file_foto_tampak_sampingkanan')->storeAs('penindakanDanPengejaran', $fileName);

      //         $requestfile = $client->request('PUT', $baseUrl.'/api/dpo/'.$inputId,
      //                 [
      //                     'headers' =>
      //                     [
      //                         'Authorization' => 'Bearer '.$token
      //                     ],
      //                     'form_params' => [
      //                         'file_foto_tampak_sampingkanan' => $fileName,
      //                     ]
      //                 ]
      //             );
      //      }

      //      $this->kelengkapan_PendataanDpo($inputId);

      //   return redirect('/pemberantasan/dir_penindakan/pendataan_dpo');
    }

    public function updatePendataanDpo(Request $request){
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');
      $client = new Client();
      $inputId = $request->input('id');
      if ($request->input('tanggal_lahir') != '') {
        $tgl_lahir = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal_lahir'))));
      } else {
        $tgl_lahir = '';
      }

      $requestDpo = $client->request('PUT', $baseUrl.'/api/dpo/'.$request->input('id'),
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token
          ],
          'form_params' =>
          [
            "nomor_sprint_dpo" => $request->input('nomor_sprint_dpo'),
            "nama" => $request->input('nama'),
            "alamat" => $request->input('alamat'),
            "kode_jenis_kelamin" => $request->input('kode_jenis_kelamin'),
            "tempat_lahir" => $request->input('tempat_lahir'),
            "usia" => $request->input('usia'),
            "kode_kelompok_usia" => $request->input('kode_kelompok_usia'),
            "tanggal_lahir" => $tgl_lahir,
            "kode_pendidikan_akhir" => $request->input('kode_pendidikan_akhir'),
            "kode_pekerjaan" => $request->input('kode_pekerjaan'),
            "kode_warga_negara" => $request->input('kode_warga_negara'),
            "kode_peran_tersangka" => $request->input('kode_peran_tersangka'),
            "no_identitas" => $request->input('no_identitas'),
            "kode_negara" => $request->input('kode_negara'),
            "kode_jenisidentitas" => $request->input('kode_jenisidentitas'),
            // "alamatktp_idprovinsi" => $request->input('alamatktp_idprovinsi'),
            // "alamatktp_idkabkota" => $request->input('alamatktp_idkabkota'),
            "alamatktp_kodepos" => $request->input('alamatktp_kodepos'),
            "alamatdomisili" => $request->input('alamatdomisili'),
            // "alamatdomisili_idprovinsi" => $request->input('alamatdomisili_idprovinsi'),
            // "alamatdomisili_idkabkota" => $request->input('alamatdomisili_idkabkota'),
            "alamatdomisili_kodepos" => $request->input('alamatdomisili_kodepos'),
            "alamatlainnya" => $request->input('alamatlainnya'),
            // "alamatlainnya_idprovinsi" => $request->input('alamatlainnya_idprovinsi'),
            // "alamatlainnya_idkabkota" => $request->input('alamatlainnya_idkabkota'),
            "alamatlainnya_kodepos" => $request->input('alamatlainnya_kodepos'),
            "fisik_warna_rambut" => $request->input('fisik_warna_rambut'),
            "fisik_tipikal_rambut" => $request->input('fisik_tipikal_rambut'),
            "fisik_tinggi_badan" => $request->input('fisik_tinggi_badan'),
            "fisik_perawakan" => $request->input('fisik_perawakan'),
            "fisik_warna_kulit" => $request->input('fisik_warna_kulit'),
            "fisik_bentuk_mata" => $request->input('fisik_bentuk_mata'),
            "fisik_bentuk_wajah" => $request->input('fisik_bentuk_wajah'),
            "fisik_lohat_bahasa" => $request->input('fisik_lohat_bahasa'),
            "fisik_suku_ras" => $request->input('fisik_suku_ras'),
            "file_foto_tampak_depan" => $request->input('file_foto_tampak_depan'),
            "file_foto_tampak_sampingkanan" => $request->input('file_foto_tampak_sampingkanan'),
            "file_foto_tampak_sampingkiri" => $request->input('file_foto_tampak_sampingkiri'),
            // "file_laporan" => $request->input('file_laporan'),
            "id_instansi" => $request->input('id_instansi')
          ]
        ]
      );

      $dpo = json_decode($requestDpo->getBody()->getContents(), true);
      // dd($result);
      // $inputId = $result['data']['eventID'];

      $this->form_params = array("nomor_sprint_dpo" => $request->input('nomor_sprint_dpo'),
      "nama" => $request->input('nama'),
      "alamat" => $request->input('alamat'),
      "kode_jenis_kelamin" => $request->input('kode_jenis_kelamin'),
      "tempat_lahir" => $request->input('tempat_lahir'),
      "usia" => $request->input('usia'),
      "kode_kelompok_usia" => $request->input('kode_kelompok_usia'),
      "tanggal_lahir" => $tgl_lahir,
      "kode_pendidikan_akhir" => $request->input('kode_pendidikan_akhir'),
      "kode_pekerjaan" => $request->input('kode_pekerjaan'),
      "kode_warga_negara" => $request->input('kode_warga_negara'),
      "kode_peran_tersangka" => $request->input('kode_peran_tersangka'),
      "no_identitas" => $request->input('no_identitas'),
      "kode_negara" => $request->input('kode_negara'),
      "kode_jenisidentitas" => $request->input('kode_jenisidentitas'),
      // "alamatktp_idprovinsi" => $request->input('alamatktp_idprovinsi'),
      // "alamatktp_idkabkota" => $request->input('alamatktp_idkabkota'),
      "alamatktp_kodepos" => $request->input('alamatktp_kodepos'),
      "alamatdomisili" => $request->input('alamatdomisili'),
      // "alamatdomisili_idprovinsi" => $request->input('alamatdomisili_idprovinsi'),
      // "alamatdomisili_idkabkota" => $request->input('alamatdomisili_idkabkota'),
      "alamatdomisili_kodepos" => $request->input('alamatdomisili_kodepos'),
      "alamatlainnya" => $request->input('alamatlainnya'),
      // "alamatlainnya_idprovinsi" => $request->input('alamatlainnya_idprovinsi'),
      // "alamatlainnya_idkabkota" => $request->input('alamatlainnya_idkabkota'),
      "alamatlainnya_kodepos" => $request->input('alamatlainnya_kodepos'),
      "fisik_warna_rambut" => $request->input('fisik_warna_rambut'),
      "fisik_tipikal_rambut" => $request->input('fisik_tipikal_rambut'),
      "fisik_tinggi_badan" => $request->input('fisik_tinggi_badan'),
      "fisik_perawakan" => $request->input('fisik_perawakan'),
      "fisik_warna_kulit" => $request->input('fisik_warna_kulit'),
      "fisik_bentuk_mata" => $request->input('fisik_bentuk_mata'),
      "fisik_bentuk_wajah" => $request->input('fisik_bentuk_wajah'),
      "fisik_lohat_bahasa" => $request->input('fisik_lohat_bahasa'),
      "fisik_suku_ras" => $request->input('fisik_suku_ras'),
      "file_foto_tampak_depan" => $request->input('file_foto_tampak_depan'),
      "file_foto_tampak_sampingkanan" => $request->input('file_foto_tampak_sampingkanan'),
      "file_foto_tampak_sampingkiri" => $request->input('file_foto_tampak_sampingkiri'),
      // "file_laporan" => $request->input('file_laporan'),
      "id_instansi" => $request->input('id_instansi'));

      $trail['audit_menu'] = 'Pemberantasan - Direktorat Penindakan dan Pengejaran - DPO';
      $trail['audit_event'] = 'put';
      $trail['audit_value'] = json_encode($this->form_params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $dpo['comment'];
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($token,$trail);


      if ($request->file('file_foto_tampak_depan') != ''){
            $fileName = $inputId.'-'.$request->file('file_foto_tampak_depan')->getClientOriginalName();
            $request->file('file_foto_tampak_depan')->storeAs('penindakanDanPengejaran', $fileName);

            $requestfile = $client->request('PUT', $baseUrl.'/api/dpo/'.$inputId,
                    [
                        'headers' =>
                        [
                            'Authorization' => 'Bearer '.$token
                        ],
                        'form_params' => [
                            'file_foto_tampak_depan' => $fileName,
                        ]
                    ]
                );
            $result1 = json_decode($requestfile->getBody()->getContents(), true);
         }

         if ($request->file('file_foto_tampak_sampingkiri') != ''){
             $fileName = $inputId.'-'.$request->file('file_foto_tampak_sampingkiri')->getClientOriginalName();
             $request->file('file_foto_tampak_sampingkiri')->storeAs('penindakanDanPengejaran', $fileName);

             $requestfile = $client->request('PUT', $baseUrl.'/api/dpo/'.$inputId,
                     [
                         'headers' =>
                         [
                             'Authorization' => 'Bearer '.$token
                         ],
                         'form_params' => [
                             'file_foto_tampak_sampingkiri' => $fileName,
                         ]
                     ]
                 );
             $result2 = json_decode($requestfile->getBody()->getContents(), true);
          }

          if ($request->file('file_foto_tampak_sampingkanan') != ''){
              $fileName = $inputId.'-'.$request->file('file_foto_tampak_sampingkanan')->getClientOriginalName();
              $request->file('file_foto_tampak_sampingkanan')->storeAs('penindakanDanPengejaran', $fileName);

              $requestfile = $client->request('PUT', $baseUrl.'/api/dpo/'.$inputId,
                      [
                          'headers' =>
                          [
                              'Authorization' => 'Bearer '.$token
                          ],
                          'form_params' => [
                              'file_foto_tampak_sampingkanan' => $fileName,
                          ]
                      ]
                  );

              $result3 = json_decode($requestfile->getBody()->getContents(), true);
              // dd($resulti);
        }
        if($dpo['code'] == 200 && $dpo['status'] != 'error'){
          $this->kelengkapan_PendataanDpo($inputId);
          $this->data['status'] = 'success';
          $this->data['message'] = 'Data Daftar Pencarian Orang Berhasil Diperbarui';
        }else{
          $this->data['status'] = 'error';
          $this->data['message'] = 'Data Daftar Pencarian Orang Gagal Diperbarui';
        }
        return back()->with('status',$this->data);

      // $client = new Client();
      // $baseUrl = URL::to('/');
      // $token = $request->session()->get('token');

      // $requestDpo = $client->request('PUT', $baseUrl.'/api/dpo/'.$request->input('id'),
      // [
      //   'headers' =>
      //   [
      //     'Authorization' => 'Bearer '.$token
      //   ],
      //   'form_params' =>
      //   [
      //     "nomor_sprint_dpo" => $request->input('nomor_sprint_dpo'),
      //     "nama" => $request->input('nama'),
      //     "alamat" => $request->input('alamat'),
      //     "kode_jenis_kelamin" => $request->input('kode_jenis_kelamin'),
      //     "tempat_lahir" => $request->input('tempat_lahir'),
      //     "usia" => $request->input('usia'),
      //     "kode_kelompok_usia" => $request->input('kode_kelompok_usia'),
      //     "tanggal_lahir" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal_lahir')))),
      //     "kode_pendidikan_akhir" => $request->input('kode_pendidikan_akhir'),
      //     "kode_pekerjaan" => $request->input('kode_pekerjaan'),
      //     "kode_warga_negara" => $request->input('kode_warga_negara'),
      //     "kode_peran_tersangka" => $request->input('kode_peran_tersangka'),
      //     "no_identitas" => $request->input('no_identitas'),
      //     "kode_negara" => $request->input('kode_negara'),
      //     "kode_jenisidentitas" => $request->input('kode_jenisidentitas'),
      //     "alamatktp_idprovinsi" => $request->input('alamatktp_idprovinsi'),
      //     "alamatktp_idkabkota" => $request->input('alamatktp_idkabkota'),
      //     "alamatktp_kodepos" => $request->input('alamatktp_kodepos'),
      //     "alamatdomisili" => $request->input('alamatdomisili'),
      //     "alamatdomisili_idprovinsi" => $request->input('alamatdomisili_idprovinsi'),
      //     "alamatdomisili_idkabkota" => $request->input('alamatdomisili_idkabkota'),
      //     "alamatdomisili_kodepos" => $request->input('alamatdomisili_kodepos'),
      //     "alamatlainnya" => $request->input('alamatlainnya'),
      //     "alamatlainnya_idprovinsi" => $request->input('alamatlainnya_idprovinsi'),
      //     "alamatlainnya_idkabkota" => $request->input('alamatlainnya_idkabkota'),
      //     "alamatlainnya_kodepos" => $request->input('alamatlainnya_kodepos'),
      //     "fisik_warna_rambut" => $request->input('fisik_warna_rambut'),
      //     "fisik_tipikal_rambut" => $request->input('fisik_tipikal_rambut'),
      //     "fisik_tinggi_badan" => $request->input('fisik_tinggi_badan'),
      //     "fisik_perawakan" => $request->input('fisik_perawakan'),
      //     "fisik_warna_kulit" => $request->input('fisik_warna_kulit'),
      //     "fisik_bentuk_mata" => $request->input('fisik_bentuk_mata'),
      //     "fisik_bentuk_wajah" => $request->input('fisik_bentuk_wajah'),
      //     "fisik_lohat_bahasa" => $request->input('fisik_lohat_bahasa'),
      //     "fisik_suku_ras" => $request->input('fisik_suku_ras'),
      //     "file_foto_tampak_depan" => $request->input('file_foto_tampak_depan'),
      //     "file_foto_tampak_sampingkanan" => $request->input('file_foto_tampak_sampingkanan'),
      //     "file_foto_tampak_sampingkiri" => $request->input('file_foto_tampak_sampingkiri'),
      //     "file_laporan" => $request->input('file_laporan'),
      //     "id_instansi" => $request->input('id_instansi')
      //   ]
      //   ]
      //   );
      //   $dpo = json_decode($requestDpo->getBody()->getContents(), true);
      //   $inputId = $request->input('id');

      //   if ($request->file('file_foto_tampak_depan') != ''){
      //       $fileName = $inputId.'-'.$request->file('file_foto_tampak_depan')->getClientOriginalName();
      //       $request->file('file_foto_tampak_depan')->storeAs('penindakanDanPengejaran', $fileName);

      //       $requestfile = $client->request('PUT', $baseUrl.'/api/dpo/'.$inputId,
      //               [
      //                   'headers' =>
      //                   [
      //                       'Authorization' => 'Bearer '.$token
      //                   ],
      //                   'form_params' => [
      //                       'file_foto_tampak_depan' => $fileName,
      //                   ]
      //               ]
      //           );
      //    }

      //    if ($request->file('file_foto_tampak_sampingkiri') != ''){
      //        $fileName = $inputId.'-'.$request->file('file_foto_tampak_sampingkiri')->getClientOriginalName();
      //        $request->file('file_foto_tampak_sampingkiri')->storeAs('penindakanDanPengejaran', $fileName);

      //        $requestfile = $client->request('PUT', $baseUrl.'/api/dpo/'.$inputId,
      //                [
      //                    'headers' =>
      //                    [
      //                        'Authorization' => 'Bearer '.$token
      //                    ],
      //                    'form_params' => [
      //                        'file_foto_tampak_sampingkiri' => $fileName,
      //                    ]
      //                ]
      //            );
      //     }

      //     if ($request->file('file_foto_tampak_sampingkanan') != ''){
      //         $fileName = $inputId.'-'.$request->file('file_foto_tampak_sampingkanan')->getClientOriginalName();
      //         $request->file('file_foto_tampak_sampingkanan')->storeAs('penindakanDanPengejaran', $fileName);

      //         $requestfile = $client->request('PUT', $baseUrl.'/api/dpo/'.$inputId,
      //                 [
      //                     'headers' =>
      //                     [
      //                         'Authorization' => 'Bearer '.$token
      //                     ],
      //                     'form_params' => [
      //                         'file_foto_tampak_sampingkanan' => $fileName,
      //                     ]
      //                 ]
      //             );
      //      }
      //      $this->kelengkapan_PendataanDpo($inputId);

      //   return redirect('/pemberantasan/dir_penindakan/pendataan_dpo');
    }

    public function deletePendataanDpo(Request $request){
        if ($request->ajax()) {
            $id = $request->id;
            if($id){
                $data_request = execute_api('api/dpo/'.$id,'DELETE');
                $this->form_params['delete_id'] = $id;
                $trail['audit_menu'] = 'Pemberantasan - Direktorat Penindakan dan Pengejaran - DPO';
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
            }else{
                $data_request = ['code'=>200,'status'=>'error','message'=>'Daftar Pencarian Orang (DPO) Gagal Dihapus'];
                return $data_request;
            }
        }
    }

    private function kelengkapan_PendataanDpo($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('berantas_dpo')->where('id',$id)
                  ->select(
                    'id_instansi',
                    'nomor_sprint_dpo',
                    'kode_jenisidentitas',
                    'no_identitas',
                    'nama',
                    'alamat',
                    'alamatktp_kodepos',
                    'alamatdomisili',
                    'alamatdomisili_kodepos',
                    'alamatlainnya',
                    'alamatlainnya_kodepos',
                    'kode_jenis_kelamin',
                    'tempat_lahir',
                    'tanggal_lahir',
                    'usia',
                    'kode_pendidikan_akhir',
                    'kode_pekerjaan',
                    'kode_warga_negara',
                    'kode_peran_tersangka',
                    'kode_negara',
                    'fisik_tinggi_badan',
                    'fisik_warna_kulit',
                    'fisik_perawakan',
                    'fisik_lohat_bahasa',
                    'fisik_warna_rambut',
                    'fisik_tipikal_rambut',
                    'fisik_bentuk_wajah',
                    'file_foto_tampak_depan',
                    'file_foto_tampak_sampingkanan',
                    'file_foto_tampak_sampingkiri');
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
          $kelengkapan = execute_api_json('api/dpo/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/dpo/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

    public function printPagePendataanDpo(Request $request){
      $client = new Client();
      $page = $request->input('page');
      $token = $request->session()->get('token');
      $baseUrl = URL::to('/');

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

      $page = $request->page;
      if($page){
        $start_number = ($request->limit * ($request->page -1 )) + 1;
      }else{
        $start_number = 1;
      }
      $segment = $request->segment;

      // $page = 'page='.$request->current_page;
      // $id_wil = $request->session()->get('wilayah');
      $inteljaringan = execute_api('/api/dpo'.$kondisi, 'GET');
      $limit = config('app.limit');
      $start_number = ($limit * ($request->page -1 )) + 1;
      $result = [];
      if(($inteljaringan['code']== 200) && ($inteljaringan['status'] != 'error')){
        $data = $inteljaringan['data'];
        if(count($data) >0){

        $i = $start_number;
              foreach($data as $key=>$d){
                  $metas = [];
                  $result[$key]['No'] = $i;
                  $result[$key]['Nomor Surat Perintah'] =$d['nomor_sprint_dpo'];
                  $result[$key]['Nomor Identitas'] = $d['no_identitas'];
                  $result[$key]['Alamat'] = $d['alamat'];
                  $result[$key]['Jenis Kelamin'] = $d['kode_jenis_kelamin'];

                  $i = $i+1;
              }
              $name = 'Pendataan DPO '.Carbon::now()->format('Y-m-d H:i:s');
              $this->printData($result, $name);
          }else{
              echo 'data tidak tersedia';
          }
      }else{
        echo 'data tidak tersedia';
      }

    }

}

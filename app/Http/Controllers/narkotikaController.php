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

use Excel;
use Storage;

class narkotikaController extends Controller
{
    public $data;
    public $selected;
    public $form_params;
    public function pendataanLKN(Request $request){
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
              }else if(($key == 'pelaksana') || ($key == 'no_lap') || ($key == 'BrgBukti') || ($key == 'status_kelengkapan') ){
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
        $pelaksana = $request->pelaksana;
        $BrgBukti = $request->BrgBukti;
        $tgl_from = $request->tgl_from;
        $tgl_to = $request->tgl_to;
        $order = $request->order;
        $limit = $request->limit;
        $status_kelengkapan = $request->status_kelengkapan;
        // dd($pelaksana);

        if($tipe == 'periode'){
          if($tgl_from){
            $date = date('Y-m-d',strtotime(str_replace('/', '-', $tgl_from)));
            $kondisi .= '&tgl_from='.$date;
            $this->selected['tgl_from'] = $tgl_from;
          }else{
              $kondisi .='';
          }
          if($tgl_to){
            $date = date('Y-m-d',strtotime(str_replace('/', '-', $tgl_to)));
            $kondisi .= '&tgl_to='.$date;
            $this->selected['tgl_to'] = $tgl_to;
          }else{
            $kondisi .='';
          }
        }elseif($tipe == 'pelaksana'){
          $kondisi .= '&pelaksana='.$pelaksana;
          $this->selected['pelaksana'] = $pelaksana;
        }elseif($tipe == 'status_kelengkapan'){
          $kondisi .= '&status_kelengkapan='.$status_kelengkapan;
          $this->selected['status_kelengkapan'] = $status_kelengkapan;
        }elseif($tipe == 'BrgBukti'){
          $kondisi .= '&BrgBukti='.$BrgBukti;
          $this->selected['BrgBukti'] = $BrgBukti;
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
      $this->data['title'] = "Pendataan LKN";
      if($request->page){
          $current_page = $request->page;
          $start_number = ($this->limit * ($request->page -1 )) + 1;
      }else{
          $current_page = 1;
          $start_number = $current_page;
      }

      $limit = 'limit='.$this->limit;
      $offset = 'page='.$current_page;
      $kondisi .='&id_wilayah='.$request->session()->get('wilayah').'&kategori=narkotika';
      $datas = execute_api('api/kasus?'.$limit.'&'.$offset.$kondisi,'get');
      // print_r($datas);
      // echo '</pre>';
      // dd($datas);
      $this->data['kondisi'] ='?'.$offset.$kondisi;
      if( ($datas['code']= 200) && ($datas['code'] != 'error')){
        $this->data['data_kasus'] = $datas['data'];
        $total_item = $datas['paginate']['totalpage'] * $this->limit;
      }else{
        $this->data['data_kasus'] = [];
        $total_item =  0;
      }

      $instansi = $this->globalinstansi($request->session()->get('wilayah'), $token);
      $this->data['instansi'] = $instansi;

      $brgbukti = execute_api('api/jnsbrgbukti','POST');
      // print_r($brgbukti);
      // exit();
      if($brgbukti['code'] == 200 && $brgbukti['status'] != 'error'){
        $this->data['brgbukti'] = $brgbukti['data'];
      }else{
        $this->data['brgbukti'] = [];
      }

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
      $this->data['title'] = "Pendataan LKN";
      $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
      $this->data['token'] = $token;
      $this->data['path'] = $request->path();
      $this->data['delete_route'] = 'delete_pendataan_lkn';
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
      $this->data['breadcrumps'] = breadcrumps_narkotika($request->route()->getName());


        // dd($request->session()->get('foto_pegawai'));
        // $client = new Client();
        // if ($request->input('page')) {
        //   $page = $request->input('page');
        // } else {
        //   $page = 1;
        // }
        // $baseUrl = URL::to('/');
        // $token = $request->session()->get('token');
        // $kasus = [];
        // try {
        //     $requestKasus = $client->request('GET', $baseUrl.'/api/kasus?page='.$page.'&id_wilayah='.$request->session()->get('wilayah').'&kategori=narkotika',
        //         [
        //             'headers' =>
        //             [
        //                 'Authorization' => 'Bearer '.$token
        //             ]
        //         ]
        //     );

        //     $kasus = json_decode($requestKasus->getBody()->getContents(), true);
        // }catch (\GuzzleHttp\Exception\GuzzleException $e) {
        //     $response = $e->getResponse();
        //     if($response){
        //       $responseBodyAsString = $response->getBody()->getContents();
        //     }else{
        //       $responseBodyAsString = (Object)['code'=>'200','status'=>'error','messages'=>'Network connection failed'];
        //     }
        // }
        // $page = [];
        // if($kasus){
        //     if($kasus['code'] == 200 && $kasus['status'] != 'error'){
        //         $page = $kasus['paginate'];
        //         $this->data['data_kasus'] = $kasus['data'];
        //     }else{
        //         $this->data['data_kasus'] =[];
        //     }
        // }else{
        //     $this->data['data_kasus'] = [];
        // }

        // $instansi = $this->globalinstansi($request->session()->get('wilayah'), $token);
        // $this->data['title'] = "kasus";
        // $this->data['token'] = $token;

        // $user_id = Auth::user()->user_id;
        // $detail = MainModel::getUserDetail($user_id);
        // $this->data['data_detail'] = $detail;
        // $this->data['path'] = $request->path();
        // $this->data['instansi'] = $instansi;
        // $this->data['page'] = $page;
        // $this->data['breadcrumps'] = breadcrumps_narkotika($request->route()->getName());
        return view('pemberantasan.narkotika.index',$this->data);
    }

    public function editPendataanLKN(Request $request){
        $id = $request->id;

        $client = new Client();

            $baseUrl = URL::to('/');
            $token = $request->session()->get('token');

            $LKN = $this->globalLkn($token, $id);

            $wilayah = $this->globalWilayah($token);

            if($LKN['data']['kasus_tkp_idprovinsi'] == "kosong" || $LKN['data']['kasus_tkp_idprovinsi'] == ""){
                $kotaKab = "kosong";
            } else {
                $requestFilterWilayah = $client->request('GET', $baseUrl.'/api/filterwilayah/'.$LKN['data']['kasus_tkp_idprovinsi'],
                    [
                        'headers' =>
                        [
                            'Authorization' => 'Bearer '.$token
                        ]
                    ]
                );
                $kotaKab = json_decode($requestFilterWilayah->getBody()->getContents(), true);
            }

            $jalur_masuk = $this->globalJalurMasuk($token);

            $instansi = $this->globalinstansi($request->session()->get('wilayah'), $token);

            $jenisKasus = $this->globalJnsKasus($token);

            $tersangka = $this->globalGetTersangka($token, $id);

            $brgBuktiNarkotika = $this->globalBuktiNarkotika($token, $id);

            $brgBuktiPrekursor = $this->globalBuktiPrekursor($token, $id);

            $brgBuktiAsetBarang = $this->globalBuktiAsetBarang($token, $id);

            $brgBuktiAsetTanah = $this->globalBuktiAsetTanah($token, $id);

            $brgBuktiAsetBangunan = $this->globalBuktiAsetBangunan($token, $id);

            $brgBuktiAsetLogam = $this->globalBuktiAsetLogam($token, $id);

            $brgBuktiAsetUang = $this->globalBuktiAsetUang($token, $id);

            $brgBuktiAsetRekening = $this->globalBuktiAsetRekening($token, $id);

            $brgBuktiAsetSurat = $this->globalBuktiAsetSurat($token, $id);

            $brgBuktiNonNarkotika = $this->globalBuktiNonNarkotika($token, $id);

            $jenisBrgBuktiNarkotika = $this->globalJenisBrgBuktiNarkotika($token);

            $jenisBrgBuktiPrekursor = $this->globalJenisBrgBuktiPrekursor($token);

            $brgBuktiAdiktif = $this->globalBuktiAdiktif($token, $id);

            $jenisBrgBuktiAdiktif = $this->globalJenisBrgBuktiAdiktif($token);

            $satuan = $this->globalSatuan($token);

            $propkab = $this->globalPropkab($token);
            try{
                $requestpenyidik = $client->request('GET', config('app.url_soa').'simpeg/penyidikbysatker?unit_id='.$LKN['data']['satker_penyidik']);
                $penyidik = json_decode($requestpenyidik->getBody()->getContents(), true);
                $this->data['penyidik'] = $penyidik;
            }catch(\Exception $e){
                $this->data['penyidik'] = [];
            }



        $this->data['jalur_masuk'] = $jalur_masuk;
        $this->data['wilayah'] = $wilayah;
        $this->data['instansi'] = $instansi;
        $this->data['jenisKasus'] = $jenisKasus;
        $this->data['data_kasus'] = $LKN;
        $this->data['propinsi'] = $wilayah;
        $this->data['kabupaten'] = $kotaKab;
        $this->data['negara'] = MainModel::getListNegara();
        $this->data['jenisKasus'] = $jenisKasus;
        $this->data['tersangka'] = $tersangka;
        $this->data['brgBuktiNonNarkotika'] = $brgBuktiNonNarkotika;
        $this->data['brgBuktiNarkotika'] = $brgBuktiNarkotika;
        $this->data['brgBuktiAsetBarang'] = $brgBuktiAsetBarang;
        $this->data['brgBuktiAsetTanah'] = $brgBuktiAsetTanah;
        $this->data['brgBuktiAsetBangunan'] = $brgBuktiAsetBangunan;
        $this->data['brgBuktiAsetUang'] = $brgBuktiAsetUang;
        $this->data['brgBuktiAsetLogam'] = $brgBuktiAsetLogam;
        $this->data['brgBuktiAsetRekening'] = $brgBuktiAsetRekening;
        $this->data['brgBuktiAsetSurat'] = $brgBuktiAsetSurat;
        $this->data['brgBuktiPrekursor'] = $brgBuktiPrekursor;
        $this->data['brgBuktiAdiktif'] = $brgBuktiAdiktif;
        $this->data['jenisBrgBuktiAdiktif'] = $jenisBrgBuktiAdiktif;
        $this->data['jenisBrgBuktiNarkotika'] = $jenisBrgBuktiNarkotika;
        $this->data['jenisBrgBuktiPrekursor'] = $jenisBrgBuktiPrekursor;
        $this->data['satuan'] = $satuan;
        $this->data['propkab'] = $propkab;

        $this->data['id'] = $id;
        $this->data['title'] = 'tersangka';
        // $this->kelengkapan_PendataanLKN($id);
        // dd($this->data);
        $this->data['breadcrumps'] = breadcrumps_narkotika($request->route()->getName());
        return view('pemberantasan.narkotika.edit_pendataanLKN',$this->data);
    }

    public function addpendataanLKN(Request $request){
      $this->data['title']="Pemberantasan";
      $client = new Client();
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');

      $instansi = $this->globalinstansi($request->session()->get('wilayah'), $token);

      $jenisKasus = $this->globalJnsKasus($token);

      $requestJalur = $client->request('GET', $baseUrl.'/api/lookup/jalur_masuk_narkotika',
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token
          ]
        ]
      );

      $jalur_masuk = json_decode($requestJalur->getBody()->getContents(), true);

      $requestpenyidik = $client->request('GET', config('app.url_soa').'simpeg/penyidikbysatker?unit_id='.$request->session()->get('satker_simpeg'));
      $penyidik = json_decode($requestpenyidik->getBody()->getContents(), true);
      $this->data['penyidik'] = $penyidik;

      $requestsatker = $client->request('GET', config('app.url_soa').'simpeg/listSatker');
      $satker = json_decode($requestsatker->getBody()->getContents(), true);
      $this->data['satker'] = $satker['data'];

      $this->data['jalur_masuk'] = $jalur_masuk;
      $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
      $this->data['jenisKasus'] = $jenisKasus;
      $this->data['propinsi'] = MainModel::getPropinsi();
      $this->data['negara'] = MainModel::getListNegara();

      // dd($jenisKasus);
      $this->data['breadcrumps'] = breadcrumps_narkotika($request->route()->getName());
      return view('pemberantasan.narkotika.add_pendataanLKN', $this->data);
    }

    public function inputPendataanLKN(Request $request){

        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        // dd($request->all());

        $client = new Client();

        //generate image base64
        if($request->hasFile('foto1')){
            $filenameWithExt = $request->file('foto1')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('foto1')->getClientOriginalExtension();
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            $path = $request->file('foto1')->storeAs('Berantas/Narkotika', $fileNameToStore);
            $image = public_path('upload/Berantas/Narkotika/'.$fileNameToStore);
            $data = file_get_contents($image);
            $image1 = base64_encode($data);
            Storage::delete('Berantas/Narkotika/'.$fileNameToStore);
        }else{
          $image1 = null;
        }

        if($request->hasFile('foto2')){
            $filenameWithExt = $request->file('foto2')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('foto2')->getClientOriginalExtension();
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            $path = $request->file('foto2')->storeAs('Berantas/Narkotika', $fileNameToStore);
            $image = public_path('upload/Berantas/Narkotika/'.$fileNameToStore);
            $data = file_get_contents($image);
            $image2 = base64_encode($data);
            Storage::delete('Berantas/Narkotika/'.$fileNameToStore);
        }else{
          $image2 = null;
        }

        if($request->hasFile('foto3')){
            $filenameWithExt = $request->file('foto3')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('foto3')->getClientOriginalExtension();
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            $path = $request->file('foto3')->storeAs('Berantas/Narkotika', $fileNameToStore);
            $image = public_path('upload/Berantas/Narkotika/'.$fileNameToStore);
            $data = file_get_contents($image);
            $image3 = base64_encode($data);
            Storage::delete('Berantas/Narkotika/'.$fileNameToStore);
        }else{
          $image3 = null;
        }

        $requestkasus = $client->request('POST', $baseUrl.'/api/kasus',
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' => [
                        // 'kasus_tanggal' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('kasus_tanggal')))),
                        'kasus_tanggal' => ( $request->input('kasus_tanggal') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('kasus_tanggal')))) : ''),
                        'id_instansi' => $request->input('pelaksana'),
                        'kasus_no' => $request->input('kasus_no'),
                        //'nama_penyidik' => $request->input('penyidik'),
                        // 'tgl_kejadian' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggalKejadian')))),
                        'tgl_kejadian' => ( $request->input('tanggalKejadian') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggalKejadian')))) : ''),
                        'kasus_tkp' => $request->input('tkp'),
                        'kasus_tkp_idprovinsi' => $request->input('propinsi'),
                        'kasus_tkp_idkabkota' => $request->input('kabupaten'),
                        'modus_operandi' => $request->input('modus'),
                        'kode_negarasumbernarkotika' => $request->input('negaraSumber'),
                        'jalur_masuk' => $request->input('jalurMasuk'),
                        'rute_asal' => $request->input('ruteAsal'),
                        'rute_transit' => $request->input('ruteTransit'),
                        'rute_tujuan' => $request->input('ruteTujuan'),
                        'kasus_jenis' => $request->input('jenisKasus'),
                        //'kasus_kelompok' => $request->input('kelompokKasus'),
                        'foto1' => $image1,
                        'foto2' => $image2,
                        'foto3' => $image3,
                        'uraian_singkat' => $request->input('uraian_singkat'),
                        'keterangan_lainnya' => $request->input('keterangan_lainnya'),
                        'meta_penyidik' => json_encode($request->input('penyidik')),
                        'satker_penyidik' => $request->input('satker'),
                        'kategori' => 'narkotika',
                        'created_by' => $request->session()->get('id'),
                        'create_date' => date("Y-m-d H:i:s"),
                    ]
                ]
            );

        $result = json_decode($requestkasus->getBody()->getContents(), true);
        $id = $result['data']['eventID'];

        if ($request->file('file_upload')){
            $fileName = date('Y-m-d').'_'.$id.'-'.$request->file('file_upload')->getClientOriginalName();
            try {
              $request->file('file_upload')->storeAs('NarkotikaKasus', $fileName);

              $requestfile = $client->request('PUT', $baseUrl.'/api/kasus/'.$id,
                     [
                         'headers' =>
                         [
                             'Authorization' => 'Bearer '.$token
                         ],
                         'form_params' => [
                             'file_upload' => $fileName,
                         ]
                     ]
                 );
            }catch(\Exception $e){
              $e->getMessage();
            }
        }

        $this->form_params = array('kasus_tanggal' => ( $request->input('kasus_tanggal') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('kasus_tanggal')))) : ''),
                                  'id_instansi' => $request->input('pelaksana'),
                                  'kasus_no' => $request->input('kasus_no'),
                                  //'nama_penyidik' => $request->input('penyidik'),
                                  // 'tgl_kejadian' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggalKejadian')))),
                                  'tgl_kejadian' => ( $request->input('tanggalKejadian') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggalKejadian')))) : ''),
                                  'kasus_tkp' => $request->input('tkp'),
                                  'kasus_tkp_idprovinsi' => $request->input('propinsi'),
                                  'kasus_tkp_idkabkota' => $request->input('kabupaten'),
                                  'modus_operandi' => $request->input('modus'),
                                  'kode_negarasumbernarkotika' => $request->input('negaraSumber'),
                                  'jalur_masuk' => $request->input('jalurMasuk'),
                                  'rute_asal' => $request->input('ruteAsal'),
                                  'rute_transit' => $request->input('ruteTransit'),
                                  'rute_tujuan' => $request->input('ruteTujuan'),
                                  'kasus_jenis' => $request->input('jenisKasus'),
                                  'foto1' => $image1,
                                  'foto2' => $image2,
                                  'foto3' => $image3,
                                  'uraian_singkat' => $request->input('uraian_singkat'),
                                  'keterangan_lainnya' => $request->input('keterangan_lainnya'),
                                  //'kasus_kelompok' => $request->input('kelompokKasus'),
                                  'meta_penyidik' => json_encode($request->input('penyidik')),
                                  'satker_penyidik' => $request->input('satker'),
                                  'kategori' => 'narkotika',
                                  'created_by' => $request->session()->get('id'),
                                  'create_date' => date("Y-m-d H:i:s"));

        $trail['audit_menu'] = 'Pemberantasan - Direktorat Narkotika - Pendataan LKN';
        $trail['audit_event'] = 'post';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $result['comment'];
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($token,$trail);

        $this->kelengkapan_PendataanLKN($id);

        $this->data['breadcrumps'] = breadcrumps_narkotika($request->route()->getName());
        return redirect('pemberantasan/dir_narkotika/edit_pendataan_lkn/'.$id);
    }

    public function updatePendataanLKN(Request $request){
        $id = $request->input('id');

        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        // dd($request->all());

        $client = new Client();
        
        //generate image base64
        if($request->hasFile('foto1')){
            $filenameWithExt = $request->file('foto1')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('foto1')->getClientOriginalExtension();
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            $path = $request->file('foto1')->storeAs('Berantas/Narkotika', $fileNameToStore);
            $image = public_path('upload/Berantas/Narkotika/'.$fileNameToStore);
            $data = file_get_contents($image);
            $image1 = base64_encode($data);
            Storage::delete('Berantas/Narkotika/'.$fileNameToStore);
//            $form_foto1 = 'foto1 => '.$image1.',';
        }else{
            $image1 = $request->input('foto1_old');
//            $form_foto1 = '';
        }

        if($request->hasFile('foto2')){
            $filenameWithExt = $request->file('foto2')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('foto2')->getClientOriginalExtension();
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            $path = $request->file('foto2')->storeAs('Berantas/Narkotika', $fileNameToStore);
            $image = public_path('upload/Berantas/Narkotika/'.$fileNameToStore);
            $data = file_get_contents($image);
            $image2 = base64_encode($data);
            Storage::delete('Berantas/Narkotika/'.$fileNameToStore);
//            $form_foto2 = 'foto2 => '.$image2.',';
        }else{
            $image2 = $request->input('foto2_old');
//            $form_foto2 = '';
        }

        if($request->hasFile('foto3')){
            $filenameWithExt = $request->file('foto3')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('foto3')->getClientOriginalExtension();
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            $path = $request->file('foto3')->storeAs('Berantas/Narkotika', $fileNameToStore);
            $image = public_path('upload/Berantas/Narkotika/'.$fileNameToStore);
            $data = file_get_contents($image);
            $image3 = base64_encode($data);
            Storage::delete('Berantas/Narkotika/'.$fileNameToStore);
//            $form_foto3 = 'foto3 => '.$image.',';
        }else{
            $image3 = $request->input('foto3_old');
//            $form_foto3 = '';
        }
        
        $query = $client->request('PUT', $baseUrl.'/api/kasus/'.$id,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ],
                'form_params' => [
                    // 'kasus_tanggal' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('kasus_tanggal')))),
                    'kasus_tanggal' => ( $request->input('kasus_tanggal') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('kasus_tanggal')))) : ''),
                    'id_instansi' => $request->input('pelaksana'),
                    'kasus_no' => $request->input('kasus_no'),
                    //'nama_penyidik' => $request->input('penyidik'),
                    // 'tgl_kejadian' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggalKejadian')))),
                    'tgl_kejadian' => ( $request->input('tanggalKejadian') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggalKejadian')))) : ''),
                    'kasus_tkp' => $request->input('tkp'),
                    'kasus_tkp_idprovinsi' => $request->input('propinsi'),
                    'kasus_tkp_idkabkota' => $request->input('kabupaten'),
                    'modus_operandi' => $request->input('modus'),
                    'kode_negarasumbernarkotika' => $request->input('negaraSumber'),
                    'jalur_masuk' => $request->input('jalurMasuk'),
                    'rute_asal' => $request->input('ruteAsal'),
                    'rute_transit' => $request->input('ruteTransit'),
                    'rute_tujuan' => $request->input('ruteTujuan'),
                    'kasus_jenis' => $request->input('jenisKasus'),
                    'foto1' => $image1,
                    'foto2' => $image2,
                    'foto3' => $image3,
                    'uraian_singkat' => $request->input('uraian_singkat'),
                    'keterangan_lainnya' => $request->input('keterangan_lainnya'),                    
                    //'kasus_kelompok' => $request->input('kelompokKasus'),
                    'meta_penyidik' => json_encode($request->input('penyidik')),
                    'updated_by' => $request->session()->get('id'),
                    'update_date' => date("Y-m-d H:i:s"),
                ]
            ]
        );
        $result = json_decode($query->getBody()->getContents(), true);

        if ($request->file('file_upload')){
            $fileName = date('Y-m-d').'_'.$id.'-'.$request->file('file_upload')->getClientOriginalName();
            try {
              $request->file('file_upload')->storeAs('NarkotikaKasus', $fileName);

              $requestfile = $client->request('PUT', $baseUrl.'/api/kasus/'.$id,
                     [
                         'headers' =>
                         [
                             'Authorization' => 'Bearer '.$token
                         ],
                         'form_params' => [
                             'file_upload' => $fileName,
                         ]
                     ]
                 );
            }catch(\Exception $e){
              $e->getMessage();
            }
        }

        $this->form_params = array('kasus_tanggal' => ( $request->input('kasus_tanggal') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('kasus_tanggal')))) : ''),
                                  'id_instansi' => $request->input('pelaksana'),
                                  'kasus_no' => $request->input('kasus_no'),
                                  //'nama_penyidik' => $request->input('penyidik'),
                                  // 'tgl_kejadian' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggalKejadian')))),
                                  'tgl_kejadian' => ( $request->input('tanggalKejadian') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggalKejadian')))) : ''),
                                  'kasus_tkp' => $request->input('tkp'),
                                  'kasus_tkp_idprovinsi' => $request->input('propinsi'),
                                  'kasus_tkp_idkabkota' => $request->input('kabupaten'),
                                  'modus_operandi' => $request->input('modus'),
                                  'kode_negarasumbernarkotika' => $request->input('negaraSumber'),
                                  'jalur_masuk' => $request->input('jalurMasuk'),
                                  'rute_asal' => $request->input('ruteAsal'),
                                  'rute_transit' => $request->input('ruteTransit'),
                                  'rute_tujuan' => $request->input('ruteTujuan'),
                                  'kasus_jenis' => $request->input('jenisKasus'),
                                  'foto1' => $image1,
                                  'foto2' => $image2,
                                  'foto3' => $image3,
                                  'uraian_singkat' => $request->input('uraian_singkat'),
                                  'keterangan_lainnya' => $request->input('keterangan_lainnya'),                                  
                                  //'kasus_kelompok' => $request->input('kelompokKasus'),
                                  'meta_penyidik' => json_encode($request->input('penyidik')),
                                  'updated_by' => $request->session()->get('id'),
                                  'update_date' => date("Y-m-d H:i:s"));

        $trail['audit_menu'] = 'Pemberantasan - Direktorat Narkotika - Pendataan LKN';
        $trail['audit_event'] = 'put';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $result['comment'];
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($token,$trail);

        $this->kelengkapan_PendataanLKN($id);
        if($result['code'] == 200 && $result['status'] != 'error'){
            $this->data['status'] = 'success';
            $this->data['message'] = 'Data Pendataan LKN Berhasil Diperbarui';
        }else{
            $this->data['status'] = 'error';
            $this->data['message'] = 'Data Pendataan LKN Gagal Diperbarui';
        }
        return back()->with('status',$this->data);
    }

     public function deletePendataanLKN(Request $request){
        if ($request->ajax()) {
            $id = $request->id;
            if($id){
                $data_request = execute_api('api/kasus/'.$id,'DELETE');
                $this->form_params['delete_id'] = $id;
                $trail['audit_menu'] = 'Pemberantasan - Direktorat Narkotika - Pendataan LKN';
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
                $data_request = ['code'=>200,'status'=>'error','message'=>'Data Kasus Gagal Dihapus'];
                return $id;
            }
        }
    }

    private function kelengkapan_PendataanLKN($id){
        $status_kelengkapan = true;
      try{
          $query_kasus = DB::table('berantas_kasus')->where('kasus_id',$id)
          ->select('kasus_tanggal','id_instansi','satker_penyidik','meta_penyidik', 'tgl_kejadian', 'kasus_tkp', 'kasus_tkp_idprovinsi', 'kasus_tkp_idkabkota','modus_operandi','kode_negarasumbernarkotika','jalur_masuk','rute_asal','rute_transit','rute_tujuan', 'kasus_jenis');

          $query_tersangka = DB::table('berantas_kasus_tersangka')->where('kasus_id',$id)->count();
          $column_tersangka = ['tersangka_nama','kode_pendidikan_akhir','kode_pekerjaan','kode_warga_negara','kode_peran_tersangka'];

          $query_brgbukti = DB::table('berantas_kasus_barang_bukti')->where('kasus_id',$id)->count();
          $column_brgbukti = ['id_brgbukti','jumlah_barang_bukti','kode_satuan_barang_bukti'];
          if($query_brgbukti > 0){
            if($query_tersangka > 0){
              if($query_kasus->count() > 0){
                $result = $query_kasus->first();
                foreach($result as $key=>$val){
                  if(!$val || $val == 'null' ){
                      $status_kelengkapan =false;
                    break;
                  }else{
                    continue;
                  }
                }
              }else{
                  $status_kelengkapan =false;
                }
              }else{
                $status_kelengkapan =false;
              }
            }else{
              $status_kelengkapan =false;
            }

          if($status_kelengkapan== true){
              $kelengkapan = execute_api_json('api/kasus/'.$id,'PUT',['status_kelengkapan'=>'Y']);
          }else{
              $kelengkapan = execute_api_json('api/kasus/'.$id,'PUT',['status_kelengkapan'=>'N']);
          }

      }catch(\Exception $e){
          $status_kelengkapan=false;
      }
    }

    public function pendataanLadangGanja(Request $request){
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
              }else if(($key == 'nomor_sprint_penyelidikan') || ($key == 'status') ){
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
        $nomor_sprint_penyelidikan = $request->nomor_sprint_penyelidikan;
        $tgl_from = $request->tgl_from;
        $tgl_to = $request->tgl_to;
        $luas_from = $request->luas_from;
        $luas_to = $request->luas_to;
        $order = $request->order;
        $limit = $request->limit;
        $status = $request->status;
        // dd($luas_from);

        if($tipe == 'tgl_penyelidikan'){
            if($tgl_from){
              $date = date('Y-m-d',strtotime(str_replace('/', '-', $tgl_from)));
              $kondisi .= '&tgl_from='.$date;
              $this->selected['tgl_from'] = $tgl_from;
            }else{
                $kondisi .='';
            }
            if($tgl_to){
              $date = date('Y-m-d',strtotime(str_replace('/', '-', $tgl_to)));
              $kondisi .= '&tgl_to='.$date;
              $this->selected['tgl_to'] = $tgl_to;
            }else{
              $kondisi .='';
            }
        }elseif($tipe == 'luas_lahan'){
            if($luas_from){
              $this->selected['luas_from'] = $luas_from;
              $kondisi .= '&luas_from='.$luas_from;
            }else{
              $kondisi .='';
            }
            if($luas_to){
              $this->selected['luas_to'] = $luas_to;
              $kondisi .= '&luas_to='.$luas_to;
            }else{
              $kondisi .='';
            }
        }elseif($tipe == 'pelaksana'){
          $kondisi .= '&pelaksana='.$pelaksana;
          $this->selected['pelaksana'] = $pelaksana;
        }elseif($tipe == 'status'){
          $kondisi .= '&status='.$status;
          $this->selected['status'] = $status;
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
      // $this->data['title'] = "Pendataan LKN";
      if($request->page){
          $current_page = $request->page;
          $start_number = ($this->limit * ($request->page -1 )) + 1;
      }else{
          $current_page = 1;
          $start_number = $current_page;
      }

        // $limit = 'limit='.$this->limit;
        // $offset = 'page='.$current_page;
        // $datas = execute_api_json('api/rikturiksus?'.$limit.'&'.$offset.$kondisi,'get');

        // $total_item = 0;
        // if($datas->code == 200){
        //     $this->data['data_riktu'] = $datas->data;
        //     $total_item = $datas->paginate->totalpage * $this->limit;
        // }else{
        //     $this->data['data'] = [];
        //     $total_item = 0;
        // }

      $limit = 'limit='.$this->limit;
      $offset = 'page='.$current_page;
      $kondisi .='&id_wilayah='.$request->session()->get('wilayah');
      $datas = execute_api('api/pemusnahanladang?'.$limit.'&'.$offset.$kondisi,'get');
      // print_r($datas);
      // echo '</pre>';
      // dd($datas);
      $this->data['kondisi'] ='?'.$offset.$kondisi;
      if( ($datas['code']= 200) && ($datas['code'] != 'error')){
        $this->data['data_pemusnahanladang'] = $datas['data'];
        $total_item = $datas['paginate']['totalpage'] * $this->limit;
      }else{
        $this->data['data_pemusnahanladang'] = [];
        $total_item =  0;
      }

      $instansi = $this->globalinstansi($request->session()->get('wilayah'), $token);
      $this->data['instansi'] = $instansi;

      // $brgbukti = execute_api('api/jnsbrgbukti','POST');
      // // print_r($brgbukti);
      // // exit();
      // if($brgbukti['code'] == 200 && $brgbukti['status'] != 'error'){
      //   $this->data['brgbukti'] = $brgbukti['data'];
      // }else{
      //   $this->data['brgbukti'] = [];
      // }

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
      $this->data['title'] = "Pemusnahan Ladang Tanaman Narkotika";
      $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
      $this->data['token'] = $token;
      $this->data['path'] = $request->path();
      $this->data['delete_route'] = 'delete_pendataan_pemusnahan_ladangganja';
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
      $this->data['breadcrumps'] = breadcrumps_narkotika($request->route()->getName());

      //     $client = new Client();
      //     $limit = config('app.limit');
      //     if ($request->page) {
      //         $page = $request->page;
      //         $current_page = $request->page;
      //         $start_number = ($limit * ($request->page -1 )) + 1;
      //     } else {
      //         $page = 1;
      //         $current_page = 1;
      //         $start_number = $current_page;
      //     }

      //     $baseUrl = URL::to('/');
      //     $token = $request->session()->get('token');
      //     $limit_page = 'limit='.$limit;
      //     $offset = 'page='.$current_page;
      //     $requestPemusnahanLadang = $client->request('GET', $baseUrl.'/api/pemusnahanladang?'.$limit_page.'&'.$offset,
      //         [
      //             'headers' =>
      //             [
      //                 'Authorization' => 'Bearer '.$token
      //             ]
      //         ]
      //     );
      //     $pemusnahanladang = json_decode($requestPemusnahanLadang->getBody()->getContents(), true);

      //     $page = $pemusnahanladang['paginate'];
      //     $this->data['data_pemusnahanladang'] = $pemusnahanladang['data'];

      //     $this->data['title'] = "Pemusnahan Ladang Ganja";
      //     $this->data['token'] = $token;
      //     $this->data['start_number'] = $start_number;

      //     $this->data['path'] = $request->path();
      //     $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
      //     $this->data['page'] = $page;

      //     $user_id = Auth::user()->user_id;
      //     $detail = MainModel::getUserDetail($user_id);
      //     $this->data['data_detail'] = $detail;
      //     $this->data['delete_route'] = 'delete_pendataan_pemusnahan_ladangganja';
      //     $this->data['breadcrumps'] = breadcrumps_narkotika($request->route()->getName());
      //     $total_item = $pemusnahanladang['paginate']['totalpage'] * $limit;
        // $this->data['pagination'] = pagination($current_page,$total_item, $limit, config('app.page_ellipsis'), "/pemberantasan/dir_narkotika/pendataan_pemusnahan_ladangganja/%d");
        return view('pemberantasan.narkotika.index_pemusnahanLadangGanja',$this->data);
    }

    public function addpendataanLadangGanja(Request $request){
        $client = new Client();
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');
        $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab',
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );

        $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);
        $this->data['propkab'] = $propkab;
        //-- dd($propkab);

        $this->data['breadcrumps'] = breadcrumps_narkotika($request->route()->getName());
        return view('pemberantasan.narkotika.add_pemusnahanLadangganja',$this->data);

    }

    public function inputPendataanLadangGanja(Request $request){
        // dd($request->all());
        // dd(date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_penyelidikan')))));
        $client = new Client();

        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $fileName = "";
        $file_message = "";
        if (  $request->file('file_laporan_kegiatan')) {
            $file       = $request->file('file_laporan_kegiatan');
            $fileName   = date('Y-m-d').'_'.date('H-i-s').'_'.$file->getClientOriginalName();
            $directory = 'Berantas/PemusnahanLadangGanja';
            try {
                $path = Storage::putFileAs($directory, $request->file('file_laporan_kegiatan'),$fileName);
                echo  $path;
                if($path){
                    $fileName = $fileName;
                }else{
                    $file_message = "Dengan File gagal diupload.";
                }
            }catch(\Exception $e){
                $file_message = "Dengan File gagal diupload.";
            }
          // $request->file('file_laporan_kegiatan_file')->move("data_pemusnahanladang/", $fileName);
        }else{
            $fileName = "";
            $file_message = "";
        }

        // dd($fileName);
        $requestInputPemusnahanLadang =$client->request('POST', $baseUrl.'/api/pemusnahanladang',
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ],
                'form_params' =>
                [
                    'nomor_sprint_penyelidikan' => $request->input('sprint'),
                    'tgl_penyelidikan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_penyelidikan')))),
                    'lokasi_idprovinsi' => "",
                    'lokasi_idkabkota' => $request->input('kota_kabupaten'),
                    'lokasi_idkecamatan' => $request->input('kecamatan'),
                    'lokasi_kelurahan' => $request->input('kelurahan'),
                    'lokasi_desadusun' => $request->input('desa'),
                    'koordinat_lat' => $request->input('koordinat_lat'),
                    'koordinat_lot' => $request->input('koordinat_lot'),
                    'luas_lahan_ganja' => $request->input('luas_lahan_ganja'),
                    'nomor_sprint_pemusnahan' => $request->input('nomor_sprint_pemusnahan'),
                    'tgl_pemusnahan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pemusnahan')))),
                    'luas_lahan_ganja_dimusnahkan' => $request->input('luas_lahan_ganja_dimusnahkan'),
                    'file_laporan_kegiatan' => $fileName
                ]
            ]
        );

        $return  = json_decode($requestInputPemusnahanLadang->getBody()->getContents(), false);

        $this->form_params = array('nomor_sprint_penyelidikan' => $request->input('sprint'),
                                  'tgl_penyelidikan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_penyelidikan')))),
                                  'lokasi_idprovinsi' => "",
                                  'lokasi_idkabkota' => $request->input('kota_kabupaten'),
                                  'lokasi_idkecamatan' => $request->input('kecamatan'),
                                  'lokasi_kelurahan' => $request->input('kelurahan'),
                                  'lokasi_desadusun' => $request->input('desa'),
                                  'koordinat_lat' => $request->input('koordinat_lat'),
                                  'koordinat_lot' => $request->input('koordinat_lot'),
                                  'luas_lahan_ganja' => $request->input('luas_lahan_ganja'),
                                  'nomor_sprint_pemusnahan' => $request->input('nomor_sprint_pemusnahan'),
                                  'tgl_pemusnahan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pemusnahan')))),
                                  'luas_lahan_ganja_dimusnahkan' => $request->input('luas_lahan_ganja_dimusnahkan'),
                                  'file_laporan_kegiatan' => $fileName);

        $trail['audit_menu'] = 'Pemberantasan - Direktorat Narkotika - Pemusnahan Ladang Tanaman Narkotika';
        $trail['audit_event'] = 'post';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $return->comment;
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($token,$trail);

        if(($return->status != 'error') && ($return->code == 200) ){
            $id = $return->data->eventID;
            $this->kelengkapan_PendataanLadangGanja($id);
            $this->data['status'] = 'success';
            $this->data['message'] = 'Data Pemusnahan Ladang Ganja Berhasil Ditambahkan. '.$file_message;
        }else{
            $this->data['status'] = 'error';
            $this->data['message'] = 'Data Pemusnahan Ladang Ganja Gagal Ditambahkan';
        }
        return redirect('/pemberantasan/dir_narkotika/pendataan_pemusnahan_ladangganja')->with('status',$this->data);

    }

    public function editpendataanLadangGanja(Request $request){
        $id = $request->id;
        $client = new Client();

        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestPemusnahanLadang = $client->request('GET', $baseUrl.'/api/pemusnahanladang/'.$id,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );

        $pemusnahan = json_decode($requestPemusnahanLadang->getBody()->getContents(), true);
        // dd($pemusnahan);
        $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab',
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );

        $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

        // dd($pemusnahan);
        $this->data['file_path'] = config('app.berantas_ladang_ganja');
        $this->data['pemusnahan'] = $pemusnahan['data'];
        $this->data['propkab'] = $propkab;
        $this->data['id'] = $id;
        $this->data['breadcrumps'] = breadcrumps_narkotika($request->route()->getName());
        return view('pemberantasan.narkotika.edit_pemusnahanladangganja', $this->data);
    }

    public function updatePendataanLadangGanja(Request $request){
        $id = $request->input('id');
        $client = new Client();

        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');
        // dd($request->all());
        $fileName = "";
        $file_message = "";
        if (  $request->file('file_laporan_kegiatan')) {
            $file       = $request->file('file_laporan_kegiatan');
            $fileName   = date('Y-m-d').'_'.date('H-i-s').'_'.$file->getClientOriginalName();
            $directory = 'Berantas/PemusnahanLadangGanja';
            try {
                $path = Storage::putFileAs($directory, $request->file('file_laporan_kegiatan'),$fileName);
                if($path){
                    $fileName = $fileName;
                }else{
                    $file_message = "Dengan File gagal diupload.";
                }
            }catch(\Exception $e){
                $file_message = "Dengan File gagal diupload.";
            }
        }else{
            $fileName = "";
            $file_message = "";
        }

        $requestpemusnahanladang = $client->request('PUT', $baseUrl.'/api/pemusnahanladang/'.$id,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ],
                'form_params' =>
                [
                  'nomor_sprint_penyelidikan' => $request->input('sprint'),
                  'tgl_penyelidikan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_penyelidikan')))),
                  'lokasi_idprovinsi' => "",
                  'lokasi_idkabkota' => $request->input('kota_kabupaten'),
                  'lokasi_idkecamatan' => $request->input('kecamatan'),
                  'lokasi_kelurahan' => $request->input('kelurahan'),
                  'lokasi_desadusun' => $request->input('desa'),
                  'koordinat_lat' => $request->input('koordinat_lat'),
                  'koordinat_lot' => $request->input('koordinat_lot'),
                  'luas_lahan_ganja' => $request->input('luas_lahan_ganja'),
                  'nomor_sprint_pemusnahan' => $request->input('nomor_sprint_pemusnahan'),
                  'tgl_pemusnahan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pemusnahan')))),
                  'luas_lahan_ganja_dimusnahkan' => $request->input('luas_lahan_ganja_dimusnahkan'),
                  'file_laporan_kegiatan' => $fileName
                ]
            ]
        );
        $return  = json_decode($requestpemusnahanladang->getBody()->getContents(), false);

        $this->form_params = array('nomor_sprint_penyelidikan' => $request->input('sprint'),
                                  'tgl_penyelidikan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_penyelidikan')))),
                                  'lokasi_idprovinsi' => "",
                                  'lokasi_idkabkota' => $request->input('kota_kabupaten'),
                                  'lokasi_idkecamatan' => $request->input('kecamatan'),
                                  'lokasi_kelurahan' => $request->input('kelurahan'),
                                  'lokasi_desadusun' => $request->input('desa'),
                                  'koordinat_lat' => $request->input('koordinat_lat'),
                                  'koordinat_lot' => $request->input('koordinat_lot'),
                                  'luas_lahan_ganja' => $request->input('luas_lahan_ganja'),
                                  'nomor_sprint_pemusnahan' => $request->input('nomor_sprint_pemusnahan'),
                                  'tgl_pemusnahan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pemusnahan')))),
                                  'luas_lahan_ganja_dimusnahkan' => $request->input('luas_lahan_ganja_dimusnahkan'),
                                  'file_laporan_kegiatan' => $fileName);

        $trail['audit_menu'] = 'Pemberantasan - Direktorat Narkotika - Pemusnahan Ladang Tanaman Narkotika';
        $trail['audit_event'] = 'put';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $return->comment;
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($token,$trail);


        $this->kelengkapan_PendataanLadangGanja($id);

        if(($return->status != 'error') && ($return->code == 200) ){
            $this->data['status'] = 'success';
            $this->data['message'] = 'Data Pemusnahan Ladang Ganja Berhasil Ditambahkan. '.$file_message;
        }else{
            $this->data['status'] = 'error';
            $this->data['message'] = 'Data Pemusnahan Ladang Ganja Gagal Ditambahkan';
        }
        return back()->with('status',$this->data);
    }

    public function deletePendataanLadangGanja(Request $request){
        if ($request->ajax()) {
            $id = $request->id;
            if($id){
                $data_request = execute_api('api/pemusnahanladang/'.$id,'DELETE');
                $this->form_params['delete_id'] = $id;
                $trail['audit_menu'] = 'Pemberantasan - Direktorat Narkotika - Pemusnahan Ladang Tanaman Narkotika';
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
                $data_request = ['code'=>200,'status'=>'error','message'=>'Data Pemusnahan Ladang Ganja Gagal Dihapus'];
                return $data_request;
            }
        }
    }

    private function kelengkapan_PendataanLadangGanja($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('berantas_narkotika_pemusnahan_ladang_ganja')->where('id',$id)
                  ->select('nomor_sprint_penyelidikan','tgl_penyelidikan','lokasi_idkabkota','lokasi_idkecamatan', 'lokasi_kelurahan', 'lokasi_desadusun', 'koordinat_lat', 'koordinat_lot', 'luas_lahan_ganja', 'nomor_sprint_pemusnahan','tgl_pemusnahan', 'luas_lahan_ganja_dimusnahkan', 'file_laporan_kegiatan');
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
          $kelengkapan = execute_api_json('api/pemusnahanladang/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/pemusnahanladang/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }


    public function printLkn(Request $request){
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

      $i = $start_number;

      $requestKasus = $client->request('GET', $baseUrl.'/api/kasus'.$kondisi,
          [
              'headers' =>
              [
                  'Authorization' => 'Bearer '.$token
              ]
          ]
      );

      $kasus = json_decode($requestKasus->getBody()->getContents(), true);

      $kasusArray = [];

      foreach ($kasus['data'] as $key => $value) {
        $kasusArray[$key]['No'] = $i;
        $kasusArray[$key]['Instansi'] = $value['instansi'];
        $kasusArray[$key]['Tanggal LKN'] = ( $value['kasus_tanggal'] ? date('d/m/Y', strtotime($value['kasus_tanggal'])) : '');
        $kasusArray[$key]['Nomor Kasus'] = $value['no_lap'];

        if ($value['tersangka'] != ''){
          $temp = [];
          foreach($value['tersangka'] as $keyTersangka => $valueTersangka){
            $temp[$keyTersangka] = $valueTersangka['tersangka_nama'].' ('.$valueTersangka['kode_jenis_kelamin'].')';
          }
          $kasusArray[$key]['Tersangka'] = implode("\n", $temp);
        } else {
          $kasusArray[$key]['Tersangka'] = '';
        }
        if ($value['tersangka'] != ''){
          $temp = [];
          foreach($value['BrgBukti'] as $keyBrgBukti => $valueBrgBukti){
            $temp[$keyBrgBukti] = $valueBrgBukti['nm_brgbukti'].' ('.$valueBrgBukti['jumlah_barang_bukti'].' '.$valueBrgBukti['nm_satuan'].')';
          }
          $kasusArray[$key]['Barang Bukti'] = implode("\n", $temp);
        } else {
          $kasusArray[$key]['Barang Bukti'] = '';
        }
        $i += 1;
      }
      // dd($kasusArray);
      $data = $kasusArray;
      $name = 'Data LKN '.$request->kategori.' '.Carbon::now()->format('Y-m-d H:i:s');
      $this->printData($data, $name);
    }

    public function printLadang(Request $request){
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

      $i = $start_number;

      $requestPemusnahanLadang = $client->request('GET', $baseUrl.'/api/pemusnahanladang'.$kondisi,
          [
              'headers' =>
              [
                  'Authorization' => 'Bearer '.$token
              ]
          ]
      );
      $pemusnahanladang = json_decode($requestPemusnahanLadang->getBody()->getContents(), true);
      // dd($pemusnahanladang);
      $ladangArray = [];

      foreach ($pemusnahanladang['data'] as $key => $value) {
        $ladangArray[$key]['No'] = $i;
        $ladangArray[$key]['Nomor Sprint'] = $value['nomor_sprint_penyelidikan'];
        $ladangArray[$key]['Tanggal Penyelidikan'] = $value['tgl_penyelidikan'];
        $ladangArray[$key]['Luas Lahan Ganja'] = $value['luas_lahan_ganja'];
        $ladangArray[$key]['Luas Lahan Dimusnahkan'] = $value['luas_lahan_ganja_dimusnahkan'];
        $i = $i +1;
      }
      // dd($ladangArray);
      $data = $ladangArray;
      $name = 'Data Pemusnahan Ladang '.Carbon::now()->format('Y-m-d H:i:s');
      $this->printData($data, $name);
    }

}

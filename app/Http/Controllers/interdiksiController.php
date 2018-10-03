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

class interdiksiController extends Controller
{
    public $data;
    public $selected;
    public $form_params;
    public function pendataanIntDpo(Request $request) {

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
        $status_kelengkapan = $request->status_kelengkapan;
        $BrgBukti = $request->BrgBukti;
        $tgl_from = $request->tgl_from;
        $tgl_to = $request->tgl_to;
        $order = $request->order;
        $limit = $request->limit;
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
          $this->selected['nm_brgbukti'] = $BrgBukti;
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

      $limit = 'limit='.$this->limit;
      $offset = 'page='.$current_page;
      // $datas = execute_api('api/kasus?'.$limit.'&'.$offset.$kondisi.'&id_wilayah='.$request->session()->get('wilayah').'&kategori=interdiksi','get');
      $kondisi .='&id_wilayah='.$request->session()->get('wilayah').'&kategori=interdiksi';
      $datas = execute_api('api/kasus?'.$limit.'&'.$offset.$kondisi,'get');
      // print_r($datas);
      // echo '</pre>';
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
      $this->data['title'] = "Pendataan LKN ";
      $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
      $this->data['token'] = $token;
      $this->data['path'] = $request->path();
      $this->data['delete_route'] = 'delete_pendataan_intdpo';
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
      $this->data['breadcrumps'] = breadcrumps_dir_interdiksi($request->route()->getName());

      //old//

      // dd($request->session()->get('foto_pegawai'));
      // $client = new Client();
      // if ($request->input('page')) {
      //   $page = $request->input('page');
      // } else {
      //   $page = 1;
      // }
      // $baseUrl = URL::to('/');
      // $token = $request->session()->get('token');


      // $requestKasus = $client->request('GET', $baseUrl.'/api/kasus?page='.$page.'&id_wilayah='.$request->session()->get('wilayah').'&kategori=interdiksi',
      //     [
      //         'headers' =>
      //         [
      //             'Authorization' => 'Bearer '.$token
      //         ]
      //     ]
      // );

      // $kasus = json_decode($requestKasus->getBody()->getContents(), true);
      // $page = $kasus['paginate'];
      // $instansi = $this->globalinstansi($request->session()->get('wilayah'), $token);

      // $this->data['data_kasus'] = $kasus['data'];

      // $this->data['title'] = "kasus";
      // $this->data['token'] = $token;

      // $user_id = Auth::user()->user_id;
      // $detail = MainModel::getUserDetail($user_id);
      // $this->data['data_detail'] = $detail;
      // $this->data['path'] = $request->path();
      // $this->data['instansi'] = $instansi;
      // $this->data['page'] = $page;
      // $this->data['breadcrumps'] = breadcrumps_dir_interdiksi($request->route()->getName());
      return view('pemberantasan.interdiksi.index_intpendataanDPO',$this->data);
    }

     public function editPendataanIntDpo(Request $request)
     {
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

           $satuan = $this->globalSatuan($token);

           $propkab = $this->globalPropkab($token);

           $requestpenyidik = $client->request('GET', config('app.url_soa').'simpeg/penyidikbysatker?unit_id='.$LKN['data']['satker_penyidik']);
           $penyidik = json_decode($requestpenyidik->getBody()->getContents(), true);
           $this->data['penyidik'] = $penyidik;


           $brgBuktiAdiktif = $this->globalBuktiAdiktif($token, $id);
           $jenisBrgBuktiAdiktif = $this->globalJenisBrgBuktiAdiktif($token);
           $this->data['brgBuktiAdiktif'] = $brgBuktiAdiktif;
           $this->data['jenisBrgBuktiAdiktif'] = $jenisBrgBuktiAdiktif;

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
           $this->data['jenisBrgBuktiNarkotika'] = $jenisBrgBuktiNarkotika;
           $this->data['jenisBrgBuktiPrekursor'] = $jenisBrgBuktiPrekursor;
           $this->data['satuan'] = $satuan;
           $this->data['propkab'] = $propkab;
    // dd($brgBuktiAsetBarang);
           $this->data['id'] = $id;
           $this->data['title'] = 'tersangka';
           $this->kelengkapan_PendataanIntDpo($id);
           // dd($this->data);
           $this->data['breadcrumps'] = breadcrumps_dir_interdiksi($request->route()->getName());
       return view('pemberantasan.interdiksi.edit_intpendataanDPO',$this->data);
    }

    public function addpendataanIntDpo(Request $request)
    {
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
      $this->data['breadcrumps'] = breadcrumps_dir_interdiksi($request->route()->getName());
      return view('pemberantasan.interdiksi.add_intpendataanDPO', $this->data);
    }

    public function inputPendataanIntDpo(Request $request)
    {
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');

      // dd($request->all());

      $client = new Client();

      $requestkasus = $client->request('POST', $baseUrl.'/api/kasus',
              [
                  'headers' =>
                  [
                      'Authorization' => 'Bearer '.$token
                  ],
                  'form_params' => [
                      // 'kasus_tanggal' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggalLKN')))),
                      'kasus_tanggal' => ( $request->input('tanggalLKN') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggalLKN')))) : ''),
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
                      'meta_penyidik' => json_encode($request->input('penyidik')),
                      'satker_penyidik' => $request->input('satker'),
                      'kategori' => 'interdiksi',
                  ]
              ]
          );

      $result = json_decode($requestkasus->getBody()->getContents(), true);
      $id = $result['data']['eventID'];

      if ($request->file('file_upload')){
          $fileName = date('Y-m-d').'_'.$id.'-'.$request->file('file_upload')->getClientOriginalName();
          try {
            $request->file('file_upload')->storeAs('InterdiksiKasus', $fileName);

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

      $this->form_params = array('kasus_tanggal' => ( $request->input('tanggalLKN') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggalLKN')))) : ''),
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
      'meta_penyidik' => json_encode($request->input('penyidik')),
      'satker_penyidik' => $request->input('satker'),
      'kategori' => 'interdiksi');

      $trail['audit_menu'] = 'Pemberantasan - Direktorat Interdiksi - Pendataan LKN';
      $trail['audit_event'] = 'post';
      $trail['audit_value'] = json_encode($this->form_params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $result['comment'];
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($token,$trail);


      // $this->kelengkapan_PendataanIntDpo($id);

      $this->data['breadcrumps'] = breadcrumps_dir_interdiksi($request->route()->getName());
      return redirect('pemberantasan/dir_interdiksi/edit_pendataan_intdpo/'.$id);
    }

    public function updatePendataanIntDpo(Request $request)
    {
      $id = $request->input('id');

      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');

      // dd($request->all());

      $client = new Client();

      $requestkasus = $client->request('PUT', $baseUrl.'/api/kasus/'.$id,
              [
                  'headers' =>
                  [
                      'Authorization' => 'Bearer '.$token
                  ],
                  'form_params' => [
                      // 'kasus_tanggal' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggalLKN')))),
                      'kasus_tanggal' => ( $request->input('tanggalLKN') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggalLKN')))) : ''),
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
                      'meta_penyidik' => json_encode($request->input('penyidik')),
                  ]
              ]
          );

          $result = json_decode($requestkasus->getBody()->getContents(), true);

          if ($request->file('file_upload')){
              $fileName = date('Y-m-d').'_'.$id.'-'.$request->file('file_upload')->getClientOriginalName();
              try {
                $request->file('file_upload')->storeAs('InterdiksiKasus', $fileName);

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

          $this->form_params = array('kasus_tanggal' => ( $request->input('tanggalLKN') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggalLKN')))) : ''),
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
          'meta_penyidik' => json_encode($request->input('penyidik')));

          $trail['audit_menu'] = 'Pemberantasan - Direktorat Interdiksi - Pendataan LKN';
          $trail['audit_event'] = 'put';
          $trail['audit_value'] = json_encode($this->form_params);
          $trail['audit_url'] = $request->url();
          $trail['audit_ip_address'] = $request->ip();
          $trail['audit_user_agent'] = $request->userAgent();
          $trail['audit_message'] = $result['comment'];
          $trail['created_at'] = date("Y-m-d H:i:s");
          $trail['created_by'] = $request->session()->get('id');

          $qtrail = $this->inputtrail($token,$trail);

      $this->kelengkapan_PendataanIntDpo($id);

        return redirect('/pemberantasan/dir_interdiksi/pendataan_intdpo');
    }

    public function deletePendataanIntDpo(Request $request){
        if ($request->ajax()) {
            $id = $request->id;
            if($id){
                $data_request = execute_api('api/kasus/'.$id,'DELETE');
                $this->form_params['delete_id'] = $id;
                $trail['audit_menu'] = 'Pemberantasan - Direktorat Interdiksi - Pendataan LKN';
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
                return $data_request;
            }
        }
    }

    private function kelengkapan_PendataanIntDpo($id){
      $status_kelengkapan = true;
      try{
          $query_kasus = DB::table('berantas_kasus')->where('kasus_id',$id)
          ->select('kasus_tanggal','id_instansi','satker_penyidik','meta_penyidik', 'tgl_kejadian', 'kasus_tkp', 'kasus_tkp_idprovinsi', 'kasus_tkp_idkabkota', 'modus_operandi', 'kode_negarasumbernarkotika','jalur_masuk', 'rute_asal', 'rute_transit','rute_tujuan','kasus_jenis');

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

    // private function kelengkapan_PendataanIntDpo($id){
    //   $status_kelengkapan = true;
    //   try{
    //     $query = DB::table('berantas_kasus')->where('kasus_id',$id)
    //               ->select('kasus_tanggal','id_instansi','satker_penyidik','meta_penyidik', 'tgl_kejadian', 'kasus_tkp', 'kasus_tkp_idprovinsi', 'kasus_tkp_idkabkota', 'modus_operandi', 'kode_negarasumbernarkotika','jalur_masuk', 'rute_asal', 'rute_transit','rute_tujuan','kasus_jenis');
    //     if($query->count() > 0 ){
    //       $result = $query->first();
    //       foreach($result as $key=>$val){
    //           if(!$val){
    //             $status_kelengkapan=false;
    //             break;
    //           }else{
    //             continue;
    //           }
    //         }
    //       }
    //     if($status_kelengkapan== true){
    //       $kelengkapan = execute_api_json('api/kasus/'.$id,'PUT',['status_kelengkapan'=>'Y']);
    //     }else{
    //       $kelengkapan = execute_api_json('api/kasus/'.$id,'PUT',['status_kelengkapan'=>'N']);
    //     }
    //   }catch(\Exception $e){
    //     $status_kelengkapan=false;
    //   }
    // }

}

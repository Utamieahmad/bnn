<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\MainModel;
use App\Tr_BrgBukti;
use App\Models\BrgBukti;
use App\Models\Berantas\ViewKasusTersangka as VTersangka;
use App\Models\Berantas\ViewKasusBrgBukti as VBrgBukti;
use URL;
use DateTime;
use Carbon\Carbon;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class psikotropikaController extends Controller
{
  public $data;
  public $selected;
  public $form_params;

  public function PsipendataanLKN(Request $request){
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
      // $datas = execute_api('api/kasus?'.$limit.'&'.$offset.$kondisi.'&id_wilayah='.$request->session()->get('wilayah').'&kategori=psikotropika' ,'get');
      $kondisi .='&id_wilayah='.$request->session()->get('wilayah').'&kategori=psikotropika';
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
      $this->data['title'] = "Pendataan LKN";
      $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
      $this->data['token'] = $token;
      $this->data['path'] = $request->path();
      $this->data['delete_route'] = 'delete_psi_pendataan_lkn';
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
      $this->data['breadcrumps'] = breadcrumps_psikotropika($request->route()->getName());

      //old//

    // $client = new Client();
    // if ($request->input('page')) {
    //   $page = $request->input('page');
    // } else {
    //   $page = 1;
    // }
    // $baseUrl = URL::to('/');
    // $token = $request->session()->get('token');


    // $requestKasus = $client->request('GET', $baseUrl.'/api/kasus?page='.$page.'&id_wilayah='.$request->session()->get('wilayah').'&kategori=psikotropika',
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
    // $this->data['breadcrumps'] = breadcrumps_psikotropika($request->route()->getName());
  return view('pemberantasan.psikotropika.index_pendataanLKN',$this->data);
}

public function addPsipendataanLKN(Request $request){

  $this->data['title']="Pemberantasan";
  $client = new Client();

  $baseUrl = URL::to($this->urlapi());
//  $baseUrl = URL::to('/');
  $token = $request->session()->get('token');

  $requestWilayah = $client->request('GET', $baseUrl.'/api/propinsi',
  [
    'headers' =>
    [
      'Authorization' => 'Bearer '.$token
    ]
  ]
  );

  $wilayah = json_decode($requestWilayah->getBody()->getContents(), true);

  $requestJenisKasus = $client->request('POST', $baseUrl.'/api/jnsbrgbukti',
  [
    'headers' =>
    [
      'Authorization' => 'Bearer '.$token
    ]
  ]
  );

  $jenisKasus = json_decode($requestJenisKasus->getBody()->getContents(), true);

  $requestJalur_masuk = $client->request('GET', $baseUrl.'/api/lookup/jalur_masuk_narkotika',
  [
    'headers' =>
    [
      'Authorization' => 'Bearer '.$token
    ]
  ]
  );

  $jenisKasus = $this->globalJnsKasus($token);
  $this->data['jenisKasus'] = $jenisKasus;

  $jalur_masuk = json_decode($requestJalur_masuk->getBody()->getContents(), true);

  $requestpenyidik = $client->request('GET', config('app.url_soa').'simpeg/penyidikbysatker?unit_id='.$request->session()->get('satker_simpeg'));
  $penyidik = json_decode($requestpenyidik->getBody()->getContents(), true);
  $this->data['penyidik'] = $penyidik;

  $requestsatker = $client->request('GET', config('app.url_soa').'simpeg/listSatker');
  $satker = json_decode($requestsatker->getBody()->getContents(), true);
  $this->data['satker'] = $satker['data'];

  $this->data['jalur_masuk'] = $jalur_masuk;
  $this->data['wilayah'] = $wilayah;
  $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
  $this->data['jenisKasus'] = $jenisKasus;
  $this->data['propinsi'] = MainModel::getPropinsi();
  $this->data['negara'] = MainModel::getListNegara();
  // dd($this->data['instansi']);
  $this->data['breadcrumps'] = breadcrumps_psikotropika($request->route()->getName());
  return view('pemberantasan.psikotropika.add_psi_pendataanLKN', $this->data);
}

public function inputPsiPendataanLKN(Request $request){
  $baseUrl = URL::to($this->urlapi());
//  $baseUrl = URL::to('/');
  $token = $request->session()->get('token');

  // dd($request->all());

  $client = new Client();

  $requestbody = $client->request('POST', $baseUrl.'/api/kasus',
          [
              'headers' =>
              [
                  'Authorization' => 'Bearer '.$token
              ],
              'form_params' => [
                  // 'kasus_tanggal' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggalLKN')))),
                  // 'kasus_tanggal' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('kasus_tanggal')))),
                  'kasus_tanggal' => ( $request->input('kasus_tanggal') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('kasus_tanggal')))) : ''),
                  'id_instansi' => $request->input('pelaksana'),
                  'kasus_no' => $request->input('kasus_no'),
                  // 'nama_penyidik' => $request->input('penyidik'),
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
                  // 'kasus_kelompok' => $request->input('kelompokKasus'),
                  'meta_penyidik' => json_encode($request->input('penyidik')),
                  'satker_penyidik' => $request->input('satker'),
                  'kategori' => 'psikotropika',
              ]
          ]
      );

  $result = json_decode($requestbody->getBody()->getContents(), true);
  $id = $result['data']['eventID'];

  if ($request->file('file_upload')){
      $fileName = date('Y-m-d').'_'.$id.'-'.$request->file('file_upload')->getClientOriginalName();
      try {
        $request->file('file_upload')->storeAs('PsikotropikaKasus', $fileName);

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
  // 'nama_penyidik' => $request->input('penyidik'),
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
  // 'kasus_kelompok' => $request->input('kelompokKasus'),
  'meta_penyidik' => json_encode($request->input('penyidik')),
  'satker_penyidik' => $request->input('satker'),
  'kategori' => 'psikotropika');

  $trail['audit_menu'] = 'Pemberantasan - Direktorat Psikotropika dan Prekursor - Pendataan LKN';
  $trail['audit_event'] = 'post';
  $trail['audit_value'] = json_encode($this->form_params);
  $trail['audit_url'] = $request->url();
  $trail['audit_ip_address'] = $request->ip();
  $trail['audit_user_agent'] = $request->userAgent();
  $trail['audit_message'] = $result['comment'];
  $trail['created_at'] = date("Y-m-d H:i:s");
  $trail['created_by'] = $request->session()->get('id');

  $qtrail = $this->inputtrail($token,$trail);

  // dd($result);
  // exit();
  $this->data['breadcrumps'] = breadcrumps_psikotropika($request->route()->getName());
  // $this->kelengkapan_PsiPendataanLKN($id);
  return redirect('pemberantasan/dir_psikotropika/edit_psi_pendataan_lkn/'.$id);
}

public function editPsiPendataanLKN(Request $request){
  $id = $request->id;

  $client = new Client();

  $baseUrl = URL::to($this->urlapi());
//  $baseUrl = URL::to('/');
  $token = $request->session()->get('token');

  $requestLKN = $client->request('GET', $baseUrl.'/api/kasus/'.$id,
  [
    'headers' =>
    [
      'Authorization' => 'Bearer '.$token
    ]
  ]
  );

  $LKN = json_decode($requestLKN->getBody()->getContents(), true);

  $requestWilayah = $client->request('GET', $baseUrl.'/api/propinsi',
  [
    'headers' =>
    [
      'Authorization' => 'Bearer '.$token
    ]
  ]
  );

  $wilayah = json_decode($requestWilayah->getBody()->getContents(), true);
  // dd($LKN);
  if($LKN['data']['kasus_tkp_idprovinsi'] == null){
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

  $requestInstansi = $client->request('POST', $baseUrl.'/api/instansi',
    [
      'headers' =>
      [
        'Authorization' => 'Bearer '.$token
      ]
    ]
  );

  $instansi = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
  $requestJalur_masuk = $client->request('GET', $baseUrl.'/api/lookup/jalur_masuk_narkotika',
    [
      'headers' =>
      [
        'Authorization' => 'Bearer '.$token
      ]
    ]
  );

  $jalur_masuk = json_decode($requestJalur_masuk->getBody()->getContents(), true);

  $requestJenisKasus = $client->request('POST', $baseUrl.'/api/jnsbrgbukti',
    [
      'headers' =>
      [
        'Authorization' => 'Bearer '.$token
      ]
    ]
  );

  $jenisKasus = json_decode($requestJenisKasus->getBody()->getContents(), true);

  $requestTersangka = $client->request('GET', $baseUrl.'/api/gettersangka/'.$id,
      [
          'headers' =>
          [
              'Authorization' => 'Bearer '.$token
          ]
      ]
  );

  $tersangka = json_decode($requestTersangka->getBody()->getContents(), true);

  $requestBrgBukttiNarkotika = $client->request('GET', $baseUrl.'/api/getbbnarkotika/'.$id,
      [
          'headers' =>
          [
              'Authorization' => 'Bearer '.$token
          ]
      ]
  );

  $brgBuktiNarkotika = json_decode($requestBrgBukttiNarkotika->getBody()->getContents(), true);

  $requestBrgBukttiPrekursor = $client->request('GET', $baseUrl.'/api/getbbprekursor/'.$id,
      [
          'headers' =>
          [
              'Authorization' => 'Bearer '.$token
          ]
      ]
  );

  $brgBuktiPrekursor = json_decode($requestBrgBukttiPrekursor->getBody()->getContents(), true);

  $requestBrgBukttiAsetBarang = $client->request('POST', $baseUrl.'/api/getbbaset/'.$id,
      [
          'headers' =>
          [
              'Authorization' => 'Bearer '.$token
          ],
          'form_params' =>
          [
            'jenis' => ''
          ]
      ]
  );

  $brgBuktiAsetBarang = json_decode($requestBrgBukttiAsetBarang->getBody()->getContents(), true);

  $requestBrgBukttiAsetTanah = $client->request('POST', $baseUrl.'/api/getbbaset/'.$id,
      [
          'headers' =>
          [
              'Authorization' => 'Bearer '.$token
          ],
          'form_params' =>
          [
            'jenis' => 'ASET_TANAH'
          ]
      ]
  );

  $brgBuktiAsetTanah = json_decode($requestBrgBukttiAsetTanah->getBody()->getContents(), true);

  $requestBrgBukttiAsetBangunan = $client->request('POST', $baseUrl.'/api/getbbaset/'.$id,
      [
          'headers' =>
          [
              'Authorization' => 'Bearer '.$token
          ],
          'form_params' =>
          [
            'jenis' => 'ASET_BANGUNAN'
          ]
      ]
  );

  $brgBuktiAsetBangunan = json_decode($requestBrgBukttiAsetBangunan->getBody()->getContents(), true);

  $requestBrgBukttiAsetLogam = $client->request('POST', $baseUrl.'/api/getbbaset/'.$id,
      [
          'headers' =>
          [
              'Authorization' => 'Bearer '.$token
          ],
          'form_params' =>
          [
            'jenis' => 'ASET_LOGAMMULIA'
          ]
      ]
  );

  $brgBuktiAsetLogam = json_decode($requestBrgBukttiAsetLogam->getBody()->getContents(), true);

  $requestBrgBukttiAsetUang = $client->request('POST', $baseUrl.'/api/getbbaset/'.$id,
      [
          'headers' =>
          [
              'Authorization' => 'Bearer '.$token
          ],
          'form_params' =>
          [
            'jenis' => 'ASET_UANGTUNAI'
          ]
      ]
  );

  $brgBuktiAsetUang = json_decode($requestBrgBukttiAsetUang->getBody()->getContents(), true);

  $requestBrgBukttiAsetRekening = $client->request('POST', $baseUrl.'/api/getbbaset/'.$id,
      [
          'headers' =>
          [
              'Authorization' => 'Bearer '.$token
          ],
          'form_params' =>
          [
            'jenis' => 'ASET_REKENING'
          ]
      ]
  );

  $brgBuktiAsetRekening = json_decode($requestBrgBukttiAsetRekening->getBody()->getContents(), true);

  $requestBrgBukttiAsetSurat = $client->request('POST', $baseUrl.'/api/getbbaset/'.$id,
      [
          'headers' =>
          [
              'Authorization' => 'Bearer '.$token
          ],
          'form_params' =>
          [
            'jenis' => 'ASET_SURATBERHARGA'
          ]
      ]
  );

  $brgBuktiAsetSurat = json_decode($requestBrgBukttiAsetSurat->getBody()->getContents(), true);

  $requestBrgBuktiNonNarkotika = $client->request('GET', $baseUrl.'/api/getbbnonnarkotika/'.$id,
      [
          'headers' =>
          [
              'Authorization' => 'Bearer '.$token
          ]
      ]
  );

  $brgBuktiNonNarkotika = json_decode($requestBrgBuktiNonNarkotika->getBody()->getContents(), true);

  $requestJenisBrgBuktiNarkotika = $client->request('POST', $baseUrl.'/api/jnsbrgbukti',
      [
          'headers' =>
          [
              'Authorization' => 'Bearer '.$token
          ],
          'form_params' => [
              'jenis' => [
                '01', '02'
              ]
          ]
      ]
  );

  $jenisBrgBuktiNarkotika = json_decode($requestJenisBrgBuktiNarkotika->getBody()->getContents(), true);

  $requestJenisBrgBuktiPrekursor = $client->request('POST', $baseUrl.'/api/jnsbrgbukti',
      [
          'headers' =>
          [
              'Authorization' => 'Bearer '.$token
          ],
          'form_params' => [
              'jenis' => [
                '06'
              ]
          ]
      ]
  );

  $jenisBrgBuktiPrekursor = json_decode($requestJenisBrgBuktiPrekursor->getBody()->getContents(), true);

  $requestSatuan = $client->request('GET', $baseUrl.'/api/getsatuan',
      [
          'headers' =>
          [
              'Authorization' => 'Bearer '.$token
          ]
      ]
  );

  $satuan = json_decode($requestSatuan->getBody()->getContents(), true);

  $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab',
      [
          'headers' =>
          [
              'Authorization' => 'Bearer '.$token
          ]
      ]
  );

  $requestpenyidik = $client->request('GET', config('app.url_soa').'simpeg/penyidikbysatker?unit_id='.$LKN['data']['satker_penyidik']);
  $penyidik = json_decode($requestpenyidik->getBody()->getContents(), true);
  $this->data['penyidik'] = $penyidik;

  $jenisKasus = $this->globalJnsKasus($token);
  $this->data['jenisKasus'] = $jenisKasus;


  $brgBuktiAdiktif = $this->globalBuktiAdiktif($token, $id);
  $jenisBrgBuktiAdiktif = $this->globalJenisBrgBuktiAdiktif($token);
  $this->data['brgBuktiAdiktif'] = $brgBuktiAdiktif;
  $this->data['jenisBrgBuktiAdiktif'] = $jenisBrgBuktiAdiktif;

  $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);
 // dd($brgBuktiNarkotika);
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
  $this->data['id'] = $id;

  $this->kelengkapan_PsiPendataanLKN($id);

  // dd($this->data);
  $this->data['breadcrumps'] = breadcrumps_psikotropika($request->route()->getName());
  return view('pemberantasan.psikotropika.edit_psi_pendataanLKN',$this->data);
}

public function updatePsiPendataanLKN(Request $request){
  $id = $request->input('id');

  $baseUrl = URL::to($this->urlapi());
//  $baseUrl = URL::to('/');
  $token = $request->session()->get('token');

  // dd($request->all());

  $client = new Client();

  $requestbody = $client->request('PUT', $baseUrl.'/api/kasus/'.$id,
  [
    'headers' =>
    [
      'Authorization' => 'Bearer '.$token
    ],
    'form_params' => [
      // 'kasus_tanggal' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggalLKN')))),
      // 'kasus_tanggal' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('kasus_tanggal')))),
      'kasus_tanggal' => ( $request->input('kasus_tanggal') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('kasus_tanggal')))) : ''),
      'id_instansi' => $request->input('pelaksana'),
      'kasus_no' => $request->input('kasus_no'),
      'nama_penyidik' => $request->input('penyidik'),
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

$result = json_decode($requestbody->getBody()->getContents(), true);

if ($request->file('file_upload')){
    $fileName = date('Y-m-d').'_'.$id.'-'.$request->file('file_upload')->getClientOriginalName();
    try {
      $request->file('file_upload')->storeAs('PsikotropikaKasus', $fileName);

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
// 'nama_penyidik' => $request->input('penyidik'),
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
// 'kasus_kelompok' => $request->input('kelompokKasus'),
'meta_penyidik' => json_encode($request->input('penyidik')),
'satker_penyidik' => $request->input('satker'),
'kategori' => 'psikotropika');

$trail['audit_menu'] = 'Pemberantasan - Direktorat Psikotropika dan Prekursor - Pendataan LKN';
$trail['audit_event'] = 'put';
$trail['audit_value'] = json_encode($this->form_params);
$trail['audit_url'] = $request->url();
$trail['audit_ip_address'] = $request->ip();
$trail['audit_user_agent'] = $request->userAgent();
$trail['audit_message'] = $result['comment'];
$trail['created_at'] = date("Y-m-d H:i:s");
$trail['created_by'] = $request->session()->get('id');

$qtrail = $this->inputtrail($token,$trail);

  $this->kelengkapan_PsiPendataanLKN($id);
  return redirect('pemberantasan/dir_psikotropika/psi_pendataan_lkn');
}

public function deletePsiPendataanLKN(Request $request){
        if ($request->ajax()) {
            $id = $request->id;
            if($id){
                $data_request = execute_api('api/kasus/'.$id,'DELETE');
                $this->form_params['delete_id'] = $id;
                $trail['audit_menu'] = 'Pemberantasan - Direktorat Psikotropika dan Prekursor - Pendataan LKN';
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
                $data_request = ['code'=>200,'status'=>'error','message'=>'Data Pendataan LKN Gagal Dihanpus'];
                return $data_request;
            }
        }
    }

private function kelengkapan_PsiPendataanLKN($id){
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

// private function kelengkapan_PsiPendataanLKN($id){
//   $status_kelengkapan = true;
//   try{
//     $query = DB::table('berantas_kasus' && 'berantas_kasus_tersangka' && 'berantas_kasus_barang_bukti')->where('kasus_id',$id)
//               ->select('kasus_tanggal','id_instansi','satker_penyidik','meta_penyidik', 'tgl_kejadian', 'kasus_tkp', 'kasus_tkp_idprovinsi', 'kasus_tkp_idkabkota','modus_operandi','kode_negarasumbernarkotika','jalur_masuk','rute_asal','rute_transit','rute_tujuan', 'kasus_jenis','tersangka_nama','kode_pendidikan_akhir','kode_pekerjaan','kode_warga_negara','kode_peran_tersangka','id_brgbukti','jumlah_barang_bukti','kode_satuan_barang_bukti');
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

public function printPagePsiPendataanLKN(Request $request){

        $page = $request->page;
        if($page){
            $page = $page;
        }else{
            $page = 1;
        }
        $result = [];
        $this->limit = config('app.limit');
        $start_number = ($this->limit * ($page -1 )) + 1;
        $url = 'api/kasus?page='.$page;

        $data_request = execute_api($url,'GET');

        $i = $start_number;
        if(count($data_request)>=1){
          $data = $data_request['data'];
          foreach($data as $key=>$value){
            $result[$key]['No'] = $i;
            $result[$key]['Instansi'] = $value['id_instansi'];
            $result[$key]['Lokasi'] = $value['lokasi'];
            $result[$key]['Satker'] =$value['kode_satker'];
            $result[$key]['Pejabat yang Diganti'] =$value['pejabat_diganti'];
            $result[$key]['Pejabat yang Baru'] =$value['pejabat_baru'];
            $result[$key]['Tgl Laporan'] = ( $value['tgl_laporan'] ? date('d/m/Y',strtotime($value['tgl_laporan'])) : '');

            $i = $i +1;
          }
        $name = 'Narkotia_LKN '.date('Y-m-d H:i:s');

            $this->printData($result, $name);
         //     echo '<pre>';
          // print_r($result);
        }else{
          return false;
        }

    }

public function downloadPsiPendataanLKN(Request $request){
      $i = 1;
      $response = array();
      // dd($request->all());
      $kasusqry = DB::table('v_berantas_kasus');
      if ($request->date_from != '') {
          $kasusqry->where('kasus_tanggal', '>=', date('Y-m-d', strtotime(str_replace('/', '-', $request->date_from))));
      }
      if ($request->date_to != '' ) {
          $kasusqry->where('kasus_tanggal', '<=', date('Y-m-d', strtotime(str_replace('/', '-', $request->date_to))));
      }

      $kasusqry  = $kasusqry->where(function ($query) {
              $query->where('kategori', '=', 'psikotropika')->orWhere('kategori', '=', null);
          });

      $kasus = $kasusqry->orderBy('kasus_tanggal', 'desc')->get();

      foreach ($kasus as $row) {
        $data['eventID']              = $row->kasus_id;
        $data['kasus_tanggal']        = $row->kasus_tanggal;
        $data['instansi']             = $row->nm_instansi;
        $data['no_lap']               = $row->kasus_no;
        $data['meta_penyidik']        = $row->meta_penyidik;
        $data['tgl_kejadian']         = $row->tgl_kejadian;
        $data['tkp']                  = $row->kasus_tkp;
        $data['propinsi']             = $row->propinsi;
        $data['kabupaten']            = $row->kabupaten;
        $data['modus_operandi']       = $row->modus_operandi;
        $data['nm_kasus_negara']      = $row->nm_kasus_negara;
        $data['jalur_masuk']          = $row->jalur_masuk;
        $data['rute_asal']            = $row->rute_asal;
        $data['rute_transit']         = $row->rute_transit;
        $data['rute_tujuan']          = $row->rute_tujuan;
        $data['kasus_jenis']          = $row->nm_jnskasus;
        $data['kelompok']             = $row->nm_brgbukti;
        $data['uraian_singkat']       = $row->uraian_singkat;
        $data['keterangan_lainnya']   = $row->keterangan_lainnya;
        $data['status_kelengkapan']   = $row->status_kelengkapan;

        $data['tersangka']  = VTersangka::select('tersangka_id', 'tersangka_nama', 'kode_jenis_kelamin', 'no_identitas', 'nama_negara', 'tersangka_tempat_lahir', 'tersangka_tanggal_lahir')->where('kasus_id', $row->kasus_id)->get();

        $data['BrgBukti']   = VBrgBukti::select('kasus_barang_bukti_id', 'nm_brgbukti', 'jumlah_barang_bukti', 'nm_satuan', 'keterangan')->where('kasus_id', $row->kasus_id)->get();

        array_push($response, $data);
      }


      $kasusArray = [];

      foreach ($response as $key => $value) {
        $kasusArray[$key]['No'] = $i;
        $kasusArray[$key]['Tanggal LKN'] = ( $value['kasus_tanggal'] ? date('d/m/Y', strtotime($value['kasus_tanggal'])) : '');
        $kasusArray[$key]['Instansi'] = $value['instansi'];
        $kasusArray[$key]['Nomor Kasus'] = $value['no_lap'];

        $meta = json_decode($value['meta_penyidik'],true);
        if(count($meta)){
          foreach($meta as $mm){
              $penyidikArray[$key]['ssr'][] = $mm['nama_penyidik'];
          }
          // dd($penyidikArray[$key]['ssr']);
          $kasusArray[$key]['Penyidik'] = implode("\n", $penyidikArray[$key]['ssr']);
        } else {
          $kasusArray[$key]['Penyidik'] = '';
        }

        $kasusArray[$key]['Tanggal Kejadian'] = ( $value['tgl_kejadian'] ? date('d/m/Y', strtotime($value['tgl_kejadian'])) : '');
        $kasusArray[$key]['TKP'] = $value['tkp'];
        $kasusArray[$key]['Propinsi'] = $value['propinsi'];
        $kasusArray[$key]['Kabupaten'] = $value['kabupaten'];
        $kasusArray[$key]['Modus Operandi'] = $value['modus_operandi'];
        $kasusArray[$key]['Negara Sumber'] = $value['nm_kasus_negara'];
        $kasusArray[$key]['Jalur Masuk'] = $value['jalur_masuk'];
        $kasusArray[$key]['Rute Asal'] = $value['rute_asal'];
        $kasusArray[$key]['Rute Transit'] = $value['rute_transit'];
        $kasusArray[$key]['Rute Tujuan'] = $value['rute_tujuan'];
        $kasusArray[$key]['Jenis Kasus'] = $value['kasus_jenis'];
        $kasusArray[$key]['Uraian Singkat'] = $value['uraian_singkat'];
        $kasusArray[$key]['Keterangan Lainnya'] = $value['keterangan_lainnya'];

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
      $name = 'Export Data LKN Psikotropika '.Carbon::now()->format('Y-m-d H:i:s');
      $this->printData($data, $name);

    }

// public function printPsiLkn(Request $request){
//   $client = new Client();
//   $page = $request->input('page');
//   $token = $request->session()->get('token');
//   $baseUrl = URL::to('/');

//   $requestKasus = $client->request('GET', $baseUrl.'/api/kasus?page='.$page,
//       [
//           'headers' =>
//           [
//               'Authorization' => 'Bearer '.$token
//           ]
//       ]
//   );

//   $kasus = json_decode($requestKasus->getBody()->getContents(), true);

//   $kasusArray = $kasus['data'];

//   foreach ($kasus['data'] as $key => $value) {
//     foreach($value['tersangka'] as $keyTersangka => $valueTersangka){
//       $kasusArray[$key]['tersangka'][$keyTersangka] = $valueTersangka['tersangka_nama'].' ('.$valueTersangka['kode_jenis_kelamin'].')';
//     }
//     foreach($value['BrgBukti'] as $keyBrgBukti => $valueBrgBukti){
//       $kasusArray[$key]['BrgBukti'][$keyBrgBukti] = $valueBrgBukti['nm_brgbukti'].' ('.$valueBrgBukti['jumlah_barang_bukti'].' '.$valueBrgBukti['nm_satuan'].')';
//     }
//     $kasusArray[$key]['tersangka'] = implode("\n", $kasusArray[$key]['tersangka']);
//     $kasusArray[$key]['BrgBukti'] = implode("\n", $kasusArray[$key]['BrgBukti']);
//   }
//   // dd($kasusArray);
//   $data = $kasusArray;
//   $name = 'Data LKN '.Carbon::now()->format('Y-m-d H:i:s');
//   $this->printData($data, $name);
// }

}

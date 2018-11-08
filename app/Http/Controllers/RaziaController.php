<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use URL;
use DateTime;
use Carbon\Carbon;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use Excel;
use Storage;

class RaziaController extends Controller
{
    public $data;
    public $selected;
    public $form_params;

    public function pendataanRazia(Request $request){
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
              }else if(($key == 'lokasi') ){
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
        $lokasi = $request->lokasi;
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
        // }elseif($tipe == 'lokasi'){
        //   $kondisi .= '&lokasi='.$lokasi;
        //   $this->selected['lokasi'] = $lokasi;
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

      if($request->page){
          $current_page = $request->page;
          $start_number = ($this->limit * ($request->page -1 )) + 1;
      }else{
          $current_page = 1;
          $start_number = $current_page;
      }

      $limit = 'limit='.$this->limit;
      $offset = 'page='.$current_page;
      // $kondisi .='&id_wilayah='.$request->session()->get('wilayah').'&kategori=narkotika';
      $datas = execute_api('api/berantasrazia?'.$limit.'&'.$offset.$kondisi,'get');
      // print_r($datas);
      // echo '</pre>';
      // dd($datas);
      $this->data['kondisi'] ='?'.$offset.$kondisi;
      if( ($datas['code']= 200) && ($datas['code'] != 'error')){
        $this->data['data_razia'] = $datas['data'];
        $total_item = $datas['paginate']['totalpage'] * $this->limit;
      }else{
        $this->data['data_razia'] = [];
        $total_item =  0;
      }

      $this->data['current_page'] = $current_page;
      $this->data['start_number'] = $start_number;
      $this->data['title'] = "Pendataan Razia";
      $this->data['token'] = $token;
      $this->data['path'] = $request->path();
      $this->data['delete_route'] = 'delete_razia';
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
      $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'),config('app.url').$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
      $this->data['breadcrumps'] = breadcrumps_razia($request->route()->getName());

      return view('pemberantasan.razia.index',$this->data);
    }

    public function editPendataanRazia(Request $request){
        $id = $request->id;

        $client = new Client();

            $baseUrl = URL::to($this->urlapi());

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
        $this->data['data_razia'] = $LKN;
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

    public function addPendataanRazia(Request $request){
      $this->data['title']="pemberantasan";
      $client = new Client();
      $baseUrl = URL::to($this->urlapi());

      $token = $request->session()->get('token');

      $this->data['breadcrumps'] = breadcrumps_razia($request->route()->getName());
      return view('pemberantasan.razia.add_pendataanRazia', $this->data);
    }

    public function inputPendataanRazia(Request $request){

        $baseUrl = URL::to($this->urlapi());

        $token = $request->session()->get('token');

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

        $requestrazia = $client->request('POST', $baseUrl.'/api/berantasrazia',
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' => [
                        'tgl_razia' => ( $request->input('tgl_razia') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_razia')))) : ''),
                        'lokasi' => $request->input('lokasi'),
                        'jumlah_dirazia' => $request->input('jumlah_dirazia'),
                        'jumlah_terindikasi' => $request->input('jumlah_terindikasi'),
                        'jumlah_ditemukan' => $request->input('jumlah_ditemukan'),
                        'foto1' => $image1,
                        'foto2' => $image2,
                        'foto3' => $image3,
                        'uraian_singkat' => $request->input('uraian_singkat'),
                        'keterangan_lainnya' => $request->input('keterangan_lainnya'),
                    ]
                ]
            );

        $result = json_decode($requestrazia->getBody()->getContents(), true);
        $id = $result['data']['eventID'];

        $this->form_params = array('tgl_razia' => ( $request->input('tgl_razia') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_razia')))) : ''),
                        'jumlah_dirazia' => $request->input('jumlah_dirazia'),
                        'jumlah_terindikasi' => $request->input('jumlah_terindikasi'),
                        'jumlah_ditemukan' => $request->input('jumlah_ditemukan'),
                        'foto1' => $image1,
                        'foto2' => $image2,
                        'foto3' => $image3,
                        'uraian_singkat' => $request->input('uraian_singkat'),
                        'keterangan_lainnya' => $request->input('keterangan_lainnya'),);

        $trail['audit_menu'] = 'Pemberantasan - Pendataan Razia';
        $trail['audit_event'] = 'post';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $result['comment'];
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($token,$trail);

        if($result['code'] == 200 && $result['status'] != 'error'){
            $this->data['status'] = 'success';
            $this->data['message'] = 'Data Razia Berhasil Disimpan';
        }else{
            $this->data['status'] = 'error';
            $this->data['message'] = 'Data Razia Gagal Disimpan';
        }
        $this->data['breadcrumps'] = breadcrumps_razia($request->route()->getName());
        return redirect('pemberantasan/razia')->with('status',$this->data);
    }

    public function updatePendataanRazia(Request $request){
        $id = $request->input('id');

        $baseUrl = URL::to($this->urlapi());

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
            $image1 = $request->input('foto1_old');

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
            $image2 = $request->input('foto2_old');

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
            $image3 = $request->input('foto3_old');

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

    public function deletePendataanRazia(Request $request){
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
                $data_request = ['code'=>200,'status'=>'error','message'=>'Data Razia Gagal Dihapus'];
                return $id;
            }
        }
    }

    public function printPendataanRazia(Request $request){
      $client = new Client();
      $page = $request->input('page');
      $token = $request->session()->get('token');
      $baseUrl = URL::to($this->urlapi());

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

}

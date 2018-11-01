<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\MainModel;
use App\RapatKerjaPemetaan;
use URL;
use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use Excel;

class MasyarakatController extends Controller
{
    /* @author : Daniel Andi */

    public $data;
    public $limit;
    public $selected = [];
    public function pendataanTesNarkoba(Request $request){
      $kondisi = "";
      $current_page = "";
      if($request->limit) {
        $this->limit = $request->limit;
      } else {
        $this->limit = config('app.limit');
      }
      if ($request->input('page')) {
        $current_page = $request->input('page');
        $start_number = ($this->limit * ($request->page -1 )) + 1;
      } else {
        $current_page = 1;
        $start_number = $current_page;
      }
      /*filter */
      $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
      $sasaran = execute_api('/api/lookup/sasaran','GET');
      if( ($sasaran['code'] == 200) && ( $sasaran ['status'] != 'error')){
        $this->data['sasaran']=$sasaran['data'];
      }else{
        $this->data['sasaran']= [];
      }


      $tipe = $request->tipe;
      $tgl_to = $request->tgl_to;
      $tgl_from = $request->tgl_from;
      $jml_from = $request->jml_from;
      $jml_to = $request->jml_to;
      $sasaran = $request->sasaran;
      $kode_anggaran = $request->kode_anggaran;
      $status = $request->status;
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

      /*end filter*/
      $limit = 'limit='.$this->limit;
      $offset = 'page='.$current_page;
      $kondisi .='&id_wilayah='.$request->session()->get('wilayah');
      $requestTesNarkoba  =  execute_api('api/tesnarkobaheader?'.$offset.'&'.$limit.$kondisi,"GET");

      if($requestTesNarkoba ['code'] == 200 && $requestTesNarkoba ['status'] != 'error'){
         $this->data['data_tes'] = $requestTesNarkoba['data'];
         $total_item = $requestTesNarkoba['paginate']['totalpage'] * $this->limit;
      }else{
         $this->data['data_tes'] = [];
         $total_item = 0;
      }
      $filtering = false;
      if($kondisi){
        $filter = $kondisi.'&'.$limit;
        $filtering = true;
        $this->data['kondisi'] = '?'.$offset.$kondisi.'&'.$limit;
      }else{
        $filter = '/';
        $filtering = false;
        $this->data['kondisi'] = $current_page;
      }

      $this->data['title'] = "Pendataan Tes Narkoba";
      $this->data['current_page'] = $current_page;
      $this->data['route'] = $request->route()->getName();
      $user_id = Auth::user()->user_id;
      $detail = MainModel::getUserDetail($user_id);
      $this->data['data_detail'] = $detail;
      $this->data['start_number'] = $start_number;
      $this->data['path'] = $request->path();
      $this->data['delete_route'] = 'delete_pendataan_tes_narkoba';
      $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
      $this->data['breadcrumps'] = breadcrumps_dir_masyarakat($request->route()->getName());
      return view('pemberdayaan.masyarakat.tes_narkoba.index_pendataanTesnarkoba',$this->data);
    }

    public function addpendataanTesNarkoba(Request $request){
      $client = new Client();
      $baseUrl = URL::to('/');

      $sasaran = execute_api('/api/lookup/sasaran','GET');
      if( ($sasaran['code'] == 200) && ( $sasaran ['status'] != 'error')){
        $this->data['sasaran']=$sasaran['data'];
      }else{
        $this->data['sasaran']= [];
      }
      $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
      // $this->data['sasaran'] = $sasaran['data'];
      $this->data['title']="tesnarkobaheader";


      $user_id = Auth::user()->user_id;
      $detail = MainModel::getUserDetail($user_id);
      $data['data_detail'] = $detail;
      $this->data['breadcrumps'] = breadcrumps_dir_masyarakat($request->route()->getName());
      return view('pemberdayaan.masyarakat.tes_narkoba.add_pendataanTesnarkoba',$this->data);
    }

    public function editpendataanTesNarkoba(Request $request){
     $id = $request->id;
     $client = new Client();
     $baseUrl = URL::to($this->urlapi());
//     $baseUrl = URL::to('/');
     $token = $request->session()->get('token');

     $requestTest= $client->request('GET', $baseUrl.'/api/tesnarkobaheader/'.$id,
         [
             'headers' =>
             [
                 'Authorization' => 'Bearer '.$token
             ]
         ]
     );

     $test = json_decode($requestTest->getBody()->getContents(), true);
     if( ($test['code'] == 200) && ($test['status'] != 'error')){
        $this->data['data_tes'] = $test;
        if ($test['data']['anggaran_id']) {
          $data_anggaran = $this->globalGetAnggaran($token, $test['data']['anggaran_id']);
          if($data_anggaran['code'] == 200 && $data_anggaran['status'] != 'error'){
            $this->data['data_anggaran'] = $data_anggaran['data'];
          }else{
            $this->data['data_anggaran'] = [];
          }
        }else{
          $this->data['data_anggaran'] = [];
        }
     }else{
        $this->data['data_tes'] = [];
     }



     $requestPeserta= $client->request('GET', $baseUrl.'/api/tespeserta/'.$test['data']['header_id'],
         [
             'headers' =>
             [
                 'Authorization' => 'Bearer '.$token
             ]
         ]
     );

     $peserta = json_decode($requestPeserta->getBody()->getContents(), true);
     if(($peserta['code'] == 200) && ($peserta['status'] != 'error') ){
      $this->data['peserta'] = $peserta;
     }else{
      $this->data['peserta'] = [];
     }


      $sasaran = execute_api('/api/lookup/sasaran','GET');
      if( ($sasaran['code'] == 200) && ( $sasaran ['status'] != 'error')){
        $this->data['sasaran']=$sasaran['data'];
      }else{
        $this->data['sasaran']= [];
      }
     $jenisBrgBuktiNarkotika = $this->globalJenisBrgBuktiNarkotika($token);

     $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
     $this->data['title']="Ubah Pendataan Tes Narkoba";
     $this->data['titledel'] = "tesnarkobapeserta";



     $this->data['negara'] = MainModel::getListNegara();
     $this->data['jenisBrgBuktiNarkotika'] = $jenisBrgBuktiNarkotika;
     $this->data['token'] = $token;
     $this->data['id'] = $id;
     $this->data['breadcrumps'] = breadcrumps_dir_masyarakat($request->route()->getName());
     return view('pemberdayaan.masyarakat.tes_narkoba.edit_pendataanTesnarkoba',$this->data);
    }

    public function inputPendataanTesNarkoba(Request $request){

       $baseUrl = URL::to($this->urlapi());
//       $baseUrl = URL::to('/');
       $token = $request->session()->get('token');

       // dd($request->all());

       $client = new Client();
       
       //generate image base64
        if($request->hasFile('foto1')){
            $filenameWithExt = $request->file('foto1')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('foto1')->getClientOriginalExtension();
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            $path = $request->file('foto1')->storeAs('Pemberdayaan/DirektoratPeranSertaMasyarakat/TesNarkoba', $fileNameToStore);
            $image = public_path('upload/Pemberdayaan/DirektoratPeranSertaMasyarakat/TesNarkoba/'.$fileNameToStore);
            $data = file_get_contents($image);
            $image1 = base64_encode($data);
            Storage::delete('Pemberdayaan/DirektoratPeranSertaMasyarakat/TesNarkoba/'.$fileNameToStore);
        }else{
          $image1 = null;
        }

        if($request->hasFile('foto2')){
            $filenameWithExt = $request->file('foto2')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('foto2')->getClientOriginalExtension();
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            $path = $request->file('foto2')->storeAs('Pemberdayaan/DirektoratPeranSertaMasyarakat/TesNarkoba', $fileNameToStore);
            $image = public_path('upload/Pemberdayaan/DirektoratPeranSertaMasyarakat/TesNarkoba/'.$fileNameToStore);
            $data = file_get_contents($image);
            $image2 = base64_encode($data);
            Storage::delete('Pemberdayaan/DirektoratPeranSertaMasyarakat/TesNarkoba/'.$fileNameToStore);
        }else{
          $image2 = null;
        }

        if($request->hasFile('foto3')){
            $filenameWithExt = $request->file('foto3')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('foto3')->getClientOriginalExtension();
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            $path = $request->file('foto3')->storeAs('Pemberdayaan/DirektoratPeranSertaMasyarakat/TesNarkoba', $fileNameToStore);
            $image = public_path('upload/Pemberdayaan/DirektoratPeranSertaMasyarakat/TesNarkoba/'.$fileNameToStore);
            $data = file_get_contents($image);
            $image3 = base64_encode($data);
            Storage::delete('Pemberdayaan/DirektoratPeranSertaMasyarakat/TesNarkoba/'.$fileNameToStore);
        }else{
          $image3 = null;
        }
       
       $form_params = [
           'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_tes')))),
           'id_instansi' => $request->input('idpelaksana'),
           'tgl_test' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_tes')))),
           'kode_sasaran_test' => $request->input('sasaran'),
           'jmlh_peserta' => $request->input('jumlah_peserta'),
           'no_surat_permohonan' => $request->input('no_surat_permohonan'),
           'kodesumberanggaran' => $request->input('kodesumberanggaran'),
           'lokasi' => $request->input('lokasi'),
           'jmlh_positif' => $request->input('jmlh_positif'),           
           'keterangan_lainnya' => $request->input('keterangan_lainnya'),           
           'foto1' => $image1,
           'foto2' => $image2,
           'foto3' => $image3,
           'status' => 'N',
       ];
       if ($request->input('kodesumberanggaran')=="DIPA") {
           $requestAnggaran = $client->request('POST', $baseUrl.'/api/anggaran',
                   [
                       'headers' =>
                       [
                           'Authorization' => 'Bearer '.$token
                       ],
                       'form_params' => [
                           'kode_anggaran' => $request->input('akode_anggaran'),
                           'sasaran' => $request->input('asasaran'),
                           'pagu' => $request->input('apagu'),
                           'target_output' => $request->input('atarget_output'),
                           'satuan_output' => $request->input('asatuan_output'),
                           'tahun' => $request->input('atahun'),
                           'satker_code' => $request->input('asatker_code'),
                           'refid_anggaran' => $request->input('arefid_anggaran'),

                       ]
                   ]
               );

            $resultAnggaran = json_decode($requestAnggaran->getBody()->getContents(), true);
            if(($resultAnggaran['code'] == 200) && ($resultAnggaran['status'] != 'error') ){
              $anggaran = $resultAnggaran['data']['eventID'];
              $form_params['anggaran_id'] = $anggaran;
            }else{
              $anggaran = "";
            }
        } else {
          $anggaran = '';
        }


       $requestTes = $client->request('POST', $baseUrl.'/api/tesnarkobaheader',
             [
                 'headers' =>
                 [
                     'Authorization' => 'Bearer '.$token
                 ],
                 'form_params' => $form_params

             ]
         );

       $result = json_decode($requestTes->getBody()->getContents(), true);

       $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Peran Serta Masyarakat - Tes Narkoba';
       $trail['audit_event'] = 'post';
       $trail['audit_value'] = json_encode($form_params);
       $trail['audit_url'] = $request->url();
       $trail['audit_ip_address'] = $request->ip();
       $trail['audit_user_agent'] = $request->userAgent();
       $trail['audit_message'] = $result['comment'];
       $trail['created_at'] = date("Y-m-d H:i:s");
       $trail['created_by'] = $request->session()->get('id');

       $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

       if(($result['code'] == 200) && ($result['status'] != 'error')){
          $id = $result['data']['eventID'];
          return redirect('pemberdayaan/dir_masyarakat/edit_pendataan_tes_narkoba/'.$id);
       }else{
          $this->data['status'] = 'error';
          $this->data['messages'] = 'Data Pendataan Tes Narkoba Gagal Ditambahkan';
          return redirect('pemberdayaan/dir_masyarakat/pendataan_tes_narkoba/')->with('status',$this->data);
       }
    }

    public function deletePendataanTesNarkoba(Request $request){
      $id = $request->input('id');
      if($id){
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/tesnarkobaheader/'.$id,'DELETE');
            $this->form_params['delete_id'] = $id;
            $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Peran Serta Masyarakat - Tes Narkoba';
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
        $data_request = ['status'=>'error','message'=>'Data Tes Narkoba Gagal Dihapus'];
        return $data_request;
      }
    }
    public function updatePendataanTesNarkoba(Request $request){
       $id = $request->input('id');

        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');        

        $client = new Client();
        
        //generate image base64
        if($request->hasFile('foto1')){
            $filenameWithExt = $request->file('foto1')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('foto1')->getClientOriginalExtension();
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            $path = $request->file('foto1')->storeAs('Pemberdayaan/DirektoratPeranSertaMasyarakat/TesNarkoba', $fileNameToStore);
            $image = public_path('upload/Pemberdayaan/DirektoratPeranSertaMasyarakat/TesNarkoba/'.$fileNameToStore);
            $data = file_get_contents($image);
            $image1 = base64_encode($data);
            Storage::delete('Pemberdayaan/DirektoratPeranSertaMasyarakat/TesNarkoba/'.$fileNameToStore);
        }else{
          $image1 = $request->input('foto1_old');
        }

        if($request->hasFile('foto2')){
            $filenameWithExt = $request->file('foto2')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('foto2')->getClientOriginalExtension();
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            $path = $request->file('foto2')->storeAs('Pemberdayaan/DirektoratPeranSertaMasyarakat/TesNarkoba', $fileNameToStore);
            $image = public_path('upload/Pemberdayaan/DirektoratPeranSertaMasyarakat/TesNarkoba/'.$fileNameToStore);
            $data = file_get_contents($image);
            $image2 = base64_encode($data);
            Storage::delete('Pemberdayaan/DirektoratPeranSertaMasyarakat/TesNarkoba/'.$fileNameToStore);
        }else{
          $image2 = $request->input('foto2_old');
        }

        if($request->hasFile('foto3')){
            $filenameWithExt = $request->file('foto3')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('foto3')->getClientOriginalExtension();
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            $path = $request->file('foto3')->storeAs('Pemberdayaan/DirektoratPeranSertaMasyarakat/TesNarkoba', $fileNameToStore);
            $image = public_path('upload/Pemberdayaan/DirektoratPeranSertaMasyarakat/TesNarkoba/'.$fileNameToStore);
            $data = file_get_contents($image);
            $image3 = base64_encode($data);
            Storage::delete('Pemberdayaan/DirektoratPeranSertaMasyarakat/TesNarkoba/'.$fileNameToStore);
        }else{
          $image3 = $request->input('foto3_old');
        }
        
        if ($request->input('kodesumberanggaran')=="DIPA") {
          if($request->kd_anggaran){
             $requestAnggaran = $client->request('POST', $baseUrl.'/api/anggaran',
                     [
                         'headers' =>
                         [
                             'Authorization' => 'Bearer '.$token
                         ],
                         'form_params' => [
                             'kode_anggaran' => $request->input('akode_anggaran'),
                             'sasaran' => $request->input('asasaran'),
                             'pagu' => $request->input('apagu'),
                             'target_output' => $request->input('atarget_output'),
                             'satuan_output' => $request->input('asatuan_output'),
                             'tahun' => $request->input('atahun'),
                             'satker_code' => $request->input('asatker_code'),
                             'refid_anggaran' => $request->input('arefid_anggaran'),

                         ]
                     ]
                 );

             $resultAnggaran = json_decode($requestAnggaran->getBody()->getContents(), true);
             if($resultAnggaran['code'] == 200 && $resultAnggaran['status'] != 'error'){
              $anggaran = $resultAnggaran['data']['eventID'];
             }else{
              $anggaran ="";
             }
          }else{
            $anggaran ="";
          }
        } else {
          $anggaran = '';
        }

        $form_params = [
          'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_tes')))),
          'id_instansi' => $request->input('idpelaksana'),
          'tgl_test' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_tes')))),
          'kode_sasaran_test' => $request->input('sasaran'),
          'jmlh_peserta' => $request->input('jumlah_peserta'),
          'no_surat_permohonan' => $request->input('no_surat_permohonan'),
          'kodesumberanggaran' => $request->input('kodesumberanggaran'),
          'lokasi' => $request->input('lokasi'),
          'jmlh_positif' => $request->input('jmlh_positif'),           
          'keterangan_lainnya' => $request->input('keterangan_lainnya'),           
          'foto1' => $image1,
          'foto2' => $image2,
          'foto3' => $image3,
          'anggaran_id' => $anggaran,
        ];
        $requestTes = $client->request('PUT', $baseUrl.'/api/tesnarkobaheader/'.$id,      [
              'headers' =>
              [
                  'Authorization' => 'Bearer '.$token
              ],
              'form_params' => $form_params
        ]
       );

        $result = json_decode($requestTes->getBody()->getContents(), true);

        $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Peran Serta Masyarakat - Tes Narkoba';
        $trail['audit_event'] = 'put';
        $trail['audit_value'] = json_encode($form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $result['comment'];
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

        if(( $result['code'] == 200) &&  ($result['status'] != 'error') ){
          $this->data['status'] = 'success';
          $this->data['messages'] = 'Data Tes Narkoba Berhasil Diperbarui';
        }else{
          $this->data['status'] = 'error';
          $this->data['messages'] = 'Data Tes Narkoba Gagal Diperbarui';
        }
        return back()->with('status',$this->data);
    }

    public function inputPeserta(Request $request){
       $id = $request->input('id');
       $baseUrl = URL::to($this->urlapi());
//       $baseUrl = URL::to('/');
       $token = $request->session()->get('token');
       $client = new Client();

       $requestPeserta = $client->request('POST', $baseUrl.'/api/tesnarkobapeserta',
               [
                   'headers' =>
                   [
                       'Authorization' => 'Bearer '.$token
                   ],
                   'form_params' => [
                       'header_id' => $id,
                       'kode_jenis_identitas' => $request->input('kode_jenis_identitas'),
                       'no_identitas' => $request->input('no_identitas'),
                       'peserta_inisial' => $request->input('peserta_inisial'),
                       'kode_jenis_kelamin' => $request->input('kode_jenis_kelamin'),
                       'peserta_tempat_lahir' => $request->input('peserta_tempat_lahir'),
                       'peserta_tanggal_lahir' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('peserta_tanggal_lahir')))),
                       'peserta_usia' => $request->input('peserta_usia'),
                       'kode_pendidikan_akhir' => $request->input('kode_pendidikan_akhir'),
                       'kode_pekerjaan' => $request->input('kode_pekerjaan'),
                       'kode_warga_negara' => $request->input('kode_warga_negara'),
                       'kode_negara' => $request->input('kode_negara'),
                       'id_brgbukti' => $request->input('id_brgbukti'),
                   ]
               ]
           );

       $result = json_decode($requestPeserta->getBody()->getContents(), true);
       // dd($result);

       $this->form_params = array('header_id' => $id,
       'kode_jenis_identitas' => $request->input('kode_jenis_identitas'),
       'no_identitas' => $request->input('no_identitas'),
       'peserta_inisial' => $request->input('peserta_inisial'),
       'kode_jenis_kelamin' => $request->input('kode_jenis_kelamin'),
       'peserta_tempat_lahir' => $request->input('peserta_tempat_lahir'),
       'peserta_tanggal_lahir' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('peserta_tanggal_lahir')))),
       'peserta_usia' => $request->input('peserta_usia'),
       'kode_pendidikan_akhir' => $request->input('kode_pendidikan_akhir'),
       'kode_pekerjaan' => $request->input('kode_pekerjaan'),
       'kode_warga_negara' => $request->input('kode_warga_negara'),
       'kode_negara' => $request->input('kode_negara'),
       'id_brgbukti' => $request->input('id_brgbukti'));

       $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Peran Serta Masyarakat - Tes Narkoba Peserta';
       $trail['audit_event'] = 'post';
       $trail['audit_value'] = json_encode($this->form_params);
       $trail['audit_url'] = $request->url();
       $trail['audit_ip_address'] = $request->ip();
       $trail['audit_user_agent'] = $request->userAgent();
       $trail['audit_message'] = $result['comment'];
       $trail['created_at'] = date("Y-m-d H:i:s");
       $trail['created_by'] = $request->session()->get('id');

       $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

       if(($result['code'] == 200) && ($result['status'] != 'error')){
          $updateHeader = $client->request('PUT', $baseUrl.'/api/tesnarkobaheader/'.$id,
           [
               'headers' =>
               [
                   'Authorization' => 'Bearer '.$token
               ],
               'form_params' => [ 'status'=> 'Y']
            ]);
            $result = json_decode($updateHeader->getBody()->getContents(), true);

       }
       return redirect('pemberdayaan/dir_masyarakat/edit_pendataan_tes_narkoba/'.$id);
    }

    public function updatePeserta(Request $request){
       $id = $request->input('id');
       $pesertaid = $request->input('peserta_id');
       $baseUrl = URL::to($this->urlapi());
//       $baseUrl = URL::to('/');
       $token = $request->session()->get('token');

       $client = new Client();

       $requestPeserta = $client->request('PUT', $baseUrl.'/api/tesnarkobapeserta/'.$pesertaid,
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' => [
                        'kode_jenis_identitas' => $request->input('kode_jenis_identitas'),
                        'no_identitas' => $request->input('no_identitas'),
                        'peserta_inisial' => $request->input('peserta_inisial'),
                        'kode_jenis_kelamin' => $request->input('kode_jenis_kelamin'),
                        'peserta_tempat_lahir' => $request->input('peserta_tempat_lahir'),
                        'peserta_tanggal_lahir' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('peserta_tanggal_lahir')))),
                        'peserta_usia' => $request->input('peserta_usia'),
                        'kode_pendidikan_akhir' => $request->input('kode_pendidikan_akhir'),
                        'kode_pekerjaan' => $request->input('kode_pekerjaan'),
                        'kode_warga_negara' => $request->input('kode_warga_negara'),
                        'kode_negara' => $request->input('kode_negara'),
                        'id_brgbukti' => $request->input('id_brgbukti'),
                    ]
                ]
            );

        $result = json_decode($requestPeserta->getBody()->getContents(), true);

        $this->form_params = array('kode_jenis_identitas' => $request->input('kode_jenis_identitas'),
        'no_identitas' => $request->input('no_identitas'),
        'peserta_inisial' => $request->input('peserta_inisial'),
        'kode_jenis_kelamin' => $request->input('kode_jenis_kelamin'),
        'peserta_tempat_lahir' => $request->input('peserta_tempat_lahir'),
        'peserta_tanggal_lahir' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('peserta_tanggal_lahir')))),
        'peserta_usia' => $request->input('peserta_usia'),
        'kode_pendidikan_akhir' => $request->input('kode_pendidikan_akhir'),
        'kode_pekerjaan' => $request->input('kode_pekerjaan'),
        'kode_warga_negara' => $request->input('kode_warga_negara'),
        'kode_negara' => $request->input('kode_negara'),
        'id_brgbukti' => $request->input('id_brgbukti'));

        $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Peran Serta Masyarakat - Tes Narkoba Peserta';
        $trail['audit_event'] = 'put';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $result['comment'];
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


        return redirect('pemberdayaan/dir_masyarakat/edit_pendataan_tes_narkoba/'.$id);
    }

    public function pendataanAntiNarkoba(Request $request){
        $kondisi = "";
        if($request->limit) {
          $this->limit = $request->limit;
        } else {
          $this->limit = config('app.limit');
        }
        if ($request->input('page')) {
          $current_page = $request->input('page');
          $start_number = ($this->limit * ($request->page -1 )) + 1;
        } else {
          $current_page = 1;
          $start_number = $current_page;
        }

        if($request->isMethod('post')){
          $pelaksana = $request->pelaksana;
          $tipe = $request->tipe;
          $tgl_to = $request->tgl_to;
          $tgl_from = $request->tgl_from;
          $status = $request->status;
          $asal_penggiat = $request->asal_penggiat;
          $jenis_kegiatan = $request->jenis_kegiatan;
          $jml_from = $request->jml_from;
          $jml_to = $request->jml_to;
          $materi = $request->materi;
          $kata_kunci = $request->kata_kunci;
          if($tipe){
            $kondisi .= '&tipe='.$tipe;
            $this->selected['tipe'] = $tipe;
          }
          if($tipe == 'pelaksana'){
            $this->selected['pelaksana'] = $request->$tipe;
            $kondisi .= '&pelaksana='.$request->$tipe;
          }elseif($tipe == 'periode'){
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
          }elseif($tipe == 'jumlah_peserta'){
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
          }elseif($tipe == 'asal_penggiat'){
            $kondisi .= '&asal_penggiat='.$asal_penggiat;
            $this->selected['asal_penggiat'] = $asal_penggiat;
          }elseif($tipe == 'jenis_kegiatan'){
            $kondisi .= '&jenis_kegiatan='.$jenis_kegiatan;
            $this->selected['jenis_kegiatan'] = $jenis_kegiatan;
          }elseif($tipe == 'status'){
            $kondisi .= '&status='.$status;
            $this->selected['status'] = $status;
          }elseif($tipe == 'materi'){
            $kondisi .= '&materi='.$kata_kunci;
            $this->selected['materi'] = $kata_kunci;
          }
          if($request->order){
            $kondisi .= '&order='.$request->order;
          }elseif(!$request->order){
            $kondisi .= '&order=desc';
          }
          $this->selected['order'] = $request->order;
          $this->selected['limit'] = $request->limit;
        }else{
          $get = $request->except(['page','tgl_from','tgl_to','limit']);
          $tipe = $request->tipe;
          if(count($get)>0){
            if($tipe){
              $this->selected['tipe']  = $tipe;
            }
            foreach ($get as $key => $value) {
              $kondisi .= "&".$key.'='.$value;
              if($value == 'pelaksana'){
                $this->selected['pelaksana'] = $value;
              }else if($value == 'periode'){
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
        $kondisi .='&id_wilayah='.$request->session()->get('wilayah');
        $filtering = false;
        if($kondisi){
          $filter = $kondisi;
          $filtering = true;
          $this->data['kondisi'] = '?'.$offset.$kondisi;
        }else{
          $filter = '/';
          $filtering = false;
          $this->data['kondisi'] = $current_page;
        }
        $this->data['filter'] = $this->selected;
        $this->data['route'] = $request->route()->getName();
        $query  =  execute_api('api/psmpenggiat?'.$offset.'&'.$limit.$kondisi,"GET");
        if($query['code'] == 200 && $query['status'] != 'error'){
          $this->data['data_penggiat'] = $query['data'];
          $total_item = $query['paginate']['totalpage'] * $this->limit;
        }else{
          $this->data['data_penggiat'] = $query['data'];
          $total_item = 0;
        }

        $filtering = false;
        if($kondisi){
          $filter = $kondisi.'&'.$limit;
          $filtering = true;
          $this->data['kondisi'] = '?'.$offset.$kondisi.'&'.$limit;
        }else{
          $filter = '/';
          $filtering = false;
          $this->data['kondisi'] = $current_page;
        }
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['asal_penggiat'] = config('lookup.instansi');
        $this->data['jenis_kegiatan_antinarkoba'] = config('lookup.jenis_kegiatan_antinarkoba');
        /*end filter*/

        $this->data['start_number'] = $start_number;
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
        $this->data['title'] = "Penggiat Anti Narkoba";
        $this->data['route'] = $request->route()->getName();
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['path'] = $request->path();
        $this->data['delete_route'] = 'delete_pendataan_anti_narkoba';
        $this->data['breadcrumps'] = breadcrumps_dir_masyarakat($request->route()->getName());
        return view('pemberdayaan.masyarakat.anti_narkoba.index_pendataanAntinarkoba',$this->data);
    }

    public function addpendataanAntiNarkoba(Request $request){
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $this->data['title']="psmpenggiat";

        $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
        $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $propkab;
        $this->data['jenis_kegiatan_antinarkoba'] = config('lookup.jenis_kegiatan_antinarkoba');
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        $this->data['breadcrumps'] = breadcrumps_dir_masyarakat($request->route()->getName());
        return view('pemberdayaan.masyarakat.anti_narkoba.add_pendataanAntinarkoba',$this->data);
    }

    public function editpendataanAntiNarkoba(Request $request){
        $id = $request->id;
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

       $requestDataDetail= $client->request('GET', $baseUrl.'/api/psmpenggiat/'.$id,
           [
               'headers' =>
               [
                   'Authorization' => 'Bearer '.$token
               ]
           ]
       );

       $dataDetail = json_decode($requestDataDetail->getBody()->getContents(), true);

       $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
       $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

       $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
       $this->data['propkab'] = $propkab;
       $this->data['jenis_kegiatan_antinarkoba'] = config('lookup.jenis_kegiatan_antinarkoba');
       $this->data['propkab'] = $propkab;

        $this->data['data_detail'] = $dataDetail;
        $this->data['id'] = $id;
        $this->data['breadcrumps'] = breadcrumps_dir_masyarakat($request->route()->getName());
        return view('pemberdayaan.masyarakat.anti_narkoba.edit_pendataanAntinarkoba',$this->data);
    }

    public function inputpendataanAntiNarkoba(Request $request){

        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $client = new Client();
        $narasumber = [];
        $materi = [];
        $json_materi = "";
        $json_narasumber = "";

        $input_materi = $request->input('materi');
        if(count($input_materi)>0){
          foreach($input_materi as $m => $i){
            $narasumber[] = $i['narasumber'];
            $materi[] = $i['materi'];
          }
        }else{
          $narasumber = [];
          $materi = [];
        }

        if(count($narasumber)>0){
          $json_narasumber= json_encode($narasumber);
        }

        if(count($materi)>0){
          $json_materi= json_encode($materi);
        }

        $requestPenggiat = $client->request('POST', $baseUrl.'/api/psmpenggiat',
               [
                   'headers' =>
                   [
                       'Authorization' => 'Bearer '.$token
                   ],
                   'form_params' => [
                       'idpelaksana' => $request->input('idpelaksana'),
                       'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                       'tgl_selesai' => ( $request->input('tgl_selesai') ?date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_selesai')))) : ''),
                       'kodesasaran' => $request->input('sasaran'),
                       'jumlah_peserta' => $request->input('jumlah_peserta'),
                       'lokasi_kegiatan' => $request->input('lokasi_kegiatan'),
                       'lokasi_kegiatan_idkabkota' => $request->input('lokasi_kegiatan_idkabkota'),
                       'narasumber' => $json_narasumber,
                       'materi' => $json_materi,
                       'jenis_kegiatan' => $request->input('jenis_kegiatan'),
                   ]
               ]
           );


       $result = json_decode($requestPenggiat->getBody()->getContents(), true);

       $this->form_params = array('idpelaksana' => $request->input('idpelaksana'),
       'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'tgl_selesai' => ( $request->input('tgl_selesai') ?date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_selesai')))) : ''),
       'kodesasaran' => $request->input('sasaran'),
       'jumlah_peserta' => $request->input('jumlah_peserta'),
       'lokasi_kegiatan' => $request->input('lokasi_kegiatan'),
       'lokasi_kegiatan_idkabkota' => $request->input('lokasi_kegiatan_idkabkota'),
       'narasumber' => $json_narasumber,
       'materi' => $json_materi,
       'jenis_kegiatan' => $request->input('jenis_kegiatan'));

       $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Peran Serta Masyarakat - Pengembangan Kapasitas';
       $trail['audit_event'] = 'post';
       $trail['audit_value'] = json_encode($this->form_params);
       $trail['audit_url'] = $request->url();
       $trail['audit_ip_address'] = $request->ip();
       $trail['audit_user_agent'] = $request->userAgent();
       $trail['audit_message'] = $result['comment'];
       $trail['created_at'] = date("Y-m-d H:i:s");
       $trail['created_by'] = $request->session()->get('id');

       $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

       if($result['code'] == 200 && $result['status'] != 'error'){
        $insertedId = $result['data']['eventID'];
        $this->kelengkapan_antinarkoba($insertedId);
        $this->data['status'] = 'success';
        $this->data['messages'] = 'Data  Pengembangan Kapasitas Berhasil Ditambahkan';
       }else{
        $this->data['status'] = 'error';
        $this->data['messages'] = 'Data  Pengembangan Kapasitas Gagal Ditambahkan';
       }
       return redirect(route('pendataan_anti_narkoba'))->with('status',$this->data);

    }

    public function updatependataanAntiNarkoba(Request $request){
      $id = $request->input('id');

      $baseUrl = URL::to($this->urlapi());
//      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');

      $client = new Client();
      $narasumber = [];
      $materi = [];
      $json_materi = "";
      $json_narasumber = "";

      $input_materi = $request->input('materi');
      if(count($input_materi)>0){
        foreach($input_materi as $m => $i){
          $narasumber[] = $i['narasumber'];
          $materi[] = $i['materi'];
        }
      }else{
        $narasumber = [];
        $materi = [];
      }

      if(count($narasumber)>0){
        $json_narasumber= json_encode($narasumber);
      }

      if(count($materi)>0){
        $json_materi= json_encode($materi);
      }
      $requestPenggiat = $client->request('PUT', $baseUrl.'/api/psmpenggiat/'.$id,
               [
                   'headers' =>
                   [
                       'Authorization' => 'Bearer '.$token
                   ],
                   'form_params' => [
                     'idpelaksana' => $request->input('idpelaksana'),
                     'tgl_pelaksanaan' => ( $request->input('tgl_pelaksanaan') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))) : ''),
                     'tgl_selesai' => ( $request->input('tgl_selesai') ?date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_selesai')))) : ''),
                     'kodesasaran' => $request->input('sasaran'),
                     'jumlah_peserta' => $request->input('jumlah_peserta'),
                     'lokasi_kegiatan' => $request->input('lokasi_kegiatan'),
                     'lokasi_kegiatan_idkabkota' => $request->input('lokasi_kegiatan_idkabkota'),
                     'narasumber' => $json_narasumber,
                     'jenis_kegiatan' => $request->input('jenis_kegiatan'),
                     'materi' => $json_materi,
                   ]
               ]
           );

       $result = json_decode($requestPenggiat->getBody()->getContents(), true);

       $this->form_params = array('idpelaksana' => $request->input('idpelaksana'),
       'tgl_pelaksanaan' => ( $request->input('tgl_pelaksanaan') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))) : ''),
       'tgl_selesai' => ( $request->input('tgl_selesai') ?date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_selesai')))) : ''),
       'kodesasaran' => $request->input('sasaran'),
       'jumlah_peserta' => $request->input('jumlah_peserta'),
       'lokasi_kegiatan' => $request->input('lokasi_kegiatan'),
       'lokasi_kegiatan_idkabkota' => $request->input('lokasi_kegiatan_idkabkota'),
       'narasumber' => $json_narasumber,
       'jenis_kegiatan' => $request->input('jenis_kegiatan'),
       'materi' => $json_materi);

       $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Peran Serta Masyarakat - Pengembangan Kapasitas';
       $trail['audit_event'] = 'put';
       $trail['audit_value'] = json_encode($this->form_params);
       $trail['audit_url'] = $request->url();
       $trail['audit_ip_address'] = $request->ip();
       $trail['audit_user_agent'] = $request->userAgent();
       $trail['audit_message'] = $result['comment'];
       $trail['created_at'] = date("Y-m-d H:i:s");
       $trail['created_by'] = $request->session()->get('id');

       $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

       if($result['code'] == 200 && $result['status'] != 'error'){
        $this->kelengkapan_antinarkoba($id);
        $this->data['status'] = 'success';
        $this->data['message'] = 'Data Pengembangan Kapasitas Berhasil Diperbarui';
       }else{
        $this->data['status'] = 'error';
        $this->data['message'] = 'Data Pengembangan Kapasitas Gagal Diperbarui';
       }

       return back()->with('status',$this->data);
    }
    public function deletePendataanAntiNarkoba(Request $request){
      $id = $request->input('id');
      if($id){
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/psmpenggiat/'.$id,'DELETE');
            $this->form_params['delete_id'] = $id;
            $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Peran Serta Masyarakat - Pengembangan Kapasitas';
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
        $data_request = ['status'=>'error','message'=>'Data Tes Narkoba Gagal Dihapus'];
        return $data_request;
      }
    }
    public function pendataanPelatihan(Request $request){
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
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));

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
        $kondisi .='&id_wilayah='.$request->session()->get('wilayah');
        $query = execute_api('api/psmpelatihan?'.$limit.'&'.$offset.$kondisi,'get');
        if($query['code'] == 200 && $query['status'] != 'error'){
          $this->data['data_pelatihan'] = $query['data'];
          $total_item = $query['paginate']['totalpage'] * $this->limit;
        }else{
          $this->data['data_pelatihan'] = [];
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

        $this->data['start_number'] =$start_number;
        $this->data['title'] = "psmpelatihan";
        $requestSasaran = execute_api('/api/lookup/jenis_sasaran','GET');
        if($requestSasaran['code'] == 200 && $requestSasaran['status'] != 'error'){
          $this->data['sasaran'] = $requestSasaran['data'];
        }else{
          $this->data['sasaran'] = [];
        }
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
        $this->data['kode_anggaran'] = config('lookup.kode_anggaran');
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['delete_route'] = 'delete_pendataan_pelatihan';
        $this->data['data_detail'] = $detail;
        $this->data['path'] = $request->path();
        $this->data['breadcrumps'] = breadcrumps_dir_masyarakat($request->route()->getName());
        return view('pemberdayaan.masyarakat.pelatihan.index_pendataanPelatihan',$this->data);
    }

    public function addpendataanPelatihan(Request $request){
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $this->data['title']="psmpelatihan";

        $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
        $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

        $requestSasaran = execute_api('/api/lookup/jenis_sasaran','GET');
        if($requestSasaran['code'] == 200 && $requestSasaran['status'] != 'error'){
          $this->data['sasaran'] = $requestSasaran['data'];
        }else{
          $this->data['sasaran'] = [];
        }
        $this->data['kode_anggaran'] = config('lookup.kode_anggaran');
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $propkab;

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;


        $this->data['breadcrumps'] = breadcrumps_dir_masyarakat($request->route()->getName());
        return view('pemberdayaan.masyarakat.pelatihan.add_pendataanPelatihan',$this->data);
    }

    public function editpendataanPelatihan(Request $request){
        $id = $request->id;
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

       $requestDataDetail= $client->request('GET', $baseUrl.'/api/psmpelatihan/'.$id,
           [
               'headers' =>
               [
                   'Authorization' => 'Bearer '.$token
               ]
           ]
       );

       $dataDetail = json_decode($requestDataDetail->getBody()->getContents(), true);

        if($dataDetail['code'] ==200 && $dataDetail['status'] != 'error'){
          if ($dataDetail['data']['anggaran_id']) {
            $data_anggaran = $this->globalGetAnggaran($token, $dataDetail['data']['anggaran_id']);
            if($data_anggaran['code'] == 200 && $data_anggaran['status'] != 'error'){
              $this->data['data_anggaran'] = $data_anggaran['data'];
            }else{
              $this->data['data_anggaran'] = [];
            }
          }else{
            $this->data['data_anggaran'] = [];
          }
          $this->data['data_detail'] = $dataDetail;
        }else{
          $this->data['data_anggaran'] = [];
          $this->data['data_detail'] = [];
        }
        // exit();
        $requestSasaran = execute_api('/api/lookup/jenis_sasaran','GET');
        if($requestSasaran['code'] == 200 && $requestSasaran['status'] != 'error'){
          $this->data['sasaran'] = $requestSasaran['data'];
        }else{
          $this->data['sasaran'] = [];
        }
        $this->data['kode_anggaran'] = config('lookup.kode_anggaran');
        $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
        $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $propkab;


       $this->data['id'] = $id;
        $this->data['breadcrumps'] = breadcrumps_dir_masyarakat($request->route()->getName());
        return view('pemberdayaan.masyarakat.pelatihan.edit_pendataanPelatihan',$this->data);
    }

    public function inputpendataanPelatihan(Request $request){
       $baseUrl = URL::to($this->urlapi());
//       $baseUrl = URL::to('/');
       $token = $request->session()->get('token');
       $anggaran = '';
       $client = new Client();
       if ($request->input('kodesumberanggaran')=="DIPA") {
          if($request->kd_anggaran){
             $requestAnggaran = $client->request('POST', $baseUrl.'/api/anggaran',
                     [
                         'headers' =>
                         [
                             'Authorization' => 'Bearer '.$token
                         ],
                         'form_params' => [
                             'kode_anggaran' => $request->input('akode_anggaran'),
                             'sasaran' => $request->input('asasaran'),
                             'pagu' => $request->input('apagu'),
                             'target_output' => $request->input('atarget_output'),
                             'satuan_output' => $request->input('asatuan_output'),
                             'tahun' => $request->input('atahun'),
                             'satker_code' => $request->input('asatker_code'),
                             'refid_anggaran' => $request->input('arefid_anggaran'),

                         ]
                     ]
                 );

             $resultAnggaran = json_decode($requestAnggaran->getBody()->getContents(), true);
             if($resultAnggaran['code'] == 200 && $resultAnggaran['status'] != 'error'){
              $anggaran = $resultAnggaran['data']['eventID'];
             }else{
              $anggaran ="";
             }
          }else{
            $anggaran ="";
          }
        } else {
          $anggaran = '';
        }

        $jumlah_instansi = count($request->input('group-c'));
        $peserta = 0;
        if ($jumlah_instansi > 0) {
            foreach ($request->input('group-c') as $c1 => $r1) {
                $peserta = $peserta + $r1['list_jumlah_peserta'];
            }
        }

        $client = new Client();
        $narasumber = [];
        $materi = [];
        $json_materi = "";
        $json_narasumber = "";

        $input_materi = $request->input('materi');
        if(count($input_materi)>0){
          foreach($input_materi as $m => $i){
            $narasumber[] = $i['narasumber'];
            $materi[] = $i['materi'];
          }
        }else{
          $narasumber = [];
          $materi = [];
        }

        if(count($narasumber)>0){
          $json_narasumber= json_encode($narasumber);
        }

        if(count($materi)>0){
          $json_materi= json_encode($materi);
        }

        $requestPelatihan = $client->request('POST', $baseUrl.'/api/psmpelatihan',
               [
                   'headers' =>
                   [
                       'Authorization' => 'Bearer '.$token
                   ],
                   'form_params' => [
                       'idpelaksana' => $request->input('idpelaksana'),
                       'tgl_pelaksanaan' => ($request->input('tgl_pelaksanaan') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))) : ''),
                       // 'jenis_kegiatan' => $request->input('sasaran'),
                       'kodesasaran' => $request->input('sasaran'),
                       'jumlah_instansi' => $jumlah_instansi,
                       'meta_instansi' => json_encode($request->input('group-c')),
                       'jumlah_peserta' => $peserta,
                       'lokasi_kegiatan' => $request->input('lokasi_kegiatan'),
                       //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
                       'lokasi_kegiatan_idkabkota' => $request->input('lokasi_kegiatan_idkabkota'),
                       'narasumber' => $json_narasumber,
                       'panitia_monev' => $request->input('panitia_monev'),
                       'materi' => $json_materi,
                       'kodesumberanggaran' => $request->input('kodesumberanggaran'),
                       // 'file_upload' => $anggaran,
                       // 'created_at' => $anggaran,
                       // 'created_by' => $anggaran,
                       'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                       'periode_tahun' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                       'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                       'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                       'anggaran_id' => $anggaran,
                   ]
               ]
           );

       $result = json_decode($requestPelatihan->getBody()->getContents(), true);

       $this->form_params = array('idpelaksana' => $request->input('idpelaksana'),
       'tgl_pelaksanaan' => ($request->input('tgl_pelaksanaan') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))) : ''),
       // 'jenis_kegiatan' => $request->input('sasaran'),
       'kodesasaran' => $request->input('sasaran'),
       'jumlah_instansi' => $jumlah_instansi,
       'meta_instansi' => json_encode($request->input('group-c')),
       'jumlah_peserta' => $peserta,
       'lokasi_kegiatan' => $request->input('lokasi_kegiatan'),
       //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
       'lokasi_kegiatan_idkabkota' => $request->input('lokasi_kegiatan_idkabkota'),
       'narasumber' => $json_narasumber,
       'panitia_monev' => $request->input('panitia_monev'),
       'materi' => $json_materi,
       'kodesumberanggaran' => $request->input('kodesumberanggaran'),
       // 'file_upload' => $anggaran,
       // 'created_at' => $anggaran,
       // 'created_by' => $anggaran,
       'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'periode_tahun' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'anggaran_id' => $anggaran);

       $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Peran Serta Masyarakat - Bimbingan Teknis';
       $trail['audit_event'] = 'post';
       $trail['audit_value'] = json_encode($this->form_params);
       $trail['audit_url'] = $request->url();
       $trail['audit_ip_address'] = $request->ip();
       $trail['audit_user_agent'] = $request->userAgent();
       $trail['audit_message'] = $result['comment'];
       $trail['created_at'] = date("Y-m-d H:i:s");
       $trail['created_by'] = $request->session()->get('id');

       $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

       if($result['code'] == 200 && $result['status'] != 'error'){
        $inputId = $result['data']['eventID'];

        if ($request->file('file_upload')){
            $fileName = date('Y-m-d').'_'.$inputId.'-'.$request->file('file_upload')->getClientOriginalName();
            try {
              $request->file('file_upload')->storeAs('PsmPelatihanPenggiat', $fileName);

              $requestfile = $client->request('PUT', $baseUrl.'/api/psmpelatihan/'.$inputId,
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
        $this->kelengkapan_bimbinganteknis($inputId);
        $this->data['status'] = 'success';
        $this->data['message'] = 'Data Bimbingan Teknis Berhasil Ditambahkan';
       }else{
          $this->data['status'] = 'error';
          $this->data['message'] = 'Data Bimbingan Teknis Gagal Ditambahkan';
       }
       return redirect(route('pendataan_pelatihan'))->with('status',$this->data);
    }

    public function deletePendataanPelatihan(Request $request){
      $id = $request->input('id');
      if($id){
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/psmpelatihan/'.$id,'DELETE');
            $this->form_params['delete_id'] = $id;
            $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Peran Serta Masyarakat - Bimbingan Teknis';
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
        $data_request = ['status'=>'error','message'=>'Data Tes Narkoba Gagal Dihapus'];
        return $data_request;
      }
    }
    public function updatependataanPelatihan(Request $request){
        $id = $request->input('id');

        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $client = new Client();

        $jumlah_instansi = count($request->input('group-c'));
        $peserta = 0;
        if ($jumlah_instansi > 0) {
            foreach ($request->input('group-c') as $c1 => $r1) {
                $peserta = $peserta + $r1['list_jumlah_peserta'];
            }
        }
        $narasumber = [];
        $materi = [];
        $json_materi = "";
        $json_narasumber = "";

        $input_materi = $request->input('materi');
        if(count($input_materi)>0){
          foreach($input_materi as $m => $i){
            $narasumber[] = $i['narasumber'];
            $materi[] = $i['materi'];
          }
        }else{
          $narasumber = [];
          $materi = [];
        }

        if(count($narasumber)>0){
          $json_narasumber= json_encode($narasumber);
        }

        if(count($materi)>0){
          $json_materi= json_encode($materi);
        }
        $fileName = "";
        if ($request->input('kodesumberanggaran')=="DIPA") {
          if($request->kd_anggaran){
             $requestAnggaran = $client->request('POST', $baseUrl.'/api/anggaran',
                     [
                         'headers' =>
                         [
                             'Authorization' => 'Bearer '.$token
                         ],
                         'form_params' => [
                             'kode_anggaran' => $request->input('akode_anggaran'),
                             'sasaran' => $request->input('asasaran'),
                             'pagu' => $request->input('apagu'),
                             'target_output' => $request->input('atarget_output'),
                             'satuan_output' => $request->input('asatuan_output'),
                             'tahun' => $request->input('atahun'),
                             'satker_code' => $request->input('asatker_code'),
                             'refid_anggaran' => $request->input('arefid_anggaran'),

                         ]
                     ]
                 );

             $resultAnggaran = json_decode($requestAnggaran->getBody()->getContents(), true);
             if($resultAnggaran['code'] == 200 && $resultAnggaran['status'] != 'error'){
              $anggaran = $resultAnggaran['data']['eventID'];
             }else{
              $anggaran ="";
             }
          }else{
            $anggaran ="";
          }
        } else {
          $anggaran = '';
        }
       $form_params =[
             'idpelaksana' => $request->input('idpelaksana'),
             'tgl_pelaksanaan' => ($request->input('tgl_pelaksanaan') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))) : ''),
             // 'jenis_kegiatan' => $request->input('sasaran'),
             'kodesasaran' => $request->input('sasaran'),
             'jumlah_instansi' => $jumlah_instansi,
             'meta_instansi' => json_encode($request->input('group-c')),
             'jumlah_peserta' => $peserta,
             'lokasi_kegiatan' => $request->input('lokasi_kegiatan'),
             //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
             'lokasi_kegiatan_idkabkota' => $request->input('lokasi_kegiatan_idkabkota'),
             'narasumber' => $json_narasumber,
             'panitia_monev' => $request->input('panitia_monev'),
             'materi' => $json_materi,
             'kodesumberanggaran' => $request->input('kodesumberanggaran'),
             // 'file_upload' => $anggaran,
             // 'created_at' => $anggaran,
             // 'created_by' => $anggaran,
             'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
             'periode_tahun' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
             'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
             'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
             'anggaran_id' => $anggaran,
         ];

        if ($request->file('file_upload')){
               $fileName = $id.'-'.$request->file('file_upload')->getClientOriginalName();
               try{
                 $request->file('file_upload')->storeAs('PsmPelatihanPenggiat', $fileName);
                 $form_params['file_upload'] = $fileName;
              }catch(\Exception $e){
                $e->getMEssage();
              }
         }
        $requestPelatihan = $client->request('PUT', $baseUrl.'/api/psmpelatihan/'.$id,
                 [
                     'headers' =>
                     [
                         'Authorization' => 'Bearer '.$token
                     ],
                     'form_params' => $form_params
                  ]
             );

         $result = json_decode($requestPelatihan->getBody()->getContents(), true);

         $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Peran Serta Masyarakat - Bimbingan Teknis';
         $trail['audit_event'] = 'put';
         $trail['audit_value'] = json_encode($form_params);
         $trail['audit_url'] = $request->url();
         $trail['audit_ip_address'] = $request->ip();
         $trail['audit_user_agent'] = $request->userAgent();
         $trail['audit_message'] = $result['comment'];
         $trail['created_at'] = date("Y-m-d H:i:s");
         $trail['created_by'] = $request->session()->get('id');

         $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


          if($result['code'] == 200 && $result['status'] != 'error'){
            $this->data['status'] = 'success';
            $this->data['message'] = 'Data Bimbingan Teknis Berhasil Diperbarui';
            $this->kelengkapan_bimbinganteknis($id);
          }else{
            $this->data['status'] = 'error';
            $this->data['message'] = 'Data Bimbingan Teknis Gagal Diperbarui';
          }


          return back()->with('status',$this->data);
    }

    public function pendataanKapasitas(Request $request){

       $client = new Client();
       if ($request->input('page')) {
         $page = $request->input('page');
       } else {
         $page = 1;
       }
       $baseUrl = URL::to($this->urlapi());
//       $baseUrl = URL::to('/');
       $token = $request->session()->get('token');


       $requestKapasitas = $client->request('GET', $baseUrl.'/api/psmpengembangan?page='.$page.'&id_wilayah='.$request->session()->get('wilayah'),
           [
               'headers' =>
               [
                   'Authorization' => 'Bearer '.$token
               ]
           ]
       );

       $Kapasitas = json_decode($requestKapasitas->getBody()->getContents(), true);

       $page = $Kapasitas['paginate'];

       $this->data['data_kapasitas'] = $Kapasitas['data'];
       $this->data['title'] = "psmpengembangan";
       $this->data['token'] = $token;

       $user_id = Auth::user()->user_id;
       $detail = MainModel::getUserDetail($user_id);
       $this->data['data_detail'] = $detail;
       $this->data['path'] = $request->path();
       $this->data['page'] = $page;
       $this->data['breadcrumps'] = breadcrumps_dir_masyarakat($request->route()->getName());
       return view('pemberdayaan.masyarakat.pengembang_kapasitas.index_pendataanKapasitas',$this->data);
    }

    public function addpendataanKapasitas(Request $request){
       $client = new Client();
       $baseUrl = URL::to($this->urlapi());
//       $baseUrl = URL::to('/');
       $this->data['title']="psmpengembangan";

       $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
       $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

       $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
       $this->data['propkab'] = $propkab;

       $user_id = Auth::user()->user_id;
       $detail = MainModel::getUserDetail($user_id);
       $data['data_detail'] = $detail;
       $this->data['breadcrumps'] = breadcrumps_dir_masyarakat($request->route()->getName());
       return view('pemberdayaan.masyarakat.pengembang_kapasitas.add_pendataanKapasitas',$this->data);
    }

    public function editpendataanKapasitas(Request $request){
        $id = $request->id;
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

       $requestDataDetail= $client->request('GET', $baseUrl.'/api/psmpengembangan/'.$id,
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

       $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
       $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

       $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
       $this->data['propkab'] = $propkab;

       $this->data['data_detail'] = $dataDetail;
       $this->data['id'] = $id;
       $this->data['breadcrumps'] = breadcrumps_dir_masyarakat($request->route()->getName());
       return view('pemberdayaan.masyarakat.pengembang_kapasitas.edit_pendataanKapasitas',$this->data);
    }

    public function inputpendataanKapasitas(Request $request){

       $baseUrl = URL::to($this->urlapi());
//       $baseUrl = URL::to('/');
       $token = $request->session()->get('token');

       $client = new Client();

       if ($request->input('kodesumberanggaran')=="DIPA") {
           $requestAnggaran = $client->request('POST', $baseUrl.'/api/anggaran',
                   [
                       'headers' =>
                       [
                           'Authorization' => 'Bearer '.$token
                       ],
                       'form_params' => [
                           'kode_anggaran' => $request->input('akode_anggaran'),
                           'sasaran' => $request->input('asasaran'),
                           'pagu' => $request->input('apagu'),
                           'target_output' => $request->input('atarget_output'),
                           'satuan_output' => $request->input('asatuan_output'),
                           'tahun' => $request->input('atahun'),
                           'satker_code' => $request->input('asatker_code'),
                           'refid_anggaran' => $request->input('arefid_anggaran'),

                       ]
                   ]
               );

           $resultAnggaran = json_decode($requestAnggaran->getBody()->getContents(), true);
           $anggaran = $resultAnggaran['data']['eventID'];
        } else {
          $anggaran = '';
        }

        $jumlah_instansi = count($request->input('group-c'));
        $peserta = 0;
        if ($jumlah_instansi > 0) {
            foreach ($request->input('group-c') as $c1 => $r1) {
                $peserta = $peserta + $r1['list_jumlah_peserta'];
            }
        }

        $requestKapasitas = $client->request('POST', $baseUrl.'/api/psmpengembangan',
               [
                   'headers' =>
                   [
                       'Authorization' => 'Bearer '.$token
                   ],
                   'form_params' => [
                       'idpelaksana' => $request->input('idpelaksana'),
                       'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                       // 'jenis_kegiatan' => $request->input('sasaran'),
                       'kodesasaran' => $request->input('sasaran'),
                       'jumlah_instansi' => $jumlah_instansi,
                       'meta_instansi' => json_encode($request->input('group-c')),
                       'jumlah_peserta' => $peserta,
                       'lokasi_kegiatan' => $request->input('lokasi_kegiatan'),
                       //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
                       'lokasi_kegiatan_idkabkota' => $request->input('lokasi_kegiatan_idkabkota'),
                       'narasumber' => $request->input('narasumber'),
                       'panitia_monev' => $request->input('panitia_monev'),
                       'materi' => $request->input('materi'),
                       'kodesumberanggaran' => $request->input('kodesumberanggaran'),
                       // 'file_upload' => $anggaran,
                       // 'created_at' => $anggaran,
                       // 'created_by' => $anggaran,
                       'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                       'periode_tahun' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                       'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                       'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                       'anggaran_id' => $anggaran,
                   ]
               ]
           );

       $result = json_decode($requestKapasitas->getBody()->getContents(), true);

       $inputId = $result['data']['eventID'];

       if ($request->file('file_upload') != ''){
           $fileName = $inputId.'-'.$request->file('file_upload')->getClientOriginalName();
           $request->file('file_upload')->storeAs('PsmPengembanganKapasitas', $fileName);

           $requestfile = $client->request('PUT', $baseUrl.'/api/psmpengembangan/'.$inputId,
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
       }
       return redirect('pemberdayaan/dir_masyarakat/pendataan_kapasitas/');

    }

    public function updatependataanKapasitas(Request $request){
          $id = $request->input('id');

          $baseUrl = URL::to($this->urlapi());
//          $baseUrl = URL::to('/');
          $token = $request->session()->get('token');

          $client = new Client();

          $jumlah_instansi = count($request->input('group-c'));
          $peserta = 0;
          if ($jumlah_instansi > 0) {
              foreach ($request->input('group-c') as $c1 => $r1) {
                  $peserta = $peserta + $r1['list_jumlah_peserta'];
              }
          }

          $requestKapasitas = $client->request('PUT', $baseUrl.'/api/psmpengembangan/'.$id,
                   [
                       'headers' =>
                       [
                           'Authorization' => 'Bearer '.$token
                       ],
                       'form_params' => [
                           'idpelaksana' => $request->input('idpelaksana'),
                           'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                           // 'jenis_kegiatan' => $request->input('sasaran'),
                           'kodesasaran' => $request->input('sasaran'),
                           'jumlah_instansi' => $jumlah_instansi,
                           'meta_instansi' => json_encode($request->input('group-c')),
                           'jumlah_peserta' => $peserta,
                           'lokasi_kegiatan' => $request->input('lokasi_kegiatan'),
                           //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
                           'lokasi_kegiatan_idkabkota' => $request->input('lokasi_kegiatan_idkabkota'),
                           'narasumber' => $request->input('narasumber'),
                           'panitia_monev' => $request->input('panitia_monev'),
                           'materi' => $request->input('materi'),
                           'kodesumberanggaran' => $request->input('kodesumberanggaran'),
                           // 'file_upload' => $anggaran,
                           // 'created_at' => $anggaran,
                           // 'created_by' => $anggaran,
                           'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                           'periode_tahun' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                           'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                           'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                       ]
                   ]
               );

           $result = json_decode($requestKapasitas->getBody()->getContents(), true);

           if ($request->file('file_upload') != ''){
               $fileName = $id.'-'.$request->file('file_upload')->getClientOriginalName();
               $request->file('file_upload')->storeAs('PsmPengembanganKapasitas', $fileName);

               $requestfile = $client->request('PUT', $baseUrl.'/api/psmpengembangan/'.$id,
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
            }

           return redirect('pemberdayaan/dir_masyarakat/pendataan_kapasitas/');
      }

    public function psmSupervisi(Request $request){
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

        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
        $kondisi .='&id_wilayah='.$request->session()->get('wilayah');

        $query = execute_api('api/psmsupervisi?'.$limit.'&'.$offset.$kondisi,'get');
        if($query['code'] == 200 && $query['status'] != 'error'){
          $this->data['data_supervisi'] = $query['data'];
          $total_item = $query['paginate']['totalpage'] * $this->limit;
        }else{
          $this->data['data_supervisi'] = [];
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

        $this->data['start_number'] =$start_number;
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['title'] = "psmsupervisi";
        $this->data['delete_route'] = "delete_psm_supervisi";
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['path'] = $request->path();
        $this->data['kode_anggaran'] = config('lookup.kode_anggaran');
        $this->data['sasaran'] = config('lookup.monev_sasaran');
        $this->data['breadcrumps'] = breadcrumps_dir_masyarakat($request->route()->getName());
        return view('pemberdayaan.masyarakat.psm_supervisi.index_psmSupervisi',$this->data);
    }

    public function deletepsmSupervisi(Request $request){
      $id = $request->input('id');
      if($id){
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/psmsupervisi/'.$id,'DELETE');
            $this->form_params['delete_id'] = $id;
            $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Peran Serta Masyarakat - Monitoring dan Evaluasi';
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
        $data_request = ['status'=>'error','message'=>'Data Tes Narkoba Gagal Dihapus'];
        return $data_request;
      }
    }
    public function addpsmSupervisi(Request $request){
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $this->data['title']="psmsupervisi";

        $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
        $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $propkab;
        $this->data['hasil_penilaian'] = config('lookup.hasil_penilaian');
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        $this->data['sasaran'] = config('lookup.monev_sasaran');
        $this->data['kode_anggaran'] = config('lookup.kode_anggaran');
        $this->data['breadcrumps'] = breadcrumps_dir_masyarakat($request->route()->getName());
        return view('pemberdayaan.masyarakat.psm_supervisi.add_psmSupervisi',$this->data);
    }

    public function editpsmSupervisi(Request $request){
        $id = $request->id;
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

       $requestDataDetail= $client->request('GET', $baseUrl.'/api/psmsupervisi/'.$id,
           [
               'headers' =>
               [
                   'Authorization' => 'Bearer '.$token
               ]
           ]
       );

      $dataDetail = json_decode($requestDataDetail->getBody()->getContents(), true);

      if($dataDetail['code'] == 200 && $dataDetail['status'] != 'error'){
        $this->data['data_detail'] = $dataDetail;
        $anggaran = $dataDetail['data']['anggaran_id'];
        if ($anggaran) {
          $data_anggaran = $this->globalGetAnggaran($token, $dataDetail['data']['anggaran_id']);
          if($data_anggaran['code'] == 200 && $data_anggaran['status'] != 'error'){
            $this->data['data_anggaran'] = $data_anggaran['data'];
          }else{
            $this->data['data_anggaran'] = [];
          }
        }
      }else{
        $this->data['data_detail'] = [];
      }


      $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
      $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

      $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
      $this->data['propkab'] = $propkab;
      $this->data['hasil_penilaian'] = config('lookup.hasil_penilaian');
      $this->data['id'] = $id;
      $this->data['sasaran'] = config('lookup.monev_sasaran');
      $this->data['kode_anggaran'] = config('lookup.kode_anggaran');
      $this->data['breadcrumps'] = breadcrumps_dir_masyarakat($request->route()->getName());
      return view('pemberdayaan.masyarakat.psm_supervisi.edit_psmSupervisi',$this->data);
    }

    public function inputpsmSupervisi(Request $request){

       $baseUrl = URL::to($this->urlapi());
//       $baseUrl = URL::to('/');
       $token = $request->session()->get('token');
       $anggaran = '';
       $client = new Client();
       if ($request->input('kodesumberanggaran')=="DIPA") {
          if($request->kd_anggaran){
             $requestAnggaran = $client->request('POST', $baseUrl.'/api/anggaran',
                     [
                         'headers' =>
                         [
                             'Authorization' => 'Bearer '.$token
                         ],
                         'form_params' => [
                             'kode_anggaran' => $request->input('akode_anggaran'),
                             'sasaran' => $request->input('asasaran'),
                             'pagu' => $request->input('apagu'),
                             'target_output' => $request->input('atarget_output'),
                             'satuan_output' => $request->input('asatuan_output'),
                             'tahun' => $request->input('atahun'),
                             'satker_code' => $request->input('asatker_code'),
                             'refid_anggaran' => $request->input('arefid_anggaran'),

                         ]
                     ]
                 );

              $resultAnggaran = json_decode($requestAnggaran->getBody()->getContents(), true);
              if($resultAnggaran['code'] == 200 && $resultAnggaran['status'] != 'error'){
                $anggaran = $resultAnggaran['data']['eventID'];
              }else{
                $anggaran = '';
              }
          }else{
            $anggaran = '';
          }
        } else {
          $anggaran = '';
        }

        $jumlah_instansi = count($request->input('group-c'));
        $peserta = 0;
        if ($jumlah_instansi > 0) {
            foreach ($request->input('group-c') as $c1 => $r1) {
                $peserta = $peserta + $r1['list_jumlah_peserta'];
            }
        }
        $penilaian = $request->input('penilaian');
        $array_penilaian = [];
        if(count($penilaian) > 0 ){
          for($i = 0 ; $i < count($penilaian); $i++){
            $p = $penilaian[$i];
            if($p['nama_instansi'] || $p['hasil_penilaian']){
              $array_penilaian[] = ['nama_instansi'=>$p['nama_instansi'],'hasil_penilaian'=>$p['hasil_penilaian']];
            }
          }
        }
        $json_penilaian = "";
        if(count($array_penilaian)>0){
          $json_penilaian = json_encode($array_penilaian);
        }
        $requestSupervisi = $client->request('POST', $baseUrl.'/api/psmsupervisi',
               [
                   'headers' =>
                   [
                       'Authorization' => 'Bearer '.$token
                   ],
                   'form_params' => [
                       'idpelaksana' => $request->input('idpelaksana'),
                       'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                       'kodesasaran' => $request->input('sasaran'),
                       'jumlah_instansi' => $jumlah_instansi,
                       'meta_instansi' => json_encode($request->input('group-c')),
                       'jumlah_peserta' => $peserta,
                       'panitia_monev' => $request->input('panitia_monev'),
                       'kodesumberanggaran' => $request->input('kodesumberanggaran'),
                       'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                       'periode_tahun' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                       'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                       'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                       'anggaran_id' => $anggaran,
                       'meta_penilaian' => $json_penilaian,
                   ]
               ]
           );
        $fileMessage = "";
       $result = json_decode($requestSupervisi->getBody()->getContents(), true);

       $this->form_params = array('idpelaksana' => $request->input('idpelaksana'),
       'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'kodesasaran' => $request->input('sasaran'),
       'jumlah_instansi' => $jumlah_instansi,
       'meta_instansi' => json_encode($request->input('group-c')),
       'jumlah_peserta' => $peserta,
       'panitia_monev' => $request->input('panitia_monev'),
       'kodesumberanggaran' => $request->input('kodesumberanggaran'),
       'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'periode_tahun' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'anggaran_id' => $anggaran,
       'meta_penilaian' => $json_penilaian);

       $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Peran Serta Masyarakat - Monitoring dan Evaluasi';
       $trail['audit_event'] = 'post';
       $trail['audit_value'] = json_encode($this->form_params);
       $trail['audit_url'] = $request->url();
       $trail['audit_ip_address'] = $request->ip();
       $trail['audit_user_agent'] = $request->userAgent();
       $trail['audit_message'] = $result['comment'];
       $trail['created_at'] = date("Y-m-d H:i:s");
       $trail['created_by'] = $request->session()->get('id');

       $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

       if( ($result ['code'] == 200) && ($result['status'] != 'error')){
         $inputId = $result['data']['eventID'];
         if ($request->file('file_upload') ){
             $fileName = date('Y-m-d').'_'.$inputId.'-'.$request->file('file_upload')->getClientOriginalName();
             try {
               $request->file('file_upload')->storeAs('PsmSupervisi', $fileName);
               $requestfile = $client->request('PUT', $baseUrl.'/api/psmsupervisi/'.$inputId,
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
               $resultFile = json_decode($requestfile->getBody()->getContents(), true);
               if($resultFile['code'] == 200 && $resultFile['status'] != 'error'){
                  $fileMessage = "";
               }else{
                  $fileMessage = " Dengan file gagal diunggah.";
               }
             }catch(\Exception $e){
              $fileMessage = " Dengan file gagal diunggah.";
             }
         }else{
          $fileMessage = " Dengan file gagal diunggah.";
         }
         $this->kelengkapan_supervisi($inputId);
         $this->data['status'] = 'success';
         $this->data['message'] = "Data Kegiatan Monitoring dan Evaluasi Berhasil Ditambahkan ".$fileMessage;
       }else{
         $this->data['status'] = 'error';
         $this->data['message'] = "Data Kegiatan Monitoring dan Evaluasi Gagal Ditambahkan ";
       }

       return redirect(route('psm_supervisi'))->with('status',$this->data);
    }

    public function updatepsmSupervisi(Request $request){
          $id = $request->input('id');

          $baseUrl = URL::to($this->urlapi());
//          $baseUrl = URL::to('/');
          $token = $request->session()->get('token');

          $client = new Client();

          $jumlah_instansi = count($request->input('group-c'));
          $peserta = 0;
          if ($jumlah_instansi > 0) {
              foreach ($request->input('group-c') as $c1 => $r1) {
                  $peserta = $peserta + $r1['list_jumlah_peserta'];
              }
          }
          $form_params = ['idpelaksana' => $request->input('idpelaksana'),
             'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
             'kodesasaran' => $request->input('sasaran'),
             'jumlah_instansi' => $jumlah_instansi,
             'meta_instansi' => json_encode($request->input('group-c')),
             'jumlah_peserta' => $peserta,
             'panitia_monev' => $request->input('panitia_monev'),
             'kodesumberanggaran' => $request->input('kodesumberanggaran'),
             'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
             'periode_tahun' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
             'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
             'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan'))))
          ];
          $penilaian = $request->input('penilaian');
          $array_penilaian = [];
          if(count($penilaian) > 0 ){
            for($i = 0 ; $i < count($penilaian); $i++){
              $p = $penilaian[$i];
              if($p['nama_instansi'] || $p['hasil_penilaian']){
                $array_penilaian[] = ['nama_instansi'=>$p['nama_instansi'],'hasil_penilaian'=>$p['hasil_penilaian']];
              }
            }
          }
          $json_penilaian = "";
          if(count($array_penilaian)>0){
            $json_penilaian = json_encode($array_penilaian);
          }

          $form_params['meta_penilaian'] = $json_penilaian;
          if ($request->input('kodesumberanggaran')=="DIPA") {
          if($request->kd_anggaran){
             $requestAnggaran = $client->request('POST', $baseUrl.'/api/anggaran',
                     [
                         'headers' =>
                         [
                             'Authorization' => 'Bearer '.$token
                         ],
                         'form_params' => [
                             'kode_anggaran' => $request->input('akode_anggaran'),
                             'sasaran' => $request->input('asasaran'),
                             'pagu' => $request->input('apagu'),
                             'target_output' => $request->input('atarget_output'),
                             'satuan_output' => $request->input('asatuan_output'),
                             'tahun' => $request->input('atahun'),
                             'satker_code' => $request->input('asatker_code'),
                             'refid_anggaran' => $request->input('arefid_anggaran'),

                         ]
                     ]
                 );

              $resultAnggaran = json_decode($requestAnggaran->getBody()->getContents(), true);
              if($resultAnggaran['code'] == 200 && $resultAnggaran['status'] != 'error'){
                $anggaran = $resultAnggaran['data']['eventID'];
                $form_params['anggaran_id'] = $anggaran;
              }else{
                $anggaran = '';
              }
          }else{
            $anggaran = '';
          }
        } else {
          $anggaran = '';
        }
        $fileMessage = "";
        if ($request->file('file_upload')){
             $fileName = date('Y-m-d').'_'.$id.'-'.$request->file('file_upload')->getClientOriginalName();
             try {
              $request->file('file_upload')->storeAs('PsmSupervisi', $fileName);
              $form_params['file_upload'] = $fileName ;
             }catch(\Exception $e){
                $e->getMessage();
                $fileMessage = "Dengan File Gagal Diperbarui";
             }
          }else{
            $fileMessage = "";
          }

          $requestSupervisi = $client->request('PUT', $baseUrl.'/api/psmsupervisi/'.$id,
               [
                   'headers' =>
                   [
                       'Authorization' => 'Bearer '.$token
                   ],
                   'form_params' =>  $form_params
               ]
           );

          $result = json_decode($requestSupervisi->getBody()->getContents(), true);

          $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Peran Serta Masyarakat - Monitoring dan Evaluasi';
          $trail['audit_event'] = 'put';
          $trail['audit_value'] = json_encode($form_params);
          $trail['audit_url'] = $request->url();
          $trail['audit_ip_address'] = $request->ip();
          $trail['audit_user_agent'] = $request->userAgent();
          $trail['audit_message'] = $result['comment'];
          $trail['created_at'] = date("Y-m-d H:i:s");
          $trail['created_by'] = $request->session()->get('id');

          $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

          if($result['code'] == 200 && $result['status'] != 'error'){
            $this->kelengkapan_supervisi($id);
            $this->data['status'] = "success";
            $this->data['message'] = "Data Kegiatan Monitoring dan Evaluasi Berhasil Diperbarui ".$fileMessage;
          }else{
            $this->data['status'] = "error";
            $this->data['message'] = "Data Kegiatan Monitoring dan Evaluasi Gagal Diperbarui ";
          }
          return back()->with('status',$this->data);
      }

    public function psmOrmas(Request $request){
        $client = new Client();
        if ($request->input('page')) {
         $page = $request->input('page');
        } else {
         $page = 1;
        }
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');


        $requestPsmLsm = $client->request('GET', $baseUrl.'/api/psmlsm?page='.$page.'&id_wilayah='.$request->session()->get('wilayah'),
           [
               'headers' =>
               [
                   'Authorization' => 'Bearer '.$token
               ]
           ]
        );

        $PsmLsm = json_decode($requestPsmLsm->getBody()->getContents(), true);

        $page = $PsmLsm['paginate'];

        $this->data['data_psmlsm'] = $PsmLsm['data'];
        $this->data['title'] = "psmlsm";
        $this->data['token'] = $token;

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['path'] = $request->path();
        $this->data['page'] = $page;
        $this->data['breadcrumps'] = breadcrumps_dir_masyarakat($request->route()->getName());
        return view('pemberdayaan.masyarakat.psm_ormas.index_psmOrmas',$this->data);
    }

    public function addpsmOrmas(Request $request){
      $client = new Client();
      $baseUrl = URL::to($this->urlapi());
//      $baseUrl = URL::to('/');
      $this->data['title']="psmsupervisi";

      $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
      $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

      $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
      $this->data['propkab'] = $propkab;

      $user_id = Auth::user()->user_id;
      $detail = MainModel::getUserDetail($user_id);
      $data['data_detail'] = $detail;
      $this->data['breadcrumps'] = breadcrumps_dir_masyarakat($request->route()->getName());
        return view('pemberdayaan.masyarakat.psm_ormas.add_psmOrmas',$this->data);
    }

    public function editpsmOrmas(Request $request){
        $id = $request->id;
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

       $requestDataDetail= $client->request('GET', $baseUrl.'/api/psmlsm/'.$id,
           [
               'headers' =>
               [
                   'Authorization' => 'Bearer '.$token
               ]
           ]
       );

       $dataDetail = json_decode($requestDataDetail->getBody()->getContents(), true);

       $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
       $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

       $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
       $this->data['propkab'] = $propkab;

       $this->data['data_detail'] = $dataDetail;
       $this->data['id'] = $id;
       $this->data['breadcrumps'] = breadcrumps_dir_masyarakat($request->route()->getName());
        return view('pemberdayaan.masyarakat.psm_ormas.edit_psmOrmas',$this->data);
    }

    public function inputpsmOrmas(Request $request){

       $baseUrl = URL::to($this->urlapi());
//       $baseUrl = URL::to('/');
       $token = $request->session()->get('token');

       $client = new Client();

        $requestPsmLsm = $client->request('POST', $baseUrl.'/api/psmlsm',
             [
                 'headers' =>
                 [
                     'Authorization' => 'Bearer '.$token
                 ],
                 'form_params' => [
                     'idpelaksana' => $request->input('idpelaksana'),
                     'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                     'jenis_kegiatan' => $request->input('jenis_kegiatan'),
                     //'kodesasaran' => $request->input('sasaran'),
                     //'jumlah_instansi' => $jumlah_instansi,
                     'meta_instansi' => $request->input('meta_instansi'),
                     'jumlah_peserta' => $request->input('jumlah_peserta'),
                     'lokasi_kegiatan' => $request->input('lokasi_kegiatan'),
                     //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
                     'lokasi_kegiatan_idkabkota' => $request->input('lokasi_kegiatan_idkabkota'),
                     'narasumber' => $request->input('narasumber'),
                     'panitia_monev' => $request->input('panitia_monev'),
                     'materi' => $request->input('materi'),
                     //'kodesumberanggaran' => $request->input('kodesumberanggaran'),
                     // 'file_upload' => $anggaran,
                     // 'created_at' => $anggaran,
                     // 'created_by' => $anggaran,
                     'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                     'periode_tahun' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                     'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                     'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                     //'anggaran_id' => $anggaran,
                 ]
             ]
        );

       $result = json_decode($requestPsmLsm->getBody()->getContents(), true);

       return redirect('pemberdayaan/dir_masyarakat/psm_ormas/');

    }

    public function updatepsmOrmas(Request $request){
        $id = $request->input('id');

        $baseUrl = URL::to($this->urlapi());
//        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $client = new Client();

        $requestPsmLsm = $client->request('PUT', $baseUrl.'/api/psmlsm/'.$id,
             [
                 'headers' =>
                 [
                     'Authorization' => 'Bearer '.$token
                 ],
                 'form_params' => [
                     'idpelaksana' => $request->input('idpelaksana'),
                     'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                     'jenis_kegiatan' => $request->input('jenis_kegiatan'),
                     //'kodesasaran' => $request->input('sasaran'),
                     //'jumlah_instansi' => $jumlah_instansi,
                     'meta_instansi' => $request->input('meta_instansi'),
                     'jumlah_peserta' => $request->input('jumlah_peserta'),
                     'lokasi_kegiatan' => $request->input('lokasi_kegiatan'),
                     //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
                     'lokasi_kegiatan_idkabkota' => $request->input('lokasi_kegiatan_idkabkota'),
                     'narasumber' => $request->input('narasumber'),
                     'panitia_monev' => $request->input('panitia_monev'),
                     'materi' => $request->input('materi'),
                     //'kodesumberanggaran' => $request->input('kodesumberanggaran'),
                     // 'file_upload' => $anggaran,
                     // 'created_at' => $anggaran,
                     // 'created_by' => $anggaran,
                     'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                     'periode_tahun' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                     'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                     'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                     //'anggaran_id' => $anggaran,
                 ]
             ]
        );

        $result = json_decode($requestPsmLsm->getBody()->getContents(), true);

        return redirect('pemberdayaan/dir_masyarakat/psm_ormas/');
    }

    /*Rapat Kerja Pemetaan*/
    public function rapatKerja(Request $request){
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
          }elseif($tipe == 'jml_peserta'){
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
          }else{
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
          $get = $request->except(['page','tgl_from','tgl_to','jml_to','jml_from','limit']);
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
              }else if($value == 'jml_peserta'){
                if($request->jml_to){
                  $kondisi .= '&jml_to='.$request->jml_to;
                  $this->selected['jml_to'] = $request->jml_to;
                }else if(!$request->jml_to){
                  $kondisi .='';
                }

                if($request->jml_from){
                  $kondisi .= '&jml_from='.$request->jml_from;
                  $this->selected['jml_from'] = $request->jml_from;
                }else if(!$request->jml_from){
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
        $datas = execute_api_json('api/rapatKerjaPemetaan?type=peran_serta&'.$limit.'&'.$offset.$kondisi.'&id_wilayah='.$request->session()->get('wilayah'),'get');

        if($datas->code == 200 && $datas->status != 'error'){
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;
        }else{
            $this->data['data'] = [];
            $total_item = 0;
        }


        $this->data['route'] = $request->route()->getName();

        /*data filter*/
        $sasaran = execute_api('/api/lookup/sasaran','GET');
        if( ($sasaran['code'] == 200) && ( $sasaran ['status'] != 'error')){
          $this->data['sasaran']=$sasaran['data'];
        }else{
          $this->data['sasaran']= [];
        }
        $filtering = false;
        if($kondisi){
          $filter = '&'.$limit.$kondisi;
          $filtering = true;
          $this->data['kondisi'] = '?type=peran_serta&'.$offset.$kondisi.'&'.$limit;
        }else{
          $filter = '/';
          $filtering = false;
          $this->data['kondisi'] = '?type=peran_serta&'.$offset;
        }

        $this->data['title'] = 'Rapat Kerja Pemetaan';
        $this->data['path'] = $request->path();
        $this->data['delete_route'] = 'delete_alv_rapat_kerja_pemetaan';
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
        $this->data['breadcrumps'] = breadcrump_rapat_kerja($request->route()->getName());
        return view('pemberdayaan.masyarakat.rapat_kerja.rapat_kerja_pemetaan',$this->data);
    }
    public function editRapatKerja(Request $request){
      $id = $request->id;
      $token = $request->session()->get('token');
      $datas = execute_api_json('api/rapatKerjaPemetaan/'.$id,'get');
      if($datas->code == 200 && $datas->status != 'error'){
          if ($datas->data->anggaran_id) {
            $data_anggaran = $this->globalGetAnggaran($token, $datas->data->anggaran_id);
            if($data_anggaran['code'] == 200 && $data_anggaran['status'] != 'error'){
              $this->data['data_anggaran'] = $data_anggaran['data'];
            }else{
              $this->data['data_anggaran'] = [];
            }
          }else{
            $this->data['data_anggaran'] = [];
          }
          $this->data['data'] = $datas->data;
      }else{
          $this->data['data'] = [];
      }
      $this->data['breadcrumps'] = breadcrump_rapat_kerja($request->route()->getName());
      $sasaran = execute_api('/api/lookup/sasaran','GET');
      if( ($sasaran['code'] == 200) && ( $sasaran ['status'] != 'error')){
        $this->data['sasaran']=$sasaran['data'];
      }else{
        $this->data['sasaran']= [];
      }
      $this->data['sumber_anggaran'] = config('lookup.sumber_anggaran');
      return view('pemberdayaan.masyarakat.rapat_kerja.edit_rapat_kerja_pemetaan',$this->data);
    }
    public function addRapatKerja(Request $request){
      if($request->isMethod('post')){
        $form_params = $request->except(['_token','kd_anggaran','asatker_code','akode_anggaran','arefid_anggaran','atahun','atarget_output','asatuan_output','apagu','asasaran']);
        $form_params['created_by'] = Auth::user()->user_id;
        $form_params['type'] = 'peran_serta';
        if($request['tanggal_pemetaan'] ){
          $date = explode('/', $request['tanggal_pemetaan']);
          $form_params['tanggal_pemetaan'] = $date[2].'-'.$date[1].'-'.$date[0];
        }

        $token = $request->session()->get('token');
        $anggaran = '';
        $client = new Client();
        if ($request->input('kode_sumber_anggaran')=="dipa") {
          if($request->kd_anggaran){
             $requestAnggaran = $client->request('POST', url('/api/anggaran'),
                     [
                         'headers' =>
                         [
                             'Authorization' => 'Bearer '.$token
                         ],
                         'form_params' => [
                             'kode_anggaran' => $request->input('akode_anggaran'),
                             'sasaran' => $request->input('asasaran'),
                             'pagu' => $request->input('apagu'),
                             'target_output' => $request->input('atarget_output'),
                             'satuan_output' => $request->input('asatuan_output'),
                             'tahun' => $request->input('atahun'),
                             'satker_code' => $request->input('asatker_code'),
                             'refid_anggaran' => $request->input('arefid_anggaran'),

                         ]
                     ]
                 );

             $resultAnggaran = json_decode($requestAnggaran->getBody()->getContents(), true);
             if($resultAnggaran['code'] == 200 && $resultAnggaran['status'] != 'error'){
              $anggaran = $resultAnggaran['data']['eventID'];
             }else{
              $anggaran ="";
             }
          }else{
            $anggaran ="";
          }
        } else {
          $anggaran = '';
        }
        $form_params['anggaran_id'] = $anggaran;
        $query = execute_api_json('/api/rapatKerjaPemetaan/','POST',$form_params);

        $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Peran Serta Masyarakat - Rapat Kerja Pemetaan';
        $trail['audit_event'] = 'post';
        $trail['audit_value'] = json_encode($form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $query->comment;
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

        if( ($query->code == 200) && ($query->status != 'error') ){
          $this->data['status'] = 'success';
          $this->data['message'] = 'Data Rapat Kerja Pemetaan Berhasil Ditambahkan';
          $newId = $query->data->eventID;
          $this->kelengkapan_psm_rapatkerja($newId);
        }else{
          $this->data['status'] = 'error';
          $this->data['message'] = 'Data Rapat Kerja Pemetaan Gagal Ditambahkan';
        }
        return redirect(route('rapat_kerja_pemetaan'))->with('status',$this->data);

      }else{
        $this->data['breadcrumps'] = breadcrump_rapat_kerja($request->route()->getName());
        $sasaran = execute_api('/api/lookup/sasaran','GET');
        if( ($sasaran['code'] == 200) && ( $sasaran ['status'] != 'error')){
          $this->data['sasaran']=$sasaran['data'];
        }else{
          $this->data['sasaran']= [];
        }
        $this->data['sumber_anggaran'] = config('lookup.sumber_anggaran');

        return view('pemberdayaan.masyarakat.rapat_kerja.add_rapat_kerja_pemetaan',$this->data);
      }

    }
    public function updateRapatKerja(Request $request){
        $id = $request->id;
        $form_params = $request->except(['_token','id','kode_anggaran','kd_anggaran','asatker_code','akode_anggaran','arefid_anggaran','atahun','atarget_output','asatuan_output','apagu','asasaran']);
        $form_params['created_by'] = Auth::user()->user_id;
        $form_params['type'] = 'peran_serta';
        if($request['tanggal_pemetaan'] ){
          $date = explode('/', $request['tanggal_pemetaan']);
          $form_params['tanggal_pemetaan'] = $date[2].'-'.$date[1].'-'.$date[0];
        }
        $token = $request->session()->get('token');
        $anggaran = '';
        $client = new Client();
        if ($request->input('kode_sumber_anggaran')=="dipa") {
          if($request->kd_anggaran){
             $requestAnggaran = $client->request('POST', url('/api/anggaran'),
                     [
                         'headers' =>
                         [
                             'Authorization' => 'Bearer '.$token
                         ],
                         'form_params' => [
                             'kode_anggaran' => $request->input('akode_anggaran'),
                             'sasaran' => $request->input('asasaran'),
                             'pagu' => $request->input('apagu'),
                             'target_output' => $request->input('atarget_output'),
                             'satuan_output' => $request->input('asatuan_output'),
                             'tahun' => $request->input('atahun'),
                             'satker_code' => $request->input('asatker_code'),
                             'refid_anggaran' => $request->input('arefid_anggaran'),

                         ]
                     ]
                 );

             $resultAnggaran = json_decode($requestAnggaran->getBody()->getContents(), true);
             if($resultAnggaran['code'] == 200 && $resultAnggaran['status'] != 'error'){
              $anggaran = $resultAnggaran['data']['eventID'];
              $form_params['anggaran_id'] = $anggaran;
             }else{
              $anggaran ="";
             }
          }else{
            $anggaran ="";
          }
        } else {
          $anggaran = '';
        }

        $query = execute_api_json('/api/rapatKerjaPemetaan/'.$id,'PUT',$form_params);

        $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Peran Serta Masyarakat - Rapat Kerja Pemetaan';
        $trail['audit_event'] = 'put';
        $trail['audit_value'] = json_encode($form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $query->comment;
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

        if( ($query->code == 200) && ($query->status != 'error') ){
          $this->kelengkapan_psm_rapatkerja($id);
          $this->data['status'] = 'success';
          $this->data['message'] = 'Data Rapat Kerja Pemetaan Berhasil Diperbarui';
        }else{
          $this->data['status'] = 'error';
          $this->data['message'] = 'Data Rapat Kerja Pemetaan Gagal Diperbarui';
        }
        return back()->with('status',$this->data);
    }
    public function deleteRapatKerja(Request $request){
        if ($request->ajax()) {
            $id = $request->id;
            if($id){
              $data_request = execute_api('api/rapatKerjaPemetaan/'.$id,'DELETE');
              $this->form_params['delete_id'] = $id;
              $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Peran Serta Masyarakat - Rapat Kerja Pemetaan';
              $trail['audit_event'] = 'delete';
              $trail['audit_value'] = json_encode($this->form_params);
              $trail['audit_url'] = $request->url();
              $trail['audit_ip_address'] = $request->ip();
              $trail['audit_user_agent'] = $request->userAgent();
              $trail['audit_message'] = $data_request['comment'];
              $trail['created_at'] = date("Y-m-d H:i:s");
              $trail['created_by'] = $request->session()->get('id');

              $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

            }else{
              $data_request = ['code'=>200,'status'=>'error'];
            }
            return $data_request;
        }
    }


    /*Rapat Kerja Pemetaan Development*/
    public function rapatKerjaDevelopment(Request $request){
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
          }elseif($tipe == 'jml_peserta'){
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
          }else{
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
          $get = $request->except(['page','tgl_from','tgl_to','jml_to','jml_from','limit']);
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
              }else if($value == 'jml_peserta'){
                if($request->jml_to){
                  $kondisi .= '&jml_to='.$request->jml_to;
                  $this->selected['jml_to'] = $request->jml_to;
                }else if(!$request->jml_to){
                  $kondisi .='';
                }

                if($request->jml_from){
                  $kondisi .= '&jml_from='.$request->jml_from;
                  $this->selected['jml_from'] = $request->jml_from;
                }else if(!$request->jml_from){
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
        $datas = execute_api_json('api/rapatKerjaPemetaan?type=alternative&'.$limit.'&'.$offset.$kondisi.'&id_wilayah='.$request->session()->get('wilayah'),'get');

        if($datas->code == 200 && $datas->status != 'error'){
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;
        }else{
            $this->data['data'] = [];
            $total_item = 0;
        }


        $this->data['route'] = $request->route()->getName();

        /*data filter*/
        $sasaran = execute_api('/api/lookup/sasaran','GET');
        if( ($sasaran['code'] == 200) && ( $sasaran ['status'] != 'error')){
          $this->data['sasaran']=$sasaran['data'];
        }else{
          $this->data['sasaran']= [];
        }
        $filtering = false;
        if($kondisi){
          $filter = '&'.$limit.$kondisi;
          $filtering = true;
          $this->data['kondisi'] = '?type=alternative&'.$offset.$kondisi.'&'.$limit;
        }else{
          $filter = '/';
          $filtering = false;
          $this->data['kondisi'] = '?type=alternative&'.$offset;
        }

        $this->data['title'] = 'Rapat Kerja Pemetaan';
        $this->data['path'] = $request->path();
        $this->data['delete_route'] = 'delete_alv_rapat_kerja_pemetaan';
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
        $this->data['breadcrumps'] = breadcrump_rapat_kerja($request->route()->getName());

        return view('pemberdayaan.alternative_development.rapat_kerja.rapat_kerja_pemetaan',$this->data);
    }
    public function editRapatKerjaDevelopment(Request $request){
      $id = $request->id;
      $token = $request->session()->get('token');
      $datas = execute_api_json('api/rapatKerjaPemetaan/'.$id,'get');
      if($datas->code == 200 && $datas->status != 'error'){
          if ($datas->data->anggaran_id) {
            $data_anggaran = $this->globalGetAnggaran($token, $datas->data->anggaran_id);
            if($data_anggaran['code'] == 200 && $data_anggaran['status'] != 'error'){
              $this->data['data_anggaran'] = $data_anggaran['data'];
            }else{
              $this->data['data_anggaran'] = [];
            }
          }else{
            $this->data['data_anggaran'] = [];
          }
          $this->data['data'] = $datas->data;
      }else{
          $this->data['data'] = [];
      }
      $this->data['breadcrumps'] = breadcrump_rapat_kerja($request->route()->getName());
      $sasaran = execute_api('/api/lookup/sasaran','GET');
      if( ($sasaran['code'] == 200) && ( $sasaran ['status'] != 'error')){
        $this->data['sasaran']=$sasaran['data'];
      }else{
        $this->data['sasaran']= [];
      }
      $this->data['sumber_anggaran'] = config('lookup.sumber_anggaran');
      return view('pemberdayaan.alternative_development.rapat_kerja.edit_rapat_kerja_pemetaan',$this->data);
    }
    public function addRapatKerjaDevelopment(Request $request){
      if($request->isMethod('post')){
        $form_params = $request->except(['_token','kd_anggaran','asatker_code','akode_anggaran','arefid_anggaran','atahun','atarget_output','asatuan_output','apagu','asasaran']);
        $form_params['created_by'] = Auth::user()->user_id;
        $form_params['type'] = 'alternative';
        if($request['tanggal_pemetaan'] ){
          $date = explode('/', $request['tanggal_pemetaan']);
          $form_params['tanggal_pemetaan'] = $date[2].'-'.$date[1].'-'.$date[0];
        }

        $token = $request->session()->get('token');
        $anggaran = '';
        $client = new Client();
        if ($request->input('kode_sumber_anggaran')=="dipa") {
          if($request->kd_anggaran){
             $requestAnggaran = $client->request('POST', url('/api/anggaran'),
                     [
                         'headers' =>
                         [
                             'Authorization' => 'Bearer '.$token
                         ],
                         'form_params' => [
                             'kode_anggaran' => $request->input('akode_anggaran'),
                             'sasaran' => $request->input('asasaran'),
                             'pagu' => $request->input('apagu'),
                             'target_output' => $request->input('atarget_output'),
                             'satuan_output' => $request->input('asatuan_output'),
                             'tahun' => $request->input('atahun'),
                             'satker_code' => $request->input('asatker_code'),
                             'refid_anggaran' => $request->input('arefid_anggaran'),

                         ]
                     ]
                 );

             $resultAnggaran = json_decode($requestAnggaran->getBody()->getContents(), true);
             if($resultAnggaran['code'] == 200 && $resultAnggaran['status'] != 'error'){
              $anggaran = $resultAnggaran['data']['eventID'];
             }else{
              $anggaran ="";
             }
          }else{
            $anggaran ="";
          }
        } else {
          $anggaran = '';
        }
        $form_params['anggaran_id'] = $anggaran;
        $query = execute_api_json('/api/rapatKerjaPemetaan/','POST',$form_params);

        $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Alternative Development - Rapat Kerja Pemetaan';
        $trail['audit_event'] = 'post';
        $trail['audit_value'] = json_encode($form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $query->comment;
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

        if( ($query->code == 200) && ($query->status != 'error') ){
          $this->data['status'] = 'success';
          $this->data['message'] = 'Data Rapat Kerja Pemetaan Berhasil Ditambahkan';
          $newId = $query->data->eventID;
          $this->kelengkapan_psm_rapatkerja($newId);
        }else{
          $this->data['status'] = 'error';
          $this->data['message'] = 'Data Rapat Kerja Pemetaan Gagal Ditambahkan';
        }
        return redirect(route('alv_rapat_kerja_pemetaan'))->with('status',$this->data);

      }else{
        $this->data['breadcrumps'] = breadcrump_rapat_kerja($request->route()->getName());
        $sasaran = execute_api('/api/lookup/sasaran','GET');
        if( ($sasaran['code'] == 200) && ( $sasaran ['status'] != 'error')){
          $this->data['sasaran']=$sasaran['data'];
        }else{
          $this->data['sasaran']= [];
        }
        $this->data['sumber_anggaran'] = config('lookup.sumber_anggaran');

        return view('pemberdayaan.alternative_development.rapat_kerja.add_rapat_kerja_pemetaan',$this->data);
      }
    }
    public function updateRapatKerjaDevelopment(Request $request){
      $id = $request->id;
        $form_params = $request->except(['_token','id','kode_anggaran','kd_anggaran','asatker_code','akode_anggaran','arefid_anggaran','atahun','atarget_output','asatuan_output','apagu','asasaran']);
        $form_params['created_by'] = Auth::user()->user_id;
        $form_params['type'] = 'alternative';
        if($request['tanggal_pemetaan'] ){
          $date = explode('/', $request['tanggal_pemetaan']);
          $form_params['tanggal_pemetaan'] = $date[2].'-'.$date[1].'-'.$date[0];
        }
        $token = $request->session()->get('token');
        $anggaran = '';
        $client = new Client();
        if ($request->input('kode_sumber_anggaran')=="dipa") {
          if($request->kd_anggaran){
             $requestAnggaran = $client->request('POST', url('/api/anggaran'),
                     [
                         'headers' =>
                         [
                             'Authorization' => 'Bearer '.$token
                         ],
                         'form_params' => [
                             'kode_anggaran' => $request->input('akode_anggaran'),
                             'sasaran' => $request->input('asasaran'),
                             'pagu' => $request->input('apagu'),
                             'target_output' => $request->input('atarget_output'),
                             'satuan_output' => $request->input('asatuan_output'),
                             'tahun' => $request->input('atahun'),
                             'satker_code' => $request->input('asatker_code'),
                             'refid_anggaran' => $request->input('arefid_anggaran'),

                         ]
                     ]
                 );

             $resultAnggaran = json_decode($requestAnggaran->getBody()->getContents(), true);
             if($resultAnggaran['code'] == 200 && $resultAnggaran['status'] != 'error'){
              $anggaran = $resultAnggaran['data']['eventID'];
              $form_params['anggaran_id'] = $anggaran;
             }else{
              $anggaran ="";
             }
          }else{
            $anggaran ="";
          }
        } else {
          $anggaran = '';
        }

        $query = execute_api_json('/api/rapatKerjaPemetaan/'.$id,'PUT',$form_params);

        $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Alternative Development - Rapat Kerja Pemetaan';
        $trail['audit_event'] = 'put';
        $trail['audit_value'] = json_encode($form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $query->comment;
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

        if( ($query->code == 200) && ($query->status != 'error') ){
          $this->kelengkapan_psm_rapatkerja($id);
          $this->data['status'] = 'success';
          $this->data['message'] = 'Data Rapat Kerja Pemetaan Berhasil Diperbarui';
        }else{
          $this->data['status'] = 'error';
          $this->data['message'] = 'Data Rapat Kerja Pemetaan Gagal Diperbarui';
        }
        return back()->with('status',$this->data);
    }

    public function deleteRapatKerjaDevelopment(Request $request){
      if ($request->ajax()) {
        $id = $request->id;
        if($id){
          $data_request = execute_api('api/rapatKerjaPemetaan/'.$id,'DELETE');
          $this->form_params['delete_id'] = $id;
          $trail['audit_menu'] = 'Pemberdayaan Masyarakat - Direktorat Alternative Development - Rapat Kerja Pemetaan';
          $trail['audit_event'] = 'delete';
          $trail['audit_value'] = json_encode($this->form_params);
          $trail['audit_url'] = $request->url();
          $trail['audit_ip_address'] = $request->ip();
          $trail['audit_user_agent'] = $request->userAgent();
          $trail['audit_message'] = $data_request['comment'];
          $trail['created_at'] = date("Y-m-d H:i:s");
          $trail['created_by'] = $request->session()->get('id');

          $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

        }else{
          $data_request = ['code'=>200,'status'=>'error'];
        }
        return $data_request;
      }
    }
    // public function printPage(Request $request){
    //   $segment = $request->segment;
    //   $page = $request->page;
    //   $array_segments = [
    //       'pendataan_tes_narkoba'=>'tesnarkobaheader?page='.$page,
    //       'alv_rapat_kerja_pemetaan'=>'rapatKerjaPemetaan?type=alternative&page='.$page,
    //       'rapat_kerja_pemetaan'=>'rapatKerjaPemetaan?type=peran_serta&page='.$page,
    //   ];
    //   $array_titles=[
    //       'pendataan_tes_narkoba'=>'Data Kegiatan Pendataan Tes Narkoba',
    //       'alv_rapat_kerja_pemetaan'=>'Data Kegiatan Rapat Pemetaan alternative Development',
    //       'rapat_kerja_pemetaan'=>'Data Kegiatan Rapat Pemetaan Pemberdayaan Masyarakat',
    //   ];
    //   $url = 'api/'.$array_segments[$segment];
    //   $data_request = execute_api($url,'GET');
    //   $result= [];
    //   $i = 1;
    //   if($segment == 'pendataan_tes_narkoba'){
    //       if(count($data_request)>=1){
    //           $data = $data_request['data'];
    //           foreach($data as $key=>$value){
    //               $result[$key]['No'] = $i;
    //               $result[$key]['Nama Instansi'] = $value['nm_instansi'];
    //               $result[$key]['Tanggl Test'] = ( $value['tgl_test'] ? date('d/m/Y',strtotime($value['tgl_test'])) :'');
    //               $result[$key]['Jumlah Peserta'] = $value['jmlh_peserta'];
    //               $result[$key]['Sasaran'] = $value['sasaran_values'];
    //               $result[$key]['Kode Anggaran'] = $value['kodesumberanggaran'];
    //               $i = $i+1;
    //           }
    //           $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
    //           $this->printData($result, $name);
    //           // echo '<pre>';
    //           // print_r($result);
    //       }
    //   }else if( ($segment == 'alv_rapat_kerja_pemetaan') || ($segment== 'rapat_kerja_pemetaan') ){
    //       if(count($data_request)>=1){
    //           $data = $data_request['data'];
    //           foreach($data as $key=>$value){
    //               $result[$key]['No'] = $i;
    //               $result[$key]['Tanggal Pelaksanaan'] = ( isset($value['tanggal_pemetaan']) ? date('d/m/Y',strtotime($value['tanggal_pemetaan']))  : '');
    //               $result[$key]['Pelaksana'] =getInstansiName($value['id_pelaksana']);
    //               $result[$key]['Lokasi Kegiatan'] =getWilayahName($value['id_lokasi_kegiatan']);
    //               $result[$key]['Jumlah Peserta'] = $value['jumlah_peserta'];
    //               $result[$key]['Sasaran'] = $value['kode_sasaran'];
    //               $i = $i+1;
    //           }
    //           $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
    //           $this->printData($result, $name);
    //           // echo '<pre>';
    //           // print_r($result);
    //       }
    //   }else{
    //       return false;
    //   }
    // }
    public function printPage(Request $request){
      $array_segments = [
          'pendataan_anti_narkoba'=>'psmpenggiat',
          'pendataan_tes_narkoba'=>'tesnarkobaheader',
          'pendataan_pelatihan'=>'psmpelatihan',
          'psm_supervisi'=>'psmsupervisi',
          'rapat_kerja_pemetaan'=>'rapatKerjaPemetaan',
          'alv_rapat_kerja_pemetaan'=>'rapatKerjaPemetaan',
      ];

      $array_titles=[
          'pendataan_anti_narkoba'=>'Data Pengembangan Kapasitas',
          'pendataan_tes_narkoba'=>'Data Tes Narkoba',
          'pendataan_pelatihan'=>'Data Bimbingan Teknis',
          'psm_supervisi'=>'Data Kegiatan Monitoring dan Evaluasi',
          'rapat_kerja_pemetaan'=>'Data Rapat Kerja Pemetaan Peran Serta Masyarakat',
          'alv_rapat_kerja_pemetaan'=>'Data Rapat Kerja Pemetaan Alternative Development',
      ];

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
      $data_request = execute_api_json($url,'GET');

      $result= [];
      $i = $page;
      if ($segment == 'pendataan_anti_narkoba') {
        if($data_request->code == 200 && $data_request->status != 'error'){
          $data = $data_request->data;
          $i = $start_number;
          $asal_penggiat = config('lookup.instansi');
          $jenis_kegiatan_antinarkoba = config('lookup.jenis_kegiatan_antinarkoba');
          foreach($data as $key=>$d){
            $result[$key]['No'] = $i;
            $result[$key]['Pelaksana'] =$d->nm_instansi;
            $result[$key]['Tanggal'] =$d->tgl_pelaksanaan;
            $result[$key]['Asal Penggiat'] = isset( $asal_penggiat[$d->kodesasaran]) ? $asal_penggiat[$d->kodesasaran] : $d->kodesasaran;
            $result[$key]['Jenis Kegiatan']  = isset( $jenis_kegiatan_antinarkoba[$d->jenis_kegiatan]) ? $jenis_kegiatan_antinarkoba[$d->jenis_kegiatan] : $d->jenis_kegiatan; ;
            $result[$key]['Jumlah Peserta'] =$d->jumlah_peserta;
            $materi_name = "";
            $materi = json_decode($d->materi,true);
            if(is_array($materi)){
              if(count($materi)){
                for($j =0; $j < count($materi); $j++){
                  $materi_name = $materi[$j]."\\n";
                }
              }
            }else{
              $materi_name =  $d->materi;
            }
            $result[$key]['Materi'] = $materi_name;
            $result[$key]['Status'] = ($d['status'] == 'Y' ? 'Lengkap' : ($d['status'] == 'N' ? 'Tidak lengkap' : 'Tidak lengkap' ) );
            $i = $i+1;
          }
          $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
          $this->printData($result, $name);
        }else{
          echo 'tidak ada';
        }
      }else if($segment == 'pendataan_pelatihan'){
       if($data_request->code == 200 && $data_request->status != 'error'){
          $data = $data_request->data;
          $i = $start_number;
          $requestSasaran = execute_api('/api/lookup/jenis_sasaran','GET');
          if($requestSasaran['code'] == 200 && $requestSasaran['status'] != 'error'){
            $this->data['sasaran'] = $requestSasaran['data'];
          }else{
            $this->data['sasaran'] = [];
          }
          $this->data['kode_anggaran'] = config('lookup.kode_anggaran');
          foreach($data as $key=>$d){
            $result[$key]['No'] = $i;
            $result[$key]['Tanggal'] = ($d->tgl_pelaksanaan ? date('d/m/Y',strtotime($d->tgl_pelaksanaan)) : '');
            $result[$key]['Pelaksana'] =$d->nm_instansi;
            $result[$key]['Sasaran'] = isset( $sasaran[$d->kodesasaran]) ? $sasaran[$d->kodesasaran] : $d->kodesasaran;
            $meta = json_decode($d->meta_instansi);
            $json_meta = "";
            if(count($meta)){
              for($j = 0 ; $j < count($meta); $j++){
                $json_meta  .= $meta[$j]->list_nama_instansi.' / '.$meta[$j]->list_jumlah_peserta."\\n";
              }
            }
            $result[$key]['Instansi / Peserta'] = $json_meta;
            $result[$key]['Sumber Anggaran '] = ( isset($kode_anggaran[$d->kodesumberanggaran])? $kode_anggaran[$d->kodesumberanggaran] : $d->kodesumberanggaran );
            $result[$key]['Status'] = ($d->status == 'Y' ? 'Lengkap' : ($d->status == 'N' ? 'Tidak lengkap' : 'Tidak lengkap' ) );
            $i = $i+1;
          }
          $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
          $this->printData($result, $name);
        }
      }else if($segment == 'psm_supervisi'){
         if($data_request->code == 200 && $data_request->status != 'error'){
            $data = $data_request->data;
            $i = $start_number;
            $requestSasaran = execute_api('/api/lookup/jenis_sasaran','GET');
            if($requestSasaran['code'] == 200 && $requestSasaran['status'] != 'error'){
              $this->data['sasaran'] = $requestSasaran['data'];
            }else{
              $this->data['sasaran'] = [];
            }
            $this->data['kode_anggaran'] = config('lookup.kode_anggaran');
            foreach($data as $key=>$d){
              $result[$key]['No'] = $i;
              $result[$key]['Tanggal'] = ($d->tgl_pelaksanaan ? date('d/m/Y',strtotime($d->tgl_pelaksanaan)) : '');
              $result[$key]['Pelaksana'] =$d->nm_instansi;
              $result[$key]['Sasaran'] = isset( $sasaran[$d->kodesasaran]) ? $sasaran[$d->kodesasaran] : $d->kodesasaran;
              $meta = json_decode($d->meta_instansi);
              $json_meta = "";
              if(count($meta)){
                for($j = 0 ; $j < count($meta); $j++){
                  $json_meta  .= $meta[$j]->list_nama_instansi.' / '.$meta[$j]->list_jumlah_peserta."\\n";
                }
              }
              $result[$key]['Instansi / Peserta'] = $json_meta;
              $result[$key]['Sumber Anggaran '] = ( isset($kode_anggaran[$d->kodesumberanggaran])? $kode_anggaran[$d->kodesumberanggaran] : $d->kodesumberanggaran );
              $result[$key]['Status'] = ($d->status == 'Y' ? 'Lengkap' : ($d->status == 'N' ? 'Tidak lengkap' : 'Tidak lengkap' ) );
              $i = $i+1;
            }
            $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
            $this->printData($result, $name);
          }
      }else if($segment == 'rapat_kerja_pemetaan'){
          if($data_request->code == 200 && $data_request->status != 'error'){
            $data = $data_request->data;
            $i = $start_number;
            $requestSasaran = execute_api('/api/lookup/jenis_sasaran','GET');
            if( ($requestSasaran['code'] == 200) && ( $requestSasaran ['status'] != 'error')){
              $this->data['sasaran']=$requestSasaran['data'];
            }else{
              $this->data['sasaran']= [];
            }
            $kd_jnswilayah = config('lookup.kd_jnswilayah');
            foreach($data as $key=>$d){
              $result[$key]['No'] = $i;
              $result[$key]['Tanggal Pelaksanaan'] = ($d->tanggal_pemetaan ? date('d/m/Y',strtotime($d->tanggal_pemetaan)) : '');
              $result[$key]['Pelaksana'] = (isset($kd_jnswilayah[$d->kd_jnswilayah]) ? $kd_jnswilayah[$d->kd_jnswilayah] :'' ).' '.$d->wilayah_pelaksana.' '.$d->nm_wilayah_pelaksana;
              $result[$key]['Lokasi Kegiatan'] = $d->nm_jnswilayah .' '.$d->nm_wilayah;
              $result[$key]['Jumlah Peserta'] = $d->jumlah_peserta;
              $result[$key]['Sasaran'] = isset( $sasaran[$d->kode_sasaran]) ? $sasaran[$d->kode_sasaran] : $d->kode_sasaran;
              $result[$key]['Status'] = ($d->status == 'Y' ? 'Lengkap' : ($d->status == 'N' ? 'Tidak lengkap' : 'Tidak lengkap' ) );
              $i = $i+1;
            }
            $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
            $this->printData($result, $name);
          }
      }else if($segment == 'alv_rapat_kerja_pemetaan'){
          if($data_request->code == 200 && $data_request->status != 'error'){
            $data = $data_request->data;
            $i = $start_number;
            $requestSasaran = execute_api('/api/lookup/jenis_sasaran','GET');
            if( ($requestSasaran['code'] == 200) && ( $requestSasaran ['status'] != 'error')){
              $this->data['sasaran']=$requestSasaran['data'];
            }else{
              $this->data['sasaran']= [];
            }
            $kd_jnswilayah = config('lookup.kd_jnswilayah');
            foreach($data as $key=>$d){
              $result[$key]['No'] = $i;
              $result[$key]['Tanggal Pelaksanaan'] = ($d->tanggal_pemetaan ? date('d/m/Y',strtotime($d->tanggal_pemetaan)) : '');
              $result[$key]['Pelaksana'] = (isset($kd_jnswilayah[$d->kd_jnswilayah]) ? $kd_jnswilayah[$d->kd_jnswilayah] :'' ).' '.$d->wilayah_pelaksana.' '.$d->nm_wilayah_pelaksana;
              $result[$key]['Lokasi Kegiatan'] = $d->nm_jnswilayah .' '.$d->nm_wilayah;
              $result[$key]['Jumlah Peserta'] = $d->jumlah_peserta;
              $result[$key]['Sasaran'] = isset( $sasaran[$d->kode_sasaran]) ? $sasaran[$d->kode_sasaran] : $d->kode_sasaran;
              $result[$key]['Status'] = ($d->status == 'Y' ? 'Lengkap' : ($d->status == 'N' ? 'Tidak lengkap' : 'Tidak lengkap' ) );
              $i = $i+1;
            }
            $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
            $this->printData($result, $name);
          }
      }else if($segment == 'pendataan_tes_narkoba'){
          if($data_request->code == 200 && $data_request->status != 'error'){
            $data = $data_request->data;
            $i = $start_number;
            $requestSasaran = execute_api('/api/lookup/jenis_sasaran','GET');
            if( ($requestSasaran['code'] == 200) && ( $requestSasaran ['status'] != 'error')){
              $this->data['sasaran']=$requestSasaran['data'];
            }else{
              $this->data['sasaran']= [];
            }
            $kd_jnswilayah = config('lookup.kd_jnswilayah');
            foreach($data as $key=>$d){
              $result[$key]['No'] = $i;
              $result[$key]['Nama Instansi'] = $d->nm_instansi ;
              $result[$key]['Tanggal Tes'] = ($d->tgl_test ? date('d/m/Y',strtotime($d->tgl_test)) : '');
              $result[$key]['Jumlah Peserta'] = $d->jmlh_peserta;
              $result[$key]['Sasaran'] = $d->sasaran_values;
              $result[$key]['Kode Anggaran'] = (($d->kodesumberanggaran == 'DIPA') ? 'DIPA' : 'NON DIPA');
              $result[$key]['Status'] = ($d->status == 'Y' ? 'Lengkap' : ($d->status == 'N' ? 'Tidak lengkap' : 'Tidak lengkap' ) );
              $i = $i+1;
            }
            $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
            $this->printData($result, $name);
          }
      }else{
        echo 'data tidak ada ';
      }

    }

    private function kelengkapan_antinarkoba($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('dayamas_psm_penggiat')->where('id',$id)
                  ->select('tgl_selesai','idpelaksana', 'tgl_pelaksanaan' ,'kodesasaran' ,'jumlah_peserta', 'lokasi_kegiatan', 'lokasi_kegiatan_idkabkota','narasumber' ,'materi', 'jenis_kegiatan');

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
           $kelengkapan = execute_api_json('api/psmpenggiat/'.$id,'PUT',['status'=>'Y']);
        }else{
           $kelengkapan = execute_api_json('api/psmpenggiat/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }
    private function kelengkapan_bimbinganteknis($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('dayamas_psm_pelatihanpenggiat')->where('id',$id)
                  ->select('anggaran_id','tgl_pelaksanaan',  'idpelaksana', 'kodesasaran', 'jumlah_instansi' , 'meta_instansi', 'jumlah_peserta', 'lokasi_kegiatan','lokasi_kegiatan_idkabkota', 'narasumber', 'materi', 'kodesumberanggaran', 'file_upload' );
        if($query->count() > 0 ){
          $result = $query->first();
          $arr = ['anggaran_id','kodesumberanggaran'];
            foreach($result as $key=>$val){
              if( in_array($key,$arr)){
                if(!$result->kodesumberanggaran){
                  $status_kelengkapan=false;
                  break;
                }else{
                  if($result->kodesumberanggaran=='DIPA'){
                    if(!$result->anggaran_id){
                      $status_kelengkapan=false;
                      break;
                    }else{
                      continue;
                    }
                  }else{
                    continue;
                  }
                }
              }else{
                if(!$val){
                  $status_kelengkapan=false;
                  break;
                }else{
                  continue;
                }
              }
            }
          }
        if($status_kelengkapan== true){
           $kelengkapan = execute_api_json('api/psmpelatihan/'.$id,'PUT',['status'=>'Y']);
        }else{
           $kelengkapan = execute_api_json('api/psmpelatihan/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

    private function kelengkapan_supervisi($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('dayamas_psm_supervisi')->where('id',$id)
                  ->select('anggaran_id','tgl_pelaksanaan', 'idpelaksana', 'kodesasaran', 'meta_instansi', 'panitia_monev','kodesumberanggaran', 'file_upload' );
        if($query->count() > 0 ){
          $result = $query->first();
          $arr = ['anggaran_id','kodesumberanggaran'];
            foreach($result as $key=>$val){
              if( in_array($key,$arr)){
                if(!$result->kodesumberanggaran){
                  $status_kelengkapan=false;
                  break;
                }else{
                  if($result->kodesumberanggaran=='DIPA'){
                    if(!$result->anggaran_id){
                      $status_kelengkapan=false;
                      break;
                    }else{
                      continue;
                    }
                  }else{
                    continue;
                  }
                }
              }else{
                if(!$val){
                  $status_kelengkapan=false;
                  break;
                }else{
                  continue;
                }
              }
            }
          }
        if($status_kelengkapan== true){
           $kelengkapan = execute_api_json('api/psmsupervisi/'.$id,'PUT',['status'=>'Y']);
        }else{
           $kelengkapan = execute_api_json('api/psmsupervisi/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

    private function kelengkapan_psm_rapatkerja($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('dayamas_rapat_kerja_pemetaan')->where(['id'=>$id])
                  ->select('tanggal_pemetaan', 'id_pelaksana', 'id_lokasi_kegiatan','jumlah_peserta','kode_sumber_anggaran','kode_sasaran' );

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
           $kelengkapan = execute_api_json('api/rapatKerjaPemetaan/'.$id,'PUT',['status'=>'Y']);
        }else{
           $kelengkapan = execute_api_json('api/rapatKerjaPemetaan/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

}

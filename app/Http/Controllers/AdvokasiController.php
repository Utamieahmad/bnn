<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\MainModel;
use URL;
use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use Excel;

class AdvokasiController extends Controller
{
    /* @author : Daniel Andi */

    public $data;
    public $selected;
    public $form_params;

    public function pendataanKoordinasi(Request $request){
        $client = new Client();
        if ($request->input('page')) {
          $page = $request->input('page');
        } else {
          $page = 1;
        }
        // $url_api = ;
        $baseUrl = URL::to($this->urlapi());
        // $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $kondisi = '';
        if($request->limit) {
          $this->limit = $request->limit;
        } else {
          $this->limit = config('app.limit');
        }

        if($request->isMethod('get')){
          $get = $request->all();
          $get = $request->except(['page']);
          $tipe = $request->tipe;
          if(count($get)>0){
            $this->selected['tipe']  = $tipe;
            foreach ($get as $key => $value) {
              $kondisi .= "&".$key.'='.$value;
              if( ($key == 'tgl_from') || ($key == 'tgl_to') ){
                $this->selected[$key] = date('d/m/Y',strtotime($value));
              }else {
                  $this->selected[$key] = $value;
                  $this->selected['keyword'] = $value;
              }
            }

            $this->selected['order'] = $request->order;
            $this->selected['limit'] = $request->limit;
          }
        } else {
          $post = $request->all();
          $tipe = $request->tipe;
          $kata_kunci = $request->kata_kunci;
          $pelaksana = $request->pelaksana;
          // $namapelaksana = $request->namapelaksana;
          $sasaran = $request->sasaran;
          $anggaran = $request->anggaran;
          $tgl_from = $request->tgl_from;
          $tgl_to = $request->tgl_to;
          $order = $request->order;
          $limit = $request->limit;
          $kelengkapan = $request->kelengkapan;

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
          }else if($tipe == 'pelaksana'){
            $kondisi .= '&pelaksana='.$pelaksana;
            $this->selected['pelaksana'] = $pelaksana;
          }else if($tipe == 'sasaran'){
            $kondisi .= '&sasaran='.$sasaran;
            $this->selected['sasaran'] = $sasaran;
          }else if($tipe == 'anggaran'){
            $kondisi .= '&anggaran='.$anggaran;
            $this->selected['anggaran'] = $anggaran;
          }else if($tipe == 'kelengkapan'){
            $kondisi .= '&kelengkapan='.$kelengkapan;
            $this->selected['kelengkapan'] = $kelengkapan;
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

        $requestAdvorakor = $client->request('GET', $baseUrl.'/api/advorakor?id_wilayah='.$request->session()->get('wilayah').'&'.$limit.'&'.$offset.$kondisi,
        // $requestAdvorakor = $client->request('GET', $baseUrl.'cegah/advorakor?id_wilayah='.$request->session()->get('wilayah').'&'.$limit.'&'.$offset.$kondisi,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                    // 'Authorization' => 'Bearer rUjEwAucsuiEc0wyypbuchvwEB19DgCnEqj5uGl2Yytp9aFqlEWfAUQM45W7MRKHaCF5bowyECplrTCWOk3M2mmFxCFsjevNKbsEpRz8nELNpHiM19y5C4ZXYi1CcLtuvBuiN0JH0pg5ngn599SRg7amx2EQnQDrv0oBgBLCaaZZeCsaAkGVfRZBTzp4RrtVW9CdGxsSHGdsRJLctNA0GTYjUZ7vhbmbawLV4bcCmlCNAmg1OctS4nJSUQtPpUy'
                ]
            ]
        );
        $advorakor = json_decode($requestAdvorakor->getBody()->getContents(), true);
        // dd($advorakor);
        $this->data['data_advorakor'] = $advorakor['data'];
        $page = $advorakor['paginate'];
        $this->data['titledel'] = "advorakor";
        $this->data['title'] = "Kegiatan Rapat Koordinasi";
        $this->data['route'] = $request->route()->getName();
        $this->data['delete_route'] = "delete_pendataan_koordinasi";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['token'] = $token;

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['path'] = $request->path();
        $this->data['page'] = $page;
        $this->data['forprint'] = $limit.'&'.$offset.$kondisi;
        $this->data['filter'] = $this->selected;
        $this->data['start_number'] = $start_number;
        $total_item = $page['totalpage'] * $this->limit;
        $filtering = false;
	      if($kondisi){
	        $filter = $kondisi;
	        $filtering = true;
	        $this->data['kondisi'] = '?'.$limit.'&'.$offset.$kondisi;
	      }else{
	        $filter = '/';
	        $filtering = false;
	        $this->data['kondisi'] = $current_page;
	      }
        // dd($filter);
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), '/'.$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter);
        $this->data['breadcrumps'] = breadcrumps_dir_advokasi($request->route()->getName());
        // dd($this->data);
        return view('pencegahan.advokasi.koordinasi.index_pendataanKoordinasi',$this->data);
    }

    public function addpendataanKoordinasi(Request $request){
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
        // $baseUrl = URL::to('/');

        $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
        $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);
        $this->data['propkab'] = $propkab;
        // dd($propkab);
        $requestpenyidik = $client->request('GET', config('app.url_soa').'simpeg/staffBySatker?unit_id='.$request->session()->get('satker_simpeg'));
        $panitia = json_decode($requestpenyidik->getBody()->getContents(), true);
        $this->data['panitia'] = $panitia;
        // dd($requestpenyidik);
        $requestsatker = $client->request('GET', config('app.url_soa').'simpeg/listSatker');
        $satker = json_decode($requestsatker->getBody()->getContents(), true);
        $this->data['satker'] = $satker['data'];

        // $requestpegawai = $client->request('GET', config('app.url_soa').'simpeg/staffBySatker?unit_id=156');
        // $pegawai = json_decode($requestpegawai->getBody()->getContents(), true);
        // $this->data['pegawai'] = $pegawai['data']['pegawai'];

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        $this->data['breadcrumps'] = breadcrumps_dir_advokasi($request->route()->getName());
        return view('pencegahan.advokasi.koordinasi.add_pendataanKoordinasi',$this->data);
    }

    public function editpendataanKoordinasi(Request $request){
       $id = $request->id;
       $client = new Client();
       $baseUrl = URL::to($this->urlapi());
       // $baseUrl = URL::to('/');
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

       $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
       $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

       $requestpenyidik = $client->request('GET', config('app.url_soa').'simpeg/staffBySatker?unit_id='.$dataDetail['data']['satker_panitia']);
       // dd("$id");
        $panitia = json_decode($requestpenyidik->getBody()->getContents(), true);
        $this->data['panitia'] = $panitia;

        // $requestsatker = $client->request('GET', config('app.url_soa').'simpeg/listSatker');
        // $satker = json_decode($requestsatker->getBody()->getContents(), true);
        // $this->data['satker'] = $satker['data'];

       $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
       $this->data['propkab'] = $propkab;
       $this->data['sasaran'] = config('lookup.instansi');
       $this->data['data_detail'] = $dataDetail;
       $this->data['id'] = $id;
       $this->data['breadcrumps'] = breadcrumps_dir_advokasi($request->route()->getName());
       return view('pencegahan.advokasi.koordinasi.edit_pendataanKoordinasi',$this->data);
    }

    public function inputpendataanKoordinasi(Request $request){

       $baseUrl = URL::to($this->urlapi());
       // $baseUrl = URL::to('/');
       $token = $request->session()->get('token');

       $client = new Client();

       //generate image base64
       if($request->hasFile('foto1')){
           $filenameWithExt = $request->file('foto1')->getClientOriginalName();
           $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
           $extension = $request->file('foto1')->getClientOriginalExtension();
           $fileNameToStore= $filename.'_'.time().'.'.$extension;
           $path = $request->file('foto1')->storeAs('AdvokasiKoordinasi', $fileNameToStore);
           $image = public_path('upload/AdvokasiKoordinasi/'.$fileNameToStore);
           $data = file_get_contents($image);
           $image1 = base64_encode($data);
           Storage::delete('AdvokasiKoordinasi/'.$fileNameToStore);
       }else{
         $image1 = null;
       }

       if($request->hasFile('foto2')){
           $filenameWithExt = $request->file('foto2')->getClientOriginalName();
           $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
           $extension = $request->file('foto2')->getClientOriginalExtension();
           $fileNameToStore= $filename.'_'.time().'.'.$extension;
           $path = $request->file('foto2')->storeAs('AdvokasiKoordinasi', $fileNameToStore);
           $image = public_path('upload/AdvokasiKoordinasi/'.$fileNameToStore);
           $data = file_get_contents($image);
           $image2 = base64_encode($data);
           Storage::delete('AdvokasiKoordinasi/'.$fileNameToStore);
       }else{
         $image2 = null;
       }

       if($request->hasFile('foto3')){
           $filenameWithExt = $request->file('foto3')->getClientOriginalName();
           $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
           $extension = $request->file('foto3')->getClientOriginalExtension();
           $fileNameToStore= $filename.'_'.time().'.'.$extension;
           $path = $request->file('foto3')->storeAs('AdvokasiKoordinasi', $fileNameToStore);
           $image = public_path('upload/AdvokasiKoordinasi/'.$fileNameToStore);
           $data = file_get_contents($image);
           $image3 = base64_encode($data);
           Storage::delete('AdvokasiKoordinasi/'.$fileNameToStore);
       }else{
         $image3 = null;
       }
       $uraian_singkat_materi = $request->input('uraian_singkat');

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

        $meta_panitia = $request->input('meta_panitia');
        $panitia =[];
        if (count($meta_panitia) > 0) {
          for ($i=0; $i < count($meta_panitia); $i++) {
            $panitia[]=$meta_panitia[$i]["panitia_monev"];
          }
           $meta_panitia = json_encode($panitia);

        }else{
          $meta_panitia="";
        }

        $meta_nasum_materi = $request->input('meta_nasum_materi');
        if (count($meta_nasum_materi) > 0 && $meta_nasum_materi[0]['narasumber'] && $meta_nasum_materi[0]['narasumber']) {
           $meta_nasum_materi = json_encode($request->input('meta_nasum_materi'));
        }else{
          $meta_nasum_materi="";
        }

        $meta_instansi = $request->input('group-c');
        if (count($meta_instansi) > 0 && $meta_instansi[0]['list_nama_instansi'] && $meta_instansi[0]['list_jumlah_peserta']) {
           $meta_instansi = json_encode($request->input('group-c'));
        }else{
          $meta_instansi="";
        }

        $meta_sasaran = $request->input('meta_sasaran');
        if (count($meta_sasaran) > 0 ) {
           $meta_sasaran = json_encode($request->input('meta_sasaran'));
        }else{
          $meta_sasaran="";
        }

        $requestInput = $client->request('POST', $baseUrl.'/api/advorakor',
               [
                   'headers' =>
                   [
                       'Authorization' => 'Bearer '.$token
                   ],
                   'form_params' => [
                       'idpelaksana' => $request->input('idpelaksana'),
                       'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                       // 'jenis_kegiatan' => $request->input('sasaran'),
                       // 'kodesasaran' => json_encode($request->input('sasaran')),
                       'meta_sasaran' => $meta_sasaran,
                       'jumlah_instansi' => $jumlah_instansi,
                       'meta_instansi' => $meta_instansi,
                       'meta_nasum_materi' => $meta_nasum_materi,
                       'uraian_singkat' => $uraian_singkat_materi,
                       'foto1' => $image1,
                       'foto2' => $image2,
                       'foto3' => $image3,
                       'meta_panitia' => $meta_panitia,
                       'jumlah_peserta' => $peserta,
                       'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
                       //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
                       'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
                       // 'narasumber' => $json_narasumber,
                       // 'panitia_monev' => $request->input('panitia_monev'),
                       // 'materi' => $request->input('materi'),
                       'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
                       // 'file_upload' => $anggaran,
                       // 'created_at' => $anggaran,
                       // 'created_by' => $anggaran,
                       'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                       'periode_tahun' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                       'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                       'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                       'anggaran_id' => $anggaran,
                       'no_sprint' => $request->input('no_sprint'),
                       'satker_panitia' => $request->input('satker_panitia'),
                   ]
               ]
           );

       $result = json_decode($requestInput->getBody()->getContents(), true);

       $this->form_params = array('idpelaksana' => $request->input('idpelaksana'),
       'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       // 'jenis_kegiatan' => $request->input('sasaran'),
       // 'kodesasaran' => json_encode($request->input('sasaran')),
       'meta_sasaran' => $meta_sasaran,
       'jumlah_instansi' => $jumlah_instansi,
       'meta_instansi' => $meta_instansi,
       'meta_nasum_materi' => $meta_nasum_materi,
       'uraian_singkat' => $uraian_singkat_materi,
       'foto1' => $image1,
       'foto2' => $image2,
       'foto3' => $image3,
       'meta_panitia' => $meta_panitia,
       'jumlah_peserta' => $peserta,
       'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
       //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
       'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
       // 'narasumber' => $json_narasumber,
       // 'panitia_monev' => $request->input('panitia_monev'),
       // 'materi' => $request->input('materi'),
       'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
       // 'file_upload' => $anggaran,
       // 'created_at' => $anggaran,
       // 'created_by' => $anggaran,
       'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'periode_tahun' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'anggaran_id' => $anggaran,
       'no_sprint' => $request->input('no_sprint'),
       'satker_panitia' => $request->input('satker_panitia'));

       $trail['audit_menu'] = 'Pencegahan - Direktorat Advokasi - Kegiatan Rapat Koordinasi';
       $trail['audit_event'] = 'post';
       $trail['audit_value'] = json_encode($this->form_params);
       $trail['audit_url'] = $request->url();
       $trail['audit_ip_address'] = $request->ip();
       $trail['audit_user_agent'] = $request->userAgent();
       $trail['audit_message'] = $result['comment'];
       $trail['created_at'] = date("Y-m-d H:i:s");
       $trail['created_by'] = $request->session()->get('id');

       $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


       $inputId = $result['data']['eventID'];

       if ($request->file('file_upload') != ''){
           $fileName = $inputId.'-'.$request->file('file_upload')->getClientOriginalName();
           $request->file('file_upload')->storeAs('AdvokasiRakor', $fileName);

           $requestfile = $client->request('PUT', $baseUrl.'/api/advorakor/'.$inputId,
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

       $this->kelengkapan_Koordinasi($inputId);

       if( ($result['code'] == 200) && ($result['status'] != 'error') ){
       	$this->data['status'] = 'success';
       	$this->data['message'] = 'Data Kegiatan Rapat Koordinasi berhasil disimpan. ';
       }else{
		    $this->data['status'] = 'error';
       	$this->data['message'] = 'Data Kegiatan Rapat Koordinasi gagal disimpan. ';
       }

       return redirect('pencegahan/dir_advokasi/pendataan_koordinasi/')->with('status',$this->data);

    }

    public function updatependataanKoordinasi(Request $request){
          $id = $request->input('id');

          $baseUrl = URL::to($this->urlapi());
          // $baseUrl = URL::to('/');
          $token = $request->session()->get('token');

          $client = new Client();

          //generate image base64
          if($request->hasFile('foto1')){
              $filenameWithExt = $request->file('foto1')->getClientOriginalName();
              $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
              $extension = $request->file('foto1')->getClientOriginalExtension();
              $fileNameToStore= $filename.'_'.time().'.'.$extension;
              $path = $request->file('foto1')->storeAs('AdvokasiKoordinasi', $fileNameToStore);
              $image = public_path('upload/AdvokasiKoordinasi/'.$fileNameToStore);
              $data = file_get_contents($image);
              $image1 = base64_encode($data);
              Storage::delete('AdvokasiKoordinasi/'.$fileNameToStore);
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
              $path = $request->file('foto2')->storeAs('AdvokasiKoordinasi', $fileNameToStore);
              $image = public_path('upload/AdvokasiKoordinasi/'.$fileNameToStore);
              $data = file_get_contents($image);
              $image2 = base64_encode($data);
              Storage::delete('AdvokasiKoordinasi/'.$fileNameToStore);
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
              $path = $request->file('foto3')->storeAs('AdvokasiKoordinasi', $fileNameToStore);
              $image = public_path('upload/AdvokasiKoordinasi/'.$fileNameToStore);
              $data = file_get_contents($image);
              $image3 = base64_encode($data);
              Storage::delete('AdvokasiKoordinasi/'.$fileNameToStore);
  //            $form_foto3 = 'foto3 => '.$image.',';
          }else{
              $image3 = $request->input('foto3_old');
  //            $form_foto3 = '';
          }
          $uraian_singkat_materi = $request->input('uraian_singkat');

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

          // $narasumber = $request->input('narasumber');
          // $arr_narasumber = [];
          // $json_narasumber = "";
          // if($narasumber){
          //   if(count($narasumber)>0){
          //     for($n = 0; $n < count($narasumber); $n++){
          //       $arr_narasumber[] = ['narasumber'=>$narasumber[$n]['narasumber'],'materi'=>$narasumber[$n]['materi']];
          //     }
          //     $json_narasumber = json_encode($arr_narasumber);
          //   }else{
          //     $json_narasumber =  "";
          //   }
          // }

          $meta_panitia = $request->input('meta_panitia');
          $panitia =[];

          if (count($meta_panitia) > 0 && $request->input('meta_panitia')) {
            for ($i=0; $i < count($meta_panitia); $i++) {
              $panitia[]=$meta_panitia[$i]["panitia_monev"];
            }
             $meta_panitia = json_encode($panitia);

          }else{
            $meta_panitia="";
          }

          $meta_nasum_materi = $request->input('meta_nasum_materi');
          if (count($meta_nasum_materi) > 0 && $meta_nasum_materi[0]['narasumber'] && $meta_nasum_materi[0]['narasumber']) {
             $meta_nasum_materi = json_encode($request->input('meta_nasum_materi'));
          }else{
            $meta_nasum_materi="";
          }

          $meta_instansi = $request->input('group-c');
          if (count($meta_instansi) > 0 && $meta_instansi[0]['list_nama_instansi'] && $meta_instansi[0]['list_jumlah_peserta']) {
             $meta_instansi = json_encode($request->input('group-c'));
          }else{
            $meta_instansi="";
          }

          $meta_sasaran = $request->input('meta_sasaran');
          if (count($meta_sasaran) > 0 ) {
             $meta_sasaran = json_encode($request->input('meta_sasaran'));
          }else{
            $meta_sasaran="";
          }

          $requestInput = $client->request('PUT', $baseUrl.'/api/advorakor/'.$id,
                   [
                       'headers' =>
                       [
                           'Authorization' => 'Bearer '.$token
                       ],
                       'form_params' => [
                           'idpelaksana' => $request->input('idpelaksana'),
                           'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                           // 'jenis_kegiatan' => $request->input('sasaran'),
                           // 'kodesasaran' => json_encode($request->input('sasaran')),
                           'meta_sasaran' => $meta_sasaran,
                           'jumlah_instansi' => $jumlah_instansi,
                           'meta_instansi' => $meta_instansi,
                           'meta_nasum_materi' => $meta_nasum_materi,
                           'uraian_singkat' => $uraian_singkat_materi,
                           'foto1' => $image1,
                           'foto2' => $image2,
                           'foto3' => $image3,
                           'meta_panitia' => $meta_panitia,
                           'jumlah_peserta' => $peserta,
                           'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
                           //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
                           'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
                           // 'narasumber' => $json_narasumber,
                           // 'panitia_monev' => $request->input('panitia_monev'),
                           // 'materi' => $request->input('materi'),
                           'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
                           // 'file_upload' => $anggaran,
                           // 'created_at' => $anggaran,
                           // 'created_by' => $anggaran,
                           'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                           'periode_tahun' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                           'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                           'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                           'anggaran_id' => $anggaran,
                           'no_sprint' => $request->input('no_sprint'),
                       ]
                   ]
               );

           $result = json_decode($requestInput->getBody()->getContents(), true);

           $this->form_params = array('idpelaksana' => $request->input('idpelaksana'),
           'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           // 'jenis_kegiatan' => $request->input('sasaran'),
           // 'kodesasaran' => json_encode($request->input('sasaran')),
           'meta_sasaran' => $meta_sasaran,
           'jumlah_instansi' => $jumlah_instansi,
           'meta_instansi' => $meta_instansi,
           'meta_nasum_materi' => $meta_nasum_materi,
           'uraian_singkat' => $uraian_singkat_materi,
           'foto1' => $image1,
           'foto2' => $image2,
           'foto3' => $image3,
           'meta_panitia' => $meta_panitia,
           'jumlah_peserta' => $peserta,
           'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
           //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
           'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
           // 'narasumber' => $json_narasumber,
           // 'panitia_monev' => $request->input('panitia_monev'),
           // 'materi' => $request->input('materi'),
           'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
           // 'file_upload' => $anggaran,
           // 'created_at' => $anggaran,
           // 'created_by' => $anggaran,
           'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           'periode_tahun' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           'anggaran_id' => $anggaran,
           'no_sprint' => $request->input('no_sprint'));

           $trail['audit_menu'] = 'Pencegahan - Direktorat Advokasi - Kegiatan Rapat Koordinasi';
           $trail['audit_event'] = 'put';
           $trail['audit_value'] = json_encode($this->form_params);
           $trail['audit_url'] = $request->url();
           $trail['audit_ip_address'] = $request->ip();
           $trail['audit_user_agent'] = $request->userAgent();
           $trail['audit_message'] = $result['comment'];
           $trail['created_at'] = date("Y-m-d H:i:s");
           $trail['created_by'] = $request->session()->get('id');

           $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


           if ($request->file('file_upload') != ''){
               $fileName = $id.'-'.$request->file('file_upload')->getClientOriginalName();
               $request->file('file_upload')->storeAs('AdvokasiRakor', $fileName);

               $requestfile = $client->request('PUT', $baseUrl.'/api/advorakor/'.$id,
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

            $this->kelengkapan_Koordinasi($id);

            if( ($result['code'] == 200) && ($result['status'] != 'error') ){
            	$this->data['status'] = 'success';
            	$this->data['message'] = 'Data Kegiatan Rapat Koordinasi berhasil diperbaharui. ';
            }else{
     		    $this->data['status'] = 'error';
            	$this->data['message'] = 'Data Kegiatan Rapat Koordinasi gagal diperbaharui. ';
            }


           return back()->with('status',$this->data);
      }

    public function deletePendataanKoordinasi(Request $request){
      $id = $request->input('id');
      if($id){
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/advorakor/'.$id,'DELETE');
            $this->form_params['delete_id'] = $id;
            $trail['audit_menu'] = 'Pencegahan - Direktorat Advokasi - Kegiatan Rapat Koordinasi';
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
        $data_request = ['status'=>'error','message'=>'Data Kegiatan Rapat Koordinasi Gagal Dihapus'];
        return $data_request;
      }
    }

    public function printKoordinasi(Request $request){
        $client = new Client();
        $page = $request->input('page');
        $token = $request->session()->get('token');
        $baseUrl = URL::to($this->urlapi());
        // $baseUrl = URL::to('/');

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
        // if($page){
        //     $page = $page;
        // }else{
        //     $page = 1;
        // }
        $result = [];
        $url = 'api/advorakor'.$kondisi.'&id_wilayah='.$request->session()->get('wilayah');

        //$no = $start_number;

        $requestPrintData = $client->request('GET', $baseUrl.'/'.$url,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
        $PrintData = json_decode($requestPrintData->getBody()->getContents(), true);
        // dd($pemusnahanladang);
        $DataArray = [];

        $i = $start_number;
        // dd($PrintData['data']);
        foreach ($PrintData['data'] as $key => $value) {
          $DataArray[$key]['No'] = $i;
          $DataArray[$key]['Tanggal'] = ($value['tgl_pelaksanaan'] ? date('d-m-Y', strtotime($value['tgl_pelaksanaan'])) : '');
          $DataArray[$key]['Pelaksana'] = $value['nm_instansi'];
          $meta = json_decode($value['meta_sasaran'],true);
          if(count($meta)){
            for($j = 0 ; $j < count($meta); $j++){
                $InstansiArray[$key]['ssr'][$j] = $meta[$j];
            }
            $DataArray[$key]['Sasaran'] = implode("\n", $InstansiArray[$key]['ssr']);
          } else {
            $DataArray[$key]['Sasaran'] = '';
          }
          $meta = json_decode($value['meta_instansi'],true);
          if(count($meta)){
            for($j = 0 ; $j < count($meta); $j++){
                $InstansiArray[$key]['Instansi'][$j] = $meta[$j]['list_nama_instansi'].'('.$meta[$j]['list_jumlah_peserta'].')';
            }
            $DataArray[$key]['Instansi/Peserta'] = implode("\n", $InstansiArray[$key]['Instansi']);
          } else {
            $DataArray[$key]['Instansi/Peserta'] = '-';
          }
          $DataArray[$key]['Sumber Anggaran'] = $value['kodesumberanggaran'];
          $i = $i +1;
        }
         //dd($DataArray);
        $data = $DataArray;
        $name = 'Data Kegiatan Rapat Koordinasi '.Carbon::now()->format('Y-m-d H:i:s');
        $this->printData($data, $name);
    }

    private function kelengkapan_Koordinasi($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('cegahadvokasi_rakor')->where('id',$id)
                  ->select('tgl_pelaksanaan','idpelaksana','meta_instansi','lokasi_kegiatan', 'lokasi_kegiatan_idkabkota', 'kodesumberanggaran', 'file_upload','no_sprint', 'meta_nasum_materi', 'meta_panitia','satker_panitia', 'meta_sasaran');
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
          $kelengkapan = execute_api_json('api/advorakor/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/advorakor/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

    public function pendataanJejaring(Request $request){
        $client = new Client();
        if ($request->input('page')) {
          $page = $request->input('page');
        } else {
          $page = 1;
        }
        $baseUrl = URL::to($this->urlapi());
        // $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $kondisi = '';
        if($request->limit) {
          $this->limit = $request->limit;
        } else {
          $this->limit = config('app.limit');
        }

        if($request->isMethod('get')){
          $get = $request->all();
          $get = $request->except(['page']);
          $tipe = $request->tipe;
          if(count($get)>0){
            $this->selected['tipe']  = $tipe;
            foreach ($get as $key => $value) {
              $kondisi .= "&".$key.'='.$value;
              if( ($key == 'tgl_from') || ($key == 'tgl_to') ){
                $this->selected[$key] = date('d/m/Y',strtotime($value));
              }else {
                  $this->selected[$key] = $value;
                  $this->selected['keyword'] = $value;
              }
            }

            $this->selected['order'] = $request->order;
            $this->selected['limit'] = $request->limit;
          }
        } else {
          $post = $request->all();
          $tipe = $request->tipe;
          $kata_kunci = $request->kata_kunci;
          $pelaksana = $request->pelaksana;
          // $namapelaksana = $request->namapelaksana;
          $sasaran = $request->sasaran;
          $anggaran = $request->anggaran;
          $tgl_from = $request->tgl_from;
          $tgl_to = $request->tgl_to;
          $order = $request->order;
          $limit = $request->limit;
          $kelengkapan = $request->kelengkapan;

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
          }else if($tipe == 'pelaksana'){
            $kondisi .= '&pelaksana='.$pelaksana;
            $this->selected['pelaksana'] = $pelaksana;
          }else if($tipe == 'sasaran'){
            $kondisi .= '&sasaran='.$sasaran;
            $this->selected['sasaran'] = $sasaran;
          }else if($tipe == 'anggaran'){
            $kondisi .= '&anggaran='.$anggaran;
            $this->selected['anggaran'] = $anggaran;
          }else if($tipe == 'kelengkapan'){
            $kondisi .= '&kelengkapan='.$kelengkapan;
            $this->selected['kelengkapan'] = $kelengkapan;
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

        $requestAdvojejaring = $client->request('GET', $baseUrl.'/api/advojejaring?id_wilayah='.$request->session()->get('wilayah').'&'.$limit.'&'.$offset.$kondisi,
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
        $this->data['titledel'] = "advojejaring";
        $this->data['title'] = "Kegiatan Membangun Jejaring";
        $this->data['forprint'] = $limit.'&'.$offset.$kondisi;
        $this->data['delete_route'] = "delete_pendataan_jejaring";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['token'] = $token;

        $this->data['filter'] = $this->selected;
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['path'] = $request->path();
        $this->data['page'] = $page;
        $this->data['start_number'] = $start_number;
        $total_item = $page['totalpage'] * $this->limit;
        $filtering = false;
	      if($kondisi){
	        $filter = $kondisi;
	        $filtering = true;
	        $this->data['kondisi'] = '?'.$limit.'&'.$offset.$kondisi;
	      }else{
	        $filter = '/';
	        $filtering = false;
	        $this->data['kondisi'] = $current_page;
	      }
        // dd($filter);
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), '/'.$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter);

        $this->data['breadcrumps'] = breadcrumps_dir_advokasi($request->route()->getName());
        return view('pencegahan.advokasi.jejaring.index_pendataanJejaring',$this->data);
    }

    public function addpendataanJejaring(Request $request){
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
        // $baseUrl = URL::to('/');

        $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
        $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $propkab;

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        $this->data['breadcrumps'] = breadcrumps_dir_advokasi($request->route()->getName());
        return view('pencegahan.advokasi.jejaring.add_pendataanJejaring',$this->data);
    }

    public function editpendataanJejaring(Request $request){
        $id = $request->id;
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
        // $baseUrl = URL::to('/');
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

        $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
        $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $propkab;

        $this->data['data_detail'] = $dataDetail;
        $this->data['id'] = $id;
        $this->data['breadcrumps'] = breadcrumps_dir_advokasi($request->route()->getName());
        return view('pencegahan.advokasi.jejaring.edit_pendataanJejaring',$this->data);
    }

    public function inputpendataanJejaring(Request $request){

      $baseUrl = URL::to($this->urlapi());
       // $baseUrl = URL::to('/');
       $token = $request->session()->get('token');

       $client = new Client();

       //generate image base64
       if($request->hasFile('foto1')){
           $filenameWithExt = $request->file('foto1')->getClientOriginalName();
           $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
           $extension = $request->file('foto1')->getClientOriginalExtension();
           $fileNameToStore= $filename.'_'.time().'.'.$extension;
           $path = $request->file('foto1')->storeAs('AdvokasiJejaring', $fileNameToStore);
           $image = public_path('upload/AdvokasiJejaring/'.$fileNameToStore);
           $data = file_get_contents($image);
           $image1 = base64_encode($data);
           Storage::delete('AdvokasiJejaring/'.$fileNameToStore);
       }else{
         $image1 = null;
       }

       if($request->hasFile('foto2')){
           $filenameWithExt = $request->file('foto2')->getClientOriginalName();
           $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
           $extension = $request->file('foto2')->getClientOriginalExtension();
           $fileNameToStore= $filename.'_'.time().'.'.$extension;
           $path = $request->file('foto2')->storeAs('AdvokasiJejaring', $fileNameToStore);
           $image = public_path('upload/AdvokasiJejaring/'.$fileNameToStore);
           $data = file_get_contents($image);
           $image2 = base64_encode($data);
           Storage::delete('AdvokasiJejaring/'.$fileNameToStore);
       }else{
         $image2 = null;
       }

       if($request->hasFile('foto3')){
           $filenameWithExt = $request->file('foto3')->getClientOriginalName();
           $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
           $extension = $request->file('foto3')->getClientOriginalExtension();
           $fileNameToStore= $filename.'_'.time().'.'.$extension;
           $path = $request->file('foto3')->storeAs('AdvokasiJejaring', $fileNameToStore);
           $image = public_path('upload/AdvokasiJejaring/'.$fileNameToStore);
           $data = file_get_contents($image);
           $image3 = base64_encode($data);
           Storage::delete('AdvokasiJejaring/'.$fileNameToStore);
       }else{
         $image3 = null;
       }
       $peserta = $request->input('jumlah_peserta');
       $uraian_singkat_materi = $request->input('uraian_singkat');

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
        // $peserta = 0;
        // if ($jumlah_instansi > 0) {
        //     foreach ($request->input('group-c') as $c1 => $r1) {
        //         $peserta = $peserta + $r1['list_jumlah_peserta'];
        //     }
        // }

        $meta_nasum_materi = $request->input('meta_nasum_materi');
        if (count($meta_nasum_materi) > 0 && $meta_nasum_materi[0]['narasumber'] && $meta_nasum_materi[0]['narasumber']) {
           $meta_nasum_materi = json_encode($request->input('meta_nasum_materi'));
        }else{
          $meta_nasum_materi="";
        }

        $meta_instansi = $request->input('group-c');
        if (count($meta_instansi) > 0 && $meta_instansi[0]['list_nama_instansi']) {
           $meta_instansi = json_encode($request->input('group-c'));
        }else{
          $meta_instansi="";
        }

        $requestInput = $client->request('POST', $baseUrl.'/api/advojejaring',
               [
                   'headers' =>
                   [
                       'Authorization' => 'Bearer '.$token
                   ],
                   'form_params' => [
                       'idpelaksana' => $request->input('idpelaksana'),
                       'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                       // 'jenis_kegiatan' => $request->input('sasaran'),
                       'kodesasaran' => ($request->input('sasaran') ? $request->input('sasaran') : ''),
                       'jumlah_instansi' => $jumlah_instansi,
                       'meta_instansi' => $meta_instansi,
                       // 'jumlah_peserta' => $peserta,
                       'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
                       'meta_nasum_materi' => $meta_nasum_materi,
                       'jumlah_peserta' => $peserta,
                       'uraian_singkat' => $uraian_singkat_materi,
                       'foto1' => $image1,
                       'foto2' => $image2,
                       'foto3' => $image3,
                       //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
                       'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
                       // 'narasumber' => $request->input('narasumber'),
                       'panitia_monev' => ($request->input('panitia_monev') ? $request->input('panitia_monev') : ''),
                       // 'materi' => $request->input('materi'),
                       'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
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

       $result = json_decode($requestInput->getBody()->getContents(), true);

       $this->form_params = array('idpelaksana' => $request->input('idpelaksana'),
       'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       // 'jenis_kegiatan' => $request->input('sasaran'),
       'kodesasaran' => ($request->input('sasaran') ? $request->input('sasaran') : ''),
       'jumlah_instansi' => $jumlah_instansi,
       'meta_instansi' => $meta_instansi,
       // 'jumlah_peserta' => $peserta,
       'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
       'meta_nasum_materi' => $meta_nasum_materi,
       'jumlah_peserta' => $peserta,
       'uraian_singkat' => $uraian_singkat_materi,
       'foto1' => $image1,
       'foto2' => $image2,
       'foto3' => $image3,
       //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
       'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
       // 'narasumber' => $request->input('narasumber'),
       'panitia_monev' => ($request->input('panitia_monev') ? $request->input('panitia_monev') : ''),
       // 'materi' => $request->input('materi'),
       'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
       // 'file_upload' => $anggaran,
       // 'created_at' => $anggaran,
       // 'created_by' => $anggaran,
       'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'periode_tahun' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'anggaran_id' => $anggaran);

       $trail['audit_menu'] = 'Pencegahan - Direktorat Advokasi - Kegiatan Membangun Jejaring';
       $trail['audit_event'] = 'post';
       $trail['audit_value'] = json_encode($this->form_params);
       $trail['audit_url'] = $request->url();
       $trail['audit_ip_address'] = $request->ip();
       $trail['audit_user_agent'] = $request->userAgent();
       $trail['audit_message'] = $result['comment'];
       $trail['created_at'] = date("Y-m-d H:i:s");
       $trail['created_by'] = $request->session()->get('id');

       $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


       $inputId = $result['data']['eventID'];

       if ($request->file('file_upload') != ''){
           $fileName = $inputId.'-'.$request->file('file_upload')->getClientOriginalName();
           $request->file('file_upload')->storeAs('AdvokasiJejaring', $fileName);

           $requestfile = $client->request('PUT', $baseUrl.'/api/advojejaring/'.$inputId,
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

       $this->kelengkapan_Jejaring($inputId);

       if( ($result['code'] == 200) && ($result['status'] != 'error') ){
         $this->data['status'] = 'success';
         $this->data['message'] = 'Data Kegiatan Membangun Jejaring berhasil disimpan. ';
       }else{
       $this->data['status'] = 'error';
         $this->data['message'] = 'Data Kegiatan Membangun Jejaring gagal disimpan. ';
       }


       return redirect('pencegahan/dir_advokasi/pendataan_jejaring/')->with('status',$this->data);

    }

    public function updatependataanJejaring(Request $request){
          $id = $request->input('id');

          $baseUrl = URL::to($this->urlapi());
          // $baseUrl = URL::to('/');
          $token = $request->session()->get('token');

          $client = new Client();

          //generate image base64
          if($request->hasFile('foto1')){
              $filenameWithExt = $request->file('foto1')->getClientOriginalName();
              $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
              $extension = $request->file('foto1')->getClientOriginalExtension();
              $fileNameToStore= $filename.'_'.time().'.'.$extension;
              $path = $request->file('foto1')->storeAs('AdvokasiJejaring', $fileNameToStore);
              $image = public_path('upload/AdvokasiJejaring/'.$fileNameToStore);
              $data = file_get_contents($image);
              $image1 = base64_encode($data);
              Storage::delete('AdvokasiJejaring/'.$fileNameToStore);
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
              $path = $request->file('foto2')->storeAs('AdvokasiJejaring', $fileNameToStore);
              $image = public_path('upload/AdvokasiJejaring/'.$fileNameToStore);
              $data = file_get_contents($image);
              $image2 = base64_encode($data);
              Storage::delete('AdvokasiJejaring/'.$fileNameToStore);
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
              $path = $request->file('foto3')->storeAs('AdvokasiJejaring', $fileNameToStore);
              $image = public_path('upload/AdvokasiJejaring/'.$fileNameToStore);
              $data = file_get_contents($image);
              $image3 = base64_encode($data);
              Storage::delete('AdvokasiJejaring/'.$fileNameToStore);
  //            $form_foto3 = 'foto3 => '.$image.',';
          }else{
              $image3 = $request->input('foto3_old');
  //            $form_foto3 = '';
          }
          $peserta = $request->input('jumlah_peserta');
          $uraian_singkat_materi = $request->input('uraian_singkat');

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
          // $peserta = 0;
          // if ($jumlah_instansi > 0) {
          //     foreach ($request->input('group-c') as $c1 => $r1) {
          //         $peserta = $peserta + $r1['list_jumlah_peserta'];
          //     }
          // }

          $meta_nasum_materi = $request->input('meta_nasum_materi');
          if (count($meta_nasum_materi) > 0 && $meta_nasum_materi[0]['narasumber'] && $meta_nasum_materi[0]['narasumber']) {
             $meta_nasum_materi = json_encode($request->input('meta_nasum_materi'));
          }else{
            $meta_nasum_materi="";
          }

          $meta_instansi = $request->input('group-c');
          if (count($meta_instansi) > 0 && $meta_instansi[0]['list_nama_instansi']) {
             $meta_instansi = json_encode($request->input('group-c'));
          }else{
            $meta_instansi="";
          }

          $requestInput = $client->request('PUT', $baseUrl.'/api/advojejaring/'.$id,
                   [
                       'headers' =>
                       [
                           'Authorization' => 'Bearer '.$token
                       ],
                       'form_params' => [
                           'idpelaksana' => $request->input('idpelaksana'),
                           'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                           // 'jenis_kegiatan' => $request->input('sasaran'),
                           'kodesasaran' => ($request->input('sasaran') ? $request->input('sasaran') : ''),
                           'jumlah_instansi' => $jumlah_instansi,
                           'meta_instansi' => $meta_instansi,
                           'uraian_singkat' => $uraian_singkat_materi,
                           'jumlah_peserta' => $peserta,
                           'foto1' => $image1,
                           'foto2' => $image2,
                           'foto3' => $image3,
                           // 'jumlah_peserta' => $peserta,
                           'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
                           'meta_nasum_materi' => $meta_nasum_materi,
                           //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
                           'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
                           // 'narasumber' => $request->input('narasumber'),
                           'panitia_monev' => ($request->input('panitia_monev') ? $request->input('panitia_monev') : ''),
                           // 'materi' => $request->input('materi'),
                           'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
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

           $result = json_decode($requestInput->getBody()->getContents(), true);

           $this->form_params = array('idpelaksana' => $request->input('idpelaksana'),
           'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           // 'jenis_kegiatan' => $request->input('sasaran'),
           'kodesasaran' => ($request->input('sasaran') ? $request->input('sasaran') : ''),
           'jumlah_instansi' => $jumlah_instansi,
           'meta_instansi' => $meta_instansi,
           'uraian_singkat' => $uraian_singkat_materi,
           'jumlah_peserta' => $peserta,
           'foto1' => $image1,
           'foto2' => $image2,
           'foto3' => $image3,
           // 'jumlah_peserta' => $peserta,
           'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
           'meta_nasum_materi' => $meta_nasum_materi,
           //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
           'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
           // 'narasumber' => $request->input('narasumber'),
           'panitia_monev' => ($request->input('panitia_monev') ? $request->input('panitia_monev') : ''),
           // 'materi' => $request->input('materi'),
           'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
           // 'file_upload' => $anggaran,
           // 'created_at' => $anggaran,
           // 'created_by' => $anggaran,
           'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           'periode_tahun' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           'anggaran_id' => $anggaran);

           $trail['audit_menu'] = 'Pencegahan - Direktorat Advokasi - Kegiatan Membangun Jejaring';
           $trail['audit_event'] = 'put';
           $trail['audit_value'] = json_encode($this->form_params);
           $trail['audit_url'] = $request->url();
           $trail['audit_ip_address'] = $request->ip();
           $trail['audit_user_agent'] = $request->userAgent();
           $trail['audit_message'] = $result['comment'];
           $trail['created_at'] = date("Y-m-d H:i:s");
           $trail['created_by'] = $request->session()->get('id');

           $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


           if ($request->file('file_upload') != ''){
               $fileName = $id.'-'.$request->file('file_upload')->getClientOriginalName();
               $request->file('file_upload')->storeAs('AdvokasiJejaring', $fileName);

               $requestfile = $client->request('PUT', $baseUrl.'/api/advojejaring/'.$id,
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

            $this->kelengkapan_Jejaring($id);

            if( ($result['code'] == 200) && ($result['status'] != 'error') ){
            	$this->data['status'] = 'success';
            	$this->data['message'] = 'Data Kegiatan Membangun Jejaring berhasil diperbaharui. ';
            }else{
     		    $this->data['status'] = 'error';
            	$this->data['message'] = 'Data Kegiatan Membangun Jejaring gagal diperbaharui. ';
            }


           return back()->with('status',$this->data);
      }

    public function deletePendataanJejaring(Request $request){
      $id = $request->input('id');
      if($id){
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/advojejaring/'.$id,'DELETE');
            $this->form_params['delete_id'] = $id;
            $trail['audit_menu'] = 'Pencegahan - Direktorat Advokasi - Kegiatan Membangun Jejaring';
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
        $data_request = ['status'=>'error','message'=>'Data Kegiatan Membangun Jejaring Gagal Dihapus'];
        return $data_request;
      }
    }

    public function printJejaring(Request $request){
        $client = new Client();
        $page = $request->input('page');
        $token = $request->session()->get('token');
        $baseUrl = URL::to($this->urlapi());
        // $baseUrl = URL::to('/');

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
        // if($page){
        //     $page = $page;
        // }else{
        //     $page = 1;
        // }
        $result = [];
        $url = 'api/advojejaring'.$kondisi.'&id_wilayah='.$request->session()->get('wilayah');

        //$no = $start_number;

        $requestPrintData = $client->request('GET', $baseUrl.'/'.$url,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
        $PrintData = json_decode($requestPrintData->getBody()->getContents(), true);
        // dd($pemusnahanladang);
        $DataArray = [];

        $i = $start_number;
        foreach ($PrintData['data'] as $key => $value) {
          $DataArray[$key]['No'] = $i;
          $DataArray[$key]['Tanggal'] = ($value['tgl_pelaksanaan'] ? date('d-m-Y', strtotime($value['tgl_pelaksanaan'])) : '');
          $DataArray[$key]['Pelaksana'] = $value['nm_instansi'];
          $DataArray[$key]['Sasaran'] = $value['kodesasaran'];
          $meta = json_decode($value['meta_instansi'],true);
          if(count($meta)){
            for($j = 0 ; $j < count($meta); $j++){
                $InstansiArray[$key]['Instansi'][$j] = $meta[$j]['list_nama_instansi'];
            }
            $DataArray[$key]['Instansi'] = implode("\n", $InstansiArray[$key]['Instansi']);
          } else {
            $DataArray[$key]['Instansi'] = '-';
          }
          $DataArray[$key]['Sumber Anggaran'] = $value['kodesumberanggaran'];
          $i = $i +1;
        }
         //dd($DataArray);
        $data = $DataArray;
        $name = 'Data Kegiatan Membangun Jejaring '.Carbon::now()->format('Y-m-d H:i:s');
        $this->printData($data, $name);
    }

    private function kelengkapan_Jejaring($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('cegahadvokasi_jejaring')->where('id',$id)
                  ->select('tgl_pelaksanaan', 'kodesasaran', 'idpelaksana', 'meta_instansi', 'lokasi_kegiatan', 'lokasi_kegiatan_idkabkota', 'kodesumberanggaran', 'file_upload', 'panitia_monev', 'meta_nasum_materi');
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
          $kelengkapan = execute_api_json('api/advojejaring/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/advojejaring/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

    public function pendataanAsistensi(Request $request){
        $client = new Client();
        if ($request->input('page')) {
          $page = $request->input('page');
        } else {
          $page = 1;
        }

        $baseUrl = URL::to($this->urlapi());
        // $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $kondisi = '';
        if($request->limit) {
          $this->limit = $request->limit;
        } else {
          $this->limit = config('app.limit');
        }

        if($request->isMethod('get')){
          $get = $request->all();
          $get = $request->except(['page']);
          $tipe = $request->tipe;
          if(count($get)>0){
            $this->selected['tipe']  = $tipe;
            foreach ($get as $key => $value) {
              $kondisi .= "&".$key.'='.$value;
              if( ($key == 'tgl_from') || ($key == 'tgl_to') ){
                $this->selected[$key] = date('d/m/Y',strtotime($value));
              }else {
                  $this->selected[$key] = $value;
                  $this->selected['keyword'] = $value;
              }
            }

            $this->selected['order'] = $request->order;
            $this->selected['limit'] = $request->limit;
          }
        } else {
          $post = $request->all();
          $tipe = $request->tipe;
          $kata_kunci = $request->kata_kunci;
          $pelaksana = $request->pelaksana;
          // $namapelaksana = $request->namapelaksana;
          $sasaran = $request->sasaran;
          $anggaran = $request->anggaran;
          $tgl_from = $request->tgl_from;
          $tgl_to = $request->tgl_to;
          $order = $request->order;
          $limit = $request->limit;
          $kelengkapan = $request->kelengkapan;

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
          }else if($tipe == 'pelaksana'){
            $kondisi .= '&pelaksana='.$pelaksana;
            $this->selected['pelaksana'] = $pelaksana;
          }else if($tipe == 'sasaran'){
            $kondisi .= '&sasaran='.$sasaran;
            $this->selected['sasaran'] = $sasaran;
          }else if($tipe == 'anggaran'){
            $kondisi .= '&anggaran='.$anggaran;
            $this->selected['anggaran'] = $anggaran;
          }else if($tipe == 'kelengkapan'){
            $kondisi .= '&kelengkapan='.$kelengkapan;
            $this->selected['kelengkapan'] = $kelengkapan;
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

        $requestAdvoasistensi = $client->request('GET', $baseUrl.'/api/advoasistensi?id_wilayah='.$request->session()->get('wilayah').'&'.$limit.'&'.$offset.$kondisi,
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
        $this->data['page'] = $page;

        $this->data['titledel'] = "advoasistensi";
        $this->data['title'] = "Kegiatan Asistensi";
        $this->data['delete_route'] = "delete_pendataan_asistensi";
        $this->data['forprint'] = $limit.'&'.$offset.$kondisi;
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['token'] = $token;

        $this->data['filter'] = $this->selected;
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['path'] = $request->path();
        $this->data['start_number'] = $start_number;
        $total_item = $page['totalpage'] * $this->limit;
        $filtering = false;
	      if($kondisi){
	        $filter = $kondisi;
	        $filtering = true;
	        $this->data['kondisi'] = '?'.$limit.'&'.$offset.$kondisi;
	      }else{
	        $filter = '/';
	        $filtering = false;
	        $this->data['kondisi'] = $current_page;
	      }
        // dd($filter);
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), '/'.$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter);

        $this->data['breadcrumps'] = breadcrumps_dir_advokasi($request->route()->getName());
        return view('pencegahan.advokasi.asistensi.index_pendataanAsistensi',$this->data);
    }

    public function addpendataanAsistensi(Request $request){
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
        // $baseUrl = URL::to('/');

        $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
        $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $propkab;

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        $this->data['breadcrumps'] = breadcrumps_dir_advokasi($request->route()->getName());
        return view('pencegahan.advokasi.asistensi.add_pendataanAsistensi',$this->data);
    }

    public function editpendataanAsistensi(Request $request){
        $id = $request->id;
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
        // $baseUrl = URL::to('/');
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

        $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
        $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $propkab;

        $this->data['data_detail'] = $dataDetail;
        $this->data['id'] = $id;
        $this->data['breadcrumps'] = breadcrumps_dir_advokasi($request->route()->getName());
        return view('pencegahan.advokasi.asistensi.edit_pendataanAsistensi',$this->data);
    }

    public function inputpendataanAsistensi(Request $request){

       $baseUrl = URL::to($this->urlapi());
       // $baseUrl = URL::to('/');
       $token = $request->session()->get('token');

       $client = new Client();

       //generate image base64
       if($request->hasFile('foto1')){
           $filenameWithExt = $request->file('foto1')->getClientOriginalName();
           $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
           $extension = $request->file('foto1')->getClientOriginalExtension();
           $fileNameToStore= $filename.'_'.time().'.'.$extension;
           $path = $request->file('foto1')->storeAs('AdvokasiAsistensi', $fileNameToStore);
           $image = public_path('upload/AdvokasiAsistensi/'.$fileNameToStore);
           $data = file_get_contents($image);
           $image1 = base64_encode($data);
           Storage::delete('AdvokasiAsistensi/'.$fileNameToStore);
       }else{
         $image1 = null;
       }

       if($request->hasFile('foto2')){
           $filenameWithExt = $request->file('foto2')->getClientOriginalName();
           $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
           $extension = $request->file('foto2')->getClientOriginalExtension();
           $fileNameToStore= $filename.'_'.time().'.'.$extension;
           $path = $request->file('foto2')->storeAs('AdvokasiAsistensi', $fileNameToStore);
           $image = public_path('upload/AdvokasiAsistensi/'.$fileNameToStore);
           $data = file_get_contents($image);
           $image2 = base64_encode($data);
           Storage::delete('AdvokasiAsistensi/'.$fileNameToStore);
       }else{
         $image2 = null;
       }

       if($request->hasFile('foto3')){
           $filenameWithExt = $request->file('foto3')->getClientOriginalName();
           $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
           $extension = $request->file('foto3')->getClientOriginalExtension();
           $fileNameToStore= $filename.'_'.time().'.'.$extension;
           $path = $request->file('foto3')->storeAs('AdvokasiAsistensi', $fileNameToStore);
           $image = public_path('upload/AdvokasiAsistensi/'.$fileNameToStore);
           $data = file_get_contents($image);
           $image3 = base64_encode($data);
           Storage::delete('AdvokasiAsistensi/'.$fileNameToStore);
       }else{
         $image3 = null;
       }
       $peserta = $request->input('jumlah_peserta');
       $uraian_singkat_materi = $request->input('uraian_singkat');

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
        // $peserta = 0;
        // if ($jumlah_instansi > 0) {
        //     foreach ($request->input('group-c') as $c1 => $r1) {
        //         $peserta = $peserta + $r1['list_jumlah_peserta'];
        //     }
        // }

        $meta_nasum_materi = $request->input('meta_nasum_materi');
        if (count($meta_nasum_materi) > 0 && $meta_nasum_materi[0]['narasumber'] && $meta_nasum_materi[0]['narasumber']) {
           $meta_nasum_materi = json_encode($request->input('meta_nasum_materi'));
        }else{
          $meta_nasum_materi="";
        }

        $meta_instansi = $request->input('group-c');
        if (count($meta_instansi) > 0 && $meta_instansi[0]['list_nama_instansi']) {
           $meta_instansi = json_encode($request->input('group-c'));
        }else{
          $meta_instansi="";
        }

        $requestInput = $client->request('POST', $baseUrl.'/api/advoasistensi',
               [
                   'headers' =>
                   [
                       'Authorization' => 'Bearer '.$token
                   ],
                   'form_params' => [
                       'idpelaksana' => $request->input('idpelaksana'),
                       'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                       'jenis_kegiatan' => ($request->input('jenis_kegiatan') ? $request->input('jenis_kegiatan') : ''),
                       'kodesasaran' => ($request->input('sasaran') ? $request->input('sasaran') : ''),
                       'jumlah_instansi' => $jumlah_instansi,
                       'meta_instansi' => $meta_instansi,
                       'jumlah_peserta' => $peserta,
                       'uraian_singkat' => $uraian_singkat_materi,
                       'foto1' => $image1,
                       'foto2' => $image2,
                       'foto3' => $image3,
                       // 'jumlah_peserta' => $peserta,
                       'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
                       'meta_nasum_materi' => $meta_nasum_materi,
                       //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
                       'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
                       // 'narasumber' => $request->input('narasumber'),
                       'panitia_monev' => ($request->input('panitia_monev') ? $request->input('panitia_monev') : ''),
                       // 'materi' => $request->input('materi'),
                       'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
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

       $result = json_decode($requestInput->getBody()->getContents(), true);

       $this->form_params = array('idpelaksana' => $request->input('idpelaksana'),
       'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'jenis_kegiatan' => ($request->input('jenis_kegiatan') ? $request->input('jenis_kegiatan') : ''),
       'kodesasaran' => ($request->input('sasaran') ? $request->input('sasaran') : ''),
       'jumlah_instansi' => $jumlah_instansi,
       'meta_instansi' => $meta_instansi,
       'jumlah_peserta' => $peserta,
       'uraian_singkat' => $uraian_singkat_materi,
       'foto1' => $image1,
       'foto2' => $image2,
       'foto3' => $image3,
       // 'jumlah_peserta' => $peserta,
       'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
       'meta_nasum_materi' => $meta_nasum_materi,
       //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
       'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
       // 'narasumber' => $request->input('narasumber'),
       'panitia_monev' => ($request->input('panitia_monev') ? $request->input('panitia_monev') : ''),
       // 'materi' => $request->input('materi'),
       'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
       // 'file_upload' => $anggaran,
       // 'created_at' => $anggaran,
       // 'created_by' => $anggaran,
       'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'periode_tahun' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'anggaran_id' => $anggaran);

       $trail['audit_menu'] = 'Pencegahan - Direktorat Advokasi - Kegiatan Asistensi';
       $trail['audit_event'] = 'post';
       $trail['audit_value'] = json_encode($this->form_params);
       $trail['audit_url'] = $request->url();
       $trail['audit_ip_address'] = $request->ip();
       $trail['audit_user_agent'] = $request->userAgent();
       $trail['audit_message'] = $result['comment'];
       $trail['created_at'] = date("Y-m-d H:i:s");
       $trail['created_by'] = $request->session()->get('id');

       $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


       $inputId = $result['data']['eventID'];

       if ($request->file('file_upload') != ''){
           $fileName = $inputId.'-'.$request->file('file_upload')->getClientOriginalName();
           $request->file('file_upload')->storeAs('AdvokasiAsistensi', $fileName);

           $requestfile = $client->request('PUT', $baseUrl.'/api/advoasistensi/'.$inputId,
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

       $this->kelengkapan_Asistensi($inputId);

       if( ($result['code'] == 200) && ($result['status'] != 'error') ){
         $this->data['status'] = 'success';
         $this->data['message'] = 'Data Kegiatan Asistensi berhasil disimpan. ';
       }else{
       $this->data['status'] = 'error';
         $this->data['message'] = 'Data Kegiatan Asistensi gagal disimpan. ';
       }


       return redirect('pencegahan/dir_advokasi/pendataan_asistensi/')->with('status',$this->data);

    }

    public function updatependataanAsistensi(Request $request){
          $id = $request->input('id');

          $baseUrl = URL::to($this->urlapi());
          // $baseUrl = URL::to('/');
          $token = $request->session()->get('token');

          $client = new Client();

          //generate image base64
          if($request->hasFile('foto1')){
              $filenameWithExt = $request->file('foto1')->getClientOriginalName();
              $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
              $extension = $request->file('foto1')->getClientOriginalExtension();
              $fileNameToStore= $filename.'_'.time().'.'.$extension;
              $path = $request->file('foto1')->storeAs('AdvokasiAsistensi', $fileNameToStore);
              $image = public_path('upload/AdvokasiAsistensi/'.$fileNameToStore);
              $data = file_get_contents($image);
              $image1 = base64_encode($data);
              Storage::delete('AdvokasiAsistensi/'.$fileNameToStore);
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
              $path = $request->file('foto2')->storeAs('AdvokasiAsistensi', $fileNameToStore);
              $image = public_path('upload/AdvokasiAsistensi/'.$fileNameToStore);
              $data = file_get_contents($image);
              $image2 = base64_encode($data);
              Storage::delete('AdvokasiAsistensi/'.$fileNameToStore);
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
              $path = $request->file('foto3')->storeAs('AdvokasiAsistensi', $fileNameToStore);
              $image = public_path('upload/AdvokasiAsistensi/'.$fileNameToStore);
              $data = file_get_contents($image);
              $image3 = base64_encode($data);
              Storage::delete('AdvokasiAsistensi/'.$fileNameToStore);
  //            $form_foto3 = 'foto3 => '.$image.',';
          }else{
              $image3 = $request->input('foto3_old');
  //            $form_foto3 = '';
          }
          $peserta = $request->input('jumlah_peserta');
          $uraian_singkat_materi = $request->input('uraian_singkat');

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
          // if ($jumlah_instansi > 0) {
          //     foreach ($request->input('group-c') as $c1 => $r1) {
          //         $peserta = $peserta + $r1['list_jumlah_peserta'];
          //     }
          // }

          $meta_nasum_materi = $request->input('meta_nasum_materi');
          if (count($meta_nasum_materi) > 0 && $meta_nasum_materi[0]['narasumber'] && $meta_nasum_materi[0]['narasumber']) {
             $meta_nasum_materi = json_encode($request->input('meta_nasum_materi'));
          }else{
            $meta_nasum_materi="";
          }

          $meta_instansi = $request->input('group-c');
          if (count($meta_instansi) > 0 && $meta_instansi[0]['list_nama_instansi']) {
             $meta_instansi = json_encode($request->input('group-c'));
          }else{
            $meta_instansi="";
          }

          $requestInput = $client->request('PUT', $baseUrl.'/api/advoasistensi/'.$id,
                   [
                       'headers' =>
                       [
                           'Authorization' => 'Bearer '.$token
                       ],
                       'form_params' => [
                           'idpelaksana' => $request->input('idpelaksana'),
                           'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                           'jenis_kegiatan' => ($request->input('jenis_kegiatan') ? $request->input('jenis_kegiatan') : ''),
                           'kodesasaran' => ($request->input('sasaran') ? $request->input('sasaran') : ''),
                           'jumlah_instansi' => $jumlah_instansi,
                           'meta_instansi' => $meta_instansi,
                           'uraian_singkat' => $uraian_singkat_materi,
                           'jumlah_peserta' => $peserta,
                           'foto1' => $image1,
                           'foto2' => $image2,
                           'foto3' => $image3,
                           // 'jumlah_peserta' => $peserta,
                           'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
                           'meta_nasum_materi' => $meta_nasum_materi,
                           //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
                           'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
                           // 'narasumber' => $request->input('narasumber'),
                           'panitia_monev' => ($request->input('panitia_monev') ? $request->input('panitia_monev') : ''),
                           // 'materi' => $request->input('materi'),
                           'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
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

           $result = json_decode($requestInput->getBody()->getContents(), true);

           $this->form_params = array('idpelaksana' => $request->input('idpelaksana'),
           'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           'jenis_kegiatan' => ($request->input('jenis_kegiatan') ? $request->input('jenis_kegiatan') : ''),
           'kodesasaran' => ($request->input('sasaran') ? $request->input('sasaran') : ''),
           'jumlah_instansi' => $jumlah_instansi,
           'meta_instansi' => $meta_instansi,
           'uraian_singkat' => $uraian_singkat_materi,
           'jumlah_peserta' => $peserta,
           'foto1' => $image1,
           'foto2' => $image2,
           'foto3' => $image3,
           // 'jumlah_peserta' => $peserta,
           'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
           'meta_nasum_materi' => $meta_nasum_materi,
           //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
           'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
           // 'narasumber' => $request->input('narasumber'),
           'panitia_monev' => ($request->input('panitia_monev') ? $request->input('panitia_monev') : ''),
           // 'materi' => $request->input('materi'),
           'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
           // 'file_upload' => $anggaran,
           // 'created_at' => $anggaran,
           // 'created_by' => $anggaran,
           'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           'periode_tahun' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           'anggaran_id' => $anggaran);

           $trail['audit_menu'] = 'Pencegahan - Direktorat Advokasi - Kegiatan Asistensi';
           $trail['audit_event'] = 'put';
           $trail['audit_value'] = json_encode($this->form_params);
           $trail['audit_url'] = $request->url();
           $trail['audit_ip_address'] = $request->ip();
           $trail['audit_user_agent'] = $request->userAgent();
           $trail['audit_message'] = $result['comment'];
           $trail['created_at'] = date("Y-m-d H:i:s");
           $trail['created_by'] = $request->session()->get('id');

           $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


           if ($request->file('file_upload') != ''){
               $fileName = $id.'-'.$request->file('file_upload')->getClientOriginalName();
               $request->file('file_upload')->storeAs('AdvokasiAsistensi', $fileName);

               $requestfile = $client->request('PUT', $baseUrl.'/api/advoasistensi/'.$id,
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

            $this->kelengkapan_Asistensi($id);

            if( ($result['code'] == 200) && ($result['status'] != 'error') ){
            	$this->data['status'] = 'success';
            	$this->data['message'] = 'Data Kegiatan Asistensi berhasil diperbaharui. ';
            }else{
     		    $this->data['status'] = 'error';
            	$this->data['message'] = 'Data Kegiatan Asistensi gagal diperbaharui. ';
            }


           return back()->with('status',$this->data);
      }

    public function deletePendataanAsistensi(Request $request){
      $id = $request->input('id');
      if($id){
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/advoasistensi/'.$id,'DELETE');
            $this->form_params['delete_id'] = $id;
            $trail['audit_menu'] = 'Pencegahan - Direktorat Advokasi - Kegiatan Asistensi';
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
        $data_request = ['status'=>'error','message'=>'Data Kegiatan Asistensi Gagal Dihapus'];
        return $data_request;
      }
    }

    public function printAsistensi(Request $request){
        $client = new Client();
        $page = $request->input('page');
        $token = $request->session()->get('token');
        $baseUrl = URL::to($this->urlapi());
        // $baseUrl = URL::to('/');

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
        // if($page){
        //     $page = $page;
        // }else{
        //     $page = 1;
        // }
        $result = [];
        $url = 'api/advoasistensi'.$kondisi.'&id_wilayah='.$request->session()->get('wilayah');

        //$no = $start_number;

        $requestPrintData = $client->request('GET', $baseUrl.'/'.$url,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
        $PrintData = json_decode($requestPrintData->getBody()->getContents(), true);
        // dd($pemusnahanladang);
        $DataArray = [];

        $i = $start_number;
        foreach ($PrintData['data'] as $key => $value) {
          $DataArray[$key]['No'] = $i;
          $DataArray[$key]['Tanggal'] = ($value['tgl_pelaksanaan'] ? date('d-m-Y', strtotime($value['tgl_pelaksanaan'])) : '');
          $DataArray[$key]['Pelaksana'] = $value['nm_instansi'];
          $DataArray[$key]['Sasaran'] = $value['kodesasaran'];
          $meta = json_decode($value['meta_instansi'],true);
          if(count($meta)){
            for($j = 0 ; $j < count($meta); $j++){
                $InstansiArray[$key]['Instansi'][$j] = $meta[$j]['list_nama_instansi'];
            }
            $DataArray[$key]['Instansi'] = implode("\n", $InstansiArray[$key]['Instansi']);
          } else {
            $DataArray[$key]['Instansi'] = '-';
          }
          $DataArray[$key]['Sumber Anggaran'] = $value['kodesumberanggaran'];
          $i = $i +1;
        }
         //dd($DataArray);
        $data = $DataArray;
        $name = 'Data Kegiatan Asistensi '.Carbon::now()->format('Y-m-d H:i:s');
        $this->printData($data, $name);
    }

    private function kelengkapan_Asistensi($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('cegahadvokasi_asistensi')->where('id',$id)
                  ->select('tgl_pelaksanaan', 'jenis_kegiatan', 'kodesasaran', 'idpelaksana', 'meta_instansi', 'lokasi_kegiatan', 'lokasi_kegiatan_idkabkota', 'kodesumberanggaran', 'file_upload', 'panitia_monev', 'meta_nasum_materi');
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
          $kelengkapan = execute_api_json('api/advoasistensi/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/advoasistensi/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

    public function penguatanAsistensi(Request $request){
        $client = new Client();
        if ($request->input('page')) {
          $page = $request->input('page');
        } else {
          $page = 1;
        }

        $baseUrl = URL::to($this->urlapi());
        // $baseUrl = URL::to('/');
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

        $this->data['title'] = "advoasistensipenguatan";
        $this->data['forprint'] = $limit.'&'.$offset.$kondisi;
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['token'] = $token;


        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['path'] = $request->path();
        $this->data['page'] = $page;
        $this->data['breadcrumps'] = breadcrumps_dir_advokasi($request->route()->getName());
        return view('pencegahan.advokasi.penguatan.index_penguatanAsistensi',$this->data);
    }

    public function addpenguatanAsistensi(Request $request){
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
        // $baseUrl = URL::to('/');

        $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
        $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $propkab;

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        $this->data['breadcrumps'] = breadcrumps_dir_advokasi($request->route()->getName());
        return view('pencegahan.advokasi.penguatan.add_penguatanAsistensi',$this->data);
    }

    public function editpenguatanAsistensi(Request $request){
        $id = $request->id;
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
        // $baseUrl = URL::to('/');
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

        $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
        $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $propkab;

        $this->data['data_detail'] = $dataDetail;
        $this->data['id'] = $id;
        $this->data['breadcrumps'] = breadcrumps_dir_advokasi($request->route()->getName());
        return view('pencegahan.advokasi.penguatan.edit_penguatanAsistensi',$this->data);
    }

    public function inputpenguatanAsistensi(Request $request){

       $baseUrl = URL::to($this->urlapi());
       // $baseUrl = URL::to('/');
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

        $requestInput = $client->request('POST', $baseUrl.'/api/advoasistensipenguatan',
               [
                   'headers' =>
                   [
                       'Authorization' => 'Bearer '.$token
                   ],
                   'form_params' => [
                       'idpelaksana' => $request->input('idpelaksana'),
                       'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                       'jenis_kegiatan' => $request->input('jenis_kegiatan'),
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

       $result = json_decode($requestInput->getBody()->getContents(), true);

       $inputId = $result['data']['eventID'];

       if ($request->file('file_upload') != ''){
           $fileName = $inputId.'-'.$request->file('file_upload')->getClientOriginalName();
           $request->file('file_upload')->storeAs('AdvokasiAsistensiPenguatan', $fileName);

           $requestfile = $client->request('PUT', $baseUrl.'/api/advoasistensipenguatan/'.$inputId,
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
       return redirect('pencegahan/dir_advokasi/penguatan_asistensi/');

    }

    public function updatepenguatanAsistensi(Request $request){
          $id = $request->input('id');

          $baseUrl = URL::to($this->urlapi());
          // $baseUrl = URL::to('/');
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

          $requestInput = $client->request('PUT', $baseUrl.'/api/advoasistensipenguatan/'.$id,
                   [
                       'headers' =>
                       [
                           'Authorization' => 'Bearer '.$token
                       ],
                       'form_params' => [
                           'idpelaksana' => $request->input('idpelaksana'),
                           'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                           'jenis_kegiatan' => $request->input('jenis_kegiatan'),
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

           $result = json_decode($requestInput->getBody()->getContents(), true);

           if ($request->file('file_upload') != ''){
               $fileName = $id.'-'.$request->file('file_upload')->getClientOriginalName();
               $request->file('file_upload')->storeAs('AdvokasiAsistensiPenguatan', $fileName);

               $requestfile = $client->request('PUT', $baseUrl.'/api/advoasistensipenguatan/'.$id,
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

           return redirect('pencegahan/dir_advokasi/penguatan_asistensi/');
      }

    public function printPenguatan(Request $request){
        $client = new Client();
        $page = $request->input('page');
        $token = $request->session()->get('token');
        $baseUrl = URL::to($this->urlapi());
        // $baseUrl = URL::to('/');

        $requestPrintData = $client->request('GET', $baseUrl.'/api/advoasistensipenguatan?page='.$page,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
        $PrintData = json_decode($requestPrintData->getBody()->getContents(), true);
        // dd($pemusnahanladang);
        $DataArray = [];

        $i = 1;
        foreach ($PrintData['data'] as $key => $value) {
          $DataArray[$key]['No'] = $i;
          $DataArray[$key]['Tanggal'] = $value['tgl_pelaksanaan'];
          $DataArray[$key]['Pelaksana'] = $value['nm_instansi'];
          $DataArray[$key]['Sasaran'] = $value['kodesasaran'];
          $meta = json_decode($value['meta_instansi'],true);
          if(count($meta)){
            for($j = 0 ; $j < count($meta); $j++){
                $InstansiArray[$key]['Instansi'][$j] = $meta[$j]['list_nama_instansi'].'('.$meta[$j]['list_jumlah_peserta'].')';
            }
            $DataArray[$key]['Instansi/Peserta'] = implode("\n", $InstansiArray[$key]['Instansi']);
          } else {
            $DataArray[$key]['Instansi/Peserta'] = '-';
          }
          $DataArray[$key]['Sumber Anggaran'] = $value['kodesumberanggaran'];
          $i = $i +1;
        }
         //dd($DataArray);
        $data = $DataArray;
        $name = 'Data Kegiatan Penguatan Asistensi '.Carbon::now()->format('Y-m-d H:i:s');
        $this->printData($data, $name);
    }

    public function pendataanIntervensi(Request $request){
        $client = new Client();
        if ($request->input('page')) {
          $page = $request->input('page');
        } else {
          $page = 1;
        }

        $baseUrl = URL::to($this->urlapi());
        // $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $kondisi = '';
        if($request->limit) {
          $this->limit = $request->limit;
        } else {
          $this->limit = config('app.limit');
        }

        if($request->isMethod('get')){
          $get = $request->all();
          $get = $request->except(['page']);
          $tipe = $request->tipe;
          if(count($get)>0){
            $this->selected['tipe']  = $tipe;
            foreach ($get as $key => $value) {
              $kondisi .= "&".$key.'='.$value;
              if( ($key == 'tgl_from') || ($key == 'tgl_to') ){
                $this->selected[$key] = date('d/m/Y',strtotime($value));
              }else {
                  $this->selected[$key] = $value;
                  $this->selected['keyword'] = $value;
              }
            }

            $this->selected['order'] = $request->order;
            $this->selected['limit'] = $request->limit;
          }
        } else {
          $post = $request->all();
          $tipe = $request->tipe;
          $kata_kunci = $request->kata_kunci;
          $pelaksana = $request->pelaksana;
          // $namapelaksana = $request->namapelaksana;
          $sasaran = $request->sasaran;
          $anggaran = $request->anggaran;
          $tgl_from = $request->tgl_from;
          $tgl_to = $request->tgl_to;
          $order = $request->order;
          $limit = $request->limit;
          $kelengkapan = $request->kelengkapan;

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
          }else if($tipe == 'pelaksana'){
            $kondisi .= '&pelaksana='.$pelaksana;
            $this->selected['pelaksana'] = $pelaksana;
          }else if($tipe == 'sasaran'){
            $kondisi .= '&sasaran='.$sasaran;
            $this->selected['sasaran'] = $sasaran;
          }else if($tipe == 'anggaran'){
            $kondisi .= '&anggaran='.$anggaran;
            $this->selected['anggaran'] = $anggaran;
          }else if($tipe == 'kelengkapan'){
            $kondisi .= '&kelengkapan='.$kelengkapan;
            $this->selected['kelengkapan'] = $kelengkapan;
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

        $requestAdvointervensi = $client->request('GET', $baseUrl.'/api/advointervensi?id_wilayah='.$request->session()->get('wilayah').'&'.$limit.'&'.$offset.$kondisi,
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

        $this->data['titledel'] = "advointervensi";
        $this->data['title'] = "Kegiatan Intervensi";
        $this->data['forprint'] = $limit.'&'.$offset.$kondisi;
        $this->data['delete_route'] = "delete_pendataan_intervensi";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['token'] = $token;

        $this->data['filter'] = $this->selected;
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['path'] = $request->path();
        $this->data['page'] = $page;
        $this->data['start_number'] = $start_number;
        $total_item = $page['totalpage'] * $this->limit;
        $filtering = false;
	      if($kondisi){
	        $filter = $kondisi;
	        $filtering = true;
	        $this->data['kondisi'] = '?'.$limit.'&'.$offset.$kondisi;
	      }else{
	        $filter = '/';
	        $filtering = false;
	        $this->data['kondisi'] = $current_page;
	      }
        // dd($filter);
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), '/'.$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter);

        $this->data['breadcrumps'] = breadcrumps_dir_advokasi($request->route()->getName());
        return view('pencegahan.advokasi.intervensi.index_pendataanIntervensi',$this->data);
    }

    public function addpendataanIntervensi(Request $request){
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
        // $baseUrl = URL::to('/');

        $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
        $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $propkab;

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        $this->data['breadcrumps'] = breadcrumps_dir_advokasi($request->route()->getName());
        return view('pencegahan.advokasi.intervensi.add_pendataanIntervensi',$this->data);
    }

    public function editpendataanIntervensi(Request $request){
        $id = $request->id;
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
        // $baseUrl = URL::to('/');
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

        $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
        $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $propkab;

        $this->data['data_detail'] = $dataDetail;
        $this->data['id'] = $id;
        $this->data['breadcrumps'] = breadcrumps_dir_advokasi($request->route()->getName());
        return view('pencegahan.advokasi.intervensi.edit_pendataanIntervensi',$this->data);
    }

    public function inputpendataanIntervensi(Request $request){

       $baseUrl = URL::to($this->urlapi());
       // $baseUrl = URL::to('/');
       $token = $request->session()->get('token');

       $client = new Client();

       //generate image base64
       if($request->hasFile('foto1')){
           $filenameWithExt = $request->file('foto1')->getClientOriginalName();
           $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
           $extension = $request->file('foto1')->getClientOriginalExtension();
           $fileNameToStore= $filename.'_'.time().'.'.$extension;
           $path = $request->file('foto1')->storeAs('AdvokasiIntervensi', $fileNameToStore);
           $image = public_path('upload/AdvokasiIntervensi/'.$fileNameToStore);
           $data = file_get_contents($image);
           $image1 = base64_encode($data);
           Storage::delete('AdvokasiIntervensi/'.$fileNameToStore);
       }else{
         $image1 = null;
       }

       if($request->hasFile('foto2')){
           $filenameWithExt = $request->file('foto2')->getClientOriginalName();
           $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
           $extension = $request->file('foto2')->getClientOriginalExtension();
           $fileNameToStore= $filename.'_'.time().'.'.$extension;
           $path = $request->file('foto2')->storeAs('AdvokasiIntervensi', $fileNameToStore);
           $image = public_path('upload/AdvokasiIntervensi/'.$fileNameToStore);
           $data = file_get_contents($image);
           $image2 = base64_encode($data);
           Storage::delete('AdvokasiIntervensi/'.$fileNameToStore);
       }else{
         $image2 = null;
       }

       if($request->hasFile('foto3')){
           $filenameWithExt = $request->file('foto3')->getClientOriginalName();
           $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
           $extension = $request->file('foto3')->getClientOriginalExtension();
           $fileNameToStore= $filename.'_'.time().'.'.$extension;
           $path = $request->file('foto3')->storeAs('AdvokasiIntervensi', $fileNameToStore);
           $image = public_path('upload/AdvokasiIntervensi/'.$fileNameToStore);
           $data = file_get_contents($image);
           $image3 = base64_encode($data);
           Storage::delete('AdvokasiIntervensi/'.$fileNameToStore);
       }else{
         $image3 = null;
       }
       $uraian_singkat_materi = $request->input('uraian_singkat');

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

        $meta_nasum_materi = $request->input('meta_nasum_materi');
        if (count($meta_nasum_materi) > 0 && $meta_nasum_materi[0]['narasumber'] && $meta_nasum_materi[0]['narasumber']) {
           $meta_nasum_materi = json_encode($request->input('meta_nasum_materi'));
        }else{
          $meta_nasum_materi="";
        }

        $meta_instansi = $request->input('group-c');
        if (count($meta_instansi) > 0 && $meta_instansi[0]['list_nama_instansi'] && $meta_instansi[0]['list_jumlah_peserta']) {
           $meta_instansi = json_encode($request->input('group-c'));
        }else{
          $meta_instansi="";
        }

        $requestInput = $client->request('POST', $baseUrl.'/api/advointervensi',
               [
                   'headers' =>
                   [
                       'Authorization' => 'Bearer '.$token
                   ],
                   'form_params' => [
                       'idpelaksana' => $request->input('idpelaksana'),
                       'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                       // 'jenis_kegiatan' => $request->input('sasaran'),
                       'kodesasaran' => ($request->input('sasaran') ? $request->input('sasaran') : ''),
                       'jumlah_instansi' => $jumlah_instansi,
                       'meta_instansi' => $meta_instansi,
                       'jumlah_peserta' => $peserta,
                       'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
                       'meta_nasum_materi' => $meta_nasum_materi,
                       'uraian_singkat' => $uraian_singkat_materi,
                       'foto1' => $image1,
                       'foto2' => $image2,
                       'foto3' => $image3,
                       //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
                       'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
                       // 'narasumber' => $request->input('narasumber'),
                       'panitia_monev' => ($request->input('panitia_monev') ? $request->input('panitia_monev') : ''),
                       // 'materi' => $request->input('materi'),
                       'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
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

       $result = json_decode($requestInput->getBody()->getContents(), true);

       $this->form_params = array('idpelaksana' => $request->input('idpelaksana'),
       'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       // 'jenis_kegiatan' => $request->input('sasaran'),
       'kodesasaran' => ($request->input('sasaran') ? $request->input('sasaran') : ''),
       'jumlah_instansi' => $jumlah_instansi,
       'meta_instansi' => $meta_instansi,
       'jumlah_peserta' => $peserta,
       'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
       'meta_nasum_materi' => $meta_nasum_materi,
       'uraian_singkat' => $uraian_singkat_materi,
       'foto1' => $image1,
       'foto2' => $image2,
       'foto3' => $image3,
       //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
       'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
       // 'narasumber' => $request->input('narasumber'),
       'panitia_monev' => ($request->input('panitia_monev') ? $request->input('panitia_monev') : ''),
       // 'materi' => $request->input('materi'),
       'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
       // 'file_upload' => $anggaran,
       // 'created_at' => $anggaran,
       // 'created_by' => $anggaran,
       'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'periode_tahun' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'anggaran_id' => $anggaran);

       $trail['audit_menu'] = 'Pencegahan - Direktorat Advokasi - Kegiatan Intervensi';
       $trail['audit_event'] = 'post';
       $trail['audit_value'] = json_encode($this->form_params);
       $trail['audit_url'] = $request->url();
       $trail['audit_ip_address'] = $request->ip();
       $trail['audit_user_agent'] = $request->userAgent();
       $trail['audit_message'] = $result['comment'];
       $trail['created_at'] = date("Y-m-d H:i:s");
       $trail['created_by'] = $request->session()->get('id');

       $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


       $inputId = $result['data']['eventID'];

       if ($request->file('file_upload') != ''){
           $fileName = $inputId.'-'.$request->file('file_upload')->getClientOriginalName();
           $request->file('file_upload')->storeAs('AdvokasiIntervensi', $fileName);

           $requestfile = $client->request('PUT', $baseUrl.'/api/advointervensi/'.$inputId,
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

       $this->kelengkapan_Intervensi($inputId);

       if( ($result['code'] == 200) && ($result['status'] != 'error') ){
         $this->data['status'] = 'success';
         $this->data['message'] = 'Data Kegiatan Intervensi berhasil disimpan. ';
       }else{
       $this->data['status'] = 'error';
         $this->data['message'] = 'Data Kegiatan Intervensi gagal disimpan. ';
       }


       return redirect('pencegahan/dir_advokasi/pendataan_intervensi/')->with('status',$this->data);

    }

    public function updatependataanIntervensi(Request $request){
          $id = $request->input('id');

          $baseUrl = URL::to($this->urlapi());
          // $baseUrl = URL::to('/');
          $token = $request->session()->get('token');

          $client = new Client();

          //generate image base64
          if($request->hasFile('foto1')){
              $filenameWithExt = $request->file('foto1')->getClientOriginalName();
              $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
              $extension = $request->file('foto1')->getClientOriginalExtension();
              $fileNameToStore= $filename.'_'.time().'.'.$extension;
              $path = $request->file('foto1')->storeAs('AdvokasiIntervensi', $fileNameToStore);
              $image = public_path('upload/AdvokasiIntervensi/'.$fileNameToStore);
              $data = file_get_contents($image);
              $image1 = base64_encode($data);
              Storage::delete('AdvokasiIntervensi/'.$fileNameToStore);
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
              $path = $request->file('foto2')->storeAs('AdvokasiIntervensi', $fileNameToStore);
              $image = public_path('upload/AdvokasiIntervensi/'.$fileNameToStore);
              $data = file_get_contents($image);
              $image2 = base64_encode($data);
              Storage::delete('AdvokasiIntervensi/'.$fileNameToStore);
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
              $path = $request->file('foto3')->storeAs('AdvokasiIntervensi', $fileNameToStore);
              $image = public_path('upload/AdvokasiIntervensi/'.$fileNameToStore);
              $data = file_get_contents($image);
              $image3 = base64_encode($data);
              Storage::delete('AdvokasiIntervensi/'.$fileNameToStore);
  //            $form_foto3 = 'foto3 => '.$image.',';
          }else{
              $image3 = $request->input('foto3_old');
  //            $form_foto3 = '';
          }
          $uraian_singkat_materi = $request->input('uraian_singkat');

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

          $meta_nasum_materi = $request->input('meta_nasum_materi');
          if (count($meta_nasum_materi) > 0 && $meta_nasum_materi[0]['narasumber'] && $meta_nasum_materi[0]['narasumber']) {
             $meta_nasum_materi = json_encode($request->input('meta_nasum_materi'));
          }else{
            $meta_nasum_materi="";
          }

          $meta_instansi = $request->input('group-c');
          if (count($meta_instansi) > 0 && $meta_instansi[0]['list_nama_instansi'] && $meta_instansi[0]['list_jumlah_peserta']) {
             $meta_instansi = json_encode($request->input('group-c'));
          }else{
            $meta_instansi="";
          }

          $requestInput = $client->request('PUT', $baseUrl.'/api/advointervensi/'.$id,
                   [
                       'headers' =>
                       [
                           'Authorization' => 'Bearer '.$token
                       ],
                       'form_params' => [
                           'idpelaksana' => $request->input('idpelaksana'),
                           'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                           // 'jenis_kegiatan' => $request->input('sasaran'),
                           'kodesasaran' => ($request->input('sasaran') ? $request->input('sasaran') : ''),
                           'jumlah_instansi' => $jumlah_instansi,
                           'meta_instansi' => $meta_instansi,
                           'jumlah_peserta' => $peserta,
                           'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
                           'meta_nasum_materi' => $meta_nasum_materi,
                           'uraian_singkat' => $uraian_singkat_materi,
                           'foto1' => $image1,
                           'foto2' => $image2,
                           'foto3' => $image3,
                           //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
                           'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
                           // 'narasumber' => $request->input('narasumber'),
                           'panitia_monev' => ($request->input('panitia_monev') ? $request->input('panitia_monev') : ''),
                           // 'materi' => $request->input('materi'),
                           'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
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

           $result = json_decode($requestInput->getBody()->getContents(), true);

           $this->form_params = array('idpelaksana' => $request->input('idpelaksana'),
           'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           // 'jenis_kegiatan' => $request->input('sasaran'),
           'kodesasaran' => ($request->input('sasaran') ? $request->input('sasaran') : ''),
           'jumlah_instansi' => $jumlah_instansi,
           'meta_instansi' => $meta_instansi,
           'jumlah_peserta' => $peserta,
           'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
           'meta_nasum_materi' => $meta_nasum_materi,
           'uraian_singkat' => $uraian_singkat_materi,
           'foto1' => $image1,
           'foto2' => $image2,
           'foto3' => $image3,
           //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
           'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
           // 'narasumber' => $request->input('narasumber'),
           'panitia_monev' => ($request->input('panitia_monev') ? $request->input('panitia_monev') : ''),
           // 'materi' => $request->input('materi'),
           'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
           // 'file_upload' => $anggaran,
           // 'created_at' => $anggaran,
           // 'created_by' => $anggaran,
           'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           'periode_tahun' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           'anggaran_id' => $anggaran);

           $trail['audit_menu'] = 'Pencegahan - Direktorat Advokasi - Kegiatan Intervensi';
           $trail['audit_event'] = 'put';
           $trail['audit_value'] = json_encode($this->form_params);
           $trail['audit_url'] = $request->url();
           $trail['audit_ip_address'] = $request->ip();
           $trail['audit_user_agent'] = $request->userAgent();
           $trail['audit_message'] = $result['comment'];
           $trail['created_at'] = date("Y-m-d H:i:s");
           $trail['created_by'] = $request->session()->get('id');

           $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


           if ($request->file('file_upload') != ''){
               $fileName = $id.'-'.$request->file('file_upload')->getClientOriginalName();
               $request->file('file_upload')->storeAs('AdvokasiIntervensi', $fileName);

               $requestfile = $client->request('PUT', $baseUrl.'/api/advointervensi/'.$id,
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

            $this->kelengkapan_Intervensi($id);

            if( ($result['code'] == 200) && ($result['status'] != 'error') ){
            	$this->data['status'] = 'success';
            	$this->data['message'] = 'Data Kegiatan Intervensi berhasil diperbaharui. ';
            }else{
     		    $this->data['status'] = 'error';
            	$this->data['message'] = 'Data Kegiatan Intervensi gagal diperbaharui. ';
            }


           return back()->with('status',$this->data);
      }

    public function deletePendataanIntervensi(Request $request){
      $id = $request->input('id');
      if($id){
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/advointervensi/'.$id,'DELETE');
            $this->form_params['delete_id'] = $id;
            $trail['audit_menu'] = 'Pencegahan - Direktorat Advokasi - Kegiatan Intervensi';
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
        $data_request = ['status'=>'error','message'=>'Data Kegiatan Intervensi Gagal Dihapus'];
        return $data_request;
      }
    }

    public function printIntervensi(Request $request){
        $client = new Client();
        $page = $request->input('page');
        $token = $request->session()->get('token');
        $baseUrl = URL::to($this->urlapi());
        // $baseUrl = URL::to('/');

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
        // if($page){
        //     $page = $page;
        // }else{
        //     $page = 1;
        // }
        $result = [];
        $url = 'api/advointervensi'.$kondisi.'&id_wilayah='.$request->session()->get('wilayah');

        //$no = $start_number;

        $requestPrintData = $client->request('GET', $baseUrl.'/'.$url,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
        $PrintData = json_decode($requestPrintData->getBody()->getContents(), true);
        // dd($pemusnahanladang);
        $DataArray = [];

        $i = $start_number;
        foreach ($PrintData['data'] as $key => $value) {
          $DataArray[$key]['No'] = $i;
          $DataArray[$key]['Tanggal'] = ($value['tgl_pelaksanaan'] ? date('d-m-Y', strtotime($value['tgl_pelaksanaan'])) : '');
          $DataArray[$key]['Pelaksana'] = $value['nm_instansi'];
          $DataArray[$key]['Sasaran'] = $value['kodesasaran'];
          $meta = json_decode($value['meta_instansi'],true);
          if(count($meta)){
            for($j = 0 ; $j < count($meta); $j++){
                $InstansiArray[$key]['Instansi'][$j] = $meta[$j]['list_nama_instansi'].'('.$meta[$j]['list_jumlah_peserta'].')';
            }
            $DataArray[$key]['Instansi/Peserta'] = implode("\n", $InstansiArray[$key]['Instansi']);
          } else {
            $DataArray[$key]['Instansi/Peserta'] = '-';
          }
          $DataArray[$key]['Sumber Anggaran'] = $value['kodesumberanggaran'];
          $i = $i +1;
        }
         //dd($DataArray);
        $data = $DataArray;
        $name = 'Data Kegiatan Intervensi '.Carbon::now()->format('Y-m-d H:i:s');
        $this->printData($data, $name);
    }

    private function kelengkapan_Intervensi($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('cegahadvokasi_intervensi')->where('id',$id)
                  ->select('tgl_pelaksanaan', 'kodesasaran', 'idpelaksana', 'meta_instansi', 'lokasi_kegiatan', 'lokasi_kegiatan_idkabkota', 'kodesumberanggaran', 'file_upload', 'panitia_monev', 'meta_nasum_materi');
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
          $kelengkapan = execute_api_json('api/advointervensi/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/advointervensi/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

    public function pendataanSupervisi(Request $request){
        $client = new Client();
        if ($request->input('page')) {
          $page = $request->input('page');
        } else {
          $page = 1;
        }

        $baseUrl = URL::to($this->urlapi());
        // $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $kondisi = '';
        if($request->limit) {
          $this->limit = $request->limit;
        } else {
          $this->limit = config('app.limit');
        }

        if($request->isMethod('get')){
          $get = $request->all();
          $get = $request->except(['page']);
          $tipe = $request->tipe;
          if(count($get)>0){
            $this->selected['tipe']  = $tipe;
            foreach ($get as $key => $value) {
              $kondisi .= "&".$key.'='.$value;
              if( ($key == 'tgl_from') || ($key == 'tgl_to') ){
                $this->selected[$key] = date('d/m/Y',strtotime($value));
              }else {
                  $this->selected[$key] = $value;
                  $this->selected['keyword'] = $value;
              }
            }

            $this->selected['order'] = $request->order;
            $this->selected['limit'] = $request->limit;
          }
        } else {
          $post = $request->all();
          $tipe = $request->tipe;
          $kata_kunci = $request->kata_kunci;
          $pelaksana = $request->pelaksana;
          // $namapelaksana = $request->namapelaksana;
          $sasaran = $request->sasaran;
          $anggaran = $request->anggaran;
          $tgl_from = $request->tgl_from;
          $tgl_to = $request->tgl_to;
          $order = $request->order;
          $limit = $request->limit;
          $kelengkapan = $request->kelengkapan;

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
          }else if($tipe == 'pelaksana'){
            $kondisi .= '&pelaksana='.$pelaksana;
            $this->selected['pelaksana'] = $pelaksana;
          }else if($tipe == 'sasaran'){
            $kondisi .= '&sasaran='.$sasaran;
            $this->selected['sasaran'] = $sasaran;
          }else if($tipe == 'anggaran'){
            $kondisi .= '&anggaran='.$anggaran;
            $this->selected['anggaran'] = $anggaran;
          }else if($tipe == 'kelengkapan'){
            $kondisi .= '&kelengkapan='.$kelengkapan;
            $this->selected['kelengkapan'] = $kelengkapan;
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

        $requestAdvosupervisi = $client->request('GET', $baseUrl.'/api/advosupervisi?id_wilayah='.$request->session()->get('wilayah').'&'.$limit.'&'.$offset.$kondisi,
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

        $this->data['titledel'] = "advosupervisi";
        $this->data['title'] = "Kegiatan Supervisi";
        $this->data['forprint'] = $limit.'&'.$offset.$kondisi;
        $this->data['delete_route'] = "delete_pendataan_supervisi";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['token'] = $token;

        $this->data['filter'] = $this->selected;
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['path'] = $request->path();
        $this->data['page'] = $page;
        $this->data['start_number'] = $start_number;
        $total_item = $page['totalpage'] * $this->limit;
        $filtering = false;
	      if($kondisi){
	        $filter = $kondisi;
	        $filtering = true;
	        $this->data['kondisi'] = '?'.$limit.'&'.$offset.$kondisi;
	      }else{
	        $filter = '/';
	        $filtering = false;
	        $this->data['kondisi'] = $current_page;
	      }
        // dd($filter);
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), '/'.$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter);

        $this->data['breadcrumps'] = breadcrumps_dir_advokasi($request->route()->getName());
        return view('pencegahan.advokasi.supervisi.index_pendataanSupervisi',$this->data);
    }

    public function addpendataanSupervisi(Request $request){
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
        // $baseUrl = URL::to('/');

        $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
        $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $propkab;

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        $this->data['breadcrumps'] = breadcrumps_dir_advokasi($request->route()->getName());
        return view('pencegahan.advokasi.supervisi.add_pendataanSupervisi',$this->data);
    }

    public function editpendataanSupervisi(Request $request){
        $id = $request->id;
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
        // $baseUrl = URL::to('/');
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

        $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
        $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $propkab;

        $this->data['data_detail'] = $dataDetail;
        $this->data['id'] = $id;
        $this->data['breadcrumps'] = breadcrumps_dir_advokasi($request->route()->getName());
        return view('pencegahan.advokasi.supervisi.edit_pendataanSupervisi',$this->data);
    }

    public function inputpendataanSupervisi(Request $request){

       $baseUrl = URL::to($this->urlapi());
       // $baseUrl = URL::to('/');
       $token = $request->session()->get('token');

       $client = new Client();

       //generate image base64
       if($request->hasFile('foto1')){
           $filenameWithExt = $request->file('foto1')->getClientOriginalName();
           $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
           $extension = $request->file('foto1')->getClientOriginalExtension();
           $fileNameToStore= $filename.'_'.time().'.'.$extension;
           $path = $request->file('foto1')->storeAs('AdvokasiSupervisi', $fileNameToStore);
           $image = public_path('upload/AdvokasiSupervisi/'.$fileNameToStore);
           $data = file_get_contents($image);
           $image1 = base64_encode($data);
           Storage::delete('AdvokasiSupervisi/'.$fileNameToStore);
       }else{
         $image1 = null;
       }

       if($request->hasFile('foto2')){
           $filenameWithExt = $request->file('foto2')->getClientOriginalName();
           $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
           $extension = $request->file('foto2')->getClientOriginalExtension();
           $fileNameToStore= $filename.'_'.time().'.'.$extension;
           $path = $request->file('foto2')->storeAs('AdvokasiSupervisi', $fileNameToStore);
           $image = public_path('upload/AdvokasiSupervisi/'.$fileNameToStore);
           $data = file_get_contents($image);
           $image2 = base64_encode($data);
           Storage::delete('AdvokasiSupervisi/'.$fileNameToStore);
       }else{
         $image2 = null;
       }

       if($request->hasFile('foto3')){
           $filenameWithExt = $request->file('foto3')->getClientOriginalName();
           $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
           $extension = $request->file('foto3')->getClientOriginalExtension();
           $fileNameToStore= $filename.'_'.time().'.'.$extension;
           $path = $request->file('foto3')->storeAs('AdvokasiSupervisi', $fileNameToStore);
           $image = public_path('upload/AdvokasiSupervisi/'.$fileNameToStore);
           $data = file_get_contents($image);
           $image3 = base64_encode($data);
           Storage::delete('AdvokasiSupervisi/'.$fileNameToStore);
       }else{
         $image3 = null;
       }
       $peserta = $request->input('jumlah_peserta');
       $uraian_singkat_materi = $request->input('uraian_singkat');

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
        $peserta = $request->input('jumlah_peserta');
        $uraian_singkat_materi = $request->input('uraian_singkat');
        // $peserta = 0;
        // if ($jumlah_instansi > 0) {
        //     foreach ($request->input('group-c') as $c1 => $r1) {
        //         $peserta = $peserta + $r1['list_jumlah_peserta'];
        //     }
        // }

        $meta_nasum_materi = $request->input('meta_nasum_materi');
        if (count($meta_nasum_materi) > 0 && $meta_nasum_materi[0]['narasumber'] && $meta_nasum_materi[0]['narasumber']) {
           $meta_nasum_materi = json_encode($request->input('meta_nasum_materi'));
        }else{
          $meta_nasum_materi="";
        }

        $meta_instansi = $request->input('group-c');
        if (count($meta_instansi) > 0 && $meta_instansi[0]['list_nama_instansi']) {
           $meta_instansi = json_encode($request->input('group-c'));
        }else{
          $meta_instansi="";
        }

        $requestInput = $client->request('POST', $baseUrl.'/api/advosupervisi',
               [
                   'headers' =>
                   [
                       'Authorization' => 'Bearer '.$token
                   ],
                   'form_params' => [
                       'idpelaksana' => $request->input('idpelaksana'),
                       'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                       // 'jenis_kegiatan' => $request->input('sasaran'),
                       'kodesasaran' => ($request->input('sasaran') ? $request->input('sasaran') : ''),
                       'jumlah_instansi' => $jumlah_instansi,
                       'meta_instansi' => $meta_instansi,
                       'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
                       'meta_nasum_materi' => $meta_nasum_materi,
                       'jumlah_peserta' => $peserta,
                       'uraian_singkat' => $uraian_singkat_materi,
                       'foto1' => $image1,
                       'foto2' => $image2,
                       'foto3' => $image3,
                       //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
                       'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
                       // 'narasumber' => $request->input('narasumber'),
                       'panitia_monev' => ($request->input('panitia_monev') ? $request->input('panitia_monev') : ''),
                       // 'materi' => $request->input('materi'),
                       'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
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

       $result = json_decode($requestInput->getBody()->getContents(), true);

       $this->form_params = array('idpelaksana' => $request->input('idpelaksana'),
       'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       // 'jenis_kegiatan' => $request->input('sasaran'),
       'kodesasaran' => ($request->input('sasaran') ? $request->input('sasaran') : ''),
       'jumlah_instansi' => $jumlah_instansi,
       'meta_instansi' => $meta_instansi,
       // 'jumlah_peserta' => $peserta,
       'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
       'meta_nasum_materi' => $meta_nasum_materi,
       //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
       'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
       // 'narasumber' => $request->input('narasumber'),
       'panitia_monev' => ($request->input('panitia_monev') ? $request->input('panitia_monev') : ''),
       // 'materi' => $request->input('materi'),
       'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
       // 'file_upload' => $anggaran,
       // 'created_at' => $anggaran,
       // 'created_by' => $anggaran,
       'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'periode_tahun' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'anggaran_id' => $anggaran);

       $trail['audit_menu'] = 'Pencegahan - Direktorat Advokasi - Kegiatan Supervisi';
       $trail['audit_event'] = 'post';
       $trail['audit_value'] = json_encode($this->form_params);
       $trail['audit_url'] = $request->url();
       $trail['audit_ip_address'] = $request->ip();
       $trail['audit_user_agent'] = $request->userAgent();
       $trail['audit_message'] = $result['comment'];
       $trail['created_at'] = date("Y-m-d H:i:s");
       $trail['created_by'] = $request->session()->get('id');

       $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


       $inputId = $result['data']['eventID'];

       if ($request->file('file_upload') != ''){
           $fileName = $inputId.'-'.$request->file('file_upload')->getClientOriginalName();
           $request->file('file_upload')->storeAs('AdvokasiSupervisi', $fileName);

           $requestfile = $client->request('PUT', $baseUrl.'/api/advosupervisi/'.$inputId,
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

       $this->kelengkapan_Supervisi($inputId);

       if( ($result['code'] == 200) && ($result['status'] != 'error') ){
         $this->data['status'] = 'success';
         $this->data['message'] = 'Data Kegiatan Supervisi berhasil disimpan. ';
       }else{
       $this->data['status'] = 'error';
         $this->data['message'] = 'Data Kegiatan Supervisi gagal disimpan. ';
       }


       return redirect('pencegahan/dir_advokasi/pendataan_supervisi/')->with('status',$this->data);

    }

    public function updatependataanSupervisi(Request $request){
          $id = $request->input('id');

          $baseUrl = URL::to($this->urlapi());
          // $baseUrl = URL::to('/');
          $token = $request->session()->get('token');

          $client = new Client();

          //generate image base64
          if($request->hasFile('foto1')){
              $filenameWithExt = $request->file('foto1')->getClientOriginalName();
              $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
              $extension = $request->file('foto1')->getClientOriginalExtension();
              $fileNameToStore= $filename.'_'.time().'.'.$extension;
              $path = $request->file('foto1')->storeAs('AdvokasiSupervisi', $fileNameToStore);
              $image = public_path('upload/AdvokasiSupervisi/'.$fileNameToStore);
              $data = file_get_contents($image);
              $image1 = base64_encode($data);
              Storage::delete('AdvokasiSupervisi/'.$fileNameToStore);
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
              $path = $request->file('foto2')->storeAs('AdvokasiSupervisi', $fileNameToStore);
              $image = public_path('upload/AdvokasiSupervisi/'.$fileNameToStore);
              $data = file_get_contents($image);
              $image2 = base64_encode($data);
              Storage::delete('AdvokasiSupervisi/'.$fileNameToStore);
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
              $path = $request->file('foto3')->storeAs('AdvokasiSupervisi', $fileNameToStore);
              $image = public_path('upload/AdvokasiSupervisi/'.$fileNameToStore);
              $data = file_get_contents($image);
              $image3 = base64_encode($data);
              Storage::delete('AdvokasiSupervisi/'.$fileNameToStore);
  //            $form_foto3 = 'foto3 => '.$image.',';
          }else{
              $image3 = $request->input('foto3_old');
  //            $form_foto3 = '';
          }
          $peserta = $request->input('jumlah_peserta');
          $uraian_singkat_materi = $request->input('uraian_singkat');

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
          // $peserta = 0;
          // if ($jumlah_instansi > 0) {
          //     foreach ($request->input('group-c') as $c1 => $r1) {
          //         $peserta = $peserta + $r1['list_jumlah_peserta'];
          //     }
          // }

          $meta_nasum_materi = $request->input('meta_nasum_materi');
          if (count($meta_nasum_materi) > 0 && $meta_nasum_materi[0]['narasumber'] && $meta_nasum_materi[0]['narasumber']) {
             $meta_nasum_materi = json_encode($request->input('meta_nasum_materi'));
          }else{
            $meta_nasum_materi="";
          }

          $meta_instansi = $request->input('group-c');
          if (count($meta_instansi) > 0 && $meta_instansi[0]['list_nama_instansi']) {
             $meta_instansi = json_encode($request->input('group-c'));
          }else{
            $meta_instansi="";
          }

          $requestInput = $client->request('PUT', $baseUrl.'/api/advosupervisi/'.$id,
                   [
                       'headers' =>
                       [
                           'Authorization' => 'Bearer '.$token
                       ],
                       'form_params' => [
                           'idpelaksana' => $request->input('idpelaksana'),
                           'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                           // 'jenis_kegiatan' => $request->input('sasaran'),
                           'kodesasaran' => ($request->input('sasaran') ? $request->input('sasaran') : ''),
                           'jumlah_instansi' => $jumlah_instansi,
                           'meta_instansi' => $meta_instansi,
                           'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
                           'meta_nasum_materi' => $meta_nasum_materi,
                           'uraian_singkat' => $uraian_singkat_materi,
                           'jumlah_peserta' => $peserta,
                           'foto1' => $image1,
                           'foto2' => $image2,
                           'foto3' => $image3,
                           //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
                           'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
                           // 'narasumber' => $request->input('narasumber'),
                           'panitia_monev' => ($request->input('panitia_monev') ? $request->input('panitia_monev') : ''),
                           // 'materi' => $request->input('materi'),
                           'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
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

           $result = json_decode($requestInput->getBody()->getContents(), true);

           $this->form_params = array('idpelaksana' => $request->input('idpelaksana'),
           'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           // 'jenis_kegiatan' => $request->input('sasaran'),
           'kodesasaran' => ($request->input('sasaran') ? $request->input('sasaran') : ''),
           'jumlah_instansi' => $jumlah_instansi,
           'meta_instansi' => $meta_instansi,
           // 'jumlah_peserta' => $peserta,
           'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
           'meta_nasum_materi' => $meta_nasum_materi,
           //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
           'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
           // 'narasumber' => $request->input('narasumber'),
           'panitia_monev' => ($request->input('panitia_monev') ? $request->input('panitia_monev') : ''),
           // 'materi' => $request->input('materi'),
           'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
           // 'file_upload' => $anggaran,
           // 'created_at' => $anggaran,
           // 'created_by' => $anggaran,
           'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           'periode_tahun' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           'anggaran_id' => $anggaran);

           $trail['audit_menu'] = 'Pencegahan - Direktorat Advokasi - Kegiatan Supervisi';
           $trail['audit_event'] = 'put';
           $trail['audit_value'] = json_encode($this->form_params);
           $trail['audit_url'] = $request->url();
           $trail['audit_ip_address'] = $request->ip();
           $trail['audit_user_agent'] = $request->userAgent();
           $trail['audit_message'] = $result['comment'];
           $trail['created_at'] = date("Y-m-d H:i:s");
           $trail['created_by'] = $request->session()->get('id');

           $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


           if ($request->file('file_upload') != ''){
               $fileName = $id.'-'.$request->file('file_upload')->getClientOriginalName();
               $request->file('file_upload')->storeAs('AdvokasiSupervisi', $fileName);

               $requestfile = $client->request('PUT', $baseUrl.'/api/advosupervisi/'.$id,
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

            $this->kelengkapan_Supervisi($id);

            if( ($result['code'] == 200) && ($result['status'] != 'error') ){
            	$this->data['status'] = 'success';
            	$this->data['message'] = 'Data Kegiatan Supervisi berhasil diperbaharui. ';
            }else{
     		    $this->data['status'] = 'error';
            	$this->data['message'] = 'Data Kegiatan Supervisi gagal diperbaharui. ';
            }


           return back()->with('status',$this->data);
    }

    public function deletePendataanSupervisi(Request $request){
      $id = $request->input('id');
      if($id){
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/advosupervisi/'.$id,'DELETE');
            $this->form_params['delete_id'] = $id;
            $trail['audit_menu'] = 'Pencegahan - Direktorat Advokasi - Kegiatan Supervisi';
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
        $data_request = ['status'=>'error','message'=>'Data Kegiatan Supervisi Gagal Dihapus'];
        return $data_request;
      }
    }

    public function printSupervisi(Request $request){
        $client = new Client();
        $page = $request->input('page');
        $token = $request->session()->get('token');
        $baseUrl = URL::to($this->urlapi());
        // $baseUrl = URL::to('/');

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
        // if($page){
        //     $page = $page;
        // }else{
        //     $page = 1;
        // }
        $result = [];
        $url = 'api/advosupervisi'.$kondisi.'&id_wilayah='.$request->session()->get('wilayah');

        //$no = $start_number;

        $requestPrintData = $client->request('GET', $baseUrl.'/'.$url,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
        $PrintData = json_decode($requestPrintData->getBody()->getContents(), true);
        // dd($pemusnahanladang);
        $DataArray = [];

        $i = $start_number;
        foreach ($PrintData['data'] as $key => $value) {
          $DataArray[$key]['No'] = $i;
          $DataArray[$key]['Tanggal'] = ($value['tgl_pelaksanaan'] ? date('d-m-Y', strtotime($value['tgl_pelaksanaan'])) : '');
          $DataArray[$key]['Pelaksana'] = $value['nm_instansi'];
          $DataArray[$key]['Sasaran'] = $value['kodesasaran'];
          $meta = json_decode($value['meta_instansi'],true);
          if(count($meta)){
            for($j = 0 ; $j < count($meta); $j++){
                $InstansiArray[$key]['Instansi'][$j] = $meta[$j]['list_nama_instansi'];
            }
            $DataArray[$key]['Instansi'] = implode("\n", $InstansiArray[$key]['Instansi']);
          } else {
            $DataArray[$key]['Instansi'] = '-';
          }
          $DataArray[$key]['Sumber Anggaran'] = $value['kodesumberanggaran'];
          $i = $i +1;
        }
         //dd($DataArray);
        $data = $DataArray;
        $name = 'Data Kegiatan Supervisi '.Carbon::now()->format('Y-m-d H:i:s');
        $this->printData($data, $name);
    }

    private function kelengkapan_Supervisi($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('cegahadvokasi_supervisi')->where('id',$id)
                  ->select('tgl_pelaksanaan', 'kodesasaran', 'idpelaksana', 'meta_instansi', 'lokasi_kegiatan', 'lokasi_kegiatan_idkabkota', 'kodesumberanggaran', 'file_upload', 'panitia_monev', 'meta_nasum_materi');
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
          $kelengkapan = execute_api_json('api/advosupervisi/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/advosupervisi/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

    public function pendataanMonitoring(Request $request){
        $client = new Client();
        if ($request->input('page')) {
          $page = $request->input('page');
        } else {
          $page = 1;
        }

        $baseUrl = URL::to($this->urlapi());
        // $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $kondisi = '';
        if($request->limit) {
          $this->limit = $request->limit;
        } else {
          $this->limit = config('app.limit');
        }

        if($request->isMethod('get')){
          $get = $request->all();
          $get = $request->except(['page']);
          $tipe = $request->tipe;
          if(count($get)>0){
            $this->selected['tipe']  = $tipe;
            foreach ($get as $key => $value) {
              $kondisi .= "&".$key.'='.$value;
              if( ($key == 'tgl_from') || ($key == 'tgl_to') ){
                $this->selected[$key] = date('d/m/Y',strtotime($value));
              }else {
                  $this->selected[$key] = $value;
                  $this->selected['keyword'] = $value;
              }
            }

            $this->selected['order'] = $request->order;
            $this->selected['limit'] = $request->limit;
          }
        } else {
          $post = $request->all();
          $tipe = $request->tipe;
          $kata_kunci = $request->kata_kunci;
          $pelaksana = $request->pelaksana;
          // $namapelaksana = $request->namapelaksana;
          $sasaran = $request->sasaran;
          $anggaran = $request->anggaran;
          $tgl_from = $request->tgl_from;
          $tgl_to = $request->tgl_to;
          $order = $request->order;
          $limit = $request->limit;
          $kelengkapan = $request->kelengkapan;

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
          }else if($tipe == 'pelaksana'){
            $kondisi .= '&pelaksana='.$pelaksana;
            $this->selected['pelaksana'] = $pelaksana;
          }else if($tipe == 'sasaran'){
            $kondisi .= '&sasaran='.$sasaran;
            $this->selected['sasaran'] = $sasaran;
          }else if($tipe == 'anggaran'){
            $kondisi .= '&anggaran='.$anggaran;
            $this->selected['anggaran'] = $anggaran;
          }else if($tipe == 'kelengkapan'){
            $kondisi .= '&kelengkapan='.$kelengkapan;
            $this->selected['kelengkapan'] = $kelengkapan;
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

        $requestAdvomonev = $client->request('GET', $baseUrl.'/api/advomonev?id_wilayah='.$request->session()->get('wilayah').'&'.$limit.'&'.$offset.$kondisi,
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
        $this->data['titledel'] = "advomonev";
        $this->data['title'] = "Kegiatan Monitoring Evaluasi";
        $this->data['forprint'] = $limit.'&'.$offset.$kondisi;
        $this->data['delete_route'] = "delete_pendataan_monitoring";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['token'] = $token;

        $this->data['filter'] = $this->selected;
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['path'] = $request->path();
        $this->data['page'] = $page;
        $this->data['start_number'] = $start_number;
        $total_item = $page['totalpage'] * $this->limit;
        $filtering = false;
	      if($kondisi){
	        $filter = $kondisi;
	        $filtering = true;
	        $this->data['kondisi'] = '?'.$limit.'&'.$offset.$kondisi;
	      }else{
	        $filter = '/';
	        $filtering = false;
	        $this->data['kondisi'] = $current_page;
	      }
        // dd($filter);
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), '/'.$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter);

        $this->data['breadcrumps'] = breadcrumps_dir_advokasi($request->route()->getName());
        return view('pencegahan.advokasi.monitoring.index_pendataanMonitoring',$this->data);
    }

    public function addpendataanMonitoring(Request $request){
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
        // $baseUrl = URL::to('/');

        $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
        $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $propkab;

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        $this->data['breadcrumps'] = breadcrumps_dir_advokasi($request->route()->getName());
        return view('pencegahan.advokasi.monitoring.add_pendataanMonitoring',$this->data);
    }

    public function editpendataanMonitoring(Request $request){
          $id = $request->id;
          $client = new Client();
          $baseUrl = URL::to($this->urlapi());
          // $baseUrl = URL::to('/');
          $token = $request->session()->get('token');

          $requestDataDetail= $client->request('GET', $baseUrl.'/api/advomonev/'.$id,
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
          $this->data['breadcrumps'] = breadcrumps_dir_advokasi($request->route()->getName());
          return view('pencegahan.advokasi.monitoring.edit_pendataanMonitoring',$this->data);
      }

    public function inputpendataanMonitoring(Request $request){

       $baseUrl = URL::to($this->urlapi());
       // $baseUrl = URL::to('/');
       $token = $request->session()->get('token');

       $client = new Client();

       //generate image base64
       if($request->hasFile('foto1')){
           $filenameWithExt = $request->file('foto1')->getClientOriginalName();
           $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
           $extension = $request->file('foto1')->getClientOriginalExtension();
           $fileNameToStore= $filename.'_'.time().'.'.$extension;
           $path = $request->file('foto1')->storeAs('AdvokasiMonitoring', $fileNameToStore);
           $image = public_path('upload/AdvokasiMonitoring/'.$fileNameToStore);
           $data = file_get_contents($image);
           $image1 = base64_encode($data);
           Storage::delete('AdvokasiMonitoring/'.$fileNameToStore);
       }else{
         $image1 = null;
       }

       if($request->hasFile('foto2')){
           $filenameWithExt = $request->file('foto2')->getClientOriginalName();
           $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
           $extension = $request->file('foto2')->getClientOriginalExtension();
           $fileNameToStore= $filename.'_'.time().'.'.$extension;
           $path = $request->file('foto2')->storeAs('AdvokasiMonitoring', $fileNameToStore);
           $image = public_path('upload/AdvokasiMonitoring/'.$fileNameToStore);
           $data = file_get_contents($image);
           $image2 = base64_encode($data);
           Storage::delete('AdvokasiMonitoring/'.$fileNameToStore);
       }else{
         $image2 = null;
       }

       if($request->hasFile('foto3')){
           $filenameWithExt = $request->file('foto3')->getClientOriginalName();
           $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
           $extension = $request->file('foto3')->getClientOriginalExtension();
           $fileNameToStore= $filename.'_'.time().'.'.$extension;
           $path = $request->file('foto3')->storeAs('AdvokasiMonitoring', $fileNameToStore);
           $image = public_path('upload/AdvokasiMonitoring/'.$fileNameToStore);
           $data = file_get_contents($image);
           $image3 = base64_encode($data);
           Storage::delete('AdvokasiMonitoring/'.$fileNameToStore);
       }else{
         $image3 = null;
       }

       $uraian_singkat_materi = $request->input('uraian_singkat');

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

        $meta_nasum_materi = $request->input('meta_nasum_materi');
        if (count($meta_nasum_materi) > 0 && $meta_nasum_materi[0]['narasumber'] && $meta_nasum_materi[0]['narasumber']) {
           $meta_nasum_materi = json_encode($request->input('meta_nasum_materi'));
        }else{
          $meta_nasum_materi="";
        }

        $meta_instansi = $request->input('group-c');
        if (count($meta_instansi) > 0 && $meta_instansi[0]['list_nama_instansi'] && $meta_instansi[0]['list_jumlah_peserta']) {
           $meta_instansi = json_encode($request->input('group-c'));
        }else{
          $meta_instansi="";
        }

        $requestInput = $client->request('POST', $baseUrl.'/api/advomonev',
               [
                   'headers' =>
                   [
                       'Authorization' => 'Bearer '.$token
                   ],
                   'form_params' => [
                       'idpelaksana' => $request->input('idpelaksana'),
                       'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                       // 'jenis_kegiatan' => $request->input('sasaran'),
                       'kodesasaran' => ($request->input('sasaran') ? $request->input('sasaran') : ''),
                       'jumlah_instansi' => $jumlah_instansi,
                       'meta_instansi' => $meta_instansi,
                       'jumlah_peserta' => $peserta,
                       'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
                       'meta_nasum_materi' => $meta_nasum_materi,
                       'uraian_singkat' => $uraian_singkat_materi,
                       'foto1' => $image1,
                       'foto2' => $image2,
                       'foto3' => $image3,
                       //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
                       'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
                       // 'narasumber' => $request->input('narasumber'),
                       'panitia_monev' => ($request->input('panitia_monev') ? $request->input('panitia_monev') : ''),
                       // 'materi' => $request->input('materi'),
                       'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
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

       $result = json_decode($requestInput->getBody()->getContents(), true);

       $this->form_params = array('idpelaksana' => $request->input('idpelaksana'),
       'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       // 'jenis_kegiatan' => $request->input('sasaran'),
       'kodesasaran' => ($request->input('sasaran') ? $request->input('sasaran') : ''),
       'jumlah_instansi' => $jumlah_instansi,
       'meta_instansi' => $meta_instansi,
       'jumlah_peserta' => $peserta,
       'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
       'meta_nasum_materi' => $meta_nasum_materi,
       'uraian_singkat' => $uraian_singkat_materi,
       'foto1' => $image1,
       'foto2' => $image2,
       'foto3' => $image3,
       //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
       'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
       // 'narasumber' => $request->input('narasumber'),
       'panitia_monev' => ($request->input('panitia_monev') ? $request->input('panitia_monev') : ''),
       // 'materi' => $request->input('materi'),
       'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
       // 'file_upload' => $anggaran,
       // 'created_at' => $anggaran,
       // 'created_by' => $anggaran,
       'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'periode_tahun' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'anggaran_id' => $anggaran);

       $trail['audit_menu'] = 'Pencegahan - Direktorat Advokasi - Kegiatan Monitoring dan Evaluasi';
       $trail['audit_event'] = 'post';
       $trail['audit_value'] = json_encode($this->form_params);
       $trail['audit_url'] = $request->url();
       $trail['audit_ip_address'] = $request->ip();
       $trail['audit_user_agent'] = $request->userAgent();
       $trail['audit_message'] = $result['comment'];
       $trail['created_at'] = date("Y-m-d H:i:s");
       $trail['created_by'] = $request->session()->get('id');

       $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


       $inputId = $result['data']['eventID'];

       if ($request->file('file_upload') != ''){
           $fileName = $inputId.'-'.$request->file('file_upload')->getClientOriginalName();
           $request->file('file_upload')->storeAs('AdvokasiMonev', $fileName);

           $requestfile = $client->request('PUT', $baseUrl.'/api/advomonev/'.$inputId,
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

       $this->kelengkapan_Monitoring($inputId);

       if( ($result['code'] == 200) && ($result['status'] != 'error') ){
         $this->data['status'] = 'success';
         $this->data['message'] = 'Data Kegiatan Monitoring dan Evaluasi berhasil disimpan. ';
       }else{
       $this->data['status'] = 'error';
         $this->data['message'] = 'Data Kegiatan Monitoring dan Evaluasi gagal disimpan. ';
       }


       return redirect('pencegahan/dir_advokasi/pendataan_monitoring/')->with('status',$this->data);

    }

    public function updatependataanMonitoring(Request $request){
          $id = $request->input('id');

          $baseUrl = URL::to($this->urlapi());
          // $baseUrl = URL::to('/');
          $token = $request->session()->get('token');

          $client = new Client();

          //generate image base64
          if($request->hasFile('foto1')){
              $filenameWithExt = $request->file('foto1')->getClientOriginalName();
              $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
              $extension = $request->file('foto1')->getClientOriginalExtension();
              $fileNameToStore= $filename.'_'.time().'.'.$extension;
              $path = $request->file('foto1')->storeAs('AdvokasiMonitoring', $fileNameToStore);
              $image = public_path('upload/AdvokasiMonitoring/'.$fileNameToStore);
              $data = file_get_contents($image);
              $image1 = base64_encode($data);
              Storage::delete('AdvokasiMonitoring/'.$fileNameToStore);
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
              $path = $request->file('foto2')->storeAs('AdvokasiMonitoring', $fileNameToStore);
              $image = public_path('upload/AdvokasiMonitoring/'.$fileNameToStore);
              $data = file_get_contents($image);
              $image2 = base64_encode($data);
              Storage::delete('AdvokasiMonitoring/'.$fileNameToStore);
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
              $path = $request->file('foto3')->storeAs('AdvokasiMonitoring', $fileNameToStore);
              $image = public_path('upload/AdvokasiMonitoring/'.$fileNameToStore);
              $data = file_get_contents($image);
              $image3 = base64_encode($data);
              Storage::delete('AdvokasiMonitoring/'.$fileNameToStore);
  //            $form_foto3 = 'foto3 => '.$image.',';
          }else{
              $image3 = $request->input('foto3_old');
  //            $form_foto3 = '';
          }

          $uraian_singkat_materi = $request->input('uraian_singkat');

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

          $meta_nasum_materi = $request->input('meta_nasum_materi');
          if (count($meta_nasum_materi) > 0 && $meta_nasum_materi[0]['narasumber'] && $meta_nasum_materi[0]['narasumber']) {
             $meta_nasum_materi = json_encode($request->input('meta_nasum_materi'));
          }else{
            $meta_nasum_materi="";
          }

          $meta_instansi = $request->input('group-c');
          if (count($meta_instansi) > 0 && $meta_instansi[0]['list_nama_instansi'] && $meta_instansi[0]['list_jumlah_peserta']) {
             $meta_instansi = json_encode($request->input('group-c'));
          }else{
            $meta_instansi="";
          }

          $requestInput = $client->request('PUT', $baseUrl.'/api/advomonev/'.$id,
                   [
                       'headers' =>
                       [
                           'Authorization' => 'Bearer '.$token
                       ],
                       'form_params' => [
                           'idpelaksana' => $request->input('idpelaksana'),
                           'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                           // 'jenis_kegiatan' => $request->input('sasaran'),
                           'kodesasaran' => ($request->input('sasaran') ? $request->input('sasaran') : ''),
                           'jumlah_instansi' => $jumlah_instansi,
                           'meta_instansi' => $meta_instansi,
                           'jumlah_peserta' => $peserta,
                           'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
                           'meta_nasum_materi' => $meta_nasum_materi,
                           'uraian_singkat' => $uraian_singkat_materi,
                           'foto1' => $image1,
                           'foto2' => $image2,
                           'foto3' => $image3,
                           //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
                           'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
                           // 'narasumber' => $request->input('narasumber'),
                           'panitia_monev' => ($request->input('panitia_monev') ? $request->input('panitia_monev') : ''),
                           // 'materi' => $request->input('materi'),
                           'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
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

           $result = json_decode($requestInput->getBody()->getContents(), true);

           $this->form_params = array('idpelaksana' => $request->input('idpelaksana'),
           'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           // 'jenis_kegiatan' => $request->input('sasaran'),
           'kodesasaran' => ($request->input('sasaran') ? $request->input('sasaran') : ''),
           'jumlah_instansi' => $jumlah_instansi,
           'meta_instansi' => $meta_instansi,
           'jumlah_peserta' => $peserta,
           'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
           'meta_nasum_materi' => $meta_nasum_materi,
           //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
           'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
           // 'narasumber' => $request->input('narasumber'),
           'panitia_monev' => ($request->input('panitia_monev') ? $request->input('panitia_monev') : ''),
           // 'materi' => $request->input('materi'),
           'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
           // 'file_upload' => $anggaran,
           // 'created_at' => $anggaran,
           // 'created_by' => $anggaran,
           'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           'periode_tahun' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           'anggaran_id' => $anggaran);

           $trail['audit_menu'] = 'Pencegahan - Direktorat Advokasi - Kegiatan Monitoring dan Evaluasi';
           $trail['audit_event'] = 'put';
           $trail['audit_value'] = json_encode($this->form_params);
           $trail['audit_url'] = $request->url();
           $trail['audit_ip_address'] = $request->ip();
           $trail['audit_user_agent'] = $request->userAgent();
           $trail['audit_message'] = $result['comment'];
           $trail['created_at'] = date("Y-m-d H:i:s");
           $trail['created_by'] = $request->session()->get('id');

           $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


           if ($request->file('file_upload') != ''){
               $fileName = $id.'-'.$request->file('file_upload')->getClientOriginalName();
               $request->file('file_upload')->storeAs('AdvokasiMonev', $fileName);

               $requestfile = $client->request('PUT', $baseUrl.'/api/advomonev/'.$id,
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

            $this->kelengkapan_Monitoring($id);

            if( ($result['code'] == 200) && ($result['status'] != 'error') ){
            	$this->data['status'] = 'success';
            	$this->data['message'] = 'Data Kegiatan Monitoring dan Evaluasi berhasil diperbaharui. ';
            }else{
     		    $this->data['status'] = 'error';
            	$this->data['message'] = 'Data Kegiatan Monitoring dan Evaluasi gagal diperbaharui. ';
            }


           return back()->with('status',$this->data);
    }

    public function deletePendataanMonitoring(Request $request){
      $id = $request->input('id');
      if($id){
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/advomonev/'.$id,'DELETE');
            $this->form_params['delete_id'] = $id;
            $trail['audit_menu'] = 'Pencegahan - Direktorat Advokasi - Kegiatan Monitoring dan Evaluasi';
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
        $data_request = ['status'=>'error','message'=>'Data Kegiatan Advokasi Monitoring Evaluasi Gagal Dihapus'];
        return $data_request;
      }
    }

    public function printMonitoring(Request $request){
        $client = new Client();
        $page = $request->input('page');
        $token = $request->session()->get('token');
        $baseUrl = URL::to($this->urlapi());
        // $baseUrl = URL::to('/');

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
        // if($page){
        //     $page = $page;
        // }else{
        //     $page = 1;
        // }
        $result = [];
        $url = 'api/advomonev'.$kondisi.'&id_wilayah='.$request->session()->get('wilayah');

        //$no = $start_number;

        $requestPrintData = $client->request('GET', $baseUrl.'/'.$url,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
        $PrintData = json_decode($requestPrintData->getBody()->getContents(), true);
        // dd($pemusnahanladang);
        $DataArray = [];

        $i = $start_number;
        foreach ($PrintData['data'] as $key => $value) {
          $DataArray[$key]['No'] = $i;
          $DataArray[$key]['Tanggal'] = ($value['tgl_pelaksanaan'] ? date('d-m-Y', strtotime($value['tgl_pelaksanaan'])) : '');
          $DataArray[$key]['Pelaksana'] = $value['nm_instansi'];
          $DataArray[$key]['Sasaran'] = $value['kodesasaran'];
          $meta = json_decode($value['meta_instansi'],true);
          if(count($meta)){
            for($j = 0 ; $j < count($meta); $j++){
                $InstansiArray[$key]['Instansi'][$j] = $meta[$j]['list_nama_instansi'].'('.$meta[$j]['list_jumlah_peserta'].')';
            }
            $DataArray[$key]['Instansi/Peserta'] = implode("\n", $InstansiArray[$key]['Instansi']);
          } else {
            $DataArray[$key]['Instansi/Peserta'] = '-';
          }
          $DataArray[$key]['Sumber Anggaran'] = $value['kodesumberanggaran'];
          $i = $i +1;
        }
         //dd($DataArray);
        $data = $DataArray;
        $name = 'Data Kegiatan Advokasi Monitoring Evaluasi '.Carbon::now()->format('Y-m-d H:i:s');
        $this->printData($data, $name);
    }

    private function kelengkapan_Monitoring($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('cegahadvokasi_monev')->where('id',$id)
                  ->select('tgl_pelaksanaan', 'kodesasaran', 'idpelaksana', 'meta_instansi', 'lokasi_kegiatan', 'lokasi_kegiatan_idkabkota', 'kodesumberanggaran', 'file_upload', 'panitia_monev', 'meta_nasum_materi');
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
          $kelengkapan = execute_api_json('api/advomonev/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/advomonev/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

    public function pendataanBimbingan(Request $request){
        $client = new Client();
        if ($request->input('page')) {
          $page = $request->input('page');
        } else {
          $page = 1;
        }

        $baseUrl = URL::to($this->urlapi());
        // $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $kondisi = '';
        if($request->limit) {
          $this->limit = $request->limit;
        } else {
          $this->limit = config('app.limit');
        }

        if($request->isMethod('get')){
          $get = $request->all();
          $get = $request->except(['page']);
          $tipe = $request->tipe;
          if(count($get)>0){
            $this->selected['tipe']  = $tipe;
            foreach ($get as $key => $value) {
              $kondisi .= "&".$key.'='.$value;
              if( ($key == 'tgl_from') || ($key == 'tgl_to') ){
                $this->selected[$key] = date('d/m/Y',strtotime($value));
              }else {
                  $this->selected[$key] = $value;
                  $this->selected['keyword'] = $value;
              }
            }

            $this->selected['order'] = $request->order;
            $this->selected['limit'] = $request->limit;
          }
        } else {
          $post = $request->all();
          $tipe = $request->tipe;
          $kata_kunci = $request->kata_kunci;
          $pelaksana = $request->pelaksana;
          // $namapelaksana = $request->namapelaksana;
          $sasaran = $request->sasaran;
          $anggaran = $request->anggaran;
          $tgl_from = $request->tgl_from;
          $tgl_to = $request->tgl_to;
          $order = $request->order;
          $limit = $request->limit;
          $kelengkapan = $request->kelengkapan;

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
          }else if($tipe == 'pelaksana'){
            $kondisi .= '&pelaksana='.$pelaksana;
            $this->selected['pelaksana'] = $pelaksana;
          }else if($tipe == 'sasaran'){
            $kondisi .= '&sasaran='.$sasaran;
            $this->selected['sasaran'] = $sasaran;
          }else if($tipe == 'anggaran'){
            $kondisi .= '&anggaran='.$anggaran;
            $this->selected['anggaran'] = $anggaran;
          }else if($tipe == 'kelengkapan'){
            $kondisi .= '&kelengkapan='.$kelengkapan;
            $this->selected['kelengkapan'] = $kelengkapan;
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

        $requestAdvobimtek = $client->request('GET', $baseUrl.'/api/advobimtek?id_wilayah='.$request->session()->get('wilayah').'&'.$limit.'&'.$offset.$kondisi,
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
        $this->data['titledel'] = "advobimtek";
        $this->data['title'] = "Kegiatan Bimbingan Teknis";
        $this->data['forprint'] = $limit.'&'.$offset.$kondisi;
        $this->data['delete_route'] = "delete_pendataan_bimbingan";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['token'] = $token;

        $this->data['filter'] = $this->selected;
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['path'] = $request->path();
        $this->data['page'] = $page;
        $this->data['start_number'] = $start_number;
        $total_item = $page['totalpage'] * $this->limit;
        $filtering = false;
	      if($kondisi){
	        $filter = $kondisi;
	        $filtering = true;
	        $this->data['kondisi'] = '?'.$limit.'&'.$offset.$kondisi;
	      }else{
	        $filter = '/';
	        $filtering = false;
	        $this->data['kondisi'] = $current_page;
	      }
        // dd($filter);
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), '/'.$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter);

        $this->data['breadcrumps'] = breadcrumps_dir_advokasi($request->route()->getName());
        return view('pencegahan.advokasi.bimbingan.index_pendataanBimbingan',$this->data);
    }

    public function addpendataanBimbingan(Request $request){
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
        // $baseUrl = URL::to('/');

        $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
        $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $propkab;

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        $this->data['breadcrumps'] = breadcrumps_dir_advokasi($request->route()->getName());
        return view('pencegahan.advokasi.bimbingan.add_pendataanBimbingan',$this->data);
    }

    public function editpendataanBimbingan(Request $request){
        $id = $request->id;
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
        // $baseUrl = URL::to('/');
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

        $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
        $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $propkab;

        $this->data['data_detail'] = $dataDetail;
        $this->data['id'] = $id;
        $this->data['breadcrumps'] = breadcrumps_dir_advokasi($request->route()->getName());
        return view('pencegahan.advokasi.bimbingan.edit_pendataanBimbingan',$this->data);
    }

    public function inputpendataanBimbingan(Request $request){

       $baseUrl = URL::to($this->urlapi());
       // $baseUrl = URL::to('/');
       $token = $request->session()->get('token');

       $client = new Client();

       //generate image base64
       if($request->hasFile('foto1')){
           $filenameWithExt = $request->file('foto1')->getClientOriginalName();
           $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
           $extension = $request->file('foto1')->getClientOriginalExtension();
           $fileNameToStore= $filename.'_'.time().'.'.$extension;
           $path = $request->file('foto1')->storeAs('AdvokasiBimbingan', $fileNameToStore);
           $image = public_path('upload/AdvokasiBimbingan/'.$fileNameToStore);
           $data = file_get_contents($image);
           $image1 = base64_encode($data);
           Storage::delete('AdvokasiBimbingan/'.$fileNameToStore);
       }else{
         $image1 = null;
       }

       if($request->hasFile('foto2')){
           $filenameWithExt = $request->file('foto2')->getClientOriginalName();
           $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
           $extension = $request->file('foto2')->getClientOriginalExtension();
           $fileNameToStore= $filename.'_'.time().'.'.$extension;
           $path = $request->file('foto2')->storeAs('AdvokasiBimbingan', $fileNameToStore);
           $image = public_path('upload/AdvokasiBimbingan/'.$fileNameToStore);
           $data = file_get_contents($image);
           $image2 = base64_encode($data);
           Storage::delete('AdvokasiBimbingan/'.$fileNameToStore);
       }else{
         $image2 = null;
       }

       if($request->hasFile('foto3')){
           $filenameWithExt = $request->file('foto3')->getClientOriginalName();
           $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
           $extension = $request->file('foto3')->getClientOriginalExtension();
           $fileNameToStore= $filename.'_'.time().'.'.$extension;
           $path = $request->file('foto3')->storeAs('AdvokasiBimbingan', $fileNameToStore);
           $image = public_path('upload/AdvokasiBimbingan/'.$fileNameToStore);
           $data = file_get_contents($image);
           $image3 = base64_encode($data);
           Storage::delete('AdvokasiBimbingan/'.$fileNameToStore);
       }else{
         $image3 = null;
       }

       $uraian_singkat_materi = $request->input('uraian_singkat');

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

        $meta_nasum_materi = $request->input('meta_nasum_materi');
        if (count($meta_nasum_materi) > 0 && $meta_nasum_materi[0]['narasumber'] && $meta_nasum_materi[0]['narasumber']) {
           $meta_nasum_materi = json_encode($request->input('meta_nasum_materi'));
        }else{
          $meta_nasum_materi="";
        }

        $meta_instansi = $request->input('group-c');
        if (count($meta_instansi) > 0 && $meta_instansi[0]['list_nama_instansi'] && $meta_instansi[0]['list_jumlah_peserta']) {
           $meta_instansi = json_encode($request->input('group-c'));
        }else{
          $meta_instansi="";
        }

        $requestInput = $client->request('POST', $baseUrl.'/api/advobimtek',
               [
                   'headers' =>
                   [
                       'Authorization' => 'Bearer '.$token
                   ],
                   'form_params' => [
                       'idpelaksana' => $request->input('idpelaksana'),
                       'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                       // 'jenis_kegiatan' => $request->input('sasaran'),
                       'kodesasaran' => ($request->input('sasaran') ? $request->input('sasaran') : ''),
                       'jumlah_instansi' => $jumlah_instansi,
                       'meta_instansi' => $meta_instansi,
                       'jumlah_peserta' => $peserta,
                       'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
                       'meta_nasum_materi' => $meta_nasum_materi,
                       'uraian_singkat' => $uraian_singkat_materi,
                       'foto1' => $image1,
                       'foto2' => $image2,
                       'foto3' => $image3,
                       //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
                       'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
                       // 'narasumber' => $request->input('narasumber'),
                       'panitia_monev' => ($request->input('panitia_monev') ? $request->input('panitia_monev') : ''),
                       // 'materi' => $request->input('materi'),
                       'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
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

       $result = json_decode($requestInput->getBody()->getContents(), true);

       $this->form_params = array('idpelaksana' => $request->input('idpelaksana'),
       'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       // 'jenis_kegiatan' => $request->input('sasaran'),
       'kodesasaran' => ($request->input('sasaran') ? $request->input('sasaran') : ''),
       'jumlah_instansi' => $jumlah_instansi,
       'meta_instansi' => $meta_instansi,
       'jumlah_peserta' => $peserta,
       'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
       'meta_nasum_materi' => $meta_nasum_materi,
       //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
       'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
       // 'narasumber' => $request->input('narasumber'),
       'panitia_monev' => ($request->input('panitia_monev') ? $request->input('panitia_monev') : ''),
       // 'materi' => $request->input('materi'),
       'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
       // 'file_upload' => $anggaran,
       // 'created_at' => $anggaran,
       // 'created_by' => $anggaran,
       'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'periode_tahun' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'anggaran_id' => $anggaran);

       $trail['audit_menu'] = 'Pencegahan - Direktorat Advokasi - Kegiatan Bimbingan Teknis';
       $trail['audit_event'] = 'post';
       $trail['audit_value'] = json_encode($this->form_params);
       $trail['audit_url'] = $request->url();
       $trail['audit_ip_address'] = $request->ip();
       $trail['audit_user_agent'] = $request->userAgent();
       $trail['audit_message'] = $result['comment'];
       $trail['created_at'] = date("Y-m-d H:i:s");
       $trail['created_by'] = $request->session()->get('id');

       $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


       $inputId = $result['data']['eventID'];

       if ($request->file('file_upload') != ''){
           $fileName = $inputId.'-'.$request->file('file_upload')->getClientOriginalName();
           $request->file('file_upload')->storeAs('AdvokasiBimtek', $fileName);

           $requestfile = $client->request('PUT', $baseUrl.'/api/advobimtek/'.$inputId,
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

       $this->kelengkapan_Bimbingan($inputId);

       if( ($result['code'] == 200) && ($result['status'] != 'error') ){
         $this->data['status'] = 'success';
         $this->data['message'] = 'Data Kegiatan Bimbingan Teknis berhasil disimpan. ';
       }else{
       $this->data['status'] = 'error';
         $this->data['message'] = 'Data Kegiatan Bimbingan Teknis gagal disimpan. ';
       }


       return redirect('pencegahan/dir_advokasi/pendataan_bimbingan/')->with('status',$this->data);

    }

    public function updatependataanBimbingan(Request $request){
          $id = $request->input('id');

          $baseUrl = URL::to($this->urlapi());
          // $baseUrl = URL::to('/');
          $token = $request->session()->get('token');

          $client = new Client();

          //generate image base64
          if($request->hasFile('foto1')){
              $filenameWithExt = $request->file('foto1')->getClientOriginalName();
              $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
              $extension = $request->file('foto1')->getClientOriginalExtension();
              $fileNameToStore= $filename.'_'.time().'.'.$extension;
              $path = $request->file('foto1')->storeAs('AdvokasiBimbingan', $fileNameToStore);
              $image = public_path('upload/AdvokasiBimbingan/'.$fileNameToStore);
              $data = file_get_contents($image);
              $image1 = base64_encode($data);
              Storage::delete('AdvokasiBimbingan/'.$fileNameToStore);
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
              $path = $request->file('foto2')->storeAs('AdvokasiBimbingan', $fileNameToStore);
              $image = public_path('upload/AdvokasiBimbingan/'.$fileNameToStore);
              $data = file_get_contents($image);
              $image2 = base64_encode($data);
              Storage::delete('AdvokasiBimbingan/'.$fileNameToStore);
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
              $path = $request->file('foto3')->storeAs('AdvokasiBimbingan', $fileNameToStore);
              $image = public_path('upload/AdvokasiBimbingan/'.$fileNameToStore);
              $data = file_get_contents($image);
              $image3 = base64_encode($data);
              Storage::delete('AdvokasiBimbingan/'.$fileNameToStore);
  //            $form_foto3 = 'foto3 => '.$image.',';
          }else{
              $image3 = $request->input('foto3_old');
  //            $form_foto3 = '';
          }

          $uraian_singkat_materi = $request->input('uraian_singkat');

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

          $meta_nasum_materi = $request->input('meta_nasum_materi');
          if (count($meta_nasum_materi) > 0 && $meta_nasum_materi[0]['narasumber'] && $meta_nasum_materi[0]['narasumber']) {
             $meta_nasum_materi = json_encode($request->input('meta_nasum_materi'));
          }else{
            $meta_nasum_materi="";
          }

          $meta_instansi = $request->input('group-c');
          if (count($meta_instansi) > 0 && $meta_instansi[0]['list_nama_instansi'] && $meta_instansi[0]['list_jumlah_peserta']) {
             $meta_instansi = json_encode($request->input('group-c'));
          }else{
            $meta_instansi="";
          }

          $requestInput = $client->request('PUT', $baseUrl.'/api/advobimtek/'.$id,
                   [
                       'headers' =>
                       [
                           'Authorization' => 'Bearer '.$token
                       ],
                       'form_params' => [
                           'idpelaksana' => $request->input('idpelaksana'),
                           'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                           // 'jenis_kegiatan' => $request->input('sasaran'),
                           'kodesasaran' => ($request->input('sasaran') ? $request->input('sasaran') : ''),
                           'jumlah_instansi' => $jumlah_instansi,
                           'meta_instansi' => $meta_instansi,
                           'jumlah_peserta' => $peserta,
                           'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
                           'meta_nasum_materi' => $meta_nasum_materi,
                           'uraian_singkat' => $uraian_singkat_materi,
                           'foto1' => $image1,
                           'foto2' => $image2,
                           'foto3' => $image3,
                           //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
                           'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
                           // 'narasumber' => $request->input('narasumber'),
                           'panitia_monev' => ($request->input('panitia_monev') ? $request->input('panitia_monev') : ''),
                           // 'materi' => $request->input('materi'),
                           'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
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

           $result = json_decode($requestInput->getBody()->getContents(), true);

           $this->form_params = array('idpelaksana' => $request->input('idpelaksana'),
           'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           // 'jenis_kegiatan' => $request->input('sasaran'),
           'kodesasaran' => ($request->input('sasaran') ? $request->input('sasaran') : ''),
           'jumlah_instansi' => $jumlah_instansi,
           'meta_instansi' => $meta_instansi,
           'jumlah_peserta' => $peserta,
           'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
           'meta_nasum_materi' => $meta_nasum_materi,
           'uraian_singkat' => $uraian_singkat_materi,
           'foto1' => $image1,
           'foto2' => $image2,
           'foto3' => $image3,
           //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
           'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
           // 'narasumber' => $request->input('narasumber'),
           'panitia_monev' => ($request->input('panitia_monev') ? $request->input('panitia_monev') : ''),
           // 'materi' => $request->input('materi'),
           'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
           // 'file_upload' => $anggaran,
           // 'created_at' => $anggaran,
           // 'created_by' => $anggaran,
           'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           'periode_tahun' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           'anggaran_id' => $anggaran);

           $trail['audit_menu'] = 'Pencegahan - Direktorat Advokasi - Kegiatan Bimbingan Teknis';
           $trail['audit_event'] = 'put';
           $trail['audit_value'] = json_encode($this->form_params);
           $trail['audit_url'] = $request->url();
           $trail['audit_ip_address'] = $request->ip();
           $trail['audit_user_agent'] = $request->userAgent();
           $trail['audit_message'] = $result['comment'];
           $trail['created_at'] = date("Y-m-d H:i:s");
           $trail['created_by'] = $request->session()->get('id');

           $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


           if ($request->file('file_upload') != ''){
               $fileName = $id.'-'.$request->file('file_upload')->getClientOriginalName();
               $request->file('file_upload')->storeAs('AdvokasiBimtek', $fileName);

               $requestfile = $client->request('PUT', $baseUrl.'/api/advobimtek/'.$id,
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

            $this->kelengkapan_Bimbingan($id);

            if( ($result['code'] == 200) && ($result['status'] != 'error') ){
            	$this->data['status'] = 'success';
            	$this->data['message'] = 'Data Kegiatan Bimbingan Teknis berhasil diperbaharui. ';
            }else{
     		    $this->data['status'] = 'error';
            	$this->data['message'] = 'Data Kegiatan Bimbingan Teknis gagal diperbaharui. ';
            }


           return back()->with('status',$this->data);
    }

    public function deletePendataanBimbingan(Request $request){
      $id = $request->input('id');
      if($id){
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/advobimtek/'.$id,'DELETE');
            $this->form_params['delete_id'] = $id;
            $trail['audit_menu'] = 'Pencegahan - Direktorat Advokasi - Kegiatan Bimbingan Teknis';
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
        $data_request = ['status'=>'error','message'=>'Data Kegiatan Bimbingan Teknis Gagal Dihapus'];
        return $data_request;
      }
    }

    public function printBimbingan(Request $request){
        $client = new Client();
        $page = $request->input('page');
        $token = $request->session()->get('token');
        $baseUrl = URL::to($this->urlapi());
        // $baseUrl = URL::to('/');

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
        // if($page){
        //     $page = $page;
        // }else{
        //     $page = 1;
        // }
        $result = [];
        $url = 'api/advobimtek'.$kondisi.'&id_wilayah='.$request->session()->get('wilayah');

        //$no = $start_number;

        $requestPrintData = $client->request('GET', $baseUrl.'/'.$url,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
        $PrintData = json_decode($requestPrintData->getBody()->getContents(), true);
        // dd($pemusnahanladang);
        $DataArray = [];

        $i = $start_number;
        foreach ($PrintData['data'] as $key => $value) {
          $DataArray[$key]['No'] = $i;
          $DataArray[$key]['Tanggal'] = ($value['tgl_pelaksanaan'] ? date('d-m-Y', strtotime($value['tgl_pelaksanaan'])) : '');
          $DataArray[$key]['Pelaksana'] = $value['nm_instansi'];
          $DataArray[$key]['Sasaran'] = $value['kodesasaran'];
          $meta = json_decode($value['meta_instansi'],true);
          if(count($meta)){
            for($j = 0 ; $j < count($meta); $j++){
                $InstansiArray[$key]['Instansi'][$j] = $meta[$j]['list_nama_instansi'].'('.$meta[$j]['list_jumlah_peserta'].')';
            }
            $DataArray[$key]['Instansi/Peserta'] = implode("\n", $InstansiArray[$key]['Instansi']);
          } else {
            $DataArray[$key]['Instansi/Peserta'] = '-';
          }
          $DataArray[$key]['Sumber Anggaran'] = $value['kodesumberanggaran'];
          $i = $i +1;
        }
         //dd($DataArray);
        $data = $DataArray;
        $name = 'Data Kegiatan Bimbingan Teknis '.Carbon::now()->format('Y-m-d H:i:s');
        $this->printData($data, $name);
    }

    private function kelengkapan_Bimbingan($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('cegahadvokasi_bimtek')->where('id',$id)
                  ->select('tgl_pelaksanaan', 'kodesasaran', 'idpelaksana', 'meta_instansi', 'lokasi_kegiatan', 'lokasi_kegiatan_idkabkota', 'kodesumberanggaran', 'file_upload', 'panitia_monev', 'meta_nasum_materi');
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
          $kelengkapan = execute_api_json('api/advobimtek/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/advobimtek/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

    public function pendataanSosialisasi(Request $request){
        $client = new Client();
        if ($request->input('page')) {
          $page = $request->input('page');
        } else {
          $page = 1;
        }

        $baseUrl = URL::to($this->urlapi());
        // $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $kondisi = '';
        if($request->limit) {
          $this->limit = $request->limit;
        } else {
          $this->limit = config('app.limit');
        }

        if($request->isMethod('get')){
          $get = $request->all();
          $get = $request->except(['page']);
          $tipe = $request->tipe;
          if(count($get)>0){
            $this->selected['tipe']  = $tipe;
            foreach ($get as $key => $value) {
              $kondisi .= "&".$key.'='.$value;
              if( ($key == 'tgl_from') || ($key == 'tgl_to') ){
                $this->selected[$key] = date('d/m/Y',strtotime($value));
              }else {
                  $this->selected[$key] = $value;
                  $this->selected['keyword'] = $value;
              }
            }

            $this->selected['order'] = $request->order;
            $this->selected['limit'] = $request->limit;
          }
        } else {
          $post = $request->all();
          $tipe = $request->tipe;
          $kata_kunci = $request->kata_kunci;
          $pelaksana = $request->pelaksana;
          // $namapelaksana = $request->namapelaksana;
          $sasaran = $request->sasaran;
          $anggaran = $request->anggaran;
          $tgl_from = $request->tgl_from;
          $tgl_to = $request->tgl_to;
          $order = $request->order;
          $limit = $request->limit;
          $kelengkapan = $request->kelengkapan;

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
          }else if($tipe == 'pelaksana'){
            $kondisi .= '&pelaksana='.$pelaksana;
            $this->selected['pelaksana'] = $pelaksana;
          }else if($tipe == 'sasaran'){
            $kondisi .= '&sasaran='.$sasaran;
            $this->selected['sasaran'] = $sasaran;
          }else if($tipe == 'anggaran'){
            $kondisi .= '&anggaran='.$anggaran;
            $this->selected['anggaran'] = $anggaran;
          }else if($tipe == 'kelengkapan'){
            $kondisi .= '&kelengkapan='.$kelengkapan;
            $this->selected['kelengkapan'] = $kelengkapan;
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

        $requestAdvososialisasi = $client->request('GET', $baseUrl.'/api/disemsosialisasi?id_wilayah='.$request->session()->get('wilayah').'&'.$limit.'&'.$offset.$kondisi,
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
        $this->data['titledel'] = "disemsosialisasi";
        $this->data['title'] = "Kegiatan KIE";
        $this->data['forprint'] = $limit.'&'.$offset.$kondisi;
        $this->data['delete_route'] = "delete_pendataan_sosialisasi";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['token'] = $token;

        $this->data['filter'] = $this->selected;
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['path'] = $request->path();
        $this->data['page'] = $page;
        $this->data['start_number'] = $start_number;
        $total_item = $page['totalpage'] * $this->limit;
        $filtering = false;
	      if($kondisi){
	        $filter = $kondisi;
	        $filtering = true;
	        $this->data['kondisi'] = '?'.$limit.'&'.$offset.$kondisi;
	      }else{
	        $filter = '/';
	        $filtering = false;
	        $this->data['kondisi'] = $current_page;
	      }
        // dd($filter);
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), '/'.$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter);

        $this->data['breadcrumps'] = breadcrumps_dir_advokasi($request->route()->getName());
        return view('pencegahan.advokasi.sosialisasi.index_pendataanSosialisasi',$this->data);
    }

    public function addpendataanSosialisasi(Request $request){
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
        // $baseUrl = URL::to('/');

        $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
        $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $propkab;

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        $this->data['breadcrumps'] = breadcrumps_dir_advokasi($request->route()->getName());
        return view('pencegahan.advokasi.sosialisasi.add_pendataanSosialisasi',$this->data);
    }

    public function editpendataanSosialisasi(Request $request){
        $id = $request->id;
        $client = new Client();
        $baseUrl = URL::to($this->urlapi());
        // $baseUrl = URL::to('/');
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

        $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
        $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $propkab;

        $this->data['data_detail'] = $dataDetail;
        $this->data['id'] = $id;
        $this->data['breadcrumps'] = breadcrumps_dir_advokasi($request->route()->getName());
        return view('pencegahan.advokasi.sosialisasi.edit_pendataanSosialisasi',$this->data);
    }

    public function inputpendataanSosialisasi(Request $request){

       $baseUrl = URL::to($this->urlapi());
       // $baseUrl = URL::to('/');
       $token = $request->session()->get('token');

       $client = new Client();

       //generate image base64
       if($request->hasFile('foto1')){
           $filenameWithExt = $request->file('foto1')->getClientOriginalName();
           $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
           $extension = $request->file('foto1')->getClientOriginalExtension();
           $fileNameToStore= $filename.'_'.time().'.'.$extension;
           $path = $request->file('foto1')->storeAs('AdvokasiSosialisasi', $fileNameToStore);
           $image = public_path('upload/AdvokasiSosialisasi/'.$fileNameToStore);
           $data = file_get_contents($image);
           $image1 = base64_encode($data);
           Storage::delete('AdvokasiSosialisasi/'.$fileNameToStore);
       }else{
         $image1 = null;
       }

       if($request->hasFile('foto2')){
           $filenameWithExt = $request->file('foto2')->getClientOriginalName();
           $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
           $extension = $request->file('foto2')->getClientOriginalExtension();
           $fileNameToStore= $filename.'_'.time().'.'.$extension;
           $path = $request->file('foto2')->storeAs('AdvokasiSosialisasi', $fileNameToStore);
           $image = public_path('upload/AdvokasiSosialisasi/'.$fileNameToStore);
           $data = file_get_contents($image);
           $image2 = base64_encode($data);
           Storage::delete('AdvokasiSosialisasi/'.$fileNameToStore);
       }else{
         $image2 = null;
       }

       if($request->hasFile('foto3')){
           $filenameWithExt = $request->file('foto3')->getClientOriginalName();
           $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
           $extension = $request->file('foto3')->getClientOriginalExtension();
           $fileNameToStore= $filename.'_'.time().'.'.$extension;
           $path = $request->file('foto3')->storeAs('AdvokasiSosialisasi', $fileNameToStore);
           $image = public_path('upload/AdvokasiSosialisasi/'.$fileNameToStore);
           $data = file_get_contents($image);
           $image3 = base64_encode($data);
           Storage::delete('AdvokasiSosialisasi/'.$fileNameToStore);
       }else{
         $image3 = null;
       }

       // $peserta = $request->input('jumlah_peserta');
       $uraian_singkat_materi = $request->input('uraian_singkat');

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

        $meta_nasum_materi = $request->input('meta_nasum_materi');
        if (count($meta_nasum_materi) > 0 && $meta_nasum_materi[0]['narasumber'] && $meta_nasum_materi[0]['narasumber']) {
           $meta_nasum_materi = json_encode($request->input('meta_nasum_materi'));
        }else{
          $meta_nasum_materi="";
        }

        $meta_instansi = $request->input('group-c');
        if (count($meta_instansi) > 0 && $meta_instansi[0]['list_nama_instansi'] && $meta_instansi[0]['list_jumlah_peserta']) {
           $meta_instansi = json_encode($request->input('group-c'));
        }else{
          $meta_instansi="";
        }

        $requestInput = $client->request('POST', $baseUrl.'/api/disemsosialisasi',
               [
                   'headers' =>
                   [
                       'Authorization' => 'Bearer '.$token
                   ],
                   'form_params' => [
                       'idpelaksana' => $request->input('idpelaksana'),
                       'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                       // 'jenis_kegiatan' => $request->input('sasaran'),
                       'kodesasaran' => ($request->input('sasaran') ? $request->input('sasaran') : ''),
                       'jumlah_instansi' => $jumlah_instansi,
                       'meta_instansi' => $meta_instansi,
                       'jumlah_peserta' => $peserta,
                       'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
                       'meta_nasum_materi' => $meta_nasum_materi,
                       'uraian_singkat' => $uraian_singkat_materi,
                       'foto1' => $image1,
                       'foto2' => $image2,
                       'foto3' => $image3,
                       //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
                       'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
                       // 'narasumber' => $request->input('narasumber'),
                       'panitia_monev' => ($request->input('panitia_monev') ? $request->input('panitia_monev') : ''),
                       // 'materi' => $request->input('materi'),
                       'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
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

       $result = json_decode($requestInput->getBody()->getContents(), true);

       $this->form_params = array('idpelaksana' => $request->input('idpelaksana'),
       'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       // 'jenis_kegiatan' => $request->input('sasaran'),
       'kodesasaran' => ($request->input('sasaran') ? $request->input('sasaran') : ''),
       'jumlah_instansi' => $jumlah_instansi,
       'meta_instansi' => $meta_instansi,
       'jumlah_peserta' => $peserta,
       'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
       'meta_nasum_materi' => $meta_nasum_materi,
       'uraian_singkat' => $uraian_singkat_materi,
       'foto1' => $image1,
       'foto2' => $image2,
       'foto3' => $image3,
       //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
       'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
       // 'narasumber' => $request->input('narasumber'),
       'panitia_monev' => ($request->input('panitia_monev') ? $request->input('panitia_monev') : ''),
       // 'materi' => $request->input('materi'),
       'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
       // 'file_upload' => $anggaran,
       // 'created_at' => $anggaran,
       // 'created_by' => $anggaran,
       'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'periode_tahun' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
       'anggaran_id' => $anggaran);

       $trail['audit_menu'] = 'Pencegahan - Direktorat Advokasi - Kegiatan KIE';
       $trail['audit_event'] = 'post';
       $trail['audit_value'] = json_encode($this->form_params);
       $trail['audit_url'] = $request->url();
       $trail['audit_ip_address'] = $request->ip();
       $trail['audit_user_agent'] = $request->userAgent();
       $trail['audit_message'] = $result['comment'];
       $trail['created_at'] = date("Y-m-d H:i:s");
       $trail['created_by'] = $request->session()->get('id');

       $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


       $inputId = $result['data']['eventID'];

       if ($request->file('file_upload') != ''){
           $fileName = $inputId.'-'.$request->file('file_upload')->getClientOriginalName();
           $request->file('file_upload')->storeAs('AdvokasiSosialisasi', $fileName);

           $requestfile = $client->request('PUT', $baseUrl.'/api/disemsosialisasi/'.$inputId,
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

       $this->kelengkapan_Sosialisasi($inputId);

       if( ($result['code'] == 200) && ($result['status'] != 'error') ){
         $this->data['status'] = 'success';
         $this->data['message'] = 'Data Kegiatan KIE berhasil disimpan. ';
       }else{
       $this->data['status'] = 'error';
         $this->data['message'] = 'Data Kegiatan KIE gagal disimpan. ';
       }


       return redirect('pencegahan/dir_advokasi/pendataan_sosialisasi/')->with('status',$this->data);

    }

    public function updatependataanSosialisasi(Request $request){
          $id = $request->input('id');

          $baseUrl = URL::to($this->urlapi());
          // $baseUrl = URL::to('/');
          $token = $request->session()->get('token');

          $client = new Client();

          //generate image base64
          if($request->hasFile('foto1')){
              $filenameWithExt = $request->file('foto1')->getClientOriginalName();
              $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
              $extension = $request->file('foto1')->getClientOriginalExtension();
              $fileNameToStore= $filename.'_'.time().'.'.$extension;
              $path = $request->file('foto1')->storeAs('AdvokasiSosialisasi', $fileNameToStore);
              $image = public_path('upload/AdvokasiSosialisasi/'.$fileNameToStore);
              $data = file_get_contents($image);
              $image1 = base64_encode($data);
              Storage::delete('AdvokasiSosialisasi/'.$fileNameToStore);
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
              $path = $request->file('foto2')->storeAs('AdvokasiSosialisasi', $fileNameToStore);
              $image = public_path('upload/AdvokasiSosialisasi/'.$fileNameToStore);
              $data = file_get_contents($image);
              $image2 = base64_encode($data);
              Storage::delete('AdvokasiSosialisasi/'.$fileNameToStore);
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
              $path = $request->file('foto3')->storeAs('AdvokasiSosialisasi', $fileNameToStore);
              $image = public_path('upload/AdvokasiSosialisasi/'.$fileNameToStore);
              $data = file_get_contents($image);
              $image3 = base64_encode($data);
              Storage::delete('AdvokasiSosialisasi/'.$fileNameToStore);
  //            $form_foto3 = 'foto3 => '.$image.',';
          }else{
              $image3 = $request->input('foto3_old');
  //            $form_foto3 = '';
          }

          $uraian_singkat_materi = $request->input('uraian_singkat');

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

          $meta_nasum_materi = $request->input('meta_nasum_materi');
          if (count($meta_nasum_materi) > 0 && $meta_nasum_materi[0]['narasumber'] && $meta_nasum_materi[0]['narasumber']) {
             $meta_nasum_materi = json_encode($request->input('meta_nasum_materi'));
          }else{
            $meta_nasum_materi="";
          }

          $meta_instansi = $request->input('group-c');
          if (count($meta_instansi) > 0 && $meta_instansi[0]['list_nama_instansi'] && $meta_instansi[0]['list_jumlah_peserta']) {
             $meta_instansi = json_encode($request->input('group-c'));
          }else{
            $meta_instansi="";
          }

          $requestInput = $client->request('PUT', $baseUrl.'/api/disemsosialisasi/'.$id,
                   [
                       'headers' =>
                       [
                           'Authorization' => 'Bearer '.$token
                       ],
                       'form_params' => [
                           'idpelaksana' => $request->input('idpelaksana'),
                           'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                           // 'jenis_kegiatan' => $request->input('sasaran'),
                           'kodesasaran' => ($request->input('sasaran') ? $request->input('sasaran') : ''),
                           'jumlah_instansi' => $jumlah_instansi,
                           'meta_instansi' => $meta_instansi,
                           'jumlah_peserta' => $peserta,
                           'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
                           'meta_nasum_materi' => $meta_nasum_materi,
                           'uraian_singkat' => $uraian_singkat_materi,
                           'foto1' => $image1,
                           'foto2' => $image2,
                           'foto3' => $image3,
                           //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
                           'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
                           // 'narasumber' => $request->input('narasumber'),
                           'panitia_monev' => ($request->input('panitia_monev') ? $request->input('panitia_monev') : ''),
                           // 'materi' => $request->input('materi'),
                           'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
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

           $result = json_decode($requestInput->getBody()->getContents(), true);

           $this->form_params = array('idpelaksana' => $request->input('idpelaksana'),
           'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           // 'jenis_kegiatan' => $request->input('sasaran'),
           'kodesasaran' => ($request->input('sasaran') ? $request->input('sasaran') : ''),
           'jumlah_instansi' => $jumlah_instansi,
           'meta_instansi' => $meta_instansi,
           'jumlah_peserta' => $peserta,
           'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
           'meta_nasum_materi' => $meta_nasum_materi,
           'uraian_singkat' => $uraian_singkat_materi,
           'foto1' => $image1,
           'foto2' => $image2,
           'foto3' => $image3,
           //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
           'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
           // 'narasumber' => $request->input('narasumber'),
           'panitia_monev' => ($request->input('panitia_monev') ? $request->input('panitia_monev') : ''),
           // 'materi' => $request->input('materi'),
           'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
           // 'file_upload' => $anggaran,
           // 'created_at' => $anggaran,
           // 'created_by' => $anggaran,
           'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           'periode_tahun' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
           'anggaran_id' => $anggaran);

           $trail['audit_menu'] = 'Pencegahan - Direktorat Advokasi - Kegiatan KIE';
           $trail['audit_event'] = 'put';
           $trail['audit_value'] = json_encode($this->form_params);
           $trail['audit_url'] = $request->url();
           $trail['audit_ip_address'] = $request->ip();
           $trail['audit_user_agent'] = $request->userAgent();
           $trail['audit_message'] = $result['comment'];
           $trail['created_at'] = date("Y-m-d H:i:s");
           $trail['created_by'] = $request->session()->get('id');

           $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


           if ($request->file('file_upload') != ''){
               $fileName = $id.'-'.$request->file('file_upload')->getClientOriginalName();
               $request->file('file_upload')->storeAs('AdvokasiSosialisasi', $fileName);

               $requestfile = $client->request('PUT', $baseUrl.'/api/disemsosialisasi/'.$id,
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

            $this->kelengkapan_Sosialisasi($id);

            if( ($result['code'] == 200) && ($result['status'] != 'error') ){
            	$this->data['status'] = 'success';
            	$this->data['message'] = 'Data Kegiatan KIE berhasil diperbaharui. ';
            }else{
     		    $this->data['status'] = 'error';
            	$this->data['message'] = 'Data Kegiatan KIE gagal diperbaharui. ';
            }


           return back()->with('status',$this->data);
    }

    public function deletePendataanSosialisasi(Request $request){
      $id = $request->input('id');
      if($id){
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/disemsosialisasi/'.$id,'DELETE');
            $this->form_params['delete_id'] = $id;
            $trail['audit_menu'] = 'Pencegahan - Direktorat Advokasi - Kegiatan KIE';
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
        $data_request = ['status'=>'error','message'=>'Data Kegiatan KIE Dihapus'];
        return $data_request;
      }
    }

    public function printSosialisasi(Request $request){
        $client = new Client();
        $page = $request->input('page');
        $token = $request->session()->get('token');
        $baseUrl = URL::to($this->urlapi());
        // $baseUrl = URL::to('/');

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
        // if($page){
        //     $page = $page;
        // }else{
        //     $page = 1;
        // }
        $result = [];
        $url = 'api/disemsosialisasi'.$kondisi.'&id_wilayah='.$request->session()->get('wilayah');

        //$no = $start_number;

        $requestPrintData = $client->request('GET', $baseUrl.'/'.$url,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
        $PrintData = json_decode($requestPrintData->getBody()->getContents(), true);
        // dd($pemusnahanladang);
        $DataArray = [];

        $i = $start_number;
        foreach ($PrintData['data'] as $key => $value) {
          $DataArray[$key]['No'] = $i;
          $DataArray[$key]['Tanggal'] = ($value['tgl_pelaksanaan'] ? date('d-m-Y', strtotime($value['tgl_pelaksanaan'])) : '');
          $DataArray[$key]['Pelaksana'] = $value['nm_instansi'];
          $DataArray[$key]['Sasaran'] = $value['kodesasaran'];
          $meta = json_decode($value['meta_instansi'],true);
          if(count($meta)){
            for($j = 0 ; $j < count($meta); $j++){
                $InstansiArray[$key]['Instansi'][$j] = $meta[$j]['list_nama_instansi'].'('.$meta[$j]['list_jumlah_peserta'].')';
            }
            $DataArray[$key]['Instansi/Peserta'] = implode("\n", $InstansiArray[$key]['Instansi']);
          } else {
            $DataArray[$key]['Instansi/Peserta'] = '-';
          }
          $DataArray[$key]['Sumber Anggaran'] = $value['kodesumberanggaran'];
          $i = $i +1;
        }
         //dd($DataArray);
        $data = $DataArray;
        $name = 'Data Kegiatan Sosialisasi '.Carbon::now()->format('Y-m-d H:i:s');
        $this->printData($data, $name);
    }

    private function kelengkapan_Sosialisasi($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('cegahdiseminfo_sosialisasi')->where('id',$id)
                  ->select('tgl_pelaksanaan', 'kodesasaran', 'idpelaksana', 'meta_instansi', 'lokasi_kegiatan', 'lokasi_kegiatan_idkabkota', 'kodesumberanggaran', 'file_upload', 'panitia_monev', 'meta_nasum_materi');
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
          $kelengkapan = execute_api_json('api/disemsosialisasi/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/disemsosialisasi/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

}

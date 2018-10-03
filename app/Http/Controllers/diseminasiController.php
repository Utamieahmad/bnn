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
use Illuminate\Support\Facades\Storage;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class diseminasiController extends Controller
{
  public $data;
  public $selected;
  public $form_params;
  public function pendataanOnline(Request $request)
  {
      $client = new Client();
      if ($request->input('page')) {
        $page = $request->input('page');
      } else {
        $page = 1;
      }

      $baseUrl = URL::to('/');
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
            if( ($key == 'waktu_from') || ($key == 'waktu_to') ){
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
        $anggaran = $request->anggaran;
        $waktu_from = $request->waktu_from;
        $waktu_to = $request->waktu_to;
        $order = $request->order;
        $limit = $request->limit;
        $kelengkapan = $request->kelengkapan;

        if($tipe == 'periode'){
          if($waktu_from){
            $date = date('Y-m-d',strtotime(str_replace('/', '-', $waktu_from)));
            $kondisi .= '&waktu_from='.$date;
            $this->selected['waktu_from'] = $waktu_from;
          }else{
              $kondisi .='';
          }
          if($waktu_to){
            $date = date('Y-m-d',strtotime(str_replace('/', '-', $waktu_to)));
            $kondisi .= '&waktu_to='.$date;
            $this->selected['waktu_to'] = $waktu_to;
          }else{
            $kondisi .='';
          }
        }else if($tipe == 'pelaksana'){
          $kondisi .= '&pelaksana='.$pelaksana;
          $this->selected['pelaksana'] = $pelaksana;
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

      $requestDisemonline = $client->request('GET', $baseUrl.'/api/disemonline?id_wilayah='.$request->session()->get('wilayah').'&'.$limit.'&'.$offset.$kondisi,
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
    $this->data['titledel'] = "disemonline";
    $this->data['title'] = "Kegiatan Media Online";
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

    $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), '/'.$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter);
    $this->data['breadcrumps'] = breadcrumps_dir_diseminasi($request->route()->getName());
    return view('pencegahan.diseminasi.online.index_pendataanOnline',$this->data);
  }

  public function editpendataanOnline(Request $request)
  {
    $id = $request->id;
    $client = new Client();
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');

    $requestDataDetail= $client->request('GET', $baseUrl.'/api/disemonline/'.$id,
      [
      'headers' =>
        [
        'Authorization' => 'Bearer '.$token
        ]
      ]
    );

    $result = json_decode($requestDataDetail->getBody()->getContents(), true);

    if ($result['data']['anggaran_id'] != '') {
      $this->data['data_anggaran'] = $this->globalGetAnggaran($token, $result['data']['anggaran_id']);
    }else{
      $this->data['data_anggaran'] = [];
    }
    $media = $client->request('GET', $baseUrl.'/api/getmedia/mediaonline');
    $this->data['media'] = json_decode($media->getBody()->getContents(), true);
    $querymeta = DB::table('tr_media')->where('value_media', $result['data']['jenis_media'])->get();
    $idmeta = json_decode($querymeta, true);
    $metamedia = $client->request('GET', $baseUrl.'/api/getmedia/mediasosial/'.$idmeta[0]['id']);
    $this->data['metamedia'] = json_decode($metamedia->getBody()->getContents(), true);
    $this->data['nama_media'] = $idmeta[0]['nama_media'];
    $this->data['pendataan'] = $result['data'];
    $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
    $this->data['breadcrumps'] = breadcrumps_dir_diseminasi($request->route()->getName());
    return view('pencegahan.diseminasi.online.edit_pendataanOnline',$this->data);
  }

  public function addpendataanOnline(Request $request)
  {
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');
    $client = new Client();

    $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
    $user_id = Auth::user()->user_id;
    $media = $client->request('GET', $baseUrl.'/api/getmedia/mediaonline');
    $this->data['media'] = json_decode($media->getBody()->getContents(), true);
    $detail = MainModel::getUserDetail($user_id);
    $data['data_detail'] = $detail;
    $this->data['breadcrumps'] = breadcrumps_dir_diseminasi($request->route()->getName());
    return view('pencegahan.diseminasi.online.add_pendataanOnline',$this->data);
  }

  public function inputpendataanOnline(Request $request)
  {
    $baseUrl = URL::to('/');
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

     $meta_media = $request->input('meta_media');
     if (count($meta_media) > 0 ) {
        $meta_media = json_encode($request->input('meta_media'));
     }else{
       $meta_media="";
     }

    $requestData = $client->request('POST', $baseUrl.'/api/disemonline',
      [
        'headers' =>
        [
          'Authorization' => 'Bearer '.$token
        ],
        'form_params' =>
        [
          "idpelaksana" => $request->input('idpelaksana'),
          "dasar_kegiatan" => $request->input('dasar_kegiatan'),
          "no_sprint" => ($request->input('no_sprint') ? $request->input('no_sprint') : ''),
          "no_spk" => ($request->input('no_spk') ? $request->input('no_spk') : ''),
          "tgl_pelaksanaan" => ($request->input('tgl_pelaksanaan') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))) : ''),
          "materi" => ($request->input('materi') ? $request->input('materi') : ''),
          "kodesumberanggaran" => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
          'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'periode_tahun' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          "meta_media" => $meta_media,
          "jumlah_yang_melihat" => ($request->input('jumlah_yang_melihat') ? $request->input('jumlah_yang_melihat') : ''),
          "orang_yang_tertarik" => ($request->input('orang_yang_tertarik') ? $request->input('orang_yang_tertarik') : ''),
          "laporan" => ($request->input('laporan') ? $request->input('laporan') : ''),
          "waktu_publish" => ($request->input('waktu_publish') ? date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $request->input('waktu_publish')))) : ''),
          "selesai_publish" => ($request->input('selesai_publish') ? date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $request->input('selesai_publish')))) : ''),
          "jenis_media" => ($request->input('jenis_media') ? $request->input('jenis_media') : ''),
          'anggaran_id' => $anggaran,
        ]
      ]
    );

    $result = json_decode($requestData->getBody()->getContents(), true);
    // dd($result);

    $this->form_params = array("idpelaksana" => $request->input('idpelaksana'),
    "dasar_kegiatan" => $request->input('dasar_kegiatan'),
    "no_sprint" => ($request->input('no_sprint') ? $request->input('no_sprint') : ''),
    "no_spk" => ($request->input('no_spk') ? $request->input('no_spk') : ''),
    "tgl_pelaksanaan" => ($request->input('tgl_pelaksanaan') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))) : ''),
    "materi" => ($request->input('materi') ? $request->input('materi') : ''),
    "kodesumberanggaran" => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
    'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
    'periode_tahun' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
    'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
    'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
    "meta_media" => $meta_media,
    "jumlah_yang_melihat" => ($request->input('jumlah_yang_melihat') ? $request->input('jumlah_yang_melihat') : ''),
    "orang_yang_tertarik" => ($request->input('orang_yang_tertarik') ? $request->input('orang_yang_tertarik') : ''),
    "laporan" => ($request->input('laporan') ? $request->input('laporan') : ''),
    "waktu_publish" => ($request->input('waktu_publish') ? date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $request->input('waktu_publish')))) : ''),
    "selesai_publish" => ($request->input('selesai_publish') ? date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $request->input('selesai_publish')))) : ''),
    "jenis_media" => ($request->input('jenis_media') ? $request->input('jenis_media') : ''),
    'anggaran_id' => $anggaran);

    $trail['audit_menu'] = 'Pencegahan - Direktorat Diseminasi Informasi - Media Online';
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

    // $fileName = $inputId.'-'.$request->file('file_upload')->getClientOriginalName();
    // $request->file('file_upload')->storeAs('DiseminfoMediaonline', $fileName);

    if ($request->file('file_upload') != ''){
        $fileName = $inputId.'-'.$request->file('file_upload')->getClientOriginalName();
        $request->file('file_upload')->storeAs('DiseminfoMediaonline', $fileName);

        $requestfile = $client->request('PUT', $baseUrl.'/api/disemonline/'.$inputId,
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

     $this->kelengkapan_Online($inputId);

     if( ($result['code'] == 200) && ($result['status'] != 'error') ){
      $this->data['status'] = 'success';
      $this->data['message'] = 'Pendataan Media Online berhasil disimpan. ';
     }else{
      $this->data['status'] = 'error';
      $this->data['message'] = 'Pendataan Media Online gagal disimpan. ';
     } //->with('status',$this->data)

    return redirect('pencegahan/dir_diseminasi/pendataan_online')->with('status',$this->data);
  }

  public function updatependataanOnline(Request $request)
  {
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');
    // dd($request->all());
    $client = new Client();
    $id = $request->input('id');

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

     $meta_media = $request->input('meta_media');
     if (count($meta_media) > 0 ) {
        $meta_media = json_encode($request->input('meta_media'));
     }else{
       $meta_media="";
     }

    $requestData = $client->request('PUT', $baseUrl.'/api/disemonline/'.$id,
      [
        'headers' =>
        [
          'Authorization' => 'Bearer '.$token
        ],
        'form_params' =>
        [
          "idpelaksana" => $request->input('idpelaksana'),
          "dasar_kegiatan" => $request->input('dasar_kegiatan'),
          "no_sprint" => ($request->input('no_sprint') ? $request->input('no_sprint') : ''),
          "no_spk" => ($request->input('no_spk') ? $request->input('no_spk') : ''),
          "tgl_pelaksanaan" => ($request->input('tgl_pelaksanaan') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))) : ''),
          "materi" => ($request->input('materi') ? $request->input('materi') : ''),
          "kodesumberanggaran" => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
          'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'periode_tahun' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          "meta_media" => $meta_media,
          "jumlah_yang_melihat" => ($request->input('jumlah_yang_melihat') ? $request->input('jumlah_yang_melihat') : ''),
          "orang_yang_tertarik" => ($request->input('orang_yang_tertarik') ? $request->input('orang_yang_tertarik') : ''),
          "laporan" => ($request->input('laporan') ? $request->input('laporan') : ''),
          "waktu_publish" => ($request->input('waktu_publish') ? date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $request->input('waktu_publish')))) : ''),
          "selesai_publish" => ($request->input('selesai_publish') ? date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $request->input('selesai_publish')))) : ''),
          "jenis_media" => ($request->input('jenis_media') ? $request->input('jenis_media') : ''),
          'anggaran_id' => $anggaran,
        ]
      ]
    );

    $result = json_decode($requestData->getBody()->getContents(), true);

    $this->form_params = array("idpelaksana" => $request->input('idpelaksana'),
    "dasar_kegiatan" => $request->input('dasar_kegiatan'),
    "no_sprint" => ($request->input('no_sprint') ? $request->input('no_sprint') : ''),
    "no_spk" => ($request->input('no_spk') ? $request->input('no_spk') : ''),
    "tgl_pelaksanaan" => ($request->input('tgl_pelaksanaan') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))) : ''),
    "materi" => ($request->input('materi') ? $request->input('materi') : ''),
    "kodesumberanggaran" => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
    'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
    'periode_tahun' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
    'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
    'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
    "meta_media" => $meta_media,
    "jumlah_yang_melihat" => ($request->input('jumlah_yang_melihat') ? $request->input('jumlah_yang_melihat') : ''),
    "orang_yang_tertarik" => ($request->input('orang_yang_tertarik') ? $request->input('orang_yang_tertarik') : ''),
    "laporan" => ($request->input('laporan') ? $request->input('laporan') : ''),
    "waktu_publish" => ($request->input('waktu_publish') ? date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $request->input('waktu_publish')))) : ''),
    "selesai_publish" => ($request->input('selesai_publish') ? date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $request->input('selesai_publish')))) : ''),
    "jenis_media" => ($request->input('jenis_media') ? $request->input('jenis_media') : ''),
    'anggaran_id' => $anggaran);

    $trail['audit_menu'] = 'Pencegahan - Direktorat Diseminasi Informasi - Media Online';
    $trail['audit_event'] = 'put';
    $trail['audit_value'] = json_encode($this->form_params);
    $trail['audit_url'] = $request->url();
    $trail['audit_ip_address'] = $request->ip();
    $trail['audit_user_agent'] = $request->userAgent();
    $trail['audit_message'] = $result['comment'];
    $trail['created_at'] = date("Y-m-d H:i:s");
    $trail['created_by'] = $request->session()->get('id');

    $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

    $fileMessage = "";
    if ($request->file('file_upload') != ''){
        $fileName = date('Y-m-d').'-'.$id.'-'.$request->file('file_upload')->getClientOriginalName();
        $request->file('file_upload')->storeAs('DiseminfoMediaonline', $fileName);

        $requestfile = $client->request('PUT', $baseUrl.'/api/disemonline/'.$id,
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
        if( ($resultFile['code'] == 200)  && ($resultFile['code'] != 'error')){
          $fileMessage = "";
        }else{
          $fileMessage = "Dengan File Gagal Diupload";
        }
     }else{
      $fileMessage = "";
     }
      $this->kelengkapan_Online($id);
     if( ($result['code'] == 200) && ($result['code'] != 'error') ){
      $this->data['status'] = 'success';
      $this->data['message'] = 'Pendataan Media Online Berhasil Diperbarui ';
     }else{
      $this->data['status'] = 'error';
      $this->data['message'] = 'Pendataan Media Online Gagal Diperbarui';
     }
     return back()->with('status',$this->data);
  }

  public function pendataanPenyiaran(Request $request)
  {
    $client = new Client();
    if ($request->input('page')) {
      $page = $request->input('page');
    } else {
      $page = 1;
    }

    $baseUrl = URL::to('/');
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
          if( ($key == 'waktu_from') || ($key == 'waktu_to') ){
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
      $anggaran = $request->anggaran;
      $waktu_from = $request->waktu_from;
      $waktu_to = $request->waktu_to;
      $order = $request->order;
      $limit = $request->limit;
      $kelengkapan = $request->kelengkapan;

      if($tipe == 'periode'){
        if($waktu_from){
          $date = date('Y-m-d',strtotime(str_replace('/', '-', $waktu_from)));
          $kondisi .= '&waktu_from='.$date;
          $this->selected['waktu_from'] = $waktu_from;
        }else{
            $kondisi .='';
        }
        if($waktu_to){
          $date = date('Y-m-d',strtotime(str_replace('/', '-', $waktu_to)));
          $kondisi .= '&waktu_to='.$date;
          $this->selected['waktu_to'] = $waktu_to;
        }else{
          $kondisi .='';
        }
      }else if($tipe == 'pelaksana'){
        $kondisi .= '&pelaksana='.$pelaksana;
        $this->selected['pelaksana'] = $pelaksana;
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

    $requestDisempenyiaran = $client->request('GET', $baseUrl.'/api/disempenyiaran?id_wilayah='.$request->session()->get('wilayah').'&'.$limit.'&'.$offset.$kondisi,
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
    $this->data['titledel'] = "disempenyiaran";
    $this->data['title'] = "Kegiatan Media Penyiaran";
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

    $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), '/'.$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter);
    $this->data['breadcrumps'] = breadcrumps_dir_diseminasi($request->route()->getName());
    return view('pencegahan.diseminasi.penyiaran.index_pendataanPenyiaran',$this->data);
  }

  public function editpendataanPenyiaran(Request $request)
  {
    $id = $request->id;
    $client = new Client();
    $baseUrl = URL::to('/');
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

    $meta_media = json_decode($dataDetail['data']['meta_media']);
    $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
    $this->data['pendataan'] = $dataDetail['data'];
    $this->data['meta_media'] = $meta_media;
    $this->data['breadcrumps'] = breadcrumps_dir_diseminasi($request->route()->getName());
    return view('pencegahan.diseminasi.penyiaran.edit_pendataanPenyiaran',$this->data);
  }

  public function addpendataanPenyiaran(Request $request)
  {
    $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
    $this->data['breadcrumps'] = breadcrumps_dir_diseminasi($request->route()->getName());
    return view('pencegahan.diseminasi.penyiaran.add_pendataanPenyiaran',$this->data);
  }

  public function inputpendataanPenyiaran(Request $request)
  {
        $client = new Client();
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');
        // dd($request->all());
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

      $jumlah_media = count($request->input('group-c'));

      $meta_media = $request->input('group-c');
      if (count($meta_media) > 0 ) {
         $meta_media = json_encode($request->input('group-c'));
      }else{
        $meta_media="";
      }

      $requestKapasitas = $client->request('POST', $baseUrl.'/api/disempenyiaran',
      [
        'headers' =>
        [
          'Authorization' => 'Bearer '.$token
        ],
        'form_params' => [
          "jenis_kegiatan" => ($request->input('jenis_kegiatan') ? $request->input('jenis_kegiatan') : ''),
          "dasar_kegiatan" => ($request->input('dasar_kegiatan') ? $request->input('dasar_kegiatan') : ''),
          "no_sprint" => ($request->input('no_sprint') ? $request->input('no_sprint') : ''),
          "no_spk" => ($request->input('no_spk') ? $request->input('no_spk') : ''),
          "waktu_publish" => ($request->input('waktu_publish') ? date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $request->input('waktu_publish')))) : ''),
          "selesai_publish" => ($request->input('selesai_publish') ? date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $request->input('selesai_publish')))) : ''),
          'idpelaksana' => ($request->input('idpelaksana') ? $request->input('idpelaksana') : ''),
          'tgl_pelaksanaan' => ($request->input('tgl_pelaksanaan') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))) : ''),
          'jenis_media' => ($request->input('jenis_media') ? $request->input('jenis_media') : ''),
          // 'kodesasaran' => ($request->input('sasaran') ? $request->input('sasaran') : ''),
          'meta_media' => $meta_media,
          'jumlah_media' => $jumlah_media,
          // 'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
          //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
          // 'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
          'narasumber' => ($request->input('narasumber') ? $request->input('narasumber') : ''),
          'penonton' => ($request->input('penonton') ? $request->input('penonton') : ''),
          // 'durasi_penyiaran' => $request->input('durasi_penyiaran'),
          // 'panitia_monev' => ($request->input('panitia_monev') ? $request->input('panitia_monev') : ''),
          'materi' => ($request->input('materi') ? $request->input('materi') : ''),
          'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
          'laporan' => ($request->input('laporan') ? $request->input('laporan') : ''),
          // 'file_upload' => $anggaran,
          // 'created_at' => $anggaran,
          // 'created_by' => $anggaran,
          'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'periode_tahun' => date('Y', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'anggaran_id' => $anggaran,
        ]
      ]
          );

          $result = json_decode($requestKapasitas->getBody()->getContents(), true);
          // dd($result);

          $this->form_params = array("jenis_kegiatan" => ($request->input('jenis_kegiatan') ? $request->input('jenis_kegiatan') : ''),
          "dasar_kegiatan" => ($request->input('dasar_kegiatan') ? $request->input('dasar_kegiatan') : ''),
          "no_sprint" => ($request->input('no_sprint') ? $request->input('no_sprint') : ''),
          "no_spk" => ($request->input('no_spk') ? $request->input('no_spk') : ''),
          "waktu_publish" => ($request->input('waktu_publish') ? date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $request->input('waktu_publish')))) : ''),
          "selesai_publish" => ($request->input('selesai_publish') ? date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $request->input('selesai_publish')))) : ''),
          'idpelaksana' => ($request->input('idpelaksana') ? $request->input('idpelaksana') : ''),
          'tgl_pelaksanaan' => ($request->input('tgl_pelaksanaan') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))) : ''),
          'jenis_media' => ($request->input('jenis_media') ? $request->input('jenis_media') : ''),
          // 'kodesasaran' => ($request->input('sasaran') ? $request->input('sasaran') : ''),
          'meta_media' => $meta_media,
          'jumlah_media' => $jumlah_media,
          // 'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
          //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
          // 'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
          'narasumber' => ($request->input('narasumber') ? $request->input('narasumber') : ''),
          'penonton' => ($request->input('penonton') ? $request->input('penonton') : ''),
          // 'durasi_penyiaran' => $request->input('durasi_penyiaran'),
          // 'panitia_monev' => ($request->input('panitia_monev') ? $request->input('panitia_monev') : ''),
          'materi' => ($request->input('materi') ? $request->input('materi') : ''),
          'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
          'laporan' => ($request->input('laporan') ? $request->input('laporan') : ''),
          // 'file_upload' => $anggaran,
          // 'created_at' => $anggaran,
          // 'created_by' => $anggaran,
          'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'periode_tahun' => date('Y', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'anggaran_id' => $anggaran);

          $trail['audit_menu'] = 'Pencegahan - Direktorat Diseminasi Informasi - Media Penyiaran';
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
            $request->file('file_upload')->storeAs('DiseminfoMediapenyiaran', $fileName);
            $requestfile = $client->request('PUT', $baseUrl.'/api/disempenyiaran/'.$inputId,
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

          $this->kelengkapan_Penyiaran($inputId);

          if( ($result['code'] == 200) && ($result['status'] != 'error') ){
           $this->data['status'] = 'success';
           $this->data['message'] = 'Pendataan Media Penyiaran berhasil disimpan. ';
          }else{
           $this->data['status'] = 'error';
           $this->data['message'] = 'Pendataan Media Penyiaran gagal disimpan. ';
          } //->with('status',$this->data)

        return redirect('/pencegahan/dir_diseminasi/pendataan_penyiaran')->with('status',$this->data);
  }

  public function updatependataanPenyiaran(Request $request)
  {
        $id = $request->input('id');
        $client = new Client();
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

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

        $jumlah_media = count($request->input('group-c'));

        $meta_media = $request->input('group-c');
        if (count($meta_media) > 0 ) {
           $meta_media = json_encode($request->input('group-c'));
        }else{
          $meta_media="";
        }

        $requestPenyiaran = $client->request('PUT', $baseUrl.'/api/disempenyiaran/'.$id,
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token
          ],
          'form_params' => [
            "jenis_kegiatan" => ($request->input('jenis_kegiatan') ? $request->input('jenis_kegiatan') : ''),
            "dasar_kegiatan" => ($request->input('dasar_kegiatan') ? $request->input('dasar_kegiatan') : ''),
            "no_sprint" => ($request->input('no_sprint') ? $request->input('no_sprint') : ''),
            "no_spk" => ($request->input('no_spk') ? $request->input('no_spk') : ''),
            "waktu_publish" => ($request->input('waktu_publish') ? date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $request->input('waktu_publish')))) : ''),
            "selesai_publish" => ($request->input('selesai_publish') ? date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $request->input('selesai_publish')))) : ''),
            'idpelaksana' => ($request->input('idpelaksana') ? $request->input('idpelaksana') : ''),
            'tgl_pelaksanaan' => ($request->input('tgl_pelaksanaan') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))) : ''),
            'jenis_media' => ($request->input('jenis_media') ? $request->input('jenis_media') : ''),
            // 'kodesasaran' => ($request->input('sasaran') ? $request->input('sasaran') : ''),
            'meta_media' => $meta_media,
            'jumlah_media' => $jumlah_media,
            // 'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
            //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
            // 'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
            'narasumber' => ($request->input('narasumber') ? $request->input('narasumber') : ''),
            'penonton' => ($request->input('penonton') ? $request->input('penonton') : ''),
            // 'durasi_penyiaran' => $request->input('durasi_penyiaran'),
            // 'panitia_monev' => ($request->input('panitia_monev') ? $request->input('panitia_monev') : ''),
            'materi' => ($request->input('materi') ? $request->input('materi') : ''),
            'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
            'laporan' => ($request->input('laporan') ? $request->input('laporan') : ''),
            // 'file_upload' => $anggaran,
            // 'created_at' => $anggaran,
            // 'created_by' => $anggaran,
            'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
            'periode_tahun' => date('Y', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
            'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
            'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
            'anggaran_id' => $anggaran,
          ]
        ]
      );

      $result = json_decode($requestPenyiaran->getBody()->getContents(), true);
      // dd($result);

      $this->form_params = array("jenis_kegiatan" => ($request->input('jenis_kegiatan') ? $request->input('jenis_kegiatan') : ''),
      "dasar_kegiatan" => ($request->input('dasar_kegiatan') ? $request->input('dasar_kegiatan') : ''),
      "no_sprint" => ($request->input('no_sprint') ? $request->input('no_sprint') : ''),
      "no_spk" => ($request->input('no_spk') ? $request->input('no_spk') : ''),
      "waktu_publish" => ($request->input('waktu_publish') ? date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $request->input('waktu_publish')))) : ''),
      "selesai_publish" => ($request->input('selesai_publish') ? date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $request->input('selesai_publish')))) : ''),
      'idpelaksana' => ($request->input('idpelaksana') ? $request->input('idpelaksana') : ''),
      'tgl_pelaksanaan' => ($request->input('tgl_pelaksanaan') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))) : ''),
      'jenis_media' => ($request->input('jenis_media') ? $request->input('jenis_media') : ''),
      // 'kodesasaran' => ($request->input('sasaran') ? $request->input('sasaran') : ''),
      'meta_media' => $meta_media,
      'jumlah_media' => $jumlah_media,
      // 'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
      //'lokasi_kegiatan_idprovinsi' => $request->input('lokasi_kegiatan_idprovinsi'),
      // 'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
      'narasumber' => ($request->input('narasumber') ? $request->input('narasumber') : ''),
      'penonton' => ($request->input('penonton') ? $request->input('penonton') : ''),
      // 'durasi_penyiaran' => $request->input('durasi_penyiaran'),
      // 'panitia_monev' => ($request->input('panitia_monev') ? $request->input('panitia_monev') : ''),
      'materi' => ($request->input('materi') ? $request->input('materi') : ''),
      'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
      'laporan' => ($request->input('laporan') ? $request->input('laporan') : ''),
      // 'file_upload' => $anggaran,
      // 'created_at' => $anggaran,
      // 'created_by' => $anggaran,
      'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
      'periode_tahun' => date('Y', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
      'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
      'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
      'anggaran_id' => $anggaran);

      $trail['audit_menu'] = 'Pencegahan - Direktorat Diseminasi Informasi - Media Penyiaran';
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
        $request->file('file_upload')->storeAs('DiseminfoMediapenyiaran', $fileName);
        $requestfile = $client->request('PUT', $baseUrl.'/api/disempenyiaran/'.$id,
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

      $this->kelengkapan_Penyiaran($id);
      if( ($result['code'] == 200) && ($result['code'] != 'error') ){
       $this->data['status'] = 'success';
       $this->data['message'] = 'Pendataan Media Penyiaran Berhasil Diperbarui ';
      }else{
       $this->data['status'] = 'error';
       $this->data['message'] = 'Pendataan Media Penyiaran Gagal Diperbarui';
      }
      return back()->with('status',$this->data);
  }

  public function pendataanCetak(Request $request)
  {
    $client = new Client();
    if ($request->input('page')) {
      $page = $request->input('page');
    } else {
      $page = 1;
    }

    $baseUrl = URL::to('/');
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
          if( ($key == 'waktu_from') || ($key == 'waktu_to') ){
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
      $anggaran = $request->anggaran;
      $jenis = $request->jenis;
      $waktu_from = $request->waktu_from;
      $waktu_to = $request->waktu_to;
      $order = $request->order;
      $limit = $request->limit;
      $kelengkapan = $request->kelengkapan;

      if($tipe == 'periode'){
        if($waktu_from){
          $date = date('Y-m-d',strtotime(str_replace('/', '-', $waktu_from)));
          $kondisi .= '&waktu_from='.$date;
          $this->selected['waktu_from'] = $waktu_from;
        }else{
            $kondisi .='';
        }
        if($waktu_to){
          $date = date('Y-m-d',strtotime(str_replace('/', '-', $waktu_to)));
          $kondisi .= '&waktu_to='.$date;
          $this->selected['waktu_to'] = $waktu_to;
        }else{
          $kondisi .='';
        }
      }else if($tipe == 'pelaksana'){
        $kondisi .= '&pelaksana='.$pelaksana;
        $this->selected['pelaksana'] = $pelaksana;
      }else if($tipe == 'anggaran'){
        $kondisi .= '&anggaran='.$anggaran;
        $this->selected['anggaran'] = $anggaran;
      }else if($tipe == 'jenis'){
        $kondisi .= '&jenis='.$jenis;
        $this->selected['jenis'] = $jenis;
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

    $requestDisemcetak = $client->request('GET', $baseUrl.'/api/disemcetak?id_wilayah='.$request->session()->get('wilayah').'&'.$limit.'&'.$offset.$kondisi,
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
    $this->data['titledel'] = "disemcetak";
    $this->data['title'] = "Kegiatan Media Cetak";
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

    $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), '/'.$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter);
    $this->data['breadcrumps'] = breadcrumps_dir_diseminasi($request->route()->getName());
    return view('pencegahan.diseminasi.cetak.index_pendataanCetak',$this->data);
  }

  public function addpendataanCetak(Request $request)
  {
    $client = new Client();
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');

    $this->data['title']="Pencegahan";
    $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));

    $propkab = $this->globalPropkab($token);
    $media = $client->request('GET', $baseUrl.'/api/getmedia/mediacetak');
    $this->data['media'] = json_decode($media->getBody()->getContents(), true);
    $this->data['propkab'] = $propkab['data'];
    $this->data['breadcrumps'] = breadcrumps_dir_diseminasi($request->route()->getName());
    return view('pencegahan.diseminasi.cetak.add_pendataanCetak',$this->data);
  }

  public function inputpendataanCetak(Request $request)
  {
    $client = new Client();
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');

    if ($request->input('kodesumberanggaran')=="DIPA") {
        $requestAnggaran = $client->request('POST', $baseUrl.'/api/anggaran',
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token
          ],
          'form_params' =>
          [
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

    $meta_media = $request->input('meta_media');
    if (count($meta_media) > 0 ) {
       $meta_media = json_encode($request->input('meta_media'));
    }else{
      $meta_media="";
    }

    $requestDisemcetak = $client->request('POST', $baseUrl.'/api/disemcetak',
      [
      'headers' =>
        [
        'Authorization' => 'Bearer '.$token
        ],
      'form_params' =>
        [
          "dasar_kegiatan" => ($request->input('dasar_kegiatan') ? $request->input('dasar_kegiatan') : ''),
          "no_sprint" => ($request->input('no_sprint') ? $request->input('no_sprint') : ''),
          "no_spk" => ($request->input('no_spk') ? $request->input('no_spk') : ''),
          "waktu_publish" => ($request->input('waktu_publish') ? date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $request->input('waktu_publish')))) : ''),
          "selesai_publish" => ($request->input('selesai_publish') ? date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $request->input('selesai_publish')))) : ''),
          'tgl_pelaksanaan' => ($request->input('tgl_pelaksanaan') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))) : ''),
          'idpelaksana' => ($request->input('idpelaksana') ? $request->input('idpelaksana') : ''),
          'kode_jenis_media' => ($request->input('kode_jenis_media') ? $request->input('kode_jenis_media') : ''),
          'kode_jenis_media_ruang' => ($request->input('kode_jenis_media_ruang') ? $request->input('kode_jenis_media_ruang') : ''),
          'nama_media' => ($request->input('nama_media') ? $request->input('nama_media') : ''),
          'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
          'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
          'materi' => ($request->input('materi') ? $request->input('materi') : ''),
          'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
          'kode_jenis_media_ruang' => ($request->input('kode_jenis_media_ruang') ? $request->input('kode_jenis_media_ruang') : ''),
          'laporan' => ($request->input('laporan') ? $request->input('laporan') : ''),
          'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'periode_tahun' => date('Y', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'anggaran_id' => $anggaran,
          'meta_media' => $meta_media,
          'jumlah_cetak' => ($request->input('jumlah_cetak') ? $request->input('jumlah_cetak') : '')
        ]
      ]
    );
    $disemcetak = json_decode($requestDisemcetak->getBody()->getContents(), true);
    // dd($disemcetak);

    $this->form_params = array("dasar_kegiatan" => ($request->input('dasar_kegiatan') ? $request->input('dasar_kegiatan') : ''),
    "no_sprint" => ($request->input('no_sprint') ? $request->input('no_sprint') : ''),
    "no_spk" => ($request->input('no_spk') ? $request->input('no_spk') : ''),
    "waktu_publish" => ($request->input('waktu_publish') ? date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $request->input('waktu_publish')))) : ''),
    "selesai_publish" => ($request->input('selesai_publish') ? date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $request->input('selesai_publish')))) : ''),
    'tgl_pelaksanaan' => ($request->input('tgl_pelaksanaan') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))) : ''),
    'idpelaksana' => ($request->input('idpelaksana') ? $request->input('idpelaksana') : ''),
    'kode_jenis_media' => ($request->input('kode_jenis_media') ? $request->input('kode_jenis_media') : ''),
    'kode_jenis_media_ruang' => ($request->input('kode_jenis_media_ruang') ? $request->input('kode_jenis_media_ruang') : ''),
    'nama_media' => ($request->input('nama_media') ? $request->input('nama_media') : ''),
    'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
    'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
    'materi' => ($request->input('materi') ? $request->input('materi') : ''),
    'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
    'kode_jenis_media_ruang' => ($request->input('kode_jenis_media_ruang') ? $request->input('kode_jenis_media_ruang') : ''),
    'laporan' => ($request->input('laporan') ? $request->input('laporan') : ''),
    'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
    'periode_tahun' => date('Y', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
    'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
    'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
    'anggaran_id' => $anggaran,
    'meta_media' => $meta_media,
    'jumlah_cetak' => ($request->input('jumlah_cetak') ? $request->input('jumlah_cetak') : ''));

    $trail['audit_menu'] = 'Pencegahan - Direktorat Diseminasi Informasi - Media Cetak';
    $trail['audit_event'] = 'post';
    $trail['audit_value'] = json_encode($this->form_params);
    $trail['audit_url'] = $request->url();
    $trail['audit_ip_address'] = $request->ip();
    $trail['audit_user_agent'] = $request->userAgent();
    $trail['audit_message'] = $disemcetak['comment'];
    $trail['created_at'] = date("Y-m-d H:i:s");
    $trail['created_by'] = $request->session()->get('id');

    $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

    $inputId = $disemcetak['data']['eventID'];

    if ($request->file('file_upload') != ''){
      $fileName = $inputId.'-'.$request->file('file_upload')->getClientOriginalName();
      $request->file('file_upload')->storeAs('DiseminfoMediacetak', $fileName);
      $requestfile = $client->request('PUT', $baseUrl.'/api/disemcetak/'.$inputId,
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

    $this->kelengkapan_Cetak($inputId);

    if( ($disemcetak['code'] == 200) && ($disemcetak['status'] != 'error') ){
     $this->data['status'] = 'success';
     $this->data['message'] = 'Pendataan Media Cetak berhasil disimpan. ';
    }else{
     $this->data['status'] = 'error';
     $this->data['message'] = 'Pendataan Media Cetak gagal disimpan. ';
    } //->with('status',$this->data)

    return redirect('/pencegahan/dir_diseminasi/pendataan_cetak')->with('status',$this->data);
  }

  public function editpendataanCetak(Request $request)
  {
    $id = $request->id;
    $client = new Client();
    $baseUrl = URL::to('/');
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
    $propkab = $this->globalPropkab($token);
    $media = $client->request('GET', $baseUrl.'/api/getmedia/mediacetak');
    $this->data['media'] = json_decode($media->getBody()->getContents(), true);
    $querymeta = DB::table('tr_media')->where('value_media', $dataDetail['data']['kode_jenis_media'])->get();
    $idmeta = json_decode($querymeta, true);
    $metamedia = $client->request('GET', $baseUrl.'/api/getmedia/mediaruang/'.$idmeta[0]['id']);
    $this->data['metamedia'] = json_decode($metamedia->getBody()->getContents(), true);
    $this->data['nama_media'] = $idmeta[0]['nama_media'];

    $this->data['pendataan'] = $dataDetail['data'];
    $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
    $this->data['propkab'] = $propkab['data'];
    $this->data['breadcrumps'] = breadcrumps_dir_diseminasi($request->route()->getName());
    return view('pencegahan.diseminasi.cetak.edit_pendataanCetak',$this->data);
  }

  public function updatependataanCetak(Request $request)
  {
    $id = $request->input('id');
    $client = new Client();
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');

    if ($request->input('kodesumberanggaran')=="DIPA") {
        $requestAnggaran = $client->request('POST', $baseUrl.'/api/anggaran',
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token
          ],
          'form_params' =>
          [
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

    $meta_media = $request->input('meta_media');
    if (count($meta_media) > 0 ) {
       $meta_media = json_encode($request->input('meta_media'));
    }else{
      $meta_media="";
    }

    $requestPenyiaran = $client->request('PUT', $baseUrl.'/api/disemcetak/'.$id,
    [
      'headers' =>
      [
        'Authorization' => 'Bearer '.$token
      ],
      'form_params' => [
          "dasar_kegiatan" => ($request->input('dasar_kegiatan') ? $request->input('dasar_kegiatan') : ''),
          "no_sprint" => ($request->input('no_sprint') ? $request->input('no_sprint') : ''),
          "no_spk" => ($request->input('no_spk') ? $request->input('no_spk') : ''),
          "waktu_publish" => ($request->input('waktu_publish') ? date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $request->input('waktu_publish')))) : ''),
          "selesai_publish" => ($request->input('selesai_publish') ? date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $request->input('selesai_publish')))) : ''),
          'tgl_pelaksanaan' => ($request->input('tgl_pelaksanaan') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))) : ''),
          'idpelaksana' => ($request->input('idpelaksana') ? $request->input('idpelaksana') : ''),
          'kode_jenis_media' => ($request->input('kode_jenis_media') ? $request->input('kode_jenis_media') : ''),
          'kode_jenis_media_ruang' => ($request->input('kode_jenis_media_ruang') ? $request->input('kode_jenis_media_ruang') : ''),
          'nama_media' => ($request->input('nama_media') ? $request->input('nama_media') : ''),
          'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
          'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
          'materi' => ($request->input('materi') ? $request->input('materi') : ''),
          'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
          'kode_jenis_media_ruang' => ($request->input('kode_jenis_media_ruang') ? $request->input('kode_jenis_media_ruang') : ''),
          'laporan' => ($request->input('laporan') ? $request->input('laporan') : ''),
          'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'periode_tahun' => date('Y', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'anggaran_id' => $anggaran,
          'meta_media' => $meta_media,
          'jumlah_cetak' => ($request->input('jumlah_cetak') ? $request->input('jumlah_cetak') : '')
        ]
      ]
    );

    $result = json_decode($requestPenyiaran->getBody()->getContents(), true);
    // dd($result);

    $this->form_params = array("dasar_kegiatan" => ($request->input('dasar_kegiatan') ? $request->input('dasar_kegiatan') : ''),
    "no_sprint" => ($request->input('no_sprint') ? $request->input('no_sprint') : ''),
    "no_spk" => ($request->input('no_spk') ? $request->input('no_spk') : ''),
    "waktu_publish" => ($request->input('waktu_publish') ? date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $request->input('waktu_publish')))) : ''),
    "selesai_publish" => ($request->input('selesai_publish') ? date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $request->input('selesai_publish')))) : ''),
    'tgl_pelaksanaan' => ($request->input('tgl_pelaksanaan') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))) : ''),
    'idpelaksana' => ($request->input('idpelaksana') ? $request->input('idpelaksana') : ''),
    'kode_jenis_media' => ($request->input('kode_jenis_media') ? $request->input('kode_jenis_media') : ''),
    'kode_jenis_media_ruang' => ($request->input('kode_jenis_media_ruang') ? $request->input('kode_jenis_media_ruang') : ''),
    'nama_media' => ($request->input('nama_media') ? $request->input('nama_media') : ''),
    'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
    'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
    'materi' => ($request->input('materi') ? $request->input('materi') : ''),
    'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
    'kode_jenis_media_ruang' => ($request->input('kode_jenis_media_ruang') ? $request->input('kode_jenis_media_ruang') : ''),
    'laporan' => ($request->input('laporan') ? $request->input('laporan') : ''),
    'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
    'periode_tahun' => date('Y', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
    'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
    'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
    'anggaran_id' => $anggaran,
    'meta_media' => $meta_media,
    'jumlah_cetak' => ($request->input('jumlah_cetak') ? $request->input('jumlah_cetak') : ''));

    $trail['audit_menu'] = 'Pencegahan - Direktorat Diseminasi Informasi - Media Cetak';
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
      $request->file('file_upload')->storeAs('DiseminfoMediacetak', $fileName);
      $requestfile = $client->request('PUT', $baseUrl.'/api/disemcetak/'.$id,
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

    $this->kelengkapan_Cetak($id);
    if( ($result['code'] == 200) && ($result['code'] != 'error') ){
     $this->data['status'] = 'success';
     $this->data['message'] = 'Pendataan Media Cetak Berhasil Diperbarui ';
    }else{
     $this->data['status'] = 'error';
     $this->data['message'] = 'Pendataan Media Cetak Gagal Diperbarui';
    }
    return back()->with('status',$this->data);
  }

  public function pendataanKonvensional(Request $request)
  {
    $client = new Client();
    if ($request->input('page')) {
      $page = $request->input('page');
    } else {
      $page = 1;
    }

    $baseUrl = URL::to('/');
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
      $anggaran = $request->anggaran;
      $sasaran = $request->sasaran;
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
      }else if($tipe == 'anggaran'){
        $kondisi .= '&anggaran='.$anggaran;
        $this->selected['anggaran'] = $anggaran;
      }else if($tipe == 'sasaran'){
        $kondisi .= '&sasaran='.$sasaran;
        $this->selected['sasaran'] = $sasaran;
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

    $requestDisemkonven = $client->request('GET', $baseUrl.'/api/disemkonven?id_wilayah='.$request->session()->get('wilayah').'&'.$limit.'&'.$offset.$kondisi,
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
    $this->data['titledel'] = "disemkonven";
    $this->data['title'] = "Kegiatan Media Konvensional";
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

    $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), '/'.$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter);
    $this->data['breadcrumps'] = breadcrumps_dir_diseminasi($request->route()->getName());
    return view('pencegahan.diseminasi.konvensional.index_pendataanKonvensional',$this->data);
  }

  public function addpendataanKonvensional(Request $request)
  {
    $client = new Client();
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');
    $propkab = $this->globalPropkab($token);

    $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
    $this->data['propkab'] = $propkab['data'];
    $this->data['breadcrumps'] = breadcrumps_dir_diseminasi($request->route()->getName());
    return view('pencegahan.diseminasi.konvensional.add_pendataanKonvensional',$this->data);
  }

  public function inputpendataanKonvensional(Request $request)
  {
    // dd($request->all());
    $client = new Client();
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');

    if ($request->input('kodesumberanggaran')=="DIPA") {
        $requestAnggaran = $client->request('POST', $baseUrl.'/api/anggaran',
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token
          ],
          'form_params' =>
          [
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

    $requestDisemcetak = $client->request('POST', $baseUrl.'/api/disemkonven',
      [
      'headers' =>
        [
        'Authorization' => 'Bearer '.$token
        ],
      'form_params' =>
        [
          "dasar_kegiatan" => ($request->input('dasar_kegiatan') ? $request->input('dasar_kegiatan') : ''),
          "no_sprint" => ($request->input('no_sprint') ? $request->input('no_sprint') : ''),
          "no_spk" => ($request->input('no_spk') ? $request->input('no_spk') : ''),
          "waktu_publish" => ($request->input('waktu_publish') ? date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $request->input('waktu_publish')))) : ''),
          "selesai_publish" => ($request->input('selesai_publish') ? date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $request->input('selesai_publish')))) : ''),
          'tgl_pelaksanaan' => ($request->input('tgl_pelaksanaan') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))) : ''),
          'idpelaksana' => ($request->input('idpelaksana') ? $request->input('idpelaksana') : ''),
          'kodesasaran' => ($request->input('kodesasaran') ? $request->input('kodesasaran') : ''),
          'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
          'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
          'materi' => ($request->input('materi') ? $request->input('materi') : ''),
          'narasumber' => ($request->input('narasumber') ? $request->input('narasumber') : ''),
          'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
          'jumlah_peserta' => ($request->input('jumlah_peserta') ? $request->input('jumlah_peserta') : ''),
          'laporan' => ($request->input('laporan') ? $request->input('laporan') : ''),
          'jenis_kegiatan' => ($request->input('jenis_kegiatan') ? $request->input('jenis_kegiatan') : ''),
          'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'periode_tahun' => date('Y', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'anggaran_id' => $anggaran
        ]
      ]
    );
    $disemcetak = json_decode($requestDisemcetak->getBody()->getContents(), true);
    // dd($disemcetak);

    $this->form_params = array("dasar_kegiatan" => ($request->input('dasar_kegiatan') ? $request->input('dasar_kegiatan') : ''),
    "no_sprint" => ($request->input('no_sprint') ? $request->input('no_sprint') : ''),
    "no_spk" => ($request->input('no_spk') ? $request->input('no_spk') : ''),
    "waktu_publish" => ($request->input('waktu_publish') ? date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $request->input('waktu_publish')))) : ''),
    "selesai_publish" => ($request->input('selesai_publish') ? date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $request->input('selesai_publish')))) : ''),
    'tgl_pelaksanaan' => ($request->input('tgl_pelaksanaan') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))) : ''),
    'idpelaksana' => ($request->input('idpelaksana') ? $request->input('idpelaksana') : ''),
    'kodesasaran' => ($request->input('kodesasaran') ? $request->input('kodesasaran') : ''),
    'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
    'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
    'materi' => ($request->input('materi') ? $request->input('materi') : ''),
    'narasumber' => ($request->input('narasumber') ? $request->input('narasumber') : ''),
    'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
    'jumlah_peserta' => ($request->input('jumlah_peserta') ? $request->input('jumlah_peserta') : ''),
    'laporan' => ($request->input('laporan') ? $request->input('laporan') : ''),
    'jenis_kegiatan' => ($request->input('jenis_kegiatan') ? $request->input('jenis_kegiatan') : ''),
    'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
    'periode_tahun' => date('Y', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
    'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
    'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
    'anggaran_id' => $anggaran);

    $trail['audit_menu'] = 'Pencegahan - Direktorat Diseminasi Informasi - Media Konvensional';
    $trail['audit_event'] = 'post';
    $trail['audit_value'] = json_encode($this->form_params);
    $trail['audit_url'] = $request->url();
    $trail['audit_ip_address'] = $request->ip();
    $trail['audit_user_agent'] = $request->userAgent();
    $trail['audit_message'] = $disemcetak['comment'];
    $trail['created_at'] = date("Y-m-d H:i:s");
    $trail['created_by'] = $request->session()->get('id');

    $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

    $inputId = $disemcetak['data']['eventID'];

    if ($request->file('file_upload') != ''){
      $fileName = $inputId.'-'.$request->file('file_upload')->getClientOriginalName();
      $request->file('file_upload')->storeAs('DiseminfoMediakonvensional', $fileName);
      $requestfile = $client->request('PUT', $baseUrl.'/api/disemkonven/'.$inputId,
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

    $this->kelengkapan_Konven($inputId);

    if( ($disemcetak['code'] == 200) && ($disemcetak['status'] != 'error') ){
     $this->data['status'] = 'success';
     $this->data['message'] = 'Pendataan Media Konvensional berhasil disimpan. ';
    }else{
     $this->data['status'] = 'error';
     $this->data['message'] = 'Pendataan Media Konvensional gagal disimpan. ';
    } //->with('status',$this->data)

    return redirect('/pencegahan/dir_diseminasi/pendataan_konvensional')->with('status',$this->data);
  }

  public function editpendataanKonvensional(Request $request)
  {
    $id = $request->id;
    $client = new Client();
    $baseUrl = URL::to('/');
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
    $propkab = $this->globalPropkab($token);

    $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
    $this->data['propkab'] = $propkab['data'];
    $this->data['pendataan'] = $dataDetail['data'];
    $this->data['breadcrumps'] = breadcrumps_dir_diseminasi($request->route()->getName());
    return view('pencegahan.diseminasi.konvensional.edit_pendataanKonvensional',$this->data);
  }

  public function updatependataanKonvensional(Request $request)
  {
    // dd($request->all());
    $id = $request->input('id');
    $client = new Client();
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');

    if ($request->input('kodesumberanggaran')=="DIPA") {
        $requestAnggaran = $client->request('POST', $baseUrl.'/api/anggaran',
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token
          ],
          'form_params' =>
          [
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

    $requestDisemcetak = $client->request('PuT', $baseUrl.'/api/disemkonven/'.$id,
      [
      'headers' =>
        [
        'Authorization' => 'Bearer '.$token
        ],
      'form_params' =>
        [
          "dasar_kegiatan" => ($request->input('dasar_kegiatan') ? $request->input('dasar_kegiatan') : ''),
          "no_sprint" => ($request->input('no_sprint') ? $request->input('no_sprint') : ''),
          "no_spk" => ($request->input('no_spk') ? $request->input('no_spk') : ''),
          "waktu_publish" => ($request->input('waktu_publish') ? date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $request->input('waktu_publish')))) : ''),
          "selesai_publish" => ($request->input('selesai_publish') ? date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $request->input('selesai_publish')))) : ''),
          'tgl_pelaksanaan' => ($request->input('tgl_pelaksanaan') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))) : ''),
          'idpelaksana' => ($request->input('idpelaksana') ? $request->input('idpelaksana') : ''),
          'kodesasaran' => ($request->input('kodesasaran') ? $request->input('kodesasaran') : ''),
          'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
          'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
          'materi' => ($request->input('materi') ? $request->input('materi') : ''),
          'narasumber' => ($request->input('narasumber') ? $request->input('narasumber') : ''),
          'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
          'jumlah_peserta' => ($request->input('jumlah_peserta') ? $request->input('jumlah_peserta') : ''),
          'laporan' => ($request->input('laporan') ? $request->input('laporan') : ''),
          'jenis_kegiatan' => ($request->input('jenis_kegiatan') ? $request->input('jenis_kegiatan') : ''),
          'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'periode_tahun' => date('Y', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'anggaran_id' => $anggaran
        ]
      ]
    );
    $disemcetak = json_decode($requestDisemcetak->getBody()->getContents(), true);
    // dd($disemcetak);

    $this->form_params = array("dasar_kegiatan" => ($request->input('dasar_kegiatan') ? $request->input('dasar_kegiatan') : ''),
    "no_sprint" => ($request->input('no_sprint') ? $request->input('no_sprint') : ''),
    "no_spk" => ($request->input('no_spk') ? $request->input('no_spk') : ''),
    "waktu_publish" => ($request->input('waktu_publish') ? date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $request->input('waktu_publish')))) : ''),
    "selesai_publish" => ($request->input('selesai_publish') ? date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $request->input('selesai_publish')))) : ''),
    'tgl_pelaksanaan' => ($request->input('tgl_pelaksanaan') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))) : ''),
    'idpelaksana' => ($request->input('idpelaksana') ? $request->input('idpelaksana') : ''),
    'kodesasaran' => ($request->input('kodesasaran') ? $request->input('kodesasaran') : ''),
    'lokasi_kegiatan' => ($request->input('lokasi_kegiatan') ? $request->input('lokasi_kegiatan') : ''),
    'lokasi_kegiatan_idkabkota' => ($request->input('lokasi_kegiatan_idkabkota') ? $request->input('lokasi_kegiatan_idkabkota') : ''),
    'materi' => ($request->input('materi') ? $request->input('materi') : ''),
    'narasumber' => ($request->input('narasumber') ? $request->input('narasumber') : ''),
    'kodesumberanggaran' => ($request->input('kodesumberanggaran') ? $request->input('kodesumberanggaran') : ''),
    'jumlah_peserta' => ($request->input('jumlah_peserta') ? $request->input('jumlah_peserta') : ''),
    'laporan' => ($request->input('laporan') ? $request->input('laporan') : ''),
    'jenis_kegiatan' => ($request->input('jenis_kegiatan') ? $request->input('jenis_kegiatan') : ''),
    'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
    'periode_tahun' => date('Y', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
    'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
    'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
    'anggaran_id' => $anggaran);

    $trail['audit_menu'] = 'Pencegahan - Direktorat Diseminasi Informasi - Media Konvensional';
    $trail['audit_event'] = 'put';
    $trail['audit_value'] = json_encode($this->form_params);
    $trail['audit_url'] = $request->url();
    $trail['audit_ip_address'] = $request->ip();
    $trail['audit_user_agent'] = $request->userAgent();
    $trail['audit_message'] = $disemcetak['comment'];
    $trail['created_at'] = date("Y-m-d H:i:s");
    $trail['created_by'] = $request->session()->get('id');

    $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


    if ($request->file('file_upload') != ''){
      $fileName = $id.'-'.$request->file('file_upload')->getClientOriginalName();
      $request->file('file_upload')->storeAs('DiseminfoMediakonvensional', $fileName);
      $requestfile = $client->request('PUT', $baseUrl.'/api/disemkonven/'.$id,
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

    $this->kelengkapan_Konven($id);
    if( ($disemcetak['code'] == 200) && ($disemcetak['code'] != 'error') ){
     $this->data['status'] = 'success';
     $this->data['message'] = 'Pendataan Media Konvensional Berhasil Diperbarui ';
    }else{
     $this->data['status'] = 'error';
     $this->data['message'] = 'Pendataan Media Konvensional Gagal Diperbarui';
    }
    return back()->with('status',$this->data);
  }

  public function pendataanVideotron(Request $request)
  {
    $client = new Client();
    if ($request->input('page')) {
      $page = $request->input('page');
    } else {
      $page = 1;
    }

    $baseUrl = URL::to('/');
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
    $this->data['title'] = "disemvideotron";
    $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
    $this->data['token'] = $token;
    $this->data['path'] = $request->path();
    $this->data['page'] = $page;
    $this->data['breadcrumps'] = breadcrumps_dir_diseminasi($request->route()->getName());
    return view('pencegahan.diseminasi.videotron.index_pendataanVideotron',$this->data);
  }

  public function addpendataanVideotron(Request $request)
  {
    $client = new Client();
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');
    $propkab = $this->globalPropkab($token);

    $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
    $this->data['propkab'] = $propkab['data'];
    $this->data['breadcrumps'] = breadcrumps_dir_diseminasi($request->route()->getName());
    return view('pencegahan.diseminasi.videotron.add_pendataanVideotron',$this->data);
  }

  public function inputpendataanVideotron(Request $request)
  {
    // dd($request->all());
    $client = new Client();
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');

    if ($request->input('kodesumberanggaran')=="DIPA") {
        $requestAnggaran = $client->request('POST', $baseUrl.'/api/anggaran',
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token
          ],
          'form_params' =>
          [
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

    $requestDisemvid = $client->request('POST', $baseUrl.'/api/disemvideotron',
      [
      'headers' =>
        [
        'Authorization' => 'Bearer '.$token
        ],
      'form_params' =>
        [
          'tanggal_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'idpelaksana' => $request->input('idpelaksana'),
          'kodesasaran' => $request->input('kodesasaran'),
          'lokasi_penempatan' => $request->input('lokasi_penempatan'),
          'lokasi_penempatan_idkabkota' => $request->input('lokasi_penempatan_idkabkota'),
          'durasi_waktu' => $request->input('durasi_waktu'),
          'materi' => $request->input('materi'),
          'keterangan' => $request->input('keterangan'),
          'kodesumberanggaran' => $request->input('kodesumberanggaran'),
          'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'periode_tahun' => date('Y', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'anggaran_id' => $anggaran
        ]
      ]
    );
    $disemvid = json_decode($requestDisemvid->getBody()->getContents(), true);
    // dd($disemvid);
    $inputId = $disemvid['data']['eventID'];

    if ($request->file('file_upload') != ''){
      $fileName = $inputId.'-'.$request->file('file_upload')->getClientOriginalName();
      $request->file('file_upload')->storeAs('DiseminfoMediavideotron', $fileName);
      $requestfile = $client->request('PUT', $baseUrl.'/api/disemvideotron/'.$inputId,
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
      $file = json_decode($requestfile->getBody()->getContents(), true);
    }

    return redirect('/pencegahan/dir_diseminasi/pendataan_videotron');
  }

  public function editpendataanVideotron(Request $request)
  {
    $id = $request->id;
    $client = new Client();
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');

    $requestDataDetail= $client->request('GET', $baseUrl.'/api/disemvideotron/'.$id,
    [
    'headers' =>
    [
    'Authorization' => 'Bearer '.$token
    ]
    ]
    );

    $result = json_decode($requestDataDetail->getBody()->getContents(), true);

    if ($result['data']['anggaran_id'] != '') {
      $this->data['data_anggaran'] = $this->globalGetAnggaran($token, $result['data']['anggaran_id']);
    }

    $propkab = $this->globalPropkab($token);

    $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
    $this->data['propkab'] = $propkab['data'];
    $this->data['pendataan'] = $result['data'];
    $this->data['breadcrumps'] = breadcrumps_dir_diseminasi($request->route()->getName());
    return view('pencegahan.diseminasi.videotron.edit_pendataanVideotron',$this->data);
  }

  public function updatependataanVideotron(Request $request)
  {
    // dd($request->all());
    $id = $request->input('id');
    $client = new Client();
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');

    $requestDisemvid = $client->request('PUT', $baseUrl.'/api/disemvideotron/'.$id,
      [
      'headers' =>
        [
        'Authorization' => 'Bearer '.$token
        ],
      'form_params' =>
        [
          'tanggal_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'idpelaksana' => $request->input('idpelaksana'),
          'lokasi_penempatan' => $request->input('lokasi_penempatan'),
          'lokasi_penempatan_idkabkota' => $request->input('lokasi_penempatan_idkabkota'),
          'durasi_waktu' => $request->input('durasi_waktu'),
          'materi' => $request->input('materi'),
          'keterangan' => $request->input('keterangan'),
          'periode' => date('Ym', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'periode_tahun' => date('Y', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'periode_bulan' => date('m', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
          'periode_tanggal' => date('d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan'))))
        ]
      ]
    );
    $disemvid = json_decode($requestDisemvid->getBody()->getContents(), true);
    // dd($disemvid);
    $inputId = $disemvid['data']['eventID'];

    if ($request->file('file_upload') != ''){
      $fileName = $inputId.'-'.$request->file('file_upload')->getClientOriginalName();
      $request->file('file_upload')->storeAs('DiseminfoMediavideotron', $fileName);
      $requestfile = $client->request('PUT', $baseUrl.'/api/disemvideotron/'.$inputId,
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

    return redirect('/pencegahan/dir_diseminasi/pendataan_videotron');
  }

  public function printOnline(Request $request){
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
      // if($page){
      //     $page = $page;
      // }else{
      //     $page = 1;
      // }
      $result = [];
      $url = 'api/disemonline'.$kondisi.'&id_wilayah='.$request->session()->get('wilayah');

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
        $DataArray[$key]['Pelaksana'] = $value['nm_instansi'];
        $DataArray[$key]['Waktu'] = ( $value['waktu_publish'] ? date('d/m/Y H:i:s', strtotime($value['waktu_publish'])) :'' );
        $DataArray[$key]['Materi'] = $value['materi'];
        $DataArray[$key]['Sumber Anggaran'] = $value['kodesumberanggaran'];
        $i = $i +1;
      }
       //dd($DataArray);
      $data = $DataArray;
      $name = 'Data Kegiatan Media Online '.Carbon::now()->format('Y-m-d H:i:s');
      $this->printData($data, $name);
  }

  public function printPenyiaran(Request $request){
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
      // if($page){
      //     $page = $page;
      // }else{
      //     $page = 1;
      // }
      $result = [];
      $url = 'api/disempenyiaran'.$kondisi.'&id_wilayah='.$request->session()->get('wilayah');

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
        $DataArray[$key]['Pelaksana'] = $value['nm_instansi'];
        $DataArray[$key]['Waktu'] = ( $value['waktu_publish'] ? date('d/m/Y H:i:s', strtotime($value['waktu_publish'])) :'' );
        $DataArray[$key]['Materi'] = $value['materi'];
        $DataArray[$key]['Narasumber'] = $value['narasumber'];
        $i = $i +1;
      }
       //dd($DataArray);
      $data = $DataArray;
      $name = 'Data Penyebarluasan Informasi P4GN Melalui Penyiaran '.Carbon::now()->format('Y-m-d H:i:s');
      $this->printData($data, $name);
  }

  public function printCetak(Request $request){
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
      // if($page){
      //     $page = $page;
      // }else{
      //     $page = 1;
      // }
      $result = [];
      $url = 'api/disemcetak'.$kondisi.'&id_wilayah='.$request->session()->get('wilayah');

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
        $DataArray[$key]['Pelaksana'] = $value['nm_instansi'];
        $DataArray[$key]['Waktu'] = ( $value['waktu_publish'] ? date('d/m/Y H:i:s', strtotime($value['waktu_publish'])) :'' );
        $DataArray[$key]['Nama Media'] = $value['nama_media'];
        $DataArray[$key]['Materi'] = $value['materi'];
        $DataArray[$key]['Jenis Media'] = $value['kode_jenis_media'];
        $i = $i +1;
      }
       //dd($DataArray);
      $data = $DataArray;
      $name = 'Data Kegiatan Penyebarluasan Informasi P4GN Melalui Media Cetak '.Carbon::now()->format('Y-m-d H:i:s');
      $this->printData($data, $name);
  }

  public function printKonvensional(Request $request){
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
      // if($page){
      //     $page = $page;
      // }else{
      //     $page = 1;
      // }
      $result = [];
      $url = 'api/disemkonven'.$kondisi.'&id_wilayah='.$request->session()->get('wilayah');

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
        $DataArray[$key]['Pelaksana'] = $value['nm_instansi'];
        $DataArray[$key]['Waktu'] = ( $value['waktu_publish'] ? date('d/m/Y H:i:s', strtotime($value['waktu_publish'])) :'' );
        $DataArray[$key]['Sasaran'] = $value['kodesasaran'];
        $DataArray[$key]['Materi'] = $value['materi'];
        $DataArray[$key]['Narasumber'] = $value['narasumber'];
        $i = $i +1;
      }
       //dd($DataArray);
      $data = $DataArray;
      $name = 'Data Kegiatan Penyebarluasan Informasi P4GN Melalui Media Konvensional '.Carbon::now()->format('Y-m-d H:i:s');
      $this->printData($data, $name);
  }

  public function printVideotron(Request $request){
      $client = new Client();
      $page = $request->input('page');
      $token = $request->session()->get('token');
      $baseUrl = URL::to('/');

      $requestPrintData = $client->request('GET', $baseUrl.'/api/disemvideotron?page='.$page,
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
        $DataArray[$key]['Pelaksana'] = $value['nm_instansi'];
        $DataArray[$key]['Waktu'] = $value['tanggal_pelaksanaan'];
        $DataArray[$key]['Tema'] = $value['materi'];
        $DataArray[$key]['Alamat Penempatan'] = $value['lokasi_penempatan'];
        $DataArray[$key]['Durasi Waktu (Detik)'] = $value['durasi_waktu'];
        $i = $i +1;
      }
       //dd($DataArray);
      $data = $DataArray;
      $name = 'Data Kegiatan Penyebarluasan Informasi P4GN Melalui Media Videotron '.Carbon::now()->format('Y-m-d H:i:s');
      $this->printData($data, $name);
  }

  private function kelengkapan_Online($id){
    $status_kelengkapan = true;
    try{
      $query = DB::table('cegahdiseminfo_mediaonline')->where('id',$id)
                ->select('dasar_kegiatan','waktu_publish','selesai_publish','idpelaksana','materi', 'jenis_media', 'meta_media', 'jumlah_yang_melihat','orang_yang_tertarik', 'kodesumberanggaran', 'file_upload');
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
        $kelengkapan = execute_api_json('api/disemonline/'.$id,'PUT',['status'=>'Y']);
      }else{
        $kelengkapan = execute_api_json('api/disemonline/'.$id,'PUT',['status'=>'N']);
      }
    }catch(\Exception $e){
      $status_kelengkapan=false;
    }
  }

  private function kelengkapan_Penyiaran($id){
    $status_kelengkapan = true;
    try{
      $query = DB::table('cegahdiseminfo_mediapenyiaran')->where('id',$id)
                ->select('dasar_kegiatan','waktu_publish','idpelaksana','materi', 'jenis_media', 'meta_media', 'file_upload','penonton', 'narasumber', 'kodesumberanggaran');
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
        $kelengkapan = execute_api_json('api/disempenyiaran/'.$id,'PUT',['status'=>'Y']);
      }else{
        $kelengkapan = execute_api_json('api/disempenyiaran/'.$id,'PUT',['status'=>'N']);
      }
    }catch(\Exception $e){
      $status_kelengkapan=false;
    }
  }

  private function kelengkapan_Cetak($id){
    $status_kelengkapan = true;
    try{
      $query = DB::table('cegahdiseminfo_mediacetak')->where('id',$id)
                ->select('dasar_kegiatan','waktu_publish','idpelaksana','materi', 'kode_jenis_media', 'meta_media','nama_media', 'jumlah_cetak', 'lokasi_kegiatan', 'lokasi_kegiatan_idkabkota', 'kodesumberanggaran', 'file_upload');
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
        $kelengkapan = execute_api_json('api/disemcetak/'.$id,'PUT',['status'=>'Y']);
      }else{
        $kelengkapan = execute_api_json('api/disemcetak/'.$id,'PUT',['status'=>'N']);
      }
    }catch(\Exception $e){
      $status_kelengkapan=false;
    }
  }

  private function kelengkapan_Konven($id){
    $status_kelengkapan = true;
    try{
      $query = DB::table('cegahdiseminfo_mediakonvensional')->where('id',$id)
                ->select('jenis_kegiatan', 'dasar_kegiatan','waktu_publish','idpelaksana','materi', 'narasumber', 'kodesasaran', 'file_upload', 'tgl_pelaksanaan', 'lokasi_kegiatan', 'lokasi_kegiatan_idkabkota', 'jumlah_peserta', 'kodesumberanggaran');
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
        $kelengkapan = execute_api_json('api/disemkonven/'.$id,'PUT',['status'=>'Y']);
      }else{
        $kelengkapan = execute_api_json('api/disemkonven/'.$id,'PUT',['status'=>'N']);
      }
    }catch(\Exception $e){
      $status_kelengkapan=false;
    }
  }


}

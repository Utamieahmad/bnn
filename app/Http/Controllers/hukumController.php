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

class HukumController extends Controller
{
    public $data;
    public $messages;

    public $selected;
    public $form_params;

    public function hukumRakor(Request $request){
        $client = new Client();
        if ($request->input('page')) {
          $page = $request->input('page');
        } else {
          $page = 1;
        }

        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestRakoraudiensi = $client->request('GET', $baseUrl.'/api/irtarakoraudiensi?page='.$page,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
        $irtarakoraudiensi = json_decode($requestRakoraudiensi->getBody()->getContents(), true);

        $this->data['data_irtarakoraudiensi'] = $irtarakoraudiensi['data'];
		    $page = $irtarakoraudiensi['paginate'];
        $this->data['title'] = "irtarakoraudiensi";
        $this->data['token'] = $token;


        $instansi = $this->globalinstansi($request->session()->get('wilayah'), $token);

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['path'] = $request->path();
        $this->data['instansi'] = $instansi;
        $this->data['page'] = $page;
        $this->data['breadcrumps'] = breadcrumps_dir_hukum($request->route()->getName());
        return view('huker.hukum.rakor.index_hukumRakor',$this->data);
    }

    public function addhukumRakor(Request $request){
        // $this->data['title']="Hukum dan Kerjasama";
        $client = new Client();
        $baseUrl = URL::to('/');

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        $this->data['breadcrumps'] = breadcrumps_dir_hukum($request->route()->getName());
        return view('huker.hukum.rakor.add_hukumRakor',$this->data);
    }

    public function edithukumRakor(Request $request){
        $id = $request->id;
        $client = new Client();
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestDataDetail= $client->request('GET', $baseUrl.'/api/irtarakoraudiensi/'.$id,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );

        $dataDetail = json_decode($requestDataDetail->getBody()->getContents(), true);
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['data_detail'] = $dataDetail;
        $this->data['id'] = $id;
        $this->data['breadcrumps'] = breadcrumps_dir_hukum($request->route()->getName());
        return view('huker.hukum.rakor.edit_hukumRakor',$this->data);
    }

    public function inputhukumRakor(Request $request){
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        // dd($request->all());

        $client = new Client();
         $fileName = "";
        if ($request->file('file_upload') != ''){
           $fileName =date('Y-m-d').$Id.'-'.$request->file('file_upload')->getClientOriginalName();
           $request->file('file_upload')->storeAs('HukumRakorAudiensi', $fileName);

           // $requestfile = $client->request('PUT', $baseUrl.'/api/kerjasamamonev/'.$inputId,
           //         [
           //             'headers' =>
           //             [
           //                 'Authorization' => 'Bearer '.$token
           //             ],
           //             'form_params' => [
           //                 'file_upload' => $fileName,
           //             ]
           //         ]
           //     );
       }
        $request = $client->request('POST', $baseUrl.'/api/irtarakoraudiensi',
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' => [
                        'idpelaksana' => $request->input('idpelaksana'),
                        'jenis_kegiatan' => $request->input('jenis_kegiatan'),
                        'nomor_sprint' => $request->input('nomor_sprint'),
                        'tgl_pelaksanaan' => $request->input('tgl_pelaksanaan'),
                        'materi' => $request->input('materi'),
                        'narasumber' => $request->input('narasumber'),
                        'meta_instansi' => json_encode($request->input('meta_instansi')),
                        'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                        'lokasi_kegiatan' => $request->input('lokasi_kegiatan'),
                        'lokasi_kegiatan_kodepos' => $request->input('lokasi_kegiatan_kodepos'),
                        'file_upload' => $fileName

                    ]
                ]
            );

        $result = json_decode($request->getBody()->getContents(), true);
        $id = $result['data']['eventID'];
        // dd($result);

        return redirect('huker/dir_hukum/hukum_rakor/'.$id);
    }

    public function updatehukumRakor(Request $request){
        $baseUrl = URL::to('/');

        $token = $request->session()->get('token');

        // dd($request->all());
        $id = $request -> input('id');
        $client = new Client();
        $fileName = "";
        if ($request->file('file_upload') != ''){
           $fileName =date('Y-m-d').$id.'-'.$request->file('file_upload')->getClientOriginalName();
           $request->file('file_upload')->storeAs('HukumRakorAudiensi', $fileName);

           // $requestfile = $client->request('PUT', $baseUrl.'/api/kerjasamamonev/'.$inputId,
           //         [
           //             'headers' =>
           //             [
           //                 'Authorization' => 'Bearer '.$token
           //             ],
           //             'form_params' => [
           //                 'file_upload' => $fileName,
           //             ]
           //         ]
           //     );
       }

        $data_request = $client->request('PUT', $baseUrl.'/api/irtarakoraudiensi/'.$id,
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' => [
                        'idpelaksana' => $request->input('idpelaksana'),
                        'jenis_kegiatan' => $request->input('jenis_kegiatan'),
                        'nomor_sprint' => $request->input('nomor_sprint'),
                        'tgl_pelaksanaan' => $request->input('tgl_pelaksanaan'),
                        'materi' => $request->input('materi'),
                        'narasumber' => $request->input('narasumber'),
                        'meta_instansi' => json_encode($request->input('meta_instansi')),
                        'tgl_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))),
                        'lokasi_kegiatan' => $request->input('lokasi_kegiatan'),
                        'lokasi_kegiatan_kodepos' => $request->input('lokasi_kegiatan_kodepos'),
                        'file_upload' => $fileName
                    ]
                ]
            );
        $data_request = json_decode($data_request->getBody()->getContents(), true);
        if(($data_request['code'] == 200)&& ($data_request['status'] != "error") ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Hukum Rakor Audiensi Berhasil Diperbarui';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Hukum Rakor Audiensi Gagal Diperbarui';
        }
        return back()->with('status', $this->messages);
        // return redirect('huker/dir_hukum/hukum_rakor/');

    }


    public function hukumPendampingan(Request $request){
        //filter
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
                if( ($key == 'tgl_from') || ($key == 'tgl_to')){
                  $this->selected[$key] = date('d/m/Y',strtotime($value));
                }else if(($key == 'permasalahan') || ($key == 'nomor_perkara') || ($key == 'no_identitas')){
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
          $tgl_from = $request->tgl_from;
          $tgl_to = $request->tgl_to;
          $order = $request->order;
          $limit = $request->limit;
          $status = $request->status;

          if($tipe == 'tgl_perkara'){
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
          }else if($tipe == 'status'){
            $kondisi .= '&status='.$status;
            $this->selected['status'] = $status;
          }
          else{
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
        $datas = execute_api_json('api/hukumpendampingan?'.$limit.'&'.$offset.$kondisi,'get');

        $total_item = 0;
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;

        }else{
            $this->data['data'] = [];
            $total_item = 0;
        }

        $this->data['filter'] = $this->selected;
        $this->data['kondisi'] = $kondisi;

        //end filter
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['title'] = "Hukum Pendampingan (Litigasi)";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));

        $this->data['delete_route'] = "delete_hukum_pendampingan";
        $this->data['path'] = $request->path();
        $this->data['route_name'] = $request->route()->getName();
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;

        $this->data['route'] = $request->route()->getName();

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

        $this->data['title'] = "Hukum Pendampingan";

        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
        return view('huker.hukum.pendampingan.index_hukumPendampingan',$this->data);
    }

    public function addhukumPendampingan(Request $request){
        // $this->data['title']="Hukum dan Kerjasama";
        $client = new Client();
        $baseUrl = URL::to('/');

        $url_simpeg = config('app.url_simpeg');
        $query  =  execute_api_json($url_simpeg,"GET");
        if($query->code == 200 && ($query->status != 'error')){
          $this->data['satker'] = $query->data;
        }else{
          $this->data['satker'] = [];
        }

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $this->globalPropkab($request->session()->get('token'));

        $this->data['title'] = "Hukum Pendampingan";

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        $this->data['breadcrumps'] = breadcrumps_dir_hukum($request->route()->getName());
        return view('huker.hukum.pendampingan.add_hukumPendampingan',$this->data);
    }

    public function edithukumPendampingan(Request $request){
        // $this->data['title']="Hukum dan Kerjasama";
        $id = $request->id;
        $client = new Client();
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestDataDetail= $client->request('GET', $baseUrl.'/api/hukumpendampingan/'.$id,
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

        $url_simpeg = config('app.url_simpeg');
        $query  =  execute_api_json($url_simpeg,"GET");
        if($query->code == 200 && ($query->status != 'error')){
          $this->data['satker'] = $query->data;
        }else{
          $this->data['satker'] = [];
        }

        $satker_ids = [];

        $didampingi = json_decode($dataDetail['data']['meta_didampingi'], true);

        $satker_ids[] = $didampingi['list_satker'];

        $pendamping = json_decode($dataDetail['data']['meta_pendamping'], true);
        if(count($pendamping)>0) {
          foreach($pendamping as $p)
          {
              $satker_ids[] = $p['list_satker'];
          }
        }

        $list_pegawai = [];

        foreach($satker_ids as $satker_id)
        {
            $requestpegawai = $client->request('GET', config('app.url_soa').'simpeg/staffBySatker?unit_id=' . $satker_id);
            $pegawai = json_decode($requestpegawai->getBody()->getContents(), true);

            if(isset($pegawai['data']['pegawai']))
                $list_pegawai[$satker_id] = $pegawai['data']['pegawai'];
        }

        $this->data['pegawai'] = $list_pegawai;

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $this->globalPropkab($request->session()->get('token'));

        $this->data['title'] = "Hukum Pendampingan";

        $this->data['data_detail'] = $dataDetail['data'];
        $this->data['id'] = $id;
        $this->data['breadcrumps'] = breadcrumps_dir_hukum($request->route()->getName());
        return view('huker.hukum.pendampingan.edit_hukumPendampingan',$this->data);
    }

    public function inputhukumPendampingan(Request $request){
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        // print_r($request->except(['_token']));

        $client = new Client();

       if ($request->input('sumberanggaran')=="DIPA") {

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

        $requests = $client->request('POST', $baseUrl.'/api/hukumpendampingan',
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' =>  [
                      "tgl_sidang" => ($request->input('tgl_sidang') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_sidang')))) : '',
                      "tempatsidang" => $request->input('tempatsidang'),
                      "tempatsidang_idprovinsi" => $request->input('tempatsidang_idprovinsi'),
                      "nomor_perkara" => $request->input('nomor_perkara'),
                      "tgl_perkara" => ($request->input('tgl_perkara') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_perkara')))) : '',
                      "jenis_kasus" => $request->input('jenis_kasus'),
                      "permasalahan" => $request->input('permasalahan'),
                      "meta_didampingi" => json_encode($request->input('meta_didampingi')),
                      "meta_pendamping" => json_encode($request->input('meta_pendamping')),
                      "meta_pendamping_luar_bnn" => json_encode($request->input('meta_pendamping_luar_bnn')),
                      "sumberanggaran" => $request->input('sumberanggaran'),
                      "anggaran_id" => $anggaran,
                    ]
                ]

            );

        $result = json_decode($requests->getBody()->getContents(), true);

        $this->form_params = array("tgl_sidang" => ($request->input('tgl_sidang') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_sidang')))) : '',
        "tempatsidang" => $request->input('tempatsidang'),
        "tempatsidang_idprovinsi" => $request->input('tempatsidang_idprovinsi'),
        "nomor_perkara" => $request->input('nomor_perkara'),
        "tgl_perkara" => ($request->input('tgl_perkara') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_perkara')))) : '',
        "jenis_kasus" => $request->input('jenis_kasus'),
        "permasalahan" => $request->input('permasalahan'),
        "meta_didampingi" => json_encode($request->input('meta_didampingi')),
        "meta_pendamping" => json_encode($request->input('meta_pendamping')),
        "meta_pendamping_luar_bnn" => json_encode($request->input('meta_pendamping_luar_bnn')),
        "sumberanggaran" => $request->input('sumberanggaran'),
        "anggaran_id" => $anggaran);

        $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Hukum - Pendampingan (Litigasi)';
        $trail['audit_event'] = 'post';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $result['comment'];
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

        $id = $result['data']['eventID'];

        if(($result['code'] == 200)&& ($result['status'] != "error") ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Pembelaan Hukum (pendampingan) Berhasil Disimpan';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Pembelaan Hukum (pendampingan) Gagal Disimpan';
        }

        $this->kelengkapan_hukumPendampingan($id);

       return redirect('huker/dir_hukum/edit_hukum_pendampingan/'.$id)->with('status', $this->messages);
    }

    public function updatehukumPendampingan(Request $request){
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        // print_r($request->except(['_token']));
        $id = $request -> input('id');

        $client = new Client();

       if ($request->input('sumberanggaran')=="DIPA") {

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

        $requests = $client->request('PUT', $baseUrl.'/api/hukumpendampingan/' . $id,
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' =>  [
                      "tgl_sidang" => ($request->input('tgl_sidang') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_sidang')))) : '',
                      "tempatsidang" => $request->input('tempatsidang'),
                      "tempatsidang_idprovinsi" => $request->input('tempatsidang_idprovinsi'),
                      "nomor_perkara" => $request->input('nomor_perkara'),
                      "tgl_perkara" => ($request->input('tgl_perkara') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_perkara')))) : '',
                      "jenis_kasus" => $request->input('jenis_kasus'),
                      "permasalahan" => $request->input('permasalahan'),
                      "meta_didampingi" => json_encode($request->input('meta_didampingi')),
                      "meta_pendamping" => json_encode($request->input('meta_pendamping')),
                      "meta_pendamping_luar_bnn" => json_encode($request->input('meta_pendamping_luar_bnn')),
                      "sumberanggaran" => $request->input('sumberanggaran'),
                      "anggaran_id" => $anggaran,
                    ]
                ]

            );

        $result = json_decode($requests->getBody()->getContents(), true);

        $this->form_params = array("tgl_sidang" => ($request->input('tgl_sidang') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_sidang')))) : '',
        "tempatsidang" => $request->input('tempatsidang'),
        "tempatsidang_idprovinsi" => $request->input('tempatsidang_idprovinsi'),
        "nomor_perkara" => $request->input('nomor_perkara'),
        "tgl_perkara" => ($request->input('tgl_perkara') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_perkara')))) : '',
        "jenis_kasus" => $request->input('jenis_kasus'),
        "permasalahan" => $request->input('permasalahan'),
        "meta_didampingi" => json_encode($request->input('meta_didampingi')),
        "meta_pendamping" => json_encode($request->input('meta_pendamping')),
        "meta_pendamping_luar_bnn" => json_encode($request->input('meta_pendamping_luar_bnn')),
        "sumberanggaran" => $request->input('sumberanggaran'),
        "anggaran_id" => $anggaran);

        $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Hukum - Pendampingan (Litigasi)';
        $trail['audit_event'] = 'put';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $result['comment'];
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


        if(($result['code'] == 200)&& ($result['status'] != "error") ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Pembelaan Hukum (pendampingan) Berhasil Diperbarui';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Pembelaan Hukum (pendampingan) Gagal Diperbarui';
        }

        $this->kelengkapan_hukumPendampingan($id);

        return back()->with('status', $this->messages);

    }

    private function kelengkapan_hukumPendampingan($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('hukerhukum_pendampingan')->where('id',$id)
                  ->select('tgl_sidang','tempatsidang','tempatsidang_idprovinsi','nomor_perkara', 'tgl_perkara','jenis_kasus', 'permasalahan', 'sumberanggaran', 'meta_didampingi', 'meta_pendamping', 'meta_pendamping_luar_bnn');

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
          $kelengkapan = execute_api_json('api/hukumpendampingan/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/hukumpendampingan/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

    public function deleteHukumPendampingan(Request $request){
      $id = $request->input('id');
      if($id){
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/hukumpendampingan/'.$id,'DELETE');
            $this->form_params['delete_id'] = $id;
            $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Hukum - Pendampingan (Litigasi)';
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
        $data_request = ['status'=>'error','message'=>'Data Kasus Gagal Dihapus'];
        return $data_request;
      }
    }

    public function printPendampingan(Request $request){
        $client = new Client();
        $token = $request->session()->get('token');
        $baseUrl = URL::to('/');

        $get = $request->all();
        $kondisi = "";
        if(count($get)>0){
          foreach($get as $key=>$val){
            $kondisi .= $key.'='.$val.'&';
          }
          $kondisi = rtrim($kondisi,'&');
        }

        $requestPrintData = $client->request('GET', $baseUrl.'/api/hukumpendampingan?'.$kondisi,
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

        print_r($PrintData); exit;

        $i = 1;
        foreach ($PrintData['data'] as $key => $value) {
          $DataArray[$key]['No'] = $i;
          $DataArray[$key]['Permasalahan'] = $value['permasalahan'];
          $DataArray[$key]['No. Laporan Polisi'] = $value['nomor_perkara'];
          $DataArray[$key]['Tanggal Laporan'] = date('d-m-Y', strtotime($value['tgl_perkara']));
          $DataArray[$key]['Nomor Identitas'] = $value['no_identitas'];
          $DataArray[$key]['Status'] = ($value['status'] == 'Y') ? "Lengkap" : "Tidak Lengkap";
          $i = $i +1;
        }
         //dd($DataArray);
        $data = $DataArray;
        $name = 'Data Kegiatan Pembelaan Hukum (Pendampingan)'.Carbon::now()->format('Y-m-d H:i:s');
        $this->printData($data, $name);
    }

    public function hukumPrapradilan(Request $request){
        //filter
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
                if( ($key == 'tgl_from') || ($key == 'tgl_to')){
                  $this->selected[$key] = date('d/m/Y',strtotime($value));
                }else if(($key == 'no_permohonan') || ($key == 'permasalahan') || ($key == 'nama_pemohon')){
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
          $tgl_from = $request->tgl_from;
          $tgl_to = $request->tgl_to;
          $order = $request->order;
          $limit = $request->limit;
          $status = $request->status;

          if($tipe == 'tgl_permohonan'){
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
          }else if($tipe == 'status'){
            $kondisi .= '&status='.$status;
            $this->selected['status'] = $status;
          }
          else{
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
        $datas = execute_api_json('api/hukumpraperadilan?'.$limit.'&'.$offset.$kondisi,'get');

        $total_item = 0;
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;

        }else{
            $this->data['data'] = [];
            $total_item = 0;
        }

        $this->data['filter'] = $this->selected;
        $this->data['kondisi'] = $kondisi;

        //end filter
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['title'] = "Hukum Pra Peradilan (Litigasi)";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));

        $this->data['delete_route'] = "delete_hukum_prapradilan";
        $this->data['path'] = $request->path();
        $this->data['route_name'] = $request->route()->getName();
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;

        $this->data['route'] = $request->route()->getName();

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

        $this->data['title'] = "Hukum Pendampingan";

        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
        return view('huker.hukum.prapradilan.index_hukumPrapradilan',$this->data);
    }

    public function addhukumPrapradilan(Request $request){
        $client = new Client();
        $baseUrl = URL::to('/');

        $url_simpeg = config('app.url_simpeg');
        $query  =  execute_api_json($url_simpeg,"GET");
        if($query->code == 200 && ($query->status != 'error')){
          $this->data['satker'] = $query->data;
        }else{
          $this->data['satker'] = [];
        }

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $this->globalPropkab($request->session()->get('token'));

        $this->data['title'] = "Hukum Pra Peradilan";

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        $this->data['breadcrumps'] = breadcrumps_dir_hukum($request->route()->getName());
        return view('huker.hukum.prapradilan.add_hukumPrapradilan',$this->data);
    }

    public function edithukumPrapradilan(Request $request){
        $id = $request->id;
        $client = new Client();
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $url_simpeg = config('app.url_simpeg');
        $query  =  execute_api_json($url_simpeg,"GET");
        if($query->code == 200 && ($query->status != 'error')){
          $this->data['satker'] = $query->data;
        }else{
          $this->data['satker'] = [];
        }

        $requestDataDetail= $client->request('GET', $baseUrl.'/api/hukumpraperadilan/'.$id,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );

        $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
        $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $propkab;

        $dataDetail = json_decode($requestDataDetail->getBody()->getContents(), true);

        $satker_ids = [];

        $pelaksana = json_decode($dataDetail['data']['meta_pelaksana'], true);

        foreach($pelaksana as $p)
        {
            $satker_ids[] = $p['list_satker'];
        }

        $list_pegawai = [];

        foreach($satker_ids as $satker_id)
        {
            $requestpegawai = $client->request('GET', config('app.url_soa').'simpeg/staffBySatker?unit_id=' . $satker_id);
            $pegawai = json_decode($requestpegawai->getBody()->getContents(), true);

            if(isset($pegawai['data']['pegawai']))
                $list_pegawai[$satker_id] = $pegawai['data']['pegawai'];
        }

        $this->data['pegawai'] = $list_pegawai;

        if ($dataDetail['data']['anggaran_id'] != '') {
           $this->data['data_anggaran'] = $this->globalGetAnggaran($token, $dataDetail['data']['anggaran_id']);

        }

        $this->data['data_detail'] = $dataDetail['data'];
        $this->data['id'] = $id;
        $this->data['breadcrumps'] = breadcrumps_dir_hukum($request->route()->getName());
        return view('huker.hukum.prapradilan.edit_hukumPrapradilan',$this->data);
    }

    public function inputhukumPrapradilan(Request $request){
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        // print_r($request->except(['_token']));

        $client = new Client();

       if ($request->input('sumberanggaran')=="DIPA") {

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

        $requests = $client->request('POST', $baseUrl.'/api/hukumpraperadilan',
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' =>  [
                      "tgl_mulai" => ($request->input('tgl_mulai') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_mulai')))) : '',
                      "tgl_selesai" => ($request->input('tgl_selesai') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_selesai')))) : '',
                      "tempat_kegiatan" => $request->input('tempat_kegiatan'),
                      "lokasi_kegiatan" => $request->input('lokasi_kegiatan'),
                      "no_permohonan" => $request->input('nomor_permohonan_praperadilan'),
                      "tgl_permohonan" => ($request->input('tgl_permohonan') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_permohonan')))) : '',
                      "permasalahan" => $request->input('permasalahan'),
                      "no_perkara" => $request->input('no_perkara'),
                      "tergugat" => $request->input('tergugat'),
                      "jns_identitas_pemohon" => $request->input('jenis_identitas'),
                      "no_identitas_pemohon" => $request->input('no_identitas'),
                      "nama_pemohon" => $request->input('nama_pemohon'),
                      "tempat_lahir_pemohon" => $request->input('tempat_lahir'),
                      "tgl_lahir_pemohon" => ($request->input('tgl_lahir_pemohon') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_lahir_pemohon')))) : '',
                      "lokasi_pemohon" => $request->input('lokasi_pemohon'),
                      "alamat_pemohon" => $request->input('alamat_pemohon'),
                      "pekerjaan_pemohon" => $request->input('pekerjaan_pemohon'),
                      "meta_praperadilan" => json_encode($request->input('meta_praperadilan')),
                      "meta_pelaksana" => json_encode($request->input('meta_pelaksana')),
                      "meta_ahli_hukum" => json_encode($request->input('meta_ahli')),
                      "meta_sidang" => json_encode($request->input('meta_sidang')),
                      "sumberanggaran" => $request->input('sumberanggaran'),
                      "anggaran_id" => $anggaran,
                      "hasil_akhir" => $request->input('hasil_akhir'),
                    ]
                ]

            );

        $result = json_decode($requests->getBody()->getContents(), true);

        $this->form_params = array("tgl_mulai" => ($request->input('tgl_mulai') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_mulai')))) : '',
        "tgl_selesai" => ($request->input('tgl_selesai') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_selesai')))) : '',
        "tempat_kegiatan" => $request->input('tempat_kegiatan'),
        "lokasi_kegiatan" => $request->input('lokasi_kegiatan'),
        "no_permohonan" => $request->input('nomor_permohonan_praperadilan'),
        "tgl_permohonan" => ($request->input('tgl_permohonan') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_permohonan')))) : '',
        "permasalahan" => $request->input('permasalahan'),
        "no_perkara" => $request->input('no_perkara'),
        "tergugat" => $request->input('tergugat'),
        "jns_identitas_pemohon" => $request->input('jenis_identitas'),
        "no_identitas_pemohon" => $request->input('no_identitas'),
        "nama_pemohon" => $request->input('nama_pemohon'),
        "tempat_lahir_pemohon" => $request->input('tempat_lahir'),
        "tgl_lahir_pemohon" => ($request->input('tgl_lahir_pemohon') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_lahir_pemohon')))) : '',
        "lokasi_pemohon" => $request->input('lokasi_pemohon'),
        "alamat_pemohon" => $request->input('alamat_pemohon'),
        "pekerjaan_pemohon" => $request->input('pekerjaan_pemohon'),
        "meta_praperadilan" => json_encode($request->input('meta_praperadilan')),
        "meta_pelaksana" => json_encode($request->input('meta_pelaksana')),
        "meta_ahli_hukum" => json_encode($request->input('meta_ahli')),
        "meta_sidang" => json_encode($request->input('meta_sidang')),
        "sumberanggaran" => $request->input('sumberanggaran'),
        "anggaran_id" => $anggaran,
        "hasil_akhir" => $request->input('hasil_akhir'));

        $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Hukum - Pra Peradilan (Litigasi)';
        $trail['audit_event'] = 'post';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $result['comment'];
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

        $id = $result['data']['eventID'];

        $this->kelengkapan_hukumPrapradilan($id);

      if(($result['code'] == 200)&& ($result['status'] != "error") ){
          $this->messages['status'] = 'success';
          $this->messages['message'] = 'Data Kegiatan Pembelaan Hukum (Pra Peradilan) Berhasil Disimpan';
      }else{
          $this->messages['status'] = 'error';
          $this->messages['message'] = 'Data Kegiatan Pembelaan Hukum (Pra Peradilan) Gagal Disimpan';
      }

       return redirect('huker/dir_hukum/edit_hukum_prapradilan/'.$id)->with('status', $this->messages);
    }

    public function updatehukumPrapradilan(Request $request){
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');
      $id = $request->input('id');
      // print_r($request->except(['_token']));

      $client = new Client();

       if ($request->input('sumberanggaran')=="DIPA") {

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

      $requests = $client->request('PUT', $baseUrl.'/api/hukumpraperadilan/'.$id,
              [
                  'headers' =>
                  [
                      'Authorization' => 'Bearer '.$token
                  ],
                  'form_params' =>  [
                      "tgl_mulai" => ($request->input('tgl_mulai') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_mulai')))) : '',
                      "tgl_selesai" => ($request->input('tgl_selesai') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_selesai')))) : '',
                      "tempat_kegiatan" => $request->input('tempat_kegiatan'),
                      "lokasi_kegiatan" => $request->input('lokasi_kegiatan'),
                      "no_permohonan" => $request->input('nomor_permohonan_praperadilan'),
                      "tgl_permohonan" => ($request->input('tgl_permohonan') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_permohonan')))) : '',
                      "permasalahan" => $request->input('permasalahan'),
                      "no_perkara" => $request->input('no_perkara'),
                      "tergugat" => $request->input('tergugat'),
                      "jns_identitas_pemohon" => $request->input('jenis_identitas'),
                      "no_identitas_pemohon" => $request->input('no_identitas'),
                      "nama_pemohon" => $request->input('nama_pemohon'),
                      "tempat_lahir_pemohon" => $request->input('tempat_lahir'),
                      "tgl_lahir_pemohon" => ($request->input('tgl_lahir_pemohon') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_lahir_pemohon')))) : '',
                      "lokasi_pemohon" => $request->input('lokasi_pemohon'),
                      "alamat_pemohon" => $request->input('alamat_pemohon'),
                      "pekerjaan_pemohon" => $request->input('pekerjaan_pemohon'),
                      "meta_praperadilan" => json_encode($request->input('meta_praperadilan')),
                      "meta_pelaksana" => json_encode($request->input('meta_pelaksana')),
                      "meta_ahli_hukum" => json_encode($request->input('meta_ahli')),
                      "meta_sidang" => json_encode($request->input('meta_sidang')),
                      "sumberanggaran" => $request->input('sumberanggaran'),
                      "anggaran_id" => $anggaran,
                      "hasil_akhir" => $request->input('hasil_akhir'),
                  ]
                  ]

          );

      $result = json_decode($requests->getBody()->getContents(), true);

      $this->form_params = array("tgl_mulai" => ($request->input('tgl_mulai') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_mulai')))) : '',
      "tgl_selesai" => ($request->input('tgl_selesai') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_selesai')))) : '',
      "tempat_kegiatan" => $request->input('tempat_kegiatan'),
      "lokasi_kegiatan" => $request->input('lokasi_kegiatan'),
      "no_permohonan" => $request->input('nomor_permohonan_praperadilan'),
      "tgl_permohonan" => ($request->input('tgl_permohonan') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_permohonan')))) : '',
      "permasalahan" => $request->input('permasalahan'),
      "no_perkara" => $request->input('no_perkara'),
      "tergugat" => $request->input('tergugat'),
      "jns_identitas_pemohon" => $request->input('jenis_identitas'),
      "no_identitas_pemohon" => $request->input('no_identitas'),
      "nama_pemohon" => $request->input('nama_pemohon'),
      "tempat_lahir_pemohon" => $request->input('tempat_lahir'),
      "tgl_lahir_pemohon" => ($request->input('tgl_lahir_pemohon') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_lahir_pemohon')))) : '',
      "lokasi_pemohon" => $request->input('lokasi_pemohon'),
      "alamat_pemohon" => $request->input('alamat_pemohon'),
      "pekerjaan_pemohon" => $request->input('pekerjaan_pemohon'),
      "meta_praperadilan" => json_encode($request->input('meta_praperadilan')),
      "meta_pelaksana" => json_encode($request->input('meta_pelaksana')),
      "meta_ahli_hukum" => json_encode($request->input('meta_ahli')),
      "meta_sidang" => json_encode($request->input('meta_sidang')),
      "sumberanggaran" => $request->input('sumberanggaran'),
      "anggaran_id" => $anggaran,
      "hasil_akhir" => $request->input('hasil_akhir'));

      $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Hukum - Pra Peradilan (Litigasi)';
      $trail['audit_event'] = 'put';
      $trail['audit_value'] = json_encode($this->form_params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $result['comment'];
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($request->session()->get('token'),$trail);



      $this->kelengkapan_hukumPrapradilan($id);


      if(($result['code'] == 200)&& ($result['status'] != "error") ){
          $this->messages['status'] = 'success';
          $this->messages['message'] = 'Data Kegiatan Pembelaan Hukum (Pra Peradilan) Berhasil Diperbarui';
      }else{
          $this->messages['status'] = 'error';
          $this->messages['message'] = 'Data Kegiatan Pembelaan Hukum (Pra Peradilan) Gagal Diperbarui';
      }
      return back()->with('status', $this->messages);
        // return redirect('huker/dir_hukum/hukum_prapradilan/');

    }

    public function deleteHukumPrapradilan(Request $request){
      $id = $request->input('id');
      if($id){
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/hukumpraperadilan/'.$id,'DELETE');
            $this->form_params['delete_id'] = $id;
            $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Hukum - Pra Peradilan (Litigasi)';
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
        $data_request = ['status'=>'error','message'=>'Data Kasus Gagal Dihapus'];
        return $data_request;
      }
    }

    public function printPrapradilan(Request $request){
        $client = new Client();
        $token = $request->session()->get('token');
        $baseUrl = URL::to('/');

        $get = $request->all();
        $kondisi = "";
        if(count($get)>0){
          foreach($get as $key=>$val){
            $kondisi .= $key.'='.$val.'&';
          }
          $kondisi = rtrim($kondisi,'&');
        }

        $requestPrintData = $client->request('GET', $baseUrl.'/api/hukumpraperadilan?'.$kondisi,
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
          $DataArray[$key]['No. Permohonan Peradilan'] = $value['no_permohonan'];
          $DataArray[$key]['Tanggal Pelaksanaan'] = date('d-m-Y', strtotime($value['tgl_permohonan']));
          $DataArray[$key]['Permasalahan'] = $value['permasalahan'];
          $DataArray[$key]['Nama Pemohon'] = $value['nama_pemohon'];
          $DataArray[$key]['Status'] = ($value['status'] == 'Y') ? "Lengkap" : "Tidak Lengkap";
          $i = $i +1;
        }
         //dd($DataArray);
        $data = $DataArray;
        $name = 'Data Kegiatan Pembelaan Hukum (Pra Peradilan)'.Carbon::now()->format('Y-m-d H:i:s');
        $this->printData($data, $name);
    }

    private function kelengkapan_hukumPrapradilan($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('huker_hukumpraperadilan')->where('id',$id)
                  ->select('tgl_mulai','tgl_selesai','tempat_kegiatan','lokasi_kegiatan', 'no_permohonan','tgl_permohonan', 'permasalahan', 'no_perkara', 'tergugat', 'jns_identitas_pemohon', 'no_identitas_pemohon', 'nama_pemohon', 'tempat_lahir_pemohon', 'lokasi_pemohon', 'alamat_pemohon', 'pekerjaan_pemohon', 'meta_praperadilan', 'meta_pelaksana', 'meta_ahli_hukum', 'meta_sidang', 'hasil_akhir');
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
          $kelengkapan = execute_api_json('api/hukumpraperadilan/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/hukumpraperadilan/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

    public function hukumNonlitigasi(Request $request){
        //filter
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
                if( ($key == 'tgl_from_mulai') || ($key == 'tgl_to_mulai') || ($key == 'tgl_from_selesai') || ($key == 'tgl_to_selesai')){
                  $this->selected[$key] = date('d/m/Y',strtotime($value));
                }else if(($key == 'jenis_kegiatan') || ($key == 'tema') || ($key == 'no_sprint_kepala') || ($key == 'no_sprint_deputi')){
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
          $tgl_from_mulai = $request->tgl_from_mulai;
          $tgl_to_mulai = $request->tgl_to_mulai;
          $tgl_from_selesai = $request->tgl_from_selesai;
          $tgl_to_selesai = $request->tgl_to_selesai;
          $order = $request->order;
          $limit = $request->limit;
          $status = $request->status;

          if($tipe == 'tgl_mulai'){
            if($tgl_from_mulai){
              $date = date('Y-m-d',strtotime(str_replace('/', '-', $tgl_from_mulai)));
              $kondisi .= '&tgl_from_mulai='.$date;
              $this->selected['tgl_from_mulai'] = $tgl_from_mulai;
            }else{
                $kondisi .='';
            }
            if($tgl_to_mulai){
              $date = date('Y-m-d',strtotime(str_replace('/', '-', $tgl_to_mulai)));
              $kondisi .= '&tgl_to_mulai='.$date;
              $this->selected['tgl_to_mulai'] = $tgl_to_mulai;
            }else{
              $kondisi .='';
            }
          }
          else if($tipe == 'tgl_selesai'){
            if($tgl_from_selesai){
              $date = date('Y-m-d',strtotime(str_replace('/', '-', $tgl_from_selesai)));
              $kondisi .= '&tgl_from_selesai='.$date;
              $this->selected['tgl_from_selesai'] = $tgl_from_selesai;
            }else{
                $kondisi .='';
            }
            if($tgl_to_selesai){
              $date = date('Y-m-d',strtotime(str_replace('/', '-', $tgl_to_selesai)));
              $kondisi .= '&tgl_to_selesai='.$date;
              $this->selected['tgl_to_selesai'] = $tgl_to_selesai;
            }else{
              $kondisi .='';
            }
          }else if($tipe == 'status'){
            $kondisi .= '&status='.$status;
            $this->selected['status'] = $status;
          }
          else{
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
        $datas = execute_api_json('api/hukumnonlitigasi?'.$limit.'&'.$offset.$kondisi,'get');

        $total_item = 0;
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;

        }else{
            $this->data['data'] = [];
            $total_item = 0;
        }

        $this->data['filter'] = $this->selected;
        $this->data['kondisi'] = $kondisi;

        //end filter
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['title'] = "Hukum Non Litigasi";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));

        $this->data['delete_route'] = "delete_hukum_nonlitigasi";
        $this->data['path'] = $request->path();
        $this->data['route_name'] = $request->route()->getName();
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;

        $this->data['route'] = $request->route()->getName();

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

        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
        return view('huker.hukum.non_litigasi.index_hukumNonlitigasi',$this->data);
    }

    public function addhukumNonlitigasi(Request $request){
        // $this->data['title']="hukumnonlitigasi";
        $client = new Client();
        $baseUrl = URL::to('/');

        $propkab = $this->globalPropkab($request->session()->get('token'));

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $propkab['data'];

        $this->data['title'] = "Hukum Non Litigasi";

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        $this->data['breadcrumps'] = breadcrumps_dir_hukum($request->route()->getName());
        return view('huker.hukum.non_litigasi.add_hukumNonlitigasi',$this->data);
    }

    public function edithukumNonlitigasi(Request $request){
        $id = $request->id;
        $client = new Client();
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestDataDetail= $client->request('GET', $baseUrl.'/api/hukumnonlitigasi/'.$id,
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

        $propkab = $this->globalPropkab($request->session()->get('token'));

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $propkab['data'];

        $this->data['title'] = "Hukum Non Litigasi";

        $this->data['data_detail'] = $dataDetail['data'];
        $this->data['id'] = $id;
        $this->data['breadcrumps'] = breadcrumps_dir_hukum($request->route()->getName());
        return view('huker.hukum.non_litigasi.edit_hukumNonlitigasi',$this->data);
    }

    public function inputhukumNonlitigasi(Request $request){
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        // print_r($request->except(['_token']));

        $client = new Client();

       if ($request->input('sumberanggaran')=="DIPA") {

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

        $requests = $client->request('POST', $baseUrl.'/api/hukumnonlitigasi',
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' =>  [
                      "pelaksana" => $request->input('pelaksana'),
                      "jenis_kegiatan" => $request->input('jenis_kegiatan'),
                      "no_sprint_kepala" => $request->input('nomor_surat_perintah'),
                      "no_sprint_deputi" => $request->input('sprint_deputi'),
                      "tgl_mulai" => ($request->input('tgl_mulai') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_mulai')))) : '',
                      "tgl_selesai" => ($request->input('tgl_selesai') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_selesai')))) : '',
                      "tema" => $request->input('tema_kegiatan'),
                      "meta_narasumber" => json_encode($request->input('meta_narasumber')),
                      "meta_peserta" => json_encode($request->input('meta_peserta')),
                      "tempat_kegiatan" => $request->input('tempat_kegiatan'),
                      "lokasi_kegiatan" => $request->input('lokasi_kegiatan'),
                      "sumberanggaran" => $request->input('sumberanggaran'),
                      "anggaran_id" => $anggaran,
                    ]
                ]

            );

        $result = json_decode($requests->getBody()->getContents(), true);

        $this->form_params = array("pelaksana" => $request->input('pelaksana'),
        "jenis_kegiatan" => $request->input('jenis_kegiatan'),
        "no_sprint_kepala" => $request->input('nomor_surat_perintah'),
        "no_sprint_deputi" => $request->input('sprint_deputi'),
        "tgl_mulai" => ($request->input('tgl_mulai') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_mulai')))) : '',
        "tgl_selesai" => ($request->input('tgl_selesai') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_selesai')))) : '',
        "tema" => $request->input('tema_kegiatan'),
        "meta_narasumber" => json_encode($request->input('meta_narasumber')),
        "meta_peserta" => json_encode($request->input('meta_peserta')),
        "tempat_kegiatan" => $request->input('tempat_kegiatan'),
        "lokasi_kegiatan" => $request->input('lokasi_kegiatan'),
        "sumberanggaran" => $request->input('sumberanggaran'),
        "anggaran_id" => $anggaran);

        $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Hukum - Non Litigasi (Konsultasi)';
        $trail['audit_event'] = 'post';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $result['comment'];
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

        $id = $result['data']['eventID'];

        if ($request->file('file_laporan') != ''){
            $fileName = $id.'-'.$request->file('file_laporan')->getClientOriginalName();
            $request->file('file_laporan')->storeAs('HukumNonlitigasi', $fileName);

            $requestfile = $client->request('PUT', $baseUrl.'/api/hukumnonlitigasi/'.$id,
                    [
                        'headers' =>
                        [
                            'Authorization' => 'Bearer '.$token
                        ],
                        'form_params' => [
                            'hasil_dicapai' => $fileName,
                        ]
                    ]
                );
         }

         $this->kelengkapan_hukumNonlitigasi($id);

        if(($result['code'] == 200)&& ($result['status'] != "error") ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Kegiatan Konsultasi Hukum (Non Litigasi) Berhasil Disimpan';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Kegiatan Konsultasi Hukum (Non Litigasi) Gagal Disimpan';
        }

       return redirect('huker/dir_hukum/edit_hukum_nonlitigasi/'.$id)->with('status', $this->messages);
    }

    public function updatehukumNonlitigasi(Request $request){
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');
      $id = $request->input('id');
      // print_r($request->except(['_token']));

      $client = new Client();


       if ($request->input('sumberanggaran')=="DIPA") {

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

      $requests = $client->request('PUT', $baseUrl.'/api/hukumnonlitigasi/'.$id,
              [
                  'headers' =>
                  [
                      'Authorization' => 'Bearer '.$token
                  ],
                  'form_params' =>  [
                    "pelaksana" => $request->input('pelaksana'),
                    "jenis_kegiatan" => $request->input('jenis_kegiatan'),
                    "no_sprint_kepala" => $request->input('nomor_surat_perintah'),
                    "no_sprint_deputi" => $request->input('sprint_deputi'),
                    "tgl_mulai" => ($request->input('tgl_mulai') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_mulai')))) : '',
                    "tgl_selesai" => ($request->input('tgl_selesai') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_selesai')))) : '',
                    "tema" => $request->input('tema_kegiatan'),
                    "meta_narasumber" => json_encode($request->input('meta_narasumber')),
                    "meta_peserta" => json_encode($request->input('meta_peserta')),
                    "tempat_kegiatan" => $request->input('tempat_kegiatan'),
                    "lokasi_kegiatan" => $request->input('lokasi_kegiatan'),
                    "sumberanggaran" => $request->input('sumberanggaran'),
                    "anggaran_id" => $anggaran,
                  ]
                  ]

          );

      $result = json_decode($requests->getBody()->getContents(), true);

      $this->form_params = array("pelaksana" => $request->input('pelaksana'),
      "jenis_kegiatan" => $request->input('jenis_kegiatan'),
      "no_sprint_kepala" => $request->input('nomor_surat_perintah'),
      "no_sprint_deputi" => $request->input('sprint_deputi'),
      "tgl_mulai" => ($request->input('tgl_mulai') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_mulai')))) : '',
      "tgl_selesai" => ($request->input('tgl_selesai') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_selesai')))) : '',
      "tema" => $request->input('tema_kegiatan'),
      "meta_narasumber" => json_encode($request->input('meta_narasumber')),
      "meta_peserta" => json_encode($request->input('meta_peserta')),
      "tempat_kegiatan" => $request->input('tempat_kegiatan'),
      "lokasi_kegiatan" => $request->input('lokasi_kegiatan'),
      "sumberanggaran" => $request->input('sumberanggaran'),
      "anggaran_id" => $anggaran);

      $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Hukum - Non Litigasi (Konsultasi)';
      $trail['audit_event'] = 'put';
      $trail['audit_value'] = json_encode($this->form_params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $result['comment'];
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

      // echo '<br/>';
      // dd($result);
      if ($request->file('file_laporan') != ''){
          $fileName = $id.'-'.$request->file('file_laporan')->getClientOriginalName();
          $request->file('file_laporan')->storeAs('HukumNonlitigasi', $fileName);

          $requestfile = $client->request('PUT', $baseUrl.'/api/hukumnonlitigasi/'.$id,
                  [
                      'headers' =>
                      [
                          'Authorization' => 'Bearer '.$token
                      ],
                      'form_params' => [
                          'hasil_dicapai' => $fileName,
                      ]
                  ]
              );
       }


        if(($result['code'] == 200)&& ($result['status'] != "error") ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Kegiatan Konsultasi Hukum (Non Litigasi) Berhasil Diperbarui';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Kegiatan Konsultasi Hukum (Non Litigasi) Gagal Diperbarui';
        }

        $this->kelengkapan_hukumNonlitigasi($id);

        return back()->with('status', $this->messages);
        // return redirect('huker/dir_hukum/hukum_nonlitigasi/');

    }

    public function deleteHukumNonlitigasi(Request $request){
      $id = $request->input('id');
      if($id){
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/hukumnonlitigasi/'.$id,'DELETE');
            $this->form_params['delete_id'] = $id;
            $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Hukum - Non Litigasi (Konsultasi)';
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
        $data_request = ['status'=>'error','message'=>'Data Kasus Gagal Dihapus'];
        return $data_request;
      }
    }

    private function kelengkapan_hukumNonlitigasi($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('huker_hukumnonlitigasi')->where('id',$id)
                  ->select('pelaksana','jenis_kegiatan','no_sprint_kepala','no_sprint_deputi', 'tgl_mulai','tgl_selesai', 'tema', 'meta_narasumber', 'meta_peserta', 'tempat_kegiatan', 'lokasi_kegiatan', 'sumberanggaran', 'hasil_dicapai');
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
          $kelengkapan = execute_api_json('api/hukumnonlitigasi/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/hukumnonlitigasi/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

    public function printNonlitigasi(Request $request){
        $client = new Client();
        $token = $request->session()->get('token');
        $baseUrl = URL::to('/');

        $get = $request->all();
        $kondisi = "";
        if(count($get)>0){
          foreach($get as $key=>$val){
            $kondisi .= $key.'='.$val.'&';
          }
          $kondisi = rtrim($kondisi,'&');
        }

        $requestPrintData = $client->request('GET', $baseUrl.'/api/hukumnonlitigasi?'.$kondisi,
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
          $DataArray[$key]['Jenis Kegiatan'] = $value['jenis_kegiatan'];
          $DataArray[$key]['Tema'] = $value['tema'];
          $DataArray[$key]['No Sprint Kepala'] = $value['no_sprint_kepala'];
          $DataArray[$key]['No Sprint Deputi'] = $value['no_sprint_deputi'];
          $DataArray[$key]['Tanggal Mulai'] = date('d-m-Y', strtotime($value['tgl_mulai']));
          $DataArray[$key]['Tanggal Selesai'] = date('d-m-Y', strtotime($value['tgl_selesai']));
          $DataArray[$key]['Status'] = ($value['status'] == 'Y') ? "Lengkap" : "Tidak Lengkap";
          $i = $i +1;
        }
         //dd($DataArray);
        $data = $DataArray;
        $name = 'Data Kegiatan Konsultasi Hukum (Non Litigasi) '.Carbon::now()->format('Y-m-d H:i:s');
        $this->printData($data, $name);
    }

    public function hukumAudiensi(Request $request){
        //filter
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
                if(($key == 'tempat_kegiatan') || ($key == 'no_sprint_kepala') || ($key == 'no_sprint_deputi')){
                    $this->selected[$key] = $value;
                    $this->selected['keyword'] = $value;
                }else if($key == 'pelaksana'){
                    $this->selected[$key] = $value;
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
          $order = $request->order;
          $limit = $request->limit;
          $status = $request->status;
          $pelaksana = $request->pelaksana;

          if($tipe == 'status'){
            $kondisi .= '&status='.$status;
            $this->selected['status'] = $status;
          }
          else if($tipe == 'pelaksana'){
            $kondisi .= '&pelaksana='.$pelaksana;
            $this->selected['pelaksana'] = $pelaksana;
          }
          else{
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
        $datas = execute_api_json('api/hukumaudiensi?'.$limit.'&'.$offset.$kondisi,'get');

        $total_item = 0;
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;

        }else{
            $this->data['data'] = [];
            $total_item = 0;
        }

        $this->data['filter'] = $this->selected;
        $this->data['kondisi'] = $kondisi;

        //end filter
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['title'] = "Hukum Audiensi";
        $this->data['instansi'] = $this->globalinstansi('', $request->session()->get('token'));

        //Display nama instansi di tabel - Tommy

        $nm_instansi = array();

        foreach($this->data['instansi'] as $i)
            $nm_instansi[$i['id_instansi']] = $i['nm_instansi'];

        foreach($this->data['data'] as $data)
        {
            $data->nm_instansi = $nm_instansi[$data->pelaksana];
        }

        $this->data['nm_instansi'] = $nm_instansi;//untuk filter

        $this->data['delete_route'] = "delete_hukum_audiensi";
        $this->data['path'] = $request->path();
        $this->data['route_name'] = $request->route()->getName();
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;

        $this->data['route'] = $request->route()->getName();

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

        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
        return view('huker.hukum.audiensi.index_hukumAudiensi',$this->data);
    }

    public function addhukumAudiensi(Request $request){
        // $this->data['title']="hukumnonlitigasi";
        $client = new Client();
        $baseUrl = URL::to('/');

        $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
        $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $propkab;

        $this->data['title'] = "Hukum Audiensi";

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        $this->data['breadcrumps'] = breadcrumps_dir_hukum($request->route()->getName());
        return view('huker.hukum.audiensi.add_hukumAudiensi',$this->data);
    }

    public function edithukumAudiensi(Request $request){
        $id = $request->id;
        $client = new Client();
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestDataDetail= $client->request('GET', $baseUrl.'/api/hukumaudiensi/'.$id,
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

        $propkab = $this->globalPropkab($request->session()->get('token'));

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $propkab['data'];

        $this->data['title'] = "Hukum Audiensi";

        $this->data['data_detail'] = $dataDetail['data'];
        $this->data['id'] = $id;
        $this->data['breadcrumps'] = breadcrumps_dir_hukum($request->route()->getName());
        return view('huker.hukum.audiensi.edit_hukumAudiensi',$this->data);
    }

    public function inputhukumAudiensi(Request $request){
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        // print_r($request->except(['_token']));

        $client = new Client();

       if ($request->input('sumberanggaran')=="DIPA") {

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

        $requests = $client->request('POST', $baseUrl.'/api/hukumaudiensi',
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' =>  [
                      "pelaksana" => $request->input('pelaksana'),
                      "no_sprint_kepala" => $request->input('no_sprint_kepala'),
                      "no_sprint_deputi" => $request->input('no_sprint_deputi'),
                      "tgl_mulai" => ($request->input('tgl_mulai')) ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_mulai')))) : '',
                      "tgl_selesai" => $request->input('tgl_selesai') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_selesai')))) : '',
                      "meta_peserta" => json_encode($request->input('meta_peserta')),
                      "tempat_kegiatan" => $request->input('tempat_kegiatan'),
                      "lokasi_kegiatan_idkabkota" => $request->input('lokasi_kegiatan_idkabkota'),
                      "sumberanggaran" => $request->input('sumberanggaran'),
                      "anggaran_id" => $anggaran,
                    ]
                ]

            );

        $result = json_decode($requests->getBody()->getContents(), true);

        $this->form_params = array("pelaksana" => $request->input('pelaksana'),
        "no_sprint_kepala" => $request->input('no_sprint_kepala'),
        "no_sprint_deputi" => $request->input('no_sprint_deputi'),
        "tgl_mulai" => ($request->input('tgl_mulai')) ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_mulai')))) : '',
        "tgl_selesai" => $request->input('tgl_selesai') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_selesai')))) : '',
        "meta_peserta" => json_encode($request->input('meta_peserta')),
        "tempat_kegiatan" => $request->input('tempat_kegiatan'),
        "lokasi_kegiatan_idkabkota" => $request->input('lokasi_kegiatan_idkabkota'),
        "sumberanggaran" => $request->input('sumberanggaran'),
        "anggaran_id" => $anggaran);

        $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Hukum - Audiensi (Konsultasi)';
        $trail['audit_event'] = 'post';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $result['comment'];
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


        $id = $result['data']['eventID'];

        if ($request->file('hasil_dicapai') != ''){
            $fileName = $id.'-'.$request->file('hasil_dicapai')->getClientOriginalName();
            $request->file('hasil_dicapai')->storeAs('HukumAudiensi', $fileName);

            $requestfile = $client->request('PUT', $baseUrl.'/api/hukumaudiensi/'.$id,
                    [
                        'headers' =>
                        [
                            'Authorization' => 'Bearer '.$token
                        ],
                        'form_params' => [
                            'hasil_dicapai' => $fileName,
                        ]
                    ]
                );
         }

        if(($result['code'] == 200)&& ($result['status'] != "error") ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Kegiatan Konsultasi Hukum (Audiensi) Berhasil Disimpan';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Kegiatan Konsultasi Hukum (Audiensi) Gagal Disimpan';
        }

        $this->kelengkapan_hukumAudiensi($id);

       return redirect('huker/dir_hukum/edit_hukum_audiensi/'.$id)->with('status', $this->messages);
    }

    public function updatehukumAudiensi(Request $request){
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');
      $id = $request->input('id');
      // print_r($request->except(['_token']));

      $client = new Client();

      if ($request->input('sumberanggaran')=="DIPA") {

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

      $requests = $client->request('PUT', $baseUrl.'/api/hukumaudiensi/'.$id,
              [
                  'headers' =>
                  [
                      'Authorization' => 'Bearer '.$token
                  ],
                    'form_params' =>  [
                      "pelaksana" => $request->input('pelaksana'),
                      "no_sprint_kepala" => $request->input('no_sprint_kepala'),
                      "no_sprint_deputi" => $request->input('no_sprint_deputi'),
                      "tgl_mulai" => ($request->input('tgl_mulai')) ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_mulai')))) : '',
                      "tgl_selesai" => $request->input('tgl_selesai') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_selesai')))) : '',
                      "meta_peserta" => json_encode($request->input('meta_peserta')),
                      "tempat_kegiatan" => $request->input('tempat_kegiatan'),
                      "lokasi_kegiatan_idkabkota" => $request->input('lokasi_kegiatan_idkabkota'),
                      "anggaran_id" => $anggaran,
                    ]
                  ]

          );

      $result = json_decode($requests->getBody()->getContents(), true);

      $this->form_params = array("pelaksana" => $request->input('pelaksana'),
      "no_sprint_kepala" => $request->input('no_sprint_kepala'),
      "no_sprint_deputi" => $request->input('no_sprint_deputi'),
      "tgl_mulai" => ($request->input('tgl_mulai')) ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_mulai')))) : '',
      "tgl_selesai" => $request->input('tgl_selesai') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_selesai')))) : '',
      "meta_peserta" => json_encode($request->input('meta_peserta')),
      "tempat_kegiatan" => $request->input('tempat_kegiatan'),
      "lokasi_kegiatan_idkabkota" => $request->input('lokasi_kegiatan_idkabkota'),
      "anggaran_id" => $anggaran);

      $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Hukum - Audiensi (Konsultasi)';
      $trail['audit_event'] = 'put';
      $trail['audit_value'] = json_encode($this->form_params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $result['comment'];
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

      // echo '<br/>';
      // dd($result);
      if ($request->file('hasil_dicapai') != ''){
          $fileName = $id.'-'.$request->file('hasil_dicapai')->getClientOriginalName();
          $request->file('hasil_dicapai')->storeAs('HukumAudiensi', $fileName);

          $requestfile = $client->request('PUT', $baseUrl.'/api/hukumaudiensi/'.$id,
                  [
                      'headers' =>
                      [
                          'Authorization' => 'Bearer '.$token
                      ],
                      'form_params' => [
                          'hasil_dicapai' => $fileName,
                      ]
                  ]
              );
       }


        if(($result['code'] == 200)&& ($result['status'] != "error") ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Kegiatan Konsultasi Hukum (Audiensi) Berhasil Diperbarui';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Kegiatan Konsultasi Hukum (Audiensi) Gagal Diperbarui';
        }

        $this->kelengkapan_hukumAudiensi($id);

        return back()->with('status', $this->messages);
        // return redirect('huker/dir_hukum/hukum_audiensi/');

    }

    public function deleteHukumAudiensi(Request $request){
      $id = $request->input('id');
      if($id){
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/hukumaudiensi/'.$id,'DELETE');
            $this->form_params['delete_id'] = $id;
            $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Hukum - Audiensi (Konsultasi)';
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
        $data_request = ['status'=>'error','message'=>'Data Kegiatan Konsultasi Hukum (Audiensi) Gagal Dihapus'];
        return $data_request;
      }
    }

    private function kelengkapan_hukumAudiensi($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('huker_hukumaudiensi')->where('id',$id)
                  ->select('pelaksana','no_sprint_kepala','no_sprint_deputi', 'tgl_mulai','tgl_selesai', 'meta_peserta', 'tempat_kegiatan', 'lokasi_kegiatan_idkabkota', 'sumberanggaran');
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
          $kelengkapan = execute_api_json('api/hukumaudiensi/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/hukumaudiensi/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

    public function printAudiensi(Request $request){
        $client = new Client();
        $token = $request->session()->get('token');
        $baseUrl = URL::to('/');

        $get = $request->all();
        $kondisi = "";
        if(count($get)>0){
          foreach($get as $key=>$val){
            $kondisi .= $key.'='.$val.'&';
          }
          $kondisi = rtrim($kondisi,'&');
        }

        $requestPrintData = $client->request('GET', $baseUrl.'/api/hukumaudiensi?'.$kondisi,
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

        $instansi = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));

        //Display nama instansi di tabel - Tommy

        $nm_instansi = array();

        foreach($instansi as $i)
            $nm_instansi[$i['id_instansi']] = $i['nm_instansi'];

        $i = 1;
        foreach ($PrintData['data'] as $key => $value) {
          $DataArray[$key]['No'] = $i;
          $DataArray[$key]['Pelaksana'] = $nm_instansi[$value['pelaksana']];
          $DataArray[$key]['No Surat Perintah BNN'] = $value['no_sprint_kepala'];
          $DataArray[$key]['No Surat Perintah Deputi'] = $value['no_sprint_deputi'];
          $DataArray[$key]['Tempat Kegiatan'] = $value['tempat_kegiatan'];
          $DataArray[$key]['Status'] = ($value['status'] == 'Y') ? "Lengkap" : "Tidak Lengkap";
          $i = $i +1;
        }
         //dd($DataArray);
        $data = $DataArray;
        $name = 'Data Kegiatan Konsultasi Hukum (Audiensi) '.Carbon::now()->format('Y-m-d H:i:s');
        $this->printData($data, $name);
    }

    public function hukumPerka(Request $request){
        //filter
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
                if(($key == 'nama_perka') || ($key == 'no_sprint') || ($key == 'satker_inisiasi')){
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
          $tgl_from = $request->tgl_from;
          $tgl_to = $request->tgl_to;
          $order = $request->order;
          $limit = $request->limit;
          $status = $request->status;
          $bagian = $request->bagian;

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
          }
          else if($tipe == 'status'){
            $kondisi .= '&status='.$status;
            $this->selected['status'] = $status;
          }
           else if($tipe == 'bagian'){
            $kondisi .= '&bagian='.$bagian;
            $this->selected['bagian'] = $bagian;
          }
          else if($tipe == 'pelaksana'){
            $kondisi .= '&pelaksana='.$pelaksana;
            $this->selected['pelaksana'] = $pelaksana;
          }
          else{
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
        $datas = execute_api_json('api/hukumperka?'.$limit.'&'.$offset.$kondisi,'get');

        $total_item = 0;
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;

        }else{
            $this->data['data'] = [];
            $total_item = 0;
        }

        $this->data['filter'] = $this->selected;
        $this->data['kondisi'] = $kondisi;

        //end filter
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['title'] = "Hukum Perka";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));

        $this->data['delete_route'] = "delete_hukum_perka";
        $this->data['path'] = $request->path();
        $this->data['route_name'] = $request->route()->getName();
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;

        $this->data['route'] = $request->route()->getName();

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

        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
        return view('huker.hukum.perka.index_hukumPerka',$this->data);
    }

    public function addhukumPerka(Request $request){
        // $this->data['title']="Pencegahan";
        $client = new Client();
        $baseUrl = URL::to('/');

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        $this->data['breadcrumps'] = breadcrumps_dir_hukum($request->route()->getName());
        return view('huker.hukum.perka.add_hukumPerka',$this->data);
    }

    public function edithukumPerka(Request $request){
        $id = $request->id;
        $client = new Client();
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestDataDetail = $client->request('GET', $baseUrl.'/api/hukumperka/'.$id,
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

        $propkab = $this->globalPropkab($request->session()->get('token'));

        $draftAwal = [];

        $requestDraftAwal = $client->request('GET', $baseUrl.'/api/getperkadraftawal/'.$id,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
        $draftAwal = json_decode($requestDraftAwal->getBody()->getContents(), true);

        if($draftAwal['code'] == 200){
            $this->data['draft_awal'] = $draftAwal;

        }else{
            $this->data['draft_awal'] = [];
        }

        $rapatPenetapan = [];

        $requestPenetapan = $client->request('GET', $baseUrl.'/api/getperkapenetapan/'.$id,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );

        $rapatPenetapan = json_decode($requestPenetapan->getBody()->getContents(), true);

        $this->data['penetapan'] = $rapatPenetapan['data'];

        if($rapatPenetapan['code'] == 200){
            $this->data['penetapan'] = $rapatPenetapan['data'];

        }else{
            $this->data['penetapan'] = [];
        }

        $harmonisasi = [];

        $requestHarmonisasi = $client->request('GET', $baseUrl.'/api/getperkaharmonisasi/'.$id,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
        $harmonisasi = json_decode($requestHarmonisasi->getBody()->getContents(), true);

        if($harmonisasi['code'] == 200){
            $this->data['harmonisasi'] = $harmonisasi;

        }else{
            $this->data['harmonisasi'] = [];
        }

        $finalisasi = [];

        $requestFinalisasi = $client->request('GET', $baseUrl.'/api/getperkafinalisasi/'.$id,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );

        $finalisasi = json_decode($requestFinalisasi->getBody()->getContents(), true);

        if($finalisasi['code'] == 200){
            $this->data['finalisasi'] = $finalisasi;

        }else{
            $this->data['finalisasi'] = [];
        }

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $propkab['data'];

        $this->data['title'] = "Hukum Perka";

        $this->data['data_detail'] = $dataDetail['data'];
        $this->data['id'] = $id;
        $this->data['breadcrumps'] = breadcrumps_dir_hukum($request->route()->getName());
        return view('huker.hukum.perka.edit_hukumPerka',$this->data);
    }

    public function inputhukumPerka(Request $request){
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        // print_r($request->except(['_token']));

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

        $requests = $client->request('POST', $baseUrl.'/api/hukumperka',
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' =>  [
                      "nama_perka" => $request->input('nama_perka'),
                      "bagian" => $request->input('bagian'),
                      "no_sprint" => $request->input('no_sprint'),
                      "tgl_mulai" => ($request->input('tgl_mulai') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_mulai')))) : '',
                      "tgl_selesai" => ($request->input('tgl_selesai') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_selesai')))) : '',
                      "satker_inisiasi" => $request->input('satker_inisiasi'),
                      "sumberanggaran" => $request->input('kodesumberanggaran'),
                      "anggaran_id" => $anggaran,
                    ]
                ]

            );

        $result = json_decode($requests->getBody()->getContents(), true);

        $this->form_params = array("nama_perka" => $request->input('nama_perka'),
        "bagian" => $request->input('bagian'),
        "no_sprint" => $request->input('no_sprint'),
        "tgl_mulai" => ($request->input('tgl_mulai') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_mulai')))) : '',
        "tgl_selesai" => ($request->input('tgl_selesai') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_selesai')))) : '',
        "satker_inisiasi" => $request->input('satker_inisiasi'),
        "sumberanggaran" => $request->input('kodesumberanggaran'),
        "anggaran_id" => $anggaran);

        $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Hukum - Penyusunan Perka';
        $trail['audit_event'] = 'post';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $result['comment'];
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


        $id = $result['data']['eventID'];

        if ($request->file('hasil_dicapai') != ''){
            $fileName = $id.'-'.$request->file('hasil_dicapai')->getClientOriginalName();
            $request->file('hasil_dicapai')->storeAs('HukumPerka', $fileName);

            $requestfile = $client->request('PUT', $baseUrl.'/api/hukumperka/'.$id,
                    [
                        'headers' =>
                        [
                            'Authorization' => 'Bearer '.$token
                        ],
                        'form_params' => [
                            'hasil_dicapai' => $fileName,
                        ]
                    ]
                );
         }

         $this->kelengkapan_hukumPerka($id);

        if(($result['code'] == 200)&& ($result['status'] != "error") ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Kegiatan Pembentukan Perka BNN Berhasil Disimpan';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Kegiatan Pembentukan Perka BNN Gagal Disimpan';
        }

       return redirect('huker/dir_hukum/edit_hukum_perka/'.$id)->with('status', $this->messages);
    }

    public function updatehukumPerka(Request $request){
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');
      $id = $request->input('id');
      // print_r($request->except(['_token']));

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

      $requests = $client->request('PUT', $baseUrl.'/api/hukumperka/'.$id,
              [
                  'headers' =>
                  [
                      'Authorization' => 'Bearer '.$token
                  ],
                    'form_params' =>  [
                      "nama_perka" => $request->input('nama_perka'),
                      "bagian" => $request->input('bagian'),
                      "no_sprint" => $request->input('no_sprint'),
                      "tgl_mulai" => ($request->input('tgl_mulai') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_mulai')))) : '',
                      "tgl_selesai" => ($request->input('tgl_selesai') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_selesai')))) : '',
                      "satker_inisiasi" => $request->input('satker_inisiasi'),
                      "tgl_ttd_kepala" => ($request->input('tgl_ttd_kepala') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_ttd_kepala')))) : '',
                      "tgl_penomoran" => ($request->input('tgl_penomoran') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_penomoran')))) : '',
                      "tgl_diundang" => ($request->input('tgl_diundang') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_diundang')))) : '',
                      "sumberanggaran" => $request->input('kodesumberanggaran'),
                      "anggaran_id" => $anggaran,

                    ]
                  ]

          );

      $result = json_decode($requests->getBody()->getContents(), true);

      $this->form_params = array("nama_perka" => $request->input('nama_perka'),
      "bagian" => $request->input('bagian'),
      "no_sprint" => $request->input('no_sprint'),
      "tgl_mulai" => ($request->input('tgl_mulai') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_mulai')))) : '',
      "tgl_selesai" => ($request->input('tgl_selesai') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_selesai')))) : '',
      "satker_inisiasi" => $request->input('satker_inisiasi'),
      "tgl_ttd_kepala" => ($request->input('tgl_ttd_kepala') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_ttd_kepala')))) : '',
      "tgl_penomoran" => ($request->input('tgl_penomoran') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_penomoran')))) : '',
      "tgl_diundang" => ($request->input('tgl_diundang') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_diundang')))) : '',
      "sumberanggaran" => $request->input('kodesumberanggaran'),
      "anggaran_id" => $anggaran);

      $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Hukum - Penyusunan Perka';
      $trail['audit_event'] = 'put';
      $trail['audit_value'] = json_encode($this->form_params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $result['comment'];
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


      if ($request->file('hasil_dicapai') != ''){
          $fileName = $id.'-'.$request->file('hasil_dicapai')->getClientOriginalName();
          $request->file('hasil_dicapai')->storeAs('HukumPerka', $fileName);

          $requestfile = $client->request('PUT', $baseUrl.'/api/hukumperka/'.$id,
                  [
                      'headers' =>
                      [
                          'Authorization' => 'Bearer '.$token
                      ],
                      'form_params' => [
                          'hasil_dicapai' => $fileName,
                      ]
                  ]
              );
       }

        $this->kelengkapan_hukumPerka($id);

        if(($result['code'] == 200)&& ($result['status'] != "error") ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Kegiatan Pembentukan Perka BNN Berhasil Diperbarui';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Kegiatan Pembentukan Perka BNN Gagal Diperbarui';
        }

        return back()->with('status', $this->messages);
        // return redirect('huker/dir_hukum/hukum_perka/');

    }

    public function deletehukumPerka(Request $request){
      $id = $request->input('id');
      if($id){
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/hukumperka/'.$id,'DELETE');
            $this->form_params['delete_id'] = $id;
            $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Hukum - Penyusunan Perka';
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
        $data_request = ['status'=>'error','message'=>'Data Kegiatan Pembentukan Perka BNN Gagal Dihapus'];
        return $data_request;
      }
    }

    private function kelengkapan_hukumPerka($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('huker_hukumpembentukanperka')->where('id',$id)
                  ->select('nama_perka','no_sprint','tgl_mulai', 'tgl_selesai','satker_inisiasi', 'bagian');
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
          $kelengkapan = execute_api_json('api/hukumperka/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/hukumperka/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

    public function printPerka(Request $request){
        $client = new Client();
        $token = $request->session()->get('token');
        $baseUrl = URL::to('/');

        $get = $request->all();
        $kondisi = "";
        if(count($get)>0){
          foreach($get as $key=>$val){
            $kondisi .= $key.'='.$val.'&';
          }
          $kondisi = rtrim($kondisi,'&');
        }

        $requestPrintData = $client->request('GET', $baseUrl.'/api/hukumperka?'.$kondisi,
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

        $instansi = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));

        $i = 1;
        foreach ($PrintData['data'] as $key => $value) {
          $DataArray[$key]['No'] = $i;
          $DataArray[$key]['Nama Perka'] = $value['nama_perka'];
          $DataArray[$key]['Bagian'] = (trim($value['bagian']) == 'Penelahaan') ? 'Penelahaan' : ((trim($value['bagian']) == 'Perancangan') ? 'Perancangan' : '') ;
          $DataArray[$key]['Nomor Surat Perintah'] = $value['no_sprint'];
          $DataArray[$key]['Satker Inisiasi'] = $value['satker_inisiasi'];
          $DataArray[$key]['Tanggal Pelaksanaan'] = date('d-m-Y', strtotime($value['tgl_mulai'])) . ' - ' . date('d-m-Y', strtotime($value['tgl_selesai']));
          $DataArray[$key]['Status'] = ($value['status'] == 'Y') ? "Lengkap" : "Tidak Lengkap";
          $i = $i +1;
        }
         //dd($DataArray);
        $data = $DataArray;
        $name = 'Data Kegiatan Pembentukan Perka BNN '.Carbon::now()->format('Y-m-d H:i:s');
        $this->printData($data, $name);
    }

    public function inputPerkaFinalisasi(Request $request){
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $client = new Client();

        $id_perka = $request->input('id_perka');

        $requestData = $client->request('POST', $baseUrl.'/api/perkafinalisasi',
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' => [
                      "id_perka" => $id_perka,
                      "tanggal" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal')))),
                      "no_sprint_peserta" => $request->input('no_sprint_peserta'),
                      "no_sgas_peserta" => $request->input('no_sgas_peserta'),
                      "no_sprint_nasum" => $request->input('no_sprint_nasum'),
                      "meta_narasumber" => json_encode($request->input('meta_narsum_materi')),
                      "meta_peserta" => json_encode($request->input('meta_peserta')),
                    ]
                ]
            );

        $response = json_decode($requestData->getBody()->getContents(), true);

        $this->form_params = array("id_perka" => $id_perka,
        "tanggal" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal')))),
        "no_sprint_peserta" => $request->input('no_sprint_peserta'),
        "no_sgas_peserta" => $request->input('no_sgas_peserta'),
        "no_sprint_nasum" => $request->input('no_sprint_nasum'),
        "meta_narasumber" => json_encode($request->input('meta_narsum_materi')),
        "meta_peserta" => json_encode($request->input('meta_peserta')));

        $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Hukum - Perka Finalisasi';
        $trail['audit_event'] = 'post';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $response['comment'];
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


        $id = $response['data']['eventID'];

      if ($request->file('hasil_dicapai') != ''){
          $fileName = $id.'-'.$request->file('hasil_dicapai')->getClientOriginalName();
          $request->file('hasil_dicapai')->storeAs('HukumPerkaFinalisasi', $fileName);

          $requestfile = $client->request('PUT', $baseUrl.'/api/perkafinalisasi/'.$id,
                  [
                      'headers' =>
                      [
                          'Authorization' => 'Bearer '.$token
                      ],
                      'form_params' => [
                          'laporan' => $fileName,
                      ]
                  ]
              );
       }
        return redirect('huker/dir_hukum/edit_hukum_perka/'.$id_perka);
    }

    public function updatePerkaFinalisasi(Request $request){
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $client = new Client();

        $id = $request->input('finalisasi_id');

        $id_perka = $request->input('id_perka');

        $requestData = $client->request('PUT', $baseUrl.'/api/perkafinalisasi/' . $id,
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' => [
                      "tanggal" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal')))),
                      "no_sprint_peserta" => $request->input('no_sprint_peserta'),
                      "no_sgas_peserta" => $request->input('no_sgas_peserta'),
                      "no_sprint_nasum" => $request->input('no_sprint_nasum'),
                      "meta_narasumber" => json_encode($request->input('meta_narsum_materi')),
                      "meta_peserta" => json_encode($request->input('meta_peserta')),
                    ]
                ]
            );

        $response = json_decode($requestData->getBody()->getContents(), true);

        $this->form_params = array("tanggal" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal')))),
        "no_sprint_peserta" => $request->input('no_sprint_peserta'),
        "no_sgas_peserta" => $request->input('no_sgas_peserta'),
        "no_sprint_nasum" => $request->input('no_sprint_nasum'),
        "meta_narasumber" => json_encode($request->input('meta_narsum_materi')),
        "meta_peserta" => json_encode($request->input('meta_peserta')));

        $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Hukum - Perka Finalisasi';
        $trail['audit_event'] = 'put';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $response['comment'];
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


      if ($request->file('hasil_dicapai') != ''){
          $fileName = $id.'-'.$request->file('hasil_dicapai')->getClientOriginalName();
          $request->file('hasil_dicapai')->storeAs('HukumPerkaFinalisasi', $fileName);

          $requestfile = $client->request('PUT', $baseUrl.'/api/perkafinalisasi/'.$id,
                  [
                      'headers' =>
                      [
                          'Authorization' => 'Bearer '.$token
                      ],
                      'form_params' => [
                          'laporan' => $fileName,
                      ]
                  ]
              );
       }
        return redirect('huker/dir_hukum/edit_hukum_perka/'.$id_perka);
    }

    public function inputPerkaHarmonisasi(Request $request){
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $client = new Client();

        $id_perka = $request->input('id_perka');

        $requestData = $client->request('POST', $baseUrl.'/api/perkaharmonisasi',
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' => [
                      "id_perka" => $id_perka,
                      "tanggal" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal')))),
                      "no_sprint_peserta" => $request->input('no_sprint_peserta'),
                      "no_sgas_peserta" => $request->input('no_sgas_peserta'),
                      "no_sprint_nasum" => $request->input('no_sprint_nasum'),
                      "meta_narasumber" => json_encode($request->input('meta_narsum_materi')),
                      "meta_peserta" => json_encode($request->input('meta_peserta')),
                    ]
                ]
            );

        $response = json_decode($requestData->getBody()->getContents(), true);

        $this->form_params = array("id_perka" => $id_perka,
        "tanggal" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal')))),
        "no_sprint_peserta" => $request->input('no_sprint_peserta'),
        "no_sgas_peserta" => $request->input('no_sgas_peserta'),
        "no_sprint_nasum" => $request->input('no_sprint_nasum'),
        "meta_narasumber" => json_encode($request->input('meta_narsum_materi')),
        "meta_peserta" => json_encode($request->input('meta_peserta')));

        $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Hukum - Perka Harmonisasi';
        $trail['audit_event'] = 'post';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $response['comment'];
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


        $id = $response['data']['eventID'];

      if ($request->file('hasil_dicapai') != ''){
          $fileName = $id.'-'.$request->file('hasil_dicapai')->getClientOriginalName();
          $request->file('hasil_dicapai')->storeAs('HukumPerkaHarmonisasi', $fileName);

          $requestfile = $client->request('PUT', $baseUrl.'/api/perkaharmonisasi/'.$id,
                  [
                      'headers' =>
                      [
                          'Authorization' => 'Bearer '.$token
                      ],
                      'form_params' => [
                          'laporan' => $fileName,
                      ]
                  ]
              );
       }
        return redirect('huker/dir_hukum/edit_hukum_perka/'.$id_perka);
    }

    public function updatePerkaHarmonisasi(Request $request){
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $client = new Client();

        $id = $request->input('harmonisasi_id');

        $id_perka = $request->input('id_perka');

        $requestData = $client->request('PUT', $baseUrl.'/api/perkaharmonisasi/' . $id,
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' => [
                      "tanggal" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal')))),
                      "no_sprint_peserta" => $request->input('no_sprint_peserta'),
                      "no_sgas_peserta" => $request->input('no_sgas_peserta'),
                      "no_sprint_nasum" => $request->input('no_sprint_nasum'),
                      "meta_narasumber" => json_encode($request->input('meta_narsum_materi')),
                      "meta_peserta" => json_encode($request->input('meta_peserta')),
                    ]
                ]
            );

        $response = json_decode($requestData->getBody()->getContents(), true);

        $this->form_params = array("tanggal" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal')))),
        "no_sprint_peserta" => $request->input('no_sprint_peserta'),
        "no_sgas_peserta" => $request->input('no_sgas_peserta'),
        "no_sprint_nasum" => $request->input('no_sprint_nasum'),
        "meta_narasumber" => json_encode($request->input('meta_narsum_materi')),
        "meta_peserta" => json_encode($request->input('meta_peserta')));

        $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Hukum - Perka Harmonisasi';
        $trail['audit_event'] = 'put';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $response['comment'];
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


      if ($request->file('hasil_dicapai') != ''){
          $fileName = $id.'-'.$request->file('hasil_dicapai')->getClientOriginalName();
          $request->file('hasil_dicapai')->storeAs('HukumPerkaHarmonisasi', $fileName);

          $requestfile = $client->request('PUT', $baseUrl.'/api/perkaharmonisasi/'.$id,
                  [
                      'headers' =>
                      [
                          'Authorization' => 'Bearer '.$token
                      ],
                      'form_params' => [
                          'laporan' => $fileName,
                      ]
                  ]
              );
       }
        return redirect('huker/dir_hukum/edit_hukum_perka/'.$id_perka);
    }

    public function inputPerkaDraftAwal(Request $request){
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $client = new Client();

        $id_perka = $request->input('id_perka');

        $requestData = $client->request('POST', $baseUrl.'/api/perkadraftawal',
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' => [
                      "id_perka" => $id_perka,
                      "tanggal" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal')))),
                      "no_sprint" => $request->input('no_sprint'),
                      "jml_peserta" => $request->input('jml_peserta'),
                    ]
                ]
            );

        $response = json_decode($requestData->getBody()->getContents(), true);

        $this->form_params = array("id_perka" => $id_perka,
        "tanggal" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal')))),
        "no_sprint" => $request->input('no_sprint'),
        "jml_peserta" => $request->input('jml_peserta'));

        $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Hukum - Perka Draft Awal';
        $trail['audit_event'] = 'post';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $response['comment'];
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


        $id = $response['data']['eventID'];

      if ($request->file('hasil_dicapai') != ''){
          $fileName = $id.'-'.$request->file('hasil_dicapai')->getClientOriginalName();
          $request->file('hasil_dicapai')->storeAs('HukumPerkaDraftAwal', $fileName);

          $requestfile = $client->request('PUT', $baseUrl.'/api/perkadraftawal/'.$id,
                  [
                      'headers' =>
                      [
                          'Authorization' => 'Bearer '.$token
                      ],
                      'form_params' => [
                          'laporan' => $fileName,
                      ]
                  ]
              );
       }
        return redirect('huker/dir_hukum/edit_hukum_perka/'.$id_perka);
    }

    public function updatePerkaDraftAwal(Request $request){
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $client = new Client();

        $id = $request->input('draft_id');

        $id_perka = $request->input('id_perka');

        $requestData = $client->request('PUT', $baseUrl.'/api/perkadraftawal/' . $id,
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' => [
                      "tanggal" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal')))),
                      "no_sprint" => $request->input('no_sprint'),
                      "jml_peserta" => $request->input('jml_peserta'),
                    ]
                ]
            );

        $response = json_decode($requestData->getBody()->getContents(), true);

        $this->form_params = array("tanggal" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal')))),
        "no_sprint" => $request->input('no_sprint'),
        "jml_peserta" => $request->input('jml_peserta'));

        $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Hukum - Perka Draft Awal';
        $trail['audit_event'] = 'put';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $response['comment'];
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


      if ($request->file('hasil_dicapai') != ''){
          $fileName = $id.'-'.$request->file('hasil_dicapai')->getClientOriginalName();
          $request->file('hasil_dicapai')->storeAs('HukumPerkaDraftAwal', $fileName);

          $requestfile = $client->request('PUT', $baseUrl.'/api/perkadraftawal/'.$id,
                  [
                      'headers' =>
                      [
                          'Authorization' => 'Bearer '.$token
                      ],
                      'form_params' => [
                          'laporan' => $fileName,
                      ]
                  ]
              );
       }
        return redirect('huker/dir_hukum/edit_hukum_perka/'.$id_perka);
    }

    public function updatePerkaPenetapan(Request $request){
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $client = new Client();

        $id_penetapan = $request->input('penetapan_id');

        $id_perka = $request->input('id');

        if(!$id_penetapan)
        {
          //add new
          $requestData = $client->request('POST', $baseUrl.'/api/perkapenetapan',
                  [
                      'headers' =>
                      [
                          'Authorization' => 'Bearer '.$token
                      ],
                      'form_params' => [
                        "id_perka" => $id_perka,
                        "tanggal" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('penetapan_tanggal')))),
                        "no_sprint" => $request->input('penetapan_no_sprint'),
                        "jml_peserta" => $request->input('penetapan_jml_peserta'),
                      ]
                  ]
              );

          $response = json_decode($requestData->getBody()->getContents(), true);

          $this->form_params = array("id_perka" => $id_perka,
          "tanggal" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('penetapan_tanggal')))),
          "no_sprint" => $request->input('penetapan_no_sprint'),
          "jml_peserta" => $request->input('penetapan_jml_peserta'));

          $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Hukum - Perka Penetapan';
          $trail['audit_event'] = 'post';
          $trail['audit_value'] = json_encode($this->form_params);
          $trail['audit_url'] = $request->url();
          $trail['audit_ip_address'] = $request->ip();
          $trail['audit_user_agent'] = $request->userAgent();
          $trail['audit_message'] = $response['comment'];
          $trail['created_at'] = date("Y-m-d H:i:s");
          $trail['created_by'] = $request->session()->get('id');

          $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


          $id_penetapan = $response['data']['eventID'];
        }
        else
        {
          //update
          $requestData = $client->request('PUT', $baseUrl.'/api/perkapenetapan/' . $id_penetapan,
                  [
                      'headers' =>
                      [
                          'Authorization' => 'Bearer '.$token
                      ],
                      'form_params' => [
                        "tanggal" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('penetapan_tanggal')))),
                        "no_sprint" => $request->input('penetapan_no_sprint'),
                        "jml_peserta" => $request->input('penetapan_jml_peserta'),
                      ]
                  ]
              );

          $response = json_decode($requestData->getBody()->getContents(), true);

          $this->form_params = array("tanggal" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('penetapan_tanggal')))),
          "no_sprint" => $request->input('penetapan_no_sprint'),
          "jml_peserta" => $request->input('penetapan_jml_peserta'));

          $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Hukum - Perka Penetapan';
          $trail['audit_event'] = 'put';
          $trail['audit_value'] = json_encode($this->form_params);
          $trail['audit_url'] = $request->url();
          $trail['audit_ip_address'] = $request->ip();
          $trail['audit_user_agent'] = $request->userAgent();
          $trail['audit_message'] = $response['comment'];
          $trail['created_at'] = date("Y-m-d H:i:s");
          $trail['created_by'] = $request->session()->get('id');

          $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


        }

        $id = $id_penetapan;

      if ($request->file('penetapan_file_hasil') != ''){
          $fileName = $id.'-'.$request->file('penetapan_file_hasil')->getClientOriginalName();
          $request->file('penetapan_file_hasil')->storeAs('HukumPerkaRapatPenetapan', $fileName);

          $requestfile = $client->request('PUT', $baseUrl.'/api/perkapenetapan/'.$id,
                  [
                      'headers' =>
                      [
                          'Authorization' => 'Bearer '.$token
                      ],
                      'form_params' => [
                          'laporan' => $fileName,
                      ]
                  ]
              );
       }
        return redirect('huker/dir_hukum/edit_hukum_perka/'.$id_perka);
    }

    public function hukumPeraturanuu(Request $request){
        $client = new Client();
        if ($request->input('page')) {
          $page = $request->input('page');
        } else {
          $page = 1;
        }

        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestHukumperaturanuu = $client->request('GET', $baseUrl.'/api/sosialisasiperaturan?page='.$page,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
        $sosialisasiperaturan = json_decode($requestHukumperaturanuu->getBody()->getContents(), true);
        $this->data['data_hukumperaturanuu'] = $sosialisasiperaturan['data'];
		    $page = $sosialisasiperaturan['paginate'];
        $this->data['title'] = "sosialisasiperaturan";
        $this->data['token'] = $token;


        $instansi = $this->globalinstansi($request->session()->get('wilayah'), $token);

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['path'] = $request->path();
        $this->data['instansi'] = $instansi;
        $this->data['page'] = $page;
        $this->data['breadcrumps'] = breadcrumps_dir_hukum($request->route()->getName());
        return view('huker.hukum.peraturanuu.index_hukumPeraturanuu',$this->data);
    }

    public function addhukumPeraturanuu(Request $request){
        $client = new Client();
        $baseUrl = URL::to('/');

        $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
        $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $propkab;

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        $this->data['breadcrumps'] = breadcrumps_dir_hukum($request->route()->getName());
        return view('huker.hukum.peraturanuu.add_hukumPeraturanuu',$this->data);
    }

    public function edithukumPeraturanuu(Request $request){
        $id = $request->id;
        $client = new Client();
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestDataDetail= $client->request('GET', $baseUrl.'/api/sosialisasiperaturan/'.$id,
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
        $this->data['breadcrumps'] = breadcrumps_dir_hukum($request->route()->getName());
        return view('huker.hukum.peraturanuu.edit_hukumPeraturanuu',$this->data);
    }

    public function inputhukumPeraturanuu(Request $request){
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        // print_r($request->except(['_token']));
        $client = new Client();

        $params = $request->except(['_token', 'idpelaksana', 'akode_anggaran', 'asasaran', 'apagu', 'atarget_output', 'asatuan_output', 'atahun', 'asatker_code', 'arefid_anggaran', 'kd_anggaran']);

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
            $params['anggaran_id'] = $resultAnggaran['data']['eventID'];
         } else {
           $params['anggaran_id'] = '';
         }

         $fileName = "";
        if ($request->file('file_upload') != ''){
           $fileName =date('Y-m-d').'-'.$request->file('file_upload')->getClientOriginalName();
           $request->file('file_upload')->storeAs('HukumPeraturanPerundangundangan', $fileName);
        }
        $params['file_upload'] = $fileName;

        $date =  date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal_pelaksanaan'))));
        $params['tanggal_pelaksanaan'] = $date;

        $params['meta_instansi'] = json_encode($request->input("meta_instansi"));

        $total=0;
        for ($i=0; $i <count($request->input("meta_instansi")) ; $i++) {
            #
            if (($request->input("meta_instansi")[$i]['list_nama_instansi'] ) || ( $request->input("meta_instansi")[$i]['list_alamat_instansi'])  || ($request->input("meta_instansi")[$i]['list_jumlah_peserta'])){
                 $total=$total+1;

            }
        }
        $params['jumlah_instansi'] =$total;

        $peserta=0;
        for ($j=0; $j <count($request->input("meta_instansi")) ; $j++){
            if (($request->input("meta_instansi")[$j]['list_jumlah_peserta'])){

                 $peserta=$peserta+($request->input("meta_instansi")[$j]['list_jumlah_peserta']);

            }


        }
        $params['jumlah_peserta'] =$peserta;

        // dd($params);
        $requests = $client->request('POST', $baseUrl.'/api/sosialisasiperaturan',
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' =>  $params
                    ]

            );

        $result = json_decode($requests->getBody()->getContents(), true);
        $id = $result['data']['eventID'];
        // echo '<br/>';
        // dd($result);

       return redirect('huker/dir_hukum/hukum_peraturanuu/'.$id);
    }

    public function updatehukumPeraturanuu(Request $request){
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        // print_r($request->except(['_token']));
        $id = $request -> input('id');

        $client = new Client();

        $params = $request->except(['_token', 'id', 'idpelaksana', 'akode_anggaran', 'asasaran', 'apagu', 'atarget_output', 'asatuan_output', 'atahun', 'asatker_code', 'arefid_anggaran', 'kd_anggaran']);

        $fileName = "";
        if ($request->file('file_upload') != ''){
           $fileName =date('Y-m-d').$id.'-'.$request->file('file_upload')->getClientOriginalName();
           $request->file('file_upload')->storeAs('HukumPeraturanPerundangundangan', $fileName);
        }
        $params['file_upload'] = $fileName;

        $date =  date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal_pelaksanaan'))));
        $params['tanggal_pelaksanaan'] = $date;

        $params['meta_instansi'] = json_encode($request->input("meta_instansi"));

        $total=0;
        for ($i=0; $i <count($request->input("meta_instansi")) ; $i++) {
            #
            if (($request->input("meta_instansi")[$i]['list_nama_instansi'] ) || ( $request->input("meta_instansi")[$i]['list_alamat_instansi'])  || ($request->input("meta_instansi")[$i]['list_jumlah_peserta'])){
                 $total=$total+1;

            }
        }
        $params['jumlah_instansi'] =$total;

        $peserta=0;
        for ($j=0; $j <count($request->input("meta_instansi")) ; $j++){
            if (($request->input("meta_instansi")[$j]['list_jumlah_peserta'])){

                 $peserta=$peserta+($request->input("meta_instansi")[$j]['list_jumlah_peserta']);

            }


        }
        $params['jumlah_peserta'] =$peserta;


        // print_r($params);
        $requests = $client->request('PUT', $baseUrl.'/api/sosialisasiperaturan/'.$id,
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' =>  $params
                    ]

            );

        $result = json_decode($requests->getBody()->getContents(), true);
        // dd($result);
        if(($result['code'] == 200)&& ($result['status'] != "error") ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Kegiatan Sosialisasi Peraturan Perundang-undangan Berhasil Diperbarui';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Kegiatan Sosialisasi Peraturan Perundang-undangan Gagal Diperbarui';
        }
        return back()->with('status', $this->messages);
        // return redirect('huker/dir_hukum/hukum_peraturanuu/');

    }

    public function hukumMonevperaturanuu(Request $request){
        $client = new Client();
        if ($request->input('page')) {
          $page = $request->input('page');
        } else {
          $page = 1;
        }

        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestMonevperaturanuu = $client->request('GET', $baseUrl.'/api/monevperaturanuu?page='.$page,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
        $monevperaturanuu = json_decode($requestMonevperaturanuu->getBody()->getContents(), true);
        $this->data['data_monevperaturanuu'] = $monevperaturanuu['data'];
        $page = $monevperaturanuu['paginate'];
        $this->data['title'] = "monevperaturanuu";
        $this->data['token'] = $token;


        $instansi = $this->globalinstansi($request->session()->get('wilayah'), $token);

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['path'] = $request->path();
        $this->data['instansi'] = $instansi;
        $this->data['page'] = $page;
        $this->data['breadcrumps'] = breadcrumps_dir_hukum($request->route()->getName());
        return view('huker.hukum.monev_peraturanuu.index_hukumMonevperaturanuu',$this->data);
    }

    public function addhukumMonevperaturanuu(Request $request){
        $client = new Client();
        $baseUrl = URL::to('/');

        $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
        $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $propkab;

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        $this->data['breadcrumps'] = breadcrumps_dir_hukum($request->route()->getName());
        return view('huker.hukum.monev_peraturanuu.add_hukumMonevperaturanuu',$this->data);
    }

    public function edithukumMonevperaturanuu(Request $request){
        $id = $request->id;
        $query =  DB::table('v_berantas_kasus')->join('berantas_kasus','berantas_kasus.kasus_id','=','v_berantas_kasus.kasus_id')->where('v_berantas_kasus.kasus_id',$id)->first();

        $prefix = explode('/',$request->route()->getPrefix());
        $route = $prefix[0];
        $client = new Client();
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestDataDetail= $client->request('GET', $baseUrl.'/api/monevperaturanuu/'.$id,
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
        $this->data['breadcrumps'] = breadcrumps_dir_hukum($request->route()->getName());
        return view('huker.hukum.monev_peraturanuu.edit_hukumMonevperaturanuu',$this->data);
    }

    public function inputhukummonevperaturanuu(Request $request){
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        // print_r($request->except(['_token']));
        $client = new Client();

        $params = $request->except(['_token', 'idpelaksana', 'akode_anggaran', 'asasaran', 'apagu', 'atarget_output', 'asatuan_output', 'atahun', 'asatker_code', 'arefid_anggaran', 'kd_anggaran']);

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
            $params['anggaran_id'] = $resultAnggaran['data']['eventID'];
         } else {
           $params['anggaran_id'] = '';
         }

         $fileName = "";
        if ($request->file('file_upload') != ''){
           $fileName =date('Y-m-d').'-'.$request->file('file_upload')->getClientOriginalName();
           $request->file('file_upload')->storeAs('HukumMonevPerundangundangan', $fileName);
        }

        $date =  date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal_pelaksanaan'))));
        $params['tanggal_pelaksanaan'] = $date;

        $params['meta_instansi'] = json_encode($request->input("meta_instansi"));
        $params['file_upload'] = $fileName;
        $total=0;
        for ($i=0; $i <count($request->input("meta_instansi")) ; $i++) {
            #
            if (($request->input("meta_instansi")[$i]['list_nama_instansi'] ) || ( $request->input("meta_instansi")[$i]['list_alamat_instansi'])  || ($request->input("meta_instansi")[$i]['list_jumlah_peserta'])){
                 $total=$total+1;

            }
        }
        $params['jumlah_instansi'] =$total;

        $peserta=0;
        for ($j=0; $j <count($request->input("meta_instansi")) ; $j++){
            if (($request->input("meta_instansi")[$j]['list_jumlah_peserta'])){

                 $peserta=$peserta+($request->input("meta_instansi")[$j]['list_jumlah_peserta']);

            }


        }
        $params['jumlah_peserta'] =$peserta;

        // echo $params['jumlah_peserta'];



        // dd($params);
        $requests = $client->request('POST', $baseUrl.'/api/monevperaturanuu',
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' =>  $params
                    ]

            );

        $result = json_decode($requests->getBody()->getContents(), true);
        $id = $result['data']['eventID'];
        // echo '<br/>';
        // dd($result);

        return redirect('huker/dir_hukum/hukum_monevperaturanuu/'.$id);
    }

    public function updatehukummonevperaturanuu(Request $request){
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        // print_r($request->except(['_token']));
        $id = $request -> input('id');

        $client = new Client();

        $params = $request->except(['_token', 'id', 'idpelaksana', 'akode_anggaran', 'asasaran', 'apagu', 'atarget_output', 'asatuan_output', 'atahun', 'asatker_code', 'arefid_anggaran', 'kd_anggaran']);

        $fileName = "";
        if ($request->file('file_upload') != ''){
           $fileName =date('Y-m-d').$id.'-'.$request->file('file_upload')->getClientOriginalName();
           $request->file('file_upload')->storeAs('HukumMonevPerundangundangan', $fileName);
        }

        $date =  date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal_pelaksanaan'))));
        $params['tanggal_pelaksanaan'] = $date;

        $params['meta_instansi'] = json_encode($request->input("meta_instansi"));
        $total=0;
        for ($i=0; $i <count($request->input("meta_instansi")) ; $i++) {
            #
            if (($request->input("meta_instansi")[$i]['list_nama_instansi'] ) || ( $request->input("meta_instansi")[$i]['list_alamat_instansi'])  || ($request->input("meta_instansi")[$i]['list_jumlah_peserta'])){
                 $total=$total+1;

            }
        }
        $params['jumlah_instansi'] =$total;

        $peserta=0;
        for ($j=0; $j <count($request->input("meta_instansi")) ; $j++){
            if (($request->input("meta_instansi")[$j]['list_jumlah_peserta'])){

                 $peserta=$peserta+($request->input("meta_instansi")[$j]['list_jumlah_peserta']);

            }


        }
        $params['jumlah_peserta'] =$peserta;
        $params['file_upload'] = $fileName;
        // print_r($params);
        $requests = $client->request('PUT', $baseUrl.'/api/monevperaturanuu/'.$id,
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' =>  $params
                    ]

            );

        $result = json_decode($requests->getBody()->getContents(), true);
        // dd($result);
        if(($result['code'] == 200)&& ($result['status'] != "error") ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Kegiatan Monev Peraturan Perundang-undangan Berhasil Diperbarui';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Kegiatan Monev Peraturan Perundang-undangan Gagal Diperbarui';
        }
        return back()->with('status', $this->messages);
        // return redirect('huker/dir_hukum/hukum_monevperaturanuu/');

    }

    public function hukumLainnya(Request $request){
        //filter
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
                if( ($key == 'tgl_from') || ($key == 'tgl_to')){
                  $this->selected[$key] = date('d/m/Y',strtotime($value));
                }else if(($key == 'jenis_kegiatan') || ($key == 'tema') || ($key == 'no_sprint_kepala')){
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
          $tgl_from = $request->tgl_from;
          $tgl_to = $request->tgl_to;
          $jenis_kegiatan = $request->jenis_kegiatan;
          $order = $request->order;
          $limit = $request->limit;
          $status = $request->status;
          $bagian = $request->bagian;

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
          }
          else if($tipe == 'status'){
            $kondisi .= '&status='.$status;
            $this->selected['status'] = $status;
          }
          else if($tipe == 'bagian'){
            $kondisi .= '&bagian='.$bagian;
            $this->selected['bagian'] = $bagian;
          }
          else if($tipe == 'jenis_kegiatan'){
            $kondisi .= '&jenis_kegiatan='.$jenis_kegiatan;
            $this->selected['jenis_kegiatan'] = $jenis_kegiatan;
          }
          else{
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
        $datas = execute_api_json('api/hukumlainnya?'.$limit.'&'.$offset.$kondisi,'get');

        $total_item = 0;
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;

        }else{
            $this->data['data'] = [];
            $total_item = 0;
        }

        $this->data['filter'] = $this->selected;
        $this->data['kondisi'] = $kondisi;

        //end filter
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['title'] = "Hukum Lainnya";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));

        $this->data['delete_route'] = "delete_hukum_lainnya";
        $this->data['path'] = $request->path();
        $this->data['route_name'] = $request->route()->getName();
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;

        $this->data['route'] = $request->route()->getName();

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

        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
        return view('huker.hukum.kegiatan_lainnya.index_hukumLainnya',$this->data);
    }

    public function addhukumLainnya(Request $request){
        // $this->data['title']="hukumnonlitigasi";
        $client = new Client();
        $baseUrl = URL::to('/');

        $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
        $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $propkab;

        $this->data['title'] = "Hukum Lainnya";

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        $this->data['breadcrumps'] = breadcrumps_dir_hukum($request->route()->getName());
        return view('huker.hukum.kegiatan_lainnya.add_hukumLainnya',$this->data);
    }

    public function edithukumLainnya(Request $request){
        $id = $request->id;
        $client = new Client();
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestDataDetail= $client->request('GET', $baseUrl.'/api/hukumlainnya/'.$id,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );

        $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
        $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $propkab;

        $dataDetail = json_decode($requestDataDetail->getBody()->getContents(), true);

        if ($dataDetail['data']['anggaran_id'] != '') {
           $this->data['data_anggaran'] = $this->globalGetAnggaran($token, $dataDetail['data']['anggaran_id']);

        }

        $this->data['title'] = "Hukum Lainnya";

        $this->data['data_detail'] = $dataDetail['data'];
        $this->data['id'] = $id;
        $this->data['breadcrumps'] = breadcrumps_dir_hukum($request->route()->getName());
        return view('huker.hukum.kegiatan_lainnya.edit_hukumLainnya',$this->data);
    }

    public function inputhukumLainnya(Request $request){
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        // print_r($request->except(['_token']));

        $client = new Client();

       if ($request->input('sumberanggaran')=="DIPA") {

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

        $requests = $client->request('POST', $baseUrl.'/api/hukumlainnya',
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' =>  [
                      "jenis_kegiatan" => $request->input('jenis_kegiatan'),
                      "bagian" => $request->input('bagian'),
                      "no_sprint_kepala" => $request->input('sprint_kepala'),
                      "no_sprint_deputi" => $request->input('sprint_deputi'),
                      "tgl_mulai" => ($request->input('tgl_mulai') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_mulai')))) : '',
                      "tgl_selesai" => ($request->input('tgl_selesai') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_selesai')))) : '',
                      "tempat_kegiatan" => $request->input('tempat_kegiatan'),
                      "lokasi_kegiatan" => $request->input('lokasi_kegiatan'),
                      "tema" => $request->input('tema'),
                      "meta_narasumber" => json_encode($request->input('meta_narasumber')),
                      "meta_peserta" => json_encode($request->input('meta_peserta')),
                      "sumberanggaran" => $request->input('sumberanggaran'),
                      "anggaran_id" => $anggaran,
                    ]
                ]

            );

        $result = json_decode($requests->getBody()->getContents(), true);

        $this->form_params = array("jenis_kegiatan" => $request->input('jenis_kegiatan'),
        "bagian" => $request->input('bagian'),
        "no_sprint_kepala" => $request->input('sprint_kepala'),
        "no_sprint_deputi" => $request->input('sprint_deputi'),
        "tgl_mulai" => ($request->input('tgl_mulai') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_mulai')))) : '',
        "tgl_selesai" => ($request->input('tgl_selesai') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_selesai')))) : '',
        "tempat_kegiatan" => $request->input('tempat_kegiatan'),
        "lokasi_kegiatan" => $request->input('lokasi_kegiatan'),
        "tema" => $request->input('tema'),
        "meta_narasumber" => json_encode($request->input('meta_narasumber')),
        "meta_peserta" => json_encode($request->input('meta_peserta')),
        "sumberanggaran" => $request->input('sumberanggaran'),
        "anggaran_id" => $anggaran);

        $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Hukum - Kegiatan Lainnya';
        $trail['audit_event'] = 'post';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $result['comment'];
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


        $id = $result['data']['eventID'];

        if ($request->file('hasil_dicapai') != ''){
            $fileName = $id.'-'.$request->file('hasil_dicapai')->getClientOriginalName();
            $request->file('hasil_dicapai')->storeAs('HukumLainnya', $fileName);

            $requestfile = $client->request('PUT', $baseUrl.'/api/hukumlainnya/'.$id,
                    [
                        'headers' =>
                        [
                            'Authorization' => 'Bearer '.$token
                        ],
                        'form_params' => [
                            'hasil_dicapai' => $fileName,
                        ]
                    ]
                );
         }

        if(($result['code'] == 200)&& ($result['status'] != "error") ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Kegiatan Hukum Lainnya Berhasil Disimpan';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Kegiatan Hukum Lainnya Gagal Disimpan';
        }

        $this->kelengkapan_hukumLainnya($id);

       return redirect('huker/dir_hukum/edit_hukum_lainnya/'.$id)->with('status', $this->messages);
    }

    public function updatehukumLainnya(Request $request){
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        // print_r($request->except(['_token']));
        $id = $request -> input('id');

        $client = new Client();

       if ($request->input('sumberanggaran')=="DIPA") {

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

        $requests = $client->request('PUT', $baseUrl.'/api/hukumlainnya/'.$id,
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' =>  [
                      "jenis_kegiatan" => $request->input('jenis_kegiatan'),
                      "bagian" => $request->input('bagian'),
                      "no_sprint_kepala" => $request->input('sprint_kepala'),
                      "no_sprint_deputi" => $request->input('sprint_deputi'),
                      "tgl_mulai" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_mulai')))),
                      "tgl_selesai" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_selesai')))),
                      "tempat_kegiatan" => $request->input('tempat_kegiatan'),
                      "lokasi_kegiatan" => $request->input('lokasi_kegiatan'),
                      "tema" => $request->input('tema'),
                      "meta_narasumber" => json_encode($request->input('meta_narasumber')),
                      "meta_peserta" => json_encode($request->input('meta_peserta')),
                      "sumberanggaran" => $request->input('sumberanggaran'),
                      "anggaran_id" => $anggaran,
                    ]
                ]
            );

        $result = json_decode($requests->getBody()->getContents(), true);

        $this->form_params = array("jenis_kegiatan" => $request->input('jenis_kegiatan'),
        "bagian" => $request->input('bagian'),
        "no_sprint_kepala" => $request->input('sprint_kepala'),
        "no_sprint_deputi" => $request->input('sprint_deputi'),
        "tgl_mulai" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_mulai')))),
        "tgl_selesai" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_selesai')))),
        "tempat_kegiatan" => $request->input('tempat_kegiatan'),
        "lokasi_kegiatan" => $request->input('lokasi_kegiatan'),
        "tema" => $request->input('tema'),
        "meta_narasumber" => json_encode($request->input('meta_narasumber')),
        "meta_peserta" => json_encode($request->input('meta_peserta')),
        "sumberanggaran" => $request->input('sumberanggaran'),
        "anggaran_id" => $anggaran);

        $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Hukum - Kegiatan Lainnya';
        $trail['audit_event'] = 'put';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $result['comment'];
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


        if ($request->file('hasil_dicapai') != ''){
            $fileName = $id.'-'.$request->file('hasil_dicapai')->getClientOriginalName();
            $request->file('hasil_dicapai')->storeAs('HukumLainnya', $fileName);

            $requestfile = $client->request('PUT', $baseUrl.'/api/hukumlainnya/'.$id,
                    [
                        'headers' =>
                        [
                            'Authorization' => 'Bearer '.$token
                        ],
                        'form_params' => [
                            'hasil_dicapai' => $fileName,
                        ]
                    ]
                );
         }

        if(($result['code'] == 200)&& ($result['status'] != "error") ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Kegiatan Hukum Lainnya Berhasil Diperbarui';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Kegiatan Hukum Lainnya Gagal Diperbarui';
        }

        $this->kelengkapan_hukumLainnya($id);

        return back()->with('status', $this->messages);
        // return redirect('huker/dir_hukum/hukum_nonlitigasi/');

    }

    public function deleteHukumLainnya(Request $request){
      $id = $request->input('id');
      if($id){
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/hukumlainnya/'.$id,'DELETE');
            $this->form_params['delete_id'] = $id;
            $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Hukum - Kegiatan Lainnya';
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
        $data_request = ['status'=>'error','message'=>'Data Kasus Gagal Dihapus'];
        return $data_request;
      }
    }

    private function kelengkapan_hukumLainnya($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('huker_hukumkegiatanlainnya')->where('id',$id)
                  ->select('jenis_kegiatan','no_sprint_kepala','no_sprint_deputi','tgl_mulai', 'tgl_selesai','tempat_kegiatan', 'lokasi_kegiatan', 'tema', 'meta_narasumber', 'meta_peserta', 'sumberanggaran', 'hasil_dicapai', 'bagian');
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
          $kelengkapan = execute_api_json('api/hukumlainnya/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/hukumlainnya/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

    public function printLainnya(Request $request){
        $client = new Client();
        $token = $request->session()->get('token');
        $baseUrl = URL::to('/');

        $get = $request->all();
        $kondisi = "";
        if(count($get)>0){
          foreach($get as $key=>$val){
            $kondisi .= $key.'='.$val.'&';
          }
          $kondisi = rtrim($kondisi,'&');
        }

        $requestPrintData = $client->request('GET', $baseUrl.'/api/hukumlainnya?'.$kondisi,
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
          $DataArray[$key]['Jenis Kegiatan'] = $value['jenis_kegiatan'];
          $DataArray[$key]['Bagian'] = (trim($value['bagian']) == 'Penelahaan') ? 'Penelahaan' : ((trim($value['bagian']) == 'Perancangan') ? 'Perancangan' : '') ;
          $DataArray[$key]['Nomor Surat Perintah BNN'] = $value['no_sprint_kepala'];
          $DataArray[$key]['Tanggal Kegiatan'] = date('d-m-Y', strtotime($value['tgl_mulai'])) . ' - ' . date('d-m-Y', strtotime($value['tgl_mulai']));
          $DataArray[$key]['Tema'] = $value['tema'];
          $DataArray[$key]['Status'] = ($value['status'] == 'Y') ? "Lengkap" : "Tidak Lengkap";
          $i = $i +1;
        }
         //dd($DataArray);
        $data = $DataArray;
        $name = 'Data Kegiatan Hukum Lainnya '.Carbon::now()->format('Y-m-d H:i:s');
        $this->printData($data, $name);
    }


}

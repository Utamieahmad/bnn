<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\MainModel;
use App\Tr_BrgBukti;
use App\Models\BrgBukti;
use App\Models\Irtama\AuditBidangLha;
use App\Models\Irtama\AuditRekomendasiBidang;
use URL;
use DateTime;
use Carbon\Carbon;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use Excel;
use Storage;
use App\Models\Irtama\IrtamaPtl;

class irtamaController extends Controller
{
    public $data;
    public $selected;
    public $form_params;
    public function irtamaAudit(Request $request){
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
              }else if(($key == 'nomor_lha') || ($key == 'nama_satker')  || ($key == 'ketua_tim')){
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
        $nama_satker = $request->nama_satker;
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
        }else if($tipe == 'nama_satker'){
          $kondisi .= '&nama_satker='.$nama_satker;
          $this->selected['keyword'] = $nama_satker;
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

      $this->data['title'] = "Audit Laporan Hasil Audit";
      if($request->page){
          $current_page = $request->page;
          $start_number = ($this->limit * ($request->page -1 )) + 1;
      }else{
          $current_page = 1;
          $start_number = $current_page;
      }
      $limit = 'limit='.$this->limit;
      $offset = 'page='.$current_page;
      $datas = execute_api_json('api/auditlha?'.$limit.'&'.$offset.$kondisi,'get');

      $total_item = 0;
      if($datas->code == 200){
          $this->data['data'] = $datas->data;
          $total_item = $datas->paginate->totalpage * $this->limit;
      }else{
          $this->data['data'] = [];
          $total_item = 0;
      }


      $url_simpeg = config('app.url_simpeg');
      $query  =  execute_api_json($url_simpeg,"GET");
      if($query->code == 200 && ($query->status != 'error')){
        $this->data['satker'] = $query->data;
      }else{
        $this->data['satker'] = [];
      }
      // $client = new Client();
      // $requestsatker = $client->request('GET', config('app.url_soa').'simpeg/listSatker');
      // $satker = json_decode($requestsatker->getBody()->getContents(), true);
      // $this->data['satker'] = $satker['data'];

      $this->data['filter'] = $this->selected;
      $this->data['kondisi'] = $kondisi;

      $this->data['delete_route']  = 'delete_irtama_audit';
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
      $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());
      return view('irtama.audit.index_irtamaAudit',$this->data);
    }


    public function editirtamaAudit(Request $request){
        $id = $request->id;
        $url_satker = config('app.url_satker');
        $satker_irtama = config('app.satker_irtama');

        try {
          $satker = execute_api_json($url_satker.'?unit_id='.$satker_irtama,'GET');

          if(($satker->code == 200) && ($satker->status != 'error')){

            $this->data['pegawai'] = $satker->data->pegawai;
          }else{
            $this->data['pegawai'] = [];
          }
        }catch(\Exception $e){
          $this->data['pegawai'] = [];
        }

        $url_simpeg = config('app.url_simpeg');
        $query  =  execute_api_json($url_simpeg,"GET");
        if($query->code == 200 && ($query->status != 'error')){
          $this->data['satker'] = $query->data;
        }else{
          $this->data['satker'] = [];
        }

        $datas = execute_api_json('api/auditlha/'.$id,'get');
        if($datas->code == 200 && $datas->status != 'error'){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }


        $tipe = 'tipe=kinerja&id_lha='.$id;
        $datas = execute_api_json('api/detailirtamalhabidang?'.$tipe,'get');

        if(($datas->code == 200) && ($datas->status != 'error')){
            $this->data['kinerja'] = $datas->data;
        }else{
            $this->data['kinerja'] = [];
        }
        $this->data['kinerja_path'] = 'upload/Irtama/BidangKinerja';
        // tab keuangan
        $tipe = 'tipe=keuangan&id_lha='.$id;
        $datas = execute_api_json('api/detailirtamalhabidang?'.$tipe,'get');
        if(($datas->code == 200) && ($datas->status != 'error')){
            $this->data['keuangan'] = $datas->data;
        }else{
            $this->data['keuangan'] = [];
        }
        // tab sdm
        $tipe = 'tipe=sdm&id_lha='.$id;
        $datas = execute_api_json('api/detailirtamalhabidang?'.$tipe,'get');
        if(($datas->code == 200) && ($datas->status != 'error')){
            $this->data['sdm'] = $datas->data;
        }else{
            $this->data['sdm'] = [];
        }
        // tab prasarana
        $tipe = 'tipe=sarana&id_lha='.$id;
        $datas = execute_api_json('api/detailirtamalhabidang?'.$tipe,'get');
        if(($datas->code == 200) && ($datas->status != 'error')){
            $this->data['sarana'] = $datas->data;
        }else{
            $this->data['sarana'] = [];
        }

        $query  =  execute_api_json('api/gettemuan',"GET");
        if($query->code == 200 && ($query->status != 'error')){
          $this->data['kode_temuan'] = $query->data;
        }else{
          $this->data['kode_temuan'] = [];
        }

        $query  =  execute_api_json('api/getrekomendasi',"GET");
        if($query->code == 200 && ($query->status != 'error')){
          $this->data['kode_rekomendasi'] = $query->data;
        }else{
          $this->data['kode_rekomendasi'] = [];
        }

        // kode_temuan
        //$this->data['kode_temuan']  = config('app.kode_temuan');
        $this->data['satker_id'] = config('app.satker_irtama');
        $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());
        return view('irtama.audit.edit_irtamaAudit',$this->data);
    }

    public function updateirtamaAudit(Request $request)
    {
      $id = $request->input('id');
      // $this->form_params = $request->except(['_token','id','satker_id','coll_anggota','meta_anggota','satker_pengendali_mutu','satker_pengendali_teknis','satker_ketua_tim']);
      $this->form_params = $request->except(['_token','id','satker_id','coll_anggota','meta_anggota','satker_pengendali_mutu','satker_pengendali_teknis','satker_ketua_tim','nama_satker','list_satker']);

      if($request->input('tgl_mulai')){
        $date = $request->tgl_mulai;
        $date = str_replace('/', '-', $date);
        $date = date('Y-m-d',strtotime($date));
        $this->form_params['tgl_mulai'] = $date;
      }
      if($request->input('tanggal_lha')){
        $date = $request->tanggal_lha;
        $date = str_replace('/', '-', $date);
        $date = date('Y-m-d',strtotime($date));
        $this->form_params['tanggal_lha'] = $date;
      }
      if($request->input('tgl_selesai')){
        $date = $request->input('tgl_selesai');
        $date = str_replace('/', '-', $date);
        $date = date('Y-m-d',strtotime($date));
        $this->form_params['tgl_selesai'] = $date;
      }
      $list_satker = $request->input('list_satker');
      $j = json_decode($list_satker,true);
      $this->form_params['nama_satker'] = $request->input('list_satker');
      $data_ptl = $request->data_ptl;
      $ptl_messages= "";
      if($data_ptl == 'Y'){
        $query = IrtamaPtl::where('id_lha',$id)->count();

        if($query < 1){
          if($data_ptl == 'Y'){
            $insertPtl = execute_api_json('api/irtamaptl','POST',['id_lha'=>$id]);

            $trail['audit_menu'] = 'Inspektorat Utama - Pemantauan Tindak Lanjut';
            $trail['audit_event'] = 'post';
            $trail['audit_value'] = array('id_lha'=>$id);
            $trail['audit_url'] = $request->url();
            $trail['audit_ip_address'] = $request->ip();
            $trail['audit_user_agent'] = $request->userAgent();
            $trail['audit_message'] = $insertPtl->comment;
            $trail['created_at'] = date("Y-m-d H:i:s");
            $trail['created_by'] = $request->session()->get('id');

            $qtrail = $this->inputtrail($token,$trail);

            $ptl_messages = "";
            if($insertPtl->code == 200 && ($insertPtl->status != 'error')){
              $ptl_messages .= "dengan Data LHA berhasil disimpan di PTL";
            }else{
               $ptl_messages .= "dengan Data LHA gagal disimpan di PTL";
            }
          }else{
            $ptl_messages .= "";
          }
        }else{
          $ptl_messages .= "";
        }
      }

      $this->form_params['meta_tim_anggota'] = $request->input('meta_anggota');
      $query = execute_api_json('api/auditlha/'.$id,"PUT",$this->form_params);

      $trail['audit_menu'] = 'Inspektorat Utama - Laporan Hasil Audit';
      $trail['audit_event'] = 'put';
      $trail['audit_value'] = json_encode($this->form_params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $query->comment;
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($token,$trail);

      $this->kelengkapan_lha($id);
      if($query->code == 200 && ($query->status != 'error')){
        $this->data['status'] = 'success';
        $this->data['message'] = 'Data LHA berhasil diperbarui.'.$ptl_messages;
      }else{
        $this->data['status'] = 'error';
        $this->data['message'] = 'Data LHA gagal diperbarui.';
      }


      return back()->with('status',$this->data);
    }

    public function deleteIrtamaAudit(Request $request){
      $id = $request->input('id');
        if ($request->ajax()) {
            if($id){
                $id = $request->id;
                $id_lha = [];
                $bidang = AuditBidangLha::where('id_lha',$id)->select('id_lha_bidang');
                if($bidang->count()>0){
                  $b = $bidang->get();
                  foreach($b as $k){
                    $id_lha[]  = $k->id_lha_bidang;
                  }
                }else{
                  $id_lha = [];
                }
                if(count($id_lha)>0){
                  $rekomendasi = AuditRekomendasiBidang::whereIn('id_lha_bidang',$id_lha);
                  $rekomendasi->delete();
                }
                $data_request2 = execute_api('api/deletelhabidang/'.$id,'DELETE');
                $data_request = execute_api('api/auditlha/'.$id,'DELETE');

								$this->form_params['delete_id'] = $id;
                $trail['audit_menu'] = 'Inspektorat Utama - Laporan Hasil Audit';
                $trail['audit_event'] = 'delete';
                $trail['audit_value'] = json_encode($this->form_params);
                $trail['audit_url'] = $request->url();
                $trail['audit_ip_address'] = $request->ip();
                $trail['audit_user_agent'] = $request->userAgent();
                $trail['audit_message'] = $data_request['comment'];
                $trail['created_at'] = date("Y-m-d H:i:s");
                $trail['created_by'] = $request->session()->get('id');

                $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


                return $data_request2;
            }else{
                $data_request =['status'=>'error','messages'=>'Data Lembaga LHA Gagal Dihapus','code'=>200];
                return $data_request;
            }

        }
    }
    public function addirtamaAudit(Request $request){
        $client = new Client();
        $url_satker = config('app.url_satker');
        $satker_irtama = config('app.satker_irtama');
        try {
          $satker = execute_api_json($url_satker.'?unit_id='.$satker_irtama,'GET');

          if(($satker->code == 200) && ($satker->status != 'error')){

            $this->data['pegawai'] = $satker->data->pegawai;
          }else{
            $this->data['pegawai'] = [];
          }
        }catch(\Exception $e){
          $this->data['pegawai'] = [];
        }
        $url_simpeg = config('app.url_simpeg');
        $query  =  execute_api_json($url_simpeg,"GET");
        if($query->code == 200 && ($query->status != 'error')){
          $this->data['satker'] = $query->data;
        }else{
          $this->data['satker'] = [];
        }

        $query  =  execute_api_json('api/gettemuan',"GET");
        if($query->code == 200 && ($query->status != 'error')){
          $this->data['kode_temuan'] = $query->data;
        }else{
          $this->data['kode_temuan'] = [];
        }

        $query  =  execute_api_json('api/getrekomendasi',"GET");
        if($query->code == 200 && ($query->status != 'error')){
          $this->data['kode_rekomendasi'] = $query->data;
        }else{
          $this->data['kode_rekomendasi'] = [];
        }

        $this->data['satker_id'] = config('app.satker_irtama');
        $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());
        return view('irtama.audit.add_irtamaAudit',$this->data);
    }

    public function inputirtamaAudit(Request $request){
      $this->form_params = $request->except(['_token','satker_id','coll_anggota','meta_anggota','satker_pengendali_mutu','satker_pengendali_teknis','satker_ketua_tim','nama_satker','list_satker']);

      $token = $request->session()->get('token');
      if($request->input('tgl_mulai')){
        $date = $request->tgl_mulai;
        $date = str_replace('/', '-', $date);
        $date = date('Y-m-d',strtotime($date));
        $this->form_params['tgl_mulai'] = $date;
      }
      if($request->input('tanggal_lha')){
        $date = $request->tanggal_lha;
        $date = str_replace('/', '-', $date);
        $date = date('Y-m-d',strtotime($date));
        $this->form_params['tanggal_lha'] = $date;
      }
      if($request->input('tgl_selesai')){
        $date = $request->input('tgl_selesai');
        $date = str_replace('/', '-', $date);
        $date = date('Y-m-d',strtotime($date));
        $this->form_params['tgl_selesai'] = $date;
      }
      $this->form_params['meta_tim_anggota'] = $request->input('meta_anggota');
      $list_satker = $request->input('list_satker');
      $j = json_decode($list_satker,true);
      $this->form_params['nama_satker'] = $request->input('list_satker');

      $query = execute_api_json('api/auditlha',"POST",$this->form_params);

      $trail['audit_menu'] = 'Inspektorat Utama - Laporan Hasil Audit';
      $trail['audit_event'] = 'post';
      $trail['audit_value'] = json_encode($this->form_params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $query->comment;
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($token,$trail);

      $ptl_messages = "";
      if($query->code == 200 && ($query->status != 'error')){
        $insertedId = $query->data->eventID;
        $data_ptl = $request->input('data_ptl');
        if($data_ptl == 'Y'){
          $insertPtl = execute_api_json('api/irtamaptl','POST',['id_lha'=>$insertedId]);

          $trail['audit_menu'] = 'Inspektorat Utama - Pemantauan Tindak Lanjut';
          $trail['audit_event'] = 'post';
          $trail['audit_value'] = array('id_lha'=>$insertedId);
          $trail['audit_url'] = $request->url();
          $trail['audit_ip_address'] = $request->ip();
          $trail['audit_user_agent'] = $request->userAgent();
          $trail['audit_message'] = $insertPtl->comment;
          $trail['created_at'] = date("Y-m-d H:i:s");
          $trail['created_by'] = $request->session()->get('id');

          $qtrail = $this->inputtrail($token,$trail);

          $ptl_messages = "";
          if($insertPtl->code == 200 && ($insertPtl->status != 'error')){
            $ptl_messages .= "dengan Data LHA berhasil disimpan di PTL";
          }else{
             $ptl_messages .= "dengan Data LHA gagal disimpan di PTL";
          }
        }else{
          $ptl_messages .= "";
        }
        $this->kelengkapan_lha($insertedId);

        $this->data['status'] = 'success';
        $this->data['message'] = 'Data LHA berhasil disimpan. '.$ptl_messages;

        return redirect(route('edit_irtama_audit',[$insertedId]))->with('status',$this->data);
      }else{
        $this->data['status'] = 'error';
        $this->data['messages'] = 'Data LHA gagal disimpan.';
        // return redirect(route('irtama_audit'))->with('status',$this->data);
        return redirect(route('irtama_audit'))->with('status',$this->data);
      }


    }

    public function updateirtamaPtl(Request $request)
    {
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');
      $client = new Client();
      $id = $request->input('id');

      $requestData = $client->request('PUT', $baseUrl.'/api/irtamaptl/'.$id,
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token
          ],
          'form_params' =>
          [
            "no_sprin" => $request->input('no_sprin'),
            "kode_temuan" => $request->input('kode_temuan'),
            "nama_temuan" => $request->input('nama_temuan'),
            "judul_temuan" => $request->input('judul_temuan'),
            "kondisi_temuan" => $request->input('kondisi_temuan'),
            "tindak_lanjut" => $request->input('tindak_lanjut'),
            "nilai_tindak_lanjut" => $request->input('nilai_tindak_lanjut')
          ]
        ]
      );

      $result = json_decode($requestData->getBody()->getContents(), true);
      // dd($result);
      $inputId = $result['data']['eventID'];

      $this->form_params = array("no_sprin" => $request->input('no_sprin'),
                                "kode_temuan" => $request->input('kode_temuan'),
                                "nama_temuan" => $request->input('nama_temuan'),
                                "judul_temuan" => $request->input('judul_temuan'),
                                "kondisi_temuan" => $request->input('kondisi_temuan'),
                                "tindak_lanjut" => $request->input('tindak_lanjut'),
                                "nilai_tindak_lanjut" => $request->input('nilai_tindak_lanjut'));

      $trail['audit_menu'] = 'Inspektorat Utama - Pemantauan Tindak Lanjut';
      $trail['audit_event'] = 'put';
      $trail['audit_value'] = json_encode($this->form_params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $result['comment'];
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($token,$trail);


      if ($request->file('file_upload') != ''){
          $fileName = date('Y_m_d').'_'.$inputId.'-'.$request->file('file_upload')->getClientOriginalName();
          $request->file('file_upload')->storeAs('IrtamaAuditLha', $fileName);

          $requestfile = $client->request('PUT', $baseUrl.'/api/irtamaptl/'.$inputId,
                  [
                      'headers' =>
                      [
                          'Authorization' => 'Bearer '.$token
                      ],
                      'form_params' => [
                          'url_bukti' => $fileName,
                      ]
                  ]
              );
       }

      return redirect('irtama/ptl/irtama_ptl');
    }

    public function addirtamaPtl(Request $request){
        $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());
        return view('irtama.ptl.add_irtamaPtl',$this->data);
    }

    public function inputirtamaPtl(Request $request)
    {
      $this->limit = config('app.limit');
      $this->data['title'] = "Audit Laporan Pemantauan Tindak Lanjut";
      if($request->page){
          $current_page = $request->page;
          $start_number = ($this->limit * ($request->page -1 )) + 1;
      }else{
          $current_page = 1;
          $start_number = $current_page;
      }
      $limit = 'limit='.$this->limit;
      $offset = 'page='.$current_page;
      $datas = execute_api_json('api/irtamaptl?'.$limit.'&'.$offset,'get');

      if($datas->code == 200){
          $this->data['data'] = $datas->data;
      }else{
          $this->data['data'] = [];
      }

      $this->data['delete_route']  = 'delete_irtama_audit';
      $this->data['path'] = $request->path();
      $this->data['route_name'] = $request->route()->getName();
      $this->data['start_number'] = $start_number;
      $this->data['current_page'] = $current_page;
      $total_item = $datas->paginate->totalpage * $this->limit;
      $this->data['pagination'] = pagination($current_page,$total_item, $this->limit, config('app.page_ellipsis'), "/".$request->route()->getPrefix()."/".$request->route()->getName()."/%d");

    }


    public function irtamaLk(Request $request){


      $kondisi = '';
      $offset = '';
      $limit = '';

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
      if($request->isMethod('get')){
          $get = $request->except(['page']);
          $tipe = $request->tipe;
          if(count($get)>0){
            $this->selected['tipe']  = $tipe;
            foreach ($get as $key => $value) {
              if($key == 'no_sprint' || $key == 'ketua_tim'){
                $kondisi .= "&".$key.'='.$value;
                $this->selected['keyword']  = $value;
              }else{
                $kondisi .= "&".$key.'='.$value;
                $this->selected[$key]  = $value;
              }
            }
          }

      }else{
        $post = $request->all();
        $tipe = $request->tipe;
        $hasil_reviu = $request->hasil_reviu;
        $objek_reviu = $request->objek_reviu;
        $objek_value = $request->objek_value;
        $order = $request->order;
        $limit = $request->limit;
        $no_sprint = $request->no_sprint;
        $kata_kunci = $request->kata_kunci;
        $hasil_reviu_value = $request->hasil_reviu_value;
        $status = $request->status;


        if($tipe == 'objek_reviu'){
          $kondisi .= '&'.$tipe.'='.$objek_reviu;
          $this->selected['objek_reviu'] = $objek_reviu;
          $kondisi .= '&objek_value='.$objek_value;
          $this->selected['objek_value'] = $objek_value;
        }else if( ($tipe == 'no_sprint')|| ($tipe == 'ketua_tim')){
          $kondisi .= '&'.$tipe.'='.$kata_kunci;
          $this->selected['keyword'] = $kata_kunci;
        }elseif($tipe == 'hasil_reviu'){
          $kondisi .= '&'.$tipe.'='.$hasil_reviu;
          $this->selected['hasil_reviu'] = $hasil_reviu;
          $kondisi .= '&hasil_reviu_value='.$hasil_reviu_value;
          $this->selected['hasil_reviu_value'] = $hasil_reviu_value;
        }elseif($tipe == 'status'){
          $kondisi .= '&status='.$status;
          $this->selected['status'] = $status;
        }
        if($tipe){
          $kondisi .= '&tipe='.$tipe;
          $this->selected['tipe'] = $tipe;
        }
        $kondisi .='&limit='.$this->limit;
        $kondisi .='&order='.$order;
        $this->selected['limit'] = $limit;
        $this->selected['order'] =  $order;
      }
      $limit = 'limit='.$this->limit;
      $offset = 'page='.$current_page;
      $query  =  execute_api('api/reviulk?'.$offset.$kondisi,"GET");

      if($query['code'] == 200 && ($query['status'] != 'error')){
        $this->data['reviulk'] = $query['data'];
        $total_item = $query['paginate']['totalpage'] * $this->limit;
      }else{
        $this->data['reviulk'] = [];
        $total_item = 0;
      }
      $this->data['route'] = $request->route()->getName();
      $this->data['filter'] = $this->selected;
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

      $this->data['kondisi'] = '?'.$offset.$kondisi;
      $this->data['filter'] = $this->selected;
      $this->data['start_number'] = $start_number;
      $this->data['title'] = "Reviu Laporan Keuangan";
      $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
      $this->data['path'] = $request->path();
      $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());
      $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
      return view('irtama.reviu.lk.index_irtamaLk',$this->data);
    }

    public function editirtamaLk(Request $request){
       $id = $request->id;
       $baseUrl = URL::to('/');
       $token = $request->session()->get('token');
       $client = new Client();

       $requestpegawai = $client->request('GET', config('app.url_soa').'simpeg/staffBySatker?unit_id=156');
       $pegawai = json_decode($requestpegawai->getBody()->getContents(), true);
       $this->data['pegawai'] = $pegawai['data']['pegawai'];

       $requestData = $client->request('GET', $baseUrl.'/api/reviulk/'.$id,
         [
           'headers' =>
           [
             'Authorization' => 'Bearer '.$token
           ]
         ]
       );

       $reviulk = json_decode($requestData->getBody()->getContents(), true);

       $this->data['reviulk'] = $reviulk['data'];
       $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());
       return view('irtama.reviu.lk.edit_irtamaLk',$this->data);
    }

    public function updateirtamaLk(Request $request)
    {
      $id = $request->input('id');
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');
      $client = new Client();

      $requestData = $client->request('PUT', $baseUrl.'/api/reviulk/'.$id,
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token
          ],
          'form_params' =>
          [
            "no_sprint" => $request->input('sprin'),
            "semester" => $request->input('semester'),
            "tahun_anggaran" => $request->input('thn_anggaran'),
            "uappa" => $request->input('uappa'),
            "uappa_e1" => $request->input('uappa_e1'),
            "uappa_w" => $request->input('uappa_w'),
            "uakpa" => $request->input('uakpa'),
            "pengendali_teknis" => $request->input('pengendali_teknis'),
            "ketua_tim" => $request->input('ketua_tim'),
            "pereviu" => $request->input('pereviu'),
            "lap_realisasi" => $request->input('lap_realisasi'),
            "neraca" => $request->input('neraca'),
            "lap_operasional" => $request->input('lap_operasional'),
            "lap_perubahan" => $request->input('lap_perubahan'),
            "catatan_lap" => $request->input('catatan_lap'),
            "nomor_lap" => $request->input('nomor_lap'),
            "tanggal_lap" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal_lap'))))
          ]
        ]
      );

      $result = json_decode($requestData->getBody()->getContents(), true);
      // dd($result);
      $this->form_params = array("no_sprint" => $request->input('sprin'),
                                "semester" => $request->input('semester'),
                                "tahun_anggaran" => $request->input('thn_anggaran'),
                                "uappa" => $request->input('uappa'),
                                "uappa_e1" => $request->input('uappa_e1'),
                                "uappa_w" => $request->input('uappa_w'),
                                "uakpa" => $request->input('uakpa'),
                                "pengendali_teknis" => $request->input('pengendali_teknis'),
                                "ketua_tim" => $request->input('ketua_tim'),
                                "pereviu" => $request->input('pereviu'),
                                "lap_realisasi" => $request->input('lap_realisasi'),
                                "neraca" => $request->input('neraca'),
                                "lap_operasional" => $request->input('lap_operasional'),
                                "lap_perubahan" => $request->input('lap_perubahan'),
                                "catatan_lap" => $request->input('catatan_lap'),
                                "nomor_lap" => $request->input('nomor_lap'),
                                "tanggal_lap" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal_lap')))));

      $trail['audit_menu'] = 'Inspektorat Utama - Reviu Laporan Keuangan';
      $trail['audit_event'] = 'put';
      $trail['audit_value'] = json_encode($this->form_params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $result['comment'];
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($token,$trail);

      if ($request->file('file_upload') != ''){
          $fileName = date('Y-m-d').'_'.$id.'-'.$request->file('file_upload')->getClientOriginalName();
          $request->file('file_upload')->storeAs('IrtamaReviuLk', $fileName);

          $requestfile = $client->request('PUT', $baseUrl.'/api/reviulk/'.$id,
                  [
                      'headers' =>
                      [
                          'Authorization' => 'Bearer '.$token
                      ],
                      'form_params' => [
                          'lap_reviu' => $fileName,
                      ]
                  ]
              );
       }
      if(($result['code'] == 200) && ($result['status'] != 'error')){
        $this->kelengkapan_reviu_lk($id);
        $this->data['status'] = 'success';
        $this->data['message'] = 'Data Reviu Laporan Keuangan Berhasil Diperbarui';
      }else{
        $this->data['status'] = 'error';
        $this->data['message'] = 'Data Reviu Laporan Keuangan Gagal Diperbarui';
      }
      return back()->with('status',$this->data);
    }

    public function addirtamaLk(Request $request){
        $client = new Client();
        $requestpegawai = $client->request('GET', config('app.url_soa').'simpeg/staffBySatker?unit_id=156');
        $pegawai = json_decode($requestpegawai->getBody()->getContents(), true);
        $this->data['pegawai'] = $pegawai['data']['pegawai'];
        $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());
        return view('irtama.reviu.lk.add_irtamaLk',$this->data);
    }

    public function inputirtamaLk(Request $request)
    {
      // dd($request->all());
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');
      $client = new Client();

      $requestData = $client->request('POST', $baseUrl.'/api/reviulk',
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token
          ],
          'form_params' =>
          [
            "no_sprint" => $request->input('sprin'),
            "semester" => $request->input('semester'),
            "tahun_anggaran" => $request->input('thn_anggaran'),
            "uappa" => $request->input('uappa'),
            "uappa_e1" => $request->input('uappa_e1'),
            "uappa_w" => $request->input('uappa_w'),
            "uakpa" => $request->input('uakpa'),
            "pengendali_teknis" => $request->input('pengendali_teknis'),
            "ketua_tim" => $request->input('ketua_tim'),
            "pereviu" => $request->input('pereviu'),
            "lap_realisasi" => $request->input('lap_realisasi'),
            "neraca" => $request->input('neraca'),
            "lap_operasional" => $request->input('lap_operasional'),
            "lap_perubahan" => $request->input('lap_perubahan'),
            "catatan_lap" => $request->input('catatan_lap'),
            "nomor_lap" => $request->input('nomor_lap'),
            "tanggal_lap" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal_lap'))))
          ]
        ]
      );

      $result = json_decode($requestData->getBody()->getContents(), true);
      // dd($result);
      $inputId = $result['data']['eventID'];

      $this->form_params = array("no_sprint" => $request->input('sprin'),
                                "semester" => $request->input('semester'),
                                "tahun_anggaran" => $request->input('thn_anggaran'),
                                "uappa" => $request->input('uappa'),
                                "uappa_e1" => $request->input('uappa_e1'),
                                "uappa_w" => $request->input('uappa_w'),
                                "uakpa" => $request->input('uakpa'),
                                "pengendali_teknis" => $request->input('pengendali_teknis'),
                                "ketua_tim" => $request->input('ketua_tim'),
                                "pereviu" => $request->input('pereviu'),
                                "lap_realisasi" => $request->input('lap_realisasi'),
                                "neraca" => $request->input('neraca'),
                                "lap_operasional" => $request->input('lap_operasional'),
                                "lap_perubahan" => $request->input('lap_perubahan'),
                                "catatan_lap" => $request->input('catatan_lap'),
                                "nomor_lap" => $request->input('nomor_lap'),
                                "tanggal_lap" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal_lap')))));

      $trail['audit_menu'] = 'Inspektorat Utama - Reviu Laporan Keuangan';
      $trail['audit_event'] = 'post';
      $trail['audit_value'] = json_encode($this->form_params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $result['comment'];
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($token,$trail);


      if ($request->file('file_upload') != ''){
          $fileName = $inputId.'-'.$request->file('file_upload')->getClientOriginalName();
          $request->file('file_upload')->storeAs('IrtamaReviuLk', $fileName);

          $requestfile = $client->request('PUT', $baseUrl.'/api/reviulk/'.$inputId,
                  [
                      'headers' =>
                      [
                          'Authorization' => 'Bearer '.$token
                      ],
                      'form_params' => [
                          'lap_reviu' => $fileName,
                      ]
                  ]
              );
       }
      if( ($result['code'] == 200) && ($result['status'] != 'error')){
        $this->kelengkapan_reviu_lk($inputId);
        $this->data['status'] = 'success';
        $this->data['message'] = 'Data Reviu Laporan Keuangan Berhasil Ditambahkan';
      }else{
        $this->data['status'] = 'error';
        $this->data['message'] = 'Data Reviu Laporan Keuangan Gagal Ditambahkan';
      }
      return redirect(route('irtama_lk'))->with('status',$this->data);
    }

    public function printPageirtamaLk(Request $request){

        $page = $request->page;
        if($page){
            $page = $page;
        }else{
            $page = 1;
        }
        $result = [];
        $this->limit = config('app.limit');
        $start_number = ($this->limit * ($page -1 )) + 1;
        $url = 'api/reviulk?page='.$page;

        $data_request = execute_api($url,'GET');

        $i = $start_number;
        if(count($data_request)>=1){
          $data = $data_request['data'];
          foreach($data as $key=>$value){
            $result[$key]['No'] = $i;
            $result[$key]['Objek Reviu'] = 'UAPPA : '.$value['uappa']."\nUAPPA-E1 : ".$value['uappa_e1']."\nUAPPA-W : ".$value['uappa_w']."\nUAKPA : ".$value['uakpa'] ;
            $result[$key]['Surat Perintah'] = $value['no_sprint'];
            $result[$key]['Ketua Tim'] =$value['ketua_tim'];
            $result[$key]['Hasil reviu'] = "Hasil Reviu LRA:".$value["lap_realisasi"]."\nHasil Reviu Neraca:".$value["neraca"]."\nHasil Reviu LO:".$value["lap_operasional"]."\nHasil Reviu LPE:".$value["lap_perubahan"]."\nHasil Reviu CaLK:".$value["catatan_lap"];

            $i = $i +1;
          }
        $name = 'Reviu LK '.date('Y-m-d H:i:s');

            $this->printData($result, $name);
         //  	echo '<pre>';
          // print_r($result);
        }else{
          return false;
        }

    }

    public function irtamaRkakl(Request $request){
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
              if($tipe == 'tahun_anggaran'){
                if($request->tahun_to){
                  $this->selected['tahun_to'] = $request->tahun_to;
                }
                if($request->tahun_from){
                  $this->selected['tahun_from'] = $request->tahun_from;
                }
              }else if(($key == 'ketua_tim') || ($key == 'no_sprint') ){
                  $this->selected[$key] = $value;
                  $this->selected['keyword'] = $value;
              }elseif($key == 'status'){
                $this->selected['status'] = $value;
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
        $tahun_from = $request->tahun_from;
        $tahun_to = $request->tahun_to;
        $status = $request->status;
        $order = $request->order;
        $limit = $request->limit;
        if($tipe == 'tahun_anggaran' ){
          if($tahun_from){
            $kondisi .= '&tahun_from='.$tahun_from;
            $this->selected['tahun_from'] = $tahun_from;
            }else{
            $kondisi .='';
          }
          if($tahun_to){
            $kondisi .= '&tahun_to='.$tahun_to;
            $this->selected['tahun_to'] = $tahun_to;
          }else{
            $kondisi .='';
          }
        }elseif($tipe == 'status'){
          $kondisi .= '&status='.$status;
          $this->selected['status'] = $status;
        }
        else{
          if($tipe || $kata_kunci){
            $kondisi .= '&'.$tipe.'='.$kata_kunci;
            $this->selected['keyword'] = $kata_kunci;
          }
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
      $this->data['title'] = "Reviu Rencana Kerja Anggaran/Lembaga";
      if($request->page){
          $current_page = $request->page;
          $start_number = ($this->limit * ($request->page -1 )) + 1;
      }else{
          $current_page = 1;
          $start_number = $current_page;
      }
      $limit = 'limit='.$this->limit;
      $offset = 'page='.$current_page;
      $datas = execute_api_json('api/reviurkakl?'.$limit.'&'.$offset.$kondisi,'get');

      if( ($datas->code == 200) && ($datas->status!= 'error')){
          $this->data['data'] = $datas->data;
      }else{
          $this->data['data'] = [];
      }
      $this->data['route'] = $request->route()->getName();
      $this->data['filter'] = $this->selected;
      $this->data['delete_route']  = 'delete_irtama_rkakl';
      $this->data['path'] = $request->path();
      $this->data['route_name'] = $request->route()->getName();
      $this->data['start_number'] = $start_number;
      $this->data['current_page'] = $current_page;
      $this->data['kondisi'] = '?'.$offset.$kondisi;
      $total_item = $datas->paginate->totalpage * $this->limit;
      $filtering = false;
      if($kondisi){
        $filter = $kondisi;
        $filtering = true;
      }else{
        $filter = '/';
        $filtering = false;
      }
      $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), "/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
      $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());
      return view('irtama.reviu.rkakl.index_irtamaRkakl',$this->data);
    }

     public function editirtamaRkakl(Request $request){
       $id = $request->id;
       $baseUrl = URL::to('/');
       $token = $request->session()->get('token');
       $client = new Client();
       $requestpegawai = $client->request('GET', config('app.url_soa').'simpeg/staffBySatker?unit_id=156');
       $pegawai = json_decode($requestpegawai->getBody()->getContents(), true);
       $this->data['pegawai'] = $pegawai['data']['pegawai'];

       $requestData = $client->request('GET', $baseUrl.'/api/reviurkakl/'.$id,
         [
           'headers' =>
           [
             'Authorization' => 'Bearer '.$token
           ]
         ]
       );

       $reviurkakl = json_decode($requestData->getBody()->getContents(), true);

       $this->data['reviurkakl'] = $reviurkakl['data'];
       $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());

       return view('irtama.reviu.rkakl.edit_irtamaRkakl',$this->data);
    }

    public function updateirtamaRkakl(Request $request)
    {
      $id = $request->input('id');
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');
      $client = new Client();

      $requestData = $client->request('PUT', $baseUrl.'/api/reviurkakl/'.$id,
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token
          ],
          'form_params' =>
          [
            "no_sprint" => $request->input('no_sprint'),
            "semester" => $request->input('semester'),
            "tahun_anggaran" => $request->input('tahun_anggaran'),
            "jmlh_direviu" => $request->input('jmlh_direviu'),
            "keterangan_direviu" => $request->input('keterangan_direviu'),
            "jmlh_tdk_direviu" => $request->input('jmlh_tdk_direviu'),
            "keterangan_tdk_direviu" => $request->input('keterangan_tdk_direviu'),
            "pengendali_teknis" => $request->input('pengendali_teknis'),
            "ketua_tim" => $request->input('ketua_tim'),
            "pereviu" => $request->input('pereviu'),
            "indikatif_dukungan" => $request->input('indikatif_dukungan'),
            "indikatif_p4gn" => $request->input('indikatif_p4gn'),
            "sebaran_dukungan" => $request->input('sebaran_dukungan'),
            "sebaran_p4gn" => $request->input('sebaran_p4gn'),
            "meta_permasalahan" => json_encode($request->input('permasalahan')),
            "nomor_lap" => $request->input('nomor_lap'),
            "tanggal_lap" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal_lap'))))
          ]
        ]
      );

      $result = json_decode($requestData->getBody()->getContents(), true);
      // dd($result);
      $this->form_params = array("no_sprint" => $request->input('no_sprint'),
                                "semester" => $request->input('semester'),
                                "tahun_anggaran" => $request->input('tahun_anggaran'),
                                "jmlh_direviu" => $request->input('jmlh_direviu'),
                                "keterangan_direviu" => $request->input('keterangan_direviu'),
                                "jmlh_tdk_direviu" => $request->input('jmlh_tdk_direviu'),
                                "keterangan_tdk_direviu" => $request->input('keterangan_tdk_direviu'),
                                "pengendali_teknis" => $request->input('pengendali_teknis'),
                                "ketua_tim" => $request->input('ketua_tim'),
                                "pereviu" => $request->input('pereviu'),
                                "indikatif_dukungan" => $request->input('indikatif_dukungan'),
                                "indikatif_p4gn" => $request->input('indikatif_p4gn'),
                                "sebaran_dukungan" => $request->input('sebaran_dukungan'),
                                "sebaran_p4gn" => $request->input('sebaran_p4gn'),
                                "meta_permasalahan" => json_encode($request->input('permasalahan')),
                                "nomor_lap" => $request->input('nomor_lap'),
                                "tanggal_lap" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal_lap')))));

      $trail['audit_menu'] = 'Inspektorat Utama - Reviu Rencana Kerja Anggaran Kementerian/Lembaga';
      $trail['audit_event'] = 'put';
      $trail['audit_value'] = json_encode($this->form_params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $result['comment'];
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($token,$trail);

      if ($request->file('file_upload') != ''){
          $fileName = date('Y-m-d').'_'. $id.'-'.$request->file('file_upload')->getClientOriginalName();
          $request->file('file_upload')->storeAs('IrtamaReviuRkakl', $fileName);

          $requestfile = $client->request('PUT', $baseUrl.'/api/reviurkakl/'.$id,
                  [
                      'headers' =>
                      [
                          'Authorization' => 'Bearer '.$token
                      ],
                      'form_params' => [
                          'lap_reviu' => $fileName,
                      ]
                  ]
              );
      }
      if($result['code'] == 200 && $result['status'] != 'error'){
        $this->kelengkapan_irtama_rkakl($id);
        $this->data['status'] = 'success';
        $this->data['message'] = ' Data  Reviu Rencana Kerja Anggaran Kementerian/Lembaga Berhasil Diperbarui';

      }else{
        $this->data['status'] = 'success';
        $this->data['message'] = ' Data Reviu Rencana Kerja Anggaran Kementerian/Lembaga Gagal Diperbarui';
      }
      return back()->with('status',$this->data);
    }

    public function addirtamaRkakl(Request $request){
        $client = new Client();
        $requestpegawai = $client->request('GET', config('app.url_soa').'simpeg/staffBySatker?unit_id=156');
        $pegawai = json_decode($requestpegawai->getBody()->getContents(), true);
        $this->data['pegawai'] = $pegawai['data']['pegawai'];
        $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());
        return view('irtama.reviu.rkakl.add_irtamaRkakl',$this->data);
    }

    public function inputirtamaRkakl(Request $request)
    {
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');
      $client = new Client();

      $requestData = $client->request('POST', $baseUrl.'/api/reviurkakl',
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token
          ],
          'form_params' =>
          [
            "no_sprint" => $request->input('no_sprint'),
            "semester" => $request->input('semester'),
            "tahun_anggaran" => $request->input('tahun_anggaran'),
            "jmlh_direviu" => $request->input('jmlh_direviu'),
            "keterangan_direviu" => $request->input('keterangan_direviu'),
            "jmlh_tdk_direviu" => $request->input('jmlh_tdk_direviu'),
            "keterangan_tdk_direviu" => $request->input('keterangan_tdk_direviu'),
            "pengendali_teknis" => $request->input('pengendali_teknis'),
            "ketua_tim" => $request->input('ketua_tim'),
            "pereviu" => $request->input('pereviu'),
            "indikatif_dukungan" => $request->input('indikatif_dukungan'),
            "indikatif_p4gn" => $request->input('indikatif_p4gn'),
            "sebaran_dukungan" => $request->input('sebaran_dukungan'),
            "sebaran_p4gn" => $request->input('sebaran_p4gn'),
            "meta_permasalahan" => json_encode($request->input('permasalahan')),
            "nomor_lap" => $request->input('nomor_lap'),
            "tanggal_lap" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal_lap'))))
          ]
        ]
      );

      $result = json_decode($requestData->getBody()->getContents(), true);
      // dd($result);
      $this->form_params = array("no_sprint" => $request->input('no_sprint'),
                                "semester" => $request->input('semester'),
                                "tahun_anggaran" => $request->input('tahun_anggaran'),
                                "jmlh_direviu" => $request->input('jmlh_direviu'),
                                "keterangan_direviu" => $request->input('keterangan_direviu'),
                                "jmlh_tdk_direviu" => $request->input('jmlh_tdk_direviu'),
                                "keterangan_tdk_direviu" => $request->input('keterangan_tdk_direviu'),
                                "pengendali_teknis" => $request->input('pengendali_teknis'),
                                "ketua_tim" => $request->input('ketua_tim'),
                                "pereviu" => $request->input('pereviu'),
                                "indikatif_dukungan" => $request->input('indikatif_dukungan'),
                                "indikatif_p4gn" => $request->input('indikatif_p4gn'),
                                "sebaran_dukungan" => $request->input('sebaran_dukungan'),
                                "sebaran_p4gn" => $request->input('sebaran_p4gn'),
                                "meta_permasalahan" => json_encode($request->input('permasalahan')),
                                "nomor_lap" => $request->input('nomor_lap'),
                                "tanggal_lap" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal_lap')))));

      $trail['audit_menu'] = 'Inspektorat Utama - Reviu Rencana Kerja Anggaran Kementerian/Lembaga';
      $trail['audit_event'] = 'post';
      $trail['audit_value'] = json_encode($this->form_params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $result['comment'];
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($token,$trail);

      $inputId = $result['data']['eventID'];
      $filleName = "";
      $filleMessage = "";
      if ($request->file('file_upload') != ''){
          $fileName = date('Y-m-d').'_'.$inputId.'-'.$request->file('file_upload')->getClientOriginalName();
          try {

            $request->file('file_upload')->storeAs('IrtamaReviuRkakl', $fileName);

            $requestfile = $client->request('PUT', $baseUrl.'/api/reviurkakl/'.$inputId,
                    [
                        'headers' =>
                        [
                            'Authorization' => 'Bearer '.$token
                        ],
                        'form_params' => [
                            'lap_reviu' => $fileName,
                        ]
                    ]
                );
            $datas =  json_decode($requestfile->getBody()->getContents(), true);
            if($result['code'] == 200 && $result['status'] != 'error'){
              $filleMessage ="";
            }else{
              $filleMessage = "dengan File Gagal Diupload";
            }
          }catch(\Exception $e){
            $filleMessage = "dengan File Gagal Diupload";
          }
       }else{
        $filleMessage ="";
       }

       if($result['code'] == 200 && $result['status'] != 'error'){
          $this->kelengkapan_irtama_rkakl($inputId);
          $this->data['status'] = 'success';
          $this->data['messages'] = 'Data Irtama Reviu Rencana Kerja Anggaran kementerian/Lembaga berhasil ditambahkan '.$filleMessage;
        }else{
          $this->data['status'] = 'error';
          $this->data['messages'] = 'Data Irtama Reviu Rencana Kerja Anggaran kementerian/Lembaga gagal ditambahkan ';
        }
      return redirect(route('irtama_rkakl'))->with('status',$this->data);
    }

    public function deleteirtamaRkakl(Request $request){
      $id = $request->input('id');
        if ($request->ajax()) {
            if($id){
                $id = $request->id;
                $data_request = execute_api('api/IrtamaReviuRkakl/'.$id,'DELETE');
                return $data_request;
            }else{
                $data_request =['status'=>'error','messages'=>'Data Reviu Rencana Kerja Anggaran/Lembaga Gagal Dihapus'];
                return $data_request;
            }
        }
    }

    public function printPageirtamaRkakl(Request $request){

        $page = $request->page;
        if($page){
            $page = $page;
        }else{
            $page = 1;
        }
        $result = [];
        $this->limit = config('app.limit');
        $start_number = ($this->limit * ($page -1 )) + 1;
        $url = 'api/reviurkakl?page='.$page;

        $data_request = execute_api($url,'GET');

        $i = $start_number;
        if(count($data_request)>=1){
          $data = $data_request['data'];
          foreach($data as $key=>$value){
            $result[$key]['No'] = $i;
            $result[$key]['Ketua Tim'] =$value['ketua_tim'];
            $result[$key]['Surat Perintah'] = $value['no_sprint'];
            $result[$key]['Tanggal Laporan'] = ( $value['tanggal_lap'] ? date('d/m/Y',strtotime($value['tanggal_lap'])) : '');

            $i = $i +1;
          }
        $name = 'Reviu RKAKL '.date('Y-m-d H:i:s');

            $this->printData($result, $name);
         //  	echo '<pre>';
          // print_r($result);
        }else{
          return false;
        }

    }

    public function irtamaRkbmn(Request $request){
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
            if($tipe == 'tahun_anggaran'){
              if($request->tahun_to){
                $this->selected['tahun_to'] = $request->tahun_to;
              }
              if($request->tahun_from){
                $this->selected['tahun_from'] = $request->tahun_from;
              }
            }else if( ($key == 'tgl_from') || ($key == 'tgl_to') ){
              $this->selected[$key] = date('d/m/Y',strtotime($value));
            }else if(($key == 'ketua_tim') || ($key == 'no_sprint') ){
              $this->selected[$key] = $value;
              $this->selected['keyword'] = $value;
            }elseif($key == 'status'){
              $this->selected['status'] = $value;
            }
            $this->selected['order'] = $request->order;
            $this->selected['limit'] = $request->limit;
            $this->data['filter'] = $this->selected;
          }
        }
      }else{
        $post = $request->all();
        $tipe = $request->tipe;
        $kata_kunci = $request->kata_kunci;
        $tgl_from = $request->tgl_from;
        $tgl_to = $request->tgl_to;
        $tahun_from = $request->tahun_from;
        $tahun_from = $request->tahun_from;
        $tahun_to = $request->tahun_to;
        $order = $request->order;
        $limit = $request->limit;
        $status = $request->status;

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
        }else if($tipe == 'tahun_anggaran'){
          $kondisi .= '&tahun_to='. $tahun_to;
          $this->selected['tahun_to'] = $tahun_to;
          $kondisi .= '&tahun_from='. $tahun_from;
          $this->selected['tahun_from'] = $tahun_from;
        }else if($tipe == 'status'){
          $kondisi .= '&status='. $status;
          $this->selected['status'] = $status;
        }else{
          if($tipe || $kata_kunci){
              $kondisi .= '&'.$tipe.'='.$kata_kunci;
              $this->selected['keyword'] = $kata_kunci;
            }
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

      $this->data['title'] = "Reviu Rencana Kebutuhan Barang Milik Negara";
      if($request->page){
          $current_page = $request->page;
          $start_number = ($this->limit * ($request->page -1 )) + 1;
      }else{
          $current_page = 1;
          $start_number = $current_page;
      }
      $limit = 'limit='.$this->limit;
      $offset = 'page='.$current_page;
      $datas = execute_api_json('api/reviurkbmn?'.$limit.'&'.$offset.$kondisi,'get');

      if($datas->code == 200){
          $this->data['data'] = $datas->data;
      }else{
          $this->data['data'] = [];
      }
      $this->data['route'] = $request->route()->getName();
      $this->data['filter'] = $this->selected;
      $this->data['delete_route']  = 'delete_irtama_rkbmn';
      $this->data['path'] = $request->path();
      $this->data['route_name'] = $request->route()->getName();
      $this->data['start_number'] = $start_number;
      $this->data['current_page'] = $current_page;
      $this->data['kondisi'] ='?'. $offset.$kondisi;
      $total_item = $datas->paginate->totalpage * $this->limit;
      $filtering = false;
      if($kondisi){
        $filter = $kondisi;
        $filtering = true;
      }else{
        $filter = '/';
        $filtering = false;
      }
      $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), "/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
      $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());
      return view('irtama.reviu.rkbmn.index_irtamaRkbmn',$this->data);
    }

     public function editirtamaRkbmn(Request $request){
        $id = $request->id;

        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');
        $client = new Client();
        $requestpegawai = $client->request('GET', config('app.url_soa').'simpeg/staffBySatker?unit_id=156');
        $pegawai = json_decode($requestpegawai->getBody()->getContents(), true);
        $this->data['pegawai'] = $pegawai['data']['pegawai'];

        $requestData = $client->request('GET', $baseUrl.'/api/reviurkbmn/'.$id,
          [
            'headers' =>
            [
              'Authorization' => 'Bearer '.$token
            ]
          ]
        );

        $reviurkbmn = json_decode($requestData->getBody()->getContents(), true);
        // dd($reviurkbmn);
        $instansi = $this->globalinstansi($request->session()->get('wilayah'), $token);
        $this->data['instansi'] = $instansi;
        $this->data['reviurkbmn'] = $reviurkbmn['data'];
        $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());
        return view('irtama.reviu.rkbmn.edit_irtamaRkbmn',$this->data);
    }

    public function updateirtamaRkbmn(Request $request)
    {
      $id = $request->input('id');
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');
      $client = new Client();

      $requestData = $client->request('PUT', $baseUrl.'/api/reviurkbmn/'.$id,
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token
          ],
          'form_params' =>
          [
            "no_sprint" => $request->input('no_sprint'),
            "tahun_anggaran" => $request->input('thn_anggaran'),
            "jmlh_direviu" => $request->input('jmlh_direviu'),
            "keterangan_direviu" => $request->input('keterangan_direviu'),
            "jmlh_tdk_direviu" => $request->input('jmlh_tdk_direviu'),
            "keterangan_tdk_direviu" => $request->input('keterangan_tdk_direviu'),
            "pengendali_teknis" => $request->input('pengendali_teknis'),
            "ketua_tim" => $request->input('ketua_tim'),
            "pereviu" => $request->input('pereviu'),
            "kelengkapan" => $request->input('kelengkapan_dok'),
            "ket_kelengkapan" => $request->input('keterangan_dokumen'),
            "kesesuaian" => $request->input('kesesuaian_lk'),
            "ket_kesesuaian" => $request->input('keterangan_kesesuaian'),
            "kantor_jmlh_usulan" => $request->input('kantor_jmlh_usulan'),
            "kantor_jmlh_disetujui" => $request->input('kantor_jmlh_disetujui'),
            "kantor_jmlh_tdk_disetujui" => $request->input('kantor_jmlh_tdk_disetujui'),
            "kantor_alasan" => $request->input('kantor_alasan'),
            "rumah_jmlh_usulan" => $request->input('rumah_jmlh_usulan'),
            "rumah_jmlh_disetujui" => $request->input('rumah_jmlh_disetujui'),
            "rumah_jmlh_tdk_disetujui" => $request->input('rumah_jmlh_tdk_disetujui'),
            "rumah_alasan" => $request->input('rumah_alasan'),
            "tanahkantor_jmlh_usulan" => $request->input('tanahkantor_jmlh_usulan'),
            "tanahkantor_jmlh_disetujui" => $request->input('tanahkantor_jmlh_disetujui'),
            "tanahkantor_jmlh_tdk_disetujui" => $request->input('tanahkantor_jmlh_tdk_disetujui'),
            "tanahkantor_alasan" => $request->input('tanahkantor_alasan'),
            "tanahrumah_jmlh_usulan" => $request->input('tanahrumah_jmlh_usulan'),
            "tanahrumah_jmlh_disetujui" => $request->input('tanahrumah_jmlh_disetujui'),
            "tanahrumah_jmlh_tdk_disetujui" => $request->input('tanahrumah_jmlh_tdk_disetujui'),
            "tanahrumah_alasan" => $request->input('tanahrumah_alasan'),
            "angkutan_jmlh_usulan" => $request->input('angkutan_jmlh_usulan'),
            "angkutan_jmlh_disetujui" => $request->input('angkutan_jmlh_disetujui'),
            "angkutan_jmlh_tdk_disetujui" => $request->input('angkutan_jmlh_tdk_disetujui'),
            "angkutan_alasan" => $request->input('angkutan_alasan'),
            "pemeliharaan_jmlh_usulan" => $request->input('pemeliharaan_jmlh_usulan'),
            "pemeliharaan_jmlh_disetujui" => $request->input('pemeliharaan_jmlh_disetujui'),
            "pemeliharaan_jmlh_tdk_disetujui" => $request->input('pemeliharaan_jmlh_tdk_disetujui'),
            "pemeliharaan_alasan" => $request->input('pemeliharaan_alasan'),
            "nomor_lap" => $request->input('nomor_lap'),
            "tanggal_lap" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal_lap'))))
          ]
        ]
      );

      $result = json_decode($requestData->getBody()->getContents(), true);
      // dd($result);
      $this->form_params = array("no_sprint" => $request->input('no_sprint'),
                                "tahun_anggaran" => $request->input('thn_anggaran'),
                                "jmlh_direviu" => $request->input('jmlh_direviu'),
                                "keterangan_direviu" => $request->input('keterangan_direviu'),
                                "jmlh_tdk_direviu" => $request->input('jmlh_tdk_direviu'),
                                "keterangan_tdk_direviu" => $request->input('keterangan_tdk_direviu'),
                                "pengendali_teknis" => $request->input('pengendali_teknis'),
                                "ketua_tim" => $request->input('ketua_tim'),
                                "pereviu" => $request->input('pereviu'),
                                "kelengkapan" => $request->input('kelengkapan_dok'),
                                "ket_kelengkapan" => $request->input('keterangan_dokumen'),
                                "kesesuaian" => $request->input('kesesuaian_lk'),
                                "ket_kesesuaian" => $request->input('keterangan_kesesuaian'),
                                "kantor_jmlh_usulan" => $request->input('kantor_jmlh_usulan'),
                                "kantor_jmlh_disetujui" => $request->input('kantor_jmlh_disetujui'),
                                "kantor_jmlh_tdk_disetujui" => $request->input('kantor_jmlh_tdk_disetujui'),
                                "kantor_alasan" => $request->input('kantor_alasan'),
                                "rumah_jmlh_usulan" => $request->input('rumah_jmlh_usulan'),
                                "rumah_jmlh_disetujui" => $request->input('rumah_jmlh_disetujui'),
                                "rumah_jmlh_tdk_disetujui" => $request->input('rumah_jmlh_tdk_disetujui'),
                                "rumah_alasan" => $request->input('rumah_alasan'),
                                "tanahkantor_jmlh_usulan" => $request->input('tanahkantor_jmlh_usulan'),
                                "tanahkantor_jmlh_disetujui" => $request->input('tanahkantor_jmlh_disetujui'),
                                "tanahkantor_jmlh_tdk_disetujui" => $request->input('tanahkantor_jmlh_tdk_disetujui'),
                                "tanahkantor_alasan" => $request->input('tanahkantor_alasan'),
                                "tanahrumah_jmlh_usulan" => $request->input('tanahrumah_jmlh_usulan'),
                                "tanahrumah_jmlh_disetujui" => $request->input('tanahrumah_jmlh_disetujui'),
                                "tanahrumah_jmlh_tdk_disetujui" => $request->input('tanahrumah_jmlh_tdk_disetujui'),
                                "tanahrumah_alasan" => $request->input('tanahrumah_alasan'),
                                "angkutan_jmlh_usulan" => $request->input('angkutan_jmlh_usulan'),
                                "angkutan_jmlh_disetujui" => $request->input('angkutan_jmlh_disetujui'),
                                "angkutan_jmlh_tdk_disetujui" => $request->input('angkutan_jmlh_tdk_disetujui'),
                                "angkutan_alasan" => $request->input('angkutan_alasan'),
                                "pemeliharaan_jmlh_usulan" => $request->input('pemeliharaan_jmlh_usulan'),
                                "pemeliharaan_jmlh_disetujui" => $request->input('pemeliharaan_jmlh_disetujui'),
                                "pemeliharaan_jmlh_tdk_disetujui" => $request->input('pemeliharaan_jmlh_tdk_disetujui'),
                                "pemeliharaan_alasan" => $request->input('pemeliharaan_alasan'),
                                "nomor_lap" => $request->input('nomor_lap'),
                                "tanggal_lap" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal_lap')))));

      $trail['audit_menu'] = 'Inspektorat Utama - Reviu Rencana Kebutuhan Barang Milik Negara';
      $trail['audit_event'] = 'put';
      $trail['audit_value'] = json_encode($this->form_params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $result['comment'];
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($token,$trail);


      if ($request->file('file_upload') != ''){
          $fileName = $id.'-'.$request->file('file_upload')->getClientOriginalName();
          $request->file('file_upload')->storeAs('IrtamaReviuRkbmn', $fileName);

          $requestfile = $client->request('PUT', $baseUrl.'/api/reviurkbmn/'.$id,
                  [
                      'headers' =>
                      [
                          'Authorization' => 'Bearer '.$token
                      ],
                      'form_params' => [
                          'lap_reviu' => $fileName,
                      ]
                  ]
              );
       }
      if($result['code'] == 200 && $result['status'] != 'error'){
        $this->kelengkapan_irtama_rkbmn($id);
        $this->data['status'] = 'success';
        $this->data['message'] = 'Data Reviu Rencana Kebutuhan Barang Milik Negara Berhasil Diperbarui';
      }else{
        $this->data['status'] = 'error';
        $this->data['message'] = 'Data Reviu Rencana Kebutuhan Barang Milik Negara Gagal Diperbarui';
      }
      return back()->with('status',$this->data);
    }

    public function addirtamaRkbmn(Request $request){
        $client = new Client();
        $requestpegawai = $client->request('GET', config('app.url_soa').'simpeg/staffBySatker?unit_id=156');
        $pegawai = json_decode($requestpegawai->getBody()->getContents(), true);
        $this->data['pegawai'] = $pegawai['data']['pegawai'];
        $token = $request->session()->get('token');
        $instansi = $this->globalinstansi($request->session()->get('wilayah'), $token);
        $this->data['instansi'] = $instansi;
        $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());
        return view('irtama.reviu.rkbmn.add_irtamaRkbmn', $this->data);
    }

    public function inputirtamaRkbmn(Request $request)
    {
      // dd($request->all());
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');
      $client = new Client();

      $requestData = $client->request('POST', $baseUrl.'/api/reviurkbmn',
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token
          ],
          'form_params' =>
          [
            "no_sprint" => $request->input('no_sprint'),
            "tahun_anggaran" => $request->input('thn_anggaran'),
            "jmlh_direviu" => $request->input('jmlh_direviu'),
            "keterangan_direviu" => $request->input('keterangan_direviu'),
            "jmlh_tdk_direviu" => $request->input('jmlh_tdk_direviu'),
            "keterangan_tdk_direviu" => $request->input('keterangan_tdk_direviu'),
            "pengendali_teknis" => $request->input('pengendali_teknis'),
            "ketua_tim" => $request->input('ketua_tim'),
            "pereviu" => $request->input('pereviu'),
            "kelengkapan" => $request->input('kelengkapan_dok'),
            "ket_kelengkapan" => $request->input('keterangan_dokumen'),
            "kesesuaian" => $request->input('kesesuaian_lk'),
            "ket_kesesuaian" => $request->input('keterangan_kesesuaian'),
            "kantor_jmlh_usulan" => $request->input('kantor_jmlh_usulan'),
            "kantor_jmlh_disetujui" => $request->input('kantor_jmlh_disetujui'),
            "kantor_jmlh_tdk_disetujui" => $request->input('kantor_jmlh_tdk_disetujui'),
            "kantor_alasan" => $request->input('kantor_alasan'),
            "rumah_jmlh_usulan" => $request->input('rumah_jmlh_usulan'),
            "rumah_jmlh_disetujui" => $request->input('rumah_jmlh_disetujui'),
            "rumah_jmlh_tdk_disetujui" => $request->input('rumah_jmlh_tdk_disetujui'),
            "rumah_alasan" => $request->input('rumah_alasan'),
            "tanahkantor_jmlh_usulan" => $request->input('tanahkantor_jmlh_usulan'),
            "tanahkantor_jmlh_disetujui" => $request->input('tanahkantor_jmlh_disetujui'),
            "tanahkantor_jmlh_tdk_disetujui" => $request->input('tanahkantor_jmlh_tdk_disetujui'),
            "tanahkantor_alasan" => $request->input('tanahkantor_alasan'),
            "tanahrumah_jmlh_usulan" => $request->input('tanahrumah_jmlh_usulan'),
            "tanahrumah_jmlh_disetujui" => $request->input('tanahrumah_jmlh_disetujui'),
            "tanahrumah_jmlh_tdk_disetujui" => $request->input('tanahrumah_jmlh_tdk_disetujui'),
            "tanahrumah_alasan" => $request->input('tanahrumah_alasan'),
            "angkutan_jmlh_usulan" => $request->input('angkutan_jmlh_usulan'),
            "angkutan_jmlh_disetujui" => $request->input('angkutan_jmlh_disetujui'),
            "angkutan_jmlh_tdk_disetujui" => $request->input('angkutan_jmlh_tdk_disetujui'),
            "angkutan_alasan" => $request->input('angkutan_alasan'),
            "pemeliharaan_jmlh_usulan" => $request->input('pemeliharaan_jmlh_usulan'),
            "pemeliharaan_jmlh_disetujui" => $request->input('pemeliharaan_jmlh_disetujui'),
            "pemeliharaan_jmlh_tdk_disetujui" => $request->input('pemeliharaan_jmlh_tdk_disetujui'),
            "pemeliharaan_alasan" => $request->input('pemeliharaan_alasan'),
            "nomor_lap" => $request->input('nomor_lap'),
            "tanggal_lap" =>(  $request->input('tanggal_lap') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal_lap')))) : '')
          ]
        ]
      );

      $result = json_decode($requestData->getBody()->getContents(), true);
      $inputId = $result['data']['eventID'];
      $this->form_params = array("no_sprint" => $request->input('no_sprint'),
      "tahun_anggaran" => $request->input('thn_anggaran'),
      "jmlh_direviu" => $request->input('jmlh_direviu'),
      "keterangan_direviu" => $request->input('keterangan_direviu'),
      "jmlh_tdk_direviu" => $request->input('jmlh_tdk_direviu'),
      "keterangan_tdk_direviu" => $request->input('keterangan_tdk_direviu'),
      "pengendali_teknis" => $request->input('pengendali_teknis'),
      "ketua_tim" => $request->input('ketua_tim'),
      "pereviu" => $request->input('pereviu'),
      "kelengkapan" => $request->input('kelengkapan_dok'),
      "ket_kelengkapan" => $request->input('keterangan_dokumen'),
      "kesesuaian" => $request->input('kesesuaian_lk'),
      "ket_kesesuaian" => $request->input('keterangan_kesesuaian'),
      "kantor_jmlh_usulan" => $request->input('kantor_jmlh_usulan'),
      "kantor_jmlh_disetujui" => $request->input('kantor_jmlh_disetujui'),
      "kantor_jmlh_tdk_disetujui" => $request->input('kantor_jmlh_tdk_disetujui'),
      "kantor_alasan" => $request->input('kantor_alasan'),
      "rumah_jmlh_usulan" => $request->input('rumah_jmlh_usulan'),
      "rumah_jmlh_disetujui" => $request->input('rumah_jmlh_disetujui'),
      "rumah_jmlh_tdk_disetujui" => $request->input('rumah_jmlh_tdk_disetujui'),
      "rumah_alasan" => $request->input('rumah_alasan'),
      "tanahkantor_jmlh_usulan" => $request->input('tanahkantor_jmlh_usulan'),
      "tanahkantor_jmlh_disetujui" => $request->input('tanahkantor_jmlh_disetujui'),
      "tanahkantor_jmlh_tdk_disetujui" => $request->input('tanahkantor_jmlh_tdk_disetujui'),
      "tanahkantor_alasan" => $request->input('tanahkantor_alasan'),
      "tanahrumah_jmlh_usulan" => $request->input('tanahrumah_jmlh_usulan'),
      "tanahrumah_jmlh_disetujui" => $request->input('tanahrumah_jmlh_disetujui'),
      "tanahrumah_jmlh_tdk_disetujui" => $request->input('tanahrumah_jmlh_tdk_disetujui'),
      "tanahrumah_alasan" => $request->input('tanahrumah_alasan'),
      "angkutan_jmlh_usulan" => $request->input('angkutan_jmlh_usulan'),
      "angkutan_jmlh_disetujui" => $request->input('angkutan_jmlh_disetujui'),
      "angkutan_jmlh_tdk_disetujui" => $request->input('angkutan_jmlh_tdk_disetujui'),
      "angkutan_alasan" => $request->input('angkutan_alasan'),
      "pemeliharaan_jmlh_usulan" => $request->input('pemeliharaan_jmlh_usulan'),
      "pemeliharaan_jmlh_disetujui" => $request->input('pemeliharaan_jmlh_disetujui'),
      "pemeliharaan_jmlh_tdk_disetujui" => $request->input('pemeliharaan_jmlh_tdk_disetujui'),
      "pemeliharaan_alasan" => $request->input('pemeliharaan_alasan'),
      "nomor_lap" => $request->input('nomor_lap'),
      "tanggal_lap" =>(  $request->input('tanggal_lap') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal_lap')))) : ''));

      $trail['audit_menu'] = 'Inspektorat Utama - Reviu Rencana Kebutuhan Barang Milik Negara';
      $trail['audit_event'] = 'post';
      $trail['audit_value'] = json_encode($this->form_params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $result['comment'];
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($token,$trail);

      $fileMessage = "";
      if ($request->file('file_upload') != ''){
          $fileName = date('Y-m-d').'-'.$inputId.'-'.$request->file('file_upload')->getClientOriginalName();
          try {
            $request->file('file_upload')->storeAs('IrtamaReviuRkbmn', $fileName);

            $requestfile = $client->request('PUT', $baseUrl.'/api/reviurkbmn/'.$inputId,
                    [
                        'headers' =>
                        [
                            'Authorization' => 'Bearer '.$token
                        ],
                        'form_params' => [
                            'lap_reviu' => $fileName,
                        ]
                    ]
                );
            $datas = json_decode($requestfile->getBody()->getContents(), true);
            if($datas['code'] == 200 && $datas['status'] != 'error'){
              $fileMessage = "";
             }else{
              $fileMessage = " Dengan File Gagal Diunngah.";
            }

          }catch(\Exception $e){
            $fileMessage = "";
          }
      }
      if($result['code'] == 200 && $result['status'] != 'error'){
        $this->kelengkapan_irtama_rkbmn($inputId);
        $this->data['status'] = 'success';
        $this->data['message'] = 'Data Reviu Rencana Kebutuhan Barang Milik Negara Berhasil Ditambahkan '.$fileMessage;
      }else{
        $this->data['status'] = 'error_message';
        $this->data['message'] = 'Data Reviu Rencana Kebutuhan Barang Milik Negara Gagal Ditambahkan';
      }
      return redirect(route('irtama_rkbmn'))->with('status',$this->data);
    }

    public function deleteirtamaRkbmn(Request $request){
      $id = $request->input('id');
        if ($request->ajax()) {
            if($id){
                $id = $request->id;
                $data_request = execute_api('api/reviurkbmn/'.$id,'DELETE');

                $this->form_params['delete_id'] = $id;
                $trail['audit_menu'] = 'Inspektorat Utama - Reviu Rencana Kebutuhan Barang Milik Negara';
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
                $data_request =['status'=>'error','messages'=>'Data Reviu Rencana Kerja Anggaran/Lembaga Gagal Dihapus'];
                return $data_request;
            }
        }
    }

    public function printPageirtamaRkbmn(Request $request){

        $page = $request->page;
        if($page){
            $page = $page;
        }else{
            $page = 1;
        }
        $result = [];
        $this->limit = config('app.limit');
        $start_number = ($this->limit * ($page -1 )) + 1;
        $url = 'api/reviurkbmn?page='.$page;

        $data_request = execute_api($url,'GET');

        $i = $start_number;
        if(count($data_request)>=1){
          $data = $data_request['data'];
          foreach($data as $key=>$value){
            $result[$key]['No'] = $i;
            $result[$key]['Surat Perintah'] = $value['no_sprint'];
            $result[$key]['Ketua Tim'] =$value['ketua_tim'];
            $result[$key]['Tanggal Laporan'] = ( $value['tanggal_lap'] ? date('d/m/Y',strtotime($value['tanggal_lap'])) : '');

            $i = $i +1;
          }
        $name = 'Reviu RKBMN '.date('Y-m-d H:i:s');

            $this->printData($result, $name);
         //  	echo '<pre>';
          // print_r($result);
        }else{
          return false;
        }

    }

    public function irtamaLkip(Request $request){
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
              }else if($tipe == 'tahun_anggaran'){
                if($request->tahun_to){
                  $this->selected['tahun_to'] = $request->tahun_to;
                }
                if($request->tahun_from){
                  $this->selected['tahun_from'] = $request->tahun_from;
                }
              }else if($key == 'status') {
                $this->selected['status'] = $value;
              }else if(($key == 'no_sprint') || ($key == 'sasaran')){
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
        $tahun_from = $request->tahun_from;
        $tahun_to = $request->tahun_to;
        $status = $request->status;

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
        }else if($tipe == 'tahun_anggaran'){
          $kondisi .= '&tahun_to='. $tahun_to;
          $this->selected['tahun_to'] = $tahun_to;
          $kondisi .= '&tahun_from='. $tahun_from;
          $this->selected['tahun_from'] = $tahun_from;
        }else if($tipe == 'status'){
          $kondisi .= '&status='. $status;
          $this->selected['status'] = $status;
        }else{
          if($tipe || $kata_kunci){
              $kondisi .= '&'.$tipe.'='.$kata_kunci;
              $this->selected['keyword'] = $kata_kunci;
            }
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
      $this->data['title'] = "Reviu Laporan Kinerja Instansi Pemerintah";
      if($request->page){
          $current_page = $request->page;
          $start_number = ($this->limit * ($request->page -1 )) + 1;
      }else{
          $current_page = 1;
          $start_number = $current_page;
      }
      $limit = 'limit='.$this->limit;
      $offset = 'page='.$current_page;
      $datas = execute_api_json('api/reviulkip?'.$limit.'&'.$offset.$kondisi,'get');

      if($datas->code == 200){
          $this->data['data'] = $datas->data;
      }else{
          $this->data['data'] = [];
      }
      $this->data['route'] = $request->route()->getName();
      $this->data['filter'] = $this->selected;
      $this->data['delete_route']  = 'delete_irtama_lkip';
      $this->data['path'] = $request->path();
      $this->data['route_name'] = $request->route()->getName();
      $this->data['start_number'] = $start_number;
      $this->data['current_page'] = $current_page;
      $this->data['kondisi'] = '?'.$offset.$kondisi;
      $total_item = $datas->paginate->totalpage * $this->limit;
      $filtering = false;
      if($kondisi){
        $filter = $kondisi;
        $filtering = true;
      }else{
        $filter = '/';
        $filtering = false;
      }
      $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), "/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );

        // $client = new Client();
        // if ($request->input('page')) {
        //   $page = $request->input('page');
        // } else {
        //   $page = 1;
        // }

        // $baseUrl = URL::to('/');
        // $token = $request->session()->get('token');

        // $requestAudit = $client->request('GET', $baseUrl.'/api/reviulkip?page='.$page,
        //   [
        //     'headers' =>
        //     [
        //       'Authorization' => 'Bearer '.$token
        //     ]
        //   ]
        // );
        // $reviulkip = json_decode($requestAudit->getBody()->getContents(), true);

        // $this->data['reviulkip'] = $reviulkip['data'];

        // $page = $reviulkip['paginate'];
        // $this->data['title'] = "reviulkip";
        // $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        // $this->data['token'] = $token;


        // $user_id = Auth::user()->user_id;
        // $detail = MainModel::getUserDetail($user_id);
        // $this->data['data_detail'] = $detail;
        // $this->data['path'] = $request->path();
        // $this->data['page'] = $page;
        $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());
        return view('irtama.reviu.lkip.index_irtamaLkip',$this->data);
    }

     public function editirtamaLkip(Request $request){
       $id = $request->id;
       $baseUrl = URL::to('/');
       $token = $request->session()->get('token');
       $client = new Client();
       $requestpegawai = $client->request('GET', config('app.url_soa').'simpeg/staffBySatker?unit_id=156');
       $pegawai = json_decode($requestpegawai->getBody()->getContents(), true);
       $this->data['pegawai'] = $pegawai['data']['pegawai'];


       $requestData = $client->request('GET', $baseUrl.'/api/reviulkip/'.$id,
         [
           'headers' =>
           [
             'Authorization' => 'Bearer '.$token
           ]
         ]
       );

       $reviulkip = json_decode($requestData->getBody()->getContents(), true);
       // dd($reviulkip);
       $this->data['reviulkip'] = $reviulkip['data'];
       $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());
       return view('irtama.reviu.lkip.edit_irtamaLkip',$this->data);
    }

    public function updateirtamaLkip(Request $request)
    {
      // dd($request->all());
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');
      $client = new Client();
      $id = $request->input('id');

      $requestData = $client->request('PUT', $baseUrl.'/api/reviulkip/'.$id,
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token
          ],
          'form_params' =>
          [
            "no_sprint" => $request->input('no_sprint'),
            "tahun_anggaran" => $request->input('tahun_anggaran'),
            "pengendali_teknis" => $request->input('pengendali_teknis'),
            "ketua_tim" => $request->input('ketua_tim'),
            "pereviu" => $request->input('pereviu'),
            "sasaran" => $request->input('sasaran'),
            "meta_indikator" => json_encode($request->input('meta_indikator')),
            "nomor_lap" => $request->input('nomor_lap'),
            "tanggal_lap" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal_lap'))))
          ]
        ]
      );

      $result = json_decode($requestData->getBody()->getContents(), true);
      // dd($result);
      $this->form_params = array("no_sprint" => $request->input('no_sprint'),
                                "tahun_anggaran" => $request->input('tahun_anggaran'),
                                "pengendali_teknis" => $request->input('pengendali_teknis'),
                                "ketua_tim" => $request->input('ketua_tim'),
                                "pereviu" => $request->input('pereviu'),
                                "sasaran" => $request->input('sasaran'),
                                "meta_indikator" => json_encode($request->input('meta_indikator')),
                                "nomor_lap" => $request->input('nomor_lap'),
                                "tanggal_lap" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal_lap')))));

      $trail['audit_menu'] = 'Inspektorat Utama - Reviu Laporan Kinerja Instansi Pemerintah';
      $trail['audit_event'] = 'put';
      $trail['audit_value'] = json_encode($this->form_params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $result['comment'];
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($token,$trail);


      if ($request->file('file_upload1') != ''){
          $fileName = $id.'-checklist-'.$request->file('file_upload1')->getClientOriginalName();
          $request->file('file_upload1')->storeAs('IrtamaReviuLkip', $fileName);

          $requestfile1 = $client->request('PUT', $baseUrl.'/api/reviulkip/'.$id,
                  [
                      'headers' =>
                      [
                          'Authorization' => 'Bearer '.$token
                      ],
                      'form_params' => [
                          'checklist_reviu' => $fileName,
                      ]
                  ]
              );

              $resulti1 = json_decode($requestfile1->getBody()->getContents(), true);
              // dd($resulti);
      }


      if ($request->file('file_upload2') != ''){
          $fileName = date('Y-m-d').'_'.$id.'-lap-'.$request->file('file_upload2')->getClientOriginalName();
          $request->file('file_upload2')->storeAs('IrtamaReviuLkip', $fileName);

          $requestfile2 = $client->request('PUT', $baseUrl.'/api/reviulkip/'.$id,
                  [
                      'headers' =>
                      [
                          'Authorization' => 'Bearer '.$token
                      ],
                      'form_params' => [
                          'lap_reviu' => $fileName,
                      ]
                  ]
              );

              $resulti2 = json_decode($requestfile2->getBody()->getContents(), true);
              // dd($resulti);
      }
      if($result['code']==200 && $result['status'] != 'error'){
        $this->kelengkapan_irtama_lkip( $id);
        $this->data['status'] = 'success';
        $this->data['message'] = 'Data Reviu Laporan Kinerja Instansi Pemerintah Berhasil Diperbarui ';
      }else{
        $this->data['status'] = 'error';
        $this->data['message'] = 'Data Reviu Laporan Kinerja Instansi Pemerintah Gagal Diperbarui ';
      }
      return back()->with('status',$this->data);
    }

    public function addirtamaLkip(Request $request){
        $client = new Client();
        $requestpegawai = $client->request('GET', config('app.url_soa').'simpeg/staffBySatker?unit_id=156');
        $pegawai = json_decode($requestpegawai->getBody()->getContents(), true);
        $this->data['pegawai'] = $pegawai['data']['pegawai'];
        $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());
        return view('irtama.reviu.lkip.add_irtamaLkip',$this->data);
    }

    public function inputirtamaLkip(Request $request)
    {
      // dd($request->all());
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');
      $client = new Client();

      $requestData = $client->request('POST', $baseUrl.'/api/reviulkip',
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token
          ],
          'form_params' =>
          [
            "no_sprint" => $request->input('no_sprint'),
            "tahun_anggaran" => $request->input('tahun_anggaran'),
            "pengendali_teknis" => $request->input('pengendali_teknis'),
            "ketua_tim" => $request->input('ketua_tim'),
            "pereviu" => $request->input('pereviu'),
            "sasaran" => $request->input('sasaran'),
            "meta_indikator" => json_encode($request->input('meta_indikator')),
            "nomor_lap" => $request->input('nomor_lap'),
            "tanggal_lap" =>(  $request->input('tanggal_lap') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal_lap')))) : '')
          ]
        ]
      );

      $result = json_decode($requestData->getBody()->getContents(), true);
      // dd($result);
      $inputId = $result['data']['eventID'];
      $this->form_params = array("no_sprint" => $request->input('no_sprint'),
                                "tahun_anggaran" => $request->input('tahun_anggaran'),
                                "pengendali_teknis" => $request->input('pengendali_teknis'),
                                "ketua_tim" => $request->input('ketua_tim'),
                                "pereviu" => $request->input('pereviu'),
                                "sasaran" => $request->input('sasaran'),
                                "meta_indikator" => json_encode($request->input('meta_indikator')),
                                "nomor_lap" => $request->input('nomor_lap'),
                                "tanggal_lap" =>(  $request->input('tanggal_lap') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal_lap')))) : ''));

      $trail['audit_menu'] = 'Inspektorat Utama - Reviu Laporan Kinerja Instansi Pemerintah';
      $trail['audit_event'] = 'post';
      $trail['audit_value'] = json_encode($this->form_params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $result['comment'];
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($token,$trail);

      $fileMessage = "";
      if ($request->file('file_upload1') != ''){
          $fileName = date('Y-m-d').'_'.$inputId.'-checklist-'.$request->file('file_upload1')->getClientOriginalName();
          try {
            $request->file('file_upload1')->storeAs('IrtamaReviuLkip', $fileName);
            $requestfile1 = $client->request('PUT', $baseUrl.'/api/reviulkip/'.$inputId,
                  [
                      'headers' =>
                      [
                          'Authorization' => 'Bearer '.$token
                      ],
                      'form_params' => [
                          'checklist_reviu' => $fileName,
                      ]
                  ]
              );

            $resulti1 = json_decode($requestfile1->getBody()->getContents(), true);
            if($resulti1['code'] == 200 && $resulti1['status'] != 'error'){
              $fileMessage = "";
            }else{
              $fileMessage = ' Dengan File Gagal Diunggah';
            }
          }catch(\Exception $e){
            $fileMessage = ' Dengan File Gagal Diunggah '.$e->getMessage();
          }
              // dd($resulti);
      }


      if ($request->file('file_upload2') != ''){
          $fileName = date('Y-m-d').'_'.$inputId.'-lap-'.$request->file('file_upload2')->getClientOriginalName();
          try {
            $request->file('file_upload2')->storeAs('IrtamaReviuLkip', $fileName);
            $requestfile2 = $client->request('PUT', $baseUrl.'/api/reviulkip/'.$inputId,
                  [
                      'headers' =>
                      [
                          'Authorization' => 'Bearer '.$token
                      ],
                      'form_params' => [
                          'lap_reviu' => $fileName,
                      ]
                  ]
              );

            $resulti2 = json_decode($requestfile2->getBody()->getContents(), true);
            if($resulti2['code'] == 200 && $resulti2['status'] != 'error'){
              $fileMessage = "";
            }else{
              $fileMessage = ' Dengan File Gagal Diunggah';
            }
          }catch (\Exception $e){
            $fileMessage = ' Dengan File Gagal Diunggah'.$e->getMessage();
          }
              // dd($resulti);
      }
      if($result['code'] == 200 && $result['status'] != 'error'){
        $this->kelengkapan_irtama_lkip( $inputId);
        $this->data['status'] = 'success';
        $this->data['messages'] = 'Data Reviu Laporan Kerja Instansi Pemerintah Berhasil Ditambahkan '.$fileMessage;
      }else{
        $this->data['status'] = 'error';
        $this->data['messages'] = 'Data Reviu Laporan Kerja Instansi Pemerintah Gagal Ditambahkan';
      }
      return redirect(route('irtama_lkip'))->with('status',$this->data);
    }

    public function deleteirtamaLkip(Request $request){
      $id = $request->input('id');
        if ($request->ajax()) {
            if($id){
                $id = $request->id;
                $data_request = execute_api('api/reviulkip/'.$id,'DELETE');
                $this->form_params['delete_id'] = $id;
                $trail['audit_menu'] = 'Inspektorat Utama - Reviu Laporan Kinerja Instansi Pemerintah';
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
                $data_request =['status'=>'error','messages'=>'Data Reviu Laporan Kinerja Instansi Pemerintah Gagal Dihapus'];
                return $data_request;
            }
        }
    }

    public function printPageirtamaLkip(Request $request){

        $page = $request->page;
        if($page){
            $page = $page;
        }else{
            $page = 1;
        }
        $result = [];
        $this->limit = config('app.limit');
        $start_number = ($this->limit * ($page -1 )) + 1;
        $url = 'api/reviulkip?page='.$page;

        $data_request = execute_api($url,'GET');

        $i = $start_number;
        if(count($data_request)>=1){
          $data = $data_request['data'];
          foreach($data as $key=>$value){
            $result[$key]['No'] = $i;
            $result[$key]['Surat Perintah'] = $value['no_sprint'];
            $result[$key]['Tanggal Laporan'] = ( $value['tanggal_lap'] ? date('d/m/Y',strtotime($value['tanggal_lap'])) : '');
            $result[$key]['Sasaran'] =$value['sasaran'];

            $i = $i +1;
          }
        $name = 'Reviu LKIP '.date('Y-m-d H:i:s');

            $this->printData($result, $name);
         //  	echo '<pre>';
          // print_r($result);
        }else{
          return false;
        }

    }

    public function irtamaSosialisasi(Request $request){
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
              }else if($key == 'status'){
                $this->selected['status'] = $value;
              }else if(($key == 'sprin') || ($key == 'kode_satker') ){
                  $this->selected[$key] = $value;
                  $this->selected['keyword'] = $value;
              }
            }
            if($tipe == 'periode'){
              if($request->tgl_to){
                $this->selected['tgl_to'] = date('d/m/Y',strtotime($request->tgl_to));
              }
              if($request->tgl_from){
                $this->selected['tgl_from'] = date('d/m/Y',strtotime($request->tgl_from));
              }
            }else if($tipe == 'jumlah_peserta'){
              $this->selected['jumlah_to'] = $request->jumlah_to;
              $this->selected['jumlah_from'] = $request->jumlah_from;
            }
            $this->selected['order'] = $request->order;
            $this->selected['limit'] = $request->limit;
            $this->data['filter'] = $this->selected;
          }
      }else{
        $post = $request->all();
        $tipe = $request->tipe;
        $kata_kunci = $request->kata_kunci;
        $kode_satker = $request->kode_satker;
        $jumlah_from = $request->jumlah_from;
        $jumlah_to = $request->jumlah_to;
        $tgl_from = $request->tgl_from;
        $tgl_to = $request->tgl_to;
        $order = $request->order;
        $limit = $request->limit;
        $status = $request->status;

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
        }else if($tipe == 'kode_satker'){
          $kondisi .= '&kode_satker='.$kode_satker;
          $this->selected['keyword'] = $kode_satker;
        }else if($tipe == 'status'){
          $kondisi .= '&status='.$status;
          $this->selected['status'] = $status;
        }else if($tipe == 'jumlah_peserta' ){
          if($jumlah_from){
            $kondisi .= '&jumlah_from='.$jumlah_from;
            $this->selected['jumlah_from'] = $jumlah_from;
          }else{
              $kondisi .='';
          }
          if($jumlah_to){
            $kondisi .= '&jumlah_to='.$jumlah_to;
            $this->selected['jumlah_to'] = $jumlah_to;
          }else{
            $kondisi .='';
          }

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

      $this->data['title'] = "Audit Laporan Hasil Audit";
      if($request->page){
          $current_page = $request->page;
          $start_number = ($this->limit * ($request->page -1 )) + 1;
      }else{
          $current_page = 1;
          $start_number = $current_page;
      }
      $limit = 'limit='.$this->limit;
      $offset = 'page='.$current_page;
      $datas = execute_api('api/irtamasosialisasi?'.$limit.'&'.$offset.$kondisi,'get');

      if( ($datas['code']= 200) && ($datas['code'] != 'error')){
        $this->data['sosialisasi'] = $datas['data'];
        $total_item = $datas['paginate']['totalpage'] * $this->limit;
      }else{
        $this->data['sosialisasi'] = [];
        $total_item =  0;
      }

      $url_simpeg = config('app.url_simpeg');
      $query  =  execute_api_json($url_simpeg,"GET");
      if($query->code == 200 && ($query->status != 'error')){
        $this->data['satker'] = $query->data;
      }else{
        $this->data['satker'] = [];
      }
      $this->data['kondisi'] ='?'.$offset.$kondisi;
      $this->data['current_page'] = $current_page;
      $this->data['start_number'] = $start_number;
      $this->data['title'] = "Irtama Sosialisasi";
      $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
      $this->data['token'] = $token;
      $this->data['path'] = $request->path();
      $this->data['delete_route'] = 'delete_irtama_sosialisasi';
      $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());
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
      $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), "/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
      return view('irtama.sosialisasi.index_irtamaSosialisasi', $this->data);
    }

     public function editirtamaSosialisasi(Request $request){
       $id = $request->id;
       $baseUrl = URL::to('/');
       $token = $request->session()->get('token');
       $client = new Client();

       $requestData = $client->request('GET', $baseUrl.'/api/irtamasosialisasi/'.$id,
         [
           'headers' =>
           [
             'Authorization' => 'Bearer '.$token
           ]
         ]
       );
      $url_simpeg = config('app.url_simpeg');
      $query  =  execute_api_json($url_simpeg,"GET");
      if($query->code == 200 && ($query->status != 'error')){
        $this->data['satker'] = $query->data;
      }else{
        $this->data['satker'] = [];
      }
       $sosialisasi = json_decode($requestData->getBody()->getContents(), true);
       // dd($sosialisasi);
       $this->data['sosialisasi'] = $sosialisasi['data'];
       $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());
       return view('irtama.sosialisasi.edit_irtamaSosialisasi',$this->data);
    }


    public function updateirtamaSosialisasi(Request $request)
    {
      // dd($request->all());
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');
      $client = new Client();
      $id = $request->input('id');


      $fileName = "";
      $fileMessage = "";
      if ($request->file('file_upload') != ''){
          $fileName = date('Y_m_d').'_'.time().'_'.$id.'-'.$request->file('file_upload')->getClientOriginalName();
          try{
              $request->file('file_upload')->storeAs('IrtamaSosialisasi', $fileName);
              $fileMessage = "";
          }catch(\Exception $e){
            $fileName =$request->input('dokumen');
            $fileMessage = "Dengan File Gagal Diupload";
          }
       }else{
        $fileName =$request->input('dokumen');
        $fileMessage = "";
       }


       $requestData = $client->request('PUT', $baseUrl.'/api/irtamasosialisasi/'.$id,
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token
          ],
          'form_params' =>
          [
            "sprin" => $request->input('sprin'),
            "lokasi" => $request->input('lokasi'),
            "kode_satker" => $request->input('list_satker'),
            "no_laporan" => $request->input('no_laporan'),
            "tgl_laporan" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_laporan')))),
            "jumlah_peserta" => $request->input('jumlah_peserta'),
            "pemapar" => $request->input('pemapar'),
            "dokumen" => $fileName,
          ]
        ]
      );

      $result = json_decode($requestData->getBody()->getContents(), true);

      $this->form_params = array("sprin" => $request->input('sprin'),
                                "lokasi" => $request->input('lokasi'),
                                "kode_satker" => $request->input('list_satker'),
                                "no_laporan" => $request->input('no_laporan'),
                                "tgl_laporan" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_laporan')))),
                                "jumlah_peserta" => $request->input('jumlah_peserta'),
                                "pemapar" => $request->input('pemapar'),
                                "dokumen" => $fileName);

      $trail['audit_menu'] = 'Inspektorat Utama - Sosialisasi';
      $trail['audit_event'] = 'put';
      $trail['audit_value'] = json_encode($this->form_params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $result['comment'];
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($token,$trail);

      $this->kelengkapan_sosialisasi($id);
      if(($result['code']== 200) && ($result['status'] != 'error')){
          $this->data['status'] = 'success';
          $this->data['message'] = 'Data Irtama sosialisasi berhasil diperbarui. '.$fileMessage;
      }else{
          $this->data['status'] = 'error';
          $this->data['message'] = 'Data Irtama sosialisasi gagal diperbarui';
      }
      return back()->with('status',$this->data);
    }

    public function addirtamaSosialisasi(Request $request){
      $url_simpeg = config('app.url_simpeg');
      $query  =  execute_api_json($url_simpeg,"GET");
      if($query->code == 200 && ($query->status != 'error')){
        $this->data['satker'] = $query->data;
      }else{
        $this->data['satker'] = [];
      }
      $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());
      return view('irtama.sosialisasi.add_irtamaSosialisasi',$this->data);
    }

    public function inputirtamaSosialisasi(Request $request)
    {
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');
      $client = new Client();
      $requestData = $client->request('POST', $baseUrl.'/api/irtamasosialisasi',
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token
          ],
          'form_params' =>
          [
            "sprin" => $request->input('sprin'),
            "lokasi" => $request->input('lokasi'),
            "kode_satker" => $request->input('list_satker'),
            "no_laporan" => $request->input('no_laporan'),
            "tgl_laporan" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_laporan')))),
            "jumlah_peserta" => $request->input('jumlah_peserta'),
            "pemapar" => $request->input('pemapar'),
          ]
        ]
      );
      $inputId= "";
      $result = json_decode($requestData->getBody()->getContents(), true);

      $this->form_params = array("sprin" => $request->input('sprin'),
                                "lokasi" => $request->input('lokasi'),
                                "kode_satker" => $request->input('list_satker'),
                                "no_laporan" => $request->input('no_laporan'),
                                "tgl_laporan" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_laporan')))),
                                "jumlah_peserta" => $request->input('jumlah_peserta'),
                                "pemapar" => $request->input('pemapar'));

      $trail['audit_menu'] = 'Inspektorat Utama - Sosialisasi';
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
          $inputId = $result['data']['eventID'];
      }else{
         $inputId= "";
      }

      $file_message = "";
      if($inputId){
        if ($request->file('file_upload') != ''){
            $fileName = date('Y_m_d').'_'.time().'_'.$inputId.'-'.$request->file('file_upload')->getClientOriginalName();
            try {
              $request->file('file_upload')->storeAs('IrtamaSosialisasi', $fileName);

              $requestfile = $client->request('PUT', $baseUrl.'/api/irtamasosialisasi/'.$inputId,
                      [
                          'headers' =>
                          [
                              'Authorization' => 'Bearer '.$token
                          ],
                          'form_params' => [
                              'dokumen' => $fileName,
                          ]
                      ]
                  );
                  $resulti = json_decode($requestfile->getBody()->getContents(), true);
                  if($resulti['code'] == 200 && $resulti['status'] == 'error'){
                    $file_message = "<i>Dengan file gagal di diupload.</i>";
                  }else{
                    $file_message = "";
                  }
            }catch(\Exception $e){
                $file_message = "<i>Dengan file gagal di diupload.</i>";
            }
          }
        }
        if($result['code'] == 200 && $result['status'] != 'error'){
          $this->kelengkapan_sosialisasi($inputId);
          $this->data['status'] = 'success';
          $this->data['message'] = 'Data Irtama sosialisasi Berhasil Ditambahkan .'.$file_message;
      }else{
         $this->data['status'] = 'error';
         $this->data['message'] = 'Data Irtama sosialisasi Gagal Ditambahkan';
      }
      return redirect(route('irtama_sosialisasi'))->with('status',$this->data);
    }

    public function irtamaVerifikasi(Request $request){
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
              }else if(($key == 'sprin') || ($key == 'status') || ($key == 'kode_satker') || ($key == 'pejabat_diganti') || ($key == 'pejabat_baru') ){
                  $this->selected[$key] = $value;
                  $this->selected['keyword'] = $value;
              }
            }
            if($tipe == 'periode'){
              if($request->tgl_to){
                $this->selected['tgl_to'] = date('d/m/Y',strtotime($request->tgl_to));
              }
              if($request->tgl_from){
                $this->selected['tgl_from'] = date('d/m/Y',strtotime($request->tgl_from));
              }
            }else

            $this->selected['order'] = $request->order;
            $this->selected['limit'] = $request->limit;
            $this->data['filter'] = $this->selected;
          }
      }else{
        $post = $request->all();
        $tipe = $request->tipe;
        $kata_kunci = $request->kata_kunci;
        $kode_satker = $request->kode_satker;
        $status = $request->status;
        $tgl_from = $request->tgl_from;
        $tgl_to = $request->tgl_to;
        $order = $request->order;
        $limit = $request->limit;

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
        }elseif($tipe == 'kode_satker'){
          $kondisi .= '&kode_satker='.$kode_satker;
          $this->selected['keyword'] = $kode_satker;
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

      $this->data['title'] = "Irtama Verifikasi";
      if($request->page){
          $current_page = $request->page;
          $start_number = ($this->limit * ($request->page -1 )) + 1;
      }else{
          $current_page = 1;
          $start_number = $current_page;
      }

      $limit = 'limit='.$this->limit;
      $offset = 'page='.$current_page;
      $datas = execute_api('api/irtamaverifikasi?'.$limit.'&'.$offset.$kondisi,'get');
      // print_r($datas);
      // echo '</pre>';
      $this->data['kondisi'] ='?'.$offset.$kondisi;
      if( ($datas['code']= 200) && ($datas['code'] != 'error')){
        $this->data['verifikasi'] = $datas['data'];
        $total_item = $datas['paginate']['totalpage'] * $this->limit;
      }else{
        $this->data['verifikasi'] = [];
        $total_item =  0;
      }

      $url_simpeg = config('app.url_simpeg');
      $query  =  execute_api_json($url_simpeg,"GET");
      if($query->code == 200 && ($query->status != 'error')){
        $this->data['satker'] = $query->data;
      }else{
        $this->data['satker'] = [];
      }
      $this->data['current_page'] = $current_page;
      $this->data['start_number'] = $start_number;
      $this->data['title'] = "Irtama Verifikasi";
      $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
      $this->data['token'] = $token;
      $this->data['path'] = $request->path();
      $this->data['delete_route'] = 'delete_irtama_verifikasi';
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
      $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), "/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
      $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());
      return view('irtama.verifikasi.index_irtamaVerifikasi',$this->data);
    }

     public function editirtamaVerifikasi(Request $request){
       $id = $request->id;
       $baseUrl = URL::to('/');
       $token = $request->session()->get('token');
       $client = new Client();

       $requestData = $client->request('GET', $baseUrl.'/api/irtamaverifikasi/'.$id,
         [
           'headers' =>
           [
             'Authorization' => 'Bearer '.$token
           ]
         ]
       );
      $url_simpeg = config('app.url_simpeg');
      $query  =  execute_api_json($url_simpeg,"GET");
      if($query->code == 200 && ($query->status != 'error')){
        $this->data['satker'] = $query->data;
      }else{
        $this->data['satker'] = [];
      }
      $verifikasi = json_decode($requestData->getBody()->getContents(), true);
      $this->data['verifikasi'] = $verifikasi['data'];
      $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());
      return view('irtama.verifikasi.edit_irtamaVerifikasi',$this->data);
    }

    public function updateirtamaVerifikasi(Request $request)
    {
      // dd($request->all());
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');
      $client = new Client();
      $id = $request->input('id');
      if ($request->input('pejabat_tgl_skep_diganti') != '') {
        $tgl1 = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('pejabat_tgl_skep_diganti'))));
      } else {
        $tgl1 = '';
      }
      if ($request->input('pejabat_tgl_skep_baru') != '') {
        $tgl2 = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('pejabat_tgl_skep_baru'))));
      } else {
        $tgl2 = '';
      }
      if ($request->input('tgl_laporan') != '') {
        $tgl3 = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_laporan'))));
      } else {
        $tgl3 = '';
      }

      $requestData = $client->request('PUT', $baseUrl.'/api/irtamaverifikasi/'.$id,
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token
          ],
          'form_params' =>
          [
            "sprin" => $request->input('sprin'),
            "lokasi" => $request->input('lokasi'),
            "kode_satker" => $request->input('list_satker'),
            "pejabat_diganti" => $request->input('pejabat_diganti'),
            "pejabat_skep_diganti" => $request->input('pejabat_skep_diganti'),
            "pejabat_tgl_skep_diganti" => $tgl1,
            "pejabat_baru" => $request->input('pejabat_baru'),
            "pejabat_skep_baru" => $request->input('pejabat_skep_baru'),
            "pejabat_tgl_skep_baru" => $tgl2,
            "no_laporan" => $request->input('no_laporan'),
            "tgl_laporan" => $tgl3,
            "hal_menjadi_perhatian" => $request->input('hal_menjadi_perhatian'),
            "saran" => $request->input('saran')
          ]
        ]
      );


      $result = json_decode($requestData->getBody()->getContents(), true);
      // dd($result);
      // $inputId = $result['data']['eventID'];
      $this->form_params = array("sprin" => $request->input('sprin'),
                                "lokasi" => $request->input('lokasi'),
                                "kode_satker" => $request->input('list_satker'),
                                "pejabat_diganti" => $request->input('pejabat_diganti'),
                                "pejabat_skep_diganti" => $request->input('pejabat_skep_diganti'),
                                "pejabat_tgl_skep_diganti" => $tgl1,
                                "pejabat_baru" => $request->input('pejabat_baru'),
                                "pejabat_skep_baru" => $request->input('pejabat_skep_baru'),
                                "pejabat_tgl_skep_baru" => $tgl2,
                                "no_laporan" => $request->input('no_laporan'),
                                "tgl_laporan" => $tgl3,
                                "hal_menjadi_perhatian" => $request->input('hal_menjadi_perhatian'),
                                "saran" => $request->input('saran'));

      $trail['audit_menu'] = 'Inspektorat Utama - Verifikasi';
      $trail['audit_event'] = 'put';
      $trail['audit_value'] = json_encode($this->form_params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $result['comment'];
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($token,$trail);


      if ($request->file('file_upload') != ''){
          $fileName = date('Y-m-d').'_'.$id.'-'.$request->file('file_upload')->getClientOriginalName();
          $request->file('file_upload')->storeAs('IrtamaVerifikasi', $fileName);

          $requestfile = $client->request('PUT', $baseUrl.'/api/irtamaverifikasi/'.$id,
                  [
                      'headers' =>
                      [
                          'Authorization' => 'Bearer '.$token
                      ],
                      'form_params' => [
                          'dokumen' => $fileName,
                      ]
                  ]
              );

              $resulti = json_decode($requestfile->getBody()->getContents(), true);
              // dd($resulti);
        }
        if($result['code'] == 200 && $result['status'] != 'error'){
          $this->kelengkapan_verifikasi($id);
          $this->data['status'] = 'success';
          $this->data['message'] = 'Data Irtama Verifikasi Berhasil Diperbarui';
        }else{
          $this->data['status'] = 'error';
          $this->data['message'] = 'Data Irtama Verifikasi Gagal Diperbarui';
        }
        return back()->with('status',$this->data);
    }

    public function addirtamaVerifikasi(Request $request){
      $url_simpeg = config('app.url_simpeg');
      $query  =  execute_api_json($url_simpeg,"GET");
      if($query->code == 200 && ($query->status != 'error')){
        $this->data['satker'] = $query->data;
      }else{
        $this->data['satker'] = [];
      }
      $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());
      return view('irtama.verifikasi.add_irtamaVerifikasi',$this->data);
    }

    public function inputirtamaVerifikasi(Request $request)
    {
      // dd($request->all());
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');
      $client = new Client();
      if ($request->input('pejabat_tgl_skep_diganti') != '') {
        $tgl1 = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('pejabat_tgl_skep_diganti'))));
      } else {
        $tgl1 = '';
      }
      if ($request->input('pejabat_tgl_skep_baru') != '') {
        $tgl2 = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('pejabat_tgl_skep_baru'))));
      } else {
        $tgl2 = '';
      }
      if ($request->input('tgl_laporan') != '') {
        $tgl3 = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_laporan'))));
      } else {
        $tgl3 = '';
      }

      $requestData = $client->request('POST', $baseUrl.'/api/irtamaverifikasi',
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token
          ],
          'form_params' =>
          [
            "sprin" => $request->input('sprin'),
            "lokasi" => $request->input('lokasi'),
            "kode_satker" => $request->input('list_satker'),
            "pejabat_diganti" => $request->input('pejabat_diganti'),
            "pejabat_skep_diganti" => $request->input('pejabat_skep_diganti'),
            "pejabat_tgl_skep_diganti" => $tgl1,
            "pejabat_baru" => $request->input('pejabat_baru'),
            "pejabat_skep_baru" => $request->input('pejabat_skep_baru'),
            "pejabat_tgl_skep_baru" => $tgl2,
            "no_laporan" => $request->input('no_laporan'),
            "tgl_laporan" => $tgl3,
            "hal_menjadi_perhatian" => $request->input('hal_menjadi_perhatian'),
            "saran" => $request->input('saran')
          ]
        ]
      );

      $inputId = "";
      $result = json_decode($requestData->getBody()->getContents(), true);
      // dd($result);
      $this->form_params = array("sprin" => $request->input('sprin'),
                                "lokasi" => $request->input('lokasi'),
                                "kode_satker" => $request->input('list_satker'),
                                "pejabat_diganti" => $request->input('pejabat_diganti'),
                                "pejabat_skep_diganti" => $request->input('pejabat_skep_diganti'),
                                "pejabat_tgl_skep_diganti" => $tgl1,
                                "pejabat_baru" => $request->input('pejabat_baru'),
                                "pejabat_skep_baru" => $request->input('pejabat_skep_baru'),
                                "pejabat_tgl_skep_baru" => $tgl2,
                                "no_laporan" => $request->input('no_laporan'),
                                "tgl_laporan" => $tgl3,
                                "hal_menjadi_perhatian" => $request->input('hal_menjadi_perhatian'),
                                "saran" => $request->input('saran'));

      $trail['audit_menu'] = 'Inspektorat Utama - Verifikasi';
      $trail['audit_event'] = 'post';
      $trail['audit_value'] = json_encode($this->form_params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $result['comment'];
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($token,$trail);

      if(($result['code'] == 200) && ($result['status'] != 'error')){
        $inputId = $result['data']['eventID'];

      }

      if($inputId){
        if ($request->file('file_upload') != ''){
            $fileName = $inputId.'-'.$request->file('file_upload')->getClientOriginalName();
            $request->file('file_upload')->storeAs('IrtamaVerifikasi', $fileName);

            $requestfile = $client->request('PUT', $baseUrl.'/api/irtamaverifikasi/'.$inputId,
                    [
                        'headers' =>
                        [
                            'Authorization' => 'Bearer '.$token
                        ],
                        'form_params' => [
                            'dokumen' => $fileName,
                        ]
                    ]
                );

                $resulti = json_decode($requestfile->getBody()->getContents(), true);
                // dd($resulti);
         }
        }
        if(($result['code'] == 200) && ($result['status'] != 'error')){
          $this->kelengkapan_verifikasi($inputId);
          $this->data['status'] = 'success';
          $this->data['message'] = 'Data Irtama Verifikasi Berhasil Ditambahkan.';
        }else{
          $this->data['status'] = 'error';
          $this->data['message'] = 'Data Irtama Verifikasi Gagal Ditambahkan.';
        }
       return redirect(route('irtama_verifikasi'))->with('status',$this->data);
    }

    public function deleteirtamaVerifikasi(Request $request){
      $id = $request->input('id');
        if ($request->ajax()) {
            if($id){
                $id = $request->id;
                $data_request = execute_api('api/irtamaverifikasi/'.$id,'DELETE');
                $this->form_params['delete_id'] = $id;
                $trail['audit_menu'] = 'Inspektorat Utama - Verifikasi';
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
                $data_request =['status'=>'error','messages'=>'Data Verifikasi Gagal Dihapus'];
                return $data_request;
            }
        }
    }

    public function printPageirtamaVerifikasi(Request $request){

        $page = $request->page;
        if($page){
            $page = $page;
        }else{
            $page = 1;
        }
        $result = [];
        $this->limit = config('app.limit');
        $start_number = ($this->limit * ($page -1 )) + 1;
        $url = 'api/irtamaverifikasi?page='.$page;

        $data_request = execute_api($url,'GET');

        $i = $start_number;
        if(count($data_request)>=1){
          $data = $data_request['data'];
          foreach($data as $key=>$value){
            $result[$key]['No'] = $i;
            $result[$key]['Surat Perintah'] = $value['sprin'];
            $result[$key]['Lokasi'] = $value['lokasi'];
            $result[$key]['Satker'] =$value['kode_satker'];
            $result[$key]['Pejabat yang Diganti'] =$value['pejabat_diganti'];
            $result[$key]['Pejabat yang Baru'] =$value['pejabat_baru'];
            $result[$key]['Tgl Laporan'] = ( $value['tgl_laporan'] ? date('d/m/Y',strtotime($value['tgl_laporan'])) : '');

            $i = $i +1;
          }
        $name = 'Verifikasi '.date('Y-m-d H:i:s');

            $this->printData($result, $name);
         //  	echo '<pre>';
          // print_r($result);
        }else{
          return false;
        }

    }

    public function irtamaSop(Request $request){
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
              }else if(($key == 'sprin') || ($key == 'jenis_sop_kebijakan') || ($key == 'status') ){
                  $this->selected[$key] = $value;
                  $this->selected['keyword'] = $value;
              }
            }
            if($tipe == 'periode'){
              if($request->tgl_to){
                $this->selected['tgl_to'] = date('d/m/Y',strtotime($request->tgl_to));
              }
              if($request->tgl_from){
                $this->selected['tgl_from'] = date('d/m/Y',strtotime($request->tgl_from));
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
        $jenis_sop_kebijakan = $request->jenis_sop_kebijakan;

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
        } else if($tipe == 'jenis_sop_kebijakan'){

          $kondisi .= '&'.$tipe.'='.$request->jenis_sop_kebijakan;
          $this->selected['keyword'] = $request->jenis_sop_kebijakan;
        } else if($tipe == 'status'){

          $kondisi .= '&'.$tipe.'='.$request->status;
          $this->selected['keyword'] = $request->status;
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
      $this->data['title'] = "Irtama SOP & Kebijakan";
      if($request->page){
          $current_page = $request->page;
          $start_number = ($this->limit * ($request->page -1 )) + 1;
      }else{
          $current_page = 1;
          $start_number = $current_page;
      }
      $limit = 'limit='.$this->limit;
      $offset = 'page='.$current_page;
      $this->data['kondisi'] ='?'.$offset.$kondisi;
      $datas = execute_api('api/sopkebijakan?'.$limit.'&'.$offset.$kondisi,'get');
      // echo '<pre>';
      // print_r($datas);

      if( ($datas['code']= 200) && ($datas['code'] != 'error')){
        $this->data['sop'] = $datas['data'];
        $total_item = $datas['paginate']['totalpage'] * $this->limit;
      }else{
        $this->data['sop'] = [];
        $total_item =  0;
      }

      $this->data['current_page'] = $current_page;
      $this->data['start_number'] = $start_number;
      $this->data['title'] = "Irtama SOP & Kebijakan";
      $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
      $this->data['token'] = $token;
      $this->data['path'] = $request->path();
      $this->data['delete_route'] = 'delete_irtama_sop';
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
      $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), "/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );


      $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());
      return view('irtama.sop.index_irtamaSop',$this->data);
    }

     public function editirtamaSop(Request $request){
       $id = $request->id;
       $baseUrl = URL::to('/');
       $token = $request->session()->get('token');
       $client = new Client();

       $requestData = $client->request('GET', $baseUrl.'/api/sopkebijakan/'.$id,
         [
           'headers' =>
           [
             'Authorization' => 'Bearer '.$token
           ]
         ]
       );

       $sop = json_decode($requestData->getBody()->getContents(), true);
       // dd($sop);
       $this->data['sop'] = $sop['data'];
       $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());
       return view('irtama.sop.edit_irtamaSop',$this->data);
    }

    public function updateirtamaSop(Request $request)
    {
      // dd($request->all());
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');
      $client = new Client();
      $id = $request->input('id');
      if ($request->input('tgl_sprin') != ''){
        $tgl1 = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_sprin'))));
      } else {
        $tgl1 = '';
      }
      if ($request->input('tgl_sop') != ''){
        $tgl2 = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_sop'))));
      } else {
        $tgl2 = '';
      }

      $requestData = $client->request('PUT', $baseUrl.'/api/sopkebijakan/'.$id,
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token
          ],
          'form_params' =>
          [
            "sprin" => $request->input('sprin'),
            "tgl_sprin" => $tgl1,
            "nama_sop_kebijakan" => $request->input('nama_sop_kebijakan'),
            "jenis_sop_kebijakan" => $request->input('jenis_sop_kebijakan'),
            "tgl_sop" => $tgl2
          ]
        ]
      );


      $result = json_decode($requestData->getBody()->getContents(), true);

      $this->form_params = array("sprin" => $request->input('sprin'),
                                "tgl_sprin" => $tgl1,
                                "nama_sop_kebijakan" => $request->input('nama_sop_kebijakan'),
                                "jenis_sop_kebijakan" => $request->input('jenis_sop_kebijakan'),
                                "tgl_sop" => $tgl2);

      $trail['audit_menu'] = 'Inspektorat Utama - SOP & Kebijakan';
      $trail['audit_event'] = 'put';
      $trail['audit_value'] = json_encode($this->form_params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $result['comment'];
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($token,$trail);

      if ($request->file('file_upload') != ''){
          $fileName = $id.'-'.$request->file('file_upload')->getClientOriginalName();
          $request->file('file_upload')->storeAs('IrtamaSopKebijakan', $fileName);
          $requestfile = $client->request('PUT', $baseUrl.'/api/sopkebijakan/'.$id,
              [
                  'headers' =>
                  [
                      'Authorization' => 'Bearer '.$token
                  ],
                  'form_params' => [
                      'dokumen' => $fileName,
                  ]
              ]
          );

          $resulti = json_decode($requestfile->getBody()->getContents(), true);
              // dd($resulti);
       }
       if($result['code'] == 200 && $result['status'] != 'error'){
        $this->kelengkapan_sop($id);
        $this->data['status'] = 'success';
        $this->data['message'] = ' Data SOP & Kebijakan Berhasil Ditambahkan';
       }else{
        $this->data['status'] = 'error';
        $this->data['message'] = ' Data SOP & Kebijakan Gagal Ditambahkan';
       }

       return redirect(route('irtama_sop'))->with('status',$this->data);
    }

    public function addirtamaSop(Request $request){
        $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());
        return view('irtama.sop.add_irtamaSop',$this->data);
    }

    public function inputirtamaSop(Request $request)
    {
      // dd($request->all());
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');
      $client = new Client();
      if ($request->input('tgl_sprin') != ''){
        $tgl1 = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_sprin'))));
      } else {
        $tgl1 = '';
      }
      if ($request->input('tgl_sop') != ''){
        $tgl2 = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_sop'))));
      } else {
        $tgl2 = '';
      }

      $requestData = $client->request('POST', $baseUrl.'/api/sopkebijakan',
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token
          ],
          'form_params' =>
          [
            "sprin" => $request->input('sprin'),
            "tgl_sprin" => $tgl1,
            "nama_sop_kebijakan" => $request->input('nama_sop_kebijakan'),
            "jenis_sop_kebijakan" => $request->input('jenis_sop_kebijakan'),
            "tgl_sop" => $tgl2
          ]
        ]
      );

      $result = json_decode($requestData->getBody()->getContents(), true);
      // dd($result);
      $inputId = $result['data']['eventID'];

      $this->form_params = array("sprin" => $request->input('sprin'),
                                "tgl_sprin" => $tgl1,
                                "nama_sop_kebijakan" => $request->input('nama_sop_kebijakan'),
                                "jenis_sop_kebijakan" => $request->input('jenis_sop_kebijakan'),
                                "tgl_sop" => $tgl2);

      $trail['audit_menu'] = 'Inspektorat Utama - SOP & Kebijakan';
      $trail['audit_event'] = 'post';
      $trail['audit_value'] = json_encode($this->form_params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $result['comment'];
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($token,$trail);

      $fileMessage = "";
      if ($request->file('file_upload') != ''){
          $fileName = date('Y-m-d').'-'.$inputId.'-'.$request->file('file_upload')->getClientOriginalName();
          try {
            $request->file('file_upload')->storeAs('IrtamaSopKebijakan', $fileName);

            $requestfile = $client->request('PUT', $baseUrl.'/api/sopkebijakan/'.$inputId,
                  [
                      'headers' =>
                      [
                          'Authorization' => 'Bearer '.$token
                      ],
                      'form_params' => [
                          'dokumen' => $fileName,
                      ]
                  ]
              );

              $resulti = json_decode($requestfile->getBody()->getContents(), true);
              if($resulti['code'] == 200 && $resulti['status'] != 'error'){
                $fileMessage .= '';
              }else{
                $fileMessage .= ' Dengan File Gagal diunggah';
              }
          }catch(\Exception $e){
            $fileMessage .= ' Dengan File Gagal diunggah';
          }

              // dd($resulti);
       }
       if($result['code'] == 200 && $result['status'] != 'error'){
          $this->kelengkapan_sop($inputId);
          $this->data['status'] ="success";
          $this->data['message'] = 'Data Irtama SOP berhasil Ditambahkan.'.$fileMessage;
        }else{
          $this->data['status'] ="error";
          $this->data['messages'] = 'Data Irtama SOP gagal Ditambahkan';
        }
       return redirect('/irtama/sop/irtama_sop')->with('status',$this->data);
    }

    public function deleteirtamaSop(Request $request){
      $id = $request->input('id');
        if ($request->ajax()) {
            if($id){
                $id = $request->id;
                $data_request = execute_api('api/sopkebijakan/'.$id,'DELETE');
                $this->form_params['delete_id'] = $id;
                $trail['audit_menu'] = 'Inspektorat Utama - SOP & Kebijakan';
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
                $data_request =['status'=>'error','messages'=>'Data SOP & Kebijakan Gagal Dihapus'];
                return $data_request;
            }
        }
    }

    public function printPageirtamaSop(Request $request){

        $page = $request->page;
        if($page){
            $page = $page;
        }else{
            $page = 1;
        }
        $result = [];
        $this->limit = config('app.limit');
        $start_number = ($this->limit * ($page -1 )) + 1;
        $url = 'api/sopkebijakan?page='.$page;
        $data_request = execute_api($url,'GET');
        $i = $start_number;
        if(count($data_request)>=1){
          $data = $data_request['data'];
          foreach($data as $key=>$value){
            $result[$key]['No'] = $i;
            $result[$key]['Surat Perintah'] = $value['sprin'];
            $result[$key]['Tgl Surat Perintah'] = ( $value['tgl_sprin'] ? date('d/m/Y',strtotime($value['tgl_sprin'])) : '');
            $result[$key]['Nama SOP & kebijakan'] = $value['nama_sop_kebijakan'];
            $result[$key]['Jenis SOP & kebijakan'] =$value['jenis_sop_kebijakan'];
            $result[$key]['Tgl SOP'] = ( $value['tgl_sop'] ? date('d/m/Y',strtotime($value['tgl_sop'])) : '');
            $i = $i +1;
          }
          $name = 'SOP dan kebijakan '.date('Y-m-d H:i:s');
          $this->printData($result, $name);
        }else{
          return false;
        }

    }

    public function irtamaPenegakan(Request $request){

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
                }else if(($key == 'no_laporan') || ($key == 'kode_satker')  || ($key == 'status')){
                    $this->selected[$key] = $value;
                    $this->selected['keyword'] = $value;
                }else if($key == 'status'){
                    $this->selected[$key] = $value;
                    $this->selected['status'] = $value;
                }
              }
              if($tipe == 'periode'){
                if($request->tgl_to){
                  $this->selected['tgl_to'] = date('d/m/Y',strtotime($request->tgl_to));
                }
                if($request->tgl_from){
                  $this->selected['tgl_from'] = date('d/m/Y',strtotime($request->tgl_from));
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
          $satker = $request->kode_satker;
          $tgl_from = $request->tgl_from;
          $tgl_to = $request->tgl_to;
          $order = $request->order;
          $limit = $request->limit;
          $kelengkapan = $request->status;

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
          }else if($tipe == 'kode_satker'){
            $kondisi .= '&kode_satker='.$satker;
            $this->selected['keyword'] = $satker;
          }else if($tipe == 'status'){
            $kondisi .= '&status='.$kelengkapan;
            $this->selected['status'] = $kelengkapan;
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
        $penegakan = execute_api('api/penegakandisiplin?'.$limit.'&'.$offset.$kondisi,'get');

        $total_item = 0;
        if($penegakan['code'] == 200 && ($penegakan['status'] != 'error')){
          $this->data['penegakan'] = $penegakan['data'];
          $total_item = $penegakan['paginate']['totalpage'] * $this->limit;
        }else{
          $this->data['penegakan'] = [];
          $total_item = 0;
        }
        $this->data['filter'] = $this->selected;
        $this->data['route'] = $request->route()->getName();
        $this->data['kondisi'] = $kondisi;
        $this->data['start_number'] = $start_number;
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

        $this->data['title'] = "Irtama Penegakan Disiplin";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));

        $this->data['path'] = $request->path();
        $url_simpeg = config('app.url_simpeg');
        $query  =  execute_api_json($url_simpeg,"GET");
        if($query->code == 200 && ($query->status != 'error')){
          $this->data['satker'] = $query->data;
        }else{
          $this->data['satker'] = [];
        }

        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), "/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
        $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());
        return view('irtama.penegakan.index_irtamaPenegakan',$this->data);
    }

    public function addirtamaPenegakan(Request $request){
        $this->data['title']="Inspektorat Utama";
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        $url_simpeg = config('app.url_simpeg');
        $query  =  execute_api_json($url_simpeg,"GET");
        if($query->code == 200 && ($query->status != 'error')){
          $this->data['satker'] = $query->data;
        }else{
          $this->data['satker'] = [];
        }
        $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());
        return view('irtama.penegakan.add_irtamaPenegakan',$this->data);
    }

    public function editirtamaPenegakan(Request $request){
        $id = $request->id;
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');
        $client = new Client();

        $requestData = $client->request('GET', $baseUrl.'/api/penegakandisiplin/'.$id,
          [
            'headers' =>
            [
              'Authorization' => 'Bearer '.$token
            ]
          ]
        );

        $penegakan= json_decode($requestData->getBody()->getContents(), true);
        $url_simpeg = config('app.url_simpeg');
        $query  =  execute_api_json($url_simpeg,"GET");
        if($query->code == 200 && ($query->status != 'error')){
          $this->data['satker'] = $query->data;
        }else{
          $this->data['satker'] = [];
        }
        $this->data['data_detail'] = $penegakan['data'];
        $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());
        return view('irtama.penegakan.edit_irtamaPenegakan',$this->data);
    }

    public function inputirtamaPenegakan(Request $request){
      // dd($request->all());
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');
      $client = new Client();
      if($request->input('tgl_laporan') != ''){
        $tgl1 = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_laporan'))));
      } else {
        $tgl1 = '';
      }

      $periode = explode('/', $request->periode);
      $insertedId = "";
      $requestData = $client->request('POST', $baseUrl.'/api/penegakandisiplin',
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token
          ],
          'form_params' =>
          [
            "periode_tahun" => $periode[1],
            "periode_bulan" => $periode[0],
            "no_laporan" => $request->input('no_laporan'),
            "tgl_laporan" => $tgl1,
            "kode_satker" => $request->input('list_satker'),
            "absensi_tanpa_keterangan" => $request->input('absensi_tanpa_keterangan'),
            "absensi_terlambat" => $request->input('absensi_terlambat'),
            "absensi_pulang_cepat" => $request->input('absensi_pulang_cepat'),
            "absensi_telambat_pulang_cepat" => $request->input('absensi_telambat_pulang_cepat')
          ]
        ]
      );

      $result = json_decode($requestData->getBody()->getContents(), true);

      $this->form_params = array("periode_tahun" => $periode[1],
                                "periode_bulan" => $periode[0],
                                "no_laporan" => $request->input('no_laporan'),
                                "tgl_laporan" => $tgl1,
                                "kode_satker" => $request->input('list_satker'),
                                "absensi_tanpa_keterangan" => $request->input('absensi_tanpa_keterangan'),
                                "absensi_terlambat" => $request->input('absensi_terlambat'),
                                "absensi_pulang_cepat" => $request->input('absensi_pulang_cepat'),
                                "absensi_telambat_pulang_cepat" => $request->input('absensi_telambat_pulang_cepat'));

      $trail['audit_menu'] = 'Inspektorat Utama - Penegakan Disiplin';
      $trail['audit_event'] = 'post';
      $trail['audit_value'] = json_encode($this->form_params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $result['comment'];
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($token,$trail);


      if(($result['status'] != 'error') && ($result['code'] == 200)){
        $this->data['status'] = 'success';
        $this->data['message'] = 'Data Irtama Penegakan Disiplin berhasil ditambahkan';
        $insertedId = $result['data']['eventID'];
        $this->kelengkapan_penegakan($insertedId);
      }else{
        $this->data['status'] = 'error';
        $this->data['message'] = 'Data Irtama Penegakan Disiplin gagal ditambahkan';
      }

      return redirect('/irtama/penegakan/irtama_penegakan')->with('status',$this->data);
    }

    public function updateirtamaPenegakan(Request $request){
      $id = $request->input('id');
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');
      $client = new Client();
      if($request->input('tgl_laporan') != ''){
        $tgl1 = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_laporan'))));
      } else {
        $tgl1 = '';
      }

      $periode = explode('/', $request->periode);
      $requestData = $client->request('PUT', $baseUrl.'/api/penegakandisiplin/'.$id,
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token
          ],
          'form_params' =>
          [
            "periode_tahun" => $periode[1],
            "periode_bulan" => $periode[0],
            "no_laporan" => $request->input('no_laporan'),
            "tgl_laporan" => $tgl1,
            "kode_satker" => $request->input('list_satker'),
            "absensi_tanpa_keterangan" => $request->input('absensi_tanpa_keterangan'),
            "absensi_terlambat" => $request->input('absensi_terlambat'),
            "absensi_pulang_cepat" => $request->input('absensi_pulang_cepat'),
            "absensi_telambat_pulang_cepat" => $request->input('absensi_telambat_pulang_cepat')
          ]
        ]
      );

      $result = json_decode($requestData->getBody()->getContents(), true);

      $this->form_params = array("periode_tahun" => $periode[1],
                                "periode_bulan" => $periode[0],
                                "no_laporan" => $request->input('no_laporan'),
                                "tgl_laporan" => $tgl1,
                                "kode_satker" => $request->input('list_satker'),
                                "absensi_tanpa_keterangan" => $request->input('absensi_tanpa_keterangan'),
                                "absensi_terlambat" => $request->input('absensi_terlambat'),
                                "absensi_pulang_cepat" => $request->input('absensi_pulang_cepat'),
                                "absensi_telambat_pulang_cepat" => $request->input('absensi_telambat_pulang_cepat'));

      $trail['audit_menu'] = 'Inspektorat Utama - Penegakan Disiplin';
      $trail['audit_event'] = 'put';
      $trail['audit_value'] = json_encode($this->form_params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $result['comment'];
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($token,$trail);

      if(($result['status'] != 'error') && ($result['code'] == 200)){
        $this->data['status'] = 'success';
        $this->data['message'] = 'Data Irtama Penegakan Disiplin berhasil diperbarui';
        $this->kelengkapan_penegakan($id);
      }else{
        $this->data['status'] = 'error';
        $this->data['message'] = 'Data Irtama Penegakan Disiplin gagal diperbarui';
      }
      return back()->with('status',$this->data);;
    }

    public function printPageirtamaPenegakan(Request $request){

        $page = $request->page;
        if($page){
            $page = $page;
        }else{
            $page = 1;
        }
        $result = [];
        $this->limit = config('app.limit');
        $start_number = ($this->limit * ($page -1 )) + 1;
        $url = 'api/penegakandisiplin?page='.$page;

        $data_request = execute_api($url,'GET');

        $i = $start_number;
        if(count($data_request)>=1){
          $data = $data_request['data'];
          foreach($data as $key=>$value){
            $result[$key]['No'] = $i;
            $result[$key]['Bulan'] = $value['periode_bulan'].'/'.$value['periode_bulan'];
            $result[$key]['No Laporan'] = $value['no_laporan'];
            $result[$key]['Tgl Laporan'] = ( $value['tgl_laporan'] ? date('d/m/Y',strtotime($value['tgl_laporan'])) : '');
            $result[$key]['Satker'] =$value['kode_satker'];
            $result[$key]['Tanpa keterangan'] =$value['absensi_tanpa_keterangan'];
            $result[$key]['Terlambat'] =$value['absensi_terlambat'];
            $result[$key]['Pulang cepat'] =$value['absensi_pulang_cepat'];
            $result[$key]['Terlambat/Pulang cepat'] =$value['absensi_telambat_pulang_cepat'];

            $i = $i +1;
          }
          $name = 'Penegakan Disiplin '.date('Y-m-d H:i:s');
          $this->printData($result, $name);
        }else{
          return false;
        }

    }

    public function irtamaApel(Request $request){
        $kondisi = "";
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
                }else if($key == 'status' ){
                  $this->selected[$key] = $value;
                }else if($key == 'kode_satker' ){
                  $this->selected['keyword'] = $value;
                }else {
                    $this->selected[$key] = $value;
                    // $this->selected['keyword'] = $value;
                }
              }
              if($tipe == 'periode'){
                if($request->tgl_to){
                  $this->selected['tgl_to'] = date('d/m/Y',strtotime($request->tgl_to));
                }
                if($request->tgl_from){
                  $this->selected['tgl_from'] = date('d/m/Y',strtotime($request->tgl_from));
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
          $kode_satker = $request->kode_satker;

          $tgl_from = $request->tgl_from;
          $tgl_to = $request->tgl_to;
          $order = $request->order;
          $limit = $request->limit;
          $hadir_from = $request->hadir_from;
          $hadir_to = $request->hadir_to;
          $absen_from = $request->absen_from;
          $absen_to = $request->absen_to;
          $status = $request->status;

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
          elseif($tipe == 'kode_satker'){
            $kondisi .= '&kode_satker='.$kode_satker;
            $this->selected['keyword'] = $kode_satker;
          }
          elseif($tipe == 'status'){
            $kondisi .= '&status='.$status;
            $this->selected['status'] = $status;
          }
          elseif($tipe == 'jml_tdk_hadir'){
            if($absen_from){
              $kondisi .= '&absen_from='.$absen_from;
              $this->selected['absen_from'] = $absen_from;
            }
            if($absen_to){
              $kondisi .= '&absen_to='.$absen_to;
              $this->selected['absen_to'] = $absen_to;
            }
          }
          elseif($tipe == 'jml_hadir'){
            if($hadir_from){
              $kondisi .= '&hadir_from='.$hadir_from;
              $this->selected['hadir_from'] = $hadir_from;
            }
            if($hadir_to){
              $kondisi .= '&hadir_to='.$hadir_to;
              $this->selected['hadir_to'] = $hadir_to;
            }
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

        $datas = execute_api('api/apelupacara?'.$limit.'&'.$offset.$kondisi,'get');
        if( ($datas['code']= 200) && ($datas['code'] != 'error')){
          $this->data['apel'] = $datas['data'];
          $total_item = $datas['paginate']['totalpage'] * $datas['paginate']['limit'];
        }else{
          $this->data['apel'] = [];
          $total_item =  0;
        }

        $this->data['title'] = "Irtama Apel Senin & Upacara";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));

        $this->data['path'] = $request->path();
        $url_simpeg = config('app.url_simpeg');
        $query  =  execute_api_json($url_simpeg,"GET");
        if($query->code == 200 && ($query->status != 'error')){
          $this->data['satker'] = $query->data;
        }else{
          $this->data['satker'] = [];
        }
        $this->data['kondisi'] ='?'.$offset.$kondisi;
        $this->data['current_page'] = $current_page;
        $this->data['start_number'] = $start_number;
        $this->data['path'] = $request->path();
        $this->data['delete_route'] = 'delete_irtama_verifikasi';
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
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), "/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );

        $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());
        return view('irtama.apel.index_irtamaApel',$this->data);
    }

    public function addirtamaApel(Request $request){
        $this->data['title']="Inspektorat Utama";
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        $url_simpeg = config('app.url_simpeg');
        $query  =  execute_api_json($url_simpeg,"GET");
        if($query->code == 200 && ($query->status != 'error')){
          $this->data['satker'] = $query->data;
        }else{
          $this->data['satker'] = [];
        }
        $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());
        return view('irtama.apel.add_irtamaApel',$this->data);
    }

    public function editirtamaApel(Request $request){
        $id = $request->id;
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');
        $client = new Client();

        $requestData = $client->request('GET', $baseUrl.'/api/apelupacara/'.$id,
          [
            'headers' =>
            [
              'Authorization' => 'Bearer '.$token
            ]
          ]
        );

        $apel = json_decode($requestData->getBody()->getContents(), true);
        $url_simpeg = config('app.url_simpeg');
        $query  =  execute_api_json($url_simpeg,"GET");
        if($query->code == 200 && ($query->status != 'error')){
          $this->data['satker'] = $query->data;
        }else{
          $this->data['satker'] = [];
        }
        $this->data['data_detail'] = $apel['data'];
        $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());
        return view('irtama.apel.edit_irtamaApel',$this->data);
    }

    public function inputirtamaApel(Request $request){
      // dd($request->all());
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');
      $client = new Client();
      if ($request->input('tanggal') != '') {
        $tgl1 = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal'))));
      } else {
        $tgl1 = '';
      }

      $requestData = $client->request('POST', $baseUrl.'/api/apelupacara',
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token
          ],
          'form_params' =>
          [
            "tanggal" => $tgl1,
            "jenis_kegiatan" => $request->input('jenis_kegiatan'),
            "kode_satker" => $request->input('list_satker'),
            "jumlah_hadir" => $request->input('jumlah_hadir'),
            "jumlah_tidak_hadir" => $request->input('jumlah_tidak_hadir'),
            "keterangan_dinas" => $request->input('keterangan_dinas'),
            "keterangan_izin" => $request->input('keterangan_izin'),
            "keterangan_sakit" => $request->input('keterangan_sakit'),
            "keterangan_cuti" => $request->input('keterangan_cuti'),
            "keterangan_pendidikan" => $request->input('keterangan_pendidikan'),
            "keterangan_hamil" => $request->input('keterangan_hamil'),
            "keterangan_terlambat" => $request->input('keterangan_terlambat'),
            "keterangan_tugas_kantor" => $request->input('keterangan_tugas_kantor'),
            "keterangan_tanpa_keterangan" => $request->input('keterangan_tanpa_keterangan')
          ]
        ]
      );

      $result = json_decode($requestData->getBody()->getContents(), true);

      $this->form_params = array("tanggal" => $tgl1,
                                "jenis_kegiatan" => $request->input('jenis_kegiatan'),
                                "kode_satker" => $request->input('list_satker'),
                                "jumlah_hadir" => $request->input('jumlah_hadir'),
                                "jumlah_tidak_hadir" => $request->input('jumlah_tidak_hadir'),
                                "keterangan_dinas" => $request->input('keterangan_dinas'),
                                "keterangan_izin" => $request->input('keterangan_izin'),
                                "keterangan_sakit" => $request->input('keterangan_sakit'),
                                "keterangan_cuti" => $request->input('keterangan_cuti'),
                                "keterangan_pendidikan" => $request->input('keterangan_pendidikan'),
                                "keterangan_hamil" => $request->input('keterangan_hamil'),
                                "keterangan_terlambat" => $request->input('keterangan_terlambat'),
                                "keterangan_tugas_kantor" => $request->input('keterangan_tugas_kantor'),
                                "keterangan_tanpa_keterangan" => $request->input('keterangan_tanpa_keterangan'));

      $trail['audit_menu'] = 'Inspektorat Utama - Apel Senin & Upacara';
      $trail['audit_event'] = 'post';
      $trail['audit_value'] = json_encode($this->form_params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $result['comment'];
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($token,$trail);

      if( ($result['code'] == 200) && ($result['status'] != 'error') ){
        $inputId = $result['data']['eventID'];
        $this->kelengkapan_apel($inputId);
        $this->data['status'] =  'success';
        $this->data['message'] = 'Data Irtama Apel Senin dan Hari Besar Lainnya Berhasi Ditambahkan';
      }else{
        $this->data['status'] =  'error';
        $this->data['message'] = 'Data Irtama Apel Senin dan Hari Besar Lainnya Gagal Ditambahkan';
      }
      return redirect('/irtama/apel/irtama_apel')->with('status',$this->data);
    }

    public function updateirtamaApel(Request $request){
      $id = $request->input('id');
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');
      $client = new Client();
      if ($request->input('tanggal') != '') {
        $tgl1 = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal'))));
      } else {
        $tgl1 = '';
      }

      $requestData = $client->request('PUT', $baseUrl.'/api/apelupacara/'.$id,
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token
          ],
          'form_params' =>
          [
            "tanggal" => $tgl1,
            "jenis_kegiatan" => $request->input('jenis_kegiatan'),
            "kode_satker" => $request->input('list_satker'),
            "jumlah_hadir" => $request->input('jumlah_hadir'),
            "jumlah_tidak_hadir" => $request->input('jumlah_tidak_hadir'),
            "keterangan_dinas" => $request->input('keterangan_dinas'),
            "keterangan_izin" => $request->input('keterangan_izin'),
            "keterangan_sakit" => $request->input('keterangan_sakit'),
            "keterangan_cuti" => $request->input('keterangan_cuti'),
            "keterangan_pendidikan" => $request->input('keterangan_pendidikan'),
            "keterangan_hamil" => $request->input('keterangan_hamil'),
            "keterangan_terlambat" => $request->input('keterangan_terlambat'),
            "keterangan_tugas_kantor" => $request->input('keterangan_tugas_kantor'),
            "keterangan_tanpa_keterangan" => $request->input('keterangan_tanpa_keterangan')
          ]
        ]
      );

      $result = json_decode($requestData->getBody()->getContents(), true);

      $this->form_params = array("tanggal" => $tgl1,
                                "jenis_kegiatan" => $request->input('jenis_kegiatan'),
                                "kode_satker" => $request->input('list_satker'),
                                "jumlah_hadir" => $request->input('jumlah_hadir'),
                                "jumlah_tidak_hadir" => $request->input('jumlah_tidak_hadir'),
                                "keterangan_dinas" => $request->input('keterangan_dinas'),
                                "keterangan_izin" => $request->input('keterangan_izin'),
                                "keterangan_sakit" => $request->input('keterangan_sakit'),
                                "keterangan_cuti" => $request->input('keterangan_cuti'),
                                "keterangan_pendidikan" => $request->input('keterangan_pendidikan'),
                                "keterangan_hamil" => $request->input('keterangan_hamil'),
                                "keterangan_terlambat" => $request->input('keterangan_terlambat'),
                                "keterangan_tugas_kantor" => $request->input('keterangan_tugas_kantor'),
                                "keterangan_tanpa_keterangan" => $request->input('keterangan_tanpa_keterangan'));

      $trail['audit_menu'] = 'Inspektorat Utama - Apel Senin & Upacara';
      $trail['audit_event'] = 'put';
      $trail['audit_value'] = json_encode($this->form_params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $result['comment'];
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($token,$trail);

      if( ($result['code'] == 200) && ($result['status'] != 'error') ){
        $this->kelengkapan_apel($id);
        $this->data['status'] =  'success';
        $this->data['message'] = 'Data Irtama Apel Senin dan Hari Besar Lainnya Berhasi Diperbarui';
      }else{
        $this->data['status'] =  'error';
        $this->data['message'] = 'Data Irtama Apel Senin dan Hari Besar Lainnya Gagal Diperbarui';
      }
      return back()->with('status',$this->data);
    }

    public function printPageirtamaApel(Request $request){

        $page = $request->page;
        if($page){
            $page = $page;
        }else{
            $page = 1;
        }
        $result = [];
        $this->limit = config('app.limit');
        $start_number = ($this->limit * ($page -1 )) + 1;
        $url = 'api/apelupacara?page='.$page;

        $data_request = execute_api($url,'GET');

        $i = $start_number;
        if(count($data_request)>=1){
          $data = $data_request['data'];
          foreach($data as $key=>$value){
            $result[$key]['No'] = $i;
            $result[$key]['Tanggal'] = ( $value['tanggal'] ? date('d/m/Y',strtotime($value['tanggal'])) : '');
            $result[$key]['Jenis kegiatan'] = $value['jenis_kegiatan'];
            $result[$key]['Satker'] =$value['kode_satker'];
            $result[$key]['Jumlah hadir'] =$value['jumlah_hadir'];
            $result[$key]['Jumlah tidak hadir'] =$value['jumlah_tidak_hadir'];

            $i = $i +1;
          }
        $name = 'Apel Senin & Upacara '.date('Y-m-d H:i:s');

            $this->printData($result, $name);
         //  	echo '<pre>';
          // print_r($result);
        }else{
          return false;
        }

    }

    public function irtamaRiktu(Request $request){
      $client = new Client();
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
      $kondisi = "";
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
              }else if(($key == 'no_sprint') || ($key == 'status')|| ($key == 'satker')  || ($key == 'lokasi')){
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
        $satker = $request->satker;
        $lokasi = $request->lokasi;
        $tgl_from = $request->tgl_from;
        $tgl_to = $request->tgl_to;
        $order = $request->order;
        $limit = $request->limit;
        $status = $request->status;

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
        }else if($tipe == 'satker'){
          $kondisi .= '&satker='.$satker;
          $this->selected['keyword'] = $satker;
        }else if($tipe == 'lokasi'){
          $kondisi .= '&lokasi='.$lokasi;
          $this->selected['keyword'] = $lokasi;
        }else if($tipe == 'status'){
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

      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');
      $instansi = $this->globalinstansi($request->session()->get('wilayah'), $token);
      $this->data['instansi'] = $instansi;
      $propkab = $this->globalPropkab($token);

      if($propkab['code'] == 200 && $propkab['status'] != 'error'){
        $this->data['propkab'] = $propkab['data'];
      }else{
        $this->data['propkab'] = [];
      }


      $limit = 'limit='.$this->limit;
      $offset = 'page='.$current_page;
      $datas = execute_api_json('api/rikturiksus?'.$limit.'&'.$offset.$kondisi,'get');

      $total_item = 0;
      if($datas->code == 200){
          $this->data['data_riktu'] = $datas->data;
          $total_item = $datas->paginate->totalpage * $this->limit;
      }else{
          $this->data['data'] = [];
          $total_item = 0;
      }

      $this->data['filter'] =  $this->selected;
      $this->data['current_page'] = $current_page ;
      $this->data['start_number'] = $start_number ;
      $this->data['title'] = "rikturiksus";
      $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
      // echo '<pre>';
      // print_r($this->data['instansi']);
      // echo '</pre>';
      $this->data['token'] = $token;
      $this->data['delete_route'] = 'delete_irtama_riktu';
      $this->data['path'] = $request->path();
      // $this->data['page'] = $page;
      $this->data['kondisi'] ='?'.$offset.$kondisi;
      $this->data['route'] = $request->route()->getName();
      $filtering = false;
      if($kondisi){
        $filter = $kondisi;
        $filtering = true;
      }else{
        $filter = '/';
        $filtering = false;
      }
      $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'),config('app.url'). "/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
      $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());
      return view('irtama.riktu.index_irtamaRiktu',$this->data);
    }

    public function addirtamaRiktu(Request $request){
      $client = new Client();
      $token = $request->session()->get('token');

      $requestLookup = $client->request('GET', url('/api/lookup/barangbukti_irtama_rikturiksus'),
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token
          ]
        ]
      );

      $barangbukti = json_decode($requestLookup->getBody()->getContents(), true);
      if(($barangbukti['code'] == 200) && ($barangbukti['status'] != 'error') ){
        $this->data['barang_bukti'] = $barangbukti['data'];
      }else{
        $this->data['barang_bukti'] = [];
      }
      $requestLookup = $client->request('GET', url('/api/lookup/sumber_informasi_irtama'),
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token
          ]
        ]
      );

      $informasi = json_decode($requestLookup->getBody()->getContents(), true);
      if(($informasi['code'] == 200) && ($informasi['status'] != 'error') ){
        $this->data['informasi'] = $informasi['data'];
      }else{
        $this->data['informasi'] = [];
      }
      $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());
      $instansi = $this->globalinstansi($request->session()->get('wilayah'), $token);
      $this->data['instansi'] = $instansi;
      $propkab = $this->globalPropkab($token);
      $this->data['propkab'] = $propkab['data'];
      $this->data['hasil_riktu'] = config('lookup.hasil_riktu');
      $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());
      return view('irtama.riktu.add_irtamaRiktu',$this->data);
    }

    public function editirtamaRiktu(Request $request){
      $id = $request->id;
      $client = new Client();
      $token = $request->session()->get('token');
      $requestRiktu = $client->request('GET', url('/api/rikturiksus/'.$id),
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token
          ]
        ]
      );
      $riktu = json_decode($requestRiktu->getBody()->getContents(), true);
      if(($riktu['code'] == 200) && ($riktu['status'] != 'error')){
        $this->data['riktu'] = $riktu['data'];
      }else{
         $this->data['riktu'] =  [];
      }
      $requestLookup = $client->request('GET', url('/api/lookup/barangbukti_irtama_rikturiksus'),
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token
          ]
        ]
      );

      $barangbukti = json_decode($requestLookup->getBody()->getContents(), true);
      if(($barangbukti['code'] == 200) && ($barangbukti['status'] != 'error') ){
        $this->data['barang_bukti'] = $barangbukti['data'];
      }else{
        $this->data['barang_bukti'] = [];
      }
      $requestLookup = $client->request('GET', url('/api/lookup/sumber_informasi_irtama'),
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token
          ]
        ]
      );

      $informasi = json_decode($requestLookup->getBody()->getContents(), true);
      if(($informasi['code'] == 200) && ($informasi['status'] != 'error') ){
        $this->data['informasi'] = $informasi['data'];
      }else{
        $this->data['informasi'] = [];
      }
      $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());
      $instansi = $this->globalinstansi($request->session()->get('wilayah'), $token);
      $this->data['instansi'] = $instansi;
      $propkab = $this->globalPropkab($token);
      $this->data['propkab'] = $propkab['data'];
      $this->data['hasil_riktu'] = config('lookup.hasil_riktu');
      return view('irtama.riktu.edit_irtamaRiktu',$this->data);
    }

    public function inputirtamaRiktu(Request $request)
    {
      $messages = [];
      $fileMessage = "";
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');
      $client = new Client();
      $form_params = $request->except('_token');
      if($request->tgl_hasil_laporan){
        $date = explode('/', $request->tgl_hasil_laporan);
        $form_params['tgl_hasil_laporan'] = $date[2].'-'.$date[1].'-'.$date[0];
      }
      $requestData = $client->request('POST', $baseUrl.'/api/rikturiksus',
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token
          ],
          'form_params' => $form_params
        ]);

      $result = json_decode($requestData->getBody()->getContents(), true);

      $trail['audit_menu'] = 'Inspektorat Utama - Audit dengan Tujuan Tertentu';
      $trail['audit_event'] = 'post';
      $trail['audit_value'] = json_encode($form_params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $result['comment'];
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

      if( ($result['code'] == 200) && ($result['status'] != 'error')){
        $messages['status'] = 'success';
        $messages['message'] = 'Data Irtama Riktu Berhasil Ditambahkan.';
        $inputId = $result['data']['eventID'];
        if ($request->file('file_hasil_pemeriksaan')){
            $fileName = date('Y-m-d').'_'.$inputId.'-'.$request->file('file_hasil_pemeriksaan')->getClientOriginalName();
            try{
              $storeFile = $request->file('file_hasil_pemeriksaan')->storeAs('IrtamaRiktu', $fileName);
              $requestfile = $client->request('PUT', $baseUrl.'/api/rikturiksus/'.$inputId,
                [
                  'headers' =>
                  [
                      'Authorization' => 'Bearer '.$token
                  ],
                  'form_params' => [
                      'file_hasil_pemeriksaan' => $fileName,
                  ]
                ]
              );
              $resulti = json_decode($requestfile->getBody()->getContents(), true);
              if( ($resulti['code'] == 200)&& ($resulti['status'] != 'error') ) {
                $fileMessage = "Dengan File Gagal Diunggah.";
              }else{
                $fileMessage = "";
              }
            }catch(\Exception $e){
              $fileMessage = "Dengan File Gagal Diunggah.".$e->getMessage();
            }
         }
         $this->kelengkapan_riktu($inputId);
      }else{
        $messages['status'] = 'error';
        $messages['message'] = 'Data Irtama Riktu Gagal Ditambahkan.'.$fileMessage;
      }
      return redirect(route('irtama_riktu'))->with('status',$messages);
    }

    public function updateirtamaRiktu(Request $request){
      $messages= [];
      $id =$request->id;
      $token = $request->session()->get('token');
      $form_params = $request->except(['_token','id']);
      $fileMessage =  "";
      if($request->tgl_hasil_laporan){
        $date = explode('/', $request->tgl_hasil_laporan);
        $form_params['tgl_hasil_laporan'] = $date[2].'-'.$date[1].'-'.$date[0];
      }
      if ($request->file('file_hasil_pemeriksaan') != ''){
            $fileName = date('Y-m-d').'_'.$id.'-'.$request->file('file_hasil_pemeriksaan')->getClientOriginalName();
            try{
              $storeFile = $request->file('file_hasil_pemeriksaan')->storeAs('IrtamaRiktu', $fileName);
              $form_params['file_hasil_pemeriksaan'] = $fileName;
            }catch(\Exception $e){
              $fileMessage = "Dengan File Gagal Diunggah.";
            }
         }
      $client = new Client();
      $store = $client->request('PUT', url('/api/rikturiksus/'.$id),
        [
          'headers' =>
          [
            'Authorization' => 'Bearer '.$token
          ],
          'form_params' => $form_params
      ]);
      $result = json_decode($store->getBody()->getContents(), true);

      $trail['audit_menu'] = 'Inspektorat Utama - Audit dengan Tujuan Tertentu';
      $trail['audit_event'] = 'put';
      $trail['audit_value'] = json_encode($form_params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $result['comment'];
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

      $this->kelengkapan_riktu($id);
      if( ($result['code'] == 200)&& ($result['status'] != 'error') ) {
        $messages['status'] = 'success';
        $messages['message'] = 'Data RIKTU Berhasil Diperbarui. '.$fileMessage;
      }else{
        $messages['status'] = 'error';
        $messages['message'] = 'Data RIKTU Gagal Diperbarui. '.$fileMessage;
      }
      return back()->with('status',$messages);
    }


    public function irtamaPtl(Request $request){
      $this->limit = config('app.limit');
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
              }else if(($key == 'nomor_lha') || ($key == 'nama_satker')  || ($key == 'ketua_tim')){
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
        $nama_satker = $request->nama_satker;
        $tgl_from = $request->tgl_from;
        $tgl_to = $request->tgl_to;
        $order = $request->order;
        $limit = $request->limit;

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
        }else if($tipe == 'nama_satker'){
          $kondisi .= '&nama_satker='.$nama_satker;
          $this->selected['keyword'] = $nama_satker;
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

      $this->data['title'] = "Audit Laporan Pemantauan Tindak Lanjut";
      if($request->page){
          $current_page = $request->page;
          $start_number = ($this->limit * ($request->page -1 )) + 1;
      }else{
          $current_page = 1;
          $start_number = $current_page;
      }
      $limit = 'limit='.$this->limit;
      $offset = 'page='.$current_page;

      $datas = execute_api_json('api/irtamaptl?'.$limit.'&'.$offset.$kondisi,'get');

      $this->data['kondisi'] ='?'.$offset.$kondisi;
      if($datas->code == 200){
          $this->data['data'] = $datas->data;
      }else{
          $this->data['data'] = [];
      }
      $query  =  execute_api('api/lookup/irtama_satker',"GET");
      if($query['code'] == 200 && ($query['status'] != 'error')){
        $this->data['satker'] = $query['data'];
      }else{
        $this->data['satker'] = [];
      }
      $filtering = false;
      if($kondisi){
        $filter = $kondisi;
        $filtering = true;
      }else{
        $filter = '/';
        $filtering = false;
      }

      $this->data['route'] = $request->route()->getName();
      $this->data['filter'] = $this->selected;
      $this->data['delete_route']  = 'delete_irtama_ptl';
      $this->data['path'] = $request->path();
      $this->data['route_name'] = $request->route()->getName();
      $this->data['start_number'] = $start_number;
      $this->data['current_page'] = $current_page;
      $total_item = $datas->paginate->totalpage * $this->limit;
      $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), "/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
      return view('irtama.ptl.index_irtamaPtl',$this->data);
    }

    public function editirtamaPtl(Request $request){
        $id = $request->id;
        $datas = execute_api_json('api/irtamaptl/'.$id,'get');
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }
        $status = execute_api_json('api/lookup/rekomendasi_status','GET');
        if($status->code == 200 && $status->status != 'error'){
          $this->data['status'] =$status->data;
        }else{
          $this->data['status'] =[];
        }
        $this->data['delete_route'] = 'delete_irtama_ptl';
        $this->data['breadcrumps'] = breadcrumps_irtama($request->route()->getName());
        return view('irtama.ptl.edit_irtamaPtl',$this->data);
    }

    public function editTemuanPRL(Request $request){
      $segment = $request->segment;
      $id = $request->id;

    }
    /*
    BIDANG LHA
    */
    public function addBidangLHA(Request $request){
      // kurang masukin rekomendasi ke table ptl

      $this->form_params= $request->except(['_token','meta_rekomendasi','id_lha_parent']);
      $file_message = "";
      if($request->file('bukti_temuan')){
          $fileName = $request->file('bukti_temuan')->getClientOriginalName();
          $fileName = date('Y-m-d').'_'.$fileName;
          $extension = $request->file('bukti_temuan')->getClientOriginalExtension(); // getting image extension
          $tipe = $request->tipe;
          $tipe = ucfirst($tipe);
          $directory = 'Irtama/Bidang'.$tipe;
          try {
            $path = Storage::putFileAs($directory, $request->file('bukti_temuan'),$fileName);
            if($path){
                $this->form_params['bukti_temuan'] = $fileName;
            }else{
                $file_message = "Dengan File gagal diupload.";
            }

          }catch(\Exception $e){
                $file_message = "Dengan File gagal diupload.";
          }
      }

      $this->form_params['id_lha'] = $request->id_lha_parent;
      $data_request = execute_api_json('api/irtamalhabidang','post',$this->form_params);
      $rekomendasi_message = [];
      if( ($data_request->code == 200) && ($data_request->status != 'error') ){

          $id_ha_bidang = $data_request->data->eventID;
          if($id_ha_bidang){
            if($request->meta_rekomendasi){
                $l =$request->meta_rekomendasi;
                if($l > 0 ){
                  for($i =0; $i < count($l) ; $i ++){
                    $rekomendasi = $l[$i];
                    $empty = true;
                    foreach ($rekomendasi as $value) {
                      if($value){
                        $empty = false;
                        break;
                      }else{
                        continue;
                      }
                    }
                    if($empty == false){
                      $this->form_params =  $l[$i];
                      $this->form_params['id_lha_bidang'] = $id_ha_bidang;
                      $rekomendasi =  execute_api_json('api/irtamalharekomendasi','post',$this->form_params);
                      if($rekomendasi->status == 'error' && $rekomendasi->code == 200){
                        $rekomendasi_message[] =  $this->form_params['judul_rekomendasi'];
                      }else{
                        continue;
                      }
                    }else{
                      continue;
                    }
                  }
                }
            }else{
              $rekomendasi_message = [];
            }
          }else{
            $rekomendasi_message = [];
          }
          $this->data['status'] = 'success';
          $this->data['message'] = 'data bidang lha   Berhasil Ditambahkan '. $file_message;
          $this->data['rekomendasi_message'] =$rekomendasi_message;

      }else{
          $this->data['status'] = 'error';
          $this->data['message'] = 'Dokumen LHA Gagal Ditambahkan';
          $this->data['rekomendasi_message'] = [];
      }
      return back()->with('status',$this->data);
    }

    // public function updateBidangLHA(Request $request){
    //   $this->form_params= $request->except(['_token','meta_rekomendasi','id_lha_parent']);
    //   $file_message = "";
    //   if($request->file('bukti_temuan')){
    //       $fileName = $request->file('bukti_temuan')->getClientOriginalName();
    //       $fileName = date('Y-m-d').'_'.$fileName;
    //       $extension = $request->file('bukti_temuan')->getClientOriginalExtension(); // getting image extension
    //       $directory = 'Irtama/BidangKinerja';
    //       $path = Storage::putFileAs($directory, $request->file('bukti_temuan'),$fileName);
    //       if($path){
    //           $this->form_params['bukti_temuan'] = $fileName;
    //       }else{
    //           $file_message = "Dengan File gagal diupload.";
    //       }
    //       $this->form_params['bukti_temuan']  =$fileName;
    //   }
    //   $this->form_params['id_lha'] = $request->id_lha_parent;
    //   $data_request = execute_api_json('api/irtamalhabidang','post',$this->form_params);
    //   $rekomendasi_message = [];
    //   if( ($data_request->code == 200) && ($data_request->status != 'error') ){

    //       $id_ha_bidang = $data_request->data->eventID;
    //       if($id_ha_bidang){
    //         if($request->meta_rekomendasi){
    //             $l = count($request->meta_rekomendasi);
    //             if($l > 0 ){
    //               for($i =0; $i < $l ; $i ++){
    //                 $this->form_params = $l[$i];
    //                 $this->form_params['id_lha_bidang'] = $id_ha_bidang;
    //                 $rekomendasi =  execute_api_json('api/irtamalharekomendasi','post',$this->form_params);
    //                 if($rekomendasi->status == 'error' && $rekomendasi->code == 200){
    //                   $rekomendasi_message[] =  $this->form_params['judul_rekomendasi'];
    //                 }else{
    //                   continue;
    //                 }
    //               }
    //             }
    //         }else{
    //           $rekomendasi_message = [];
    //         }
    //       }else{
    //         $rekomendasi_message = [];
    //       }
    //       $this->data['status'] = 'success';
    //       $this->data['message'] = 'data bidang lha   Berhasil Ditambahkan '. $file_message;
    //       $this->data['rekomendasi_message'] =$rekomendasi_message;

    //   }else{
    //       $this->data['status'] = 'error';
    //       $this->data['message'] = 'Dokumen LHA Gagal Ditambahkan';
    //       $this->data['rekomendasi_message'] = [];
    //   }
    //   // print_r( $this->data);
    //   // print_r($this->form_params);
    //   // save to bidang
    //   // save to rekomendasi
    //   return back()->with('status',$this->data);
    // }

    public function deleteBidangLHA(Request $request){
      if ($request->ajax()) {
        $id = $request->id;
        if($id){
            $data_request = execute_api('api/irtamalhabidang/'.$id,'DELETE');
            $data_request = execute_api('api/deletelharekomendasi/'.$id,'GET');
            return $data_request;
        }else{
            $data_request = ['code'=>200,'status'=>'error','message'=>'Data Bidang Gagal Dihanpus'];
            return $data_request;
        }
      }

    }
    public function updateBidangLHA(Request $request){
        $data = $request->all();
        $bidang = $request->except(['_token','tipe','id_lha_bidang','meta_rekomendasi','id_deleted']);
        $tipe = $request->tipe;
        $tipe = ucfirst($tipe);
        $directory = 'Irtama/Bidang'.$tipe;
        $id = $request->id_lha_bidang;
        $file = $request->bukti_temuan;
        $error_file ="";
        if($file ){
          $filename  = $file->getClientOriginalName();
          try {
            $filename = date('Y-m-d').'_'.'_'.$filename;
            $path = Storage::putFileAs($directory, $file ,$filename);
            $bidang['bukti_temuan'] = $filename;
          }catch(\Exception $e){
            $error_file =" Dengan file gagal diupload";
          }
        }else{
          $error_file = "";
        }
        $query = execute_api_json('api/irtamalhabidang/'.$id,'PUT',$bidang);
        $bidang_message = "";
        $bidang_status = "";
        if($query->code == 200 && $query->status != 'error'){
            $bidang_message= "Data Bidang Berhasil Diperbarui. ".$error_file;
            $bidang_status= "success";
        }else{
            $bidang_message= "Data Bidang Gagal Diperbarui";
            $bidang_status= "error";
        }

        $rekomendasi = $request->meta_rekomendasi;

        $rekomendasi_update =[];
        $rekomendasi_new =[];
        if(count($rekomendasi) > 0 ){
          for($i = 0; $i < count($rekomendasi) ; $i++){
            $r = $rekomendasi[$i];

            $id_rek = $r['id_rekomendasi'];
              if($r['id_rekomendasi']){
                unset($r['id_rekomendasi']);
                $query = execute_api_json('api/irtamalharekomendasi/'.$id_rek,'PUT',$r);
                if($query->status =='error' && $query->code == 200){
                    $rekomendasi_update[] =$r['judul_rekomendasi'];
                }else{
                    continue;
                }
              }else{
                unset($r['id_rekomendasi']);
                $r['id_lha_bidang'] =  $id;
                $query = execute_api_json('api/irtamalharekomendasi/','POST',$r);
                if($query->status =='error' && $query->code == 200){
                    $rekomendasi_new[] =$r['judul_rekomendasi'];
                }else{
                    continue;
                }
              }
          }
        }else{
          $rekomendasi_update =[];
          $rekomendasi_new =[];
        }


        $id_deleted = $request->id_deleted;
        if($id_deleted){
            $array_id = [];
            $array_id = explode(',',$id_deleted);
            $rekomendasi_delete = [];
            if(count($array_id) > 0 ){
              for($j = 0; $j < count($array_id); $j++){
                $query = execute_api_json('api/irtamalharekomendasi/'.$array_id[$j],'DELETE');
                if($query->status =='error' && $query->code == 200){
                    $rekomendasi_delete[] = $rekomendasi[$j]['judul_rekomendasi'];
                }else{
                    continue;
                }
              }
            }else{
              $rekomendasi_delete=[];
            }
          }else{
            $rekomendasi_delete=[];
          }

          $this->data['bidang_message']  = $bidang_message;
          $this->data['bidang_status']  = $bidang_status;
          $this->data['error_rekomendasi_update']  = (count($rekomendasi_update ) > 0 ? implode(',',$rekomendasi_update) :'');
          $this->data['error_rekomendasi_new']  = (count($rekomendasi_new ) > 0 ? implode(',',$rekomendasi_new) :'') ;
          $this->data['rekomendasi_delete']  = (count($rekomendasi_delete ) > 0 ? implode(',',$rekomendasi_delete) :'') ;

          return back()->with('status_rekomendasi',$this->data);
    }
    public function getDetailBidang(Request $request){
      $id = $request->id;
      $where = ['id_lha_bidang'=>$id];
      $file_path = "";
      $query = AuditBidangLha::where($where);
      if($query->count()>0){
        $this->data['bidang'] = $query->first();
        $q = $query->first();
        $path = config('app.irtama_'.$q->tipe.'_file_path');
        if(file_exists($path.$q->bukti_temuan)){
          $file_path = url($path.$q->bukti_temuan);
        }else{
          $file_path="";
        }
      }else{
        $this->data['bidang'] =[];
        $file_path="";
      }
      $query = AuditRekomendasiBidang::where($where);
      if($query->count()>0){
        $this->data['rekomendasi'] = $query->get();
      }else{
        $this->data['rekomendasi'] =[];
      }
      $this->data['file_path'] = $file_path;
      return json_encode($this->data);
    }

    public function printPage(Request $request,$kondisi){
        $array_segments = [
            'irtama_audit'=>'auditlha',
            'irtama_ptl'=>'irtamaptl',
            'irtama_riktu'=>'rikturiksus',
            'irtama_sosialisasi'=>'irtamasosialisasi',
            'irtama_verifikasi'=>'irtamaverifikasi',
            'irtama_sop'=>'sopkebijakan',
            'irtama_penegakan'=>'penegakandisiplin',
            'irtama_apel'=>'apelupacara',
            'irtama_lk'=>'reviulk',
            'irtama_rkakl'=>'reviurkakl',
            'irtama_rkbmn'=>'reviurkbmn',
            'irtama_lkip'=>'reviulkip',
        ];
        $array_titles=[
            'irtama_audit'=>'Irtama Audit LHA',
            'irtama_ptl'=>'Irtama Audit PTL',
            'irtama_riktu'=>'Irtama Audit Dengan Tujuan Tertentu',
            'irtama_sosialisasi'=>'Irtama Sosialisasi',
            'irtama_verifikasi'=>'Irtama Verifikasi',
            'irtama_sop'=>'Irtama Sop Kebijakan',
            'irtama_penegakan'=>'Irtama Penegakan Disiplin',
            'irtama_apel'=>'Irtama Apel Senin dan Upacara Lainnya',
            'irtama_lk'=>'Reviu Laporan Keuangan',
            'irtama_rkakl'=>'Reviu Rencana Kerja Anggaran Kementerian/Lembaga',
            'irtama_rkbmn'=>'Reviu Rencana Kebutuhan Barang Milik Negara',
            'irtama_lkip'=>'Reviu Laporan Kinerja Instansi Pemerintah',
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
        // echo '<br/>';
        // echo 'start_number '.$start_number;
        // if($page){
        //     $page = $page;
        // }else{
        //     $page = 1;
        // }
        $url = 'api/'.$array_segments[$segment].$kondisi;
        // exit();
        $data_request = execute_api_json($url,'GET');

        $result= [];
        $i = $page;
        if ($segment == 'irtama_audit') {
            if(count($data_request)>=1){
                $data = $data_request->data;
                if(count($data) >0){
                    $i =  $start_number;
                    foreach($data as $key=>$d){
                        $result[$key]['No'] =  $i;
                        $result[$key]['No LHA'] =$d->nomor_lha;
                        $result[$key]['Tanggal LHA'] =$d->nomor_lha;
                        $result[$key]['Periode Audit'] =($d->tgl_mulai ? date('d/m/Y',strtotime($d->tgl_mulai)) : '') .' - '. ($d->tgl_selesai ? date('d/m/Y',strtotime($d->tgl_selesai)) : '');
                        $nama_satker = $d->nama_satker;
                        if($nama_satker){
                            $j = json_decode($nama_satker);
                            $satker = $j->satker;
                        }else{
                          $satker = "";
                        }
                        $result[$key]['Nama Satker'] =  $satker;

                        $result[$key]['Status'] = ( $d->status ? ( (trim($d->status) == 'Y' )? 'Lengkap' : 'Belum Lengkap'):'Belum Lengkap');
                        $mutu = "";
                        $nama = [];
                        $coll_nama = "";

                        $mutu = $d->pengendali_mutu;
                        if($mutu){
                          $json = json_decode($mutu,true);
                          if(count($json)> 0 ){
                            foreach ($json as $j){
                              $nama[] = $j['nama'];
                            }
                          }
                        }
                        if($nama){
                          $coll_nama = implode("\n",$nama);
                        }
                        $result[$key]['Pengendali Mutu'] = $coll_nama;
                        $mutu = $d->pengendali_teknis;
                        $nama = [];
                        if($mutu){
                          $json = json_decode($mutu,true);
                          if(count($json)> 0 ){
                            foreach ($json as $j){
                              $nama[] = $j['nama'];
                            }
                          }
                        }
                        if($nama){
                          $coll_nama = implode("\n",$nama);
                        }
                        $result[$key]['Pengendali Teknis'] =$coll_nama ;

                        $mutu = $d->ketua_tim;
                        $nama = [];
                        if($mutu){
                          $json = json_decode($mutu,true);
                          if(count($json)> 0 ){
                            foreach ($json as $j){
                              $nama[] = $j['nama'];
                            }
                          }
                        }
                        if($nama){
                          $coll_nama = implode("\n",$nama);
                        }


                        $result[$key]['Ketua Tim'] =$coll_nama;

                        $mutu = $d->meta_tim_anggota;
                        if($mutu){
                          $json = json_decode($mutu,true);
                          if(count($json)> 0 ){
                            foreach ($json as $j){
                              $nama[] = $j['nama'];
                            }
                          }
                        }
                        if($nama){
                          $coll_nama = implode("\n",$nama);
                        }
                        $result[$key]['Anggota'] = $coll_nama;

                        $i = $i+1;
                    }
                }else{
                    $result= [];
                }
                $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');

                $this->printData($result, $name);
            }else{
                return false;
            }
        }else if ($segment == 'irtama_ptl') {
          if(($data_request->code == 200) && ($data_request->status != 'error')){
            $param = $data_request->data;

            $excel = Excel::create('Data PTL Page - '.$get['page'], function($excel) use($param) {
              foreach($param as $d){
                $id = $d->id_ptl;
                $rekomendasi = execute_api_json('/api/ptlbidang/'.$id,'GET');
                if($rekomendasi->code == 200 && $rekomendasi->status != 'error'){
                  $r  = $rekomendasi->data;
                    $excel->sheet( 'ID - '.$id, function($sheet) use($r) {

                        $sheet->loadView('irtama.ptl.print_template.irtama_ptl', array('data' => $r));
                    });
                }
              }
            })->export('xls');

          }
        }else if($segment == 'irtama_riktu'){
          if($data_request->code == 200 && $data_request->status != 'error'){
            $data = $data_request->data;
                $i = $start_number;
             foreach($data as $key=>$d){
                $result[$key]['No'] = $i;
                $result[$key]['No Surat Perintah'] =$d->no_sprint;
                $result[$key]['No Hasil Laporan'] =$d->no_hasil_laporan;
                $result[$key]['Tanggal Hasil Laporan'] =($d->tgl_hasil_laporan ? date('d/m/Y',strtotime($d->tgl_hasil_laporan)) : '');
                $result[$key]['Judul Hasil Laporan']  =$d->judul_hasil_laporan ;
                $result[$key]['Jenis Pelanggaran'] =$d->jenis_pelanggaran;
                $result[$key]['Tempat Kejadian'] = getWilayahName($d->tempatkejadian);
                $result[$key]['Jumlah Terperiksa'] =$d->jumlah_terperiksa;
                $result[$key]['Jumlah Saksi'] =$d->jumlah_saksi;
                $result[$key]['Kriteria Perka'] =$d->kriteria_perka;
                $result[$key]['Status'] = ( $d->status ? ( (trim($d->status) == 'Y' )? 'Lengkap' : 'Belum Lengkap'):'Belum Lengkap');
                $i = $i+1;
              }
              $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
              $this->printData($result, $name);
          }else{
            echo 'data tidak ada ';
          }
        }else if($segment == 'irtama_sosialisasi'){

          if($data_request->code == 200 && $data_request->status != 'error'){
            $data = $data_request->data;
                $i = $start_number;
             foreach($data as $key=>$d){
                $result[$key]['No'] = $i;
                $result[$key]['No Surat Perintah'] =$d->sprin;
                $result[$key]['Lokasi'] =$d->lokasi;

                $data_satker = $d->kode_satker;
                $id_satker = "";
                if($data_satker){
                  $j = json_decode($data_satker,true);
                  $nama_satker = $j['nama'];
                }else{
                  $nama_satker = "";
                }

                $result[$key]['Satker'] =  $nama_satker;
                $result[$key]['No Laporan']  =$d->no_laporan ;
                $result[$key]['Tanggal Laporan'] = ($d->tgl_laporan ? date('d/m/Y',strtotime($d->tgl_laporan)) : '');
                $result[$key]['Jumlah Peserta'] = $d->jumlah_peserta;
                $result[$key]['Pemapar'] =$d->pemapar;
                $result[$key]['Status'] = ( $d->status ? ( (trim($d->status) == 'Y' )? 'Lengkap' : 'Belum Lengkap'):'Belum Lengkap');
                $i = $i+1;
              }
              $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
              $this->printData($result, $name);
          }else{
            echo 'data tidak ada ';
          }

        }else if($segment == 'irtama_verifikasi'){


          if($data_request->code == 200 && $data_request->status != 'error'){
            $data = $data_request->data;
                $i = $start_number;
             foreach($data as $key=>$d){
                $result[$key]['No'] = $i;
                $result[$key]['Surat Perintah'] =$d->sprin;
                $result[$key]['Lokasi'] =$d->lokasi;
                $data_satker = $d->kode_satker;
                $id_satker = "";
                if($data_satker){
                  $j = json_decode($data_satker,true);
                  $nama_satker = $j['nama'];
                }else{
                  $nama_satker = "";
                }

                $result[$key]['Satker'] =  $nama_satker;
                $result[$key]['Pejabat yang Diganti']  =$d->pejabat_diganti ;
                $result[$key]['Pejabat yang Baru'] = $d->pejabat_baru;
                $result[$key]['Tanggal laporan'] = ($d->tgl_laporan ? date('d/m/Y',strtotime($d->tgl_laporan)) : '');
                $result[$key]['Status'] = ( $d->status ? ( (trim($d->status) == 'Y' )? 'Lengkap' : 'Belum Lengkap'):'Belum Lengkap');
                $i = $i+1;
              }
              $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
              $this->printData($result, $name);
          }else{
            echo 'data tidak ada ';
          }
        }else if($segment == 'irtama_sop'){
          if($data_request->code == 200 && $data_request->status != 'error'){
            $data = $data_request->data;
            $i = $start_number;
             foreach($data as $key=>$d){
                $result[$key]['No'] = $i;
                $result[$key]['Surat Perintah'] =$d->sprin;
                $result[$key]['Tgl Surat Perintah'] = ($d->tgl_sprin? date('d/m/Y',strtotime($d->tgl_sprin)) : '');
                $result[$key]['Nama SOP &amp; kebijakan'] = $d->nama_sop_kebijakan ;
                $result[$key]['Jenis SOP &amp; kebijakan']  =$d->jenis_sop_kebijakan ;
                $result[$key]['Tgl SOP'] = ($d->tgl_sop? date('d/m/Y',strtotime($d->tgl_sop)) : '');
                $result[$key]['Status'] = ( $d->status ? ( (trim($d->status) == 'Y' )? 'Lengkap' : 'Belum Lengkap'):'Belum Lengkap');
                $i = $i+1;
              }
              $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
              $this->printData($result, $name);
          }else{
            echo 'data tidak ada ';
          }

        }else if($segment == 'irtama_penegakan'){
          if($data_request->code == 200 && $data_request->status != 'error'){
            $query  =  execute_api('api/lookup/irtama_satker',"GET");
            if($query['code'] == 200 && ($query['status'] != 'error')){
              $satker= $query['data'];
            }else{
              $satker= [];
            }
            $data = $data_request->data;
            $i = $start_number;
             foreach($data as $key=>$d){
                $result[$key]['No'] = $i;
                $result[$key]['No Laporan'] =$d->no_laporan;
                $result[$key]['Tanggal Laporan'] = ($d->tgl_laporan? date('d/m/Y',strtotime($d->tgl_laporan)) : '');
                $result[$key]['Satker'] = ( isset($satker) ? (isset($satker[$d->kode_satker]) ? $satker[$d->kode_satker] :$d->kode_satker ) : '') ;
                $result[$key]['Status'] = ( $d->status ? ( (trim($d->status) == 'Y' )? 'Lengkap' : 'Belum Lengkap'):'Belum Lengkap');
                $i = $i+1;
              }
              $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
              $this->printData($result, $name);
          }else{
            echo 'data tidak ada ';
          }

        }else if($segment == 'irtama_apel'){
          if($data_request->code == 200 && $data_request->status != 'error'){
            $query  =  execute_api('api/lookup/irtama_satker',"GET");
            if($query['code'] == 200 && ($query['status'] != 'error')){
              $satker= $query['data'];
            }else{
              $satker= [];
            }
            $data = $data_request->data;
            $i = $start_number;
             foreach($data as $key=>$d){
                $result[$key]['No'] = $i;
                $result[$key]['Tanggal'] = ($d->tanggal ? date('d/m/Y',strtotime($d->tanggal)) : '');
                $result[$key]['Jenis kegiatan'] =$d->jenis_kegiatan;
                $data_satker = $d->kode_satker;
                $id_satker = "";
                if($data_satker){
                  $j = json_decode($data_satker,true);
                  $nama_satker = $j['nama'];
                }else{
                  $nama_satker = "";
                }

                $result[$key]['Satker'] =  $nama_satker;
                $result[$key]['Jumlah Hadir'] = $d->jumlah_hadir;
                $result[$key]['Jumlah Tidak Hadir'] = $d->jumlah_tidak_hadir;
                $result[$key]['Status'] = ( $d->status ? ( (trim($d->status) == 'Y' )? 'Lengkap' : 'Belum Lengkap'):'Belum Lengkap');
                $i = $i+1;
              }
              $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
              $this->printData($result, $name);
          }else{
            echo 'data tidak ada ';
          }

        }else if($segment == 'irtama_lk'){
          if($data_request->code == 200 && $data_request->status != 'error'){
            $data = $data_request->data;
            $i = $start_number;
             foreach($data as $key=>$d){
                $result[$key]['No'] = $i;
                $result[$key]['Objek Reviu'] = 'UAPPA : '.$d->uappa."\n".
                                              'UAPPA-E1 : '.$d->uappa_e1."\n".
                                              'UAPPA-W : '.$d->uappa_w."\n".
                                              'UAKPA : '.$d->uappa_w."\n";
                $result[$key]['Surat Perintah'] =$d->no_sprint;
                $result[$key]['Ketua Tim'] =  $d->ketua_tim;
                $result[$key]['Hasil reviu'] ='Hasil Reviu LRA:'. $d->lap_realisasi."\n".
                                              'Hasil Reviu Neraca:'. $d->neraca."\n".
                                              'Hasil Reviu LO:'. $d->lap_operasional."\n".
                                              'Hasil Reviu LPE:'. $d->lap_perubahan."\n".
                                              'Hasil Reviu CaLK:'. $d->catatan_lap;
                $result[$key]['Tanggal Laporan'] = ($d->tanggal_lap ? date('d/m/Y',strtotime($d->tanggal_lap)) :'');
                $result[$key]['Status'] = ( $d->status ? ( (trim($d->status) == 'Y' )? 'Lengkap' : 'Belum Lengkap'):'Belum Lengkap');
                $i = $i+1;
              }
              $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
              $this->printData($result, $name);
          }else{
            echo 'data tidak ada ';
          }
        }else if($segment == 'irtama_rkakl'){
          if($data_request->code == 200 && $data_request->status != 'error'){
            $data = $data_request->data;
            $i = $start_number;
            foreach($data as $key=>$d){
               $result[$key]['No'] = $i;
               $result[$key]['Ketua Tim'] = $d->ketua_tim;
               $result[$key]['Surat Perintah'] = $d->no_sprint;
               $result[$key]['Tahun Anggaran'] = $d->tahun_anggaran;
               $result[$key]['Status'] = $d->status == 'Y' ? 'Lengkap' : 'Belum Lengkap';
               $i = $i +1;
            }
            $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
            $this->printData($result, $name);
          }else{
            echo 'data tidak ada ';
          }
        }else if($segment == 'irtama_rkbmn'){
          if($data_request->code == 200 && $data_request->status != 'error'){
            $data = $data_request->data;
            $i = $start_number;
            foreach($data as $key=>$d){
               $result[$key]['No'] = $i;
               $result[$key]['No Surat Perintah'] = $d->no_sprint;
               $result[$key]['Ketua Tim'] = $d->ketua_tim;
               $result[$key]['Tanggal Laporan'] = ($d->tanggal_lap ? date('d/m/Y', strtotime(str_replace('/', ',', $d->tanggal_lap))) : '');
               $result[$key]['Tahun Anggaran'] = $d->tahun_anggaran;
               $result[$key]['Status'] = $d->status == 'Y' ? 'Lengkap' : 'Belum Lengkap';
               $i = $i +1;
            }
            $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
            $this->printData($result, $name);
          }else{
            echo 'data tidak ada ';
          }
        }else if($segment == 'irtama_lkip'){
          if($data_request->code == 200 && $data_request->status != 'error'){
            $data = $data_request->data;
            $i = $start_number;
            foreach($data as $key=>$d){
               $result[$key]['No'] = $i;
               $result[$key]['No Surat Perintah'] = $d->no_sprint;
               $result[$key]['Tanggal Laporan'] = ($d->tanggal_lap ? date('d/m/Y', strtotime(str_replace('/', ',', $d->tanggal_lap))) : '');
               $result[$key]['Tahun Anggaran'] = $d->tahun_anggaran;
               $result[$key]['Sasaran'] = $d->sasaran;
               $result[$key]['Status'] = $d->status == 'Y' ? 'Lengkap' : 'Belum Lengkap';
               $i = $i +1;
            }
            $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
            $this->printData($result, $name);
          }else{
            echo 'data tidak ada ';
          }
        }else{
          echo 'tidak ada';
        }
    }

    public function deleteIrtamaPtl(Request $request){
      if ($request->ajax()) {
          $id = $request->id;
          $id_lha = "";
          $data_request = execute_api('api/irtamaptl/'.$id,'GET');
          if($data_request['status'] != 'error' && $data_request['code'] == 200 ){
            $id_lha = $data_request['data']['id_lha'];
          }else{
            $id_lha = "";
          }
          if($id){
              $data_request = execute_api('api/irtamaptl/'.$id,'DELETE');

              $this->form_params['delete_id'] = $id;
              $trail['audit_menu'] = 'Inspektorat Utama - Pemantauan Tindak Lanjut';
              $trail['audit_event'] = 'delete';
              $trail['audit_value'] = json_encode($this->form_params);
              $trail['audit_url'] = $request->url();
              $trail['audit_ip_address'] = $request->ip();
              $trail['audit_user_agent'] = $request->userAgent();
              $trail['audit_message'] = $data_request['comment'];
              $trail['created_at'] = date("Y-m-d H:i:s");
              $trail['created_by'] = $request->session()->get('id');

              $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

              if($data_request['status'] != 'error' && $data_request['code'] == 200 ){
                if($id_lha){
                  $query = execute_api('api/auditlha/'.$id_lha,'PUT',['data_ptl'=> 'N']);
                }
              }
              return $data_request;
          }else{
              $data_request = ['code'=>200,'status'=>'error','message'=>'Data Irtama PTL Gagal Dihapus'];
              return $data_request;
          }
      }
    }

    public function ajaxIrtamaRekomendasi(Request $request,$id){
      $tipe = $request->tipe;
      $tipe = ucfirst($tipe);
      $query = execute_api('/api/irtamalharekomendasi/'.$id,'GET');
      $params = [];
      if(($query['status'] != 'error') && ($query['code'] == 200) ){
        $params = $query['data'];
        $params['file_path'] = 'upload/IrtamaPtl/Bukti'.$tipe;
        $this->data['code'] = 200;
        $this->data['status'] = 'success';
        $this->data['data'] = $params;
      }else{
        $this->data['code'] = 200;
        $this->data['status'] = 'error';
      }
      return $this->data;
    }

    public function updateRekomendasi(Request $request){
      // if($request->ajax()){
        $id = $request->id;
        $tipe = $request->tipe;
        $tipe = ucfirst($tipe);
        $forms = $request->data;
        $params = [];
        // $params = $request->except(['_token','id','meta_bukti','tipe']);

        $meta_bukti = $request->meta_bukti;
        $array_file= [];
        $meta_filename = "";
        $error_message = [];
        $error = "";
        $directory = 'IrtamaPtl/Bukti'.$tipe;

        if($meta_bukti){
          foreach($meta_bukti as $m ){
            if($m['filename']){
              $array_file[] = $m['filename'];
            }

            if(isset($m['bukti'])){
              $file = $m['bukti'];
              $filename  = $file->getClientOriginalName();
              try {
                $filename = date('Y-m-d').'_'.'_'.$filename;
                $path = Storage::putFileAs($directory, $file ,$filename);
                $array_file[] = $filename;
              }catch(\Exception $e){
                $error_message[] =$filename;
              }
            }
          }
          if(count($error_message)){
            $error = ' dengan file berikut gagal disimpan : ';
            $error .= implode(',', $error_message);
          }else{
            $error = "";
          }

          if(count($array_file)){
            $meta_filename = json_encode($array_file);
          }else{
            $meta_filename = "";
          }
        }else{
          $error = "";
          $meta_filename = "";
        }
        $params['bukti'] = $meta_filename;
        $params['nilai_tindak_lanjut'] = $request->nilai_tindak_lanjut;
        $params['status'] = $request->status;
        $query = execute_api_json('/api/irtamalharekomendasi/'.$id,'PUT',$params);

        if( ($query->code == 200) && ($query->status != 'error')){
          $this->data['status'] = 'success';
          $this->data['message'] = 'Data Rekomendasi Berhasil DiPerbarui. '.$error;
        }else{
          $this->data['status'] = 'error';
          $this->data['message'] = 'Data Rekomendasi Gagal DiPerbarui';
        }
        return back()->with('status',$this->data);
      // }
    }

    public function deleteirtamaRiktu(Request $request){
      if ($request->ajax()) {
        $id = $request->id;
        if($id){
            $data_request = execute_api('/api/rikturiksus/'.$id,'DELETE');

            $this->form_params['delete_id'] = $id;
            $trail['audit_menu'] = 'Inspektorat Utama - Audit dengan Tujuan Tertentu';
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
            $data_request = ['code'=>200,'status'=>'error','message'=>'Data Bidang Gagal Dihanpus'];
            return $data_request;
        }
      }
    }

    public function deleteirtamaSosialisasi(Request $request){
      if ($request->ajax()) {
        $id = $request->id;
        if($id){
            $data_request = execute_api('/api/irtamasosialisasi/'.$id,'DELETE');
            $this->form_params['delete_id'] = $id;
            $trail['audit_menu'] = 'Inspektorat Utama - Sosialisasi';
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
            $data_request = ['code'=>200,'status'=>'error','message'=>'Data Bidang Gagal Dihanpus'];
            return $data_request;
        }
      }

    }

    private function kelengkapan_lha($id){
      //note : tidak dilakukan pengecekan untuk masing masing bidang
      $status_kelengkapan = true;
      try{
        $query = DB::table('irtama_audit_lha')->where('id_lha',$id)
                  ->select('nomor_lha', 'tanggal_lha', 'nama_satker', 'tgl_mulai', 'tgl_selesai', 'pengendali_mutu', 'pengendali_teknis','ketua_tim', 'meta_tim_anggota','tahun_anggaran','data_ptl');

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
           $kelengkapan = execute_api_json('api/auditlha/'.$id,'PUT',['status'=>'Y']);
        }else{
           $kelengkapan = execute_api_json('api/auditlha/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

    private function kelengkapan_riktu($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('irtama_rikturiksus')->where('id',$id)
                  ->select('no_sprint', 'no_hasil_laporan', 'tgl_hasil_laporan', 'judul_hasil_laporan', 'jenis_pelanggaran',
                    'tempatkejadian_idprovinsi', 'tempatkejadian_idkabkota', 'jumlah_terperiksa', 'jumlah_saksi', 'kodebarangbukti',
                    'kodesumberinformasi', 'kriteria_perka', 'file_hasil_pemeriksaan', 'terbukti');
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
          $kelengkapan = execute_api_json('api/rikturiksus/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/rikturiksus/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }


    private function kelengkapan_sosialisasi($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('irtama_sosialisasi')->where('id',$id)
                  ->select('sprin','lokasi','kode_satker','no_laporan','tgl_laporan','jumlah_peserta','pemapar','dokumen');
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
          echo 'Lengkap';
          $kelengkapan = execute_api_json('api/irtamasosialisasi/'.$id,'PUT',['status'=>'Y']);
        }else{
          echo 'tidak Lengkap';
          $kelengkapan = execute_api_json('api/irtamasosialisasi/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }
    private function kelengkapan_penegakan($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('irtama_penegakan_disiplin')->where('id',$id)
                  ->select('periode_tahun', 'periode_bulan', 'no_laporan', 'tgl_laporan','kode_satker','absensi_tanpa_keterangan', 'absensi_terlambat','absensi_pulang_cepat', 'absensi_telambat_pulang_cepat');
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
          $kelengkapan = execute_api_json('api/penegakandisiplin/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/penegakandisiplin/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

    private function kelengkapan_verifikasi($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('irtama_verifikasi')->where('id',$id)
                  ->select('sprin','lokasi','kode_satker','pejabat_diganti','pejabat_skep_diganti', 'pejabat_tgl_skep_diganti', 'pejabat_baru',
                          'pejabat_skep_baru', 'pejabat_tgl_skep_baru', 'no_laporan','tgl_laporan', 'hal_menjadi_perhatian', 'saran','dokumen');
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
          $kelengkapan = execute_api_json('api/irtamaverifikasi/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/irtamaverifikasi/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }
    private function kelengkapan_sop($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('irtama_sop_kebijakan')->where('id',$id)
                  ->select('sprin','tgl_sprin','nama_sop_kebijakan', 'jenis_sop_kebijakan', 'tgl_sop','dokumen');
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
          $kelengkapan = execute_api_json('api/sopkebijakan/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/sopkebijakan/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

    private function kelengkapan_apel($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('irtama_apel_upacara')->where('id',$id)
                  ->select('tanggal', 'kode_satker', 'jenis_kegiatan','jumlah_hadir', 'jumlah_tidak_hadir', 'keterangan_dinas', 'keterangan_izin', 'keterangan_sakit', 'keterangan_cuti', 'keterangan_pendidikan',
                    'keterangan_hamil' ,'keterangan_terlambat', 'keterangan_tugas_kantor', 'keterangan_tanpa_keterangan');
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
          $kelengkapan = execute_api_json('api/apelupacara/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/apelupacara/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

    private function kelengkapan_reviu_lk($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('irtama_reviu_lk')->where('id',$id)
                  ->select('no_sprint','semester', 'tahun_anggaran', 'uappa','uappa_e1','uappa_w', 'uakpa', 'pengendali_teknis', 'ketua_tim', 'pereviu',
                    'lap_realisasi', 'neraca', 'lap_operasional', 'lap_perubahan','catatan_lap', 'nomor_lap', 'tanggal_lap', 'lap_reviu');
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
          $kelengkapan = execute_api_json('api/reviulk/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/reviulk/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

    private function kelengkapan_irtama_rkakl($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('irtama_reviu_rkakl')->where('id',$id)
                  ->select('no_sprint','tahun_anggaran', 'jmlh_direviu', 'keterangan_direviu', 'jmlh_tdk_direviu', 'keterangan_tdk_direviu',
                    'pengendali_teknis', 'ketua_tim', 'pereviu','indikatif_dukungan', 'indikatif_p4gn', 'sebaran_dukungan','sebaran_p4gn', 'meta_permasalahan', 'nomor_lap',
                    'tanggal_lap','lap_reviu');
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
          $kelengkapan = execute_api_json('api/reviurkakl/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/reviurkakl/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }
    private function kelengkapan_irtama_rkbmn($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('irtama_reviu_rkbmn')->where('id',$id)
                  ->select('no_sprint', 'tahun_anggaran', 'jmlh_direviu', 'keterangan_direviu', 'jmlh_tdk_direviu',
                        'keterangan_tdk_direviu','pengendali_teknis', 'ketua_tim', 'pereviu','kelengkapan', 'ket_kelengkapan', 'kesesuaian', 'ket_kesesuaian',
                        'kantor_jmlh_usulan', 'kantor_jmlh_disetujui','kantor_jmlh_tdk_disetujui','kantor_alasan', 'rumah_jmlh_usulan', 'rumah_jmlh_disetujui',
                        'rumah_jmlh_tdk_disetujui', 'rumah_alasan','tanahkantor_jmlh_usulan', 'tanahkantor_jmlh_disetujui', 'tanahkantor_jmlh_tdk_disetujui',
                        'tanahkantor_alasan', 'tanahrumah_jmlh_usulan', 'tanahrumah_jmlh_disetujui','tanahrumah_jmlh_tdk_disetujui','tanahrumah_alasan',
                        'angkutan_jmlh_usulan','angkutan_jmlh_disetujui','angkutan_jmlh_tdk_disetujui','angkutan_alasan', 'pemeliharaan_jmlh_usulan',
                        'pemeliharaan_jmlh_disetujui', 'pemeliharaan_jmlh_tdk_disetujui', 'pemeliharaan_alasan', 'nomor_lap', 'tanggal_lap',
                        'lap_reviu');
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
          $kelengkapan = execute_api_json('api/reviurkbmn/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/reviurkbmn/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }
    private function kelengkapan_irtama_lkip($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('irtama_reviu_lkip')->where('id',$id)
                  ->select('no_sprint','tahun_anggaran','pengendali_teknis','ketua_tim', 'pereviu', 'sasaran', 'meta_indikator','checklist_reviu', 'nomor_lap', 'tanggal_lap','lap_reviu');
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
          $kelengkapan = execute_api_json('api/reviulkip/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/reviulkip/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }
}

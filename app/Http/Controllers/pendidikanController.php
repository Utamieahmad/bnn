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

class pendidikanController extends Controller
{
    public $data;
    public $selected;
    public function pendidikanPelatihan(Request $request){
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
          }elseif( ($tipe == 'nama_kegiatan') || ($tipe == 'tempat') || ($tipe == 'tujuan_kegiatan')){
            $kondisi .= '&'.$tipe.'='.$request->kata_kunci;
            $this->selected[$tipe] = $request->kata_kunci;
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
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
        $datas = execute_api_json('api/kegiatan?'.$limit.'&'.$offset.$kondisi,'get');

        if(($datas->code == 200) && ($datas->status != 'error')) {
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;
        }else{
            $this->data['data'] = [];
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

        $this->data['title'] = "Data Kegiatan Pendidikan dan Pelatihan Pada Balai Diklat";
        $this->data['delete_route'] = 'delete_pendidikan_pelatihan';
        $this->data['path'] = $request->path();
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;
        $this->data['breadcrumps'] = breadcrumps_balai_diklat($request->route()->getName());
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
        return view('balai_diklat.index_pendidikanPelatihan',$this->data);
    }

     public function editpendidikanPelatihan(Request $request){
        $this->data['title'] = "Edit Data Kegiatan Pendidikan dan Pelatihan Pada Balai Diklat";
        $id= $request->id;
        $datas = execute_api_json('api/kegiatan/'.$id,'get');
        if( ($datas->code == 200) && ($datas->code != 'error')){
            if ($datas->data->anggaran_id) {
                $token = $request->session()->get('token');
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


        $datas = execute_api_json('api/lookup/sumber_anggaran','get');
        if( ($datas->code == 200) && ($datas->code != 'error')){
            $this->data['sumber_anggaran'] = $datas->data;
        }else{
            $this->data['sumber_anggaran'] = [];
        }

        $this->limit = config('app.limit');
        $limit = 'limit='.$this->limit;
        $offset = 'page= 1';
        $this->kelengkapan_balai_diklat($id);
        $peserta = execute_api_json('api/kegiatanpeserta?header_id='.$id.'&'.$limit.'&'.$offset,'get');
        if( ($peserta->code == 200) && ($peserta->code != 'error')){
            $this->data['peserta'] = $peserta->data;
            $total_item = $peserta->paginate->totalpage * $this->limit;
        }else{
            $this->data['peserta'] = [];
            $total_item = 0;
        }

        $datas = execute_api_json('api/lookup/pendidikan','get');
        if( ($peserta->code == 200) && ($peserta->code != 'error')){
            $this->data['pendidikan'] = $datas->data;
        }else{
            $this->data['pendidikan'] = [];
        }

        $this->data['pangkat'] = config('lookup.pangkat');
        $this->data['jenis_diklat'] = config('lookup.jenis_diklat');
        $this->data['file_path'] = config('lookup.balai_diklat_file_path');

        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        $this->data['id_pelaksana'] =  532;
        $this->data['pagination'] = ajax_pagination($current_page,$total_item, config('app.limit'), config('app.page_ellipsis'),"/".$request->route()->getPrefix()."/index_peserta_pelatihan/".$id."/%d");
        $this->data['delete_route'] = "delete_peserta_pelatihan";
        $this->data['start_number']  = $start_number;
        $this->data['path'] = $request->path();
        $this->data['breadcrumps'] = breadcrumps_balai_diklat($request->route()->getName());
        return view('balai_diklat.edit_pendidikanPelatihan',$this->data);
    }

    public function addpendidikanPelatihan(Request $request){
        if($request->isMethod('post')){
            $id = $request->input('id');
            $token = $request->session()->get('token');

            // $this->form_params = $request->except(['_token', 'id']);
            $this->form_params = $request->except(['_token', 'lokasi','tgl_mulai','id_pelaksana','kd_anggaran','asatker_code','akode_anggaran','arefid_anggaran','atahun','atarget_output','asatuan_output','apagu','asasaran']);
            if($request->tgl_mulai){
                $date = explode('/', $request->tgl_mulai);
                $this->form_params['tgl_pelaksanaan'] = $date[2].'-'.$date[1].'-'.$date[0];
            }
            if($request->tgl_selesai){
                $date = explode('/', $request->tgl_selesai);
                $this->form_params['tgl_selesai'] = $date[2].'-'.$date[1].'-'.$date[0];
            }
            if($request->lokasi){
                $this->form_params['tempat_idkabkota'] = $request->lokasi;
            }
            $token = $request->session()->get('token');
            $anggaran = '';
            $client = new Client();
            if (strtolower($request->input('kodeanggaran'))=="dipa") {
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
            $this->form_params['anggaran_id'] = $anggaran;
            $this->form_params['status'] = 'N';

            $fileMessage = "";
            if ($request->file('file_laporan')){
                $fileName = date('Y-m-d').'_'.$request->file('file_laporan')->getClientOriginalName();
                try{
                    $storeFile = $request->file('file_laporan')->storeAs('BalaiDiklat', $fileName);
                    $fileMessage = "";
                    $this->form_params['file_laporan'] = $fileName;
                }catch(\Exception $e){
                    $fileMessage = "Dengan File Gagal Diunggah.".$e->getMessage();
                }
            }else{
                $fileMessage = "";
            }


            $data_request = execute_api_json('api/kegiatan/','POST',$this->form_params);

            $trail['audit_menu'] = 'Balai Diklat - Pendidikan dan Pelatihan';
            $trail['audit_event'] = 'post';
            $trail['audit_value'] = json_encode($this->form_params);
            $trail['audit_url'] = $request->url();
            $trail['audit_ip_address'] = $request->ip();
            $trail['audit_user_agent'] = $request->userAgent();
            $trail['audit_message'] = $data_request->comment;
            $trail['created_at'] = date("Y-m-d H:i:s");
            $trail['created_by'] = $request->session()->get('id');

            $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

            if(($data_request->code == 200)&& ($data_request->status != "error") ){
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Data Kegiatan Pendidikan dan Pelatihan Berhasil Ditambahkan.'.$fileMessage;
                return redirect(route('edit_pendidikan_pelatihan',$data_request->data->eventID))->with('status', $this->messages);
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Data Kegiatan Pendidikan dan Pelatihan Gagal Ditambahkan';
                return redirect(route('pendidikan_pelatihan'))->with('status', $this->messages);
            }
        }else{
            //hardcode
            $this->data['id_pelaksana'] =  532;
            $this->data['title'] = "Tambah Data Kegiatan Pendidikan dan Pelatihan Pada Balai Diklat";
            $datas = execute_api_json('api/lookup/sumber_anggaran','get');
            if($datas->code == 200){
                $this->data['sumber_anggaran'] = $datas->data;
            }else{
                $this->data['sumber_anggaran'] = [];
            }

            $datas = execute_api_json('api/kegiatanpeserta','get');
            if($datas->code == 200){
                $this->data['peserta'] = $datas->data;
            }else{
                $this->data['peserta'] = [];
            }

            $datas = execute_api_json('api/lookup/pendidikan','get');
            if($datas->code == 200){
                $this->data['pendidikan'] = $datas->data;
            }else{
                $this->data['pendidikan'] = [];
            }

            $this->data['pangkat'] = config('lookup.pangkat');
            $this->data['jenis_diklat'] = config('lookup.jenis_diklat');


            $this->data['path'] = $request->path();
            $this->data['breadcrumps'] = breadcrumps_balai_diklat($request->route()->getName());
            return view('balai_diklat.add_pendidikanPelatihan',$this->data);
        }
    }

    public function deletependidikanPelatihan(Request $request){
        if ($request->ajax()) {
            $id = $request->id;
            if($id){
                $data_request = execute_api('api/kegiatan/'.$id,'DELETE');
                $this->form_params['delete_id'] = $id;
                $trail['audit_menu'] = 'Balai Diklat - Pendidikan dan Pelatihan';
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
                $data_request = ['code'=>200,'status'=>'error','message'=>'Data Survey Gagal Dihanpus'];
                return $data_request;
            }
        }
    }
    public function updatependidikanPelatihan(Request $request){
        $id = $request->input('id');
        $token = $request->session()->get('token');

        $this->form_params = $request->except(['_token','kode_anggaran', 'id','lokasi','tgl_mulai','id_pelaksana','kd_anggaran','asatker_code','akode_anggaran','arefid_anggaran','atahun','atarget_output','asatuan_output','apagu','asasaran']);
        if($request->tgl_mulai){
            $date = explode('/', $request->tgl_mulai);
            $this->form_params['tgl_pelaksanaan'] = $date[2].'-'.$date[1].'-'.$date[0];
        }
        if($request->tgl_selesai){
            $date = explode('/', $request->tgl_selesai);
            $this->form_params['tgl_selesai'] = $date[2].'-'.$date[1].'-'.$date[0];
        }

        if($request->lokasi){
            $this->form_params['tempat_idkabkota'] = $request->lokasi;
        }
        if ($request->file('file_laporan')){
            $fileName = date('Y-m-d').'_'.$request->file('file_laporan')->getClientOriginalName();
            try{
                $storeFile = $request->file('file_laporan')->storeAs('BalaiDiklat', $fileName);
                $fileMessage = "";
                $this->form_params['file_laporan'] = $fileName;
            }catch(\Exception $e){
                $fileMessage = "Dengan File Gagal Diunggah.".$e->getMessage();
            }
        }else{
            $fileMessage = "";
        }
        $token = $request->session()->get('token');
        $anggaran = '';
        $client = new Client();
        if (strtolower($request->input('kodeanggaran'))=="dipa") {
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
        $this->form_params['anggaran_id'] = $anggaran;

        $data_request = execute_api_json('api/kegiatan/'.$id,'PUT',$this->form_params);

        $trail['audit_menu'] = 'Balai Diklat - Pendidikan dan Pelatihan';
        $trail['audit_event'] = 'put';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $data_request->comment;
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

        $fileMessage = "";
        if(($data_request->code == 200)&& ($data_request->status != "error") ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Pendidikan dan Pelatihan Berhasil Diperbarui. '.$fileMessage;
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Pendidikan dan Pelatihan Gagal Diperbarui';
        }

        return back()->with('status', $this->messages);
    }

    /*
    -------------------------
    | FUNCTION PESERTA
    |
    -------------------------
    */
    public function addPesertaPelatihan(Request $request){
        $token = $request->session()->get('token');
        $this->form_params = $request->except(['_token','parent_id']);
        $parent_id = $request->parent_id;

        $this->form_params['header_id'] = $request['parent_id'];
        $data_request = execute_api_json('api/kegiatanpeserta/','POST',$this->form_params);

        $trail['audit_menu'] = 'Balai Diklat - Pendidikan dan Pelatihan Peserta';
        $trail['audit_event'] = 'post';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $data_request->comment;
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


        if(($data_request->code == 200)&& ($data_request->status != "error") ){
            $kelengkapan = execute_api_json('api/kegiatan/'.$parent_id,'PUT',['status'=>'Y']);
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Peserta Pelatihan Berhasil Ditambahkan';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Peserta Pelatihan Gagal Ditambahkan';
        }
        return back()->with('status',$this->messages);
    }
    public function updatePesertaPelatihan(Request $request){
        if($request->ajax()){
            $token = $request->session()->get('token');
            $id = $request->id;
            $this->form_params = $request->except(['_token','id','index']);
            $data_request = execute_api_json('api/kegiatanpeserta/'.$id,'PUT',$this->form_params);

            $trail['audit_menu'] = 'Balai Diklat - Pendidikan dan Pelatihan Peserta';
            $trail['audit_event'] = 'put';
            $trail['audit_value'] = json_encode($this->form_params);
            $trail['audit_url'] = $request->url();
            $trail['audit_ip_address'] = $request->ip();
            $trail['audit_user_agent'] = $request->userAgent();
            $trail['audit_message'] = $data_request->comment;
            $trail['created_at'] = date("Y-m-d H:i:s");
            $trail['created_by'] = $request->session()->get('id');

            $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

            $pangkat= config('lookup.pangkat');
            $data_return  = "";
            if(($data_request->code == 200)&& ($data_request->status != "error") ){
                $data_return .= "<td></td>";
                $data_return .= "<td>".($this->form_params['nama'] ? $this->form_params['nama'] : '')."</td>";
                $data_return .= "<td>".$this->form_params['nip']."</td>";
                $data_return .= "<td>".$this->form_params['satker']."</td>";
                $data_return .= "<td>".(isset($pangkat[$this->form_params['pangkat_golongan']]) ? $pangkat[$this->form_params['pangkat_golongan']] : $this->form_params['pangkat_golongan'])."</td>";
                $data_return .= '<td>
                        <button type="button" class="btn btn-primary button-edit" data-target="'.$id.'" onClick="open_modalEditPeserta(event,this,\'/balai_diklat/pendidikan/edit_peserta_pelatihan/\',\'modal_edit_peserta\')"><i class="fa fa-pencil"></i></button>
                        <button type="button" class="btn btn-primary button-action" data-target="'.$id.'" onClick="delete_row_peserta(event,this)"><i class="fa fa-trash"></i></button></td>';
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Data Peserta Pelatihan Berhasil Diperbarui';
                $this->messages['data_return'] = $data_return;
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Data Peserta Pelatihan Gagal Diperbarui';
                $this->messages['data_return'] = null;
            }
            return response()->json($this->messages);
        }
    }
    public function deletePesertaPelatihan(Request $request){
      if ($request->ajax()) {
          $id = $request->id;
          if($id){
              $data_request = execute_api('api/kegiatanpeserta/'.$id,'DELETE');
              $this->form_params['delete_id'] = $id;
              $trail['audit_menu'] = 'Balai Diklat - Pendidikan dan Pelatihan Peserta';
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
              $data_request = ['code'=>200,'status'=>'error','message'=>'Data Peserta Pelatihan Gagal Dihanpus'];
              return $data_request;
          }
      }
    }
    public function editPesertaPelatihan(Request $request){
        if($request->ajax()){
            $token = $request->session()->get('token');
            $id = $request->id;
            $this->form_params = $request->except(['_token','id']);
            $data_request = execute_api_json('api/kegiatanpeserta/'.$id,'GET',$this->form_params);

            if(($data_request->code == 200)&& ($data_request->status != "error") ){
                $this->messages['status'] = 'success';
                $this->messages['message'] = '';
                $this->messages['data'] = $data_request->data;
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Data Peserta Pelatihan Gagal Ditambahkan';
                $this->messages['data'] = [];

            }
            return response()->json($this->messages);
        }
    }
    public function printPage(Request $request){
        $array_segments = [
            'pendidikan_pelatihan'=>'kegiatan'
        ];
        $array_titles=[
            'pendidikan_pelatihan'=>'Data Kegiatan Pendidikan dan Pelatihan Pada Balai Diklat'
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


        if($segment == 'pendidikan_pelatihan'){
            if($data_request->code == 200 && $data_request->status != 'error'){
                $data = $data_request->data;
                $i = $start_number;
                $kode = "";

                // $pangkat= config('lookup.pangkat');
                foreach($data as $key=>$d){
                    $array_peserta = [];
                    $data_peserta = [];
                    // $peserta = execute_api('api/singlePesertaPelatihan/32','get');
                    // if(isset($peserta['data'])){
                    //   $p =$peserta['data'];
                    //   if(count($p)>=1){
                    //     for($j = 0 ; $j < count($p); $j++) {
                    //       $data_peserta['No'] = $j;
                    //       $data_peserta['Nama'] = $p[$j]['nama'];
                    //       $data_peserta['NIP'] = $p[$j]['nip'];
                    //       $data_peserta['Satker'] = $p[$j]['satker'];
                    //       $data_peserta['Pangkat/Golongan'] = (isset($pangkat[$p[$j]['pangkat_golongan']]) ? $pangkat[$p[$j]['pangkat_golongan']] :$p[$j]['pangkat_golongan']);
                    //       $array_peserta[] = $data_peserta;
                    //     }
                    //   }else{
                    //     $array_peserta = [];
                    //   }

                    // }else{
                    //   $array_peserta = [];
                    // }
                    $result[$key]['No'] = $i;
                    $result[$key]['Nama Kegiatan'] = $d->nama_kegiatan;
                    $result[$key]['Periode Pelaksanaan'] = ( $d->tgl_pelaksanaan ? date('d/m/Y',strtotime($d->tgl_pelaksanaan)) :'' ).
                                                    ( ($d->tgl_pelaksanaan && $d->tgl_selesai) ? '- ' :'').
                                                    ( $d->tgl_selesai ? date('d/m/Y',strtotime($d->tgl_selesai)) :'' );
                    $result[$key]['Tempat'] = $d->tempat;
                    $result[$key]['Jumlah Peserta'] = $d->total_peserta;
                    $result[$key]['Tujuan Kegiatan'] = $d->tujuan_kegiatan;
                    $result[$key]['Status'] =  ( ($d->status == 'Y') ? 'Lengkap' : 'Belum Lengkap') ;
                    // $result[$key]['Peserta'] = $array_peserta;
                    $i = $i+1;
                }
                // echo '<pre>';
                // print_r($result);
                // exit();
                $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
                $this->printData($result, $name);
                // echo '<pre>';
                    // print_r($result);
            }else{
                echo 'tidak ada';
            }
        }else{
            return false;
        }
    }

    public function indexPesertaPelatihan(Request $request){
        $this->limit = config('app.limit');
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        $parent_id = $request->parent_id;
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
        $parent_id = 'header_id='.$parent_id;

        $datas = execute_api_json('api/kegiatanpeserta?'.$parent_id.'&'.$limit.'&'.$offset,'get');

        // $datas = execute_api_json('api/single_pelatihan_rehabilitasi/'.$parent_id.'?'.$limit.'&'.$offset,'get');
        if($datas->code == 200){
            $this->data['status'] = 'success';
            if(count($datas->data)>=1){
                $html = "";
                $j = $start_number;
                $jenis_kelamin = config('lookup.jenis_kelamin');
                foreach($datas->data as $d){
                    $html .= "<tr>";
                    $html .= "<td>".$j."</td>";
                    $html .= "<td>".$d->nama."</td>";
                    $html .= "<td>".$d->nip."</td>";
                    $html .= "<td>".$d->satker."</td>";
                    $html .= "<td>".(isset($pangkat[$d->pangkat_golongan]) ? $pangkat[$d->pangkat_golongan] : $d->pangkat_golongan)."</td>";
                    $html .= '<td class="actionTable">
                                <button type="button" class="btn btn-primary button-edit" data-target="'.$d->id.'" onClick="open_modalEditPeserta(event,this,\'/balai_diklat/pendidikan/edit_peserta_pelatihan/\',\'modal_edit_pelatihan\')"><i class="fa fa-pencil"></i></button>
                                <button type="button" class="btn btn-primary button-delete" data-target="'.$d->id.'" onClick="delete_row_peserta(event,this)"><i class="fa fa-trash"></i></button>
                            </td>';
                    $html .= "</tr>";
                    $j = $j+1;
                }
                $this->data['data'] = $html;
            }else{
                $this->data['data'] = null;
            }
        }else{
            $this->data['status'] = 'error';
            $this->data['data'] = null;
        }
        return response()->json($this->data);
    }

    private function kelengkapan_balai_diklat($id){
        $status_kelengkapan = true;
        try{
            $query = DB::table('badiklat_kegiatan')->where('id',$id)
            ->select('nama_kegiatan','tgl_selesai','jenis_diklat','tujuan_kegiatan','tempat','total_hari_diklat','total_jam_pelajaran','total_narasumber_pengajar','total_peserta','syarat_mengikuti_diklat','kodeanggaran','tempat_idkabkota','anggaran_id');

            $query_peserta = DB::table('badiklat_kegiatan_peserta')->where('header_id',$id)->count();
            $columns = ['anggaran_id','kodeanggaran'];
            if($query_peserta > 0){
              if($query->count() > 0 ){
                $result = $query->first();
                if(strtolower($result->kodeanggaran) == 'dipa'){
                  if(!$result->anggaran_id){
                    $status_kelengkapan=false;
                  }else if($result->anggaran_id){
                    $status_kelengkapan=true;
                  }
                }else {
                  foreach($result as $key=>$val){
                    if(!in_array($key, $columns)){
                      if(!$val || $val == 'null' ){
                        $status_kelengkapan=false;
                        break;
                      }else{
                        continue;
                      }
                    }else{
                      continue;
                    }
                  }
                }

              }
            }else{
              $status_kelengkapan =false;
            }
            if($status_kelengkapan== true){
                $kelengkapan = execute_api_json('api/kegiatan/'.$id,'PUT',['status'=>'Y']);
            }else{
                $kelengkapan = execute_api_json('api/kegiatan/'.$id,'PUT',['status'=>'N']);
            }
        }catch(\Exception $e){
            $status_kelengkapan=false;
        }
    }
}

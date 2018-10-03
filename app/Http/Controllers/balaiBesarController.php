<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Auth;
use Carbon\Carbon;
use Storage;

class balaiBesarController extends Controller{
	public $data = array();
	public $message = array();
    public $form_params = array();

    public function magang(Request $request){
        $kondisi = '';
        if($request->limit) {
          $this->limit = $request->limit;
        } else {
          $this->limit = config('app.limit');
        }
        $this->data['title'] = 'Balai Besar';
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
          }elseif($tipe == 'nama_kegiatan'){
            $kondisi .= '&nama_kegiatan='.$request->kata_kunci;
            $this->selected['nama_kegiatan'] = $request->kata_kunci;
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
              }elseif($value == 'nama_kegiatan'){
                $kondisi .= '&nama_kegiatan='.$request->kata_kunci;
                $this->selected['nama_kegiatan'] = $request->kata_kunci;
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
        $query = execute_api_json('api/balaiBesar?'.$limit.'&'.$offset.$kondisi,'get');
        if($query->code == 200 && $query->status != 'error'){
            $this->data['data'] = $query->data;
            $total_item = $query->paginate->totalpage * $this->limit;
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
        $this->data['path'] = $request->path();
        $this->data['delete_route'] = 'delete_magang';
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;

        $query = execute_api_json('/api/lookup/balai_besar_kegiatan','GET');
        $arr_kegiatan = [];
        if($query->code == 200 && $query->status != 'error'){
            if(count($query->data)>0){
                foreach($query->data as $q => $d){
                    $arr_kegiatan[$q] = $d;
                }
            }else{
                $arr_kegiatan = [];
            }
        }else{
            $arr_kegiatan = [];
        }
        $this->data['jenis_kegiatan']  =$arr_kegiatan;

        $query = execute_api('/api/lookup/balai_besar_instansi','GET');
        if($query['code'] == 200 && $query['status'] != 'error'){
            $this->data['instansi']  = $query['data'];
        }else{
            $this->data['instansi']  = [];
        }
        $this->data['breadcrumps'] = breadcrump_balai_besar($request->route()->getName());
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
    	return view('balai_besar.magang.index_magang',$this->data);
    }
    public function editMagang(Request $request){
        $id = $request->id;
        $datas = [];
        $url_simpeg = config('app.url_simpeg');
        $datas = execute_api_json($url_simpeg,'GET');
        if( ($datas->code == 200) && ($datas->status != 'error') ){
            $this->data['satker'] = $datas->data;
        }else{
            $this->data['satker'] = [];
        }

        $datas = execute_api_json('/api/balaiBesar/'.$id,'GET');
        if( ($datas->code == 200) && ($datas->status != 'error') ){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }
        $this->data['file_path'] = config('app.balai_besar_file_path');
        $query = execute_api_json('/api/lookup/balai_besar_kegiatan','GET');
        if($query->code == 200 && $query->status != 'error'){
            $this->data['jenis_kegiatan']  = $query->data;
        }else{
            $this->data['jenis_kegiatan']  = [];
        }

        $query = execute_api_json('/api/lookup/balai_besar_instansi','GET');
        if($query->code == 200 && $query->status != 'error'){
            $this->data['instansi']  = $query->data;
        }else{
            $this->data['instansi']  = [];
        }
    	$this->data['breadcrumps'] = breadcrump_balai_besar($request->route()->getName());
    	return view('balai_besar.magang.edit_magang',$this->data);
    }
    public function addMagang(Request $request){
    	if($request->isMethod('post')){
            $kode_instansi = $request->kode_instansi;
            $except  = [];
            $except[] = '_token';
            $data_instansi = [];
            $total = 0;
            if($kode_instansi == 'bnn'){
                $instansi = $request->bnn_detail_instansi;
                if(count($instansi) > 0){
                    if($instansi){
                        for($i = 0; $i < count($instansi); $i++){
                            $detail = $instansi[$i]['nama_peserta'];
                            if($detail){
                                $arr_detail = explode('-',$detail);
                                $data_instansi[] = ['nama'=>(isset($arr_detail[1]) ? trim($arr_detail[1]) : '' ) ,'id'=>(isset($arr_detail[0]) ? trim($arr_detail[0]) : '' ), 'jumlah_peserta' => $instansi[$i]['jumlah_peserta']];
                                $total =  $total + $instansi[$i]['jumlah_peserta'];
                            }
                        }
                    }else{
                        $data_instansi = [];
                    }
                }else{
                    $data_instansi = [];
                }
            }else if($kode_instansi != 'bnn'){
                $instansi = $request->detail_instansi;
                if(count($instansi) > 0){
                    if($instansi){
                        for($i = 0; $i < count($instansi); $i++){
                            $d = $instansi[$i];
                            if($d['nama_instansi'] || $d['jumlah_peserta'] ){
                                $data_instansi[] = ['nama_instansi'=>$d['nama_instansi'],'jumlah_peserta'=>$d['jumlah_peserta']];
                                $total =  $total + $d['jumlah_peserta'];
                            }
                        }
                    }
                }else{
                    $data_instansi = [];
                }
            }
            $except = ['_token','bnn_detail_instansi','detail_instansi','materi'];
            $this->form_params = $request->except($except);
            $data_materi = [];
            $materi = $request->materi;
            if(count($materi)>0){
                for($j=0; $j < count($materi); $j++){
                    $m = $materi[$j];
                    if($m['narasumber'] || $m['judul_materi']){
                        $data_materi[] = ['narasumber'=>$m['narasumber'],'judul_materi'=>$m['judul_materi']];
                    }else{
                        continue;
                    }
                }
                $this->form_params['meta_materi'] = json_encode($data_materi);
            }else{
                $data_materi = [];
            }

            if($request->tanggal_mulai){
                $date = explode('/',$request->tanggal_mulai);
                $this->form_params['tanggal_mulai'] = $date[2].'-'.$date[1].'-'.$date[0];
            }

            if($request->tanggal_selesai){
                $date = explode('/',$request->tanggal_selesai);
                $this->form_params['tanggal_selesai'] = $date[2].'-'.$date[1].'-'.$date[0];
            }

            if($kode_instansi == 'bnn'){
                $this->form_params['meta_bnn_instansi'] = json_encode($data_instansi);
                $this->form_params['bnn_jumlah_peserta'] = $total;
            }else{
                $this->form_params['meta_instansi'] = json_encode($data_instansi);
                $this->form_params['bnn_jumlah_peserta'] = $total;
            }
            $this->form_params['created_by'] = Auth::user()->user_id;
            $file_message = "";
            if($request->file('file_laporan')){
                $fileName = $request->file('file_laporan')->getClientOriginalName();
                $fileName = date('Y-m-d').'_'.$fileName;
                $extension = $request->file('file_laporan')->getClientOriginalExtension(); // getting image extension
                $directory = 'BalaiBesar';
                try {

                    $path = Storage::putFileAs($directory, $request->file('file_laporan'),$fileName);
                    if($path){
                        $this->form_params['file_laporan'] = $fileName;
                    }else{
                        $file_message = "Dengan File gagal diupload.";
                    }
                }catch(\Exception $e){
                    $file_message = "Dengan File gagal diupload.".json_encodee($e->getResponse());
                }
            }
            $query = execute_api_json('/api/balaiBesar','POST',$this->form_params);

						$trail['audit_menu'] = 'Balai Besar - Magang';
						$trail['audit_event'] = 'post';
						$trail['audit_value'] = json_encode($this->form_params);
						$trail['audit_url'] = $request->url();
						$trail['audit_ip_address'] = $request->ip();
						$trail['audit_user_agent'] = $request->userAgent();
						$trail['audit_message'] = $query->comment;
						$trail['created_at'] = date("Y-m-d H:i:s");
						$trail['created_by'] = $request->session()->get('id');

						$qtrail = $this->inputtrail($request->session()->get('token'),$trail);

            if($query->code == 200 && $query->status != 'error'){
                $id = $query->data->eventID;
                $this->kelengkapan_balai_besar($id);
                $this->data['status'] ='success';
                $this->data['message'] ='Data Balai Besar Berhasil Ditambahkan. '.$file_message;
            }else{
                $this->data['status'] ='error';
                $this->data['message'] ='Data Balai Besar Gagal Ditambahkan';
            }
            return redirect(route('data_magang'))->with('status',$this->data);
    	}else{
            $datas = [];
    		$url_simpeg = config('app.url_simpeg');
            $datas = execute_api_json($url_simpeg,'GET');
            if( ($datas->code == 200) && ($datas->status != 'error') ){
                $this->data['satker'] = $datas->data;
            }else{
                $this->data['satker'] = [];
            }

			$this->data['breadcrumps'] = breadcrump_balai_besar($request->route()->getName());

            $query = execute_api_json('/api/lookup/balai_besar_kegiatan','GET');
            if($query->code == 200 && $query->status != 'error'){
                $this->data['jenis_kegiatan']  = $query->data;
            }else{
                $this->data['jenis_kegiatan']  = [];
            }

            $query = execute_api_json('/api/lookup/balai_besar_instansi','GET');
            if($query->code == 200 && $query->status != 'error'){
                $this->data['instansi']  = $query->data;
            }else{
                $this->data['instansi']  = [];
            }


    		return view('balai_besar.magang.add_magang',$this->data);
    	}
    }
    public function updateMagang(Request $request){
        $kode_instansi = $request->kode_instansi;
        $id = $request->id;
        $except  = [];
        $except[] = '_token';
        $data_instansi = [];
        $total = 0;
        if($kode_instansi == 'bnn'){
            $instansi = $request->bnn_detail_instansi;
            if(count($instansi) > 0){
                if($instansi){
                    for($i = 0; $i < count($instansi); $i++){
                        $detail = $instansi[$i]['nama_peserta'];
                        if($detail){
                            $arr_detail = explode('-',$detail);
                            $data_instansi[] = ['nama'=>(isset($arr_detail[1]) ? trim($arr_detail[1]) : '' ) ,'id'=>(isset($arr_detail[0]) ? trim($arr_detail[0]) : '' ), 'jumlah_peserta' => $instansi[$i]['jumlah_peserta']];
                            $total =  $total + $instansi[$i]['jumlah_peserta'];
                        }
                    }
                }else{
                    $data_instansi = [];
                }
            }else{
                $data_instansi = [];
            }
        }else if($kode_instansi != 'bnn'){
            $instansi = $request->detail_instansi;
            if(count($instansi) > 0){
                if($instansi){
                    for($i = 0; $i < count($instansi); $i++){
                        $d = $instansi[$i];
                        if($d['nama_instansi'] || $d['jumlah_peserta'] ){
                            $data_instansi[] = ['nama_instansi'=>$d['nama_instansi'],'jumlah_peserta'=>$d['jumlah_peserta']];
                            $total =  $total + $d['jumlah_peserta'];
                        }
                    }
                }
            }else{
                $data_instansi = [];
            }
        }
        $except = ['_token','bnn_detail_instansi','detail_instansi','materi'];
        $this->form_params = $request->except($except);
        $data_materi = [];
        $materi = $request->materi;
        if(count($materi)>0){
            for($j=0; $j < count($materi); $j++){
                $m = $materi[$j];
                if($m['narasumber'] || $m['judul_materi']){
                    $data_materi[] = ['narasumber'=>$m['narasumber'],'judul_materi'=>$m['judul_materi']];
                }else{
                    continue;
                }
            }
            $this->form_params['meta_materi'] = json_encode($data_materi);
        }else{
            $data_materi = [];
        }

        if($request->tanggal_mulai){
            $date = explode('/',$request->tanggal_mulai);
            $this->form_params['tanggal_mulai'] = $date[2].'-'.$date[1].'-'.$date[0];
        }

        if($request->tanggal_selesai){
            $date = explode('/',$request->tanggal_selesai);
            $this->form_params['tanggal_selesai'] = $date[2].'-'.$date[1].'-'.$date[0];
        }

        if($kode_instansi == 'bnn'){
            $this->form_params['meta_bnn_instansi'] = json_encode($data_instansi);
            $this->form_params['bnn_jumlah_peserta'] = $total;
        }else{
            $this->form_params['meta_instansi'] = json_encode($data_instansi);
            $this->form_params['bnn_jumlah_peserta'] = $total;
        }

        $this->form_params['created_by'] = Auth::user()->user_id;
        $file_message = "";
        if($request->file('file_laporan')){
            $fileName = $request->file('file_laporan')->getClientOriginalName();
            $fileName = date('Y-m-d').'_'.$fileName;
            $extension = $request->file('file_laporan')->getClientOriginalExtension(); // getting image extension
            $directory = 'BalaiBesar';
            try {

                $path = Storage::putFileAs($directory, $request->file('file_laporan'),$fileName);
                if($path){
                    $this->form_params['file_laporan'] = $fileName;
                }else{
                    $file_message = "Dengan File gagal diupload.";
                }
            }catch(\Exception $e){
                $file_message = "Dengan File gagal diupload.".json_encodee($e->getResponse());
            }
        }

        $query = execute_api_json('/api/balaiBesar/'.$id,'PUT',$this->form_params);

				$trail['audit_menu'] = 'Balai Besar - Magang';
				$trail['audit_event'] = 'put';
				$trail['audit_value'] = json_encode($this->form_params);
				$trail['audit_url'] = $request->url();
				$trail['audit_ip_address'] = $request->ip();
				$trail['audit_user_agent'] = $request->userAgent();
				$trail['audit_message'] = $query->comment;
				$trail['created_at'] = date("Y-m-d H:i:s");
				$trail['created_by'] = $request->session()->get('id');

				$qtrail = $this->inputtrail($request->session()->get('token'),$trail);

        if($query->code == 200 && $query->status != 'error'){
            $this->kelengkapan_balai_besar($id);
            $this->data['status'] ='success';
            $this->data['message'] ='Data Balai Besar Berhasil Diperbarui. '.$file_message;
        }else{
            $this->data['status'] ='error';
            $this->data['message'] ='Data Balai Besar Gagal Diperbarui';
        }
        return back()->with('status',$this->data);
    }
    public function deleteMagang(Request $request){
        $id = $request->input('id');
        if ($request->ajax()) {
            if($id){
                $id = $request->id;
                $data_request = execute_api('api/balaiBesar/'.$id,'DELETE');
								$this->form_params['delete_id'] = $id;
								$trail['audit_menu'] = 'Balai Besar - Magang';
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
                $this->data['status'] ='error';
                $this->data['code'] =200;
                $this->data['message'] ='Data Balai Besar Gagal Dihapus';
                return $this->data;
            }
        }
    }

    public function printPage(Request $request){
        $array_segments = [
            'data_magang'=>'balaiBesar'
        ];
        $array_titles=[
            'data_magang'=>'Data Balai Besar'
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
        if($data_request->code == 200 && $data_request->status != 'error'){
            $data = $data_request->data;
            $i = $start_number;
            $query = execute_api_json('/api/lookup/balai_besar_kegiatan','GET');
            $arr_kegiatan = [];
            if($query->code == 200 && $query->status != 'error'){
                if(count($query->data)>0){
                    foreach($query->data as $q => $d){
                        $arr_kegiatan[$q] = $d;
                    }
                }else{
                    $arr_kegiatan = [];
                }
            }else{
                $arr_kegiatan = [];
            }
            $this->data['jenis_kegiatan']  =$arr_kegiatan;

            $query = execute_api('/api/lookup/balai_besar_instansi','GET');
            if($query['code'] == 200 && $query['status'] != 'error'){
                $instansi  = $query['data'];
            }else{
                $instansi  = [];
            }
            foreach($data as $key=>$d){
                $result[$key]['No'] = $i;
                $result[$key]['Jenis Kegiatan'] = (isset( $arr_kegiatan[$d->jenis_kegiatan] ) ?  $arr_kegiatan[$d->jenis_kegiatan]  : $d->jenis_kegiatan) ;
                $result[$key]['Nama Kegiatan'] = ( $d->nama_kegiatan );
                $result[$key]['Instansi'] = ( isset($instansi[$d->kode_instansi]) ? $instansi[$d->kode_instansi] :$d->kode_instansi);
                $result[$key]['Periode'] = ($d->tanggal_mulai ? date('d/m/Y',strtotime($d->tanggal_mulai)) : '') .'-'. ($d->tanggal_selesai ? date('d/m/Y',strtotime($d->tanggal_selesai)) : '');
                $result[$key]['Jumlah Peserta'] = number_format($d->bnn_jumlah_peserta);
                $result[$key]['Status'] = ( ($d->status == 'Y') ? 'Lengkap' : 'Belum Lengkap');

                $i = $i+1;
            }
            $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
            $this->printData($result, $name);
        }else{
          echo 'tidak ada';
        }
    }

    private function kelengkapan_balai_besar($id){
        $status_kelengkapan = true;
        try{
            $query = DB::table('balai_besar')->where('id',$id)
            ->select('jenis_kegiatan','nama_kegiatan','tanggal_mulai','tanggal_selesai','nomor_agenda','nomor_surat','kode_instansi','meta_materi','meta_instansi','meta_bnn_instansi','file_laporan');
            if($query->count() > 0 ){
                $result = $query->first();

                if($result->kode_instansi == 'bnn'){
                    unset($result->meta_instansi);
                }else if($result->kode_instansi != 'bnn'){
                    unset($result->meta_bnn_instansi);
                }
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
                $kelengkapan = execute_api_json('api/balaiBesar/'.$id,'PUT',['status'=>'Y']);
            }else{
                $kelengkapan = execute_api_json('api/balaiBesar/'.$id,'PUT',['status'=>'N']);
            }
        }catch(\Exception $e){
            $status_kelengkapan=false;
        }
    }
}

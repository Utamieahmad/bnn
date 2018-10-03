<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
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
use Storage;

class RehabilitasiController extends Controller
{
	private $data = array();
	private $message = array();
    private $param = array();
	private $result_data779 = array();
	private $route_template = 'rehabilitasi.plrip';
    private $limit ;
    private $selected ;
    /*
    | DIREKTORAT PLRKM |
    | Penilaian Lembaga            |
    */

    public function penilaianLembagaPlrkm(Request $request){
        $this->limit = config('app.limit');
        $this->data['title'] = "Penilaian Lembaga PLRKM";
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
        $tipe = 'tipe='.'plrkm';
        $datas = execute_api_json('api/penilaianlembaga?'.$tipe.'&'.$limit.'&'.$offset,'get');
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }
        $this->data['delete_route'] = 'delete_penilaian_lembaga_plrkm';
        $this->data['path'] = $request->path();
        $this->data['route_name'] = $request->route()->getName();
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;
        $total_item = $datas->paginate->totalpage * $this->limit;
        $this->data['pagination'] = pagination($current_page,$total_item, $this->limit, config('app.page_ellipsis'), "/".$request->route()->getPrefix()."/".$request->route()->getName()."/%d");
        $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
        return view('rehabilitasi.plrkm.penilaian_lembaga.index_penilaianLembaga',$this->data);
    }
    public function editPenilaianLembagaPlrkm(Request $request){
        $id = $request->id;
        $this->data['penilaian'] = config('lookup.penilaian_lembaga');
        $datas = execute_api_json('api/penilaianlembaga/'.$id,'get');

        if($datas->code == 200){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }
        $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
        return view('rehabilitasi.plrkm.penilaian_lembaga.edit_penilaianLembaga',$this->data);
    }
    public function addPenilaianLembagaPlrkm(Request $request){
        if($request->isMethod('post')){
            $this->form_params   = $request->except(['_token']);
            $this->form_params['tipe'] = 'plrkm';
            $data_request = execute_api_json('api/penilaianlembaga/','POST',$this->form_params);

            if(($data_request->code == 200)&& ($data_request->status != "error") ){
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Data Penilaian Lembaga PLRKM Berhasil Diperbarui';
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Data Penilaian Lembaga PLRKM Gagal Diperbarui';
            }
            return redirect(route('penilaian_lembaga_pascarehabilitasi'))->with('status', $this->messages);
        }else{
            $this->data['penilaian'] = config('lookup.penilaian_lembaga');
            $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
            return view('rehabilitasi.plrkm.penilaian_lembaga.add_penilaianLembaga',$this->data);
        }
    }
    public function updatePenilaianLembagaPlrkm(Request $request){
        $id = $request->id;
        $this->form_params = $request->except(['_token','id']);
        $data_request = execute_api_json('api/penilaianlembaga/'.$id,'PUT',$this->form_params);

        if(($data_request->code == 200)&& ($data_request->status != "error") ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Info Penilaian Lembaga PLRKM Berhasil Diperbarui';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Info Penilaian Lembaga PLRKM Gagal Diperbarui';
        }
        return back()->with('status', $this->messages);
    }
    public function deletePenilaianLembagaPlrkm(Request $request){
        $id = $request->input('id');
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/penilaianlembaga/'.$id,'DELETE');
            return $data_request;
        }
    }

    /*
    | DIREKTORAT PLRKM |
    | info lembaga umum             |
    */

    public function indexLembagaUmumPlrkm(Request $request){
        if($request->limit) {
            $this->limit = $request->limit;
        } else {
            $this->limit = config('app.limit');
        }


        $kondisi = '';
        if($request->isMethod('post')){
          $post = $request->except(['_token']);
          $tipe = $request->tipe;
          $tgl_from = $request->tgl_from;
          $tgl_from = $request->tgl_from;
          $tgl_to = $request->tgl_to;
          if($tipe){
            $kondisi .= '&tipe='.$tipe;
            $this->selected['tipe'] = $tipe;
          }
          if($tipe == 'nama' || $tipe == 'alamat'  || $tipe == 'cp_nama'){
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
          $get = $request->except(['page','limit','kategori']);
          $tipe = $request->tipe;
          if(count($get)>0){
            if($tipe){
              $this->selected['tipe']  = $tipe;
            }
            foreach ($get as $key => $value) {
                $kondisi .= "&".$key.'='.$value;
                $this->selected[$key] = $value;
            }

            $this->selected['order'] = $request->order;
            $this->selected['limit'] = $request->limit;
            $this->data['filter'] = $this->selected;
          }
        }
        $this->data['title'] = "Informasi Lembaga Umum Direktorat PLRIP";
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
        $kategori = 'kategori=plrkm';
        $datas = execute_api_json('api/infolembaga?'.$kategori.'&'.$limit.'&'.$offset.$kondisi,'get');
        if(($datas->status != 'error') && ($datas->code == 200) ){
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;
        }else{
            $this->data['data'] = [];
            $total_item = 0;
        }
        $this->data['delete_route'] = 'delete_informasi_lembaga_umum_plrip';
        $this->data['path'] = $request->path();
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
        $datas = execute_api('api/lookup/bentuk_layanan','get');
        if(($datas['code'] == 200) && ($datas['status'] != 'error')){
            $this->data['bentuk_layanan'] = $datas['data'];
        }else{
            $this->data['bentuk_layanan'] = [];
        }
        $this->data['route_name'] = $request->route()->getName();
        $this->data['delete_route'] = 'delete_informasi_lembaga_umum_plrkm';
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), '/'.$request->route()->getPrefix()."/informasi_lembaga_umum_plrkm",$filtering,$filter);
        $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
        return view('rehabilitasi.plrkm.informasi_umum.index_informasiLembagaUmum',$this->data);
    }
    public function editLembagaUmumPlrkm(Request $request){
        $id = $request->id;
        $datas = execute_api_json('api/infolembaga/'.$id ,'get');
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }

        $datas = execute_api_json('api/lookup/sumberbiaya_lembaga_rehab','get');
        if($datas->code == 200){
            $this->data['sumberbiaya'] = $datas->data;
        }else{
            $this->data['sumberbiaya'] = [];
        }

        $datas = execute_api_json('api/lookup/model_layanan_lembaga','get');
        if($datas->code == 200){
            $this->data['model_layanan'] = $datas->data;
        }else{
            $this->data['model_layanan'] = [];
        }

        $datas = execute_api_json('api/lookup/prasarana_lembaga_rehab','get');
        if($datas->code == 200){
            $this->data['prasarana'] = $datas->data;
        }else{
            $this->data['prasarana'] = [];
        }


        $datas = execute_api_json('api/lookup/status_layanan','get');
        if($datas->code == 200){
            $this->data['status_layanan'] = $datas->data;
        }else{
            $this->data['status_layanan'] = [];
        }

        $datas = execute_api_json('api/lookup/layanan_pendekatan','get');
        if($datas->code == 200){
            $this->data['layanan_pendekatan'] = $datas->data;
        }else{
            $this->data['layanan_pendekatan'] = [];
        }

        $datas = execute_api_json('api/lookup/layanan_ketersediaan','get');
        if($datas->code == 200){
            $this->data['layanan_ketersediaan'] = $datas->data;
        }else{
            $this->data['layanan_ketersediaan'] = [];
        }

        $datas = execute_api_json('api/lookup/periode_rawatan','get');
        if($datas->code == 200){
            $this->data['periode_rawatan'] = $datas->data;
        }else{
            $this->data['periode_rawatan'] = [];
        }
        $datas = execute_api_json('api/lookup/bentuk_layanan','get');
        if($datas->code == 200){
            $this->data['bentuk_layanan'] = $datas->data;
        }else{
            $this->data['bentuk_layanan'] = [];
        }
        $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
        return view('rehabilitasi.plrkm.informasi_umum.edit_informasiLembagaUmum',$this->data);
    }
    public function addLembagaUmumPlrkm(Request $request){
        if($request->isMethod('post')){
            $token = $request->session()->get('token');
            $this->form_params = $request->except(['_token', 'id']);
            if($request->kode_saranaprasarana){
                $this->form_params['kode_saranaprasarana'] = implode(',',$request->kode_saranaprasarana);
            }
            if($request->tgl_legalitas_masa_berlaku){
                $date = explode('/', $request->tgl_legalitas_masa_berlaku);
                $this->form_params['tgl_legalitas_masa_berlaku'] = $date[2].'-'.$date[1].'-'.$date[0];
            }


            $this->form_params['tarif_resmi_inap'] = str_replace(',', '', $request->tarif_resmi_inap);
            $this->form_params['tarif_resmi_jalan'] = str_replace(',', '', $request->tarif_resmi_jalan);
            if($request->bentuk_layanan){
                $this->form_params['bentuk_layanan'] = json_encode($request->bentuk_layanan);
            }
            if($request->layanan_pendekatan){
                $this->form_params['layanan_pendekatan'] = json_encode($request->layanan_pendekatan);
            }
            if($request->layanan_ketersediaan){
                $this->form_params['layanan_ketersediaan'] = json_encode($request->layanan_ketersediaan);
            }

            $this->form_params['kategori'] = 'plrkm';
            $data_request = execute_api_json('api/infolembaga/','POST',$this->form_params);

						$trail['audit_menu'] = 'Rehabilitasi - Direktorat PLRKM - Informasi Umum Lembaga';
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
                $id = $data_request->data->eventID;
                $this->kelengkapan_LembagaUmumPlrkm($id);
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Data Info Lembaga Umum PLRKM Berhasil Ditambahkan';
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Data Info Lembaga Umum PLRKM Gagal Ditambahkan';
            }
            return redirect(route('informasi_lembaga_umum_plrkm'))->with('status', $this->messages);
        }else{
            $datas = execute_api_json('api/lookup/sumberbiaya_lembaga_rehab','get');
            if($datas->code == 200){
                $this->data['sumberbiaya'] = $datas->data;
            }else{
                $this->data['sumberbiaya'] = [];
            }

            $datas = execute_api_json('api/lookup/model_layanan_lembaga','get');
            if($datas->code == 200){
                $this->data['model_layanan'] = $datas->data;
            }else{
                $this->data['model_layanan'] = [];
            }

            $datas = execute_api_json('api/lookup/prasarana_lembaga_rehab','get');
            if($datas->code == 200){
                $this->data['prasarana'] = $datas->data;
            }else{
                $this->data['prasarana'] = [];
            }


            $datas = execute_api_json('api/lookup/status_layanan','get');
            if($datas->code == 200){
                $this->data['status_layanan'] = $datas->data;
            }else{
                $this->data['status_layanan'] = [];
            }

            $datas = execute_api_json('api/lookup/layanan_pendekatan','get');
            if($datas->code == 200){
                $this->data['layanan_pendekatan'] = $datas->data;
            }else{
                $this->data['layanan_pendekatan'] = [];
            }

            $datas = execute_api_json('api/lookup/layanan_ketersediaan','get');
            if($datas->code == 200){
                $this->data['layanan_ketersediaan'] = $datas->data;
            }else{
                $this->data['layanan_ketersediaan'] = [];
            }

            $datas = execute_api_json('api/lookup/periode_rawatan','get');
            if($datas->code == 200){
                $this->data['periode_rawatan'] = $datas->data;
            }else{
                $this->data['periode_rawatan'] = [];
            }
            $datas = execute_api_json('api/lookup/bentuk_layanan','get');
            if($datas->code == 200){
                $this->data['bentuk_layanan'] = $datas->data;
            }else{
                $this->data['bentuk_layanan'] = [];
            }
            $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
            return view('rehabilitasi.plrkm.informasi_umum.add_informasiLembagaUmum',$this->data);
        }
    }

    public function updateLembagaUmumPlrkm(Request $request){
        $id = $request->id;
        $token = $request->session()->get('token');
        $this->form_params = $request->except(['_token', 'id']);
        if($request->kode_saranaprasarana){
            $this->form_params['kode_saranaprasarana'] = implode(',',$request->kode_saranaprasarana);
        }
        if($request->tgl_legalitas_masa_berlaku){
            $date = explode('/', $request->tgl_legalitas_masa_berlaku);
            $this->form_params['tgl_legalitas_masa_berlaku'] = $date[2].'-'.$date[1].'-'.$date[0];
        }


        $this->form_params['tarif_resmi_inap'] = str_replace(',', '', $request->tarif_resmi_inap);
        $this->form_params['tarif_resmi_jalan'] = str_replace(',', '', $request->tarif_resmi_jalan);
        if($request->bentuk_layanan){
            $this->form_params['bentuk_layanan'] = json_encode($request->bentuk_layanan);
        }
        if($request->layanan_pendekatan){
            $this->form_params['layanan_pendekatan'] = json_encode($request->layanan_pendekatan);
        }
        if($request->layanan_ketersediaan){
            $this->form_params['layanan_ketersediaan'] = json_encode($request->layanan_ketersediaan);
        }

        $data_request = execute_api_json('api/infolembaga/'.$id,'PUT',$this->form_params);

				$trail['audit_menu'] = 'Rehabilitasi - Direktorat PLRKM - Informasi Umum Lembaga';
				$trail['audit_event'] = 'put';
				$trail['audit_value'] = json_encode($this->form_params);
				$trail['audit_url'] = $request->url();
				$trail['audit_ip_address'] = $request->ip();
				$trail['audit_user_agent'] = $request->userAgent();
				$trail['audit_message'] = $data_request->comment;
				$trail['created_at'] = date("Y-m-d H:i:s");
				$trail['created_by'] = $request->session()->get('id');

				$qtrail = $this->inputtrail($request->session()->get('token'),$trail);

        if(($data_request->code == 200)&& ($data_request->status != "error") ){
            $this->kelengkapan_LembagaUmumPlrkm($id);
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Info Lembaga Umum PLRKM Berhasil Diperbarui';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Info Lembaga Umum PLRKM Gagal Diperbarui';
        }
        return back()->with('status', $this->messages);
    }


    public function deleteLembagaUmumPlrkm(Request $request){
        $id = $request->input('id');
        if ($request->ajax()) {
            if($id){
                $id = $request->id;
                $data_request = execute_api('api/infolembaga/'.$id,'DELETE');
								$this->form_params['delete_id'] = $id;
								$trail['audit_menu'] = 'Rehabilitasi - Direktorat PLRKM - Informasi Umum Lembaga';
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
                $data_request =['status'=>'error','messages'=>'Data Lembaga Umum PLRKM Gagal Dihapus'];
                return $data_request;
            }
        }
    }

    private function kelengkapan_LembagaUmumPlrkm($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('rehab_infoumumlembaga')->where('id',$id)
                  ->select('nama','alamat','alamat_kodepos','nomor_telp','email','penanggung_jawab','cp_nama','cp_telp','cp_email','status_layanan','layanan_pendekatan','layanan_ketersediaan','periode_rawatan','bentuk_layanan','periode_layanan_inap','periode_layanan_jalan','tarif_resmi_inap','tarif_resmi_jalan','sdm_manajemen','sdm_dokter_umum','sdm_spesialis_jiwa','sdm_dokter_gigi','sdm_spesialis_lain','sdm_psikolog','sdm_perawat','sdm_apoteker','sdm_analis','sdm_peksos','sdm_konselor','sdm_spsi','sdm_skm','sdm_sag','sdm_penunjang_administrasi','sdm_penunjang_logistik','sdm_penunjang_keamanan','kode_saranaprasarana','kapasitas_inap','kapasitas_jalan','pelatihan_assessment','pelatihan_adiksi','pelatihan_intervensi','pelatihan_fisiologi','pelatihan_tatalaksana','pelatihan_lainnya','pelatihan_lainnya_jumlah','jml_inap_sosial','jml_jalan_sosial','jml_inap_medis','jml_jalan_medis');
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
          $kelengkapan = execute_api_json('api/infolembaga/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/infolembaga/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }



    /*
    | DIREKTORAT PLRKM |
    | Dokumen NSPK     |
    */
    public function dokumenNspkPlrkm(Request $request){
        if($request->limit) {
            $this->limit = $request->limit;
        } else {
            $this->limit = config('app.limit');
        }
        $kondisi = '';
         if($request->isMethod('post')){
          $post = $request->except(['_token','tgl_from','tgl_to']);
          $tipe = $request->tipe;
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
          }else if( ($tipe == 'nama_nspk') || ($tipe == 'nomor_nsdpk' ) || ($tipe == 'peruntukan') ){
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
          $get = $request->except(['page','limit','tgl_from','tgl_to','kategori']);
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
              }else{
                $this->selected[$key] = $value;
                }
            }
            $this->selected['order'] = $request->order;
            $this->selected['limit'] = $request->limit;
            $this->data['filter'] = $this->selected;
          }
        }
        $this->data['title'] = "Dokumen NSPK PLRKM";
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
        $kategori = 'kategori=plrkm';
        $datas = execute_api_json('api/nspk?'.$kategori.'&'.$limit.'&'.$offset.$kondisi,'get');
        if(($datas->status != 'error') && ($datas->code == 200) ){
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;
        }else{
            $this->data['data'] = [];
            $total_item = 0;
        }
        $this->data['delete_route'] = 'delete_dokumen_nspk_plrkm';
        $this->data['path'] = $request->path();
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
        $this->data['route_name'] = $request->route()->getName();
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), '/'.$request->route()->getPrefix()."/dokumen_nspk_plrkm",$filtering,$filter);
        $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
        return view('rehabilitasi.plrkm.nspk.index_dokumenNspk',$this->data);

    }
    public function editDokumenNspkPlrkm(Request $request){
        $id = $request->id;
        $datas = execute_api_json('api/nspk/'.$id,'get');
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }
        $this->data['file_path'] = config('app.plrkm_nspk_file_path');
        $this->data['kode_direktorat'] = config('lookup.kode_direktorat');
        $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
        return view('rehabilitasi.plrkm.nspk.edit_dokumenNspk',$this->data);
    }
    public function addDokumenNspkPlrkm(Request $request){
        if($request->isMethod('post')){
            $this->form_params = $request->except(['_token']);
            if($request->tgl_pembuatan){
                $date = explode('/', $request->tgl_pembuatan);
                $this->form_params['tgl_pembuatan'] = $date[2].'-'.$date[1].'-'.$date[0];
            }
            $file_message = "";
            if($request->file('file_nspk')){
                $fileName = $request->file('file_nspk')->getClientOriginalName();
                $fileName = date('Y-m-d').'_'.$fileName;
                $extension = $request->file('file_nspk')->getClientOriginalExtension(); // getting image extension
                $directory = 'Rehabilitasi/NSPK/PLRKM';
                $path = Storage::putFileAs($directory, $request->file('file_nspk'),$fileName);
                if($path){
                    $this->form_params['file_nspk'] = $fileName;
                }else{
                    $file_message = "Dengan File gagal diupload.";
                }
            }
            $this->form_params['kategori'] = 'plrkm';
            $data_request = execute_api_json('api/nspk/','POST',$this->form_params);

						$trail['audit_menu'] = 'Rehabilitasi - Direktorat PLRKM - Dokumen NSPK';
						$trail['audit_event'] = 'post';
						$trail['audit_value'] = json_encode($this->form_params);
						$trail['audit_url'] = $request->url();
						$trail['audit_ip_address'] = $request->ip();
						$trail['audit_user_agent'] = $request->userAgent();
						$trail['audit_message'] = $data_request->comment;
						$trail['created_at'] = date("Y-m-d H:i:s");
						$trail['created_by'] = $request->session()->get('id');

						$qtrail = $this->inputtrail($request->session()->get('token'),$trail);

            if( ($data_request->code == 200) && ($data_request->status != 'error') ){
                $id = $data_request->data->eventID;
                $this->kelengkapan_DokumenNspkPlrkm($id);
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Dokumen NSPK Berhasil Ditambahkan '. $file_message;
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Dokumen NSPK Gagal Ditambahkan';
            }
            return redirect(route('dokumen_nspk_plrkm'))->with('status',$this->messages);
        }else{
            $this->data['current_category'] = 'plrkm';
            $this->data['kode_direktorat'] = config('lookup.kode_direktorat');
            $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
            return view('rehabilitasi.plrkm.nspk.add_dokumenNspk',$this->data);
        }
    }
    public function updateDokumenNspkPlrkm(Request $request){
        $id = $request->id;
        $this->form_params = $request->except(['_token', 'id']);
        if($request->tgl_pembuatan){
            $date = explode('/', $request->tgl_pembuatan);
            $this->form_params['tgl_pembuatan'] = $date[2].'-'.$date[1].'-'.$date[0];
        }
        $file_message ="";
        if($request->file('file_nspk')){
            $fileName = $request->file('file_nspk')->getClientOriginalName();
            $fileName = date('Y-m-d').'_'.$fileName;
            $extension = $request->file('file_nspk')->getClientOriginalExtension(); // getting image extension
            $directory = 'Rehabilitasi/NSPK/PLRKM';
            $path = Storage::putFileAs($directory, $request->file('file_nspk'),$fileName);
            if($path){
                $this->form_params['file_nspk'] = $fileName;
            }else{
                $file_message = "Dengan File gagal diupload.";
            }
        }
        $data_request = execute_api_json('api/nspk/'.$id,'PUT',$this->form_params);

				$trail['audit_menu'] = 'Rehabilitasi - Direktorat PLRKM - Dokumen NSPK';
				$trail['audit_event'] = 'put';
				$trail['audit_value'] = json_encode($this->form_params);
				$trail['audit_url'] = $request->url();
				$trail['audit_ip_address'] = $request->ip();
				$trail['audit_user_agent'] = $request->userAgent();
				$trail['audit_message'] = $data_request->comment;
				$trail['created_at'] = date("Y-m-d H:i:s");
				$trail['created_by'] = $request->session()->get('id');

				$qtrail = $this->inputtrail($request->session()->get('token'),$trail);

        if( ($data_request->code == 200) && ($data_request->status != 'error') ){
            // $id = $data_request->data->eventID;
            $this->kelengkapan_DokumenNspkPlrkm($id);
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Dokumen NSPK Berhasil Diperbarui '. $file_message;
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Dokumen NSPK Gagal Diperbarui';
        }
        return back()->with('status',$this->messages);
    }
    public function deleteDokumenNspkPlrkm(Request $request){
        $id = $request->id;
        if ($request->ajax()) {
            if($id){
                $data_request = execute_api('api/nspk/'.$id,'DELETE');
								$this->form_params['delete_id'] = $id;
								$trail['audit_menu'] = 'Rehabilitasi - Direktorat PLRKM - Dokumen NSPK';
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
                $data_request = ['status'=>'error','message'=>'Data Gagal Dihapus'];
                return $data_request;
            }

        }
    }

    private function kelengkapan_DokumenNspkPlrkm($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('rehab_nspk')->where('id',$id)
                  ->select('tgl_pembuatan','nama_nspk','nomor_nsdpk','peruntukan', 'file_nspk');
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
          $kelengkapan = execute_api_json('api/nspk/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/nspk/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

    /*
    | DIREKTORAT PLRKM |
    | Dokumen KegiatanPelatihan     |
    */
    public function kegiatanPelatihanPlrkm(Request $request){
        if($request->limit) {
            $this->limit = $request->limit;
        } else {
            $this->limit = config('app.limit');
        }


        $kondisi = '';
        if($request->isMethod('get')){
            $get = $request->except(['page','_token','kategori']);
            if(count($get ) > 0 ) {
                foreach ($get as $key => $value) {
                    $kondisi .= "&".$key.'='.$value;
                    if(($key == 'start_from') || ($key == 'start_to') || ($key == 'end_from') || ($key == 'end_to')|| ($key == 'jumlah_from') || ($key == 'jumlah_to') ){
                        if($value){
                            $value = date('d/m/Y',strtotime($value));
                        }else{
                            $value = "";
                        }
                    }else{
                        $value = $value;
                    }
                    $this->selected[$key] =$value;
                }
                $this->selected['order'] = $request->order;
                $this->data['filter'] = $this->selected;
            }
        }else{
            $post = $request->except(['_token','start_to','start_from','end_to','end_from','jumlah_from','jumlah_to']);
            $tipe = trim($request->tipe);
            $kata_kunci = trim($request->kata_kunci);
            $pelaksana = trim($request->pelaksana);
            $start_from = trim($request->start_from);
            $start_to = trim($request->start_to);
            $end_from = trim($request->end_from);
            $end_to = trim($request->end_to);
            $jumlah_from = trim($request->jumlah_from);
            $jumlah_to = trim($request->jumlah_to);
            $order = trim($request->order);
            $status = trim($request->status);

            if($tipe){
                $this->selected['selected'] = $tipe;
                $this->selected['tipe'] = $tipe;
                $kondisi .= '&tipe='.$tipe;

            }
            if( ($tipe == 'tema') || ($tipe == 'nomor_sprint' )  ){
                $kondisi .= '&'.$tipe.'='.$request->kata_kunci;
                $this->selected[$tipe] = $request->kata_kunci;
            }else {
                if($tipe == 'periode_start'){
                    if($start_from){
                        $this->selected['start_from'] = $start_from;
                        $start_from = date('Y-m-d',strtotime(str_replace('/', '-',$start_from)));
                        $kondisi .= '&start_from='.$start_from;
                    }else if(!$start_from){
                        $this->selected['start_from'] = "";
                        $kondisi .= "";
                    }
                    if($start_to){
                        $this->selected['start_to'] = $start_to;
                        $start_to = str_replace('/', '-',$start_to);
                        $start_to = date('Y-m-d',strtotime($start_to));
                        $kondisi .= '&start_to='.$start_to;
                    }else if(!$start_to){
                        $this->selected['start_to'] = "";
                        $kondisi .= "";
                    }
                }else if($tipe == 'periode_end'){
                    if($end_from){
                        $this->selected['end_from'] = $end_from;
                        $end_from = str_replace('/', '-',$end_from);
                        $end_from = date('Y-m-d',strtotime($end_from));
                        $kondisi .= '&end_from='.$end_from;
                    }else if(!$end_from){
                        $this->selected['end_from'] = "";
                        $kondisi .= "";
                    }
                    if($end_to){
                        $this->selected['end_to'] = $end_to;
                        $end_to = str_replace('/', '-',$end_to);
                        $end_to = date('Y-m-d',strtotime($end_to));
                        $kondisi .= '&end_to='.$end_to;
                    }else if(!$end_to){
                        $this->selected['end_to'] = "";
                        $kondisi .= "";
                    }
                }else if($tipe == 'jumlah_peserta'){
                    if($jumlah_from){
                        $this->selected['jumlah_from'] = $jumlah_from;
                        $kondisi .='&jumlah_from='.$jumlah_from;
                    }else if(!$jumlah_from){
                        $this->selected['jumlah_from'] = "";
                        $kondisi .= "";
                    }

                    if($jumlah_to){
                        $this->selected['jumlah_to'] = $jumlah_to;
                        $kondisi .='&jumlah_to='.$jumlah_to;
                    }else if(!$jumlah_to){
                        $this->selected['jumlah_to'] = "";
                        $kondisi .= "";
                    }
                }else {
                    if(isset($post[$tipe])){
                        $kondisi .= '&'.$tipe.'='.$post[$tipe];
                        $this->selected[$tipe] = $post[$tipe];
                    }
                }
            }

            if($order){
                $this->selected['order'] = $order;
                $kondisi .= '&order='.$order;
            }else if(!$order){
                $this->selected['order'] = "desc";
                $kondisi .= "";
            }

            $this->selected['limit'] = $request->limit;
            $kondisi .= '&limit='.$request->limit;
            $this->data['filter'] = $this->selected;
        }

        $this->data['title'] = "Data Kegiatan Direktorat PLRKM";
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
        $kategori = 'kategori=plrkm';

        $datas = execute_api_json('api/pelatihan?'.$kategori.'&'.$limit.'&'.$offset.$kondisi.'&id_wilayah='.$request->session()->get('wilayah'),'get');
        if(($datas->status != 'error') && ($datas->code == 200) ){
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;
        }else{
            $this->data['data'] = [];
            $total_item = 0;
        }
        $this->data['delete_route'] = 'delete_kegiatan_pelatihan_plrkm';
        $this->data['path'] = $request->path();
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
        $this->data['route_name'] = $request->route()->getName();
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), '/'.$request->route()->getPrefix()."/kegiatan_pelatihan_plrkm",$filtering,$filter);
        $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
        return view('rehabilitasi.plrkm.kegiatan_pelatihan.index_kegiatanPelatihan',$this->data);
    }
    public function editKegiatanPelatihanPlrkm(Request $request){
        $id = $request->id;
        $datas = execute_api_json('api/pelatihan/'.$id,'get');
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }

        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }

        $this->limit = config('app.limit');
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
        $parent_id = 'parent_id='.$id;
        $datas = execute_api_json('api/pelatihanpeserta?'.$parent_id.'&'.$offset.'&'.$limit,'get');
        if($datas->code == 200){
            $this->data['peserta'] = $datas->data;
        }else{
            $this->data['peserta'] = [];
        }

        $total_item = $datas->paginate->totalpage * $this->limit;
        $this->data['pagination'] = ajax_pagination($current_page,$total_item, $this->limit, config('app.page_ellipsis'),"/".$request->route()->getPrefix()."/index_peserta_pelatihan_pascarehabilitasi/".$id."/%d");
        $datas = execute_api_json('api/lookup/rehab_jenis_kegiatan','GET');
        if( ($datas->code == 200) && ($datas->status != 'error')){
            $this->data['jenis_kegiatan'] = $datas->data;
        }else{
            $this->data['jenis_kegiatan'] = [];
        }
        $this->data['delete_route'] = 'delete_peserta_pelatihan_plrkm';
        $this->data['file_path'] = config('app.plrkm_kegiatan_file_path');
        $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
        return view('rehabilitasi.plrkm.kegiatan_pelatihan.edit_kegiatanPelatihan',$this->data);
    }
    public function addKegiatanPelatihanPlrkm(Request $request){
        if($request->isMethod('post')){
            $this->form_params = $request->except(['_token']);
            $file_message = "";
            if($request->tgl_sprint) {
                $date = explode('/', $request->tgl_sprint);
                $this->form_params['tgl_sprint'] = $date[2].'-'.$date[1].'-'.$date[0];
            }

            if($request->tgl_dilaksanakan_mulai) {
                $date = explode('/', $request->tgl_dilaksanakan_mulai);
                $this->form_params['tgl_dilaksanakan_mulai'] = $date[2].'-'.$date[1].'-'.$date[0];
            }

            if($request->tgl_dilaksanakan_mulai) {
                $date = explode('/', $request->tgl_dilaksanakan_selesai);
                $this->form_params['tgl_dilaksanakan_selesai'] = $date[2].'-'.$date[1].'-'.$date[0];
            }

            if($request->file('file_materi')){
                $fileName = $request->file('file_materi')->getClientOriginalName();
                $fileName = date('Y-m-d').'_'.$fileName;
                $extension = $request->file('file_materi')->getClientOriginalExtension(); // getting image extension
                $directory = 'Rehabilitasi/KegiatanPelatihan/PLRKM/';
                $path = Storage::putFileAs($directory, $request->file('file_materi'),$fileName);
                if($path){
                    $this->form_params['file_materi'] = $fileName;
                }else{
                    $file_message = "Dengan File gagal diupload.";
                }
            }
            $this->form_params['created_by_username'] = getPelaksana($request->id_pelaksana);
            $this->form_params['kategori'] = 'plrkm';
            $data_request = execute_api_json('api/pelatihan/','POST',$this->form_params);

						$trail['audit_menu'] = 'Rehabilitasi - Direktorat PLRKM - Kegiatan';
						$trail['audit_event'] = 'post';
						$trail['audit_value'] = json_encode($this->form_params);
						$trail['audit_url'] = $request->url();
						$trail['audit_ip_address'] = $request->ip();
						$trail['audit_user_agent'] = $request->userAgent();
						$trail['audit_message'] = $data_request->comment;
						$trail['created_at'] = date("Y-m-d H:i:s");
						$trail['created_by'] = $request->session()->get('id');

						$qtrail = $this->inputtrail($request->session()->get('token'),$trail);


            if( ($data_request->code == 200) && ($data_request->status != 'error') ){
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Data Kegiatan PLRKM Berhasil Ditambahkan '. $file_message;
                $id = $data_request->data->id;
                $this->kelengkapan_KegiatanPelatihanPlrkm($id);
                return redirect(route('edit_kegiatan_pelatihan_plrkm',$id))->with('status',$this->messages);
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Data Kegiatan PLRKM Gagal Ditambahkan';
                return redirect(route('kegiatan_pelatihan_plrkm'))->with('status',$this->messages);
            }

        }else{
            $datas = execute_api_json('api/lookup/rehab_jenis_kegiatan','GET');
            if( ($datas->code == 200) && ($datas->status != 'error')){
                $this->data['jenis_kegiatan'] = $datas->data;
            }else{
                $this->data['jenis_kegiatan'] = [];
            }
            $this->data['title']="Form Tambah Kegiatan Direktorat PLRKM";
            $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
            return view('rehabilitasi.plrkm.kegiatan_pelatihan.add_kegiatanPelatihan',$this->data);
        }
    }

    public function deleteKegiatanPelatihanPlrkm(Request $request){
        $id = $request->input('id');
        if ($request->ajax()) {
            if($id){
                $id = $request->id;
                $data_request = execute_api('api/pelatihan/'.$id,'DELETE');
								$this->form_params['delete_id'] = $id;
								$trail['audit_menu'] = 'Rehabilitasi - Direktorat PLRKM - Kegiatan';
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
                $data_request =['status'=>'error','messages'=>'Gagal Menghapus Data'];
                return $data_request;
            }
        }
    }
    public function updateKegiatanPelatihanPlrkm(Request $request){
        $id = $request->id;
        $this->form_params = $request->except(['_token', 'id']);
        $file_message = "";
        if($request->tgl_sprint){
            $date = explode('/', $request->tgl_sprint);
            $this->form_params['tgl_sprint'] = $date[2].'-'.$date[1].'-'.$date[0];
        }

        if($request->tgl_dilaksanakan_mulai){
            $date = explode('/', $request->tgl_dilaksanakan_mulai);
            $this->form_params['tgl_dilaksanakan_mulai'] = $date[2].'-'.$date[1].'-'.$date[0];
        }

        if($request->tgl_dilaksanakan_selesai){
            $date = explode('/', $request->tgl_dilaksanakan_selesai);
            $this->form_params['tgl_dilaksanakan_selesai'] = $date[2].'-'.$date[1].'-'.$date[0];
        }

        if($request->file('file_materi')){
            $fileName = $request->file('file_materi')->getClientOriginalName();
            $fileName = date('Y-m-d').'_'.$fileName;
            $extension = $request->file('file_materi')->getClientOriginalExtension(); // getting image extension
            $directory = 'Rehabilitasi/KegiatanPelatihan/PLRKM';
            $path = Storage::putFileAs($directory, $request->file('file_materi'),$fileName);
            if($path){
                $this->form_params['file_materi'] = $fileName;
            }else{
                $file_message = "Dengan File gagal diupload.";
            }
        }
        $this->form_params['created_by_username'] = getPelaksana($request->id_pelaksana);
        $data_request = execute_api_json('api/pelatihan/'.$id,'PUT',$this->form_params);

				$trail['audit_menu'] = 'Rehabilitasi - Direktorat PLRKM - Kegiatan';
				$trail['audit_event'] = 'put';
				$trail['audit_value'] = json_encode($this->form_params);
				$trail['audit_url'] = $request->url();
				$trail['audit_ip_address'] = $request->ip();
				$trail['audit_user_agent'] = $request->userAgent();
				$trail['audit_message'] = $data_request->comment;
				$trail['created_at'] = date("Y-m-d H:i:s");
				$trail['created_by'] = $request->session()->get('id');

				$qtrail = $this->inputtrail($request->session()->get('token'),$trail);

        if( ($data_request->code == 200) && ($data_request->status != 'error') ){
            // $id = $data_request->data->eventID;
            $this->kelengkapan_KegiatanPelatihanPlrkm($id);
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Kegiatan PLRKM Berhasil Diperbarui '. $file_message;
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Kegiatan PLRKM Gagal Diperbarui';
        }
        return back()->with('status',$this->messages);
    }

    private function kelengkapan_KegiatanPelatihanPlrkm($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('rehab_pelatihan')->where('id',$id)
                  ->select('jenis_kegiatan','tema','id_pelaksana','nomor_sprint', 'tgl_sprint','tgl_dilaksanakan_mulai','tgl_dilaksanakan_selesai','tempat','jumlah_narasumber','jumlah_peserta','file_materi');
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
          $kelengkapan = execute_api_json('api/pelatihan/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/pelatihan/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }


    public function deletePesertaPelatihanPlrkm(Request $request){
        $id = $request->input('id');
        if ($request->ajax()) {
            $html = "";
            $paginate = "";
            $id = $request->id;
            $parent_id = $request->parent_id;
            $current_page = 1;
            $current_page = $request->current_page;
            $data_request = execute_api('api/pelatihanpeserta/'.$id,'DELETE');
						$this->form_params['delete_id'] = $id;
						$trail['audit_menu'] = 'Rehabilitasi - Direktorat PLRKM - Kegiatan Peserta';
						$trail['audit_event'] = 'delete';
						$trail['audit_value'] = json_encode($this->form_params);
						$trail['audit_url'] = $request->url();
						$trail['audit_ip_address'] = $request->ip();
						$trail['audit_user_agent'] = $request->userAgent();
						$trail['audit_message'] = $data_request['comment'];
						$trail['created_at'] = date("Y-m-d H:i:s");
						$trail['created_by'] = $request->session()->get('id');

						$qtrail = $this->inputtrail($request->session()->get('token'),$trail);

            $offset = 'page='.$current_page;
            $kategori = 'parent_id='.$parent_id;
            $limit = 'limit='.config('app.limit');
            if($request->current_page){
                $current_page = $request->current_page;
                $start_number = ($this->limit * ($request->current_page -1 )) + 1;
            }else{
                $current_page = 1;
                $start_number = $current_page;
            }
          $current_data = execute_api_json('api/pelatihanpeserta?'.$kategori.'&'.$limit.'&'.$offset,'get');
          if($current_data->code == 200){
            $datas = $current_data->data;
            if(count($datas)>0){

                $j = $start_number;
                foreach($datas as $d){
                    $html .= "<tr>";
                    $html .= "<td>".$j."</td>";
                    $html .= "<td>".$d->nama_peserta."</td>";
                    $html .= "<td>".$d->nomor_identitas."</td>";
                    $html .= "<td>". ( ($d->kode_jeniskelamin == "P") ? 'Perempuan' : ( ($d->kode_jeniskelamin == "L") ? 'Laki-Laki' : ''))."</td>";
                    $html .= "<td>".$d->asal_instansilembaga."</td>";
                    $html .= '<td class="actionTable">
                                <button type="button" class="btn btn-primary button-edit" data-target="'.$d->id_detail.'" onClick="open_modalEditPeserta(event,this,\'/rehabilitasi/dir_pasca/edit_peserta_pelatihan_plrkm/\',\'modal_edit_form\')"><i class="fa fa-pencil"></i></button>
                                <button type="button" class="btn btn-primary button-action" data-target="'.$d->id_detail.'" onClick="delete_row_peserta(event,this)"><i class="fa fa-trash"></i></button>
                            </td>';
                    $html .= "</tr>";
                    $j = $j+1;
                }
                $total_item = $current_data->paginate->totalpage * config('app.limit');
                if($total_item > config('app.limit')) {
                    $paginate = ajax_pagination($current_page,$total_item, config('app.limit'), config('app.page_ellipsis'),"/".$request->route()->getPrefix()."/index_peserta_pelatihan_plrkm/".$id."/%d");
                }else{
                    $paginate = "";
                }

            }
            else{
                $html = "<tr><td colspan=6> <p class='alert-warning'>Data Peserta Kegiatan Pendidikan dan Pelatihan Pada Balai Belum Tersedia </p></td> </tr>";
            }

          }else{
            $html = "<tr><td colspan=6> <p class='alert-warning'>Data Peserta Kegiatan Pendidikan dan Pelatihan Pada Balai Belum Tersedia </p></td> </tr>";
          }

          $data_request['data_return'] = $html;
          $data_request['pagination'] = $paginate ;
          return  $data_request;
        }
    }
    public function updatePesertaPelatihanPlrkm(Request $request){
        if($request->ajax()){
            $id = $request->id_detail;
            $this->form_params = $request->except(['_token','id_detail','index']);
            $data_request = execute_api('api/pelatihanpeserta/'.$id,'PUT',$this->form_params);

						$trail['audit_menu'] = 'Rehabilitasi - Direktorat PLRKM - Kegiatan Peserta';
						$trail['audit_event'] = 'put';
						$trail['audit_value'] = json_encode($this->form_params);
						$trail['audit_url'] = $request->url();
						$trail['audit_ip_address'] = $request->ip();
						$trail['audit_user_agent'] = $request->userAgent();
						$trail['audit_message'] = $data_request['comment'];
						$trail['created_at'] = date("Y-m-d H:i:s");
						$trail['created_by'] = $request->session()->get('id');

						$qtrail = $this->inputtrail($request->session()->get('token'),$trail);

            $data_request = json_decode(json_encode($data_request), FALSE);
            $html  = "<tr>";
            $html .= '<td></td>';
            $html .= '<td>'.$request->nama_peserta.'</td>';
            $html .= '<td>'.$request->nomor_identitas.'</td>';
            $html .= '<td>'.( ($request->kode_jeniskelamin == "P") ? 'Perempuan' : ( ($request->kode_jeniskelamin == "L") ? 'Laki-Laki' : '')).'</td>';
            $html .= '<td>'.$request->asal_instansilembaga.'</td>';
            $html .= '<td class="actionTable">
                        <button type="button" class="btn btn-primary button-edit" data-target="'.$id.'" onClick="open_modalEditPeserta(event,this,\'/rehabilitasi/dir_pasca/edit_peserta_pelatihan_plrkm/\',\'modal_edit_form\')"><i class="fa fa-pencil"></i></button>
                        <button type="button" class="btn btn-primary button-action" data-target="'.$id.'" onClick="delete_row_peserta(event,this)"><i class="fa fa-trash"></i></button>
                    </td>';
            $html  .= "</tr>";
            if(($data_request->code == 200)&& ($data_request->status != "error") ){
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Data Peserta Pelatihan PLRKM Berhasil Diperbarui';
                $this->messages['data'] = $html;
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Data Peserta Pelatihan PLRKM Gagal Diperbarui';
                $this->messages['data'] = null;
            }
            return response()->json($this->messages);
        }
    }
    public function addPesertaPelatihanPlrkm(Request $request){
        if($request->ajax()){
            $id = $request->id_header;
            $data_request = "";
            $this->form_params = $request->except(['_token','parent_id']);
            $data_request = execute_api_json('api/pelatihanpeserta/','POST',$this->form_params);

						$trail['audit_menu'] = 'Rehabilitasi - Direktorat PLRKM - Kegiatan Peserta';
						$trail['audit_event'] = 'post';
						$trail['audit_value'] = json_encode($this->form_params);
						$trail['audit_url'] = $request->url();
						$trail['audit_ip_address'] = $request->ip();
						$trail['audit_user_agent'] = $request->userAgent();
						$trail['audit_message'] = $data_request->comment;
						$trail['created_at'] = date("Y-m-d H:i:s");
						$trail['created_by'] = $request->session()->get('id');

						$qtrail = $this->inputtrail($request->session()->get('token'),$trail);

            // $data_request = json_decode(json_encode($data_request), FALSE);
            if(($data_request->code == 200)&& ($data_request->status != "error") ){
                $id_detail = $data_request->data->id;
                $current_page = 1;
                $parent_id = $id;
                $offset = 'page=1';
                $kategori = 'parent_id='.$parent_id;
                $limit = 'limit='.config('app.limit');
                $start_number = $current_page;
                $current_data = execute_api_json('api/pelatihanpeserta?'.$kategori.'&'.$limit.'&'.$offset,'get');
                if($current_data->code == 200 && $current_data->status != 'error'){
                    $datas = $current_data->data;
                    if(count($datas)>0){
                        $j = $start_number;
                        $html = "";
                        foreach($datas as $d){
                            $html .= "<tr>";
                            $html .= "<td>".$j."</td>";
                            $html .= "<td>".$d->nama_peserta."</td>";
                            $html .= "<td>".$d->nomor_identitas."</td>";
                            $html .= '<td>'.( ($d->kode_jeniskelamin == "P") ? 'Perempuan' : ( ($d->kode_jeniskelamin == "L") ? 'Laki-Laki' : '')).'</td>';
                            $html .= "<td>".$d->asal_instansilembaga."</td>";
                            $html .= '<td class="actionTable">
                                        <button type="button" class="btn btn-primary button-edit" data-target="'.$d->id_detail.'" onClick="open_modalEditPeserta(event,this,\'/rehabilitasi/dir_plrkm/edit_peserta_pelatihan_plrkm/\',\'modal_edit_form\')"><i class="fa fa-pencil"></i></button>
                                        <button type="button" class="btn btn-primary button-action" data-target="'.$d->id_detail.'" onClick="delete_row_peserta(event,this)"><i class="fa fa-trash"></i></button>
                                    </td>';
                            $html .= "</tr>";
                            $j = $j+1;
                        }
                        $total_item = $current_data->paginate->totalpage * config('app.limit');
                        if($total_item > config('app.limit')) {
                            $paginate = ajax_pagination($current_page,$total_item, config('app.limit'), config('app.page_ellipsis'),"/".$request->route()->getPrefix()."/index_peserta_pelatihan_plrkm/".$parent_id."/%d");
                        }else{
                            $paginate = "";
                        }

                    }
                    else{
                        $html = "<tr><td colspan=6> <p class='alert-warning'>Data Peserta Kegiatan Pendidikan dan Pelatihan Pada Balai Belum Tersedia </p></td> </tr>";
                    }
                }else{
                      $html = "<tr><td colspan=6> <p class='alert-warning'>Data Peserta Kegiatan Pendidikan dan Pelatihan Pada Balai Belum Tersedia </p></td> </tr>";
                }
                // $html  = "<tr>";
                // $html .= '<td></td>';
                // $html .= '<td>'.$request->nama_peserta.'</td>';
                // $html .= '<td>'.$request->nomor_identitas.'</td>';
                // $html .= '<td>'.( ($request->kode_jeniskelamin == "P") ? 'Perempuan' : ( ($request->kode_jeniskelamin == "L") ? 'Laki-Laki' : '')).'</td>';
                // $html .= '<td>'.$request->asal_instansilembaga.'</td>';
                // $html .= '<td class="actionTable">
                //             <button type="button" class="btn btn-primary button-edit" data-target="'.$data_request->data->id.'" onClick="open_modalEditPeserta(event,this,\'/rehabilitasi/dir_plrip/edit_peserta_pelatihan_plrip/\',\'modal_edit_form\')"><i class="fa fa-pencil"></i></button>
                //             <button type="button" class="btn btn-primary button-action" data-target="'.$data_request->data->id.'" onClick="ajax_delete(event,this)"><i class="fa fa-trash"></i></button>
                //         </td>';
                // $html  .= "</tr>";
                // $count = $data_request->data->count;
                // $paginate= ajax_pagination(1,$count, config('app.limit'), config('app.page_ellipsis'),"/".$request->route()->getPrefix()."/index_peserta_pelatihan_pascarehabilitasi/".$id."/%d");

                // $this->result_data[]
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Data Peserta Pelatihan PLRIP Berhasil Ditambahkan';
                $this->messages['data_return'] = $html;
                $this->messages['pagination'] = $paginate;


            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Data Peserta Pelatihan PLRKM Gagal Diperbarui';
                $this->messages['data'] = null;
            }
            return response()->json($this->messages );
        }
    }
    public function editPesertaPelatihanPlrkm(Request $request){
        if($request->ajax()){
            $id = $request->id;
            $this->form_params = $request->except(['_token','id']);
            $data_request = execute_api_json('api/pelatihanpeserta/'.$id,'GET');
            if(($data_request->code == 200)&& ($data_request->status != "error") ){
                $this->messages['status'] = 'success';
                $this->messages['data'] = $data_request->data;
            }else{
                $this->messages['status'] = 'error';
                $this->messages['data'] = [];
            }
            return response()->json($this->messages);

        }
    }
    public function indexPesertaKegiatanPelatihanPlrkm(Request $request){
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
        $parent_id = 'parent_id='.$parent_id;

        $datas = execute_api_json('api/pelatihanpeserta?'.$parent_id.'&'.$limit.'&'.$offset,'get');

        // $datas = execute_api_json('api/single_pelatihan_rehabilitasi/'.$parent_id.'?'.$limit.'&'.$offset,'get');
        if($datas->code == 200){
            $this->data['status'] = 'success';
            if(count($datas->data)>=1){
                $html = "";
                $j = $start_number;
                foreach($datas->data as $p){
                    $html .= '<tr>';
                    $html .= '<td>'.$j.'</td>';
                    $html .= '<td>'.$p->nama_peserta.'</td>';
                    $html .= '<td>'.$p->nomor_identitas.'</td>';
                    $html .= '<td> '. ( ($p->kode_jeniskelamin == "P") ? 'Perempuan' : ( ($p->kode_jeniskelamin == "L") ? 'Laki-Laki' : '')).'</td>';
                    $html .= '<td>'.$p->asal_instansilembaga.'</td>';
                    $html .= '<td class="actionTable">';
                    $html .= '<button type="button" class="btn btn-primary button-edit" data-target="'.$p->id_detail.'" onClick="open_modalEditPeserta(event,this,\'/rehabilitasi/dir_pasca/edit_peserta_pelatihan_plrkm/\',\'modal_edit_form\')"><i class="fa fa-pencil"></i></button>';
                    $html .= '<button type="button" class="btn btn-primary button-action" data-target="'.$p->id_detail.'" onClick="delete_row_peserta(event,this)"><i class="fa fa-trash"></i></button>';
                    $html .= '</td>';
                    $html .= '</tr>';
                    $j = $j + 1;
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




    /*
    | DIREKTORAT PLRIP |
    | info lembaga umum             |
    */
    public function indexLembagaUmumPlrip(Request $request){
        if($request->limit) {
            $this->limit = $request->limit;
        } else {
            $this->limit = config('app.limit');
        }


        $kondisi = '';
        if($request->isMethod('post')){
          $post = $request->except(['_token']);
          $tipe = $request->tipe;
          $tgl_from = $request->tgl_from;
          $tgl_from = $request->tgl_from;
          $tgl_to = $request->tgl_to;
          if($tipe){
            $kondisi .= '&tipe='.$tipe;
            $this->selected['tipe'] = $tipe;
          }
          if($tipe == 'nama' || $tipe == 'alamat'  || $tipe == 'cp_nama'){
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
          $get = $request->except(['page','limit']);
          $tipe = $request->tipe;
          if(count($get)>0){
            if($tipe){
              $this->selected['tipe']  = $tipe;
            }
            foreach ($get as $key => $value) {
                $kondisi .= "&".$key.'='.$value;
                $this->selected[$key] = $value;
            }

            $this->selected['order'] = $request->order;
            $this->selected['limit'] = $request->limit;
            $this->data['filter'] = $this->selected;
          }
        }
        $this->data['title'] = "Informasi Lembaga Umum Direktorat PLRIP";
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
        $kategori = 'kategori=plrip';
        $datas = execute_api_json('api/infolembaga?'.$kategori.'&'.$limit.'&'.$offset.$kondisi,'get');
        if(($datas->status != 'error') && ($datas->code == 200) ){
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;
        }else{
            $this->data['data'] = [];
            $total_item = 0;
        }
        $this->data['delete_route'] = 'delete_informasi_lembaga_umum_plrip';
        $this->data['path'] = $request->path();
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
        $datas = execute_api('api/lookup/bentuk_layanan','get');
        if(($datas['code'] == 200) && ($datas['status'] != 'error')){
            $this->data['bentuk_layanan'] = $datas['data'];
        }else{
            $this->data['bentuk_layanan'] = [];
        }
        $this->data['delete_route'] = 'delete_informasi_lembaga_umum_plrip';
        $this->data['route_name'] = $request->route()->getName();
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), '/'.$request->route()->getPrefix()."/informasi_lembaga_umum_plrip",$filtering,$filter);
        $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
        return view('rehabilitasi.plrip.informasi_umum.index_informasiLembagaUmum',$this->data);
    }

    public function editLembagaUmumPlrip(Request $request){
        $id = $request->id;
        $datas = execute_api_json('api/infolembaga/'.$id ,'get');
        if(($datas->code == 200) && ($datas->status != 'error')){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }

        $datas = execute_api_json('api/lookup/sumberbiaya_lembaga_rehab','get');
        if(($datas->code == 200) && ($datas->status != 'error')){
            $this->data['sumberbiaya'] = $datas->data;
        }else{
            $this->data['sumberbiaya'] = [];
        }

        $datas = execute_api_json('api/lookup/model_layanan_lembaga','get');
        if(($datas->code == 200) && ($datas->status != 'error')){
            $this->data['model_layanan'] = $datas->data;
        }else{
            $this->data['model_layanan'] = [];
        }

        $datas = execute_api_json('api/lookup/prasarana_lembaga_rehab','get');
        if(($datas->code == 200) && ($datas->status != 'error')){
            $this->data['prasarana'] = $datas->data;
        }else{
            $this->data['prasarana'] = [];
        }


        $datas = execute_api_json('api/lookup/status_layanan','get');
        if(($datas->code == 200) && ($datas->status != 'error')){
            $this->data['status_layanan'] = $datas->data;
        }else{
            $this->data['status_layanan'] = [];
        }

        $datas = execute_api_json('api/lookup/layanan_pendekatan','get');
        if(($datas->code == 200) && ($datas->status != 'error')){
            $this->data['layanan_pendekatan'] = $datas->data;
        }else{
            $this->data['layanan_pendekatan'] = [];
        }

        $datas = execute_api_json('api/lookup/layanan_ketersediaan','get');
        if(($datas->code == 200) && ($datas->status != 'error')){
            $this->data['layanan_ketersediaan'] = $datas->data;
        }else{
            $this->data['layanan_ketersediaan'] = [];
        }

        $datas = execute_api_json('api/lookup/periode_rawatan','get');
        if(($datas->code == 200) && ($datas->status != 'error')){
            $this->data['periode_rawatan'] = $datas->data;
        }else{
            $this->data['periode_rawatan'] = [];
        }
        $datas = execute_api_json('api/lookup/bentuk_layanan','get');
        if(($datas->code == 200) && ($datas->status != 'error')){
            $this->data['bentuk_layanan'] = $datas->data;
        }else{
            $this->data['bentuk_layanan'] = [];
        }


        $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
        return view('rehabilitasi.plrip.informasi_umum.edit_informasiLembagaUmum',$this->data);

    }

    public function updateLembagaUmumPlrip(Request $request){
        $id = $request->id;
        $token = $request->session()->get('token');
        $this->form_params = $request->except(['_token', 'id']);
        if($request->kode_saranaprasarana){
            $this->form_params['kode_saranaprasarana'] = implode(',',$request->kode_saranaprasarana);
        }
        if($request->tgl_legalitas_masa_berlaku){
            $date = explode('/', $request->tgl_legalitas_masa_berlaku);
            $this->form_params['tgl_legalitas_masa_berlaku'] = $date[2].'-'.$date[1].'-'.$date[0];
        }


        $this->form_params['tarif_resmi_inap'] = str_replace(',', '', $request->tarif_resmi_inap);
        $this->form_params['tarif_resmi_jalan'] = str_replace(',', '', $request->tarif_resmi_jalan);
        if($request->bentuk_layanan){
            $this->form_params['bentuk_layanan'] = json_encode($request->bentuk_layanan);
        }
        if($request->layanan_pendekatan){
            $this->form_params['layanan_pendekatan'] = json_encode($request->layanan_pendekatan);
        }
        if($request->layanan_ketersediaan){
            $this->form_params['layanan_ketersediaan'] = json_encode($request->layanan_ketersediaan);
        }


        $data_request = execute_api_json('api/infolembaga/'.$id,'PUT',$this->form_params);

				$trail['audit_menu'] = 'Rehabilitasi - Direktorat PLRIP - Informasi Umum Lembaga';
				$trail['audit_event'] = 'put';
				$trail['audit_value'] = json_encode($this->form_params);
				$trail['audit_url'] = $request->url();
				$trail['audit_ip_address'] = $request->ip();
				$trail['audit_user_agent'] = $request->userAgent();
				$trail['audit_message'] = $data_request->comment;
				$trail['created_at'] = date("Y-m-d H:i:s");
				$trail['created_by'] = $request->session()->get('id');

				$qtrail = $this->inputtrail($request->session()->get('token'),$trail);

        $this->kelengkapan_LembagaUmumPlrip($id);
        if(($data_request->code == 200)&& ($data_request->status != "error") ){
            $this->kelengkapan_LembagaUmumPlrip($id);
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Info Lembaga Umum PLRIP Berhasil Diperbarui';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Info Lembaga Umum PLRIP Gagal Diperbarui';
        }
        return back()->with('status', $this->messages);
    }

    public function addLembagaUmumPlrip(Request $request){
        if($request->isMethod('post')){
            $token = $request->session()->get('token');
            $this->form_params = $request->except(['_token', 'id']);
            if($request->kode_saranaprasarana){
                $this->form_params['kode_saranaprasarana'] = implode(',',$request->kode_saranaprasarana);
            }
            if($request->tgl_legalitas_masa_berlaku){
                $date = explode('/', $request->tgl_legalitas_masa_berlaku);
                $this->form_params['tgl_legalitas_masa_berlaku'] = $date[2].'-'.$date[1].'-'.$date[0];
            }


            $this->form_params['tarif_resmi_inap'] = str_replace(',', '', $request->tarif_resmi_inap);
            $this->form_params['tarif_resmi_jalan'] = str_replace(',', '', $request->tarif_resmi_jalan);
            if($request->bentuk_layanan){
                $this->form_params['bentuk_layanan'] = json_encode($request->bentuk_layanan);
            }
            if($request->layanan_pendekatan){
                $this->form_params['layanan_pendekatan'] = json_encode($request->layanan_pendekatan);
            }
            if($request->layanan_ketersediaan){
                $this->form_params['layanan_ketersediaan'] = json_encode($request->layanan_ketersediaan);
            }

             $this->form_params['kategori'] = 'plrip';
            $data_request = execute_api_json('api/infolembaga/','POST',$this->form_params);

						$trail['audit_menu'] = 'Rehabilitasi - Direktorat PLRIP - Informasi Umum Lembaga';
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
                $id = $data_request->data->eventID;
                $this->kelengkapan_LembagaUmumPlrip($id);
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Data Info Lembaga Umum PLRIP Berhasil Diperbarui';
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Data Info Lembaga Umum PLRIP Gagal Diperbarui';
            }

            return redirect(route('informasi_lembaga_umum_plrip'))->with('status', $this->messages);
        }else{

            $datas = execute_api_json('api/lookup/sumberbiaya_lembaga_rehab','get');
            if($datas->code == 200){
                $this->data['sumberbiaya'] = $datas->data;
            }else{
                $this->data['sumberbiaya'] = [];
            }

            $datas = execute_api_json('api/lookup/model_layanan_lembaga','get');
            if($datas->code == 200){
                $this->data['model_layanan'] = $datas->data;
            }else{
                $this->data['model_layanan'] = [];
            }

            $datas = execute_api_json('api/lookup/prasarana_lembaga_rehab','get');
            if($datas->code == 200){
                $this->data['prasarana'] = $datas->data;
            }else{
                $this->data['prasarana'] = [];
            }


            $datas = execute_api_json('api/lookup/status_layanan','get');
            if($datas->code == 200){
                $this->data['status_layanan'] = $datas->data;
            }else{
                $this->data['status_layanan'] = [];
            }

            $datas = execute_api_json('api/lookup/layanan_pendekatan','get');
            if($datas->code == 200){
                $this->data['layanan_pendekatan'] = $datas->data;
            }else{
                $this->data['layanan_pendekatan'] = [];
            }

            $datas = execute_api_json('api/lookup/layanan_ketersediaan','get');
            if($datas->code == 200){
                $this->data['layanan_ketersediaan'] = $datas->data;
            }else{
                $this->data['layanan_ketersediaan'] = [];
            }

            $datas = execute_api_json('api/lookup/periode_rawatan','get');
            if($datas->code == 200){
                $this->data['periode_rawatan'] = $datas->data;
            }else{
                $this->data['periode_rawatan'] = [];
            }
            $datas = execute_api_json('api/lookup/bentuk_layanan','get');
            if($datas->code == 200){
                $this->data['bentuk_layanan'] = $datas->data;
            }else{
                $this->data['bentuk_layanan'] = [];
            }

            $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
            return view('rehabilitasi.plrip.informasi_umum.add_informasiLembagaUmum',$this->data);
        }
    }

    public function deleteLembagaUmumPlrip(Request $request){
        $id = $request->input('id');
        if ($request->ajax()) {
            if($id){
                $id = $request->id;
                $data_request = execute_api('api/infolembaga/'.$id,'DELETE');
								$this->form_params['delete_id'] = $id;
								$trail['audit_menu'] = 'Rehabilitasi - Direktorat PLRIP - Informasi Umum Lembaga';
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
                $data_request =['status'=>'error','messages'=>'Gagal Menghapus Data'];
                return $data_request;
            }
        }
    }

    private function kelengkapan_LembagaUmumPlrip($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('rehab_infoumumlembaga')->where('id',$id)
                  ->select('nama','legalitas_lembaga_nama_notaris','legalitas_lembaga_noakta_notaris','legalitas_no_ijin_operasional', 'legalitas_lembaga_pemberi_ijin', 'legalitas_lembaga_wilayah_ijin', 'tgl_legalitas_masa_berlaku', 'legalitas_lembaga_npwp', 'alamat', 'alamat_kodepos','nomor_telp', 'email', 'penanggung_jawab','cp_nama','cp_telp','cp_email','status_layanan','layanan_pendekatan','layanan_ketersediaan','periode_rawatan','bentuk_layanan','periode_layanan_inap','periode_layanan_jalan','tarif_resmi_inap','tarif_resmi_jalan','sdm_manajemen','sdm_dokter_umum','sdm_spesialis_jiwa','sdm_dokter_gigi','sdm_spesialis_lain','sdm_psikolog','sdm_perawat','sdm_apoteker','sdm_analis','sdm_peksos','sdm_konselor','sdm_spsi','sdm_skm','sdm_sag','sdm_penunjang_administrasi','sdm_penunjang_logistik','sdm_penunjang_keamanan','kode_saranaprasarana','kapasitas_inap','kapasitas_jalan','pelatihan_assessment','pelatihan_adiksi','pelatihan_intervensi','pelatihan_fisiologi','pelatihan_tatalaksana','pelatihan_lainnya','pelatihan_lainnya_jumlah','jml_inap_sosial','jml_jalan_sosial','jml_inap_medis','jml_jalan_medis');
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
          $kelengkapan = execute_api_json('api/infolembaga/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/infolembaga/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

    /*
    | DIREKTORAT PASCA PLRIP |
    | Dokumen NSPK             |
    */
    public function dokumenNspkPlrip(Request $request){
        if($request->limit) {
            $this->limit = $request->limit;
        } else {
            $this->limit = config('app.limit');
        }
        $kondisi = '';
         if($request->isMethod('post')){
          $post = $request->except(['_token','tgl_from','tgl_to']);
          $tipe = $request->tipe;
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
          }else if( ($tipe == 'nama_nspk') || ($tipe == 'nomor_nsdpk' ) || ($tipe == 'peruntukan') ){
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
          $get = $request->except(['page','limit','tgl_from','tgl_to','kategori']);
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
              }else{
                $this->selected[$key] = $value;
                }
            }
            $this->selected['order'] = $request->order;
            $this->selected['limit'] = $request->limit;
            $this->data['filter'] = $this->selected;
          }
        }
        $this->data['title'] = "Dokumen NSPK PLRIP";
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
        $kategori = 'kategori=plrip';
        $datas = execute_api_json('api/nspk?'.$kategori.'&'.$limit.'&'.$offset.$kondisi,'get');
        if(($datas->status != 'error') && ($datas->code == 200) ){
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;
        }else{
            $this->data['data'] = [];
            $total_item = 0;
        }
        $this->data['delete_route'] = 'delete_dokumen_nspk_plrip';
        $this->data['path'] = $request->path();
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
        $this->data['route_name'] = $request->route()->getName();
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), '/'.$request->route()->getPrefix()."/dokumen_nspk_plrip",$filtering,$filter);
        $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
        return view('rehabilitasi.plrip.nspk.index_dokumenNspk',$this->data);
    }

    public function editdokumenNspkPlrip(Request $request){
        $id = $request->id;
        $datas = execute_api_json('api/nspk/'.$id,'get');
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }
        $this->data['file_path'] = config('app.plrip_nspk_file_path');
        $this->data['kode_direktorat'] = config('lookup.kode_direktorat');
        $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
        return view('rehabilitasi.plrip.nspk.edit_dokumenNspk',$this->data);
    }
    public function addDokumenNspkPlrip(Request $request){
         if($request->isMethod('post')){
            $this->form_params = $request->except(['_token']);
            $file_message = "";
            if($request->tgl_pembuatan){
                $date = explode('/', $request->tgl_pembuatan);
                $this->form_params['tgl_pembuatan'] = $date[2].'-'.$date[1].'-'.$date[0];
            }
            if($request->file('file_nspk')){
                $fileName = $request->file('file_nspk')->getClientOriginalName();
                $fileName = date('Y-m-d').'_'.$fileName;
                $extension = $request->file('file_nspk')->getClientOriginalExtension(); // getting image extension
                $directory = 'Rehabilitasi/NSPK/PLRIP/';
                $path = Storage::putFileAs($directory, $request->file('file_nspk'),$fileName);
                if($path){
                    $this->form_params['file_nspk'] = $fileName;
                }else{
                    $file_message = "Dengan File gagal diupload.";
                }
            }
            $this->form_params['kategori'] = 'plrip';
            $data_request = execute_api_json('api/nspk/','POST',$this->form_params);

						$trail['audit_menu'] = 'Rehabilitasi - Direktorat PLRIP - Dokumen NSPK';
						$trail['audit_event'] = 'post';
						$trail['audit_value'] = json_encode($this->form_params);
						$trail['audit_url'] = $request->url();
						$trail['audit_ip_address'] = $request->ip();
						$trail['audit_user_agent'] = $request->userAgent();
						$trail['audit_message'] = $data_request->comment;
						$trail['created_at'] = date("Y-m-d H:i:s");
						$trail['created_by'] = $request->session()->get('id');

						$qtrail = $this->inputtrail($request->session()->get('token'),$trail);

            if( ($data_request->code == 200) && ($data_request->status != 'error') ){
                $id = $data_request->data->eventID;
                $this->kelengkapan_DokumenNspkPlrip($id);
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Dokumen NSPK Berhasil Ditambahkan '. $file_message;
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Dokumen NSPK Gagal Ditambahkan';
            }
            return redirect(route('dokumen_nspk_plrip'))->with('status',$this->messages);
        }else{
            $this->data['current_category'] = 'plrip';
            $this->data['kode_direktorat'] = config('lookup.kode_direktorat');
            $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
            return view('rehabilitasi.plrip.nspk.add_dokumenNspk',$this->data);
        }
    }

    public function updateDokumenNspkPlrip(Request $request){
        $id = $request->id;
        $this->form_params = $request->except(['_token', 'id']);
        $file_message = "";
        if($request->tgl_pembuatan){
            $date = explode('/', $request->tgl_pembuatan);
            $this->form_params['tgl_pembuatan'] = $date[2].'-'.$date[1].'-'.$date[0];
        }
        if($request->file('file_nspk')){
            $fileName = $request->file('file_nspk')->getClientOriginalName();
            $fileName = date('Y-m-d').'_'.$fileName;
            $extension = $request->file('file_nspk')->getClientOriginalExtension(); // getting image extension
            $directory = 'Rehabilitasi/NSPK/PLRIP/';
            $path = Storage::putFileAs($directory, $request->file('file_nspk'),$fileName);
            if($path){
                $this->form_params['file_nspk'] = $fileName;
            }else{
                $file_message = "Dengan File gagal diupload.";
            }
        }
        $data_request = execute_api_json('api/nspk/'.$id,'PUT',$this->form_params);

				$trail['audit_menu'] = 'Rehabilitasi - Direktorat PLRIP - Dokumen NSPK';
				$trail['audit_event'] = 'put';
				$trail['audit_value'] = json_encode($this->form_params);
				$trail['audit_url'] = $request->url();
				$trail['audit_ip_address'] = $request->ip();
				$trail['audit_user_agent'] = $request->userAgent();
				$trail['audit_message'] = $data_request->comment;
				$trail['created_at'] = date("Y-m-d H:i:s");
				$trail['created_by'] = $request->session()->get('id');

				$qtrail = $this->inputtrail($request->session()->get('token'),$trail);

        $this->kelengkapan_DokumenNspkPlrip($id);
        if( ($data_request->code == 200) && ($data_request->status != 'error') ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Dokumen NSPK Berhasil Diperbarui '. $file_message;
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Dokumen NSPK Gagal Diperbarui';
        }
        return back()->with('status',$this->messages);
    }
    public function deleteDokumenNspkPlrip(Request $request){
        $id = $request->input('id');
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/nspk/'.$id,'DELETE');
						$this->form_params['delete_id'] = $id;
						$trail['audit_menu'] = 'Rehabilitasi - Direktorat PLRIP - Dokumen NSPK';
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
    }

    private function kelengkapan_DokumenNspkPlrip($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('rehab_nspk')->where('id',$id)
                  ->select('tgl_pembuatan','nama_nspk','nomor_nsdpk','peruntukan', 'file_nspk');
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
          $kelengkapan = execute_api_json('api/nspk/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/nspk/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }
    /*
    | DIREKTORAT PLRIP|
    | Kegiatan pelatihan           |
    */

    public function kegiatanPelatihanPLRIP(Request $request){
        if($request->limit) {
            $this->limit = $request->limit;
        } else {
            $this->limit = config('app.limit');
        }


        $kondisi = '';
        if($request->isMethod('get')){
            $get = $request->except(['page','_token','kategori']);
            if(count($get ) > 0 ) {
                foreach ($get as $key => $value) {
                    $kondisi .= "&".$key.'='.$value;
                    if(($key == 'start_from') || ($key == 'start_to') || ($key == 'end_from') || ($key == 'end_to')|| ($key == 'jumlah_from') || ($key == 'jumlah_to') ){
                        if($value){
                            $value = date('d/m/Y',strtotime($value));
                        }else{
                            $value = "";
                        }
                    }else{
                        $value = $value;
                    }
                    $this->selected[$key] =$value;
                }
                $this->selected['order'] = $request->order;
                $this->data['filter'] = $this->selected;
            }
        }else{
            $post = $request->except(['_token','start_to','start_from','end_to','end_from','jumlah_from','jumlah_to']);
            $tipe = trim($request->tipe);
            $kata_kunci = trim($request->kata_kunci);
            $pelaksana = trim($request->pelaksana);
            $start_from = trim($request->start_from);
            $start_to = trim($request->start_to);
            $end_from = trim($request->end_from);
            $end_to = trim($request->end_to);
            $jumlah_from = trim($request->jumlah_from);
            $jumlah_to = trim($request->jumlah_to);
            $order = trim($request->order);
            $status = trim($request->status);

            if($tipe){
                $this->selected['selected'] = $tipe;
                $this->selected['tipe'] = $tipe;
                $kondisi .= '&tipe='.$tipe;

            }
            if( ($tipe == 'tema') || ($tipe == 'nomor_sprint' )  ){
                $kondisi .= '&'.$tipe.'='.$request->kata_kunci;
                $this->selected[$tipe] = $request->kata_kunci;
            }else {
                if($tipe == 'periode_start'){
                    if($start_from){
                        $this->selected['start_from'] = $start_from;
                        $start_from = date('Y-m-d',strtotime(str_replace('/', '-',$start_from)));
                        $kondisi .= '&start_from='.$start_from;
                    }else if(!$start_from){
                        $this->selected['start_from'] = "";
                        $kondisi .= "";
                    }
                    if($start_to){
                        $this->selected['start_to'] = $start_to;
                        $start_to = str_replace('/', '-',$start_to);
                        $start_to = date('Y-m-d',strtotime($start_to));
                        $kondisi .= '&start_to='.$start_to;
                    }else if(!$start_to){
                        $this->selected['start_to'] = "";
                        $kondisi .= "";
                    }
                }else if($tipe == 'periode_end'){
                    if($end_from){
                        $this->selected['end_from'] = $end_from;
                        $end_from = str_replace('/', '-',$end_from);
                        $end_from = date('Y-m-d',strtotime($end_from));
                        $kondisi .= '&end_from='.$end_from;
                    }else if(!$end_from){
                        $this->selected['end_from'] = "";
                        $kondisi .= "";
                    }
                    if($end_to){
                        $this->selected['end_to'] = $end_to;
                        $end_to = str_replace('/', '-',$end_to);
                        $end_to = date('Y-m-d',strtotime($end_to));
                        $kondisi .= '&end_to='.$end_to;
                    }else if(!$end_to){
                        $this->selected['end_to'] = "";
                        $kondisi .= "";
                    }
                }else if($tipe == 'jumlah_peserta'){
                    if($jumlah_from){
                        $this->selected['jumlah_from'] = $jumlah_from;
                        $kondisi .='&jumlah_from='.$jumlah_from;
                    }else if(!$jumlah_from){
                        $this->selected['jumlah_from'] = "";
                        $kondisi .= "";
                    }

                    if($jumlah_to){
                        $this->selected['jumlah_to'] = $jumlah_to;
                        $kondisi .='&jumlah_to='.$jumlah_to;
                    }else if(!$jumlah_to){
                        $this->selected['jumlah_to'] = "";
                        $kondisi .= "";
                    }
                }else {
                    if(isset($post[$tipe])){
                        $kondisi .= '&'.$tipe.'='.$post[$tipe];
                        $this->selected[$tipe] = $post[$tipe];
                    }
                }
            }

            if($order){
                $this->selected['order'] = $order;
                $kondisi .= '&order='.$order;
            }else if(!$order){
                $this->selected['order'] = "desc";
                $kondisi .= "";
            }

            $this->selected['limit'] = $request->limit;
            $kondisi .= '&limit='.$request->limit;
            $this->data['filter'] = $this->selected;
        }

        $this->data['title'] = "Data Kegiatan Direktorat PLRIP";
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
        $kategori = 'kategori=plrip';

        $datas = execute_api_json('api/pelatihan?'.$kategori.'&'.$limit.'&'.$offset.$kondisi.'&id_wilayah='.$request->session()->get('wilayah'),'get');
        if(($datas->status != 'error') && ($datas->code == 200) ){
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;
        }else{
            $this->data['data'] = [];
            $total_item = 0;
        }
        $this->data['delete_route'] = 'delete_kegiatan_pelatihan_plrip';
        $this->data['path'] = $request->path();
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
        $this->data['route_name'] = $request->route()->getName();
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), '/'.$request->route()->getPrefix()."/kegiatan_pelatihan_plrip",$filtering,$filter);
        $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
        return view('rehabilitasi.plrip.kegiatan_pelatihan.index_kegiatanPelatihan',$this->data);
    }

    public function addkegiatanPelatihanPLRIP(Request $request){
        if($request->isMethod('post')){
            $this->form_params = $request->except(['_token']);
            $file_message = "";
            if($request->tgl_sprint){
                $date = explode('/', $request->tgl_sprint);
                $this->form_params['tgl_sprint'] = $date[2].'-'.$date[1].'-'.$date[0];
            }
            if($request->tgl_dilaksanakan_mulai){
                $date = explode('/', $request->tgl_dilaksanakan_mulai);
                $this->form_params['tgl_dilaksanakan_mulai'] = $date[2].'-'.$date[1].'-'.$date[0];
            }

            if($request->tgl_dilaksanakan_selesai){
                $date = explode('/', $request->tgl_dilaksanakan_selesai);
                $this->form_params['tgl_dilaksanakan_selesai'] = $date[2].'-'.$date[1].'-'.$date[0];
            }

            if($request->file('file_materi')){
                $fileName = $request->file('file_materi')->getClientOriginalName();
                $fileName = date('Y-m-d').'_'.$fileName;
                $extension = $request->file('file_materi')->getClientOriginalExtension(); // getting image extension
                $directory = 'Rehabilitasi/KegiatanPelatihan/PLRIP/';
                $path = Storage::putFileAs($directory, $request->file('file_materi'),$fileName);
                if($path){
                    $this->form_params['file_materi'] = $fileName;
                }else{
                    $file_message = "Dengan File gagal diupload.";
                }
            }
            $this->form_params['created_by_username'] = getPelaksana($request->id_pelaksana);
            $this->form_params['kategori'] = 'plrip';
            $data_request = execute_api_json('api/pelatihan/','POST',$this->form_params);

						$trail['audit_menu'] = 'Rehabilitasi - Direktorat PLRIP - Kegiatan';
						$trail['audit_event'] = 'post';
						$trail['audit_value'] = json_encode($this->form_params);
						$trail['audit_url'] = $request->url();
						$trail['audit_ip_address'] = $request->ip();
						$trail['audit_user_agent'] = $request->userAgent();
						$trail['audit_message'] = $data_request->comment;
						$trail['created_at'] = date("Y-m-d H:i:s");
						$trail['created_by'] = $request->session()->get('id');

						$qtrail = $this->inputtrail($request->session()->get('token'),$trail);

            if( ($data_request->code == 200) && ($data_request->status != 'error') ){
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Data Kegiatan PLRIP Berhasil Ditambahkan '. $file_message;
                $id = $data_request->data->id;
                $this->kelengkapan_kegiatanPelatihanPLRIP($id);
                return redirect(route('edit_kegiatan_pelatihan_plrip',$id))->with('status',$this->messages);
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Data Kegiatan PLRIP Gagal Ditambahkan';
                return redirect(route('kegiatan_pelatihan_plrip'))->with('status',$this->messages);
            }
        }else{
            $datas = execute_api_json('api/lookup/rehab_jenis_kegiatan','GET');
            if( ($datas->code == 200) && ($datas->status != 'error')){
                $this->data['jenis_kegiatan'] = $datas->data;
            }else{
                $this->data['jenis_kegiatan'] = [];
            }
            $this->data['title']="Form Tambah Direktorat PLRIP";
            $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
            return view('rehabilitasi.plrip.kegiatan_pelatihan.add_kegiatanPelatihan',$this->data);
        }
    }
    public function editkegiatanPelatihanPLRIP(Request $request){
        $id = $request->id;
        $check_page = checkCurrentPage($id,'plrip');
        if($check_page){
            $datas = execute_api_json('api/pelatihan/'.$id,'get');
            if(($datas->code == 200) && ($datas->status != 'error')){
                $this->data['data'] = $datas->data;
            }else{
                $this->data['data'] = [];
            }

            if($request->page){
                $current_page = $request->page;
                $start_number = ($this->limit * ($request->page -1 )) + 1;
            }else{
                $current_page = 1;
                $start_number = $current_page;
            }

            $this->limit = config('app.limit');
            $limit = 'limit='.$this->limit;
            $offset = 'page='.$current_page;
            $parent_id = 'parent_id='.$id;
            $datas = execute_api_json('api/pelatihanpeserta?'.$parent_id.'&'.$offset.'&'.$limit,'get');
            if(($datas->code == 200) && ($datas->status != 'error')){
                $this->data['peserta'] = $datas->data;
            }else{
                $this->data['peserta'] = [];
            }
            // dd(count($this->data['peserta']));
            $total_item = $datas->paginate->totalpage * $this->limit;
            $datas = execute_api_json('api/lookup/rehab_jenis_kegiatan','GET');
            if( ($datas->code == 200) && ($datas->status != 'error')){
                $this->data['jenis_kegiatan'] = $datas->data;
            }else{
                $this->data['jenis_kegiatan'] = [];
            }
            $this->data['pagination'] = ajax_pagination($current_page,$total_item, $this->limit, config('app.page_ellipsis'),"/".$request->route()->getPrefix()."/index_peserta_pelatihan_pascarehabilitasi/".$id."/%d");
            $this->data['delete_route'] = 'delete_peserta_pelatihan_plrip';
            $this->data['file_path'] = config('app.plrip_kegiatan_file_path');
            $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
            return view('rehabilitasi.plrip.kegiatan_pelatihan.edit_kegiatanPelatihan',$this->data);
        }else{
            return view('errors.unauthorized');
        }


    }

    public function updateKegiatanPelatihanPlrip(Request $request){
        $id = $request->id;
        $this->form_params = $request->except(['_token', 'id']);
        $file_message = "";
        if($request->tgl_sprint){
            $date = explode('/', $request->tgl_sprint);
            $this->form_params['tgl_sprint'] = $date[2].'-'.$date[1].'-'.$date[0];
        }

        if($request->tgl_dilaksanakan_mulai){
            $date = explode('/', $request->tgl_dilaksanakan_mulai);
            $this->form_params['tgl_dilaksanakan_mulai'] = $date[2].'-'.$date[1].'-'.$date[0];
        }

        if($request->tgl_dilaksanakan_selesai){
            $date = explode('/', $request->tgl_dilaksanakan_selesai);
            $this->form_params['tgl_dilaksanakan_selesai'] = $date[2].'-'.$date[1].'-'.$date[0];
        }
        if($request->file('file_materi')){
            $fileName = $request->file('file_materi')->getClientOriginalName();
            $fileName = date('Y-m-d').'_'.$fileName;
            $extension = $request->file('file_materi')->getClientOriginalExtension(); // getting image extension
            $directory = 'Rehabilitasi/KegiatanPelatihan/PLRIP/';
            $path = Storage::putFileAs($directory, $request->file('file_materi'),$fileName);
            if($path){
                $this->form_params['file_materi'] = $fileName;
            }else{
                $file_message = "Dengan File gagal diupload.";
            }
        }
        $this->form_params['created_by_username'] = getPelaksana($request->id_pelaksana);
        $data_request = execute_api_json('api/pelatihan/'.$id,'PUT',$this->form_params);

				$trail['audit_menu'] = 'Rehabilitasi - Direktorat PLRIP - Kegiatan';
				$trail['audit_event'] = 'put';
				$trail['audit_value'] = json_encode($this->form_params);
				$trail['audit_url'] = $request->url();
				$trail['audit_ip_address'] = $request->ip();
				$trail['audit_user_agent'] = $request->userAgent();
				$trail['audit_message'] = $data_request->comment;
				$trail['created_at'] = date("Y-m-d H:i:s");
				$trail['created_by'] = $request->session()->get('id');

				$qtrail = $this->inputtrail($request->session()->get('token'),$trail);

        if( ($data_request->code == 200) && ($data_request->status != 'error') ){
            // $id = $data_request->data->eventID;
            $this->kelengkapan_KegiatanPelatihanPlrip($id);
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Kegiatan PLRIP Berhasil Diperbarui '. $file_message;
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Kegiatan PLRIP Gagal Diperbarui';
        }
        return back()->with('status',$this->messages);
    }

    public function deleteKegiatanPelatihanPlrip(Request $request){
        $id = $request->input('id');
        if ($request->ajax()) {
            if($id){
                $id = $request->id;
                $data_request = execute_api('api/pelatihan/'.$id,'DELETE');
								$this->form_params['delete_id'] = $id;
								$trail['audit_menu'] = 'Rehabilitasi - Direktorat PLRIP - Kegiatan';
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
                $data_request =['status'=>'error','messages'=>'Gagal Menghapus Data'];
                return $data_request;
            }
        }
    }

    private function kelengkapan_KegiatanPelatihanPlrip($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('rehab_pelatihan')->where('id',$id)
                  ->select('jenis_kegiatan','tema','id_pelaksana','nomor_sprint', 'tgl_sprint','tgl_dilaksanakan_mulai','tgl_dilaksanakan_selesai','tempat','jumlah_narasumber','jumlah_peserta','file_materi');
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
          $kelengkapan = execute_api_json('api/pelatihan/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/pelatihan/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

    public function updatePesertaPelatihanPlrip(Request $request){
        if($request->ajax()){
            $id = $request->id_detail;
            $this->form_params = $request->except(['_token','id_detail','index']);
            $data_request = execute_api_json('api/pelatihanpeserta/'.$id,'PUT',$this->form_params);

						$trail['audit_menu'] = 'Rehabilitasi - Direktorat PLRIP - Kegiatan Peserta';
						$trail['audit_event'] = 'put';
						$trail['audit_value'] = json_encode($this->form_params);
						$trail['audit_url'] = $request->url();
						$trail['audit_ip_address'] = $request->ip();
						$trail['audit_user_agent'] = $request->userAgent();
						$trail['audit_message'] = $data_request->comment;
						$trail['created_at'] = date("Y-m-d H:i:s");
						$trail['created_by'] = $request->session()->get('id');

						$qtrail = $this->inputtrail($request->session()->get('token'),$trail);


            if(($data_request->code == 200)&& ($data_request->status != "error") ){
                $html  = "<tr>";
                $html .= '<td></td>';
                $html .= '<td>'.$request->nama_peserta.'</td>';
                $html .= '<td>'.$request->nomor_identitas.'</td>';
                $html .= '<td>'.( ($request->kode_jeniskelamin == "P") ? 'Perempuan' : ( ($request->kode_jeniskelamin == "L") ? 'Laki-Laki' : '')).'</td>';
                $html .= '<td>'.$request->asal_instansilembaga.'</td>';
                $html .= '<td class="actionTable">
                            <button type="button" class="btn btn-primary button-edit" data-target="'.$id.'" onClick="open_modalEditPeserta(event,this,\'/rehabilitasi/dir_plrip/edit_peserta_pelatihan_plrip/\',\'modal_edit_form\')"><i class="fa fa-pencil"></i></button>
                            <button type="button" class="btn btn-primary button-action" data-target="'.$id.'" onClick="delete_row_peserta(event,this)"><i class="fa fa-trash"></i></button>
                        </td>';
                $html  .= "</tr>";
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Data Peserta Pelatihan DIR PLRIP Berhasil Diperbarui';
                $this->messages['data_return'] = $html;
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Data Peserta Pelatihan DIR PLRIP Gagal Diperbarui';
                $this->messages['data_return'] = null;
            }
            return response()->json($this->messages);
        }
    }
    public function deletePesertaPelatihanPlrip(Request $request){
        $id = $request->input('id');
        if ($request->ajax()) {
            $html = "";
            $paginate = "";
            $id = $request->id;
            $parent_id = $request->parent_id;
            $current_page = 1;
            $current_page = $request->current_page;
            $data_request = execute_api('api/pelatihanpeserta/'.$id,'DELETE');
						$this->form_params['delete_id'] = $id;
						$trail['audit_menu'] = 'Rehabilitasi - Direktorat PLRIP- Kegiatan Peserta';
						$trail['audit_event'] = 'delete';
						$trail['audit_value'] = json_encode($this->form_params);
						$trail['audit_url'] = $request->url();
						$trail['audit_ip_address'] = $request->ip();
						$trail['audit_user_agent'] = $request->userAgent();
						$trail['audit_message'] = $data_request['comment'];
						$trail['created_at'] = date("Y-m-d H:i:s");
						$trail['created_by'] = $request->session()->get('id');

						$qtrail = $this->inputtrail($request->session()->get('token'),$trail);

            $offset = 'page='.$current_page;
            $kategori = 'parent_id='.$parent_id;
            $limit = 'limit='.config('app.limit');
            if($request->current_page){
                $current_page = $request->current_page;
                $start_number = ($this->limit * ($request->current_page -1 )) + 1;
            }else{
                $current_page = 1;
                $start_number = $current_page;
            }
          $current_data = execute_api_json('api/pelatihanpeserta?'.$kategori.'&'.$limit.'&'.$offset,'get');
          if($current_data->code == 200){
            $datas = $current_data->data;
            if(count($datas)>0){

                $j = $start_number;
                foreach($datas as $d){
                    $html .= "<tr>";
                    $html .= "<td>".$j."</td>";
                    $html .= "<td>".$d->nama_peserta."</td>";
                    $html .= "<td>".$d->nomor_identitas."</td>";
                    $html .= "<td>". ( ($d->kode_jeniskelamin == "P") ? 'Perempuan' : ( ($d->kode_jeniskelamin == "L") ? 'Laki-Laki' : ''))."</td>";
                    $html .= "<td>".$d->asal_instansilembaga."</td>";
                    $html .= '<td class="actionTable">
                                <button type="button" class="btn btn-primary button-edit" data-target="'.$d->id_detail.'" onClick="open_modalEditPeserta(event,this,\'/rehabilitasi/dir_plrip/edit_peserta_pelatihan_plrip/\',\'modal_edit_form\')"><i class="fa fa-pencil"></i></button>
                                <button type="button" class="btn btn-primary button-action" data-target="'.$d->id_detail.'" onClick="delete_row_peserta(event,this)"><i class="fa fa-trash"></i></button>
                            </td>';
                    $html .= "</tr>";
                    $j = $j+1;
                }
                $total_item = $current_data->paginate->totalpage * config('app.limit');
                if($total_item > config('app.limit')) {
                    $paginate = ajax_pagination($current_page,$total_item, config('app.limit'), config('app.page_ellipsis'),"/".$request->route()->getPrefix()."/index_peserta_pelatihan_pascarehabilitasi/".$id."/%d");
                }else{
                    $paginate = "";
                }

            }
            else{
                $html = "<tr><td colspan=6> <p class='alert-warning'>Data Peserta Kegiatan Pendidikan dan Pelatihan Pada DIR PLRIP Belum Tersedia </p></td> </tr>";
            }

          }else{
            $html = "<tr><td colspan=6> <p class='alert-warning'>Data Peserta Kegiatan Pendidikan dan Pelatihan Pada DIR PLRIP Belum Tersedia </p></td> </tr>";
          }

          $data_request['data_return'] = $html;
          $data_request['pagination'] = $paginate ;
          return  $data_request;
        }
    }
    public function addPesertaPelatihanPlrip(Request $request){
    if($request->ajax()){
            $id = $request->id_header;
            $data_request = "";
            $this->form_params = $request->except(['_token','parent_id']);
            $data_request = execute_api_json('api/pelatihanpeserta/','POST',$this->form_params);

						$trail['audit_menu'] = 'Rehabilitasi - Direktorat PLRIP - Kegiatan Peserta';
						$trail['audit_event'] = 'post';
						$trail['audit_value'] = json_encode($this->form_params);
						$trail['audit_url'] = $request->url();
						$trail['audit_ip_address'] = $request->ip();
						$trail['audit_user_agent'] = $request->userAgent();
						$trail['audit_message'] = $data_request->comment;
						$trail['created_at'] = date("Y-m-d H:i:s");
						$trail['created_by'] = $request->session()->get('id');

						$qtrail = $this->inputtrail($request->session()->get('token'),$trail);

            // $data_request = json_decode(json_encode($data_request), FALSE);
            if(($data_request->code == 200)&& ($data_request->status != "error") ){
                $id_detail = $data_request->data->id;
                $current_page = 1;
                $parent_id = $id;
                $offset = 'page=1';
                $kategori = 'parent_id='.$parent_id;
                $limit = 'limit='.config('app.limit');
                $start_number = $current_page;
                $current_data = execute_api_json('api/pelatihanpeserta?'.$kategori.'&'.$limit.'&'.$offset,'get');
                if($current_data->code == 200 && $current_data->status != 'error'){
                    $datas = $current_data->data;
                    if(count($datas)>0){
                        $j = $start_number;
                        $html = "";
                        foreach($datas as $d){
                            $html .= "<tr>";
                            $html .= "<td>".$j."</td>";
                            $html .= "<td>".$d->nama_peserta."</td>";
                            $html .= "<td>".$d->nomor_identitas."</td>";
                            $html .= '<td>'.( ($d->kode_jeniskelamin == "P") ? 'Perempuan' : ( ($d->kode_jeniskelamin == "L") ? 'Laki-Laki' : '')).'</td>';
                            $html .= "<td>".$d->asal_instansilembaga."</td>";
                            $html .= '<td class="actionTable">
                                        <button type="button" class="btn btn-primary button-edit" data-target="'.$d->id_detail.'" onClick="open_modalEditPeserta(event,this,\'/rehabilitasi/dir_plrip/edit_peserta_pelatihan_plrip/\',\'modal_edit_form\')"><i class="fa fa-pencil"></i></button>
                                        <button type="button" class="btn btn-primary button-action" data-target="'.$d->id_detail.'" onClick="delete_row_peserta(event,this)"><i class="fa fa-trash"></i></button>
                                    </td>';
                            $html .= "</tr>";
                            $j = $j+1;
                        }
                        $total_item = $current_data->paginate->totalpage * config('app.limit');
                        if($total_item > config('app.limit')) {
                            $paginate = ajax_pagination($current_page,$total_item, config('app.limit'), config('app.page_ellipsis'),"/".$request->route()->getPrefix()."/index_peserta_pelatihan_plrip/".$parent_id."/%d");
                        }else{
                            $paginate = "";
                        }

                    }
                    else{
                        $html = "<tr><td colspan=6> <p class='alert-warning'>Data Peserta Kegiatan Pendidikan dan Pelatihan Pada Balai Belum Tersedia </p></td> </tr>";
                    }
                }else{
                      $html = "<tr><td colspan=6> <p class='alert-warning'>Data Peserta Kegiatan Pendidikan dan Pelatihan Pada Balai Belum Tersedia </p></td> </tr>";
                }
                // $html  = "<tr>";
                // $html .= '<td></td>';
                // $html .= '<td>'.$request->nama_peserta.'</td>';
                // $html .= '<td>'.$request->nomor_identitas.'</td>';
                // $html .= '<td>'.( ($request->kode_jeniskelamin == "P") ? 'Perempuan' : ( ($request->kode_jeniskelamin == "L") ? 'Laki-Laki' : '')).'</td>';
                // $html .= '<td>'.$request->asal_instansilembaga.'</td>';
                // $html .= '<td class="actionTable">
                //             <button type="button" class="btn btn-primary button-edit" data-target="'.$data_request->data->id.'" onClick="open_modalEditPeserta(event,this,\'/rehabilitasi/dir_plrip/edit_peserta_pelatihan_plrip/\',\'modal_edit_form\')"><i class="fa fa-pencil"></i></button>
                //             <button type="button" class="btn btn-primary button-action" data-target="'.$data_request->data->id.'" onClick="ajax_delete(event,this)"><i class="fa fa-trash"></i></button>
                //         </td>';
                // $html  .= "</tr>";
                // $count = $data_request->data->count;
                // $paginate= ajax_pagination(1,$count, config('app.limit'), config('app.page_ellipsis'),"/".$request->route()->getPrefix()."/index_peserta_pelatihan_pascarehabilitasi/".$id."/%d");

                // $this->result_data[]
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Data Peserta Pelatihan PLRIP Berhasil Ditambahkan';
                $this->messages['data_return'] = $html;
                $this->messages['pagination'] = $paginate;


            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Data Peserta Pelatihan PLRIP Gagal Ditambahkan';
                $this->messages['data_return'] = null;
            }
             $this->messages['data'] = $kategori;
            return response()->json($this->messages );
        }
    }

    public function editPesertaPelatihanPlrip(Request $request){
        if($request->ajax()){
            $id = $request->id;
            $this->form_params = $request->except(['_token','id']);
            $data_request = execute_api_json('api/pelatihanpeserta/'.$id,'GET');
            if(($data_request->code == 200)&& ($data_request->status != "error") ){
                $this->messages['status'] = 'success';
                $this->messages['data'] = $data_request->data;
            }else{
                $this->messages['status'] = 'error';
                $this->messages['data'] = [];
            }
            return response()->json($this->messages);

        }
    }
    public function indexPesertaKegiatanPelatihanPlrip(Request $request){
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
        $parent_id = 'parent_id='.$parent_id;

        $datas = execute_api_json('api/pelatihanpeserta?'.$parent_id.'&'.$limit.'&'.$offset,'get');
        if($datas->code == 200){
            $this->data['status'] = 'success';
            if(count($datas->data)>=1){
                $html = "";
                $j = $start_number;
                foreach($datas->data as $p){
                    $html .= '<tr>';
                    $html .= '<td>'.$j.'</td>';
                    $html .= '<td>'.$p->nama_peserta.'</td>';
                    $html .= '<td>'.$p->nomor_identitas.'</td>';
                    $html .= '<td> '. ( ($p->kode_jeniskelamin == "P") ? 'Perempuan' : ( ($p->kode_jeniskelamin == "L") ? 'Laki-Laki' : '')).'</td>';
                    $html .= '<td>'.$p->asal_instansilembaga.'</td>';
                    $html .= '<td class="actionTable">';
                    $html .= '<button type="button" class="btn btn-primary button-edit" data-target="'.$p->id_detail.'" onClick="open_modalEditPeserta(event,this,\'/rehabilitasi/dir_pasca/edit_peserta_pelatihan_plrip/\',\'modal_edit_form\')"><i class="fa fa-pencil"></i></button>';
                    $html .= '<button type="button" class="btn btn-primary button-action" data-target="'.$p->id_detail.'" onClick="delete_row_peserta(event,this)"><i class="fa fa-trash"></i></button>';
                    $html .= '</td>';
                    $html .= '</tr>';
                    $j = $j + 1;
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
    /*
    | DIREKTORAT PLRIP|
    | Penilaianlembaga             |
    */

    public function penilaianLembagaPlrip(Request $request){
        $this->limit = config('app.limit');
        $this->data['title'] = "Penilaian Lembaga PLRIP";
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
        $tipe = 'tipe='.'plrip';
        $datas = execute_api_json('api/penilaianlembaga?'.$tipe.'&'.$limit.'&'.$offset,'get');
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }
        $this->data['delete_route'] = 'delete_penilaian_lembaga_plrip';
        $this->data['path'] = $request->path();
        $this->data['route_name'] = $request->route()->getName();
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;
        $total_item = $datas->paginate->totalpage * $this->limit;
        $this->data['pagination'] = pagination($current_page,$total_item, $this->limit, config('app.page_ellipsis'), "/".$request->route()->getPrefix()."/".$request->route()->getName()."/%d");
        $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
        return view('rehabilitasi.plrip.penilaian_lembaga.index_penilaianLembaga',$this->data);

    }
    public function editPenilaianLembagaPlrip(Request $request){
        $id = $request->id;
        $this->data['penilaian'] = config('lookup.penilaian_lembaga');
        $datas = execute_api_json('api/penilaianlembaga/'.$id,'get');

        if($datas->code == 200){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }
        $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
        return view('rehabilitasi.plrip.penilaian_lembaga.edit_penilaianLembaga',$this->data);

    }
    public function addPenilaianLembagaPlrip(Request $request){
        if($request->isMethod('post')){
            $this->form_params   = $request->except(['_token']);
            $this->form_params['tipe'] = 'plrip';
            $data_request = execute_api_json('api/penilaianlembaga/','POST',$this->form_params);
            if(($data_request->code == 200)&& ($data_request->status != "error") ){
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Data Info Lembaga Umum PLRIP Berhasil Diperbarui';
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Data Info Lembaga Umum PLRIP Gagal Diperbarui';
            }
            return redirect(route('penilaian_lembaga_plrip'))->with('status', $this->messages);
        }else{
            $this->data['penilaian'] = config('lookup.penilaian_lembaga');
            $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
            return view('rehabilitasi.plrip.penilaian_lembaga.add_penilaianLembaga',$this->data);
        }
    }
    public function updatePenilaianLembagaPlrip(Request $request){
        $id = $request->id;
        $this->form_params = $request->except(['_token','id']);
        $data_request = execute_api_json('api/penilaianlembaga/'.$id,'PUT',$this->form_params);

        if(($data_request->code == 200)&& ($data_request->status != "error") ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Info Lembaga Umum PLRIP Berhasil Diperbarui';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Info Lembaga Umum PLRIP Gagal Diperbarui';
        }
        return back()->with('status', $this->messages);
    }
    public function deletePenilaianLembagaPlrip(Request $request){
        $id = $request->input('id');
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/penilaianlembaga/'.$id,'DELETE');
            return $data_request;
        }
    }

    /*
    | DIREKTORAT PASCA REHABILITASI |
    | info lembaga umum             |
    */
    public function indexLembagaUmumPasca(Request $request){
        if($request->limit) {
            $this->limit = $request->limit;
        } else {
            $this->limit = config('app.limit');
        }


        $kondisi = '';
        if($request->isMethod('post')){
          $post = $request->except(['_token']);
          $tipe = $request->tipe;
          $tgl_from = $request->tgl_from;
          $tgl_from = $request->tgl_from;
          $tgl_to = $request->tgl_to;
          if($tipe){
            $kondisi .= '&tipe='.$tipe;
            $this->selected['tipe'] = $tipe;
          }
          if($tipe == 'nama' || $tipe == 'alamat'  || $tipe == 'cp_nama'){
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
          $get = $request->except(['page','limit','kategori']);
          $tipe = $request->tipe;
          if(count($get)>0){
            if($tipe){
              $this->selected['tipe']  = $tipe;
            }
            foreach ($get as $key => $value) {
                $kondisi .= "&".$key.'='.$value;
                $this->selected[$key] = $value;
            }

            $this->selected['order'] = $request->order;
            $this->selected['limit'] = $request->limit;
            $this->data['filter'] = $this->selected;
          }
        }
        $this->data['title'] = "Informasi Lembaga Umum Direktorat Pascarehabilitasi";
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
        $kategori = 'kategori=pasca';
        $datas = execute_api_json('api/infolembaga?'.$kategori.'&'.$limit.'&'.$offset.$kondisi,'get');
        if(($datas->status != 'error') && ($datas->code == 200) ){
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;
        }else{
            $this->data['data'] = [];
            $total_item = 0;
        }
        $this->data['path'] = $request->path();
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
        $datas = execute_api('api/lookup/bentuk_layanan','get');
        if(($datas['code'] == 200) && ($datas['status'] != 'error')){
            $this->data['bentuk_layanan'] = $datas['data'];
        }else{
            $this->data['bentuk_layanan'] = [];
        }
        $this->data['delete_route'] = 'delete_informasi_lembaga_umum_pascarehabilitasi';
        $this->data['route_name'] = $request->route()->getName();
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), '/'.$request->route()->getPrefix()."/informasi_lembaga_umum_pascarehabilitasi",$filtering,$filter);
        $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
        return view('rehabilitasi.pasca.informasi_umum.index_informasiUmum',$this->data);
    }

     public function editLembagaUmumPasca(Request $request){
        $id = $request->id;
        $datas = execute_api_json('api/infolembaga/'.$id ,'get');
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }

        $datas = execute_api_json('api/lookup/sumberbiaya_lembaga_rehab','get');
        if($datas->code == 200){
            $this->data['sumberbiaya'] = $datas->data;
        }else{
            $this->data['sumberbiaya'] = [];
        }

        $datas = execute_api_json('api/lookup/model_layanan_lembaga','get');
        if($datas->code == 200){
            $this->data['model_layanan'] = $datas->data;
        }else{
            $this->data['model_layanan'] = [];
        }

        $datas = execute_api_json('api/lookup/prasarana_lembaga_rehab','get');
        if($datas->code == 200){
            $this->data['prasarana'] = $datas->data;
        }else{
            $this->data['prasarana'] = [];
        }

        $datas = execute_api_json('api/lookup/periode_rawatan','get');
        if($datas->code == 200){
            $this->data['periode_rawatan'] = $datas->data;
        }else{
            $this->data['periode_rawatan'] = [];
        }

        $datas = execute_api_json('api/lookup/bentuk_layanan_rehab','get');
        if($datas->code == 200){
            $this->data['bentuk_layanan'] = $datas->data;
        }else{
            $this->data['bentuk_layanan'] = [];
        }

        $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
        return view('rehabilitasi.pasca.informasi_umum.edit_informasiUmum',$this->data);
    }

    public function addLembagaUmumPasca(Request $request){
        if($request->isMethod('post')){
            $token = $request->session()->get('token');
            $this->form_params = $request->except(['_token']);
            if($request->kode_saranaprasarana){
                $this->form_params['kode_saranaprasarana'] = implode(',',$request->kode_saranaprasarana);
            }
            if($request->tgl_legalitas_masa_berlaku){
                $date = explode('/', $request->tgl_legalitas_masa_berlaku);
                $this->form_params['tgl_legalitas_masa_berlaku'] = $date[2].'-'.$date[1].'-'.$date[0];
            }
            $this->form_params['tarif_resmi_inap'] = str_replace(',', '', $request->tarif_resmi_inap);
            $this->form_params['tarif_resmi_jalan'] = str_replace(',', '', $request->tarif_resmi_jalan);
            $this->form_params['kategori'] = 'pasca';
            if($request->bentuk_layanan){
                $this->form_params['bentuk_layanan'] = json_encode($request->bentuk_layanan);
            }

            $data_request = execute_api_json('api/infolembaga/','POST',$this->form_params);

						$trail['audit_menu'] = 'Rehabilitasi - Direktorat PascaRehabilitasi - Informasi Umum Lembaga';
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
                $id = $data_request->data->eventID;
                // dd($data_request);
                $this->kelengkapan_LembagaUmumPasca($id);
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Data Info Lembaga Umum PascaRehabilitasi Berhasil Ditambah';
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Data Info Lembaga Umum PascaRehabilitasi Gagal Ditambah';
            }
            return redirect(route('informasi_lembaga_umum_pascarehabilitasi'))->with('status', $this->messages);
        }else{
            $datas = execute_api_json('api/lookup/sumberbiaya_lembaga_rehab','get');
            if($datas->code == 200){
                $this->data['sumberbiaya'] = $datas->data;
            }else{
                $this->data['sumberbiaya'] = [];
            }

            $datas = execute_api_json('api/lookup/model_layanan_lembaga','get');
            if($datas->code == 200){
                $this->data['model_layanan'] = $datas->data;
            }else{
                $this->data['model_layanan'] = [];
            }

            $datas = execute_api_json('api/lookup/prasarana_lembaga_rehab','get');
            if($datas->code == 200){
                $this->data['prasarana'] = $datas->data;
            }else{
                $this->data['prasarana'] = [];
            }

            $datas = execute_api_json('api/lookup/periode_rawatan','get');
            if($datas->code == 200){
                $this->data['periode_rawatan'] = $datas->data;
            }else{
                $this->data['periode_rawatan'] = [];
            }

            $datas = execute_api_json('api/lookup/bentuk_layanan_rehab','get');
            if($datas->code == 200){
                $this->data['bentuk_layanan'] = $datas->data;
            }else{
                $this->data['bentuk_layanan'] = [];
            }


            $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
            return view('rehabilitasi.pasca.informasi_umum.add_informasiUmum',$this->data);
        }
    }

    public function deleteLembagaUmumPasca(Request $request){
        $id = $request->input('id');
        if ($request->ajax()) {
            if($id){
                $id = $request->id;
                $data_request = execute_api('api/infolembaga/'.$id,'DELETE');
								$this->form_params['delete_id'] = $id;
								$trail['audit_menu'] = 'Rehabilitasi - Direktorat Pascarehabilitasi - Informasi Umum Lembaga';
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
                $data_request =['status'=>'error','messages'=>'Gagal Menghapus Data'];
                return $data_request;
            }
        }
    }
    public function updateLembagaUmumPasca(Request $request){
        $id = $request->id;
        $token = $request->session()->get('token');
        $this->form_params = $request->except(['_token', 'id']);
        if($request->kode_saranaprasarana){
            $this->form_params['kode_saranaprasarana'] = implode(',',$request->kode_saranaprasarana);
        }

        if( $request->tgl_legalitas_masa_berlaku){
            $date = explode('/', $request->tgl_legalitas_masa_berlaku);
            $this->form_params['tgl_legalitas_masa_berlaku'] = $date[2].'-'.$date[1].'-'.$date[0];
        }
        $this->form_params['tarif_resmi_inap'] = str_replace(',', '', $request->tarif_resmi_inap);
        $this->form_params['tarif_resmi_jalan'] = str_replace(',', '', $request->tarif_resmi_jalan);

        if($request->bentuk_layanan){
            $this->form_params['bentuk_layanan'] = json_encode($request->bentuk_layanan);
        }

        $data_request = execute_api_json('api/infolembaga/'.$id,'PUT',$this->form_params);

				$trail['audit_menu'] = 'Rehabilitasi - Direktorat Pascarehabilitasi - Informasi Umum Lembaga';
				$trail['audit_event'] = 'put';
				$trail['audit_value'] = json_encode($this->form_params);
				$trail['audit_url'] = $request->url();
				$trail['audit_ip_address'] = $request->ip();
				$trail['audit_user_agent'] = $request->userAgent();
				$trail['audit_message'] = $data_request->comment;
				$trail['created_at'] = date("Y-m-d H:i:s");
				$trail['created_by'] = $request->session()->get('id');

				$qtrail = $this->inputtrail($request->session()->get('token'),$trail);

        if(($data_request->code == 200)&& ($data_request->status != "error") ){
            // $id = $data_request->data->eventID;
                $this->kelengkapan_LembagaUmumPasca($id);
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Info Lembaga Umum PascaRehabilitasi Berhasil Diperbarui';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Info Lembaga Umum PascaRehabilitasi Gagal Diperbarui';
        }
        return back()->with('status', $this->messages);
    }

     private function kelengkapan_LembagaUmumPasca($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('rehab_infoumumlembaga')->where('id',$id)
                  ->select('nama','alamat','alamat_kodepos','nomor_telp','email','penanggung_jawab','cp_nama','cp_telp','cp_email','periode_rawatan','bentuk_layanan','periode_layanan_inap','periode_layanan_jalan','tarif_resmi_inap','tarif_resmi_jalan','sdm_manajemen','sdm_dokter_umum','sdm_spesialis_jiwa','sdm_dokter_gigi','sdm_spesialis_lain','sdm_psikolog','sdm_perawat','sdm_apoteker','sdm_analis','sdm_peksos','sdm_konselor','sdm_spsi','sdm_skm','sdm_sag','sdm_penunjang_administrasi','sdm_penunjang_logistik','sdm_penunjang_keamanan','kode_saranaprasarana','kapasitas_inap','kapasitas_jalan','pelatihan_assessment','pelatihan_adiksi','pelatihan_intervensi','pelatihan_fisiologi','pelatihan_tatalaksana','pelatihan_lainnya','pelatihan_lainnya_jumlah','jml_pasca_reg','jml_pasca_int','jml_pasca_lnj');
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
          $kelengkapan = execute_api_json('api/infolembaga/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/infolembaga/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

    /*
    |--------------------------------------------------------------------------
    |   Rehabilitasi : Dokumen NSPK PascaRehabilitasi
    |--------------------------------------------------------------------------
    |
    */
    public function dokumenNspkPasca(Request $request){
        if($request->limit) {
            $this->limit = $request->limit;
        } else {
            $this->limit = config('app.limit');
        }
        $kondisi = '';
         if($request->isMethod('post')){
          $post = $request->except(['_token','tgl_from','tgl_to']);
          $tipe = $request->tipe;
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
          }else if( ($tipe == 'nama_nspk') || ($tipe == 'nomor_nsdpk' ) || ($tipe == 'peruntukan') ){
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
          $get = $request->except(['page','limit','tgl_from','tgl_to','kategori']);
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
              }else{
                $this->selected[$key] = $value;
                }
            }
            $this->selected['order'] = $request->order;
            $this->selected['limit'] = $request->limit;
            $this->data['filter'] = $this->selected;
          }
        }
        $this->data['title'] = "Dokumen NSPK Pascarehabilitasi";
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
        $kategori = 'kategori=pasca';
        $datas = execute_api_json('api/nspk?'.$kategori.'&'.$limit.'&'.$offset.$kondisi,'get');
        if(($datas->status != 'error') && ($datas->code == 200) ){
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;
        }else{
            $this->data['data'] = [];
            $total_item = 0;
        }
        $this->data['delete_route'] = 'delete_dokumen_nspk_pascarehabiltasi';
        $this->data['path'] = $request->path();
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
        $this->data['route_name'] = $request->route()->getName();
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), '/'.$request->route()->getPrefix()."/dokumen_nspk_pascarehabilitasi",$filtering,$filter);
        $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
        return view('rehabilitasi.pasca.nspk.index_dokumenNspk',$this->data);
    }

     public function editDokumenNspkPasca(Request $request){
        $id = $request->id;
        $datas = execute_api_json('api/nspk/'.$id,'get');
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }
        $this->data['file_path'] = config('app.pascarehabilitasi_nspk_file_path');
        $this->data['kode_direktorat'] = config('lookup.kode_direktorat');
        $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
        return view('rehabilitasi.pasca.nspk.edit_dokumenNspk',$this->data);
    }

    public function addDokumenNspkPasca(Request $request){
        if($request->isMethod('post')){
            $this->form_params = $request->except(['_token']);
            $date = explode('/', $request->tgl_pembuatan);
            $file_message = "";
            $this->form_params['tgl_pembuatan'] = $date[2].'-'.$date[1].'-'.$date[0];
            if($request->file('file_nspk')){
                $fileName = $request->file('file_nspk')->getClientOriginalName();
                $fileName = date('Y-m-d').'_'.$fileName;
                $extension = $request->file('file_nspk')->getClientOriginalExtension(); // getting image extension
                $directory = 'Rehabilitasi/NSPK/Pascarehabilitasi';
                $path = Storage::putFileAs($directory, $request->file('file_nspk'),$fileName);
                if($path){
                    $this->form_params['file_nspk'] = $fileName;
                }else{
                    $file_message = "Dengan File gagal diupload.";
                }
            }
            $this->form_params['kategori'] = 'pasca';
            $data_request = execute_api_json('api/nspk/','POST',$this->form_params);

						$trail['audit_menu'] = 'Rehabilitasi - Direktorat Pascarehabilitasi - Dokumen NSPK';
						$trail['audit_event'] = 'post';
						$trail['audit_value'] = json_encode($this->form_params);
						$trail['audit_url'] = $request->url();
						$trail['audit_ip_address'] = $request->ip();
						$trail['audit_user_agent'] = $request->userAgent();
						$trail['audit_message'] = $data_request->comment;
						$trail['created_at'] = date("Y-m-d H:i:s");
						$trail['created_by'] = $request->session()->get('id');

						$qtrail = $this->inputtrail($request->session()->get('token'),$trail);

            if( ($data_request->code == 200) && ($data_request->status != 'error') ){
                $id = $data_request->data->eventID;
                $this->kelengkapan_DokumenNspkPasca($id);
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Dokumen NSPK Berhasil Ditambahkan '. $file_message;
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Dokumen NSPK Gagal Ditambahkan';
            }
            return redirect(route('dokumen_nspk_pascarehabilitasi'))->with('status',$this->messages);
        }else{
            $this->data['current_category'] = 'pasca';
            $this->data['kode_direktorat'] = config('lookup.kode_direktorat');
            $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
            return view('rehabilitasi.pasca.nspk.add_dokumenNspk',$this->data);
        }
    }
    public function deleteDokumenNspkPasca(Request $request){
        $id = $request->input('id');
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/nspk/'.$id,'DELETE');
						$this->form_params['delete_id'] = $id;
						$trail['audit_menu'] = 'Rehabilitasi - Direktorat Pascarehabilitasi - Dokumen NSPK';
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
    }
    public function updateDokumenNspkPasca(Request $request){
        $id = $request->id;
        $this->form_params = $request->except(['_token', 'id']);
        if($request->tgl_pembuatan){
            $date = explode('/', $request->tgl_pembuatan);
            $this->form_params['tgl_pembuatan'] = $date[2].'-'.$date[1].'-'.$date[0];
        }
        $file_message = "";
        if($request->file('file_nspk')){
            $fileName = $request->file('file_nspk')->getClientOriginalName();
            $fileName = date('Y-m-d').'_'.$fileName;
            $extension = $request->file('file_nspk')->getClientOriginalExtension(); // getting image extension
            $directory = 'Rehabilitasi/NSPK/Pascarehabilitasi';
            $path = Storage::putFileAs($directory, $request->file('file_nspk'),$fileName);
            if($path){
                $this->form_params['file_nspk'] = $fileName;
            }else{
                $file_message = "Dengan File gagal diupload.";
            }
        }
        $data_request = execute_api_json('api/nspk/'.$id,'PUT',$this->form_params);

				$trail['audit_menu'] = 'Rehabilitasi - Direktorat Pascarehabilitasi - Dokumen NSPK';
				$trail['audit_event'] = 'put';
				$trail['audit_value'] = json_encode($this->form_params);
				$trail['audit_url'] = $request->url();
				$trail['audit_ip_address'] = $request->ip();
				$trail['audit_user_agent'] = $request->userAgent();
				$trail['audit_message'] = $data_request->comment;
				$trail['created_at'] = date("Y-m-d H:i:s");
				$trail['created_by'] = $request->session()->get('id');

				$qtrail = $this->inputtrail($request->session()->get('token'),$trail);

        if( ($data_request->code == 200) && ($data_request->status != 'error') ){
            $this->kelengkapan_DokumenNspkPasca($id);
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Dokumen NSPK Berhasil Diperbarui '. $file_message;
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Dokumen NSPK Gagal Diperbarui';
        }
        return back()->with('status',$this->messages);
    }

    private function kelengkapan_DokumenNspkPasca($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('rehab_nspk')->where('id',$id)
                  ->select('tgl_pembuatan','nama_nspk','nomor_nsdpk','peruntukan', 'file_nspk');
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
          $kelengkapan = execute_api_json('api/nspk/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/nspk/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

    /*
    |--------------------------------------------------------------------------
    |   Rehabilitasi : KEGIATAN PASCA REHABILITASI
    |--------------------------------------------------------------------------
    |
    */
    public function kegiatanPelatihanPasca(Request $request){
        if($request->limit) {
            $this->limit = $request->limit;
        } else {
            $this->limit = config('app.limit');
        }


        $kondisi = '';
        if($request->isMethod('get')){
            $get = $request->except(['page','_token','kategori']);
            if(count($get ) > 0 ) {
                foreach ($get as $key => $value) {
                    $kondisi .= "&".$key.'='.$value;
                    if(($key == 'start_from') || ($key == 'start_to') || ($key == 'end_from') || ($key == 'end_to')|| ($key == 'jumlah_from') || ($key == 'jumlah_to') ){
                        if($value){
                            $value = date('d/m/Y',strtotime($value));
                        }else{
                            $value = "";
                        }
                    }else{
                        $value = $value;
                    }
                    $this->selected[$key] =$value;
                }
                $this->selected['order'] = $request->order;
                $this->data['filter'] = $this->selected;
            }
        }else{
            $post = $request->except(['_token','start_to','start_from','end_to','end_from','jumlah_from','jumlah_to']);
            $tipe = trim($request->tipe);
            $kata_kunci = trim($request->kata_kunci);
            $pelaksana = trim($request->pelaksana);
            $start_from = trim($request->start_from);
            $start_to = trim($request->start_to);
            $end_from = trim($request->end_from);
            $end_to = trim($request->end_to);
            $jumlah_from = trim($request->jumlah_from);
            $jumlah_to = trim($request->jumlah_to);
            $order = trim($request->order);
            $status = trim($request->status);

            if($tipe){
                $this->selected['selected'] = $tipe;
                $this->selected['tipe'] = $tipe;
                $kondisi .= '&tipe='.$tipe;

            }
            if( ($tipe == 'tema') || ($tipe == 'nomor_sprint' )  ){
                $kondisi .= '&'.$tipe.'='.$request->kata_kunci;
                $this->selected[$tipe] = $request->kata_kunci;
            }else {
                if($tipe == 'periode_start'){
                    if($start_from){
                        $this->selected['start_from'] = $start_from;
                        $start_from = date('Y-m-d',strtotime(str_replace('/', '-',$start_from)));
                        $kondisi .= '&start_from='.$start_from;
                    }else if(!$start_from){
                        $this->selected['start_from'] = "";
                        $kondisi .= "";
                    }
                    if($start_to){
                        $this->selected['start_to'] = $start_to;
                        $start_to = str_replace('/', '-',$start_to);
                        $start_to = date('Y-m-d',strtotime($start_to));
                        $kondisi .= '&start_to='.$start_to;
                    }else if(!$start_to){
                        $this->selected['start_to'] = "";
                        $kondisi .= "";
                    }
                }else if($tipe == 'periode_end'){
                    if($end_from){
                        $this->selected['end_from'] = $end_from;
                        $end_from = str_replace('/', '-',$end_from);
                        $end_from = date('Y-m-d',strtotime($end_from));
                        $kondisi .= '&end_from='.$end_from;
                    }else if(!$end_from){
                        $this->selected['end_from'] = "";
                        $kondisi .= "";
                    }
                    if($end_to){
                        $this->selected['end_to'] = $end_to;
                        $end_to = str_replace('/', '-',$end_to);
                        $end_to = date('Y-m-d',strtotime($end_to));
                        $kondisi .= '&end_to='.$end_to;
                    }else if(!$end_to){
                        $this->selected['end_to'] = "";
                        $kondisi .= "";
                    }
                }else if($tipe == 'jumlah_peserta'){
                    if($jumlah_from){
                        $this->selected['jumlah_from'] = $jumlah_from;
                        $kondisi .='&jumlah_from='.$jumlah_from;
                    }else if(!$jumlah_from){
                        $this->selected['jumlah_from'] = "";
                        $kondisi .= "";
                    }

                    if($jumlah_to){
                        $this->selected['jumlah_to'] = $jumlah_to;
                        $kondisi .='&jumlah_to='.$jumlah_to;
                    }else if(!$jumlah_to){
                        $this->selected['jumlah_to'] = "";
                        $kondisi .= "";
                    }
                }else {
                    if(isset($post[$tipe])){
                        $kondisi .= '&'.$tipe.'='.$post[$tipe];
                        $this->selected[$tipe] = $post[$tipe];
                    }
                }
            }

            if($order){
                $this->selected['order'] = $order;
                $kondisi .= '&order='.$order;
            }else if(!$order){
                $this->selected['order'] = "desc";
                $kondisi .= "";
            }

            $this->selected['limit'] = $request->limit;
            $kondisi .= '&limit='.$request->limit;
            $this->data['filter'] = $this->selected;
        }

        $this->data['title'] = "Data Kegiatan Direktorat PascaRehabilitasi";
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
        $kategori = 'kategori=pasca';

        $datas = execute_api_json('api/pelatihan?'.$kategori.'&'.$limit.'&'.$offset.$kondisi.'&id_wilayah='.$request->session()->get('wilayah'),'get');
        if(($datas->status != 'error') && ($datas->code == 200) ){
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;
        }else{
            $this->data['data'] = [];
            $total_item = 0;
        }
        $this->data['delete_route'] = 'delete_kegiatan_pelatihan_pascarehabilitasi';
        $this->data['path'] = $request->path();
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
        $this->data['route_name'] = $request->route()->getName();
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), '/'.$request->route()->getPrefix()."/kegiatan_pelatihan_pascarehabilitasi",$filtering,$filter);
        $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
        return view('rehabilitasi.pasca.kegiatan_pelatihan.index_kegiatanPelatihan',$this->data);
    }

     public function editkegiatanPelatihanPasca(Request $request){
        $id = $request->id;
        $datas = execute_api_json('api/pelatihan/'.$id,'get');
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }

        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }

        $this->limit = config('app.limit');
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
        $parent_id = 'parent_id='.$id;
        $datas = execute_api_json('api/pelatihanpeserta?'.$parent_id.'&'.$offset.'&'.$limit,'get');
        if($datas->code == 200){
            $this->data['peserta'] = $datas->data;
        }else{
            $this->data['peserta'] = [];
        }

        $total_item = $datas->paginate->totalpage * $this->limit;
        $this->data['pagination'] = ajax_pagination($current_page,$total_item, $this->limit, config('app.page_ellipsis'),"/".$request->route()->getPrefix()."/index_peserta_pelatihan_pascarehabilitasi/".$id."/%d");
        $datas = execute_api_json('api/lookup/rehab_jenis_kegiatan','GET');
        if( ($datas->code == 200) && ($datas->status != 'error')){
            $this->data['jenis_kegiatan'] = $datas->data;
        }else{
            $this->data['jenis_kegiatan'] = [];
        }
        $this->data['delete_route'] = 'delete_peserta_pelatihan_pascarehabilitasi';
        $this->data['file_path'] = config('app.pascarehabilitasi_kegiatan_file_path');
        $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
        return view('rehabilitasi.pasca.kegiatan_pelatihan.edit_kegiatanPelatihan',$this->data);
    }

    public function addkegiatanPelatihanPasca(Request $request){
        if($request->isMethod('post')){
            $this->form_params = $request->except(['_token']);
            $file_message = "";
            if($request->tgl_sprint){
                $date = explode('/', $request->tgl_sprint);
                $this->form_params['tgl_sprint'] = $date[2].'-'.$date[1].'-'.$date[0];
            }

            if($request->tgl_dilaksanakan_mulai){
                $date = explode('/', $request->tgl_dilaksanakan_mulai);
                $this->form_params['tgl_dilaksanakan_mulai'] = $date[2].'-'.$date[1].'-'.$date[0];
            }

            if($request->tgl_dilaksanakan_selesai){
                $date = explode('/', $request->tgl_dilaksanakan_selesai);
                $this->form_params['tgl_dilaksanakan_selesai'] = $date[2].'-'.$date[1].'-'.$date[0];
            }
            $this->form_params['created_by_username'] = getPelaksana($request->id_pelaksana);
            if($request->file('file_materi')){
                $fileName = $request->file('file_materi')->getClientOriginalName();
                $fileName = date('Y-m-d').'_'.$fileName;
                $extension = $request->file('file_materi')->getClientOriginalExtension(); // getting image extension
                $directory = 'Rehabilitasi/KegiatanPelatihan/Pascarehabilitasi';
                $path = Storage::putFileAs($directory, $request->file('file_materi'),$fileName);
                if($path){
                    $this->form_params['file_materi'] = $fileName;
                }else{
                    $file_message = "Dengan File gagal diupload.";
                }
            }

            $this->form_params['kategori'] = 'pasca';
            $data_request = execute_api_json('api/pelatihan/','POST',$this->form_params);

						$trail['audit_menu'] = 'Rehabilitasi - Direktorat Pascarehabilitasi - Kegiatan';
						$trail['audit_event'] = 'post';
						$trail['audit_value'] = json_encode($this->form_params);
						$trail['audit_url'] = $request->url();
						$trail['audit_ip_address'] = $request->ip();
						$trail['audit_user_agent'] = $request->userAgent();
						$trail['audit_message'] = $data_request->comment;
						$trail['created_at'] = date("Y-m-d H:i:s");
						$trail['created_by'] = $request->session()->get('id');

						$qtrail = $this->inputtrail($request->session()->get('token'),$trail);


            if( ($data_request->code == 200) && ($data_request->status != 'error') ){
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Data Kegiatan PascaReahbilitasi Berhasil Ditambahkan '. $file_message;
                $id = $data_request->data->id;
                $this->kelengkapan_KegiatanPelatihanPasca($id);
                return redirect(route('edit_kegiatan_pelatihan_pascarehabilitasi',$id))->with('status',$this->messages);
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Data Kegiatan PascaReahbilitasi Gagal Ditambahkan';
                return redirect(route('kegiatan_pelatihan_pascarehabilitasi'))->with('status',$this->messages);
            }

        }else{
            $datas = execute_api_json('api/lookup/rehab_jenis_kegiatan','GET');
            if( ($datas->code == 200) && ($datas->status != 'error')){
                $this->data['jenis_kegiatan'] = $datas->data;
            }else{
                $this->data['jenis_kegiatan'] = [];
            }
            $this->data['title']="Form Tambah Kegiatan Direktorat PascaRehabilitasi";
            $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
            return view('rehabilitasi.pasca.kegiatan_pelatihan.add_kegiatanPelatihan',$this->data);
        }
        // $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        // $user_id = Auth::user()->user_id;
        // $detail = MainModel::getUserDetail($user_id);
        // $data['data_detail'] = $detail;
        // $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
    }
    public function updateKegiatanPelatihanPasca(Request $request){
        $id = $request->id;
        $this->form_params = $request->except(['_token', 'id']);
        $file_message = "";
        if($request->tgl_sprint){
            $date = explode('/', $request->tgl_sprint);
            $this->form_params['tgl_sprint'] = $date[2].'-'.$date[1].'-'.$date[0];
        }

        if($request->tgl_dilaksanakan_mulai){
            $date = explode('/', $request->tgl_dilaksanakan_mulai);
            $this->form_params['tgl_dilaksanakan_mulai'] = $date[2].'-'.$date[1].'-'.$date[0];
        }

        if($request->tgl_dilaksanakan_selesai){
            $date = explode('/', $request->tgl_dilaksanakan_selesai);
            $this->form_params['tgl_dilaksanakan_selesai'] = $date[2].'-'.$date[1].'-'.$date[0];
        }
        $this->form_params['created_by_username'] = getPelaksana($request->id_pelaksana);
        if($request->file('file_materi')){
            $fileName = $request->file('file_materi')->getClientOriginalName();
            $fileName = date('Y-m-d').'_'.$fileName;
            $extension = $request->file('file_materi')->getClientOriginalExtension(); // getting image extension
            $directory = 'Rehabilitasi/KegiatanPelatihan/Pascarehabilitasi';
            $path = Storage::putFileAs($directory, $request->file('file_materi'),$fileName);
            if($path){
                $this->form_params['file_materi'] = $fileName;
            }else{
                $file_message = "Dengan File gagal diupload.";
            }
        }
        $data_request = execute_api_json('api/pelatihan/'.$id,'PUT',$this->form_params);

				$trail['audit_menu'] = 'Rehabilitasi - Direktorat Pascarehabilitasi - Kegiatan';
				$trail['audit_event'] = 'put';
				$trail['audit_value'] = json_encode($this->form_params);
				$trail['audit_url'] = $request->url();
				$trail['audit_ip_address'] = $request->ip();
				$trail['audit_user_agent'] = $request->userAgent();
				$trail['audit_message'] = $data_request->comment;
				$trail['created_at'] = date("Y-m-d H:i:s");
				$trail['created_by'] = $request->session()->get('id');

				$qtrail = $this->inputtrail($request->session()->get('token'),$trail);

        if( ($data_request->code == 200) && ($data_request->status != 'error') ){
            $this->kelengkapan_KegiatanPelatihanPasca($id);
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Kegiatan PascaReahbilitasi Berhasil Diperbarui '. $file_message;
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Kegiatan PascaReahbilitasi Gagal Diperbarui';
        }
        return back()->with('status',$this->messages);
    }

    private function kelengkapan_KegiatanPelatihanPasca($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('rehab_pelatihan')->where('id',$id)
                  ->select('jenis_kegiatan','tema','id_pelaksana','nomor_sprint', 'tgl_sprint','tgl_dilaksanakan_mulai','tgl_dilaksanakan_selesai','tempat','jumlah_narasumber','jumlah_peserta','file_materi');
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
          $kelengkapan = execute_api_json('api/pelatihan/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/pelatihan/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }


    public function indexPesertaKegiatanPelatihanPasca(Request $request){
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
        $parent_id = 'parent_id='.$parent_id;

        $datas = execute_api_json('api/pelatihanpeserta?'.$parent_id.'&'.$limit.'&'.$offset,'get');

        // $datas = execute_api_json('api/single_pelatihan_rehabilitasi/'.$parent_id.'?'.$limit.'&'.$offset,'get');
        if($datas->code == 200){
            $this->data['status'] = 'success';
            if(count($datas->data)>=1){
                $html = "";
                $j = $start_number;
                foreach($datas->data as $p){
                    $html .= '<tr>';
                    $html .= '<td>'.$j.'</td>';
                    $html .= '<td>'.$p->nama_peserta.'</td>';
                    $html .= '<td>'.$p->nomor_identitas.'</td>';
                    $html .= '<td> '. ( ($p->kode_jeniskelamin == "P") ? 'Perempuan' : ( ($p->kode_jeniskelamin == "L") ? 'Laki-Laki' : '')).'</td>';
                    $html .= '<td>'.$p->asal_instansilembaga.'</td>';
                    $html .= '<td class="actionTable">';
                    $html .= '<button type="button" class="btn btn-primary button-edit" data-target="'.$p->id_detail.'" onClick="open_modalEditPeserta(event,this,\'/rehabilitasi/dir_pasca/edit_peserta_pelatihan_pascarehabilitasi/\',\'modal_edit_form\')"><i class="fa fa-pencil"></i></button>';
                    $html .= '<button type="button" class="btn btn-primary button-action" data-target="'.$p->id_detail.'" onClick="delete_row_peserta(event,this)"><i class="fa fa-trash"></i></button>';
                    $html .= '</td>';
                    $html .= '</tr>';
                    $j = $j + 1;
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
    public function deleteKegiatanPelatihanPasca(Request $request){
    $id = $request->input('id');
    if ($request->ajax()) {
      $id = $request->id;
      $data_request = execute_api('api/pelatihan/'.$id,'DELETE');
			$this->form_params['delete_id'] = $id;
			$trail['audit_menu'] = 'Rehabilitasi - Direktorat Pascarehabilitasi - Kegiatan';
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


    }
    public function deletePesertaKegiatanPelatihanPasca(Request $request){


        $id = $request->input('id');
        if ($request->ajax()) {
            $html = "";
            $paginate = "";
            $id = $request->id;
            $parent_id = $request->parent_id;
            $current_page = 1;
            $current_page = $request->current_page;
            $data_request = execute_api('api/pelatihanpeserta/'.$id,'DELETE');
						$this->form_params['delete_id'] = $id;
						$trail['audit_menu'] = 'Rehabilitasi - Direktorat Pascarehabilitasi- Kegiatan Peserta';
						$trail['audit_event'] = 'delete';
						$trail['audit_value'] = json_encode($this->form_params);
						$trail['audit_url'] = $request->url();
						$trail['audit_ip_address'] = $request->ip();
						$trail['audit_user_agent'] = $request->userAgent();
						$trail['audit_message'] = $data_request['comment'];
						$trail['created_at'] = date("Y-m-d H:i:s");
						$trail['created_by'] = $request->session()->get('id');

						$qtrail = $this->inputtrail($request->session()->get('token'),$trail);

            $offset = 'page='.$current_page;
            $kategori = 'parent_id='.$parent_id;
            $limit = 'limit='.config('app.limit');
            if($request->current_page){
                $current_page = $request->current_page;
                $start_number = ($this->limit * ($request->current_page -1 )) + 1;
            }else{
                $current_page = 1;
                $start_number = $current_page;
            }
          $current_data = execute_api_json('api/pelatihanpeserta?'.$kategori.'&'.$limit.'&'.$offset,'get');
          if($current_data->code == 200){
            $datas = $current_data->data;
            if(count($datas)>0){

                $j = $start_number;
                foreach($datas as $d){
                    $html .= "<tr>";
                    $html .= "<td>".$j."</td>";
                    $html .= "<td>".$d->nama_peserta."</td>";
                    $html .= "<td>".$d->nomor_identitas."</td>";
                    $html .= "<td>". ( ($d->kode_jeniskelamin == "P") ? 'Perempuan' : ( ($d->kode_jeniskelamin == "L") ? 'Laki-Laki' : ''))."</td>";
                    $html .= "<td>".$d->asal_instansilembaga."</td>";
                    $html .= '<td class="actionTable">
                                <button type="button" class="btn btn-primary button-edit" data-target="'.$d->id_detail.'" onClick="open_modalEditPeserta(event,this,\'/rehabilitasi/dir_pasca/edit_peserta_pelatihan_pascarehabilitasi/\',\'modal_edit_form\')"><i class="fa fa-pencil"></i></button>
                                <button type="button" class="btn btn-primary button-action" onClick="delete_row_peserta(event,this)" data-target="'.$d->id_detail.'" ><i class="fa fa-trash"></i></button>
                            </td>';
                    $html .= "</tr>";
                    $j = $j+1;
                }
                $total_item = $current_data->paginate->totalpage * config('app.limit');
                if($total_item > config('app.limit')) {
                    $paginate = ajax_pagination($current_page,$total_item, config('app.limit'), config('app.page_ellipsis'),"/".$request->route()->getPrefix()."/index_peserta_pelatihan_pascarehabilitasi/".$id."/%d");
                }else{
                    $paginate = "";
                }

            }
            else{
                $html = "<tr><td colspan=6> <p class='alert-warning'>Data Peserta Kegiatan Pendidikan dan Pelatihan Pada Balai Belum Tersedia </p></td> </tr>";
            }

          }else{
            $html = "<tr><td colspan=6> <p class='alert-warning'>Data Peserta Kegiatan Pendidikan dan Pelatihan Pada Balai Belum Tersedia </p></td> </tr>";
          }

          $data_request['data_return'] = $html;
          $data_request['pagination'] = $paginate ;
          return  $data_request;
        }
    }
    public function addPesertaKegiatanPelatihanPasca(Request $request){

        if($request->ajax()){
            $id = $request->id_header;
            $data_request = "";
            $this->form_params = $request->except(['_token','parent_id']);
            $data_request = execute_api_json('api/pelatihanpeserta/','POST',$this->form_params);

						$trail['audit_menu'] = 'Rehabilitasi - Direktorat Pascarehabilitasi - Kegiatan Peserta';
						$trail['audit_event'] = 'post';
						$trail['audit_value'] = json_encode($this->form_params);
						$trail['audit_url'] = $request->url();
						$trail['audit_ip_address'] = $request->ip();
						$trail['audit_user_agent'] = $request->userAgent();
						$trail['audit_message'] = $data_request->comment;
						$trail['created_at'] = date("Y-m-d H:i:s");
						$trail['created_by'] = $request->session()->get('id');

						$qtrail = $this->inputtrail($request->session()->get('token'),$trail);

            // $data_request = json_decode(json_encode($data_request), FALSE);
            if(($data_request->code == 200)&& ($data_request->status != "error") ){
                $id_detail = $data_request->data->id;
                $current_page = 1;
                $parent_id = $id;
                $offset = 'page=1';
                $kategori = 'parent_id='.$parent_id;
                $limit = 'limit='.config('app.limit');
                $start_number = $current_page;
                $current_data = execute_api_json('api/pelatihanpeserta?'.$kategori.'&'.$limit.'&'.$offset,'get');
                if($current_data->code == 200 && $current_data->status != 'error'){
                    $datas = $current_data->data;
                    if(count($datas)>0){
                        $j = $start_number;
                        $html = "";
                        foreach($datas as $d){
                            $html .= "<tr>";
                            $html .= "<td>".$j."</td>";
                            $html .= "<td>".$d->nama_peserta."</td>";
                            $html .= "<td>".$d->nomor_identitas."</td>";
                            $html .= '<td>'.( ($d->kode_jeniskelamin == "P") ? 'Perempuan' : ( ($d->kode_jeniskelamin == "L") ? 'Laki-Laki' : '')).'</td>';
                            $html .= "<td>".$d->asal_instansilembaga."</td>";
                            $html .= '<td class="actionTable">
                                        <button type="button" class="btn btn-primary button-edit" data-target="'.$d->id_detail.'" onClick="open_modalEditPeserta(event,this,\'/rehabilitasi/dir_pasca/edit_peserta_pelatihan_pascarehabilitasi/\',\'modal_edit_form\')"><i class="fa fa-pencil"></i></button>
                                        <button type="button" class="btn btn-primary button-action" data-target="'.$d->id_detail.'" onClick="delete_row_peserta(event,this)"><i class="fa fa-trash"></i></button>
                                    </td>';
                            $html .= "</tr>";
                            $j = $j+1;
                        }
                        $total_item = $current_data->paginate->totalpage * config('app.limit');
                        if($total_item > config('app.limit')) {
                            $paginate = ajax_pagination($current_page,$total_item, config('app.limit'), config('app.page_ellipsis'),"/".$request->route()->getPrefix()."/index_peserta_pelatihan_pascarehabilitasi/".$parent_id."/%d");
                        }else{
                            $paginate = "";
                        }

                    }
                    else{
                        $html = "<tr><td colspan=6> <p class='alert-warning'>Data Peserta Kegiatan Pendidikan dan Pelatihan Pada Balai Belum Tersedia </p></td> </tr>";
                    }
                }else{
                      $html = "<tr><td colspan=6> <p class='alert-warning'>Data Peserta Kegiatan Pendidikan dan Pelatihan Pada Balai Belum Tersedia </p></td> </tr>";
                }

                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Data Peserta Pelatihan Pascarehabilitasi Berhasil Ditambahkan';
                $this->messages['data_return'] = $html;
                $this->messages['pagination'] = $paginate;


            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Data Peserta Pelatihan Pascarehabilitasi Gagal Ditambahkan';
                $this->messages['data_return'] = null;
            }
             // $this->messages['data'] = $kategori;
            return response()->json($this->messages );
        }
    }
    public function updatePesertaKegiatanPelatihanPasca(Request $request){
        // if($request->ajax()){
        //     $id = $request->id_detail;
        //     $this->form_params = $request->except(['_token','id_detail','index']);
        //     $data_request = execute_api('api/pelatihanpeserta/'.$id,'PUT',$this->form_params);
        //     $data_request = json_decode(json_encode($data_request), FALSE);
        //     $html  = "<tr>";
        //     $html .= '<td></td>';
        //     $html .= '<td>'.$request->nama_peserta.'</td>';
        //     $html .= '<td>'.$request->nomor_identitas.'</td>';
        //     $html .= '<td>'.( ($request->kode_jeniskelamin == "P") ? 'Perempuan' : ( ($request->kode_jeniskelamin == "L") ? 'Laki-Laki' : '')).'</td>';
        //     $html .= '<td>'.$request->asal_instansilembaga.'</td>';
        //     $html .= '<td class="actionTable">
        //                 <button type="button" class="btn btn-primary button-edit" data-target="'.$id.'" onClick="open_modalEditPeserta(event,this,\'/rehabilitasi/dir_pasca/edit_peserta_pelatihan_pascarehabilitasi/\',\'modal_edit_form\')"><i class="fa fa-pencil"></i></button>
        //                 <button type="button" class="btn btn-primary button-action" data-target="'.$id.'" onClick="ajax_delete(event,this)"><i class="fa fa-trash"></i></button>
        //             </td>';
        //     $html  .= "</tr>";
        //     if(($data_request->code == 200)&& ($data_request->status != "error") ){
        //         $this->messages['status'] = 'success';
        //         $this->messages['message'] = 'Data Peserta Pelatihan Pasca Rehabilitasi Berhasil Diperbarui';
        //         $this->messages['data'] = $html;
        //     }else{
        //         $this->messages['status'] = 'error';
        //         $this->messages['message'] = 'Data Peserta Pelatihan Pasca Rehabilitasi Gagal Diperbarui';
        //         $this->messages['data'] = null;
        //     }
        //     return response()->json($this->messages);
        // }
        if($request->ajax()){
            $id = $request->id_detail;
            $this->form_params = $request->except(['_token','id_detail','parent_id','index']);
            $data_request = execute_api_json('api/pelatihanpeserta/'.$id,'PUT',$this->form_params);

						$trail['audit_menu'] = 'Rehabilitasi - Direktorat Pascarehabilitasi - Kegiatan Peserta';
						$trail['audit_event'] = 'put';
						$trail['audit_value'] = json_encode($this->form_params);
						$trail['audit_url'] = $request->url();
						$trail['audit_ip_address'] = $request->ip();
						$trail['audit_user_agent'] = $request->userAgent();
						$trail['audit_message'] = $data_request->comment;
						$trail['created_at'] = date("Y-m-d H:i:s");
						$trail['created_by'] = $request->session()->get('id');

						$qtrail = $this->inputtrail($request->session()->get('token'),$trail);


            if(($data_request->code == 200)&& ($data_request->status != "error") ){
                $html  = "<tr>";
                $html .= '<td></td>';
                $html .= '<td>'.$request->nama_peserta.'</td>';
                $html .= '<td>'.$request->nomor_identitas.'</td>';
                $html .= '<td>'.( ($request->kode_jeniskelamin == "P") ? 'Perempuan' : ( ($request->kode_jeniskelamin == "L") ? 'Laki-Laki' : '')).'</td>';
                $html .= '<td>'.$request->asal_instansilembaga.'</td>';
                $html .= '<td class="actionTable">
                            <button type="button" class="btn btn-primary button-edit" data-target="'.$id.'" onClick="open_modalEditPeserta(event,this,\'/rehabilitasi/dir_pasca/edit_peserta_pelatihan_pascarehabilitasi/\',\'modal_edit_form\')"><i class="fa fa-pencil"></i></button>
                            <button type="button" class="btn btn-primary button-action" data-target="'.$id.'" onClick="delete_row_peserta(event,this)"><i class="fa fa-trash"></i></button>
                        </td>';
                $html  .= "</tr>";
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Data Peserta Pelatihan DIR Pasca Rehabilitasi Berhasil Diperbarui';
                $this->messages['data_return'] = $html;
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Data Peserta Pelatihan DIR Pasca Rehabilitasi Gagal Diperbarui';
                $this->messages['data_return'] = null;
            }
            return response()->json($this->messages);
        }
    }
    public function editPesertaKegiatanPelatihanPasca(Request $request){
        if($request->ajax()){
            $id = $request->id;
            $this->form_params = $request->except(['_token','id']);
            $data_request = execute_api_json('api/pelatihanpeserta/'.$id,'GET');
            if(($data_request->code == 200)&& ($data_request->status != "error") ){
                $this->messages['status'] = 'success';
                $this->messages['data'] = $data_request->data;
            }else{
                $this->messages['status'] = 'error';
                $this->messages['data'] = [];
            }
            return response()->json($this->messages);

        }
    }
    /*
    |--------------------------------------------------------------------------
    |   Rehabilitasi : PEnilaian LEmbaga REHABILITASI
    |--------------------------------------------------------------------------
    |
    */
    public function penilaianLembagaPasca(Request $request){
        $this->limit = config('app.limit');
        $this->data['title'] = "Penilaian Lembaga Pasca Rehabilitasi";
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
        $tipe = 'tipe='.'pasca';
        $datas = execute_api_json('api/penilaianlembaga?'.$tipe.'&'.$limit.'&'.$offset,'get');
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }
        $this->data['delete_route'] = 'delete_penilaian_lembaga_pascarehabilitasi';
        $this->data['path'] = $request->path();
        $this->data['route_name'] = $request->route()->getName();
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;
        $total_item = $datas->paginate->totalpage * $this->limit;
        $this->data['pagination'] = pagination($current_page,$total_item, $this->limit, config('app.page_ellipsis'), "/".$request->route()->getPrefix()."/".$request->route()->getName()."/%d");
        $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
        return view('rehabilitasi.pasca.penilaian_lembaga.index_penilaianLembaga',$this->data);
    }

     public function editpenilaianLembagaPasca(Request $request){
        $id = $request->id;
        $this->data['penilaian'] = config('lookup.penilaian_lembaga');
        $datas = execute_api_json('api/penilaianlembaga/'.$id,'get');

        if($datas->code == 200){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }
        $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
        return view('rehabilitasi.pasca.penilaian_lembaga.edit_penilaianLembaga',$this->data);
    }

    public function updatePenilaianLembagaPasca(Request $request){
        $id = $request->id;
        $this->form_params = $request->except(['_token','id']);
        $data_request = execute_api_json('api/penilaianlembaga/'.$id,'PUT',$this->form_params);

        if(($data_request->code == 200)&& ($data_request->status != "error") ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Info Lembaga Umum PascaRehabilitasi Berhasil Diperbarui';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Info Lembaga Umum PascaRehabilitasi Gagal Diperbarui';
        }
        return back()->with('status', $this->messages);
    }
    public function deletePenilaianLembagaPasca(Request $request){
        $id = $request->input('id');
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/penilaianlembaga/'.$id,'DELETE');
            return $data_request;
        }
    }
    public function addpenilaianLembagaPasca(Request $request){
        if($request->isMethod('post')){
            $this->form_params   = $request->except(['_token']);
            $this->form_params['tipe'] = 'pasca';
            $data_request = execute_api_json('api/penilaianlembaga/','POST',$this->form_params);
            if(($data_request->code == 200)&& ($data_request->status != "error") ){
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Data Info Lembaga Umum PascaRehabilitasi Berhasil Diperbarui';
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Data Info Lembaga Umum PascaRehabilitasi Gagal Diperbarui';
            }
            return redirect(route('penilaian_lembaga_pascarehabilitasi'))->with('status', $this->messages);
        }else{
            $this->data['penilaian'] = config('lookup.penilaian_lembaga');
            $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
            return view('rehabilitasi.pasca.penilaian_lembaga.add_penilaianLembaga',$this->data);
        }
    }

    public function printPage(Request $request){
        $array_segments = [
            'informasi_lembaga_umum_pascarehabilitasi'=>'infolembaga?kategori=pasca&id_wilayah='.$request->session()->get('wilayah'),
            'dokumen_nspk_pascarehabilitasi'=>'nspk?kategori=pasca&id_wilayah='.$request->session()->get('wilayah'),
            'informasi_lembaga_umum_plrip'=>'infolembaga?kategori=plrip&id_wilayah='.$request->session()->get('wilayah'),
            'informasi_lembaga_umum_plrkm'=>'infolembaga?kategori=plrkm&id_wilayah='.$request->session()->get('wilayah'),
            'dokumen_nspk_plrip'=>'nspk?kategori=plrip&id_wilayah='.$request->session()->get('wilayah'),
            'kegiatan_pelatihan_plrip'=>'pelatihan?kategori=plrip&id_wilayah='.$request->session()->get('wilayah'),
            'kegiatan_pelatihan_plrkm'=>'pelatihan?kategori=plrkm&id_wilayah='.$request->session()->get('wilayah'),
            'kegiatan_pelatihan_pascarehabilitasi'=>'pelatihan?kategori=pasca&id_wilayah='.$request->session()->get('wilayah'),
            'penilaian_lembaga_plrip'=>'penilaianlembaga?tipe=plrip&id_wilayah='.$request->session()->get('wilayah'),
            'penilaian_lembaga_plrkm'=>'penilaianlembaga?tipe=plrkm&id_wilayah='.$request->session()->get('wilayah'),
            'penilaian_lembaga_pascarehabilitasi'=>'penilaianlembaga?tipe=pasca&id_wilayah='.$request->session()->get('wilayah'),
            'dokumen_nspk_plrkm'=>'nspk?kategori=plrkm&id_wilayah='.$request->session()->get('wilayah'),
        ];
        $array_titles=[
            'informasi_lembaga_umum_pascarehabilitasi'=>'Data Info Lembaga Umum Rehabilitasi',
            'dokumen_nspk_pascarehabilitasi'=>'Dokumen NSPK Pasca Rehabilitasi',
            'informasi_lembaga_umum_plrip'=>'Informasi Lembaga Umum PLRIP',
            'informasi_lembaga_umum_plrkm'=>'Informasi Lembaga Umum PLRKM',
            'dokumen_nspk_plrip'=>'Dokumen NSPK Pasca PLRIP',
            'kegiatan_pelatihan_plrip'=>'Kegiatan Pelatihan PLRIP',
            'kegiatan_pelatihan_plrkm'=>'Kegiatan Pelatihan PLRKM',
            'kegiatan_pelatihan_pascarehabilitasi'=>'Kegiatan Pelatihan Pascarehabilitasi',
            'penilaian_lembaga_plrip'=>'Penilaian Lembaga PLRIP',
            'penilaian_lembaga_plrkm'=>'Penilaian Lembaga PLRKM',
            'penilaian_lembaga_pascarehabilitasi'=>'Penilaian Lembaga Pasca Rehabilitasi',
            'dokumen_nspk_plrkm'=>'Dokumen NSPK PLRKM',
        ];
        $result= [];
        $get = $request->all();
        $kondisi = "";
        if(count($get)>0){
          foreach($get as $key=>$val){
            $kondisi .= $key.'='.$val.'&';
          }
          $kondisi = rtrim($kondisi,'&');
          $kondisi = '&'.$kondisi;
        }else{
          $kondisi = '&page='.$request->page;
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

        $data_request = execute_api($url,'GET');
        $i = $start_number;
        if( ($segment == 'informasi_lembaga_umum_pascarehabilitasi') || ($segment == 'informasi_lembaga_umum_plrip')|| ($segment == 'informasi_lembaga_umum_plrkm') ){
            if($data_request['code'] == 200 && $data_request['status'] != 'error'){
                $datas = execute_api('api/lookup/bentuk_layanan','get');
                // dd($datas);
                if(($datas['code'] == 200) && ($datas['status'] != 'error')){
                    $bentuk_layanan = $datas['data'];
                }else{
                    $bentuk_layanan = [];
                }
                $data = $data_request['data'];
                $j = [];
                if(count($data) >0){
                    foreach($data as $key=>$value){
                        $meta_bentuk_layanan = "";
                        if($value['bentuk_layanan']){
                            $json = json_decode($value['bentuk_layanan'],true);
                            if(count($json) > 0 ){
                                foreach ($json as $k => $j) {
                                    $meta_bentuk_layanan .= (isset($bentuk_layanan[$j]) ? $bentuk_layanan[$j] : '')."\n";
                                }
                            }else{
                                $meta_bentuk_layanan = "";
                            }
                        }else{
                            $meta_bentuk_layanan = "";
                        }
                        $result[$key]['No'] = $i;
                        $result[$key]['Nama Lembaga'] = $value['nama'];
                        $result[$key]['Alamat'] = $value['alamat'];
                        $result[$key]['Contact Person'] = $value['cp_nama'];
                        $result[$key]['Bentuk Layanan'] = ( $value['bentuk_layanan'] ? getBentukLayananPrint(json_decode($value['bentuk_layanan'],true)) : '');
                        $result[$key]['Status'] = ($value['status'] == 'Y'  ? 'Lengkap' : 'Belum Lengkap');
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
        }else if( ($segment == 'dokumen_nspk_pascarehabilitasi') || ($segment == 'dokumen_nspk_plrip') || ($segment == 'dokumen_nspk_plrkm') ){
            if($data_request['code'] == 200 && $data_request['status'] != 'error'){
                $data = $data_request['data'];
                if(count($data) >0){
                    foreach($data as $key=>$value){
                        $result[$key]['No'] = $i;
                        $result[$key]['Tanggal Pengesahan'] = ($value['tgl_pembuatan'] ? date('d/m/Y',strtotime($value['tgl_pembuatan'])) : '');
                        $result[$key]['Nama NSPK'] = $value['nama_nspk'];
                        $result[$key]['No. NSPK'] = $value['nomor_nsdpk'];
                        $result[$key]['Peruntukan'] = $value['peruntukan'];
                        $result[$key]['Status'] = ($value['status'] == 'Y'  ? 'Lengkap' : 'Belum Lengkap');
                        $i = $i+1;
                    }
                    $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
                    $this->printData($result, $name);
                }else{
                    $result =[];
                }
                // echo $name;
                // dd($result);
            }else{
                return false;
            }
        }else if( ($segment == 'kegiatan_pelatihan_plrip') || ($segment ==  'kegiatan_pelatihan_plrkm') || ($segment ==  'kegiatan_pelatihan_pascarehabilitasi') ){
            if($data_request['code'] == 200 && $data_request['status'] != 'error'){
                $data = $data_request['data'];
                if(count($data) >0){
                    foreach($data as $key=>$value){
                        $result[$key]['No'] = $i;
                        $result[$key]['Pelaksana'] = $value['created_by_username'];
                        $result[$key]['Judul'] = $value['tema'];
                        $result[$key]['Nomor Surat Perintah'] = $value['nomor_sprint'];
                        $result[$key]['Tanggal Mulai'] = ($value['tgl_dilaksanakan_mulai'] ? date('d/m/Y',strtotime($value['tgl_dilaksanakan_mulai'])) : '');
                        $result[$key]['Tanggal Selesai'] = ($value['tgl_dilaksanakan_selesai'] ? date('d/m/Y',strtotime($value['tgl_dilaksanakan_selesai'])) : '');
                        $result[$key]['Jumlah Peserta'] = number_format($value['jumlah_peserta']);
                        $result[$key]['Status'] = ($value['status'] == 'Y'  ? 'Lengkap' : 'Belum Lengkap');
                        $i = $i+1;
                    }
                    $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
                    $this->printData($result, $name);
                }else{
                    $result =[];
                }
                // echo $name;
                // dd($result);
            }else{
                return false;
            }
        }else if( ($segment == 'penilaian_lembaga_pascarehabilitasi') || ($segment ==  'penilaian_lembaga_plrkm') || ($segment ==  'penilaian_lembaga_plrip') ){
           if($data_request['code'] == 200 && $data_request['status'] != 'error'){
                $data = $data_request['data'];
                if(count($data) >0){
                    foreach($data as $key=>$value){
                        $result[$key]['No'] = $i;
                        $result[$key]['Nama Lembaga'] = $value['nama'];
                        $result[$key]['Alamat'] = $value['alamat'];
                        $result[$key]['Kode'] = $value['alamat_kodepos'];
                        $i = $i+1;
                    }
                    $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
                    $this->printData($result, $name);
                }else{
                    $result =[];
                }
            }else{
                return false;
            }
        }
    }
}

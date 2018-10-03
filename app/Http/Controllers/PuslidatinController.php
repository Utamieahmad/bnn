<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Storage;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\MainModel;
use URL;

class PuslidatinController extends Controller
{
    public $data;
    public $selected;
    public $messages;
    public $limit;
    public $form_params = [];
    public function survey(Request $request){
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
          $post = $request->except(['_token','year_from','year_to','jml_to','jml_from']);
          $tipe = $request->tipe;
          $year_from = $request->year_from;
          $year_to = $request->year_to;
          $jml_to = $request->jml_to;
          $jml_from = $request->jml_from;
          if($tipe){
            $kondisi .= '&tipe='.$tipe;
            $this->selected['tipe'] = $tipe;
          }
          if($tipe == 'periode'){
            if($year_from){
                $this->selected['year_from'] = $year_from;
                $kondisi .='&year_from='.$year_from;
            }else if(!$year_from){
                $kondisi .='';
            }
            if($year_to){
              $this->selected['year_to'] = $year_to;
              $kondisi .='&year_to='.$year_to;
            }else if(!$year_to){
              $kondisi .='';
            }
          }else if($tipe == 'jml_responden'){
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
          }elseif( $tipe == 'judul' ){
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
          $get = $request->except(['page','year_from','year_to','limit','jml_to','jml_from']);
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
                if($request->year_from){
                    $kondisi .= "&year_from".'='.$request->year_from;
                    $this->selected['year_from'] = $request->year_from;
                }
                if($request->year_to){
                  $kondisi .= "&year_to".'='.$request->year_to;
                  $this->selected['year_to'] = $request->year_to;
                }
              }else if($value == 'jml_responden'){
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
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
        $datas = execute_api_json('api/surveypenyalahguna?'.$limit.'&'.$offset.$kondisi,'get');

        if(($datas->code == 200) && $datas->status != 'error'){
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;
        }else{
            $this->data['data'] = [];
            $total_item = 0;
        }

        $this->data['delete_route'] = 'delete_survey';
        $this->data['path'] = $request->path();
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;
        $this->data['kelompok_survey'] = config('lookup.kelompok_survey');
        $this->data['breadcrumps'] = breadcrump_litbang($request->route()->getName());
        $this->data['title'] = 'Puslitdatin';
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
        return view('puslitdatin/litbang/survey/index_survey',$this->data);
    }

    public function editSurvey(Request $request){
        $id = $request->id;
        $datas = execute_api_json('api/surveypenyalahguna/'.$id,'get');
        if($datas->code == 200 && $datas->status != 'error' ){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }
        $propinsi = execute_api_json('api/propinsi/','get');
        if($propinsi->code == 200 && $datas->status != 'error' ){
            $this->data['propinsi'] = $propinsi->data;
        }else{
            $this->data['propinsi'] = [];
        }
        $brgbukti = execute_api('api/jnsbrgbukti','post');
        if($brgbukti['code'] == 200 && $brgbukti['status'] != 'error'){
            $this->data['jenisBrgBuktiNarkotika'] = $brgbukti['data'];
        }else{
            $this->data['jenisBrgBuktiNarkotika'] = [];
        }
        $this->data['kelompok_survey'] = config('lookup.kelompok_survey');
        $this->data['breadcrumps'] = breadcrump_litbang($request->route()->getName());
        return view('puslitdatin/litbang/survey/edit_survey',$this->data);
    }

    public function updateSurvey(Request $request){
        $id = $request->input('id');
        $token = $request->session()->get('token');

        $this->form_params = $request->except(['_token', 'id','meta_narkoba','meta_data']);
        $meta_narkoba = $request->meta_narkoba;
        $meta_provinsi = $request->meta_data;
        $meta_data_narkoba =[];
        $meta_data_provinsi =[];
        $json_data_narkoba = "";
        $json_data_provinsi ="";
        if(count($meta_narkoba) > 0 ){
            foreach($meta_narkoba as $m => $mval){
                    if($mval['jenis_narkoba'] || $mval['jumlah_orang']){
                        $meta_data_narkoba[] = ['jenis_narkoba'=>$mval['jenis_narkoba'] ,'jumlah_orang'=>$mval['jumlah_orang'] ];
                    }
            }
            if(count($meta_data_narkoba) > 0 ){
                $json_data_narkoba  = json_encode($meta_data_narkoba);
            }else{
                $json_data_narkoba = "";
            }
        }else{
            $json_data_narkoba  = "";
        }

        if(count($meta_provinsi) > 0 ){
            foreach($meta_provinsi as $p => $pval){
                    if($pval['id_provinsi'] || $pval['list_prevalensi']|| $pval['list_absolut']){
                        $meta_data_provinsi[] = ['id_provinsi'=>$pval['id_provinsi'] ,'list_prevalensi'=>$pval['list_prevalensi'] ,'list_absolut'=>$pval['list_absolut'] ];
                    }
            }
            if(count($meta_data_provinsi) > 0 ){
                $json_data_provinsi  = json_encode($meta_data_provinsi);
            }else{
                $json_data_provinsi = "";
            }
        }else{
            $json_data_provinsi  = "";
        }
        $this->form_params['meta_narkoba'] = $json_data_narkoba;
        $this->form_params['meta_data_provinsi'] = $json_data_provinsi;
        $data_request = execute_api_json('api/surveypenyalahguna/'.$id,'PUT',$this->form_params);

        $trail['audit_menu'] = 'Puslitdatin - Bidang Litbang - Survey';
        $trail['audit_event'] = 'put';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $data_request->comment;
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

        if(($data_request->code == 200) && ($data_request->status != 'error')){
            $this->kelengkapan_survey($id);
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Survey Penyalahgunaan Narkoba Berhasil Diperbarui';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Survey Penyalahgunaan Narkoba Gagal Diperbarui';
        }
        return back()->with('status', $this->messages);
    }

    public function deleteSurvey(Request $request){
        if ($request->ajax()) {
            $id = $request->id;
            if($id){
                $data_request = execute_api('api/surveypenyalahguna/'.$id,'DELETE');
                $this->form_params['delete_id'] = $id;
                $trail['audit_menu'] = 'Puslitdatin - Bidang Litbang - Survey';
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

    public function addSurvey(Request $request){
        if ($request->isMethod('post')) {
            $token = $request->session()->get('token');
            $this->form_params = $request->except(['_token','meta_narkoba','meta_data']);
            $meta_narkoba = $request->meta_narkoba;
            $meta_provinsi = $request->meta_data;
            $meta_data_narkoba =[];
            $meta_data_provinsi =[];
            $json_data_narkoba = "";
            $json_data_provinsi ="";
            if(count($meta_narkoba) > 0 ){
                foreach($meta_narkoba as $m => $mval){
                        if($mval['jenis_narkoba'] || $mval['jumlah_orang']){
                            $meta_data_narkoba[] = ['jenis_narkoba'=>$mval['jenis_narkoba'] ,'jumlah_orang'=>$mval['jumlah_orang'] ];
                        }
                }
                if(count($meta_data_narkoba) > 0 ){
                    $json_data_narkoba  = json_encode($meta_data_narkoba);
                }else{
                    $json_data_narkoba = "";
                }
            }else{
                $json_data_narkoba  = "";
            }

            if(count($meta_provinsi) > 0 ){
                foreach($meta_provinsi as $p => $pval){
                        if($pval['id_provinsi'] || $pval['list_prevalensi']|| $pval['list_absolut']){
                            $meta_data_provinsi[] = ['id_provinsi'=>$pval['id_provinsi'] ,'list_prevalensi'=>$pval['list_prevalensi'] ,'list_absolut'=>$pval['list_absolut'] ];
                        }
                }
                if(count($meta_data_provinsi) > 0 ){
                    $json_data_provinsi  = json_encode($meta_data_provinsi);
                }else{
                    $json_data_provinsi = "";
                }
            }else{
                $json_data_provinsi  = "";
            }
            $this->form_params['meta_narkoba'] = $json_data_narkoba;
            $this->form_params['meta_data_provinsi'] = $json_data_provinsi;
            $data_request = execute_api_json('api/surveypenyalahguna/','POST',$this->form_params);

            $trail['audit_menu'] = 'Puslitdatin - Bidang Litbang - Survey';
            $trail['audit_event'] = 'post';
            $trail['audit_value'] = json_encode($this->form_params);
            $trail['audit_url'] = $request->url();
            $trail['audit_ip_address'] = $request->ip();
            $trail['audit_user_agent'] = $request->userAgent();
            $trail['audit_message'] = $data_request->comment;
            $trail['created_at'] = date("Y-m-d H:i:s");
            $trail['created_by'] = $request->session()->get('id');

            $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

            if(($data_request->code == 200) && ($data_request->status != 'error')){
                $id = $data_request->data->eventID;
                $this->kelengkapan_survey($id);
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Data Survey Penyalahgunaan Narkoba Berhasil Ditambahkan';
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Data Survey Penyalahgunaan Narkoba Gagal Ditambahkan';
            }
            return redirect('puslitdatin/bidang_litbang/survey')->with('status', $this->messages);
        }
        else{
            $propinsi = execute_api_json('api/propinsi/','get');
            if($propinsi->code == 200 && $propinsi->status != 'error'){
                $this->data['propinsi'] = $propinsi->data;

            }else{
                $this->data['propinsi'] = [];
            }
            $this->data['kelompok_survey'] = config('lookup.kelompok_survey');
            $brgbukti = execute_api('api/jnsbrgbukti','post');
            if($brgbukti['code'] == 200 && $brgbukti['status'] != 'error'){
                $this->data['jenisBrgBuktiNarkotika'] = $brgbukti['data'];
            }else{
                $this->data['jenisBrgBuktiNarkotika'] = [];
            }
            $this->data['breadcrumps'] = breadcrump_litbang($request->route()->getName());
            return view('puslitdatin/litbang/survey/add_survey',$this->data);
        }
    }

    public function surveyNarkoba(Request $request){
    	$kondisi = '';
        if($request->limit) {
            $this->limit = $request->limit;
        } else {
            $this->limit = config('app.limit');
        }

        if($request->isMethod('post')){
          $post = $request->except(['_token','year_from','year_to','jml_to','jml_from']);
          $tipe = $request->tipe;
          $year_from = $request->year_from;
          $year_to = $request->year_to;
          $jml_to = $request->jml_to;
          $jml_from = $request->jml_from;
          $angka_from = $request->angka_from;
          $angka_to = $request->angka_to;
          if($tipe){
            $kondisi .= '&tipe='.$tipe;
            $this->selected['tipe'] = $tipe;
          }
          if($tipe == 'periode'){
            if($year_from){
                $this->selected['year_from'] = $year_from;
                $kondisi .='&year_from='.$year_from;
            }else if(!$year_from){
                $kondisi .='';
            }
            if($year_to){
              $this->selected['year_to'] = $year_to;
              $kondisi .='&year_to='.$year_to;
            }else if(!$year_to){
              $kondisi .='';
            }
          }else if($tipe == 'jml_responden'){
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
          }else if($tipe == 'angka'){
            if($angka_from){
              $kondisi .= '&angka_from='.$angka_from;
              $this->selected['angka_from'] = $angka_from;
            }else if(!$angka_from){
                $kondisi .='';
            }
            if($angka_to){
              $kondisi .= '&angka_to='.$angka_to;
              $this->selected['angka_to'] = $angka_to;
            }else if(!$angka_to){
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
          $get = $request->except(['page','year_from','year_to','limit','jml_to','jml_from']);
          $tipe = $request->tipe;
          $jml_to = $request->jml_to;
          $jml_from = $request->jml_from;
          $angka_from = $request->angka_from;
          $angka_to = $request->angka_to;
          if(count($get)>0){
            if($tipe){
              $this->selected['tipe']  = $tipe;
            }
            foreach ($get as $key => $value) {
              $kondisi .= "&".$key.'='.$value;
              if($value == 'periode'){
                if($request->year_from){
                    $kondisi .= "&year_from".'='.$request->year_from;
                    $this->selected['year_from'] = $request->year_from;
                }
                if($request->year_to){
                  $kondisi .= "&year_to".'='.$request->year_to;
                  $this->selected['year_to'] = $request->year_to;
                }
              }else if($value == 'jml_responden'){
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
              }else if($value == 'angka'){
                if($angka_from){
                  $kondisi .= '&angka_from='.$angka_from;
                  $this->selected['angka_from'] = $angka_from;
                }else if(!$angka_from){
                    $kondisi .='';
                }
                if($angka_to){
                  $kondisi .= '&angka_to='.$angka_to;
                  $this->selected['angka_to'] = $angka_to;
                }else if(!$angka_to){
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

        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }

        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;

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

    	$this->data['title'] = 'Puslitdatin';


    	$datas = execute_api_json('api/surveypenyalahgunanarkoba?'.$limit.'&'.$offset.$kondisi,'get');
  		if(($datas->code == 200) && ($datas->code != 'error')){
  			$this->data['data'] = $datas->data;
              $total_item = $datas->paginate->totalpage * $this->limit;
  		}else{
  			$this->data['data'] = [];
              $total_item = 0;
  		}
        $jenisBrgBuktiNarkotika = execute_api('api/jnsbrgbukti','post');
        if($jenisBrgBuktiNarkotika['code'] == 200 && $jenisBrgBuktiNarkotika['status'] != 'error'){
            $this->data['jenisBrgBuktiNarkotika'] = $jenisBrgBuktiNarkotika['data'];
        }else{
            $this->data['jenisBrgBuktiNarkotika'] = [];
        }
        $this->data['delete_route'] = 'delete_survey_narkoba';
        $this->data['path'] = $request->path();
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;
        $this->data['breadcrumps'] = breadcrump_litbang($request->route()->getName());
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
    	return view('puslitdatin/litbang/survey_narkoba/index_surveyNarkoba',$this->data);
    }

    public function editSurveyNarkoba(Request $request){
    	$id = $request->id;
    	$datas = execute_api_json('api/surveypenyalahgunanarkoba/'.$id,'get');
        if(($datas->code == 200) && ($datas->code != 'error') ){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }
        $propinsi = execute_api_json('api/propinsi/','get');
        if(($propinsi->code == 200) && ($propinsi->code != 'error') ){
            $this->data['propinsi'] = $propinsi->data;
        }else{
            $this->data['propinsi'] = [];
        }
        $jenisBrgBuktiNarkotika = execute_api('api/jnsbrgbukti','post');
        if($jenisBrgBuktiNarkotika['code'] == 200 && $jenisBrgBuktiNarkotika['status'] != 'error'){
            $this->data['jenisBrgBuktiNarkotika'] = $jenisBrgBuktiNarkotika['data'];
        }else{
            $this->data['jenisBrgBuktiNarkotika'] = [];
        }
        $this->data['breadcrumps'] = breadcrump_litbang($request->route()->getName());
    	return view('puslitdatin/litbang/survey_narkoba/edit_surveyNarkoba',$this->data);
    }

    public function updateSurveyNarkoba(Request $request){
        $id = $request->input('id');
        $token = $request->session()->get('token');
        $meta_narkoba = [];
        $meta_data_provinsi =[];
        $this->form_params = $request->except(['_token', 'id','meta_narkoba','meta_data_provinsi']);
        if(count($request->meta_narkoba) > 0 ){
            foreach($request->meta_narkoba as $m => $val){
                if($val['jenisKasus'] || $val['jumlah_prosentase'] || $val['jumlah_orang']){
                    $meta_narkoba[] = ['jenisKasus' => $val['jenisKasus'],'jumlah_prosentase' => $val['jumlah_prosentase'],'jumlah_orang' => $val['jumlah_orang'] ];
                }
            }
            if(count($meta_narkoba) > 0 ){
                $json_meta_narkoba = json_encode($meta_narkoba);
                $this->form_params['meta_data_narkoba'] = $json_meta_narkoba;
            }
        }else{
            $json_meta_narkoba = "";
        }

        if(count($request->meta_data_provinsi) > 0 ){
            foreach($request->meta_data_provinsi as $p => $pval){
                if($pval['id_provinsi'] || $pval['list_prevalensi']|| $pval['list_absolut']  || $pval['proyeksi_prevalensi_1']|| $pval['proyeksi_prevalensi_2']|| $pval['proyeksi_prevalensi_3']|| $pval['proyeksi_prevalensi_4']|| $pval['proyeksi_prevalensi_5']|| $pval['kerugian'] || $pval['proyeksi_kerugian_1']|| $pval['proyeksi_kerugian_2']|| $pval['proyeksi_kerugian_3']|| $pval['proyeksi_kerugian_4']|| $pval['proyeksi_kerugian_5']){
                    $meta_data_provinsi[] = ['id_provinsi'=>$pval['id_provinsi'] ,'list_prevalensi'=>$pval['list_prevalensi'] ,'list_absolut'=>$pval['list_absolut'] ,
                    'proyeksi_prevalensi_1' => $pval['proyeksi_prevalensi_1'],'proyeksi_prevalensi_2' => $pval['proyeksi_prevalensi_2'],'proyeksi_prevalensi_3' => $pval['proyeksi_prevalensi_3'],'proyeksi_prevalensi_4' => $pval['proyeksi_prevalensi_4'],'proyeksi_prevalensi_5' => $pval['proyeksi_prevalensi_5'],
                    'kerugian' => $pval['kerugian'],'proyeksi_kerugian_1' => $pval['proyeksi_kerugian_1'],'proyeksi_kerugian_2' => $pval['proyeksi_kerugian_2'],'proyeksi_kerugian_3' => $pval['proyeksi_kerugian_3'],'proyeksi_kerugian_4' => $pval['proyeksi_kerugian_4'],'proyeksi_kerugian_5' => $pval['proyeksi_kerugian_5']];
                }
            }
            if(count($meta_data_provinsi) > 0 ){
                $json_data_provinsi  = json_encode($meta_data_provinsi);
                $this->form_params['meta_data_provinsi'] = $json_data_provinsi;
            }else{
                $json_data_provinsi = "";
            }
        }else{
            $json_data_provinsi  = "";
        }

        $data_request = execute_api_json('api/surveypenyalahgunanarkoba/'.$id,'PUT',$this->form_params);

        $trail['audit_menu'] = 'Puslitdatin - Bidang Litbang - Survey Nasional Penyalahgunaan Narkoba';
        $trail['audit_event'] = 'put';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $data_request->comment;
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

        if( ($data_request->code == 200) && ($data_request->status != 'error')){
            $this->kelengkapan_survey_narkoba($id);
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Survey Penyalahgunaan Narkoba Berhasil Diperbarui';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Survey Penyalahgunaan Narkoba Gagal Diperbarui';
        }
        return back()->with('status', $this->messages);
    }

    public function deleteSurveyNarkoba(Request $request){
        if ($request->ajax()) {
            $id = $request->id;
            if($id){
                $data_request = execute_api('api/surveypenyalahgunanarkoba/'.$id,'DELETE');
                $this->form_params['delete_id'] = $id;
                $trail['audit_menu'] = 'Puslitdatin - Bidang Litbang - Survey Nasional Penyalahgunaan Narkoba';
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

    public function addSurveyNarkoba(Request $request){
        if ($request->isMethod('post')) {
            $token = $request->session()->get('token');
            $this->form_params = $request->except(['_token','meta_narkoba','meta_data_provinsi']);
            $meta_narkoba = [];
            $meta_data_provinsi =[];
            $json_data_narkoba = "";
            $json_data_provinsi ="";
            if(count($request->meta_narkoba) > 0 ){
                foreach($request->meta_narkoba as $m => $val){
                    if($val['jenisKasus'] || $val['jumlah_prosentase'] || $val['jumlah_orang'] ){
                        $meta_narkoba[] = ['jenisKasus' => $val['jenisKasus'],'jumlah_prosentase' => $val['jumlah_prosentase'],'jumlah_orang' => $val['jumlah_orang'] ];
                    }
                }
                if(count($meta_narkoba) > 0 ){
                    $json_meta_narkoba = json_encode($meta_narkoba);
                    $this->form_params['meta_data_narkoba'] = $json_meta_narkoba;
                }
            }else{
                $json_meta_narkoba = "";
            }

            if(count($request->meta_data_provinsi) > 0 ){
                foreach($request->meta_data_provinsi as $p => $pval){
                        if($pval['id_provinsi'] || $pval['list_prevalensi']|| $pval['list_absolut']  || $pval['proyeksi_prevalensi_1']|| $pval['proyeksi_prevalensi_2']|| $pval['proyeksi_prevalensi_3']|| $pval['proyeksi_prevalensi_4']|| $pval['proyeksi_prevalensi_5']|| $pval['kerugian'] || $pval['proyeksi_kerugian_1']|| $pval['proyeksi_kerugian_2']|| $pval['proyeksi_kerugian_3']|| $pval['proyeksi_kerugian_4']|| $pval['proyeksi_kerugian_5']){
                            $meta_data_provinsi[] = ['id_provinsi'=>$pval['id_provinsi'] ,'list_prevalensi'=>$pval['list_prevalensi'] ,'list_absolut'=>$pval['list_absolut'] ,
                            'proyeksi_prevalensi_1' => $pval['proyeksi_prevalensi_1'],'proyeksi_prevalensi_2' => $pval['proyeksi_prevalensi_2'],'proyeksi_prevalensi_3' => $pval['proyeksi_prevalensi_3'],'proyeksi_prevalensi_4' => $pval['proyeksi_prevalensi_4'],'proyeksi_prevalensi_5' => $pval['proyeksi_prevalensi_5'],
                            'kerugian' => $pval['kerugian'],'proyeksi_kerugian_1' => $pval['proyeksi_kerugian_1'],'proyeksi_kerugian_2' => $pval['proyeksi_kerugian_2'],'proyeksi_kerugian_3' => $pval['proyeksi_kerugian_3'],'proyeksi_kerugian_4' => $pval['proyeksi_kerugian_4'],'proyeksi_kerugian_5' => $pval['proyeksi_kerugian_5']];
                        }
                }
                if(count($meta_data_provinsi) > 0 ){
                    $json_data_provinsi  = json_encode($meta_data_provinsi);
                    $this->form_params['meta_data_provinsi'] = $json_data_provinsi;
                }else{
                    $json_data_provinsi = "";
                }
            }else{
                $json_data_provinsi  = "";
            }
            $data_request = execute_api_json('api/surveypenyalahgunanarkoba/','POST',$this->form_params);

            $trail['audit_menu'] = 'Puslitdatin - Bidang Litbang - Survey Nasional Penyalahgunaan Narkoba';
            $trail['audit_event'] = 'post';
            $trail['audit_value'] = json_encode($this->form_params);
            $trail['audit_url'] = $request->url();
            $trail['audit_ip_address'] = $request->ip();
            $trail['audit_user_agent'] = $request->userAgent();
            $trail['audit_message'] = $data_request->comment;
            $trail['created_at'] = date("Y-m-d H:i:s");
            $trail['created_by'] = $request->session()->get('id');

            $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

            if(($data_request->code == 200)&& ($data_request->status != 'error')){
                $id = $data_request->data->eventID;
                $this->kelengkapan_survey_narkoba($id);
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Data Survey Penyalahgunaan Narkoba Berhasil Ditambahkan';
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Data Survey Penyalahgunaan Narkoba Gagal Ditambahkan';
            }

            return redirect('puslitdatin/bidang_litbang/survey_narkoba')->with('status', $this->messages);
        }
        else{
            $propinsi = execute_api_json('api/propinsi/','get');
            if(($propinsi->code == 200 )&& ($propinsi->code != 'error' )){
                $this->data['propinsi'] = $propinsi->data;

            }else{
                $this->data['propinsi'] = [];
            }

            $jenisBrgBuktiNarkotika = execute_api('api/jnsbrgbukti','post');
            if($jenisBrgBuktiNarkotika['code'] == 200 && $jenisBrgBuktiNarkotika['status'] != 'error'){
                $this->data['jenisBrgBuktiNarkotika'] = $jenisBrgBuktiNarkotika['data'];
            }else{
                $this->data['jenisBrgBuktiNarkotika'] = $brgbukti;
            }
            $this->data['breadcrumps'] = breadcrump_litbang($request->route()->getName());
            return view('puslitdatin/litbang/survey_narkoba/add_surveyNarkoba',$this->data);
        }
    }

    public function surveyNarkobaKetergantungan(Request $request){
        $kondisi = '';
        if($request->limit) {
            $this->limit = $request->limit;
        } else {
            $this->limit = config('app.limit');
        }

        if($request->isMethod('post')){
          $post = $request->except(['_token','year_from','year_to','jml_to','jml_from']);
          $tipe = $request->tipe;
          $year_from = $request->year_from;
          $year_to = $request->year_to;
          $jml_to = $request->jml_to;
          $jml_from = $request->jml_from;
          $angka_from = $request->angka_from;
          $angka_to = $request->angka_to;
          if($tipe){
            $kondisi .= '&tipe='.$tipe;
            $this->selected['tipe'] = $tipe;
          }
          if($tipe == 'periode'){
            if($year_from){
                $this->selected['year_from'] = $year_from;
                $kondisi .='&year_from='.$year_from;
            }else if(!$year_from){
                $kondisi .='';
            }
            if($year_to){
              $this->selected['year_to'] = $year_to;
              $kondisi .='&year_to='.$year_to;
            }else if(!$year_to){
              $kondisi .='';
            }
          }else if($tipe == 'jml_responden'){
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
          $get = $request->except(['page','year_from','year_to','limit','jml_to','jml_from']);
          $tipe = $request->tipe;
          $jml_to = $request->jml_to;
          $jml_from = $request->jml_from;
          $angka_from = $request->angka_from;
          $angka_to = $request->angka_to;
          if(count($get)>0){
            if($tipe){
              $this->selected['tipe']  = $tipe;
            }
            foreach ($get as $key => $value) {
              $kondisi .= "&".$key.'='.$value;
              if($value == 'periode'){
                if($request->year_from){
                    $kondisi .= "&year_from".'='.$request->year_from;
                    $this->selected['year_from'] = $request->year_from;
                }
                if($request->year_to){
                  $kondisi .= "&year_to".'='.$request->year_to;
                  $this->selected['year_to'] = $request->year_to;
                }
              }else if($value == 'jml_responden'){
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
              }else if($value == 'angka'){
                if($angka_from){
                  $kondisi .= '&angka_from='.$angka_from;
                  $this->selected['angka_from'] = $angka_from;
                }else if(!$angka_from){
                    $kondisi .='';
                }
                if($angka_to){
                  $kondisi .= '&angka_to='.$angka_to;
                  $this->selected['angka_to'] = $angka_to;
                }else if(!$angka_to){
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

        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }

        $filtering = false;

        $this->data['route'] = $request->route()->getName();


        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;


        $datas = execute_api_json('api/surveypenyalahgunaketergantungan?'.$limit.'&'.$offset.$kondisi,'get');
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;
        }else{
            $this->data['data'] = [];
            $total_item = 0;
        }

        if($kondisi){
          $filter = '&'.$limit.$kondisi;
          $filtering = true;
          $this->data['kondisi'] = '?'.$offset.$kondisi.'&'.$limit;
        }else{
          $filter = '/';
          $filtering = false;
          $this->data['kondisi'] = $current_page;
        }
        $brgbukti = execute_api('api/jnsbrgbukti','post');
        if($brgbukti['code'] == 200 && $brgbukti['status'] != 'error'){
            $this->data['jenisBrgBuktiNarkotika'] = $brgbukti['data'];
        }else{
            $this->data['jenisBrgBuktiNarkotika'] = [];
        }
        $this->data['title'] = 'Puslitdatin';
        $this->data['kategori'] = config('lookup.kategori_penyalahgunaan');
        $this->data['delete_route'] = 'delete_survey_narkoba_ketergantungan';
        $this->data['path'] = $request->path();
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;
        $this->data['breadcrumps'] = breadcrump_litbang($request->route()->getName());
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
        return view('puslitdatin/litbang/survey_narkoba_ketergantungan/index_surveyNarkobaKetergantungan',$this->data);
    }

    public function editSurveyNarkobaKetergantungan(Request $request){
        $id = $request->id;
        $datas = execute_api_json('api/surveypenyalahgunaketergantungan/'.$id,'get');
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }
        $propinsi = execute_api_json('api/propinsi/','get');
        if($propinsi->code == 200){
            $this->data['propinsi'] = $propinsi->data;
        }else{
            $this->data['propinsi'] = [];
        }
        $this->data['kategori'] = config('lookup.kategori_penyalahgunaan');
        $brgbukti = execute_api('api/jnsbrgbukti','post');
        if($brgbukti['code'] == 200 && $brgbukti['status'] != 'error'){
            $this->data['jenisBrgBuktiNarkotika'] = $brgbukti;
        }else{
            $this->data['jenisBrgBuktiNarkotika'] = [];
        }
        $this->data['breadcrumps'] = breadcrump_litbang($request->route()->getName());
        return view('puslitdatin/litbang/survey_narkoba_ketergantungan/edit_surveyNarkobaKetergantungan',$this->data);
    }

    public function updateSurveyNarkobaKetergantungan(Request $request){
        $id = $request->input('id');
        $token = $request->session()->get('token');

        $this->form_params = $request->except(['_token','id','meta_narkoba']);
        $meta_narkoba = [];
        $meta_data_provinsi = [];
        $json_meta_narkoba = "";
        if(count($request->meta_narkoba) > 0 ){
            foreach($request->meta_narkoba as $m => $val){
                if($val['jenis_narkoba'] || $val['jumlah_orang'] ){
                    $meta_narkoba[] = ['jenis_narkoba' => $val['jenis_narkoba'],'jumlah_orang' => $val['jumlah_orang']];
                }
            }
            if(count($meta_narkoba) > 0 ){
                $json_meta_narkoba = json_encode($meta_narkoba);
                $this->form_params['meta_data_narkoba'] = $json_meta_narkoba;
            }
        }else{
            $json_meta_narkoba = "";
        }

        if(count($request->meta_data_provinsi) > 0 ){
            foreach($request->meta_data_provinsi as $p => $pval){
              if($pval['id_provinsi'] || $pval['list_prevalensi']|| $pval['list_absolut']|| $pval['proyeksi_prevalensi_1']|| $pval['proyeksi_prevalensi_2']|| $pval['proyeksi_prevalensi_3']|| $pval['proyeksi_prevalensi_4']|| $pval['proyeksi_prevalensi_5']){
                  $meta_data_provinsi[] = ['id_provinsi'=>$pval['id_provinsi'] ,'list_prevalensi'=>$pval['list_prevalensi'] ,'list_absolut'=>$pval['list_absolut'],
                    'proyeksi_prevalensi_1'=>$pval['proyeksi_prevalensi_1'], 'proyeksi_prevalensi_2'=>$pval['proyeksi_prevalensi_2'], 'proyeksi_prevalensi_3'=>$pval['proyeksi_prevalensi_3'], 'proyeksi_prevalensi_4'=>$pval['proyeksi_prevalensi_4'], 'proyeksi_prevalensi_5'=>$pval['proyeksi_prevalensi_5']];
              }
            }
            if(count($meta_data_provinsi) > 0 ){
                $json_data_provinsi  = json_encode($meta_data_provinsi);
                $this->form_params['meta_data_provinsi'] = $json_data_provinsi;
            }else{
                $json_data_provinsi = "";
            }
        }else{
            $json_data_provinsi  = "";
        }
        $data_request = execute_api_json('api/surveypenyalahgunaketergantungan/'.$id,'PUT',$this->form_params);

        $trail['audit_menu'] = 'Puslitdatin - Bidang Litbang - Survey Nasional Berdasarkan Tingkat Ketergantungan';
        $trail['audit_event'] = 'put';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $data_request->comment;
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

        if(($data_request->code == 200) && ($data_request->status != 'error')){
            $this->kelengkapan_survey_ketergantungan($id);
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Survey Penyalahgunaan Narkoba Ketergantungan Berhasil Diperbarui';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Survey Penyalahgunaan Narkoba Ketergantungan Gagal Diperbarui';
        }
        return back()->with('status', $this->messages);
    }

    public function deleteSurveyNarkobaKetergantungan(Request $request){
        if ($request->ajax()) {
            $id = $request->id;
            if($id){
                $data_request = execute_api('api/surveypenyalahgunaketergantungan/'.$id,'DELETE');
                $this->form_params['delete_id'] = $id;
                $trail['audit_menu'] = 'Puslitdatin - Bidang Litbang - Survey Nasional Berdasarkan Tingkat Ketergantungan';
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

    public function addSurveyNarkobaKetergantungan(Request $request){
        if ($request->isMethod('post')) {
            $token = $request->session()->get('token');
            $this->form_params = $request->except(['_token','meta_narkoba']);
            $meta_narkoba = [];
            $meta_data_provinsi =[];
            $json_meta_narkoba = "";
            $json_data_provinsi = "";
            if(count($request->meta_narkoba) > 0 ){
                foreach($request->meta_narkoba as $m => $val){
                    if($val['jenis_narkoba'] || $val['jumlah_orang'] ){
                        $meta_narkoba[] = ['jenis_narkoba' => $val['jenis_narkoba'],'jumlah_orang' => $val['jumlah_orang']];
                    }
                }
                if(count($meta_narkoba) > 0 ){
                    $json_meta_narkoba = json_encode($meta_narkoba);
                    $this->form_params['meta_data_narkoba'] = $json_meta_narkoba;
                }
            }else{
                $json_meta_narkoba = "";
            }

            if(count($request->meta_data_provinsi) > 0 ){
                foreach($request->meta_data_provinsi as $p => $pval){
                        if($pval['id_provinsi'] || $pval['list_prevalensi']|| $pval['list_absolut']|| $pval['proyeksi_prevalensi_1']|| $pval['proyeksi_prevalensi_2']|| $pval['proyeksi_prevalensi_3']|| $pval['proyeksi_prevalensi_4']|| $pval['proyeksi_prevalensi_5']){
                            $meta_data_provinsi[] = ['id_provinsi'=>$pval['id_provinsi'] ,'list_prevalensi'=>$pval['list_prevalensi'] ,'list_absolut'=>$pval['list_absolut'],
                              'proyeksi_prevalensi_1'=>$pval['proyeksi_prevalensi_1'], 'proyeksi_prevalensi_2'=>$pval['proyeksi_prevalensi_2'], 'proyeksi_prevalensi_3'=>$pval['proyeksi_prevalensi_3'], 'proyeksi_prevalensi_4'=>$pval['proyeksi_prevalensi_4'], 'proyeksi_prevalensi_5'=>$pval['proyeksi_prevalensi_5']];
                        }
                }
                if(count($meta_data_provinsi) > 0 ){
                    $json_data_provinsi  = json_encode($meta_data_provinsi);
                    $this->form_params['meta_data_provinsi'] = $json_data_provinsi;
                }else{
                    $json_data_provinsi = "";
                }
            }else{
                $json_data_provinsi  = "";
            }
            $data_request = execute_api_json('api/surveypenyalahgunaketergantungan/','POST',$this->form_params);

            $trail['audit_menu'] = 'Puslitdatin - Bidang Litbang - Survey Nasional Berdasarkan Tingkat Ketergantungan';
            $trail['audit_event'] = 'post';
            $trail['audit_value'] = json_encode($this->form_params);
            $trail['audit_url'] = $request->url();
            $trail['audit_ip_address'] = $request->ip();
            $trail['audit_user_agent'] = $request->userAgent();
            $trail['audit_message'] = $data_request->comment;
            $trail['created_at'] = date("Y-m-d H:i:s");
            $trail['created_by'] = $request->session()->get('id');

            $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

            if(($data_request->code == 200) && ($data_request->status != 'error')){
                $id = $data_request->data->eventID;
                $this->kelengkapan_survey_ketergantungan($id);
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Data Survey Penyalahgunaan Narkoba Ketergantungan Berhasil Ditambahkan';
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Data Survey Penyalahgunaan Narkoba Ketergantungan Gagal Ditambahkan';
            }
            return redirect(route('survey_narkoba_ketergantungan'))->with('status', $this->messages);
        }
        else{
            $propinsi = execute_api_json('api/propinsi/','get');
            if($propinsi->code == 200){
                $this->data['propinsi'] = $propinsi->data;

            }else{
                $this->data['propinsi'] = [];
            }
            $this->data['kategori'] = config('lookup.kategori_penyalahgunaan');
            $brgbukti = execute_api('api/jnsbrgbukti','post');
            if($brgbukti['code'] == 200 && $brgbukti['status'] != 'error'){
                $this->data['jenisBrgBuktiNarkotika'] = $brgbukti;
            }else{
                $this->data['jenisBrgBuktiNarkotika'] = [];
            }
            $this->data['breadcrumps'] = breadcrump_litbang($request->route()->getName());
            return view('puslitdatin/litbang/survey_narkoba_ketergantungan/add_surveyNarkobaKetergantungan',$this->data);
        }
    }


    public function penyalahgunaanCobaPakai(Request $request){
        $this->limit = config('app.limit');

        $this->data['title'] = 'Puslitdatin';
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
        $datas = execute_api_json('api/cobapakai?'.$limit.'&'.$offset,'get');
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }
        $total_item = $datas->paginate->totalpage * $this->limit;
        $this->data['breadcrumps'] = breadcrump_litbang($request->route()->getName());
        $this->data['start_number'] = $start_number;
        $this->data['path'] = $request->path();
        $this->data['delete_route'] = 'delete_penyalahgunaan_coba_pakai';
        $this->data['current_page'] = $current_page;
        $this->data['pagination'] = pagination($current_page,$total_item, $this->limit, config('app.page_ellipsis'), "/puslitdatin/bidang_litbang/penyalahgunaan_coba_pakai/%d");
        return view('puslitdatin/litbang/penyalahgunaan_cobaPakai/index_penyalahgunaanCobaPakai',$this->data);
    }

    public function editPenyalahgunaanCobaPakai(Request $request){
        $id = $request->id;
        $datas = execute_api_json('api/cobapakai/'.$id,'get');
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }
        $this->data['breadcrumps'] = breadcrump_litbang($request->route()->getName());
        $propinsi = execute_api_json('api/propinsi/','get');
        if($propinsi->code == 200){
            $this->data['propinsi'] = $propinsi->data;
        }else{
            $this->data['propinsi'] = [];
        }
        return view('puslitdatin/litbang/penyalahgunaan_cobaPakai/edit_penyalahgunaanCobaPakai',$this->data);

    }

    public function addPenyalahgunaanCobaPakai(Request $request){
        if ($request->isMethod('post')) {
            $token = $request->session()->get('token');
            $this->form_params = $request->except(['_token']);
            $populasi = $request->input('populasi');
            $populasi = str_replace(',', '', $populasi);
            $jumlah_penyalahguna = $request->input('jumlah_penyalahguna');
            $jumlah_penyalahguna = str_replace(',', '', $jumlah_penyalahguna);

            // $prevalensi = $request->input('prevalensi');
            // $prevalensi = str_replace(',', '.', $prevalensi);

            $this->form_params['populasi'] = $populasi;
            $this->form_params['jumlah_penyalahguna'] = $jumlah_penyalahguna;
            $data_request = execute_api_json('api/cobapakai/','POST',$this->form_params);
            if( ($data_request->code == 200) && ($data_request->status != 'error') ){
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Data Survey Penyalahgunaan Narkoba Berhasil Ditambahkan';
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Data Survey Penyalahgunaan Narkoba Gagal Ditambahkan';
            }
            return redirect('puslitdatin/bidang_litbang/penyalahgunaan_coba_pakai')->with('status', $this->messages);
        }
        else{
            $this->data['breadcrumps'] = breadcrump_litbang($request->route()->getName());
            $propinsi = execute_api_json('api/propinsi/','get');
            if($propinsi->code == 200){
                $this->data['propinsi'] = $propinsi->data;
            }else{
                $this->data['propinsi'] = [];
            }
            return view('puslitdatin/litbang/penyalahgunaan_cobaPakai/add_penyalahgunaanCobaPakai',$this->data);
        }
    }

    public function updatePenyalahgunaanCobaPakai(Request $request){
        $id = $request->input('id');
        $token = $request->session()->get('token');

        $this->form_params = $request->except(['_token', 'id']);
        $populasi = $request->input('populasi');
        $populasi = str_replace(',', '', $populasi);
        $jumlah_penyalahguna = $request->input('jumlah_penyalahguna');
        $jumlah_penyalahguna = str_replace(',', '', $jumlah_penyalahguna);
        $this->form_params['populasi'] = $populasi;
        $this->form_params['jumlah_penyalahguna'] = $jumlah_penyalahguna;
        $data_request = execute_api_json('api/cobapakai/'.$id,'PUT',$this->form_params);

        if(($data_request->code == 200)&& ($data_request->status != "error") ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Penyalahguna Narkoba Coba Pakai Berhasil Diperbarui';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Penyalahguna Narkoba Coba Pakai Gagal Diperbarui';
        }
        return back()->with('status', $this->messages);
    }

    public function deletePenyalahgunaanCobaPakai(Request $request){
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/cobapakai/'.$id,'DELETE',$this->form_params);
            return $data_request;
        }
    }

    public function penyalahgunaTeraturPakai(Request $request){
        $this->limit = config('app.limit');

        $this->data['title'] = 'Puslitdatin';
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        $this->data['start_number'] = $start_number;
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
        $datas = execute_api_json('api/teraturpakai?'.$limit.'&'.$offset,'get');
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }
        $this->data['breadcrumps'] = breadcrump_litbang($request->route()->getName());
        $this->data['path'] = $request->path();
        $this->data['delete_route'] = 'delete_penyalahguna_teratur_pakai';
        $this->data['current_page'] = $current_page;
        $total_item = $datas->paginate->totalpage * $this->limit;
        $this->data['pagination'] = pagination($current_page,$total_item, $this->limit, config('app.page_ellipsis'), "/puslitdatin/bidang_litbang/penyalahguna_teratur_pakai/%d");
        return view('puslitdatin/litbang/penyalahguna_teraturPakai/index_penyalahgunaTeraturPakai',$this->data);
    }

    public function editPenyalahgunaTeraturPakai(Request $request){
        $id = $request->id;
        $datas = execute_api_json('api/teraturpakai/'.$id,'get');
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }
        $this->data['breadcrumps'] = breadcrump_litbang($request->route()->getName());
        $propinsi = execute_api_json('api/propinsi/','get');
        if($propinsi->code == 200){
            $this->data['propinsi'] = $propinsi->data;
        }else{
            $this->data['propinsi'] = [];
        }
        return view('puslitdatin/litbang/penyalahguna_teraturPakai/edit_penyalahgunaTeraturPakai',$this->data);

    }

    public function addPenyalahgunaTeraturPakai(Request $request){
        if ($request->isMethod('post')) {
            $token = $request->session()->get('token');
            $this->form_params = $request->except(['_token']);
            $populasi = $request->input('populasi');
            $populasi = str_replace(',', '', $populasi);
            $jumlah_penyalahguna = $request->input('jumlah_penyalahguna');
            $jumlah_penyalahguna = str_replace(',', '', $jumlah_penyalahguna);
            $this->form_params['populasi'] = $populasi;
            $this->form_params['jumlah_penyalahguna'] = $jumlah_penyalahguna;
            $data_request = execute_api_json('api/teraturpakai/','POST',$this->form_params);
            if( ($data_request->code == 200) && ($data_request->status != 'error') ){
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Data Survey Penyalahgunaan Narkoba Teratur Pakai Berhasil Ditambahkan';
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Data Survey Penyalahgunaan Narkoba Teratur Pakai Gagal Ditambahkan';
            }
            return redirect('puslitdatin/bidang_litbang/penyalahguna_teratur_pakai')->with('status', $this->messages);
        }
        else{
            $this->data['breadcrumps'] = breadcrump_litbang($request->route()->getName());
            $propinsi = execute_api_json('api/propinsi/','get');
            if($propinsi->code == 200){
                $this->data['propinsi'] = $propinsi->data;
            }else{
                $this->data['propinsi'] = [];
            }
            return view('puslitdatin/litbang/penyalahguna_teraturPakai/add_penyalahgunaTeraturPakai',$this->data);
        }
    }

    public function updatePenyalahgunaTeraturPakai(Request $request){
        $id = $request->input('id');
        $token = $request->session()->get('token');

        $this->form_params = $request->except(['_token', 'id']);
        $populasi = $request->input('populasi');
        $populasi = str_replace(',', '', $populasi);
        $jumlah_penyalahguna = $request->input('jumlah_penyalahguna');
        $jumlah_penyalahguna = str_replace(',', '', $jumlah_penyalahguna);
        $this->form_params['populasi'] = $populasi;
        $this->form_params['jumlah_penyalahguna'] = $jumlah_penyalahguna;
        $data_request = execute_api_json('api/teraturpakai/'.$id,'PUT',$this->form_params);

        if(($data_request->code == 200)&& ($data_request->status != "error") ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Penyalahguna Narkoba Teratur Pakai Berhasil Diperbarui';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Penyalahguna Narkoba Teratur Pakai Gagal Diperbarui';
        }
        return back()->with('status', $this->messages);
    }

    public function deletePenyalahgunaTeraturPakai(Request $request){
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/teraturpakai/'.$id,'DELETE',$this->form_params);
            return $data_request;
        }
    }


    public function penyalahgunaPecanduNonSuntik(Request $request){
        $this->limit = config('app.limit');

        $this->data['title'] = 'Puslitdatin';
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        $this->data['start_number'] = $start_number;
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
        $datas = execute_api_json('api/pecandunonsuntik?'.$limit.'&'.$offset,'get');
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }
        $this->data['path'] = $request->path();
        $this->data['delete_route'] = 'delete_penyalahguna_pecandu_non_suntik';
        $this->data['current_page'] = $current_page;
        $total_item = $datas->paginate->totalpage * $this->limit;
        $this->data['pagination'] = pagination($current_page,$total_item, $this->limit, config('app.page_ellipsis'), "/puslitdatin/bidang_litbang/penyalahguna_pecandu_non_suntik/%d");
        $this->data['breadcrumps'] = breadcrump_litbang($request->route()->getName());
        return view('puslitdatin/litbang/penyalahguna_pecanduNonSuntik/index_penyalahgunaPecanduNonSuntik',$this->data);
    }

    public function editPenyalahgunaPecanduNonSuntik(Request $request){
        $id = $request->id;
        $datas = execute_api_json('api/pecandunonsuntik/'.$id,'get');
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }
        $propinsi = execute_api_json('api/propinsi/','get');
        if($propinsi->code == 200){
            $this->data['propinsi'] = $propinsi->data;
        }else{
            $this->data['propinsi'] = [];
        }
        $this->data['breadcrumps'] = breadcrump_litbang($request->route()->getName());
        return view('puslitdatin/litbang/penyalahguna_pecanduNonSuntik/edit_penyalahgunaPecanduNonSuntik',$this->data);

    }

    public function addPenyalahgunaPecanduNonSuntik(Request $request){
        if ($request->isMethod('post')) {
            $token = $request->session()->get('token');
            $this->form_params = $request->except(['_token']);
            $populasi = $request->input('populasi');
            $populasi = str_replace(',', '', $populasi);
            $jumlah_penyalahguna = $request->input('jumlah_penyalahguna');
            $jumlah_penyalahguna = str_replace(',', '', $jumlah_penyalahguna);
            $this->form_params['populasi'] = $populasi;
            $this->form_params['jumlah_penyalahguna'] = $jumlah_penyalahguna;
            $data_request = execute_api_json('api/pecandunonsuntik/','POST',$this->form_params);

            if( ($data_request->code == 200) && ($data_request->status != 'error') ){
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Data Penyalahgunaan Narkoba Pecandu Non Suntik Berhasil Ditambahkan';
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Data Penyalahgunaan Narkoba Narkoba Pecandu Non Suntik Gagal Ditambahkan';
            }
            return redirect('puslitdatin/bidang_litbang/penyalahguna_pecandu_non_suntik')->with('status', $this->messages);
        }
        else{
            $propinsi = execute_api_json('api/propinsi/','get');
            if($propinsi->code == 200){
                $this->data['propinsi'] = $propinsi->data;
            }else{
                $this->data['propinsi'] = [];
            }
            $this->data['breadcrumps'] = breadcrump_litbang($request->route()->getName());
            return view('puslitdatin/litbang/penyalahguna_pecanduNonSuntik/add_penyalahgunaPecanduNonSuntik',$this->data);
        }
    }

    public function updatePenyalahgunaPecanduNonSuntik(Request $request){
        $id = $request->input('id');
        $token = $request->session()->get('token');

        $this->form_params = $request->except(['_token', 'id']);
        $populasi = $request->input('populasi');
        $populasi = str_replace(',', '', $populasi);
        $jumlah_penyalahguna = $request->input('jumlah_penyalahguna');
        $jumlah_penyalahguna = str_replace(',', '', $jumlah_penyalahguna);
        $this->form_params['populasi'] = $populasi;
        $this->form_params['jumlah_penyalahguna'] = $jumlah_penyalahguna;
        $data_request = execute_api_json('api/pecandunonsuntik/'.$id,'PUT',$this->form_params);

        if(($data_request->code == 200)&& ($data_request->status != "error") ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Penyalahguna Narkoba Pecandu Non Suntik Berhasil Diperbarui';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Penyalahguna Narkoba Pecandu Non Suntik Gagal Diperbarui';
        }
        return back()->with('status', $this->messages);
    }

    public function deletePenyalahgunaPecanduNonSuntik(Request $request){
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/pecandunonsuntik/'.$id,'DELETE',$this->form_params);
            return $data_request;
        }
    }
    public function penyalahgunaPecanduSuntik(Request $request){
        $this->limit = config('app.limit');

        $this->data['title'] = 'Puslitdatin';
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        $this->data['start_number'] = $start_number;
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
        $datas = execute_api_json('api/pecandusuntik?'.$limit.'&'.$offset,'get');
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }
        $this->data['breadcrumps'] = breadcrump_litbang($request->route()->getName());
        $this->data['path'] = $request->path();
        $this->data['delete_route'] = "delete_penyalahguna_pecandu_suntik";
        $this->data['current_page'] = $current_page;
        $total_item = $datas->paginate->totalpage * $this->limit;
        $this->data['pagination'] = pagination($current_page,$total_item, $this->limit, config('app.page_ellipsis'), "/puslitdatin/bidang_litbang/penyalahguna_pecandu_suntik/%d");
        return view('puslitdatin/litbang/penyalahguna_pecanduSuntik/index_penyalahgunaPecanduSuntik',$this->data);
    }

    public function editPenyalahgunaPecanduSuntik(Request $request){
        $id = $request->id;
        $datas = execute_api_json('api/pecandusuntik/'.$id,'get');
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }
        $propinsi = execute_api_json('api/propinsi/','get');
        if($propinsi->code == 200){
            $this->data['propinsi'] = $propinsi->data;
        }else{
            $this->data['propinsi'] = [];
        }
        $this->data['breadcrumps'] = breadcrump_litbang($request->route()->getName());
        return view('puslitdatin/litbang/penyalahguna_pecanduSuntik/edit_penyalahgunaPecanduSuntik',$this->data);

    }

    public function addPenyalahgunaPecanduSuntik(Request $request){
        if ($request->isMethod('post')) {
            $token = $request->session()->get('token');
            $this->form_params = $request->except(['_token']);
            $populasi = $request->input('populasi');
            $populasi = str_replace(',', '', $populasi);
            $jumlah_penyalahguna = $request->input('jumlah_penyalahguna');
            $jumlah_penyalahguna = str_replace(',', '', $jumlah_penyalahguna);

            $this->form_params['populasi'] = $populasi;
            $this->form_params['jumlah_penyalahguna'] = $jumlah_penyalahguna;
            $data_request = execute_api_json('api/pecandusuntik/','POST',$this->form_params);

            if( ($data_request->code == 200) && ($data_request->status != 'error') ){
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Data Penyalahgunaan Narkoba Pecandu Suntik Berhasil Ditambahkan';
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Data Penyalahgunaan Narkoba Narkoba Pecandu Suntik Gagal Ditambahkan';
            }
            return redirect('puslitdatin/bidang_litbang/penyalahguna_pecandu_suntik')->with('status', $this->messages);
        }
        else{
            $propinsi = execute_api_json('api/propinsi/','get');
            if($propinsi->code == 200){
                $this->data['propinsi'] = $propinsi->data;
            }else{
                $this->data['propinsi'] = [];
            }
            $this->data['breadcrumps'] = breadcrump_litbang($request->route()->getName());
            return view('puslitdatin/litbang/penyalahguna_pecanduSuntik/add_penyalahgunaPecanduSuntik',$this->data);
        }
    }

    public function updatePenyalahgunaPecanduSuntik(Request $request){
        $id = $request->input('id');
        $token = $request->session()->get('token');

        $this->form_params = $request->except(['_token', 'id']);
        $populasi = $request->input('populasi');
        $populasi = str_replace(',', '', $populasi);
        $jumlah_penyalahguna = $request->input('jumlah_penyalahguna');
        $jumlah_penyalahguna = str_replace(',', '', $jumlah_penyalahguna);
        $this->form_params['populasi'] = $populasi;
        $this->form_params['jumlah_penyalahguna'] = $jumlah_penyalahguna;
        $data_request = execute_api_json('api/pecandusuntik/'.$id,'PUT',$this->form_params);

        if(($data_request->code == 200)&& ($data_request->status != "error") ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Penyalahguna Narkoba Pecandu Suntik Berhasil Diperbarui';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Penyalahguna Narkoba Pecandu Suntik Gagal Diperbarui';
        }
        return back()->with('status', $this->messages);
    }

    public function deletePenyalahgunaPecanduSuntik(Request $request){
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/pecandusuntik/'.$id,'DELETE',$this->form_params);
            return $data_request;
        }
    }

    public function penyalahgunaSetahunPakai(Request $request){
        $this->limit = config('app.limit');

        $this->data['title'] = 'Puslitdatin';
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        $this->data['start_number'] = $start_number;
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
        $datas = execute_api_json('api/setahunpakai?'.$limit.'&'.$offset,'get');
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }
        $this->data['path'] = $request->path();
        $this->data['delete_route'] = 'delete_penyalahguna_setahun_pakai';
        $this->data['current_page'] = $current_page;
        $total_item = $datas->paginate->totalpage * $this->limit;
        $this->data['pagination'] = pagination($current_page,$total_item, $this->limit, config('app.page_ellipsis'), "/puslitdatin/bidang_litbang/penyalahguna_setahun_pakai/%d");
        $this->data['breadcrumps'] = breadcrump_litbang($request->route()->getName());
        return view('puslitdatin/litbang/penyalahguna_setahunPakai/index_penyalahgunaSetahunPakai',$this->data);
    }

    public function editPenyalahgunaSetahunPakai(Request $request){
        $id = $request->id;
        $datas = execute_api_json('api/setahunpakai/'.$id,'get');
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }
        $propinsi = execute_api_json('api/propinsi/','get');
        if($propinsi->code == 200){
            $this->data['propinsi'] = $propinsi->data;
        }else{
            $this->data['propinsi'] = [];
        }
        $this->data['breadcrumps'] = breadcrump_litbang($request->route()->getName());
        return view('puslitdatin/litbang/penyalahguna_setahunPakai/edit_penyalahgunaSetahunPakai',$this->data);

    }

    public function addPenyalahgunaSetahunPakai(Request $request){
        if ($request->isMethod('post')) {
            $token = $request->session()->get('token');
            $this->form_params = $request->except(['_token']);
            $populasi = $request->input('populasi');
            $populasi = str_replace(',', '', $populasi);
            $jumlah_penyalahguna = $request->input('jumlah_penyalahguna');
            $jumlah_penyalahguna = str_replace(',', '', $jumlah_penyalahguna);

            $prevalensi = $request->input('prevalensi');
            $prevalensi = str_replace(',', '.', $prevalensi);

            $this->form_params['populasi'] = $populasi;
            $this->form_params['jumlah_penyalahguna'] = $jumlah_penyalahguna;
            $this->form_params['prevalensi'] = $prevalensi;
            $data_request = execute_api_json('api/setahunpakai/','POST',$this->form_params);

            if( ($data_request->code == 200) && ($data_request->status != 'error') ){
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Data Survey Penyalahgunaan Narkoba Setahun Pakai Berhasil Ditambahkan';
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Data Survey Penyalahgunaan Narkoba Setahun Pakai Gagal Ditambahkan';
            }
            return redirect('puslitdatin/bidang_litbang/penyalahguna_setahun_pakai')->with('status', $this->messages);
        }
        else{
            $propinsi = execute_api_json('api/propinsi/','get');
            if($propinsi->code == 200){
                $this->data['propinsi'] = $propinsi->data;
            }else{
                $this->data['propinsi'] = [];
            }
            $this->data['breadcrumps'] = breadcrump_litbang($request->route()->getName());
            return view('puslitdatin/litbang/penyalahguna_setahunPakai/add_penyalahgunaSetahunPakai',$this->data);
        }
    }

    public function updatePenyalahgunaSetahunPakai(Request $request){
        $id = $request->input('id');
        $token = $request->session()->get('token');

        $this->form_params = $request->except(['_token', 'id']);
        $populasi = $request->input('populasi');
        $populasi = str_replace(',', '', $populasi);
        $jumlah_penyalahguna = $request->input('jumlah_penyalahguna');
        $jumlah_penyalahguna = str_replace(',', '', $jumlah_penyalahguna);
        $this->form_params['populasi'] = $populasi;
        $this->form_params['jumlah_penyalahguna'] = $jumlah_penyalahguna;
        $data_request = execute_api_json('api/setahunpakai/'.$id,'PUT',$this->form_params);

        if(($data_request->code == 200)&& ($data_request->status != "error") ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Penyalahguna Narkoba Setahun Pakai Berhasil Diperbarui';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Penyalahguna Narkoba Setahun Pakai Gagal Diperbarui';
        }
        return back()->with('status', $this->messages);
    }

    public function deletePenyalahgunaSetahunPakai(Request $request){
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/setahunpakai/'.$id,'DELETE',$this->form_params);
            return $data_request;
        }
    }


    public function dataPenelitianBNN(Request $request){
        $this->limit = config('app.limit');

        $this->data['title'] = 'Puslitdatin';
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        $this->data['start_number'] = $start_number;
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
        $datas = execute_api_json('api/permintaandata?'.$limit.'&'.$offset,'get');
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }
        $this->data['delete_route'] = 'delete_informasi_melalui_contact_center';
        $this->data['path'] = $request->path();
        $this->data['current_page'] = $current_page;
        $total_item = $datas->paginate->totalpage * $this->limit;
        $this->data['pagination'] = pagination($current_page,$total_item, $this->limit, config('app.page_ellipsis'), "/puslitdatin/bidang_litbang/data_penelitian_bnn/%d");
        $this->data['breadcrumps'] = breadcrump_litbang($request->route()->getName());
        return view('puslitdatin/litbang/data_penelitianBNN/index_dataPenelitianBNN',$this->data);
    }
    public function editDataPenelitianBNN(Request $request){
        $id = $request->id;
        $datas = execute_api_json('api/permintaandata/'.$id,'get');
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }

        $gender = execute_api_json('api/lookup/jenis_kelamin','get');
        $this->data['gender'] = $gender->data;
        $this->data['kode_permintaan'] = config('lookup.kode_carapermintaan');
        $this->data['kode_tujuan'] = config('lookup.kode_tujuan');
        $this->data['dokumen'] = config('lookup.bentuk_dokumen');
        $this->data['breadcrumps'] = breadcrump_litbang($request->route()->getName());
        return view('puslitdatin/litbang/data_penelitianBNN/edit_dataPenelitianBNN',$this->data);
    }
    public function addDataPenelitianBNN(Request $request){
        if ($request->isMethod('post')) {
            $token = $request->session()->get('token');
            $this->form_params = $request->except(['_token']);
            $data_request = execute_api_json('api/permintaandata/','POST',$this->form_params);
            if( ($data_request->code == 200) && ($data_request->status != 'error') ){
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Data Survey Penyalahgunaan Narkoba Setahun Pakai Berhasil Ditambahkan';
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Data Survey Penyalahgunaan Narkoba Setahun Pakai Gagal Ditambahkan';
            }
            return redirect('puslitdatin/bidang_litbang/data_penelitian_bnn')->with('status', $this->messages);
        }else{
            $gender = execute_api_json('api/lookup/jenis_kelamin','get');
            $this->data['gender'] = $gender->data;
            $this->data['kode_permintaan'] = config('lookup.kode_carapermintaan');
            $this->data['kode_tujuan'] = config('lookup.kode_tujuan');
            $this->data['dokumen'] = config('lookup.bentuk_dokumen');
            $this->data['dropdownPropinsiKabupaten'] = dropdownPropinsiKabupaten("",'alamat_idkabkota');
            $this->data['breadcrumps'] = breadcrump_litbang($request->route()->getName());
            return view('puslitdatin/litbang/data_penelitianBNN/add_dataPenelitianBNN',$this->data);
        }
    }
    public function updateDataPenelitianBNN(Request $request){
        $id = $request->input('id');
        $token = $request->session()->get('token');

        $this->form_params = $request->except(['_token', 'id']);
        $data_request = execute_api_json('api/permintaandata/'.$id,'PUT',$this->form_params);

        if(($data_request->code == 200)&& ($data_request->status != "error") ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Permintaan Data Hasil Penelitian BNN Berhasil Diperbarui';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Permintaan Data Hasil Penelitian BNN Gagal Diperbarui';
        }
        return back()->with('status', $this->messages);
    }
    public function deleteDataPenelitianBNN(Request $request){
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/permintaandata/'.$id,'DELETE',$this->form_params);
            return $data_request;
        }
    }

    public function risetPenyalahgunaanNarkoba(Request $request){
        $this->limit = config('app.limit');
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
          $post = $request->except(['_token','year_from','year_to','jml_to','jml_from']);
          $tipe = $request->tipe;
          $year_from = $request->year_from;
          $year_to = $request->year_to;
          $jml_to = $request->jml_to;
          $jml_from = $request->jml_from;
          if($tipe){
            $kondisi .= '&tipe='.$tipe;
            $this->selected['tipe'] = $tipe;
          }
          if($tipe == 'periode'){
            if($year_from){
                $this->selected['year_from'] = $year_from;
                $kondisi .='&year_from='.$year_from;
            }else if(!$year_from){
                $kondisi .='';
            }
            if($year_to){
              $this->selected['year_to'] = $year_to;
              $kondisi .='&year_to='.$year_to;
            }else if(!$year_to){
              $kondisi .='';
            }
          }else if($tipe == 'jml_responden'){
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
          }elseif( ($tipe == 'judul') || ($tipe == 'lokasi') ){
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
          $get = $request->except(['page','year_from','year_to','limit','jml_to','jml_from']);
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
                if($request->year_from){
                    $kondisi .= "&year_from".'='.$request->year_from;
                    $this->selected['year_from'] = $request->year_from;
                }
                if($request->year_to){
                  $kondisi .= "&year_to".'='.$request->year_to;
                  $this->selected['year_to'] = $request->year_to;
                }
              }else if($value == 'jml_responden'){
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
        $this->data['start_number'] = $start_number;
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;

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


        $datas = execute_api_json('api/riset?'.$limit.'&'.$offset.$kondisi,'get');
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;
        }else{
            $this->data['data'] = [];
            $total_item =  0;
        }

        $datas = execute_api('api/propinsi','get');
        if($datas['code'] == 200){
            $this->data['propinsi'] = $datas['data'];
        }else{
            $this->data['propinsi'] = [];
        }

        $this->data['title'] = 'Puslitdatin';
        $this->data['path'] = $request->path();
        $this->data['delete_route'] = 'delete_riset_penyalahgunaan_narkoba';
        $this->data['current_page'] = $current_page;
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
        $this->data['breadcrumps'] = breadcrump_litbang($request->route()->getName());
        return view('puslitdatin/litbang/riset_penyalahgunaanNarkoba/index_risetPenyalahgunaanNarkoba',$this->data);
    }
    public function editRisetPenyalahgunaanNarkoba(Request $request){
        $id = $request->id;
        $client = new Client();
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');
        $datas = execute_api_json('api/riset/'.$id,'get');
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }

        $this->data['propinsi'] = MainModel::getPropinsi();
        $this->data['file_path'] = config('app.puslidatin_file_path');
        $this->data['breadcrumps'] = breadcrump_litbang($request->route()->getName());
        return view('puslitdatin/litbang/riset_penyalahgunaanNarkoba/edit_risetPenyalahgunaanNarkoba',$this->data);
    }
    public function addRisetPenyalahgunaanNarkoba(Request $request){
        if ($request->isMethod('post')) {
            $token = $request->session()->get('token');
            $file_message ="";
            $this->form_params = $request->except(['_token', 'id', 'meta_lokasi']);
            $jumlah_responden = str_replace(',', '', $request->jumlah_responden);
            $this->form_params['jumlah_responden'] = $jumlah_responden;
            if($request->file('file_upload')){
                $fileName = $request->file('file_upload')->getClientOriginalName();
                $fileName = date('Y-m-d').'_'.$fileName;
                $extension = $request->file('file_upload')->getClientOriginalExtension(); // getting image extension
                $directory = 'Puslitdatin/Data_Riset';
                try {
                    $path = Storage::putFileAs($directory, $request->file('file_upload'),$fileName);
                    if($path){
                        $this->form_params['file_upload'] = $fileName;
                    }else{
                        $file_message = "Dengan File gagal diupload.";
                    }
                }catch(\Exception $e){
                    $file_message ="";
                }
            }
            if(count($request->meta_lokasi) > 0 ){
                foreach($request->meta_lokasi as $m => $val){
                    if($val['propinsi'] ){
                        $meta_lokasi[] = ['propinsi' => $val['propinsi'] ];
                    }
                }
                if(count($meta_lokasi) > 0 ){
                    $json_meta_lokasi = json_encode($meta_lokasi);
                    $this->form_params['meta_lokasi'] = $json_meta_lokasi;
                }
            }else{
                $json_meta_lokasi = "";
            }

            $data_request = execute_api_json('api/riset/','POST',$this->form_params);

            $trail['audit_menu'] = 'Puslitdatin - Bidang Litbang - Riset Operasional Penyalahgunaan Narkoba';
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
                $this->kelengkapan_riset_penyalahgunaan($id);
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Data Data Hasil Penelitian BNN Berhasil Ditambahkan '. $file_message;
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Data Data Hasil Penelitian BNN Gagal Ditambahkan';
            }
            return redirect('puslitdatin/bidang_litbang/riset_penyalahgunaan_narkoba')->with('status', $this->messages);
        }else{
            $this->data['propinsi'] = MainModel::getPropinsi();
            $this->data['breadcrumps'] = breadcrump_litbang($request->route()->getName());
            return view('puslitdatin/litbang/riset_penyalahgunaanNarkoba/add_risetPenyalahgunaanNarkoba',$this->data);
        }
    }
    public function updateRisetPenyalahgunaanNarkoba(Request $request){
        $file_message = "";
        $id = $request->input('id');
        $token = $request->session()->get('token');

        $this->form_params = $request->except(['_token', 'id']);
        $jumlah_responden = str_replace(',', '', $request->jumlah_responden);
        $this->form_params['jumlah_responden'] = $jumlah_responden;

        if($request->file('file_upload')){
            $fileName = $request->file('file_upload')->getClientOriginalName();
            $fileName = date('Y-m-d').'_'.$fileName;
            $extension = $request->file('file_upload')->getClientOriginalExtension(); // getting image extension
            $directory = 'Puslitdatin/Data_Riset';
            try {
                $path = Storage::putFileAs($directory, $request->file('file_upload'),$fileName);
                if($path){
                    $this->form_params['file_upload'] = $fileName;
                }else{
                    $file_message = "Dengan File gagal diupload.";
                }
            }catch (\Exception $e){
                $file_message = "";
            }
        }
        if(count($request->meta_lokasi) > 0 ){
            foreach($request->meta_lokasi as $m => $val){
                if($val['propinsi'] ){
                    $meta_lokasi[] = ['propinsi' => $val['propinsi']];
                }
            }
            if(count($meta_lokasi) > 0 ){
                $json_meta_lokasi = json_encode($meta_lokasi);
                $this->form_params['meta_lokasi'] = $json_meta_lokasi;
            }
        }else{
            $json_meta_lokasi = "";
        }

        $data_request = execute_api_json('api/riset/'.$id,'PUT',$this->form_params);

        $trail['audit_menu'] = 'Puslitdatin - Bidang Litbang - Riset Operasional Penyalahgunaan Narkoba';
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
            $this->kelengkapan_riset_penyalahgunaan($id);
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Permintaan Data Hasil Penelitian BNN Berhasil Diperbarui '.$file_message;
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Permintaan Data Hasil Penelitian BNN Gagal Diperbarui';
        }
        return back()->with('status', $this->messages);
    }
    public function deleteRisetPenyalahgunaanNarkoba(Request $request){
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/riset/'.$id,'DELETE',$this->form_params);
            $this->form_params['delete_id'] = $id;
            $trail['audit_menu'] = 'Puslitdatin - Bidang Litbang - Riset Operasional Penyalahgunaan Narkoba';
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

    public function PekerjaanJaringan(Request $request){
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
          $tgl_to = $request->tgl_to;
          if($tipe){
            $kondisi .= '&tipe='.$tipe;
            $this->selected['tipe'] = $tipe;
          }
          if($tipe == 'periode'){
            if($tgl_from){

                $this->selected['tgl_from'] = $tgl_from;
                $kondisi .='&tgl_from='.date('Y-m-d',strtotime(str_replace('/', '-', $tgl_from)));
            }else if(!$tgl_from){
                $kondisi .='';
            }
            if($tgl_to){
              $this->selected['tgl_to'] =  $tgl_to;
              $kondisi .='&tgl_to='.date('Y-m-d',strtotime(str_replace('/', '-', $tgl_to)));
            }else if(!$tgl_to){
              $kondisi .='';
            }
          }elseif( $tipe == 'pelapor' ){
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
          $get = $request->except(['page','tgl_from','tgl_to','limit']);
          $tipe = $request->tipe;
          $tgl_to = $request->tgl_to;
          $tgl_from = $request->tgl_from;
          if(count($get)>0){
            if($tipe){
              $this->selected['tipe']  = $tipe;
            }
            foreach ($get as $key => $value) {
              $kondisi .= "&".$key.'='.$value;
              if($value == 'periode'){
                if($request->tgl_from){
                    $kondisi .= "&tgl_from".'='.$request->tgl_from;
                    $this->selected['tgl_from'] = date('d/m/Y',strtotime($request->tgl_from));
                }
                if($request->tgl_to){
                  $kondisi .= "&tgl_to".'='.$request->tgl_to;
                  $this->selected['tgl_to'] = date('d/m/Y',strtotime($request->tgl_to));
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

        $datas = execute_api_json('api/pekerjaanjaringan?'.$limit.'&'.$offset.$kondisi,'get');
        dd($datas);
        if($datas->code == 200 && $datas->status != 'error'){
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;
        }else{
            $this->data['data'] = [];
            $total_item = 0;
        }

        $this->data['start_number'] = $start_number;
        $this->data['title'] = 'Pekerjaan Jaringan Dir Puslitadin';
        $this->data['jenis_kegiatan'] = config('lookup.jenis_kegiatan');
        $this->data['instansi']  = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['breadcrumps'] = breadcrump_bidang_tik($request->route()->getName());
        $this->data['path'] = $request->path();
        $this->data['delete_route'] ='delete_pekerjaan_jaringan';
        $this->data['current_page'] = $current_page;
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
        return view('puslitdatin/tik/pekerjaan_jaringan/index_pekerjaanJaringan',$this->data);
    }

    public function addPekerjaanJaringan(Request $request){

        if ($request->isMethod('post')) {
            $meta_kodepekerjaan = "";
            $meta_teknisi = [];
            $meta_permasalahan = [];
            $json_meta_teknisi = "";
            $json_meta_permaslahan = "";

            $token = $request->session()->get('token');
            $this->form_params = $request->except(['_token','kode_jenis_jaringan','meta_teknisi','meta_permaslahan']);
            if($request->tgl_pelaporan){
                $this->form_params['tgl_pelaporan'] = date('Y-m-d',strtotime(str_replace('/', '-', $request->tgl_pelaporan)));
            }
            if($request->tgl_mulai){
                $this->form_params['tgl_mulai'] = date('Y-m-d h:i:s',strtotime(str_replace('/', '-', $request->tgl_mulai)));
            }
            if($request->tgl_selesai){
                $this->form_params['tgl_selesai'] = date('Y-m-d h:i:s',strtotime(str_replace('/', '-', $request->tgl_selesai)));
            }


            if(count($request->kode_jenis_jaringan)){
              $meta_kodepekerjaan = json_encode($request->kode_jenis_jaringan);
              $this->form_params['meta_kodejaringan'] = $meta_kodepekerjaan;
            }else{
              $meta_kodepekerjaan = "";
            }

            if(count($request->meta_teknisi)){
              foreach($request->meta_teknisi as $m => $tek){
                if($tek['nama_teknisi']){
                  $meta_teknisi[] = $tek['nama_teknisi'];
                }
              }
              if(count($meta_teknisi)>0){
                $json_meta_teknisi = json_encode($meta_teknisi);
                $this->form_params['meta_teknisi'] = $json_meta_teknisi;
              }
            }else{
              $json_meta_teknisi = "";
              $this->form_params['meta_teknisi'] = $json_meta_teknisi;
            }

            if(count($request->meta_permasalahan)){
              foreach($request->meta_permasalahan as $p => $mp){
                if($mp['masalah'] || $mp['tindak_lanjut']|| $mp['hasil']){
                  $meta_permasalahan[] = ['masalah'=>$mp['masalah'],'tindak_lanjut'=>$mp['tindak_lanjut'],'hasil'=>$mp['hasil']];
                }
              }

              if(count($meta_permasalahan)>0){
                $json_meta_permasalahan = json_encode($meta_permasalahan);
                $this->form_params['meta_permasalahan'] = $json_meta_permasalahan;
              }
            }else{
              $json_meta_permasalahan = "";
            }

            $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
            $data_request = execute_api_json('api/pekerjaanjaringan/','POST',$this->form_params);

            $trail['audit_menu'] = 'Puslitdatin - Bidang TIK - Pekerjaan Jaringan';
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
                $id= $data_request->data->eventID;
                $this->kelengkapan_pekerjaanjaringan($id);
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Data Pekerjaan Jaringan Berhasil Ditambahkan';
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Data Pekerjaan Jaringan Gagal Ditambahkan';
            }
            return redirect('puslitdatin/bidang_tik/pekerjaan_jaringan')->with('status', $this->messages);
        }else{
            $this->data['jenis_kegiatan'] = config('lookup.jenis_kegiatan');
            $this->data['jenis_jaringan'] = config('lookup.jenis_jaringan');
            $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
            $this->data['breadcrumps'] = breadcrump_bidang_tik($request->route()->getName());
            $this->data['media_contact'] = config('lookup.media_contact');
            return view('puslitdatin/tik/pekerjaan_jaringan/add_pekerjaanJaringan',$this->data);
        }
    }


    public function editPekerjaanJaringan(Request $request){
      $id = $request->id;
      $datas = execute_api_json('api/pekerjaanjaringan/'.$id,'GET');
      if(($datas->code ==200) && ($datas->status != 'error') ){
        $this->data['data'] = $datas->data;
      }else{
        $this->data['data'] = [];
      }
      $this->data['breadcrumps'] = breadcrump_bidang_tik($request->route()->getName());
      $this->data['jenis_kegiatan'] = config('lookup.jenis_kegiatan');
      $this->data['jenis_jaringan'] = config('lookup.jenis_jaringan');
      $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
      $this->data['media_contact'] = config('lookup.media_contact');
      return view('puslitdatin/tik/pekerjaan_jaringan/edit_pekerjaanJaringan',$this->data);
    }

    public function deletePekerjaanJaringan(Request $request){
      if ($request->ajax()) {
            $id = $request->id;
            if($id){
                $data_request = execute_api('api/pekerjaanjaringan/'.$id,'DELETE');
                $this->form_params['delete_id'] = $id;
                $trail['audit_menu'] = 'Puslitdatin - Bidang TIK - Pekerjaan Jaringan';
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

    public function updatePekerjaanJaringan(Request $request){
      $id = $request->id;
      $meta_kodepekerjaan = "";
      $meta_teknisi = [];
      $meta_permasalahan = [];
      $json_meta_teknisi = "";
      $json_meta_permaslahan = "";

      $this->form_params = $request->except(['id','_token','kode_jenis_jaringan','meta_teknisi','meta_permasalahan']);
      if($request->tgl_pelaporan){
          $this->form_params['tgl_pelaporan'] = date('Y-m-d',strtotime(str_replace('/', '-', $request->tgl_pelaporan)));
      }
      if($request->tgl_mulai){
          $this->form_params['tgl_mulai'] = date('Y-m-d h:i:s',strtotime(str_replace('/', '-', $request->tgl_mulai)));
      }
      if($request->tgl_selesai){
          $this->form_params['tgl_selesai'] = date('Y-m-d h:i:s',strtotime(str_replace('/', '-', $request->tgl_selesai)));
      }


      if(count($request->kode_jenis_jaringan)){
        $meta_kodepekerjaan = json_encode($request->kode_jenis_jaringan);
        $this->form_params['meta_kodejaringan'] = $meta_kodepekerjaan;
      }else{
        $meta_kodepekerjaan = "";
      }

      if(count($request->meta_teknisi)){
        foreach($request->meta_teknisi as $m => $tek){
          if($tek['nama_teknisi']){
            $meta_teknisi[] = $tek['nama_teknisi'];
          }
        }
        if(count($meta_teknisi)>0){
          $json_meta_teknisi = json_encode($meta_teknisi);
          $this->form_params['meta_teknisi'] = $json_meta_teknisi;
        }
      }else{
        $json_meta_teknisi = "";
      }
      if(count($request->meta_permasalahan)){
        foreach($request->meta_permasalahan as $p => $mp){
          if($mp['masalah'] || $mp['tindak_lanjut']|| $mp['hasil']){
            $meta_permasalahan[] = ['masalah'=>$mp['masalah'],'tindak_lanjut'=>$mp['tindak_lanjut'],'hasil'=>$mp['hasil']];
          }
        }

        if(count($meta_permasalahan)>0){
          $json_meta_permasalahan = json_encode($meta_permasalahan);
          $this->form_params['meta_permasalahan'] = $json_meta_permasalahan;
        }
      }else{
        $json_meta_permasalahan = "";
      }

      $data_request = execute_api_json('api/pekerjaanjaringan/'.$id,'PUT',$this->form_params);

      $trail['audit_menu'] = 'Puslitdatin - Bidang TIK - Pekerjaan Jaringan';
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
          $this->kelengkapan_pekerjaanjaringan($id);
          $this->messages['status'] = 'success';
          $this->messages['message'] = 'Data Pekerjaan Jaringan Berhasil Diperbarui';
      }else{
          $this->messages['status'] = 'error';
          $this->messages['message'] = 'Data Pekerjaan Jaringan  Gagal Diperbarui';
      }
      return back()->with('status',$this->messages);
    }
    public function PengadaanEmail(Request $request){
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
          $tgl_to = $request->tgl_to;
          if($tipe){
            $kondisi .= '&tipe='.$tipe;
            $this->selected['tipe'] = $tipe;
          }
          if($tipe == 'periode'){
            if($tgl_from){
                $this->selected['tgl_from'] = $tgl_from;
                $kondisi .='&tgl_from='.date('Y-m-d',strtotime(str_replace('/', '-', $tgl_from)));
            }else if(!$tgl_from){
                $kondisi .='';
            }
            if($tgl_to){
              $this->selected['tgl_to'] =  $tgl_to;
              $kondisi .='&tgl_to='.date('Y-m-d',strtotime(str_replace('/', '-', $tgl_to)));
            }else if(!$tgl_to){
              $kondisi .='';
            }
          }elseif( $tipe == 'email'){
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
          $get = $request->except(['page','tgl_from','tgl_to','limit']);
          $tipe = $request->tipe;
          $tgl_to = $request->tgl_to;
          $tgl_from = $request->tgl_from;
          if(count($get)>0){
            if($tipe){
              $this->selected['tipe']  = $tipe;
            }
            foreach ($get as $key => $value) {
              $kondisi .= "&".$key.'='.$value;
              if($value == 'periode'){
                if($request->tgl_from){
                    $kondisi .= "&tgl_from".'='.$request->tgl_from;
                    $this->selected['tgl_from'] = date('d/m/Y',strtotime($request->tgl_from));
                }
                if($request->tgl_to){
                  $kondisi .= "&tgl_to".'='.$request->tgl_to;
                  $this->selected['tgl_to'] = date('d/m/Y',strtotime($request->tgl_to));
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


        $this->data['route'] = $request->route()->getName();

        $this->data['start_number'] = $start_number;
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
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

        $datas = execute_api_json('api/pengadaanemail?'.$limit.'&'.$offset.$kondisi,'get');
        if(($datas->code == 200) || ($datas->code != 'error')){
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;
        }else{
            $this->data['data'] = [];
            $total_item = 0;
        }
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['title'] = 'Pembuatan Email BNN';
        $this->data['breadcrumps'] = breadcrump_bidang_tik($request->route()->getName());
        $this->data['path'] = $request->path();
        $this->data['delete_route'] = 'delete_pengadaan_email';
        $this->data['current_page'] = $current_page;
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
        return view('puslitdatin/tik/pengadaan_email/index_pengadaanEmail',$this->data);
    }

    public function addPengadaanEmail(Request $request){
        if ($request->isMethod('post')) {
            $this->form_params = $request->except(['_token']);
            if($request->tgl_pelaporan){
                $this->form_params['tgl_pelaporan'] = date('Y-m-d',strtotime(str_replace('/', '-', $request->tgl_pelaporan)));
            }
            $this->form_params['email'] = $request->email.config('app.email_bnn');
            $data_request = execute_api_json('api/pengadaanemail/','POST',$this->form_params);

            $trail['audit_menu'] = 'Puslitdatin - Bidang TIK - Pembuatan Email BNN';
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
                $this->kelengkapan_pengadaanemail($id);
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Data Pembuatan Email BNN Berhasil Ditambahkan';
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Data Pembuatan Email BNN Gagal Ditambahkan';
            }
            return redirect(route('pengadaan_email'))->with('status', $this->messages);
        }else{
            $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
            $this->data['breadcrumps'] = breadcrump_bidang_tik($request->route()->getName());
            return view('puslitdatin/tik/pengadaan_email/add_pengadaanEmail',$this->data);
        }
    }

    public function deletePengadaanEmail(Request $request){
      if ($request->ajax()) {
          $id = $request->id;
          if($id){
              $data_request = execute_api('api/pengadaanemail/'.$id,'DELETE');
              $this->form_params['delete_id'] = $id;
              $trail['audit_menu'] = 'Puslitdatin - Bidang TIK - Pembuatan Email BNN';
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
    public function editPengadaanEmail(Request $request){
      $id = $request->id;
      $data_request = execute_api_json('api/pengadaanemail/'.$id,'GET');
      if( ($data_request->code == 200) && ($data_request->status != 'error') ){
          $this->data['data'] = $data_request->data;
      }else{
          $this->data['data'] = [];
      }
      $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
      $this->data['breadcrumps'] = breadcrump_bidang_tik($request->route()->getName());
      return view('puslitdatin/tik/pengadaan_email/edit_pengadaanEmail',$this->data);
    }

    public function updatePengadaanEmail(Request $request){
      $this->form_params = $request->except(['_token','id','tgl_pelaporan']);
      $id = $request->id;
      if($request->tgl_pelaporan){
          $this->form_params['tgl_pelaporan'] = date('Y-m-d',strtotime(str_replace('/', '-', $request->tgl_pelaporan)));
      }
      $data_request = execute_api_json('api/pengadaanemail/'.$id,'PUT',$this->form_params);

      $trail['audit_menu'] = 'Puslitdatin - Bidang TIK - Pembuatan Email BNN';
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
          $this->kelengkapan_pengadaanemail($id);
          $this->messages['status'] = 'success';
          $this->messages['message'] = 'Data Pembuatan Email BNN Berhasil Diperbarui';
      }else{
          $this->messages['status'] = 'error';
          $this->messages['message'] = 'Data Pembuatan Email BNN Gagal Diperbarui';
      }
      return back()->with('status', $this->messages);
    }

    public function PengecekanJaringan(Request $request){
        $kondisi = '';
        if($request->limit) {
            $this->limit = $request->limit;
        } else {
            $this->limit = config('app.limit');
        }

        $this->data['title'] = 'Puslitdatin';
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        $this->data['start_number'] = $start_number;


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
                $this->selected['tgl_from'] = $tgl_from;
                $kondisi .='&tgl_from='.date('Y-m-d',strtotime(str_replace('/', '-', $tgl_from)));
            }else if(!$tgl_from){
                $kondisi .='';
            }
            if($tgl_to){
              $this->selected['tgl_to'] =  $tgl_to;
              $kondisi .='&tgl_to='.date('Y-m-d',strtotime(str_replace('/', '-', $tgl_to)));
            }else if(!$tgl_to){
              $kondisi .='';
            }
          }elseif( ($tipe == 'tim') || ($tipe == 'aktivitas') ){
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
          $get = $request->except(['page','tgl_from','tgl_to','limit']);
          $tipe = $request->tipe;
          $tgl_to = $request->tgl_to;
          $tgl_from = $request->tgl_from;
          if(count($get)>0){
            if($tipe){
              $this->selected['tipe']  = $tipe;
            }
            foreach ($get as $key => $value) {
              $kondisi .= "&".$key.'='.$value;
              if($value == 'periode'){
                if($request->tgl_from){
                    $kondisi .= "&tgl_from".'='.$request->tgl_from;
                    $this->selected['tgl_from'] = date('d/m/Y',strtotime($request->tgl_from));
                }
                if($request->tgl_to){
                  $kondisi .= "&tgl_to".'='.$request->tgl_to;
                  $this->selected['tgl_to'] = date('d/m/Y',strtotime($request->tgl_to));
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

        $datas = execute_api_json('api/pengecekanjaringan?'.$limit.'&'.$offset.$kondisi,'get');
        if(($datas->code == 200) &&  ($datas->status != 'error') ){
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;
        }else{
            $this->data['data'] = [];
            $total_item = 0;
        }
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['breadcrumps'] = breadcrump_bidang_tik($request->route()->getName());
        $this->data['path'] = $request->path();
        $this->data['delete_route'] = 'delete_pengecekan_jaringan';
        $this->data['current_page'] = $current_page;
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
        return view('puslitdatin/tik/pengecekan_jaringan/index_pengecekanJaringan',$this->data);
    }
    public function addPengecekanJaringan(Request $request){
        if ($request->isMethod('post')) {
            $meta_tim = [];
            $meta_aktivitas = [];

            $json_meta_tim = "";
            $json_meta_aktivitas = "";

            $this->form_params = $request->except(['_token','meta_tim','meta_aktivitas']);


            if(count($request->meta_tim) > 0){
              foreach($request->meta_tim as $m => $p){
                if($p['c'] || $p['teknisi']){
                  $meta_tim[] = ['penanggung_jawab'=> $p['penanggung_jawab'], 'teknisi'=> $p['teknisi']];
                }
              }
              if(count($meta_tim) >0){
                $json_meta_tim = json_encode($meta_tim);
                $this->form_params['meta_tim'] = $json_meta_tim;
              }
            }

            $input_meta_aktivitas = $request->meta_aktivitas;
            if(count($input_meta_aktivitas) > 0 ){
              foreach($input_meta_aktivitas as $m => $p ){
                if(isset($p['cek_jaringan']) || isset($p['cek_ip']) || isset($p['cek_ping'])|| isset($p['cek_switch'])|| isset($p['cek_manageable'])|| isset($p['cek_kabel'])|| isset($p['cek_wireless'])
                  || $p['nama_pengguna']|| $p['ket_jaringan']|| $p['ket_ping']|| $p['ket_ip']|| $p['ket_switch'] || $p['ket_manageable'] || $p['ket_kabel'] || $p['ket_wireless'] ){
                    $meta_params = [];
                    $meta_params['cek_jaringan'] = (isset($p['cek_jaringan']) ? $p['cek_jaringan'] : '');
                    $meta_params['cek_ip'] = (isset($p['cek_ip']) ? $p['cek_ip'] : '');
                    $meta_params['cek_ping'] = (isset($p['cek_ping']) ? $p['cek_ping'] : '');
                    $meta_params['cek_switch'] = (isset($p['cek_switch']) ? $p['cek_switch'] : '');
                    $meta_params['cek_manageable'] = (isset($p['cek_manageable']) ? $p['cek_manageable'] : '');
                    $meta_params['cek_kabel'] = (isset($p['cek_kabel']) ? $p['cek_kabel'] : '');
                    $meta_params['cek_wireless'] = (isset($p['cek_wireless']) ? $p['cek_wireless'] : '');
                    $meta_params['ket_jaringan'] = $p['ket_jaringan'];
                    $meta_params['ket_ip'] = $p['ket_ip'];
                    $meta_params['ket_switch'] = $p['ket_switch'];
                    $meta_params['ket_manageable'] = $p['ket_manageable'];
                    $meta_params['ket_kabel'] = $p['ket_kabel'];
                    $meta_params['ket_wireless'] = $p['ket_wireless'];
                    $meta_params['ket_ping'] =$p['ket_ping'];
                    $meta_params['nama_pengguna'] =$p['nama_pengguna'];
                    $meta_aktivitas[] = $meta_params;
                }
              }

            }

            if(count($meta_aktivitas)){
              $json_meta_aktivitas = json_encode($meta_aktivitas);
              $this->form_params['meta_aktivitas'] =  $json_meta_aktivitas;
            }

            if($request->tgl_mulai){
                $this->form_params['tgl_mulai'] = date('Y-m-d',strtotime(str_replace('/', '-', $request->tgl_mulai)));
            }
            if($request->tgl_selesai){
                $this->form_params['tgl_selesai'] = date('Y-m-d',strtotime(str_replace('/', '-', $request->tgl_selesai)));
            }

            $data_request = execute_api_json('api/pengecekanjaringan/','POST',$this->form_params);

            $trail['audit_menu'] = 'Puslitdatin - Bidang TIK - Pengecekan dan Pemeliharaan Jaringan LAN';
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
                $this->kelengkapan_pengecekanjaringan($id);
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Data Pengecekan dan Pemeliharaan Jaringan LAN Berhasil Ditambahkan';
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Data Pengecekan dan Pemeliharaan Jaringan LAN Gagal Ditambahkan';
            }
            return redirect('puslitdatin/bidang_tik/pengecekan_jaringan')->with('status', $this->messages);
        }else{
            $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
            $this->data['breadcrumps'] = breadcrump_bidang_tik($request->route()->getName());
            $this->data['media_contact'] = config('lookup.media_contact');
            $this->data['codes'] = config('lookup.kode_klarifikasi');
            $this->data['labels'] = config('lookup.label_kode_klarifikasi');
            return view('puslitdatin/tik/pengecekan_jaringan/add_pengecekanJaringan',$this->data);
        }
    }


    public function updatePengecekanJaringan(Request $request){
            $meta_tim = [];
            $meta_aktivitas = [];
            // $meta_pengguna = [];
            $json_meta_tim = "";
            $json_meta_aktivitas = "";
            // $json_meta_pengguna = "";
            $id = $request->id;

            $this->form_params = $request->except(['id','_token','meta_tim','meta_aktivitas']);

            // $meta_pengguna = $request->meta_pengguna;
            // if(count($meta_pengguna) > 0){
            //   $json_meta_pengguna = json_encode($meta_pengguna);
            //   $this->form_params['meta_pengguna'] = $json_meta_pengguna;
            // }

            if(count($request->meta_tim) > 0){
              foreach($request->meta_tim as $m => $p){
                if($p['penanggung_jawab'] || $p['teknisi']){
                  $meta_tim[] = ['penanggung_jawab'=> $p['penanggung_jawab'], 'teknisi'=> $p['teknisi']];
                }
              }
              if(count($meta_tim) >0){
                $json_meta_tim = json_encode($meta_tim);
                $this->form_params['meta_tim'] = $json_meta_tim;
              }
            }

            $input_meta_aktivitas = $request->meta_aktivitas;
            if(count($input_meta_aktivitas) > 0 ){
              foreach($input_meta_aktivitas as $m => $p ){
                if(isset($p['cek_jaringan']) || isset($p['cek_ip']) || isset($p['cek_ping'])|| isset($p['cek_switch'])|| isset($p['cek_manageable'])|| isset($p['cek_kabel'])|| isset($p['cek_wireless'])
                  || $p['nama_pengguna']|| $p['ket_jaringan']|| $p['ket_ping']|| $p['ket_ip']|| $p['ket_switch'] || $p['ket_manageable'] || $p['ket_kabel'] || $p['ket_wireless'] ){
                    $meta_params = [];
                    $meta_params['cek_jaringan'] = (isset($p['cek_jaringan']) ? $p['cek_jaringan'] : '');
                    $meta_params['cek_ip'] = (isset($p['cek_ip']) ? $p['cek_ip'] : '');
                    $meta_params['cek_ping'] = (isset($p['cek_ping']) ? $p['cek_ping'] : '');
                    $meta_params['cek_switch'] = (isset($p['cek_switch']) ? $p['cek_switch'] : '');
                    $meta_params['cek_manageable'] = (isset($p['cek_manageable']) ? $p['cek_manageable'] : '');
                    $meta_params['cek_kabel'] = (isset($p['cek_kabel']) ? $p['cek_kabel'] : '');
                    $meta_params['cek_wireless'] = (isset($p['cek_wireless']) ? $p['cek_wireless'] : '');
                    $meta_params['ket_jaringan'] = $p['ket_jaringan'];
                    $meta_params['ket_ip'] = $p['ket_ip'];
                    $meta_params['ket_switch'] = $p['ket_switch'];
                    $meta_params['ket_manageable'] = $p['ket_manageable'];
                    $meta_params['ket_kabel'] = $p['ket_kabel'];
                    $meta_params['ket_wireless'] = $p['ket_wireless'];
                    $meta_params['ket_ping'] =$p['ket_ping'];
                    $meta_params['nama_pengguna'] =$p['nama_pengguna'];
                    $meta_aktivitas[] = $meta_params;
                }
              }

            }

            if(count($meta_aktivitas)){
              $json_meta_aktivitas = json_encode($meta_aktivitas);
              $this->form_params['meta_aktivitas'] =  $json_meta_aktivitas;
            }

            if($request->tgl_mulai){
                $this->form_params['tgl_mulai'] = date('Y-m-d',strtotime(str_replace('/', '-', $request->tgl_mulai)));
            }
            if($request->tgl_selesai){
                $this->form_params['tgl_selesai'] = date('Y-m-d',strtotime(str_replace('/', '-', $request->tgl_selesai)));
            }

            $data_request = execute_api_json('api/pengecekanjaringan/'.$id,'PUT',$this->form_params);

            $trail['audit_menu'] = 'Puslitdatin - Bidang TIK - Pengecekan dan Pemeliharaan Jaringan LAN';
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
                $this->kelengkapan_pengecekanjaringan($id);
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Data Pengecekan dan Pemeliharaan Jaringan LAN Berhasil Diperbarui';
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Data Pengecekan dan Pemeliharaan Jaringan LAN Gagal Diperbarui';
            }
            return back()->with('status',$this->messages);
    }
    public function editPengecekanJaringan(Request $request){
        $id = $request->id;
        $data_request = execute_api_json('api/pengecekanjaringan/'.$id,'GET');
        if( ($data_request->code == 200) && ($data_request->status != 'error') ){
            $this->data['data'] = $data_request->data;
        }else{
            $this->data['data'] = [];
        }
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['breadcrumps'] = breadcrump_bidang_tik($request->route()->getName());
        return view('puslitdatin/tik/pengecekan_jaringan/edit_pengecekanJaringan',$this->data);
    }
    public function deletePengecekanJaringan(Request $request){
       if ($request->ajax()) {
          $id = $request->id;
          if($id){
              $data_request = execute_api('api/pengecekanjaringan/'.$id,'DELETE');
              $this->form_params['delete_id'] = $id;
              $trail['audit_menu'] = 'Puslitdatin - Bidang TIK - Pengecekan dan Pemeliharaan Jaringan LAN';
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
              $data_request = ['code'=>200,'status'=>'error','message'=>'Data Survey Gagal DiHapus'];
              return $data_request;
          }
        }
    }

    //old//

    public function informasiMelaluiContactCenter(Request $request){
        $kondisi = '';
        if($request->limit) {
            $this->limit = $request->limit;
        } else {
            $this->limit = config('app.limit');
        }

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
          }elseif( ($tipe == 'pelapor') || ($tipe == 'agen') ){
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
          $get = $request->except(['page','tgl_from','tgl_to','limit']);
          $tipe = $request->tipe;
          $tgl_from = $request->tgl_from;
          $tgl_to = $request->tgl_to;
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

        $this->data['title'] = 'Puslitdatin';
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        $this->data['start_number'] = $start_number;
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;

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
        $datas = execute_api_json('api/callcenter?'.$limit.'&'.$offset.$kondisi,'get');

        if(($datas->code == 200) && ($datas->code != 'error') ){
          $this->data['data'] = $datas->data;
          $total_item = $datas->paginate->totalpage * $this->limit;
        }else{
          $this->data['data'] = [];
          $total_item = 0;
        }

        $this->data['media'] = config('lookup.media_contact');
        $this->data['breadcrumps'] = breadcrump_bidang_tik($request->route()->getName());
        $this->data['path'] = $request->path();
        $this->data['delete_route'] ='delete_informasi_melalui_contact_center';
        $this->data['current_page'] = $current_page;
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
        return view('puslitdatin/tik/informasi_melaluiContactCenter/index_informasiMelaluiContactCenter',$this->data);
    }

    public function editInformasiMelaluiContactCenter(Request $request){
        $id = $request->id;
        $datas = execute_api_json('api/callcenter/'.$id,'get');
        if($datas->code == 200 && $datas->status != 'error'){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }
        $this->data['breadcrumps'] = breadcrump_bidang_tik($request->route()->getName());
        $this->data['media_contact'] = config('lookup.media_contact');
        $this->data['codes'] = config('lookup.kode_klarifikasi');
        $this->data['labels'] = config('lookup.label_kode_klarifikasi');
        return view('puslitdatin/tik/informasi_melaluiContactCenter/edit_informasiMelaluiContactCenter',$this->data);
    }

    public function addInformasiMelaluiContactCenter(Request $request){
        if ($request->isMethod('post')) {
            $token = $request->session()->get('token');
            $this->form_params = $request->except(['_token']);
            if($request->tgl_dibuat){
                $date = explode('/', $request->tgl_dibuat);
                $this->form_params['tgl_dibuat'] = $date[2].'-'.$date[1].'-'.$date[0];
            }
            $data_request = execute_api_json('api/callcenter/','POST',$this->form_params);
            if( ($data_request->code == 200) && ($data_request->status != 'error') ){
                $id = $data_request->data->eventID;
                $this->kelengkapan_infocallcenter($id);
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Informasi Melalui Contact Center Berhasil Ditambahkan';
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Informasi Melalui Contact Center Gagal Ditambahkan';
            }
            return redirect('puslitdatin/bidang_tik/informasi_melalui_contact_center')->with('status', $this->messages);
        }else{
            $this->data['breadcrumps'] = breadcrump_bidang_tik($request->route()->getName());
            $this->data['media_contact'] = config('lookup.media_contact');
            $this->data['codes'] = config('lookup.kode_klarifikasi');
            $this->data['labels'] = config('lookup.label_kode_klarifikasi');
            return view('puslitdatin/tik/informasi_melaluiContactCenter/add_informasiMelaluiContactCenter',$this->data);
        }
    }

    public function updateInformasiMelaluiContactCenter(Request $request){
        $id = $request->input('id');
        $token = $request->session()->get('token');
        $this->form_params = $request->except(['_token', 'id']);
        if($request->tgl_dibuat){
            $date = explode('/', $request->tgl_dibuat);
            $this->form_params['tgl_dibuat'] = $date[2].'-'.$date[1].'-'.$date[0];
        }
        $data_request = execute_api_json('api/callcenter/'.$id,'PUT',$this->form_params);
        if(($data_request->code == 200)&& ($data_request->status != "error") ){
            $this->kelengkapan_infocallcenter($id);
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Informasi Melalui Contact Center Berhasil Diperbarui ';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Informasi Melalui Contact Center Gagal Diperbarui';
        }
        return back()->with('status', $this->messages);
    }

    public function deleteInformasiMelaluiContactCenter(Request $request){
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/callcenter/'.$id,'DELETE',$this->form_params);
            return $data_request;
        }
    }

    public function tindakLanjutBNNPusat(Request $request){
        $this->limit = config('app.limit');

        $this->data['title'] = 'Puslitdatin';
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        $this->data['start_number'] = $start_number;
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
        $datas = execute_api_json('api/tindakcallcenter?'.$limit.'&'.$offset,'get');
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;
        }else{
            $this->data['data'] = [];
            $total_item =  0;
        }
        $this->data['breadcrumps'] = breadcrump_bidang_tik($request->route()->getName());
        $this->data['path'] = $request->path();
        $this->data['delete_route'] = 'delete_tindak_lanjut_bnn_pusat';
        $this->data['current_page'] = $current_page;
        $this->data['pagination'] = pagination($current_page,$total_item, $this->limit, config('app.page_ellipsis'), "/puslitdatin/bidang_tik/tindak_lanjut_bnn_pusat/%d");
        return view('puslitdatin/tik/tindaklanjut_contactCenterBNNPusat/index_tindakLanjutCCBNNPusat',$this->data);
    }

    public function editTindakLanjutBNNPusat(Request $request){
        $id = $request->id;
        $datas = execute_api_json('api/tindakcallcenter/'.$id,'get');
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }
        $this->data['breadcrumps'] = breadcrump_bidang_tik($request->route()->getName());
        $this->data['media_contact'] = config('lookup.media_contact');
        $this->data['codes'] = config('lookup.kode_klarifikasi');
        $this->data['labels'] = config('lookup.label_kode_klarifikasi');
        return view('puslitdatin/tik/tindaklanjut_contactCenterBNNPusat/edit_tindakLanjutCCBNNPusat',$this->data);
    }

    public function addTindakLanjutBNNPusat(Request $request){
        if ($request->isMethod('post')) {
            $token = $request->session()->get('token');
            $this->form_params = $request->except(['_token']);
            if($request->tgl_dibuat){
                $date = explode('/', $request->tgl_dibuat);
                $this->form_params['tgl_dibuat'] = $date[2].'-'.$date[1].'-'.$date[0];
            }
            if($request->tgl_close){
                $date = explode('/', $request->tgl_close);
                $this->form_params['tgl_close'] = $date[2].'-'.$date[1].'-'.$date[0];
            }
            $data_request = execute_api_json('api/tindakcallcenter/','POST',$this->form_params);
            if( ($data_request->code == 200) && ($data_request->status != 'error') ){
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Informasi Melalui Contact Center Berhasil Ditambahkan';
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Informasi Melalui Contact Center Gagal Ditambahkan';
            }
            return redirect('puslitdatin/bidang_tik/tindak_lanjut_bnn_pusat')->with('status', $this->messages);
        }else{
            $this->data['breadcrumps'] = breadcrump_bidang_tik($request->route()->getName());
            $this->data['media_contact'] = config('lookup.media_contact');
            $this->data['codes'] = config('lookup.kode_klarifikasi');
            $this->data['labels'] = config('lookup.label_kode_klarifikasi');
            return view('puslitdatin/tik/tindaklanjut_contactCenterBNNPusat/add_tindakLanjutCCBNNPusat',$this->data);
        }
    }

    public function updateTindakLanjutBNNPusat(Request $request){
        $id = $request->input('id');
        $token = $request->session()->get('token');

        $this->form_params = $request->except(['_token', 'id']);

        if($request->tgl_dibuat){
            $date = explode('/', $request->tgl_dibuat);
            $this->form_params['tgl_dibuat'] = $date[2].'-'.$date[1].'-'.$date[0];
        }
        if($request->tgl_close){
            $date = explode('/', $request->tgl_close);
            $this->form_params['tgl_close'] = $date[2].'-'.$date[1].'-'.$date[0];
        }
        $data_request = execute_api_json('api/tindakcallcenter/'.$id,'PUT',$this->form_params);
        if(($data_request->code == 200)&& ($data_request->status != "error") ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Informasi Melalui Contact Center Berhasil Diperbarui ';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Informasi Melalui Contact Center Gagal Diperbarui';
        }
        return back()->with('status', $this->messages);
    }

    public function deleteTindakLanjutBNNPusat(Request $request){
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/tindakcallcenter/'.$id,'DELETE',$this->form_params);
            return $data_request;
        }
    }


    public function tindakLanjutBNNP(Request $request){
        $this->limit = config('app.limit');

        $this->data['title'] = 'Puslitdatin';
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        $this->data['start_number'] = $start_number;
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
        $datas = execute_api_json('api/tindakcallcenterbnnp?'.$limit.'&'.$offset,'get');
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }
        $this->data['breadcrumps'] = breadcrump_bidang_tik($request->route()->getName());
        $this->data['path'] = $request->path();
        $this->data['delete_route'] = 'delete_tindak_lanjut_bnn';
        $this->data['current_page'] = $current_page;
        $total_item = $datas->paginate->totalpage * $this->limit;
        $this->data['pagination'] = pagination($current_page,$total_item, $this->limit, config('app.page_ellipsis'), "/puslitdatin/bidang_tik/tindak_lanjut_bnn/%d");
        return view('puslitdatin/tik/tindaklanjut_contactCenterBNNP/index_tindakLanjutCCBNNP',$this->data);
    }

    public function editTindakLanjutBNNP(Request $request){
        $id = $request->id;
        $datas = execute_api_json('api/tindakcallcenterbnnp/'.$id,'get');
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }
        $this->data['breadcrumps'] = breadcrump_bidang_tik($request->route()->getName());
        $this->data['media_contact'] = config('lookup.media_contact');
        $this->data['codes'] = config('lookup.kode_klarifikasi');
        $this->data['labels'] = config('lookup.label_kode_klarifikasi');
        return view('puslitdatin/tik/tindaklanjut_contactCenterBNNP/edit_tindakLanjutCCBNNP',$this->data);
    }

    public function addTindakLanjutBNNP(Request $request){
        if ($request->isMethod('post')) {
            $token = $request->session()->get('token');
            $this->form_params = $request->except(['_token']);
            if($request->tgl_dibuat){
                $date = explode('/', $request->tgl_dibuat);
                $this->form_params['tgl_dibuat'] = $date[2].'-'.$date[1].'-'.$date[0];
            }

            $data_request = execute_api_json('api/tindakcallcenterbnnp/','POST',$this->form_params);
            if( ($data_request->code == 200) && ($data_request->status != 'error') ){
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Informasi Melalui Contact Center Berhasil Ditambahkan';
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Informasi Melalui Contact Center Gagal Ditambahkan';
            }
            return redirect('puslitdatin/bidang_tik/tindak_lanjut_bnn')->with('status', $this->messages);
        }else{
            $this->data['breadcrumps'] = breadcrump_bidang_tik($request->route()->getName());
            $this->data['media_contact'] = config('lookup.media_contact');
            $this->data['codes'] = config('lookup.kode_klarifikasi');
            $this->data['labels'] = config('lookup.label_kode_klarifikasi');
            return view('puslitdatin/tik/tindaklanjut_contactCenterBNNP/add_tindakLanjutCCBNNP',$this->data);
        }
    }

    public function updateTindakLanjutBNNP(Request $request){
        $id = $request->input('id');
        $token = $request->session()->get('token');

        $this->form_params = $request->except(['_token', 'id']);

        if($request->tgl_dibuat){
            $date = explode('/', $request->tgl_dibuat);
            $this->form_params['tgl_dibuat'] = $date[2].'-'.$date[1].'-'.$date[0];
        }

        $data_request = execute_api_json('api/tindakcallcenterbnnp/'.$id,'PUT',$this->form_params);
        if(($data_request->code == 200)&& ($data_request->status != "error") ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Informasi Melalui Contact Center Berhasil Diperbarui ';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Informasi Melalui Contact Center Gagal Diperbarui';
        }
        return back()->with('status', $this->messages);
    }

    public function deleteTindakLanjutBNNP(Request $request){
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/tindakcallcenterbnnp/'.$id,'DELETE',$this->form_params);
            return $data_request;
        }
    }

    public function printPage(Request $request){
        $array_segments = [
            'survey_narkoba'=>'surveypenyalahgunanarkoba',
            'survey'=>'surveypenyalahguna',
            'penyalahguna_teratur_pakai'=>'cobapakai',
            'penyalahgunaan_coba_pakai'=>'teraturpakai',
            'penyalahguna_pecandu_non_suntik'=>'pecandunonsuntik',
            'penyalahguna_pecandu_suntik'=>'pecandusuntik',
            'penyalahguna_setahun_pakai'=>'setahunpakai',
            'data_penelitian_bnn'=>'permintaandata',
            'riset_penyalahgunaan_narkoba'=>'riset',
            'informasi_melalui_contact_center'=>'callcenter',
            'tindak_lanjut_bnn_pusat'=>'tindakcallcenter',
            'tindak_lanjut_bnn'=>'tindakcallcenterbnnp' ,
            'survey_narkoba_ketergantungan'=>'surveypenyalahgunaketergantungan',
            'pekerjaan_jaringan'=>'pekerjaanjaringan' ,
            'pengecekan_jaringan'=>'pengecekanjaringan',
            'pengadaan_email'=>'pengadaanemail',
            'call_center'=>'callcenterdisposisi'
        ];
        $array_titles=[
            'survey_narkoba'=>'Survey Nasional Penyalahgunaan Narkoba di Indonesia',
            'survey'=>'Survey Nasional Penyalahgunaan Narkoba di Indonesia',
            'penyalahguna_teratur_pakai'=>'Penyalah Guna Narkoba Teratur Pakai',
            'penyalahgunaan_coba_pakai'=>'Penyalah Guna Narkoba Coba Pakai',
            'penyalahguna_pecandu_non_suntik'=>'Penyalah Guna Narkoba Pecandu Non Suntik',
            'penyalahguna_pecandu_suntik'=>'Penyalah Guna Narkoba Pecandu Suntik',
            'penyalahguna_setahun_pakai'=>'Penyalah Guna Narkoba Setahun Pakai',
            'data_penelitian_bnn'=>'Permintaan Data Hasil Penelitian BNN',
            'riset_penyalahgunaan_narkoba'=>'Riset Operasional Penyalahgunaan Narkoba di Indonesia',
            'informasi_melalui_contact_center'=>'Informasi Masuk Melalui Contact Center',
            'tindak_lanjut_bnn_pusat'=>'Tindak Lanjut Contact Center BNN Pusat',
            'tindak_lanjut_bnn'=>'Tindak Lanjut Contact Center BNNP/BNNK' ,
            'survey_narkoba_ketergantungan'=>'Survey Nasional Penyalahgunaan Narkoba Berdasarkan Tingkat Ketergantungan',
            'pekerjaan_jaringan'=>'Data Pekerjaan Jaringan' ,
            'pengecekan_jaringan'=>'Data Pengecekan Jaringan',
            'pengadaan_email'=>'Data Pengadaan Email',
            'call_center'=>'Pusat Informasi'
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
        $i = $start_number;

        if($segment == 'survey_narkoba'){
            if($data_request->code == 200 && $data_request->status != 'error'){
                $data = $data_request->data;
                foreach($data as $key=>$d){
                    $result[$key]['No'] = $i;
                    $result[$key]['Tahun'] = $d->tahun;
                    $result[$key]['Jumlah Responden'] = $d->jumlah_responden;
                    $result[$key]['Angka Kematian'] = ($d->angka_kematian ? number_format($d->angka_kematian) : '');
                    $meta = [];
                    $list_meta = "";
                    if($d->meta_data_narkoba){
                        $meta =json_decode($d->meta_data_narkoba,true);
                        if(count($meta) > 0 ){
                            foreach($meta as $k => $m){
                                if(isset($m['jenisKasus'])){
                                    $det = getDetailBarangBukti($m['jenisKasus']);
                                    $list_meta .=  strtoupper($det)."\n";
                                }
                            }
                        }else{
                            $list_meta = "";
                        }
                    }else{
                        $list_meta = "";
                    }
                    $result[$key]['Jenis Narkoba'] = $list_meta;
                    $result[$key]['Status'] = ( ($d->status == 'Y') ? 'Lengkap' : 'Belum Lengkap');
                    $i = $i + 1;
                }
                $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
                $this->printData($result, $name);
            }else{
                return false;
            }
        }else if($segment == 'pekerjaan_jaringan'){
            if($data_request->code == 200 && $data_request->status != 'error'){
                $data = $data_request->data;
                $jenis_kegiatan = config('lookup.jenis_kegiatan');
                foreach($data as $key=>$d){
                    $result[$key]['No'] = $i;
                    $result[$key]['Jenis Kegiatan'] = isset($jenis_kegiatan[$d->jenis_kegiatan])  ?  $jenis_kegiatan[$d->jenis_kegiatan]: $d->jenis_kegiatan;
                    $result[$key]['Tanggal Pelaporan'] = ( $d->tgl_pelaporan? date('d/m/Y',strtotime($d->tgl_pelaporan)): ''  );
                    $result[$key]['Pelapor'] = $d->nama_pelapor;
                    $result[$key]['Satuan Kerja'] = $d->nm_instansi;
                    $result[$key]['Status'] = ( ($d->status == 'Y') ? 'Lengkap' : 'Belum Lengkap');
                    $i = $i + 1;
                }
                $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
                $this->printData($result, $name);
            }else{
                return false;
            }
        }else if($segment == 'pengecekan_jaringan'){
            if($data_request->code == 200 && $data_request->status != 'error'){
                $data = $data_request->data;
                $kategori = config('lookup.kategori_penyalahgunaan');
                foreach($data as $key=>$d){
                    $meta = "";
                    if($d->meta_tim){
                      $json_tim = json_decode($d->meta_tim,true);
                      foreach($json_tim as $t => $j ){
                        $meta .= "Penanggung Jawab : ".$j['penanggung_jawab'].","."Teknisi   : ".$j['teknisi']."\n";
                      }
                    }
                    $activitas = "";
                    if($d->meta_pengguna){
                      $json = json_decode($d->meta_pengguna,true);
                      foreach($json as $jk => $s){
                        $activitas .= $s['nama_pengguna']."\n";
                      }
                    }

                    $result[$key]['No'] = $i;
                    $result[$key]['Waktu Pelaksanaan'] = ($d->tgl_mulai ? date('d/m/Y',strtotime($d->tgl_mulai)) : '').(($d->tgl_selesai && $d->tgl_mulai)?' - ' : '').($d->tgl_selesai ? date('d/m/Y',strtotime($d->tgl_selesai)) : '');
                    $result[$key]['Satuan Kerja'] = $d->nm_instansi;
                    $result[$key]['Tim'] = $meta;
                    // $result[$key]['Aktivitas'] =  $activitas;
                    $result[$key]['Status'] = ( ($d->status == 'Y') ? 'Lengkap' : 'Belum Lengkap');
                    $i = $i + 1;
                }
                $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
                $this->printData($result, $name);
            }else{
                return false;
            }
        }else if($segment == 'pengadaan_email'){
            if($data_request->code == 200 && $data_request->status != 'error'){
                $data = $data_request->data;
                foreach($data as $key=>$d){
                    $result[$key]['No'] = $i;
                    $result[$key]['Tanggal'] = ($d->tgl_pelaporan ? date('d/m/Y',strtotime($d->tgl_pelaporan)) : '');
                    $result[$key]['Satuan Kerja'] = $d->nm_instansi;
                    $result[$key]['Jenis Kuota'] = ( $d->jenis_kuota ? ucfirst($d->jenis_kuota) : '').(($d->jenis_kuota == 'limited') ? ( $d->kuota ? ' / ' .$d->kuota.' MB' : ' / ' .'0 MB') : '');
                    $result[$key]['Email'] =  $d->email;
                    $result[$key]['Status'] = ( ($d->status == 'Y') ? 'Lengkap' : 'Belum Lengkap');
                    $i = $i + 1;
                }
                $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
                $this->printData($result, $name);
            }else{
                return false;
            }
        }else if($segment == 'call_center'){
            if($data_request->code == 200 && $data_request->status != 'error'){
                $data = $data_request->data;
                foreach($data as $key=>$d){
                    $result[$key]['ID'] = $i;
                    $result[$key]['Pengirim'] = $d->pengirim;
                    $result[$key]['Penerima'] = $d->penerima;
                    $result[$key]['Subject'] = $d->subjek;
                    $result[$key]['Isi Konten'] =  $d->konten;
                    $result[$key]['Lampiran'] =  $d->lampiran;
                    $result[$key]['Tanggal Terima'] =  ( $d->waktuterima ? date('d/m/Y H:i:s',strtotime($d->waktuterima)) : ''  );
                    $result[$key]['Tanggal Input'] = ( $d->waktuinput ? date('d/m/Y H:i:s',strtotime($d->waktuinput)) : ''  );
                    $i = $i + 1;
                }
                $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
                $this->printData($result, $name);
            }else{
                return false;
            }
        }else if($segment == 'survey_narkoba_ketergantungan'){
            if($data_request->code == 200 && $data_request->status != 'error'){
                $data = $data_request->data;
                $kategori = config('lookup.kategori_penyalahgunaan');
                foreach($data as $key=>$d){
                    $result[$key]['No'] = $i;
                    $result[$key]['Tahun'] = $d->tahun;
                    $result[$key]['Jumlah Responden'] = $d->jumlah_responden;
                    $result[$key]['Kategori'] = ($d->kategori ? ( isset($kategori[$d->kategori]) ? $kategori[$d->kategori]: $d->kategori) : '');
                    $meta = [];
                    $list_meta = "";
                    if($d->meta_data_narkoba){
                        $meta =json_decode($d->meta_data_narkoba,true);
                        if(count($meta) > 0 ){
                            foreach($meta as $k => $m){
                                if(isset($m['jenis_narkoba'])){
                                    $det = getDetailBarangBukti($m['jenis_narkoba']);
                                    $list_meta .=  strtoupper($det)."\n";
                                }
                            }
                        }else{
                            $list_meta = "";
                        }
                    }else{
                        $list_meta = "";
                    }
                    $result[$key]['Jenis Narkoba'] = $list_meta;
                    $result[$key]['Status'] = ( ($d->status == 'Y') ? 'Lengkap' : 'Belum Lengkap');
                    $i = $i + 1;
                }
                $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
                $this->printData($result, $name);
            }else{
                return false;
            }
        }else if($segment == 'survey'){
            if($data_request->code == 200 && $data_request->status != 'error'){
                $data = $data_request->data;
                $kelompok_survey = config('lookup.kelompok_survey');

                foreach($data as $key=>$d){
                    $result[$key]['No'] = $i;
                    $result[$key]['Tahun Pelaksanaan'] = $d->tahun;
                    $result[$key]['Kelompok Survey'] = (isset($kelompok_survey[$d->kelompok_survey]) ? $kelompok_survey[$d->kelompok_survey] : $d->kelompok_survey);
                    $result[$key]['Judul Penelitian'] = $d->judul_penelitian;
                    $result[$key]['Jumlah Responden'] =  $d->jumlah_responden;
                    $result[$key]['Status'] =  ( ($d->status == 'Y') ? 'Lengkap' : 'Belum Lengkap');

                    $i = $i + 1;
                }
                $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
                $this->printData($result, $name);
            }else{
                return false;
            }
        }else if($segment == 'penyalahguna_teratur_pakai'){
            if(count($data_request)>=1){
                $data = $data_request['data'];
                foreach($data as $key=>$value){
                    $result[$key]['No'] = $i;
                    $result[$key]['Tahun'] = $value['tahun'];
                    $result[$key]['Populasi'] = $value['populasi'];
                    $result[$key]['Jumlah Penyalah Guna'] = $value['jumlah_penyalahguna'];
                    $result[$key]['Prevalensi'] = $value['prevalensi'];
                    $i = $i + 1;
                }
                $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
                $this->printData($result, $name);
            }else{
                return false;
            }
        }else if($segment == 'penyalahgunaan_coba_pakai'){
            if(count($data_request)>=1){
                $data = $data_request['data'];
                foreach($data as $key=>$value){
                    $result[$key]['No'] = $i;
                    $result[$key]['Tahun'] = $value['tahun'];
                    $result[$key]['Populasi'] = $value['populasi'];
                    $result[$key]['Jumlah Penyalah Guna'] = $value['jumlah_penyalahguna'];
                    $result[$key]['Prevalensi'] = $value['prevalensi'];
                    $i = $i + 1;
                }
                $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
                $this->printData($result, $name);
            }else{
                return false;
            }
        }else if($segment == 'penyalahguna_pecandu_non_suntik'){
            if(count($data_request)>=1){
                $data = $data_request['data'];
                foreach($data as $key=>$value){
                    $result[$key]['No'] = $i;
                    $result[$key]['Tahun'] = $value['tahun'];
                    $result[$key]['Populasi'] = $value['populasi'];
                    $result[$key]['Jumlah Penyalah Guna'] = $value['jumlah_penyalahguna'];
                    $result[$key]['Prevalensi'] = $value['prevalensi'];
                    $i = $i + 1;
                }
                $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
                $this->printData($result, $name);
            }else{
                return false;
            }
        }else if($segment == 'penyalahguna_pecandu_suntik'){
            if(count($data_request)>=1){
                $data = $data_request['data'];
                foreach($data as $key=>$value){
                    $result[$key]['No'] = $i;
                    $result[$key]['Tahun'] = $value['tahun'];
                    $result[$key]['Populasi'] = $value['populasi'];
                    $result[$key]['Jumlah Penyalah Guna'] = $value['jumlah_penyalahguna'];
                    $result[$key]['Prevalensi'] = $value['prevalensi'];
                    $i = $i + 1;
                }
                $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
                $this->printData($result, $name);
            }else{
                return false;
            }
        }else if($segment == 'penyalahguna_setahun_pakai'){
            if(count($data_request)>=1){
                $data = $data_request['data'];
                foreach($data as $key=>$value){
                    $result[$key]['No'] = $i;
                    $result[$key]['Tahun'] = $value['tahun'];
                    $result[$key]['Populasi'] = $value['populasi'];
                    $result[$key]['Jumlah Penyalah Guna'] = $value['jumlah_penyalahguna'];
                    $result[$key]['Prevalensi'] = $value['prevalensi'];
                    $i = $i + 1;
                }
                $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
                $this->printData($result, $name);
            }else{
                return false;
            }
        }else if($segment == 'data_penelitian_bnn'){
            if(count($data_request)>=1){
                $data = $data_request['data'];
                foreach($data as $key=>$value){
                    $result[$key]['No'] = $i;
                    $result[$key]['Nama'] = $value['nama'];
                    $result[$key]['No. Identitas'] = $value['no_identitas'];
                    $result[$key]['Satker/Instansi'] = $value['satker_instansi'];
                    $result[$key]['Data Dibutuhkan'] = $value['data_dibutuhkan'];
                    $i = $i + 1;
                }
                $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
                $this->printData($result, $name);
            }else{
                return false;
            }
        }else if($segment == 'riset_penyalahgunaan_narkoba'){
            if($data_request->code == 200 && $data_request->status != 'error'){
                $data = $data_request->data;
                foreach($data as $key=>$d){
                    $result[$key]['No'] = $i;
                    $result[$key]['Tahun'] = $d->tahun;
                    $result[$key]['Judul Riset'] = $d->judul;
                    $result[$key]['Lokasi Riset'] = $d->lokasi;
                    $result[$key]['Lokasi Kabupatern'] = ( $d->lokasi_idkabkota ? getWilayahName($d->lokasi_idkabkota,false): '');
                    $result[$key]['Jumlah Responden'] = ( $d->jumlah_responden ? number_format($d->jumlah_responden) : $d->jumlah_responden )  ;
                    $i = $i + 1;
                }
                $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
                $this->printData($result, $name);
            }else{
                return false;
            }
        }else if($segment == 'informasi_melalui_contact_center'){
            if($data_request->code == 200 && $data_request->status != 'error'){
                $data = $data_request->data;
                foreach($data as $key=>$d){
                    $result[$key]['No'] = $i;
                    $result[$key]['Tanggal Dibuat'] = ($d->tgl_dibuat ? date('d/m/Y',strtotime($d->tgl_dibuat)) : '');
                    $result[$key]['Pelapor'] = $d->nama_pelapor;
                    $result[$key]['Media'] = $d->kodejenismedia;
                    $result[$key]['Agent'] = $d->nama_agent;
                    $result[$key]['Status'] = ( ($d->status == 'Y') ? 'Lengkap' : 'Belum Lengkap');
                    $i = $i + 1;
                }
                $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
                $this->printData($result, $name);
            }else{
                return false;
            }
        }else if($segment == 'tindak_lanjut_bnn_pusat'){
            if(count($data_request)>=1){
                $data = $data_request['data'];
                foreach($data as $key=>$value){
                    $result[$key]['No'] = $i;
                    $result[$key]['Tanggal'] = $value['tgl_dibuat'];
                    $result[$key]['Pelapor'] = $value['nama_pelapor'];
                    $result[$key]['Media'] = $value['kodejenismedia'];
                    $result[$key]['Agent'] = $value['nama_agent'];
                    $i = $i + 1;
                }
                $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
                $this->printData($result, $name);
            }else{
                return false;
            }
        }else if($segment == 'tindak_lanjut_bnn'){
            if(count($data_request)>=1){
                $data = $data_request['data'];
                foreach($data as $key=>$value){
                    $result[$key]['No'] = $i;
                    $result[$key]['Pelaksana'] = $value['nm_instansi'];
                    $result[$key]['Pelapor'] = $value['nama_pelapor'];
                    $result[$key]['Judul'] = $value['judul'];
                    $result[$key]['Tindak Lanjut'] = $value['resolusion_close'];
                    $i = $i + 1;
                }
                $name = $array_titles[$segment].' '.Carbon::now()->format('Y-m-d H:i:s');
                $this->printData($result, $name);
            }else{
                return false;
            }
        }

    }

    private function kelengkapan_survey($id){
        $status_kelengkapan = true;
        try{
            $query = DB::table('datin_research_survey_penyalahguna')->where('id',$id)
            ->select('tahun','kelompok_survey','meta_narkoba', 'meta_data_provinsi', 'jumlah_responden', 'angka_absolut','angka_prevalensi','judul_penelitian' );
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
                $kelengkapan = execute_api_json('api/surveypenyalahguna/'.$id,'PUT',['status'=>'Y']);
            }else{
                $kelengkapan = execute_api_json('api/surveypenyalahguna/'.$id,'PUT',['status'=>'N']);
            }
        }catch(\Exception $e){
            $status_kelengkapan=false;
        }
    }

    private function kelengkapan_survey_narkoba($id){
        $status_kelengkapan = true;
        try{
            $query = DB::table('datin_survey_penyalahguna_narkoba')->where('id',$id)
            ->select('tahun','jumlah_responden','angka_prevalensi','angka_absolut','prevalensi_thn1','prevalensi_thn2','prevalensi_thn3','prevalensi_thn4','prevalensi_thn5','angka_kematian','kerugian_thn1','kerugian_thn2','kerugian_thn3','kerugian_thn4','kerugian_thn5','proyeksi_thn1','proyeksi_thn2','proyeksi_thn3','proyeksi_thn4','proyeksi_thn5','meta_data_narkoba','meta_data_provinsi');
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
                $kelengkapan = execute_api_json('api/surveypenyalahgunanarkoba/'.$id,'PUT',['status'=>'Y']);
            }else{
                $kelengkapan = execute_api_json('api/surveypenyalahgunanarkoba/'.$id,'PUT',['status'=>'N']);
            }
        }catch(\Exception $e){
            $status_kelengkapan=false;
        }
    }

    private function kelengkapan_survey_ketergantungan($id){
        $status_kelengkapan = true;
        try{
            $query = DB::table('datin_survey_penyalahguna_ketergantungan')->where('id',$id)
            ->select('tahun','kategori','jumlah_responden','angka_prevalensi','angka_absolut','prevalensi_thn1','prevalensi_thn2','prevalensi_thn3','prevalensi_thn4','prevalensi_thn5','meta_data_narkoba');
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
                $kelengkapan = execute_api_json('api/surveypenyalahgunaketergantungan/'.$id,'PUT',['status'=>'Y']);
            }else{
                $kelengkapan = execute_api_json('api/surveypenyalahgunaketergantungan/'.$id,'PUT',['status'=>'N']);
            }
        }catch(\Exception $e){
            $status_kelengkapan=false;
        }
    }

    private function kelengkapan_riset_penyalahgunaan($id){
        $status_kelengkapan = true;
        try{
            $query = DB::table('datin_research_riset AS r')->where('id',$id)
            ->select( 'r.tahun', 'r.judul','r.lokasi','r.lokasi_idkabkota','r.jumlah_responden','r.hasil_riset','r.file_upload');
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
                $kelengkapan = execute_api_json('api/riset/'.$id,'PUT',['status'=>'Y']);
            }else{
                $kelengkapan = execute_api_json('api/riset/'.$id,'PUT',['status'=>'N']);
            }
        }catch(\Exception $e){
            $status_kelengkapan=false;
        }
    }



    private function kelengkapan_pekerjaanjaringan($id){
      $status_kelengkapan = true;
        try{
            $columns = ['jenis_kegiatan','meta_kodejaringan','nomor_nota_dinas'];
            $query = DB::table('datin_pekerjaan_jaringan')->where('id',$id)
                      ->select('jenis_kegiatan','meta_kodejaringan', 'nomor_nota_dinas','tgl_pelaporan','tempatkejadian_idprovinsi', 'penerima_laporan','tgl_mulai', 'tgl_selesai','meta_teknisi', 'meta_permasalahan');
            if($query->count() > 0 ){
                $result = $query->first();
                if($result->jenis_kegiatan == 'pemasangan_jaringan'){
                  if(!$result->meta_kodejaringan){
                    $status_kelengkapan=false;
                  }
                }else if($result->jenis_kegiatan == 'penanganan_gangguan'){
                  if(!$result->nomor_nota_dinas){
                    $status_kelengkapan=false;
                  }
                }else {
                  foreach($result as $key=>$val){
                    if(!in_array($key, $columns)){
                      if(!$val){
                          $status_kelengkapan=false;
                          break;
                      }else{
                          continue;
                      }
                    }
                  }
                }
            }
            if($status_kelengkapan== true){
                $kelengkapan = execute_api_json('api/pekerjaanjaringan/'.$id,'PUT',['status'=>'Y']);
            }else{
                $kelengkapan = execute_api_json('api/pekerjaanjaringan/'.$id,'PUT',['status'=>'N']);
            }
        }catch(\Exception $e){
          $status_kelengkapan=false;
        }
    }

    private function kelengkapan_pengecekanjaringan($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('datin_pengecekan_jaringan')->where('id',$id)
                  ->select('tgl_mulai','tgl_selesai','tempatkejadian_idprovinsi','meta_aktivitas','meta_tim');
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
          $kelengkapan = execute_api_json('api/pengecekanjaringan/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/pengecekanjaringan/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }


    // private function kelengkapan_pengecekanjaringan($id){
    //   $status_kelengkapan = true;
    //     try{
    //         $columns = ['ket_baik_jaringan',
    //                     'ket_tdk_baik_jaringan',
    //                     'ket_baik_ip',
    //                     'ket_tdk_baik_ip',
    //                     'ket_baik_ping',
    //                     'ket_tdk_baik_ping',
    //                     'ket_baik_switch',
    //                     'ket_tdk_baik_switch',
    //                     'ket_baik_manageable',
    //                     'ket_tdk_baik_manageable',
    //                     'ket_baik_kabel',
    //                     'ket_tdk_baik_kabel',
    //                     'ket_baik_wireless',
    //                     'ket_tdk_baik_wireless',
    //                     'cek_jaringan',
    //                     'cek_ip',
    //                     'cek_ping',
    //                     'cek_switch',
    //                     'cek_kabel',
    //                     'cek_wireless',
    //                     'cek_manageable'
    //                   ];
    //         $column2 = [
    //           'tgl_mulai','tgl_selesai','tempatkejadian_idprovinsi','meta_aktivitas','meta_tim'
    //         ];

    //         // $query = DB::table('datin_pengecekan_jaringan')->where('id',$id)
    //         //           ->select('tgl_mulai','tgl_selesai','tempatkejadian_idprovinsi','cek_jaringan','ket_baik_jaringan','ket_tdk_baik_jaringan','cek_ip','ket_baik_ip','ket_tdk_baik_ip','cek_ping','ket_baik_ping','ket_tdk_baik_ping','cek_switch','ket_baik_switch','ket_tdk_baik_switch','cek_manageable','ket_baik_manageable','ket_tdk_baik_manageable','cek_kabel','ket_baik_kabel','ket_tdk_baik_kabel','cek_wireless','ket_baik_wireless','ket_tdk_baik_wireless','meta_pengguna','meta_tim');

    //       $query = DB::table('datin_pengecekan_jaringan')->where('id',$id)
    //                   ->select('tgl_mulai','tgl_selesai','tempatkejadian_idprovinsi','meta_aktivitas','meta_tim');
    //         if($query->count() > 0 ){
    //             $result = $query->first();
    //             // if($result->cek_jaringan == 'baik'){
    //             //   if(!$result->ket_baik_jaringan){
    //             //     $status_kelengkapan = false;
    //             //   }else if($result->ket_baik_jaringan){
    //             //     $status_kelengkapan = true;
    //             //   }
    //             // }elseif($result->cek_jaringan == 'tdk_baik'){
    //             //   if(!$result->ket_tdk_baik_jaringan){
    //             //     $status_kelengkapan = false;
    //             //   }else if($result->ket_tdk_baik_jaringan){
    //             //     $status_kelengkapan = true;
    //             //   }
    //             // }elseif($result->cek_ip == 'baik'){
    //             //   if(!$result->ket_baik_ip){
    //             //     $status_kelengkapan = false;
    //             //   }else if($result->ket_baik_ip){
    //             //     $status_kelengkapan = true;
    //             //   }
    //             // }elseif($result->cek_ip == 'tdk_baik'){
    //             //   if(!$result->ket_tdk_baik_ip){
    //             //     $status_kelengkapan = false;
    //             //   }else if($result->ket_tdk_baik_ip){
    //             //     $status_kelengkapan = true;
    //             //   }
    //             // }elseif($result->cek_ping == 'baik'){
    //             //   if(!$result->ket_baik_ping){
    //             //     $status_kelengkapan = false;
    //             //   }else if($result->ket_baik_ping){
    //             //     $status_kelengkapan = true;
    //             //   }
    //             // }elseif($result->cek_ping == 'tdk_baik'){
    //             //   if(!$result->ket_tdk_baik_ping){
    //             //     $status_kelengkapan = false;
    //             //   }else if($result->ket_tdk_baik_ping){
    //             //     $status_kelengkapan = true;
    //             //   }
    //             // }elseif($result->cek_switch == 'baik'){
    //             //   if(!$result->ket_baik_switch){
    //             //     $status_kelengkapan = false;
    //             //   }else if($result->ket_baik_switch){
    //             //     $status_kelengkapan = true;
    //             //   }
    //             // }elseif($result->cek_switch == 'tdk_baik'){
    //             //   if(!$result->ket_tdk_baik_switch){
    //             //     $status_kelengkapan = false;
    //             //   }else if($result->ket_tdk_baik_switch){
    //             //     $status_kelengkapan = true;
    //             //   }
    //             // }elseif($result->cek_manageable == 'baik'){
    //             //   if(!$result->ket_baik_manageable){
    //             //     $status_kelengkapan = false;
    //             //   }else if($result->ket_baik_manageable){
    //             //     $status_kelengkapan = true;
    //             //   }
    //             // }elseif($result->cek_manageable == 'tdk_baik'){
    //             //   if(!$result->ket_tdk_baik_manageable){
    //             //     $status_kelengkapan = false;
    //             //   }else if($result->ket_tdk_baik_manageable){
    //             //     $status_kelengkapan = true;
    //             //   }
    //             // }elseif($result->cek_kabel == 'baik'){
    //             //   if(!$result->ket_baik_kabel){
    //             //     $status_kelengkapan = false;
    //             //   }else if($result->ket_baik_kabel){
    //             //     $status_kelengkapan = true;
    //             //   }
    //             // }elseif($result->cek_kabel == 'tdk_baik'){
    //             //   if(!$result->ket_tdk_baik_kabel){
    //             //     $status_kelengkapan = false;
    //             //   }else if($result->ket_tdk_baik_kabel){
    //             //     $status_kelengkapan = true;
    //             //   }
    //             // }elseif($result->cek_wireless == 'baik'){
    //             //   if(!$result->ket_baik_wireless){
    //             //     $status_kelengkapan = false;
    //             //   }else if($result->ket_baik_wireless){
    //             //     $status_kelengkapan = true;
    //             //   }
    //             // }elseif($result->cek_wireless == 'tdk_baik'){
    //             //   if(!$result->ket_tdk_baik_wireless){
    //             //     $status_kelengkapan = false;
    //             //   }else if($result->ket_tdk_baik_wireless){
    //             //     $status_kelengkapan = true;
    //             //   }
    //             }else{
    //               foreach($column2 as $col => $c){
    //                 if(!$result->$c){
    //                   $status_kelengkapan== false;
    //                   break;
    //                 }
    //               }
    //             }

    //         }
    //         if($status_kelengkapan== true){
    //             $kelengkapan = execute_api_json('api/pengecekanjaringan/'.$id,'PUT',['status'=>'Y']);
    //         }else{
    //             $kelengkapan = execute_api_json('api/pengecekanjaringan/'.$id,'PUT',['status'=>'N']);
    //         }
    //     }catch(\Exception $e){
    //       $status_kelengkapan=false;
    //     }
    // }

    private function kelengkapan_pengadaanemail($id){
      $status_kelengkapan = true;
        try{
            $columns = ['kuota','jenis_kuota'];
            $query = DB::table('datin_pengadaan_email')->where('id',$id)
                      ->select('tgl_pelaporan','tempatkejadian_idprovinsi','nomor_nota_dinas','jenis_kuota','kuota','status_aktif');
            if($query->count() > 0 ){
                $result = $query->first();
                if($result->jenis_kuota == 'limited'){
                  if(!$result->kuota){
                    $status_kelengkapan=false;
                  }else if($result->kuota){
                    $status_kelengkapan=true;
                  }
                }else {
                  foreach($result as $key=>$val){
                    if(!in_array($key, $columns)){
                      if(!$val){
                          $status_kelengkapan=false;
                          break;
                      }else{
                          continue;
                      }
                    }
                  }
                }
            }

            if($status_kelengkapan== true){
                $kelengkapan = execute_api_json('api/pengadaanemail/'.$id,'PUT',['status'=>'Y']);
            }else{
                $kelengkapan = execute_api_json('api/pengadaanemail/'.$id,'PUT',['status'=>'N']);
            }
        }catch(\Exception $e){
          $status_kelengkapan=false;
        }
    }

    public function CallCenter(Request $request){
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
          $post = $request->except(['_token','tgl_from','tgl_to','tgl_input_from','tgl_input_to']);
          $tipe = $request->tipe;
          $tgl_from = $request->tgl_from;
          $tgl_to = $request->tgl_to;
          $tgl_input_from = $request->tgl_input_from;
          $tgl_input_to = $request->tgl_input_to;
          if($tipe){
            $kondisi .= '&tipe='.$tipe;
            $this->selected['tipe'] = $tipe;
          }
          if($tipe == 'tgl_terima'){
            if($tgl_from){
                $this->selected['tgl_from'] = $tgl_from;
                $kondisi .='&tgl_from='.date('Y-m-d',strtotime(str_replace('/', '-', $tgl_from)));
            }else if(!$tgl_from){
                $kondisi .='';
            }
            if($tgl_to){
              $this->selected['tgl_to'] =  $tgl_to;
              $kondisi .='&tgl_to='.date('Y-m-d',strtotime(str_replace('/', '-', $tgl_to)));
            }else if(!$tgl_to){
              $kondisi .='';
            }
          }else if($tipe == 'tgl_input') {
            if($tgl_input_from){
              $this->selected['tgl_input_from'] =  $tgl_input_from;
              $kondisi .='&tgl_input_from='.date('Y-m-d',strtotime(str_replace('/', '-', $tgl_input_from)));
            }else if(!$tgl_input_from){
              $kondisi .='';
            }
            if($tgl_input_to){
              $this->selected['tgl_input_to'] =  $tgl_input_to;
              $kondisi .='&tgl_input_to='.date('Y-m-d',strtotime(str_replace('/', '-', $tgl_input_to)));
            }else if(!$tgl_input_to){
              $kondisi .='';
            }
          }elseif( ($tipe == 'pengirim') ||  ($tipe == 'penerima')||  ($tipe == 'subyek')||  ($tipe == 'konten')){
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
          $get = $request->except(['page','tgl_from','tgl_to','tgl_input_from','tgl_input_to','limit']);
          $tipe = $request->tipe;
          $tgl_to = $request->tgl_to;
          $tgl_from = $request->tgl_from;
          if(count($get)>0){
            if($tipe){
              $this->selected['tipe']  = $tipe;
            }
            foreach ($get as $key => $value) {
              $kondisi .= "&".$key.'='.$value;
              if($value == 'tgl_terima'){
                if($request->tgl_from){
                    $kondisi .= "&tgl_from".'='.$request->tgl_from;
                    $this->selected['tgl_from'] = date('d/m/Y',strtotime($request->tgl_from));
                }
                if($request->tgl_to){
                  $kondisi .= "&tgl_to".'='.$request->tgl_to;
                  $this->selected['tgl_to'] = date('d/m/Y',strtotime($request->tgl_to));
                }
              }else if($value == 'tgl_input'){
                if($request->tgl_input_from){
                    $kondisi .= "&tgl_input_from".'='.$request->tgl_input_from;
                    $this->selected['tgl_input_from'] = date('d/m/Y',strtotime($request->tgl_input_from));
                }
                if($request->tgl_input_to){
                  $kondisi .= "&tgl_input_to".'='.$request->tgl_input_to;
                  $this->selected['tgl_input_to'] = date('d/m/Y',strtotime($request->tgl_input_to));
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

        $this->data['route'] = $request->route()->getName();

        $this->data['start_number'] = $start_number;
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
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

        $datas = execute_api_json('api/callcenterdisposisi?'.$limit.'&'.$offset.$kondisi,'get');
        if(($datas->code == 200) || ($datas->code != 'error')){
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;
        }else{
            $this->data['data'] = [];
            $total_item = 0;
        }
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['title'] = 'Pengadaan Email';
        $this->data['breadcrumps'] = breadcrump_bidang_tik($request->route()->getName());
        $this->data['path'] = $request->path();
        $this->data['delete_route'] = 'delete_pengadaan_email';
        $this->data['current_page'] = $current_page;
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
        return view('puslitdatin.call_center',$this->data);
        // $this->data['title']="Puslitdatin";
        // $query = DB::table('soa_callcenter_disposisi')->limit(5)->orderBy('rid', 'desc')->get();
        // $this->data['data_call_center'] = $query ;
        // return view('puslitdatin.call_center',$this->data);
    }
}

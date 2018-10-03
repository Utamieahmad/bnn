<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\MainModel;
use URL;
use DateTime;
use Carbon\Carbon;
use App\Models\Settama\SettamaLookup;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class MasterController extends Controller
{
  public $data;
  public $selected;
  public $form_params;

  public function dataInstansi(Request $request){
      $client = new Client();
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');

      $kondisi = '';
      if($request->limit) {
        $limit = $request->limit;
      } else {
        $limit = config('app.limit');
      }
      if ($request->order==Null) {
          $order = 'asc';
      } else {
          $order = $request->order;
      }

      if ($request->limit==Null) {
          $limit = config('constant.LIMITPAGE');
      } else {
          $limit = $request->limit;
      }
      if ($request->page==Null) {
          $current_page = 1;
          $start_number = 1;
      } else {
          $current_page = $request->page;
          $start_number = ($limit * ($request->page -1 )) + 1;
      }

      if($request->isMethod('get')){
        $get = $request->all();
        $get = $request->except(['page']);
        $tipe = $request->tipe;
        if(count($get)>0){
          $this->selected['tipe']  = $tipe;
          foreach ($get as $key => $value) {
            $kondisi .= "&".$key.'='.$value;
            $this->selected[$key] = $value;
          }
        }
      } else {
        $tipe = $request->tipe;
        $kata_kunci = $request->kata_kunci;

        $kondisi .= '&kata_kunci='.$kata_kunci;
        $this->selected['kata_kunci'] = $kata_kunci;
        $kondisi .= '&tipe='.$tipe;
        $this->selected['tipe'] = $tipe;

        $kondisi .='&limit='.$limit;
        $kondisi .='&order='.$order;
        $this->selected['limit'] = $limit;
        $this->selected['order'] = $order;
      }

      $kondisifilter = array();
      if ($request->kata_kunci != '' ) {
        if ($request->tipe == 'instansi' ) {
          array_push($kondisifilter, array('nm_instansi', 'ilike', '%'.$request->kata_kunci.'%'));
        }else if ($request->tipe == 'wilayah' ) {
          array_push($kondisifilter, array('nm_wilayah', 'ilike', '%'.$request->kata_kunci.'%'));
        }else if ($request->tipe == 'alamat' ) {
          array_push($kondisifilter, array('alamat_inst', 'ilike', '%'.$request->kata_kunci.'%'));
        }
      }
      $this->data['filter'] = $this->selected;
      $this->data['route'] = $request->route()->getName();

      $qresults = DB::table('v_instansi')->where($kondisifilter)->where('kd_jnsinst','11');

      $total_results = $qresults->count();
      $offset = ($current_page-1) * $limit;
      $totalpage = ceil($total_results / $limit);

      $datas = $qresults->orderBy('nm_instansi', $order)->offset($offset)->limit($limit)->get();

      $this->data['datamaster'] = json_decode($datas, true);

      if($kondisi){
        $filter = $kondisi;
        $filtering = true;
      }else{
        $filter = '/';
        $filtering = false;
      }

      $requestwilayah = $client->request('GET', $baseUrl.'/api/wilayah');
      $wilayah = json_decode($requestwilayah->getBody()->getContents(), true);
      $this->data['wilayah'] = $wilayah['data'];

      $this->data['title']="Master Data Instansi";
      $this->data['delete_route'] = 'delete_dataInstansi';
      $this->data['current_page'] = $current_page;
      $this->data['start_number'] = $start_number;
      $this->data['pagination'] = paginations($current_page,$total_results, $limit, config('app.page_ellipsis'),config('app.url')."/master/instansi",$filtering,$filter );
      $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

      return view('master.instansi.index_dataInstansi',$this->data);
  }

  public function inputDataInstansi(Request $request){
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');
    $client = new Client();

    $inputData = $request->except(['_token', 'id']);
    $inputData['status'] = TRUE;
    $inputData['kd_jnsinst'] = '11';

    DB::table('tr_instansi')->insert($inputData);

    $trail['audit_menu'] = 'Master Data - Instansi';
    $trail['audit_event'] = 'post';
    $trail['audit_value'] = json_encode($inputData);
    $trail['audit_url'] = $request->url();
    $trail['audit_ip_address'] = $request->ip();
    $trail['audit_user_agent'] = $request->userAgent();
    $trail['audit_message'] = '';
    $trail['created_at'] = date("Y-m-d H:i:s");
    $trail['created_at'] = $request->session()->get('id');

    $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


    $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

    return redirect('/master/instansi');
  }

  public function updateDataInstansi(Request $request){
    $id = $request->input('id');
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');
    $client = new Client();

    $inputData = $request->except(['_token', 'id']);
    $inputData['status'] = TRUE;
    $inputData['kd_jnsinst'] = '11';

    DB::table('tr_instansi')
            ->where('id_instansi', $id)
            ->update($inputData);

    $trail['audit_menu'] = 'Master Data - Instansi';
    $trail['audit_event'] = 'put';
    $trail['audit_value'] = json_encode($inputData);
    $trail['audit_url'] = $request->url();
    $trail['audit_ip_address'] = $request->ip();
    $trail['audit_user_agent'] = $request->userAgent();
    $trail['audit_message'] = '';
    $trail['created_at'] = date("Y-m-d H:i:s");
    $trail['created_at'] = $request->session()->get('id');

    $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


    $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

    return redirect('/master/instansi');
  }

  public function deleteDataInstansi(Request $request){
      if ($request->ajax()) {
          $id = $request->id;
          $del = DB::table('tr_instansi')->where('id_instansi', $id)->delete();
          $this->form_params['delete_id'] = $id;
          $trail['audit_menu'] = 'Master Data - Instansi';
          $trail['audit_event'] = 'delete';
          $trail['audit_value'] = json_encode($this->form_params);
          $trail['audit_url'] = $request->url();
          $trail['audit_ip_address'] = $request->ip();
          $trail['audit_user_agent'] = $request->userAgent();
          $trail['audit_message'] = '';
          $trail['created_at'] = date("Y-m-d H:i:s");
          $trail['created_at'] = $request->session()->get('id');

          $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

          if($del){
              $data_request = ['code'=>200,'status'=>'Sukses','message'=>'Data Master Instansi Berhasil Dihapus'];
              return $data_request;
          }else{
              $data_request = ['code'=>200,'status'=>'error','message'=>'Data Master Instansi Gagal Dihapus'];
              return $data_request;
          }
      }
  }

  public function dataPropinsi(Request $request){
      $client = new Client();
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');

      $kondisi = '';
      if($request->limit) {
        $limit = $request->limit;
      } else {
        $limit = config('app.limit');
      }
      if ($request->order==Null) {
          $order = 'asc';
      } else {
          $order = $request->order;
      }

      if ($request->limit==Null) {
          $limit = config('constant.LIMITPAGE');
      } else {
          $limit = $request->limit;
      }
      if ($request->page==Null) {
          $current_page = 1;
          $start_number = 1;
      } else {
          $current_page = $request->page;
          $start_number = ($limit * ($request->page -1 )) + 1;
      }

      if($request->isMethod('get')){
        $get = $request->all();
        $get = $request->except(['page']);
        $tipe = $request->tipe;
        if(count($get)>0){
          $this->selected['tipe']  = $tipe;
          foreach ($get as $key => $value) {
            $kondisi .= "&".$key.'='.$value;
            $this->selected[$key] = $value;
          }
        }
      } else {
        $tipe = $request->tipe;
        $kata_kunci = $request->kata_kunci;

        $kondisi .= '&kata_kunci='.$kata_kunci;
        $this->selected['kata_kunci'] = $kata_kunci;
        $kondisi .= '&tipe='.$tipe;
        $this->selected['tipe'] = $tipe;

        $kondisi .='&limit='.$limit;
        $kondisi .='&order='.$order;
        $this->selected['limit'] = $limit;
        $this->selected['order'] = $order;
      }

      $kondisifilter = array();
      if ($request->kata_kunci != '' ) {
          array_push($kondisifilter, array('nm_wilayah', 'ilike', '%'.$request->kata_kunci.'%'));
      }
      $this->data['filter'] = $this->selected;
      $this->data['route'] = $request->route()->getName();

      $qresults = DB::table('tr_wilayah')->where($kondisifilter)->where('kd_jnswilayah','1');

      $total_results = $qresults->count();
      $offset = ($current_page-1) * $limit;
      $totalpage = ceil($total_results / $limit);

      $datas = $qresults->orderBy('nm_wilayah', $order)->offset($offset)->limit($limit)->get();

      $this->data['datamaster'] = json_decode($datas, true);

      if($kondisi){
        $filter = $kondisi;
        $filtering = true;
      }else{
        $filter = '/';
        $filtering = false;
      }

      $requestwilayah = $client->request('GET', $baseUrl.'/api/wilayah');
      $wilayah = json_decode($requestwilayah->getBody()->getContents(), true);
      $this->data['wilayah'] = $wilayah['data'];

      $this->data['title']="Master Data Provinsi";
      $this->data['delete_route'] = 'delete_dataPropinsi';
      $this->data['current_page'] = $current_page;
      $this->data['start_number'] = $start_number;
      $this->data['pagination'] = paginations($current_page,$total_results, $limit, config('app.page_ellipsis'),config('app.url')."/master/propinsi",$filtering,$filter );
      $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

      return view('master.wilayah.index_dataPropinsi',$this->data);
  }

  public function inputDataPropinsi(Request $request){
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');
    $client = new Client();

    $inputData = $request->except(['_token', 'id']);
    // $inputData['status'] = TRUE;
    $inputData['kd_jnswilayah'] = '1';

    DB::table('tr_wilayah')->insert($inputData);

    $trail['audit_menu'] = 'Master Data - Provinsi';
    $trail['audit_event'] = 'post';
    $trail['audit_value'] = json_encode($inputData);
    $trail['audit_url'] = $request->url();
    $trail['audit_ip_address'] = $request->ip();
    $trail['audit_user_agent'] = $request->userAgent();
    $trail['audit_message'] = '';
    $trail['created_at'] = date("Y-m-d H:i:s");
    $trail['created_at'] = $request->session()->get('id');

    $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


    $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

    return redirect('/master/propinsi');
  }

  public function updateDataPropinsi(Request $request){
    $id = $request->input('id');
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');
    $client = new Client();

    $inputData = $request->except(['_token', 'id']);
    // $inputData['status'] = TRUE;
    $inputData['kd_jnswilayah'] = '1';

    DB::table('tr_wilayah')
            ->where('id_wilayah', $id)
            ->update($inputData);

            $trail['audit_menu'] = 'Master Data - Provinsi';
            $trail['audit_event'] = 'put';
            $trail['audit_value'] = json_encode($inputData);
            $trail['audit_url'] = $request->url();
            $trail['audit_ip_address'] = $request->ip();
            $trail['audit_user_agent'] = $request->userAgent();
            $trail['audit_message'] = '';
            $trail['created_at'] = date("Y-m-d H:i:s");
            $trail['created_at'] = $request->session()->get('id');

            $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


    $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

    return redirect('/master/propinsi');
  }

  public function deleteDataPropinsi(Request $request){
      if ($request->ajax()) {
          $id = $request->id;
          $del = DB::table('tr_wilayah')->where('id_wilayah', $id)->delete();
          $this->form_params['delete_id'] = $id;
          $trail['audit_menu'] = 'Master Data - Provinsi';
          $trail['audit_event'] = 'delete';
          $trail['audit_value'] = json_encode($this->form_params);
          $trail['audit_url'] = $request->url();
          $trail['audit_ip_address'] = $request->ip();
          $trail['audit_user_agent'] = $request->userAgent();
          $trail['audit_message'] = '';
          $trail['created_at'] = date("Y-m-d H:i:s");
          $trail['created_at'] = $request->session()->get('id');

          $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

          if($del){
              $data_request = ['code'=>200,'status'=>'Sukses','message'=>'Data Master Propinsi Berhasil Dihapus'];
              return $data_request;
          }else{
              $data_request = ['code'=>200,'status'=>'error','message'=>'Data Master Propinsi Gagal Dihapus'];
              return $data_request;
          }
      }
  }

  public function dataKota(Request $request){
      $client = new Client();
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');

      $kondisi = '';
      if($request->limit) {
        $limit = $request->limit;
      } else {
        $limit = config('app.limit');
      }
      if ($request->order==Null) {
          $order = 'asc';
      } else {
          $order = $request->order;
      }

      if ($request->limit==Null) {
          $limit = config('constant.LIMITPAGE');
      } else {
          $limit = $request->limit;
      }
      if ($request->page==Null) {
          $current_page = 1;
          $start_number = 1;
      } else {
          $current_page = $request->page;
          $start_number = ($limit * ($request->page -1 )) + 1;
      }

      if($request->isMethod('get')){
        $get = $request->all();
        $get = $request->except(['page']);
        $tipe = $request->tipe;
        if(count($get)>0){
          $this->selected['tipe']  = $tipe;
          foreach ($get as $key => $value) {
            $kondisi .= "&".$key.'='.$value;
            $this->selected[$key] = $value;
          }
        }
      } else {
        $tipe = $request->tipe;
        $kata_kunci = $request->kata_kunci;

        $kondisi .= '&kata_kunci='.$kata_kunci;
        $this->selected['kata_kunci'] = $kata_kunci;
        $kondisi .= '&tipe='.$tipe;
        $this->selected['tipe'] = $tipe;

        $kondisi .='&limit='.$limit;
        $kondisi .='&order='.$order;
        $this->selected['limit'] = $limit;
        $this->selected['order'] = $order;
      }

      $kondisifilter = array();
      if ($request->kata_kunci != '' ) {
        if ($request->tipe == 'propinsi' ) {
          array_push($kondisifilter, array('t2.nm_wilayah', 'ilike', '%'.$request->kata_kunci.'%'));
        }
      }
      $this->data['filter'] = $this->selected;
      $this->data['route'] = $request->route()->getName();

      $qresults = DB::table('tr_wilayah')
                      ->leftJoin('tr_wilayah as t2', 'tr_wilayah.wil_id_wilayah', '=', 't2.id_wilayah')
                      ->leftJoin('tr_jnswilayah as t3', 'tr_wilayah.kd_jnswilayah', '=', 't3.kd_jnswilayah')
                      ->select('tr_wilayah.*', 't2.nm_wilayah as nm_propinsi')
                      ->selectRaw("t3.nm_jnswilayah || ' ' || tr_wilayah.nm_wilayah AS nama")
                      ->where($kondisifilter)
                      ->whereIn('tr_wilayah.kd_jnswilayah',['2', '5', '6']);

      if ($request->tipe == 'nama' ) {
        $qresults  = $qresults->where(function ($query) use ($request) {
            $query->where('t3.nm_jnswilayah', 'ilike', '%'.$request->kata_kunci.'%')->orWhere('tr_wilayah.nm_wilayah', 'ilike', '%'.$request->kata_kunci.'%');
        });
      }

      $total_results = $qresults->count();
      $offset = ($current_page-1) * $limit;
      $totalpage = ceil($total_results / $limit);

      $datas = $qresults->orderBy('nm_wilayah', $order)->offset($offset)->limit($limit)->get();

      $this->data['datamaster'] = json_decode($datas, true);

      if($kondisi){
        $filter = $kondisi;
        $filtering = true;
      }else{
        $filter = '/';
        $filtering = false;
      }


      $this->data['propinsi'] = MainModel::getPropinsi();

      $this->data['title']="Master Data Kota";
      $this->data['delete_route'] = 'delete_dataKota';
      $this->data['current_page'] = $current_page;
      $this->data['start_number'] = $start_number;
      $this->data['pagination'] = paginations($current_page,$total_results, $limit, config('app.page_ellipsis'),config('app.url')."/master/kota",$filtering,$filter );
      $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

      return view('master.wilayah.index_dataKota',$this->data);
  }

  public function inputDataKota(Request $request){
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');
    $client = new Client();

    $inputData = $request->except(['_token', 'id']);
    // $inputData['status'] = TRUE;
    // $inputData['kd_jnswilayah'] = '1';

    DB::table('tr_wilayah')->insert($inputData);

    $trail['audit_menu'] = 'Master Data - Kota';
    $trail['audit_event'] = 'post';
    $trail['audit_value'] = json_encode($inputData);
    $trail['audit_url'] = $request->url();
    $trail['audit_ip_address'] = $request->ip();
    $trail['audit_user_agent'] = $request->userAgent();
    $trail['audit_message'] = '';
    $trail['created_at'] = date("Y-m-d H:i:s");
    $trail['created_at'] = $request->session()->get('id');

    $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


    $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

    return redirect('/master/kota');
  }

  public function updateDataKota(Request $request){
    $id = $request->input('id');
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');
    $client = new Client();

    $inputData = $request->except(['_token', 'id']);
    // $inputData['status'] = TRUE;
    // $inputData['kd_jnswilayah'] = '1';

    DB::table('tr_wilayah')
            ->where('id_wilayah', $id)
            ->update($inputData);

            $trail['audit_menu'] = 'Master Data - Kota';
            $trail['audit_event'] = 'put';
            $trail['audit_value'] = json_encode($inputData);
            $trail['audit_url'] = $request->url();
            $trail['audit_ip_address'] = $request->ip();
            $trail['audit_user_agent'] = $request->userAgent();
            $trail['audit_message'] = '';
            $trail['created_at'] = date("Y-m-d H:i:s");
            $trail['created_at'] = $request->session()->get('id');

            $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


    $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

    return redirect('/master/kota');
  }

  public function deleteDataKota(Request $request){
      if ($request->ajax()) {
          $id = $request->id;
          $del = DB::table('tr_wilayah')->where('id_wilayah', $id)->delete();
          $this->form_params['delete_id'] = $id;
          $trail['audit_menu'] = 'Master Data - Kota';
          $trail['audit_event'] = 'delete';
          $trail['audit_value'] = json_encode($this->form_params);
          $trail['audit_url'] = $request->url();
          $trail['audit_ip_address'] = $request->ip();
          $trail['audit_user_agent'] = $request->userAgent();
          $trail['audit_message'] = '';
          $trail['created_at'] = date("Y-m-d H:i:s");
          $trail['created_at'] = $request->session()->get('id');

          $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

          if($del){
              $data_request = ['code'=>200,'status'=>'Sukses','message'=>'Data Master Kota Berhasil Dihapus'];
              return $data_request;
          }else{
              $data_request = ['code'=>200,'status'=>'error','message'=>'Data Master Kota Gagal Dihapus'];
              return $data_request;
          }
      }
  }

  public function dataMediaonline(Request $request){
      $client = new Client();
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');

      $kondisi = '';
      if($request->limit) {
        $limit = $request->limit;
      } else {
        $limit = config('app.limit');
      }
      if ($request->order==Null) {
          $order = 'asc';
      } else {
          $order = $request->order;
      }

      if ($request->limit==Null) {
          $limit = config('constant.LIMITPAGE');
      } else {
          $limit = $request->limit;
      }
      if ($request->page==Null) {
          $current_page = 1;
          $start_number = 1;
      } else {
          $current_page = $request->page;
          $start_number = ($limit * ($request->page -1 )) + 1;
      }

      if($request->isMethod('get')){
        $get = $request->all();
        $get = $request->except(['page']);
        $tipe = $request->tipe;
        if(count($get)>0){
          $this->selected['tipe']  = $tipe;
          foreach ($get as $key => $value) {
            $kondisi .= "&".$key.'='.$value;
            $this->selected[$key] = $value;
          }
        }
      } else {
        $tipe = $request->tipe;
        $kata_kunci = $request->kata_kunci;

        $kondisi .= '&kata_kunci='.$kata_kunci;
        $this->selected['kata_kunci'] = $kata_kunci;
        $kondisi .= '&tipe='.$tipe;
        $this->selected['tipe'] = $tipe;

        $kondisi .='&limit='.$limit;
        $kondisi .='&order='.$order;
        $this->selected['limit'] = $limit;
        $this->selected['order'] = $order;
      }

      $kondisifilter = array();
      if ($request->kata_kunci != '' ) {
        if ($request->tipe == 'nama' ) {
          array_push($kondisifilter, array('nama_media', 'ilike', '%'.$request->kata_kunci.'%'));
        }else if ($request->tipe == 'kode' ) {
          array_push($kondisifilter, array('value_media', 'ilike', '%'.$request->kata_kunci.'%'));
        }
      }
      $this->data['filter'] = $this->selected;
      $this->data['route'] = $request->route()->getName();

      $qresults = DB::table('tr_media')->where($kondisifilter)->where('jenis','mediaonline');

      $total_results = $qresults->count();
      $offset = ($current_page-1) * $limit;
      $totalpage = ceil($total_results / $limit);

      $datas = $qresults->orderBy('nama_media', $order)->offset($offset)->limit($limit)->get();

      $this->data['datamaster'] = json_decode($datas, true);

      if($kondisi){
        $filter = $kondisi;
        $filtering = true;
      }else{
        $filter = '/';
        $filtering = false;
      }

      $this->data['title']="Master Data Media Online";
      $this->data['delete_route'] = 'delete_dataMediaonline';
      $this->data['current_page'] = $current_page;
      $this->data['start_number'] = $start_number;
      $this->data['pagination'] = paginations($current_page,$total_results, $limit, config('app.page_ellipsis'),config('app.url')."/master/mediaonline",$filtering,$filter );
      $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

      return view('master.media.index_dataMediaonline',$this->data);
  }

  public function inputDataMediaonline(Request $request){
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');
    $client = new Client();

    $inputData = $request->except(['_token', 'id']);
    $inputData['jenis'] = 'mediaonline';

    DB::table('tr_media')->insert($inputData);

    $trail['audit_menu'] = 'Master Data - Media Online';
    $trail['audit_event'] = 'post';
    $trail['audit_value'] = json_encode($inputData);
    $trail['audit_url'] = $request->url();
    $trail['audit_ip_address'] = $request->ip();
    $trail['audit_user_agent'] = $request->userAgent();
    $trail['audit_message'] = '';
    $trail['created_at'] = date("Y-m-d H:i:s");
    $trail['created_at'] = $request->session()->get('id');

    $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


    $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

    return redirect('/master/mediaonline');
  }

  public function updateDataMediaonline(Request $request){
    $id = $request->input('id');
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');
    $client = new Client();

    $inputData = $request->except(['_token', 'id']);
    $inputData['jenis'] = 'mediaonline';

    DB::table('tr_media')
            ->where('id', $id)
            ->update($inputData);

            $trail['audit_menu'] = 'Master Data - Media Online';
            $trail['audit_event'] = 'put';
            $trail['audit_value'] = json_encode($inputData);
            $trail['audit_url'] = $request->url();
            $trail['audit_ip_address'] = $request->ip();
            $trail['audit_user_agent'] = $request->userAgent();
            $trail['audit_message'] = '';
            $trail['created_at'] = date("Y-m-d H:i:s");
            $trail['created_at'] = $request->session()->get('id');

            $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


    $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

    return redirect('/master/mediaonline');
  }

  public function deleteDataMediaonline(Request $request){
      if ($request->ajax()) {
          $id = $request->id;
          $del = DB::table('tr_media')->where('id', $id)->delete();
          $this->form_params['delete_id'] = $id;
          $trail['audit_menu'] = 'Master Data - Media Online';
          $trail['audit_event'] = 'delete';
          $trail['audit_value'] = json_encode($this->form_params);
          $trail['audit_url'] = $request->url();
          $trail['audit_ip_address'] = $request->ip();
          $trail['audit_user_agent'] = $request->userAgent();
          $trail['audit_message'] = '';
          $trail['created_at'] = date("Y-m-d H:i:s");
          $trail['created_at'] = $request->session()->get('id');

          $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

          if($del){
              $data_request = ['code'=>200,'status'=>'Sukses','message'=>'Data Master Media Online Berhasil Dihapus'];
              return $data_request;
          }else{
              $data_request = ['code'=>200,'status'=>'error','message'=>'Data Master Media Online Gagal Dihapus'];
              return $data_request;
          }
      }
  }

  public function dataMediasosial(Request $request){
      $client = new Client();
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');

      $kondisi = '';
      if($request->limit) {
        $limit = $request->limit;
      } else {
        $limit = config('app.limit');
      }
      if ($request->order==Null) {
          $order = 'asc';
      } else {
          $order = $request->order;
      }

      if ($request->limit==Null) {
          $limit = config('constant.LIMITPAGE');
      } else {
          $limit = $request->limit;
      }
      if ($request->page==Null) {
          $current_page = 1;
          $start_number = 1;
      } else {
          $current_page = $request->page;
          $start_number = ($limit * ($request->page -1 )) + 1;
      }

      if($request->isMethod('get')){
        $get = $request->all();
        $get = $request->except(['page']);
        $tipe = $request->tipe;
        if(count($get)>0){
          $this->selected['tipe']  = $tipe;
          foreach ($get as $key => $value) {
            $kondisi .= "&".$key.'='.$value;
            $this->selected[$key] = $value;
          }
        }
      } else {
        $tipe = $request->tipe;
        $kata_kunci = $request->kata_kunci;

        $kondisi .= '&kata_kunci='.$kata_kunci;
        $this->selected['kata_kunci'] = $kata_kunci;
        $kondisi .= '&tipe='.$tipe;
        $this->selected['tipe'] = $tipe;

        $kondisi .='&limit='.$limit;
        $kondisi .='&order='.$order;
        $this->selected['limit'] = $limit;
        $this->selected['order'] = $order;
      }

      $kondisifilter = array();
      if ($request->kata_kunci != '' ) {
        if ($request->tipe == 'nama' ) {
          array_push($kondisifilter, array('tr_media.nama_media', 'ilike', '%'.$request->kata_kunci.'%'));
        }else if ($request->tipe == 'kode' ) {
          array_push($kondisifilter, array('tr_media.value_media', 'ilike', '%'.$request->kata_kunci.'%'));
        }else if ($request->tipe == 'jenis' ) {
          array_push($kondisifilter, array('t2.nama_media', 'ilike', '%'.$request->kata_kunci.'%'));
        }
      }
      $this->data['filter'] = $this->selected;
      $this->data['route'] = $request->route()->getName();

      $jenis = DB::table('tr_media')->where('jenis','mediaonline')->orderBy('nama_media', 'asc')->get();
      $this->data['jenis'] = json_decode($jenis, true);

      $qresults = DB::table('tr_media')
                      ->leftJoin('tr_media as t2', 'tr_media.parent_id', '=', 't2.id')
                      ->select('tr_media.*', 't2.nama_media as nama_parent')
                      ->where('tr_media.jenis','mediasosial')
                      ->where($kondisifilter);

      $total_results = $qresults->count();
      $offset = ($current_page-1) * $limit;
      $totalpage = ceil($total_results / $limit);

      $datas = $qresults->orderBy('nama_media', $order)->offset($offset)->limit($limit)->get();

      $this->data['datamaster'] = json_decode($datas, true);

      if($kondisi){
        $filter = $kondisi;
        $filtering = true;
      }else{
        $filter = '/';
        $filtering = false;
      }

      $this->data['title']="Master Data Media Sosial";
      $this->data['delete_route'] = 'delete_dataMediasosial';
      $this->data['current_page'] = $current_page;
      $this->data['start_number'] = $start_number;
      $this->data['pagination'] = paginations($current_page,$total_results, $limit, config('app.page_ellipsis'),config('app.url')."/master/mediasosial",$filtering,$filter );
      $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

      return view('master.media.index_dataMediasosial',$this->data);
  }

  public function inputDataMediasosial(Request $request){
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');
    $client = new Client();

    $inputData = $request->except(['_token', 'id']);
    $inputData['jenis'] = 'mediasosial';

    DB::table('tr_media')->insert($inputData);

    $trail['audit_menu'] = 'Master Data - Media Sosial';
    $trail['audit_event'] = 'post';
    $trail['audit_value'] = json_encode($inputData);
    $trail['audit_url'] = $request->url();
    $trail['audit_ip_address'] = $request->ip();
    $trail['audit_user_agent'] = $request->userAgent();
    $trail['audit_message'] = '';
    $trail['created_at'] = date("Y-m-d H:i:s");
    $trail['created_at'] = $request->session()->get('id');

    $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


    $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

    return redirect('/master/mediasosial');
  }

  public function updateDataMediasosial(Request $request){
    $id = $request->input('id');
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');
    $client = new Client();

    $inputData = $request->except(['_token', 'id']);
    $inputData['jenis'] = 'mediasosial';

    DB::table('tr_media')
            ->where('id', $id)
            ->update($inputData);

            $trail['audit_menu'] = 'Master Data - Media Sosial';
            $trail['audit_event'] = 'put';
            $trail['audit_value'] = json_encode($inputData);
            $trail['audit_url'] = $request->url();
            $trail['audit_ip_address'] = $request->ip();
            $trail['audit_user_agent'] = $request->userAgent();
            $trail['audit_message'] = '';
            $trail['created_at'] = date("Y-m-d H:i:s");
            $trail['created_at'] = $request->session()->get('id');

            $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


    $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

    return redirect('/master/mediasosial');
  }

  public function deleteDataMediasosial(Request $request){
      if ($request->ajax()) {
          $id = $request->id;
          $del = DB::table('tr_media')->where('id', $id)->delete();
          $this->form_params['delete_id'] = $id;
          $trail['audit_menu'] = 'Master Data - Media Sosial';
          $trail['audit_event'] = 'delete';
          $trail['audit_value'] = json_encode($this->form_params);
          $trail['audit_url'] = $request->url();
          $trail['audit_ip_address'] = $request->ip();
          $trail['audit_user_agent'] = $request->userAgent();
          $trail['audit_message'] = '';
          $trail['created_at'] = date("Y-m-d H:i:s");
          $trail['created_at'] = $request->session()->get('id');

          $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

          if($del){
              $data_request = ['code'=>200,'status'=>'Sukses','message'=>'Data Master Media Sosial Berhasil Dihapus'];
              return $data_request;
          }else{
              $data_request = ['code'=>200,'status'=>'error','message'=>'Data Master Media Sosial Gagal Dihapus'];
              return $data_request;
          }
      }
  }

  public function dataMediacetak(Request $request){
      $client = new Client();
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');

      $kondisi = '';
      if($request->limit) {
        $limit = $request->limit;
      } else {
        $limit = config('app.limit');
      }
      if ($request->order==Null) {
          $order = 'asc';
      } else {
          $order = $request->order;
      }

      if ($request->limit==Null) {
          $limit = config('constant.LIMITPAGE');
      } else {
          $limit = $request->limit;
      }
      if ($request->page==Null) {
          $current_page = 1;
          $start_number = 1;
      } else {
          $current_page = $request->page;
          $start_number = ($limit * ($request->page -1 )) + 1;
      }

      if($request->isMethod('get')){
        $get = $request->all();
        $get = $request->except(['page']);
        $tipe = $request->tipe;
        if(count($get)>0){
          $this->selected['tipe']  = $tipe;
          foreach ($get as $key => $value) {
            $kondisi .= "&".$key.'='.$value;
            $this->selected[$key] = $value;
          }
        }
      } else {
        $tipe = $request->tipe;
        $kata_kunci = $request->kata_kunci;

        $kondisi .= '&kata_kunci='.$kata_kunci;
        $this->selected['kata_kunci'] = $kata_kunci;
        $kondisi .= '&tipe='.$tipe;
        $this->selected['tipe'] = $tipe;

        $kondisi .='&limit='.$limit;
        $kondisi .='&order='.$order;
        $this->selected['limit'] = $limit;
        $this->selected['order'] = $order;
      }

      $kondisifilter = array();
      if ($request->kata_kunci != '' ) {
        if ($request->tipe == 'nama' ) {
          array_push($kondisifilter, array('nama_media', 'ilike', '%'.$request->kata_kunci.'%'));
        }else if ($request->tipe == 'kode' ) {
          array_push($kondisifilter, array('value_media', 'ilike', '%'.$request->kata_kunci.'%'));
        }
      }
      $this->data['filter'] = $this->selected;
      $this->data['route'] = $request->route()->getName();

      $qresults = DB::table('tr_media')->where($kondisifilter)->where('jenis','mediacetak');

      $total_results = $qresults->count();
      $offset = ($current_page-1) * $limit;
      $totalpage = ceil($total_results / $limit);

      $datas = $qresults->orderBy('nama_media', $order)->offset($offset)->limit($limit)->get();

      $this->data['datamaster'] = json_decode($datas, true);

      if($kondisi){
        $filter = $kondisi;
        $filtering = true;
      }else{
        $filter = '/';
        $filtering = false;
      }

      $this->data['title']="Master Data Media Cetak";
      $this->data['delete_route'] = 'delete_dataMediacetak';
      $this->data['current_page'] = $current_page;
      $this->data['start_number'] = $start_number;
      $this->data['pagination'] = paginations($current_page,$total_results, $limit, config('app.page_ellipsis'),config('app.url')."/master/mediacetak",$filtering,$filter );
      $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

      return view('master.media.index_dataMediacetak',$this->data);
  }

  public function inputDataMediacetak(Request $request){
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');
    $client = new Client();

    $inputData = $request->except(['_token', 'id']);
    $inputData['jenis'] = 'mediacetak';

    DB::table('tr_media')->insert($inputData);

    $trail['audit_menu'] = 'Master Data - Media Cetak';
    $trail['audit_event'] = 'post';
    $trail['audit_value'] = json_encode($inputData);
    $trail['audit_url'] = $request->url();
    $trail['audit_ip_address'] = $request->ip();
    $trail['audit_user_agent'] = $request->userAgent();
    $trail['audit_message'] = '';
    $trail['created_at'] = date("Y-m-d H:i:s");
    $trail['created_at'] = $request->session()->get('id');

    $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


    $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

    return redirect('/master/mediacetak');
  }

  public function updateDataMediacetak(Request $request){
    $id = $request->input('id');
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');
    $client = new Client();

    $inputData = $request->except(['_token', 'id']);
    $inputData['jenis'] = 'mediacetak';

    DB::table('tr_media')
            ->where('id', $id)
            ->update($inputData);

            $trail['audit_menu'] = 'Master Data - Media Cetak';
            $trail['audit_event'] = 'put';
            $trail['audit_value'] = json_encode($inputData);
            $trail['audit_url'] = $request->url();
            $trail['audit_ip_address'] = $request->ip();
            $trail['audit_user_agent'] = $request->userAgent();
            $trail['audit_message'] = '';
            $trail['created_at'] = date("Y-m-d H:i:s");
            $trail['created_at'] = $request->session()->get('id');

            $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


    $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

    return redirect('/master/mediacetak');
  }

  public function deleteDataMediacetak(Request $request){
      if ($request->ajax()) {
          $id = $request->id;
          $del = DB::table('tr_media')->where('id', $id)->delete();
          $this->form_params['delete_id'] = $id;
          $trail['audit_menu'] = 'Master Data - Media Cetak';
          $trail['audit_event'] = 'delete';
          $trail['audit_value'] = json_encode($this->form_params);
          $trail['audit_url'] = $request->url();
          $trail['audit_ip_address'] = $request->ip();
          $trail['audit_user_agent'] = $request->userAgent();
          $trail['audit_message'] = '';
          $trail['created_at'] = date("Y-m-d H:i:s");
          $trail['created_at'] = $request->session()->get('id');

          $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

          if($del){
              $data_request = ['code'=>200,'status'=>'Sukses','message'=>'Data Master Media Cetak Berhasil Dihapus'];
              return $data_request;
          }else{
              $data_request = ['code'=>200,'status'=>'error','message'=>'Data Master Media Cetak Gagal Dihapus'];
              return $data_request;
          }
      }
  }

  public function dataMediaruang(Request $request){
      $client = new Client();
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');

      $kondisi = '';
      if($request->limit) {
        $limit = $request->limit;
      } else {
        $limit = config('app.limit');
      }
      if ($request->order==Null) {
          $order = 'asc';
      } else {
          $order = $request->order;
      }

      if ($request->limit==Null) {
          $limit = config('constant.LIMITPAGE');
      } else {
          $limit = $request->limit;
      }
      if ($request->page==Null) {
          $current_page = 1;
          $start_number = 1;
      } else {
          $current_page = $request->page;
          $start_number = ($limit * ($request->page -1 )) + 1;
      }

      if($request->isMethod('get')){
        $get = $request->all();
        $get = $request->except(['page']);
        $tipe = $request->tipe;
        if(count($get)>0){
          $this->selected['tipe']  = $tipe;
          foreach ($get as $key => $value) {
            $kondisi .= "&".$key.'='.$value;
            $this->selected[$key] = $value;
          }
        }
      } else {
        $tipe = $request->tipe;
        $kata_kunci = $request->kata_kunci;

        $kondisi .= '&kata_kunci='.$kata_kunci;
        $this->selected['kata_kunci'] = $kata_kunci;
        $kondisi .= '&tipe='.$tipe;
        $this->selected['tipe'] = $tipe;

        $kondisi .='&limit='.$limit;
        $kondisi .='&order='.$order;
        $this->selected['limit'] = $limit;
        $this->selected['order'] = $order;
      }

      $kondisifilter = array();
      if ($request->kata_kunci != '' ) {
        if ($request->tipe == 'nama' ) {
          array_push($kondisifilter, array('tr_media.nama_media', 'ilike', '%'.$request->kata_kunci.'%'));
        }else if ($request->tipe == 'kode' ) {
          array_push($kondisifilter, array('tr_media.value_media', 'ilike', '%'.$request->kata_kunci.'%'));
        }else if ($request->tipe == 'jenis' ) {
          array_push($kondisifilter, array('t2.nama_media', 'ilike', '%'.$request->kata_kunci.'%'));
        }
      }
      $this->data['filter'] = $this->selected;
      $this->data['route'] = $request->route()->getName();

      $jenis = DB::table('tr_media')->where('jenis','mediacetak')->orderBy('nama_media', 'asc')->get();
      $this->data['jenis'] = json_decode($jenis, true);

      $qresults = DB::table('tr_media')
                      ->leftJoin('tr_media as t2', 'tr_media.parent_id', '=', 't2.id')
                      ->select('tr_media.*', 't2.nama_media as nama_parent')
                      ->where('tr_media.jenis','mediaruang')
                      ->where($kondisifilter);

      $total_results = $qresults->count();
      $offset = ($current_page-1) * $limit;
      $totalpage = ceil($total_results / $limit);

      $datas = $qresults->orderBy('nama_media', $order)->offset($offset)->limit($limit)->get();

      $this->data['datamaster'] = json_decode($datas, true);

      if($kondisi){
        $filter = $kondisi;
        $filtering = true;
      }else{
        $filter = '/';
        $filtering = false;
      }

      $this->data['title']="Master Data Media Luar Ruang";
      $this->data['delete_route'] = 'delete_dataMediaruang';
      $this->data['current_page'] = $current_page;
      $this->data['start_number'] = $start_number;
      $this->data['pagination'] = paginations($current_page,$total_results, $limit, config('app.page_ellipsis'),config('app.url')."/master/mediaruang",$filtering,$filter );
      $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

      return view('master.media.index_dataMediaruang',$this->data);
  }

  public function inputDataMediaruang(Request $request){
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');
    $client = new Client();

    $inputData = $request->except(['_token', 'id']);
    $inputData['jenis'] = 'mediaruang';

    DB::table('tr_media')->insert($inputData);

    $trail['audit_menu'] = 'Master Data - Media Ruang';
    $trail['audit_event'] = 'post';
    $trail['audit_value'] = json_encode($inputData);
    $trail['audit_url'] = $request->url();
    $trail['audit_ip_address'] = $request->ip();
    $trail['audit_user_agent'] = $request->userAgent();
    $trail['audit_message'] = '';
    $trail['created_at'] = date("Y-m-d H:i:s");
    $trail['created_at'] = $request->session()->get('id');

    $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


    $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

    return redirect('/master/mediaruang');
  }

  public function updateDataMediaruang(Request $request){
    $id = $request->input('id');
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');
    $client = new Client();

    $inputData = $request->except(['_token', 'id']);
    $inputData['jenis'] = 'mediaruang';

    DB::table('tr_media')
            ->where('id', $id)
            ->update($inputData);

            $trail['audit_menu'] = 'Master Data - Media Ruang';
            $trail['audit_event'] = 'put';
            $trail['audit_value'] = json_encode($inputData);
            $trail['audit_url'] = $request->url();
            $trail['audit_ip_address'] = $request->ip();
            $trail['audit_user_agent'] = $request->userAgent();
            $trail['audit_message'] = '';
            $trail['created_at'] = date("Y-m-d H:i:s");
            $trail['created_at'] = $request->session()->get('id');

            $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


    $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

    return redirect('/master/mediaruang');
  }

  public function deleteDataMediaruang(Request $request){
      if ($request->ajax()) {
          $id = $request->id;
          $del = DB::table('tr_media')->where('id', $id)->delete();
          $this->form_params['delete_id'] = $id;
          $trail['audit_menu'] = 'Master Data - Media Ruang';
          $trail['audit_event'] = 'delete';
          $trail['audit_value'] = json_encode($this->form_params);
          $trail['audit_url'] = $request->url();
          $trail['audit_ip_address'] = $request->ip();
          $trail['audit_user_agent'] = $request->userAgent();
          $trail['audit_message'] = '';
          $trail['created_at'] = date("Y-m-d H:i:s");
          $trail['created_at'] = $request->session()->get('id');

          $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

          if($del){
              $data_request = ['code'=>200,'status'=>'Sukses','message'=>'Data Master Media Luar Ruang Berhasil Dihapus'];
              return $data_request;
          }else{
              $data_request = ['code'=>200,'status'=>'error','message'=>'Data Master Media Luar Ruang Gagal Dihapus'];
              return $data_request;
          }
      }
  }

  public function dataBagian(Request $request){
      $client = new Client();
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');

      $kondisi = '';
      if($request->limit) {
        $limit = $request->limit;
      } else {
        $limit = config('app.limit');
      }
      if ($request->order==Null) {
          $order = 'asc';
      } else {
          $order = $request->order;
      }

      if ($request->limit==Null) {
          $limit = config('constant.LIMITPAGE');
      } else {
          $limit = $request->limit;
      }
      if ($request->page==Null) {
          $current_page = 1;
          $start_number = 1;
      } else {
          $current_page = $request->page;
          $start_number = ($limit * ($request->page -1 )) + 1;
      }

      if($request->isMethod('get')){
        $get = $request->all();
        $get = $request->except(['page']);
        $tipe = $request->tipe;
        if(count($get)>0){
          $this->selected['tipe']  = $tipe;
          foreach ($get as $key => $value) {
            $kondisi .= "&".$key.'='.$value;
            $this->selected[$key] = $value;
          }
        }
      } else {
        $tipe = $request->tipe;
        $kata_kunci = $request->kata_kunci;

        $kondisi .= '&kata_kunci='.$kata_kunci;
        $this->selected['kata_kunci'] = $kata_kunci;
        $kondisi .= '&tipe='.$tipe;
        $this->selected['tipe'] = $tipe;

        $kondisi .='&limit='.$limit;
        $kondisi .='&order='.$order;
        $this->selected['limit'] = $limit;
        $this->selected['order'] = $order;
      }

      $kondisifilter = array();
      if ($request->kata_kunci != '' ) {
        if ($request->tipe == 'nama' ) {
          array_push($kondisifilter, array('settama_lookup.lookup_name', 'ilike', '%'.$request->kata_kunci.'%'));
        }else if ($request->tipe == 'biro' ) {
          array_push($kondisifilter, array('t2.lookup_name', 'ilike', '%'.$request->kata_kunci.'%'));
        }
      }
      $this->data['filter'] = $this->selected;
      $this->data['route'] = $request->route()->getName();

      $jenis = DB::table('settama_lookup')->where('type','biro')->orderBy('id_lookup_parent', 'asc')->get();
      $this->data['biro'] = json_decode($jenis, true);

      $qresults = DB::table('settama_lookup')
                      ->leftJoin('settama_lookup as t2', 'settama_lookup.id_lookup_parent', '=', 't2.id_settama_lookup')
                      ->select('settama_lookup.*', 't2.lookup_name as nama_parent')
                      ->where('settama_lookup.type','pelaksana')
                      ->where($kondisifilter);

      $total_results = $qresults->count();
      $offset = ($current_page-1) * $limit;
      $totalpage = ceil($total_results / $limit);

      $datas = $qresults->orderBy('id_lookup_parent', $order)->offset($offset)->limit($limit)->get();

      $this->data['datamaster'] = json_decode($datas, true);

      if($kondisi){
        $filter = $kondisi;
        $filtering = true;
      }else{
        $filter = '/';
        $filtering = false;
      }

      $this->data['title']="Master Data Bagian";
      $this->data['delete_route'] = 'delete_dataBagian';
      $this->data['current_page'] = $current_page;
      $this->data['start_number'] = $start_number;
      $this->data['pagination'] = paginations($current_page,$total_results, $limit, config('app.page_ellipsis'),config('app.url')."/master/bagian",$filtering,$filter );
      $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

      return view('master.settama.index_dataBagian',$this->data);
  }

  public function inputDataBagian(Request $request){
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');
    $client = new Client();

    $inputData = $request->except(['_token', 'id']);
    $inputData['type'] = 'pelaksana';

    DB::table('settama_lookup')->insert($inputData);

    $trail['audit_menu'] = 'Master Data - Settama Bagian';
    $trail['audit_event'] = 'post';
    $trail['audit_value'] = json_encode($inputData);
    $trail['audit_url'] = $request->url();
    $trail['audit_ip_address'] = $request->ip();
    $trail['audit_user_agent'] = $request->userAgent();
    $trail['audit_message'] = '';
    $trail['created_at'] = date("Y-m-d H:i:s");
    $trail['created_at'] = $request->session()->get('id');

    $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


    $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

    return redirect('/master/bagian');
  }

  public function updateDataBagian(Request $request){
    $id = $request->input('id');
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');
    $client = new Client();

    $inputData = $request->except(['_token', 'id']);
    $inputData['type'] = 'pelaksana';

    DB::table('settama_lookup')
            ->where('id_settama_lookup', $id)
            ->update($inputData);

            $trail['audit_menu'] = 'Master Data - Settama Bagian';
            $trail['audit_event'] = 'put';
            $trail['audit_value'] = json_encode($inputData);
            $trail['audit_url'] = $request->url();
            $trail['audit_ip_address'] = $request->ip();
            $trail['audit_user_agent'] = $request->userAgent();
            $trail['audit_message'] = '';
            $trail['created_at'] = date("Y-m-d H:i:s");
            $trail['created_at'] = $request->session()->get('id');

            $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


    $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

    return redirect('/master/bagian');
  }

  public function deleteDataBagian(Request $request){
      if ($request->ajax()) {
          $id = $request->id;
          $del = DB::table('settama_lookup')->where('id_settama_lookup', $id)->delete();
          $this->form_params['delete_id'] = $id;
          $trail['audit_menu'] = 'Master Data - Settama Bagian';
          $trail['audit_event'] = 'delete';
          $trail['audit_value'] = json_encode($this->form_params);
          $trail['audit_url'] = $request->url();
          $trail['audit_ip_address'] = $request->ip();
          $trail['audit_user_agent'] = $request->userAgent();
          $trail['audit_message'] = '';
          $trail['created_at'] = date("Y-m-d H:i:s");
          $trail['created_at'] = $request->session()->get('id');

          $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

          if($del){
              $data_request = ['code'=>200,'status'=>'Sukses','message'=>'Data Master Data Bagian Berhasil Dihapus'];
              return $data_request;
          }else{
              $data_request = ['code'=>200,'status'=>'error','message'=>'Data Master Data Bagian Gagal Dihapus'];
              return $data_request;
          }
      }
  }

  public function dataKegiatan(Request $request){
      $client = new Client();
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');

      $kondisi = '';
      if($request->limit) {
        $limit = $request->limit;
      } else {
        $limit = config('app.limit');
      }
      if ($request->order==Null) {
          $order = 'asc';
      } else {
          $order = $request->order;
      }

      if ($request->limit==Null) {
          $limit = config('constant.LIMITPAGE');
      } else {
          $limit = $request->limit;
      }
      if ($request->page==Null) {
          $current_page = 1;
          $start_number = 1;
      } else {
          $current_page = $request->page;
          $start_number = ($limit * ($request->page -1 )) + 1;
      }

      if($request->isMethod('get')){
        $get = $request->all();
        $get = $request->except(['page']);
        $tipe = $request->tipe;
        if(count($get)>0){
          $this->selected['tipe']  = $tipe;
          foreach ($get as $key => $value) {
            $kondisi .= "&".$key.'='.$value;
            $this->selected[$key] = $value;
          }
        }
      } else {
        $tipe = $request->tipe;
        $kata_kunci = $request->kata_kunci;

        $kondisi .= '&kata_kunci='.$kata_kunci;
        $this->selected['kata_kunci'] = $kata_kunci;
        $kondisi .= '&tipe='.$tipe;
        $this->selected['tipe'] = $tipe;

        $kondisi .='&limit='.$limit;
        $kondisi .='&order='.$order;
        $this->selected['limit'] = $limit;
        $this->selected['order'] = $order;
      }

      $kondisifilter = array();
      if ($request->kata_kunci != '' ) {
        if ($request->tipe == 'nama' ) {
          array_push($kondisifilter, array('settama_lookup.lookup_name', 'ilike', '%'.$request->kata_kunci.'%'));
        }else if ($request->tipe == 'biro' ) {
          array_push($kondisifilter, array('t2.lookup_name', 'ilike', '%'.$request->kata_kunci.'%'));
        }
      }
      $this->data['filter'] = $this->selected;
      $this->data['route'] = $request->route()->getName();

      $jenis = DB::table('settama_lookup')->where('type','biro')->orderBy('id_lookup_parent', 'asc')->get();
      $this->data['biro'] = json_decode($jenis, true);

      $qresults = DB::table('settama_lookup')
                      ->leftJoin('settama_relasi_jnskegiatan as r', 'settama_lookup.id_settama_lookup', '=', 'r.id_lookup')
                      ->leftJoin('settama_lookup as t2', 'r.id_parent', '=', 't2.id_settama_lookup')
                      ->select('settama_lookup.id_settama_lookup', 'settama_lookup.lookup_name')
                      ->selectRaw('(array_agg(t2.id_settama_lookup)) as id_parent')
                      ->selectRaw('(array_agg(t2.lookup_name)) as nama_parent')
                      ->where('settama_lookup.type','kegiatan')
                      ->where($kondisifilter)
                      ->groupBy('settama_lookup.id_settama_lookup', 'settama_lookup.lookup_name');

      $total_results = $qresults->get()->count();
      $offset = ($current_page-1) * $limit;
      $totalpage = ceil($total_results / $limit);

      $datas = $qresults->orderBy('settama_lookup.lookup_name', $order)->offset($offset)->limit($limit)->get();

      $this->data['datamaster'] = json_decode($datas, true);

      if($kondisi){
        $filter = $kondisi;
        $filtering = true;
      }else{
        $filter = '/';
        $filtering = false;
      }

      $this->data['title']="Master Data Kegiatan";
      $this->data['delete_route'] = 'delete_dataKegiatan';
      $this->data['current_page'] = $current_page;
      $this->data['start_number'] = $start_number;
      $this->data['pagination'] = paginations($current_page,$total_results, $limit, config('app.page_ellipsis'),config('app.url')."/master/kegiatan",$filtering,$filter );
      $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

      return view('master.settama.index_dataKegiatan',$this->data);
  }

  public function inputDataKegiatan(Request $request){
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');
    $client = new Client();

    $inputData = $request->except(['_token', 'id', 'id_parent']);
    $inputData['type'] = 'kegiatan';
    $inputData['id_lookup_parent'] = 0;

    $insert = SettamaLookup::create($inputData);
    $insertID = $insert->id_settama_lookup;

    $trail['audit_menu'] = 'Master Data - Settama Kegiatan';
    $trail['audit_event'] = 'post';
    $trail['audit_value'] = json_encode($inputData);
    $trail['audit_url'] = $request->url();
    $trail['audit_ip_address'] = $request->ip();
    $trail['audit_user_agent'] = $request->userAgent();
    $trail['audit_message'] = '';
    $trail['created_at'] = date("Y-m-d H:i:s");
    $trail['created_at'] = $request->session()->get('id');

    $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


    foreach ($request->id_parent as $biro) {
      DB::table('settama_relasi_jnskegiatan')->insert(['id_parent' => $biro, 'id_lookup' => $insertID]);
    }
    $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

    return redirect('/master/kegiatan');
  }

  public function updateDataKegiatan(Request $request){
    $id = $request->input('id');
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');
    $client = new Client();

    $inputData = $request->except(['_token', 'id', 'id_parent']);
    $inputData['type'] = 'kegiatan';
    $inputData['id_lookup_parent'] = 0;

    DB::table('settama_relasi_jnskegiatan')->where('id_lookup', '=', $id)->delete();

    $update = SettamaLookup::findOrFail($id);
    $update->update($inputData);

    $trail['audit_menu'] = 'Master Data - Settama Kegiatan';
    $trail['audit_event'] = 'put';
    $trail['audit_value'] = json_encode($inputData);
    $trail['audit_url'] = $request->url();
    $trail['audit_ip_address'] = $request->ip();
    $trail['audit_user_agent'] = $request->userAgent();
    $trail['audit_message'] = '';
    $trail['created_at'] = date("Y-m-d H:i:s");
    $trail['created_at'] = $request->session()->get('id');

    $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


    foreach ($request->id_parent as $biro) {
      DB::table('settama_relasi_jnskegiatan')->insert(['id_parent' => $biro, 'id_lookup' => $id]);
    }
    $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

    return redirect('/master/kegiatan');
  }

  public function deleteDataKegiatan(Request $request){
      if ($request->ajax()) {
          $id = $request->id;
          $del = DB::table('settama_lookup')->where('id_settama_lookup', $id)->delete();

          $this->form_params['delete_id'] = $id;
          $trail['audit_menu'] = 'Master Data - Settama Kegiatan';
          $trail['audit_event'] = 'delete';
          $trail['audit_value'] = json_encode($this->form_params);
          $trail['audit_url'] = $request->url();
          $trail['audit_ip_address'] = $request->ip();
          $trail['audit_user_agent'] = $request->userAgent();
          $trail['audit_message'] = '';
          $trail['created_at'] = date("Y-m-d H:i:s");
          $trail['created_at'] = $request->session()->get('id');

          $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

          if($del){
              DB::table('settama_relasi_jnskegiatan')->where('id_lookup', '=', $id)->delete();
              $data_request = ['code'=>200,'status'=>'Sukses','message'=>'Data Master Data Kegiatan Berhasil Dihapus'];
              return $data_request;
          }else{
              $data_request = ['code'=>200,'status'=>'error','message'=>'Data Master Data Kegiatan Gagal Dihapus'];
              return $data_request;
          }
      }
  }

  public function dataKomoditi(Request $request){
      $client = new Client();
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');

      $kondisi = '';
      if($request->limit) {
        $limit = $request->limit;
      } else {
        $limit = config('app.limit');
      }
      if ($request->order==Null) {
          $order = 'asc';
      } else {
          $order = $request->order;
      }

      if ($request->limit==Null) {
          $limit = config('constant.LIMITPAGE');
      } else {
          $limit = $request->limit;
      }
      if ($request->page==Null) {
          $current_page = 1;
          $start_number = 1;
      } else {
          $current_page = $request->page;
          $start_number = ($limit * ($request->page -1 )) + 1;
      }

      if($request->isMethod('get')){
        $get = $request->all();
        $get = $request->except(['page']);
        $tipe = $request->tipe;
        if(count($get)>0){
          $this->selected['tipe']  = $tipe;
          foreach ($get as $key => $value) {
            $kondisi .= "&".$key.'='.$value;
            $this->selected[$key] = $value;
          }
        }
      } else {
        $tipe = $request->tipe;
        $kata_kunci = $request->kata_kunci;

        $kondisi .= '&kata_kunci='.$kata_kunci;
        $this->selected['kata_kunci'] = $kata_kunci;
        $kondisi .= '&tipe='.$tipe;
        $this->selected['tipe'] = $tipe;

        $kondisi .='&limit='.$limit;
        $kondisi .='&order='.$order;
        $this->selected['limit'] = $limit;
        $this->selected['order'] = $order;
      }

      $kondisifilter = array();
      if ($request->kata_kunci != '' ) {
          array_push($kondisifilter, array('nama_komoditi', 'ilike', '%'.$request->kata_kunci.'%'));
      }
      $this->data['filter'] = $this->selected;
      $this->data['route'] = $request->route()->getName();

      $qresults = DB::table('tr_komoditi')->where($kondisifilter);

      $total_results = $qresults->count();
      $offset = ($current_page-1) * $limit;
      $totalpage = ceil($total_results / $limit);

      $datas = $qresults->orderBy('nama_komoditi', $order)->offset($offset)->limit($limit)->get();

      $this->data['datamaster'] = json_decode($datas, true);

      if($kondisi){
        $filter = $kondisi;
        $filtering = true;
      }else{
        $filter = '/';
        $filtering = false;
      }

      $this->data['title']="Master Data Komoditi";
      $this->data['delete_route'] = 'delete_dataKomoditi';
      $this->data['current_page'] = $current_page;
      $this->data['start_number'] = $start_number;
      $this->data['pagination'] = paginations($current_page,$total_results, $limit, config('app.page_ellipsis'),config('app.url')."/master/komoditi",$filtering,$filter );
      $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

      return view('master.dayamas.index_dataKomoditi',$this->data);
  }

  public function inputDataKomoditi(Request $request){
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');
    $client = new Client();

    $inputData = $request->except(['_token', 'id']);

    DB::table('tr_komoditi')->insert($inputData);

    $trail['audit_menu'] = 'Master Data - Komoditi';
    $trail['audit_event'] = 'post';
    $trail['audit_value'] = json_encode($inputData);
    $trail['audit_url'] = $request->url();
    $trail['audit_ip_address'] = $request->ip();
    $trail['audit_user_agent'] = $request->userAgent();
    $trail['audit_message'] = '';
    $trail['created_at'] = date("Y-m-d H:i:s");
    $trail['created_at'] = $request->session()->get('id');

    $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


    $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

    return redirect('/master/komoditi');
  }

  public function updateDataKomoditi(Request $request){
    $id = $request->input('id');
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');
    $client = new Client();

    $inputData = $request->except(['_token', 'id']);

    DB::table('tr_komoditi')
            ->where('id', $id)
            ->update($inputData);

            $trail['audit_menu'] = 'Master Data - Komoditi';
            $trail['audit_event'] = 'put';
            $trail['audit_value'] = json_encode($inputData);
            $trail['audit_url'] = $request->url();
            $trail['audit_ip_address'] = $request->ip();
            $trail['audit_user_agent'] = $request->userAgent();
            $trail['audit_message'] = '';
            $trail['created_at'] = date("Y-m-d H:i:s");
            $trail['created_at'] = $request->session()->get('id');

            $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


    $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

    return redirect('/master/komoditi');
  }

  public function deleteDataKomoditi(Request $request){
      if ($request->ajax()) {
          $id = $request->id;
          $del = DB::table('tr_komoditi')->where('id', $id)->delete();
          $this->form_params['delete_id'] = $id;
          $trail['audit_menu'] = 'Master Data - Komoditi';
          $trail['audit_event'] = 'delete';
          $trail['audit_value'] = json_encode($this->form_params);
          $trail['audit_url'] = $request->url();
          $trail['audit_ip_address'] = $request->ip();
          $trail['audit_user_agent'] = $request->userAgent();
          $trail['audit_message'] = '';
          $trail['created_at'] = date("Y-m-d H:i:s");
          $trail['created_at'] = $request->session()->get('id');

          $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

          if($del){
              $data_request = ['code'=>200,'status'=>'Sukses','message'=>'Data Master Komoditi Berhasil Dihapus'];
              return $data_request;
          }else{
              $data_request = ['code'=>200,'status'=>'error','message'=>'Data Master Komoditi Gagal Dihapus'];
              return $data_request;
          }
      }
  }

  public function dataJeniskasus(Request $request){
      $client = new Client();
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');

      $kondisi = '';
      if($request->limit) {
        $limit = $request->limit;
      } else {
        $limit = config('app.limit');
      }
      if ($request->order==Null) {
          $order = 'asc';
      } else {
          $order = $request->order;
      }

      if ($request->limit==Null) {
          $limit = config('constant.LIMITPAGE');
      } else {
          $limit = $request->limit;
      }
      if ($request->page==Null) {
          $current_page = 1;
          $start_number = 1;
      } else {
          $current_page = $request->page;
          $start_number = ($limit * ($request->page -1 )) + 1;
      }

      if($request->isMethod('get')){
        $get = $request->all();
        $get = $request->except(['page']);
        $tipe = $request->tipe;
        if(count($get)>0){
          $this->selected['tipe']  = $tipe;
          foreach ($get as $key => $value) {
            $kondisi .= "&".$key.'='.$value;
            $this->selected[$key] = $value;
          }
        }
      } else {
        $tipe = $request->tipe;
        $kata_kunci = $request->kata_kunci;

        $kondisi .= '&kata_kunci='.$kata_kunci;
        $this->selected['kata_kunci'] = $kata_kunci;
        $kondisi .= '&tipe='.$tipe;
        $this->selected['tipe'] = $tipe;

        $kondisi .='&limit='.$limit;
        $kondisi .='&order='.$order;
        $this->selected['limit'] = $limit;
        $this->selected['order'] = $order;
      }

      $kondisifilter = array();
      if ($request->kata_kunci != '' ) {
          array_push($kondisifilter, array('nm_jnskasus', 'ilike', '%'.$request->kata_kunci.'%'));
      }
      $this->data['filter'] = $this->selected;
      $this->data['route'] = $request->route()->getName();

      $qresults = DB::table('tr_jnskasus')->where($kondisifilter);

      $total_results = $qresults->count();
      $offset = ($current_page-1) * $limit;
      $totalpage = ceil($total_results / $limit);

      $datas = $qresults->orderBy('nm_jnskasus', $order)->offset($offset)->limit($limit)->get();

      $this->data['datamaster'] = json_decode($datas, true);

      if($kondisi){
        $filter = $kondisi;
        $filtering = true;
      }else{
        $filter = '/';
        $filtering = false;
      }

      $this->data['title']="Master Data Jeniskasus";
      $this->data['delete_route'] = 'delete_dataJeniskasus';
      $this->data['current_page'] = $current_page;
      $this->data['start_number'] = $start_number;
      $this->data['pagination'] = paginations($current_page,$total_results, $limit, config('app.page_ellipsis'),config('app.url')."/master/jeniskasus",$filtering,$filter );
      $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

      return view('master.kasus.index_dataJeniskasus',$this->data);
  }

  public function inputDataJeniskasus(Request $request){
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');
    $client = new Client();

    $inputData = $request->except(['_token', 'id']);

    DB::table('tr_jnskasus')->insert($inputData);

    $trail['audit_menu'] = 'Master Data - Jenis Kasus';
    $trail['audit_event'] = 'post';
    $trail['audit_value'] = json_encode($inputData);
    $trail['audit_url'] = $request->url();
    $trail['audit_ip_address'] = $request->ip();
    $trail['audit_user_agent'] = $request->userAgent();
    $trail['audit_message'] = '';
    $trail['created_at'] = date("Y-m-d H:i:s");
    $trail['created_at'] = $request->session()->get('id');

    $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


    $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

    return redirect('/master/jeniskasus');
  }

  public function updateDataJeniskasus(Request $request){
    $id = $request->input('id');
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');
    $client = new Client();

    $inputData = $request->except(['_token', 'id']);

    DB::table('tr_jnskasus')
            ->where('id', $id)
            ->update($inputData);

            $trail['audit_menu'] = 'Master Data - Jenis Kasus';
            $trail['audit_event'] = 'put';
            $trail['audit_value'] = json_encode($inputData);
            $trail['audit_url'] = $request->url();
            $trail['audit_ip_address'] = $request->ip();
            $trail['audit_user_agent'] = $request->userAgent();
            $trail['audit_message'] = '';
            $trail['created_at'] = date("Y-m-d H:i:s");
            $trail['created_at'] = $request->session()->get('id');

            $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


    $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

    return redirect('/master/jeniskasus');
  }

  public function deleteDataJeniskasus(Request $request){
      if ($request->ajax()) {
          $id = $request->id;
          $del = DB::table('tr_jnskasus')->where('id', $id)->delete();
          $this->form_params['delete_id'] = $id;
          $trail['audit_menu'] = 'Master Data - Jenis Kasus';
          $trail['audit_event'] = 'delete';
          $trail['audit_value'] = json_encode($this->form_params);
          $trail['audit_url'] = $request->url();
          $trail['audit_ip_address'] = $request->ip();
          $trail['audit_user_agent'] = $request->userAgent();
          $trail['audit_message'] = '';
          $trail['created_at'] = date("Y-m-d H:i:s");
          $trail['created_at'] = $request->session()->get('id');

          $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

          if($del){
              $data_request = ['code'=>200,'status'=>'Sukses','message'=>'Data Master Jenis Kasus Berhasil Dihapus'];
              return $data_request;
          }else{
              $data_request = ['code'=>200,'status'=>'error','message'=>'Data Master Jenis Kasus Gagal Dihapus'];
              return $data_request;
          }
      }
  }

  public function dataJenisbarbuk(Request $request){
      $client = new Client();
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');

      $kondisi = '';
      if($request->limit) {
        $limit = $request->limit;
      } else {
        $limit = config('app.limit');
      }
      if ($request->order==Null) {
          $order = 'asc';
      } else {
          $order = $request->order;
      }

      if ($request->limit==Null) {
          $limit = config('constant.LIMITPAGE');
      } else {
          $limit = $request->limit;
      }
      if ($request->page==Null) {
          $current_page = 1;
          $start_number = 1;
      } else {
          $current_page = $request->page;
          $start_number = ($limit * ($request->page -1 )) + 1;
      }

      if($request->isMethod('get')){
        $get = $request->all();
        $get = $request->except(['page']);
        $tipe = $request->tipe;
        if(count($get)>0){
          $this->selected['tipe']  = $tipe;
          foreach ($get as $key => $value) {
            $kondisi .= "&".$key.'='.$value;
            $this->selected[$key] = $value;
          }
        }
      } else {
        $tipe = $request->tipe;
        $kata_kunci = $request->kata_kunci;

        $kondisi .= '&kata_kunci='.$kata_kunci;
        $this->selected['kata_kunci'] = $kata_kunci;
        $kondisi .= '&tipe='.$tipe;
        $this->selected['tipe'] = $tipe;

        $kondisi .='&limit='.$limit;
        $kondisi .='&order='.$order;
        $this->selected['limit'] = $limit;
        $this->selected['order'] = $order;
      }

      $kondisifilter = array();
      if ($request->kata_kunci != '' ) {
        if ($request->tipe == 'kode' ) {
          array_push($kondisifilter, array('kd_jnsbrgbukti', 'ilike', '%'.$request->kata_kunci.'%'));
        }else if ($request->tipe == 'nama' ) {
          array_push($kondisifilter, array('nm_jnsbrgbukti', 'ilike', '%'.$request->kata_kunci.'%'));
        }
      }
      $this->data['filter'] = $this->selected;
      $this->data['route'] = $request->route()->getName();

      $qresults = DB::table('tr_jnsbrgbukti')->where($kondisifilter);

      $total_results = $qresults->count();
      $offset = ($current_page-1) * $limit;
      $totalpage = ceil($total_results / $limit);

      $datas = $qresults->orderBy('nm_jnsbrgbukti', $order)->offset($offset)->limit($limit)->get();

      $this->data['datamaster'] = json_decode($datas, true);

      if($kondisi){
        $filter = $kondisi;
        $filtering = true;
      }else{
        $filter = '/';
        $filtering = false;
      }

      $this->data['title']="Master Data Jenisbarbuk";
      $this->data['delete_route'] = 'delete_dataJenisbarbuk';
      $this->data['current_page'] = $current_page;
      $this->data['start_number'] = $start_number;
      $this->data['pagination'] = paginations($current_page,$total_results, $limit, config('app.page_ellipsis'),config('app.url')."/master/jenisbarbuk",$filtering,$filter );
      $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

      return view('master.kasus.index_dataJenisbarbuk',$this->data);
  }

  public function inputDataJenisbarbuk(Request $request){
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');
    $client = new Client();

    $inputData = $request->except(['_token', 'id']);

    DB::table('tr_jnsbrgbukti')->insert($inputData);

    $trail['audit_menu'] = 'Master Data - Jenis Barang Bukti';
    $trail['audit_event'] = 'post';
    $trail['audit_value'] = json_encode($inputData);
    $trail['audit_url'] = $request->url();
    $trail['audit_ip_address'] = $request->ip();
    $trail['audit_user_agent'] = $request->userAgent();
    $trail['audit_message'] = '';
    $trail['created_at'] = date("Y-m-d H:i:s");
    $trail['created_at'] = $request->session()->get('id');

    $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


    $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

    return redirect('/master/jenisbarbuk');
  }

  public function updateDataJenisbarbuk(Request $request){
    $id = $request->input('id');
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');
    $client = new Client();

    $inputData = $request->except(['_token', 'id']);

    DB::table('tr_jnsbrgbukti')
            ->where('kd_jnsbrgbukti', $id)
            ->update($inputData);

            $trail['audit_menu'] = 'Master Data - Jenis Barang Bukti';
            $trail['audit_event'] = 'put';
            $trail['audit_value'] = json_encode($inputData);
            $trail['audit_url'] = $request->url();
            $trail['audit_ip_address'] = $request->ip();
            $trail['audit_user_agent'] = $request->userAgent();
            $trail['audit_message'] = '';
            $trail['created_at'] = date("Y-m-d H:i:s");
            $trail['created_at'] = $request->session()->get('id');

            $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


    $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

    return redirect('/master/jenisbarbuk');
  }

  public function deleteDataJenisbarbuk(Request $request){
      if ($request->ajax()) {
          $id = $request->id;
          $del = DB::table('tr_jnsbrgbukti')->where('kd_jnsbrgbukti', $id)->delete();
          $this->form_params['delete_id'] = $id;
          $trail['audit_menu'] = 'Master Data - Jenis Barang Bukti';
          $trail['audit_event'] = 'delete';
          $trail['audit_value'] = json_encode($this->form_params);
          $trail['audit_url'] = $request->url();
          $trail['audit_ip_address'] = $request->ip();
          $trail['audit_user_agent'] = $request->userAgent();
          $trail['audit_message'] = '';
          $trail['created_at'] = date("Y-m-d H:i:s");
          $trail['created_at'] = $request->session()->get('id');

          $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

          if($del){
              $data_request = ['code'=>200,'status'=>'Sukses','message'=>'Data Master Jenis Barang Bukti Berhasil Dihapus'];
              return $data_request;
          }else{
              $data_request = ['code'=>200,'status'=>'error','message'=>'Data Master Jenis Barang Bukti Gagal Dihapus'];
              return $data_request;
          }
      }
  }

  public function dataBarangbukti(Request $request){
      $client = new Client();
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');

      $kondisi = '';
      if($request->limit) {
        $limit = $request->limit;
      } else {
        $limit = config('app.limit');
      }
      if ($request->order==Null) {
          $order = 'asc';
      } else {
          $order = $request->order;
      }

      if ($request->limit==Null) {
          $limit = config('constant.LIMITPAGE');
      } else {
          $limit = $request->limit;
      }
      if ($request->page==Null) {
          $current_page = 1;
          $start_number = 1;
      } else {
          $current_page = $request->page;
          $start_number = ($limit * ($request->page -1 )) + 1;
      }

      if($request->isMethod('get')){
        $get = $request->all();
        $get = $request->except(['page']);
        $tipe = $request->tipe;
        if(count($get)>0){
          $this->selected['tipe']  = $tipe;
          foreach ($get as $key => $value) {
            $kondisi .= "&".$key.'='.$value;
            $this->selected[$key] = $value;
          }
        }
      } else {
        $tipe = $request->tipe;
        $kata_kunci = $request->kata_kunci;

        $kondisi .= '&kata_kunci='.$kata_kunci;
        $this->selected['kata_kunci'] = $kata_kunci;
        $kondisi .= '&tipe='.$tipe;
        $this->selected['tipe'] = $tipe;

        $kondisi .='&limit='.$limit;
        $kondisi .='&order='.$order;
        $this->selected['limit'] = $limit;
        $this->selected['order'] = $order;
      }

      $kondisifilter = array();
      if ($request->kata_kunci != '' ) {
        if ($request->tipe == 'nama' ) {
          array_push($kondisifilter, array('nm_brgbukti', 'ilike', '%'.$request->kata_kunci.'%'));
        }else if ($request->tipe == 'satuan' ) {
          array_push($kondisifilter, array('t3.nm_satuan', 'ilike', '%'.$request->kata_kunci.'%'));
        }else if ($request->tipe == 'jenis' ) {
          array_push($kondisifilter, array('t2.nm_jnsbrgbukti', 'ilike', '%'.$request->kata_kunci.'%'));
        }else if ($request->tipe == 'kasus' ) {
          array_push($kondisifilter, array('t1.nm_jnskasus', 'ilike', '%'.$request->kata_kunci.'%'));
        }
      }
      $this->data['filter'] = $this->selected;
      $this->data['route'] = $request->route()->getName();

      $jnskasus = DB::table('tr_jnskasus')->orderBy('nm_jnskasus', 'asc')->get();
      $this->data['jnskasus'] = json_decode($jnskasus, true);

      $jnsbarbuk = DB::table('tr_jnsbrgbukti')->orderBy('nm_jnsbrgbukti', 'asc')->get();
      $this->data['jnsbarbuk'] = json_decode($jnsbarbuk, true);

      $satuan = DB::table('tr_satuan')->orderBy('nm_satuan', 'asc')->get();
      $this->data['satuan'] = json_decode($satuan, true);

      $qresults = DB::table('tr_brgbukti')
                      ->leftJoin('tr_jnskasus as t1', 'tr_brgbukti.kd_jnskasus', '=', 't1.id')
                      ->leftJoin('tr_jnsbrgbukti as t2', 'tr_brgbukti.kd_jnsbrgbukti', '=', 't2.kd_jnsbrgbukti')
                      ->leftJoin('tr_satuan as t3', 'tr_brgbukti.kd_satuan', '=', 't3.kd_satuan')
                      ->select('tr_brgbukti.*', 't1.nm_jnskasus', 't2.nm_jnsbrgbukti', 't3.nm_satuan')
                      ->where($kondisifilter);

      $total_results = $qresults->count();
      $offset = ($current_page-1) * $limit;
      $totalpage = ceil($total_results / $limit);

      $datas = $qresults->orderBy('nm_brgbukti', $order)->offset($offset)->limit($limit)->get();

      $this->data['datamaster'] = json_decode($datas, true);

      if($kondisi){
        $filter = $kondisi;
        $filtering = true;
      }else{
        $filter = '/';
        $filtering = false;
      }

      $this->data['title']="Master Data Barangbukti";
      $this->data['delete_route'] = 'delete_dataBarangbukti';
      $this->data['current_page'] = $current_page;
      $this->data['start_number'] = $start_number;
      $this->data['pagination'] = paginations($current_page,$total_results, $limit, config('app.page_ellipsis'),config('app.url')."/master/barangbukti",$filtering,$filter );
      $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

      return view('master.kasus.index_dataBarangbukti',$this->data);
  }

  public function inputDataBarangbukti(Request $request){
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');
    $client = new Client();

    $inputData = $request->except(['_token', 'id']);
    $inputData['kd_brgbukti'] = '';

    DB::table('tr_brgbukti')->insert($inputData);

    $trail['audit_menu'] = 'Master Data - Barang Bukti';
    $trail['audit_event'] = 'post';
    $trail['audit_value'] = json_encode($inputData);
    $trail['audit_url'] = $request->url();
    $trail['audit_ip_address'] = $request->ip();
    $trail['audit_user_agent'] = $request->userAgent();
    $trail['audit_message'] = '';
    $trail['created_at'] = date("Y-m-d H:i:s");
    $trail['created_at'] = $request->session()->get('id');

    $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


    $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

    return redirect('/master/barangbukti');
  }

  public function updateDataBarangbukti(Request $request){
    $id = $request->input('id');
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');
    $client = new Client();

    $inputData = $request->except(['_token', 'id']);
    $inputData['kd_brgbukti'] = '';

    DB::table('tr_brgbukti')
            ->where('id_brgbukti', $id)
            ->update($inputData);

            $trail['audit_menu'] = 'Master Data - Barang Bukti';
            $trail['audit_event'] = 'put';
            $trail['audit_value'] = json_encode($inputData);
            $trail['audit_url'] = $request->url();
            $trail['audit_ip_address'] = $request->ip();
            $trail['audit_user_agent'] = $request->userAgent();
            $trail['audit_message'] = '';
            $trail['created_at'] = date("Y-m-d H:i:s");
            $trail['created_at'] = $request->session()->get('id');

            $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


    $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

    return redirect('/master/barangbukti');
  }

  public function deleteDataBarangbukti(Request $request){
      if ($request->ajax()) {
          $id = $request->id;
          $del = DB::table('tr_brgbukti')->where('id_brgbukti', $id)->delete();
          $this->form_params['delete_id'] = $id;
          $trail['audit_menu'] = 'Master Data - Barang Bukti';
          $trail['audit_event'] = 'delete';
          $trail['audit_value'] = json_encode($this->form_params);
          $trail['audit_url'] = $request->url();
          $trail['audit_ip_address'] = $request->ip();
          $trail['audit_user_agent'] = $request->userAgent();
          $trail['audit_message'] = '';
          $trail['created_at'] = date("Y-m-d H:i:s");
          $trail['created_at'] = $request->session()->get('id');

          $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

          if($del){
              $data_request = ['code'=>200,'status'=>'Sukses','message'=>'Data Master Barang Bukti Berhasil Dihapus'];
              return $data_request;
          }else{
              $data_request = ['code'=>200,'status'=>'error','message'=>'Data Master Barang Bukti Gagal Dihapus'];
              return $data_request;
          }
      }
  }

  public function dataSatuan(Request $request){
      $client = new Client();
      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');

      $kondisi = '';
      if($request->limit) {
        $limit = $request->limit;
      } else {
        $limit = config('app.limit');
      }
      if ($request->order==Null) {
          $order = 'asc';
      } else {
          $order = $request->order;
      }

      if ($request->limit==Null) {
          $limit = config('constant.LIMITPAGE');
      } else {
          $limit = $request->limit;
      }
      if ($request->page==Null) {
          $current_page = 1;
          $start_number = 1;
      } else {
          $current_page = $request->page;
          $start_number = ($limit * ($request->page -1 )) + 1;
      }

      if($request->isMethod('get')){
        $get = $request->all();
        $get = $request->except(['page']);
        $tipe = $request->tipe;
        if(count($get)>0){
          $this->selected['tipe']  = $tipe;
          foreach ($get as $key => $value) {
            $kondisi .= "&".$key.'='.$value;
            $this->selected[$key] = $value;
          }
        }
      } else {
        $tipe = $request->tipe;
        $kata_kunci = $request->kata_kunci;

        $kondisi .= '&kata_kunci='.$kata_kunci;
        $this->selected['kata_kunci'] = $kata_kunci;
        $kondisi .= '&tipe='.$tipe;
        $this->selected['tipe'] = $tipe;

        $kondisi .='&limit='.$limit;
        $kondisi .='&order='.$order;
        $this->selected['limit'] = $limit;
        $this->selected['order'] = $order;
      }

      $kondisifilter = array();
      if ($request->kata_kunci != '' ) {
        if ($request->tipe == 'nama' ) {
          array_push($kondisifilter, array('nm_satuan', 'ilike', '%'.$request->kata_kunci.'%'));
        }else if ($request->tipe == 'kode' ) {
          array_push($kondisifilter, array('kd_satuan', 'ilike', '%'.$request->kata_kunci.'%'));
        }
      }
      $this->data['filter'] = $this->selected;
      $this->data['route'] = $request->route()->getName();

      $qresults = DB::table('tr_satuan')->where($kondisifilter);

      $total_results = $qresults->count();
      $offset = ($current_page-1) * $limit;
      $totalpage = ceil($total_results / $limit);

      $datas = $qresults->orderBy('nm_satuan', $order)->offset($offset)->limit($limit)->get();

      $this->data['datamaster'] = json_decode($datas, true);

      if($kondisi){
        $filter = $kondisi;
        $filtering = true;
      }else{
        $filter = '/';
        $filtering = false;
      }

      $this->data['title']="Master Data Satuan";
      $this->data['delete_route'] = 'delete_dataSatuan';
      $this->data['current_page'] = $current_page;
      $this->data['start_number'] = $start_number;
      $this->data['pagination'] = paginations($current_page,$total_results, $limit, config('app.page_ellipsis'),config('app.url')."/master/satuan",$filtering,$filter );
      $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

      return view('master.kasus.index_dataSatuan',$this->data);
  }

  public function inputDataSatuan(Request $request){
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');
    $client = new Client();

    $inputData = $request->except(['_token', 'id']);

    DB::table('tr_satuan')->insert($inputData);

    $trail['audit_menu'] = 'Master Data - Satuan';
    $trail['audit_event'] = 'post';
    $trail['audit_value'] = json_encode($inputData);
    $trail['audit_url'] = $request->url();
    $trail['audit_ip_address'] = $request->ip();
    $trail['audit_user_agent'] = $request->userAgent();
    $trail['audit_message'] = '';
    $trail['created_at'] = date("Y-m-d H:i:s");
    $trail['created_at'] = $request->session()->get('id');

    $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


    $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

    return redirect('/master/satuan');
  }

  public function updateDataSatuan(Request $request){
    $id = $request->input('id');
    $baseUrl = URL::to('/');
    $token = $request->session()->get('token');
    $client = new Client();

    $inputData = $request->except(['_token', 'id']);

    DB::table('tr_satuan')
            ->where('kd_satuan', $id)
            ->update($inputData);

            $trail['audit_menu'] = 'Master Data - Satuan';
            $trail['audit_event'] = 'put';
            $trail['audit_value'] = json_encode($inputData);
            $trail['audit_url'] = $request->url();
            $trail['audit_ip_address'] = $request->ip();
            $trail['audit_user_agent'] = $request->userAgent();
            $trail['audit_message'] = '';
            $trail['created_at'] = date("Y-m-d H:i:s");
            $trail['created_at'] = $request->session()->get('id');

            $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


    $this->data['breadcrumps'] = breadcrumps_master($request->route()->getName());

    return redirect('/master/satuan');
  }

  public function deleteDataSatuan(Request $request){
      if ($request->ajax()) {
          $id = $request->id;
          $del = DB::table('tr_satuan')->where('kd_satuan', $id)->delete();
          $this->form_params['delete_id'] = $id;
          $trail['audit_menu'] = 'Master Data - Satuan';
          $trail['audit_event'] = 'delete';
          $trail['audit_value'] = json_encode($this->form_params);
          $trail['audit_url'] = $request->url();
          $trail['audit_ip_address'] = $request->ip();
          $trail['audit_user_agent'] = $request->userAgent();
          $trail['audit_message'] = '';
          $trail['created_at'] = date("Y-m-d H:i:s");
          $trail['created_at'] = $request->session()->get('id');

          $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

          if($del){
              $data_request = ['code'=>200,'status'=>'Sukses','message'=>'Data Master Satuan Berhasil Dihapus'];
              return $data_request;
          }else{
              $data_request = ['code'=>200,'status'=>'error','message'=>'Data Master Satuan Gagal Dihapus'];
              return $data_request;
          }
      }
  }

}

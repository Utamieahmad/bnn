<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\MainModel;
use App\Tr_BrgBukti;
use App\Models\BrgBukti;
use App\Models\Berantas\Pemusnahan;
use App\Models\Berantas\PemusnahanDetail;
use App\Models\Berantas\Tahanan;
use App\Models\Berantas\TahananHeader;
use URL;
use DateTime;
use Carbon\Carbon;
use App\Models\Berantas\ViewPemusnahan;
use Illuminate\Support\Facades\Storage;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class wastahtiController extends Controller
{
    public $data;
    public $selected;
    public $form_params;
    public function pendataanBrgbukti(Request $request){
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
              }else if(($key == 'nomor_lkn') || ($key == 'nama_penyidik') || ($key == 'nomor_tap') || ($key == 'status') ){
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
        $pelaksana = $request->pelaksana;
        $status = $request->status;
        $BrgBukti = $request->BrgBukti;
        $tgl_from = $request->tgl_from;
        $tgl_to = $request->tgl_to;
        $order = $request->order;
        $limit = $request->limit;
        // dd($pelaksana);

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
        }elseif($tipe == 'pelaksana'){
          $kondisi .= '&pelaksana='.$pelaksana;
          $this->selected['pelaksana'] = $pelaksana;
        }elseif($tipe == 'status'){
          $kondisi .= '&status='.$status_kelengkapan;
          $this->selected['status'] = $status_kelengkapan;
        }elseif($tipe == 'BrgBukti'){
          $kondisi .= '&BrgBukti='.$BrgBukti;
          $this->selected['nm_brgbukti'] = $BrgBukti;
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
      // $this->data['title'] = "Pendataan LKN";
      if($request->page){
          $current_page = $request->page;
          $start_number = ($this->limit * ($request->page -1 )) + 1;
      }else{
          $current_page = 1;
          $start_number = $current_page;
      }

      $limit = 'limit='.$this->limit;
      $offset = 'page='.$current_page;
      $kondisi .='&id_wilayah='.$request->session()->get('wilayah');
      $datas = execute_api('api/pemusnahan?'.$limit.'&'.$offset.$kondisi,'get');
      // print_r($datas);
      // echo '</pre>';
      $this->data['kondisi'] ='?'.$offset.$kondisi;
      if( ($datas['code']= 200) && ($datas['code'] != 'error')){
        $this->data['pemusnahan'] = $datas['data'];
        $total_item = $datas['paginate']['totalpage'] * $this->limit;
      }else{
        $this->data['pemusnahan'] = [];
        $total_item =  0;
      }

      $instansi = $this->globalinstansi($request->session()->get('wilayah'), $token);
      $this->data['instansi'] = $instansi;

      $brgbukti = execute_api('api/jnsbrgbukti','POST');
      // print_r($brgbukti);
      // exit();
      if($brgbukti['code'] == 200 && $brgbukti['status'] != 'error'){
        $this->data['brgbukti'] = $brgbukti['data'];
      }else{
        $this->data['brgbukti'] = [];
      }

      // $instansi  =  execute_api('api/instansi',"GET");


      // if($instansi['code'] == 200 && ($instansi['status'] != 'error')){
      //   $this->data['instansi'] = $instansi['data'];
      // }else{
      //   $this->data['instansi'] = [];
      // }

      // $query  =  execute_api('api/lookup/irtama_satker',"GET");


      // if($query['code'] == 200 && ($query['status'] != 'error')){
      //   $this->data['satker'] = $query['data'];
      // }else{
      //   $this->data['satker'] = [];
      // }
      $this->data['current_page'] = $current_page;
      $this->data['start_number'] = $start_number;
      $this->data['title'] = "Pemusnahan Barang Bukti Dir Wastahti";
      $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
      $this->data['token'] = $token;
      $this->data['path'] = $request->path();
      $this->data['delete_route'] = 'delete_pendataan_brgbukti';
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
      $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'),config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
      $this->data['breadcrumps'] = breadcrumps_dir_wastahti($request->route()->getName());

        //old

        // $client = new Client();
        // if ($request->input('page')) {
        //   $page = $request->input('page');
        //   $start_number = ($limit * ($request->page -1 )) + 1;
        // } else {
        //   $page = 1;
        //   $start_number =$page;
        // }

        // $baseUrl = URL::to('/');
        // $token = $request->session()->get('token');

        // $pemusnahan  = [];
        // try {
        //   $requestPemusnahan = $client->request('GET', $baseUrl.'/api/pemusnahan?page='.$page.'&id_wilayah='.$request->session()->get('wilayah'),
        //   // $requestPemusnahan = $client->request('GET', $baseUrl.'/api/pemusnahan?page='.$page,
        //     [
        //         'headers' =>
        //         [
        //             'Authorization' => 'Bearer '.$token
        //         ]
        //     ]
        //   );
        //   $pemusnahan = json_decode($requestPemusnahan->getBody()->getContents(), true);
        // }catch (\GuzzleHttp\Exception\GuzzleException $e) {
        //     $response = $e->getResponse();
        //     if($response){
        //       $responseBodyAsString = $response->getBody()->getContents();
        //     }else{
        //       $responseBodyAsString = (Object)['code'=>'200','status'=>'error','messages'=>'Network connection failed'];
        //     }
        // }



        // if($pemusnahan['code'] == 200 && $pemusnahan['status'] != 'error' ){
        //   $this->data['pemusnahan'] = $pemusnahan['data'];
        // }else{
        //     $this->data['pemusnahan'] = [];
        // }
        // $page = $pemusnahan['paginate'];
        // $this->data['page'] = $page;

        // $this->data['title'] = "pemusnahan";
        // $this->data['token'] = $token;

        // $instansi = $this->globalinstansi($request->session()->get('wilayah'), $token);

        // $user_id = Auth::user()->user_id;
        // $detail = MainModel::getUserDetail($user_id);
        // $this->data['data_detail'] = $detail;
        // $this->data['path'] = $request->path();
        // $this->data['current_page'] = $start_number;
        // $this->data['instansi'] = $instansi;
        // $this->data['breadcrumps'] = breadcrumps_dir_wastahti($request->route()->getName());
        return view('pemberantasan.wastahti.index_pendataanBrgbukti',$this->data);
    }

     public function editPendataanBrgbukti(Request $request){
        $id = $request->id;
        $token = $request->session()->get('token');
        // $id = '68950';
        $pemusnahan = $this->globalGetPemusnahan($token, $id);
        $pemusnahanDetail = $this->globalGetPemusnahanDetail($token, $pemusnahan['data']['id']);

        $this->data['pemusnahan'] = $pemusnahan['data'];
        $this->data['pemusnahanBrgBuktiDetail'] = $pemusnahanDetail;
        $this->data['no_lkn'] = $pemusnahan['data']['nomor_lkn'];
        $this->data['breadcrumps'] = breadcrumps_dir_wastahti($request->route()->getName());
        return view('pemberantasan.wastahti.edit_pendataanBrgbukti',$this->data);
    }

    public function addFormPendataanBrgbukti(Request $request){
        $id = $request->id;
        $token = $request->session()->get('token');
        // $id = '68950';
        $pemusnahan = $this->globalGetPemusnahan($token, $id);
        $pemusnahanDetail = $this->globalGetPemusnahanDetail($token, $pemusnahan['data']['id']);

        $this->data['pemusnahan'] = $pemusnahan['data'];
        $this->data['pemusnahanBrgBuktiDetail'] = $pemusnahanDetail;
        $this->data['no_lkn'] = $pemusnahan['data']['nomor_lkn'];
        $this->data['breadcrumps'] = breadcrumps_dir_wastahti($request->route()->getName());
        return view('pemberantasan.wastahti.add_data_pendataanBrgbukti',$this->data);
    }

    public function addPendataanBrgbukti(Request $request){

      $client = new Client();

       $baseUrl = URL::to($this->urlapi());
//       $baseUrl = URL::to('/');
       $token = $request->session()->get('token');

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;

        $requestlkn = $client->request('GET', $baseUrl.'/api/getlistlkn?id_wilayah='.$request->session()->get('wilayah'),
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
        $lkn = json_decode($requestlkn->getBody()->getContents(), true);

        $data['lkn'] = $lkn['data'];
        $data['breadcrumps'] = breadcrumps_dir_wastahti($request->route()->getName());
        return view('pemberantasan.wastahti.add_pendataanBrgbukti',$data);
    }

    public function addDataPendataanBrgbukti(Request $request)
    {
      $client = new Client();
      $baseUrl = URL::to($this->urlapi());
//      $baseUrl = URL::to('/');

      $token = $request->session()->get('token');
      $lkn = $this->globalGetByLkn($token, $request->input('kasus_no'));
      // dd($lkn);
      if (count($lkn['data']) == 0) {
        $this->messages['status'] = 'error';
        $this->messages['message'] = 'No LKN tidak ditemukan.';
        return redirect('pemberantasan/dir_wastahti/add_pendataan_brgbukti')->with('message',$this->messages);
      }

      $dataPemusnahanByLkn = Pemusnahan::where('nomor_lkn', $request->input('kasus_no'))->get();

      if (count($dataPemusnahanByLkn) >= 1) {
        $dataPemusnahanByLkn = Pemusnahan::where('nomor_lkn', $request->input('kasus_no'))->first();

        $pemusnahanDetail = $this->globalGetPemusnahanDetail($token, $dataPemusnahanByLkn->id);

        return redirect('pemberantasan/dir_wastahti/add_form_pendataan_brgbukti/'.$dataPemusnahanByLkn->id);
      }

      $id = $lkn['data'][0]['kasus_id'];
      $noLkn = $lkn['data'][0]['kasus_no'];
      $brgBuktiNarkotika = $this->globalBuktiNarkotika($token, $id);
      // dd($brgBuktiNarkotika);
      $requestPemusnahanBrgBukti = $client->request('POST', $baseUrl.'/api/pemusnahan',
          [
              'headers' =>
              [
                  'Authorization' => 'Bearer '.$token
              ],
              'form_params' =>
              [
                  'id_kasus' => $id,
                  'nomor_lkn' => $noLkn
              ]
          ]
      );

      $pemusnahanBrgBukti = json_decode($requestPemusnahanBrgBukti->getBody()->getContents(), true);

      $parentId = $pemusnahanBrgBukti['data']['eventID'];

      $this->form_params = array('id_kasus' => $id,
      'nomor_lkn' => $noLkn);

      $trail['audit_menu'] = 'Pemberantasan - Direktorat Wastahti - Pemusnahan Barang Bukti';
      $trail['audit_event'] = 'post';
      $trail['audit_value'] = json_encode($this->form_params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $pemusnahanBrgBukti['comment'];
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($token,$trail);


      foreach ($brgBuktiNarkotika['data'] as $bb) {
        $pemusnahanBbDetail = $this->globalInputPemusnahanDetail($token, $parentId, $bb['id_brgbukti'], $bb['nm_satuan'], $bb['jumlah_barang_bukti']);
      }

      $pemusnahanBrgBuktiDetail = $this->globalGetPemusnahanDetail($token, $parentId);

      $this->data['pemusnahanBrgBuktiDetail'] = $pemusnahanBrgBuktiDetail;
      $this->data['no_lkn'] = $noLkn;
      // $this->data['id_kasus'] = $id;

      return redirect('pemberantasan/dir_wastahti/add_form_pendataan_brgbukti/'.$parentId);
    }

    public function inputPendataanBrgbukti(Request $request)
    {

      // $client = new Client();
      // $baseUrl = URL::to('/');
      $token = $request->session()->get('token');

      // $requestPemusnahanBrgBukti = $client->request('PUT', $baseUrl.'/api/pemusnahan/'.$request->input('id'),
      //     [
      //         'headers' =>
      //         [
      //             'Authorization' => 'Bearer '.$token
      //         ],
      //         'form_params' =>
      //         [
      //             '' => $request->input('id'),
      //             'nomor_lkn' => $request->input('no_lkn'),
      //             'nama_penyidik' => $request->input('nama_penyidik'),
      //             'tgl_penitipan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_penitipan')))),
      //             'tgl_penitipan_ambil' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_penitipan_ambil')))),
      //             // 'tgl_pemusnahan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pemusnahan')))),
      //             'nomor_tap' => $request->input('nomor_tap'),
      //             'tgl_tap' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_tap')))),
      //             'nama_kejari' => $request->input('nama_kejari')
      //         ]
      //     ]
      // );

      // $pemusnahanBrgBukti = json_decode($requestPemusnahanBrgBukti->getBody()->getContents(), true);

      // return redirect('/pemberantasan/dir_wastahti/pendataan_brgbukti');

      $id = $request->id;
      $params = $request->only(['nama_penyidik','tgl_penitipan','tgl_penitipan_ambil','tgl_pemusnahan','nomor_tap','tgl_tap','nama_kejari']);
      if($request->tgl_penitipan){
        $date = str_replace('/', '-', $request->tgl_penitipan);
        $params['tgl_penitipan'] = date('Y-m-d',strtotime($date));
      }else{
        $params['tgl_penitipan'] = null;
      }
      if($request->tgl_penitipan_ambil){
        $date = str_replace('/', '-', $request->tgl_penitipan_ambil);
        $params['tgl_penitipan_ambil'] = date('Y-m-d',strtotime($date));
      }else{
        $params['tgl_penitipan_ambil'] = null;
      }
      if($request->tgl_pemusnahan){
        $date = str_replace('/', '-', $request->tgl_pemusnahan);
        $params['tgl_pemusnahan'] = date('Y-m-d',strtotime($date));
      }else{
        $params['tgl_pemusnahan'] = null;
      }
      if($request->tgl_tap){
        $date = str_replace('/', '-', $request->tgl_tap);
        $params['tgl_tap'] = date('Y-m-d',strtotime($date));
      }else{
        $params['tgl_tap'] = null;
      }
      // $params['id_kasus'] = $id;
      $params['nomor_lkn'] = $request->no_lkn;
      $query = execute_api_json('/api/pemusnahan/'.$id,'PUT',$params);

			if ($request->file('file_upload')){
					$fileName = date('Y-m-d').'_'.$id.'-'.$request->file('file_upload')->getClientOriginalName();
					try {
						$request->file('file_upload')->storeAs('PemusnahanBB', $fileName);
						$params2['file_upload'] = $fileName;
						$update2 = execute_api_json('/api/pemusnahan/'.$id,'PUT',$params2);

					}catch(\Exception $e){
						$e->getMessage();
					}
			}

      $trail['audit_menu'] = 'Pemberantasan - Direktorat Wastahti - Pemusnahan Barang Bukti';
      $trail['audit_event'] = 'put';
      $trail['audit_value'] = json_encode($params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $query->comment;
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($token,$trail);

      // $this->kelengkapan_PendataanBrgbukti($id);
      if(($query->code == 200) && ($query->status != 'error')){
            $this->kelengkapan_PendataanBrgbukti($id);
            $this->data['status'] = 'success';
            $this->data['message'] = 'Data Pemusnahan Barang Bukti Berhasil Ditambahkan.';
          }else{
            $this->data['status'] = 'error';
            $this->data['message'] = 'Data Pemusnahan Barang Bukti Gagal Ditambahkan.';
          }
         return redirect(route('pendataan_brgbukti'))->with('status',$this->data);
      // return redirect('/pemberantasan/dir_wastahti/pendataan_brgbukti');
    }

    public function inputDetailPendataanBrgbukti(Request $request)
    {
      $client = new Client();
      $baseUrl = URL::to($this->urlapi());
//      $baseUrl = URL::to('/');
      $token = $request->session()->get('token');

      //generate image base64
      if($request->hasFile('foto1')){
          $filenameWithExt = $request->file('foto1')->getClientOriginalName();
          $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
          $extension = $request->file('foto1')->getClientOriginalExtension();
          $fileNameToStore= $filename.'_'.time().'.'.$extension;
          $path = $request->file('foto1')->storeAs('Diseminfo/Online', $fileNameToStore);
          $image = public_path('upload/Diseminfo/Online/'.$fileNameToStore);
          $data = file_get_contents($image);
          $image1 = base64_encode($data);
          Storage::delete('Diseminfo/Online/'.$fileNameToStore);
      }else{
        $image1 = null;
      }

      if($request->hasFile('foto2')){
          $filenameWithExt = $request->file('foto2')->getClientOriginalName();
          $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
          $extension = $request->file('foto2')->getClientOriginalExtension();
          $fileNameToStore= $filename.'_'.time().'.'.$extension;
          $path = $request->file('foto2')->storeAs('Diseminfo/Online', $fileNameToStore);
          $image = public_path('upload/Diseminfo/Online/'.$fileNameToStore);
          $data = file_get_contents($image);
          $image2 = base64_encode($data);
          Storage::delete('Diseminfo/Online/'.$fileNameToStore);
      }else{
        $image2 = null;
      }

      if($request->hasFile('foto3')){
          $filenameWithExt = $request->file('foto3')->getClientOriginalName();
          $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
          $extension = $request->file('foto3')->getClientOriginalExtension();
          $fileNameToStore= $filename.'_'.time().'.'.$extension;
          $path = $request->file('foto3')->storeAs('Diseminfo/Online', $fileNameToStore);
          $image = public_path('upload/Diseminfo/Online/'.$fileNameToStore);
          $data = file_get_contents($image);
          $image3 = base64_encode($data);
          Storage::delete('Diseminfo/Online/'.$fileNameToStore);
      }else{
        $image3 = null;
      }

      $requestPemusnahanBrgBuktiDetail = $client->request('PUT', $baseUrl.'/api/pemusnahandetail/'.$request->input('id'),
          [
              'headers' =>
              [
                'Authorization' => 'Bearer '.$token
              ],
              'form_params' =>
              [
                "parent_id" => $request->input('parent_id'),
                "id_brgbukti" => $request->input('id_brgbukti'),
                "jumlah_barang_bukti" => $request->input('jumlah_barang_bukti'),
                "kode_satuan_barang_bukti" => $request->input('nm_satuan'),
                "keperluan_lab" => $request->input('keperluan_lab'),
                "keperluan_diklat" => $request->input('keperluan_diklat'),
                "keperluan_iptek" => $request->input('keperluan_iptek'),
                "jumlah_dimusnahkan" => $request->input('jumlah_dimusnahkan'),
                'tgl_pemusnahan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pemusnahan')))),
                "lokasi" => $request->input('lokasi'),
                "foto1" => $image1,
                "foto2" => $image2,
                "foto3" => $image3,
              ]
          ]
      );

      $pemusnahanBrgBuktiDetail = json_decode($requestPemusnahanBrgBuktiDetail->getBody()->getContents(), true);

      return redirect('pemberantasan/dir_wastahti/edit_pendataan_brgbukti/'.$request->input('parent_id'));
    }

    public function updatePendataanBrgbukti(Request $request){
      // $baseUrl = URL::to('/');
      $token = $request->session()->get('token');
      // $client = new Client();
      // $inputId = $request->input('id');
      // if ($request->input('tgl_penitipan') != '') {
      //   $penitipan = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_penitipan'))));
      // } else {
      //   $penitipan = '';
      // }
      // if ($request->input('tgl_penitipan_ambil') != '') {
      //   $penitipan_ambil = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_penitipan_ambil'))));
      // } else {
      //   $penitipan_ambil = '';
      // }
      // if ($request->input('tgl_pemusnahan') != '') {
      //   $pemusnahan = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pemusnahan'))));
      // } else {
      //   $pemusnahan = '';
      // }
      // if ($request->input('tgl_tap') != '') {
      //   $tap = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_tap'))));
      // } else {
      //   $tap = '';
      // }

      // $params = $client->request('PUT', $baseUrl.'/api/pemusnahan/'.$request->input('id'),
      //   [
      //     'headers' =>
      //     [
      //       'Authorization' => 'Bearer '.$token
      //     ],
      //     'form_params' =>
      //     [
      //       "nama_penyidik" => $request->input('nama_penyidik'),
      //       "tgl_penitipan" => $penitipan,
      //       "tgl_penitipan_ambil" => $penitipan_ambil,
      //       "tgl_pemusnahan" => $pemusnahan,
      //       "nomor_tap" => $request->input('nomor_tap'),
      //       "tgl_tap" => $tap,
      //       "nama_kejari" => $request->input('nama_kejari')
      //     ]
      //   ]
      // );

      // $brgbukti_data = json_decode($params->getBody()->getContents(), true);

      //   if($brgbukti_data['code'] == 200 && $brgbukti_data['status'] != 'error'){
      //     $this->kelengkapan_PendataanBrgbukti($id);
      //     $this->data['status'] = 'success';
      //     $this->data['message'] = 'Data Pemusnahan Barang Bukti Berhasil Diperbarui';
      //   }else{
      //     $this->data['status'] = 'error';
      //     $this->data['message'] = 'Data Pemusnahan Barang Bukti Gagal Diperbarui';
      //   }
      //   return back()->with('status',$this->data);

      $id = $request->id;
      $params = $request->only(['nama_penyidik','tgl_penitipan','tgl_penitipan_ambil','tgl_pemusnahan','nomor_tap','tgl_tap','nama_kejari']);
      if($request->tgl_penitipan){
        $date = str_replace('/', '-', $request->tgl_penitipan);
        $params['tgl_penitipan'] = date('Y-m-d',strtotime($date));
      }else{
        $params['tgl_penitipan'] = null;
      }
      if($request->tgl_penitipan_ambil){
        $date = str_replace('/', '-', $request->tgl_penitipan_ambil);
        $params['tgl_penitipan_ambil'] = date('Y-m-d',strtotime($date));
      }else{
        $params['tgl_penitipan_ambil'] = null;
      }
      if($request->tgl_pemusnahan){
        $date = str_replace('/', '-', $request->tgl_pemusnahan);
        $params['tgl_pemusnahan'] = date('Y-m-d',strtotime($date));
      }else{
        $params['tgl_pemusnahan'] = null;
      }
      if($request->tgl_tap){
        $date = str_replace('/', '-', $request->tgl_tap);
        $params['tgl_tap'] = date('Y-m-d',strtotime($date));
      }else{
        $params['tgl_tap'] = null;
      }
      $query = execute_api_json('/api/pemusnahan/'.$id,'PUT',$params);

			if ($request->file('file_upload')){
					$fileName = date('Y-m-d').'_'.$id.'-'.$request->file('file_upload')->getClientOriginalName();
					try {
						$request->file('file_upload')->storeAs('PemusnahanBB', $fileName);
						$params2['file_upload'] = $fileName;
						$update2 = execute_api_json('/api/pemusnahan/'.$id,'PUT',$params2);

					}catch(\Exception $e){
						$e->getMessage();
					}
			}

      $trail['audit_menu'] = 'Pemberantasan - Direktorat Wastahti - Pemusnahan Barang Bukti';
      $trail['audit_event'] = 'put';
      $trail['audit_value'] = json_encode($params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $query->comment;
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($token,$trail);

        if($query->code == 200 && $query->status != 'error'){
          $this->kelengkapan_PendataanBrgbukti($id);
          $this->data['status'] = 'success';
          $this->data['message'] = 'Data Pemusnahan Barang Bukti Berhasil Diperbarui';
        }else{
          $this->data['status'] = 'error';
          $this->data['message'] = 'Data Pemusnahan Barang Bukti Gagal Diperbarui';
        }
        return back()->with('status',$this->data);


      // // print_r($query);

      // $this->kelengkapan_PendataanBrgbukti($id);


    }

    public function deletePendataanBrgbukti(Request $request){
        if ($request->ajax()) {
            $id = $request->id;
            if($id){
                $data_request = execute_api('api/pemusnahan/'.$id,'DELETE');
                $this->form_params['delete_id'] = $id;
                $trail['audit_menu'] = 'Pemberantasan - Direktorat Wastahti - Pemusnahan Barang Bukti';
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
                $data_request = ['code'=>200,'status'=>'error','message'=>'Pendataan Pemusnahan Barang Bukti Gagal Dihapus'];
                return $data_request;
            }
        }
    }

    private function kelengkapan_PendataanBrgbukti($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('berantas_pemusnahan_barangbukti')->where('id',$id)
                  ->select('nomor_lkn','nama_penyidik','tgl_penitipan','tgl_penitipan_ambil','tgl_penitipan_ambil','tgl_tap', 'nama_kejari');
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
          $kelengkapan = execute_api_json('api/pemusnahan/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/pemusnahan/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }


    public function pendataanTahanan(Request $request){
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
              }else if(($key == 'jenistahanan') || ($key == 'nomor_kasus') || ($key == 'alamatdomisili') || ($key == 'status') ){
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
        $jenistahanan = $request->jenistahanan;
        // $pelaksana = $request->pelaksana;
        $status = $request->status;
        // $BrgBukti = $request->BrgBukti;
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
        }elseif($tipe == 'jenistahanan'){
          $kondisi .= '&jenistahanan='.$jenistahanan;
          $this->selected['jenistahanan'] = $jenistahanan;
        }elseif($tipe == 'status'){
          $kondisi .= '&status='.$status;
          $this->selected['status'] = $status;
        // }elseif($tipe == 'BrgBukti'){
        //   $kondisi .= '&BrgBukti='.$BrgBukti;
        //   $this->selected['nm_brgbukti'] = $BrgBukti;
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
      // $this->data['title'] = "Pendataan Tahanan Dir Wastahti";
      if($request->page){
          $current_page = $request->page;
          $start_number = ($this->limit * ($request->page -1 )) + 1;
      }else{
          $current_page = 1;
          $start_number = $current_page;
      }

      $limit = 'limit='.$this->limit;
      $offset = 'page='.$current_page;
      $kondisi .='&id_wilayah='.$request->session()->get('wilayah');
      $datas = execute_api('api/tahanan?'.$limit.'&'.$offset.$kondisi,'get');
      // print_r($datas);
      // echo '</pre>';
      // dd($datas);
      $this->data['kondisi'] ='?'.$offset.$kondisi;
      if( ($datas['code']= 200) && ($datas['code'] != 'error')){
        $this->data['tahanan'] = $datas['data'];
        $total_item = $datas['paginate']['totalpage'] * $this->limit;
      }else{
        $this->data['tahanan'] = [];
        $total_item =  0;
      }

      $instansi = $this->globalinstansi($request->session()->get('wilayah'), $token);
      $this->data['instansi'] = $instansi;

      $brgbukti = execute_api('api/jnsbrgbukti','POST');
      // print_r($brgbukti);
      // exit();
      if($brgbukti['code'] == 200 && $brgbukti['status'] != 'error'){
        $this->data['brgbukti'] = $brgbukti['data'];
      }else{
        $this->data['brgbukti'] = [];
      }

      // $instansi  =  execute_api('api/instansi',"GET");


      // if($instansi['code'] == 200 && ($instansi['status'] != 'error')){
      //   $this->data['instansi'] = $instansi['data'];
      // }else{
      //   $this->data['instansi'] = [];
      // }

      // $query  =  execute_api('api/lookup/irtama_satker',"GET");


      // if($query['code'] == 200 && ($query['status'] != 'error')){
      //   $this->data['satker'] = $query['data'];
      // }else{
      //   $this->data['satker'] = [];
      // }
      $this->data['current_page'] = $current_page;
      $this->data['start_number'] = $start_number;
      $this->data['title'] = "Pendataan Tahanan Dir Wastahti";
      $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
      $this->data['token'] = $token;
      $this->data['path'] = $request->path();
      $this->data['delete_route'] = 'delete_pendataan_tahanan';
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
      $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'),config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
      $this->data['breadcrumps'] = breadcrumps_dir_wastahti($request->route()->getName());

        //old

        // $client = new Client();

        // $baseUrl = URL::to('/');
        // $token = $request->session()->get('token');
        // if ($request->input('page')) {
        //   $page = $request->input('page');
        // } else {
        //   $page = 1;
        // }

        // $requestTahanan = $client->request('GET', $baseUrl.'/api/tahanan?page='.$page.'&id_wilayah='.$request->session()->get('wilayah'),
        //     [
        //         'headers' =>
        //         [
        //             'Authorization' => 'Bearer '.$token
        //         ]
        //     ]
        // );
        // $tahanan = json_decode($requestTahanan->getBody()->getContents(), true);
        // $page = $tahanan['paginate'];

        // $this->data['tahanan'] = $tahanan['data'];

        // $this->data['title'] = "tahanan";
        // $this->data['token'] = $token;


        // $instansi = $this->globalinstansi($request->session()->get('wilayah'), $token);

        // $user_id = Auth::user()->user_id;
        // $detail = MainModel::getUserDetail($user_id);
        // $this->data['data_detail'] = $detail;
        // $this->data['path'] = $request->path();
        // $this->data['instansi'] = $instansi;
        // $this->data['page'] = $page;
        // $this->data['breadcrumps'] = breadcrumps_dir_wastahti($request->route()->getName());
        return view('pemberantasan.wastahti.index_pendataanTahanan',$this->data);
    }

    public function addDataPendataanTahanan(Request $request)
    {
      $client = new Client();
      $baseUrl = URL::to($this->urlapi());
//      $baseUrl = URL::to('/');

      $token = $request->session()->get('token');
      $lkn = $this->globalGetByLkn($token, $request->input('kasus_no'));

      if (count($lkn['data']) == 0) {
        $this->messages['status'] = 'error';
        $this->messages['message'] = 'No LKN tidak ditemukan.';
        return redirect('pemberantasan/dir_wastahti/add_pendataan_tahanan')->with('message',$this->messages);
      }

      $dataTahananHeaderByLkn = TahananHeader::where('nomor_kasus', $request->input('kasus_no'))->get();

      if (count($dataTahananHeaderByLkn) >= 1) {
        $dataTahananHeaderByLkn = TahananHeader::where('nomor_kasus', $request->input('kasus_no'));
        if($dataTahananHeaderByLkn->count()>0){
          $d  = $dataTahananHeaderByLkn->first();
          $id =  $d->id;
          $tahanan = Tahanan::where('header_id', $id);
          if($tahanan->count() > 0){
            $q = $tahanan->first();
            return redirect(route('edit_pendataan_tahanan',[$q->tahanan_id]));
          }else{
            $d  = $dataTahananHeaderByLkn->first();
            return redirect('pemberantasan/dir_wastahti/edit_list_pendataan_tahanan/'.$d->id);
          }
        }else{
            $d  = $dataTahananHeaderByLkn->first();
            return redirect('pemberantasan/dir_wastahti/edit_list_pendataan_tahanan/'.$d->id);
        }



      }

      $id = $lkn['data'][0]['kasus_id'];
      $noLkn = $lkn['data'][0]['kasus_no'];
      $tersangka = $this->globalGetTersangka($token, $id);
      // dd($tersangka);
      $requestPendataanTahananHeader = $client->request('POST', $baseUrl.'/api/tahananheader',
          [
              'headers' =>
              [
                  'Authorization' => 'Bearer '.$token
              ],
              'form_params' =>
              [
                  'kasus_id' => $id,
                  'nomor_kasus' => $noLkn
              ]
          ]
      );

      $pendataanTahananHeader = json_decode($requestPendataanTahananHeader->getBody()->getContents(), true);

      $headerId = $pendataanTahananHeader['data']['eventID'];
      // dd($headerId);

      $this->form_params = array('kasus_id' => $id,
      'nomor_kasus' => $noLkn);

      $trail['audit_menu'] = 'Pemberantasan - Direktorat Wastahti - Pendataan Tahanan Header';
      $trail['audit_event'] = 'post';
      $trail['audit_value'] = json_encode($this->form_params);
      $trail['audit_url'] = $request->url();
      $trail['audit_ip_address'] = $request->ip();
      $trail['audit_user_agent'] = $request->userAgent();
      $trail['audit_message'] = $pendataanTahananHeader['comment'];
      $trail['created_at'] = date("Y-m-d H:i:s");
      $trail['created_by'] = $request->session()->get('id');

      $qtrail = $this->inputtrail($token,$trail);


      foreach ($tersangka['data'] as $value) {
        // dd($value);
        $requestPendataanTahanan = $client->request('POST', $baseUrl.'/api/tahanan',
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ],
                'form_params' =>
                [
                    'id_kasus' => $value['kasus_id'],
                    'nomor_kasus' => $noLkn,
                    'tersangka_id' => intval($value['tersangka_id']),
                    'header_id' => $headerId,
                    'kode_jenistahanan' => $request->input('jenis_tahanan'),
                    'tersangka_nama' => $value['tersangka_nama'],
                    'tersangka_alamat' => $value['tersangka_alamat'],
                    'kode_jenis_kelamin' => $value['kode_jenis_kelamin'],
                    'tersangka_tempat_lahir' => $value['tersangka_tempat_lahir'],
                    'tersangka_usia' => $value['tersangka_usia'],
                    'kode_kelompok_usia' => $value['kode_kelompok_usia'],
                    'tersangka_tanggal_lahir' => $value['tersangka_tanggal_lahir'],
                    'kode_pendidikan_akhir' => $value['kode_pendidikan_akhir'],
                    'kode_pekerjaan' => $value['kode_pekerjaan'],
                    'kode_warga_negara' => $value['kode_warga_negara'],
                    'kode_peran_tersangka' => $value['kode_peran_tersangka'],
                    'created_by' => $value['created_by'],
                    'create_date' => $value['create_date'],
                    'no_identitas' => $value['no_identitas'],
                    'kode_negara' => $value['kode_negara'],
                    'kode_jenisidentitas' => $value['kode_jenisidentitas'],
                    'alamatktp_idprovinsi' => $value['alamatktp_idprovinsi'],
                    'alamatktp_idkabkota' => $value['alamatktp_idkabkota'],
                    'alamatktp_kodepos' => $value['alamatktp_kodepos'],
                    'alamatdomisili' => $value['alamatdomisili'],
                    'alamatdomisili_idprovinsi' => $value['alamatdomisili_idprovinsi'],
                    'alamatdomisili_idkabkota' => $value['alamatdomisili_idkabkota'],
                    'alamatdomisili_kodepos' => $value['alamatdomisili_kodepos'],
                    'alamatlainnya' => $value['alamatlainnya'],
                    'alamatlainnya_idprovinsi' => $value['alamatlainnya_idprovinsi'],
                    'alamatlainnya_idkabkota' => $value['alamatlainnya_idkabkota'],
                    'alamatlainnya_kodepos' => $value['alamatlainnya_kodepos'],
                    'tersangka_nama_alias' => $value['tersangka_nama_alias'],
                    'pasal_dikenakan' => $value['pasal']
                ]
            ]
        );

        $pendataanTahanan = json_decode($requestPendataanTahanan->getBody()->getContents(), true);

        $trail['audit_menu'] = 'Pemberantasan - Direktorat Wastahti - Pendataan Tahanan';
        $trail['audit_event'] = 'post';
        $trail['audit_value'] = json_encode($value);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $pendataanTahanan['comment'];
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($token,$trail);

      }
      $tahanan = Tahanan::where('header_id', $headerId);
      if($tahanan->count() > 0 ){
        $t = $tahanan->first();
        return redirect(route('edit_pendataan_tahanan',[$t->tahanan_id]));
      }else{
        return redirect('pemberantasan/dir_wastahti/edit_list_pendataan_tahanan/'.$headerId);
      }
    }

    public function editListPendataanTahanan(Request $request)
    {
      $headerId = $request->id;
      $tahanan = Tahanan::where('header_id', $headerId)->get();

      $this->data['token'] = $request->session()->get('token');
      $this->data['title'] = 'Tahanan';
      $this->data['tahanan'] = $tahanan;
      $this->data['breadcrumps'] = breadcrumps_dir_wastahti($request->route()->getName());
      return view('pemberantasan.wastahti.edit_list_pendataanTahanan',$this->data);
    }

    public function editPendataanTahanan(Request $request)
    {
      $client = new Client();
      $baseUrl = URL::to($this->urlapi());
//      $baseUrl = URL::to('/');

      $token = $request->session()->get('token');

      $id = $request->id;
      // $id = '68950';
      $requestPendataanTahanan = $client->request('GET', $baseUrl.'/api/tahanan/'.$id,
      [
        'headers' =>
        [
          'Authorization' => 'Bearer '.$token
        ]
      ]
    );

    $pendataanTahanan = json_decode($requestPendataanTahanan->getBody()->getContents(), true);
    $this->data['tahanan'] = $pendataanTahanan['data'];
    $this->data['negara'] = $this->globalNegara($token);
    // dd($this->data['tahanan']);
    $this->data['breadcrumps'] = breadcrumps_dir_wastahti($request->route()->getName());
    return view('pemberantasan.wastahti.edit_pendataanTahanan',$this->data);
  }

    public function addPendataanTahanan(Request $request){
      $client = new Client();

       $baseUrl = URL::to($this->urlapi());
//       $baseUrl = URL::to('/');
       $token = $request->session()->get('token');

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;

        $requestlkn = $client->request('GET', $baseUrl.'/api/getlistlkn?id_wilayah='.$request->session()->get('wilayah'),
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
        $lkn = json_decode($requestlkn->getBody()->getContents(), true);

        $data['lkn'] = $lkn['data'];
        $data['breadcrumps'] = breadcrumps_dir_wastahti($request->route()->getName());
        return view('pemberantasan.wastahti.add_pendataanTahanan',$data);
    }

    public function inputPendataanTahanan(Request $request)
    {
      $client = new Client();
      $baseUrl = URL::to($this->urlapi());
//      $baseUrl = URL::to('/');

      // $datediff = \Carbon\Carbon::parse(date('Y-m-d', strtotime(str_replace('/', '-', $request->input('masa_berlaku_penahanan')))))->diff(\Carbon\Carbon::parse(date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_masuk'))))))->format('%y tahun %m bulan %d hari');

      $token = $request->session()->get('token');
      // dd($request->all());
      $requestPendataanTahanan = $client->request('PUT', $baseUrl.'/api/tahanan/'.$request->input('tahanan_id'),
      [
        'headers' =>
        [
          'Authorization' => 'Bearer '.$token
        ],
        'form_params' =>
        [
          'id_kasus' => $request->input('id_kasus'),
          'nomor_kasus' => $request->input('nomor_kasus'),
          'kode_jenistahanan' => $request->input('kode_jenistahanan'),
          'tersangka_nama' => $request->input('tersangka_nama'),
          'tersangka_alamat' => $request->input('tersangka_alamat'),
          'kode_jenis_kelamin' => $request->input('kode_jenis_kelamin'),
          'tersangka_tempat_lahir' => $request->input('tersangka_tempat_lahir'),
          'tersangka_usia' => $request->input('tersangka_usia'),
          'kode_kelompok_usia' => $request->input('kode_kelompok_usia'),
          "tersangka_tanggal_lahir" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tersangtanggal_lahir')))),
          // 'tersangka_tanggal_lahir' => $request->input('tersangka_tanggal_lahir'),
          'kode_pendidikan_akhir' => $request->input('kode_pendidikan_akhir'),
          'kode_pekerjaan' => $request->input('kode_pekerjaan'),
          'kode_warga_negara' => $request->input('kode_warga_negara'),
          'kode_peran_tersangka' => $request->input('kode_peran_tersangka'),
          'create_date' => Carbon::now()->format('Y-m-d H:i:s'),
          'no_identitas' => $request->input('no_identitas'),
          'kode_negara' => $request->input('kode_negara'),
          'kode_jenisidentitas' => $request->input('kode_jenisidentitas'),
          'alamatktp_idprovinsi' => $request->input('alamatktp_idprovinsi'),
          'alamatktp_kodepos' => $request->input('alamatktp_kodepos'),
          'alamatdomisili' => $request->input('alamatdomisili'),
          'alamatdomisili_idprovinsi' => $request->input('alamatdomisili_idprovinsi'),
          'alamatdomisili_idkabkota' => $request->input('alamatdomisili_idkabkota'),
          'alamatdomisili_kodepos' => $request->input('alamatdomisili_kodepos'),
          'alamatlainnya' => $request->input('alamatlainnya'),
          'alamatlainnya_idprovinsi' => $request->input('alamatlainnya_idprovinsi'),
          'alamatlainnya_idkabkota' => $request->input('alamatlainnya_idkabkota'),
          'alamatlainnya_kodepos' => $request->input('alamatlainnya_kodepos'),
          'id_pasaldikenakan' => $request->input('id_pasaldikenakan'),
          'file_foto_tampak_depan' => $request->input('file_foto_tampak_depan'),
          'file_foto_tampaksampingkanan' => $request->input('file_foto_tampaksampingkanan'),
          'file_foto_tampaksampingkiri' => $request->input('file_foto_tampaksampingkiri'),
          'document_ak23' => $request->input('document_ak23'),
          'file_sidikjari_kanan01' => $request->input('file_sidikjari_kanan01'),
          'file_sidikjari_kanan02' => $request->input('file_sidikjari_kanan02'),
          'file_sidikjari_kanan03' => $request->input('file_sidikjari_kanan03'),
          'file_sidikjari_kanan04' => $request->input('file_sidikjari_kanan04'),
          'file_sidikjari_kanan05' => $request->input('file_sidikjari_kanan05'),
          'file_sidikjari_kiri01' => $request->input('file_sidikjari_kiri01'),
          'file_sidikjari_kiri02' => $request->input('file_sidikjari_kiri02'),
          'file_sidikjari_kiri03' => $request->input('file_sidikjari_kiri03'),
          'file_sidikjari_kiri04' => $request->input('file_sidikjari_kiri04'),
          'file_sidikjari_kiri05' => $request->input('file_sidikjari_kiri05'),
          'fisik_warna_rambut' => $request->input('fisik_warna_rambut'),
          'fisik_tipikal_rambut' => $request->input('fisik_tipikal_rambut'),
          'fisik_tinggi_badan' => $request->input('fisik_tinggi_badan'),
          'fisik_perawakan' => $request->input('fisik_perawakan'),
          'fisik_warna_kulit' => $request->input('fisik_warna_kulit'),
          'fisik_bentuk_mata' => $request->input('fisik_bentuk_mata'),
          'fisik_bentuk_wajah' => $request->input('fisik_bentuk_wajah'),
          'fisik_lohat_bahasa' => $request->input('fisik_lohat_bahasa'),
          'fisik_suku_ras' => $request->input('fisik_suku_ras'),
          'tgl_masuk' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_masuk')))),
          'tgl_keluar' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_keluar')))),
          'lama_penahanan' => $request->input('lama_penahanan'),
          'nomor_sp_penahanan' => $request->input('nomor_sp_penahanan'),
          'asal_tahanan' => $request->input('asal_tahanan'),
          'nama_penyidik' => $request->input('nama_penyidik'),
          'nomor_sp_perpanjangan_penahanan_01' => $request->input('nomor_sp_perpanjangan_penahanan_01'),
          'nomor_sp_perpanjangan_penahanan_02' => $request->input('nomor_sp_perpanjangan_penahanan_02'),
          'nomor_sp_perpanjangan_penahanan_03' => $request->input('nomor_sp_perpanjangan_penahanan_03'),
          'tersangka_id' => $request->input('tersangka_id'),
          'header_id' => $request->input('header_id'),
          'tersangka_nama_alias' => $request->input('tersangka_nama_alias'),
          'pasal_dikenakan' => $request->input('pasal_dikenakan'),
          'fisik_berat_badan' => $request->input('fisik_berat_badan'),
          'fisik_kelainan_mata' => $request->input('fisik_kelainan_mata'),
          'fisik_hidung' => $request->input('fisik_hidung'),
          'fisik_bibir' => $request->input('fisik_bibir'),
          'fisik_gigi' => $request->input('fisik_gigi'),
          'fisik_dagu' => $request->input('fisik_dagu'),
          'fisik_telinga' => $request->input('fisik_telinga'),
          'fisik_tatto' => $request->input('fisik_tatto'),
          'fisik_cacat' => $request->input('fisik_cacat'),
          'kode_pasal' => $request->input('kode_pasal'),
          'alamatktp_idkabkota' => $request->input('alamatktp_idkabkota')
          ]
          ]
          );

          $pendataanTahanan = json_decode($requestPendataanTahanan->getBody()->getContents(), true);
          // dd($pendataanTahanan);
          // exit();
          $this->form_params = array('id_kasus' => $request->input('id_kasus'),
          'nomor_kasus' => $request->input('nomor_kasus'),
          'kode_jenistahanan' => $request->input('kode_jenistahanan'),
          'tersangka_nama' => $request->input('tersangka_nama'),
          'tersangka_alamat' => $request->input('tersangka_alamat'),
          'kode_jenis_kelamin' => $request->input('kode_jenis_kelamin'),
          'tersangka_tempat_lahir' => $request->input('tersangka_tempat_lahir'),
          'tersangka_usia' => $request->input('tersangka_usia'),
          'kode_kelompok_usia' => $request->input('kode_kelompok_usia'),
          "tersangka_tanggal_lahir" => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tersangtanggal_lahir')))),
          // 'tersangka_tanggal_lahir' => $request->input('tersangka_tanggal_lahir'),
          'kode_pendidikan_akhir' => $request->input('kode_pendidikan_akhir'),
          'kode_pekerjaan' => $request->input('kode_pekerjaan'),
          'kode_warga_negara' => $request->input('kode_warga_negara'),
          'kode_peran_tersangka' => $request->input('kode_peran_tersangka'),
          'create_date' => Carbon::now()->format('Y-m-d H:i:s'),
          'no_identitas' => $request->input('no_identitas'),
          'kode_negara' => $request->input('kode_negara'),
          'kode_jenisidentitas' => $request->input('kode_jenisidentitas'),
          'alamatktp_idprovinsi' => $request->input('alamatktp_idprovinsi'),
          'alamatktp_kodepos' => $request->input('alamatktp_kodepos'),
          'alamatdomisili' => $request->input('alamatdomisili'),
          'alamatdomisili_idprovinsi' => $request->input('alamatdomisili_idprovinsi'),
          'alamatdomisili_idkabkota' => $request->input('alamatdomisili_idkabkota'),
          'alamatdomisili_kodepos' => $request->input('alamatdomisili_kodepos'),
          'alamatlainnya' => $request->input('alamatlainnya'),
          'alamatlainnya_idprovinsi' => $request->input('alamatlainnya_idprovinsi'),
          'alamatlainnya_idkabkota' => $request->input('alamatlainnya_idkabkota'),
          'alamatlainnya_kodepos' => $request->input('alamatlainnya_kodepos'),
          'id_pasaldikenakan' => $request->input('id_pasaldikenakan'),
          'file_foto_tampak_depan' => $request->input('file_foto_tampak_depan'),
          'file_foto_tampaksampingkanan' => $request->input('file_foto_tampaksampingkanan'),
          'file_foto_tampaksampingkiri' => $request->input('file_foto_tampaksampingkiri'),
          'document_ak23' => $request->input('document_ak23'),
          'file_sidikjari_kanan01' => $request->input('file_sidikjari_kanan01'),
          'file_sidikjari_kanan02' => $request->input('file_sidikjari_kanan02'),
          'file_sidikjari_kanan03' => $request->input('file_sidikjari_kanan03'),
          'file_sidikjari_kanan04' => $request->input('file_sidikjari_kanan04'),
          'file_sidikjari_kanan05' => $request->input('file_sidikjari_kanan05'),
          'file_sidikjari_kiri01' => $request->input('file_sidikjari_kiri01'),
          'file_sidikjari_kiri02' => $request->input('file_sidikjari_kiri02'),
          'file_sidikjari_kiri03' => $request->input('file_sidikjari_kiri03'),
          'file_sidikjari_kiri04' => $request->input('file_sidikjari_kiri04'),
          'file_sidikjari_kiri05' => $request->input('file_sidikjari_kiri05'),
          'fisik_warna_rambut' => $request->input('fisik_warna_rambut'),
          'fisik_tipikal_rambut' => $request->input('fisik_tipikal_rambut'),
          'fisik_tinggi_badan' => $request->input('fisik_tinggi_badan'),
          'fisik_perawakan' => $request->input('fisik_perawakan'),
          'fisik_warna_kulit' => $request->input('fisik_warna_kulit'),
          'fisik_bentuk_mata' => $request->input('fisik_bentuk_mata'),
          'fisik_bentuk_wajah' => $request->input('fisik_bentuk_wajah'),
          'fisik_lohat_bahasa' => $request->input('fisik_lohat_bahasa'),
          'fisik_suku_ras' => $request->input('fisik_suku_ras'),
          'tgl_masuk' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_masuk')))),
          'tgl_keluar' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_keluar')))),
          'lama_penahanan' => $request->input('lama_penahanan'),
          'nomor_sp_penahanan' => $request->input('nomor_sp_penahanan'),
          'asal_tahanan' => $request->input('asal_tahanan'),
          'nama_penyidik' => $request->input('nama_penyidik'),
          'nomor_sp_perpanjangan_penahanan_01' => $request->input('nomor_sp_perpanjangan_penahanan_01'),
          'nomor_sp_perpanjangan_penahanan_02' => $request->input('nomor_sp_perpanjangan_penahanan_02'),
          'nomor_sp_perpanjangan_penahanan_03' => $request->input('nomor_sp_perpanjangan_penahanan_03'),
          'tersangka_id' => $request->input('tersangka_id'),
          'header_id' => $request->input('header_id'),
          'tersangka_nama_alias' => $request->input('tersangka_nama_alias'),
          'pasal_dikenakan' => $request->input('pasal_dikenakan'),
          'fisik_berat_badan' => $request->input('fisik_berat_badan'),
          'fisik_kelainan_mata' => $request->input('fisik_kelainan_mata'),
          'fisik_hidung' => $request->input('fisik_hidung'),
          'fisik_bibir' => $request->input('fisik_bibir'),
          'fisik_gigi' => $request->input('fisik_gigi'),
          'fisik_dagu' => $request->input('fisik_dagu'),
          'fisik_telinga' => $request->input('fisik_telinga'),
          'fisik_tatto' => $request->input('fisik_tatto'),
          'fisik_cacat' => $request->input('fisik_cacat'),
          'kode_pasal' => $request->input('kode_pasal'),
          'alamatktp_idkabkota' => $request->input('alamatktp_idkabkota'));

          $trail['audit_menu'] = 'Pemberantasan - Direktorat Wastahti - Pendataan Tahanan';
          $trail['audit_event'] = 'put';
          $trail['audit_value'] = json_encode($this->form_params);
          $trail['audit_url'] = $request->url();
          $trail['audit_ip_address'] = $request->ip();
          $trail['audit_user_agent'] = $request->userAgent();
          $trail['audit_message'] = $pendataanTahanan['comment'];
          $trail['created_at'] = date("Y-m-d H:i:s");
          $trail['created_by'] = $request->session()->get('id');

          $qtrail = $this->inputtrail($token,$trail);


          if ($request->file('file_foto_tampak_depan') != ''){
              $fileName = $request->input('tahanan_id').'-'.$request->file('file_foto_tampak_depan')->getClientOriginalName();
              $request->file('file_foto_tampak_depan')->storeAs('wastahtiTahananFoto', $fileName);

              $requestfile = $client->request('PUT', $baseUrl.'/api/tahanan/'.$request->input('tahanan_id'),
                      [
                          'headers' =>
                          [
                              'Authorization' => 'Bearer '.$token
                          ],
                          'form_params' => [
                              'file_foto_tampak_depan' => $fileName,
                          ]
                      ]
                  );
           }

           if ($request->file('document_ak23') != ''){
               $fileName = $request->input('tahanan_id').'-'.$request->file('document_ak23')->getClientOriginalName();
               $request->file('document_ak23')->storeAs('wastahtiTahananDocument', $fileName);

               $requestfile = $client->request('PUT', $baseUrl.'/api/tahanan/'.$request->input('tahanan_id'),
                       [
                           'headers' =>
                           [
                               'Authorization' => 'Bearer '.$token
                           ],
                           'form_params' => [
                               'document_ak23' => $fileName,
                           ]
                       ]
                   );
            }
        if($pendataanTahanan['code'] == 200 && $pendataanTahanan['status'] != 'error'){
          $id = $request->input('tahanan_id');
          // echo $id;
          // exit();/
          $this->kelengkapan_PendataanTahanan($id);
          $this->data['status'] = 'success';
          $this->data['message'] = 'Data Pendataan Tahanan di BNN dan BNNP Berhasil Diperbarui';
        }else{
          $this->data['status'] = 'error';
          $this->data['message'] = 'Data Pendataan Tahanan di BNN dan BNNP Gagal Diperbarui';
        }
        return back()->with('status',$this->data);

          //   $this->kelengkapan_PendataanTahanan($id);

          // return redirect('/pemberantasan/dir_wastahti/pendataan_tahanan');
        }

    public function deletePendataanTahanan(Request $request){
        if ($request->ajax()) {
            $id = $request->id;
            if($id){
                $data_request = execute_api('api/tahanan/'.$id,'DELETE');
                $this->form_params['delete_id'] = $id;
                $trail['audit_menu'] = 'Pemberantasan - Direktorat Wastahti - Pendataan Tahanan';
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
                $data_request = ['code'=>200,'status'=>'error','message'=>'Pendataan Tahanan di BNN dan BNNP Gagal Dihapus'];
                return $data_request;
            }
        }
    }

    private function kelengkapan_PendataanTahanan($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('berantas_tahanan')->where('tahanan_id',$id)
                  ->select('kode_jenisidentitas','no_identitas','tersangka_nama','tersangka_nama_alias','tersangka_alamat','alamatktp_kodepos','alamatdomisili','alamatdomisili_kodepos','alamatlainnya','alamatlainnya_kodepos','kode_jenis_kelamin','tersangka_tempat_lahir','tersangka_tanggal_lahir','tersangka_usia','kode_pendidikan_akhir','kode_pekerjaan','kode_warga_negara','kode_peran_tersangka','kode_negara','document_ak23','file_foto_tampak_depan','pasal_dikenakan','tgl_masuk','tgl_keluar','lama_penahanan','nomor_sp_penahanan','asal_tahanan','nama_penyidik','nomor_sp_perpanjangan_penahanan_01','nomor_sp_perpanjangan_penahanan_02','nomor_sp_perpanjangan_penahanan_03');
        // echo $query->count();

        // exit();

        if($query->count() > 0 ){
          $result = $query->first();
          // dd($result);
          // exit();
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
          $kelengkapan = execute_api_json('api/tahanan/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/tahanan/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

  public function printPendataanBrgbukti(Request $request){
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

    // $page = 'page='.$request->current_page;
    // $id_wil = $request->session()->get('wilayah');
    $inteljaringan = execute_api('/api/pemusnahan'.$kondisi, 'GET');
    $limit = config('app.limit');
    $start_number = ($limit * ($request->page -1 )) + 1;
    $result = [];
    if(($inteljaringan['code']== 200) && ($inteljaringan['status'] != 'error')){
      $data = $inteljaringan['data'];
      if(count($data) >0){

      $i = $start_number;
            foreach($data as $key=>$d){
                $metas = [];
                $result[$key]['No'] = $i;
                $result[$key]['Nomor LKN'] =$d['nomor_lkn'];
                $result[$key]['Nama Penyidik'] = $d['nama_penyidik'];
                $result[$key]['Tanggal Ketetapan'] =( $d['tgl_tap'] ? date('d/m/Y', strtotime($d['tgl_tap'])) : '');
                $result[$key]['Nomor Tap'] = $d['nomor_tap'];
                $meta = $d['bbdetail'];

                $string_meta = $d['bbdetail'];
                  if(count($meta)){
                    for($j = 0 ; $j < count($meta); $j++){
                      $metas[]  = $meta[$j]['nm_brgbukti'].'('.$meta[$j]['jumlah_dimusnahkan'].' '.$meta[$j]['nm_satuan'].')';
                    }
                  }else{
                    $metas = [];
                  }
                if(count($metas)>0 ){
                  $string_meta = implode(',', $metas);
                }
                $result[$key]['Barang bukti dimusnahkan'] = $string_meta ;
                $i = $i+1;
            }
            $name = 'Pendataan Pemusnahan Barang Bukti '.Carbon::now()->format('Y-m-d H:i:s');
            $this->printData($result, $name);
        }else{
            echo 'data tidak tersedia';
        }
    }else{
      echo 'data tidak tersedia';
    }

  }

  public function printPendataanTahanan(Request $request){
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

    // $page = 'page='.$request->current_page;
    // $id_wil = $request->session()->get('wilayah');
    $inteljaringan = execute_api('/api/tahanan'.$kondisi, 'GET');
    $limit = config('app.limit');
    $start_number = ($limit * ($request->page -1 )) + 1;
    $result = [];
    if(($inteljaringan['code']== 200) && ($inteljaringan['status'] != 'error')){
      $data = $inteljaringan['data'];
      if(count($data) >0){

      $i = $start_number;
            foreach($data as $key=>$d){
                $metas = [];
                $result[$key]['No'] = $i;
                $result[$key]['Jenis Tahanan'] =$d['kode_jenistahanan'];
                $result[$key]['No. Kasus'] = $d['nomor_kasus'];
                $result[$key]['Tanggal Tahanan'] = ( $d['tgl_masuk'] ? date('d/m/Y', strtotime($d['tgl_masuk'])) : '');
                $result[$key]['Alamat Tahanan'] = $d['alamatdomisili'];

                $i = $i+1;
            }
            $name = 'Pendataan Tahanan '.Carbon::now()->format('Y-m-d H:i:s');
            $this->printData($result, $name);
        }else{
            echo 'data tidak tersedia';
        }
    }else{
      echo 'data tidak tersedia';
    }

  }

}

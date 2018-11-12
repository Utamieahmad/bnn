<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use URL;
use DateTime;
use Carbon\Carbon;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use Excel;
use Storage;

class RaziaController extends Controller
{
    public $data;
    public $selected;
    public $form_params;

    public function pendataanRazia(Request $request){
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
              }else if(($key == 'lokasi') ){
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
        $lokasi = $request->lokasi;
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
        // }elseif($tipe == 'lokasi'){
        //   $kondisi .= '&lokasi='.$lokasi;
        //   $this->selected['lokasi'] = $lokasi;
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
      // $kondisi .='&id_wilayah='.$request->session()->get('wilayah').'&kategori=narkotika';
      $datas = execute_api('api/berantasrazia?'.$limit.'&'.$offset.$kondisi,'get');
      // print_r($datas);
      // echo '</pre>';
      // dd($datas);
      $this->data['kondisi'] ='?'.$offset.$kondisi;
      if( ($datas['code']= 200) && ($datas['code'] != 'error')){
        $this->data['data_razia'] = $datas['data'];
        $total_item = $datas['paginate']['totalpage'] * $this->limit;
      }else{
        $this->data['data_razia'] = [];
        $total_item =  0;
      }

      $this->data['current_page'] = $current_page;
      $this->data['start_number'] = $start_number;
      $this->data['title'] = "Pendataan Razia";
      $this->data['token'] = $token;
      $this->data['path'] = $request->path();
      $this->data['delete_route'] = 'delete_razia';
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
      $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'),config('app.url').$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
      $this->data['breadcrumps'] = breadcrumps_razia($request->route()->getName());

      return view('pemberantasan.razia.index',$this->data);
    }

    public function editPendataanRazia(Request $request){
        $id = $request->id;

        $client = new Client();

        $baseUrl = URL::to($this->urlapi());

        $token = $request->session()->get('token');

        $requestrazia = $client->request('GET', $baseUrl.'/api/berantasrazia/'.$id,
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ]
                ]
            );

        $result = json_decode($requestrazia->getBody()->getContents(), true);

        $this->data['data_razia'] = $result;

        $this->data['id'] = $id;
        $this->data['title'] = 'tersangka';

        $this->data['breadcrumps'] = breadcrumps_razia($request->route()->getName());
        return view('pemberantasan.razia.edit_pendataanRazia',$this->data);
    }

    public function addPendataanRazia(Request $request){
      $this->data['title']="pemberantasan";
      $client = new Client();
      $baseUrl = URL::to($this->urlapi());

      $token = $request->session()->get('token');

      $this->data['breadcrumps'] = breadcrumps_razia($request->route()->getName());
      return view('pemberantasan.razia.add_pendataanRazia', $this->data);
    }

    public function inputPendataanRazia(Request $request){

        $baseUrl = URL::to($this->urlapi());

        $token = $request->session()->get('token');

        $client = new Client();

        //generate image base64
        if($request->hasFile('foto1')){
            $filenameWithExt = $request->file('foto1')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('foto1')->getClientOriginalExtension();
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            $path = $request->file('foto1')->storeAs('Berantas/Razia', $fileNameToStore);
            $image = public_path('upload/Berantas/Razia/'.$fileNameToStore);
            $data = file_get_contents($image);
            $image1 = base64_encode($data);
            Storage::delete('Berantas/Razia/'.$fileNameToStore);
        }else{
          $image1 = null;
        }

        if($request->hasFile('foto2')){
            $filenameWithExt = $request->file('foto2')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('foto2')->getClientOriginalExtension();
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            $path = $request->file('foto2')->storeAs('Berantas/Razia', $fileNameToStore);
            $image = public_path('upload/Berantas/Razia/'.$fileNameToStore);
            $data = file_get_contents($image);
            $image2 = base64_encode($data);
            Storage::delete('Berantas/Razia/'.$fileNameToStore);
        }else{
          $image2 = null;
        }

        if($request->hasFile('foto3')){
            $filenameWithExt = $request->file('foto3')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('foto3')->getClientOriginalExtension();
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            $path = $request->file('foto3')->storeAs('Berantas/Razia', $fileNameToStore);
            $image = public_path('upload/Berantas/Razia/'.$fileNameToStore);
            $data = file_get_contents($image);
            $image3 = base64_encode($data);
            Storage::delete('Berantas/Razia/'.$fileNameToStore);
        }else{
          $image3 = null;
        }

        $requestrazia = $client->request('POST', $baseUrl.'/api/berantasrazia',
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer '.$token
                    ],
                    'form_params' => [
                        'tgl_razia' => ( $request->input('tgl_razia') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_razia')))) : ''),
                        'lokasi' => $request->input('lokasi'),
                        'jumlah_dirazia' => $request->input('jumlah_dirazia'),
                        'jumlah_terindikasi' => $request->input('jumlah_terindikasi'),
                        'jumlah_ditemukan' => $request->input('jumlah_ditemukan'),
                        'foto1' => $image1,
                        'foto2' => $image2,
                        'foto3' => $image3,
                        'uraian_singkat' => $request->input('uraian_singkat'),
                        'keterangan_lainnya' => $request->input('keterangan_lainnya'),
                    ]
                ]
            );

        $result = json_decode($requestrazia->getBody()->getContents(), true);
        $id = $result['data']['eventID'];

        $this->form_params = array('tgl_razia' => ( $request->input('tgl_razia') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_razia')))) : ''),
                        'jumlah_dirazia' => $request->input('jumlah_dirazia'),
                        'jumlah_terindikasi' => $request->input('jumlah_terindikasi'),
                        'jumlah_ditemukan' => $request->input('jumlah_ditemukan'),
                        'foto1' => $image1,
                        'foto2' => $image2,
                        'foto3' => $image3,
                        'uraian_singkat' => $request->input('uraian_singkat'),
                        'keterangan_lainnya' => $request->input('keterangan_lainnya'),);

        $trail['audit_menu'] = 'Pemberantasan - Pendataan Razia';
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
            $this->data['status'] = 'success';
            $this->data['message'] = 'Data Razia Berhasil Disimpan';
        }else{
            $this->data['status'] = 'error';
            $this->data['message'] = 'Data Razia Gagal Disimpan';
        }
        $this->data['breadcrumps'] = breadcrumps_razia($request->route()->getName());
        return redirect('pemberantasan/razia')->with('status',$this->data);
    }

    public function updatePendataanRazia(Request $request){
        $id = $request->input('id');

        $baseUrl = URL::to($this->urlapi());

        $token = $request->session()->get('token');

        $client = new Client();
        
        //generate image base64
        if($request->hasFile('foto1')){
            $filenameWithExt = $request->file('foto1')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('foto1')->getClientOriginalExtension();
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            $path = $request->file('foto1')->storeAs('Berantas/Razia', $fileNameToStore);
            $image = public_path('upload/Berantas/Razia/'.$fileNameToStore);
            $data = file_get_contents($image);
            $image1 = base64_encode($data);
            Storage::delete('Berantas/Razia/'.$fileNameToStore);

        }else{
            $image1 = $request->input('foto1_old');

        }

        if($request->hasFile('foto2')){
            $filenameWithExt = $request->file('foto2')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('foto2')->getClientOriginalExtension();
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            $path = $request->file('foto2')->storeAs('Berantas/Razia', $fileNameToStore);
            $image = public_path('upload/Berantas/Razia/'.$fileNameToStore);
            $data = file_get_contents($image);
            $image2 = base64_encode($data);
            Storage::delete('Berantas/Razia/'.$fileNameToStore);

        }else{
            $image2 = $request->input('foto2_old');

        }

        if($request->hasFile('foto3')){
            $filenameWithExt = $request->file('foto3')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('foto3')->getClientOriginalExtension();
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            $path = $request->file('foto3')->storeAs('Berantas/Razia', $fileNameToStore);
            $image = public_path('upload/Berantas/Razia/'.$fileNameToStore);
            $data = file_get_contents($image);
            $image3 = base64_encode($data);
            Storage::delete('Berantas/Razia/'.$fileNameToStore);

        }else{
            $image3 = $request->input('foto3_old');

        }
        
        $query = $client->request('PUT', $baseUrl.'/api/berantasrazia/'.$id,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ],
                'form_params' => [
                        'tgl_razia' => ( $request->input('tgl_razia') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_razia')))) : ''),
                        'lokasi' => $request->input('lokasi'),
                        'jumlah_dirazia' => $request->input('jumlah_dirazia'),
                        'jumlah_terindikasi' => $request->input('jumlah_terindikasi'),
                        'jumlah_ditemukan' => $request->input('jumlah_ditemukan'),
                        'foto1' => $image1,
                        'foto2' => $image2,
                        'foto3' => $image3,
                        'uraian_singkat' => $request->input('uraian_singkat'),
                        'keterangan_lainnya' => $request->input('keterangan_lainnya'),
                ]
            ]
        );
        $result = json_decode($query->getBody()->getContents(), true);

        $this->form_params = array(
                        'tgl_razia' => ( $request->input('tgl_razia') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_razia')))) : ''),
                        'lokasi' => $request->input('lokasi'),
                        'jumlah_dirazia' => $request->input('jumlah_dirazia'),
                        'jumlah_terindikasi' => $request->input('jumlah_terindikasi'),
                        'jumlah_ditemukan' => $request->input('jumlah_ditemukan'),
                        'foto1' => $image1,
                        'foto2' => $image2,
                        'foto3' => $image3,
                        'uraian_singkat' => $request->input('uraian_singkat'),
                        'keterangan_lainnya' => $request->input('keterangan_lainnya'),);

        $trail['audit_menu'] = 'Pemberantasan - Pendataan Razia';
        $trail['audit_event'] = 'put';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $result['comment'];
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($token,$trail);

        if($result['code'] == 200 && $result['status'] != 'error'){
            $this->data['status'] = 'success';
            $this->data['message'] = 'Data Razia Berhasil Diperbarui';
        }else{
            $this->data['status'] = 'error';
            $this->data['message'] = 'Data Razia Gagal Diperbarui';
        }
        return back()->with('status',$this->data);
    }

    public function deletePendataanRazia(Request $request){
        if ($request->ajax()) {
            $id = $request->id;
            if($id){
                $data_request = execute_api('api/berantasrazia/'.$id,'DELETE');
                $this->form_params['delete_id'] = $id;
                $trail['audit_menu'] = 'Pemberantasan - Pendataan Razia';
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
                $data_request = ['code'=>200,'status'=>'error','message'=>'Data Razia Gagal Dihapus'];
                return $id;
            }
        }
    }

    public function printPendataanRazia(Request $request){
      $client = new Client();
      $page = $request->input('page');
      $token = $request->session()->get('token');
      $baseUrl = URL::to($this->urlapi());

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

      $i = $start_number;

      $requestKasus = $client->request('GET', $baseUrl.'/api/berantasrazia'.$kondisi,
          [
              'headers' =>
              [
                  'Authorization' => 'Bearer '.$token
              ]
          ]
      );

      $kasus = json_decode($requestKasus->getBody()->getContents(), true);

      $kasusArray = [];

      foreach ($kasus['data'] as $key => $value) {
        $kasusArray[$key]['No'] = $i;
        $kasusArray[$key]['Tanggal Razia'] = ( $value['tgl_razia'] ? date('d/m/Y', strtotime($value['tgl_razia'])) : '');
        $kasusArray[$key]['Lokasi'] = $value['lokasi'];
        $kasusArray[$key]['Uraian Singkat'] = $value['uraian_singkat'];
        $kasusArray[$key]['Jumlah Dirazia'] = $value['jumlah_dirazia'];
        $kasusArray[$key]['Jumlah Terindikasi'] = $value['jumlah_terindikasi'];
        $kasusArray[$key]['Jumlah Ditemukan'] = $value['jumlah_ditemukan'];
        $kasusArray[$key]['Keterangan Lainnya'] = $value['keterangan_lainnya'];
        $i += 1;
      }
      // dd($kasusArray);
      $data = $kasusArray;
      $name = 'Data Razia '.$request->kategori.' '.Carbon::now()->format('Y-m-d H:i:s');
      $this->printData($data, $name);
    }

    public function downloadPendataanRazia(Request $request){
      $dataqry = DB::table('v_razia');
      if ($request->date_from != '') {
          $dataqry->where('tgl_razia', '>=', date('Y-m-d', strtotime(str_replace('/', '-', $request->date_from))));
      }
      if ($request->date_to != '' ) {
          $dataqry->where('tgl_razia', '<=', date('Y-m-d', strtotime(str_replace('/', '-', $request->date_to))));
      }

      $dataqry = $dataqry->orderBy('tgl_razia', 'desc')->get();
      // dd($pemusnahanladang);
      $kasusArray = [];
      $i = 1;

      foreach ($dataqry as $key => $value) {
        $kasusArray[$key]['No'] = $i;
        $kasusArray[$key]['Tanggal Razia'] = ( $value->tgl_razia ? date('d/m/Y', strtotime($value->tgl_razia)) : '');
        $kasusArray[$key]['Lokasi'] = $value->lokasi;
        $kasusArray[$key]['Uraian Singkat'] = $value->uraian_singkat;
        $kasusArray[$key]['Jumlah Dirazia'] = $value->jumlah_dirazia;
        $kasusArray[$key]['Jumlah Terindikasi'] = $value->jumlah_terindikasi;
        $kasusArray[$key]['Jumlah Ditemukan'] = $value->jumlah_ditemukan;
        $kasusArray[$key]['Keterangan Lainnya'] = $value->keterangan_lainnya;
        $i += 1;
      }
      // dd($kasusArray);
      $data = $kasusArray;
      $name = 'Export Data Razia '.$request->kategori.' '.Carbon::now()->format('Y-m-d H:i:s');
      $this->printData($data, $name);
    }

}

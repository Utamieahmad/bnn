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
use Illuminate\Support\Facades\Route;
use Excel;

class pengujianController extends Controller
{
    public $data;
	  public $selected;
    private $url_balaiLab = 'http://103.3.70.84:7001/balailab/berkas';
    // public function pengujianBahan(Request $request){
    //     $kondisi = '';
    //     if($request->limit) {
    //       $this->limit = $request->limit;
    //     } else {
    //       $this->limit = config('app.limit');
    //     }
    //     if($request->order) {
    //       $this->order = $request->order;
    //     } else {
    //       $this->order = 1;
    //     }
    //     if($request->isMethod('post')){
    //       $post = $request->except(['_token','tanggal_terima_start','tanggal_terima_end','no_lab','no_surat_permohonan','no_lp','nama_instansi','provinsi','jenis_berkas','status_berkas']);
    //       $tipe = $request->tipe;
    //       $tanggal_terima_start = $request->tanggal_terima_start;
    //       $tanggal_terima_end = $request->tanggal_terima_end;
    //       $no_lab = $request->no_lab;
    //       $no_surat_permohonan = $request->no_surat_permohonan;
    //       $no_lp = $request->no_lp;
    //       $nama_instansi = $request->nama_instansi;
    //       $provinsi = $request->provinsi;
    //       $jenis_berkas = $request->jenis_berkas;
    //       $status_berkas = $request->status_berkas;
    //       if($tipe){
    //         $kondisi .= '&tipe='.$tipe;
    //         $this->selected['tipe'] = $tipe;
    //       }
    //       if($tipe == 'tanggal_terima'){
    //         if($tanggal_terima_start){
    //           $date = date('Y-m-d',strtotime(str_replace('/', '-', $tanggal_terima_start)));
    //           $kondisi .= '&tanggal_terima_start='.$date;
    //           $this->selected['tanggal_terima_start'] = date('Y-m-d',strtotime(str_replace('/', '-', $tanggal_terima_start)));
    //         }else if(!$tanggal_terima_start){
    //             $kondisi .='';
    //         }
    //         if($tanggal_terima_end){
    //           $date = date('Y-m-d',strtotime(str_replace('/', '-', $tanggal_terima_end)));
    //           $kondisi .= '&tanggal_terima_end='.$date;
    //           $this->selected['tanggal_terima_end'] = date('Y-m-d',strtotime(str_replace('/', '-', $tanggal_terima_end)));
    //         }else if(!$tanggal_terima_end){
    //           $kondisi .='';
    //         }
    //       }elseif( ($tipe == 'no_lab') || ($tipe == 'no_surat_permohonan') || ($tipe == 'no_lp')|| ($tipe == 'nama_instansi') || ($tipe == 'provinsi') ||($tipe == 'jenis_berkas') || ($tipe == 'status_berkas')){
    //         $kondisi .= '&'.$tipe.'='.$request->kata_kunci;
    //         $this->selected[$tipe] = $request->kata_kunci;
    //       }else {
    //         if(isset($post[$tipe])){
    //           $kondisi .= '&'.$tipe.'='.$post[$tipe];
    //           $this->selected[$tipe] = $post[$tipe];
    //         }
    //       }
    //       if($request->order){
    //         $kondisi .= '&order='.$request->order;
    //       }elseif(!$request->order){
    //         $kondisi .= '&order=desc';
    //       }
    //       $this->selected['order'] = $this->order;
    //       $this->selected['limit'] = $this->limit;
    //       $this->data['filter'] = $this->selected;

    //     }else{
    //       $get = $request->except(['page','tanggal_terima_start','tanggal_terima_end','limit']);
    //       $tipe = $request->tipe;
    //       if(count($get)>0){
    //         if($tipe){
    //           $this->selected['tipe']  = $tipe;
    //         }
    //         foreach ($get as $key => $value) {
    //           $kondisi .= "&".$key.'='.$value;
    //           if($value == 'tanggal_terima'){
    //             if($request->tanggal_terima_start){
    //                 $this->selected['tanggal_terima_start'] = date('Y-m-d',strtotime($request->tanggal_terima_start));
    //                 $kondisi .= "&tanggal_terima_start".'='.$request->tanggal_terima_start;
    //             }
    //             if($request->tanggal_terima_end){
    //               $this->selected['tanggal_terima_end'] = date('Y-m-d',strtotime($request->tanggal_terima_end));
    //               $kondisi .= "&tanggal_terima_end".'='.$request->tanggal_terima_end;
    //             }
    //           }else {
    //             $this->selected[$key] = $value;
    //           }
    //         }
    //       }
    //       $this->selected['order'] = $this->order;
    //       $this->selected['limit'] = $this->limit;
    //       $this->data['filter'] = $this->selected;
    //     }
    //     if($request->page){
    //         $current_page = $request->page;
    //         $start_number = ($this->limit * ($request->page -1 )) + 1;
    //     }else{
    //         $current_page = 1;
    //         $start_number = $current_page;
    //     }
    //     $limit = 'limit='.$this->limit;
    //     $offset = 'page='.$current_page;
    //     // $datas = execute_api_json('api/pengujian?'.$limit.'&'.$offset.$kondisi,'get');
    //     // $datas = execute_api_json($this->url_balaiLab.'?'.$limit.'&'.$offset.$kondisi,'post');
    //     // if($request->isMethod('get')){
    //     //   $datas = execute_api_json($this->url_balaiLab.'?'.$limit.'&'.$offset.$kondisi,'get');
    //     // }else{
    //     // dd($this->selected);
    //       $data = $this->selected;
    //       $data_string = json_encode($data);

    //       $ch = curl_init();

    //       curl_setopt($ch, CURLOPT_URL, $this->url_balaiLab.'/filter'.'?'.$limit.'&'.$offset.$kondisi);
    //       // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    //       curl_setopt($ch, CURLOPT_POST, true);
    //       curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    //       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //       curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    //           'Content-Type: application/json')//,
    //           // 'Content-Length: ' . strlen($data_string))
    //       );
    //       $datas = json_decode(curl_exec($ch),false);
    //       curl_close($ch);
    //     // }

    //     if($datas->code == 200){
    //         $this->data['data'] = $datas->data;
    //         $total_item = $datas->paginate->totalpage * $this->limit;
    //     }else{
    //         $this->data['data'] = [];
    //         $total_item = 0;
    //     }

    //     $filtering = false;
    //     if($kondisi){
    //       $filter = '&'.$limit.$kondisi;
    //       $filtering = true;
    //       $this->data['kondisi'] = '?'.$offset.$kondisi.'&'.$limit;
    //     }else{
    //       $filter = '/';
    //       $filtering = false;
    //       $this->data['kondisi'] = $current_page;
    //     }

    //     $this->data['breadcrumps'] = breadcrump_balai_lab($request->route()->getName());
    //     $this->data['route'] = $request->route()->getName();
    //     $this->data['title'] = 'Berkas Sampel';
    //     $this->data['path'] = $request->path();
    //     $this->data['delete_route'] = 'delete_berkas_sampel';
    //     $this->data['start_number'] = $start_number;
    //     $this->data['current_page'] = $current_page;
    //     $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
    //     return view('balai_lab.index_pengujianBahan',$this->data);
    // }
    public function pengujianBahan(Request $request){
        $kondisi = '';
        $array_kondisi = [];
        $datas = [];
        if($request->limit) {
          $this->limit = $request->limit;
        } else {
          $this->limit = config('app.limit');
        }
        if($request->order) {
          $this->order = $request->order;
        } else {
          $this->order = 1;
        }
        if($request->isMethod('post')){
          $post = $request->except(['_token','tanggal_terima_start','tanggal_terima_end','no_lab','no_surat_permohonan','no_lp','nama_instansi','provinsi','jenis_berkas','status_berkas']);
          $tipe = $request->tipe;
          $tanggal_terima_start = $request->tanggal_terima_start;
          $tanggal_terima_end = $request->tanggal_terima_end;
          $no_lab = $request->no_lab;
          $no_surat_permohonan = $request->no_surat_permohonan;
          $no_lp = $request->no_lp;
          $nama_instansi = $request->nama_instansi;
          $provinsi = $request->provinsi;
          $jenis_berkas = $request->jenis_berkas;
          $status_berkas = $request->status_berkas;
          $limit = $request->limit;
          if($tipe){
            $kondisi .= '&tipe='.$tipe;
            $this->selected['tipe'] = $tipe;
          }
          if($tipe == 'tanggal_terima'){
            if($tanggal_terima_start){
              $date = date('Y-m-d',strtotime(str_replace('/', '-', $tanggal_terima_start)));
              $kondisi .= '&tanggal_terima_start='.$date;
              $this->selected['tanggal_terima_start'] = $tanggal_terima_start;
            }else if(!$tanggal_terima_start){
                $kondisi .='';
            }
            if($tanggal_terima_end){
              $date = date('Y-m-d',strtotime(str_replace('/', '-', $tanggal_terima_end)));
              $kondisi .= '&tanggal_terima_end='.$date;
              $this->selected['tanggal_terima_end'] = $tanggal_terima_end;
            }else if(!$tanggal_terima_end){
              $kondisi .='';
            }
          }elseif( ($tipe == 'no_lab') || ($tipe == 'no_surat_permohonan') || ($tipe == 'no_lp')|| ($tipe == 'nama_instansi') || ($tipe == 'provinsi') ||($tipe == 'jenis_berkas') || ($tipe == 'status_berkas')){
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
          $this->selected['order'] = $this->order;
          $this->selected['limit'] = $this->limit;
          $this->data['filter'] = $this->selected;

        }else{
          $get = $request->except(['page','tanggal_terima_start','tanggal_terima_end','limit']);
          $tipe = $request->tipe;
          if(count($get)>0){
            if($tipe){
              $this->selected['tipe']  = $tipe;
            }
            foreach ($get as $key => $value) {
              $kondisi .= "&".$key.'='.$value;
              if($value == 'tanggal_terima'){
                if($request->tanggal_terima_start){
                    $this->selected['tanggal_terima_start'] = date('d/m/Y',strtotime($request->tanggal_terima_start));
                    $kondisi .= "&tanggal_terima_start".'='.$request->tanggal_terima_start;
                }
                if($request->tanggal_terima_end){
                  $this->selected['tanggal_terima_end'] = date('d/m/Y',strtotime($request->tanggal_terima_end));
                  $kondisi .= "&tanggal_terima_end".'='.$request->tanggal_terima_end;
                }
              }else {
                $this->selected[$key] = $value;
              }
            }
          }
          $this->selected['order'] = $this->order;
          $this->selected['limit'] = $this->limit;
          $this->data['filter'] = $this->selected;
        }
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }

        $tipe = $request->tipe;
        $kata_kunci = $request->kata_kunci;
        $order = $request->order;
        $limit = $request->limit;
        $method = 'GET';
        if($request->isMethod('post')){
          $array_kondisi[$tipe] = $kata_kunci;
          $array_kondisi['order'] = $this->order ;
          $array_kondisi['limit'] = $this->limit ;
          $this->selected['order'] = $this->order;
          $this->selected['limit'] = $this->limit;
          $this->selected['tipe'] = $tipe;
          $array_keywords = ['no_lab','no_surat_permohonan','no_lp','nama_instansi','provinsi'];
          $options_keywords = ['jenis_berkas','status_berkas'];

          if(in_array($tipe, $array_keywords) ){
            $this->selected[$tipe] = $kata_kunci;
            $kondisi .= 'tipe='.$tipe.'&'.$tipe.'='.$kata_kunci.'&limit='.$this->limit.'&order='.$this->order;
          }else if(in_array($tipe, $options_keywords) ){
            $this->selected[$tipe] = $request->$tipe;
            $kondisi .= 'tipe='.$tipe.'&'.$tipe.'='.$request->$tipe.'&limit='.$this->limit.'&order='.$this->order;
          }else{
              $kondisi .= 'tipe='.$tipe.'&limit='.$this->limit.'&order='.$this->order;
              if($request->tanggal_terima_start){
                  $array_kondisi['tanggal_terima_start'] = date('Y-m-d',strtotime(str_replace('/', '-', $request->tanggal_terima_start))) ;
                  $this->selected['tanggal_terima_start'] = date('Y-m-d',strtotime(str_replace('/', '-', $request->tanggal_terima_start)));
                  $kondisi .= "&tanggal_terima_start".'='.$request->tanggal_terima_start;
              }
              if($request->tanggal_terima_end){
                $array_kondisi['tanggal_terima_end'] = date('Y-m-d',strtotime(str_replace('/', '-', $request->tanggal_terima_end))) ;
                $this->selected['tanggal_terima_end'] = date('Y-m-d',strtotime(str_replace('/', '-', $request->tanggal_terima_end)));
                $kondisi .= "&tanggal_terima_end".'='.$request->tanggal_terima_end;
              }
          }
          $this->data['filter'] =$this->selected;
          $kondisi = rtrim($kondisi,"&");
          $method  = 'POST';
        }else{
          $get = $request->except(['page']);
          foreach($get as $k => $g){
            $array_kondisi[$k] = $g;
            $this->selected[$k] = $g;
            $kondisi .= $k.'='.$g.'&';
          }
          $array_kondisi['page'] = $request->page;
          $array_kondisi['order'] = $this->order ;
          $array_kondisi['limit'] = $this->limit ;
          $this->data['filter'] =$this->selected;

          $kondisi = rtrim($kondisi,"&");

          $method  = 'POST';
        }
        if(count($array_kondisi) > 0 ){
          $data_string = json_encode($array_kondisi);
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $this->url_balaiLab.'/filter');
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array(
              'Content-Type: application/json')
          );
          $datas = json_decode(curl_exec($ch),false);
          curl_close($ch);
        }else{
          $datas = execute_api_json($this->url_balaiLab,'GET');
        }



        if($datas->code == 200 && $datas->status != 'error'){
          $this->data['data'] = $datas->data;
          $total_item = $datas->paginate->totalpage * $this->limit;
        }else{
          $this->data['data'] = [];
          $total_item = 0;
        }
        $filtering = false;
        if($kondisi){
          $filter = '&'.$kondisi;
          $filtering = true;
          $this->data['kondisi'] = '?page='.$current_page.'&'.$kondisi;
        }else{
          $filter = '/';
          $filtering = false;
          $this->data['kondisi'] = $current_page;
        }
        $this->data['breadcrumps'] = breadcrump_balai_lab($request->route()->getName());
        $this->data['route'] = $request->route()->getName();
        $this->data['title'] = 'Berkas Sampel';
        $this->data['path'] = $request->path();
        $this->data['delete_route'] = 'delete_berkas_sampel';
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
        return view('balai_lab.index_pengujianBahan',$this->data);
    }
    public function editpengujianBahan(Request $request){
        $id = $request->id;
        $datas = execute_api_json('api/pengujian/'.$id,'get');
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
        $this->data['breadcrumps'] = breadcrump_balai_lab($request->route()->getName());
        $this->data['jenis_kasus'] = config('lookup.jenis_kasus');
        $this->data['golongan'] = config('lookup.golongan');
        $this->data['jenis_barang_bukti'] = config('lookup.jenis_barang_bukti');
        $this->data['satuan'] = config('lookup.satuan');
        $this->data['laporan_hasil'] = config('lookup.laporan_hasil');
        $this->data['start_number'] = $start_number;
        return view('balai_lab.edit_pengujianBahan',$this->data);
    }



    public function addpengujianBahan(Request $request){
        if ($request->isMethod('post')) {
            $token = $request->session()->get('token');
            $this->form_params = $request->except(['_token']);
            if($request->tgl_surat){
                $date = explode('/', $request->tgl_surat);
                $this->form_params['tgl_surat'] = $date[2].'-'.$date[1].'-'.$date[0];
            }
            if($request->tgl_lplkn){
                $date = explode('/', $request->tgl_lplkn);
                $this->form_params['tgl_lplkn'] = $date[2].'-'.$date[1].'-'.$date[0];
            }
            $kuantitas = str_replace(',', '', $request->kuantitas);
            $this->form_params['kuantitas'] = $kuantitas;
            $data_request = execute_api_json('api/pengujian/','POST',$this->form_params);
            if( ($data_request->code == 200) && ($data_request->status != 'error') ){
                $id = $data_request->data->eventID;
                $this->kelengkapan_balai_lab($id);
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Berkas Sampel Berhasil Ditambahkan';
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Berkas Sampel Gagal Ditambahkan';
            }
            return redirect(route('berkas_sampel'))->with('status', $this->messages);
        }else{
            $this->data['breadcrumps'] = breadcrump_balai_lab($request->route()->getName());
            $this->data['jenis_kasus'] = config('lookup.jenis_kasus');
            $this->data['golongan'] = config('lookup.golongan');
            $this->data['jenis_barang_bukti'] = config('lookup.jenis_barang_bukti');
            $this->data['satuan'] = config('lookup.satuan');
            $this->data['laporan_hasil'] = config('lookup.laporan_hasil');
            return view('balai_lab/add_pengujianBahan',$this->data);
        }

        // $this->data['title']="Balai Pendidikan dan Pelatihan";
        // $client = new Client();
        // $baseUrl = URL::to('/');

        // $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
        // $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

        // $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        // $this->data['propkab'] = $propkab;
        // $data['data_detail'] = $detail;
        // return view('balai_lab.add_pengujianBahan',$this->data);
    }

    public function deletePengujianBahan(Request $request){
        $id = $request->input('id');
        if ($request->ajax()) {
            if($id){
                $id = $request->id;
                $data_request = execute_api('api/pengujian/'.$id,'DELETE');
                return $data_request;
            }else{
                $data_request =['status'=>'error','messages'=>'Gagal Menghapus Data'];
                return $data_request;
            }
        }
    }
    public function updatePengujianBahan(Request $request){
        $id = $request->input('id');
        $token = $request->session()->get('token');
        $this->form_params = $request->except(['_token', 'id']);
        if($request->tgl_surat){
            $date = explode('/', $request->tgl_surat);
            $this->form_params['tgl_surat'] = $date[2].'-'.$date[1].'-'.$date[0];
        }

        if($request->tgl_lplkn){
            $date = explode('/', $request->tgl_lplkn);
            $this->form_params['tgl_lplkn'] = $date[2].'-'.$date[1].'-'.$date[0];
        }
        $kuantitas = str_replace(',', '', $request->kuantitas);
        $this->form_params['kuantitas'] = $kuantitas;
        $data_request = execute_api_json('api/pengujian/'.$id,'PUT',$this->form_params);
        if( ($data_request->code == 200) && ($data_request->status != 'error') ){
            $this->kelengkapan_balai_lab($id);
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Berkas Sampel Berhasil Diperbarui';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Berkas Sampel Gagal Diperbarui';
        }
        return back()->with('status', $this->messages);
    }

    public function printPage(Request $request){
        // $get = $request->all();
        $kondisi = "";
        if($request->limit) {
          $this->limit = $request->limit;
        } else {
          $this->limit = config('app.limit');
        }
        if($request->order) {
          $this->order = $request->order;
        } else {
          $this->order = 1;
        }
        // if(count($get)>0){
        //     foreach($get as $key=>$val){
        //       $kondisi .= $key.'='.$val.'&';
        //     }
        //     $kondisi = rtrim($kondisi,'&');
        //     $kondisi = '?'.$kondisi;
        // }else{
        //     $kondisi = '?page='.$request->page;
        // }
        //
        // if($request->limit){
        //     $limit = $request->limit;
        // }else{
        //     $limit = config('app.limit');
        // }
        // $page = $request->page;
        // if($page){
        //     $start_number = ($limit * ($request->page -1 )) + 1;
        // }else{
        //     $start_number = 1;
        // }

          $get = $request->except(['page','tanggal_terima_start','tanggal_terima_end','limit']);
          $tipe = $request->tipe;
          if(count($get)>0){
            if($tipe){
              $this->selected['tipe']  = $tipe;
            }
            foreach ($get as $key => $value) {
              $kondisi .= "&".$key.'='.$value;
              if($value == 'tanggal_terima'){
                if($request->tanggal_terima_start){
                    $this->selected['tanggal_terima_start'] = date('d/m/Y',strtotime($request->tanggal_terima_start));
                    $kondisi .= "&tanggal_terima_start".'='.$request->tanggal_terima_start;
                }
                if($request->tanggal_terima_end){
                  $this->selected['tanggal_terima_end'] = date('d/m/Y',strtotime($request->tanggal_terima_end));
                  $kondisi .= "&tanggal_terima_end".'='.$request->tanggal_terima_end;
                }
              }else {
                $this->selected[$key] = $value;
              }
            }
          }
          $this->selected['order'] = $this->order;
          $this->selected['limit'] = $this->limit;
          $this->data['filter'] = $this->selected;
          if($request->page){
              $current_page = $request->page;
              $start_number = ($this->limit * ($request->page -1 )) + 1;
          }else{
              $current_page = 1;
              $start_number = $current_page;
          }
          $limit = 'limit='.$this->limit;
          $offset = 'page='.$current_page;

        $data = $this->selected;
        $data_string = json_encode($data);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->url_balaiLab.'/filter'.'?'.$limit.'&'.$offset.$kondisi);
        // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json')//,
            // 'Content-Length: ' . strlen($data_string))
        );
        $data_request = json_decode(curl_exec($ch),false);

        // $url = 'api/pengujian/'.$kondisi;
        // $data_request = execute_api_json($url,'GET');
        $result= [];
        $i = $start_number;
        if($data_request->code == 200 && $data_request->status != 'error'){
            $data = $data_request->data;
            $i = $start_number;
            if(count($data_request)>=1){
                $data = $data_request->data;
                foreach($data as $key=>$d){
                    $result[$key]['No'] = $i;
                    $result[$key]['No Lab'] = $d->no_lab;
                    $result[$key]['No. Surat Permohonan'] = $d->no_surat_permohonan;
                    $result[$key]['No Laporan'] = $d->no_lp;
                    $result[$key]['Tanggal Terima'] = ( $d->tanggal_terima? date('d/m/Y',strtotime($d->tanggal_terima)) :'');
                    $result[$key]['Instansi'] = $d->nama_instansi;
                    $result[$key]['Posisi Berkas'] = $d->provinsi;
                    $result[$key]['Jenis Berkas'] = $d->jenis_berkas;
                    $result[$key]['Status Berkas'] = $d->status_berkas;
                    $i = $i + 1;
                }
                $name = 'Berkas Sampel'.' '.Carbon::now()->format('Y-m-d H:i:s');
                $this->printData($result, $name);
            }else{
                return false;
            }
        }else{
            echo 'tidak ada';
        }
    }

    private function kelengkapan_balai_lab($id){
        $status_kelengkapan = true;
        try{
            $query = DB::table('balailab_pengujian')->where('id',$id)
                    ->select('nomor_surat_permohonan_pengajuan','tgl_surat','perihal_surat','no_lplkn','tgl_lplkn','nama_instansi','nama_pengirim','no_telp_pengirim','kuantitas','logo','hasil_uji');
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
                $kelengkapan = execute_api_json('api/pengujian/'.$id,'PUT',['status'=>'Y']);
            }else{
                $kelengkapan = execute_api_json('api/pengujian/'.$id,'PUT',['status'=>'N']);
            }
        }catch(\Exception $e){
            $status_kelengkapan=false;
        }
    }
}

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

class arahanController extends Controller
{
    public $data;
	public $selected = [];
    public function arahanPimpinan(Request $request){
        $kondisi = '';

        $currentPath= Route::getFacadeRoot()->current()->uri();

        $this->limit = config('app.limit');

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
                    $kondisi .= "&".$key.'='.$value;
                    if( ($key == 'tgl_from') || ($key == 'tgl_to') ){
                        if($value){
                            $this->selected[$key] = date('d/m/Y',strtotime($value));
                        }
                    }else if( ($key == 'tgl_from_kadaluarsa') || ($key == 'tgl_to_kadaluarsa') ){
                        if($value){
                            $this->selected[$key] = date('d/m/Y',strtotime($value));
                        }
                    }else if(($key == 'status')|| ($key == 'satker' )){
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
            $tgl_from = $request->tgl_from;
            $tgl_to = $request->tgl_to;
            $tgl_from_kadaluarsa = $request->tgl_from_kadaluarsa;
            $tgl_to_kadaluarsa = $request->tgl_to_kadaluarsa;
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
            }else if($tipe == 'periode_kadaluarsa'){
                if($tgl_from_kadaluarsa){
                    $date = date('Y-m-d',strtotime(str_replace('/', '-', $tgl_from_kadaluarsa)));
                    $kondisi .= '&tgl_from_kadaluarsa='.$date;
                    $this->selected['tgl_from_kadaluarsa'] = $tgl_from_kadaluarsa;
                }else{
                    $kondisi .='';
                }
                if($tgl_to_kadaluarsa){
                    $date = date('Y-m-d',strtotime(str_replace('/', '-', $tgl_to_kadaluarsa)));
                    $kondisi .= '&tgl_to_kadaluarsa='.$date;
                    $this->selected['tgl_to_kadaluarsa'] = $tgl_to_kadaluarsa;
                }else{
                    $kondisi .='';
                }
            }else if($tipe == 'satker'){
                $kondisi .= '&'.$tipe.'='.$request->satker;
                $this->selected['satker'] = $request->satker;
            }else if($tipe == 'status'){
                $kondisi .= '&'.$tipe.'='.$request->status;
                $this->selected['status'] = $request->status;
            }
            if($tipe){
                $kondisi .= '&tipe='.$tipe;
                $this->selected['tipe'] = $tipe;
            }
            $kondisi .='&limit='.$this->limit;
            $kondisi .='&order='.$order;
            $this->selected['limit'] = $this->limit;
            $this->selected['order'] =  $order;
            $this->data['filter'] = $this->selected;

        }

        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
        $this->data['kondisi'] ='?'.$offset.$kondisi;
        $datas = execute_api_json('api/arahan?'.$limit.'&'.$offset.$kondisi,'get');
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;
        }else{
            $this->data['data'] = [];
            $total_item = 0;
        }
        $token = $request->session()->get('token');

        $url_simpeg = config('app.url_simpeg');
        $satker = execute_api_json($url_simpeg,'get');
        if( $satker->code == 200 && $satker->status =='berhasil'){
            $this->data['satker'] =$satker->data ;
        }else{
            $this->data['satker'] = [] ;
        }


        if($datas->code == 200){
            $this->data['data'] = $datas->data;
        }else{
            $this->data['data'] = [];
        }
        $this->data['route'] = $request->route()->getName();
        $this->data['path'] = $request->path();
        $this->data['delete_route'] = 'delete_arahan_pimpinan';
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;

        $filtering = false;
        if($kondisi){
            $filter = $kondisi;
            $filtering = true;
        }else{
            $filter = '/';
            $filtering = false;
        }
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), "/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
        $this->data['title'] = 'Arahan Pimpinan';
        $this->data['breadcrumps'] = breadcrump_arahan_pimpinan($request->route()->getName());
        return view('arahan_pimpinan.index_arahanPimpinan',$this->data);

    }

    public function addarahanPimpinan(Request $request){
        $user_data = $request->session()->get('user_data');
        $auth = Auth::user();
        $nip = $auth->nip;
        $disabled = true;
        $nama_satker = "";
        $object_satker = "";
        $id_satker = "";
        try{
            $data_user = $this->getSimpegSatker($nip);
            if( ($data_user['code'] == 200) && ($data_user['status'] != 'error') ){

                $id_satker = $data_user['data']['id_satker'];
                $nama_satker = $data_user['data']['nama_satker'];
                if($id_satker){
                    $disabled = false;
                    $object_satker=  array('id_satker'=>$id_satker,'nama_satker'=>$nama_satker);
                }else{
                    if($user_data['group_id'] == 1){
                        $nama_satker  = 'Administator';
                        $object_satker= (object) array('id_satker'=>$user_data['group_id'],'nama_satker'=>$nama_satker);
                        $disabled = false;
                    }else{
                        $disabled = true;
                    }
                }
            }else{
                $disabled = true;
                $nama_satker = "";
                $object_satker = "";
            }
        }catch(\Exception $e){
            $disabled = true;
            $nama_satker = "";
            $object_satker = "";
        }
        $object_satker = json_encode($object_satker);
        if ($request->isMethod('post')) {
            $token = $request->session()->get('token');
            $this->form_params = $request->except(['_token']);
            // $this->form_params['satker'] = $user_data['group_id'];
            $this->form_params['satker'] = $object_satker;
            $this->form_params['created_by'] = $id_satker;
            if($request->tgl_kadaluarsa){
                $date = str_replace('/','-',$request->tgl_kadaluarsa);
                $this->form_params['tgl_kadaluarsa'] = date('Y-m-d',strtotime($date));
            }
            if($request->tgl_arahan){
                $date = str_replace('/','-',$request->tgl_arahan);
                $this->form_params['tgl_arahan'] = date('Y-m-d',strtotime($date));
            }
            $data_request = execute_api_json('api/arahan','POST',$this->form_params);

            $trail['audit_menu'] = 'Arahan Pimpinan';
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
                $insertedId = $data_request->data->eventID;
                $this->kelengkapan($insertedId);
                $this->messages['status'] = 'success';
                $this->messages['message'] = 'Data Arahan Pimpinan Berhasil Ditambahkan';
            }else{
                $this->messages['status'] = 'error';
                $this->messages['message'] = 'Data Arahan Pimpinan Gagal Ditambahkan';
            }
            return redirect(route('arahan_pimpinan'))->with('status', $this->messages);
        }else{
            $this->data['disabled'] = $disabled;
            $this->data['group_id'] = $user_data['group_id'];
            $this->data['group'] = $user_data['group'];
            $this->data['nama_satker'] = $nama_satker;
            $this->data['title'] = 'Tambah Arahan Pimpinan';
            $this->data['breadcrumps'] = breadcrump_arahan_pimpinan($request->route()->getName());
            return view('arahan_pimpinan/add_arahanPimpinan',$this->data);
        }
    }

    public function editarahanPimpinan(Request $request){
        $id = $request->id;
        $user_data = $request->session()->get('user_data');
        $this->data['user_data'] = $user_data;
        $data_request = execute_api_json('api/arahan/'.$id,'GET');
        $d = [];
        $edit = false;
        if( ($data_request->code == 200) && ($data_request->status != 'error') ){
            $this->data['data'] = $data_request->data;
            $d = $data_request->data;
        }else{
            $this->data['data'] = [];
        }

        $auth = Auth::user();
        $nip = $auth->nip;
        $disabled = true;
        $nama_satker = "";
        try{
            $data_user = $this->getSimpegSatker($nip);
            // if($user_data['group_id'] == 1){
            //     $disabled = false;
            //     $nama_satker = 'Administator';
            // }else{
                if( ($data_user['code'] == 200) && ($data_user['status'] != 'error') ){
                    $id_satker = $data_user['data']['id_satker'];
                    if($id_satker){
                        if(isset($d->satker)){
                            $j = json_decode($d->satker,true);
                            $nama_satker = $data_user['data']['nama_satker'];
                            if($j['id_satker'] ==$id_satker){
                                $disabled = false;
                            }else{
                                if($user_data['group_id'] == 1){
                                    $disabled = false;
                                    $nama_satker = 'Administator';
                                }else{
                                    $disabled = true;
                                }
                            }
                        }else{
                            if($user_data['group_id'] == 1){
                                $disabled = false;
                                $nama_satker = 'Administator';
                            }else{
                                $disabled = true;
                            }
                        }
                    }else{
                        if($user_data['group_id'] == 1){
                            $disabled = false;
                            $nama_satker = 'Administator';
                        }else{
                            $disabled = true;
                        }
                    }
                }else{
                    $disabled = true;
                    $nama_satker = "";
                }
            // }
        }catch(\Exception $e){
            $disabled = true;
            $nama_satker = "";
        }
        $this->data['disabled'] = $disabled;
        $this->data['group_id'] = $user_data['group_id'];
        $this->data['group'] = $user_data['group'];
        $this->data['nama_satker'] = $nama_satker;
        $this->data['title'] = 'Ubah Arahan Pimpinan';
        $this->data['breadcrumps'] = breadcrump_arahan_pimpinan($request->route()->getName());
        return view('arahan_pimpinan.edit_arahanPimpinan',$this->data);
    }

    public function deletearahanPimpinan(Request $request){
        $id = $request->input('id');
        if ($request->ajax()) {
            if($id){
                $id = $request->id;
                $data_request = execute_api('api/arahan/'.$id,'DELETE');
                $this->form_params['delete_id'] = $id;
                $trail['audit_menu'] = 'Arahan Pimpinan';
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
                $data_request =['status'=>'error','messages'=>'Gagal Menghapus Data Arahan Pimpinan'];
                return $data_request;
            }
        }
    }
    public function updatearahanPimpinan(Request $request){
        $id = $request->input('id');
        $token = $request->session()->get('token');
        $this->form_params = $request->except(['_token', 'id']);
        $id_satker = "";
        $auth = Auth::user();
        $nip = $auth->nip;
        $data_user = $this->getSimpegSatker($nip);
        if( ($data_user['code'] == 200) && ($data_user['status'] != 'error') ){
            $id_satker = $data_user['data']['id_satker'];
        }else{
            $id_satker = "";
        }
        if($request->tgl_kadaluarsa){
            $date = str_replace('/','-',$request->tgl_kadaluarsa);
            $this->form_params['tgl_kadaluarsa'] = date('Y-m-d',strtotime($date));
        }

        if($request->tgl_arahan){
            $date = str_replace('/','-',$request->tgl_arahan);
            $this->form_params['tgl_arahan'] = date('Y-m-d',strtotime($date));
        }
        $this->form_params['updated_by'] = $id_satker;
        $data_request = execute_api_json('/api/arahan/'.$id,'PUT',$this->form_params);

        $trail['audit_menu'] = 'Arahan Pimpinan';
        $trail['audit_event'] = 'put';
        $trail['audit_value'] = json_encode($this->form_params);
        $trail['audit_url'] = $request->url();
        $trail['audit_ip_address'] = $request->ip();
        $trail['audit_user_agent'] = $request->userAgent();
        $trail['audit_message'] = $data_request->comment;
        $trail['created_at'] = date("Y-m-d H:i:s");
        $trail['created_by'] = $request->session()->get('id');

        $qtrail = $this->inputtrail($request->session()->get('token'),$trail);

        // print($data_request);
        if( ($data_request->code == 200) && ($data_request->status != 'error') ){
            $this->kelengkapan($id);
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Arahan Pimpinan Diperbarui';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Arahan Pimpinan Gagal Diperbarui';
        }
        return back()->with('status', $this->messages);
    }

    public function printPage(Request $request){
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

        $url = 'api/arahan'.$kondisi;
        $data_request = execute_api_json($url,'GET');

        $result= [];
        if(count($data_request)>=1){
            $data = $data_request->data;
            $i =  $start_number;
            foreach($data as $key=>$d){
                $result[$key]['No'] =  $i;
                $result[$key]['Tanggal Arahan'] =  ($d->tgl_arahan ? date('d/m/Y',strtotime($d->tgl_arahan)) : '');
                $satker = $d->satker;
                $satker_name = "";
                if($satker){
                    $j = json_decode($satker,true);
                    $satker_name = $j['nama_satker'];
                }else{
                    $satker_name = "";
                }
                $result[$key]['Satker'] = $satker_name;
                $result[$key]['Tanggal Kadaluarsa'] = ( $d->tgl_kadaluarsa ? date('d/m/Y',strtotime($d->tgl_kadaluarsa)) : '');
                $result[$key]['Judul Arahan'] =  $d->judul_arahan;
                $result[$key]['Status'] = ( $d->status ? ( (trim($d->status) == 'Y' )? 'Lengkap' : 'Belum Lengkap'):'Belum Lengkap');
                $i = $i + 1;
            }
            $name = 'Arahan Pimpinan'.' '.Carbon::now()->format('Y-m-d H:i:s');

            $this->printData($result, $name);
        }

    }

    private function kelengkapan($id){
        $status_kelengkapan = true;
        try{
            $query = DB::table('arahan_pimpinan')->where('id',$id)
                      ->select('tgl_arahan','satker', 'tgl_kadaluarsa', 'judul_arahan','isi_arahan');

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
               $kelengkapan = execute_api_json('api/arahan/'.$id,'PUT',['status'=>'Y']);
            }else{
               $kelengkapan = execute_api_json('api/arahan/'.$id,'PUT',['status'=>'N']);
            }
          }catch(\Exception $e){
            $status_kelengkapan=false;
          }
    }
}

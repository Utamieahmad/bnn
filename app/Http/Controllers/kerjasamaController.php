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

class KerjasamaController extends Controller
{
    /* @author : Daniel Andi */

    public $data;
    public $selected;
    public $form_params;

    public function kerjasamaLuarnegeri(Request $request){
        $client = new Client();
        if ($request->input('page')) {
          $page = $request->input('page');
        } else {
          $page = 1;
        }

        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestLuarnegeri = $client->request('GET', $baseUrl.'/api/kerjasamaluarnegeri?page='.$page,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
        $kerjasamaluarnegeri = json_decode($requestLuarnegeri->getBody()->getContents(), true);

        $this->data['data_kerjasamaluarnegeri'] = $kerjasamaluarnegeri['data'];
		    $page = $kerjasamaluarnegeri['paginate'];
        $this->data['title'] = "kerjasamaluarnegeri";
        $this->data['token'] = $token;


        $instansi = $this->globalinstansi($request->session()->get('wilayah'), $token);

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['path'] = $request->path();
        $this->data['instansi'] = $instansi;
        $this->data['page'] = $page;
        $this->data['breadcrumps'] = breadcrumps_dir_kerjasama($request->route()->getName());
        return view('huker.kerjasama.luar_negeri.index_kerjasamaLuarnegeri',$this->data);
    }

    public function addkerjasamaLuarnegeri(Request $request){
        $client = new Client();
        $baseUrl = URL::to('/');

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        $this->data['breadcrumps'] = breadcrumps_dir_kerjasama($request->route()->getName());
        return view('huker.kerjasama.luar_negeri.add_kerjasamaLuarnegeri',$this->data);
    }

    public function editkerjasamaLuarnegeri(Request $request){
        $id = $request->id;
        $client = new Client();
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestDataDetail= $client->request('GET', $baseUrl.'/api/kerjasamaluarnegeri/'.$id,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );

        $dataDetail = json_decode($requestDataDetail->getBody()->getContents(), true);

        if ($dataDetail['data']['anggaran_id'] != '') {
           $this->data['data_anggaran'] = $this->globalGetAnggaran($token, $dataDetail['data']['anggaran_id']);
        }

        $this->data['data_detail'] = $dataDetail;
        $this->data['id'] = $id;
        $this->data['breadcrumps'] = breadcrumps_dir_kerjasama($request->route()->getName());
        return view('huker.kerjasama.luar_negeri.edit_kerjasamaLuarnegeri',$this->data);
    }

    public function inputkerjasamaLuarnegeri(Request $request){

       $baseUrl = URL::to('/');
       $token = $request->session()->get('token');

       $client = new Client();

       if ($request->input('kodesumberanggaran')=="DIPA") {
           $requestAnggaran = $client->request('POST', $baseUrl.'/api/anggaran',
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
           $anggaran = $resultAnggaran['data']['eventID'];
        } else {
          $anggaran = '';
        }

        $requestInput = $client->request('POST', $baseUrl.'/api/kerjasamaluarnegeri',
               [
                   'headers' =>
                   [
                       'Authorization' => 'Bearer '.$token
                   ],
                   'form_params' => [
                       'kodejeniskerjasama' => $request->input('kodejeniskerjasama'),
                       'tgl_pelaksana' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksana')))),
                       'tempatpelaksana' => $request->input('tempatpelaksana'),
                       'kodenegara' => $request->input('kodenegara'),
                       'materi' => $request->input('materi'),
                       'kodeanggaran' => $request->input('kodesumberanggaran'),
                       'no_sprint' => $request->input('no_sprint'),
                       'jmlh_delegasi_bnn' => $request->input('jmlh_delegasi_bnn'),
                       'jmlh_delegasi_client' => $request->input('jmlh_delegasi_client'),
                       'anggaran_id' => $anggaran,
                   ]
               ]
           );

       $result = json_decode($requestInput->getBody()->getContents(), true);

       $inputId = $result['data']['eventID'];

       if ($request->file('file_upload') != ''){
           $fileName = $inputId.'-'.$request->file('file_upload')->getClientOriginalName();
           $request->file('file_upload')->storeAs('KerjasamaLuarnegeri', $fileName);

           $requestfile = $client->request('PUT', $baseUrl.'/api/kerjasamaluarnegeri/'.$inputId,
                   [
                       'headers' =>
                       [
                           'Authorization' => 'Bearer '.$token
                       ],
                       'form_params' => [
                           'file_laporan' => $fileName,
                       ]
                   ]
               );
       }
       return redirect('huker/dir_kerjasama/kerjasama_luarnegeri/');

    }

    public function updatekerjasamaLuarnegeri(Request $request){
          $id = $request->input('id');

          $baseUrl = URL::to('/');
          $token = $request->session()->get('token');

          $client = new Client();

          $requestInput = $client->request('PUT', $baseUrl.'/api/kerjasamaluarnegeri/'.$id,
                   [
                       'headers' =>
                       [
                           'Authorization' => 'Bearer '.$token
                       ],
                       'form_params' => [
                           'kodejeniskerjasama' => $request->input('kodejeniskerjasama'),
                           'tgl_pelaksana' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksana')))),
                           'tempatpelaksana' => $request->input('tempatpelaksana'),
                           'kodenegara' => $request->input('kodenegara'),
                           'materi' => $request->input('materi'),
                           'kodeanggaran' => $request->input('kodesumberanggaran'),
                           'no_sprint' => $request->input('no_sprint'),
                           'jmlh_delegasi_bnn' => $request->input('jmlh_delegasi_bnn'),
                           'jmlh_delegasi_client' => $request->input('jmlh_delegasi_client'),
                       ]
                   ]
               );

           $result = json_decode($requestInput->getBody()->getContents(), true);

           if ($request->file('file_upload') != ''){
               $fileName = $id.'-'.$request->file('file_upload')->getClientOriginalName();
               $request->file('file_upload')->storeAs('KerjasamaLuarnegeri', $fileName);

               $requestfile = $client->request('PUT', $baseUrl.'/api/kerjasamaluarnegeri/'.$id,
                       [
                           'headers' =>
                           [
                               'Authorization' => 'Bearer '.$token
                           ],
                           'form_params' => [
                               'file_laporan' => $fileName,
                           ]
                       ]
                   );
            }

           return redirect('huker/dir_kerjasama/kerjasama_luarnegeri/');
      }

    public function kerjasamaBilateral(Request $request){

        //filter
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
                }else if(($key == 'tempatpelaksana') || ($key == 'materi')){
                    $this->selected[$key] = $value;
                    $this->selected['keyword'] = $value;

                }else if($key == 'kodejeniskerjasama'){
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
          $kata_kunci = $request->kata_kunci;
          $tgl_from = $request->tgl_from;
          $tgl_to = $request->tgl_to;
          $order = $request->order;
          $limit = $request->limit;
          $status = $request->status;
          $kodejeniskerjasama = $request->kodejeniskerjasama;

          if($tipe == 'tgl_pelaksana'){
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
          }else if($tipe == 'status'){
            $kondisi .= '&status='.$status;
            $this->selected['status'] = $status;
          }else if($tipe == 'kodejeniskerjasama'){
            $kondisi .= '&kodejeniskerjasama='.$kodejeniskerjasama;
            $this->selected['kodejeniskerjasama'] = $kodejeniskerjasama;
          }
          else{
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
        $datas = execute_api_json('api/kerjasamabilateral?'.$limit.'&'.$offset.$kondisi,'get');

        $total_item = 0;
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;
        }else{
            $this->data['data'] = [];
            $total_item = 0;
        }

        $this->data['filter'] = $this->selected;
        $this->data['kondisi'] = $kondisi;

        //end filter
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['title'] = "Pertemuan";
        $this->data['path'] = $request->path();
        $this->data['breadcrumps'] = breadcrumps_dir_kerjasama($request->route()->getName());
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));

        $this->data['delete_route'] = "delete_kerjasama_bilateral";
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

        return view('huker.kerjasama.bilateral.index_kerjasamaBilateral',$this->data);
    }

    public function addkerjasamaBilateral(Request $request){
        $client = new Client();
        $baseUrl = URL::to('/');

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        $this->data['negara'] = MainModel::getListNegara();
        $this->data['breadcrumps'] = breadcrumps_dir_kerjasama($request->route()->getName());
        return view('huker.kerjasama.bilateral.add_kerjasamaBilateral',$this->data);
    }

    public function editkerjasamaBilateral(Request $request){
        $id = $request->id;
        $client = new Client();
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestDataDetail= $client->request('GET', $baseUrl.'/api/kerjasamabilateral/'.$id,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );

        $dataDetail = json_decode($requestDataDetail->getBody()->getContents(), true);

        if ($dataDetail['data']['anggaran_id'] != '') {
           $this->data['data_anggaran'] = $this->globalGetAnggaran($token, $dataDetail['data']['anggaran_id']);
        }

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['data_detail'] = $dataDetail;
        $this->data['id'] = $id;
        $this->data['negara'] = MainModel::getListNegara();
        $this->data['breadcrumps'] = breadcrumps_dir_kerjasama($request->route()->getName());
        return view('huker.kerjasama.bilateral.edit_kerjasamaBilateral',$this->data);
    }

    public function inputkerjasamaBilateral(Request $request){

       $baseUrl = URL::to('/');
       $token = $request->session()->get('token');

       $client = new Client();

       if ($request->input('kodesumberanggaran')=="DIPA") {
           $requestAnggaran = $client->request('POST', $baseUrl.'/api/anggaran',
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
           $anggaran = $resultAnggaran['data']['eventID'];
        } else {
          $anggaran = '';
        }

        $requestInput = $client->request('POST', $baseUrl.'/api/kerjasamabilateral',
               [
                   'headers' =>
                   [
                       'Authorization' => 'Bearer '.$token
                   ],
                   'form_params' => [
                       'kodejeniskerjasama' => $request->input('kodejeniskerjasama'),
                       'tgl_pelaksana' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksana')))),
                       'tempatpelaksana' => $request->input('tempatpelaksana'),
                       'lembaga_penyelenggara' => $request->input('lembaga_penyelenggara'),
                       'kodenegara' => $request->input('kodenegara'),
                       'institusi_penyelenggara' => $request->input('institusi_penyelenggara'),
                       'materi' => $request->input('materi'),
                       'kodeanggaran' => $request->input('kodesumberanggaran'),
                       'no_sprint' => $request->input('no_sprint'),
                       'jmlh_delegasi_bnn' => $request->input('jmlh_delegasi_bnn'),
                       'jmlh_delegasi_client' => $request->input('jmlh_delegasi_client'),
                       'kodenegara_mitra' => $request->input('kodenegara_mitra'),
                       'anggaran_id' => $anggaran,
                   ]
               ]
           );

       $result = json_decode($requestInput->getBody()->getContents(), true);

       $this->form_params = array('kodejeniskerjasama' => $request->input('kodejeniskerjasama'),
       'tgl_pelaksana' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksana')))),
       'tempatpelaksana' => $request->input('tempatpelaksana'),
       'lembaga_penyelenggara' => $request->input('lembaga_penyelenggara'),
       'kodenegara' => $request->input('kodenegara'),
       'institusi_penyelenggara' => $request->input('institusi_penyelenggara'),
       'materi' => $request->input('materi'),
       'kodeanggaran' => $request->input('kodesumberanggaran'),
       'no_sprint' => $request->input('no_sprint'),
       'jmlh_delegasi_bnn' => $request->input('jmlh_delegasi_bnn'),
       'jmlh_delegasi_client' => $request->input('jmlh_delegasi_client'),
       'kodenegara_mitra' => $request->input('kodenegara_mitra'),
       'anggaran_id' => $anggaran);

       $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Kerjasama - Pertemuan';
       $trail['audit_event'] = 'post';
       $trail['audit_value'] = json_encode($this->form_params);
       $trail['audit_url'] = $request->url();
       $trail['audit_ip_address'] = $request->ip();
       $trail['audit_user_agent'] = $request->userAgent();
       $trail['audit_message'] = $result['comment'];
       $trail['created_at'] = date("Y-m-d H:i:s");
       $trail['created_by'] = $request->session()->get('id');

       $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


       $inputId = $result['data']['eventID'];

       if ($request->file('file_upload') != ''){
           $fileName = $inputId.'-'.$request->file('file_upload')->getClientOriginalName();
           $request->file('file_upload')->storeAs('KerjasamaPerjanjianBilateral', $fileName);

           $requestfile = $client->request('PUT', $baseUrl.'/api/kerjasamabilateral/'.$inputId,
                   [
                       'headers' =>
                       [
                           'Authorization' => 'Bearer '.$token
                       ],
                       'form_params' => [
                           'file_laporan' => $fileName,
                       ]
                   ]
               );
       }

       $this->kelengkapan_kerjasamaBilateral($inputId);

        if(($result['code'] == 200)&& ($result['status'] != "error") ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Kegiatan Kerjasama Perjanjian Bilateral Berhasil Disimpan';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Kegiatan Kerjasama Perjanjian Bilateral Gagal Disimpan';
        }

       return redirect('huker/dir_kerjasama/edit_kerjasama_bilateral/' . $inputId)->with('status', $this->messages);

    }

    public function updatekerjasamaBilateral(Request $request){
          $id = $request->input('id');

          $baseUrl = URL::to('/');
          $token = $request->session()->get('token');

          $client = new Client();

           if ($request->input('kodesumberanggaran')=="DIPA") {
               $requestAnggaran = $client->request('POST', $baseUrl.'/api/anggaran',
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
               $anggaran = $resultAnggaran['data']['eventID'];
            } else {
              $anggaran = '';
            }

          $requestInput = $client->request('PUT', $baseUrl.'/api/kerjasamabilateral/'.$id,
                   [
                       'headers' =>
                       [
                           'Authorization' => 'Bearer '.$token
                       ],
                       'form_params' => [
                           'kodejeniskerjasama' => $request->input('kodejeniskerjasama'),
                           'tgl_pelaksana' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksana')))),
                           'tempatpelaksana' => $request->input('tempatpelaksana'),
                           'lembaga_penyelenggara' => $request->input('lembaga_penyelenggara'),
                           'kodenegara' => $request->input('kodenegara'),
                           'institusi_penyelenggara' => $request->input('institusi_penyelenggara'),
                           'materi' => $request->input('materi'),
                           'kodeanggaran' => $request->input('kodesumberanggaran'),
                           'no_sprint' => $request->input('no_sprint'),
                           'jmlh_delegasi_bnn' => $request->input('jmlh_delegasi_bnn'),
                           'jmlh_delegasi_client' => $request->input('jmlh_delegasi_client'),
                           'kodenegara_mitra' => $request->input('kodenegara_mitra'),
                           'anggaran_id' => $anggaran,
                       ]
                   ]
               );

           $result = json_decode($requestInput->getBody()->getContents(), true);

           $this->form_params = array('kodejeniskerjasama' => $request->input('kodejeniskerjasama'),
           'tgl_pelaksana' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksana')))),
           'tempatpelaksana' => $request->input('tempatpelaksana'),
           'lembaga_penyelenggara' => $request->input('lembaga_penyelenggara'),
           'kodenegara' => $request->input('kodenegara'),
           'institusi_penyelenggara' => $request->input('institusi_penyelenggara'),
           'materi' => $request->input('materi'),
           'kodeanggaran' => $request->input('kodesumberanggaran'),
           'no_sprint' => $request->input('no_sprint'),
           'jmlh_delegasi_bnn' => $request->input('jmlh_delegasi_bnn'),
           'jmlh_delegasi_client' => $request->input('jmlh_delegasi_client'),
           'kodenegara_mitra' => $request->input('kodenegara_mitra'),
           'anggaran_id' => $anggaran);

           $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Kerjasama - Pertemuan';
           $trail['audit_event'] = 'put';
           $trail['audit_value'] = json_encode($this->form_params);
           $trail['audit_url'] = $request->url();
           $trail['audit_ip_address'] = $request->ip();
           $trail['audit_user_agent'] = $request->userAgent();
           $trail['audit_message'] = $result['comment'];
           $trail['created_at'] = date("Y-m-d H:i:s");
           $trail['created_by'] = $request->session()->get('id');

           $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


           if ($request->file('file_upload') != ''){
               $fileName = $id.'-'.$request->file('file_upload')->getClientOriginalName();
               $request->file('file_upload')->storeAs('KerjasamaPerjanjianBilateral', $fileName);

               $requestfile = $client->request('PUT', $baseUrl.'/api/kerjasamabilateral/'.$id,
                       [
                           'headers' =>
                           [
                               'Authorization' => 'Bearer '.$token
                           ],
                           'form_params' => [
                               'file_laporan' => $fileName,
                           ]
                       ]
                   );
            }

            $this->kelengkapan_kerjasamaBilateral($id);

        if(($result['code'] == 200)&& ($result['status'] != "error") ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Kegiatan Kerjasama Perjanjian Bilateral Berhasil Diperbarui';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Kegiatan Kerjasama Perjanjian Bilateral Gagal Diperbarui';
        }

        return back()->with('status', $this->messages);
      }

    public function deletekerjasamaBilateral(Request $request){
      $id = $request->input('id');
      if($id){
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/kerjasamabilateral/'.$id,'DELETE');
            $this->form_params['delete_id'] = $id;
            $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Kerjasama - Pertemuan';
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
      }else{
        $data_request = ['status'=>'error','message'=>'Data Kegiatan Perjanjian Bilateral Gagal Dihapus'];
        return $data_request;
      }
    }

    public function deletekerjasamaKesepahaman(Request $request){
      $id = $request->input('id');
      if($id){
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/kerjasamanota/'.$id,'DELETE');
            $this->form_params['delete_id'] = $id;
            $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Kerjasama - Nota Kesepahaman';
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
      }else{
        $data_request = ['status'=>'error','message'=>'Data Kegiatan Kerja Sama Nota Kesepahaman Gagal Dihapus'];
        return $data_request;
      }
    }

      public function deletekerjasamaLainnya(Request $request){
      $id = $request->input('id');
      if($id){
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/kerjasamalainnya/'.$id,'DELETE');
            $this->form_params['delete_id'] = $id;
            $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Kerjasama - Kerjasama Lainnya';
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
      }else{
        $data_request = ['status'=>'error','message'=>'Data Kegiatan Kerja Sama Lainnya Gagal Dihapus'];
        return $data_request;
      }
    }

    public function deletekerjasamaMonev(Request $request){
      $id = $request->input('id');
      if($id){
        if ($request->ajax()) {
            $id = $request->id;
            $data_request = execute_api('api/kerjasamamonev/'.$id,'DELETE');
            $this->form_params['delete_id'] = $id;
            $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Kerjasama - Monitoring dan Evaluasi Pelaksanaan Kerjasama';
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
      }else{
        $data_request = ['status'=>'error','message'=>'Data Kegiatan Perjanjian Monev Gagal Dihapus'];
        return $data_request;
      }
    }

    public function printBilateral(Request $request){
        $client = new Client();
        $token = $request->session()->get('token');
        $baseUrl = URL::to('/');

        $get = $request->all();
        $kondisi = "";
        if(count($get)>0){
          foreach($get as $key=>$val){
            $kondisi .= $key.'='.$val.'&';
          }
          $kondisi = rtrim($kondisi,'&');
        }

        $requestPrintData = $client->request('GET', $baseUrl.'/api/kerjasamabilateral?'.$kondisi,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );

        $PrintData = json_decode($requestPrintData->getBody()->getContents(), true);
        // dd($pemusnahanladang);
        $DataArray = [];

        $i = 1;
        foreach ($PrintData['data'] as $key => $value) {
          $DataArray[$key]['No'] = $i;
          $DataArray[$key]['Jenis Kerja Sama'] = $value['kodejeniskerjasama'];
          $DataArray[$key]['Tanggal Pelaksanaan'] = $value['tgl_pelaksana'];
          $DataArray[$key]['Tempat Pelaksanaan'] = $value['tempatpelaksana'];
          $DataArray[$key]['Nama Kegiatan'] = $value['materi'];
          $DataArray[$key]['Status'] = ($value['status'] == 'Y') ? "Lengkap" : "Tidak Lengkap";
          // $meta = json_decode($value['meta_instansi'],true);
        //   if(count($meta)){
        //     for($j = 0 ; $j < count($meta); $j++){
        //         $InstansiArray[$key]['Instansi'][$j] = $meta[$j]['list_nama_instansi'].'('.$meta[$j]['list_jumlah_peserta'].')';
        //     }
        //     $DataArray[$key]['Instansi/Peserta'] = implode("\n", $InstansiArray[$key]['Instansi']);
        //   } else {
        //     $DataArray[$key]['Instansi/Peserta'] = '-';
        //   }
        //   $DataArray[$key]['Sumber Anggaran'] = $value['kodesumberanggaran'];
          $i = $i +1;
        }
         //dd($DataArray);
        $data = $DataArray;
        $name = 'Data Pertemuan '.Carbon::now()->format('Y-m-d H:i:s');
        $this->printData($data, $name);
    }

    public function printKesepahaman(Request $request){
        $client = new Client();
        $token = $request->session()->get('token');
        $baseUrl = URL::to('/');

        $get = $request->all();
        $kondisi = "";
        if(count($get)>0){
          foreach($get as $key=>$val){
            $kondisi .= $key.'='.$val.'&';
          }
          $kondisi = rtrim($kondisi,'&');
        }

        $requestPrintData = $client->request('GET', $baseUrl.'/api/kerjasamanota?'.$kondisi,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
        $PrintData = json_decode($requestPrintData->getBody()->getContents(), true);
        // dd($pemusnahanladang);
        $DataArray = [];

        $i = 1;
        foreach ($PrintData['data'] as $key => $value) {
          $DataArray[$key]['No'] = $i;
          $DataArray[$key]['Jenis Kerja Sama'] = $value['jenis_kerjasama'];
          $DataArray[$key]['Instansi Mitra'] = $value['nama_instansi'];
          $DataArray[$key]['Nomor MOU/PKS'] = $value['nomor_sprint'];
          $DataArray[$key]['Tanggal TTD'] = date('d-m-Y', strtotime($value['tgl_ttd']));
          $DataArray[$key]['Tanggal Berakhir'] = date('d-m-Y', strtotime($value['tgl_berakhir']));
          $DataArray[$key]['Status'] = ($value['status'] == 'Y') ? "Lengkap" : "Tidak Lengkap";

          $i = $i +1;
        }

        $data = $DataArray;
        $name = 'Data Kegiatan Perjanjian Nota Kesepahaman '.Carbon::now()->format('Y-m-d H:i:s');
        $this->printData($data, $name);
    }

    public function printLainnya(Request $request){
        $client = new Client();
        $token = $request->session()->get('token');
        $baseUrl = URL::to('/');

        $get = $request->all();
        $kondisi = "";
        if(count($get)>0){
          foreach($get as $key=>$val){
            $kondisi .= $key.'='.$val.'&';
          }
          $kondisi = rtrim($kondisi,'&');
        }

        $requestPrintData = $client->request('GET', $baseUrl.'/api/kerjasamalainnya?'.$kondisi,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
        $PrintData = json_decode($requestPrintData->getBody()->getContents(), true);
        // dd($pemusnahanladang);
        $DataArray = [];

        $i = 1;
        foreach ($PrintData['data'] as $key => $value) {
          $DataArray[$key]['No'] = $i;
          $DataArray[$key]['Nama Kegiatan'] = $value['nm_kegiatan'];
          $DataArray[$key]['Tanggal Pelaksanaan'] = date('d-m-Y', strtotime($value['tgl_pelaksanaan']));
          $DataArray[$key]['Tempat Pelaksanaan'] = $value['tempat_pelaksanaan'];
          $DataArray[$key]['Status'] = ($value['status'] == 'Y') ? "Lengkap" : "Tidak Lengkap";

          $i = $i +1;
        }

        $data = $DataArray;
        $name = 'Data Kegiatan Kerjasama Lainnya '.Carbon::now()->format('Y-m-d H:i:s');
        $this->printData($data, $name);
    }

    public function printMonev(Request $request){
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
        }

        $requestPrintData = $client->request('GET', $baseUrl.'/api/kerjasamamonev?'.$kondisi,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
        $PrintData = json_decode($requestPrintData->getBody()->getContents(), true);
        // dd($pemusnahanladang);
        $DataArray = [];

        $i = 1;
        foreach ($PrintData['data'] as $key => $value) {
          $DataArray[$key]['No'] = $i;
          $DataArray[$key]['No. Surat Perintah'] = $value['nomor_sprint'];
          $DataArray[$key]['Nama Kegiatan'] = $value['nama_kegiatan'];
          $DataArray[$key]['Tanggal Pelaksanaan'] = date('d-m-Y', strtotime($value['tanggal_pelaksanaan']));
          $DataArray[$key]['Sumber Anggaran'] = $value['kodesumberanggaran'];
          $DataArray[$key]['Status'] = ($value['status'] == 'Y') ? "Lengkap" : "Tidak Lengkap";

          $i = $i +1;
        }

        $data = $DataArray;
        $name = 'Data Kegiatan Kerja Sama Monev '.Carbon::now()->format('Y-m-d H:i:s');
        $this->printData($data, $name);
    }

    public function kerjasamaKesepemahaman(Request $request){
        //filter
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
                }else if(($key == 'nama_instansi') || ($key == 'nomor_sprint')){
                    $this->selected[$key] = $value;
                    $this->selected['keyword'] = $value;

                }else if($key == 'jenis_kerjasama'){
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
          $kata_kunci = $request->kata_kunci;
          $tgl_from_ttd = $request->tgl_from_ttd;
          $tgl_to_ttd = $request->tgl_to_ttd;
          $tgl_from_berakhir = $request->tgl_from_berakhir;
          $tgl_to_berakhir = $request->tgl_to_berakhir;
          $order = $request->order;
          $limit = $request->limit;
          $status = $request->status;
          $jenis_kerjasama = $request->jenis_kerjasama;

          if($tipe == 'tgl_ttd'){
            if($tgl_from_ttd){
              $date = date('Y-m-d',strtotime(str_replace('/', '-', $tgl_from_ttd)));
              $kondisi .= '&tgl_from_ttd='.$date;
              $this->selected['tgl_from_ttd'] = $tgl_from_ttd;
            }else{
                $kondisi .='';
            }
            if($tgl_to_ttd){
              $date = date('Y-m-d',strtotime(str_replace('/', '-', $tgl_to_ttd)));
              $kondisi .= '&tgl_to_ttd='.$date;
              $this->selected['tgl_to_ttd'] = $tgl_to_ttd;
            }else{
              $kondisi .='';
            }
          }else if($tipe == 'tgl_berakhir'){
            if($tgl_from_berakhir){
              $date = date('Y-m-d',strtotime(str_replace('/', '-', $tgl_from_berakhir)));
              $kondisi .= '&tgl_from_berakhir='.$date;
              $this->selected['tgl_from_berakhir'] = $tgl_from_berakhir;
            }else{
                $kondisi .='';
            }
            if($tgl_to_berakhir){
              $date = date('Y-m-d',strtotime(str_replace('/', '-', $tgl_to_berakhir)));
              $kondisi .= '&tgl_to_berakhir='.$date;
              $this->selected['tgl_to_berakhir'] = $tgl_to_ttd;
            }else{
              $kondisi .='';
            }
          }else if($tipe == 'status'){
            $kondisi .= '&status='.$status;
            $this->selected['status'] = $status;
          }else if($tipe == 'jenis_kerjasama'){
            $kondisi .= '&jenis_kerjasama='.$jenis_kerjasama;
            $this->selected['jenis_kerjasama'] = $jenis_kerjasama;
          }
          else{
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
        $datas = execute_api_json('api/kerjasamanota?'.$limit.'&'.$offset.$kondisi,'get');

        $total_item = 0;
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;
        }else{
            $this->data['data'] = [];
            $total_item = 0;
        }

        $this->data['filter'] = $this->selected;
        $this->data['kondisi'] = $kondisi;

        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;

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

        //end filter

        $client = new Client();
        if ($request->input('page')) {
          $page = $request->input('page');
        } else {
          $page = 1;
        }

        $this->data['title'] = "Kerjasama Nota Kesepahaman";
        $this->data['delete_route'] = "delete_kerjasama_kesepahaman";

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['path'] = $request->path();
        $this->data['page'] = $page;
        $this->data['breadcrumps'] = breadcrumps_dir_kerjasama($request->route()->getName());
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );
        return view('huker.kerjasama.nota_kesepemahaman.index_kerjasamaKesepemahaman',$this->data);
    }

    public function addkerjasamaKesepemahaman(Request $request){
        $client = new Client();
        $baseUrl = URL::to('/');

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        $this->data['breadcrumps'] = breadcrumps_dir_kerjasama($request->route()->getName());
        return view('huker.kerjasama.nota_kesepemahaman.add_kerjasamaKesepemahaman',$this->data);
    }

    public function editkerjasamaKesepemahaman(Request $request){
        $id = $request->id;
        $client = new Client();
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));

        $requestDataDetail= $client->request('GET', $baseUrl.'/api/kerjasamanota/'.$id,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );

        $dataDetail = json_decode($requestDataDetail->getBody()->getContents(), true);

        if ($dataDetail['data']['anggaran_id'] != '') {
           $this->data['data_anggaran'] = $this->globalGetAnggaran($token, $dataDetail['data']['anggaran_id']);
        }

        $this->data['data_detail'] = $dataDetail;
        $this->data['id'] = $id;
        $this->data['breadcrumps'] = breadcrumps_dir_kerjasama($request->route()->getName());
        return view('huker.kerjasama.nota_kesepemahaman.edit_kerjasamaKesepemahaman',$this->data);
    }


    public function inputkerjasamaKesepemahaman(Request $request){

       $baseUrl = URL::to('/');
       $token = $request->session()->get('token');

       $client = new Client();

       if ($request->input('kodesumberanggaran')=="DIPA") {
           $requestAnggaran = $client->request('POST', $baseUrl.'/api/anggaran',
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
           $anggaran = $resultAnggaran['data']['eventID'];
        } else {
          $anggaran = '';
        }

        $requestInput = $client->request('POST', $baseUrl.'/api/kerjasamanota',
               [
                   'headers' =>
                   [
                       'Authorization' => 'Bearer '.$token
                   ],
                   'form_params' => [
                       'jenis_kerjasama' => $request->input('jenis_kerjasama'),
                       'nama_instansi' => $request->input('nama_instansi'),
                       'nomor_sprint' => $request->input('nomor_sprint'),
                       'tgl_ttd' => ($request->input('tgl_ttd') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_ttd')))) : '',
                       'tgl_berakhir' => ($request->input('tgl_berakhir') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_berakhir')))) : '',
                       'tema' => $request->input('tema'),
                       'meta_ruanglingkup_kerjasama' => json_encode($request->input('meta_ruanglingkup_kerjasama')),
                       'meta_unitkerja_pelaksana' => json_encode($request->input('meta_unitkerja_pelaksana')),
                       'kode_sumberanggaran' => $request->input('kodesumberanggaran'),
                       'kodejenisinstansi' => $request->input('kodejenisinstansi'),
                       'tempat_ttd' => $request->input('tempat_ttd'),
                       'anggaran_id' => $anggaran,
                   ]
               ]
           );

       $result = json_decode($requestInput->getBody()->getContents(), true);

       $this->form_params = array('jenis_kerjasama' => $request->input('jenis_kerjasama'),
       'nama_instansi' => $request->input('nama_instansi'),
       'nomor_sprint' => $request->input('nomor_sprint'),
       'tgl_ttd' => ($request->input('tgl_ttd') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_ttd')))) : '',
       'tgl_berakhir' => ($request->input('tgl_berakhir') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_berakhir')))) : '',
       'tema' => $request->input('tema'),
       'meta_ruanglingkup_kerjasama' => json_encode($request->input('meta_ruanglingkup_kerjasama')),
       'meta_unitkerja_pelaksana' => json_encode($request->input('meta_unitkerja_pelaksana')),
       'kode_sumberanggaran' => $request->input('kodesumberanggaran'),
       'kodejenisinstansi' => $request->input('kodejenisinstansi'),
       'tempat_ttd' => $request->input('tempat_ttd'),
       'anggaran_id' => $anggaran);

       $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Kerjasama - Nota Kesepahaman';
       $trail['audit_event'] = 'post';
       $trail['audit_value'] = json_encode($this->form_params);
       $trail['audit_url'] = $request->url();
       $trail['audit_ip_address'] = $request->ip();
       $trail['audit_user_agent'] = $request->userAgent();
       $trail['audit_message'] = $result['comment'];
       $trail['created_at'] = date("Y-m-d H:i:s");
       $trail['created_by'] = $request->session()->get('id');

       $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


       $inputId = $result['data']['eventID'];

       if ($request->file('file_upload') != ''){
           $fileName = $inputId.'-'.$request->file('file_upload')->getClientOriginalName();
           $request->file('file_upload')->storeAs('KerjasamaNotakesepahaman', $fileName);

           $requestfile = $client->request('PUT', $baseUrl.'/api/kerjasamanota/'.$inputId,
                   [
                       'headers' =>
                       [
                           'Authorization' => 'Bearer '.$token
                       ],
                       'form_params' => [
                           'file_upload' => $fileName,
                       ]
                   ]
               );
       }

       $this->kelengkapan_kerjasamaKesepemahaman($inputId);

        if(($result['code'] == 200)&& ($result['status'] != "error") ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Kegiatan Kerjasama Nota Kesepahaman Berhasil Disimpan';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Kegiatan Kerjasama Nota Kesepahaman Gagal Disimpan';
        }

       return redirect('huker/dir_kerjasama/edit_kerjasama_kesepemahaman/' . $inputId)->with('status', $this->messages);

    }

    public function updatekerjasamaKesepemahaman(Request $request){
          $id = $request->input('id');

          $baseUrl = URL::to('/');
          $token = $request->session()->get('token');

          $client = new Client();

       if ($request->input('kodesumberanggaran')=="DIPA") {
           $requestAnggaran = $client->request('POST', $baseUrl.'/api/anggaran',
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
           $anggaran = $resultAnggaran['data']['eventID'];
        } else {
          $anggaran = '';
        }

          $requestInput = $client->request('PUT', $baseUrl.'/api/kerjasamanota/'.$id,
                   [
                       'headers' =>
                       [
                           'Authorization' => 'Bearer '.$token
                       ],
                       'form_params' => [
                           'jenis_kerjasama' => $request->input('jenis_kerjasama'),
                           'nama_instansi' => $request->input('nama_instansi'),
                           'nomor_sprint' => $request->input('nomor_sprint'),
                          'tgl_ttd' => ($request->input('tgl_ttd') != '') ? date('Y-m-d', strtotime(  str_replace('/', '-', $request->input('tgl_ttd')))) : '',
                          'tgl_berakhir' => ($request->input('tgl_berakhir') != '') ? date('Y-m-d',   strtotime(str_replace('/', '-', $request->input('tgl_berakhir')))) : '',
                           'tema' => $request->input('tema'),
                           'meta_ruanglingkup_kerjasama' => json_encode($request->input('meta_ruanglingkup_kerjasama')),
                           'meta_unitkerja_pelaksana' => json_encode($request->input('meta_unitkerja_pelaksana')),
                           'kode_sumberanggaran' => $request->input('kodesumberanggaran'),
                           'kodejenisinstansi' => $request->input('kodejenisinstansi'),
                           'tempat_ttd' => $request->input('tempat_ttd'),
                           'anggaran_id' => $anggaran,
                       ]
                   ]
               );

           $result = json_decode($requestInput->getBody()->getContents(), true);

           $this->form_params = array('jenis_kerjasama' => $request->input('jenis_kerjasama'),
           'nama_instansi' => $request->input('nama_instansi'),
           'nomor_sprint' => $request->input('nomor_sprint'),
          'tgl_ttd' => ($request->input('tgl_ttd') != '') ? date('Y-m-d', strtotime(  str_replace('/', '-', $request->input('tgl_ttd')))) : '',
          'tgl_berakhir' => ($request->input('tgl_berakhir') != '') ? date('Y-m-d',   strtotime(str_replace('/', '-', $request->input('tgl_berakhir')))) : '',
           'tema' => $request->input('tema'),
           'meta_ruanglingkup_kerjasama' => json_encode($request->input('meta_ruanglingkup_kerjasama')),
           'meta_unitkerja_pelaksana' => json_encode($request->input('meta_unitkerja_pelaksana')),
           'kode_sumberanggaran' => $request->input('kodesumberanggaran'),
           'kodejenisinstansi' => $request->input('kodejenisinstansi'),
           'tempat_ttd' => $request->input('tempat_ttd'),
           'anggaran_id' => $anggaran);

           $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Kerjasama - Nota Kesepahaman';
           $trail['audit_event'] = 'put';
           $trail['audit_value'] = json_encode($this->form_params);
           $trail['audit_url'] = $request->url();
           $trail['audit_ip_address'] = $request->ip();
           $trail['audit_user_agent'] = $request->userAgent();
           $trail['audit_message'] = $result['comment'];
           $trail['created_at'] = date("Y-m-d H:i:s");
           $trail['created_by'] = $request->session()->get('id');

           $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


           if ($request->file('file_upload') != ''){
               $fileName = $id.'-'.$request->file('file_upload')->getClientOriginalName();
               $request->file('file_upload')->storeAs('KerjasamaNotakesepahaman', $fileName);

               $requestfile = $client->request('PUT', $baseUrl.'/api/kerjasamanota/'.$id,
                       [
                           'headers' =>
                           [
                               'Authorization' => 'Bearer '.$token
                           ],
                           'form_params' => [
                               'file_upload' => $fileName,
                           ]
                       ]
                   );
            }

            $this->kelengkapan_kerjasamaKesepemahaman($id);

        if(($result['code'] == 200)&& ($result['status'] != "error") ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Kegiatan Kerjasama Nota Kesepahaman Berhasil Diperbarui';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Kegiatan Kerjasama Nota Kesepahaman Gagal Diperbarui';
        }

        return back()->with('status', $this->messages);
      }

    public function kerjasamaSosialisasi(Request $request){
        $client = new Client();
        if ($request->input('page')) {
          $page = $request->input('page');
        } else {
          $page = 1;
        }

        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestsosialisasi = $client->request('GET', $baseUrl.'/api/kerjasamasosialisasi?page='.$page,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
        $sosialisasi = json_decode($requestsosialisasi->getBody()->getContents(), true);

        $this->data['data_sosialisasi'] = $sosialisasi['data'];
        $page = $sosialisasi['paginate'];
        $this->data['title'] = "Kegiatan Sosialisasi";
        $this->data['token'] = $token;


        $instansi = $this->globalinstansi($request->session()->get('wilayah'), $token);

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['path'] = $request->path();
        $this->data['instansi'] = $instansi;
        $this->data['page'] = $page;
        $this->data['breadcrumps'] = breadcrumps_dir_kerjasama($request->route()->getName());
        return view('huker.kerjasama.sosialisasi.index_kerjasamaSosialisasi',$this->data);
    }

    public function addkerjasamaSosialisasi(Request $request){
        $client = new Client();
        $baseUrl = URL::to('/');

        $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
        $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $propkab;

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        $this->data['breadcrumps'] = breadcrumps_dir_kerjasama($request->route()->getName());
        return view('huker.kerjasama.sosialisasi.add_kerjasamaSosialisasi',$this->data);
    }

    public function editkerjasamaSosialisasi(Request $request){
        $id = $request->id;
        $client = new Client();
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestDataDetail= $client->request('GET', $baseUrl.'/api/kerjasamasosialisasi/'.$id,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );

        $dataDetail = json_decode($requestDataDetail->getBody()->getContents(), true);

        if ($dataDetail['data']['anggaran_id'] != '') {
           $this->data['data_anggaran'] = $this->globalGetAnggaran($token, $dataDetail['data']['anggaran_id']);
        }

        $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
        $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $propkab;

        $this->data['data_detail'] = $dataDetail;
        $this->data['id'] = $id;
        $this->data['breadcrumps'] = breadcrumps_dir_kerjasama($request->route()->getName());
        return view('huker.kerjasama.sosialisasi.edit_kerjasamaSosialisasi',$this->data);
    }

    public function inputkerjasamaSosialisasi(Request $request){

       $baseUrl = URL::to('/');
       $token = $request->session()->get('token');

       $client = new Client();

       if ($request->input('kodesumberanggaran')=="DIPA") {
           $requestAnggaran = $client->request('POST', $baseUrl.'/api/anggaran',
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
           $anggaran = $resultAnggaran['data']['eventID'];
        } else {
          $anggaran = '';
        }

        $jumlah_instansi = count($request->input('meta_instansi'));
        $peserta = 0;
        if ($jumlah_instansi > 0) {
            foreach ($request->input('meta_instansi') as $c1 => $r1) {
                $peserta = $peserta + $r1['list_jumlah_peserta'];
            }
        }

        $requestInput = $client->request('POST', $baseUrl.'/api/kerjasamasosialisasi',
               [
                   'headers' =>
                   [
                       'Authorization' => 'Bearer '.$token
                   ],
                   'form_params' => [
                       'nomor_sprint' => $request->input('nomor_sprint'),
                       'tanggal_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal_pelaksanaan')))),
                       'lokasi_kegiatan' => $request->input('lokasi_kegiatan'),
                       'jumlah_instansi' => $jumlah_instansi,
                       'jumlah_peserta' => $peserta,
                       'lokasi_kegiatan_idkabkota' => $request->input('lokasi_kegiatan_idkabkota'),
                       'materi' => $request->input('materi'),
                       'narasumber' => $request->input('narasumber'),
                       'meta_instansi' => json_encode($request->input('meta_instansi')),
                       'kodesumberanggaran' => $request->input('kodesumberanggaran'),
                       'anggaran_id' => $anggaran,
                   ]
               ]
           );

       $result = json_decode($requestInput->getBody()->getContents(), true);

       $inputId = $result['data']['eventID'];

       if ($request->file('file_upload') != ''){
           $fileName = $inputId.'-'.$request->file('file_upload')->getClientOriginalName();
           $request->file('file_upload')->storeAs('KerjasamaSosialisasi', $fileName);

           $requestfile = $client->request('PUT', $baseUrl.'/api/kerjasamasosialisasi/'.$inputId,
                   [
                       'headers' =>
                       [
                           'Authorization' => 'Bearer '.$token
                       ],
                       'form_params' => [
                           'file_upload' => $fileName,
                       ]
                   ]
               );
       }
       return redirect('huker/dir_kerjasama/kerjasama_sosialisasi/');

    }

    public function updatekerjasamaSosialisasi(Request $request){
          $id = $request->input('id');

          $baseUrl = URL::to('/');
          $token = $request->session()->get('token');

          $client = new Client();

          $jumlah_instansi = count($request->input('meta_instansi'));
          $peserta = 0;
          if ($jumlah_instansi > 0) {
              foreach ($request->input('meta_instansi') as $c1 => $r1) {
                  $peserta = $peserta + $r1['list_jumlah_peserta'];
              }
          }

          $requestInput = $client->request('PUT', $baseUrl.'/api/kerjasamasosialisasi/'.$id,
                   [
                       'headers' =>
                       [
                           'Authorization' => 'Bearer '.$token
                       ],
                       'form_params' => [
                           'nomor_sprint' => $request->input('nomor_sprint'),
                           'tanggal_pelaksanaan' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal_pelaksanaan')))),
                           'lokasi_kegiatan' => $request->input('lokasi_kegiatan'),
                           'jumlah_instansi' => $jumlah_instansi,
                           'jumlah_peserta' => $peserta,
                           'lokasi_kegiatan_idkabkota' => $request->input('lokasi_kegiatan_idkabkota'),
                           'materi' => $request->input('materi'),
                           'narasumber' => $request->input('narasumber'),
                           'meta_instansi' => json_encode($request->input('meta_instansi')),
                           'kodesumberanggaran' => $request->input('kodesumberanggaran'),
                       ]
                   ]
               );

           $result = json_decode($requestInput->getBody()->getContents(), true);

           if ($request->file('file_upload') != ''){
               $fileName = $id.'-'.$request->file('file_upload')->getClientOriginalName();
               $request->file('file_upload')->storeAs('KerjasamaSosialisasi', $fileName);

               $requestfile = $client->request('PUT', $baseUrl.'/api/kerjasamasosialisasi/'.$id,
                       [
                           'headers' =>
                           [
                               'Authorization' => 'Bearer '.$token
                           ],
                           'form_params' => [
                               'file_upload' => $fileName,
                           ]
                       ]
                   );
            }

           return redirect('huker/dir_kerjasama/kerjasama_sosialisasi/');
      }

    public function kerjasamaMonev(Request $request){
        //filter
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
                }else if(($key == 'nomor_sprint') || ($key == 'nama_kegiatan')){
                    $this->selected[$key] = $value;
                    $this->selected['keyword'] = $value;
                }else if($key == 'kodesumberanggaran'){
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
          $kata_kunci = $request->kata_kunci;
          $tgl_from = $request->tgl_from;
          $tgl_to = $request->tgl_to;
          $order = $request->order;
          $limit = $request->limit;
          $kodesumberanggaran = $request->kodesumberanggaran;
          $status = $request->status;

          if($tipe == 'tanggal_pelaksanaan'){
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
          }else if($tipe == 'status'){
            $kondisi .= '&status='.$status;
            $this->selected['status'] = $status;
          }else if($tipe == 'kodesumberanggaran'){
            $kondisi .= '&kodesumberanggaran='.$kodesumberanggaran;
            $this->selected['kodesumberanggaran'] = $kodesumberanggaran;
          }
          else{
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
        $datas = execute_api_json('api/kerjasamamonev?'.$limit.'&'.$offset.$kondisi,'get');

        $total_item = 0;
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;

        }else{
            $this->data['data'] = [];
            $total_item = 0;
        }

        $this->data['filter'] = $this->selected;
        $this->data['kondisi'] = $kondisi;

        //end filter
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['title'] = "Kerja Sama Monitoring dan Evaluasi";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));

        $this->data['delete_route'] = "delete_kerjasama_monev";
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
        $this->data['breadcrumps'] = breadcrumps_dir_kerjasama($request->route()->getName());
        return view('huker.kerjasama.monev_kerjasama.index_kerjasamaMonev',$this->data);
    }

    public function addkerjasamaMonev(Request $request){
        $client = new Client();
        $baseUrl = URL::to('/');

        $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
        $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $propkab;

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        $this->data['breadcrumps'] = breadcrumps_dir_kerjasama($request->route()->getName());
        return view('huker.kerjasama.monev_kerjasama.add_kerjasamaMonev',$this->data);
    }

    public function editkerjasamaMonev(Request $request){
        $id = $request->id;
        $client = new Client();
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestDataDetail= $client->request('GET', $baseUrl.'/api/kerjasamamonev/'.$id,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );

        $dataDetail = json_decode($requestDataDetail->getBody()->getContents(), true);

        if ($dataDetail['data']['anggaran_id'] != '') {
           $this->data['data_anggaran'] = $this->globalGetAnggaran($token, $dataDetail['data']['anggaran_id']);
        }

        $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
        $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $propkab;

        $this->data['data_detail'] = $dataDetail;
        $this->data['id'] = $id;
        $this->data['breadcrumps'] = breadcrumps_dir_kerjasama($request->route()->getName());
        return view('huker.kerjasama.monev_kerjasama.edit_kerjasamaMonev',$this->data);
    }

    public function inputkerjasamaMonev(Request $request){

       $baseUrl = URL::to('/');
       $token = $request->session()->get('token');

       $client = new Client();

       if ($request->input('kodesumberanggaran')=="DIPA") {
           $requestAnggaran = $client->request('POST', $baseUrl.'/api/anggaran',
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
           $anggaran = $resultAnggaran['data']['eventID'];
        } else {
          $anggaran = '';
        }

        // $jumlah_instansi = count($request->input('meta_instansi'));
        // $peserta = 0;
        // if ($jumlah_instansi > 0) {
        //     foreach ($request->input('meta_instansi') as $c1 => $r1) {
        //         $peserta = $peserta + $r1['list_jumlah_peserta'];
        //     }
        // }

        $requestInput = $client->request('POST', $baseUrl.'/api/kerjasamamonev',
               [
                   'headers' =>
                   [
                       'Authorization' => 'Bearer '.$token
                   ],
                   'form_params' => [
                       'nomor_sprint' => $request->input('nomor_sprint'),
                       'tanggal_pelaksanaan' => ($request->input('tanggal_pelaksanaan') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal_pelaksanaan')))) : '',
                       'tempat_pelaksanaan' => $request->input('tempat_pelaksanaan'),
                       'lokasi_kegiatan' => $request->input('lokasi_kegiatan'),
                       'nama_kegiatan' => $request->input('nama_kegiatan'),
                       // 'jumlah_instansi' => $jumlah_instansi,
                       // 'jumlah_peserta' => $peserta,
                       // 'lokasi_kegiatan_idkabkota' => $request->input('lokasi_kegiatan_idkabkota'),
                       // 'materi' => $request->input('materi'),
                       // 'narasumber' => $request->input('narasumber'),
                       // 'meta_instansi' => json_encode($request->input('meta_instansi')),
                       'kodesumberanggaran' => $request->input('kodesumberanggaran'),
                       'anggaran_id' => $anggaran,
                   ]
               ]
           );

       $result = json_decode($requestInput->getBody()->getContents(), true);

       $this->form_params = array('nomor_sprint' => $request->input('nomor_sprint'),
       'tanggal_pelaksanaan' => ($request->input('tanggal_pelaksanaan') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal_pelaksanaan')))) : '',
       'tempat_pelaksanaan' => $request->input('tempat_pelaksanaan'),
       'lokasi_kegiatan' => $request->input('lokasi_kegiatan'),
       'nama_kegiatan' => $request->input('nama_kegiatan'),
       'kodesumberanggaran' => $request->input('kodesumberanggaran'),
       'anggaran_id' => $anggaran);

       $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Kerjasama - Monitoring dan Evaluasi Pelaksanaan Kerjasama';
       $trail['audit_event'] = 'post';
       $trail['audit_value'] = json_encode($this->form_params);
       $trail['audit_url'] = $request->url();
       $trail['audit_ip_address'] = $request->ip();
       $trail['audit_user_agent'] = $request->userAgent();
       $trail['audit_message'] = $result['comment'];
       $trail['created_at'] = date("Y-m-d H:i:s");
       $trail['created_by'] = $request->session()->get('id');

       $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


       $inputId = $result['data']['eventID'];

       if ($request->file('file_upload') != ''){
           $fileName = $inputId.'-'.$request->file('file_upload')->getClientOriginalName();
           $request->file('file_upload')->storeAs('KerjasamaMonev', $fileName);

           $requestfile = $client->request('PUT', $baseUrl.'/api/kerjasamamonev/'.$inputId,
                   [
                       'headers' =>
                       [
                           'Authorization' => 'Bearer '.$token
                       ],
                       'form_params' => [
                           'file_upload' => $fileName,
                       ]
                   ]
               );
       }

       $this->kelengkapan_kerjasamaMonev($inputId);

        if(($result['code'] == 200)&& ($result['status'] != "error") ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Kegiatan Monitoring dan Evaluasi Hasil Pelaksanaan Kerjasama Berhasil Disimpan';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Kegiatan Monitoring dan Evaluasi Hasil Pelaksanaan Kerjasama Gagal Disimpan';
        }

       return redirect('huker/dir_kerjasama/edit_kerjasama_monev/' . $inputId)->with('status', $this->messages);

    }

    public function updatekerjasamaMonev(Request $request){
          $id = $request->input('id');

          $baseUrl = URL::to('/');
          $token = $request->session()->get('token');

          $client = new Client();

         if ($request->input('kodesumberanggaran')=="DIPA") {
             $requestAnggaran = $client->request('POST', $baseUrl.'/api/anggaran',
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
             $anggaran = $resultAnggaran['data']['eventID'];
          } else {
            $anggaran = '';
          }

          $jumlah_instansi = count($request->input('meta_instansi'));
          $peserta = 0;
          if ($jumlah_instansi > 0) {
              foreach ($request->input('meta_instansi') as $c1 => $r1) {
                  $peserta = $peserta + $r1['list_jumlah_peserta'];
              }
          }

          $requestInput = $client->request('PUT', $baseUrl.'/api/kerjasamamonev/'.$id,
                   [
                       'headers' =>
                       [
                           'Authorization' => 'Bearer '.$token
                       ],
                       'form_params' => [
                       'nomor_sprint' => $request->input('nomor_sprint'),
                       'tanggal_pelaksanaan' => ($request->input('tanggal_pelaksanaan') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal_pelaksanaan')))) : '',
                       'tempat_pelaksanaan' => $request->input('tempat_pelaksanaan'),
                       'lokasi_kegiatan' => $request->input('lokasi_kegiatan'),
                       'nama_kegiatan' => $request->input('nama_kegiatan'),
                       // 'jumlah_instansi' => $jumlah_instansi,
                       // 'jumlah_peserta' => $peserta,
                       // 'lokasi_kegiatan_idkabkota' => $request->input('lokasi_kegiatan_idkabkota'),
                       // 'materi' => $request->input('materi'),
                       // 'narasumber' => $request->input('narasumber'),
                       // 'meta_instansi' => json_encode($request->input('meta_instansi')),
                       'kodesumberanggaran' => $request->input('kodesumberanggaran'),
                       'anggaran_id' => $anggaran,
                       ]
                   ]
               );

           $result = json_decode($requestInput->getBody()->getContents(), true);

           $this->form_params = array('nomor_sprint' => $request->input('nomor_sprint'),
           'tanggal_pelaksanaan' => ($request->input('tanggal_pelaksanaan') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tanggal_pelaksanaan')))) : '',
           'tempat_pelaksanaan' => $request->input('tempat_pelaksanaan'),
           'lokasi_kegiatan' => $request->input('lokasi_kegiatan'),
           'nama_kegiatan' => $request->input('nama_kegiatan'),
           'kodesumberanggaran' => $request->input('kodesumberanggaran'),
           'anggaran_id' => $anggaran);

           $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Kerjasama - Monitoring dan Evaluasi Pelaksanaan Kerjasama';
           $trail['audit_event'] = 'put';
           $trail['audit_value'] = json_encode($this->form_params);
           $trail['audit_url'] = $request->url();
           $trail['audit_ip_address'] = $request->ip();
           $trail['audit_user_agent'] = $request->userAgent();
           $trail['audit_message'] = $result['comment'];
           $trail['created_at'] = date("Y-m-d H:i:s");
           $trail['created_by'] = $request->session()->get('id');

           $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


           if ($request->file('file_upload') != ''){
               $fileName = $id.'-'.$request->file('file_upload')->getClientOriginalName();
               $request->file('file_upload')->storeAs('KerjasamaMonev', $fileName);

               $requestfile = $client->request('PUT', $baseUrl.'/api/kerjasamamonev/'.$id,
                       [
                           'headers' =>
                           [
                               'Authorization' => 'Bearer '.$token
                           ],
                           'form_params' => [
                               'file_upload' => $fileName,
                           ]
                       ]
                   );
            }

            $this->kelengkapan_kerjasamaMonev($id);

        if(($result['code'] == 200)&& ($result['status'] != "error") ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Kegiatan Monitoring dan Evaluasi Hasil Pelaksanaan Kerjasama Berhasil Diperbarui';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Kegiatan Monitoring dan Evaluasi Hasil Pelaksanaan Kerjasama Gagal Diperbarui';
        }

        return back()->with('status', $this->messages);
      }

      public function kerjasamaLainnya(Request $request){
        //filter
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
                }else if(($key == 'tempat_pelaksanaan') || ($key == 'nm_kegiatan')){
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
          $status = $request->status;

          if($tipe == 'tgl_pelaksanaan'){
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
          }else if($tipe == 'status'){
            $kondisi .= '&status='.$status;
            $this->selected['status'] = $status;
          }
          else{
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
        $datas = execute_api_json('api/kerjasamalainnya?'.$limit.'&'.$offset.$kondisi,'get');

        $total_item = 0;
        if($datas->code == 200){
            $this->data['data'] = $datas->data;
            $total_item = $datas->paginate->totalpage * $this->limit;

        }else{
            $this->data['data'] = [];
            $total_item = 0;
        }

        $this->data['filter'] = $this->selected;
        $this->data['kondisi'] = $kondisi;

        //end filter
        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $this->data['data_detail'] = $detail;
        $this->data['title'] = "Kerjasama Lainnya";
        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));

        $this->data['delete_route'] = "delete_kerjasama_lainnya";
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
        return view('huker.kerjasama.kerjasama_lainnya.index_kerjasamaLainnya',$this->data);

    }

    public function addkerjasamaLainnya(Request $request){
        // $client = new Client();
        // $baseUrl = URL::to('/');

        // $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
        // $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        // $this->data['propkab'] = $propkab;

        $user_id = Auth::user()->user_id;
        $detail = MainModel::getUserDetail($user_id);
        $data['data_detail'] = $detail;
        $this->data['breadcrumps'] = breadcrumps_dir_kerjasama($request->route()->getName());
        return view('huker.kerjasama.kerjasama_lainnya.add_kerjasamaLainnya',$this->data);
    }

    public function editkerjasamaLainnya(Request $request){
        $id = $request->id;
        $client = new Client();
        $baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $requestDataDetail= $client->request('GET', $baseUrl.'/api/kerjasamalainnya/'.$id,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );

        $dataDetail = json_decode($requestDataDetail->getBody()->getContents(), true);

        if ($dataDetail['data']['anggaran_id'] != '') {
           $this->data['data_anggaran'] = $this->globalGetAnggaran($token, $dataDetail['data']['anggaran_id']);
        }

        $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab');
        $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

        $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['propkab'] = $propkab;
        $this->data['data_detail'] = $dataDetail;
        $this->data['id'] = $id;
        $this->data['breadcrumps'] = breadcrumps_dir_kerjasama($request->route()->getName());
        return view('huker.kerjasama.kerjasama_lainnya.edit_kerjasamaLainnya',$this->data);
    }

    public function inputkerjasamaLainnya(Request $request){

       $baseUrl = URL::to('/');
       $token = $request->session()->get('token');

       $client = new Client();

       if ($request->input('sumberanggaran')=="DIPA") {
           $requestAnggaran = $client->request('POST', $baseUrl.'/api/anggaran',
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
           $anggaran = $resultAnggaran['data']['eventID'];
        } else {
          $anggaran = '';
        }

        $jumlah_instansi = count($request->input('meta_instansi'));
        $peserta = 0;
        if ($jumlah_instansi > 0) {
            foreach ($request->input('meta_instansi') as $c1 => $r1) {
                $peserta = $peserta + $r1['list_jumlah_peserta'];
            }
        }

        $requestInput = $client->request('POST', $baseUrl.'/api/kerjasamalainnya',
               [
                   'headers' =>
                   [
                       'Authorization' => 'Bearer '.$token
                   ],
                   'form_params' => [
                       'no_sprint' => $request->input('no_sprint'),
                       'nm_kegiatan' => $request->input('nm_kegiatan'),
                       'tgl_pelaksanaan' => ($request->input('tgl_pelaksanaan') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))) : '',
                       'tempat_pelaksanaan' => $request->input('tempat_pelaksanaan'),
                       'narasumber' => $request->input('narasumber'),
                       'meta_peserta' => json_encode($request->input('meta_peserta')),
                       'sumberanggaran' => $request->input('sumberanggaran'),
                       'anggaran_id' => $anggaran,
                    ]
                ]
           );

       $result = json_decode($requestInput->getBody()->getContents(), true);

       $this->form_params = array('no_sprint' => $request->input('no_sprint'),
       'nm_kegiatan' => $request->input('nm_kegiatan'),
       'tgl_pelaksanaan' => ($request->input('tgl_pelaksanaan') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))) : '',
       'tempat_pelaksanaan' => $request->input('tempat_pelaksanaan'),
       'narasumber' => $request->input('narasumber'),
       'meta_peserta' => json_encode($request->input('meta_peserta')),
       'sumberanggaran' => $request->input('sumberanggaran'),
       'anggaran_id' => $anggaran);

       $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Kerjasama - Kerjasama Lainnya';
       $trail['audit_event'] = 'post';
       $trail['audit_value'] = json_encode($this->form_params);
       $trail['audit_url'] = $request->url();
       $trail['audit_ip_address'] = $request->ip();
       $trail['audit_user_agent'] = $request->userAgent();
       $trail['audit_message'] = $result['comment'];
       $trail['created_at'] = date("Y-m-d H:i:s");
       $trail['created_by'] = $request->session()->get('id');

       $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


       $inputId = $result['data']['eventID'];

       if ($request->file('dokumen_kegiatan') != ''){
           $fileName = $inputId.'-'.$request->file('dokumen_kegiatan')->getClientOriginalName();
           $request->file('dokumen_kegiatan')->storeAs('KerjasamaLainnya', $fileName);

           $requestfile = $client->request('PUT', $baseUrl.'/api/kerjasamalainnya/'.$inputId,
                   [
                       'headers' =>
                       [
                           'Authorization' => 'Bearer '.$token
                       ],
                       'form_params' => [
                           'dokumen_kegiatan' => $fileName,
                       ]
                   ]
               );
       }

       $this->kelengkapan_kerjasamaLainnya($inputId);

        if(($result['code'] == 200)&& ($result['status'] != "error") ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Kegiatan Kerjasama Lainnya Berhasil Disimpan';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Kegiatan Kerjasama Lainnya Gagal Disimpan';
        }

       return redirect('huker/dir_kerjasama/edit_kerjasama_lainnya/' . $inputId)->with('status', $this->messages);

    }

    public function updatekerjasamaLainnya(Request $request){
          $id = $request->input('id');

          $baseUrl = URL::to('/');
          $token = $request->session()->get('token');

          $client = new Client();

          if ($request->input('sumberanggaran')=="DIPA") {
              $requestAnggaran = $client->request('POST', $baseUrl.'/api/anggaran',
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
              $anggaran = $resultAnggaran['data']['eventID'];
           } else {
             $anggaran = '';
           }

          $jumlah_instansi = count($request->input('meta_instansi'));
          $peserta = 0;
          if ($jumlah_instansi > 0) {
              foreach ($request->input('meta_instansi') as $c1 => $r1) {
                  $peserta = $peserta + $r1['list_jumlah_peserta'];
              }
          }

          $requestInput = $client->request('PUT', $baseUrl.'/api/kerjasamalainnya/'.$id,
                   [
                       'headers' =>
                       [
                           'Authorization' => 'Bearer '.$token
                       ],
                       'form_params' => [
                       'no_sprint' => $request->input('no_sprint'),
                       'nm_kegiatan' => $request->input('nm_kegiatan'),
                       'tgl_pelaksanaan' => ($request->input('tgl_pelaksanaan') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))) : '',
                       'tempat_pelaksanaan' => $request->input('tempat_pelaksanaan'),
                       'narasumber' => $request->input('narasumber'),
                       'meta_peserta' => json_encode($request->input('meta_peserta')),
                       'sumberanggaran' => $request->input('sumberanggaran'),
                       'anggaran_id' => $anggaran,
                       ]
                   ]
               );

           $result = json_decode($requestInput->getBody()->getContents(), true);

           $this->form_params = array('no_sprint' => $request->input('no_sprint'),
           'nm_kegiatan' => $request->input('nm_kegiatan'),
           'tgl_pelaksanaan' => ($request->input('tgl_pelaksanaan') != '') ? date('Y-m-d', strtotime(str_replace('/', '-', $request->input('tgl_pelaksanaan')))) : '',
           'tempat_pelaksanaan' => $request->input('tempat_pelaksanaan'),
           'narasumber' => $request->input('narasumber'),
           'meta_peserta' => json_encode($request->input('meta_peserta')),
           'sumberanggaran' => $request->input('sumberanggaran'),
           'anggaran_id' => $anggaran);

           $trail['audit_menu'] = 'Hukum dan Kerjasama - Direktorat Kerjasama - Kerjasama Lainnya';
           $trail['audit_event'] = 'put';
           $trail['audit_value'] = json_encode($this->form_params);
           $trail['audit_url'] = $request->url();
           $trail['audit_ip_address'] = $request->ip();
           $trail['audit_user_agent'] = $request->userAgent();
           $trail['audit_message'] = $result['comment'];
           $trail['created_at'] = date("Y-m-d H:i:s");
           $trail['created_by'] = $request->session()->get('id');

           $qtrail = $this->inputtrail($request->session()->get('token'),$trail);


           if ($request->file('dokumen_kegiatan') != ''){
               $fileName = $id.'-'.$request->file('dokumen_kegiatan')->getClientOriginalName();
               $request->file('dokumen_kegiatan')->storeAs('KerjasamaLainnya', $fileName);

               $requestfile = $client->request('PUT', $baseUrl.'/api/kerjasamalainnya/'.$id,
                       [
                           'headers' =>
                           [
                               'Authorization' => 'Bearer '.$token
                           ],
                           'form_params' => [
                               'dokumen_kegiatan' => $fileName,
                           ]
                       ]
                   );
            }

            $this->kelengkapan_kerjasamaLainnya($id);

        if(($result['code'] == 200)&& ($result['status'] != "error") ){
            $this->messages['status'] = 'success';
            $this->messages['message'] = 'Data Kegiatan Kerjasama Lainnya Berhasil Diperbarui';
        }else{
            $this->messages['status'] = 'error';
            $this->messages['message'] = 'Data Kegiatan Kerjasama Lainnya Gagal Diperbarui';
        }

        return back()->with('status', $this->messages);
      }

    private function kelengkapan_kerjasamaBilateral($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('hukerkerjasama_perjanjianbilateral')->where('id',$id)
                  ->select('kodejeniskerjasama','tgl_pelaksana','tempatpelaksana','kodenegara', 'no_sprint','jmlh_delegasi_bnn', 'jmlh_delegasi_client', 'kodenegara_mitra', 'file_laporan', 'institusi_penyelenggara', 'lembaga_penyelenggara');
        if($query->count() > 0 ){
          $result = $query->first();
          foreach($result as $key=>$val){
              if(!$val && !is_numeric($val)){
                $status_kelengkapan=false;
                break;
              }else{
                continue;
              }
            }
          }
        if($status_kelengkapan== true){
          $kelengkapan = execute_api_json('api/kerjasamabilateral/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/kerjasamabilateral/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

    private function kelengkapan_kerjasamaKesepemahaman($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('hukerkerjasama_notakesepahaman')->where('id',$id)
                  ->select('jenis_kerjasama','tema','file_upload', 'nomor_sprint','tgl_berakhir', 'tgl_ttd', 'nama_instansi', 'meta_ruanglingkup_kerjasama', 'meta_unitkerja_pelaksana', 'kodejenisinstansi', 'kode_sumberanggaran', 'tempat_ttd');
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
          $kelengkapan = execute_api_json('api/kerjasamanota/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/kerjasamanota/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

    private function kelengkapan_kerjasamaMonev($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('hukerkerjasama_monev')->where('id',$id)
                  ->select('tanggal_pelaksanaan','lokasi_kegiatan','file_upload', 'kodesumberanggaran','nama_kegiatan', 'tempat_pelaksanaan', 'nomor_sprint');
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
          $kelengkapan = execute_api_json('api/kerjasamamonev/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/kerjasamamonev/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

    private function kelengkapan_kerjasamaLainnya($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('hukerkerjasama_lainnya')->where('id',$id)
                  ->select('no_sprint','nm_kegiatan','tgl_pelaksanaan', 'tempat_pelaksanaan','narasumber', 'sumberanggaran', 'dokumen_kegiatan');
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
          $kelengkapan = execute_api_json('api/kerjasamalainnya/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/kerjasamalainnya/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }

}

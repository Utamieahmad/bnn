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
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class deputiCegahController extends Controller {

    public $data;
    public $selected;
    public $form_params;

    public function pendataanAktivitasSebaran(Request $request) {        
//        dd($request->limit);        
        $url_cegah = 'http://103.3.70.160:7004/cegah/react/activityquery/getFiltered';
        $client = new Client();
        if($request->page){
            $page = $request->page;
        }else{
            $page = 1;
        }
        if ($request->limit) {
            $this->limit = $request->limit;
        } else {
            $this->limit = config('app.limit');
        }        

        $kondisi = '';
        if ($request->isMethod('get')) {
            $get = $request->except(['_token', 'kategori']);
            if (count($get) > 0) {
                foreach ($get as $key => $value) {
                    $kondisi .= "&" . $key . '=' . $value;
                    if (($key == 'start_from') || ($key == 'start_to') || ($key == 'end_from') || ($key == 'end_to') || ($key == 'jumlah_from') || ($key == 'jumlah_to')) {
                        if ($value) {
                            $value = date('d/m/Y', strtotime($value));
                        } else {
                            $value = "";
                        }
                    } else {
                        $value = $value;
                    }
                    $this->selected[$key] = $value;
                }
                $this->selected['order'] = $request->order;
                $this->data['filter'] = $this->selected;
            }
            $result_data_array = array(
                'actstat' => 'approve',
                'limit'  => $this->limit,
                'page'  => $page
            );
        } else {            
            $pembuat = trim($request->pembuat);
            if ($pembuat != '') {
                $array1 = array('userName' => $pembuat);
            } else {
                $array1 = array();
            }
            $satker = trim($request->satker);
            if ($satker != '') {
                $array2 = array('userSatker' => $satker);
            } else {
                $array2 = array();
            }
            $wilayah = trim($request->wilayah);
            if ($wilayah != '') {
                $array3 = array('userWilayah' => $wilayah);
            } else {
                $array3 = array();
            }
            $media = trim($request->media);
            if ($media != '') {
                $array4 = array('nmmedia' => $media);
            } else {
                $array4 = array();
            }
            $paket = trim($request->paket);
            if ($paket != '') {
                $array5 = array('paket' => $paket);
            } else {
                $array5 = array();
            }
            $sasaran = trim($request->sasaran);
            if ($sasaran != '') {
                $array6 = array('sasaran' => $sasaran);
            } else {
                $array6 = array();
            }
            $sebaran_from = trim($request->jmlsebarstart);
            if ($sebaran_from != '') {
                $array7 = array('jmlsebarstart' => $sebaran_from);
            } else {
                $array7 = array();
            }
            $sebaran_to = trim($request->jmlsebarend);
            if ($sebaran_to != '') {
                $array8 = array('jmlsebarend' => $sebaran_to);
            } else {
                $array8 = array();
            }
            $start_from = trim($request->tglactstart);
            if ($start_from != '') {
                $start_from = date('Y-m-d', strtotime(str_replace('/', '-', $start_from)));
                $array9 = array('tglactstart' => $start_from);
            } else {
                $array9 = array();
            }
            $start_to = trim($request->tglactend);
            if ($start_to != '') {
                $start_to = str_replace('/', '-', $start_to);
                $start_to = date('Y-m-d', strtotime($start_to));
                $array10 = array('tglactend' => $start_to);
            } else {
                $array10 = array();
            }
            $array11 = array('limit' => $this->limit);
            $array12 = array('page' => $page);
            $result_data_array = array_merge($array1, $array2, $array3, $array4, $array5, $array6, $array7, $array8, $array9, $array10, $array11, $array12);
        }

        $data_request = json_encode($result_data_array);        
        $this->data['title'] = "Data Aktivitas Sebaran Deputi Cegah";
        if ($request->page) {
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page - 1 )) + 1;
        } else {
            $current_page = 1;
            $start_number = $current_page;
        }
        $limit = 'limit=' . $this->limit;
        $offset = 'page=' . $current_page;
        $this->data['filter'] = $this->selected;
        $this->data['forprint'] = $limit.'&'.$offset.$kondisi;
        $requestCegah = $client->request('POST', $url_cegah, [   
            'headers' =>
                       [
                           'Content-Type' => 'application/json'
                       ],
            'body' => $data_request
            ]
        );
//        dd($requestCegah);

        $datas = json_decode($requestCegah->getBody()->getContents(), true);
//        print_r($datas);        
//        exit();        
        if (($datas['status'] != 'error') && ($datas['code'] == 200)) {
            $this->data['data_aktivitas'] = $datas['data'];
            $total_item = $datas['pagination']['totalpage'] * $this->limit;
//            dd($datas['pagination']['totalpage']);
        } else {
            $this->data['data'] = [];
            $total_item = 0;
        }
//        $this->data['delete_route'] = 'delete_kegiatan_pelatihan_plrkm';
        $this->data['path'] = $request->path();
        $filtering = false;
        if ($kondisi) {
            $filter = '&' . $limit . $kondisi;
            $filtering = true;
            $this->data['kondisi'] = '?' . $offset . $kondisi . '&' . $limit;
        } else {
            $filter = '/';
            $filtering = false;
            $this->data['kondisi'] = $current_page;
        }
        $this->data['route_name'] = $request->route()->getName();
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;
//        $this->data['pagination'] = paginations($current_page, 0, $this->limit, config('app.page_ellipsis'), '/' . $request->route()->getPrefix() . "/kegiatan_pelatihan_plrkm", $filtering, $filter);
        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), '/'.$request->route()->getPrefix()."/data_aktivitas_sebaran",$filtering,$filter);
        $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());

        return view('pencegahan.deputiCegah.aktivitasSebaran.index_pendataanAtivitasSebaran', $this->data);
    }

}

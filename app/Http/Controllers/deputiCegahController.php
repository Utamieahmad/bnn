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
use Excel;
use PDF;

class deputiCegahController extends Controller {

    public $data;
    public $selected;
    public $form_params;

    public function pendataanAktivitasSebaran(Request $request) {
//        dd($request->limit);        
        $url_cegah = 'http://10.210.84.11:7004/cegah/react/activityquery/getFiltered';
        $client = new Client();
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            if ($request->page) {
                $page = $request->page;
            } else {
                $page = 1;
            }
        }
//        dd($page);
        if ($request->limit) {
            $this->limit = $request->limit;
        } else {
            $this->limit = config('app.limit');
        }
        $actstat = 'approve';

        $kondisi = '';
        if ($request->isMethod('get')) {
            if (isset($_GET['userName'])) {
                $array1 = array('userName' => $_GET['userName']);
            } else {
                $array1 = array();
            }
            if (isset($_GET['userSatker'])) {
                $array2 = array('userSatker' => $_GET['userSatker']);
            } else {
                $array2 = array();
            }
            if (isset($_GET['userWilayah'])) {
                $array3 = array('userWilayah' => $_GET['userWilayah']);
            } else {
                $array3 = array();
            }
            if (isset($_GET['nmmedia'])) {
                $array4 = array('nmmedia' => $_GET['nmmedia']);
            } else {
                $array4 = array();
            }
            if (isset($_GET['sasaran'])) {
                $array5 = array('sasaran' => $_GET['sasaran']);
            } else {
                $array5 = array();
            }
            if (isset($_GET['jmlsebarMin'])) {
                $array6 = array('jmlsebarMin' => $_GET['jmlsebarMin']);
            } else {
                $array6 = array();
            }
            if (isset($_GET['jmlsebarMax'])) {
                $array7 = array('jmlsebarMax' => $_GET['jmlsebarMax']);
            } else {
                $array7 = array();
            }
            if (isset($_GET['tglactStart'])) {
                $array8 = array('tglactStart' => $_GET['tglactStart']);
            } else {
                $array8 = array();
            }
            if (isset($_GET['tglactEnd'])) {
                $array9 = array('tglactEnd' => $_GET['tglactEnd']);
            } else {
                $array9 = array();
            }
            if (isset($_GET['limit'])) {
                $array10 = array('limit' => $_GET['limit']);
            } else {
                $array10 = array('limit' => $this->limit);
            }
            if (isset($_GET['page'])) {
                $array11 = array('page' => $_GET['page']);
            } else {
                $array11 = array('page' => $page);
            }
            if (isset($_GET['anggaran'])) {
                $array12 = array('anggaran' => $_GET['anggaran']);
            } else {
                $array12 = array();
            }
            if (isset($_GET['actstat'])) {
                $array13 = array('actstat' => $_GET['actstat']);
            } else {
                $array13 = array('actstat' => $actstat);
            }


            $get = $request->except(['_token', 'kategori', 'page', 'limit']);
            if (count($get) > 0) {
                foreach ($get as $key => $value) {
                    $kondisi .= "&" . $key . '=' . $value;
                    if (($key == 'tglactStart') || ($key == 'tglactEnd')) {
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
            $result_data_array = array_merge($array1, $array2, $array3, $array4, $array5, $array6, $array7, $array8, $array9, $array10, $array11, $array12, $array13);
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
            $sebaran_from = trim($request->jmlsebarMin);
            if ($sebaran_from != '') {
                $array7 = array('jmlsebarMin' => $sebaran_from);
            } else {
                $array7 = array();
            }
            $sebaran_to = trim($request->jmlsebarMax);
            if ($sebaran_to != '') {
                $array8 = array('jmlsebarMax' => $sebaran_to);
            } else {
                $array8 = array();
            }
            $start_from = trim($request->tglactStart);
            if ($start_from != '') {
                $start_from = date('Y-m-d', strtotime(str_replace('/', '-', $start_from)));
                $array9 = array('tglactStart' => $start_from);
            } else {
                $array9 = array();
            }
            $start_to = trim($request->tglactEnd);
            if ($start_to != '') {
                $start_to = str_replace('/', '-', $start_to);
                $start_to = date('Y-m-d', strtotime($start_to));
                $array10 = array('tglactEnd' => $start_to);
            } else {
                $array10 = array();
            }
            $array11 = array('limit' => $this->limit);
            $array12 = array('page' => $page);
            $anggaran = trim($request->anggaran);
            if ($anggaran != '') {
                $array13 = array('anggaran' => $request->anggaran);
            } else {
                $array13 = array();
            }
            $array14 = array('actstat' => $actstat);
            $result_data_array = array_merge($array1, $array2, $array3, $array4, $array5, $array6, $array7, $array8, $array9, $array10, $array11, $array12, $array13, $array14);

            $result_data_array2 = array_merge($array1, $array2, $array3, $array4, $array5, $array6, $array7, $array8, $array9, $array10, $array13, $array14);
            foreach ($result_data_array2 as $key => $value) {
                $kondisi .= "&" . $key . '=' . $value;
                if (($key == 'tglactStart') || ($key == 'tglactEnd')) {
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
//        dd($result_data_array);
        $data_request = json_encode($result_data_array);
//        dd($data_request);
        $this->data['title'] = "Data Aktivitas Sebaran Deputi Cegah";
        if ($request->page) {
//            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page - 1 )) + 1;
        } else {
//            $current_page = 1;
            $start_number = $page;
        }
        $limit = 'limit=' . $this->limit;
        $offset = 'page=' . $page;
        $this->selected['limit'] = $this->limit;
        $this->data['filter'] = $this->selected;
//        dd($data_request);
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
//        dd($datas);
//        print_r($datas);        
//        exit();        
        if (($datas['status'] != 'error') && ($datas['code'] == 200)) {
            if(isset($datas['data'])){
                $this->data['data_aktivitas'] = $datas['data'];
                $total_item = $datas['pagination']['totalpage'] * $this->limit;
            }else{
                $this->data['data_aktivitas'] = [];
                $total_item = 0;
            }            
            //total data
//            dd($datas['pagination']['count']);
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
            $this->data['kondisi'] = $page;
        }
        $this->data['limit'] = $this->limit;
        $this->data['forprint'] = $filter;
        $this->data['route_name'] = $request->route()->getName();
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $page;
        $this->data['pagination'] = paginations($page, $total_item, $this->limit, config('app.page_ellipsis'), '/' . $request->route()->getPrefix() . "/data_aktivitas_sebaran", $filtering, $filter);
        $this->data['breadcrumps'] = breadcrumps_dir_pasca($request->route()->getName());
//        dd($this->data);
        return view('pencegahan.deputiCegah.aktivitasSebaran.index_pendataanAtivitasSebaran', $this->data);
    }

    public function printExcelAktivitas(Request $request) {
//        dd(date('Y-m-d', strtotime(str_replace('/', '-', $request->date_from))));
        $url_cegah = 'http://10.210.84.11:7004/cegah/react/activityquery/getFiltered';
        $client = new Client();
        $actstat = 'approve';
        $array1 = array('limit' => 10);
        $array2 = array('page' => 1);
        $array3 = array('actstat' => $actstat);
        $tglactstart = date('Y-m-d', strtotime(str_replace('/', '-', $request->date_from)));
        $tglactend = date('Y-m-d', strtotime(str_replace('/', '-', $request->date_to)));
        $array4 = array('tglactStart' => $tglactstart);
        $array5 = array('tglactEnd' => $tglactend);
        $result_data_array = array_merge($array1, $array2, $array3, $array4, $array5);
        $data_request = json_encode($result_data_array);
//        dd($data_request);

        $requestCegah = $client->request('POST', $url_cegah, [
            'headers' =>
                [
                'Content-Type' => 'application/json'
            ],
            'body' => $data_request
                ]
        );
        $datas = json_decode($requestCegah->getBody()->getContents(), true);
        if (($datas['status'] != 'error') && ($datas['code'] == 200)) {
            $totalData = $datas['pagination']['count'];
        } else {
            $totalData = 10;
        }
        if ($totalData >= 500) {
            $limit = 500;
        } else {
            $limit = $totalData;
        }

        $array6 = array('limit' => $limit);

        $result_data_array2 = array_merge($array2, $array3, $array4, $array5, $array6);
        $data_request2 = json_encode($result_data_array2);
//        dd($data_request2);

        $requestCegah2 = $client->request('POST', $url_cegah, [
            'headers' =>
                [
                'Content-Type' => 'application/json'
            ],
            'body' => $data_request2
                ]
        );
        $datas2 = json_decode($requestCegah2->getBody()->getContents(), true);
        if (($datas2['status'] != 'error') && ($datas2['code'] == 200)) {
            $result = $datas2['data'];
        } else {
            $result = [];
        }

        $DataArray = [];

        $i = 1;
        foreach ($result as $key => $value) {
            $DataArray[$key]['No'] = $i;
            $DataArray[$key]['Pembuat'] = $value['userName'];
            $DataArray[$key]['Satker'] = $value['userSatker'];
            $DataArray[$key]['Wilayah'] = $value['userWilayah'];
            $DataArray[$key]['Tanggal'] = date_format(date_create($value['tglact']), "d-m-Y");
            $DataArray[$key]['Media'] = $value['nmmedia'];
            $DataArray[$key]['Paket'] = $value['paket'];
            $DataArray[$key]['Anggaran'] = $value['anggaran'];
            $DataArray[$key]['Sasaran'] = $value['sasaran'];
            $DataArray[$key]['Jumlah Sebaran'] = $value['jmlsebar'];
            $i = $i + 1;
        }
        //dd($DataArray);
        $data = $DataArray;
        $name = 'Download Data Aktivitas Sebaran Deputi Cegah ' . date('d-m-Y H:i:s');
        $this->printData($data, $name);
    }

    public function printPdfAktivitas(Request $request) {
        $url_cegah = 'http://10.210.84.11:7004/cegah/react/activityquery/getFiltered';
        $client = new Client();
        $actstat = 'approve';
        $array1 = array('limit' => 10);
        $array2 = array('page' => 1);
        $array3 = array('actstat' => $actstat);
        $tglactstart = date('Y-m-d', strtotime(str_replace('/', '-', $request->date_from)));
        $tglactend = date('Y-m-d', strtotime(str_replace('/', '-', $request->date_to)));
        $array4 = array('tglactStart' => $tglactstart);
        $array5 = array('tglactEnd' => $tglactend);
        $result_data_array = array_merge($array1, $array2, $array3, $array4, $array5);
        $data_request = json_encode($result_data_array);
//        dd($data_request);

        $requestCegah = $client->request('POST', $url_cegah, [
            'headers' =>
                [
                'Content-Type' => 'application/json'
            ],
            'body' => $data_request
                ]
        );
        $datas = json_decode($requestCegah->getBody()->getContents(), true);
        if (($datas['status'] != 'error') && ($datas['code'] == 200)) {
            $totalData = $datas['pagination']['count'];
        } else {
            $totalData = 10;
        }
        if ($totalData >= 500) {
            $limit = 50;
        } else {
            $limit = $totalData;
        }

        $array6 = array('limit' => $limit);

        $result_data_array2 = array_merge($array2, $array3, $array4, $array5, $array6);
        $data_request2 = json_encode($result_data_array2);
//        dd($data_request2);

        $requestCegah2 = $client->request('POST', $url_cegah, [
            'headers' =>
                [
                'Content-Type' => 'application/json'
            ],
            'body' => $data_request2
                ]
        );
        $datas2 = json_decode($requestCegah2->getBody()->getContents(), true);
        if (($datas2['status'] != 'error') && ($datas2['code'] == 200)) {
            $result = $datas2['data'];
        } else {
            $result = [];
        }

        $DataArray = [];

        $i = 1;
        foreach ($result as $key => $value) {
            $DataArray[$key]['No'] = $i;
            $DataArray[$key]['Pembuat'] = $value['userName'];
            $DataArray[$key]['Satker'] = $value['userSatker'];
            $DataArray[$key]['Wilayah'] = $value['userWilayah'];
            $DataArray[$key]['Tanggal'] = date_format(date_create($value['tglact']), "d-m-Y");
            $DataArray[$key]['Media'] = $value['nmmedia'];
            $DataArray[$key]['Paket'] = $value['paket'];
            $DataArray[$key]['Anggaran'] = $value['anggaran'];
            $DataArray[$key]['Sasaran'] = $value['sasaran'];
            $DataArray[$key]['Jumlah_Sebaran'] = $value['jmlsebar'];
            $i = $i + 1;
        }
//        dd($DataArray);
        $data = $DataArray;
        $pdf = PDF::loadView('pencegahan/deputiCegah/aktivitasSebaran/pdfAktivitasSebaran', compact('data'));
        return $pdf->download('Aktivitas Sebaran Deputi Cegah ' . date('d-m-Y H:i:s') . '.pdf');
    }

    public function newPdfAktivitasSebaran(Request $request) {
        $url_cegah = 'http://10.210.84.11:7004/cegah/react/activityquery/getFiltered';
        $client = new Client();
        $actstat = 'approve';
        if (isset($_GET['userName'])) {
            $array1 = array('userName' => $_GET['userName']);
        } else {
            $array1 = array();
        }
        if (isset($_GET['userSatker'])) {
            $array2 = array('userSatker' => $_GET['userSatker']);
        } else {
            $array2 = array();
        }
        if (isset($_GET['userWilayah'])) {
            $array3 = array('userWilayah' => $_GET['userWilayah']);
        } else {
            $array3 = array();
        }
        if (isset($_GET['nmmedia'])) {
            $array4 = array('nmmedia' => $_GET['nmmedia']);
        } else {
            $array4 = array();
        }
        if (isset($_GET['sasaran'])) {
            $array5 = array('sasaran' => $_GET['sasaran']);
        } else {
            $array5 = array();
        }
        if (isset($_GET['jmlsebarMin'])) {
            $array6 = array('jmlsebarMin' => $_GET['jmlsebarMin']);
        } else {
            $array6 = array();
        }
        if (isset($_GET['jmlsebarMax'])) {
            $array7 = array('jmlsebarMax' => $_GET['jmlsebarMax']);
        } else {
            $array7 = array();
        }
        if (isset($_GET['tglactStart'])) {
            $array8 = array('tglactStart' => $_GET['tglactStart']);
        } else {
            $array8 = array();
        }
        if (isset($_GET['tglactEnd'])) {
            $array9 = array('tglactEnd' => $_GET['tglactEnd']);
        } else {
            $array9 = array();
        }                
        if (isset($_GET['anggaran'])) {
            $array10 = array('anggaran' => $_GET['anggaran']);
        } else {
            $array10 = array();
        }        
        $array11 = array('limit' => 10);
        $array12 = array('page' => 1);
        $array13 = array('actstat' => $actstat);
        $result_data_array = array_merge($array1, $array2, $array3, $array4, $array5, $array6, $array7, $array8, $array9, $array10, $array11, $array12, $array13);
        $data_request = json_encode($result_data_array);        
//        dd($data_request);
        $requestCegah = $client->request('POST', $url_cegah, [
            'headers' =>
                [
                'Content-Type' => 'application/json'
            ],
            'body' => $data_request
                ]
        );
        $datas = json_decode($requestCegah->getBody()->getContents(), true);
        if (($datas['status'] != 'error') && ($datas['code'] == 200)) {
            $totalData = $datas['pagination']['count'];
//            dd($totalData);
        } else {
            $totalData = 10;
        }
        if ($totalData >= 150) {
            $limit = 150;
        } else {
            $limit = $totalData;
        }        

        $array14 = array('limit' => $limit);

        $result_data_array2 = array_merge($array1, $array2, $array3, $array4, $array5, $array6, $array7, $array8, $array9, $array10, $array14, $array12, $array13);
        $data_request2 = json_encode($result_data_array2);
//        dd($data_request2);

        $requestCegah2 = $client->request('POST', $url_cegah, [
            'headers' =>
                [
                'Content-Type' => 'application/json'
            ],
            'body' => $data_request2
                ]
        );
        $datas2 = json_decode($requestCegah2->getBody()->getContents(), true);
        if (($datas2['status'] != 'error') && ($datas2['code'] == 200)) {
            $result = $datas2['data'];
        } else {
            $result = [];
        }

        $DataArray = [];

        $i = 1;
        foreach ($result as $key => $value) {
            $DataArray[$key]['No'] = $i;
            $DataArray[$key]['Pembuat'] = $value['userName'];
            $DataArray[$key]['Satker'] = $value['userSatker'];
            $DataArray[$key]['Wilayah'] = $value['userWilayah'];
            $DataArray[$key]['Tanggal'] = date_format(date_create($value['tglact']), "d-m-Y");
            $DataArray[$key]['Media'] = $value['nmmedia'];
            $DataArray[$key]['Paket'] = $value['paket'];
            $DataArray[$key]['Anggaran'] = $value['anggaran'];
            $DataArray[$key]['Sasaran'] = $value['sasaran'];
            $DataArray[$key]['Jumlah_Sebaran'] = $value['jmlsebar'];
            $i = $i + 1;
        }
//        dd($DataArray);
        $data = $DataArray;
        $pdf = PDF::loadView('pencegahan/deputiCegah/aktivitasSebaran/pdfAktivitasSebaran', compact('data'));
        return $pdf->download('Aktivitas Sebaran Deputi Cegah ' . date('d-m-Y H:i:s') . '.pdf');
    }
    
    public function newExcelAktivitasSebaran(){
        $url_cegah = 'http://10.210.84.11:7004/cegah/react/activityquery/getFiltered';
        $client = new Client();
        $actstat = 'approve';
        if (isset($_GET['userName'])) {
            $array1 = array('userName' => $_GET['userName']);
        } else {
            $array1 = array();
        }
        if (isset($_GET['userSatker'])) {
            $array2 = array('userSatker' => $_GET['userSatker']);
        } else {
            $array2 = array();
        }
        if (isset($_GET['userWilayah'])) {
            $array3 = array('userWilayah' => $_GET['userWilayah']);
        } else {
            $array3 = array();
        }
        if (isset($_GET['nmmedia'])) {
            $array4 = array('nmmedia' => $_GET['nmmedia']);
        } else {
            $array4 = array();
        }
        if (isset($_GET['sasaran'])) {
            $array5 = array('sasaran' => $_GET['sasaran']);
        } else {
            $array5 = array();
        }
        if (isset($_GET['jmlsebarMin'])) {
            $array6 = array('jmlsebarMin' => $_GET['jmlsebarMin']);
        } else {
            $array6 = array();
        }
        if (isset($_GET['jmlsebarMax'])) {
            $array7 = array('jmlsebarMax' => $_GET['jmlsebarMax']);
        } else {
            $array7 = array();
        }
        if (isset($_GET['tglactStart'])) {
            $array8 = array('tglactStart' => $_GET['tglactStart']);
        } else {
            $array8 = array();
        }
        if (isset($_GET['tglactEnd'])) {
            $array9 = array('tglactEnd' => $_GET['tglactEnd']);
        } else {
            $array9 = array();
        }                
        if (isset($_GET['anggaran'])) {
            $array10 = array('anggaran' => $_GET['anggaran']);
        } else {
            $array10 = array();
        }        
        $array11 = array('limit' => 10);
        $array12 = array('page' => 1);
        $array13 = array('actstat' => $actstat);
        $result_data_array = array_merge($array1, $array2, $array3, $array4, $array5, $array6, $array7, $array8, $array9, $array10, $array11, $array12, $array13);
        $data_request = json_encode($result_data_array);        
//        dd($data_request);
        $requestCegah = $client->request('POST', $url_cegah, [
            'headers' =>
                [
                'Content-Type' => 'application/json'
            ],
            'body' => $data_request
                ]
        );
        $datas = json_decode($requestCegah->getBody()->getContents(), true);
        if (($datas['status'] != 'error') && ($datas['code'] == 200)) {
            $totalData = $datas['pagination']['count'];
//            dd($totalData);
        } else {
            $totalData = 10;
        }
        if ($totalData >= 150) {
            $limit = 150;
        } else {
            $limit = $totalData;
        }        

        $array14 = array('limit' => $limit);

        $result_data_array2 = array_merge($array1, $array2, $array3, $array4, $array5, $array6, $array7, $array8, $array9, $array10, $array14, $array12, $array13);
        $data_request2 = json_encode($result_data_array2);
//        dd($data_request2);

        $requestCegah2 = $client->request('POST', $url_cegah, [
            'headers' =>
                [
                'Content-Type' => 'application/json'
            ],
            'body' => $data_request2
                ]
        );
        $datas2 = json_decode($requestCegah2->getBody()->getContents(), true);
        if (($datas2['status'] != 'error') && ($datas2['code'] == 200)) {
            $result = $datas2['data'];
        } else {
            $result = [];
        }

        $DataArray = [];

        $i = 1;
        foreach ($result as $key => $value) {
            $DataArray[$key]['No'] = $i;
            $DataArray[$key]['Pembuat'] = $value['userName'];
            $DataArray[$key]['Satker'] = $value['userSatker'];
            $DataArray[$key]['Wilayah'] = $value['userWilayah'];
            $DataArray[$key]['Tanggal'] = date_format(date_create($value['tglact']), "d-m-Y");
            $DataArray[$key]['Media'] = $value['nmmedia'];
            $DataArray[$key]['Paket'] = $value['paket'];
            $DataArray[$key]['Anggaran'] = $value['anggaran'];
            $DataArray[$key]['Sasaran'] = $value['sasaran'];
            $DataArray[$key]['Jumlah_Sebaran'] = $value['jmlsebar'];
            $i = $i + 1;
        }
        $data = $DataArray;
        $name = 'Download Data Aktivitas Sebaran Deputi Cegah ' . date('d-m-Y H:i:s');
        $this->printData($data, $name);
    }

}

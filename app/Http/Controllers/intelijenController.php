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
use App\Tr_Wilayah;
use App\Tr_Instansi;
use App\VlookupValue;
use App\Tr_Kodenegara;
use App\Tr_JenisKasus;


use App\BerantasIntelJaringanNarkotika;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class intelijenController extends Controller
{
	public $data;
	public $selected;
    public $form_params;
    public function pendataanJaringan(Request $request){
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
              }else if(($key == 'nomor_lkn') || ($key == 'jenisjaringan') || ($key == 'keterlibatan_jaringan') || ($key == 'nama_jaringan') || ($key == 'status') ){
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
        $jenisjaringan = $request->jenisjaringan;
        $keterlibatan_jaringan = $request->keterlibatan_jaringan;
        $status = $request->status;
        // $pelaksana = $request->pelaksana;
        // $BrgBukti = $request->BrgBukti;
        $tgl_from = $request->tgl_from;
        $tgl_to = $request->tgl_to;
        $order = $request->order;
        $limit = $request->limit;
        // dd($jenisjaringan);

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
        }elseif($tipe == 'jenisjaringan'){
          $kondisi .= '&jenisjaringan='.$jenisjaringan;
          $this->selected['jenisjaringan'] = $jenisjaringan;
      	}elseif($tipe == 'keterlibatan_jaringan'){
          $kondisi .= '&keterlibatan_jaringan='.$keterlibatan_jaringan;
          $this->selected['keterlibatan_jaringan'] = $keterlibatan_jaringan;
        }elseif($tipe == 'status'){
          $kondisi .= '&status='.$status;
          $this->selected['status'] = $status;
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
      $datas = execute_api('api/inteljaringan?'.$limit.'&'.$offset.$kondisi,'get');
      // print_r($datas);
      // echo '</pre>';
      // dd('api/inteljaringan?'.$limit.'&'.$offset.$kondisi);
      $this->data['kondisi'] ='?'.$offset.$kondisi;
      if( ($datas['code']= 200) && ($datas['code'] != 'error')){
        $this->data['data_kasus'] = $datas['data'];
        $total_item = $datas['paginate']['totalpage'] * $this->limit;
      }else{
        $this->data['data_kasus'] = [];
        $total_item =  0;
      }

      $instansi = $this->globalinstansi($request->session()->get('wilayah'), $token);
      $this->data['instansi'] = $instansi;

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
      $this->data['title'] = "Pendataan Jaringan Narkoba Dir Intelijen";
      $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
      $this->data['token'] = $token;
      $this->data['path'] = $request->path();
      $this->data['delete_route'] = 'delete_pendataan_jaringan';
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
          $this->data['breadcrumps'] = breadcrumps_intelijen($request->route()->getName());

    //old//

    //    $client = new Client();
			 // if ($request->input('page')) {
				//  $page = $request->input('page');
			 // } else {
				//  $page = 1;
			 // }

    //     $baseUrl = URL::to('/');
    //     $token = $request->session()->get('token');

    //     $requestIntelJaringan = $client->request('GET', $baseUrl.'/api/inteljaringan?page='.$page.'&id_wilayah='.$request->session()->get('wilayah'),
    //         [
    //             'headers' =>
    //             [
    //                 'Authorization' => 'Bearer '.$token
    //             ]
    //         ]
    //     );
    //     $inteljaringan = json_decode($requestIntelJaringan->getBody()->getContents(), true);
    //     if(($inteljaringan['code'] == 200) && ($inteljaringan['status'] != 'error')){
    //     	$this->data['data_kasus'] = $inteljaringan['data'];
				// 	$this->data['page'] = $inteljaringan['paginate'];
    //     }else{
    //     	$this->data['data_kasus'] = [];
    //     }


    //     $this->data['title'] = "Data Kasus Dir Intelijen";

    //     $this->data['token'] = $token;


    //     $instansi = $this->globalinstansi($request->session()->get('wilayah'), $token);
    // 	  $user_id = Auth::user()->user_id;
    //     $detail = MainModel::getUserDetail($user_id);
    //     $this->data['data_detail'] = $detail;

				// $this->data['path'] = $request->path();
    //     $this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
    //     $this->data['breadcrumps'] = breadcrumps_intelijen($request->route()->getName());
    //     $limit = config('app.limit');

    //     if($request->page){
    //         $current_page = $request->page;
    //         $start_number = ($limit * ($request->page -1 )) + 1;
    //     }else{
    //         $current_page = 1;
    //         $start_number = $current_page;
    //     }
    //      $this->data['current_page'] = $current_page;
        return view('pemberantasan.intelijen.index_pendataanJaringan',$this->data);
    }

     public function editPendataanJaringan(Request $request){


		$id_jaringan = $request->id;
		$data_lkn = execute_api('/api/inteljaringan/'.$id_jaringan,'GET');
		$token = $request->session()->get('token');
		$id = "";
		if($data_lkn['status'] != 'error' && $data_lkn['code'] == 200){
			$id = $data_lkn['data']['id_kasus'];
		}else{

			$this->messages['status'] = 'error';
			$this->messages['message'] = 'No LKN tidak ditemukan.';
			return redirect('pemberantasan/dir_intelijen/add_pendataan_jaringan')->with('message',$this->messages);
		}

		$LKN = execute_api('/api/kasus/'.$id,'GET');




		// $jenisKasus = $this->globalJnsKasus($token);

		$tersangka = execute_api('/api/gettersangka/'.$id,'GET');

		$brgBuktiNarkotika = execute_api('/api/getbbnarkotika/'.$id,'GET');

		$brgBuktiPrekursor = execute_api('/api/getbbprekursor/'.$id,'GET');

		$brgBuktiAsetBarang = execute_api('/api/getbbaset/'.$id,'POST',['jenis' => 'ASET_BARANG']);

		$brgBuktiAsetTanah = execute_api('/api/getbbaset/'.$id,'POST',['jenis' => 'ASET_TANAH']);

		$brgBuktiAsetBangunan = execute_api('/api/getbbaset/'.$id,'POST',['jenis' => 'ASET_BANGUNAN']);

		$brgBuktiAsetLogam = execute_api('/api/getbbaset/'.$id,'POST',['jenis' => 'ASET_LOGAMMULIA']);

		$brgBuktiAsetUang = execute_api('/api/getbbaset/'.$id,'POST',['jenis' => 'ASET_UANGTUNAI']);

		$brgBuktiAsetRekening = execute_api('/api/getbbaset/'.$id,'POST',['jenis' => 'ASET_REKENING']);

		$brgBuktiAsetSurat = execute_api('/api/getbbaset/'.$id,'POST',['jenis' => 'ASET_SURATBERHARGA']);

		$brgBuktiNonNarkotika = execute_api('/api/getbbnonnarkotika/'.$id,'GET');



		$jenisBrgBuktiNarkotika = execute_api('/api/jnsbrgbukti/','POST',['jenis' => ['01', '02' ]]);

		$jenisBrgBuktiPrekursor = execute_api('/api/jnsbrgbukti/','POST',['jenis' => ['06']]);

		$satuan = execute_api('/api/getsatuan/','GET');

		$propkab = execute_api('/api/getpropkab/','GET');


		$requestWilayah =execute_api('/api/propinsi','GET');

		if( ($requestWilayah['status'] != 'error') && ($requestWilayah['code'] == 200)){
			$wilayah_list = 	$requestWilayah['data'];
		}else{
			$wilayah_list = [];
		}

		if($LKN['data']['kasus_tkp_idprovinsi'] == "kosong" || $LKN['data']['kasus_tkp_idprovinsi'] == ""){
				$kotaKab = "kosong";
		} else {
			$requestFilterWilayah = execute_api('/api/filterwilayah/'.$LKN['data']['kasus_tkp_idprovinsi'],'GET');
			if($requestFilterWilayah['status'] != 'error' && $requestFilterWilayah['code'] == 200){
				$kotaKab = 	$requestFilterWilayah['data'];
			}else{
				$kotaKab = [];
			}


		}

		$this->data['data_lkn'] = $data_lkn['data'];
		$kabupaten = [];

		$idp = $data_lkn['data']['asal_wilayah_idprovinsi'];
		if($LKN['data']['kasus_tkp_idprovinsi']){
			$query = Tr_Wilayah::where('wil_id_wilayah',$idp);
			if($query->count()>0){
				$kabupaten = $query->get();
			}else{
				$kabupaten = [];
			}
		}else{
			$kabupaten = [];
		}
		// print_r($kabupaten);
		// exit();


		$this->data['propinsi'] = $wilayah_list;


		$this->data['data_kasus'] = $LKN;

		$this->data['negara'] = Tr_Kodenegara::get();

		$this->data['kabupaten'] = $kotaKab;
		$this->data['wil_kabupaten'] = $kabupaten;
		$this->data['tersangka'] = $tersangka;
		$this->data['brgBuktiNonNarkotika'] = $brgBuktiNonNarkotika;
		$this->data['brgBuktiNarkotika'] = $brgBuktiNarkotika;
		$this->data['brgBuktiAsetBarang'] = $brgBuktiAsetBarang;
		$this->data['brgBuktiAsetTanah'] = $brgBuktiAsetTanah;
		$this->data['brgBuktiAsetBangunan'] = $brgBuktiAsetBangunan;
		$this->data['brgBuktiAsetUang'] = $brgBuktiAsetUang;
		$this->data['brgBuktiAsetLogam'] = $brgBuktiAsetLogam;
		$this->data['brgBuktiAsetRekening'] = $brgBuktiAsetRekening;
		$this->data['brgBuktiAsetSurat'] = $brgBuktiAsetSurat;
		$this->data['brgBuktiPrekursor'] = $brgBuktiPrekursor;
		$this->data['jenisBrgBuktiNarkotika'] = $jenisBrgBuktiNarkotika;
		$this->data['jenisBrgBuktiPrekursor'] = $jenisBrgBuktiPrekursor;
		$this->data['satuan'] = $satuan;
		$this->data['propkab'] = $propkab;
		$this->data['id'] = $id;
		$d = $LKN['data'];
		// echo '<pre>';
		// print_r($d);
		// exit();
		if($d['id_instansi']){
			$instansi = Tr_Instansi::where('id_instansi',$d['id_instansi'])->select('nm_instansi');
			if($instansi->count()>0){
				$instansi = $instansi->first();
				$this->data['instansi'] = $instansi->nm_instansi;
			}else{
				$this->data['instansi'] = "";
			}
		}else{
			$this->data['instansi'] = "";
		}

		if($d['kasus_tkp_idprovinsi']){
			$wilayah = Tr_Wilayah::where('id_wilayah',$d['kasus_tkp_idprovinsi'])->select('nm_wilayah');
			if($wilayah->count()>0){
				$wilayah = $wilayah->first();
				$this->data['nm_wilayah'] = $wilayah->nm_wilayah;
			}else{
				$this->data['nm_wilayah'] = "";
			}
		}else{
			$this->data['nm_wilayah'] = "";
		}

		if($d['kode_negarasumbernarkotika']){
			$wilayah = Tr_Kodenegara::where('kode',$d['kode_negarasumbernarkotika'])->select('nama_negara');
			if($wilayah->count()>0){
				$wilayah = $wilayah->first();
				$this->data['nama_negara'] = $wilayah->nama_negara;
			}else{
				$this->data['nama_negara'] = "";
			}
		}else{
			$this->data['nama_negara'] = "";
		}

		if($d['kasus_tkp_idkabkota']){
			$wilayah = Tr_Wilayah::where('id_wilayah',$d['kasus_tkp_idkabkota'])->select('nm_wilayah');
			if($wilayah->count()>0){
				$wilayah = $wilayah->first();
				$this->data['ktp_kabupaten'] = $wilayah->nm_wilayah;
			}else{
				$this->data['ktp_kabupaten'] = "";
			}
		}else{
			$this->data['ktp_kabupaten'] = "";
		}


		if($d['jalur_masuk']){
			$wilayah = VlookupValue::where(['lookup_type'=>'jalur_masuk_narkotika','lookup_value'=>$d['jalur_masuk']])->select('lookup_name');
			if($wilayah->count()>0){
				$wilayah = $wilayah->first();
				$this->data['jalur_masuk'] = $wilayah->lookup_name;
			}else{
				$this->data['jalur_masuk'] = "";
			}
		}else{
			$this->data['jalur_masuk'] = "";
		}
		if($d['kasus_jenis']){
			$wilayah = Tr_JenisKasus::where('id',$d['kasus_jenis'])->select('nm_jnskasus');
			if($wilayah->count()>0){
				$wilayah = $wilayah->first();
				$this->data['kasus_jenis'] = $wilayah->nm_jnskasus;
			}else{
				$this->data['kasus_jenis'] = "";
			}
		}else{
			$this->data['kasus_jenis'] = "";
		}
		$this->data['breadcrumps'] = breadcrumps_intelijen($request->route()->getName());
        return view('pemberantasan.intelijen.edit_pendataanJaringan',$this->data);
    }

    public function addPendataanJaringan(Request $request){
		$client = new Client();

	 	$baseUrl = URL::to('/');
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
       	if( ($lkn['status'] != 'error') && ($lkn['code'] == 200) ){
        	$this->data['lkn'] = $lkn['data'];
       	}else{
			$this->data['lkn'] =  [] ;
       	}
        // print_r($data);
        // exit();
		$this->data['breadcrumps'] = breadcrumps_intelijen($request->route()->getName());
        return view('pemberantasan.intelijen.add_pendataanJaringan',$this->data);
    }

	public function addDataPendataanJaringan(Request $request)
		{
			// dd($request->input('kasus_no'));
			$lkn = $this->globalGetByLkn($request->session()->get('token'), $request->input('kasus_no'));

			if (count($lkn['data']) == 0) {
				$this->messages['status'] = 'error';
				$this->messages['message'] = 'No LKN tidak ditemukan.';
				return redirect('pemberantasan/dir_intelijen/add_pendataan_jaringan')->with('message',$this->messages);
			}

			$kasus = $lkn['data'][0];
			// dd($kasus);
			$id = $kasus['kasus_id'];

			$client = new Client();

					$baseUrl = URL::to('/');
					$token = $request->session()->get('token');

					$requestLKN = $client->request('GET', $baseUrl.'/api/kasus/'.$id,
							[
									'headers' =>
									[
											'Authorization' => 'Bearer '.$token
									]
							]
					);

					$LKN = json_decode($requestLKN->getBody()->getContents(), true);

					$requestWilayah = $client->request('GET', $baseUrl.'/api/propinsi',
							[
									'headers' =>
									[
											'Authorization' => 'Bearer '.$token
									]
							]
					);

					$wilayah = json_decode($requestWilayah->getBody()->getContents(), true);

					if($LKN['data']['kasus_tkp_idprovinsi'] == "kosong" || $LKN['data']['kasus_tkp_idprovinsi'] == ""){
							$kotaKab = "kosong";
					} else {
							$requestFilterWilayah = $client->request('GET', $baseUrl.'/api/filterwilayah/'.$LKN['data']['kasus_tkp_idprovinsi'],
									[
											'headers' =>
											[
													'Authorization' => 'Bearer '.$token
											]
									]
							);
							$kotaKab = json_decode($requestFilterWilayah->getBody()->getContents(), true);
					}

					$jalur_masuk = $this->globalJalurMasuk($token);

					$instansi = $this->globalinstansi($request->session()->get('wilayah'), $token);

					$jenisKasus = $this->globalJnsKasus($token);

					$requestTersangka = $client->request('GET', $baseUrl.'/api/gettersangka/'.$id,
							[
									'headers' =>
									[
											'Authorization' => 'Bearer '.$token
									]
							]
					);

					$tersangka = json_decode($requestTersangka->getBody()->getContents(), true);

					$requestBrgBukttiNarkotika = $client->request('GET', $baseUrl.'/api/getbbnarkotika/'.$id,
							[
									'headers' =>
									[
											'Authorization' => 'Bearer '.$token
									]
							]
					);

					$brgBuktiNarkotika = json_decode($requestBrgBukttiNarkotika->getBody()->getContents(), true);

					$requestBrgBukttiPrekursor = $client->request('GET', $baseUrl.'/api/getbbprekursor/'.$id,
							[
									'headers' =>
									[
											'Authorization' => 'Bearer '.$token
									]
							]
					);

					$brgBuktiPrekursor = json_decode($requestBrgBukttiPrekursor->getBody()->getContents(), true);

					$requestBrgBukttiAsetBarang = $client->request('POST', $baseUrl.'/api/getbbaset/'.$id,
							[
									'headers' =>
									[
											'Authorization' => 'Bearer '.$token
									],
									'form_params' =>
									[
										'jenis' => 'ASET_BARANG'
									]
							]
					);

					$brgBuktiAsetBarang = json_decode($requestBrgBukttiAsetBarang->getBody()->getContents(), true);

					$requestBrgBukttiAsetTanah = $client->request('POST', $baseUrl.'/api/getbbaset/'.$id,
							[
									'headers' =>
									[
											'Authorization' => 'Bearer '.$token
									],
									'form_params' =>
									[
										'jenis' => 'ASET_TANAH'
									]
							]
					);

					$brgBuktiAsetTanah = json_decode($requestBrgBukttiAsetTanah->getBody()->getContents(), true);

					$requestBrgBukttiAsetBangunan = $client->request('POST', $baseUrl.'/api/getbbaset/'.$id,
							[
									'headers' =>
									[
											'Authorization' => 'Bearer '.$token
									],
									'form_params' =>
									[
										'jenis' => 'ASET_BANGUNAN'
									]
							]
					);

					$brgBuktiAsetBangunan = json_decode($requestBrgBukttiAsetBangunan->getBody()->getContents(), true);

					$requestBrgBukttiAsetLogam = $client->request('POST', $baseUrl.'/api/getbbaset/'.$id,
							[
									'headers' =>
									[
											'Authorization' => 'Bearer '.$token
									],
									'form_params' =>
									[
										'jenis' => 'ASET_LOGAMMULIA'
									]
							]
					);

					$brgBuktiAsetLogam = json_decode($requestBrgBukttiAsetLogam->getBody()->getContents(), true);

					$requestBrgBukttiAsetUang = $client->request('POST', $baseUrl.'/api/getbbaset/'.$id,
							[
									'headers' =>
									[
											'Authorization' => 'Bearer '.$token
									],
									'form_params' =>
									[
										'jenis' => 'ASET_UANGTUNAI'
									]
							]
					);

					$brgBuktiAsetUang = json_decode($requestBrgBukttiAsetUang->getBody()->getContents(), true);

					$requestBrgBukttiAsetRekening = $client->request('POST', $baseUrl.'/api/getbbaset/'.$id,
							[
									'headers' =>
									[
											'Authorization' => 'Bearer '.$token
									],
									'form_params' =>
									[
										'jenis' => 'ASET_REKENING'
									]
							]
					);

					$brgBuktiAsetRekening = json_decode($requestBrgBukttiAsetRekening->getBody()->getContents(), true);

					$requestBrgBukttiAsetSurat = $client->request('POST', $baseUrl.'/api/getbbaset/'.$id,
							[
									'headers' =>
									[
											'Authorization' => 'Bearer '.$token
									],
									'form_params' =>
									[
										'jenis' => 'ASET_SURATBERHARGA'
									]
							]
					);

					$brgBuktiAsetSurat = json_decode($requestBrgBukttiAsetSurat->getBody()->getContents(), true);

					$requestBrgBuktiNonNarkotika = $client->request('GET', $baseUrl.'/api/getbbnonnarkotika/'.$id,
							[
									'headers' =>
									[
											'Authorization' => 'Bearer '.$token
									]
							]
					);

					$brgBuktiNonNarkotika = json_decode($requestBrgBuktiNonNarkotika->getBody()->getContents(), true);

					$requestJenisBrgBuktiNarkotika = $client->request('POST', $baseUrl.'/api/jnsbrgbukti',
							[
									'headers' =>
									[
											'Authorization' => 'Bearer '.$token
									],
									'form_params' => [
											'jenis' => [
												'01', '02'
											]
									]
							]
					);

					$jenisBrgBuktiNarkotika = json_decode($requestJenisBrgBuktiNarkotika->getBody()->getContents(), true);

					$requestJenisBrgBuktiPrekursor = $client->request('POST', $baseUrl.'/api/jnsbrgbukti',
							[
									'headers' =>
									[
											'Authorization' => 'Bearer '.$token
									],
									'form_params' => [
											'jenis' => [
												'06'
											]
									]
							]
					);

					$jenisBrgBuktiPrekursor = json_decode($requestJenisBrgBuktiPrekursor->getBody()->getContents(), true);

					$requestSatuan = $client->request('GET', $baseUrl.'/api/getsatuan',
							[
									'headers' =>
									[
											'Authorization' => 'Bearer '.$token
									]
							]
					);

					$satuan = json_decode($requestSatuan->getBody()->getContents(), true);

					$requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab',
							[
									'headers' =>
									[
											'Authorization' => 'Bearer '.$token
									]
							]
					);

					$propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);


			$this->data['jalur_masuk'] = $jalur_masuk;
			$this->data['wilayah'] = $wilayah;
			$this->data['instansi'] = $instansi;
			$this->data['jenisKasus'] = $jenisKasus;
			$this->data['data_kasus'] = $LKN;
			$this->data['propinsi'] = $wilayah;
			$this->data['kabupaten'] = $kotaKab;
			$this->data['negara'] = MainModel::getListNegara();
			$this->data['jenisKasus'] = $jenisKasus;
			$this->data['tersangka'] = $tersangka;
			$this->data['brgBuktiNonNarkotika'] = $brgBuktiNonNarkotika;
			$this->data['brgBuktiNarkotika'] = $brgBuktiNarkotika;
			$this->data['brgBuktiAsetBarang'] = $brgBuktiAsetBarang;
			$this->data['brgBuktiAsetTanah'] = $brgBuktiAsetTanah;
			$this->data['brgBuktiAsetBangunan'] = $brgBuktiAsetBangunan;
			$this->data['brgBuktiAsetUang'] = $brgBuktiAsetUang;
			$this->data['brgBuktiAsetLogam'] = $brgBuktiAsetLogam;
			$this->data['brgBuktiAsetRekening'] = $brgBuktiAsetRekening;
			$this->data['brgBuktiAsetSurat'] = $brgBuktiAsetSurat;
			$this->data['brgBuktiPrekursor'] = $brgBuktiPrekursor;
			$this->data['jenisBrgBuktiNarkotika'] = $jenisBrgBuktiNarkotika;
			$this->data['jenisBrgBuktiPrekursor'] = $jenisBrgBuktiPrekursor;
			$this->data['satuan'] = $satuan;
			$this->data['propkab'] = $propkab;
// dd($brgBuktiAsetBarang);
			$this->data['id'] = $id;

			$this->data['breadcrumps'] = breadcrumps_intelijen($request->route()->getName());
			return view('pemberantasan.intelijen.input_pendataanJaringan', $this->data);
		}

		public function inputPendataanJaringan(Request $request)
		{
			$baseUrl = URL::to('/');
			$token = $request->session()->get('token');

			// dd($request->all());

			$client = new Client();
			$meta_jaringan_terkait  = $request->input('nama_jaringan_terkait');
			$arr_meta = [];
			$json_meta = "";
			if(count($meta_jaringan_terkait)){
				for($i = 0; $i < count($meta_jaringan_terkait);$i++){
					$d = $meta_jaringan_terkait[$i];
					if($d['nama_jaringan'] || $d['peran_jaringan']){
						$arr_meta[] = ['nama_jaringan'=>$d['nama_jaringan'],'peran_jaringan'=>$d['peran_jaringan']];
					}else{
						$arr_meta = [];
					}
 				}
			}else{
				$arr_meta = [];
			}

			if($arr_meta){
				$json_meta = json_encode($arr_meta);
			}else{
				$json_meta = "";
			}


			$request1 = $client->request('POST', $baseUrl.'/api/inteljaringan',
			[
					'headers' =>
					[
							'Authorization' => 'Bearer '.$token
					],
					'form_params' => [
						'nomor_lkn' => $request->input('nomor_lkn'),
						'id_kasus' => $request->input('id_kasus'),
						'keterlibatan_jaringan' => $request->input('keterlibatan_jaringan'),
						'nama_jaringan' => $request->input('nama_jaringan'),
						'nama_komandan_jaringan' => $request->input('nama_komandan_jaringan'),
						'kode_jenisjaringan' => $request->input('kode_jenisjaringan'),
						'asal_negara_jaringan' => $request->input('asal_negara_jaringan'),
						'keterkaitan_jaringan_lain' => $request->input('keterkaitan_jaringan_lain'),
						'meta_jaringan_terkait' => $json_meta,
						'asal_wilayah_idprovinsi' => $request->input('asal_wilayah_idprovinsi'),
						'asal_wilayah_idkabkota' => $request->input('asal_wilayah_idkabkota'),

					]
			]
			);

			$result = json_decode($request1->getBody()->getContents(), true);
			$id = $result['data']['eventID'];

			if ($request->file('file_upload')){
					$fileName = date('Y-m-d').'_'.$id.'-'.$request->file('file_upload')->getClientOriginalName();
					try {
						$request->file('file_upload')->storeAs('IntelJaringan', $fileName);

						$requestfile = $client->request('PUT', $baseUrl.'/api/inteljaringan/'.$id,
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
					}catch(\Exception $e){
						$e->getMessage();
					}
			}

			$this->form_params = array('nomor_lkn' => $request->input('nomor_lkn'),
			'id_kasus' => $request->input('id_kasus'),
			'keterlibatan_jaringan' => $request->input('keterlibatan_jaringan'),
			'nama_jaringan' => $request->input('nama_jaringan'),
			'nama_komandan_jaringan' => $request->input('nama_komandan_jaringan'),
			'kode_jenisjaringan' => $request->input('kode_jenisjaringan'),
			'asal_negara_jaringan' => $request->input('asal_negara_jaringan'),
			'keterkaitan_jaringan_lain' => $request->input('keterkaitan_jaringan_lain'),
			'meta_jaringan_terkait' => $json_meta,
			'asal_wilayah_idprovinsi' => $request->input('asal_wilayah_idprovinsi'),
			'asal_wilayah_idkabkota' => $request->input('asal_wilayah_idkabkota'));

			$trail['audit_menu'] = 'Pemberantasan - Direktorat Intelijen - Pendataan Jaringan';
			$trail['audit_event'] = 'post';
			$trail['audit_value'] = json_encode($this->form_params);
			$trail['audit_url'] = $request->url();
			$trail['audit_ip_address'] = $request->ip();
			$trail['audit_user_agent'] = $request->userAgent();
			$trail['audit_message'] = $result['comment'];
			$trail['created_at'] = date("Y-m-d H:i:s");
			$trail['created_by'] = $request->session()->get('id');

			$qtrail = $this->inputtrail($token,$trail);

			$this->kelengkapan_PendataanJaringan($id);

			return redirect('pemberantasan/dir_intelijen/pendataan_jaringan');
		}

		public function updatePendataanJaringan(Request $request)
		{
			$params = $request->except(['_token','id','nama_jaringan_terkait']);
			$id = $request->id;
			$meta_jaringan_terkait  = $request->input('nama_jaringan_terkait');
			$arr_meta = [];
			$json_meta = "";
			if(count($meta_jaringan_terkait)){
				for($i = 0; $i < count($meta_jaringan_terkait);$i++){
					$d = $meta_jaringan_terkait[$i];
					if($d['nama_jaringan'] || $d['peran_jaringan']){
						$arr_meta[] = ['nama_jaringan'=>$d['nama_jaringan'],'peran_jaringan'=>$d['peran_jaringan']];
					}else{
						$arr_meta = [];
					}
 				}
			}else{
				$arr_meta = [];
			}
			if($arr_meta){
				$json_meta = json_encode($arr_meta);
			}else{
				$json_meta = "";
			}

			$params['meta_jaringan_terkait'] = $json_meta;
			$update = execute_api_json('/api/inteljaringan/'.$id,'PUT',$params);

			if ($request->file('file_upload')){
					$fileName = date('Y-m-d').'_'.$id.'-'.$request->file('file_upload')->getClientOriginalName();
					try {
						$request->file('file_upload')->storeAs('IntelJaringan', $fileName);
						$params2['file_upload'] = $fileName;
						$update2 = execute_api_json('/api/inteljaringan/'.$id,'PUT',$params2);

					}catch(\Exception $e){
						$e->getMessage();
					}
			}

			$trail['audit_menu'] = 'Pemberantasan - Direktorat Intelijen - Pendataan Jaringan';
			$trail['audit_event'] = 'put';
			$trail['audit_value'] = json_encode($params);
			$trail['audit_url'] = $request->url();
			$trail['audit_ip_address'] = $request->ip();
			$trail['audit_user_agent'] = $request->userAgent();
			$trail['audit_message'] = $update->comment;
			$trail['created_at'] = date("Y-m-d H:i:s");
			$trail['created_by'] = $request->session()->get('id');

			$qtrail = $this->inputtrail($request->session()->get('token'),$trail);


			$this->kelengkapan_PendataanJaringan($id);

			if(($update->code = 200) && ($update->status != 'error')){
				$this->data['status'] = 'success';
				$this->data['message'] = 'Data Jaringan Berhasil Diperbarui';
			}else{
				$this->data['status'] = 'error';
				$this->data['message'] = 'Data Jaringan Gagal Diperbarui';
			}
			return back()->with('status',$this->data);
		}

		public function getDetailTersangka(Request $request){
			$id = $request->id;
			$tersangka = execute_api('/api/getDetailTersangka/'.$id,'GET');
			if(($tersangka['code'] == 200) && ($tersangka['code'] != 'error') ){
				$jenis_kelamin = config('lookup.jenis_kelamin');
				$pendidikan_akhir = config('lookup.pendidikan_akhir');
				$kode_pekerjaan = config('lookup.kode_pekerjaan');
				$kode_peran = config('lookup.kode_peran_tersangka');
				$data_tersangka = $tersangka['data'];
				$nama_wilayah = "";
				$ktp_wilayah = "";
				$ktp_alamat_lain = "";
				if( isset($data_tersangka['alamatdomisili_idkabkota'])){
					$wilayah = Tr_Wilayah::where('id_wilayah',$data_tersangka['alamatdomisili_idkabkota'])->select('nm_wilayah');
					if($wilayah->count()>0){
						$wilayah = $wilayah->first();
						$nama_wilayah = $wilayah->nm_wilayah;
					}else{
						$nama_wilayah = "";
					}
				}else{
					$nama_wilayah = "";
				}

				if(isset($data_tersangka['alamatktp_idkabkota'])){
					$wilayah = Tr_Wilayah::where('id_wilayah',$data_tersangka['alamatktp_idkabkota'])->select('nm_wilayah');
					if($wilayah->count()>0){
						$wilayah = $wilayah->first();
						$ktp_wilayah = $wilayah->nm_wilayah;
					}else{
						$ktp_wilayah = "";
					}
				}else{
					$ktp_wilayah = "";
				}

				if(isset($data_tersangka['alamatlainnya_idkabkota'])){
					$wilayah = Tr_Wilayah::where('id_wilayah',$data_tersangka['alamatlainnya_idkabkota'])->select('nm_wilayah');
					if($wilayah->count()>0){
						$wilayah = $wilayah->first();
						$ktp_alamat_lain = $wilayah->nm_wilayah;
					}else{
						$ktp_alamat_lain = "";
					}
				}else{
					$ktp_alamat_lain = "";
				}

				/*domisili*/
				$propinsi_domisili = "";
				if(isset($data_tersangka['alamatdomisili_idprovinsi'])){
					$wilayah = Tr_Wilayah::where('id_wilayah',$data_tersangka['alamatdomisili_idprovinsi'])->select('nm_wilayah');
					if($wilayah->count()>0){
						$wilayah = $wilayah->first();
						$propinsi_domisili = $wilayah->nm_wilayah;
					}else{
						$propinsi_domisili = "";
					}
				}else{
					$propinsi_domisili = "";
				}

				$alamatktp_idprovinsi = "";
				if(isset($data_tersangka['alamatktp_idprovinsi'])){
					$wilayah = Tr_Wilayah::where('id_wilayah',$data_tersangka['alamatktp_idprovinsi'])->select('nm_wilayah');
					if($wilayah->count()>0){
						$wilayah = $wilayah->first();
						$alamatktp_idprovinsi = $wilayah->nm_wilayah;
					}else{
						$alamatktp_idprovinsi = "";
					}
				}else{
					$alamatktp_idprovinsi = "";
				}

				$alamatlainnya_idprovinsi = "";
				if(isset($data_tersangka['alamatlainnya_idprovinsi'])){
					$wilayah = Tr_Wilayah::where('id_wilayah',$data_tersangka['alamatlainnya_idprovinsi'])->select('nm_wilayah');
					if($wilayah->count()>0){
						$wilayah = $wilayah->first();
						$alamatlainnya_idprovinsi = $wilayah->nm_wilayah;
					}else{
						$alamatlainnya_idprovinsi = "";
					}
				}else{
					$alamatlainnya_idprovinsi = "";
				}

				$kode_jenisidentitas = "";
				if(isset($data_tersangka['kode_jenisidentitas'])){
					$wilayah = VlookupValue::where('lookup_type',$data_tersangka['kode_jenisidentitas'])->select('lookup_name');
					if($wilayah->count()>0){
						$wilayah = $wilayah->first();
						$kode_jenisidentitas = $wilayah->lookup_name;
					}else{
						$kode_jenisidentitas = "";
					}
				}else{
					$kode_jenisidentitas = "";
				}

				$data_tersangka['kode_jenis_kelamin'] = (isset( $data_tersangka['kode_jenis_kelamin']) ? $jenis_kelamin[$data_tersangka['kode_jenis_kelamin']] : '');
				$data_tersangka['kode_pendidikan_akhir'] = ( isset($data_tersangka['kode_pendidikan_akhir']) ? $pendidikan_akhir[$data_tersangka['kode_pendidikan_akhir']] : '');
				$data_tersangka['kode_pekerjaan'] = ( isset($data_tersangka['kode_pekerjaan']) ? $kode_pekerjaan[$data_tersangka['kode_pekerjaan']] : '');
				$data_tersangka['alamatdomisili_idkabkota'] = $nama_wilayah;
				$data_tersangka['alamatktp_idkabkota'] =$ktp_wilayah;
				$data_tersangka['alamatlainnya_idkabkota'] =$ktp_alamat_lain;
				$data_tersangka['alamatdomisili_idprovinsi'] =$propinsi_domisili;
				$data_tersangka['alamatktp_idprovinsi'] =$alamatktp_idprovinsi;
				$data_tersangka['alamatlainnya_idprovinsi'] =$alamatlainnya_idprovinsi;
				$data_tersangka['kode_jenisidentitas'] =$kode_jenisidentitas;
				$data_tersangka['kode_peran_tersangka'] = ( isset($data_tersangka['kode_peran_tersangka']) ? $kode_peran[$data_tersangka['kode_peran_tersangka']] : '');


				$this->data['status'] = 'success';
				$this->data['message'] = 'Data Tersangka Ditemukan';
				$this->data['data'] = $data_tersangka;
			}else{
				$this->data['status'] = 'error';
				$this->data['message'] = 'Data Tersangka Tidak Ditemukan';
				$this->data['data'] = [];
			}
			/*
				Pendidikan Akhir
				kode_negara
				Pekerjaan
				Peran Tersangka
			*/
			return response()->json($this->data);
			// return view('pemberantasan.intelijen.modal_viewTersangka');
		}
	public function printIntelijen(Request $request){
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

		$i = $start_number;

		// $page = 'page='.$request->current_page;
		// $id_wil = $request->session()->get('wilayah');
        $inteljaringan = execute_api_json('/api/inteljaringan'.$kondisi, 'GET');
        // print_r($inteljaringan);
        $limit = config('app.limit');
        $start_number = ($limit * ($request->page -1 )) + 1;
        $result = [];
        if(($inteljaringan->code== 200) && ($inteljaringan->status != 'error')){
        	$data = $inteljaringan->data;
    	 	if(count($data) >0){
    	 		$i = $start_number;
                foreach($data as $key=>$d){
                    $result[$key]['No'] = $i;
                    $result[$key]['Nomor LKN'] = $d->nomor_lkn;
                    $result[$key]['Jenis Jaringan'] = $d->kode_jenisjaringan;
                    $result[$key]['Keterlibatan Jaringan'] = ( $d->keterlibatan_jaringan ?( $d->keterlibatan_jaringan == 'Y' ? "Ya" : "Tidak") : '');
                    $result[$key]['Nama Jaringan'] = $d->nama_jaringan;
                    $i = $i+1;
                }
                $name = 'Pendataan Jaringan Intelijen '.Carbon::now()->format('Y-m-d H:i:s');
                $this->printData($result, $name);
            }else{
                echo 'data tidak tersedia';
            }
        }else{
        	echo 'data tidak tersedia';
        }

	}

	public function deletePendataanJaringan(Request $request){
        if ($request->ajax()) {
            $id = $request->id;
            if($id){
                $data_request = execute_api('api/inteljaringan/'.$id,'DELETE');
								$this->form_params['delete_id'] = $id;
								$trail['audit_menu'] = 'Pemberantasan - Direktorat Intelijen - Pendataan Jaringan';
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
                $data_request = ['code'=>200,'status'=>'error','message'=>'Data Jaringan Narkoba Gagal Dihapus'];
                return $data_request;
            }
        }
    }

    private function kelengkapan_PendataanJaringan($id){
      $status_kelengkapan = true;
      try{
        $query = DB::table('berantas_intel_jaringan_narkotika')->where('id',$id)
                  ->select('nomor_lkn','id_kasus','keterlibatan_jaringan','nama_jaringan','nama_komandan_jaringan','kode_jenisjaringan', 'asal_negara_jaringan', 'keterkaitan_jaringan_lain', 'meta_jaringan_terkait', 'asal_wilayah_idprovinsi', 'asal_wilayah_idkabkota');
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
          $kelengkapan = execute_api_json('api/inteljaringan/'.$id,'PUT',['status'=>'Y']);
        }else{
          $kelengkapan = execute_api_json('api/inteljaringan/'.$id,'PUT',['status'=>'N']);
        }
      }catch(\Exception $e){
        $status_kelengkapan=false;
      }
    }
}

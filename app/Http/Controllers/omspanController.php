<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Transformers\Json;
use Auth;
use App\MainModel;
use URL;
use DateTime;
use Carbon\Carbon;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class omspanController extends Controller
{
	public $data;
	public function omspan(Request $request){
		$client = new Client();

		if($request->isMethod('post')) {
			$get = $request->all();
			
			if($get['periode']) {
				$filter = $get['periode'];
				$filter2 = $get['periode'];
				$filter3 = '';
				$filter4 = intval($get['periode']);
				$filterRealisasi = '';
				if($get['kode_satker']) {
					$filter = $get['kode_satker'] . '/' . $get['periode'];
					$filter2 = $get['periode'] . '/' . $get['kode_satker'];
					$filter3 = '/' . $get['kode_satker'];
					$filter4 = $get['kode_satker'] . '/' . intval($get['periode']);
					$filterRealisasi = $get['kode_satker'];
				}
				
				//$req_data_satker = $client->request('GET','http://10.210.84.13:8080/masterdata/api/view/list/satker'.$filter3);
				$req_data_satker = $client->request('GET','http://10.210.84.13:8080/satker/view/data/satker'.$filter3);
				$data_satker = json_decode($req_data_satker->getBody()->getContents(), true);
                
				$req_pengelolaan_up = $client->request('GET', config('app.url_soakemenkeu').'status_up/'.$filter);
				$pengelolaan_up = json_decode($req_pengelolaan_up->getBody()->getContents(), true);
				
				$req_data_kontrak = $client->request('GET', config('app.url_soakemenkeu').'p_datakontrak/'.$filter);
				$data_kontrak = json_decode($req_data_kontrak->getBody()->getContents(), true);
				
				$req_spm = $client->request('GET', config('app.url_soakemenkeu').'rekapsalahSPM/'.$filter);
				$data_spm = json_decode($req_spm->getBody()->getContents(), true);
				
				$req_pen_tagihan = $client->request('GET', config('app.url_soakemenkeu').'kemajuan/'.$filter);
				$pen_tagihan = json_decode($req_pen_tagihan->getBody()->getContents(), true);
				
				$req_rekon = $client->request('GET', config('app.url_soakemenkeu').'rekonLPJ/'.$filter4);
				$rekon = json_decode($req_rekon->getBody()->getContents(), true);
				
				$req_renkas = $client->request('GET', config('app.url_soakemenkeu').'renkas/'.$filter);
				$renkas = json_decode($req_renkas->getBody()->getContents(), true);
				
				$req_revisi = $client->request('GET', config('app.url_soakemenkeu').'revisidipa/'.$filter);
				$revisi = json_decode($req_revisi->getBody()->getContents(), true);
				
				$req_retur = $client->request('GET', config('app.url_soakemenkeu').'rekapretur/'.$filter);
				$retur = json_decode($req_retur->getBody()->getContents(), true);
				
				$req_pagudipa = $client->request('GET', config('app.url_soakemenkeu').'pagudipa'.$filter3);
				$pagudipa = json_decode($req_pagudipa->getBody()->getContents(), true);
				
				$params['headers'] = ['Content-Type' => 'application/json', 'Accept' => 'application/json'];
				$params['body'] = '{ "KD_SATKER" : "'.$filterRealisasi.'", "TAHUN" : "'.date("Y").'", "PERIODE" : "'.$get['periode'].'" }';
				$req_realisasi = $client->request('POST', 'http://10.210.84.13:8080/monevgar/api/realisasi/getBySatkerPeriode', $params);
				$realisasi = json_decode($req_realisasi->getBody()->getContents(), true);
				
				$req_3dipa = $client->request('GET', config('app.url_soakemenkeu').'hal_tiga_dipa'.$filter3);
				$hal_3dipa = json_decode($req_3dipa->getBody()->getContents(), true);
				
				$up_count = 0;
				$up_count_tepat_waktu = 0;
				$dkon_count = 0;
				$dkon_count_tepat_waktu = 0;
				$spm_salah_count = 0;
				$spm_total_count = 0;
				$ptagih_count = 0;
				$ptagih_count_tepat = 0;
				$rekon_count = 0;
				$rekon_count_tepat = 0;
				$renkas_count = 0;
				$renkas_count_tepat = 0;
				$revisi_count = 0;
				$retur_count = 0;
				$pagu = 0;
				$pagudipa_count = 0;
				$real = 0;
				$realisasi_count = 0;
				$triwulan = "";
				$rencana = 0;
				$deviasi = 0;
				$deviasi_persen = 0;
				$akumulasi_deviasi = 0;
				foreach($data_satker['data'] as $key => $row) {

					//***Rekap Data Pengelolaan UP***\\
					foreach($pengelolaan_up['data'] as $up) {
						if(trim($row['kd_satker']) == $up['kdsatker']) {
							if($up['status'] != '-') {
								if($up['status'] == 'TEPAT WAKTU') {
									$up_count_tepat_waktu++;
								}
								$up_count++;
							}
						}
					}
					if($up_count != 0) {					
						$data_satker['data'][$key]['up'] = ($up_count_tepat_waktu / $up_count) * 100;
					} else {
						$data_satker['data'][$key]['up'] = 0;
					}
					if($data_satker['data'][$key]['up'] == 0) {
						$data_satker['data'][$key]['bobot_up'] = 0;
					} else {
						$data_satker['data'][$key]['bobot_up'] = 10;
					}
					if($data_satker['data'][$key]['bobot_up'] != 0) {
						$data_satker['data'][$key]['na_up'] = round((round($data_satker['data'][$key]['up'],2) * ($data_satker['data'][$key]['bobot_up']/100)),2);
					} else {
						$data_satker['data'][$key]['na_up'] = 0;
					}
					$up_count = 0;
					$up_count_tepat_waktu = 0;

					//***Rekap Data Data Kontrak***\\
					foreach($data_kontrak['data'] as $dkon) {
						if(trim($row['kd_satker']) == $dkon['kdsatker']) {
							if($dkon['status'] != '-') {
								if($dkon['status'] == 'TEPAT WAKTU') {
									$dkon_count_tepat_waktu++;
								}
								$dkon_count++;
							}
						}
					}
					if($dkon_count != 0) {
						$data_satker['data'][$key]['dkon'] = ($dkon_count_tepat_waktu / $dkon_count) * 100;
					} else {
						$data_satker['data'][$key]['dkon'] = 0;
					}
					if($data_satker['data'][$key]['dkon'] == 0) {
						$data_satker['data'][$key]['bobot_dkon'] = 0;
					} else {
						$data_satker['data'][$key]['bobot_dkon'] = 10;
					}
					if($data_satker['data'][$key]['bobot_dkon'] != 0) {
						$data_satker['data'][$key]['na_dkon'] = round((round($data_satker['data'][$key]['dkon'],2) * ($data_satker['data'][$key]['bobot_dkon']/100)),2);
					} else {
						$data_satker['data'][$key]['na_dkon'] = 0;
					}
					$dkon_count_tepat_waktu = 0;
					$dkon_count = 0;
					
					//***Rekap Data Kesalahan SPM***\\
					foreach($data_spm['data'] as $spm) {
						if(trim($row['kd_satker']) == $spm['kdsatker']) {
							$spm_salah_count += $spm['akumulasi_salah'];
							$spm_total_count += $spm['total_spm'];
						}
					}
					if($spm_salah_count != 0) {					
						$data_satker['data'][$key]['spm'] = 100 - (($spm_salah_count / $spm_total_count) * 100);
					} else {
						$data_satker['data'][$key]['spm'] = 100;
					}
					if($data_satker['data'][$key]['spm'] == 100) {
						$data_satker['data'][$key]['bobot_spm'] = 5;
					} else {
						$data_satker['data'][$key]['bobot_spm'] = 0;
					}
					if($data_satker['data'][$key]['bobot_spm'] != 0) {
						$data_satker['data'][$key]['na_spm'] = round((round($data_satker['data'][$key]['spm'],2) * ($data_satker['data'][$key]['bobot_spm']/100)),2);
					} else {
						$data_satker['data'][$key]['na_spm'] = 0;
					}
					$spm_salah_count = 0;
					$spm_total_count = 0;
					
					//***Rekap Data Penyelesaian Tagihan***\\
					foreach($pen_tagihan['data'] as $ptagih) {
						if(trim($row['kd_satker']) == $ptagih['kdsatker']) {
							if($ptagih['status'] != 'null') {
								if($ptagih['status'] == 'TEPAT') {
									$ptagih_count_tepat++;
								}
								$ptagih_count++;
							}
						}
					}
					if($ptagih_count != 0) {
						$data_satker['data'][$key]['ptagih'] = ($ptagih_count_tepat / $ptagih_count) * 100;
					} else {
						$data_satker['data'][$key]['ptagih'] = 0;
					}
					if($data_satker['data'][$key]['ptagih'] == 0) {
						$data_satker['data'][$key]['bobot_ptagih'] = 0;
					} else {
						$data_satker['data'][$key]['bobot_ptagih'] = 20;
					}
					if($data_satker['data'][$key]['bobot_ptagih'] != 0) {
						$data_satker['data'][$key]['na_ptagih'] = round((round($data_satker['data'][$key]['ptagih'],2) * ($data_satker['data'][$key]['bobot_ptagih']/100)),2);
					} else {
						$data_satker['data'][$key]['na_ptagih'] = 0;
					}
					$ptagih_count = 0;
					$ptagih_count_tepat = 0;

					//***Rekap Rekon LPJ***\\
					foreach($rekon['data'] as $rk) {
						if(trim($row['kd_satker']) == $rk['kdsatker']) {
							if($rk['status'] != 'null') {
								if($rk['status'] == 'TEPAT') {
									$rekon_count_tepat++;
								}
								$rekon_count++;
							}
						}
					}
					if($rekon_count != 0) {
						$data_satker['data'][$key]['rekon'] = ($rekon_count_tepat / $rekon_count) * 100;
					} else {
						$data_satker['data'][$key]['rekon'] = 0;
					}
					if($data_satker['data'][$key]['rekon'] == 0) {
						$data_satker['data'][$key]['bobot_rekon'] = 0;
					} else {
						$data_satker['data'][$key]['bobot_rekon'] = 5;
					}
					if($data_satker['data'][$key]['bobot_rekon'] != 0) {
						$data_satker['data'][$key]['na_rekon'] = round((round($data_satker['data'][$key]['rekon'],2) * ($data_satker['data'][$key]['bobot_rekon']/100)),2);
					} else {
						$data_satker['data'][$key]['na_rekon'] = 0;
					}
					$rekon_count = 0;
					$rekon_count_tepat = 0;

					//***Rekap Renkas***\\
					foreach($renkas['data'] as $rks) {
						if(trim($row['kd_satker']) == $rks['kdsatker']) {
							if($rks['status'] != 'null') {
								if($rks['status'] == 'TEPAT') {
									$renkas_count_tepat++;
								}
								$renkas_count++;
							}
						}
					}
					if($renkas_count != 0) {
						$data_satker['data'][$key]['renkas'] = ($renkas_count_tepat / $renkas_count) * 100;
					} else {
						$data_satker['data'][$key]['renkas'] = 0;
					}
					if($data_satker['data'][$key]['renkas'] == 0) {
						$data_satker['data'][$key]['bobot_renkas'] = 0;
					} else {
						$data_satker['data'][$key]['bobot_renkas'] = 5;
					}
					if($data_satker['data'][$key]['bobot_renkas'] != 0) {
						$data_satker['data'][$key]['na_renkas'] = round((round($data_satker['data'][$key]['renkas'],2) * ($data_satker['data'][$key]['bobot_renkas']/100)),2);
					} else {
						$data_satker['data'][$key]['na_renkas'] = 0;
					}
					$renkas_count = 0;
					$renkas_count_tepat = 0;

					//***Rekap Revisi DIPA***\\
					foreach($revisi['data'] as $rvs) {
						if(trim($row['kd_satker']) == $rvs['kdsatker']) {
							if($rvs['nilai'] != 'null') {
								$revisi_count++;
							}
						}
					}
					if($revisi_count != 0) {
						$data_satker['data'][$key]['revisi'] = 100;
					} else {
						$data_satker['data'][$key]['revisi'] = 100;
					}
					if($data_satker['data'][$key]['revisi'] == 0) {
						$data_satker['data'][$key]['bobot_revisi'] = 0;
					} else {
						$data_satker['data'][$key]['bobot_revisi'] = 5;
					}
					if($data_satker['data'][$key]['bobot_revisi'] != 0) {
						$data_satker['data'][$key]['na_revisi'] = round((round($data_satker['data'][$key]['revisi'],2) * ($data_satker['data'][$key]['bobot_revisi']/100)),2);
					} else {
						$data_satker['data'][$key]['na_revisi'] = 0;
					}
					$revisi_count = 0;

					//***Rekap Retur SP2D***\\
					$jml_retur = 0;
					$jml_sp2d = 0;
					foreach($retur['data'] as $rtr) {
						if(trim($row['kd_satker']) == $rtr['kdsatker']) {
							if($rtr['jml_sp2d'] != 'null') {
								$jml_retur = $rtr['jml_retur'];
								$jml_sp2d = $rtr['jml_sp2d'];
								$retur_count++;
							}
						}
					}
					if($retur_count != 0) {
						$data_satker['data'][$key]['retur'] = 100 - (($jml_retur / $jml_sp2d) * 100);
					} else {
						$data_satker['data'][$key]['retur'] = 0;
					}
					if($data_satker['data'][$key]['retur'] == 0) {
						$data_satker['data'][$key]['bobot_retur'] = 0;
					} else {
						$data_satker['data'][$key]['bobot_retur'] = 5;
					}
					if($data_satker['data'][$key]['bobot_retur'] != 0) {
						$data_satker['data'][$key]['na_retur'] = round((round($data_satker['data'][$key]['retur'],2) * ($data_satker['data'][$key]['bobot_retur']/100)),2);
					} else {
						$data_satker['data'][$key]['na_retur'] = 0;
					}
					$retur_count = 0;
					
					//**Data Pagu Dipa**\\
					foreach($pagudipa['data'] as $pg) {
						if(trim($row['kd_satker']) == $pg['kdsatker']) {
							$pagudipa_count++;
							$pagu = $pg['pagudipa'];
						}
					}
					if($pagudipa_count != 0) {
						$data_satker['data'][$key]['pagudipa'] = $pagu;
					} else {
						$data_satker['data'][$key]['pagudipa'] = 0;
					}
					$pagudipa_count = 0;
					$pagu = 0;
					
					//**Data Realisasi**\\
					foreach($realisasi['data'] as $rl) {
						if(trim($row['kd_satker']) == $rl['kdSatker']) {
							$realisasi_count++;
							$real += $rl['jumlahRealisasi'];
						}
					}
					if($realisasi_count != 0) {
						$data_satker['data'][$key]['realisasi'] = $real;
					} else {
						$data_satker['data'][$key]['realisasi'] = 0;
					}
					$realisasi_count = 0;
					$real = 0;
					if($data_satker['data'][$key]['pagudipa'] != 0) {						
						$data_satker['data'][$key]['persen_realisasi'] = round(($data_satker['data'][$key]['realisasi'] / $data_satker['data'][$key]['pagudipa']) * 100,2);
					} else {
						$data_satker['data'][$key]['persen_realisasi'] = 0;
					}
					if(intval($get['periode']) <= 3) {
						$triwulan = 15;
					} else if(intval($get['periode']) > 3 && intval($get['periode']) <=6) {
						$triwulan = 45;
					} else if(intval($get['periode']) > 6 && intval($get['periode']) <=9) {
						$triwulan = 60;
					} else {
						$triwulan = 90;
					}
					$data_satker['data'][$key]['nilai_realisasi'] = ($data_satker['data'][$key]['persen_realisasi'] / $triwulan) *100;
					if($data_satker['data'][$key]['nilai_realisasi'] == 0) {
						$data_satker['data'][$key]['bobot_realisasi'] = 0;
					} else {
						$data_satker['data'][$key]['bobot_realisasi'] = 20;
					}
					if($data_satker['data'][$key]['bobot_realisasi'] != 0) {
						$data_satker['data'][$key]['na_realisasi'] = round((round($data_satker['data'][$key]['nilai_realisasi'],2) * ($data_satker['data'][$key]['bobot_realisasi']/100)),2);
					} else {
						$data_satker['data'][$key]['na_realisasi'] = 0;
					}
					
					////***Rekap Hal III Dipa***\\
					//foreach($hal_3dipa['data'] as $dipa) {
					//	if(trim($row['kd_satker']) == $dipa['kdsatker']) {
					//		$rencana = $dipa['ren_'.$get['periode']];
					//	}
					//}
					//$data_satker['data'][$key]['hal3dipa_ren'] = $rencana;
					//
					//foreach($realisasi['data'] as $realis) {
					//	if(trim($row['kd_satker']) == $realis['kdsatker']) {
					//		$deviasi = $realis['jumlahRealisasi'] - $rencana
					//		$akumulasi_deviasi +=
					//	}
					//}
					
					
					$data_satker['data'][$key]['hal3dipa'] = 0;
					$rencana = 0;
				}
				//echo "<pre>";
				//print_r($data_satker);
				//echo "</pre>";
				//die;
				$this->data['rekap'] = $data_satker['data'];
				$this->data['periode'] = $get['periode'];
				return view('omspan.view', $this->data);
			}
		}

		return view('omspan.index');
	}
		
	public function getpengelolaanup(Request $request) {
		$client = new Client();
		try {
			$kdSatker = $request['kdSatker'];
			$periode = $request['periode'];
			
			$req_pengelolaan_up = $client->request('GET', config('app.url_soakemenkeu').'status_up/'.$kdSatker . '/' . $periode);
			$data = json_decode($req_pengelolaan_up->getBody()->getContents(), true);
			
			return $data;
			//if (!$data){
            //  return response()->json(Json::response(null, 'error', "data kosong", 404), 200);
            //} else {
            //  return response()->json(Json::response($data, 'sukses', null), 200);
            //}
        } catch(\Exception $e) {
			return response()->json(Json::response(null, 'error', $e->getMessage()), 200);
        }
	}
	
	public function getdatakontrak(Request $request) {
		$client = new Client();
		try {
			$kdSatker = $request['kdSatker'];
			$periode = $request['periode'];
			
			$req_data_kontrak = $client->request('GET', config('app.url_soakemenkeu').'p_datakontrak/'.$kdSatker.'/'.$periode);
			$data = json_decode($req_data_kontrak->getBody()->getContents(), true);
			
			return $data;
        } catch(\Exception $e) {
			return response()->json(Json::response(null, 'error', $e->getMessage()), 200);
        }
	}
	
	public function getspm(Request $request) {
		$client = new Client();
		try {
			$kdSatker = $request['kdSatker'];
			$periode = $request['periode'];
			$array_periode = ['01', '02', '03', '04'];
			$req_spm = $client->request('GET', config('app.url_soakemenkeu').'rekapsalahSPM/'.$kdSatker.'/'.$periode);
			$data = json_decode($req_spm->getBody()->getContents(), true);
			if($data['status'] == 'OK') {
				if(count($data['data']) < intval($periode)) {
					$arp = [
						'kdsatker' => $data['data'][0]['kdsatker'],
						'akumulasi_salah' => 0,
						'total_spm' => 0,
						'periode' => "01",
						'instansiName' => $data['data'][0]['instansiName'],
					];
					array_unshift($data['data'], $arp);
				}
			}
			
			return $data;
        } catch(\Exception $e) {
			return response()->json(Json::response(null, 'error', $e->getMessage()), 200);
        }
	}
	
	public function getretur(Request $request) {
		$client = new Client();
		try {
			$kdSatker = $request['kdSatker'];
			$periode = $request['periode'];
			
			$req_retur = $client->request('GET', config('app.url_soakemenkeu').'rekapretur/'.$kdSatker . '/' . $periode);
			$data = json_decode($req_retur->getBody()->getContents(), true);
			
			return $data;
        } catch(\Exception $e) {
			return response()->json(Json::response(null, 'error', $e->getMessage()), 200);
        }
	}
	
	public function getrenkas(Request $request) {
		$client = new Client();
		try {
			$kdSatker = $request['kdSatker'];
			$periode = $request['periode'];
			
			$req_renkas = $client->request('GET', config('app.url_soakemenkeu').'renkas/'.$kdSatker . '/' . $periode);
			$data = json_decode($req_renkas->getBody()->getContents(), true);
			
			return $data;
        } catch(\Exception $e) {
			return response()->json(Json::response(null, 'error', $e->getMessage()), 200);
        }
	}
	
	public function gethal3dipa(Request $request) {
		$client = new Client();
		try {
			$kdSatker = $request['kdSatker'];
			$periode = $request['periode'];
			
			$req_hal3dipa = $client->request('GET', config('app.url_soakemenkeu').'hal_tiga_dipa/'.$kdSatker);
			$data = json_decode($req_hal3dipa->getBody()->getContents(), true);
			
			return $data;
        } catch(\Exception $e) {
			return response()->json(Json::response(null, 'error', $e->getMessage()), 200);
        }
	}
	
	public function getrevisi(Request $request) {
		$client = new Client();
		try {
			$kdSatker = $request['kdSatker'];
			$periode = $request['periode'];
			
			$req_revisi = $client->request('GET', config('app.url_soakemenkeu').'revisidipa/'.$kdSatker.'/'.$periode);
			$data = json_decode($req_revisi->getBody()->getContents(), true);
			
			return $data;
        } catch(\Exception $e) {
			return response()->json(Json::response(null, 'error', $e->getMessage()), 200);
        }
	}
	
	public function gettagihan(Request $request) {
		$client = new Client();
		try {
			$kdSatker = $request['kdSatker'];
			$periode = $request['periode'];
			
			$req_pen_tagihan = $client->request('GET', config('app.url_soakemenkeu').'kemajuan/'.$kdSatker.'/'.$periode);
			$data = json_decode($req_pen_tagihan->getBody()->getContents(), true);
			
			return $data;
        } catch(\Exception $e) {
			return response()->json(Json::response(null, 'error', $e->getMessage()), 200);
        }
	}
	
	public function getrekon(Request $request) {
		$client = new Client();
		try {
				
			$kdSatker = $request['kdSatker'];
			$periode = intval($request['periode']);
			
			$req_rekon = $client->request('GET', config('app.url_soakemenkeu').'rekonLPJ/'.$kdSatker.'/'.$periode);
			$data = json_decode($req_rekon->getBody()->getContents(), true);
			
			return $data;
        } catch(\Exception $e) {
			return response()->json(Json::response(null, 'error', $e->getMessage()), 200);
        }
	}
	
	public function getrealisasi(Request $request) {
		$client = new Client();
		try {
				
			$kdSatker = $request['kdSatker'];
			$periode = $request['periode'];
			
			$req_pagudipa = $client->request('GET', config('app.url_soakemenkeu').'pagudipa/'.$kdSatker);
			$pagudipa = json_decode($req_pagudipa->getBody()->getContents(), true);
				
			$params['headers'] = ['Content-Type' => 'application/json', 'Accept' => 'application/json'];
			$params['body'] = '{ "KD_SATKER" : "'.$kdSatker.'", "TAHUN" : "'.date("Y").'", "PERIODE" : "'.$periode.'" }';
			$req_realisasi = $client->request('POST', 'http://10.210.84.13:8080/monevgar/api/realisasi/getBySatkerPeriode', $params);
			$data = json_decode($req_realisasi->getBody()->getContents(), true);
			if($data['code'] == '200') {
				if($pagudipa['code'] == '200') {
					$data['pagu'] = $pagudipa['data'][0]['pagudipa'];
				}
			}
			
			return $data;
        } catch(\Exception $e) {
			return response()->json(Json::response(null, 'error', $e->getMessage()), 200);
        }
	}
}

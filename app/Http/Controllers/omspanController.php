<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
		$token = $request->session()->get('token');
		$token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c3IiOiJXZWJzZXJ2aWNlIEJOTiIsInVpZCI6IkJOTiIsInJvbCI6IndlYnNlcnZpY2UiLCJrZHMiOiJXRUJTWDQiLCJrZGIiOiIiLCJrZHQiOiIyMDE4IiwiaWF0IjoxNTI3NzUwODk1LCJuYmYiOjE1Mjc3NTAyOTUsImtpZCI6IjY4MSJ9.EJ9Gd5vPWfDYotVFS2c0AqFHcUOen4bnnA1K_5Vc36o';

		if($request->isMethod('post')) {
			$get = $request->all();
			//echo "<pre>";
			//print_r($get);
			//echo "</pre>";
			
			if($get['periode']) {
				$filter = $get['periode'];
				if($get['kode_satker']) {
					$filter = $get['kode_satker'] . '/' . $get['periode'];
				}
				
				//$params['headers'] = ['Content-Type' => 'application/json', 'Accept' => 'application/json'];
				//$params['body'] = '{ "KDSATKER" : "", "KPPN" : "", "BA" : "", "BAES1" : "", "AKUN" : "", "PROGRAM" : "", "KEGIATAN" : "", "OUTPUT" : "", "KEWENANGAN" : "", "SUMBER_DANA" : "", "LOKASI" : "", "BUDGET_TYPE" : "", "AMOUNT" : "", "Limit" : "10", "Page" : "" }';
				//$requestdatadipa = $client->request('POST', config('app.url_soadev2').'KemenkeuDataDipa/DataDipaQueryRS', $params);
				//$datadipa = json_decode($requestdatadipa->getBody()->getContents(), true);
				
				$req_data_satker = $client->request('GET','http://103.3.70.167:8080/masterdata/api/view/list/satker');
				$data_satker = json_decode($req_data_satker->getBody()->getContents(), true);
				
				$req_pengelolaan_up = $client->request('GET', config('app.url_soakemenkeu').'status_up/'.$filter);
				$pengelolaan_up = json_decode($req_pengelolaan_up->getBody()->getContents(), true);
				
				$req_data_kontrak = $client->request('GET', config('app.url_soakemenkeu').'p_datakontrak');
				$data_kontrak = json_decode($req_data_kontrak->getBody()->getContents(), true);
				
				$req_pen_tagihan = $client->request('GET', config('app.url_soakemenkeu').'kemajuan');
				$pen_tagihan = json_decode($req_pen_tagihan->getBody()->getContents(), true);
				
				$req_rekon = $client->request('GET', config('app.url_soakemenkeu').'rekonLPJ');
				$rekon = json_decode($req_rekon->getBody()->getContents(), true);
				
				$req_renkas = $client->request('GET', config('app.url_soakemenkeu').'renkas');
				$renkas = json_decode($req_renkas->getBody()->getContents(), true);
				
				$req_revisi = $client->request('GET', config('app.url_soakemenkeu').'revisidipa');
				$revisi = json_decode($req_revisi->getBody()->getContents(), true);
				
				$req_retur = $client->request('GET', config('app.url_soakemenkeu').'rekapretur');
				$retur = json_decode($req_retur->getBody()->getContents(), true);
				
				$up_count = 0;
				$up_count_tepat_waktu = 0;
				$dkon_count = 0;
				$dkon_count_tepat_waktu = 0;
				$ptagih_count = 0;
				$ptagih_count_tepat = 0;
				$rekon_count = 0;
				$rekon_count_tepat = 0;
				$renkas_count = 0;
				$renkas_count_tepat = 0;
				$revisi_count = 0;
				$retur_count = 0;
				foreach($data_satker['data'] as $key => $row) {
					
					//***Rekap Data Pengelolaan UP***\\
					foreach($pengelolaan_up['data'] as $up) {
						if(trim($row['kdInstansi']) == $up['kdsatker']) {
							if($up['status'] != '-') {
								if($up['status'] == 'TEPAT WAKTU') {
									$up_count_tepat_waktu++;
								}
								$up_count++;
							}
						}
					}
					//$data_satker['data'][$key]['total'] = $up_count;
					//$data_satker['data'][$key]['tepat_waktu'] = $up_count_tepat_waktu;
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
						$data_satker['data'][$key]['na_up'] = round((round($data_satker['data'][$key]['up'],2) / $data_satker['data'][$key]['bobot_up']),2);
					} else {
						$data_satker['data'][$key]['na_up'] = 0;
					}
					$up_count = 0;
					$up_count_tepat_waktu = 0;
					
					//***Rekap Data Data Kontrak***\\
					foreach($data_kontrak['data'] as $dkon) {
						if(trim($row['kdInstansi']) == $dkon['kdsatker']) {
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
						$data_satker['data'][$key]['na_dkon'] = round((round($data_satker['data'][$key]['dkon'],2) / $data_satker['data'][$key]['bobot_dkon']),2);
					} else {
						$data_satker['data'][$key]['na_dkon'] = 0;
					}
					$dkon_count = 0;
					$dkon_count_tepat_waktu = 0;
					
					//***Rekap Data Penyelesaian Tagihan***\\
					foreach($pen_tagihan['data'] as $ptagih) {
						if(trim($row['kdInstansi']) == $ptagih['kdsatker']) {
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
						$data_satker['data'][$key]['na_ptagih'] = round((round($data_satker['data'][$key]['ptagih'],2) / $data_satker['data'][$key]['bobot_ptagih']),2);
					} else {
						$data_satker['data'][$key]['na_ptagih'] = 0;
					}
					$ptagih_count = 0;
					$ptagih_count_tepat = 0;
					
					//***Rekap Rekon LPJ***\\
					foreach($rekon['data'] as $rk) {
						if(trim($row['kdInstansi']) == $rk['kdsatker']) {
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
						$data_satker['data'][$key]['na_rekon'] = round((round($data_satker['data'][$key]['rekon'],2) / $data_satker['data'][$key]['bobot_rekon']),2);
					} else {
						$data_satker['data'][$key]['na_rekon'] = 0;
					}
					$rekon_count = 0;
					$rekon_count_tepat = 0;
					
					//***Rekap Renkas***\\
					foreach($renkas['data'] as $rks) {
						if(trim($row['kdInstansi']) == $rks['kdsatker']) {
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
						$data_satker['data'][$key]['na_renkas'] = round((round($data_satker['data'][$key]['renkas'],2) / $data_satker['data'][$key]['bobot_renkas']),2);
					} else {
						$data_satker['data'][$key]['na_renkas'] = 0;
					}
					$renkas_count = 0;
					$renkas_count_tepat = 0;
					
					//***Rekap Revisi DIPA***\\
					foreach($revisi['data'] as $rvs) {
						if(trim($row['kdInstansi']) == $rvs['kdsatker']) {
							if($rvs['nilai'] != 'null') {
								$revisi_count++;
							}
						}
					}
					if($revisi_count != 0) {					
						$data_satker['data'][$key]['revisi'] = 100;
					} else {
						$data_satker['data'][$key]['revisi'] = 0;
					}
					if($data_satker['data'][$key]['revisi'] == 0) {
						$data_satker['data'][$key]['bobot_revisi'] = 0;
					} else {
						$data_satker['data'][$key]['bobot_revisi'] = 5;
					}
					if($data_satker['data'][$key]['bobot_revisi'] != 0) {
						$data_satker['data'][$key]['na_revisi'] = round((round($data_satker['data'][$key]['revisi'],2) / $data_satker['data'][$key]['bobot_revisi']),2);
					} else {
						$data_satker['data'][$key]['na_revisi'] = 0;
					}
					$revisi_count = 0;
					
					//***Rekap Retur SP2D***\\
					$jml_retur = 0;
					$jml_sp2d = 0;
					foreach($retur['data'] as $rtr) {
						if(trim($row['kdInstansi']) == $rtr['kdsatker']) {
							if($rtr['jml_sp2d'] != 'null') {
								$jml_retur = $rtr['jml_retur'];
								$jml_sp2d = $rtr['jml_sp2d'];
								$retur_count++;
							}
						}
					}
					if($retur_count != 0) {
						//$data_satker['data'][$key]['jml_retur'] = $jml_retur;
						//$data_satker['data'][$key]['jml_sp2d'] = $jml_sp2d;
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
						$data_satker['data'][$key]['na_retur'] = round((round($data_satker['data'][$key]['retur'],2) / $data_satker['data'][$key]['bobot_retur']),2);
					} else {
						$data_satker['data'][$key]['na_retur'] = 0;
					}
					$retur_count = 0;
				}
				
				//$req_revisi_dipa = $client->request('GET', config('app.url_soakemenkeu').'revisidipa');
				//$revisi_dipa = json_decode($req_revisi_dipa->getBody()->getContents(), true);
				//echo "3386 row<pre>";
				//print_r($revisi_dipa);
				//echo "</pre>";
				$this->data['rekap'] = $data_satker['data'];
				return view('omspan.view', $this->data);
				//die;
			}
		}

		return view('omspan.index');
	}
}

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
				$filterRealisasi = '';
				if($get['kode_satker']) {
					$filter = $get['kode_satker'] . '/' . $get['periode'];
					$filter2 = $get['periode'] . '/' . $get['kode_satker'];
					$filter3 = '/' . $get['kode_satker'];
					$filterRealisasi = $get['kode_satker'];
				}
				
				$req_data_satker = $client->request('GET','http://10.210.84.13:8080/satker/view/data/satker'.$filter3);
				$data_satker = json_decode($req_data_satker->getBody()->getContents(), true);
				
				$this->data['breadcrumps'] = breadcrumps($request->route()->getName());
				$this->data['title'] = "Indikator Pelaksanaan Anggaran";
				$this->data['periode'] = $get['periode'];
				$this->data['periode_name'] = $this->getperiode($get['periode']);
				$this->data['filter_indikator'] = $get['filter_indikator'];
				
				if($get['filter_indikator'] == '-') {
				                
					$req_pengelolaan_up = $client->request('GET', config('app.url_soakemenkeu').'status_up/'.$filter);
					$pengelolaan_up = json_decode($req_pengelolaan_up->getBody()->getContents(), true);
					
					$req_data_kontrak = $client->request('GET', config('app.url_soakemenkeu').'p_datakontrak/'.$filter);
					$data_kontrak = json_decode($req_data_kontrak->getBody()->getContents(), true);
					
					$req_spm = $client->request('GET', config('app.url_soakemenkeu').'rekapsalahSPM/'.$filter);
					$data_spm = json_decode($req_spm->getBody()->getContents(), true);
					
					$req_pen_tagihan = $client->request('GET', config('app.url_soakemenkeu').'kemajuan/'.$filter);
					$pen_tagihan = json_decode($req_pen_tagihan->getBody()->getContents(), true);
					
					$req_rekon = $client->request('GET', 'http://10.210.84.13:8080/kemenkeunew/api/data/rekonLPJ/'.$filter);
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
					//$req_realisasi = $client->request('POST', 'http://10.210.84.13:8080/monevgar/api/realisasi/getBySatkerPeriode', $params);
					$req_realisasi = $client->request('GET', config('app.url_soakemenkeu').'realisasi/'.$filter);
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
					$real_3dipa = 0;
					$deviasi = 0;
					$deviasi_persen = 0;
					$akumulasi_deviasi = 0;
					$periode_dev = 0;
					$periode_dev2 = 0;
					$rata_deviasi = 0;
					$rata_deviasi1 = 0;
					$na_deviasi = 0;
					$arr_rencana = [];
					$arr_realisasi = [];
					
					
					
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
							if(trim($row['kd_satker']) == $rl['kdsatker']) {
								$realisasi_count++;
								$real += $rl['jumlah_realisasi'];
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
							$triwulan = 40;
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
						
						
						foreach($hal_3dipa['data'] as $dipa) {
							if(trim($row['kd_satker']) == $dipa['kdsatker']) {
								$arr_rencana = $dipa;
							}
						}
						foreach($realisasi['data'] as $kr => $realis) {
							if(trim($row['kd_satker']) == $realis['kdsatker']) {
								$arr_realisasi[$kr] = $realis;
							}
						}
						$arr_rencana['realisasi'] = $arr_realisasi;
						
						for($a = 0; $a < intval($get['periode']); $a++) {
							$periode_dev++;
							foreach($arr_rencana['realisasi'] as $rl) {
								if(str_pad($periode_dev, 2, '0', STR_PAD_LEFT) == $rl['periode']) {
									$real_3dipa = $rl['jumlah_realisasi'];
								}
							}
							$deviasi = abs($real_3dipa - $arr_rencana['ren_'.str_pad($periode_dev, 2, '0', STR_PAD_LEFT)]);
							if($arr_rencana['ren_'.str_pad($periode_dev, 2, '0', STR_PAD_LEFT)] != 0) {									
								$deviasi_persen = ($deviasi / $arr_rencana['ren_'.str_pad($periode_dev, 2, '0', STR_PAD_LEFT)]) * 100;
							} else {
								$deviasi_persen = 0;
							}
							$akumulasi_deviasi = $deviasi_persen + $akumulasi_deviasi;
							$rata_deviasi = $akumulasi_deviasi / $periode_dev;
							if($periode_dev == 1) {
								if($rata_deviasi != 0) {								
									$rata_deviasi1 = $rata_deviasi;
								} else {
									$rata_deviasi1 = 100;
								}
							}
							$na_deviasi = $rata_deviasi1 - $rata_deviasi;
							if($na_deviasi < 0 ) {
								$na_deviasi = 0;
							}
							
							$data_satker['data'][$key]['hal3dipa'] = $na_deviasi;
							
							if($data_satker['data'][$key]['hal3dipa'] == 0) {
								$data_satker['data'][$key]['bobot_3dipa'] = 0;
							} else {
								$data_satker['data'][$key]['bobot_3dipa'] = 5;
							}
							if($data_satker['data'][$key]['bobot_3dipa'] != 0) {
								$data_satker['data'][$key]['na_3dipa'] = round((round($data_satker['data'][$key]['hal3dipa'],2) * ($data_satker['data'][$key]['bobot_3dipa']/100)),2);
							} else {
								$data_satker['data'][$key]['na_3dipa'] = 0;
							}
							
							$real_3dipa = 0;
							$deviasi = 0;
							$deviasi_persen = 0;
							$rata_deviasi = 0;
							$na_deviasi = 0;
						}
						$akumulasi_deviasi = 0;
						$periode_dev = 0;
						$real_3dipa = 0;
						$deviasi = 0;
						$deviasi_persen = 0;
						$rata_deviasi = 0;
						$na_deviasi = 0;
						$arr_rencana = [];
						$arr_realisasi = [];
					}
					$this->data['rekap'] = $data_satker['data'];
					return view('omspan.view', $this->data);
				
				} elseif($get['filter_indikator'] == '1') {
					//** Filter Indikator Pengelolaan UP **\\
					$req_pengelolaan_up = $client->request('GET', config('app.url_soakemenkeu').'status_up/'.$filter);
					$pengelolaan_up = json_decode($req_pengelolaan_up->getBody()->getContents(), true);
					
					$up_count = 0;
					$up_count_tepat_waktu = 0;
					
					foreach($data_satker['data'] as $key => $row) {
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
					}
					
					$this->data['rekap'] = $data_satker['data'];
					return view('omspan.pengelolaan_up', $this->data);
				} elseif($get['filter_indikator'] == '2') {
					//** Filter Indikator Data Kontrak **\\
					$req_data_kontrak = $client->request('GET', config('app.url_soakemenkeu').'p_datakontrak/'.$filter);
					$data_kontrak = json_decode($req_data_kontrak->getBody()->getContents(), true);
					
					$dkon_count = 0;
					$dkon_count_tepat_waktu = 0;
					
					foreach($data_satker['data'] as $key => $row) {
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
					}
					$this->data['rekap'] = $data_satker['data'];
					return view('omspan.data_kontrak', $this->data);
				} elseif($get['filter_indikator'] == '3') {
					//** Filter Indikator Kesalahan SPM **\\
					$req_spm = $client->request('GET', config('app.url_soakemenkeu').'rekapsalahSPM/'.$filter);
					$data_spm = json_decode($req_spm->getBody()->getContents(), true);
					
					$spm_salah_count = 0;
					$spm_total_count = 0;
					
					foreach($data_satker['data'] as $key => $row) {
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
					}
					$this->data['rekap'] = $data_satker['data'];
					return view('omspan.kesalahan_spm', $this->data);
					
				} elseif($get['filter_indikator'] == '4') {
					//** Filter Indikator Retur SP2D **\\
					
					$req_retur = $client->request('GET', config('app.url_soakemenkeu').'rekapretur/'.$filter);
					$retur = json_decode($req_retur->getBody()->getContents(), true);
					
					$retur_count = 0;
					
					foreach($data_satker['data'] as $key => $row) {
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
					}
					$this->data['rekap'] = $data_satker['data'];
					return view('omspan.retur_sp2d', $this->data);
				} elseif($get['filter_indikator'] == '5') {
					//** Filter Indikator Hal III Dipa **\\
					
					$pagudipa_count = 0;
					$pagu = 0;
					
					$hal_3dipa_count = 0;
					
					$real = 0;
					$realisasi_count = 0;
					$triwulan = "";
					$rencana = 0;
					$real_3dipa = 0;
					$deviasi = 0;
					$deviasi_persen = 0;
					$akumulasi_deviasi = 0;
					$periode_dev = 0;
					$periode_dev2 = 0;
					$rata_deviasi = 0;
					$rata_deviasi1 = 0;
					$na_deviasi = 0;
					$arr_rencana = [];
					$arr_realisasi = [];
					
					$req_3dipa = $client->request('GET', config('app.url_soakemenkeu').'hal_tiga_dipa'.$filter3);
					$hal_3dipa = json_decode($req_3dipa->getBody()->getContents(), true);
					
					$req_pagudipa = $client->request('GET', config('app.url_soakemenkeu').'pagudipa/'.$filter3);
					$pagudipa = json_decode($req_pagudipa->getBody()->getContents(), true);

					$req_realisasi = $client->request('GET', config('app.url_soakemenkeu').'realisasi/'.$filter);
					$realisasi = json_decode($req_realisasi->getBody()->getContents(), true);
					
					
					foreach($data_satker['data'] as $key => $row) {
						
						// realisasi //
						foreach($realisasi['data'] as $rl) {
							if(trim($row['kd_satker']) == $rl['kdsatker']) {
								$realisasi_count++;
								$real += $rl['jumlah_realisasi'];
							}
						}
						if($realisasi_count != 0) {
							$data_satker['data'][$key]['realisasi'] = $real;
						} else {
							$data_satker['data'][$key]['realisasi'] = 0;
						}
						$realisasi_count = 0;
						$real = 0;
						
						// pagudipa //
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
						if($data_satker['data'][$key]['pagudipa'] != 0) {						
							$data_satker['data'][$key]['persen_realisasi'] = round(($data_satker['data'][$key]['realisasi'] / $data_satker['data'][$key]['pagudipa']) * 100,2);
						} else {
							$data_satker['data'][$key]['persen_realisasi'] = 0;
						}
						if(intval($get['periode']) <= 3) {
							$triwulan = 15;
						} else if(intval($get['periode']) > 3 && intval($get['periode']) <=6) {
							$triwulan = 40;
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
						
						
						foreach($hal_3dipa['data'] as $dipa) {
							if(trim($row['kd_satker']) == $dipa['kdsatker']) {
								$arr_rencana = $dipa;
							}
						}
						foreach($realisasi['data'] as $kr => $realis) {
							if(trim($row['kd_satker']) == $realis['kdsatker']) {
								$arr_realisasi[$kr] = $realis;
							}
						}
						$arr_rencana['realisasi'] = $arr_realisasi;
						
						for($a = 0; $a < intval($get['periode']); $a++) {
							$periode_dev++;
							foreach($arr_rencana['realisasi'] as $rl) {
								if(str_pad($periode_dev, 2, '0', STR_PAD_LEFT) == $rl['periode']) {
									$real_3dipa = $rl['jumlah_realisasi'];
								}
							}
							$deviasi = abs($real_3dipa - $arr_rencana['ren_'.str_pad($periode_dev, 2, '0', STR_PAD_LEFT)]);
							if($arr_rencana['ren_'.str_pad($periode_dev, 2, '0', STR_PAD_LEFT)] != 0) {									
								$deviasi_persen = ($deviasi / $arr_rencana['ren_'.str_pad($periode_dev, 2, '0', STR_PAD_LEFT)]) * 100;
							} else {
								$deviasi_persen = 0;
							}
							$akumulasi_deviasi = $deviasi_persen + $akumulasi_deviasi;
							$rata_deviasi = $akumulasi_deviasi / $periode_dev;
							if($periode_dev == 1) {
								if($rata_deviasi != 0) {								
									$rata_deviasi1 = $rata_deviasi;
								} else {
									$rata_deviasi1 = 100;
								}
							}
							$na_deviasi = $rata_deviasi1 - $rata_deviasi;
							if($na_deviasi < 0 ) {
								$na_deviasi = 0;
							}
							
							$data_satker['data'][$key]['hal3dipa'] = $na_deviasi;
							
							if($data_satker['data'][$key]['hal3dipa'] == 0) {
								$data_satker['data'][$key]['bobot_3dipa'] = 0;
							} else {
								$data_satker['data'][$key]['bobot_3dipa'] = 5;
							}
							if($data_satker['data'][$key]['bobot_3dipa'] != 0) {
								$data_satker['data'][$key]['na_3dipa'] = round((round($data_satker['data'][$key]['hal3dipa'],2) * ($data_satker['data'][$key]['bobot_3dipa']/100)),2);
							} else {
								$data_satker['data'][$key]['na_3dipa'] = 0;
							}
							
							$real_3dipa = 0;
							$deviasi = 0;
							$deviasi_persen = 0;
							$rata_deviasi = 0;
							$na_deviasi = 0;
						}
						$akumulasi_deviasi = 0;
						$periode_dev = 0;
						$real_3dipa = 0;
						$deviasi = 0;
						$deviasi_persen = 0;
						$rata_deviasi = 0;
						$na_deviasi = 0;
						$arr_rencana = [];
						$arr_realisasi = [];
					}
					
					$this->data['rekap'] = $data_satker['data'];
					return view('omspan.hal_3dipa', $this->data);
				} elseif($get['filter_indikator'] == '6') {
					//** Filter Indikator Revisi Dipa **\\
					$req_revisi = $client->request('GET', config('app.url_soakemenkeu').'revisidipa/'.$filter);
					$revisi = json_decode($req_revisi->getBody()->getContents(), true);
					
					$revisi_count = 0;
					foreach($data_satker['data'] as $key => $row) {
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
					}
					$this->data['rekap'] = $data_satker['data'];
					return view('omspan.revisi_dipa', $this->data);
				} elseif($get['filter_indikator'] == '7') {
					//** Filter Indikator Penyelesaian Tagihan **\\
					$req_pen_tagihan = $client->request('GET', config('app.url_soakemenkeu').'kemajuan/'.$filter);
					$pen_tagihan = json_decode($req_pen_tagihan->getBody()->getContents(), true);
					
					$ptagih_count = 0;
					$ptagih_count_tepat = 0;
					foreach($data_satker['data'] as $key => $row) {
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
					}
					$this->data['rekap'] = $data_satker['data'];
					return view('omspan.penyelesaian_tagihan', $this->data);
				} elseif($get['filter_indikator'] == '8') {
					//** Filter Indikator Rekon LPJ **\\
					
					$req_rekon = $client->request('GET', 'http://10.210.84.13:8080/kemenkeunew/api/data/rekonLPJ/'.$filter);
					$rekon = json_decode($req_rekon->getBody()->getContents(), true);
				
					$rekon_count = 0;
					$rekon_count_tepat = 0;
					
					foreach($data_satker['data'] as $key => $row) {
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
					}
					$this->data['rekap'] = $data_satker['data'];
					return view('omspan.rekon_lpj', $this->data);
				} elseif($get['filter_indikator'] == '9') {
					//** Filter Indikator Renkas **\\
					
					$req_renkas = $client->request('GET', config('app.url_soakemenkeu').'renkas/'.$filter);
					$renkas = json_decode($req_renkas->getBody()->getContents(), true);
					
					$renkas_count = 0;
					$renkas_count_tepat = 0;
					foreach($data_satker['data'] as $key => $row) {
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
					}
					$this->data['rekap'] = $data_satker['data'];
					return view('omspan.renkas', $this->data);
				} elseif($get['filter_indikator'] == '10') {
					//** Filter Indikator Realisasi **\\
					
					$pagudipa_count = 0;
					$pagu = 0;
					
					$hal_3dipa_count = 0;
					
					$real = 0;
					$realisasi_count = 0;
					$triwulan = "";
					
					$req_3dipa = $client->request('GET', config('app.url_soakemenkeu').'hal_tiga_dipa'.$filter3);
					$hal_3dipa = json_decode($req_3dipa->getBody()->getContents(), true);
					
					$req_pagudipa = $client->request('GET', config('app.url_soakemenkeu').'pagudipa/'.$filter3);
					$pagudipa = json_decode($req_pagudipa->getBody()->getContents(), true);

					$req_realisasi = $client->request('GET', config('app.url_soakemenkeu').'realisasi/'.$filter);
					$realisasi = json_decode($req_realisasi->getBody()->getContents(), true);
					
					
					foreach($data_satker['data'] as $key => $row) {
						
						// realisasi //
						foreach($realisasi['data'] as $rl) {
							if(trim($row['kd_satker']) == $rl['kdsatker']) {
								$realisasi_count++;
								$real += $rl['jumlah_realisasi'];
							}
						}
						if($realisasi_count != 0) {
							$data_satker['data'][$key]['realisasi'] = $real;
						} else {
							$data_satker['data'][$key]['realisasi'] = 0;
						}
						$realisasi_count = 0;
						$real = 0;
						
						// pagudipa //
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
						if($data_satker['data'][$key]['pagudipa'] != 0) {						
							$data_satker['data'][$key]['persen_realisasi'] = round(($data_satker['data'][$key]['realisasi'] / $data_satker['data'][$key]['pagudipa']) * 100,2);
						} else {
							$data_satker['data'][$key]['persen_realisasi'] = 0;
						}
						if(intval($get['periode']) <= 3) {
							$triwulan = 15;
						} else if(intval($get['periode']) > 3 && intval($get['periode']) <=6) {
							$triwulan = 40;
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
						
					}
					
					$this->data['rekap'] = $data_satker['data'];
					
					return view('omspan.realisasi', $this->data);
				}
			}
		}
		$this->data['breadcrumps'] = breadcrumps($request->route()->getName());
		$this->data['title'] = "Indikator Pelaksanaan Anggaran";
		return view('omspan.index', $this->data);
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
			$filter = $kdSatker.'/'.$periode;
			
			$req_hal3dipa = $client->request('GET', config('app.url_soakemenkeu').'hal_tiga_dipa/'.$kdSatker);
			$data = json_decode($req_hal3dipa->getBody()->getContents(), true);
			
			$params['headers'] = ['Content-Type' => 'application/json', 'Accept' => 'application/json'];
			$params['body'] = '{ "KD_SATKER" : "'.$kdSatker.'", "TAHUN" : "'.date("Y").'", "PERIODE" : "'.$periode.'" }';
			//$req_realisasi = $client->request('POST', 'http://10.210.84.13:8080/monevgar/api/realisasi/getBySatkerPeriode', $params);
			$req_realisasi = $client->request('GET', config('app.url_soakemenkeu').'realisasi/'.$filter);
			$realisasi = json_decode($req_realisasi->getBody()->getContents(), true);
			if($data['code'] == '200') {
				if($realisasi['code'] == '200') {
					$data['realisasi'] = $realisasi['data'];
				}
			}
			
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
			$periode = $request['periode'];
			
			$req_rekon = $client->request('GET', 'http://10.210.84.13:8080/kemenkeunew/api/data/rekonLPJ/'.$kdSatker.'/'.$periode);
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
			$filter = $kdSatker.'/'.$periode;
			
			$req_pagudipa = $client->request('GET', config('app.url_soakemenkeu').'pagudipa/'.$kdSatker);
			$pagudipa = json_decode($req_pagudipa->getBody()->getContents(), true);
				
			$params['headers'] = ['Content-Type' => 'application/json', 'Accept' => 'application/json'];
			$params['body'] = '{ "KD_SATKER" : "'.$kdSatker.'", "TAHUN" : "'.date("Y").'", "PERIODE" : "'.$periode.'" }';
			//$req_realisasi = $client->request('POST', 'http://10.210.84.13:8080/monevgar/api/realisasi/getBySatkerPeriode', $params);
			$req_realisasi = $client->request('GET', config('app.url_soakemenkeu').'realisasi/'.$filter);
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
	
	public function getperiode($kd = null) {
		$periode = '';
		switch ($kd) {
			case "01":
				$periode = "Januari";
				break;
			case "02":
				$periode = "Februari";
				break;
			case "03":
				$periode = "Maret";
				break;
			case "04":
				$periode = "April";
				break;
			case "05":
				$periode = "Mei";
				break;
			case "06":
				$periode = "Juni";
				break;
			case "07":
				$periode = "Juli";
				break;
			case "08":
				$periode = "Agustus";
				break;
			case "09":
				$periode = "September";
				break;
			case "10":
				$periode = "Oktober";
				break;
			case "11":
				$periode = "November";
				break;
			case "12":
				$periode = "Desember";
				break;
			default:
				echo "-";
		}
		return $periode;
	}
}

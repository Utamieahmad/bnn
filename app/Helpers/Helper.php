<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\MainModel;
use App\Tr_JnsBrgBukti;
use App\Tr_Wilayah;
use App\Tr_Instansi;
use App\VlookupValue;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use App\Models\Rehab\Pelatihan;
use App\Models\Irtama\IrtamaPtl;

function getMonth($key){
	$key = (int)$key - 1;
	$months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
	return $months[$key];
}
/* format Y-m-d*/
function formatDate($date){
	$date = strtotime($date);
	$month = date('n',$date);
	$month = getMonth($month);
	$year = date('Y',$date);
	$day = date('d',$date);
	return $day.' '.$month.' '.$year;
}

function splitLKN($lkn){
	$lkn_number = [];
	$new_lkn = [];
	$no_lkn = explode('/',$lkn);
	// if(strpos('-',$no_lkn[1])){
	// 	$lkn_number = explode($no_lkn[1]);
	// 	$new_lkn[0] = $lkn[0];
	// 	$new_lkn[1] = $lkn_number[0];
	// 	$new_lkn[2] = $lkn_number[1];
	// 	$new_lkn[3] = $no_lkn[2];
	// 	$new_lkn[4] = $lkno_lknn[3];
	// 	$new_lkn[5] = $no_lkn[4];
	// }else{
	// 	$lkn_number = [];
	// 	for($j = 0 ; $j < count($no_lkn) ; $j++){
	// 		$new_lkn[] = $no_lkn[$j];
	// 	}
	// }
	return $no_lkn;
}

function jenisKasus(){
	$data = [
    'kualitatif' => 'NARKOTIKA',
    'tppu' => 'TPPU',
    ];
    return $data;
}
function  noLKN(){
	$data = [];
	$data['P2']= 'P2';
	$data['INTD']= 'INTD';
	$data['NAR']= 'NAR';
	$data['TPPU']= 'TPPU';
	$data['BRNTS']= 'BRNTS';
	return $data;
}

function getKotaKab($client, $token, $id){
	$baseUrl = URL::to('/');
	$requestFilterWilayah = $client->request('GET', $baseUrl.'/api/filterwilayah/'.$id,
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
	$kotaKab = json_decode($requestFilterWilayah->getBody()->getContents(), true);

	return $kotaKab['data'];
}

function kelompokKasus($selector = false,$selected=""){
	$query = Tr_JnsBrgBukti::select('kd_jnsbrgbukti','nm_jnsbrgbukti')->whereIn('kd_jnsbrgbukti',config('variable.id_jenis_kasus'))->get();
	if($selector){
		$return = '';
		$select = '';
		foreach($query as $k){
			$return .='<option class="bold">'.$k->nm_jnsbrgbukti.'</option>';
			$datas = $k->collectBarangBukti;
			if($datas){
				foreach($datas as $d){
					if($selected){
						if($d->id_brgbukti == $selected)
							$select = 'selected="selected"';
						else
							$select = '';
					}
					$return .='<option value="'.$d->id_brgbukti.'" '.$select.' >&nbsp;&nbsp;'.$d->nm_brgbukti.'</option>';
				}
			}
		}
	}else{
		$return = array();
		foreach($query as $k){
			$return[$k->kd_jnsbrgbukti][] = $k->nm_jnsbrgbukti;
			$datas = $k->collectBarangBukti;
			if($datas){
				foreach($datas as $d){
					$return[$d->kd_jnsbrgbukti][$d->id_brgbukti] = '&nbsp;&nbsp;'.$d->nm_brgbukti;
				}
			}
		}
	}
	return $return;
}
function dropdownInstansiWilayahBnnCegah($user_id="",$route=""){

	$query = MainModel::getUserDetail($user_id);

	$wilayah_id = $query->wilayah_id;
	$wilayah = $query->wilayah_posisi;

	$rows = MainModel::getPelaksana();

		if (empty($wilayah_id)) {
			if ($route == 'cegah') { // pencegahan
				$arr[1739] = 'DIREKTORAT ADVOKASI';
				$arr[1740] = 'DIREKTORAT DISEMINASI INFORMASI';
			}
			elseif ($route == 'dayamas') {
				$arr[1801] = 'DIREKTORAT ALTERNATIVE DEVELOPMENT';
				$arr[1802] = 'DIREKTORAT PERAN SERTA MASYARAKAT';
			}
			else {
				$arr[482] = 'BNN PUSAT';
			}

			foreach ($rows as $c1) {
				$provinsi = ['1','5'];
				if (in_array($c1->kd_jnswilayah, $provinsi)) {
					if ($c1->kd_jnswilayah == '5') {
						$arr[$c1->id_instansi] = $c1->nm_instansi;
					}
					else {
						$arr[$c1->id_instansi] = $c1->nm_instansi;
					}
					foreach ($rows as $c2) {
						$kabkota = ['2','6'];
						if ($c2->wil_id_wilayah == $c1->id_wilayah && in_array($c2->kd_jnswilayah, $kabkota)) {
							if (strpos(strtolower('KOTA'), strtolower($c2->nm_instansi) ||
								strpos(strtolower('KABUPATEN'), strtolower($c2->nm_instansi)))) {

								$arr[$c2->id_instansi] = '&nbsp;&nbsp;&nbsp;&nbsp;BNNK '.$c2->nm_instansi;
							}
							else {
								$arr[$c2->id_instansi] = '&nbsp;&nbsp;&nbsp;&nbsp;BNNK '.$c2->nm_jnswilayah.' '.$c2->nm_wilayah;
							}

						}
					}
				}
			}
		}
		else {
			foreach ($rows as $r1 => $c1) {
				$provinsi = ['1','5'];

				$wilayah_posisi = explode(">", $wilayah);


				if (count($wilayah_posisi) > 2) {
					if ($c1->id_wilayah == $wilayah_id) {
							$arr[$c1->id_instansi] = $c1->nm_instansi;
					}
				}
				else {
					if (in_array($c1->kd_jnswilayah, $provinsi) && $c1->id_wilayah == $wilayah_id) {
						if ($c1->kd_jnswilayah == '5') {
							$arr[$c1->id_instansi] = $c1->nm_instansi;
						}
						else {
							$arr[$c1->id_instansi] = $c1->nm_instansi;
						}

						foreach ($rows as $r2 => $c2) {
							$kabkota = ['2','6'];
							if ($c2->wil_id_wilayah == $c1->id_wilayah && in_array($c2->kd_jnswilayah, $kabkota)) {
								if (strpos(strtolower('KOTA'), strtolower($c2->nm_instansi) ||
									strpos(strtolower('KABUPATEN'), strtolower($c2->nm_instansi)))) {

									$arr[$c2->id_instansi] = '&nbsp;&nbsp;&nbsp;&nbsp;BNNK '.$c2->nm_instansi;
								}
								else {
									$arr[$c2->id_instansi] = '&nbsp;&nbsp;&nbsp;&nbsp;BNNK '.$c2->nm_jnswilayah.' '.$c2->nm_wilayah;
								}

							}
						}
					}
				}
			}
		}

	return $arr;
}

function web_token(){
	$token = session()->get('token');
	$input = '<input type="hidden" name="token" value="'.$token.'"/>';
	return $input;
}

function execute_api($url,$method,$params=array()){
	$token = session()->get('token');
	$headers = ['headers'=>[ 'Accept' => 'application/json' ,'Authorization'=>'Bearer '.$token]];
	$client = new \GuzzleHttp\Client($headers);
    try {
		if( (strtolower($method) == 'post') || (strtolower($method) == 'put')|| (strtolower($method) == 'delete')) {
	        $request = $client->request($method,url($url),[$headers, 'form_params' =>$params]);
	        $data = json_decode($request->getBody()->getContents(), true);
	        return $data;
	    }else{
	    	$request = $client->request($method,url($url),[$headers]);
	        $data = json_decode($request->getBody()->getContents(), true);
	        return $data;
	    }
    }catch (\GuzzleHttp\Exception\GuzzleException $e) {
        $response = $e->getResponse();
        $responseBodyAsString = $response->getBody()->getContents();
        return $responseBodyAsString;
    }
}

function pagination($currentPage, $itemCount, $itemsPerPage, $adjacentCount, $pageLinkTemplate, $showPrevNext = true) {
	$pagination = "";
    $firstPage = 1;
    $lastPage  = ceil($itemCount / $itemsPerPage);
    if ($lastPage == 1) {
        return;
    }
    if ($currentPage <= $adjacentCount + $adjacentCount) {
        $firstAdjacentPage = $firstPage;
        $lastAdjacentPage  = min($firstPage + $adjacentCount + $adjacentCount, $lastPage);
    } elseif ($currentPage > $lastPage - $adjacentCount - $adjacentCount) {
        $lastAdjacentPage  = $lastPage;
        $firstAdjacentPage = $lastPage - $adjacentCount - $adjacentCount;
    } else {
        $firstAdjacentPage = $currentPage - $adjacentCount;
        $lastAdjacentPage  = $currentPage + $adjacentCount;
    }

    $pagination .= '<ul class="pagination">';
    if ($showPrevNext) {
        if ($currentPage == $firstPage) {
            $pagination .= '<li class="disabled"><span>&lt;&lt;</span></li>';
        } else {
            $pagination .= '<li><a href="' . (is_callable($pageLinkTemplate) ? $pageLinkTemplate($currentPage - 1) : sprintf($pageLinkTemplate, $currentPage - 1)) . '">&lt;&lt;</a></li>';
        }
    }
    if ($firstAdjacentPage > $firstPage) {
        $pagination .= '<li><a href="' . (is_callable($pageLinkTemplate) ? $pageLinkTemplate($firstPage) : sprintf($pageLinkTemplate, $firstPage)) . '">' . $firstPage . '</a></li>';
        if ($firstAdjacentPage > $firstPage + 1) {
            $pagination .= '<li><span>...</span></li>';
        }
    }
    for ($i = $firstAdjacentPage; $i <= $lastAdjacentPage; $i++) {
        if ($currentPage == $i) {
            $pagination .= '<li class="active"><a>' . $i . '</a></li>';
        } else {
            $pagination .= '<li><a href="' . (is_callable($pageLinkTemplate) ? $pageLinkTemplate($i) : sprintf($pageLinkTemplate, $i)) . '">' . $i . '</a></li>';
        }
    }
    if ($lastAdjacentPage < $lastPage) {
        if ($lastAdjacentPage < $lastPage - 1) {
            $pagination .= '<li><span>...</span></li>';
        }
        $pagination .= '<li><a href="' . (is_callable($pageLinkTemplate) ? $pageLinkTemplate($lastPage) : sprintf($pageLinkTemplate, $lastPage)) . '">' . $lastPage . '</a></li>';
    }
    if ($showPrevNext) {
        if ($currentPage == $lastPage) {
            $pagination .= '<li class="disabled"><span>&gt;&gt;</span></li>';
        } else {
            $pagination .= '<li><a href="' . (is_callable($pageLinkTemplate) ? $pageLinkTemplate($currentPage + 1) : sprintf($pageLinkTemplate, $currentPage + 1)) . '">&gt;&gt;</a></li>';
        }
    }
    $pagination .= '</ul>';
    return $pagination;
}


function dropdownPropinsiKabupaten($selected="",$name=""){
	$token = session()->get('token');
	$selects = "";
	$select = "";
	$headers = ['headers'=>[ 'Accept' => 'application/json' ,'Authorization'=>'Bearer '.$token]];
	$client = new \GuzzleHttp\Client($headers);
	$dataKabupaten = $client->request('GET', url('/api/getpropkab/'),
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$token
                ]
            ]
        );
	$dataKabupaten = json_decode($dataKabupaten->getBody()->getContents(), false);
    if(isset($dataKabupaten->data)){
    	$arr_data = json_decode(json_encode($dataKabupaten->data),true);
    	$data = $dataKabupaten->data;
    	// echo '<pre>';
    	// print_r($arr_data);
    	// echo count($arr_data);
    	// exit();
    	if(count($arr_data)>0){
			$selects .= '<select class="form-control select2" name="'.$name.'">';
			$selects .= '<option value="">-- Pilih Provinsi -- </option>';
    		foreach($data as $d=>$value){
    			$arr_value=  json_decode(json_encode($value),true);
    			$selects .= '<option value="-1" disabled>'.$d.'</option>';
    			if(count($arr_value) >0 ){
    				foreach($value as $k=>$v){
    					if($selected){
    						if($selected == $k)
    							$select = 'selected="selected"';
    						else
    							$select = "";
    					}
    					$selects .= '<option value="'.$k.'" '.$select.'>&nbsp;&nbsp;&nbsp;'.$v.'</option>';
    				}
    			}
    		}
    		$selects .= '</select>';
    	}else{
    		$selects = "";
    	}
    }else{
		$selects = "";
	}
    return $selects;
}

function dropdownPropinsiPelaksana($id_instansi=""){
	$options = "";
	$checked = "";
	$query = Tr_Wilayah::where('kd_jnswilayah','1');
	if($query->count() >= 1 ){
		$result = $query->get();
		foreach($result as $r){
			$query2 = Tr_Wilayah::where(['wil_id_wilayah'=>$r->id_wilayah])->get();
			$propinsi = Tr_Instansi::where(['id_wilayah'=>$r->id_wilayah,'kd_jnsinst'=>'11'])->first();
			if($id_instansi && ($id_instansi == $r->instansi->id_instansi)){
				$checked = 'selected="selected"';
			}else{
				$checked = "";
			}
			$options .='<option value="'.$r->instansi->id_instansi.'" '.$checked.'>'.$r->jenis_wilayah->nm_jnswilayah.' '.$r->nm_wilayah.' > '.$propinsi->nm_instansi.'</option>';
			$kabupaten = Tr_Wilayah::where('wil_id_wilayah',$r->id_wilayah);
			if($kabupaten->count() >= 1){
				$kabupatens = $kabupaten->get();
				foreach($kabupatens as $k){
					$list = $k->instansi;
					if(isset($k->instansi)){
						if($id_instansi && ($id_instansi == $k->instansi->id_instansi)){
							$checked = 'selected="selected"';
						}else{
							$checked = "";
						}
						$options .='<option class="child" value="'.$k->instansi->id_instansi.'" '.$checked.'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$k->jenis_wilayah->nm_jnswilayah.' '.$k->nm_wilayah.' > '.$k->instansi->nm_instansi.'</option>';
					}
				}
			}
		}
	}else{
		$options = "";
	}
	return $options;
}

function globalinstansi()
{
	$wilayah = session()->get('wilayah');
	$token = session()->get('token');
	$client = new Client();
	$requestInstansi = $client->request('POST', url('/api/instansi'), [ 'headers' =>	[	'Authorization' => 'Bearer '.$token],'form_params' => [ 'wilayah' => $wilayah ] ] );
	$instansi = json_decode($requestInstansi->getBody()->getContents(), true);
	return $instansi['data'];
}

function breadcrump_balai_lab($route){
	$ul = "";
	if(isset($route)){
		$dashboard = route('dashboard');
		$ul = '<ul class="page-breadcrumb breadcrumb">';
		$ul .= '<li><a href="'.$dashboard.'">Beranda</a></li>';
		if($route == "berkas_sampel"){
			$ul .= '<li class="active">Data Berkas Sampel</li>';
		}else if($route == "edit_berkas_sampel"){
			$ul .= '<li><a href="'.route('berkas_sampel').'">Balai Laboratorium Narkoba</a></li>';
			$ul .= '<li class="active">Ubah Data Berkas Sampel</li>';
		}else if($route == "add_berkas_sampel"){
			$ul .= '<li><a href="'.route('berkas_sampel').'">Balai Laboratorium Narkoba</a></li>';
			$ul .= '<li class="active">Tambah Data Berkas Sampel</li>';
		}
		$ul .= '</ul>';
	}else{
		$ul = "";
	}

	return $ul;

}

// function breadcrump_bidang_tik($route){
// 	$ul = "";
// 	if(isset($route)){
// 		$dashboard = route('dashboard');
// 		$ul = '<ul class="page-breadcrumb breadcrumb">';
// 		$ul .= '<li><a href="'.$dashboard.'">Beranda</a></li>';
// 		if($route == "informasi_melalui_contact_center"){
// 			$ul .= '<li class="active">Informasi Masuk Melalui Contact Center</li>';
// 		}else if($route == "edit_informasi_melalui_contact_center"){
// 			$ul .= '<li><a href="'.route('index_pengujian_bahan').'">Puslidatin > Bidang TIK</a></li>';
// 			$ul .= '<li class="active">Ubah Informasi Masuk Melalui Contact Center</li>';
// 		}else if($route == "add_informasi_melalui_contact_center"){
// 			$ul .= '<li><a href="'.route('index_pengujian_bahan').'">Puslidatin > Bidang TIK</a></li>';
// 			$ul .= '<li class="active">Tambah Informasi Masuk Melalui Contact Center</li>';
// 		}else if($route == "tindak_lanjut_bnn_pusat"){
// 			$ul .= '<li class="active">Tindak Lanjut Contact Center BNN Pusat</li>';
// 		}else if($route == "edit_tindak_lanjut_bnn_pusat"){
// 			$ul .= '<li><a href="'.route('tindak_lanjut_bnn_pusat').'">Puslidatin > Bidang TIK</a></li>';
// 			$ul .= '<li class="active">Ubah Tindak Lanjut Contact Center BNN Pusat</li>';
// 		}else if($route == "add_tindak_lanjut_bnn_pusat"){
// 			$ul .= '<li><a href="'.route('tindak_lanjut_bnn_pusat').'">Puslidatin > Bidang TIK</a></li>';
// 			$ul .= '<li class="active">Tambah Tindak Lanjut Contact Center BNN Pusat</li>';
// 		}else if($route == "tindak_lanjut_bnn"){
// 			$ul .= '<li class="active">Tindak Lanjut Contact Center BNNP/BNNK</li>';
// 		}else if($route == "edit_tindak_lanjut_bnn"){
// 			$ul .= '<li><a href="'.route('tindak_lanjut_bnn').'">Tindak Lanjut Contact Center BNNP/BNNK</a></li>';
// 			$ul .= '<li class="active">Ubah</li>';
// 		}else if($route == "add_tindak_lanjut_bnn"){
// 			$ul .= '<li><a href="'.route('tindak_lanjut_bnn').'">Tindak Lanjut Contact Center BNNP/BNNK</a></li>';
// 			$ul .= '<li class="active">Tambah</li>';
// 		}
// 		$ul .= '</ul>';
// 	}else{
// 		$ul = "";
// 	}
// 	return $ul;
// }

function breadcrump_bidang_tik($route){
	$ul = "";
	if(isset($route)){
		$dashboard = route('dashboard');
		$ul = '<ul class="page-breadcrumb breadcrumb">';
		$ul .= '<li><a href="'.$dashboard.'">Beranda</a></li>';
		if($route == "informasi_melalui_contact_center"){
			$ul .= '<li class="active">Informasi Masuk Melalui Contact Center</li>';
		}else if($route == "edit_informasi_melalui_contact_center"){
			$ul .= '<li><a href="'.route('informasi_melalui_contact_center').'">Puslidatin > Bidang TIK</a></li>';
			$ul .= '<li class="active">Ubah Informasi Masuk Melalui Contact Center</li>';
		}else if($route == "add_informasi_melalui_contact_center"){
			$ul .= '<li><a href="'.route('informasi_melalui_contact_center').'">Puslidatin > Bidang TIK</a></li>';
			$ul .= '<li class="active">Tambah Informasi Masuk Melalui Contact Center</li>';
		}else if($route == "tindak_lanjut_bnn_pusat"){
			$ul .= '<li class="active">Tindak Lanjut Contact Center BNN Pusat</li>';
		}else if($route == "edit_tindak_lanjut_bnn_pusat"){
			$ul .= '<li><a href="'.route('tindak_lanjut_bnn_pusat').'">Puslidatin > Bidang TIK</a></li>';
			$ul .= '<li class="active">Ubah Tindak Lanjut Contact Center BNN Pusat</li>';
		}else if($route == "add_tindak_lanjut_bnn_pusat"){
			$ul .= '<li><a href="'.route('tindak_lanjut_bnn_pusat').'">Puslidatin > Bidang TIK</a></li>';
			$ul .= '<li class="active">Tambah Tindak Lanjut Contact Center BNN Pusat</li>';
		}else if($route == "tindak_lanjut_bnn"){
			$ul .= '<li class="active">Tindak Lanjut Contact Center BNNP/BNNK</li>';
		}else if($route == "edit_tindak_lanjut_bnn"){
			$ul .= '<li><a href="'.route('tindak_lanjut_bnn').'">Tindak Lanjut Contact Center BNNP/BNNK</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_tindak_lanjut_bnn"){
			$ul .= '<li><a href="'.route('tindak_lanjut_bnn').'">Tindak Lanjut Contact Center BNNP/BNNK</a></li>';
		}if($route == "pekerjaan_jaringan"){
			$ul .= '<li class="active">Pekerjaan Jaringan</li>';
		}else if($route == "edit_pekerjaan_jaringan"){
			$ul .= '<li><a href="'.route('pekerjaan_jaringan').'">Puslidatin > Pekerjaan Jaringan</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_pekerjaan_jaringan"){
			$ul .= '<li><a href="'.route('pekerjaan_jaringan').'">Puslidatin > Pekerjaan Jaringan</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "pengecekan_jaringan"){
			$ul .= '<li class="active">Pengecekan dan Pemeliharaan Jaringan LAN</li>';
		}else if($route == "edit_pengecekan_jaringan"){
			$ul .= '<li><a href="'.route('pengecekan_jaringan').'">Puslidatin > Pengecekan dan Pemeliharaan Jaringan LAN</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_pengecekan_jaringan"){
			$ul .= '<li><a href="'.route('pengecekan_jaringan').'">Puslidatin >  Pengecekan dan Pemeliharaan Jaringan LAN</a></li>';
			$ul .= '<li class="active">Tambah</li>';

		}else if($route == "pengadaan_email"){
			$ul .= '<li class="active">Pembuatan Email BNN</li>';
		}else if($route == "edit_pengadaan_email"){
			$ul .= '<li><a href="'.route('pengadaan_email').'">Pembuatan Email BNN</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_pengadaan_email"){
			$ul .= '<li><a href="'.route('pengadaan_email').'">Pembuatan Email BNN</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}elseif($route == 'call_center'){
			$ul .= '<li class="active">Pusat Informasi (CC)</li>';
		}else{
			$ul .= '<li class="active"></li>';
		}
		$ul .= '</ul>';
		return $ul;
	}
}

function breadcrump_litbang($route){
	$ul = "";
	if(isset($route)){
		$dashboard = route('dashboard');
		$ul = '<ul class="page-breadcrumb breadcrumb">';
		$ul .= '<li><a href="'.$dashboard.'">Beranda</a></li>';
		if($route == "survey_narkoba"){
			$ul .= '<li class="active">Survey Nasional Penyalahgunaan Narkoba</li>';
		}else if($route == "edit_survey_narkoba"){
			$ul .= '<li><a href="'.route('survey_narkoba').'">Survey Nasional Penyalahgunaan Narkoba</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_survey_narkoba"){
			$ul .= '<li><a href="'.route('survey_narkoba').'">Survey Nasional Penyalahgunaan Narkoba</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "survey"){
			$ul .= '<li class="active">Survey </li>';
		}else if($route == "edit_survey"){
			$ul .= '<li><a href="'.route('survey').'">Survey </a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_survey"){
			$ul .= '<li><a href="'.route('survey').'">Survey</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "survey_narkoba_ketergantungan"){
			$ul .= '<li class="active">Survey Nasional Penyalahgunaan Narkoba Berdasarkan Tingkat Ketergantungan</li>';
		}else if($route == "edit_survey_narkoba_ketergantungan"){
			$ul .= '<li><a href="'.route('survey_narkoba_ketergantungan').'">Survey Nasional Penyalahgunaan Narkoba Berdasarkan Tingkat Ketergantungan</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_survey_narkoba_ketergantungan"){
			$ul .= '<li><a href="'.route('survey_narkoba_ketergantungan').'">Survey Nasional Penyalahgunaan Narkoba Berdasarkan Tingkat Ketergantungan</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "penyalahgunaan_coba_pakai"){
			$ul .= '<li class="active">Penyalah Guna Narkoba Coba Pakai</li>';
		}else if($route == "edit_penyalahgunaan_coba_pakai"){
			$ul .= '<li><a href="'.route('penyalahgunaan_coba_pakai').'">Penyalah Guna Narkoba Coba Pakai</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_penyalahgunaan_coba_pakai"){
			$ul .= '<li><a href="'.route('penyalahgunaan_coba_pakai').'">Penyalah Guna Narkoba Coba Pakai</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "penyalahguna_teratur_pakai"){
			$ul .= '<li class="active">Penyalah Guna Narkoba Teratur Pakai</li>';
		}else if($route == "edit_penyalahguna_teratur_pakai"){
			$ul .= '<li><a href="'.route('penyalahgunaan_coba_pakai').'">Penyalah Guna Narkoba Teratur Pakai</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_penyalahguna_teratur_pakai"){
			$ul .= '<li><a href="'.route('penyalahgunaan_coba_pakai').'">Penyalah Guna Narkoba Teratur Pakai</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "penyalahguna_pecandu_suntik"){
			$ul .= '<li class="active">Penyalahguna Narkoba Pecandu Suntik</li>';
		}else if($route == "edit_penyalahguna_pecandu_suntik"){
			$ul .= '<li><a href="'.route('penyalahguna_pecandu_suntik').'">Penyalahguna Narkoba Pecandu Suntik</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_penyalahguna_pecandu_suntik"){
			$ul .= '<li><a href="'.route('penyalahguna_pecandu_suntik').'">Penyalahguna Narkoba Pecandu Suntik</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "penyalahguna_pecandu_non_suntik"){
			$ul .= '<li class="active">Penyalahguna Narkoba Pecandu Non Suntik</li>';
		}else if($route == "edit_penyalahguna_pecandu_non_suntik"){
			$ul .= '<li><a href="'.route('penyalahguna_pecandu_non_suntik').'">Penyalahguna Narkoba Pecandu Non Suntik</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_penyalahguna_pecandu_non_suntik"){
			$ul .= '<li><a href="'.route('penyalahguna_pecandu_non_suntik').'">Penyalahguna Narkoba Pecandu Non Suntik</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "penyalahguna_setahun_pakai"){
			$ul .= '<li class="active">Penyalahguna Narkoba Setahun Pakai</li>';
		}else if($route == "edit_penyalahguna_setahun_pakai"){
			$ul .= '<li><a href="'.route('penyalahguna_setahun_pakai').'">Penyalahguna Narkoba Setahun Pakai</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_penyalahguna_setahun_pakai"){
			$ul .= '<li><a href="'.route('penyalahguna_setahun_pakai').'">Penyalahguna Narkoba Setahun Pakai</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "data_penelitian_bnn"){
			$ul .= '<li class="active">Permintaan Data Penelitian BNN</li>';
		}else if($route == "edit_data_penelitian_bnn"){
			$ul .= '<li><a href="'.route('data_penelitian_bnn').'">Permintaan Data Penelitian BNN</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_data_penelitian_bnn"){
			$ul .= '<li><a href="'.route('data_penelitian_bnn').'">Permintaan Data Penelitian BNN</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "riset_penyalahgunaan_narkoba"){
			$ul .= '<li class="active">Riset Operasional Penyalahgunaan Narkoba</li>';
		}else if($route == "edit_riset_penyalahgunaan_narkoba"){
			$ul .= '<li><a href="'.route('riset_penyalahgunaan_narkoba').'">Riset Operasional Penyalahgunaan Narkoba</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_riset_penyalahgunaan_narkoba"){
			$ul .= '<li><a href="'.route('riset_penyalahgunaan_narkoba').'">Riset Operasional Penyalahgunaan Narkoba</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}
		$ul .= '</ul>';
	}else{
		$ul = "";
	}
	return $ul;
}

function breadcrumps_balai_diklat($route){
	$ul = "";
	if(isset($route)){
		$dashboard = route('dashboard');
		$ul = '<ul class="page-breadcrumb breadcrumb">';
		$ul .= '<li><a href="'.$dashboard.'">Beranda</a></li>';
		if($route == "pendidikan_pelatihan"){
			$ul .= '<li class="active">Balai Pendidikan dan Pelatihan > Data Kegiatan Pendidikan dan Pelatihan Pada Balai Diklat</li>';
		}else if($route == "edit_pendidikan_pelatihan"){
			$ul .= '<li><a href="'.route('pendidikan_pelatihan').'"> Balai Pendidikan dan Pelatihan > Data Kegiatan Pendidikan dan Pelatihan Pada Balai Diklat</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_pendidikan_pelatihan"){
			$ul .= '<li><a href="'.route('pendidikan_pelatihan').'">Balai Pendidikan dan Pelatihan > Data Kegiatan Pendidikan dan Pelatihan Pada Balai Diklat</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}
	}else{
		$ul = "";
	}
	return $ul;
}

function breadcrumps_narkotika($route){
	$ul = "";
	if(isset($route)){
		$dashboard = route('dashboard');
		$index_lkn = 'Direktorat Narkotika > Pendataan LKN ';
		$index_ladangGanja = 'Direktorat Narkotika > Pendataan Pemusnahan Ladang Tanaman Narkotika ';
		$ul = '<ul class="page-breadcrumb breadcrumb">';
		$ul .= '<li><a href="'.$dashboard.'">Beranda</a></li>';
		if($route == "pendataan_lkn"){
			$ul .= '<li class="active">'.$index_lkn.'</li>';
		}else if($route == "edit_pendataan_lkn"){
			$ul .= '<li><a href="'.route('pendataan_lkn').'"> '.$index_lkn.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_pendataan_lkn"){
			$ul .= '<li><a href="'.route('pendataan_lkn').'">'.$index_lkn.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "pendataan_pemusnahan_ladangganja"){
			$ul .= '<li class="active">'.$index_ladangGanja.'</li>';
		}else if($route == "edit_pendataan_pemusnahan_ladangganja"){
			$ul .= '<li><a href="'.route('pendataan_pemusnahan_ladangganja').'"> '.$index_ladangGanja.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_pendataan_pemusnahan_ladangganja"){
			$ul .= '<li><a href="'.route('pendataan_pemusnahan_ladangganja').'">'.$index_ladangGanja.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}
	}else{
		$ul = "";
	}
	return $ul;
}

function breadcrumps_master($route){
	$ul = "";
	if(isset($route)){
		$dashboard = route('dashboard');
		$ul = '<ul class="page-breadcrumb breadcrumb">';
		$ul .= '<li><a href="'.$dashboard.'">Beranda</a></li>';
		if($route == "dataInstansi"){
			$ul .= '<li class="active">Master Data Instansi</li>';
		}else if($route == "dataPropinsi"){
			$ul .= '<li class="active">Master Data Provinsi</li>';
		}else if($route == "dataKota"){
			$ul .= '<li class="active">Master Data Kota</li>';
		}else if($route == "dataJeniskasus"){
			$ul .= '<li class="active">Master Data Jenis Kasus</li>';
		}else if($route == "dataJenisbarbuk"){
			$ul .= '<li class="active">Master Data Jenis Barang Bukti</li>';
		}else if($route == "dataBarangbukti"){
			$ul .= '<li class="active">Master Data Barang Bukti</li>';
		}else if($route == "dataSatuan"){
			$ul .= '<li class="active">Master Data Satuan</li>';
		}else if($route == "dataMediaonline"){
			$ul .= '<li class="active">Master Data Media Online</li>';
		}else if($route == "dataMediasosial"){
			$ul .= '<li class="active">Master Data Media Sosial</li>';
		}else if($route == "dataMediacetak"){
			$ul .= '<li class="active">Master Data Media Cetak</li>';
		}else if($route == "dataMediaruang"){
			$ul .= '<li class="active">Master Data Media Luar Ruang</li>';
		}else if($route == "dataBagian"){
			$ul .= '<li class="active">Master Data Bagian</li>';
		}else if($route == "dataKegiatan"){
			$ul .= '<li class="active">Master Data Kegiatan</li>';
		}else if($route == "dataKomoditi"){
			$ul .= '<li class="active">Master Data Komoditi</li>';
		}else if($route == "loginLog"){
			$ul .= '<li class="active">User Data Log</li>';
		}else if($route == "userLog"){
			$ul .= '<li class="active">User Activity Log</li>';
		}else if($route == "dataGroup"){
			$ul .= '<li class="active">Master Data Group</li>';
		}else if($route == "add_dataGroup"){
			$ul .= '<li><a href="'.route('dataGroup').'">Master Data Group</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "edit_dataGroup"){
			$ul .= '<li><a href="'.route('dataGroup').'">Master Data Group</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "user"){
			$ul .= '<li class="active">Master Data User</li>';
		}else if($route == "add_user"){
			$ul .= '<li><a href="'.route('user').'">Master Data User</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "edit_user"){
			$ul .= '<li><a href="'.route('user').'">Master Data User</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}
	}else{
		$ul = "";
	}
	return $ul;
}

function breadcrumps_psikotropika($route){
	$ul = "";
	if(isset($route)){
		$dashboard = route('dashboard');
		$index = 'Direktorat Psikotropika dan Prekursor > Pendataan LKN ';
		$ul = '<ul class="page-breadcrumb breadcrumb">';
		$ul .= '<li><a href="'.$dashboard.'">Beranda</a></li>';
		if($route == "psi_pendataan_lkn"){
			$ul .= '<li class="active">'.$index.'</li>';
		}else if($route == "edit_psi_pendataan_lkn"){
			$ul .= '<li><a href="'.route('psi_pendataan_lkn').'"> '.$index.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_psi_pendataan_lkn"){
			$ul .= '<li><a href="'.route('psi_pendataan_lkn').'">'.$index.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "input_psi_pendataan_lkn"){
			$ul .= '<li><a href="'.route('psi_pendataan_lkn').'">'.$index.'</a></li>';
			$ul .= '<li class="active">Input</li>';
		}
	}else{
		$ul = "";
	}
	return $ul;
}
function breadcrumps_tppu($route){
	$ul = "";
	if(isset($route)){
		$dashboard = route('dashboard');
		$index = 'Direktorat TPPU > Pendataan TPPU ';
		$ul = '<ul class="page-breadcrumb breadcrumb">';
		$ul .= '<li><a href="'.$dashboard.'">Beranda</a></li>';
		if($route == "pendataan_tppu"){
			$ul .= '<li class="active">'.$index.'</li>';
		}else if($route == "edit_pendataan_tppu"){
			$ul .= '<li><a href="'.route('pendataan_tppu').'"> '.$index.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_pendataan_tppu"){
			$ul .= '<li><a href="'.route('pendataan_tppu').'">'.$index.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}
	}else{
		$ul = "";
	}
	return $ul;
}
function breadcrumps_intelijen($route){
	$ul = "";
	if(isset($route)){
		$dashboard = route('dashboard');
		$index = 'Dir Intelijen > Pendataan Jaringan Narkoba yang Sudah Diungkap';
		$ul = '<ul class="page-breadcrumb breadcrumb">';
		$ul .= '<li><a href="'.$dashboard.'">Beranda</a></li>';
		if($route == "pendataan_jaringan"){
			$ul .= '<li class="active">'.$index.'</li>';
		}else if($route == "edit_pendataan_jaringan"){
			$ul .= '<li><a href="'.route('pendataan_jaringan').'"> '.$index.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_pendataan_jaringan"){
			$ul .= '<li><a href="'.route('pendataan_jaringan').'">'.$index.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "add_data_pendataan_jaringan"){
			$ul .= '<li><a href="'.route('add_pendataan_jaringan').'">'.$index.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}
	}else{
		$ul = "";
	}
	return $ul;
}
function breadcrumps_dir_wastahti($route){
	$ul = "";
	if(isset($route)){
		$dashboard = route('dashboard');
		$index1 = 'Direktorat Wastahti > Pendataan Pemusnahan Barang Bukti';
		$index2 = 'Direktorat Wastahti > Pendataan Tahanan di BNN dan BNNP';
		$ul = '<ul class="page-breadcrumb breadcrumb">';
		$ul .= '<li><a href="'.$dashboard.'">Beranda</a></li>';
		if($route == "pendataan_brgbukti"){
			$ul .= '<li class="active">'.$index1.'</li>';
		}else if($route == "edit_pendataan_brgbukti"){
			$ul .= '<li><a href="'.route('pendataan_brgbukti').'"> '.$index1.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_pendataan_brgbukti"){
			$ul .= '<li><a href="'.route('pendataan_brgbukti').'">'.$index1.'</a></li>';
			$ul .= '<li class="active">Cek LKN</li>';
		}else if($route == "add_form_pendataan_brgbukti"){
			$ul .= '<li><a href="'.route('pendataan_brgbukti').'">'.$index1.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}elseif($route == "pendataan_tahanan"){
			$ul .= '<li class="active">'.$index2.'</li>';
		}else if($route == "edit_pendataan_tahanan"){
			$ul .= '<li><a href="'.route('pendataan_tahanan').'"> '.$index2.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_pendataan_tahanan"){
			$ul .= '<li><a href="'.route('pendataan_tahanan').'">'.$index2.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}
	}else{
		$ul = "";
	}
	return $ul;
}


function breadcrumps_dir_penindakan($route){
	$ul = "";
	if(isset($route)){
		$dashboard = route('dashboard');
		$index1 = 'Direktorat Penindakan dan Pengejaran > Daftar Pencarian Orang (DPO)';
		$ul = '<ul class="page-breadcrumb breadcrumb">';
		$ul .= '<li><a href="'.$dashboard.'">Beranda</a></li>';
		if($route == "pendataan_dpo"){
			$ul .= '<li class="active">'.$index1.'</li>';
		}else if($route == "edit_pendataan_dpo"){
			$ul .= '<li><a href="'.route('pendataan_dpo').'"> '.$index1.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_pendataan_dpo"){
			$ul .= '<li><a href="'.route('pendataan_dpo').'">'.$index1.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}
	}else{
		$ul = "";
	}
	return $ul;
}

function breadcrumps_dir_interdiksi($route){
	$ul = "";
	if(isset($route)){
		$dashboard = route('dashboard');
		$index1 = 'Direktorat Interdiksi > Pendataan LKN';
		$ul = '<ul class="page-breadcrumb breadcrumb">';
		$ul .= '<li><a href="'.$dashboard.'">Beranda</a></li>';
		if($route == "pendataan_intdpo"){
			$ul .= '<li class="active">'.$index1.'</li>';
		}else if($route == "edit_pendataan_intdpo"){
			$ul .= '<li><a href="'.route('pendataan_intdpo').'"> '.$index1.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_pendataan_intdpo"){
			$ul .= '<li><a href="'.route('pendataan_intdpo').'">'.$index1.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}
	}else{
		$ul = "";
	}
	return $ul;
}

function breadcrumps_dir_plrip($route){
	$ul = "";
	if(isset($route)){
		$dashboard = route('dashboard');
		$index1 = 'Direktorat PLRIP > Informasi Umum Lembaga';
		$index2 = 'Direktorat PLRIP > Dokumen NSPK';
		$index3 = 'Direktorat PLRIP > Penilaian Lembaga Rehabilitasi';
		$index4 = 'Direktorat PLRIP > Pelatihan Lembaga PLRIP';
		$ul = '<ul class="page-breadcrumb breadcrumb">';
		$ul .= '<li><a href="'.$dashboard.'">Beranda</a></li>';
		if($route == "informasi_lembaga_umum"){
			$ul .= '<li class="active">'.$index1.'</li>';
		}else if($route == "edit_informasi_lembaga_umum"){
			$ul .= '<li><a href="'.route('informasi_lembaga_umum').'"> '.$index1.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_informasi_lembaga_umum"){
			$ul .= '<li><a href="'.route('informasi_lembaga_umum').'">'.$index1.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "dokumen_nspk"){
			$ul .= '<li class="active">'.$index2.'</li>';
		}else if($route == "edit_dokumen_nspk"){
			$ul .= '<li><a href="'.route('dokumen_nspk').'"> '.$index2.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_dokumen_nspk"){
			$ul .= '<li><a href="'.route('dokumen_nspk').'">'.$index2.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "kegiatan_pelatihan"){
			$ul .= '<li class="active">'.$index4.'</li>';
		}else if($route == "edit_kegiatan_pelatihan"){
			$ul .= '<li><a href="'.route('kegiatan_pelatihan').'"> '.$index4.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_kegiatan_pelatihan"){
			$ul .= '<li><a href="'.route('kegiatan_pelatihan').'">'.$index4.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "penilaian_lembaga"){
			$ul .= '<li class="active">'.$index3.'</li>';
		}else if($route == "edit_penilaian_lembaga"){
			$ul .= '<li><a href="'.route('penilaian_lembaga').'"> '.$index3.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_penilaian_lembaga"){
			$ul .= '<li><a href="'.route('penilaian_lembaga').'">'.$index3.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}
	}else{
		$ul = "";
	}
	return $ul;
}


function breadcrumps_dir_plrkm($route){
	$ul = "";
	if(isset($route)){
		$dashboard = route('dashboard');
		$index1 = 'Direktorat PLRKM > Informasi Umum Lembaga';
		$index2 = 'Direktorat PLRKM > Dokumen NSPK';
		$index3 = 'Direktorat PLRKM > Penilaian Lembaga Rehabilitasi';
		$index4 = 'Direktorat PLRKM > Pelatihan Lembaga PLRKM';
		$ul = '<ul class="page-breadcrumb breadcrumb">';
		$ul .= '<li><a href="'.$dashboard.'">Beranda</a></li>';
		if($route == "informasi_lembaga_umum_plrkm"){
			$ul .= '<li class="active">'.$index1.'</li>';
		}else if($route == "edit_informasi_lembaga_umum_plrkm"){
			$ul .= '<li><a href="'.route('informasi_lembaga_umum_plrkm').'"> '.$index1.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_informasi_lembaga_umum_plrkm"){
			$ul .= '<li><a href="'.route('informasi_lembaga_umum_plrkm').'">'.$index1.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "dokumen_nspk_plrkm"){
			$ul .= '<li class="active">'.$index2.'</li>';
		}else if($route == "edit_dokumen_nspk_plrkm"){
			$ul .= '<li><a href="'.route('dokumen_nspk_plrkm').'"> '.$index2.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_dokumen_nspk_plrkm"){
			$ul .= '<li><a href="'.route('dokumen_nspk_plrkm').'">'.$index2.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "kegiatan_pelatihan_plrkm"){
			$ul .= '<li class="active">'.$index4.'</li>';
		}else if($route == "edit_kegiatan_pelatihan_plrkm"){
			$ul .= '<li><a href="'.route('kegiatan_pelatihan_plrkm').'"> '.$index4.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_kegiatan_pelatihan_plrkm"){
			$ul .= '<li><a href="'.route('kegiatan_pelatihan_plrkm').'">'.$index4.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "penilaian_lembaga_plrkm"){
			$ul .= '<li class="active">'.$index3.'</li>';
		}else if($route == "edit_penilaian_lembaga_plrkm"){
			$ul .= '<li><a href="'.route('penilaian_lembaga_plrkm').'"> '.$index3.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_penilaian_lembaga_plrkm"){
			$ul .= '<li><a href="'.route('penilaian_lembaga_plrkm').'">'.$index3.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}
	}else{
		$ul = "";
	}
	return $ul;
}

function breadcrumps_dir_pasca($route){
	$ul = "";
	if(isset($route)){
		$dashboard = route('dashboard');
		$index1 = 'Direktorat Pascarehabilitasi > Informasi Umum Lembaga';
		$index2 = 'Direktorat Pascarehabilitasi > Dokumen NSPK';
		$index3 = 'Direktorat Pascarehabilitasi > Penilaian Lembaga Rehabilitasi';
		$index4 = 'Direktorat Pascarehabilitasi > Kegiatan PascaRehabilitasi';
		//PLRIP
		$index5 = 'Direktorat PLRIP > Informasi Lembaga Umum PLRIP';
		$index6 = 'Direktorat PLRIP > Dokumen NSPK PLRIP';
		$index7 = 'Direktorat PLRIP > Kegiatan PLRIP';
		$index8 = 'Direktorat PLRIP > Penilaian Lembaga PLRIP';
		//PLRKM
		$index12 = 'Direktorat PLRKM > Informasi Lembaga Umum PLRKM';
		$index9 = 'Direktorat PLRKM > Dokumen NSPK PLRKM';
		$index10 = 'Direktorat PLRKM > Kegiatan PLRKM';
		$index11 = 'Direktorat PLRKM > Penilaian Lembaga PLRKM';

		$ul = '<ul class="page-breadcrumb breadcrumb">';
		$ul .= '<li><a href="'.$dashboard.'">Beranda</a></li>';
		if($route == "informasi_lembaga_umum_pascarehabilitasi"){
			$ul .= '<li class="active">'.$index1.'</li>';
		}else if($route == "edit_informasi_lembaga_umum_pascarehabilitasi"){
			$ul .= '<li><a href="'.route('informasi_lembaga_umum_pascarehabilitasi').'"> '.$index1.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_informasi_lembaga_umum_pascarehabilitasi"){
			$ul .= '<li><a href="'.route('informasi_lembaga_umum_pascarehabilitasi').'">'.$index1.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "dokumen_nspk_pascarehabilitasi"){
			$ul .= '<li class="active">'.$index2.'</li>';
		}else if($route == "edit_dokumen_nspk_pascarehabilitasi"){
			$ul .= '<li><a href="'.route('dokumen_nspk_pascarehabilitasi').'"> '.$index2.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_dokumen_nspk_pascarehabilitasi"){
			$ul .= '<li><a href="'.route('dokumen_nspk_pascarehabilitasi').'">'.$index2.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "kegiatan_pelatihan_pascarehabilitasi"){
			$ul .= '<li class="active">'.$index4.'</li>';
		}else if($route == "edit_kegiatan_pelatihan_pascarehabilitasi"){
			$ul .= '<li><a href="'.route('kegiatan_pelatihan_pascarehabilitasi').'"> '.$index4.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_kegiatan_pelatihan_pascarehabilitasi"){
			$ul .= '<li><a href="'.route('kegiatan_pelatihan_pascarehabilitasi').'">'.$index4.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "penilaian_lembaga_pascarehabilitasi"){
			$ul .= '<li class="active">'.$index3.'</li>';
		}else if($route == "edit_penilaian_lembaga_pascarehabilitasi"){
			$ul .= '<li><a href="'.route('penilaian_lembaga_pascarehabilitasi').'"> '.$index3.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_penilaian_lembaga_pascarehabilitasi"){
			$ul .= '<li><a href="'.route('penilaian_lembaga_pascarehabilitasi').'">'.$index3.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "informasi_lembaga_umum_plrip"){
			$ul .= '<li><span class="active" >'.$index5.'</span></li>';
		}
		else if($route == "add_informasi_lembaga_umum_plrip"){
			$ul .= '<li><a class="active" href="'.route('informasi_lembaga_umum_plrip').'">'.$index5.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}
		else if($route == "edit_informasi_lembaga_umum_plrip"){
			$ul .= '<li><a class="active" href="'.route('informasi_lembaga_umum_plrip').'">'.$index5.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "informasi_lembaga_umum_plrkm"){
			$ul .= '<li><span class="active" >'.$index12.'</span></li>';
		}
		else if($route == "add_informasi_lembaga_umum_plrkm"){
			$ul .= '<li><a class="active" href="'.route('informasi_lembaga_umum_plrkm').'">'.$index12.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}
		else if($route == "edit_informasi_lembaga_umum_plrkm"){
			$ul .= '<li><a class="active" href="'.route('informasi_lembaga_umum_plrkm').'">'.$index12.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "dokumen_nspk_plrip"){
			$ul .= '<li><span class="active" >'.$index6.'</span></li>';
		}
		else if($route == "add_dokumen_nspk_plrip"){
			$ul .= '<li><a class="active" href="'.route('dokumen_nspk_plrip').'">'.$index6.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}
		else if($route == "edit_dokumen_nspk_plrip"){
			$ul .= '<li><a class="active" href="'.route('dokumen_nspk_plrip').'">'.$index6.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "kegiatan_pelatihan_plrip"){
			$ul .= '<li><span class="active" >'.$index7.'</span></li>';
		}
		else if($route == "add_kegiatan_pelatihan_plrip"){
			$ul .= '<li><a class="active" href="'.route('kegiatan_pelatihan_plrip').'">'.$index7.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}
		else if($route == "edit_kegiatan_pelatihan_plrip"){
			$ul .= '<li><a class="active" href="'.route('kegiatan_pelatihan_plrip').'">'.$index7.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "penilaian_lembaga_plrip"){
			$ul .= '<li><span class="active" >'.$index8.'</span></li>';
		}
		else if($route == "add_penilaian_lembaga_plrip"){
			$ul .= '<li><a class="active" href="'.route('kegiatan_pelatihan_plrip').'">'.$index8.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}
		else if($route == "edit_penilaian_lembaga_plrip"){
			$ul .= '<li><a class="active" href="'.route('kegiatan_pelatihan_plrip').'">'.$index8.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "dokumen_nspk_plrkm"){
			$ul .= '<li><span class="active" >'.$index9.'</span></li>';
		}
		else if($route == "add_dokumen_nspk_plrkm"){
			$ul .= '<li><a class="active" href="'.route('dokumen_nspk_plrkm').'">'.$index9.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}
		else if($route == "edit_dokumen_nspk_plrkm"){
			$ul .= '<li><a class="active" href="'.route('dokumen_nspk_plrkm').'">'.$index9.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "kegiatan_pelatihan_plrkm"){
			$ul .= '<li><span class="active" >'.$index10.'</span></li>';
		}
		else if($route == "add_kegiatan_pelatihan_plrkm"){
			$ul .= '<li><a class="active" href="'.route('kegiatan_pelatihan_plrkm').'">'.$index10.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}
		else if($route == "edit_kegiatan_pelatihan_plrkm"){
			$ul .= '<li><a class="active" href="'.route('kegiatan_pelatihan_plrkm').'">'.$index10.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "penilaian_lembaga_plrkm"){
			$ul .= '<li><span class="active" >'.$index11.'</span></li>';
		}
		else if($route == "add_penilaian_lembaga_plrkm"){
			$ul .= '<li><a class="active" href="'.route('penilaian_lembaga_plrkm').'">'.$index11.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}
		else if($route == "edit_penilaian_lembaga_plrkm"){
			$ul .= '<li><a class="active" href="'.route('penilaian_lembaga_plrkm').'">'.$index11.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}
	}else{
		$ul = "";
	}
	return $ul;
}

function breadcrumps_dir_advokasi($route){
	$ul = "";
	if(isset($route)){
		$dashboard = route('dashboard');
		$index1 = 'Direktorat Advokasi > Data Kegiatan Rapat Koordinasi';
		$index2 = 'Direktorat Advokasi > Kegiatan Membangun Jejaring';
		$index3 = 'Direktorat Advokasi > Data Kegiatan Asistensi';
		$index4 = 'Direktorat Advokasi > Data Penguatan Asistensi';
		$index5 = 'Direktorat Advokasi > Data Kegiatan Intervensi';
		$index6 = 'Direktorat Advokasi > Data Kegiatan Supervisi';
		$index7 = 'Direktorat Advokasi > Data Kegiatan Advokasi Monitoring Evaluasi';
		$index8 = 'Direktorat Advokasi > Data Kegiatan Bimbingan Teknis';
		$index9 = 'Direktorat Advokasi > Data Kegiatan KIE';
		$ul = '<ul class="page-breadcrumb breadcrumb">';
		$ul .= '<li><a href="'.$dashboard.'">Beranda</a></li>';
		if($route == "pendataan_koordinasi"){
			$ul .= '<li class="active">'.$index1.'</li>';
		}else if($route == "edit_pendataan_koordinasi"){
			$ul .= '<li><a href="'.route('pendataan_koordinasi').'"> '.$index1.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_pendataan_koordinasi"){
			$ul .= '<li><a href="'.route('pendataan_koordinasi').'">'.$index1.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "pendataan_jejaring"){
			$ul .= '<li class="active">'.$index2.'</li>';
		}else if($route == "edit_pendataan_jejaring"){
			$ul .= '<li><a href="'.route('pendataan_jejaring').'"> '.$index2.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_pendataan_jejaring"){
			$ul .= '<li><a href="'.route('pendataan_jejaring').'">'.$index2.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "pendataan_asistensi"){
			$ul .= '<li class="active">'.$index3.'</li>';
		}else if($route == "edit_pendataan_asistensi"){
			$ul .= '<li><a href="'.route('pendataan_asistensi').'"> '.$index3.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_pendataan_asistensi"){
			$ul .= '<li><a href="'.route('pendataan_asistensi').'">'.$index3.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "penguatan_asistensi"){
			$ul .= '<li class="active">'.$index4.'</li>';
		}else if($route == "edit_penguatan_asistensi"){
			$ul .= '<li><a href="'.route('penguatan_asistensi').'"> '.$index4.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_penguatan_asistensi"){
			$ul .= '<li><a href="'.route('penguatan_asistensi').'">'.$index4.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "pendataan_intervensi"){
			$ul .= '<li class="active">'.$index5.'</li>';
		}else if($route == "edit_pendataan_intervensi"){
			$ul .= '<li><a href="'.route('pendataan_intervensi').'"> '.$index5.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_pendataan_intervensi"){
			$ul .= '<li><a href="'.route('pendataan_intervensi').'">'.$index5.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "pendataan_supervisi"){
			$ul .= '<li class="active">'.$index6.'</li>';
		}else if($route == "edit_pendataan_supervisi"){
			$ul .= '<li><a href="'.route('pendataan_supervisi').'"> '.$index6.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_pendataan_supervisi"){
			$ul .= '<li><a href="'.route('pendataan_supervisi').'">'.$index6.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "pendataan_monitoring"){
			$ul .= '<li class="active">'.$index7.'</li>';
		}else if($route == "edit_pendataan_monitoring"){
			$ul .= '<li><a href="'.route('pendataan_monitoring').'"> '.$index7.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_pendataan_monitoring"){
			$ul .= '<li><a href="'.route('pendataan_monitoring').'">'.$index7.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "pendataan_bimbingan"){
			$ul .= '<li class="active">'.$index8.'</li>';
		}else if($route == "edit_pendataan_bimbingan"){
			$ul .= '<li><a href="'.route('pendataan_bimbingan').'"> '.$index8.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_pendataan_bimbingan"){
			$ul .= '<li><a href="'.route('pendataan_bimbingan').'">'.$index8.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "pendataan_sosialisasi"){
			$ul .= '<li class="active">'.$index9.'</li>';
		}else if($route == "edit_pendataan_sosialisasi"){
			$ul .= '<li><a href="'.route('pendataan_sosialisasi').'"> '.$index9.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_pendataan_sosialisasi"){
			$ul .= '<li><a href="'.route('pendataan_sosialisasi').'">'.$index9.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}
	}else{
		$ul = "";
	}
	return $ul;
}

function breadcrumps_dir_diseminasi($route){
	$ul = "";
	if(isset($route)){
		$dashboard = route('dashboard');
		$index1 = 'Direktorat Diseminasi Informasi > Data Kegiatan Media Online';
		$index2 = 'Direktorat Diseminasi Informasi > Data Kegiatan Media Penyiaran';
		$index3 = 'Direktorat Diseminasi Informasi > Data Kegiatan Media Cetak';
		$index4 = 'Direktorat Diseminasi Informasi > Data Kegiatan Media Konvensional';
		$index5 = 'Direktorat Diseminasi Informasi > Data Kegiatan Media Videotron';
		$ul = '<ul class="page-breadcrumb breadcrumb">';
		$ul .= '<li><a href="'.$dashboard.'">Beranda</a></li>';
		if($route == "pendataan_online"){
			$ul .= '<li class="active">'.$index1.'</li>';
		}else if($route == "edit_pendataan_online"){
			$ul .= '<li><a href="'.route('pendataan_online').'"> '.$index1.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_pendataan_online"){
			$ul .= '<li><a href="'.route('pendataan_online').'">'.$index1.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "pendataan_penyiaran"){
			$ul .= '<li class="active">'.$index2.'</li>';
		}else if($route == "edit_pendataan_penyiaran"){
			$ul .= '<li><a href="'.route('pendataan_penyiaran').'"> '.$index2.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_pendataan_penyiaran"){
			$ul .= '<li><a href="'.route('pendataan_penyiaran').'">'.$index2.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "pendataan_cetak"){
			$ul .= '<li class="active">'.$index3.'</li>';
		}else if($route == "edit_pendataan_cetak"){
			$ul .= '<li><a href="'.route('pendataan_cetak').'"> '.$index3.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_pendataan_cetak"){
			$ul .= '<li><a href="'.route('pendataan_cetak').'">'.$index3.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "pendataan_konvensional"){
			$ul .= '<li class="active">'.$index4.'</li>';
		}else if($route == "edit_pendataan_konvensional"){
			$ul .= '<li><a href="'.route('pendataan_konvensional').'"> '.$index4.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_pendataan_konvensional"){
			$ul .= '<li><a href="'.route('pendataan_konvensional').'">'.$index4.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "pendataan_videotron"){
			$ul .= '<li class="active">'.$index5.'</li>';
		}else if($route == "edit_pendataan_videotron"){
			$ul .= '<li><a href="'.route('pendataan_videotron').'"> '.$index5.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_pendataan_videotron"){
			$ul .= '<li><a href="'.route('pendataan_videotron').'">'.$index5.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}
	}else{
		$ul = "";
	}
	return $ul;
}

function breadcrumps_dir_masyarakat($route){
	$ul = "";
	if(isset($route)){
		$dashboard = route('dashboard');
		$index1 = 'Direktorat Peran Serta Masyarakat > Data Tes Narkoba';
		$index2 = 'Direktorat Peran Serta Masyarakat > Data Pengembangan Kapasitas';
		$index3 = 'Direktorat Peran Serta Masyarakat > Data Bimbingan Teknis';
		// $index4 = 'Direktorat Peran Serta Masyarakat > Data Kegiatan Pengembangan Kapasitas';
		$index5 = 'Direktorat Peran Serta Masyarakat > Data Kegiatan Monitoring dan Evaluasi';
		$index6 = 'Direktorat Peran Serta Masyarakat > Data Kegiatan Ormas/LSM Anti Narkoba';
		$ul = '<ul class="page-breadcrumb breadcrumb">';
		$ul .= '<li><a href="'.$dashboard.'">Beranda</a></li>';
		if($route == "pendataan_tes_narkoba"){
			$ul .= '<li class="active">'.$index1.'</li>';
		}else if($route == "edit_pendataan_tes_narkoba"){
			$ul .= '<li><a href="'.route('pendataan_tes_narkoba').'"> '.$index1.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_pendataan_tes_narkoba"){
			$ul .= '<li><a href="'.route('pendataan_tes_narkoba').'"> '.$index1.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "pendataan_anti_narkoba"){
			$ul .= '<li class="active">'.$index2.'</li>';
		}else if($route == "edit_pendataan_anti_narkoba"){
			$ul .= '<li><a href="'.route('pendataan_anti_narkoba').'"> '.$index2.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_pendataan_anti_narkoba"){
			$ul .= '<li><a href="'.route('pendataan_anti_narkoba').'"> '.$index2.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "pendataan_pelatihan"){
			$ul .= '<li class="active">'.$index3.'</li>';
		}else if($route == "edit_pendataan_pelatihan"){
			$ul .= '<li><a href="'.route('pendataan_pelatihan').'"> '.$index3.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_pendataan_pelatihan"){
			$ul .= '<li><a href="'.route('pendataan_pelatihan').'"> '.$index3.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "pendataan_kapasitas"){
			$ul .= '<li class="active">'.$index4.'</li>';
		}else if($route == "edit_pendataan_kapasitas"){
			$ul .= '<li><a href="'.route('pendataan_kapasitas').'"> '.$index4.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_pendataan_kapasitas"){
			$ul .= '<li><a href="'.route('pendataan_kapasitas').'"> '.$index4.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "psm_supervisi"){
			$ul .= '<li class="active">'.$index5.'</li>';
		}else if($route == "edit_psm_supervisi"){
			$ul .= '<li><a href="'.route('psm_supervisi').'"> '.$index5.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_psm_supervisi"){
			$ul .= '<li><a href="'.route('psm_supervisi').'"> '.$index5.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "psm_ormas"){
			$ul .= '<li class="active">'.$index6.'</li>';
		}else if($route == "edit_psm_ormas"){
			$ul .= '<li><a href="'.route('psm_ormas').'"> '.$index6.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_psm_ormas"){
			$ul .= '<li><a href="'.route('psm_ormas').'"> '.$index6.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}
	}else{
		$ul = "";
	}
	return $ul;
}


function breadcrumps_dir_alternative($route){
	$ul = "";
	if(isset($route)){
		$dashboard = route('dashboard');
		$index1 = 'Dir Alternative Development > Alih Fungsi Lahan Ganja';
		$index2 = 'Dir Alternative Development > Alih Jenis Profesi/Usaha';
		$index3 = 'Dir Alternative Development > Kawasan Rawan Narkoba';
		$index4 = 'Dir Alternative Development > Monitoring dan Evaluasi Kawasan Rawan Narkotika';
		$index5 = 'Dir Alternative Development > Sinergi';
		$ul = '<ul class="page-breadcrumb breadcrumb">';
		$ul .= '<li><a href="'.$dashboard.'">Beranda</a></li>';
		if($route == "altdev_lahan_ganja"){
			$ul .= '<li class="active">'.$index1.'</li>';
		}else if($route == "edit_altdev_lahan_ganja"){
			$ul .= '<li><a href="'.route('altdev_lahan_ganja').'"> '.$index1.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_altdev_lahan_ganja"){
			$ul .= '<li><a href="'.route('altdev_lahan_ganja').'"> '.$index1.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "altdev_alih_profesi"){
			$ul .= '<li class="active">'.$index2.'</li>';
		}else if($route == "edit_altdev_alih_profesi"){
			$ul .= '<li><a href="'.route('altdev_alih_profesi').'"> '.$index2.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_altdev_alih_profesi"){
			$ul .= '<li><a href="'.route('altdev_alih_profesi').'"> '.$index2.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "altdev_kawasan_rawan"){
			$ul .= '<li class="active">'.$index3.'</li>';
		}else if($route == "edit_altdev_kawasan_rawan"){
			$ul .= '<li><a href="'.route('altdev_kawasan_rawan').'"> '.$index3.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_altdev_kawasan_rawan"){
			$ul .= '<li><a href="'.route('altdev_kawasan_rawan').'"> '.$index3.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "altdev_monitoring"){
			$ul .= '<li class="active">'.$index4.'</li>';
		}else if($route == "edit_altdev_monitoring"){
			$ul .= '<li><a href="'.route('altdev_monitoring').'"> '.$index4.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_altdev_monitoring"){
			$ul .= '<li><a href="'.route('altdev_monitoring').'"> '.$index4.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "altdev_sinergi"){
			$ul .= '<li class="active">'.$index5.'</li>';
		}else if($route == "edit_altdev_sinergitas"){
			$ul .= '<li><a href="'.route('altdev_sinergi').'"> '.$index5.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_altdev_sinergitas"){
			$ul .= '<li><a href="'.route('altdev_sinergi').'"> '.$index5.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}
	}else{
		$ul = "";
	}
	return $ul;
}

function breadcrumps_dir_hukum($route){
	$ul = "";
	if(isset($route)){
		$dashboard = route('dashboard');
		$index1 = 'Direktorat Hukum > Konsultasi Hukum (Non Litigasi)';
		$index2 = 'Direktorat Hukum > Konsultasi Hukum (Audiensi)';
		$index3 = 'Direktorat Hukum > Pembelaan Hukum Pendampingan (Litigasi)';
		$index4 = 'Direktorat Hukum > Pembelaan Hukum Pra Peradilan (Litigasi)';
		$index5 = 'Direktorat Hukum > Pembentukan Penyusunan Perka';
		$index6 = 'Direktorat Hukum > Kegiatan Lainnya';
		$ul = '<ul class="page-breadcrumb breadcrumb">';
		$ul .= '<li><a href="'.$dashboard.'">Beranda</a></li>';
		if($route == "hukum_nonlitigasi"){
			$ul .= '<li class="active">'.$index1.'</li>';
		}else if($route == "edit_hukum_nonlitigasi"){
			$ul .= '<li><a href="'.route('hukum_nonlitigasi').'"> '.$index1.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_hukum_nonlitigasi"){
			$ul .= '<li><a href="'.route('hukum_nonlitigasi').'"> '.$index1.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "hukum_audiensi"){
			$ul .= '<li class="active">'.$index2.'</li>';
		}else if($route == "edit_hukum_audiensi"){
			$ul .= '<li><a href="'.route('hukum_audiensi').'"> '.$index2.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_hukum_audiensi"){
			$ul .= '<li><a href="'.route('hukum_audiensi').'"> '.$index2.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "hukum_pendampingan"){
			$ul .= '<li class="active">'.$index3.'</li>';
		}else if($route == "edit_hukum_pendampingan"){
			$ul .= '<li><a href="'.route('hukum_pendampingan').'"> '.$index3.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_hukum_pendampingan"){
			$ul .= '<li><a href="'.route('hukum_pendampingan').'"> '.$index3.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "hukum_prapradilan"){
			$ul .= '<li class="active">'.$index4.'</li>';
		}else if($route == "edit_hukum_prapradilan"){
			$ul .= '<li><a href="'.route('hukum_prapradilan').'"> '.$index4.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_hukum_prapradilan"){
			$ul .= '<li><a href="'.route('hukum_prapradilan').'"> '.$index4.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "hukum_perka"){
			$ul .= '<li class="active">'.$index5.'</li>';
		}else if($route == "edit_hukum_perka"){
			$ul .= '<li><a href="'.route('hukum_perka').'"> '.$index5.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_hukum_perka"){
			$ul .= '<li><a href="'.route('hukum_perka').'"> '.$index5.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "hukum_lainnya"){
			$ul .= '<li class="active">'.$index6.'</li>';
		}else if($route == "edit_hukum_lainnya"){
			$ul .= '<li><a href="'.route('hukum_lainnya').'"> '.$index6.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_hukum_lainnya"){
			$ul .= '<li><a href="'.route('hukum_lainnya').'"> '.$index6.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}

		// $dashboard = route('dashboard');
		// $index1 = 'Direktorat Hukum > Data Rakor dan Audiensi';
		// $index2 = 'Direktorat Hukum > Pembelaan Hukum (Pendampingan)';
		// $index3 = 'Direktorat Hukum > Pembelaan Hukum (Pra Peradilan)';
		// $index4 = 'Direktorat Hukum > Konsultasi Hukum (Non Litigasi)';
		// $index5 = 'Direktorat Hukum > Pembentukan Perka BNN';
		// $index6 = 'Direktorat Hukum > Sosialisasi Peraturan Perundang-undangan';
		// $index7 = 'Direktorat Hukum > Monev Peraturan Perundang-undangan';
		// $ul = '<ul class="page-breadcrumb breadcrumb">';
		// $ul .= '<li><a href="'.$dashboard.'">Beranda</a></li>';
		// if($route == "hukum_rakor"){
		// 	$ul .= '<li class="active">'.$index1.'</li>';
		// }else if($route == "edit_hukum_rakor"){
		// 	$ul .= '<li><a href="'.route('hukum_rakor').'"> '.$index1.'</a></li>';
		// 	$ul .= '<li class="active">Ubah</li>';
		// }else if($route == "add_hukum_rakor"){
		// 	$ul .= '<li><a href="'.route('hukum_rakor').'"> '.$index1.'</a></li>';
		// 	$ul .= '<li class="active">Tambah</li>';
		// }else if($route == "hukum_pendampingan"){
		// 	$ul .= '<li class="active">'.$index2.'</li>';
		// }else if($route == "edit_hukum_pendampingan"){
		// 	$ul .= '<li><a href="'.route('hukum_pendampingan').'"> '.$index2.'</a></li>';
		// 	$ul .= '<li class="active">Ubah</li>';
		// }else if($route == "add_hukum_pendampingan"){
		// 	$ul .= '<li><a href="'.route('hukum_pendampingan').'"> '.$index2.'</a></li>';
		// 	$ul .= '<li class="active">Tambah</li>';
		// }else if($route == "hukum_prapradilan"){
		// 	$ul .= '<li class="active">'.$index3.'</li>';
		// }else if($route == "edit_hukum_prapradilan"){
		// 	$ul .= '<li><a href="'.route('hukum_prapradilan').'"> '.$index3.'</a></li>';
		// 	$ul .= '<li class="active">Ubah</li>';
		// }else if($route == "add_hukum_prapradilan"){
		// 	$ul .= '<li><a href="'.route('hukum_prapradilan').'"> '.$index3.'</a></li>';
		// 	$ul .= '<li class="active">Tambah</li>';
		// }else if($route == "hukum_nonlitigasi"){
		// 	$ul .= '<li class="active">'.$index4.'</li>';
		// }else if($route == "edit_hukum_nonlitigasi"){
		// 	$ul .= '<li><a href="'.route('hukum_nonlitigasi').'"> '.$index4.'</a></li>';
		// 	$ul .= '<li class="active">Ubah</li>';
		// }else if($route == "add_hukum_nonlitigasi"){
		// 	$ul .= '<li><a href="'.route('hukum_nonlitigasi').'"> '.$index4.'</a></li>';
		// 	$ul .= '<li class="active">Tambah</li>';
		// }else if($route == "hukum_perka"){
		// 	$ul .= '<li class="active">'.$index5.'</li>';
		// }else if($route == "edit_hukum_perka"){
		// 	$ul .= '<li><a href="'.route('hukum_perka').'"> '.$index5.'</a></li>';
		// 	$ul .= '<li class="active">Ubah</li>';
		// }else if($route == "add_hukum_perka"){
		// 	$ul .= '<li><a href="'.route('hukum_perka').'"> '.$index5.'</a></li>';
		// 	$ul .= '<li class="active">Tambah</li>';
		// }else if($route == "hukum_peraturanuu"){
		// 	$ul .= '<li class="active">'.$index6.'</li>';
		// }else if($route == "edit_hukum_peraturanuu"){
		// 	$ul .= '<li><a href="'.route('hukum_peraturanuu').'"> '.$index6.'</a></li>';
		// 	$ul .= '<li class="active">Ubah</li>';
		// }else if($route == "add_hukum_peraturanuu"){
		// 	$ul .= '<li><a href="'.route('hukum_peraturanuu').'"> '.$index6.'</a></li>';
		// 	$ul .= '<li class="active">Tambah</li>';
		// }else if($route == "hukum_monevperaturanuu"){
		// 	$ul .= '<li class="active">'.$index7.'</li>';
		// }else if($route == "edit_hukum_monevperaturanuu"){
		// 	$ul .= '<li><a href="'.route('hukum_monevperaturanuu').'"> '.$index7.'</a></li>';
		// 	$ul .= '<li class="active">Ubah</li>';
		// }else if($route == "add_hukum_monevperaturanuu"){
		// 	$ul .= '<li><a href="'.route('hukum_monevperaturanuu').'"> '.$index7.'</a></li>';
		// 	$ul .= '<li class="active">Tambah</li>';
		// }
	}else{
		$ul = "";
	}
	return $ul;
}

function breadcrumps_dir_kerjasama($route){
	$ul = "";
	if(isset($route)){
		$dashboard = route('dashboard');
		$index1 = 'Direktorat Kerja Sama > Data Kegiatan Kerja Sama Luar Negeri';
		$index2 = 'Direktorat Kerja Sama > Pertemuan';
		$index3 = 'Direktorat Kerja Sama > Nota Kesepahaman';
		$index4 = 'Direktorat Kerja Sama > Kegiatan Kerja Sama Lainnya ';
		$index5 = 'Direktorat Kerja Sama > Monitoring dan Evaluasi Pelaksanaan Kerja Sama';
		$ul = '<ul class="page-breadcrumb breadcrumb">';
		$ul .= '<li><a href="'.$dashboard.'">Beranda</a></li>';
		if($route == "kerjasama_luarnegeri"){
			$ul .= '<li class="active">'.$index1.'</li>';
		}else if($route == "edit_kerjasama_luarnegeri"){
			$ul .= '<li><a href="'.route('kerjasama_luarnegeri').'"> '.$index1.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_kerjasama_luarnegeri"){
			$ul .= '<li><a href="'.route('kerjasama_luarnegeri').'"> '.$index1.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "kerjasama_bilateral"){
			$ul .= '<li class="active">'.$index2.'</li>';
		}else if($route == "edit_kerjasama_bilateral"){
			$ul .= '<li><a href="'.route('kerjasama_bilateral').'"> '.$index2.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_kerjasama_bilateral"){
			$ul .= '<li><a href="'.route('kerjasama_bilateral').'"> '.$index2.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "kerjasama_kesepemahaman"){
			$ul .= '<li class="active">'.$index3.'</li>';
		}else if($route == "edit_kerjasama_kesepemahaman"){
			$ul .= '<li><a href="'.route('kerjasama_kesepemahaman').'"> '.$index3.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_kerjasama_kesepemahaman"){
			$ul .= '<li><a href="'.route('kerjasama_kesepemahaman').'"> '.$index3.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "kerjasama_lainnya"){
			$ul .= '<li class="active">'.$index4.'</li>';
		}else if($route == "edit_kerjasama_lainnya"){
			$ul .= '<li><a href="'.route('kerjasama_lainnya').'"> '.$index4.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_kerjasama_lainnya"){
			$ul .= '<li><a href="'.route('kerjasama_lainnya').'"> '.$index4.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "kerjasama_monev"){
			$ul .= '<li class="active">'.$index5.'</li>';
		}else if($route == "edit_kerjasama_monev"){
			$ul .= '<li><a href="'.route('kerjasama_monev').'"> '.$index5.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_kerjasama_monev"){
			$ul .= '<li><a href="'.route('kerjasama_monev').'"> '.$index5.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}
	}else{
		$ul = "";
	}
	return $ul;
}

function breadcrumps_irtama($route){
	$ul = "";
	if(isset($route)){
		$dashboard = route('dashboard');
		$index1 = 'Irtama > Audit Laporan Hasil Audit';
		$index2 = 'Irtama > Pemantauan Tindak Lanjut';
		$index3 = 'Irtama > Sosialisasi';
		$index4 = 'Irtama > Verifikasi';
		$index5 = 'Irtama > SOP dan kebijakan';
		$index6 = 'Reviu Laporan Keuangan';
		$index7 = 'Reviu Rencana Kerja Anggaran/Lembaga';
		$index8 = 'Reviu Rencana Kebutuhan Barang Milik Negara';
		$index9 = 'Reviu Laporan Kerja Instansi Pemerintah';
		$index10 = 'Irtama > Audit dengan Tujuan Tertentu';
		$index11 = 'Irtama > Penegakan Disiplin';
		$index12 = 'Irtama > Apel Senin dan Hari Besar Lainnya';
		$ul = '<ul class="page-breadcrumb breadcrumb">';
		$ul .= '<li><a href="'.$dashboard.'">Beranda</a></li>';
		if($route == "irtama_audit"){
			$ul .= '<li class="active">'.$index1.'</li>';
		}else if($route == "edit_irtama_audit"){
			$ul .= '<li><a href="'.route('irtama_audit').'"> '.$index1.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_irtama_audit"){
			$ul .= '<li><a href="'.route('irtama_audit').'"> '.$index1.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "irtama_ptl"){
			$ul .= '<li class="active">'.$index2.'</li>';
		}else if($route == "edit_irtama_ptl"){
			$ul .= '<li><a href="'.route('irtama_ptl').'"> '.$index2.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_irtama_ptl"){
			$ul .= '<li><a href="'.route('irtama_ptl').'"> '.$index2.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "irtama_sosialisasi"){
			$ul .= '<li class="active">'.$index3.'</li>';
		}else if($route == "edit_irtama_sosialisasi"){
			$ul .= '<li><a href="'.route('irtama_sosialisasi').'"> '.$index3.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_irtama_sosialisasi"){
			$ul .= '<li><a href="'.route('irtama_sosialisasi').'"> '.$index3.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "irtama_verifikasi"){
			$ul .= '<li class="active">'.$index4.'</li>';
		}else if($route == "edit_irtama_verifikasi"){
			$ul .= '<li><a href="'.route('irtama_verifikasi').'"> '.$index4.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_irtama_verifikasi"){
			$ul .= '<li><a href="'.route('irtama_verifikasi').'"> '.$index4.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "irtama_sop"){
			$ul .= '<li class="active">'.$index5.'</li>';
		}else if($route == "edit_irtama_sop"){
			$ul .= '<li><a href="'.route('irtama_sop').'"> '.$index5.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_irtama_sop"){
			$ul .= '<li><a href="'.route('irtama_sop').'"> '.$index5.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "irtama_lk"){
			$ul .= '<li class="active">'.$index6.'</li>';
		}else if($route == "edit_irtama_lk"){
			$ul .= '<li><a href="'.route('irtama_lk').'"> '.$index6.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_irtama_lk"){
			$ul .= '<li><a href="'.route('irtama_lk').'"> '.$index6.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "irtama_rkakl"){
			$ul .= '<li class="active">'.$index7.'</li>';
		}else if($route == "edit_irtama_rkakl"){
			$ul .= '<li><a href="'.route('irtama_rkakl').'"> '.$index7.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_irtama_rkakl"){
			$ul .= '<li><a href="'.route('irtama_rkakl').'"> '.$index7.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "irtama_rkbmn"){
			$ul .= '<li class="active">'.$index8.'</li>';
		}else if($route == "edit_irtama_rkbmn"){
			$ul .= '<li><a href="'.route('irtama_rkbmn').'"> '.$index8.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_irtama_rkbmn"){
			$ul .= '<li><a href="'.route('irtama_rkbmn').'"> '.$index8.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "irtama_lkip"){
			$ul .= '<li class="active">'.$index9.'</li>';
		}else if($route == "edit_irtama_lkip"){
			$ul .= '<li><a href="'.route('irtama_lkip').'"> '.$index9.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_irtama_lkip"){
			$ul .= '<li><a href="'.route('irtama_lkip').'"> '.$index9.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "irtama_riktu"){
			$ul .= '<li class="active">'.$index10.'</li>';
		}else if($route == "edit_irtama_riktu"){
			$ul .= '<li><a href="'.route('irtama_riktu').'"> '.$index10.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_irtama_riktu"){
			$ul .= '<li><a href="'.route('irtama_riktu').'"> '.$index10.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "irtama_penegakan"){
			$ul .= '<li class="active">'.$index11.'</li>';
		}else if($route == "edit_irtama_penegakan"){
			$ul .= '<li><a href="'.route('irtama_penegakan').'"> '.$index11.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_irtama_penegakan"){
			$ul .= '<li><a href="'.route('irtama_penegakan').'"> '.$index11.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "irtama_apel"){
			$ul .= '<li class="active">'.$index12.'</li>';
		}else if($route == "edit_irtama_apel"){
			$ul .= '<li><a href="'.route('irtama_apel').'"> '.$index12.'</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_irtama_apel"){
			$ul .= '<li><a href="'.route('irtama_apel').'"> '.$index12.'</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}
	}else{
		$ul = "";
	}
	return $ul;
}

function breadcrump_arahan_pimpinan($route){
	$ul = "";
	if(isset($route)){
		$dashboard = route('dashboard');
		$ul = '<ul class="page-breadcrumb breadcrumb">';
		$ul .= '<li><a href="'.$dashboard.'">Beranda</a></li>';
		if($route == "arahan_pimpinan"){
			$ul .= '<li class="active">Data Arahan Pimpinan</li>';
		}else if($route == "edit_arahan_pimpinan"){
			$ul .= '<li><a href="'.route('arahan_pimpinan').'">Arahan Pimpinan</a></li>';
			$ul .= '<li class="active">Ubah Data Arahan Pimpinan</li>';
		}else if($route == "add_arahan_pimpinan"){
			$ul .= '<li><a href="'.route('arahan_pimpinan').'">Arahan Pimpinan</a></li>';
			$ul .= '<li class="active">Tambah Data Arahan Pimpinan</li>';
		}
		$ul .= '</ul>';
	}else{
		$ul = "";
	}

	return $ul;

}


function dropdownPelaksana($selected="",$pusat = false){
	$id_wilayah = "";
	$id = session()->get('wilayah');
	if($id){
		$id_wilayah = "?wilayah=".$id;
	}else{
		$id_wilayah ="";
	}
	$kd_jnswilayah = [];
    $kd_jnswilayah = ['1'=>'BNNP','2'=>'BNNK','6'=>'BNNK','5'=> 'BNN'];
    $array_instansi = [];
    $return = "";
    $select = "";
    $instansi = execute_api_json('api/instansi'.$id_wilayah,'POST',[]);

    if($instansi->code == 200){
        $data_instansi = $instansi->data;
    }else{
        $data_instansi  = [];
    }

    if(count($data_instansi) >= 1){
    	foreach($data_instansi as $i){
    		$wilayah = $kd_jnswilayah[$i->kd_jnswilayah].' '.$i->nm_jnswilayah.' '.$i->nm_wilayah;
    		if($i->wil_id_wilayah){
    			$array_instansi[$i->wil_id_wilayah]['child'][] = ['id_instansi'=>$i->id_instansi,'wilayah'=>$wilayah,'id_wilayah'=>$i->id_wilayah];
    		}else{
    			if($i->id_wilayah == 831){
    				if(in_array($i->id_instansi,config('app.kode_dayamas')) && $pusat == false){
    					$array_instansi[$i->id_wilayah][$i->id_instansi] = ['id_instansi'=>$i->id_instansi,'wilayah'=>$i->nm_instansi,'id_wilayah'=>$i->id_wilayah];
    				}else if(in_array($i->id_instansi,config('app.kode_bnnpusat'))){
    					$array_instansi[$i->id_wilayah][$i->id_instansi] = ['id_instansi'=>$i->id_instansi,'wilayah'=>$i->nm_instansi,'id_wilayah'=>$i->id_wilayah];
    				}
    			}else{
    				$array_instansi[$i->id_wilayah] = ['id_instansi'=>$i->id_instansi,'wilayah'=>$wilayah,'id_wilayah'=>$i->id_wilayah];
    			}
    		}
    	}
			// dd($array_instansi);

	    if(isset($array_instansi[831])){
	    	$put = $array_instansi[831];
		    unset($array_instansi[831]);
		    array_unshift($array_instansi,$put);
	    }
	    if(isset($array_instansi[0])){
		    $shift = $array_instansi[0][config('app.kode_bnnpusat')[0]];
		    unset($array_instansi[0][config('app.kode_bnnpusat')[0]]);
		    array_push($array_instansi[0],$shift);
	    }

	    foreach($array_instansi as $key => $value){
	    	if(isset($value['child'])){
	    		if($selected && isset($value['id_instansi'])){
						if($selected == $value['id_instansi']){
								$select = 'selected="selected"';
						}
	    		}else{
	    			$select = '';
	    		}
				if(isset($value['id_instansi']))	{
					$return .= '<option value="'.$value['id_instansi'].'" '.$select.'>'.$value['wilayah'].'</option>';
				}
				if(count($value['child'])){
	    			foreach($value['child'] as $vc=> $cname){
	    				if($selected  && ($selected == $cname['id_instansi'])){
			    			$select = 'selected="selected"';
			    		}else{
			    			$select = '';
			    		}
						$return .= '<option value="'.$cname['id_instansi'].'" '.$select.'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$cname['wilayah'].'</option>';
	    			}
	    		}

	    	}else{
	    		if(count($value)){
	    			foreach($value as $v=> $name){
	    				if($selected  && ($selected == $name['id_instansi'])){
			    			$select = 'selected="selected"';
			    		}else{
			    			$select = '';
			    		}
						$return .= '<option value="'.$name['id_instansi'].'" '.$select.'>'.$name['wilayah'].'</option>';
	    			}
	    		}
	    	}
	    }
	}else{
		$return .= "";
	}

	return $return;
}

function dropdownLokasiKabupaten($id_wilayah=""){
	$options = "";
	$checked = "";
	$query = Tr_Wilayah::where('kd_jnswilayah','1');
	if($query->count() >= 1 ){
		$result = $query->get();
		foreach($result as $r){
			// $query2 = Tr_Wilayah::where(['wil_id_wilayah'=>$r->id_wilayah])->get();
			// echo '<pre>';
			// print_r($query2);
			// exit();
			// $propinsi = Tr_Instansi::first();
			if($id_wilayah && ($id_wilayah == $r->id_wilayah)){
				$checked = 'selected="selected"';
			}else{
				$checked = "";
			}
			$options .='<option value="'.$r->id_wilayah.'" '.$checked.'>'.$r->jenis_wilayah->nm_jnswilayah.' '.$r->nm_wilayah.'</option>';
			$kabupaten = Tr_Wilayah::where('wil_id_wilayah',$r->id_wilayah);
			if($kabupaten->count() >= 1){
				$kabupatens = $kabupaten->get();

				foreach($kabupatens as $k){
					// $list = $k->instansi;
					// if(isset($k->instansi)){
						if($id_wilayah && ($id_wilayah == $k->id_wilayah)){
							$checked = 'selected="selected"';
						}else{
							$checked = "";
						}
						$options .='<option class="child" value="'.$k->id_wilayah.'" '.$checked.'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$k->jenis_wilayah->nm_jnswilayah.' '.$k->nm_wilayah.'</option>';
					// }
				}
			}
		}
	}else{
		$options = "";
	}
	return $options;
}

function dropdownKabupaten($id_wilayah="",$id_kab=""){
	$options = "";
	$checked = "";
	// $query = Tr_Wilayah::where('kd_jnswilayah','1');
	// if($query->count() >= 1 ){
	// 	$result = $query->get();
	// 	foreach($result as $r){
	// 		// $query2 = Tr_Wilayah::where(['wil_id_wilayah'=>$r->id_wilayah])->get();
	// 		// echo '<pre>';
	// 		// print_r($query2);
	// 		// exit();
	// 		// $propinsi = Tr_Instansi::first();
	// 		if($id_wilayah && ($id_wilayah == $r->id_wilayah)){
	// 			$checked = 'selected="selected"';
	// 		}else{
	// 			$checked = "";
	// 		}
	// 		$options .='<option value="'.$r->id_wilayah.'" '.$checked.'>'.$r->jenis_wilayah->nm_jnswilayah.' '.$r->nm_wilayah.'</option>';
			$kabupaten = Tr_Wilayah::where('wil_id_wilayah',$id_wilayah);
			if($kabupaten->count() >= 1){
				$kabupatens = $kabupaten->get();

				foreach($kabupatens as $k){
					// $list = $k->instansi;
					// if(isset($k->instansi)){
						if($id_kab && ($id_kab == $k->id_wilayah)){
							$checked = 'selected="selected"';
						}else{
							$checked = "";
						}
						$options .='<option value="'.$k->id_wilayah.'" '.$checked.'>'.$k->jenis_wilayah->nm_jnswilayah.' '.$k->nm_wilayah.'</option>';
					// }
				}
			}
		// }
	// }else{
	// 	$options = "";
	// }
	return $options;
}

function dropdownBulan($selected =""){
	$months = ['januari'=>'Januari','februari'=>'Februari','maret'=>'Maret','april'=>"April",'mei'=>"Mei",'juni'=>'Juni','agustus'=>'Agustus','september'=>'September','oktober'=>'Oktober','november'=>'November','desember'=>'Desember'];
	$return = "";
	$checked= "";

	foreach($months as $m => $value){
		if($selected && (strtolower(trim($selected)) == $m)){
			$checked= 'selected="selected"';
		}else{
			$checked = "";
		}
		$return .= '<option value="'.$m.'" '.$checked.'>'.$value.'</option>';
	}
	return $return;
}


function labelPenyelenggara($type,$code){
	$return = "";
	try {
		$value = DB::table('v_lookup_values_type')
			->where(['lookup_type'=> $type,'lookup_value'=>$code])
			->first();
		if($value){
			$return =  $value->lookup_name;
		}
		else{
			$return = $code;
		}

	} catch(\Exception $e) {
		$return = $code;
	}

	return $return;
}

function execute_api_json($url,$method,$params=array()){
	$token = session()->get('token');
	$headers = ['headers'=>[ 'Accept' => 'application/json' ,'Authorization'=>'Bearer '.$token]];
	$client = new \GuzzleHttp\Client($headers);
    try {
		if( (strtolower($method) == 'post') || (strtolower($method) == 'put')|| (strtolower($method) == 'delete')) {
	        $request = $client->request($method,url($url),[$headers, 'form_params' =>$params]);
	        $data = json_decode($request->getBody()->getContents(), false);
	        return $data;
	    }else{
	    	$request = $client->request($method,url($url),[$headers]);
	        $data = json_decode($request->getBody()->getContents(), false);
	        return $data;
	    }
    }catch (\GuzzleHttp\Exception\GuzzleException $e) {
        $response = $e->getResponse();
        if($response){
        	$responseBodyAsString = $response->getBody()->getContents();
        }else{
        	$responseBodyAsString = (Object)['code'=>'200','status'=>'error','messages'=>'Network connection failed'];
        }
        return $responseBodyAsString;
    }
}

// function breadcrump_balai_diklat($route){
// 	$ul = "";
// 	if(isset($route)){
// 		$dashboard = route('dashboard');
// 		$index = 'Data Kegiatan Pendidikan dan Pelatihan Pada Balai Diklat';
// 		$ul = '<ul class="page-breadcrumb breadcrumb">';
// 		$ul .= '<li><a href="'.$dashboard.'">Beranda</a></li>';
// 		if($route == "index_pendidikan_pelatihan"){
// 			$ul .= '<li class="active">'.$index.'</li>';
// 		}else if($route == "add_pendidikan_pelatihan"){
// 			$ul .= '<li><a href="'.route('index_pendidikan_pelatihan').'">Data Balai Laboratorium Narkoba</a></li>';
// 			$ul .= '<li class="active">Tambah '.$index.'</li>';
// 		}else if($route == "edit_pendidikan_pelatihan"){
// 			$ul .= '<li><a href="'.route('index_pendidikan_pelatihan').'">Data Balai Laboratorium Narkoba</a></li>';
// 			$ul .= '<li class="active">Ubah '.$index.'</li>';
// 		}
// 		$ul .= '</ul>';
// 	}else{
// 		$ul = "";
// 	}

// 	return $ul;

// }

function detail_instansi($id_instansi){
	$where = json_encode(['id_isntansi'=>$id_instansi]);
    $datas = execute_api_json('api/detail_instansi/'.$where,'get');
    if($datas->code == 200 && $datas->status != 'error'){
    	return $datas->data->nm_instansi;
    }else{
    	return $id_instansi;
    }
}

function ajax_pagination($currentPage, $itemCount, $itemsPerPage, $adjacentCount, $pageLinkTemplate, $showPrevNext = true) {
	if($itemCount > config('app.limit')) {


		$pagination = "";
	    $firstPage = 1;
	    $lastPage  = ceil($itemCount / $itemsPerPage);
	    if ($lastPage == 1) {
	        return;
	    }
	    if ($currentPage <= $adjacentCount + $adjacentCount) {
	        $firstAdjacentPage = $firstPage;
	        $lastAdjacentPage  = min($firstPage + $adjacentCount + $adjacentCount, $lastPage);
	    } elseif ($currentPage > $lastPage - $adjacentCount - $adjacentCount) {
	        $lastAdjacentPage  = $lastPage;
	        $firstAdjacentPage = $lastPage - $adjacentCount - $adjacentCount;
	    } else {
	        $firstAdjacentPage = $currentPage - $adjacentCount;
	        $lastAdjacentPage  = $currentPage + $adjacentCount;
	    }

	    $pagination .= '<ul class="pagination">';
	    if ($showPrevNext) {
	        if ($currentPage == $firstPage) {
	            // $pagination .= '<li class="disabled"><span>&lt;&lt;</span></li>';
	            $pagination .= '<li><a href="' . (is_callable($pageLinkTemplate) ? $pageLinkTemplate($currentPage - 1) : sprintf($pageLinkTemplate, $currentPage - 1)) . '" onClick="get_page(event,this,'.($currentPage - 1).')">&lt;&lt;</a></li>';
	        } else {
	            $pagination .= '<li><a href="' . (is_callable($pageLinkTemplate) ? $pageLinkTemplate($currentPage - 1) : sprintf($pageLinkTemplate, $currentPage - 1)) . '" onClick="get_page(event,this,'.($currentPage - 1).')">&lt;&lt;</a></li>';
	        }
	    }
	    if ($firstAdjacentPage > $firstPage) {
	        $pagination .= '<li><a href="' . (is_callable($pageLinkTemplate) ? $pageLinkTemplate($firstPage) : sprintf($pageLinkTemplate, $firstPage)) . '"  onClick="get_page(event,this,'.$firstPage.')" >' . $firstPage . '</a></li>';
	        if ($firstAdjacentPage > $firstPage + 1) {
	            $pagination .= '<li><span>...</span></li>';
	        }
	    }
	    for ($i = $firstAdjacentPage; $i <= $lastAdjacentPage; $i++) {
	        if ($currentPage == $i) {
	            // $pagination .= '<li class="active"><a>' . $i . '</a></li>';
	            $pagination .= '<li class="active" ><a href="' . (is_callable($pageLinkTemplate) ? $pageLinkTemplate($i) : sprintf($pageLinkTemplate, $i)) . '"  onClick="get_page(event,this,'.$i.')">' . $i . '</a></li>';
	        } else {
	            $pagination .= '<li><a href="' . (is_callable($pageLinkTemplate) ? $pageLinkTemplate($i) : sprintf($pageLinkTemplate, $i)) . '"  onClick="get_page(event,this,'.$i.')">' . $i . '</a></li>';
	        }
	    }
	    if ($lastAdjacentPage < $lastPage) {
	        if ($lastAdjacentPage < $lastPage - 1) {
	            $pagination .= '<li><span>...</span></li>';
	        }
	        $pagination .= '<li><a href="' . (is_callable($pageLinkTemplate) ? $pageLinkTemplate($lastPage) : sprintf($pageLinkTemplate, $lastPage)) . '"  onClick="get_page(event,this,'.$lastPage.')">' . $lastPage . '</a></li>';
	    }
	    if ($showPrevNext) {
	        if ($currentPage == $lastPage) {
	            // $pagination .= '<li class="disabled"><span>&gt;&gt;</span></li>';
	            $pagination .= '<li><a href="' . (is_callable($pageLinkTemplate) ? $pageLinkTemplate($currentPage + 1) : sprintf($pageLinkTemplate, $currentPage + 1)) . '"  onClick="get_page(event,this,'.($currentPage+1).')">&gt;&gt;</a></li>';

	        } else {
	            $pagination .= '<li><a href="' . (is_callable($pageLinkTemplate) ? $pageLinkTemplate($currentPage + 1) : sprintf($pageLinkTemplate, $currentPage + 1)) . '"  onClick="get_page(event,this,'.($currentPage+1).')">&gt;&gt;</a></li>';
	        }
	    }
	    $pagination .= '</ul>';
	    return $pagination;
	}else{
		return null;
	}
}


function checkCurrentPage($id_segment,$kategori){
	$id = $id_segment;
	$query = Pelatihan::where(['id'=>$id])->select('kategori')->first();
	if($query){
		if( strtolower(trim($query->kategori)) == strtolower(trim($kategori))){
			return true;
		}else{
			return false;
		}
	}
}

function getPelaksana($id_instansi){
	$query = DB::table('v_instansi')->select('nm_instansi')->where('id_instansi',$id_instansi)->first();
	if(count($query) > 0 ){
		return $query->nm_instansi;
	}else{
		return null;
	}
}

function breadcrump_rapat_kerja($route){
	$ul = "";
	if(isset($route)){
		$dashboard = route('dashboard');
		$ul = '<ul class="page-breadcrumb breadcrumb">';
		$ul .= '<li><a href="'.$dashboard.'">Beranda</a></li>';
		if($route == "rapat_kerja_pemetaan"){
			$ul .= '<li class="active">Rapat Kerja Pemetaan</li>';
		}else if($route == "edit_rapat_kerja_pemetaan"){
			$ul .= '<li><a href="'.route('rapat_kerja_pemetaan').'">Rapat Kerja Pemetaan</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_rapat_kerja_pemetaan"){
			$ul .= '<li><a href="'.route('rapat_kerja_pemetaan').'">Rapat Kerja Pemetaan</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "alv_rapat_kerja_pemetaan"){
			$ul .= '<li class="active">Rapat Kerja Pemetaan Dir Alternatif Development</li>';
		}else if($route == "edit_alv_rapat_kerja_pemetaan"){
			$ul .= '<li><a href="'.route('alv_rapat_kerja_pemetaan').'">Rapat Kerja Pemetaan Dir Alternatif Development</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_alv_rapat_kerja_pemetaan"){
			$ul .= '<li><a href="'.route('alv_rapat_kerja_pemetaan').'">Rapat Kerja Pemetaan Dir Alternatif Development</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}
		$ul .= '</ul>';
	}else{
		$ul = "";
	}

	return $ul;
}

function breadcrump_balai_besar($route){
	$ul = "";
	if(isset($route)){
		$dashboard = route('dashboard');
		$ul = '<ul class="page-breadcrumb breadcrumb">';
		$ul .= '<li><a href="'.$dashboard.'">Beranda</a></li>';
		if($route == "data_magang"){
			$ul .= '<li class="active">Magang</li>';
		}else if($route == "edit_magang"){
			$ul .= '<li><a href="'.route('data_magang').'">Magang</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_magang"){
			$ul .= '<li><a href="'.route('data_magang').'">Magang</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}
		$ul .= '</ul>';
	}else{
		$ul = "";
	}

	return $ul;
}

function breadcrumps($route){
	$ul = "";
	if(isset($route)){
		$dashboard = route('dashboard');
		$ul = '<ul class="page-breadcrumb breadcrumb">';
		$ul .= '<li><a href="'.$dashboard.'">Beranda</a></li>';
		if($route == "sekretariat_utama"){
			$ul .= '<li class="active">Sekretariat Utama</li>';
		}else if($route == "edit_sekretariat_utama"){
			$ul .= '<li><a href="'.route('sekretariat_utama').'">Sekretariat Utama</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_sekretariat_utama"){
			$ul .= '<li><a href="'.route('sekretariat_utama').'">Sekretariat Utama</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "settama_keuangan"){
			$ul .= '<li class="active">Biro Keuangan Sekretariat Utama</li>';
		}else if($route == "edit_settama_keuangan"){
			$ul .= '<li><a href="'.route('settama_keuangan').'">Biro Keuangan Sekretariat Utama</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_settama_keuangan"){
			$ul .= '<li><a href="'.route('settama_keuangan').'">Biro Keuangan Sekretariat Utama</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "settama_umum"){
			$ul .= '<li class="active">Biro Umum Sekretariat Utama</li>';
		}else if($route == "edit_settama_umum"){
			$ul .= '<li><a href="'.route('settama_umum').'">Biro Umum Sekretariat Utama</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_settama_umum"){
			$ul .= '<li><a href="'.route('settama_umum').'">Biro Umum Sekretariat Utama</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "settama_kepegawaian"){
			$ul .= '<li class="active">Biro Kepegawaian & Organisasi Sekretariat Utama</li>';
		}else if($route == "edit_settama_kepegawaian"){
			$ul .= '<li><a href="'.route('settama_kepegawaian').'">Biro Kepegawaian & Organisasi Sekretariat Utama</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_settama_kepegawaian"){
			$ul .= '<li><a href="'.route('settama_kepegawaian').'">Biro Kepegawaian & Organisasi Sekretariat Utama</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}else if($route == "settama_perencanaan"){
			$ul .= '<li class="active">Biro Perencanaan Sekretariat Utama</li>';
		}else if($route == "edit_settama_perencanaan"){
			$ul .= '<li><a href="'.route('settama_perencanaan').'">Biro Perencanaan Sekretariat Utama</a></li>';
			$ul .= '<li class="active">Ubah</li>';
		}else if($route == "add_settama_perencanaan"){
			$ul .= '<li><a href="'.route('settama_perencanaan').'">Biro Perencanaan Sekretariat Utama</a></li>';
			$ul .= '<li class="active">Tambah</li>';
		}
		$ul .= '</ul>';
	}else{
		$ul = "";
	}

	return $ul;
}

function getInstansiName($id){
	$result = "";
    try {
      $id_instansi = $id;
      $kd_jnswilayah = ['1'=>'BNNP','2'=>'BNNK','6'=>'BNNK','5'=> 'BNN'];
      $query  = DB::table('v_instansi')
             ->select('kd_jnswilayah','jnsinst' ,'nm_jnswilayah', 'nm_wilayah')
             ->where(['id_instansi'=> $id_instansi]);
      if($query->count()>0){
        $data  =  $query->first();
        $result = $kd_jnswilayah[$data->kd_jnswilayah].' '.$data->nm_jnswilayah.' '.$data->nm_wilayah;
        return $result ;
      }else{
      	return $result ;
      }
    } catch(\Exception $e) {
        return $result ;
    }

}

function getWilayahName($id,$kode = true){
	$result = "";
    try {
      $id_wilayah = $id;

      $query  = Tr_Wilayah::select('kd_jnswilayah' , 'nm_wilayah')
                ->where(['id_wilayah'=> $id_wilayah]);


      if($query->count()>0){
        $data  =  $query->first();
        if($kode== true){
        	$result = $data->jenis_wilayah->nm_jnswilayah.' '.$data->nm_wilayah;
        }else{
        	$result = $data->nm_wilayah;
        }
        return $result ;
      }else{
      	return $result ;
      }
    } catch(\Exception $e) {
        return $result ;
    }

}

function getBentukLayanan($kode=array()){

		$result = "";
	    try {

			$query  = VlookupValue::select('lookup_name')
					->whereIn('lookup_value',$kode);

			if($query->count()>0){
				$data  =  $query->get();
				$result = '<ul class="no-class">';
				foreach($data as $d){
					$result .= '<li>'.$d->lookup_name.'</li>';
				}
				$result .= '</ul>';
				return $result ;
			}else{
				return $result ;
			}
		} catch(\Exception $e) {
			return $result ;
		}
}

function getBentukLayananPrint($kode=array()){

		$result = "";
	    try {

			$query  = VlookupValue::select('lookup_name')
					->whereIn('lookup_value',$kode);

			if($query->count()>0){
				$data  =  $query->get();
				$result = '';
				foreach($data as $d){
					$result .= $d->lookup_name."\n";
				}
				return $result ;
			}else{
				return $result ;
			}
		} catch(\Exception $e) {
			return $result ;
		}
}

function getLookupName($kode){
	$result = "";
    try {

		$query  = VlookupValue::select('lookup_name')
				->where('lookup_value',$kode);

		if($query->count()>0){
			$data  =  $query->first();
			return $data->lookup_name;
		}else{
			return $result ;
		}
	} catch(\Exception $e) {
		return $e ;
	}
}
function paginations($currentPage, $itemCount, $itemsPerPage, $adjacentCount, $pageLinkTemplate,$filtering=false,$filter="", $showPrevNext = true) {

	$pagination = "";
    $firstPage = 1;
    $lastPage  = ceil($itemCount / $itemsPerPage);
    if( ($itemCount < $itemsPerPage )){
    	 return;
    }
    if (($lastPage == 1)){

        return;
    }
    if ($currentPage <= $adjacentCount + $adjacentCount) {
        $firstAdjacentPage = $firstPage;
        $lastAdjacentPage  = min($firstPage + $adjacentCount + $adjacentCount, $lastPage);
    } elseif ($currentPage > $lastPage - $adjacentCount - $adjacentCount) {
        $lastAdjacentPage  = $lastPage;
        $firstAdjacentPage = $lastPage - $adjacentCount - $adjacentCount;
    } else {
        $firstAdjacentPage = $currentPage - $adjacentCount;
        $lastAdjacentPage  = $currentPage + $adjacentCount;
    }
    $pagination .= '<ul class="pagination">';
    if ($showPrevNext) {
        if ($currentPage == $firstPage) {
            $pagination .= '<li class="disabled"><span>&lt;&lt;</span></li>';
        } else {
        	if($filtering){
        		$j = $currentPage - 1;
        		$page = '?page='.$j.$filter;
        		$pagination .= '<li><a href="' . (is_callable($pageLinkTemplate) ? $pageLinkTemplate($currentPage - 1) : $pageLinkTemplate.$page) . '">&lt;&lt;</a></li>';
        	}else{
            	$pagination .= '<li><a href="' . (is_callable($pageLinkTemplate) ? $pageLinkTemplate($currentPage - 1) : $pageLinkTemplate.'/'.( $currentPage - 1)) . '">&lt;&lt;</a></li>';
        	}
        }
    }
    if ($firstAdjacentPage > $firstPage) {
    	if($filtering){
    		$page = '?page='.$firstPage.$filter;
            $pagination .= '<li><a href="' . (is_callable($pageLinkTemplate) ? $pageLinkTemplate($firstPage) : $pageLinkTemplate.$page) . '">' . $firstPage . '</a></li>';
    	}else{
        	$pagination .= '<li><a href="' . (is_callable($pageLinkTemplate) ? $pageLinkTemplate($firstPage) : sprintf($pageLinkTemplate, $firstPage)) . '">' . $firstPage . '</a></li>';
        }
        if ($firstAdjacentPage > $firstPage + 1) {
            $pagination .= '<li><span>...</span></li>';
        }
    }
    for ($i = $firstAdjacentPage; $i <= $lastAdjacentPage; $i++) {
    	if($filtering){
    		$page = '?page='.$i.$filter;
    		if ($currentPage == $i) {
	            $pagination .= '<li class="active"><a>' . $i . '</a></li>';
	        } else {
	            $pagination .= '<li><a href="' . (is_callable($pageLinkTemplate) ? $pageLinkTemplate($i) : $pageLinkTemplate.$page) . '">' . $i . '</a></li>';
	        }
    	}else{
	        if ($currentPage == $i) {
	            $pagination .= '<li class="active"><a>' . $i . '</a></li>';
	        } else {
	            $pagination .= '<li><a href="' . (is_callable($pageLinkTemplate) ? $pageLinkTemplate($i) : $pageLinkTemplate.'/'.$i) . '">' . $i . '</a></li>';
	        }
    	}
    }
    if ($lastAdjacentPage < $lastPage) {
        if ($lastAdjacentPage < $lastPage - 1) {
            $pagination .= '<li><span>...</span></li>';
        }
        if($filtering){
        	$j = $currentPage + 1;
    		$page = '?page='.$lastPage.$filter;
        	$pagination .= '<li><a href="' . (is_callable($pageLinkTemplate) ? $pageLinkTemplate($lastPage) :  $pageLinkTemplate.$page) . '">' . $lastPage . '</a></li>';
        }else{
        	$pagination .= '<li><a href="' . (is_callable($pageLinkTemplate) ? $pageLinkTemplate($lastPage) :  $pageLinkTemplate.'/'.$lastPage) . '">' . $lastPage . '</a></li>';
        }
    }
    if ($showPrevNext) {
        if ($currentPage == $lastPage) {
            $pagination .= '<li class="disabled"><span>&gt;&gt;</span></li>';
        } else {
        	if($filtering){
        		$j = $currentPage + 1;
        		$page = '?page='.$j.$filter;
        		$pagination .= '<li><a href="' . (is_callable($pageLinkTemplate) ? $pageLinkTemplate($currentPage + 1) : $pageLinkTemplate.$page) . '">&gt;&gt;</a></li>';
        	}else{
            	$pagination .= '<li><a href="' . (is_callable($pageLinkTemplate) ? $pageLinkTemplate($currentPage + 1) :$pageLinkTemplate.'/'.($currentPage + 1)) . '">&gt;&gt;</a></li>';
        	}
        }
    }
    $pagination .= '</ul>';
    return $pagination;
}

function getDetailBarangBukti($id){
 	$nm_jnsbrgbukti = "";
    try {
        $jns = DB::table('v_barang_bukti')->select('nm_brgbukti')
                      ->where('id_brgbukti', $id);
        if ($jns->count() > 0 ){
        	$data = $jns->first();
        	$nm_jnsbrgbukti =  $data->nm_brgbukti;
        }
        else {
        	$nm_jnsbrgbukti = "";
        }
    } catch(\Exception $e) {
        $nm_jnsbrgbukti = "";
    }
    return $nm_jnsbrgbukti;
}

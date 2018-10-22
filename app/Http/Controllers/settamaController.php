<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settama\SettamaLookup as SettamaLookup;
use GuzzleHttp\Exception\GuzzleException;
use App\Models\Settama\SekretariatUtama as SekretariatUtama;
use GuzzleHttp\Client;
use Storage;
use URL;
use DB;
class SettamaController extends Controller
{
	public $limit;
	public $data=[];
	public $form_params=[];
	public $selected=[];
    public function sekretariatUtama(Request $request){
    	$this->limit = config('app.limit');
        $this->data['title'] = "Kegiatan Sekretariat Utama";
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
        $datas = execute_api_json('api/settama?'.$limit.'&'.$offset,'get');
    	if(($datas->status != 'error') && ($datas->code == 200) ){
    		$this->data['data'] = $datas->data;
    	}else{
    		$this->data['data'] = [];
    	}
        $this->data['delete_route'] = 'delete_sekretariat_utama';
        $this->data['path'] = $request->path();
        $this->data['route_name'] = $request->route()->getName();
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;
        $total_item = $datas->paginate->totalpage * $this->limit;
        $this->data['pagination'] = pagination($current_page,$total_item, $this->limit, config('app.page_ellipsis'), $request->route()->getPrefix()."/".$request->route()->getName()."/%d");
        $this->data['breadcrumps'] = breadcrumps($request->route()->getName());
    	return view('settama.index_sekretariatUtama',$this->data);
    }
    public function editSekretariatUtama(Request $request){
    	$id = $request->id;
    	$datas = execute_api_json('api/settama/'.$id,'get');
    	if(($datas->status != 'error') && ($datas->code == 200) ){
    		$this->data['data'] = $datas->data;
    		$d = $datas->data;
    		$id_pelaksana = $d->pelaksana;
    		$datas = execute_api_json('api/pelaksana_settama?id_settama_lookup='.$id_pelaksana,'get');
    		if(($datas->code == 200) && ($datas->status != 'error') ){
    			//pelaksana bagian
    			$id_settama_lookup = $datas->data[0]->id_settama_lookup;
				$request_bagian = execute_api_json('/api/pelaksana_bagian?type=pelaksana&id_lookup_parent='.$id_settama_lookup,'GET');
				if(($request_bagian->code == 200) && ($request_bagian->status != 'error') ){
		        	$this->data['bagian'] = $request_bagian->data;
				}else {
				    $this->data['bagian'] = [];
				}

				$request_kegiatan = execute_api_json('/api/settama_jenis_kegiatan/'.$id_settama_lookup,'GET');

       if ($d->anggaran_id != '') {
          $this->data['data_anggaran'] = $this->globalGetAnggaran($request->session()->get('token'), $d->anggaran_id);
       }
				if(($request_kegiatan->code == 200) && ($request_kegiatan->status != 'error') ){
		        	$this->data['kegiatan'] = $request_kegiatan->data;
				}else {
				    $this->data['kegiatan'] = [];
				}


		    }else{
		        $this->data['bagian'] = [];
		        $this->data['kegiatan'] = [];
		    }


	    	$datas = execute_api_json('api/lookup/rujukan','get');
		    if(($datas->code == 200) && ($datas->status != 'error') ){
		        $this->data['rujukan'] = $datas->data;
		    }else{
		        $this->data['rujukan'] = [];
		    }

			$datas = execute_api_json('api/pelaksana_settama','get');
		    if(($datas->code == 200) && ($datas->status != 'error') ){
		        $this->data['pelaksana'] = $datas->data;
		    }else{
		        $this->data['pelaksana'] = [];
		    }
		    $this->data['breadcrumps'] = breadcrumps($request->route()->getName());
		    $this->data['file_path'] = config('app.settama_file_path');
	    	return view('settama.edit_sekretariatUtama',$this->data);
    	}else{
    		$this->data['data'] = [];
    		return back()->with('status',['status'=>'error','message'=>'Data tidak ditemukan']);
    	}

    }
    public function addSekretariatUtama(Request $request){
    	if($request->isMethod('post')){
			$insertId = "";
			$baseUrl = URL::to('/');
        $token = $request->session()->get('token');

        $client = new Client();
	       	if ($request->input('sumber_anggaran')=="DIPA") {
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

    		$this->form_params = $request->except(['_token','kd_anggaran','akode_anggaran','asasaran','apagu','atarget_output','atahun','asatker_code','arefid_anggaran','asatuan_output']);
				$this->form_params['anggaran_id'] = $anggaran;
    		if($request->tgl_mulai){
    			$date = explode('/',$request->tgl_mulai);
    			$this->form_params['tgl_mulai'] = $date[2].'-'.$date[1].'-'.$date[0];
    		}
    		if($request->tgl_selesai){
    			$date = explode('/',$request->tgl_selesai);
    			$this->form_params['tgl_selesai'] = $date[2].'-'.$date[1].'-'.$date[0];
    		}else{
    			$this->form_params['tgl_selesai'] = null;
    		}
    		$meta_peserta= [];
    		$json_peserta = "";
    		if(count($request->meta_peserta) > 0 ){
    			for($i = 0; $i< count($request->meta_peserta); $i++){
    				$d = $request->meta_peserta[$i];
    				if($d['nama_instansi'] || $d['jumlah_peserta']){
    						$meta_peserta[] = ['nama_instansi'=>$d['nama_instansi'] ,'jumlah_peserta'=>$d['jumlah_peserta']];
    				}
    			}
    		}else{
    			$meta_peserta = [];
    		}

    		if(count($meta_peserta) > 0 ){
    			$json_peserta = json_encode($meta_peserta);
    		}else{
    			$json_peserta = "";
    		}
			$this->form_params['meta_peserta'] = $json_peserta;
				//dd($this->form_params);
			$query = execute_api_json('api/settama','POST',$this->form_params);

			$file_message = "";
			if( ($query->code == 200) && ($query->status != 'error') ){
				$insertId = $query->data->eventID;
				if($request->file('file_laporan')){
	                $fileName = $request->file('file_laporan')->getClientOriginalName();
	                $fileName = date('Y-m-d').'_'.$insertId.'_'.$fileName;
	                $extension = $request->file('file_laporan')->getClientOriginalExtension(); // getting image extension
	                $directory = 'Settama';
	                try {
	                    $path = Storage::putFileAs($directory, $request->file('file_laporan'),$fileName);
	                    if($path){
	                        $this->form_params = ['file_laporan'=>$fileName];
	                        $storeFile = execute_api_json('api/settama/'.$insertId,'PUT',$this->form_params);
	                        if( ($storeFile->code == 200) && ($storeFile->status != 'error') ){
	                        	$file_message .= "";
	                        }else{
								$file_message.= "Server Error : Dengan File gagal disimpan.";
	                        }

	                    }else{
	                        $file_message .= "Direktori Error : Dengan File gagal diupload.";
	                    }
	                }catch(\Exception $e){
	                    $file_message .= "Server Error :Dengan File gagal diupload.";
	                }
	            }else{
					$file_message = "";
				}
			}else{
				$file_message = "";
			}

            if( ($query->code == 200) && ($query->status != 'error') ){
            	$this->data['status'] = 'success';
            	$this->data['message'] = 'Data Sekretariat Utama berhasil disimpan. '.$file_message;
            }else{
				$this->data['status'] = 'error';
            	$this->data['message'] = 'Data Sekretariat Utama gagal disimpan. ';
            }
    		return redirect(route('sekretariat_utama'))->with('status',$this->data);

    	}else{
    		$datas = execute_api_json('api/lookup/rujukan','get');

		    if(($datas->code == 200) && ($datas->status != 'error') ){
		        $this->data['rujukan'] = $datas->data;
		    }else{
		        $this->data['rujukan'] = [];
		    }
    		$datas = execute_api_json('api/pelaksana_settama','get');
		    if(($datas->code == 200) && ($datas->status != 'error') ){
		        $this->data['pelaksana'] = $datas->data;
		    }else{
		        $this->data['pelaksana'] = [];
		    }
		    $this->data['breadcrumps'] = breadcrumps($request->route()->getName());
			return view('settama.add_sekretariatUtama',$this->data);
			}
    }
    public function updateSekretariatUtama(Request $request){
    	$id = $request->id;

			$this->form_params = $request->except(['_token','id']);
			if($request->tgl_mulai){
				$date = explode('/',$request->tgl_mulai);
				$this->form_params['tgl_mulai'] = $date[2].'-'.$date[1].'-'.$date[0];
			}
			if($request->tgl_selesai){
				$date = explode('/',$request->tgl_selesai);
				$this->form_params['tgl_selesai'] = $date[2].'-'.$date[1].'-'.$date[0];
			}else{
				$this->form_params['tgl_selesai'] = null;
			}
			$meta_peserta= [];
			$json_peserta = "";
			if(count($request->meta_peserta) > 0 ){
				for($i = 0; $i< count($request->meta_peserta); $i++){
					$d = $request->meta_peserta[$i];
					if($d['nama_instansi'] || $d['jumlah_peserta']){
	    				$meta_peserta[] = ['nama_instansi'=>$d['nama_instansi'] ,'jumlah_peserta'=>$d['jumlah_peserta']];
	    			}
				}
			}else{
				$meta_peserta = [];
			}

			if(count($meta_peserta) > 0 ){
				$json_peserta = json_encode($meta_peserta);
			}else{
				$json_peserta = "";
			}
			$this->form_params['meta_peserta'] = $json_peserta;


			$file_message = "";
			if($request->file('file_laporan')){
	            $fileName = $request->file('file_laporan')->getClientOriginalName();
	            $fileName = date('Y-m-d').'_'.$id.'_'.$fileName;
	            $extension = $request->file('file_laporan')->getClientOriginalExtension(); // getting image extension
	            $directory = 'Settama';
	            try {
	                $path = Storage::putFileAs($directory, $request->file('file_laporan'),$fileName);
	                if($path){
	                    $this->form_params = ['file_laporan'=>$fileName];
	                }else{
	                    $file_message .= "Direktori Error : Dengan File gagal diupload.";
	                }
	            }catch(\Exception $e){
	                $file_message .= "Server Error :Dengan File gagal diupload.";
	            }
	        }else{
				$file_message = "";
			}
			$query = execute_api_json('api/settama/'.$id,'PUT',$this->form_params);
	        if( ($query->code == 200) && ($query->status != 'error') ){
	        	$this->data['status'] = 'success';
	        	$this->data['message'] = 'Data Sekretariat Utama berhasil diperbarui. '.$file_message;
	        }else{
				$this->data['status'] = 'error';
	        	$this->data['message'] = 'Data Sekretariat Utama gagal diperbarui. ';
	        }
			return back()->with('status',$this->data);

    }
    public function deleteSekretariatUtama(Request $request){
    	$id = $request->input('id');
        if ($request->ajax()) {
            if($id){
                $id = $request->id;
                $data_request = execute_api('api/settama/'.$id,'DELETE');
                return $data_request;
            }else{
                $data_request =['status'=>'error','messages'=>'Data Sekretariat Utama Gagal Dihapus'];
                return $data_request;
            }
        }
    }
    public function printPage(Request $request){
        $array_segments = [
            'sekretariat_utama'=>'settama'
        ];
        $array_titles=[
            'sekretariat_utama'=>'Sekretariat Utama'
        ];
        $page = $request->page;
        $segment = $request->segment;
        if($page){
            $page = $page;
        }else{
            $page = 1;
        }
        $result = [];
        $this->limit = config('app.limit');
        $start_number = ($this->limit * ($request->page -1 )) + 1;
        $url = 'api/'.$array_segments[$segment].'?page='.$page;

        $data_request = execute_api($url,'GET');

        $i = $start_number;
        if(count($data_request)>=1){
	      	$data = $data_request['data'];
	      	foreach($data as $key=>$value){
        		$result[$key]['No'] = $i;
	        	$result[$key]['No Rujukan'] = $value['no_rujukan'] ;
	        	$result[$key]['Tanggal Pelaksanaan'] = ( $value['tgl_mulai'] ? date('d/m/Y',strtotime($value['tgl_mulai'])) : ''). ( $value['tgl_selesai'] ? ' - '.date('d/m/Y',strtotime($value['tgl_selesai'])) : '');
	        	$result[$key]['Pelaksana'] =$value['pelaksana_value'];
	        	$result[$key]['Kegiatan'] = $value['jenis_kegiatan_value'];
	        	$peserta = "";
	        	$meta_peserta = $value['meta_peserta'];
	        	if($meta_peserta){
	        		$meta = json_decode($meta_peserta,true);
	        		if(count($meta) > 0 ){
	        			for($i = 0 ; $i < count($meta); $i ++ ){
	        				$peserta .= 'Instansi : '.$meta[$i]['nama_instansi'].' , Jumlah : '.$meta[$i]['jumlah_peserta'];
	        				$peserta .= "\n";
	        			}
	        			$peserta = rtrim($peserta);
	        		}
	        	}
	        	$result[$key]['Peserta']= $peserta;
	        // $result[$key]['Jumlah Peserta'] = $value['materi'];
	        	$i = $i +1;
	      	}
	     	$name = $array_titles[$segment].' '.date('Y-m-d H:i:s');

          	$this->printData($result, $name);
         //  	echo '<pre>';
        	// print_r($result);
        }else{
          return false;
        }

    }

	public function sekretariatUtamaKeuangan(Request $request){
    	/* filter*/
		if($request->limit) {
			$this->limit = $request->limit;
		} else {
			$this->limit = config('app.limit');
		}


		$kondisi = '';
		if($request->isMethod('get')){
        	$get = $request->except(['page']);
        	foreach ($get as $key => $value) {
        		$kondisi .= "&".$key.'='.$value;
        		if(($key == 'tgl_from') || ($key == 'tgl_to')){
        			$value = date('d/m/Y',strtotime($value));
        		}else{
        			$value = $value;
        		}
        		$this->selected[$key] =$value;
        	}
        	$this->selected['selected'] = $request->tipe;
        	$keyword = "";
        	if($request->tipe == 'dipa_anggaran'){
        		$keyword = $request->get('sumber_anggaran');
        	}else{
        		$keyword = $request->get($request->tipe);
        	}
        	$this->selected['kata_kunci'] = $keyword;
        	$this->data['filter'] = $this->selected;
		}else{
			$tipe = trim($request->tipe);
			$kata_kunci = trim($request->kata_kunci);
			$tgl_from = trim($request->tgl_from);
			$tgl_to = trim($request->tgl_to);
			$order = trim($request->order);
			$sumber_anggaran = trim($request->dipa_anggaran);
			$kelengkapan = trim($request->kelengkapan);
			$jenis_kegiatan = trim($request->jenis_kegiatan);

			if($tipe){
				$this->selected['selected'] = $tipe;
				$kondisi .= '&tipe='.$tipe;

			}else if(!$tipe){
				$this->selected['selected'] = "";
				$kondisi .= "";

			}

			if($tgl_from){
				$this->selected['tgl_from'] = $tgl_from;
				$tgl_from = str_replace('/', '-',$tgl_from);
				$tgl_from = date('Y-m-d',strtotime($tgl_from));
				$kondisi .= '&tgl_from='.$tgl_from;
			}else if(!$tgl_from){
				$this->selected['tgl_from'] = "";
				$kondisi .= "";
			}
			if($tgl_to){
				$this->selected['tgl_to'] = $tgl_to;
				$tgl_to = str_replace('/', '-',$tgl_to);
				$tgl_to = date('Y-m-d',strtotime($tgl_to));
				$kondisi .= '&tgl_to='.$tgl_to;
			}else if(!$tgl_to){
				$this->selected['tgl_to'] = "";
				$kondisi .= "";
			}

			if($kata_kunci){
				$this->selected['kata_kunci'] = $kata_kunci;
			}else if(!$kata_kunci){
				$this->selected['kata_kunci'] = "";
				$kondisi .= "";
			}

			if($tipe && $kata_kunci){
				$kondisi .= '&'.$tipe.'='.$kata_kunci;
			}
			if($order){
				$this->selected['order'] = $order;
				$kondisi .= '&order='.$order;
			}else if(!$order){
				$this->selected['order'] = "desc";
				$kondisi .= "";
			}

			if($sumber_anggaran){
				$this->selected['sumber_anggaran'] = $sumber_anggaran;
				$kondisi .= '&sumber_anggaran='.$sumber_anggaran;
			}else if(!$sumber_anggaran){
				$this->selected['sumber_anggaran'] = "";
				$kondisi .= "";
			}
			if($jenis_kegiatan){
				$this->selected['jenis_kegiatan'] = $jenis_kegiatan;
				$kondisi .= '&jenis_kegiatan='.$jenis_kegiatan;
				// $kondisi .= '&tipe='.$jenis_kegiatan;
			}else if(!$jenis_kegiatan){
				$this->selected['jenis_kegiatan'] = "";
				$kondisi .= "";
			}
			if($kelengkapan){
				$this->selected['kelengkapan'] = $kelengkapan;
				$kondisi .= '&kelengkapan='.$kelengkapan;
			}else if(!$order){
				$this->selected['kelengkapan'] = "";
				$kondisi .= "";
			}
			$this->selected['limit'] = $this->limit;
			$kondisi .= '&limit='.$this->limit;
			$this->data['filter'] = $this->selected;
		}


		$filter_parameter['parameter'] = [ 'no_rujukan'=>'No Rujukan','jenis_kegiatan'=>'Jenis Kegiatan',
                      'dipa_anggaran'=>'Sumber Anggaran','periode'=>'Periode', 'kelengkapan'=>'Status Kelengkapan'];
        $filter_parameter['selected'] = [ 'selected'=>$this->selected['selected'],
                      'tgl_from'=> (isset($this->selected['tgl_from']) ? ($this->selected['tgl_from'] ? $this->selected['tgl_from'] : ''): ''),
                      'tgl_to'=>(isset($this->selected['tgl_to']) ? ($this->selected['tgl_to'] ? $this->selected['tgl_to'] : '') : ''),
                      'kata_kunci'=>(isset($this->selected['kata_kunci']) ?($this->selected['kata_kunci'] ? $this->selected['kata_kunci'] : '') : ''),
                      'order'=>(isset($this->selected['order']) ?($this->selected['order'] ? $this->selected['order'] : '') : ''),
                      'limit'=>(isset($this->selected['limit']) ?($this->selected['limit'] ? $this->selected['limit'] : '')  : ''),
                      'sumber_anggaran'=>(isset($this->selected['sumber_anggaran']) ?($this->selected['sumber_anggaran'] ? $this->selected['sumber_anggaran'] : '')  : ''),
                      'kelengkapan'=>(isset($this->selected['kelengkapan']) ?($this->selected['kelengkapan'] ? $this->selected['kelengkapan'] : '')  : ''),
                      'jenis_kegiatan'=>(isset($this->selected['jenis_kegiatan']) ?($this->selected['jenis_kegiatan'] ? $this->selected['jenis_kegiatan'] : '')  : ''),
                    ];
        $filter_parameter['javascript'] = 'onChange=showPeriode(this)';
        $filter_parameter['route'] = $request->route()->getName();
        $request_kegiatan = execute_api_json('/api/settama_jenis_kegiatan/2','GET');
		if(($request_kegiatan->code == 200) && ($request_kegiatan->status != 'error') ){
        	$this->data['kegiatan'] = $request_kegiatan->data;
		}else {
		    $this->data['kegiatan'] = [];
		}

        $this->data['filter_parameter'] = $filter_parameter;
		/* end filter*/
        $this->data['title'] = "Kegiatan Biro Keuangan Sekretariat Utama";
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
        $datas = execute_api_json('api/settama?biro=2&'.$limit.'&'.$offset.$kondisi,'get');
    	if(($datas->status != 'error') && ($datas->code == 200) ){
    		$this->data['data'] = $datas->data;
    	}else{
    		$this->data['data'] = [];
    	}
        $this->data['delete_route'] = 'delete_settama_keuangan';
        $this->data['path'] = $request->path();
				$filtering = false;
	      if($kondisi){
	        $filter = $kondisi;
	        $filtering = true;
	        $this->data['kondisi'] = '?'.$limit.'&'.$offset.$kondisi;
	      }else{
	        $filter = '/';
	        $filtering = false;
	        $this->data['kondisi'] = $current_page;
	      }
        $this->data['route_name'] = $request->route()->getName();
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;
        $total_item = $datas->paginate->totalpage * $this->limit;
				$this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
				$this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), '/'.$request->route()->getPrefix()."/settama_umum",$filtering,$filter);
				//dd($this->data['pagination']);
				$this->data['breadcrumps'] = breadcrumps($request->route()->getName());
    	return view('settama.keuangan.index_sekretariatUtama',$this->data);
    }
    public function editSekretariatUtamaKeuangan(Request $request){
    	$id = $request->id;
    	$datas = execute_api_json('api/settama/'.$id,'get');
    	if(($datas->status != 'error') && ($datas->code == 200) ){
    		$this->data['data'] = $datas->data;
    		$d = $datas->data;
    		$id_pelaksana = $d->pelaksana;
    		$datas = execute_api_json('api/pelaksana_settama?id_settama_lookup='.$id_pelaksana,'get');
    		if(($datas->code == 200) && ($datas->status != 'error') ){
    			//pelaksana bagian
    			$id_settama_lookup = $datas->data[0]->id_settama_lookup;
				$request_bagian = execute_api_json('/api/pelaksana_bagian?type=pelaksana&id_lookup_parent='.$id_settama_lookup,'GET');
				if(($request_bagian->code == 200) && ($request_bagian->status != 'error') ){
		        	$this->data['bagian'] = $request_bagian->data;
				}else {
				    $this->data['bagian'] = [];
				}

				$request_kegiatan = execute_api_json('/api/settama_jenis_kegiatan/'.$id_settama_lookup,'GET');

		       if ($d->anggaran_id != '') {
		          $this->data['data_anggaran'] = $this->globalGetAnggaran($request->session()->get('token'), $d->anggaran_id);
		       }
				if(($request_kegiatan->code == 200) && ($request_kegiatan->status != 'error') ){
		        	$this->data['kegiatan'] = $request_kegiatan->data;
				}else {
				    $this->data['kegiatan'] = [];
				}


		    }else{
		        $this->data['bagian'] = [];
		        $this->data['kegiatan'] = [];
		    }


	    	$datas = execute_api_json('api/lookup/rujukan','get');
		    if(($datas->code == 200) && ($datas->status != 'error') ){
		        $this->data['rujukan'] = $datas->data;
		    }else{
		        $this->data['rujukan'] = [];
		    }

			$datas = execute_api_json('api/pelaksana_settama','get');
		    if(($datas->code == 200) && ($datas->status != 'error') ){
		        $this->data['pelaksana'] = $datas->data;
		    }else{
		        $this->data['pelaksana'] = [];
		    }

		    $this->data['breadcrumps'] = breadcrumps($request->route()->getName());
		    $this->data['file_path'] = config('app.settama_file_path');
	    	return view('settama.keuangan.edit_sekretariatUtama',$this->data);
    	}else{
    		$this->data['data'] = [];
    		return back()->with('status',['status'=>'error','message'=>'Data tidak ditemukan']);
    	}

    }
    public function addSekretariatUtamaKeuangan(Request $request){
    	if($request->isMethod('post')){
    		$insertId = "";
				$baseUrl = URL::to('/');
        	$token = $request->session()->get('token');

        	$client = new Client();
	        if ($request->input('sumber_anggaran')=="DIPA") {
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

    		$this->form_params = $request->except(['_token','kd_anggaran','akode_anggaran','asasaran','apagu','atarget_output','atahun','asatker_code','arefid_anggaran','asatuan_output']);
				$this->form_params['anggaran_id'] = $anggaran;
    		if($request->tgl_mulai){
    			$date = explode('/',$request->tgl_mulai);
    			$this->form_params['tgl_mulai'] = $date[2].'-'.$date[1].'-'.$date[0];
    		}
    		if($request->tgl_selesai){
    			$date = explode('/',$request->tgl_selesai);
    			$this->form_params['tgl_selesai'] = $date[2].'-'.$date[1].'-'.$date[0];
    		}else{
    			$this->form_params['tgl_selesai'] = null;
    		}
    		$meta_peserta= [];
    		$json_peserta = "";
    		if(count($request->meta_peserta) > 0 ){
    			for($i = 0; $i< count($request->meta_peserta); $i++){
    				$d = $request->meta_peserta[$i];
    				if($d['nama_instansi'] || $d['jumlah_peserta']){
    						$meta_peserta[] = ['nama_instansi'=>$d['nama_instansi'] ,'jumlah_peserta'=>$d['jumlah_peserta']];
    				}
    			}
    		}else{
    			$meta_peserta = [];
    		}

    		if(count($meta_peserta) > 0 ){
    			$json_peserta = json_encode($meta_peserta);
    		}else{
    			$json_peserta = "";
    		}
			$this->form_params['meta_peserta'] = $json_peserta;

			$query = execute_api_json('api/settama','POST',$this->form_params);

			$trail['audit_menu'] = 'Sekretariat Utama - Biro Keuangan';
			$trail['audit_event'] = 'post';
			$trail['audit_value'] = json_encode($this->form_params);
			$trail['audit_url'] = $request->url();
			$trail['audit_ip_address'] = $request->ip();
			$trail['audit_user_agent'] = $request->userAgent();
			$trail['audit_message'] = $query->comment;
			$trail['created_at'] = date("Y-m-d H:i:s");
			$trail['created_by'] = $request->session()->get('id');

			$qtrail = $this->inputtrail($token,$trail);

			$file_message = "";
			$insertId = "";
			if( ($query->code == 200) && ($query->status != 'error') ){
				$insertId = $query->data->eventID;
				if($request->file('file_laporan')){
	                $fileName = $request->file('file_laporan')->getClientOriginalName();
	                $fileName = date('Y-m-d').'_'.$insertId.'_'.$fileName;
	                $extension = $request->file('file_laporan')->getClientOriginalExtension(); // getting image extension
	                $directory = 'Settama';
	                try {
	                    $path = Storage::putFileAs($directory, $request->file('file_laporan'),$fileName);
	                    if($path){
	                        $this->form_params = ['file_laporan'=>$fileName];
	                        $storeFile = execute_api_json('api/settama/'.$insertId,'PUT',$this->form_params);
	                        if( ($storeFile->code == 200) && ($storeFile->status != 'error') ){
	                        	$file_message .= "";
	                        }else{
								$file_message.= "Server Error : Dengan File gagal disimpan.";
	                        }

	                    }else{
	                        $file_message .= "Direktori Error : Dengan File gagal diupload.";
	                    }
	                }catch(\Exception $e){
	                    $file_message .= "Server Error :Dengan File gagal diupload.";
	                }
	            }else{
					$file_message = "";
				}
			}else{
				$file_message = "";
			}
			$this->kelengkapan($insertId);
            if( ($query->code == 200) && ($query->status != 'error') ){
            	$this->data['status'] = 'success';
            	$this->data['message'] = 'Data Sekretariat Utama berhasil disimpan. '.$file_message;
            }else{
				$this->data['status'] = 'error';
            	$this->data['message'] = 'Data Sekretariat Utama gagal disimpan. ';
            }
    		return redirect(route('settama_keuangan'))->with('status',$this->data);

    	}else{
    		$datas = execute_api_json('api/lookup/rujukan','get');

		    if(($datas->code == 200) && ($datas->status != 'error') ){
		        $this->data['rujukan'] = $datas->data;
		    }else{
		        $this->data['rujukan'] = [];
		    }
    		$datas = execute_api_json('api/pelaksana_settama','get');
		    if(($datas->code == 200) && ($datas->status != 'error') ){
		        $this->data['pelaksana'] = $datas->data;
		    }else{
		        $this->data['pelaksana'] = [];
		    }
			$request_bagian = execute_api_json('/api/pelaksana_bagian?type=pelaksana&id_lookup_parent=2','GET');

			if(($request_bagian->code == 200) && ($request_bagian->status != 'error') ){
	        	$this->data['bagian'] = $request_bagian->data;
			}else {
			    $this->data['bagian'] = [];
			}
			$request_kegiatan = execute_api_json('/api/settama_jenis_kegiatan/2','GET');
			if(($request_kegiatan->code == 200) && ($request_kegiatan->status != 'error') ){
	        	$this->data['kegiatan'] = $request_kegiatan->data;
			}else {
			    $this->data['kegiatan'] = [];
			}
		    $this->data['breadcrumps'] = breadcrumps($request->route()->getName());
			return view('settama.keuangan.add_sekretariatUtama',$this->data);
		}
    }
	public function updateSekretariatUtamaKeuangan(Request $request){
    	$id = $request->id;

			$baseUrl = URL::to('/');
    	$token = $request->session()->get('token');
    	$client = new Client();
			if ($request->input('sumber_anggaran')=="DIPA") {
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

		$this->form_params = $request->except(['_token','id','aid_anggaran','kd_anggaran','akode_anggaran','asasaran','apagu','atarget_output','atahun','asatker_code','arefid_anggaran','asatuan_output']);
		$this->form_params['anggaran_id'] = $anggaran;
		if($request->tgl_mulai){
				$date = explode('/',$request->tgl_mulai);
				$this->form_params['tgl_mulai'] = $date[2].'-'.$date[1].'-'.$date[0];
			}
			if($request->tgl_selesai){
				$date = explode('/',$request->tgl_selesai);
				$this->form_params['tgl_selesai'] = $date[2].'-'.$date[1].'-'.$date[0];
			}else{
				$this->form_params['tgl_selesai'] = null;
			}
			$meta_peserta= [];
			$json_peserta = "";
			if(count($request->meta_peserta) > 0 ){
				for($i = 0; $i< count($request->meta_peserta); $i++){
					$d = $request->meta_peserta[$i];
					if($d['nama_instansi'] || $d['jumlah_peserta']){
	    				$meta_peserta[] = ['nama_instansi'=>$d['nama_instansi'] ,'jumlah_peserta'=>$d['jumlah_peserta']];
	    			}
				}
			}else{
				$meta_peserta = [];
			}

			if(count($meta_peserta) > 0 ){
				$json_peserta = json_encode($meta_peserta);
			}else{
				$json_peserta = "";
			}
			$this->form_params['meta_peserta'] = $json_peserta;


			$file_message = "";
			if($request->file('file_laporan')){
	            $fileName = $request->file('file_laporan')->getClientOriginalName();
	            $fileName = date('Y-m-d').'_'.$id.'_'.$fileName;
	            $extension = $request->file('file_laporan')->getClientOriginalExtension(); // getting image extension
	            $directory = 'Settama';
	            try {
	                $path = Storage::putFileAs($directory, $request->file('file_laporan'),$fileName);
	                if($path){
	                    $this->form_params = ['file_laporan'=>$fileName];
	                }else{
	                    $file_message .= "Direktori Error : Dengan File gagal diupload.";
	                }
	            }catch(\Exception $e){
	                $file_message .= "Server Error :Dengan File gagal diupload.";
	            }
	        }else{
				$file_message = "";
			}

			$query = execute_api_json('api/settama/'.$id,'PUT',$this->form_params);

			$trail['audit_menu'] = 'Sekretariat Utama - Biro Keuangan';
			$trail['audit_event'] = 'put';
			$trail['audit_value'] = json_encode($this->form_params);
			$trail['audit_url'] = $request->url();
			$trail['audit_ip_address'] = $request->ip();
			$trail['audit_user_agent'] = $request->userAgent();
			$trail['audit_message'] = $query->comment;
			$trail['created_at'] = date("Y-m-d H:i:s");
			$trail['created_by'] = $request->session()->get('id');

			$qtrail = $this->inputtrail($token,$trail);

			$this->kelengkapan($id);
	        if( ($query->code == 200) && ($query->status != 'error') ){
	        	$this->data['status'] = 'success';
	        	$this->data['message'] = 'Data Sekretariat Utama berhasil diperbarui. '.$file_message;
	        }else{
				$this->data['status'] = 'error';
	        	$this->data['message'] = 'Data Sekretariat Utama gagal diperbarui. ';
	        }
			return back()->with('status',$this->data);

    }
    public function deleteSekretariatUtamaKeuangan(Request $request){
    	$id = $request->input('id');
        if ($request->ajax()) {
            if($id){
                $id = $request->id;

								$this->form_params['delete_id'] = $id;
                $data_request = execute_api('api/settama/'.$id,'DELETE');

								$trail['audit_menu'] = 'Sekretariat Utama - Biro Keuangan';
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
                $data_request =['status'=>'error','messages'=>'Data Sekretariat Utama Gagal Dihapus'];
                return $data_request;
            }
        }
    }
	public function printPageKeuangan(Request $request){
        $array_segments = [
            'sekretariat_utama'=>'settama'
        ];
        $array_titles=[
            'sekretariat_utama'=>'Sekretariat Utama Biro Keuangan'
        ];

				$get = $request->all();
				$kondisi = "";
        if(count($get)>0){
          foreach($get as $key=>$val){
            $kondisi .= $key.'='.$val.'&';
          }
          $kondisi = rtrim($kondisi,'&');
          $kondisi = '?biro=2&'.$kondisi;
        }else{
          $kondisi = '?biro=2&page='.$request->page;
        }

        $page = $request->page;
        if($page){
          $start_number = ($request->limit * ($request->page -1 )) + 1;
        }else{
          $start_number = 1;
        }
        $segment = $request->segment;
        // if($page){
        //     $page = $page;
        // }else{
        //     $page = 1;
        // }
        $result = [];
        $url = 'api/settama'.$kondisi;

        $data_request = execute_api($url,'GET');

        $no = $start_number;
        if(count($data_request)>=1){
	      	$data = $data_request['data'];
	      	foreach($data as $key=>$value){
        		$result[$key]['No'] = $no;
	        	$result[$key]['No Rujukan'] = $value['no_rujukan'] ;
	        	$result[$key]['Tanggal Pelaksanaan'] = ( $value['tgl_mulai'] ? date('d/m/Y',strtotime($value['tgl_mulai'])) : ''). ( $value['tgl_selesai'] ? ' - '.date('d/m/Y',strtotime($value['tgl_selesai'])) : '');
	        	$result[$key]['Jenis Kegiatan'] =$value['nama_jenis_kegiatan'];
	        	$result[$key]['Sumber Anggaran'] = $value['sumber_anggaran'];
	        	$result[$key]['Status'] = (($value['status'] == "Y") ? 'Lengkap' : (($value['status'] == 'N') ? 'Tidak lengkap' : ''));
	        	$peserta = "";
	        	$meta_peserta = $value['meta_peserta'];
	        	if($meta_peserta){
	        		$meta = json_decode($meta_peserta,true);
	        		if(count($meta) > 0 ){
	        			for($i = 0 ; $i < count($meta); $i ++ ){
	        				$peserta .= 'Instansi : '.$meta[$i]['nama_instansi'].' , Jumlah : '.$meta[$i]['jumlah_peserta'];
	        				$peserta .= "\n";
	        			}
	        			$peserta = rtrim($peserta);
	        		}
	        	}
	        	$result[$key]['Peserta']= $peserta;
	        // $result[$key]['Jumlah Peserta'] = $value['materi'];
	        	$no = $no +1;
	      	}
	     	$name = $array_titles['sekretariat_utama'].' '.date('Y-m-d H:i:s');

          	$this->printData($result, $name);
         //  	echo '<pre>';
        	// print_r($result);
        }else{
          return false;
        }

    }

	public function sekretariatUtamaUmum(Request $request){
		/* filter*/
		if($request->limit) {
			$this->limit = $request->limit;
		} else {
			$this->limit = config('app.limit');
		}


		$kondisi = '';
		$this->selected['limit'] = $this->limit;
    	$this->data['filter'] = $this->selected;
		if($request->isMethod('get')){
        	$get = $request->except(['page']);
        	foreach ($get as $key => $value) {
        		$kondisi .= "&".$key.'='.$value;
        		if(($key == 'tgl_from') || ($key == 'tgl_to')){
        			$value = date('d/m/Y',strtotime($value));
        		}else{
        			$value = $value;
        		}
        		$this->selected[$key] =$value;
        	}
        	$this->selected['selected'] = $request->tipe;
        	$keyword = "";
        	if($request->tipe == 'dipa_anggaran'){
        		$keyword = $request->get('sumber_anggaran');
        	}else{
        		$keyword = $request->get($request->tipe);
        	}
        	$this->selected['kata_kunci'] = $keyword;
        	$this->data['filter'] = $this->selected;

		}else{
			$tipe = trim($request->tipe);
			$kata_kunci = trim($request->kata_kunci);
			$tgl_from = trim($request->tgl_from);
			$tgl_to = trim($request->tgl_to);
			$order = trim($request->order);
			$sumber_anggaran = trim($request->dipa_anggaran);
			$kelengkapan = trim($request->kelengkapan);
			$jenis_kegiatan = trim($request->jenis_kegiatan);

			if($tgl_from){
				$this->selected['tgl_from'] = $tgl_from;
				$tgl_from = str_replace('/', '-',$tgl_from);
				$tgl_from = date('Y-m-d',strtotime($tgl_from));
				$kondisi .= '&tgl_from='.$tgl_from;
			}else if(!$tgl_from){
				$this->selected['tgl_from'] = "";
				$kondisi .= "";
			}
			if($tgl_to){
				$this->selected['tgl_to'] = $tgl_to;
				$tgl_to = str_replace('/', '-',$tgl_to);
				$tgl_to = date('Y-m-d',strtotime($tgl_to));
				$kondisi .= '&tgl_to='.$tgl_to;
			}else if(!$tgl_to){
				$this->selected['tgl_to'] = "";
				$kondisi .= "";
			}

			if($kata_kunci){
				$this->selected['kata_kunci'] = $kata_kunci;
			}else if(!$kata_kunci){
				$this->selected['kata_kunci'] = "";
				$kondisi .= "";
			}
			if($jenis_kegiatan){
				$this->selected['jenis_kegiatan'] = $jenis_kegiatan;
				$kondisi .= '&jenis_kegiatan='.$jenis_kegiatan;
				// $kondisi .= '&tipe='.$jenis_kegiatan;
			}else if(!$jenis_kegiatan){
				$this->selected['jenis_kegiatan'] = "";
				$kondisi .= "";
			}

			if($tipe && $kata_kunci){
				$kondisi .= '&'.$tipe.'='.$kata_kunci;
			}
			if($order){
				$this->selected['order'] = $order;
				$kondisi .= '&order='.$order;
			}else if(!$order){
				$this->selected['order'] = "desc";
				$kondisi .= "";
			}

			if($sumber_anggaran){
				$this->selected['sumber_anggaran'] = $sumber_anggaran;
				$kondisi .= '&sumber_anggaran='.$sumber_anggaran;
			}else if(!$order){
				$this->selected['sumber_anggaran'] = "";
				$kondisi .= "";
			}

			if($kelengkapan){
				$this->selected['kelengkapan'] = $kelengkapan;
				$kondisi .= '&kelengkapan='.$kelengkapan;
			}else if(!$kelengkapan){
				$this->selected['kelengkapan'] = "";
				$kondisi .= "";
			}
			if($tipe){
				$this->selected['selected'] = $tipe;
				$kondisi .= '&tipe='.$tipe;

			}else if(!$tipe){
				$this->selected['selected'] = "";
				$kondisi .= "";
			}

			$this->selected['limit'] = $this->limit;
			$kondisi .= '&limit='.$this->limit;
			$this->data['filter'] = $this->selected;
		}

		$filter_parameter['parameter'] = [ 'no_rujukan'=>'No Rujukan','jenis_kegiatan'=>'Jenis Kegiatan',
                      'dipa_anggaran'=>'Sumber Anggaran','periode'=>'Periode', 'kelengkapan'=>'Status Kelengkapan'];
        $filter_parameter['selected'] = [ 'selected'=>$this->selected['selected'],
                      'tgl_from'=> (isset($this->selected['tgl_from']) ? ($this->selected['tgl_from'] ? $this->selected['tgl_from'] : ''): ''),
                      'tgl_to'=>(isset($this->selected['tgl_to']) ? ($this->selected['tgl_to'] ? $this->selected['tgl_to'] : '') : ''),
                      'kata_kunci'=>(isset($this->selected['kata_kunci']) ?($this->selected['kata_kunci'] ? $this->selected['kata_kunci'] : '') : ''),
                      'order'=>(isset($this->selected['order']) ?($this->selected['order'] ? $this->selected['order'] : '') : ''),
                      'limit'=>(isset($this->selected['limit']) ?($this->selected['limit'] ? $this->selected['limit'] : '')  : ''),
                      'sumber_anggaran'=>(isset($this->selected['sumber_anggaran']) ?($this->selected['sumber_anggaran'] ? $this->selected['sumber_anggaran'] : '')  : ''),
                      'kelengkapan'=>(isset($this->selected['kelengkapan']) ?($this->selected['kelengkapan'] ? $this->selected['kelengkapan'] : '')  : ''),
                      'jenis_kegiatan'=>(isset($this->selected['jenis_kegiatan']) ?($this->selected['jenis_kegiatan'] ? $this->selected['jenis_kegiatan'] : '')  : ''),
                    ];
        $filter_parameter['javascript'] = 'onChange=showPeriode(this)';
        $filter_parameter['route'] = $request->route()->getName();

        $this->data['filter_parameter'] = $filter_parameter;
		/* end filter*/
        $this->data['title'] = "Kegiatan Biro Umum Sekretariat Utama";
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;


        $datas = execute_api_json('api/settama?biro=1&'.$limit.'&'.$offset.$kondisi,'get');

        // exit();
    	if(($datas->status != 'error') && ($datas->code == 200) ){
    		$this->data['data'] = $datas->data;
    	}else{
    		$this->data['data'] = [];
    	}

    	$request_kegiatan = execute_api_json('/api/settama_jenis_kegiatan/2','GET');
		if(($request_kegiatan->code == 200) && ($request_kegiatan->status != 'error') ){
        	$this->data['kegiatan'] = $request_kegiatan->data;
		}else {
		    $this->data['kegiatan'] = [];
		}

        $this->data['delete_route'] = 'delete_settama_umum';
        $this->data['path'] = $request->path();
        $this->data['route_name'] = $request->route()->getName();
        $this->data['start_number'] = $start_number;
				$filtering = false;
	      if($kondisi){
	        $filter = $kondisi;
	        $filtering = true;
	        $this->data['kondisi'] = '?'.$limit.'&'.$offset.$kondisi;
	      }else{
	        $filter = '/';
	        $filtering = false;
	        $this->data['kondisi'] = $current_page;
	      }
        $this->data['current_page'] = $current_page;
        $total_item = $datas->paginate->totalpage * $this->limit;
		$this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));

		if($kondisi){
			$filter = $kondisi;
			$filtering = true;
		}else{
			$filter = '/';
			$filtering = false;
		}

        $this->data['pagination'] = paginations($current_page,$total_item, $this->limit, config('app.page_ellipsis'), config('app.url')."/".$request->route()->getPrefix()."/".$request->route()->getName(),$filtering,$filter );

        $this->data['breadcrumps'] = breadcrumps($request->route()->getName());
    	return view('settama.umum.index_sekretariatUtama',$this->data);
    }
    public function editSekretariatUtamaUmum(Request $request){
    	$id = $request->id;
    	$datas = execute_api_json('api/settama/'.$id,'get');
    	if(($datas->status != 'error') && ($datas->code == 200) ){
    		$this->data['data'] = $datas->data;
    		$d = $datas->data;
    		$id_pelaksana = $d->pelaksana;
    		$datas = execute_api_json('api/pelaksana_settama?id_settama_lookup='.$id_pelaksana,'get');
    		if(($datas->code == 200) && ($datas->status != 'error') ){
    			//pelaksana bagian
    			$id_settama_lookup = $datas->data[0]->id_settama_lookup;
				$request_bagian = execute_api_json('/api/pelaksana_bagian?type=pelaksana&id_lookup_parent='.$id_settama_lookup,'GET');
				if(($request_bagian->code == 200) && ($request_bagian->status != 'error') ){
		        	$this->data['bagian'] = $request_bagian->data;
				}else {
				    $this->data['bagian'] = [];
				}

	       if ($d->anggaran_id != '') {
	          $this->data['data_anggaran'] = $this->globalGetAnggaran($request->session()->get('token'), $d->anggaran_id);
	       }
				$request_kegiatan = execute_api_json('/api/settama_jenis_kegiatan/'.$id_settama_lookup,'GET');
				if(($request_kegiatan->code == 200) && ($request_kegiatan->status != 'error') ){
		        	$this->data['kegiatan'] = $request_kegiatan->data;
				}else {
				    $this->data['kegiatan'] = [];
				}


		    }else{
		        $this->data['bagian'] = [];
		        $this->data['kegiatan'] = [];
		    }


	    	$datas = execute_api_json('api/lookup/rujukan','get');
		    if(($datas->code == 200) && ($datas->status != 'error') ){
		        $this->data['rujukan'] = $datas->data;
		    }else{
		        $this->data['rujukan'] = [];
		    }

			$datas = execute_api_json('api/pelaksana_settama','get');
		    if(($datas->code == 200) && ($datas->status != 'error') ){
		        $this->data['pelaksana'] = $datas->data;
		    }else{
		        $this->data['pelaksana'] = [];
		    }
		    $this->data['breadcrumps'] = breadcrumps($request->route()->getName());
		    $this->data['file_path'] = config('app.settama_file_path');
	    	return view('settama.umum.edit_sekretariatUtama',$this->data);
    	}else{
    		$this->data['data'] = [];
    		return back()->with('status',['status'=>'error','message'=>'Data tidak ditemukan']);
    	}

    }
    public function addSekretariatUtamaUmum(Request $request){
    	if($request->isMethod('post')){
    		$insertId = "";
			$baseUrl = URL::to('/');
        	$token = $request->session()->get('token');

        	$client = new Client();
	       	if ($request->input('sumber_anggaran')=="DIPA") {
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

    		$this->form_params = $request->except(['_token','kd_anggaran','akode_anggaran','asasaran','apagu','atarget_output','atahun','asatker_code','arefid_anggaran','asatuan_output']);
			$this->form_params['anggaran_id'] = $anggaran;
    		if($request->tgl_mulai){
    			$date = explode('/',$request->tgl_mulai);
    			$this->form_params['tgl_mulai'] = $date[2].'-'.$date[1].'-'.$date[0];
    		}
    		if($request->tgl_selesai){
    			$date = explode('/',$request->tgl_selesai);
    			$this->form_params['tgl_selesai'] = $date[2].'-'.$date[1].'-'.$date[0];
    		}else{
    			$this->form_params['tgl_selesai'] = null;
    		}
    		$meta_peserta= [];
    		$json_peserta = "";
    		if(count($request->meta_peserta) > 0 ){
    			for($i = 0; $i< count($request->meta_peserta); $i++){
    				$d = $request->meta_peserta[$i];
    				if($d['nama_instansi'] || $d['jumlah_peserta']){
    						$meta_peserta[] = ['nama_instansi'=>$d['nama_instansi'] ,'jumlah_peserta'=>$d['jumlah_peserta']];
    				}
    			}
    		}else{
    			$meta_peserta = [];
    		}

    		if(count($meta_peserta) > 0 ){
    			$json_peserta = json_encode($meta_peserta);
    		}else{
    			$json_peserta = "";
    		}
			$this->form_params['meta_peserta'] = $json_peserta;

			$query = execute_api_json('api/settama','POST',$this->form_params);

			$trail['audit_menu'] = 'Sekretariat Utama - Biro Umum';
			$trail['audit_event'] = 'post';
			$trail['audit_value'] = json_encode($this->form_params);
			$trail['audit_url'] = $request->url();
			$trail['audit_ip_address'] = $request->ip();
			$trail['audit_user_agent'] = $request->userAgent();
			$trail['audit_message'] = $query->comment;
			$trail['created_at'] = date("Y-m-d H:i:s");
			$trail['created_by'] = $request->session()->get('id');

			$qtrail = $this->inputtrail($token,$trail);


			$file_message = "";
			$fileName ="";
			if( ($query->code == 200) && ($query->status != 'error') ){
				$insertId = $query->data->eventID;
				if($request->file('file_laporan')){
	                $fileName = $request->file('file_laporan')->getClientOriginalName();
	                $fileName = date('Y-m-d').'_'.$insertId.'_'.$fileName;
	                $extension = $request->file('file_laporan')->getClientOriginalExtension(); // getting image extension
	                $directory = 'Settama';
	                try {
	                    $path = Storage::putFileAs($directory, $request->file('file_laporan'),$fileName);
	                    if($path){
	                        $this->form_params = ['file_laporan'=>$fileName];
	                        $storeFile = execute_api_json('api/settama/'.$insertId,'PUT',$this->form_params);
	                        if( ($storeFile->code == 200) && ($storeFile->status != 'error') ){
	                        	$file_message .= "";
	                        }else{
								$file_message.= "Server Error : File gagal disimpan.";
	                        }

	                    }else{
	                        $file_message .= "Direktori Error : File gagal diupload.";
	                    }
	                }catch(\Exception $e){
	                    $file_message .= "Server Error :File gagal diupload.";
	                }
	            }else{
					$file_message = "";
				}

				$this->kelengkapan($insertId);

			}else{
				$file_message = "";
			}

            if( ($query->code == 200) && ($query->status != 'error') ){
            	$this->data['status'] = 'success';
            	$this->data['message'] = 'Data Sekretariat Utama berhasil disimpan. '.$file_message;
            }else{
				$this->data['status'] = 'error';
            	$this->data['message'] = 'Data Sekretariat Utama gagal disimpan. ';
            }
    		 return redirect(route('settama_umum'))->with('status',$this->data);

    	}else{
    		$datas = execute_api_json('api/lookup/rujukan','get');

		    if(($datas->code == 200) && ($datas->status != 'error') ){
		        $this->data['rujukan'] = $datas->data;
		    }else{
		        $this->data['rujukan'] = [];
		    }
    		$datas = execute_api_json('api/pelaksana_settama','get');
		    if(($datas->code == 200) && ($datas->status != 'error') ){
		        $this->data['pelaksana'] = $datas->data;
		    }else{
		        $this->data['pelaksana'] = [];
		    }

			$request_bagian = execute_api_json('/api/pelaksana_bagian?type=pelaksana&id_lookup_parent=1','GET');
			if(($request_bagian->code == 200) && ($request_bagian->status != 'error') ){
	        	$this->data['bagian'] = $request_bagian->data;
			}else {
			    $this->data['bagian'] = [];
			}

			$request_kegiatan = execute_api_json('/api/settama_jenis_kegiatan/1','GET');
			if(($request_kegiatan->code == 200) && ($request_kegiatan->status != 'error') ){
	        	$this->data['kegiatan'] = $request_kegiatan->data;
			}else {
			    $this->data['kegiatan'] = [];
			}

		    $this->data['breadcrumps'] = breadcrumps($request->route()->getName());
			return view('settama.umum.add_sekretariatUtama',$this->data);
		}
    }
	public function updateSekretariatUtamaUmum(Request $request){
    	$id = $request->id;

			$baseUrl = URL::to('/');
    	$token = $request->session()->get('token');
    	$client = new Client();
			if ($request->input('sumber_anggaran')=="DIPA") {
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

		$this->form_params = $request->except(['_token','id','aid_anggaran','kd_anggaran','akode_anggaran','asasaran','apagu','atarget_output','atahun','asatker_code','arefid_anggaran','asatuan_output']);
		$this->form_params['anggaran_id'] = $anggaran;
		if($request->tgl_mulai){
				$date = explode('/',$request->tgl_mulai);
				$this->form_params['tgl_mulai'] = $date[2].'-'.$date[1].'-'.$date[0];
			}
			if($request->tgl_selesai){
				$date = explode('/',$request->tgl_selesai);
				$this->form_params['tgl_selesai'] = $date[2].'-'.$date[1].'-'.$date[0];
			}else{
				$this->form_params['tgl_selesai'] = null;
			}
			$meta_peserta= [];
			$json_peserta = "";
			if(count($request->meta_peserta) > 0 ){
				for($i = 0; $i< count($request->meta_peserta); $i++){
					$d = $request->meta_peserta[$i];
					if($d['nama_instansi'] || $d['jumlah_peserta']){
	    				$meta_peserta[] = ['nama_instansi'=>$d['nama_instansi'] ,'jumlah_peserta'=>$d['jumlah_peserta']];
	    			}
				}
			}else{
				$meta_peserta = [];
			}

			if(count($meta_peserta) > 0 ){
				$json_peserta = json_encode($meta_peserta);
			}else{
				$json_peserta = "";
			}
			$this->form_params['meta_peserta'] = $json_peserta;


			$file_message = "";
			$fileName = "";
			if($request->file('file_laporan')){
	            $fileName = $request->file('file_laporan')->getClientOriginalName();
	            $fileName = date('Y-m-d').'_'.$id.'_'.$fileName;
	            $extension = $request->file('file_laporan')->getClientOriginalExtension(); // getting image extension
	            $directory = 'Settama';
	            try {
	                $path = Storage::putFileAs($directory, $request->file('file_laporan'),$fileName);
	                if($path){
	                    $this->form_params = ['file_laporan'=>$fileName];
	                }else{
	                    $file_message .= "Direktori Error : Dengan File gagal diupload.";
	                }
	            }catch(\Exception $e){
	                $file_message .= "Server Error :Dengan File gagal diupload.";
	            }
	        }else{
				$file_message = "";
			}

			$query = execute_api_json('api/settama/'.$id,'PUT',$this->form_params);

			$trail['audit_menu'] = 'Sekretariat Utama - Biro Umum';
			$trail['audit_event'] = 'put';
			$trail['audit_value'] = json_encode($this->form_params);
			$trail['audit_url'] = $request->url();
			$trail['audit_ip_address'] = $request->ip();
			$trail['audit_user_agent'] = $request->userAgent();
			$trail['audit_message'] = $query->comment;
			$trail['created_at'] = date("Y-m-d H:i:s");
			$trail['created_by'] = $request->session()->get('id');

			$qtrail = $this->inputtrail($token,$trail);

			$this->kelengkapan($id);
	        if( ($query->code == 200) && ($query->status != 'error') ){
	        	$this->data['status'] = 'success';
	        	$this->data['message'] = 'Data Sekretariat Utama berhasil diperbarui. '.$file_message;
	        }else{
				$this->data['status'] = 'error';
	        	$this->data['message'] = 'Data Sekretariat Utama gagal diperbarui. ';
	        }
			return back()->with('status',$this->data);

    }
    public function deleteSekretariatUtamaUmum(Request $request){
    	$id = $request->input('id');
        if ($request->ajax()) {
            if($id){
                $id = $request->id;

								$this->form_params['delete_id'] = $id;
                $data_request = execute_api('api/settama/'.$id,'DELETE');

								$trail['audit_menu'] = 'Sekretariat Utama - Biro Umum';
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
                $data_request =['status'=>'error','messages'=>'Data Sekretariat Utama Gagal Dihapus'];
                return $data_request;
            }
        }
    }
	public function printPageUmum(Request $request){
        $array_segments = [
            'sekretariat_utama'=>'settama'
        ];
        $array_titles=[
            'sekretariat_utama'=>'Sekretariat Utama Biro Umum'
        ];

				$get = $request->all();
				$kondisi = "";
        if(count($get)>0){
          foreach($get as $key=>$val){
            $kondisi .= $key.'='.$val.'&';
          }
          $kondisi = rtrim($kondisi,'&');
          $kondisi = '?biro=1&'.$kondisi;
        }else{
          $kondisi = '?biro=1&page='.$request->page;
        }

        $page = $request->page;
        if($page){
          $start_number = ($request->limit * ($request->page -1 )) + 1;
        }else{
          $start_number = 1;
        }
        $segment = $request->segment;
        // if($page){
        //     $page = $page;
        // }else{
        //     $page = 1;
        // }
        $result = [];
        $url = 'api/settama'.$kondisi;

        $data_request = execute_api($url,'GET');

        $no = $start_number;
        if(count($data_request)>=1){
	      	$data = $data_request['data'];
	      	foreach($data as $key=>$value){
						$result[$key]['No'] = $no;
	        	$result[$key]['No Rujukan'] = $value['no_rujukan'] ;
	        	$result[$key]['Tanggal Pelaksanaan'] = ( $value['tgl_mulai'] ? date('d/m/Y',strtotime($value['tgl_mulai'])) : ''). ( $value['tgl_selesai'] ? ' - '.date('d/m/Y',strtotime($value['tgl_selesai'])) : '');
	        	$result[$key]['Jenis Kegiatan'] =$value['nama_jenis_kegiatan'];
	        	$result[$key]['Sumber Anggaran'] = $value['sumber_anggaran'];
	        	$result[$key]['Status'] = (($value['status'] == "Y") ? 'Lengkap' : (($value['status'] == 'N') ? 'Tidak lengkap' : ''));
	        	$peserta = "";
	        	$meta_peserta = $value['meta_peserta'];
	        	if($meta_peserta){
	        		$meta = json_decode($meta_peserta,true);
	        		if(count($meta) > 0 ){
	        			for($i = 0 ; $i < count($meta); $i ++ ){
	        				$peserta .= 'Instansi : '.$meta[$i]['nama_instansi'].' , Jumlah : '.$meta[$i]['jumlah_peserta'];
	        				$peserta .= "\n";
	        			}
	        			$peserta = rtrim($peserta);
	        		}
	        	}
	        	$result[$key]['Peserta']= $peserta;
	        // $result[$key]['Jumlah Peserta'] = $value['materi'];
	        	$no = $no +1;
	      	}
	     	$name = $array_titles['sekretariat_utama'].' '.date('Y-m-d H:i:s');

          	$this->printData($result, $name);
         //  	echo '<pre>';
        	// print_r($result);
        }else{
          return false;
        }

    }

	public function sekretariatUtamaPerencanaan(Request $request){
		/* filter*/
		if($request->limit) {
			$this->limit = $request->limit;
		} else {
			$this->limit = config('app.limit');
		}


		$kondisi = '';
		if($request->isMethod('get')){
        	$get = $request->except(['page']);
        	foreach ($get as $key => $value) {
        		$kondisi .= "&".$key.'='.$value;
        		if(($key == 'tgl_from') || ($key == 'tgl_to')){
        			$value = date('d/m/Y',strtotime($value));
        		}else{
        			$value = $value;
        		}
        		$this->selected[$key] =$value;
        	}
        	$this->selected['selected'] = $request->tipe;
        	$keyword = "";
        	if($request->tipe == 'dipa_anggaran'){
        		$keyword = $request->get('sumber_anggaran');
        	}else{
        		$keyword = $request->get($request->tipe);
        	}
        	$this->selected['kata_kunci'] = $keyword;
        	$this->data['filter'] = $this->selected;
		}else{
			$tipe = trim($request->tipe);
			$kata_kunci = trim($request->kata_kunci);
			$tgl_from = trim($request->tgl_from);
			$tgl_to = trim($request->tgl_to);
			$order = trim($request->order);
			$sumber_anggaran = trim($request->dipa_anggaran);
			$kelengkapan = trim($request->kelengkapan);
			$jenis_kegiatan = trim($request->jenis_kegiatan);

			if($tipe){
				$this->selected['selected'] = $tipe;
				$kondisi .= '&tipe='.$tipe;

			}else if(!$tipe){
				$this->selected['selected'] = "";
				$kondisi .= "";

			}
			if($jenis_kegiatan){
				$this->selected['jenis_kegiatan'] = $jenis_kegiatan;
				$kondisi .= '&jenis_kegiatan='.$jenis_kegiatan;
				// $kondisi .= '&tipe='.$jenis_kegiatan;
			}else if(!$jenis_kegiatan){
				$this->selected['jenis_kegiatan'] = "";
				$kondisi .= "";
			}
			if($tgl_from){
				$this->selected['tgl_from'] = $tgl_from;
				$tgl_from = str_replace('/', '-',$tgl_from);
				$tgl_from = date('Y-m-d',strtotime($tgl_from));
				$kondisi .= '&tgl_from='.$tgl_from;
			}else if(!$tgl_from){
				$this->selected['tgl_from'] = "";
				$kondisi .= "";
			}
			if($tgl_to){
				$this->selected['tgl_to'] = $tgl_to;
				$tgl_to = str_replace('/', '-',$tgl_to);
				$tgl_to = date('Y-m-d',strtotime($tgl_to));
				$kondisi .= '&tgl_to='.$tgl_to;
			}else if(!$tgl_to){
				$this->selected['tgl_to'] = "";
				$kondisi .= "";
			}

			if($kata_kunci){
				$this->selected['kata_kunci'] = $kata_kunci;
			}else if(!$kata_kunci){
				$this->selected['kata_kunci'] = "";
				$kondisi .= "";
			}

			if($tipe && $kata_kunci){
				$kondisi .= '&'.$tipe.'='.$kata_kunci;
			}
			if($order){
				$this->selected['order'] = $order;
				$kondisi .= '&order='.$order;
			}else if(!$order){
				$this->selected['order'] = "desc";
				$kondisi .= "";
			}

			if($sumber_anggaran){
				$this->selected['sumber_anggaran'] = $sumber_anggaran;
				$kondisi .= '&sumber_anggaran='.$sumber_anggaran;
			}else if(!$order){
				$this->selected['sumber_anggaran'] = "";
				$kondisi .= "";
			}

			if($kelengkapan){
				$this->selected['kelengkapan'] = $kelengkapan;
				$kondisi .= '&kelengkapan='.$kelengkapan;
			}else if(!$order){
				$this->selected['kelengkapan'] = "";
				$kondisi .= "";
			}
			$this->selected['limit'] = $this->limit;
			$kondisi .= '&limit='.$this->limit;
			$this->data['filter'] = $this->selected;
		}


		$filter_parameter['parameter'] = [ 'no_rujukan'=>'No Rujukan','jenis_kegiatan'=>'Jenis Kegiatan',
                      'dipa_anggaran'=>'Sumber Anggaran','periode'=>'Periode', 'kelengkapan'=>'Status Kelengkapan'];
        $filter_parameter['selected'] = [ 'selected'=>$this->selected['selected'],
                      'tgl_from'=> (isset($this->selected['tgl_from']) ? ($this->selected['tgl_from'] ? $this->selected['tgl_from'] : ''): ''),
                      'tgl_to'=>(isset($this->selected['tgl_to']) ? ($this->selected['tgl_to'] ? $this->selected['tgl_to'] : '') : ''),
                      'kata_kunci'=>(isset($this->selected['kata_kunci']) ?($this->selected['kata_kunci'] ? $this->selected['kata_kunci'] : '') : ''),
                      'order'=>(isset($this->selected['order']) ?($this->selected['order'] ? $this->selected['order'] : '') : ''),
                      'limit'=>(isset($this->selected['limit']) ?($this->selected['limit'] ? $this->selected['limit'] : '')  : ''),
                      'sumber_anggaran'=>(isset($this->selected['sumber_anggaran']) ?($this->selected['sumber_anggaran'] ? $this->selected['sumber_anggaran'] : '')  : ''),
                      'kelengkapan'=>(isset($this->selected['kelengkapan']) ?($this->selected['kelengkapan'] ? $this->selected['kelengkapan'] : '')  : ''),
                      'jenis_kegiatan'=>(isset($this->selected['jenis_kegiatan']) ?($this->selected['jenis_kegiatan'] ? $this->selected['jenis_kegiatan'] : '')  : ''),

                    ];
        $filter_parameter['javascript'] = 'onChange=showPeriode(this)';
        $filter_parameter['route'] = $request->route()->getName();

        $this->data['filter_parameter'] = $filter_parameter;
		/* end filter*/
        $this->data['title'] = "Kegiatan Biro Perencanaan Sekretariat Utama";
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;

        $datas = execute_api_json('api/settama?biro=4&'.$limit.'&'.$offset.$kondisi,'get');

    	if(($datas->status != 'error') && ($datas->code == 200) ){
    		$this->data['data'] = $datas->data;
    	}else{
    		$this->data['data'] = [];
    	}
        $this->data['delete_route'] = 'delete_settama_perencanaan';
        $this->data['path'] = $request->path();
        $this->data['route_name'] = $request->route()->getName();
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;
				$filtering = false;
	      if($kondisi){
	        $filter = $kondisi;
	        $filtering = true;
	        $this->data['kondisi'] = '?'.$limit.'&'.$offset.$kondisi;
	      }else{
	        $filter = '/';
	        $filtering = false;
	        $this->data['kondisi'] = $current_page;
	      }
	    $request_kegiatan = execute_api_json('/api/settama_jenis_kegiatan/2','GET');
		if(($request_kegiatan->code == 200) && ($request_kegiatan->status != 'error') ){
        	$this->data['kegiatan'] = $request_kegiatan->data;
		}else {
		    $this->data['kegiatan'] = [];
		}

        $total_item = $datas->paginate->totalpage * $this->limit;
				$this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['pagination'] = pagination($current_page,$total_item, $this->limit, config('app.page_ellipsis'), config('app.url').$request->route()->getPrefix()."/".$request->route()->getName()."/%d");
        $this->data['breadcrumps'] = breadcrumps($request->route()->getName());
    	return view('settama.perencanaan.index_sekretariatUtama',$this->data);
    }
    public function editSekretariatUtamaPerencanaan(Request $request){
    	$id = $request->id;
    	$datas = execute_api_json('api/settama/'.$id,'get');
    	if(($datas->status != 'error') && ($datas->code == 200) ){
    		$this->data['data'] = $datas->data;
    		$d = $datas->data;
    		$id_pelaksana = $d->pelaksana;
    		$datas = execute_api_json('api/pelaksana_settama?id_settama_lookup='.$id_pelaksana,'get');
    		if(($datas->code == 200) && ($datas->status != 'error') ){
    			//pelaksana bagian
    			$id_settama_lookup = $datas->data[0]->id_settama_lookup;
				$request_bagian = execute_api_json('/api/pelaksana_bagian?type=pelaksana&id_lookup_parent='.$id_settama_lookup,'GET');
				if(($request_bagian->code == 200) && ($request_bagian->status != 'error') ){
		        	$this->data['bagian'] = $request_bagian->data;
				}else {
				    $this->data['bagian'] = [];
				}

				$request_kegiatan = execute_api_json('/api/settama_jenis_kegiatan/'.$id_settama_lookup,'GET');

       if ($d->anggaran_id != '') {
          $this->data['data_anggaran'] = $this->globalGetAnggaran($request->session()->get('token'), $d->anggaran_id);
       }
				if(($request_kegiatan->code == 200) && ($request_kegiatan->status != 'error') ){
		        	$this->data['kegiatan'] = $request_kegiatan->data;
				}else {
				    $this->data['kegiatan'] = [];
				}


		    }else{
		        $this->data['bagian'] = [];
		        $this->data['kegiatan'] = [];
		    }


	    	$datas = execute_api_json('api/lookup/rujukan','get');
		    if(($datas->code == 200) && ($datas->status != 'error') ){
		        $this->data['rujukan'] = $datas->data;
		    }else{
		        $this->data['rujukan'] = [];
		    }

			$datas = execute_api_json('api/pelaksana_settama','get');
		    if(($datas->code == 200) && ($datas->status != 'error') ){
		        $this->data['pelaksana'] = $datas->data;
		    }else{
		        $this->data['pelaksana'] = [];
		    }
		    $this->data['breadcrumps'] = breadcrumps($request->route()->getName());
		    $this->data['file_path'] = config('app.settama_file_path');
	    	return view('settama.perencanaan.edit_sekretariatUtama',$this->data);
    	}else{
    		$this->data['data'] = [];
    		return back()->with('status',['status'=>'error','message'=>'Data tidak ditemukan']);
    	}

    }
    public function addSekretariatUtamaPerencanaan(Request $request){
    	if($request->isMethod('post')){
    		$insertId = "";
			$baseUrl = URL::to('/');
    		$token = $request->session()->get('token');

        	$client = new Client();
	        if ($request->input('sumber_anggaran')=="DIPA") {
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

    		$this->form_params = $request->except(['_token','kd_anggaran','akode_anggaran','asasaran','apagu','atarget_output','atahun','asatker_code','arefid_anggaran','asatuan_output']);
				$this->form_params['anggaran_id'] = $anggaran;
    		if($request->tgl_mulai){
    			$date = explode('/',$request->tgl_mulai);
    			$this->form_params['tgl_mulai'] = $date[2].'-'.$date[1].'-'.$date[0];
    		}
    		if($request->tgl_selesai){
    			$date = explode('/',$request->tgl_selesai);
    			$this->form_params['tgl_selesai'] = $date[2].'-'.$date[1].'-'.$date[0];
    		}else{
    			$this->form_params['tgl_selesai'] = null;
    		}
    		$meta_peserta= [];
    		$json_peserta = "";
    		if(count($request->meta_peserta) > 0 ){
    			for($i = 0; $i< count($request->meta_peserta); $i++){
    				$d = $request->meta_peserta[$i];
    				if($d['nama_instansi'] || $d['jumlah_peserta']){
    						$meta_peserta[] = ['nama_instansi'=>$d['nama_instansi'] ,'jumlah_peserta'=>$d['jumlah_peserta']];
    				}
    			}
    		}else{
    			$meta_peserta = [];
    		}

    		if(count($meta_peserta) > 0 ){
    			$json_peserta = json_encode($meta_peserta);
    		}else{
    			$json_peserta = "";
    		}
			$this->form_params['meta_peserta'] = $json_peserta;

			$query = execute_api_json('api/settama','POST',$this->form_params);

			$trail['audit_menu'] = 'Sekretariat Utama - Biro Perencanaan';
			$trail['audit_event'] = 'post';
			$trail['audit_value'] = json_encode($this->form_params);
			$trail['audit_url'] = $request->url();
			$trail['audit_ip_address'] = $request->ip();
			$trail['audit_user_agent'] = $request->userAgent();
			$trail['audit_message'] = $query->comment;
			$trail['created_at'] = date("Y-m-d H:i:s");
			$trail['created_by'] = $request->session()->get('id');

			$qtrail = $this->inputtrail($token,$trail);


			$file_message = "";
			$insertId = "";
			if( ($query->code == 200) && ($query->status != 'error') ){
				$insertId = $query->data->eventID;
				if($request->file('file_laporan')){
	                $fileName = $request->file('file_laporan')->getClientOriginalName();
	                $fileName = date('Y-m-d').'_'.$insertId.'_'.$fileName;
	                $extension = $request->file('file_laporan')->getClientOriginalExtension(); // getting image extension
	                $directory = 'Settama';
	                try {
	                    $path = Storage::putFileAs($directory, $request->file('file_laporan'),$fileName);
	                    if($path){
	                        $this->form_params = ['file_laporan'=>$fileName];
	                        $storeFile = execute_api_json('api/settama/'.$insertId,'PUT',$this->form_params);
	                        if( ($storeFile->code == 200) && ($storeFile->status != 'error') ){
	                        	$file_message .= "";
	                        }else{
								$file_message.= "Server Error : Dengan File gagal disimpan.";
	                        }

	                    }else{
	                        $file_message .= "Direktori Error : Dengan File gagal diupload.";
	                    }
	                }catch(\Exception $e){
	                    $file_message .= "Server Error :Dengan File gagal diupload.";
	                }
	            }else{
					$file_message = "";
				}
			}else{
				$file_message = "";
			}
			$this->kelengkapan($insertId);
            if( ($query->code == 200) && ($query->status != 'error') ){
            	$this->data['status'] = 'success';
            	$this->data['message'] = 'Data Sekretariat Utama berhasil disimpan. '.$file_message;
            }else{
				$this->data['status'] = 'error';
            	$this->data['message'] = 'Data Sekretariat Utama gagal disimpan. ';
            }
    		return redirect(route('settama_perencanaan'))->with('status',$this->data);

    	}else{
    		$datas = execute_api_json('api/lookup/rujukan','get');

		    if(($datas->code == 200) && ($datas->status != 'error') ){
		        $this->data['rujukan'] = $datas->data;
		    }else{
		        $this->data['rujukan'] = [];
		    }
    		$datas = execute_api_json('api/pelaksana_settama','get');
		    if(($datas->code == 200) && ($datas->status != 'error') ){
		        $this->data['pelaksana'] = $datas->data;
		    }else{
		        $this->data['pelaksana'] = [];
		    }
			$request_bagian = execute_api_json('/api/pelaksana_bagian?type=pelaksana&id_lookup_parent=4','GET');
			;
			if(($request_bagian->code == 200) && ($request_bagian->status != 'error') ){
	        	$this->data['bagian'] = $request_bagian->data;
			}else {
			    $this->data['bagian'] = [];
			}
				$request_kegiatan = execute_api_json('/api/settama_jenis_kegiatan/4','GET');
				if(($request_kegiatan->code == 200) && ($request_kegiatan->status != 'error') ){
		        	$this->data['kegiatan'] = $request_kegiatan->data;
				}else {
				    $this->data['kegiatan'] = [];
				}
		    $this->data['breadcrumps'] = breadcrumps($request->route()->getName());
			return view('settama.perencanaan.add_sekretariatUtama',$this->data);
		}
    }
	public function updateSekretariatUtamaPerencanaan(Request $request){
    	$id = $request->id;

			$baseUrl = URL::to('/');
    	$token = $request->session()->get('token');
    	$client = new Client();
			if ($request->input('sumber_anggaran')=="DIPA") {
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

		$this->form_params = $request->except(['_token','id','aid_anggaran','kd_anggaran','akode_anggaran','asasaran','apagu','atarget_output','atahun','asatker_code','arefid_anggaran','asatuan_output']);
		$this->form_params['anggaran_id'] = $anggaran;
		if($request->tgl_mulai){
				$date = explode('/',$request->tgl_mulai);
				$this->form_params['tgl_mulai'] = $date[2].'-'.$date[1].'-'.$date[0];
			}
			if($request->tgl_selesai){
				$date = explode('/',$request->tgl_selesai);
				$this->form_params['tgl_selesai'] = $date[2].'-'.$date[1].'-'.$date[0];
			}else{
				$this->form_params['tgl_selesai'] = null;
			}
			$meta_peserta= [];
			$json_peserta = "";
			if(count($request->meta_peserta) > 0 ){
				for($i = 0; $i< count($request->meta_peserta); $i++){
					$d = $request->meta_peserta[$i];
					if($d['nama_instansi'] || $d['jumlah_peserta']){
	    				$meta_peserta[] = ['nama_instansi'=>$d['nama_instansi'] ,'jumlah_peserta'=>$d['jumlah_peserta']];
	    			}
				}
			}else{
				$meta_peserta = [];
			}

			if(count($meta_peserta) > 0 ){
				$json_peserta = json_encode($meta_peserta);
			}else{
				$json_peserta = "";
			}
			$this->form_params['meta_peserta'] = $json_peserta;


			$file_message = "";
			if($request->file('file_laporan')){
	            $fileName = $request->file('file_laporan')->getClientOriginalName();
	            $fileName = date('Y-m-d').'_'.$id.'_'.$fileName;
	            $extension = $request->file('file_laporan')->getClientOriginalExtension(); // getting image extension
	            $directory = 'Settama';
	            try {
	                $path = Storage::putFileAs($directory, $request->file('file_laporan'),$fileName);
	                if($path){
	                    $this->form_params = ['file_laporan'=>$fileName];
	                }else{
	                    $file_message .= "Direktori Error : Dengan File gagal diupload.";
	                }
	            }catch(\Exception $e){
	                $file_message .= "Server Error :Dengan File gagal diupload.";
	            }
	        }else{
				$file_message = "";
			}

			$query = execute_api_json('api/settama/'.$id,'PUT',$this->form_params);

			$trail['audit_menu'] = 'Sekretariat Utama - Biro Perencanaan';
			$trail['audit_event'] = 'put';
			$trail['audit_value'] = json_encode($this->form_params);
			$trail['audit_url'] = $request->url();
			$trail['audit_ip_address'] = $request->ip();
			$trail['audit_user_agent'] = $request->userAgent();
			$trail['audit_message'] = $query->comment;
			$trail['created_at'] = date("Y-m-d H:i:s");
			$trail['created_by'] = $request->session()->get('id');

			$qtrail = $this->inputtrail($token,$trail);

			$this->kelengkapan($id);
	        if( ($query->code == 200) && ($query->status != 'error') ){
	        	$this->data['status'] = 'success';
	        	$this->data['message'] = 'Data Sekretariat Utama berhasil diperbarui. '.$file_message;
	        }else{
				$this->data['status'] = 'error';
	        	$this->data['message'] = 'Data Sekretariat Utama gagal diperbarui. ';
	        }
			return back()->with('status',$this->data);

    }
    public function deleteSekretariatUtamaPerencanaan(Request $request){
    	$id = $request->input('id');
        if ($request->ajax()) {
            if($id){
                $id = $request->id;

								$this->form_params['delete_id'] = $id;
                $data_request = execute_api('api/settama/'.$id,'DELETE');

								$trail['audit_menu'] = 'Sekretariat Utama - Biro Perencanaan';
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
                $data_request =['status'=>'error','messages'=>'Data Sekretariat Utama Gagal Dihapus'];
                return $data_request;
            }
        }
    }
	public function printPagePerencanaan(Request $request){
        $array_segments = [
            'sekretariat_utama'=>'settama'
        ];
        $array_titles=[
            'sekretariat_utama'=>'Sekretariat Utama Biro Perencanaan.'
        ];

				$get = $request->all();
				$kondisi = "";
        if(count($get)>0){
          foreach($get as $key=>$val){
            $kondisi .= $key.'='.$val.'&';
          }
          $kondisi = rtrim($kondisi,'&');
          $kondisi = '?biro=4&'.$kondisi;
        }else{
          $kondisi = '?biro=4&page='.$request->page;
        }

        $page = $request->page;
        if($page){
          $start_number = ($request->limit * ($request->page -1 )) + 1;
        }else{
          $start_number = 1;
        }
        $segment = $request->segment;
        // if($page){
        //     $page = $page;
        // }else{
        //     $page = 1;
        // }
        $result = [];
        $url = 'api/settama'.$kondisi;

        $data_request = execute_api($url,'GET');

        $no = $start_number;
        if(count($data_request)>=1){
	      	$data = $data_request['data'];
	      	foreach($data as $key=>$value){
						$result[$key]['No'] = $no;
	        	$result[$key]['No Rujukan'] = $value['no_rujukan'] ;
	        	$result[$key]['Tanggal Pelaksanaan'] = ( $value['tgl_mulai'] ? date('d/m/Y',strtotime($value['tgl_mulai'])) : ''). ( $value['tgl_selesai'] ? ' - '.date('d/m/Y',strtotime($value['tgl_selesai'])) : '');
	        	$result[$key]['Jenis Kegiatan'] =$value['nama_jenis_kegiatan'];
	        	$result[$key]['Sumber Anggaran'] = $value['sumber_anggaran'];
	        	$result[$key]['Status'] = (($value['status'] == "Y") ? 'Lengkap' : (($value['status'] == 'N') ? 'Tidak lengkap' : ''));
	        	$peserta = "";
	        	$meta_peserta = $value['meta_peserta'];
	        	if($meta_peserta){
	        		$meta = json_decode($meta_peserta,true);
	        		if(count($meta) > 0 ){
	        			for($i = 0 ; $i < count($meta); $i ++ ){
	        				$peserta .= 'Instansi : '.$meta[$i]['nama_instansi'].' , Jumlah : '.$meta[$i]['jumlah_peserta'];
	        				$peserta .= "\n";
	        			}
	        			$peserta = rtrim($peserta);
	        		}
	        	}
	        	$result[$key]['Peserta']= $peserta;
	        // $result[$key]['Jumlah Peserta'] = $value['materi'];
	        	$no = $no +1;
	      	}
	     	$name = $array_titles['sekretariat_utama'].' '.date('Y-m-d H:i:s');

          	$this->printData($result, $name);
         //  	echo '<pre>';
        	// print_r($result);
        }else{
          return false;
        }

    }

	public function sekretariatUtamaKepegawaian(Request $request){
    	/* filter*/
		if($request->limit) {
			$this->limit = $request->limit;
		} else {
			$this->limit = config('app.limit');
		}


		$kondisi = '';
		if($request->isMethod('get')){
        	$get = $request->except(['page']);
        	foreach ($get as $key => $value) {
        		$kondisi .= "&".$key.'='.$value;
        		if(($key == 'tgl_from') || ($key == 'tgl_to')){
        			$value = date('d/m/Y',strtotime($value));
        		}else{
        			$value = $value;
        		}
        		$this->selected[$key] =$value;
        	}
        	$this->selected['selected'] = $request->tipe;
        	$keyword = "";
        	if($request->tipe == 'dipa_anggaran'){
        		$keyword = $request->get('sumber_anggaran');
        	}else{
        		$keyword = $request->get($request->tipe);
        	}
        	$this->selected['kata_kunci'] = $keyword;
        	$this->data['filter'] = $this->selected;
      //   	$this->selected['limit'] = $this->limit;
			// $kondisi .= '&limit='.$this->limit;
		}else{
			$tipe = trim($request->tipe);
			$kata_kunci = trim($request->kata_kunci);
			$tgl_from = trim($request->tgl_from);
			$tgl_to = trim($request->tgl_to);
			$order = trim($request->order);
			$sumber_anggaran = trim($request->dipa_anggaran);
			$kelengkapan = trim($request->kelengkapan);
			$jenis_kegiatan = trim($request->jenis_kegiatan);

			if($tipe){
				$this->selected['selected'] = $tipe;
				$kondisi .= '&tipe='.$tipe;

			}else if(!$tipe){
				$this->selected['selected'] = "";
				$kondisi .= "";

			}
			if($tgl_from){
				$this->selected['tgl_from'] = $tgl_from;
				$tgl_from = str_replace('/', '-',$tgl_from);
				$tgl_from = date('Y-m-d',strtotime($tgl_from));
				$kondisi .= '&tgl_from='.$tgl_from;
			}else if(!$tgl_from){
				$this->selected['tgl_from'] = "";
				$kondisi .= "";
			}
			if($tgl_to){
				$this->selected['tgl_to'] = $tgl_to;
				$tgl_to = str_replace('/', '-',$tgl_to);
				$tgl_to = date('Y-m-d',strtotime($tgl_to));
				$kondisi .= '&tgl_to='.$tgl_to;
			}else if(!$tgl_to){
				$this->selected['tgl_to'] = "";
				$kondisi .= "";
			}

			if($kata_kunci){
				$this->selected['kata_kunci'] = $kata_kunci;
			}else if(!$kata_kunci){
				$this->selected['kata_kunci'] = "";
				$kondisi .= "";
			}

			if($tipe && $kata_kunci){
				$kondisi .= '&'.$tipe.'='.$kata_kunci;
			}
			if($order){
				$this->selected['order'] = $order;
				$kondisi .= '&order='.$order;
			}else if(!$order){
				$this->selected['order'] = "desc";
				$kondisi .= "";
			}

			if($sumber_anggaran){
				$this->selected['sumber_anggaran'] = $sumber_anggaran;
				$kondisi .= '&sumber_anggaran='.$sumber_anggaran;
			}else if(!$order){
				$this->selected['sumber_anggaran'] = "";
				$kondisi .= "";
			}
			if($jenis_kegiatan){
				$this->selected['jenis_kegiatan'] = $jenis_kegiatan;
				$kondisi .= '&jenis_kegiatan='.$jenis_kegiatan;
				// $kondisi .= '&tipe='.$jenis_kegiatan;
			}else if(!$jenis_kegiatan){
				$this->selected['jenis_kegiatan'] = "";
				$kondisi .= "";
			}
			if($kelengkapan){
				$this->selected['kelengkapan'] = $kelengkapan;
				$kondisi .= '&kelengkapan='.$kelengkapan;
			}else if(!$order){
				$this->selected['kelengkapan'] = "";
				$kondisi .= "";
			}
			$this->selected['limit'] = $this->limit;
			$kondisi .= '&limit='.$this->limit;
			$this->data['filter'] = $this->selected;
		}

		$request_kegiatan = execute_api_json('/api/settama_jenis_kegiatan/2','GET');
		if(($request_kegiatan->code == 200) && ($request_kegiatan->status != 'error') ){
        	$this->data['kegiatan'] = $request_kegiatan->data;
		}else {
		    $this->data['kegiatan'] = [];
		}

		$filter_parameter['parameter'] = [ 'no_rujukan'=>'No Rujukan','jenis_kegiatan'=>'Jenis Kegiatan',
                      'dipa_anggaran'=>'Sumber Anggaran','periode'=>'Periode', 'kelengkapan'=>'Status Kelengkapan'];
        $filter_parameter['selected'] = [ 'selected'=>$this->selected['selected'],
                      'tgl_from'=> (isset($this->selected['tgl_from']) ? ($this->selected['tgl_from'] ? $this->selected['tgl_from'] : ''): ''),
                      'tgl_to'=>(isset($this->selected['tgl_to']) ? ($this->selected['tgl_to'] ? $this->selected['tgl_to'] : '') : ''),
                      'kata_kunci'=>(isset($this->selected['kata_kunci']) ?($this->selected['kata_kunci'] ? $this->selected['kata_kunci'] : '') : ''),
                      'order'=>(isset($this->selected['order']) ?($this->selected['order'] ? $this->selected['order'] : '') : ''),
                      'limit'=>(isset($this->selected['limit']) ?($this->selected['limit'] ? $this->selected['limit'] : '')  : ''),
                      'sumber_anggaran'=>(isset($this->selected['sumber_anggaran']) ?($this->selected['sumber_anggaran'] ? $this->selected['sumber_anggaran'] : '')  : ''),
                      'kelengkapan'=>(isset($this->selected['kelengkapan']) ?($this->selected['kelengkapan'] ? $this->selected['kelengkapan'] : '')  : ''),
                      'jenis_kegiatan'=>(isset($this->selected['jenis_kegiatan']) ?($this->selected['jenis_kegiatan'] ? $this->selected['jenis_kegiatan'] : '')  : ''),
                    ];
        $filter_parameter['javascript'] = 'onChange=showPeriode(this)';
        $filter_parameter['route'] = $request->route()->getName();

        $this->data['filter_parameter'] = $filter_parameter;
		/* end filter*/
        $this->data['title'] = "Kegiatan Biro Kepegawaian & Organisasi Sekretariat Utama";
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
        $limit = 'limit='.$this->limit;
        $offset = 'page='.$current_page;
        $datas = execute_api_json('api/settama?biro=3&'.$limit.'&'.$offset.$kondisi,'get');
    	if(($datas->status != 'error') && ($datas->code == 200) ){
    		$this->data['data'] = $datas->data;
    	}else{
    		$this->data['data'] = [];
    	}
        $this->data['delete_route'] = 'delete_settama_kepegawaian';
        $this->data['path'] = $request->path();
        $this->data['route_name'] = $request->route()->getName();
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;
				$filtering = false;
	      if($kondisi){
	        $filter = $kondisi;
	        $filtering = true;
	        $this->data['kondisi'] = '?'.$limit.'&'.$offset.$kondisi;
	      }else{
	        $filter = '/';
	        $filtering = false;
	        $this->data['kondisi'] = $current_page;
	      }
        $total_item = $datas->paginate->totalpage * $this->limit;
				$this->data['instansi'] = $this->globalinstansi($request->session()->get('wilayah'), $request->session()->get('token'));
        $this->data['pagination'] = pagination($current_page,$total_item, $this->limit, config('app.page_ellipsis'), $request->route()->getPrefix()."/".$request->route()->getName()."/%d");
        $this->data['breadcrumps'] = breadcrumps($request->route()->getName());
    	return view('settama.kepegawaian.index_sekretariatUtama',$this->data);
    }
    public function editSekretariatUtamaKepegawaian(Request $request){
    	$id = $request->id;
    	$datas = execute_api_json('api/settama/'.$id,'get');
    	if(($datas->status != 'error') && ($datas->code == 200) ){
    		$this->data['data'] = $datas->data;
    		$d = $datas->data;
    		$id_pelaksana = $d->pelaksana;
    		$datas = execute_api_json('api/pelaksana_settama?id_settama_lookup='.$id_pelaksana,'get');
    		if(($datas->code == 200) && ($datas->status != 'error') ){
    			//pelaksana bagian
    			$id_settama_lookup = $datas->data[0]->id_settama_lookup;
				$request_bagian = execute_api_json('/api/pelaksana_bagian?type=pelaksana&id_lookup_parent='.$id_settama_lookup,'GET');
				if(($request_bagian->code == 200) && ($request_bagian->status != 'error') ){
		        	$this->data['bagian'] = $request_bagian->data;
				}else {
				    $this->data['bagian'] = [];
				}

				$request_kegiatan = execute_api_json('/api/settama_jenis_kegiatan/'.$id_settama_lookup,'GET');

				if ($d->anggaran_id != '') {
					$this->data['data_anggaran'] = $this->globalGetAnggaran($request->session()->get('token'), $d->anggaran_id);
				}
				if(($request_kegiatan->code == 200) && ($request_kegiatan->status != 'error') ){
		        	$this->data['kegiatan'] = $request_kegiatan->data;
				}else {
				    $this->data['kegiatan'] = [];
				}


		    }else{
		        $this->data['bagian'] = [];
		        $this->data['kegiatan'] = [];
		    }


	    	$datas = execute_api_json('api/lookup/rujukan','get');
		    if(($datas->code == 200) && ($datas->status != 'error') ){
		        $this->data['rujukan'] = $datas->data;
		    }else{
		        $this->data['rujukan'] = [];
		    }

			$datas = execute_api_json('api/pelaksana_settama','get');
		    if(($datas->code == 200) && ($datas->status != 'error') ){
		        $this->data['pelaksana'] = $datas->data;
		    }else{
		        $this->data['pelaksana'] = [];
		    }
		    $this->data['breadcrumps'] = breadcrumps($request->route()->getName());
		    $this->data['file_path'] = config('app.settama_file_path');
	    	return view('settama.kepegawaian.edit_sekretariatUtama',$this->data);
    	}else{
    		$this->data['data'] = [];
    		return back()->with('status',['status'=>'error','message'=>'Data tidak ditemukan']);
    	}

    }
    public function addSekretariatUtamaKepegawaian(Request $request){
    	if($request->isMethod('post')){
    		$insertId = "";
			$baseUrl = URL::to('/');
	        $token = $request->session()->get('token');

    		$client = new Client();
	        if ($request->input('sumber_anggaran')=="DIPA") {
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

    		$this->form_params = $request->except(['_token','kd_anggaran','akode_anggaran','asasaran','apagu','atarget_output','atahun','asatker_code','arefid_anggaran','asatuan_output']);
				$this->form_params['anggaran_id'] = $anggaran;
    		if($request->tgl_mulai){
    			$date = explode('/',$request->tgl_mulai);
    			$this->form_params['tgl_mulai'] = $date[2].'-'.$date[1].'-'.$date[0];
    		}
    		if($request->tgl_selesai){
    			$date = explode('/',$request->tgl_selesai);
    			$this->form_params['tgl_selesai'] = $date[2].'-'.$date[1].'-'.$date[0];
    		}else{
    			$this->form_params['tgl_selesai'] = null;
    		}
    		$meta_peserta= [];
    		$json_peserta = "";
    		if(count($request->meta_peserta) > 0 ){
    			for($i = 0; $i< count($request->meta_peserta); $i++){
    				$d = $request->meta_peserta[$i];
    				if($d['nama_instansi'] || $d['jumlah_peserta']){
    						$meta_peserta[] = ['nama_instansi'=>$d['nama_instansi'] ,'jumlah_peserta'=>$d['jumlah_peserta']];
    				}
    			}
    		}else{
    			$meta_peserta = [];
    		}

    		if(count($meta_peserta) > 0 ){
    			$json_peserta = json_encode($meta_peserta);
    		}else{
    			$json_peserta = "";
    		}
			$this->form_params['meta_peserta'] = $json_peserta;

			$query = execute_api_json('api/settama','POST',$this->form_params);

			$trail['audit_menu'] = 'Sekretariat Utama - Biro Kepegawaian dan Organisasi';
			$trail['audit_event'] = 'post';
			$trail['audit_value'] = json_encode($this->form_params);
			$trail['audit_url'] = $request->url();
			$trail['audit_ip_address'] = $request->ip();
			$trail['audit_user_agent'] = $request->userAgent();
			$trail['audit_message'] = $query->comment;
			$trail['created_at'] = date("Y-m-d H:i:s");
			$trail['created_by'] = $request->session()->get('id');

			$qtrail = $this->inputtrail($token,$trail);


			$file_message = "";
			$insertId = "";
			if( ($query->code == 200) && ($query->status != 'error') ){
				$insertId = $query->data->eventID;
				if($request->file('file_laporan')){
	                $fileName = $request->file('file_laporan')->getClientOriginalName();
	                $fileName = date('Y-m-d').'_'.$insertId.'_'.$fileName;
	                $extension = $request->file('file_laporan')->getClientOriginalExtension(); // getting image extension
	                $directory = 'Settama';
	                try {
	                    $path = Storage::putFileAs($directory, $request->file('file_laporan'),$fileName);
	                    if($path){
	                        $this->form_params = ['file_laporan'=>$fileName];
	                        $storeFile = execute_api_json('api/settama/'.$insertId,'PUT',$this->form_params);
	                        if( ($storeFile->code == 200) && ($storeFile->status != 'error') ){
	                        	$file_message .= "";
	                        }else{
								$file_message.= "Server Error : Dengan File gagal disimpan.";
	                        }

	                    }else{
	                        $file_message .= "Direktori Error : Dengan File gagal diupload.";
	                    }
	                }catch(\Exception $e){
	                    $file_message .= "Server Error :Dengan File gagal diupload.";
	                }
	            }else{
					$file_message = "";
				}
			}else{
				$file_message = "";
			}
			$this->kelengkapan($insertId);
			//dd($this->form_params);

			$file_message = "";
			if( ($query->code == 200) && ($query->status != 'error') ){
				$insertId = $query->data->eventID;
				if($request->file('file_laporan')){
	                $fileName = $request->file('file_laporan')->getClientOriginalName();
	                $fileName = date('Y-m-d').'_'.$insertId.'_'.$fileName;
	                $extension = $request->file('file_laporan')->getClientOriginalExtension(); // getting image extension
	                $directory = 'Settama';
	                try {
	                    $path = Storage::putFileAs($directory, $request->file('file_laporan'),$fileName);
	                    if($path){
	                        $this->form_params = ['file_laporan'=>$fileName];
	                        $storeFile = execute_api_json('api/settama/'.$insertId,'PUT',$this->form_params);
	                        if( ($storeFile->code == 200) && ($storeFile->status != 'error') ){
	                        	$file_message .= "";
	                        }else{
								$file_message.= "Server Error : Dengan File gagal disimpan.";
	                        }

	                    }else{
	                        $file_message .= "Direktori Error : Dengan File gagal diupload.";
	                    }
	                }catch(\Exception $e){
	                    $file_message .= "Server Error :Dengan File gagal diupload.";
	                }
	            }else{
					$file_message = "";
				}
			}else{
				$file_message = "";
			}

            if( ($query->code == 200) && ($query->status != 'error') ){
            	$this->data['status'] = 'success';
            	$this->data['message'] = 'Data Sekretariat Utama berhasil disimpan. '.$file_message;
            }else{
				$this->data['status'] = 'error';
            	$this->data['message'] = 'Data Sekretariat Utama gagal disimpan. ';
            }
    		return redirect(route('settama_kepegawaian'))->with('status',$this->data);

    	}else{
    		$datas = execute_api_json('api/lookup/rujukan','get');

		    if(($datas->code == 200) && ($datas->status != 'error') ){
		        $this->data['rujukan'] = $datas->data;
		    }else{
		        $this->data['rujukan'] = [];
		    }
    		$datas = execute_api_json('api/pelaksana_settama','get');
		    if(($datas->code == 200) && ($datas->status != 'error') ){
		        $this->data['pelaksana'] = $datas->data;
		    }else{
		        $this->data['pelaksana'] = [];
	    	}
			$request_bagian = execute_api_json('/api/pelaksana_bagian?type=pelaksana&id_lookup_parent=3','GET');
			if(($request_bagian->code == 200) && ($request_bagian->status != 'error') ){
	        	$this->data['bagian'] = $request_bagian->data;
			}else {
			    $this->data['bagian'] = [];
			}
			$request_kegiatan = execute_api_json('/api/settama_jenis_kegiatan/3','GET');
			if(($request_kegiatan->code == 200) && ($request_kegiatan->status != 'error') ){
	        	$this->data['kegiatan'] = $request_kegiatan->data;
			}else {
			    $this->data['kegiatan'] = [];
			}
		    $this->data['breadcrumps'] = breadcrumps($request->route()->getName());
			return view('settama.kepegawaian.add_sekretariatUtama',$this->data);
		}
    }
	public function updateSekretariatUtamaKepegawaian(Request $request){
    	$id = $request->id;

			$baseUrl = URL::to('/');
    	$token = $request->session()->get('token');
    	$client = new Client();
			if ($request->input('sumber_anggaran')=="DIPA") {
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

		$this->form_params = $request->except(['_token','id','aid_anggaran','kd_anggaran','akode_anggaran','asasaran','apagu','atarget_output','atahun','asatker_code','arefid_anggaran','asatuan_output']);
		$this->form_params['anggaran_id'] = $anggaran;
		if($request->tgl_mulai){
			$date = explode('/',$request->tgl_mulai);
			$this->form_params['tgl_mulai'] = $date[2].'-'.$date[1].'-'.$date[0];
		}
		if($request->tgl_selesai){
			$date = explode('/',$request->tgl_selesai);
			$this->form_params['tgl_selesai'] = $date[2].'-'.$date[1].'-'.$date[0];
		}else{
			$this->form_params['tgl_selesai'] = null;
		}
		$meta_peserta= [];
		$json_peserta = "";
		if(count($request->meta_peserta) > 0 ){
			for($i = 0; $i< count($request->meta_peserta); $i++){
				$d = $request->meta_peserta[$i];
				if($d['nama_instansi'] || $d['jumlah_peserta']){
    				$meta_peserta[] = ['nama_instansi'=>$d['nama_instansi'] ,'jumlah_peserta'=>$d['jumlah_peserta']];
    			}
			}
		}else{
			$meta_peserta = [];
		}

		if(count($meta_peserta) > 0 ){
			$json_peserta = json_encode($meta_peserta);
		}else{
			$json_peserta = "";
		}
		$this->form_params['meta_peserta'] = $json_peserta;


		$file_message = "";
		if($request->file('file_laporan')){
            $fileName = $request->file('file_laporan')->getClientOriginalName();
            $fileName = date('Y-m-d').'_'.$id.'_'.$fileName;
            $extension = $request->file('file_laporan')->getClientOriginalExtension(); // getting image extension
            $directory = 'Settama';
            try {
                $path = Storage::putFileAs($directory, $request->file('file_laporan'),$fileName);
                if($path){
                    $this->form_params = ['file_laporan'=>$fileName];
                }else{
                    $file_message .= "Direktori Error : Dengan File gagal diupload.";
                }
            }catch(\Exception $e){
                $file_message .= "Server Error :Dengan File gagal diupload.";
            }
        }else{
			$file_message = "";
		}

		$query = execute_api_json('api/settama/'.$id,'PUT',$this->form_params);

		$trail['audit_menu'] = 'Sekretariat Utama - Biro Kepegawaian dan Organisasi';
		$trail['audit_event'] = 'put';
		$trail['audit_value'] = json_encode($this->form_params);
		$trail['audit_url'] = $request->url();
		$trail['audit_ip_address'] = $request->ip();
		$trail['audit_user_agent'] = $request->userAgent();
		$trail['audit_message'] = $query->comment;
		$trail['created_at'] = date("Y-m-d H:i:s");
		$trail['created_by'] = $request->session()->get('id');

		$qtrail = $this->inputtrail($token,$trail);

		$this->kelengkapan($id);
        if( ($query->code == 200) && ($query->status != 'error') ){
        	$this->data['status'] = 'success';
        	$this->data['message'] = 'Data Sekretariat Utama berhasil diperbarui. '.$file_message;
        }else{
			$this->data['status'] = 'error';
        	$this->data['message'] = 'Data Sekretariat Utama gagal diperbarui. ';
        }
		return back()->with('status',$this->data);

    }
    public function deleteSekretariatUtamaKepegawaian(Request $request){
    	$id = $request->input('id');
        if ($request->ajax()) {
            if($id){
                $id = $request->id;

								$this->form_params['delete_id'] = $id;
                $data_request = execute_api('api/settama/'.$id,'DELETE');

								$trail['audit_menu'] = 'Sekretariat Utama - Biro Kepegawaian dan Organisasi';
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
                $data_request =['status'=>'error','messages'=>'Data Sekretariat Utama Gagal Dihapus'];
                return $data_request;
            }
        }
    }
	public function printPageKepegawaian(Request $request){
        $array_segments = [
            'sekretariat_utama'=>'settama'
        ];
        $array_titles=[
            'sekretariat_utama'=>'Sekretariat Utama Biro Kepegawaian'
        ];

				$get = $request->all();
				$kondisi = "";
        if(count($get)>0){
          foreach($get as $key=>$val){
            $kondisi .= $key.'='.$val.'&';
          }
          $kondisi = rtrim($kondisi,'&');
          $kondisi = '?biro=3&'.$kondisi;
        }else{
          $kondisi = '?biro=3&page='.$request->page;
        }

        $page = $request->page;
        if($page){
          $start_number = ($request->limit * ($request->page -1 )) + 1;
        }else{
          $start_number = 1;
        }
        $segment = $request->segment;
        // if($page){
        //     $page = $page;
        // }else{
        //     $page = 1;
        // }
        $result = [];
        $url = 'api/settama'.$kondisi;

        $data_request = execute_api($url,'GET');

        $no = $start_number;
        if(count($data_request)>=1){
	      	$data = $data_request['data'];
	      	foreach($data as $key=>$value){
						$result[$key]['No'] = $no;
	        	$result[$key]['No Rujukan'] = $value['no_rujukan'] ;
	        	$result[$key]['Tanggal Pelaksanaan'] = ( $value['tgl_mulai'] ? date('d/m/Y',strtotime($value['tgl_mulai'])) : ''). ( $value['tgl_selesai'] ? ' - '.date('d/m/Y',strtotime($value['tgl_selesai'])) : '');
	        	$result[$key]['Jenis Kegiatan'] =$value['nama_jenis_kegiatan'];
	        	$result[$key]['Sumber Anggaran'] = $value['sumber_anggaran'];
	        	$result[$key]['Status'] = (($value['status'] == "Y") ? 'Lengkap' : (($value['status'] == 'N') ? 'Tidak lengkap' : ''));
	        	$peserta = "";
	        	$meta_peserta = $value['meta_peserta'];
	        	if($meta_peserta){
	        		$meta = json_decode($meta_peserta,true);
	        		if(count($meta) > 0 ){
	        			for($i = 0 ; $i < count($meta); $i ++ ){
	        				$peserta .= 'Instansi : '.$meta[$i]['nama_instansi'].' , Jumlah : '.$meta[$i]['jumlah_peserta'];
	        				$peserta .= "\n";
	        			}
	        			$peserta = rtrim($peserta);
	        		}
	        	}
	        	$result[$key]['Peserta']= $peserta;
	        // $result[$key]['Jumlah Peserta'] = $value['materi'];
	        	$no = $no +1;
	      	}
	     	$name = $array_titles['sekretariat_utama'].' '.date('Y-m-d H:i:s');

          	$this->printData($result, $name);
         //  	echo '<pre>';
        	// print_r($result);
        }else{
          return false;
        }

    }


    private function kelengkapan($id){
    	$status_kelengkapan = true;
    	try{

	    	$query = DB::table('sekretariat_utama')->where('id_settama',$id)->select('jns_rujukan','no_rujukan','tgl_mulai','tgl_selesai','pelaksana','bagian','jenis_kegiatan','tempat_kegiatan','tujuan_kegiatan','sumber_anggaran','anggaran_id','meta_peserta','file_laporan');
	    	if($query->count() > 0 ){
	    		$result = $query->first();
	    		foreach($result as $key=>$val){
	    			if($key == 'sumber_anggaran' && $val == 'DIPA'){
	    				if(!$result->anggaran_id){
	    					$status_kelengkapan= false;
	    					break;
	    				}else{
	    					continue;
	    				}
	    			}else if( ($key == 'meta_peserta') && $val){
	    				$d = json_decode($val,true);
						if(count($d) > 0){
							for($i = 0 ; $i < count($d) ; $i++){
								$m = $d[$i];
								if( !$m['nama_instansi'] ||  !$m['jumlah_peserta']){
									$status_kelengkapan= false;
									break;
								}else{
									continue;
								}

							}
						}
	    			}else{
	    				if(!$val && ($key != 'anggaran_id') ){
	    					$status_kelengkapan=false;
	    					break;
	    				}else{
	    					continue;
	    				}
	    			}
	    		}
	    		if($status_kelengkapan== true){
					 $kelengkapan = execute_api_json('api/settama/'.$id,'PUT',['status'=>'Y']);
				}else{
					 $kelengkapan = execute_api_json('api/settama/'.$id,'PUT',['status'=>'N']);
				}
	    	}

    	}catch(\Exception $e){
    		$status_kelengkapan=false;
    	}
    }
}

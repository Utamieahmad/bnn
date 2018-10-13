<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use URL;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class AdministratorController extends Controller
{
	public $data = [];
    public function monitoringNihil(Request $request){
      $client = new Client();
			$baseUrl = URL::to($this->urlapi());
			$token = $request->session()->get('token');

    	$this->limit = config('app.limit');
    	$this->data['title'] = 'Monitoring Nihil';
        if($request->page){
            $current_page = $request->page;
            $start_number = ($this->limit * ($request->page -1 )) + 1;
        }else{
            $current_page = 1;
            $start_number = $current_page;
        }
    	$limit = 'limit='.$this->limit;
      $offset = 'page='.$current_page;
			$datas = $client->request('GET', $baseUrl.'/api/monitoringnihil?'.$limit.'&'.$offset,
				[
						'headers' =>
						[
								'Authorization' => 'Bearer '.$token
								// 'Authorization' => 'Bearer rUjEwAucsuiEc0wyypbuchvwEB19DgCnEqj5uGl2Yytp9aFqlEWfAUQM45W7MRKHaCF5bowyECplrTCWOk3M2mmFxCFsjevNKbsEpRz8nELNpHiM19y5C4ZXYi1CcLtuvBuiN0JH0pg5ngn599SRg7amx2EQnQDrv0oBgBLCaaZZeCsaAkGVfRZBTzp4RrtVW9CdGxsSHGdsRJLctNA0GTYjUZ7vhbmbawLV4bcCmlCNAmg1OctS4nJSUQtPpUy'
						]
				]
			);
    	// $datas = execute_api('api/monitoringnihil?'.$limit.'&'.$offset,'get');
		// $datas = json_decode(json_encode($datas), FALSE);
		$datas = json_decode($datas->getBody()->getContents(),FALSE);
		// dd($datas);
		if($datas->code == 200){
			$this->data['data'] = $datas->data;
		}else{
			$this->data['data'] = [];
		}
		// echo '<pre>';
		// print_r($datas);
		// exit();
        $this->data['path'] = $request->path();
        $this->data['start_number'] = $start_number;
        $this->data['current_page'] = $current_page;
        $total_item = $datas->paginate->totalpage * $this->limit;
        $this->data['pagination'] = pagination($current_page,$total_item, $this->limit, config('app.page_ellipsis'),"/settings/monitoring_nihil/%d");
    	return view('administrator.monitoring_nihil',$this->data);

    }

    public function userManagement(){
        return view('master.user_management',$this->data);
    }

    public function userRole(){
        return view('master.user_role',$this->data);
    }
}

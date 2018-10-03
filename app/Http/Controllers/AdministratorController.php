<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdministratorController extends Controller
{
	public $data = [];
    public function monitoringNihil(Request $request){
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
    	$datas = execute_api('api/monitoringnihil?'.$limit.'&'.$offset,'get');
		$datas = json_decode(json_encode($datas), FALSE);
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

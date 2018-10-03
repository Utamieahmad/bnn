<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
	public $data = array();
    public function dashboard(Request $request){
    	$this->data['title'] = 'Beranda';
    	return view('dashboard',$this->data);
    }
}

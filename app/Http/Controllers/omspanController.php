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

	public function omspan(Request $request){
		$client = new Client();
		$token = $request->session()->get('token');
		$token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c3IiOiJXZWJzZXJ2aWNlIEJOTiIsInVpZCI6IkJOTiIsInJvbCI6IndlYnNlcnZpY2UiLCJrZHMiOiJXRUJTWDQiLCJrZGIiOiIiLCJrZHQiOiIyMDE4IiwiaWF0IjoxNTI3NzUwODk1LCJuYmYiOjE1Mjc3NTAyOTUsImtpZCI6IjY4MSJ9.EJ9Gd5vPWfDYotVFS2c0AqFHcUOen4bnnA1K_5Vc36o';

		if($request->isMethod('post')) {
			$get = $request->all();
			echo "<pre>";
			print_r($get);

			echo "</pre>";

			$params['headers'] = ['Content-Type' => 'application/json', 'Accept' => 'application/json'];
			$params['body'] = '{ "KDSATKER" : "", "KPPN" : "", "BA" : "", "BAES1" : "", "AKUN" : "", "PROGRAM" : "", "KEGIATAN" : "", "OUTPUT" : "", "KEWENANGAN" : "", "SUMBER_DANA" : "", "LOKASI" : "", "BUDGET_TYPE" : "", "AMOUNT" : "", "Limit" : "10", "Page" : "" }';
			$requestdatadipa = $client->request('POST', config('app.url_soadev2').'KemenkeuDataDipa/DataDipaQueryRS', $params);
			$datadipa = json_decode($requestdatadipa->getBody()->getContents(), true);
			echo "<pre>";
			print_r($datadipa);
			echo "</pre>";

			return view('omspan.view');
			die;
		}

		return view('omspan.index');
	}
}

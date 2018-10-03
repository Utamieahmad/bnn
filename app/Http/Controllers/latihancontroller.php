<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\latihanmodel;

class latihancontroller extends Controller
{
    public $data = array();
    public function berantas()
    {
    // $q=DB::table('v_berantas_kasus')->limit(3)->first();
    $q=latihanmodel::kasus();
    $this->data['var_data']=$q;
    print_r($q);
      //return view('pages/latihan2',$this->data);
    }
}

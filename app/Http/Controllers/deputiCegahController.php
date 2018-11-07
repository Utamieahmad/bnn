<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\MainModel;
use App\Tr_BrgBukti;
use App\Models\BrgBukti;
use URL;
use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class deputiCegahController extends Controller {

    public $data;
    public $selected;
    public $form_params;

    public function pendataanAktivitasSebaran(){
        return view('pencegahan.deputiCegah.aktivitasSebaran.index_pendataanAtivitasSebaran');
    }
}

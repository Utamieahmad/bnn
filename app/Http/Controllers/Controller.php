<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Database\QueryException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;
use URL;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

use Excel;

/**
 * @SWG\Swagger(
 *   basePath="/bnn/public/api",
 *   @SWG\Info(
 *     title="API Documentation BNN",
 *     version="1.0.0"
 *   )
 * )
 */

class Controller extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
  //url api
  public static function urlapi(){
   return $api_url = config('app.url_api_local');
     // return $api_url = config('app.url_soadev');
    // return $api_url = config('app.url_soa');
  }
  // public static function urlapisoaDev(){
  //   return $api_url = config('app.url_soadev');
  // }
  // public static function urlapisoa(){
  //   return $api_url = config('app.url_soa');
  // }

  function replacer(& $item, $key) {
    // if ($item === null) {
    //     $item = "kosong";
    // }
  }

  function printData($data, $name){
    return Excel::create($name, function($excel) use ($data) {
      $excel->sheet('Sheet', function($sheet) use ($data)
      {
        $sheet->fromArray($data);
        $sheet->setAutoSize(true);
        $sheet->getDefaultStyle()->getAlignment()->setWrapText(true);
      });
    })->download('xls');
  }

  public function globalLkn($token, $id)
  {
    $client = new Client();
    $baseUrl = URL::to('/');

    $requestLKN = $client->request('GET', $baseUrl.'/api/kasus/'.$id,
    [
      'headers' =>
      [
        'Authorization' => 'Bearer '.$token
      ]
    ]
  );

  $LKN = json_decode($requestLKN->getBody()->getContents(), true);

  return $LKN;
}

public function globalJalurMasuk($token)
{
  $client = new Client();
  $baseUrl = URL::to('/');

  $requestJalur_masuk = $client->request('GET', $baseUrl.'/api/lookup/jalur_masuk_narkotika',
  [
  'headers' =>
  [
  'Authorization' => 'Bearer '.$token
  ]
  ]
  );

  $jalur_masuk = json_decode($requestJalur_masuk->getBody()->getContents(), true);

  return $jalur_masuk;
}

public function globalGetByLkn($token, $lkn)
{
  $client = new Client();
  $baseUrl = URL::to('/');

  $requestGetByLkn = $client->request('POST', $baseUrl.'/api/getbylkn',
  [
  'headers' =>
  [
  'Authorization' => 'Bearer '.$token
  ],
  'form_params' =>
  [
  'kasus_no' => $lkn
  ]
  ]
  );

  $getByLkn = json_decode($requestGetByLkn->getBody()->getContents(), true);

  return $getByLkn;
}

public function globalGetTersangka($token, $id)
{
  $client = new Client();
  $baseUrl = URL::to('/');
  $requestTersangka = $client->request('GET', $baseUrl.'/api/gettersangka/'.$id,
  [
  'headers' =>
  [
  'Authorization' => 'Bearer '.$token
  ]
  ]
  );

  $tersangka = json_decode($requestTersangka->getBody()->getContents(), true);

  return $tersangka;
}

public function globalBuktiNarkotika($token, $id)
{
  $client = new Client();
  $baseUrl = URL::to('/');

  $requestBrgBukttiNarkotika = $client->request('GET', $baseUrl.'/api/getbbnarkotika/'.$id,
  [
  'headers' =>
  [
  'Authorization' => 'Bearer '.$token
  ]
  ]
  );

  $brgBuktiNarkotika = json_decode($requestBrgBukttiNarkotika->getBody()->getContents(), true);

  return $brgBuktiNarkotika;
}

public function globalBuktiPrekursor($token, $id)
{
  $client = new Client();
  $baseUrl = URL::to('/');

  $requestBrgBuktiPrekursor = $client->request('GET', $baseUrl.'/api/getbbprekursor/'.$id,
  [
  'headers' =>
  [
  'Authorization' => 'Bearer '.$token
  ]
  ]
  );

  $brgBuktiPrekursor = json_decode($requestBrgBuktiPrekursor->getBody()->getContents(), true);

  return $brgBuktiPrekursor;
}

public function globalBuktiAdiktif($token, $id)
{
  $client = new Client();
  $baseUrl = URL::to('/');

  $requestBrgBukttiAdiktif = $client->request('GET', $baseUrl.'/api/getbbadiktif/'.$id,
  [
  'headers' =>
  [
  'Authorization' => 'Bearer '.$token
  ]
  ]
  );

  $brgBuktiAdiktif = json_decode($requestBrgBukttiAdiktif->getBody()->getContents(), true);

  return $brgBuktiAdiktif;
}

public function globalBuktiAsetBarang($token, $id)
{
  $client = new Client();
  $baseUrl = URL::to('/');

  $requestBrgBukttiAsetBarang = $client->request('POST', $baseUrl.'/api/getbbaset/'.$id,
  [
  'headers' =>
    [
    'Authorization' => 'Bearer '.$token
    ]
  ]
  );

  $brgBuktiAsetBarang = json_decode($requestBrgBukttiAsetBarang->getBody()->getContents(), true);

  return $brgBuktiAsetBarang;
}

public function globalBuktiAsetTanah($token, $id)
{
  $client = new Client();
  $baseUrl = URL::to('/');

  $requestBrgBukttiAsetTanah = $client->request('POST', $baseUrl.'/api/getbbaset/'.$id,
  [
  'headers' =>
  [
  'Authorization' => 'Bearer '.$token
  ],
  'form_params' =>
  [
  'jenis' => 'ASET_TANAH'
  ]
  ]
  );

  $brgBuktiAsetTanah = json_decode($requestBrgBukttiAsetTanah->getBody()->getContents(), true);

  return $brgBuktiAsetTanah;
}

public function globalBuktiAsetBangunan($token, $id)
{
  $client = new Client();
  $baseUrl = URL::to('/');

  $requestBrgBukttiAsetBangunan = $client->request('POST', $baseUrl.'/api/getbbaset/'.$id,
  [
  'headers' =>
  [
  'Authorization' => 'Bearer '.$token
  ],
  'form_params' =>
  [
  'jenis' => 'ASET_BANGUNAN'
  ]
  ]
  );

  $brgBuktiAsetBangunan = json_decode($requestBrgBukttiAsetBangunan->getBody()->getContents(), true);

  return $brgBuktiAsetBangunan;
}

public function globalBuktiAsetLogam($token, $id)
{
  $client = new Client();
  $baseUrl = URL::to('/');

  $requestBrgBukttiAsetLogam = $client->request('POST', $baseUrl.'/api/getbbaset/'.$id,
  [
  'headers' =>
  [
  'Authorization' => 'Bearer '.$token
  ],
  'form_params' =>
  [
  'jenis' => 'ASET_LOGAMMULIA'
  ]
  ]
  );

  $brgBuktiAsetLogam = json_decode($requestBrgBukttiAsetLogam->getBody()->getContents(), true);

  return $brgBuktiAsetLogam;
}

public function globalBuktiAsetUang($token, $id)
{
  $client = new Client();
  $baseUrl = URL::to('/');

  $requestBrgBukttiAsetUang = $client->request('POST', $baseUrl.'/api/getbbaset/'.$id,
  [
  'headers' =>
  [
  'Authorization' => 'Bearer '.$token
  ],
  'form_params' =>
  [
  'jenis' => 'ASET_UANGTUNAI'
  ]
  ]
  );

  $brgBuktiAsetUang = json_decode($requestBrgBukttiAsetUang->getBody()->getContents(), true);

  return $brgBuktiAsetUang;
}

public function globalBuktiAsetRekening($token, $id)
{
  $client = new Client();
  $baseUrl = URL::to('/');

  $requestBrgBukttiAsetRekening = $client->request('POST', $baseUrl.'/api/getbbaset/'.$id,
  [
  'headers' =>
  [
  'Authorization' => 'Bearer '.$token
  ],
  'form_params' =>
  [
  'jenis' => 'ASET_REKENING'
  ]
  ]
  );

  $brgBuktiAsetRekening = json_decode($requestBrgBukttiAsetRekening->getBody()->getContents(), true);

  return $brgBuktiAsetRekening;
}

public function globalBuktiAsetSurat($token, $id)
{
  $client = new Client();
  $baseUrl = URL::to('/');

  $requestBrgBukttiAsetSurat = $client->request('POST', $baseUrl.'/api/getbbaset/'.$id,
  [
  'headers' =>
  [
  'Authorization' => 'Bearer '.$token
  ],
  'form_params' =>
  [
  'jenis' => 'ASET_SURATBERHARGA'
  ]
  ]
  );

  $brgBuktiAsetSurat = json_decode($requestBrgBukttiAsetSurat->getBody()->getContents(), true);

  return $brgBuktiAsetSurat;
}

public function globalBuktiNonNarkotika($token, $id)
{
  $client = new Client();
  $baseUrl = URL::to('/');

  $requestBrgBuktiNonNarkotika = $client->request('GET', $baseUrl.'/api/getbbnonnarkotika/'.$id,
  [
  'headers' =>
  [
  'Authorization' => 'Bearer '.$token
  ]
  ]
  );

  $brgBuktiNonNarkotika = json_decode($requestBrgBuktiNonNarkotika->getBody()->getContents(), true);

  return $brgBuktiNonNarkotika;
}

public function globalJenisBrgBuktiNarkotika($token)
{
  $client = new Client();
  $baseUrl = URL::to('/');

  $requestJenisBrgBuktiNarkotika = $client->request('POST', $baseUrl.'/api/jnsbrgbukti',
  [
  'headers' =>
  [
  'Authorization' => 'Bearer '.$token
  ],
  'form_params' => [
  'jenis' => [
  '01', '02', '05'
  ]
  ]
  ]
  );

  $jenisBrgBuktiNarkotika = json_decode($requestJenisBrgBuktiNarkotika->getBody()->getContents(), true);

  return $jenisBrgBuktiNarkotika;
}

public function globalJenisBrgBuktiPrekursor($token)
{
  $client = new Client();
  $baseUrl = URL::to('/');

  $requestJenisBrgBuktiPrekursor = $client->request('POST', $baseUrl.'/api/jnsbrgbukti',
  [
  'headers' =>
  [
  'Authorization' => 'Bearer '.$token
  ],
  'form_params' => [
  'jenis' => [
  '06'
  ]
  ]
  ]
  );

  $jenisBrgBuktiPrekursor = json_decode($requestJenisBrgBuktiPrekursor->getBody()->getContents(), true);

  return $jenisBrgBuktiPrekursor;
}

public function globalJenisBrgBuktiAdiktif($token)
{
  $client = new Client();
  $baseUrl = URL::to('/');

  $requestJenisBrgBuktiNarkotika = $client->request('POST', $baseUrl.'/api/jnsbrgbukti',
  [
  'headers' =>
  [
  'Authorization' => 'Bearer '.$token
  ],
  'form_params' => [
  'jenis' => [
  '03', '04', '07', '08', '09', '10', '11', '12', '13', '14'
  ]
  ]
  ]
  );

  $jenisBrgBuktiNarkotika = json_decode($requestJenisBrgBuktiNarkotika->getBody()->getContents(), true);

  return $jenisBrgBuktiNarkotika;
}

public function globalPropkab($token)
{
  $client = new Client();
  $baseUrl = URL::to('/');

  $requestPropinsiKabupaten = $client->request('GET', $baseUrl.'/api/getpropkab',
  [
  'headers' =>
  [
  'Authorization' => 'Bearer '.$token
  ]
  ]
  );

  $propkab = json_decode($requestPropinsiKabupaten->getBody()->getContents(), true);

  return $propkab;
}

public function globalWilayah($token)
{
  $client = new Client();
  $baseUrl = URL::to('/');

  $requestWilayah = $client->request('GET', $baseUrl.'/api/propinsi',
  [
  'headers' =>
  [
  'Authorization' => 'Bearer '.$token
  ]
  ]
  );

  $wilayah = json_decode($requestWilayah->getBody()->getContents(), true);

  return $wilayah;
}

public function globalSatuan($token)
{
  $client = new Client();
  $baseUrl = URL::to('/');

  $requestSatuan = $client->request('GET', $baseUrl.'/api/getsatuan',
  [
  'headers' =>
  [
  'Authorization' => 'Bearer '.$token
  ]
  ]
  );

  $satuan = json_decode($requestSatuan->getBody()->getContents(), true);

  return $satuan;
}

public function globalGetPemusnahan($token, $id)
{
  $client = new Client();
  $baseUrl = URL::to('/');
  // dd($baseUrl);
  $requestPemusnahanBrgBukti = $client->request('GET', $baseUrl.'/api/pemusnahan/'.$id,
  [
  'headers' =>
  [
  'Authorization' => 'Bearer '.$token
  ]
  ]
  );

  $pemusnahanBrgBukti = json_decode($requestPemusnahanBrgBukti->getBody()->getContents(), true);

  return $pemusnahanBrgBukti;
}

public function globalInputPemusnahanDetail($token, $parentId, $bbId, $satuan, $jumlah)
{
  $client = new Client();
  $baseUrl = URL::to('/');
  // dd($baseUrl);
  $requestPemusnahanBrgBuktiDetail = $client->request('POST', $baseUrl.'/api/pemusnahandetail',
  [
  'headers' =>
  [
  'Authorization' => 'Bearer '.$token
  ],
  'form_params' =>
  [
  'parent_id' => $parentId,
  'id_brgbukti' => $bbId,
  'kode_satuan_barang_bukti' => $satuan,
  'jumlah_barang_bukti' => $jumlah
  ]
  ]
  );
}
public function globalPemusnahanDetail($token, $id)
{
  $client = new Client();
  $baseUrl = URL::to('/');
  // dd($baseUrl);
  $requestPemusnahanBrgBuktiDetail = $client->request('GET', $baseUrl.'/api/pemusnahandetail/'.$id,
  [
  'headers' =>
  [
  'Authorization' => 'Bearer '.$token
  ]
  ]
  );

  $pemusnahanBrgBuktiDetail = json_decode($requestPemusnahanBrgBuktiDetail->getBody()->getContents(), true);

  return $pemusnahanBrgBuktiDetail;
}

public function globalinstansi($wilayah, $token)
{
  $client = new Client();
  $baseUrl = URL::to('/');
  $requestInstansi = $client->request('POST', $baseUrl.'/api/instansi',
  [
  'headers' =>
  [
  'Authorization' => 'Bearer '.$token
  ],
  'form_params' =>
  [
  'wilayah' => $wilayah
  ]
  ]
  );

  $instansi = json_decode($requestInstansi->getBody()->getContents(), true);
  // dd($instansi);

  return $instansi['data'];
}

public function getProvinsi($token)
{
  $client = new Client();
  $baseUrl = URL::to('/');

  $requestPropinsi = $client->request('GET', $baseUrl.'/api/propinsi',
  [
  'headers' =>
  [
  'Authorization' => 'Bearer '.$token
  ]
  ]
  );

  $propinsi = json_decode($requestPropinsi->getBody()->getContents(), true);

  return $propinsi['data'];
}

public function globalJnsKasus($token)
{
  $baseUrl = URL::to('/');
  $client = new Client();

  $requestJnsKasus = $client->request('GET', $baseUrl.'/api/jnskasus',
  [
  'headers' =>
  [
  'Authorization' => 'Bearer '.$token
  ]
  ]
  );

  $jenisKasus = json_decode($requestJnsKasus->getBody()->getContents(), true);

  return $jenisKasus;
}

public function getLogin($email, $password)
{
  $baseUrl = URL::to('/');
  $client = new Client();
  // dd($client);
  $requestLogin = $client->request('POST', $baseUrl.'/api/login',
    [
    'form_params' =>
        [
        "email" => $email,
        "password" => $password
        ]
    ]
  );
  // dd($requestLogin);

  $login = json_decode($requestLogin->getBody()->getContents(), true);
  // dd($login);
  return $login;
}

public function getSimpeg($nip)
{
  $client = new Client();
  try{
    $requestSimpeg = $client->request('GET', config('app.url_soa').'simpeg/getstaffprofile?nip='.$nip,
    [
    'form_params' =>
    [
    "nip" => $nip
    ]
    ]
    );

    $simpeg = json_decode($requestSimpeg->getBody()->getContents(), true);
    return $simpeg;

  } catch (RequestException $e) {
    $simpeg['code'] = 404;
    return $simpeg;
  }
}

public function getSimpegSatker($nip)
{
  $client = new Client();

  try {
      $requestSimpeg = $client->request('GET', config('app.url_soa').'simpeg/getstaffsatker?unit=1&nip='.$nip,
      [
      'form_params' =>
      [
      "nip" => $nip
      ]
      ]
      );

      $simpeg = json_decode($requestSimpeg->getBody()->getContents(), true);
      return $simpeg;

  } catch (RequestException $e) {
    // $e->getResponse()->getStatusCode();

    $simpeg['code'] = 500;
    return $simpeg;
  }

}

public function globalGetPemusnahanDetail($token, $id)
{
  $baseUrl = URL::to('/');
  $client = new Client();

  $requestPemusnahanBrgBuktiDetail = $client->request('GET', $baseUrl.'/api/getpemusnahandetail/'.$id,
      [
          'headers' =>
          [
              'Authorization' => 'Bearer '.$token
          ]
      ]
  );

  $pemusnahanBrgBuktiDetail = json_decode($requestPemusnahanBrgBuktiDetail->getBody()->getContents(), true);

  return $pemusnahanBrgBuktiDetail;
}

public function getSimpegPhoto($nip)
{
  $client = new Client();

  try {
    $requestSimpeg = $client->request('GET', config('app.url_soa').'simpeg/getstaffphoto?nip='.$nip,
    [
    'form_params' =>
    [
    "nip" => $nip
    ]
    ]
    );
    $simpeg = json_decode($requestSimpeg->getBody()->getContents(), true);

    return $simpeg;
  } catch (RequestException $e) {
    $e->getResponse()->getStatusCode();

    $path = 'assets/images/default-image.png';
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64['data'] = base64_encode($data);
    return $base64;
  }
}

public function globalNegara($token){
  $baseUrl = URL::to('/');
  $client = new Client();

  $requestNegara = $client->request('GET', $baseUrl.'/api/negara',
    [
        'headers' =>
        [
            'Authorization' => 'Bearer '.$token
        ]
    ]
  );
  $negara = json_decode($requestNegara->getBody()->getContents(), true);

  return $negara['data'];
}

public function globalGetAnggaran($token, $id)
{
  $baseUrl = URL::to('/');
  $client = new Client();
  $requestAnggaran = $client->request('GET', $baseUrl.'/api/anggaran/'.$id,
      [
          'headers' =>
          [
              'Authorization' => 'Bearer '.$token
          ]
      ]
  );


  $anggaran = json_decode($requestAnggaran->getBody()->getContents(), true);

  return $anggaran;
}

public function globagroup($token)
{
  $client = new Client();
  $baseUrl = URL::to('/');
  // dd($baseUrl);
  $requestGroup = $client->request('GET', $baseUrl.'/api/getgroup',
  [
  'headers' =>
  [
  'Authorization' => 'Bearer '.$token
  ]
  ]
  );

  $group = json_decode($requestGroup->getBody()->getContents(), true);

  return $group['data'];
}

public function inputtrail($token,$trail)
{
  $client = new Client();
  $baseUrl = URL::to('/');

  $requestGroup = $client->request('POST', $baseUrl.'/api/audittrail',
    [
      'headers' =>
      [
        'Authorization' => 'Bearer '.$token,
        'Accept' => 'application/Json'
      ],
      'form_params' => $trail
    ]
  );

  $group = json_decode($requestGroup->getBody()->getContents(), true);

  return $group;
}

}

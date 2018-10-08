<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/* @author : Daniel Andi */
Route::post('password/email', 'Auth\ForgotPasswordController@getResetToken')->name('forgotPass');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('resetPass');
Route::post('password/new_password', 'Auth\ForgotPasswordController@getNewUserToken')->name('newUserPass');
Route::post('email/reset', 'Auth\ResetEmailController@getResetEmail')->name('resetEmail');

Route::post('login', 'LoginAPIController@login')->name('login');
Route::post('logout', 'LoginAPIController@logout')->name('logout');
Route::resource('users', 'userAPIController');

Route::group(['middleware' => ['auth:api', 'TokenCheck']], function () {

  Route::match(['get','post'],'audittrail', 'GlobalAPIController@inserttrail');
  Route::post('email/new_email', 'Auth\ResetEmailController@inputResetEmail')->name('newUserEmail');

    Route::post('password/edit', 'userAPIController@updatePass')->name('editPass');
    Route::post('password/edit2', 'userAPIController@updatePass2')->name('editPass2');

  // list mobile
  Route::post('listkasus', 'Berantas\KasusAPIController@index')->name('listKasus');
  Route::post('listpemusnahan', 'Berantas\PemusnahanAPIController@index')->name('listPemusnahan');
  Route::post('listpemusnahanmobile', 'Berantas\PemusnahanMobileAPIController@index')->name('listPemusnahanMobile');
  Route::post('listberantasrazia', 'Berantas\BerantasRaziaAPIController@index')->name('listBerantasRazia');
  Route::post('listtesnarkoba', 'Dayamas\TesUjiNarkobaHeaderAPIController@index')->name('listTesNarkoba');
  Route::post('listalihfungsi', 'Dayamas\AltdevLahanganjaAPIController@index')->name('listAlihFungsi');
  Route::post('listrakor', 'Cegah\AdvokasiRakorAPIController@index')->name('listRakor');
  Route::post('listjejaring', 'Cegah\AdvokasiJejaringAPIController@index')->name('listJejaring');
  Route::post('listasistensi', 'Cegah\AdvokasiAsistensiAPIController@index')->name('listAsistensi');
  Route::post('listpenguatan', 'Cegah\AdvokasiAsistensiPenguatanAPIController@index')->name('listPenguatan');
  Route::post('listintervensi', 'Cegah\AdvokasiIntervensiAPIController@index')->name('listIntervensi');
  Route::post('listsupervisi', 'Cegah\AdvokasiSupervisiAPIController@index')->name('listDupervisi');
  Route::post('listmonev', 'Cegah\AdvokasiMonevAPIController@index')->name('listMonev');
  Route::post('listbimtek', 'Cegah\AdvokasiBimtekAPIController@index')->name('listBimtek');
  Route::post('listsosialisasi', 'Cegah\DiseminfoSosialisasiAPIController@index')->name('listSosialisasi');
  Route::post('listonline', 'Cegah\DiseminfoMediaOnlineAPIController@index')->name('listOnline');
  Route::post('listpenyiaran', 'Cegah\DiseminfoMediaPenyiaranAPIController@index')->name('listPenyiaran');
  Route::post('listcetak', 'Cegah\DiseminfoMediaCetakAPIController@index')->name('listCetak');
  Route::post('listkonven', 'Cegah\DiseminfoMediaKonvensionalAPIController@index')->name('listKonven');
  Route::post('listvideotron', 'Cegah\DiseminfoVideotronAPIController@index')->name('listVideotron');
  Route::post('listpelatihan', 'Rehab\PelatihanAPIController@index')->name('listPelatihan');

  //Berantas
  Route::resource('dpo', 'Berantas\DpoAPIController');
  Route::resource('inteljaringan', 'Berantas\IntelJaringanAPIController');
  Route::resource('kasus', 'Berantas\KasusAPIController');
  Route::resource('kasusbrgbukti', 'Berantas\KasusBrgBuktiAPIController');
  Route::resource('buktinonnarkotika', 'Berantas\KasusBrgBuktiNonnarkotikaAPIController');
  Route::resource('buktiprekursor', 'Berantas\KasusBrgBuktiPrekursorAPIController');
  Route::resource('tersangka', 'Berantas\KasusTersangkaAPIController');
  Route::resource('pemusnahan', 'Berantas\PemusnahanAPIController');
  Route::resource('pemusnahanmobile', 'Berantas\PemusnahanMobileAPIController');
  Route::resource('berantasrazia', 'Berantas\BerantasRaziaAPIController');
  Route::resource('pemusnahandetail', 'Berantas\PemusnahanDetailAPIController');
  Route::resource('pemusnahanladang', 'Berantas\PemusnahanLadangAPIController');
  Route::resource('tahanan', 'Berantas\TahananAPIController');
  Route::resource('tahananheader', 'Berantas\TahananHeaderAPIController');
  Route::get('gettersangka/{id}', 'Berantas\KasusTersangkaAPIController@getTersangka')->name('getTersangka');
  Route::get('getDetailTersangka/{tersangka_id}', 'Berantas\KasusTersangkaAPIController@getDetailTersangka')->name('getTersangka');
  Route::get('getbbprekursor/{id}', 'Berantas\KasusBrgBuktiPrekursorAPIController@getBrgBuktiPrekursor')->name('getBBPrekursor');
  Route::post('getbbaset/{id}', 'Berantas\KasusBrgBuktiNonnarkotikaAPIController@getBrgBuktiAset')->name('getBBAset');
  Route::get('getbbnarkotika/{id}', 'Berantas\KasusBrgBuktiAPIController@getBrgBukti')->name('getBBNarkotika');
  Route::get('getbbadiktif/{id}', 'Berantas\KasusBrgBuktiAPIController@getBrgBuktiAdiktif')->name('getBBAdiktif');
  Route::get('getbbnonnarkotika/{id}', 'Berantas\KasusBrgBuktiAPIController@getBrgBuktiNonnarkotika')->name('getBBNonnarkotika');
  Route::post('getbylkn', 'Berantas\KasusAPIController@getByNoLKN')->name('getByNoLKN');
  Route::get('getpemusnahandetail/{id}', 'Berantas\PemusnahanDetailAPIController@getPemusnahanDetailById')->name('getPemusnahanDetailById');
  Route::post('getintelbylkn', 'Berantas\IntelJaringanAPIController@getIntelByLKN')->name('getIntelByLKN');
  Route::post('getpemusnahanbylkn', 'Berantas\PemusnahanAPIController@getPemusnahanByLKN')->name('getPemusnahanByLKN');
  Route::get('getlistlkn', 'Berantas\KasusAPIController@getListLKN')->name('getListLKN');

  //Cegah
  Route::resource('advoasistensi', 'Cegah\AdvokasiAsistensiAPIController');
  Route::resource('advoasistensipenguatan', 'Cegah\AdvokasiAsistensiPenguatanAPIController');
  Route::resource('advobimtek', 'Cegah\AdvokasiBimtekAPIController');
  Route::resource('advointervensi', 'Cegah\AdvokasiIntervensiAPIController');
  Route::resource('advojejaring', 'Cegah\AdvokasiJejaringAPIController');
  Route::resource('advomonev', 'Cegah\AdvokasiMonevAPIController');
  Route::resource('advorakor', 'Cegah\AdvokasiRakorAPIController');
  Route::resource('advosupervisi', 'Cegah\AdvokasiSupervisiAPIController');
  Route::resource('disemcetak', 'Cegah\DiseminfoMediaCetakAPIController');
  Route::resource('disemkonven', 'Cegah\DiseminfoMediaKonvensionalAPIController');
  Route::resource('disemonline', 'Cegah\DiseminfoMediaOnlineAPIController');
  Route::resource('disempenyiaran', 'Cegah\DiseminfoMediaPenyiaranAPIController');
  Route::resource('disemmonev', 'Cegah\DiseminfoMonevAPIController');
  Route::resource('disemrakor', 'Cegah\DiseminfoRakorAPIController');
  Route::resource('disemsosialisasi', 'Cegah\DiseminfoSosialisasiAPIController');
  Route::resource('disemvideotron', 'Cegah\DiseminfoVideotronAPIController');
  Route::resource('entrikegiatan', 'Cegah\EntriKegiatanAPIController');
  Route::resource('kegiatansasaran', 'Cegah\KegiatanSasaranAPIController');

  //Datin
  Route::resource('callcenter', 'Datin\CallcenterAPIController');
  Route::resource('cobapakai', 'Datin\CobapakaiAPIController');
  Route::resource('pecandunonsuntik', 'Datin\PecanduNonsuntikAPIController');
  Route::resource('pecandusuntik', 'Datin\PecanduSuntikAPIController');
  Route::resource('permintaandata', 'Datin\PermintaandataAPIController');
  Route::resource('riset', 'Datin\RisetAPIController');
  Route::resource('setahunpakai', 'Datin\SetahunpakaiAPIController');
  Route::resource('surveypenyalahguna', 'Datin\SurveyPenyalahgunaAPIController');
  Route::resource('teraturpakai', 'Datin\TeraturpakaiAPIController');
  Route::resource('tindakcallcenter', 'Datin\TindaklanjutCallcenterAPIController');
  Route::resource('tindakcallcenterbnnp', 'Datin\TindaklanjutCallcenterBnnpAPIController');
  Route::resource('surveypenyalahgunanarkoba', 'Datin\SurveyPenyalahgunaNarkobaAPIController');
  Route::resource('surveypenyalahgunaketergantungan', 'Datin\SurveyPenyalahgunaKetergantunganAPIController');
  Route::resource('pekerjaanjaringan', 'Datin\PekerjaanJaringanAPIController');
  Route::resource('pengecekanjaringan', 'Datin\PengecekanJaringanAPIController');
  Route::resource('pengadaanemail', 'Datin\PengadaanEmailAPIController');
  Route::resource('callcenterdisposisi', 'Datin\CallCenterDisposisiAPIController');

  //Dayamas
  Route::resource('alihfungsilahan', 'Dayamas\AlihFungsiLahanAPIController');
  Route::resource('alihjenisprofesi', 'Dayamas\AlihJenisProfesiAPIController');
  Route::resource('alihjenisusaha', 'Dayamas\AlihJenisUsahaAPIController');
  Route::resource('altdevkawasan', 'Dayamas\AltdevKawasanrawanAPIController');
  Route::resource('altdevlahan', 'Dayamas\AltdevLahanganjaAPIController');

  Route::resource('altdevpetani', 'Dayamas\AltdevLahanganjaPetaniAPIController');
  Route::get('singlePesertaLahan/{parent_id}', 'Dayamas\AltdevLahanganjaPetaniAPIController@singlePeserta')->name('single_peserta_lahan');
  Route::resource('altdevprofesi', 'Dayamas\AltdevProfesiAPIController');
  Route::resource('altdevprofesipeserta', 'Dayamas\AltdevProfesiPesertaAPIController');
  Route::get('singlePesertaProfesi/{parent_id}', 'Dayamas\AltdevProfesiPesertaAPIController@singlePeserta')->name('single_peserta_profesi');
  Route::resource('monevkawasan', 'Dayamas\MonevKawasanrawanAPIController');
  Route::resource('monevkawasanpeserta', 'Dayamas\MonevKawasanrawanPesertaAPIController');
  Route::resource('psmlsm', 'Dayamas\PsmLsmAPIController');
  Route::resource('psmpelatihan', 'Dayamas\PsmPelatihanPenggiatAPIController');
  Route::resource('psmpengembangan', 'Dayamas\PsmPengembanganKapasitasAPIController');
  Route::resource('psmpenggiat', 'Dayamas\PsmPenggiatAPIController');
  Route::resource('psmsinergitas', 'Dayamas\PsmSinergitasAPIController');
  Route::resource('psmsupervisi', 'Dayamas\PsmSupervisiAPIController');
  Route::resource('tesnarkoba', 'Dayamas\TesUjiNarkobaAPIController');
  Route::resource('tesnarkobaheader', 'Dayamas\TesUjiNarkobaHeaderAPIController');
  Route::resource('tesnarkobapeserta', 'Dayamas\TesUjiNarkobaPesertaAPIController');
  Route::get('tespeserta/{id}', 'Dayamas\TesUjiNarkobaPesertaAPIController@getPeserta')->name('getPesertaTes');

  Route::resource('rapatKerjaPemetaan', 'Dayamas\RapatKerjaPemetaanAPIController');
  Route::resource('balaiBesar', 'BalaiBesar\BalaiBesarAPIController');


  //Huker
  Route::resource('hukerkerjasamamonev', 'Huker\HukerKerjasamaMonevAPIController');
  Route::resource('hukumnonlitigasi', 'Huker\HukumNonlitigasiAPIController');
  Route::resource('hukumaudiensi', 'Huker\HukumAudiensiAPIController');
  Route::resource('hukumperka', 'Huker\HukumPerkaAPIController');

  Route::resource('perkafinalisasi', 'Huker\Perka\PerkaFinalisasiAPIController');
  Route::get('getperkafinalisasi/{id}', 'Huker\Perka\PerkaFinalisasiAPIController@getFinalisasi')->name('getFinalisasi');

  Route::resource('perkaharmonisasi', 'Huker\Perka\PerkaHarmonisasiAPIController');
  Route::get('getperkaharmonisasi/{id}', 'Huker\Perka\PerkaHarmonisasiAPIController@getHarmonisasi')->name('getHarmonisasi');

  Route::resource('perkadraftawal', 'Huker\Perka\PerkaDraftAwalAPIController');
  Route::get('getperkadraftawal/{id}', 'Huker\Perka\PerkaDraftAwalAPIController@getDraftAwal')->name('getDraftAwal');

  Route::resource('perkapenetapan', 'Huker\Perka\PerkaPenetapanAPIController');
  Route::get('getperkapenetapan/{id}', 'Huker\Perka\PerkaPenetapanAPIController@getPenetapan')->name('getPenetapan');

  Route::resource('hukumpraperadilan', 'Huker\HukumPraperadilanAPIController');
  Route::resource('hukumpendampingan', 'Huker\HukumPendampinganAPIController');
  Route::resource('hukumlainnya', 'Huker\HukumKegiatanLainnyaAPIController');
  Route::resource('hukumsosialisasi', 'Huker\HukumSosialisasiAPIController');
  Route::resource('kerjasamaluarnegeri', 'Huker\KerjasamaLuarnegeriAPIController');
  Route::resource('kerjasamamonev', 'Huker\KerjasamaMonevAPIController');
  Route::resource('kerjasamanota', 'Huker\KerjasamaNotaKesepahamanAPIController');
  Route::resource('kerjasamabilateral', 'Huker\KerjasamaPerjanjianBilateralAPIController');
  Route::resource('kerjasamasosialisasi', 'Huker\KerjasamaSosialisasiAPIController');
  Route::resource('monevperaturanuu', 'Huker\MonevPeraturanUuAPIController');
  Route::resource('rakoraudiensi', 'Huker\RakorAudiensiAPIController');
  Route::resource('sosialisasiperaturan', 'Huker\SosialisasiPeraturanUuAPIController');
  Route::resource('kerjasamalainnya', 'Huker\KerjasamaLainnyaAPIController');

  //Irtama
  Route::resource('pembentukanperka', 'Irtama\PembentukanPerkaAPIController');
  Route::resource('irtarakoraudiensi', 'Irtama\RakorAudiensiAPIController');
  Route::resource('rikturiksus', 'Irtama\RikturiksusAPIController');
  Route::resource('rikturiksusterperiksa', 'Irtama\RikturiksusTerperiksaAPIController');
  Route::resource('auditlha', 'Irtama\AuditLhaAPIController');
  Route::resource('apelupacara', 'Irtama\ApelUpacaraAPIController');
  Route::resource('irtamaptl', 'Irtama\IrtamaPtlAPIController');
  Route::resource('reviulk', 'Irtama\ReviuLkAPIController');
  Route::resource('reviulkip', 'Irtama\ReviuLkipAPIController');
  Route::resource('reviurkakl', 'Irtama\ReviuRkaklAPIController');
  Route::resource('reviurkbmn', 'Irtama\ReviuRkbmnAPIController');
  Route::resource('penegakandisiplin', 'Irtama\PenegakanDisiplinAPIController');
  Route::resource('sopkebijakan', 'Irtama\SopKebijakanAPIController');
  Route::resource('irtamasosialisasi', 'Irtama\IrtamaSosialisasiAPIController');
  Route::resource('irtamaverifikasi', 'Irtama\IrtamaVerifikasiAPIController');
  Route::resource('irtamalhabidang', 'Irtama\AuditLhaBidangAPIController');
  Route::get('deletelhabidang', 'Irtama\AuditLhaBidangAPIController@deleteLhaBidang');
  Route::resource('irtamalharekomendasi', 'Irtama\AuditLhaRekomendasiAPIController');
  Route::get('deletelharekomendasi/{id_lha}', 'Irtama\AuditLhaRekomendasiAPIController@deleteLhaRekomendasi');
  Route::get('ptlbidang/{id_lha}', 'Irtama\IrtamaPtlAPIController@ptlBidang');
  Route::get('detailirtamalhabidang', 'Irtama\AuditLhaBidangAPIController@detailirtamalhabidang');

  //Rehab
  Route::resource('pasiensirena', 'Rehab\PasienSirenaAPIController');
  Route::resource('entripasien', 'Rehab\EntriPasienAPIController');
  Route::resource('entripasienk', 'Rehab\EntriPasienKAPIController');
  Route::resource('infolembaga', 'Rehab\InfoUmumLembagaAPIController');
  Route::resource('klaimpasien', 'Rehab\KlaimPasienAPIController');
  Route::resource('nspk', 'Rehab\NspkAPIController');
  Route::resource('pascaklienheader', 'Rehab\PascaKlienHeaderAPIController');
  Route::resource('pascaklienjalan', 'Rehab\PascaKlienRawatJalanAPIController');
  Route::resource('pascaklienlanjut', 'Rehab\PascaKlienRawatLanjutAPIController');
  Route::resource('pelatihan', 'Rehab\PelatihanAPIController');
  Route::resource('pelatihanpeserta', 'Rehab\PelatihanPesertaAPIController');
  Route::resource('penilaianlembaga', 'Rehab\PenilaianLembagaAPIController');

  // Route::get('dokumen_nspk_rehabilitasi','Rehab\NspkAPIController@dokumenNSPKRehabilitasi');
  //  Route::get('penilaian_lembaga_rehabilitasi','Rehab\PenilaianLembagaAPIController@penilaianLembagaRehabilitasi');
  Route::post('penilaian_lembaga_rehabilitasi','Rehab\PenilaianLembagaAPIController@penilaianLembagaRehabilitasi');
  //Badiklat
  Route::resource('kegiatan', 'Badiklat\KegiatanAPIController');
  Route::resource('kegiatanpeserta', 'Badiklat\KegiatanPesertaAPIController');
  Route::get('singlePesertaPelatihan/{parent_id}', 'Badiklat\KegiatanPesertaAPIController@singlePeserta');
  Route::get('single_pelatihan_rehabilitasi/{parent_id}', 'Rehab\PelatihanPesertaAPIController@singlePelatihanPeserta');

  //Balailab
  Route::resource('pengujian', 'Balailab\PengujianAPIController');

  //Nihil
  Route::resource('monitoringnihil', 'MonitoringNihilAPIController');
  Route::resource('anggaran', 'AnggaranAPIController');

  //Settama
  Route::resource('settama','Settama\SettamaAPIController');

  Route::resource('arahan', 'Arahan\ArahanAPIController');
});


//no auth token
Route::get('lookup/{type}', 'GlobalAPIController@lookupValues')->name('lookup');
Route::get('wilayah', 'GlobalAPIController@getWilayah')->name('getWilayah');
Route::get('negara', 'GlobalAPIController@getNegara')->name('getNegara');
Route::post('instansi', 'GlobalAPIController@getInstansi')->name('getInstansi');
Route::post('jnsbrgbukti', 'GlobalAPIController@getJnsBrgBukti')->name('getJnsBrgBukti');
Route::post('jnsbrgbuktimobile', 'GlobalAPIController@getJnsBrgBuktiMobile')->name('getJnsBrgBuktiMobile');
Route::get('jnskasus', 'GlobalAPIController@getJnsKasus')->name('getJnsKasus');
Route::get('propinsi', 'GlobalAPIController@getPropinsi')->name('getPropinsi');
Route::get('getpropkab', 'GlobalAPIController@getPropKab')->name('getPropKab');
Route::get('filterwilayah/{parent?}', 'GlobalAPIController@getWilayahByParent')->name('getWilayahByParent');
Route::get('getsatker', 'GlobalAPIController@getSatkerList')->name('getSatkerList');
Route::get('getsatkerbyid/{id}', 'GlobalAPIController@getSatkerById')->name('getSatkerById');
Route::get('getsatuan', 'GlobalAPIController@getSatuan')->name('getSatuan');
Route::get('getsasaran', 'GlobalAPIController@getSasaran')->name('getSasaran');
Route::get('getgroup', 'GlobalAPIController@getGroupList')->name('getGroup');
Route::get('gettemuan', 'GlobalAPIController@getKodeTemuan')->name('getTemuan');
Route::get('getrekomendasi', 'GlobalAPIController@getKodeRekomendasi')->name('getRekomendasi');
Route::get('singleLookupValues/{where}', 'GlobalAPIController@singleLookupValues')->name('single_lookup');
Route::get('detail_instansi/{where}', 'GlobalAPIController@detailInstansi')->name('detail_instansi');
/* @author : Daniel Andi */
Route::get('pelaksana_settama', 'GlobalAPIController@pelaksanaSettama');
Route::get('pelaksana_bagian', 'GlobalAPIController@settamaPelaksanaBagian');
Route::get('settama_jenis_kegiatan/{id_parent?}', 'GlobalAPIController@settamaJenisKegiatan');
Route::get('getmedia/{jenis}/{parent?}', 'GlobalAPIController@getMedia');
Route::get('getkomoditi', 'GlobalAPIController@getKomoditi');

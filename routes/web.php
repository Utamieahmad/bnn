<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/downloadapp','AuthenticationController@downloadApp')->name('downloadApp');
Route::get('/usermanual','AuthenticationController@userManual')->name('userManual');
Route::get('/userpelatihan','AuthenticationController@userPelatihan')->name('User_pelatihan');
Route::post('/registeruser','AuthenticationController@register')->name('registeruser');
Route::post('/login_process','AuthenticationController@login_process')->name('login_process');
Route::post('/nip_process','AuthenticationController@nip_process')->name('nip_process');
Route::get('/forgot_password','AuthenticationController@forgot_password')->name('forgot_password');
Route::post('/forgot_password_process','AuthenticationController@forgot_password')->name('forgot_password_process');
Route::get('/reset_password/{token}', 'AuthenticationController@reset_password')->name('reset_password');
Route::post('/reset_password_process', 'AuthenticationController@reset_password')->name('reset_password_process');
Route::get('/reset_email/{token}', 'AuthenticationController@reset_email')->name('reset_email');
Route::get('/api/documentation',function(){ //redirect to web SIN 
	return redirect('/login');
});

Route::group(['middleware' => ['auth']], function () {
	Route::get('/','DashboardController@dashboard')->name('dashboard');
	Route::get('logout',function(){
		Auth::logout();
		return redirect('/login');
	});
	Route::get('/profile', 'UserController@profile')->name('profile');
	Route::post('/upload_photo', 'UserController@upload_photo')->name('upload_photo');
	Route::post('/input_nihil', 'caseController@inputNihil');
	Route::get('/pilih_kabupaten','intelijenController@pilihKabupaten')->name('pilih_kabupaten');
	Route::get('/get_detail_tersangka','intelijenController@getDetailTersangka')->name('get_detail_tersangka');
	Route::get('/get_detail_irtama_bidang','irtamaController@getDetailBidang')->name('get_detail_irtama_bidang');
	Route::get('/ajax_irtama_rekomendasi/{id}','irtamaController@ajaxIrtamaRekomendasi')->name('ajax_irtama_rekomendasi');
	Route::post('/change_email_process', 'AuthenticationController@change_email');
	Route::post('/change_nip_process', 'AuthenticationController@change_nip');


	Route::group(['prefix'=>'pemberantasan'],function(){

		Route::post('/input_tersangka', 'caseController@inputTersangka');
		Route::post('/update_tersangka', 'caseController@updateTersangka');
		Route::post('/input_brgbukti', 'caseController@inputBrgBukti');
		Route::post('/update_brgbukti', 'caseController@updateBrgBukti');
		Route::post('/input_brgbukti_nonar', 'caseController@inputBrgBuktiNonNar');
		Route::post('/update_brgbukti_nonar', 'caseController@updateBrgBuktiNonNar');
		Route::post('/input_brgbukti_prekursor', 'caseController@inputBrgBuktiPrekursor');
		Route::post('/update_brgbukti_prekursor', 'caseController@updateBrgBuktiPrekursor');
		Route::post('/input_brgbukti_adiktif', 'caseController@inputBrgBuktiAdiktif');
		Route::post('/update_brgbukti_adiktif', 'caseController@updateBrgBuktiAdiktif');
		Route::post('/input_brgbukti_aset', 'caseController@inputBrgBuktiAset');
		Route::post('/update_brgbukti_aset', 'caseController@updateBrgBuktiAset');
		Route::match(['get','post'],'/razia/{page?}', 'RaziaController@pendataanRazia')->name('razia');
		Route::get('/add_razia', 'RaziaController@addPendataanRazia')->name('add_razia');
		Route::get('/edit_razia/{id}', 'RaziaController@editPendataanRazia')->name('edit_razia');
		Route::post('/input_razia', 'RaziaController@inputPendataanRazia');
		Route::post('/update_razia', 'RaziaController@updatePendataanRazia');
		Route::post('/delete_razia', 'RaziaController@deletePendataanRazia')->name('delete_razia');
		Route::get('/print_razia', 'RaziaController@printPendataanRazia');

		Route::group(['prefix'=>'dir_narkotika'],function(){

			Route::match(['get','post'],'/pendataan_lkn/{page?}','narkotikaController@pendataanLKN')->name('pendataan_lkn');
			Route::get('/edit_pendataan_lkn/{id}','narkotikaController@editPendataanLKN')->name('edit_pendataan_lkn');
			Route::get('/add_pendataan_lkn','narkotikaController@addPendataanLKN')->name('add_pendataan_lkn');
			Route::post('/input_pendataan_lkn','narkotikaController@inputPendataanLKN')->name('add_pendataan_lkn');
			Route::post('/update_pendataan_lkn','narkotikaController@updatePendataanLKN');
			Route::post('/delete_pendataan_lkn','narkotikaController@deletePendataanLKN')->name('delete_pendataan_lkn');
			Route::get('/print_pendataan_lkn','narkotikaController@printLkn');
			Route::post('/download_pendataan_lkn','narkotikaController@downloadLkn');
			Route::get('/view','narkotikaController@view');

			Route::match(['get','post'],'/pendataan_pemusnahan_ladangganja/{page?}','narkotikaController@pendataanLadangGanja')->name('pendataan_pemusnahan_ladangganja');
			Route::get('/edit_pendataan_pemusnahan_ladangganja/{id}','narkotikaController@editPendataanLadangGanja')->name('edit_pendataan_pemusnahan_ladangganja');
			Route::post('/update_pendataan_pemusnahan_ladangganja','narkotikaController@updatePendataanLadangGanja');
			Route::get('/add_pendataan_pemusnahan_ladangganja','narkotikaController@addpendataanLadangGanja')->name('add_pendataan_pemusnahan_ladangganja');
			Route::post('/input_pendataan_pemusnahan_ladangganja','narkotikaController@inputPendataanLadangGanja')->name('input_pendataan_pemusnahan_ladangganja');
			Route::post('/delete_pendataan_pemusnahan_ladangganja','narkotikaController@deletePendataanLadangGanja')->name('delete_pendataan_pemusnahan_ladangganja');
			Route::get('/print_ladang', 'narkotikaController@printLadang');
			// Route::get('/view','narkotikaController@view');
		});

		Route::group(['prefix'=>'dir_psikotropika'],function(){
			Route::match(['get','post'],'/psi_pendataan_lkn/{page?}','psikotropikaController@PsipendataanLKN')->name('psi_pendataan_lkn');
			// Route::get('/psi_pendataan_lkn','psikotropikaController@PsipendataanLKN')->name('psi_pendataan_lkn');
			Route::get('/edit_psi_pendataan_lkn/{id}','psikotropikaController@editPsiPendataanLKN')->name('edit_psi_pendataan_lkn');
			Route::get('/add_psi_pendataan_lkn','psikotropikaController@addPsiPendataanLKN')->name('add_psi_pendataan_lkn');
			Route::post('/input_psi_pendataan_lkn','psikotropikaController@inputPsiPendataanLKN')->name('input_psi_pendataan_lkn');
			Route::post('/update_psi_pendataan_lkn','psikotropikaController@updatePsiPendataanLKN');
			Route::post('/delete_psi_pendataan_lkn','psikotropikaController@deletePsiPendataanLKN')->name('delete_psi_pendataan_lkn');
			Route::get('/print_psi_pendataan_lkn','psikotropikaController@printPagePsiPendataanLKN');
			Route::get('/view','psikotropikaController@view');
			// Route::get('/print_psi_lkn', 'psikotropikaController@printPsiLkn');
		});

		Route::group(['prefix'=>'dir_tppu'],function(){
			Route::get('print_page_tppu/{segment}/{page?}','tppuController@printPage')->name('print_page_tppu');
			Route::match(['get','post'],'/pendataan_tppu/{page?}','tppuController@pendataanTPPU')->name('pendataan_tppu');
			// Route::get('/pendataan_tppu','tppuController@pendataanTPPU')->name('pendataan_tppu');
			Route::get('/edit_pendataan_tppu/{id}','tppuController@editPendataanTPPU')->name('edit_pendataan_tppu');
			Route::get('/add_pendataan_tppu','tppuController@addPendataanTPPU')->name('add_pendataan_tppu');
			Route::post('/input_pendataan_tppu','tppuController@inputPendataanTPPU');
			Route::post('/update_pendataan_tppu','tppuController@updatePendataanTPPU');
			Route::post('/delete_pendataan_tppu','tppuController@deletePendataanTPPU')->name('delete_pendataan_tppu');
			Route::get('/print_pendataan_tppu','tppuController@printPagePendataanTPPU');
			Route::get('/view','tppuController@view');
			// Route::get('/print_tppu', 'tppuController@printTppu');
		});

		Route::group(['prefix'=>'dir_intelijen'],function(){
			Route::get('print_page_jaringan/{segment}/{page?}','intelijenController@printPage')->name('print_page_jaringan');
			Route::match(['get','post'],'/pendataan_jaringan/{page?}','intelijenController@pendataanJaringan')->name('pendataan_jaringan');
			// Route::get('/pendataan_jaringan','intelijenController@pendataanJaringan')->name('pendataan_jaringan');
			Route::get('/edit_pendataan_jaringan/{id}','intelijenController@editPendataanJaringan')->name('edit_pendataan_jaringan');
			Route::get('/add_pendataan_jaringan','intelijenController@addPendataanJaringan')->name('add_pendataan_jaringan');
			Route::post('/add_data_pendataan_jaringan','intelijenController@addDataPendataanJaringan')->name('add_pendataan_jaringan');
			Route::post('/input_pendataan_jaringan','intelijenController@inputPendataanJaringan');
			Route::post('/update_pendataan_jaringan','intelijenController@updatePendataanJaringan')->name('update_pendataan_jaringan');
			Route::post('/delete_pendataan_jaringan','intelijenController@deletePendataanJaringan')->name('delete_pendataan_jaringan');
			Route::get('/print_pendataan_jaringan','intelijenController@printIntelijen');
			// Route::get('/print_intelijen/{current_page?}', 'intelijenController@printIntelijen')->name('print_intelijen');
		});

		Route::group(['prefix'=>'dir_wastahti'],function(){
			Route::match(['get','post'],'/pendataan_brgbukti/{page?}','wastahtiController@pendataanBrgbukti')->name('pendataan_brgbukti');
			// Route::get('/pendataan_brgbukti','wastahtiController@pendataanBrgbukti')->name('pendataan_brgbukti');
			Route::get('/edit_pendataan_brgbukti/{id}','wastahtiController@editPendataanBrgbukti')->name('edit_pendataan_brgbukti');
			Route::get('/add_pendataan_brgbukti','wastahtiController@addPendataanBrgbukti')->name('add_pendataan_brgbukti');
			Route::post('/add_data_pendataan_brgbukti','wastahtiController@addDataPendataanBrgbukti');
			Route::get('/add_form_pendataan_brgbukti/{id}','wastahtiController@addFormPendataanBrgbukti')->name('add_form_pendataan_brgbukti');
			Route::post('/input_pendataan_brgbukti','wastahtiController@inputPendataanBrgbukti');
			Route::post('/input_detail_pendataan_brgbukti','wastahtiController@inputDetailPendataanBrgbukti')->name('update_pendataan_brgbukti');
			Route::get('/view','wastahtiController@view');
			// Route::get('/view','caseController@view');
			Route::post('/update_pendataan_brgbukti','wastahtiController@updatePendataanBrgbukti');
			Route::post('/delete_pendataan_brgbukti','wastahtiController@deletePendataanBrgbukti')->name('delete_pendataan_brgbukti');
			Route::get('/print_pendataan_brgbukti','wastahtiController@printPendataanBrgbukti');
			// Route::get('/print_pendataan_brgbukti/{current_page?}','wastahtiController@printPendataanBrgbukti')->name('print_pendataan_brgbukti');

			Route::match(['get','post'],'/pendataan_tahanan/{page?}','wastahtiController@pendataanTahanan')->name('pendataan_tahanan');
			// Route::get('/pendataan_tahanan','wastahtiController@pendataanTahanan')->name('pendataan_tahanan');
			Route::get('/edit_pendataan_tahanan/{id}','wastahtiController@editPendataanTahanan')->name('edit_pendataan_tahanan');
			Route::get('/edit_list_pendataan_tahanan/{id}','wastahtiController@editListPendataanTahanan')->name('edit_list_pendataan_tahanan');
			Route::get('/add_pendataan_tahanan','wastahtiController@addPendataanTahanan')->name('add_pendataan_tahanan');
			Route::post('/add_data_pendataan_tahanan','wastahtiController@addDataPendataanTahanan')->name('add_pendataan_tahanan');
			Route::post('/input_pendataan_tahanan','wastahtiController@inputPendataanTahanan');
			Route::post('/delete_pendataan_tahanan','wastahtiController@deletePendataanTahanan')->name('delete_pendataan_tahanan');
			Route::get('/print_pendataan_tahanan','wastahtiController@printPendataanTahanan');
			// Route::get('/view','caseController@view');
			Route::get('/view','wastahtiController@view');
		});

		Route::group(['prefix'=>'dir_penindakan'],function(){
			Route::match(['get','post'],'/pendataan_dpo/{page?}','penindakanController@pendataanDpo')->name('pendataan_dpo');
			// Route::get('/pendataan_dpo','penindakanController@pendataanDpo')->name('pendataan_dpo');
			Route::get('/edit_pendataan_dpo/{id}','penindakanController@editPendataanDpo')->name('edit_pendataan_dpo');
			Route::post('/update_pendataan_dpo','penindakanController@updatePendataanDpo');
			Route::get('/add_pendataan_dpo','penindakanController@addPendataanDpo')->name('add_pendataan_dpo');
			Route::post('/input_pendataan_dpo','penindakanController@inputPendataanDpo');
			Route::post('/delete_pendataan_dpo','penindakanController@deletePendataanDpo')->name('delete_pendataan_dpo');
			Route::get('/print_pendataan_dpo','penindakanController@printPagePendataanDpo');
			Route::get('/view','penindakanController@view');
		});

		Route::group(['prefix'=>'dir_interdiksi'],function(){
			Route::get('print_page_interdiksi/{segment}/{page?}','wastahtiController@printPage')->name('print_page_interdiksi');
			Route::match(['get','post'],'/pendataan_intdpo/{page?}','interdiksiController@pendataanIntDpo')->name('pendataan_intdpo');
			// Route::get('/pendataan_intdpo','interdiksiController@pendataanIntDpo')->name('pendataan_intdpo');
			Route::get('/edit_pendataan_intdpo/{id}','interdiksiController@editPendataanIntDpo')->name('edit_pendataan_intdpo');
			Route::post('/update_pendataan_intdpo','interdiksiController@updatePendataanIntDpo');
			Route::get('/add_pendataan_intdpo','interdiksiController@addPendataanIntDpo')->name('add_pendataan_intdpo');
			Route::post('/input_pendataan_intdpo','interdiksiController@inputPendataanIntDpo');
			Route::post('/delete_pendataan_intdpo','interdiksiController@deletePendataanIntDpo')->name('delete_pendataan_intdpo');
			Route::get('/print_pendataan_intdpo','interdiksiController@printPagePendataanIntDpo');
			Route::get('/view','caseController@view');
		});
	});


	Route::group(['prefix'=>'rehabilitasi'],function(){
		Route::group(['prefix'=>'dir_plrip'],function(){
			Route::match(['get','post'],'/informasi_lembaga_umum_plrip/{page?}','RehabilitasiController@indexLembagaUmumPlrip')->name('informasi_lembaga_umum_plrip');
			Route::get('/add_informasi_lembaga_umum_plrip','RehabilitasiController@addLembagaUmumPlrip')->name('add_informasi_lembaga_umum_plrip');
			Route::post('/save_informasi_lembaga_umum_plrip','RehabilitasiController@addLembagaUmumPlrip')->name('save_informasi_lembaga_umum_plrip');
			Route::post('/update_informasi_lembaga_umum_plrip','RehabilitasiController@updateLembagaUmumPlrip')->name('update_informasi_lembaga_umum_plrip');
			Route::post('/delete_informasi_lembaga_umum_plrip','RehabilitasiController@deleteLembagaUmumPlrip')->name('delete_informasi_lembaga_umum_plrip');
			Route::GET('/edit_informasi_lembaga_umum_plrip/{id}','RehabilitasiController@editLembagaUmumPlrip')->name('edit_informasi_lembaga_umum_plrip');

			Route::match(['get','post'],'/dokumen_nspk_plrip/{page?}','RehabilitasiController@dokumenNspkPlrip')->name('dokumen_nspk_plrip');
			// Route::get('/dokumen_nspk_plrip','RehabilitasiController@dokumenNspkPlrip')->name('dokumen_nspk_plrip');
			Route::get('/edit_dokumen_nspk_plrip/{id}','RehabilitasiController@editdokumenNspkPlrip')->name('edit_dokumen_nspk_plrip');
			Route::get('/add_dokumen_nspk_plrip','RehabilitasiController@addDokumenNspkPlrip')->name('add_dokumen_nspk_plrip');
			Route::post('/save_dokumen_nspk_plrip','RehabilitasiController@addDokumenNspkPlrip')->name('save_dokumen_nspk_plrip');
			Route::post('/update_dokumen_nspk_plrip','RehabilitasiController@updateDokumenNspkPlrip')->name('update_dokumen_nspk_plrip');
			Route::post('/delete_dokumen_nspk_plrip','RehabilitasiController@deleteDokumenNspkPlrip')->name('delete_dokumen_nspk_plrip');

			Route::match(['get','post'],'/kegiatan_pelatihan_plrip/{page?}','RehabilitasiController@kegiatanPelatihanPLRIP')->name('kegiatan_pelatihan_plrip');
			// Route::get('/kegiatan_pelatihan_plrip','RehabilitasiController@kegiatanPelatihanPLRIP')->name("kegiatan_pelatihan_plrip");
			Route::get('/edit_kegiatan_pelatihan_plrip/{id}','RehabilitasiController@editkegiatanPelatihanPLRIP')->name('edit_kegiatan_pelatihan_plrip');
			Route::get('/add_kegiatan_pelatihan_plrip','RehabilitasiController@addkegiatanPelatihanPLRIP')->name('add_kegiatan_pelatihan_plrip');
			Route::post('/save_kegiatan_pelatihan_plrip','RehabilitasiController@addkegiatanPelatihanPLRIP')->name('save_kegiatan_pelatihan_plrip');
			Route::post('/delete_kegiatan_pelatihan_plrip','RehabilitasiController@deleteKegiatanPelatihanPlrip')->name('delete_kegiatan_pelatihan_plrip');
			Route::post('/update_kegiatan_pelatihan_plrip','RehabilitasiController@updateKegiatanPelatihanPlrip')->name('update_kegiatan_pelatihan_plrip');
			Route::post('/delete_peserta_pelatihan_plrip','RehabilitasiController@deletePesertaPelatihanPlrip')->name('delete_peserta_pelatihan_plrip');
			Route::post('/update_peserta_pelatihan_plrip','RehabilitasiController@updatePesertaPelatihanPlrip')->name('update_peserta_pelatihan_plrip');
			Route::post('/add_peserta_pelatihan_plrip','RehabilitasiController@addPesertaPelatihanPlrip')->name('add_peserta_pelatihan_plrip');
			Route::get('/edit_peserta_pelatihan_plrip/{id}','RehabilitasiController@editPesertaPelatihanPlrip')->name('edit_peserta_pelatihan_plrip');
			Route::GET('/index_peserta_pelatihan_plrip/{parent_id}/{page?}','RehabilitasiController@indexPesertaKegiatanPelatihanPlrip')->name('index_peserta_pelatihan_plrip');

			Route::get('/penilaian_lembaga_plrip','RehabilitasiController@penilaianLembagaPlrip')->name('penilaian_lembaga_plrip');
			Route::get('/edit_penilaian_lembaga_plrip/{id}','RehabilitasiController@editPenilaianLembagaPlrip')->name('edit_penilaian_lembaga_plrip');
			Route::get('/add_penilaian_lembaga_plrip','RehabilitasiController@addPenilaianLembagaPlrip')->name('add_penilaian_lembaga_plrip');
			Route::post('/save_penilaian_lembaga_plrip','RehabilitasiController@addPenilaianLembagaPlrip')->name('save_penilaian_lembaga_plrip');
			Route::post('/update_penilaian_lembaga_plrip','RehabilitasiController@updatePenilaianLembagaPlrip')->name('update_penilaian_lembaga_plrip');
			Route::post('/delete_penilaian_lembaga_plrip','RehabilitasiController@deletePenilaianLembagaPlrip')->name('delete_penilaian_lembaga_plrip');
		});

		Route::group(['prefix'=>'dir_plrkm'],function(){
			Route::match(['get','post'],'/informasi_lembaga_umum_plrkm/{page?}','RehabilitasiController@indexLembagaUmumPlrkm')->name('informasi_lembaga_umum_plrkm');
			// Route::get('/informasi_lembaga_umum_plrkm/{page?}','RehabilitasiController@indexLembagaUmumPlrkm')->name('informasi_lembaga_umum_plrkm');
			Route::get('/edit_informasi_lembaga_umum_plrkm/{id}','RehabilitasiController@editLembagaUmumPlrkm')->name('edit_informasi_lembaga_umum_plrkm');
			Route::get('/add_informasi_lembaga_umum_plrkm','RehabilitasiController@addLembagaUmumPlrkm')->name('add_informasi_lembaga_umum_plrkm');
			Route::post('/save_informasi_lembaga_umum_plrkm','RehabilitasiController@addLembagaUmumPlrkm')->name('save_informasi_lembaga_umum_plrkm');
			Route::post('/delete_informasi_lembaga_umum_plrkm','RehabilitasiController@deleteLembagaUmumPlrkm')->name('delete_informasi_lembaga_umum_plrkm');
			Route::post('/update_informasi_lembaga_umum_plrkm','RehabilitasiController@updateLembagaUmumPlrkm')->name('update_informasi_lembaga_umum_plrkm');

			Route::match(['get','post'],'/dokumen_nspk_plrkm/{page?}','RehabilitasiController@dokumenNspkPlrkm')->name('dokumen_nspk_plrkm');
			// Route::get('/dokumen_nspk_plrkm','RehabilitasiController@dokumenNspkPlrkm')->name('dokumen_nspk_plrkm');
			Route::get('/edit_dokumen_nspk_plrkm/{id}','RehabilitasiController@editDokumenNspkPlrkm')->name('edit_dokumen_nspk_plrkm');
			Route::get('/add_dokumen_nspk_plrkm','RehabilitasiController@addDokumenNspkPlrkm')->name('add_dokumen_nspk_plrkm');
			Route::post('/save_dokumen_nspk_plrkm','RehabilitasiController@addDokumenNspkPlrkm')->name('save_dokumen_nspk_plrkm');
			Route::post('/update_dokumen_nspk_plrkm','RehabilitasiController@updateDokumenNspkPlrkm')->name('update_dokumen_nspk_plrkm');
			Route::post('/delete_dokumen_nspk_plrkm','RehabilitasiController@deleteDokumenNspkPlrkm')->name('delete_dokumen_nspk_plrkm');

			Route::match(['get','post'],'/kegiatan_pelatihan_plrkm/{page?}','RehabilitasiController@kegiatanPelatihanPlrkm')->name('kegiatan_pelatihan_plrkm');
			// Route::get('/kegiatan_pelatihan_plrkm','RehabilitasiController@kegiatanPelatihanPlrkm')->name('kegiatan_pelatihan_plrkm');
			Route::get('/edit_kegiatan_pelatihan_plrkm/{id}','RehabilitasiController@editKegiatanPelatihanPlrkm')->name('edit_kegiatan_pelatihan_plrkm');
			Route::get('/add_kegiatan_pelatihan_plrkm','RehabilitasiController@addKegiatanPelatihanPlrkm')->name('add_kegiatan_pelatihan_plrkm');
			Route::post('/save_kegiatan_pelatihan_plrkm','RehabilitasiController@addKegiatanPelatihanPlrkm')->name('save_kegiatan_pelatihan_plrkm');
			Route::post('/delete_kegiatan_pelatihan_plrkm','RehabilitasiController@deleteKegiatanPelatihanPlrkm')->name('delete_kegiatan_pelatihan_plrkm');
			Route::post('/update_kegiatan_pelatihan_plrkm','RehabilitasiController@updateKegiatanPelatihanPlrkm')->name('update_kegiatan_pelatihan_plrkm');

			Route::post('/delete_peserta_pelatihan_plrkm','RehabilitasiController@deletePesertaPelatihanPlrkm')->name('delete_peserta_pelatihan_plrkm');
			Route::post('/update_peserta_pelatihan_plrkm','RehabilitasiController@updatePesertaPelatihanPlrkm')->name('update_peserta_pelatihan_plrkm');
			Route::post('/add_peserta_pelatihan_plrkm','RehabilitasiController@addPesertaPelatihanPlrkm')->name('add_peserta_pelatihan_plrkm');
			Route::get('/edit_peserta_pelatihan_plrkm/{id}','RehabilitasiController@editPesertaPelatihanPlrkm')->name('edit_peserta_pelatihan_plrkm');
			Route::GET('/index_peserta_pelatihan_plrkm/{parent_id}/{page?}','RehabilitasiController@indexPesertaKegiatanPelatihanPlrkm')->name('index_peserta_pelatihan_plrkm');


			Route::get('/penilaian_lembaga_plrkm','RehabilitasiController@penilaianLembagaPlrkm')->name('penilaian_lembaga_plrkm');
			Route::get('/edit_penilaian_lembaga_plrkm/{id}','RehabilitasiController@editPenilaianLembagaPlrkm')->name('edit_penilaian_lembaga_plrkm');
			Route::get('/add_penilaian_lembaga_plrkm','RehabilitasiController@addPenilaianLembagaPlrkm')->name('add_penilaian_lembaga_plrkm');
			Route::post('/save_penilaian_lembaga_plrkm','RehabilitasiController@addPenilaianLembagaPlrkm')->name('save_penilaian_lembaga_plrkm');
			Route::post('/update_penilaian_lembaga_plrkm','RehabilitasiController@updatePenilaianLembagaPlrkm')->name('update_penilaian_lembaga_plrkm');
			Route::post('/delete_penilaian_lembaga_plrkm','RehabilitasiController@deletePenilaianLembagaPlrkm')->name('delete_penilaian_lembaga_plrkm');



			// Route::get('/penilaian_lembaga','RehabilitasiController@penilaianLembagaPlrkm')->name('penilaian_lembaga_plrkm');
			// Route::get('/edit_penilaian_lembaga/{id}','RehabilitasiController@editPenilaianLembagaPlrkm')->name('edit_penilaian_lembaga_plrkm');
			// Route::get('/add_penilaian_lembaga','RehabilitasiController@addPenilaianLembagaPlrkm')->name('add_penilaian_lembaga_plrkm');

		});

		Route::group(['prefix'=>'dir_pasca'],function(){
			Route::match(['get','post'],'/informasi_lembaga_umum_pascarehabilitasi/{page?}','RehabilitasiController@indexLembagaUmumPasca')->name('informasi_lembaga_umum_pascarehabilitasi');
			// Route::get('/informasi_lembaga_umum_pascarehabilitasi','RehabilitasiController@indexLembagaUmumPasca')->name('informasi_lembaga_umum_pascarehabilitasi');
			Route::get('/edit_informasi_lembaga_umum_pascarehabilitasi/{id}','RehabilitasiController@editLembagaUmumPasca')->name('edit_informasi_lembaga_umum_pascarehabilitasi');
			Route::get('/add_informasi_lembaga_umum_pascarehabilitasi','RehabilitasiController@addLembagaUmumPasca')->name('add_informasi_lembaga_umum_pascarehabilitasi');
			Route::post('/save_informasi_lembaga_umum_pascarehabilitasi','RehabilitasiController@addLembagaUmumPasca')->name('save_informasi_lembaga_umum_pascarehabilitasi');
			Route::post('/delete_informasi_lembaga_umum_pascarehabilitasi','RehabilitasiController@deleteLembagaUmumPasca')->name('delete_informasi_lembaga_umum_pascarehabilitasi');
			Route::post('/update_informasi_lembaga_umum_pascarehabilitasi','RehabilitasiController@updateLembagaUmumPasca')->name('update_informasi_lembaga_umum_pascarehabilitasi');

			Route::match(['get','post'],'/dokumen_nspk_pascarehabilitasi/{page?}','RehabilitasiController@dokumenNspkPasca')->name('dokumen_nspk_pascarehabilitasi');
			// Route::get('/dokumen_nspk_pascarehabilitasi','RehabilitasiController@dokumenNspkPasca')->name("dokumen_nspk_pascarehabilitasi");
			Route::get('/edit_dokumen_nspk_pascarehabiltasi/{id}','RehabilitasiController@editDokumenNspkPasca')->name('edit_dokumen_nspk_pascarehabilitasi');
			Route::get('/add_dokumen_nspk_pascarehabiltasi','RehabilitasiController@addDokumenNspkPasca')->name('add_dokumen_nspk_pascarehabilitasi');
			Route::post('/save_dokumen_nspk_pascarehabiltasi','RehabilitasiController@addDokumenNspkPasca')->name('save_dokumen_nspk_pascarehabilitasi');
			Route::post('/delete_dokumen_nspk_pascarehabiltasi','RehabilitasiController@deleteDokumenNspkPasca')->name('delete_dokumen_nspk_pascarehabiltasi');
			Route::post('/update_dokumen_nspk_pascarehabilitasi','RehabilitasiController@updateDokumenNspkPasca')->name('update_dokumen_nspk_pascarehabilitasi');

			Route::match(['get','post'],'/kegiatan_pelatihan_pascarehabilitasi/{page?}','RehabilitasiController@kegiatanPelatihanPasca')->name('kegiatan_pelatihan_pascarehabilitasi');
			// Route::get('/kegiatan_pelatihan_pascarehabilitasi','RehabilitasiController@kegiatanPelatihanPasca')->name('kegiatan_pelatihan_pascarehabilitasi');
			Route::get('/edit_kegiatan_pelatihan_pascarehabilitasi/{id}','RehabilitasiController@editkegiatanPelatihanPasca')->name('edit_kegiatan_pelatihan_pascarehabilitasi');
			Route::get('/add_kegiatan_pelatihan_pascarehabilitasi','RehabilitasiController@addkegiatanPelatihanPasca')->name('add_kegiatan_pelatihan_pascarehabilitasi');
			Route::POST('/save_kegiatan_pelatihan_pascarehabilitasi','RehabilitasiController@addkegiatanPelatihanPasca')->name('save_kegiatan_pelatihan_pascarehabilitasi');
			Route::POST('/update_kegiatan_pelatihan_pascarehabilitasi','RehabilitasiController@updateKegiatanPelatihanPasca')->name('update_kegiatan_pelatihan_pascarehabilitasi');
			Route::POST('/delete_kegiatan_pelatihan_pascarehabilitasi','RehabilitasiController@deleteKegiatanPelatihanPasca')->name('delete_kegiatan_pelatihan_pascarehabilitasi');
			Route::POST('/delete_peserta_pelatihan_pascarehabilitasi','RehabilitasiController@deletePesertaKegiatanPelatihanPasca')->name('delete_peserta_pelatihan_pascarehabilitasi');
			Route::GET('/index_peserta_pelatihan_pascarehabilitasi/{parent_id}/{page?}','RehabilitasiController@indexPesertaKegiatanPelatihanPasca')->name('edit_peserta_pelatihan_pascarehabilitasi');
			Route::GET('/edit_peserta_pelatihan_pascarehabilitasi/{id}','RehabilitasiController@editPesertaKegiatanPelatihanPasca')->name('edit_peserta_pelatihan_pascarehabilitasi');
			Route::POST('/update_peserta_pelatihan_pascarehabilitasi','RehabilitasiController@updatePesertaKegiatanPelatihanPasca')->name('update_peserta_pelatihan_pascarehabilitasi');
			Route::POST('/add_peserta_pelatihan_pascarehabilitasi','RehabilitasiController@addPesertaKegiatanPelatihanPasca')->name('add_peserta_pelatihan_pascarehabilitasi');

			Route::get('/penilaian_lembaga_pascarehabilitasi','RehabilitasiController@penilaianLembagaPasca')->name('penilaian_lembaga_pascarehabilitasi');
			Route::get('/edit_penilaian_lembaga_pascarehabilitasi/{id}','RehabilitasiController@editpenilaianLembagaPasca')->name('edit_penilaian_lembaga_pascarehabilitasi');
			Route::get('/add_penilaian_lembaga_pascarehabilitasi','RehabilitasiController@addpenilaianLembagaPasca')->name('add_penilaian_lembaga_pascarehabilitasi');
			Route::post('/save_penilaian_lembaga_pascarehabilitasi','RehabilitasiController@addpenilaianLembagaPasca')->name('save_penilaian_lembaga_pascarehabilitasi');
			Route::post('/update_penilaian_lembaga_pascarehabilitasi','RehabilitasiController@updatePenilaianLembagaPasca')->name('update_penilaian_lembaga_pascarehabilitasi');
			Route::post('/delete_penilaian_lembaga_pascarehabilitasi','RehabilitasiController@addpenilaianLembagaPasca')->name('delete_penilaian_lembaga_pascarehabilitasi');//diindikasi salah

		});
		Route::get('/print_page_pascarehabilitasi/{segment?}/{page?}','RehabilitasiController@printPage')->name("print_page_pascarehabilitasi");

	});


	Route::group(['prefix'=>'pencegahan'],function(){
		Route::group(['prefix'=>'dir_advokasi'],function(){
			Route::match(['get', 'post'] ,'/pendataan_koordinasi/{page?}','AdvokasiController@pendataanKoordinasi')->name('pendataan_koordinasi');
			Route::get('/edit_pendataan_koordinasi/{id}','AdvokasiController@editpendataanKoordinasi')->name('edit_pendataan_koordinasi');
			Route::get('/add_pendataan_koordinasi','AdvokasiController@addpendataanKoordinasi')->name('add_pendataan_koordinasi');
			Route::post('/input_pendataan_koordinasi','AdvokasiController@inputpendataanKoordinasi');
			Route::post('/update_pendataan_koordinasi','AdvokasiController@updatependataanKoordinasi');
			Route::post('/delete_pendataan_koordinasi','AdvokasiController@deletependataanKoordinasi')->name('delete_pendataan_koordinasi');
			Route::get('/view','caseController@view');
			Route::get('/printkoordinasi', 'AdvokasiController@printKoordinasi');

			Route::match(['get', 'post'] ,'/pendataan_jejaring/{page?}','AdvokasiController@pendataanJejaring')->name('pendataan_jejaring');
			Route::get('/edit_pendataan_jejaring/{id}','AdvokasiController@editpendataanJejaring')->name('edit_pendataan_jejaring');
			Route::get('/add_pendataan_jejaring','AdvokasiController@addpendataanJejaring')->name('add_pendataan_jejaring');
			Route::post('/input_pendataan_jejaring','AdvokasiController@inputpendataanJejaring');
			Route::post('/update_pendataan_jejaring','AdvokasiController@updatependataanJejaring');
			Route::post('/delete_pendataan_jejaring','AdvokasiController@deletependataanJejaring')->name('delete_pendataan_jejaring');
			Route::get('/view','caseController@view');
			Route::get('/printjejaring', 'AdvokasiController@printJejaring');

			Route::match(['get', 'post'] ,'/pendataan_asistensi/{page?}','AdvokasiController@pendataanAsistensi')->name('pendataan_asistensi');
			Route::get('/edit_pendataan_asistensi/{id}','AdvokasiController@editpendataanAsistensi')->name('edit_pendataan_asistensi');
			Route::get('/add_pendataan_asistensi','AdvokasiController@addpendataanAsistensi')->name('add_pendataan_asistensi');
			Route::post('/input_pendataan_asistensi','AdvokasiController@inputpendataanAsistensi');
			Route::post('/update_pendataan_asistensi','AdvokasiController@updatependataanAsistensi');
			Route::post('/delete_pendataan_asistensi','AdvokasiController@deletependataanAsistensi')->name('delete_pendataan_asistensi');
			Route::get('/view','caseController@view');
			Route::get('/printasistensi', 'AdvokasiController@printAsistensi');

			Route::get('/penguatan_asistensi','AdvokasiController@penguatanAsistensi')->name('penguatan_asistensi');
			Route::get('/edit_penguatan_asistensi/{id}','AdvokasiController@editpenguatanAsistensi')->name('edit_penguatan_asistensi');
			Route::get('/add_penguatan_asistensi','AdvokasiController@addpenguatanAsistensi')->name('add_penguatan_asistensi');
			Route::post('/input_penguatan_asistensi','AdvokasiController@inputpenguatanAsistensi');
			Route::post('/update_penguatan_asistensi','AdvokasiController@updatepenguatanAsistensi');
			Route::get('/view','caseController@view');
			Route::get('/printpenguatan', 'AdvokasiController@printPenguatan');

			Route::match(['get', 'post'] ,'/pendataan_intervensi/{page?}','AdvokasiController@pendataanIntervensi')->name('pendataan_intervensi');
			Route::get('/edit_pendataan_intervensi/{id}','AdvokasiController@editpendataanIntervensi')->name('edit_pendataan_intervensi');
			Route::get('/add_pendataan_intervensi','AdvokasiController@addpendataanIntervensi')->name('add_pendataan_intervensi');
			Route::post('/input_pendataan_intervensi','AdvokasiController@inputpendataanIntervensi');
			Route::post('/update_pendataan_intervensi','AdvokasiController@updatependataanIntervensi');
			Route::post('/delete_pendataan_intervensi','AdvokasiController@deletependataanIntervensi')->name('delete_pendataan_intervensi');
			Route::get('/view','caseController@view');
			Route::get('/printintervensi', 'AdvokasiController@printIntervensi');

			Route::match(['get', 'post'] ,'/pendataan_supervisi/{page?}','AdvokasiController@pendataanSupervisi')->name('pendataan_supervisi');
			Route::get('/edit_pendataan_supervisi/{id}','AdvokasiController@editpendataanSupervisi')->name('edit_pendataan_supervisi');
			Route::get('/add_pendataan_supervisi','AdvokasiController@addpendataanSupervisi')->name('add_pendataan_supervisi');
			Route::post('/input_pendataan_supervisi','AdvokasiController@inputpendataanSupervisi');
			Route::post('/update_pendataan_supervisi','AdvokasiController@updatependataanSupervisi');
			Route::post('/delete_pendataan_supervisi','AdvokasiController@deletependataanSupervisi')->name('delete_pendataan_supervisi');
			Route::get('/view','caseController@view');
			Route::get('/printsupervisi', 'AdvokasiController@printSupervisi');

			Route::match(['get', 'post'] ,'/pendataan_monitoring/{page?}','AdvokasiController@pendataanMonitoring')->name('pendataan_monitoring');
			Route::get('/edit_pendataan_monitoring/{id}','AdvokasiController@editpendataanMonitoring')->name('edit_pendataan_monitoring');
			Route::get('/add_pendataan_monitoring','AdvokasiController@addpendataanMonitoring')->name('add_pendataan_monitoring');
			Route::post('/input_pendataan_monitoring','AdvokasiController@inputpendataanMonitoring');
			Route::post('/update_pendataan_monitoring','AdvokasiController@updatependataanMonitoring');
			Route::post('/delete_pendataan_monitoring','AdvokasiController@deletependataanMonitoring')->name('delete_pendataan_monitoring');
			Route::get('/view','caseController@view');
			Route::get('/printmonitoring', 'AdvokasiController@printMonitoring');

			Route::match(['get', 'post'] ,'/pendataan_bimbingan/{page?}','AdvokasiController@pendataanBimbingan')->name('pendataan_bimbingan');
			Route::get('/edit_pendataan_bimbingan/{id}','AdvokasiController@editpendataanBimbingan')->name('edit_pendataan_bimbingan');
			Route::get('/add_pendataan_bimbingan','AdvokasiController@addpendataanBimbingan')->name('add_pendataan_bimbingan');
			Route::post('/input_pendataan_bimbingan','AdvokasiController@inputpendataanBimbingan');
			Route::post('/update_pendataan_bimbingan','AdvokasiController@updatependataanBimbingan');
			Route::post('/delete_pendataan_bimbingan','AdvokasiController@deletependataanBimbingan')->name('delete_pendataan_bimbingan');
			Route::get('/view','caseController@view');
			Route::get('/printbimbingan', 'AdvokasiController@printBimbingan');

			Route::match(['get', 'post'] ,'/pendataan_sosialisasi/{page?}','AdvokasiController@pendataanSosialisasi')->name('pendataan_sosialisasi');
			Route::get('/edit_pendataan_sosialisasi/{id}','AdvokasiController@editpendataanSosialisasi')->name('edit_pendataan_sosialisasi');
			Route::get('/add_pendataan_sosialisasi','AdvokasiController@addpendataanSosialisasi')->name('add_pendataan_sosialisasi');
			Route::post('/input_pendataan_sosialisasi','AdvokasiController@inputpendataanSosialisasi');
			Route::post('/update_pendataan_sosialisasi','AdvokasiController@updatependataanSosialisasi');
			Route::post('/delete_pendataan_sosialisasi','AdvokasiController@deletependataanSosialisasi')->name('delete_pendataan_sosialisasi');
			Route::get('/view','caseController@view');
			Route::get('/printsosialisasi', 'AdvokasiController@printSosialisasi');

		});

		Route::group(['prefix'=>'dir_diseminasi'],function(){
			Route::match(['get', 'post'] ,'/pendataan_online/{page?}','diseminasiController@pendataanOnline')->name('pendataan_online');
			Route::get('/edit_pendataan_online/{id}','diseminasiController@editpendataanOnline')->name('edit_pendataan_online');
			Route::post('/update_pendataan_online','diseminasiController@updatependataanOnline');
			Route::get('/add_pendataan_online','diseminasiController@addpendataanOnline')->name('add_pendataan_online');
			Route::post('/input_pendataan_online','diseminasiController@inputpendataanOnline');
			Route::get('/view','diseminasiController@view');
			Route::get('/printonline', 'diseminasiController@printOnline');

			Route::match(['get', 'post'] ,'/pendataan_penyiaran/{page?}','diseminasiController@pendataanPenyiaran')->name('pendataan_penyiaran');
			Route::get('/edit_pendataan_penyiaran/{id}','diseminasiController@editpendataanPenyiaran')->name('edit_pendataan_penyiaran');
			Route::post('/update_pendataan_penyiaran','diseminasiController@updatependataanPenyiaran');
			Route::get('/add_pendataan_penyiaran','diseminasiController@addpendataanPenyiaran')->name('add_pendataan_penyiaran');
			Route::post('/input_pendataan_penyiaran','diseminasiController@inputpendataanPenyiaran');
			Route::get('/view','diseminasiController@view');
			Route::get('/printpenyiaran', 'diseminasiController@printPenyiaran');

			Route::match(['get', 'post'] ,'/pendataan_cetak/{page?}','diseminasiController@pendataanCetak')->name('pendataan_cetak');
			Route::get('/edit_pendataan_cetak/{id}','diseminasiController@editpendataanCetak')->name('edit_pendataan_cetak');
			Route::post('/update_pendataan_cetak','diseminasiController@updatependataanCetak');
			Route::get('/add_pendataan_cetak','diseminasiController@addpendataanCetak')->name('add_pendataan_cetak');
			Route::post('/input_pendataan_cetak','diseminasiController@inputpendataanCetak');
			Route::get('/view','diseminasiController@view');
			Route::get('/printcetak', 'diseminasiController@printCetak');

			Route::match(['get', 'post'] ,'/pendataan_konvensional/{page?}','diseminasiController@pendataanKonvensional')->name('pendataan_konvensional');
			Route::get('/edit_pendataan_konvensional/{id}','diseminasiController@editpendataanKonvensional')->name('edit_pendataan_konvensional');
			Route::post('/update_pendataan_konvensional','diseminasiController@updatependataanKonvensional');
			Route::get('/add_pendataan_konvensional','diseminasiController@addpendataanKonvensional')->name('add_pendataan_konvensional');
			Route::post('/input_pendataan_konvensional','diseminasiController@inputpendataanKonvensional');
			Route::get('/view','diseminasiController@view');
			Route::get('/printkonvensional', 'diseminasiController@printKonvensional');

			Route::get('/pendataan_videotron','diseminasiController@pendataanVideotron')->name('pendataan_videotron');
			Route::get('/edit_pendataan_videotron/{id}','diseminasiController@editpendataanVideotron')->name('edit_pendataan_videotron');
			Route::post('/update_pendataan_videotron','diseminasiController@updatependataanVideotron');
			Route::get('/add_pendataan_videotron','diseminasiController@addpendataanVideotron')->name('add_pendataan_videotron');
			Route::post('/input_pendataan_videotron','diseminasiController@inputpendataanVideotron');
			Route::get('/view','diseminasiController@view');
			Route::get('/printvideotron', 'diseminasiController@printVideotron');

		});
                
                Route::group(['prefix'=>'dep_cegah'],function(){
                    Route::match(['get', 'post'],'/data_aktivitas_sebaran/{page?}','deputiCegahController@pendataanAktivitasSebaran')->name('pendataan_aktivitasSebaran');
                });
	});

	Route::group(['prefix'=>'pemberdayaan'],function(){
		Route::group(['prefix'=>'dir_masyarakat'],function(){
			Route::match(['get','post'],'/pendataan_tes_narkoba/{page?}','MasyarakatController@pendataanTesNarkoba')->name('pendataan_tes_narkoba');
			Route::get('/edit_pendataan_tes_narkoba/{id}','MasyarakatController@editpendataanTesNarkoba')->name('edit_pendataan_tes_narkoba');
			Route::get('/add_pendataan_tes_narkoba','MasyarakatController@addpendataanTesNarkoba')->name('add_pendataan_tes_narkoba');
			Route::post('/input_pendataan_tes_narkoba','MasyarakatController@inputPendataanTesNarkoba');
			Route::post('/update_pendataan_tes_narkoba','MasyarakatController@updatePendataanTesNarkoba');
			Route::post('/delete_pendataan_tes_narkoba','MasyarakatController@deletePendataanTesNarkoba')->name('delete_pendataan_tes_narkoba');
			Route::get('/view','caseController@view');
			Route::post('/input_peserta','MasyarakatController@inputPeserta');
			Route::post('/update_peserta','MasyarakatController@updatePeserta');

			Route::match(['get','post'],'/pendataan_anti_narkoba/{page?}','MasyarakatController@pendataanAntiNarkoba')->name('pendataan_anti_narkoba');
			Route::get('/edit_pendataan_anti_narkoba/{id}','MasyarakatController@editpendataanAntiNarkoba')->name('edit_pendataan_anti_narkoba');
			Route::get('/add_pendataan_anti_narkoba','MasyarakatController@addpendataanAntiNarkoba')->name('add_pendataan_anti_narkoba');
			Route::post('/input_pendataan_anti_narkoba','MasyarakatController@inputPendataanAntiNarkoba');
			Route::post('/update_pendataan_anti_narkoba','MasyarakatController@updatePendataanAntiNarkoba');
			Route::post('/delete_pendataan_anti_narkoba','MasyarakatController@deletePendataanAntiNarkoba')->name('delete_pendataan_anti_narkoba');
			Route::get('/view','caseController@view');

			Route::match(['get','post'],'/pendataan_pelatihan/{page?}','MasyarakatController@pendataanPelatihan')->name('pendataan_pelatihan');
			Route::get('/edit_pendataan_pelatihan/{id}','MasyarakatController@editpendataanPelatihan')->name('edit_pendataan_pelatihan');
			Route::get('/add_pendataan_pelatihan','MasyarakatController@addpendataanPelatihan')->name('add_pendataan_pelatihan');
			Route::post('/input_pendataan_pelatihan','MasyarakatController@inputPendataanPelatihan');
			Route::post('/update_pendataan_pelatihan','MasyarakatController@updatePendataanPelatihan');
			Route::post('/delete_pendataan_pelatihan','MasyarakatController@deletePendataanPelatihan')->name('delete_pendataan_pelatihan');
			Route::get('/view','caseController@view');

			Route::get('/pendataan_kapasitas','MasyarakatController@pendataanKapasitas')->name('pendataan_kapasitas');
			Route::get('/edit_pendataan_kapasitas/{id}','MasyarakatController@editpendataanKapasitas')->name('edit_pendataan_kapasitas');
			Route::get('/add_pendataan_kapasitas','MasyarakatController@addpendataanKapasitas')->name('add_pendataan_kapasitas');
			Route::post('/input_pendataan_kapasitas','MasyarakatController@inputpendataanKapasitas');
			Route::post('/update_pendataan_kapasitas','MasyarakatController@updatependataanKapasitas');
			Route::get('/view','caseController@view');

			Route::match(['get','post'],'/psm_supervisi/{page?}','MasyarakatController@psmSupervisi')->name('psm_supervisi');
			Route::get('/edit_psm_supervisi/{id}','MasyarakatController@editpsmSupervisi')->name('edit_psm_supervisi');
			Route::get('/add_psm_supervisi','MasyarakatController@addpsmSupervisi')->name('add_psm_supervisi');
			Route::post('/input_psm_supervisi','MasyarakatController@inputpsmSupervisi');
			Route::post('/update_psm_supervisi','MasyarakatController@updatepsmSupervisi');
			Route::post('/delete_psm_supervisi','MasyarakatController@deletepsmSupervisi')->name('delete_psm_supervisi');
			Route::get('/view','caseController@view');

			Route::get('/psm_ormas','MasyarakatController@psmOrmas')->name('psm_ormas');
			Route::get('/edit_psm_ormas/{id}','MasyarakatController@editpsmOrmas')->name('edit_psm_ormas');
			Route::get('/add_psm_ormas','MasyarakatController@addpsmOrmas')->name('add_psm_ormas');
			Route::post('/input_psm_ormas','MasyarakatController@inputpsmOrmas');
			Route::post('/update_psm_ormas','MasyarakatController@updatepsmOrmas');
			Route::get('/view','caseController@view');

			Route::match(['get','post'],'/rapat_kerja_pemetaan/{page?}','MasyarakatController@rapatKerja')->name('rapat_kerja_pemetaan');
			Route::get('/edit_rapat_kerja_pemetaan/{id}','MasyarakatController@editRapatKerja')->name('edit_rapat_kerja_pemetaan');
			Route::get('/add_rapat_kerja_pemetaan','MasyarakatController@addRapatKerja')->name('add_rapat_kerja_pemetaan');
			Route::post('/save_rapat_kerja_pemetaan','MasyarakatController@addRapatKerja')->name('save_rapat_kerja_pemetaan');
			Route::post('/update_rapat_kerja_pemetaan','MasyarakatController@updateRapatKerja')->name('update_rapat_kerja_pemetaan');
			Route::post('/delete_rapat_kerja_pemetaan','MasyarakatController@deleteRapatKerja')->name('delete_rapat_kerja_pemetaan');


		});

		Route::group(['prefix'=>'dir_alternative'],function(){
			Route::match(['get','post'],'/altdev_lahan_ganja/{page?}','caseController@altdevLahanGanja')->name('altdev_lahan_ganja');
			Route::get('/edit_altdev_lahan_ganja/{id}','caseController@editaltdevLahanGanja')->name('edit_altdev_lahan_ganja');
			Route::get('/add_altdev_lahan_ganja','caseController@addaltdevLahanGanja')->name('add_altdev_lahan_ganja');
			Route::post('/save_altdev_lahan_ganja','caseController@addaltdevLahanGanja')->name('save_altdev_lahan_ganja');
			Route::post('/update_altdev_lahan_ganja','caseController@updateAltdevLahanGanja')->name('update_altdev_lahan_ganja');
			Route::post('/delete_altdev_lahan_ganja','caseController@deleteAltdevLahanGanja')->name('delete_altdev_lahan_ganja');

			Route::get('/peserta_alih_fungsi','caseController@pesertaAlihFungsi')->name('peserta_alih_fungsi');
			Route::get('/edit_peserta_alih_fungsi/{id}','caseController@editPesertaAlihFngsi')->name('edit_peserta_alih_fungsi');
			Route::get('/add_peserta_alih_fungsi','caseController@addPesertaAlihFngsi')->name('add_peserta_alih_fungsi');
			Route::post('/save_peserta_alih_fungsi','caseController@addPesertaAlihFngsi')->name('save_peserta_alih_fungsi');;
			Route::post('/update_peserta_alih_fungsi','caseController@updatePesertaAlihFngsi')->name('update_peserta_alih_fungsi');
			Route::post('/delete_peserta_alih_fungsi','caseController@deletePesertaAlihFngsi')->name('delete_peserta_alih_fungsi');

			Route::match(['get','post'],'/altdev_alih_profesi/{page?}','caseController@altdevAlihProfesi')->name('altdev_alih_profesi');
			Route::get('/edit_altdev_alih_profesi/{id}','caseController@editaltdevAlihProfesi')->name('edit_altdev_alih_profesi');
			Route::get('/add_altdev_alih_profesi','caseController@addaltdevAlihProfesi')->name('add_altdev_alih_profesi');
			Route::post('/save_altdev_alih_profesi','caseController@addaltdevAlihProfesi')->name('save_altdev_alih_profesi');
			Route::post('/update_altdev_alih_profesi','caseController@updatealtdevAlihProfesi')->name('update_altdev_alih_profesi');
			Route::post('/delete_altdev_alih_profesi','caseController@deletealtdevAlihProfesi')->name('delete_altdev_alih_profesi');

			Route::get('/peserta_alih_profesi','caseController@pesertaAlihProfesi')->name('peserta_alih_profesi');
			Route::get('/edit_peserta_alih_profesi/{id}','caseController@editPesertaAlihProfesi')->name('edit_peserta_alih_profesi');
			Route::get('/add_peserta_alih_profesi','caseController@addPesertaAlihProfesi')->name('add_peserta_alih_profesi');
			Route::post('/save_peserta_alih_profesi','caseController@addPesertaAlihProfesi')->name('save_peserta_alih_profesi');;
			Route::post('/update_peserta_alih_profesi','caseController@updatePesertaAlihProfesi')->name('update_peserta_alih_profesi');
			Route::post('/delete_peserta_alih_profesi','caseController@deletePesertaAlihProfesi')->name('delete_peserta_alih_profesi');



			Route::match(['get','post'],'/altdev_kawasan_rawan/{page?}','caseController@altdevKawasanRawan')->name('altdev_kawasan_rawan');
			Route::get('/edit_altdev_kawasan_rawan/{id}','caseController@editaltdevKawasanRawan')->name('edit_altdev_kawasan_rawan');
			Route::get('/add_altdev_kawasan_rawan','caseController@addaltdevKawasanRawan')->name('add_altdev_kawasan_rawan');
			Route::post('/save_altdev_kawasan_rawan','caseController@addaltdevKawasanRawan')->name('save_altdev_kawasan_rawan');
			Route::post('/update_altdev_kawasan_rawan','caseController@updatealtdevKawasanRawan')->name('update_altdev_kawasan_rawan');
			Route::post('/delete_altdev_kawasan_rawan','caseController@deletealtdevKawasanRawan')->name('delete_altdev_kawasan_rawan');


			Route::match(['get','post'],'/altdev_monitoring','caseController@altdevMonitoring')->name('altdev_monitoring');
			Route::get('/edit_altdev_monitoring/{id}','caseController@editaltdevMonitoring')->name('edit_altdev_monitoring');
			Route::get('/add_altdev_monitoring','caseController@addaltdevMonitoring')->name('add_altdev_monitoring');
			Route::post('/save_altdev_monitoring','caseController@addaltdevMonitoring')->name('save_altdev_monitoring');
			Route::post('/delete_altdev_monitoring','caseController@deletealtdevMonitoring')->name('delete_altdev_monitoring');
			Route::post('/update_altdev_monitoring','caseController@updatealtdevMonitoring')->name('update_altdev_monitoring');


			Route::get('/peserta_monev','caseController@pesertaMonev')->name('peserta_monev');
			Route::get('/edit_peserta_monev/{id}','caseController@editPesertaMonev')->name('edit_peserta_monev');
			Route::get('/add_peserta_monev','caseController@addPesertaMonev')->name('add_peserta_monev');
			Route::post('/save_peserta_monev','caseController@addPesertaMonev')->name('save_peserta_monev');;
			Route::post('/update_peserta_monev','caseController@updatePesertaMonev')->name('update_peserta_monev');
			Route::post('/delete_peserta_monev','caseController@deletePesertaMonev')->name('delete_peserta_monev');

			Route::match(['get','post'],'/altdev_sinergi/{page?}','caseController@altdevSinergitas')->name('altdev_sinergi');
			Route::get('/edit_altdev_sinergi/{id}','caseController@editaltdevSinergitas')->name('edit_altdev_sinergitas');
			Route::get('/add_altdev_sinergi','caseController@addaltdevSinergitas')->name("add_altdev_sinergitas");
			Route::post('/save_altdev_sinergi','caseController@addaltdevSinergitas')->name("save_altdev_sinergitas");
			Route::post('/update_altdev_sinergi','caseController@updatealtdevSinergitas')->name("update_altdev_sinergitas");
			Route::post('/delete_altdev_sinergi','caseController@deletealtdevSinergitas')->name("delete_altdev_sinergitas");
			Route::get('/print_page/{segment?}/{page?}','caseController@printPage')->name("print_page");

			Route::match(['get','post'],'/alv_rapat_kerja_pemetaan','MasyarakatController@rapatKerjaDevelopment')->name('alv_rapat_kerja_pemetaan');
			Route::get('/edit_alv_rapat_kerja_pemetaan/{id}','MasyarakatController@editRapatKerjaDevelopment')->name('edit_alv_rapat_kerja_pemetaan');
			Route::get('/add_alv_rapat_kerja_pemetaan','MasyarakatController@addRapatKerjaDevelopment')->name('add_alv_rapat_kerja_pemetaan');
			Route::post('/save_alv_rapat_kerja_pemetaan','MasyarakatController@addRapatKerjaDevelopment')->name('save_alv_rapat_kerja_pemetaan');
			Route::post('/update_alv_rapat_kerja_pemetaan','MasyarakatController@updateRapatKerjaDevelopment')->name('update_alv_rapat_kerja_pemetaan');
			Route::post('/delete_alv_rapat_kerja_pemetaan','MasyarakatController@deleteRapatKerjaDevelopment')->name('delete_alv_rapat_kerja_pemetaan');


		});
		Route::get('/print_page_dayamas/{segment?}/{page?}','MasyarakatController@printPage')->name("print_page_dayamas");
	});

	Route::group(['prefix'=>'huker'],function(){

		Route::post('/input_perka_finalisasi', 'hukumController@inputPerkaFinalisasi');
		Route::post('/update_perka_finalisasi', 'hukumController@updatePerkaFinalisasi');

		Route::post('/input_perka_harmonisasi', 'hukumController@inputPerkaHarmonisasi');
		Route::post('/update_perka_harmonisasi', 'hukumController@updatePerkaHarmonisasi');

		Route::post('/input_perka_draftawal', 'hukumController@inputPerkaDraftAwal');
		Route::post('/update_perka_draftawal', 'hukumController@updatePerkaDraftAwal');

		Route::post('/update_perka_penetapan', 'hukumController@updatePerkaPenetapan');

		Route::group(['prefix'=>'dir_hukum'],function(){
			Route::match(['get', 'post'], '/hukum_nonlitigasi/{page?}','hukumController@hukumNonlitigasi')->name('hukum_nonlitigasi');
			Route::get('/edit_hukum_nonlitigasi/{id}','hukumController@edithukumNonlitigasi')->name('edit_hukum_nonlitigasi');
			Route::get('/add_hukum_nonlitigasi','hukumController@addhukumNonlitigasi')->name('add_hukum_nonlitigasi');
			Route::post('/input_hukum_nonlitigasi','hukumController@inputhukumNonlitigasi');
			Route::post('/update_hukum_nonlitigasi','hukumController@updatehukumNonlitigasi');
			Route::get('/view','hukumController@view');
			Route::post('/delete_hukum_nonlitigasi','hukumController@deleteHukumNonlitigasi')->name('delete_hukum_nonlitigasi');
			Route::get('/print_hukum_nonlitigasi/{segment?}/{page?}','hukumController@printNonlitigasi')->name("print_hukum_nonlitigasi");

			Route::match(['get', 'post'], '/hukum_audiensi/{page?}','hukumController@hukumAudiensi')->name('hukum_audiensi');
			Route::get('/edit_hukum_audiensi/{id}','hukumController@edithukumAudiensi')->name('edit_hukum_audiensi');
			Route::get('/add_hukum_audiensi','hukumController@addhukumAudiensi')->name('add_hukum_audiensi');
			Route::post('/input_hukum_audiensi','hukumController@inputhukumAudiensi');
			Route::post('/update_hukum_audiensi','hukumController@updatehukumAudiensi');
			Route::get('/view','hukumController@view');
			Route::post('/delete_hukum_audiensi','hukumController@deleteHukumAudiensi')->name('delete_hukum_audiensi');
			Route::get('/print_hukum_audiensi/{segment?}/{page?}','hukumController@printAudiensi')->name("print_hukum_audiensi");

			Route::match(['get', 'post'], '/hukum_pendampingan/{page?}','hukumController@hukumPendampingan')->name('hukum_pendampingan');
			Route::get('/edit_hukum_pendampingan/{id}','hukumController@edithukumPendampingan')->name('edit_hukum_pendampingan');
			Route::get('/add_hukum_pendampingan','hukumController@addhukumPendampingan')->name('add_hukum_pendampingan');
			Route::post('/input_hukum_pendampingan','hukumController@inputhukumPendampingan');
			Route::post('/update_hukum_pendampingan','hukumController@updatehukumPendampingan');
			Route::get('/view','hukumController@view');
			Route::post('/delete_hukum_pendampingan','hukumController@deleteHukumPendampingan')->name('delete_hukum_pendampingan');
			Route::get('/print_hukum_pendampingan/{segment?}/{page?}','hukumController@printPendampingan')->name("print_hukum_pendampingan");

			Route::match(['get', 'post'], '/hukum_prapradilan/{page?}','hukumController@hukumPrapradilan')->name('hukum_prapradilan');
			Route::get('/edit_hukum_prapradilan/{id}','hukumController@edithukumPrapradilan')->name('edit_hukum_prapradilan');
			Route::get('/add_hukum_prapradilan','hukumController@addhukumPrapradilan')->name('add_hukum_prapradilan');
			Route::post('/input_hukum_prapradilan','hukumController@inputhukumPrapradilan');
			Route::post('/update_hukum_prapradilan','hukumController@updatehukumPrapradilan');
			Route::get('/view','hukumController@view');
			Route::post('/delete_hukum_prapradilan','hukumController@deleteHukumPrapradilan')->name('delete_hukum_prapradilan');
			Route::get('/print_hukum_prapradilan/{segment?}/{page?}','hukumController@printPrapradilan')->name("print_hukum_prapradilan");

			Route::match(['get', 'post'], '/hukum_perka/{page?}','hukumController@hukumPerka')->name('hukum_perka');
			Route::get('/edit_hukum_perka/{id}','hukumController@edithukumPerka')->name('edit_hukum_perka');
			Route::get('/add_hukum_perka','hukumController@addhukumPerka')->name('add_hukum_perka');
			Route::post('/input_hukum_perka','hukumController@inputhukumPerka');
			Route::post('/update_hukum_perka','hukumController@updatehukumPerka');
			Route::get('/view','hukumController@view');
			Route::post('/delete_hukum_perka','hukumController@deletehukumPerka')->name('delete_hukum_perka');
			Route::get('/print_hukum_perka/{segment?}/{page?}','hukumController@printPerka')->name("print_hukum_perka");

			Route::match(['get', 'post'], '/hukum_lainnya/{page?}','hukumController@hukumLainnya')->name('hukum_lainnya');
			Route::get('/edit_hukum_lainnya/{id}','hukumController@edithukumLainnya')->name('edit_hukum_lainnya');
			Route::get('/add_hukum_lainnya','hukumController@addhukumLainnya')->name('add_hukum_lainnya');
			Route::post('/input_hukum_lainnya','hukumController@inputhukumLainnya');
			Route::post('/update_hukum_lainnya','hukumController@updatehukumLainnya');
			Route::get('/view','hukumController@view');
			Route::post('/delete_hukum_lainnya','hukumController@deleteHukumLainnya')->name('delete_hukum_lainnya');
			Route::get('/print_hukum_lainnya/{segment?}/{page?}','hukumController@printLainnya')->name("print_hukum_lainnya");

			// Route::get('/hukum_rakor','hukumController@hukumRakor')->name('hukum_rakor');
			// Route::get('/edit_hukum_rakor/{id}','hukumController@edithukumRakor')->name('edit_hukum_rakor');
			// Route::get('/add_hukum_rakor','hukumController@addhukumRakor')->name('add_hukum_rakor');
			// Route::post('/input_hukum_rakor','hukumController@inputhukumRakor');
			// Route::post('/update_hukum_rakor','hukumController@updatehukumRakor');
			// Route::get('/view','hukumController@view');

			// Route::get('/hukum_peraturanuu','hukumController@hukumPeraturanuu')->name('hukum_peraturanuu');
			// Route::get('/edit_hukum_peraturanuu/{id}','hukumController@edithukumPeraturanuu')->name('edit_hukum_peraturanuu');
			// Route::get('/add_hukum_peraturanuu','hukumController@addhukumPeraturanuu')->name('add_hukum_peraturanuu');
			// Route::post('/input_hukum_peraturanuu','hukumController@inputhukumPeraturanuu');
			// Route::post('/update_hukum_peraturanuu','hukumController@updatehukumPeraturanuu');
			// Route::get('/view','hukumController@view');

			// Route::get('/hukum_monevperaturanuu','hukumController@hukumMonevperaturanuu')->name('hukum_monevperaturanuu');
			// Route::get('/edit_hukum_monevperaturanuu/{id}','hukumController@edithukumMonevperaturanuu')->name('edit_hukum_monevperaturanuu');
			// Route::get('/add_hukum_monevperaturanuu','hukumController@addhukumMonevperaturanuu')->name('add_hukum_monevperaturanuu');
			// Route::post('/input_hukum_monevperaturanuu','hukumController@inputhukumMonevperaturanuu');
			// Route::post('/update_hukum_monevperaturanuu','hukumController@updatehukumMonevperaturanuu');
			// Route::get('/view','hukumController@view');
		});

		Route::group(['prefix'=>'dir_kerjasama'],function(){
			// Route::get('/kerjasama_luarnegeri','kerjasamaController@kerjasamaLuarnegeri')->name('kerjasama_luarnegeri');
			// Route::get('/edit_kerjasama_luarnegeri/{id}','kerjasamaController@editkerjasamaLuarnegeri')->name('edit_kerjasama_luarnegeri');
			// Route::get('/add_kerjasama_luarnegeri','kerjasamaController@addkerjasamaLuarnegeri')->name('add_kerjasama_luarnegeri');
			// Route::post('/input_kerjasama_luarnegeri','kerjasamaController@inputkerjasamaLuarnegeri');
			// Route::post('/update_kerjasama_luarnegeri','kerjasamaController@updatekerjasamaLuarnegeri');
			// Route::get('/view','kerjasamaController@view');

			Route::match(['get', 'post'], '/kerjasama_bilateral/{page?}','kerjasamaController@kerjasamaBilateral')->name('kerjasama_bilateral');
			Route::get('/edit_kerjasama_bilateral/{id}','kerjasamaController@editkerjasamaBilateral')->name('edit_kerjasama_bilateral');
			Route::get('/add_kerjasama_bilateral','kerjasamaController@addkerjasamaBilateral')->name('add_kerjasama_bilateral');
			Route::post('/input_kerjasama_bilateral','kerjasamaController@inputkerjasamaBilateral');
			Route::post('/update_kerjasama_bilateral','kerjasamaController@updatekerjasamaBilateral');
			Route::post('/delete_kerjasama_bilateral','kerjasamaController@deletekerjasamaBilateral')->name('delete_kerjasama_bilateral');
			Route::get('/view','kerjasamaController@view');
			Route::get('/printbilateral/{segment}/{page?}', 'kerjasamaController@printBilateral')->name('print_kerjasama_bilateral');

			Route::match(['get', 'post'], '/kerjasama_kesepemahaman/{page?}','kerjasamaController@kerjasamaKesepemahaman')->name('kerjasama_kesepemahaman');
			Route::get('/edit_kerjasama_kesepemahaman/{id}','kerjasamaController@editkerjasamaKesepemahaman')->name('edit_kerjasama_kesepemahaman');
			Route::get('/add_kerjasama_kesepemahaman','kerjasamaController@addkerjasamaKesepemahaman')->name('add_kerjasama_kesepemahaman');
			Route::post('/input_kerjasama_kesepemahaman','kerjasamaController@inputkerjasamaKesepemahaman');
			Route::post('/update_kerjasama_kesepemahaman','kerjasamaController@updatekerjasamaKesepemahaman');
			Route::post('/delete_kerjasama_kesepahaman','kerjasamaController@deletekerjasamaKesepahaman')->name('delete_kerjasama_kesepahaman');

			Route::get('/view','kerjasamaController@view');
			Route::get('/printkesepahaman/{segment}/{page?}', 'kerjasamaController@printKesepahaman')->name('print_kerjasama_nota');

			// Route::get('/kerjasama_sosialisasi','kerjasamaController@kerjasamaSosialisasi')->name('kerjasama_sosialisasi');
			// Route::get('/edit_kerjasama_sosialisasi/{id}','kerjasamaController@editkerjasamaSosialisasi')->name('edit_kerjasama_sosialisasi');
			// Route::get('/add_kerjasama_sosialisasi','kerjasamaController@addkerjasamaSosialisasi')->name('add_kerjasama_sosialisasi');
			// Route::post('/input_kerjasama_sosialisasi','kerjasamaController@inputkerjasamaSosialisasi');
			// Route::post('/update_kerjasama_sosialisasi','kerjasamaController@updatekerjasamaSosialisasi');
			// Route::get('/view','kerjasamaController@view');

			Route::match(['get', 'post'], '/kerjasama_monev/{page?}','kerjasamaController@kerjasamaMonev')->name('kerjasama_monev');
			Route::get('/edit_kerjasama_monev/{id}','kerjasamaController@editkerjasamaMonev')->name('edit_kerjasama_monev');
			Route::get('/add_kerjasama_monev','kerjasamaController@addkerjasamaMonev')->name('add_kerjasama_monev');
			Route::post('/input_kerjasama_monev','kerjasamaController@inputkerjasamaMonev');
			Route::post('/update_kerjasama_monev','kerjasamaController@updatekerjasamaMonev');
			Route::post('/delete_kerjasama_monev','kerjasamaController@deletekerjasamaMonev')->name('delete_kerjasama_monev');
			Route::get('/view','kerjasamaController@view');
			Route::get('/printmonev/{segment}/{page?}', 'kerjasamaController@printMonev')->name('print_kerjasama_monev');

			Route::match(['get', 'post'], 'kerjasama_lainnya/{page?}', 'kerjasamaController@kerjasamaLainnya')->name('kerjasama_lainnya');
			Route::get('/edit_kerjasama_lainnya/{id}','kerjasamaController@editkerjasamaLainnya')->name('edit_kerjasama_lainnya');
			Route::get('/add_kerjasama_lainnya','kerjasamaController@addkerjasamaLainnya')->name('add_kerjasama_lainnya');
			Route::post('/input_kerjasama_lainnya','kerjasamaController@inputkerjasamaLainnya');
			Route::post('/update_kerjasama_lainnya','kerjasamaController@updatekerjasamaLainnya');
			Route::post('/delete_kerjasama_lainnya','kerjasamaController@deletekerjasamaLainnya')->name('delete_kerjasama_lainnya');;
			Route::get('/view','kerjasamaController@view');
			Route::get('/printlainnya/{segment}/{page?}', 'kerjasamaController@printLainnya')->name('print_kerjasama_lainnya');

		});
	});

	Route::group(['prefix'=>'irtama'],function(){
			Route::get('print_page_irtama/{segment}/{page?}','irtamaController@printPage')->name('print_page_irtama');
                        Route::post('/download_laporan/{segment}','irtamaController@downloadLaporan')->name('download_laporan');
			Route::group(['prefix'=>'audit'],function(){

				Route::match(['get', 'post'] ,'/irtama_audit/{page?}','irtamaController@irtamaAudit')->name('irtama_audit');
				Route::get('/edit_irtama_audit/{id}','irtamaController@editirtamaAudit')->name('edit_irtama_audit');

				Route::post('/update_irtama_audit','irtamaController@updateirtamaAudit')->name('update_irtama_audit');
				Route::get('/add_irtama_audit','irtamaController@addirtamaAudit')->name('add_irtama_audit');
				Route::post('/input_irtama_audit','irtamaController@inputirtamaAudit')->name('input_irtama_audit');
				Route::post('/delete_irtama_audit','irtamaController@deleteIrtamaAudit')->name('delete_irtama_audit');				
				// Route::get('/view','irtamaController@view');
			});

			Route::group(['prefix'=>'bidang_audit'],function(){
				Route::post('/add_bidang_lha','irtamaController@addBidangLHA')->name('add_bidang_lha');
				Route::post('/update_bidang_lha','irtamaController@updateBidangLHA')->name('update_bidang_lha');
				Route::post('/delete_bidang_lha','irtamaController@deleteBidangLHA')->name('delete_bidang_lha');
				// Route::get('/irtama_bidang_audit','irtamaController@irtamaAudit')->name('irtama_audit');
				// Route::get('/edit_irtama_audit/{id}','irtamaController@editirtamaAudit')->name('edit_irtama_audit');

				// Route::post('/update_irtama_audit','irtamaController@updateirtamaAudit')->name('update_irtama_audit');
				// Route::get('/add_irtama_audit','irtamaController@addirtamaAudit')->name('add_irtama_audit');
				// Route::post('/input_irtama_audit','irtamaController@inputirtamaAudit');
				// Route::get('/view','irtamaController@view');
			});

			Route::group(['prefix'=>'ptl'],function(){

				Route::match(['get', 'post'] ,'/irtama_ptl/{page?}','irtamaController@irtamaPtl')->name('irtama_ptl');
				Route::get('/edit_irtama_ptl/{id}','irtamaController@editirtamaPtl')->name('edit_irtama_ptl');

				Route::post('/update_irtama_ptl','irtamaController@updateirtamaPtl')->name('update_irtama_ptl');
				Route::get('/add_irtama_ptl','irtamaController@addirtamaPtl')->name('add_irtama_ptl');
				Route::post('/input_irtama_ptl','irtamaController@inputirtamaPtl');
				Route::post('/delete_irtama_ptl','irtamaController@deleteIrtamaPtl')->name('delete_irtama_ptl');

				/*tab rekomendasi*/
				Route::get('/edit_temuan_irtama_ptl/{type?}/{id?}','irtamaController@editTemuanPRL')->name('edit_temuan_irtama_ptl');
				Route::post('/update_rekomendasi','irtamaController@updateRekomendasi')->name('update_rekomendasi');

			});

			Route::group(['prefix'=>'reviu'],function(){

				Route::match(['get','post'],'/irtama_lk/{page?}','irtamaController@irtamaLk')->name('irtama_lk');
				Route::get('/edit_irtama_lk/{id}','irtamaController@editirtamaLk')->name('edit_irtama_lk');
				Route::post('/update_irtama_lk','irtamaController@updateirtamaLk');
				Route::get('/add_irtama_lk','irtamaController@addirtamaLk')->name('add_irtama_lk');
				Route::post('/input_irtama_lk','irtamaController@inputirtamaLk');
				Route::post('/delete_irtama_lk','irtamaController@deleteIrtamaLk')->name('delete_irtama_lk');
				Route::get('/print_irtama_lk','irtamaController@printPageirtamaLk');
				Route::get('/view','irtamaController@view');

				Route::match(['get', 'post'],'/irtama_rkakl/{page?}','irtamaController@irtamaRkakl')->name('irtama_rkakl');
				Route::get('/edit_irtama_rkakl/{id}','irtamaController@editirtamaRkakl')->name('edit_irtama_rkakl');
				Route::post('/update_irtama_rkakl','irtamaController@updateirtamaRkakl');
				Route::get('/add_irtama_rkakl','irtamaController@addirtamaRkakl')->name('add_irtama_rkakl');
				Route::post('/input_irtama_rkakl','irtamaController@inputirtamaRkakl');
				Route::post('/delete_irtama_rkakl','irtamaController@deleteIrtamaRkakl')->name('delete_irtama_rkakl');
				Route::get('/print_irtama_rkakl','irtamaController@printPageirtamaRkakl');
				Route::get('/view','irtamaController@view');

				Route::match(['get', 'post'],'/irtama_rkbmn/{page?}','irtamaController@irtamaRkbmn')->name('irtama_rkbmn');
				Route::get('/edit_irtama_rkbmn/{id}','irtamaController@editirtamaRkbmn')->name('edit_irtama_rkbmn');
				Route::post('/update_irtama_rkbmn','irtamaController@updateirtamaRkbmn');
				Route::get('/add_irtama_rkbmn','irtamaController@addirtamaRkbmn')->name('add_irtama_rkbmn');
				Route::post('/input_irtama_rkbmn','irtamaController@inputirtamaRkbmn');
				Route::post('/delete_irtama_rkbmn','irtamaController@deleteIrtamaRkbmn')->name('delete_irtama_rkbmn');
				Route::get('/print_irtama_rkbmn','irtamaController@printPageirtamaRkbmn');
				Route::get('/view','irtamaController@view');

				Route::match(['get', 'post'],'/irtama_lkip/{page?}','irtamaController@irtamaLkip')->name('irtama_lkip');
				Route::get('/edit_irtama_lkip/{id}','irtamaController@editirtamaLkip')->name('edit_irtama_lkip');
				Route::post('/update_irtama_lkip','irtamaController@updateirtamaLkip');
				Route::get('/add_irtama_lkip','irtamaController@addirtamaLkip')->name("add_irtama_lkip");
				Route::post('/input_irtama_lkip','irtamaController@inputirtamaLkip');
				Route::post('/delete_irtama_lkip','irtamaController@deleteIrtamaLkip')->name('delete_irtama_lkip');
				Route::get('/print_irtama_lkip','irtamaController@printPageirtamaLkip');
				Route::get('/view','irtamaController@view');
			});

			Route::group(['prefix'=>'sosialisasi'],function(){

				Route::match(['get','post'],'/irtama_sosialisasi/{page?}','irtamaController@irtamaSosialisasi')->name("irtama_sosialisasi");
				Route::get('/edit_irtama_sosialisasi/{id}','irtamaController@editirtamaSosialisasi')->name('edit_irtama_sosialisasi');
				Route::post('/update_irtama_sosialisasi','irtamaController@updateirtamaSosialisasi');
				Route::get('/add_irtama_sosialisasi','irtamaController@addirtamaSosialisasi')->name('add_irtama_sosialisasi');
				Route::post('/delete_irtama_sosialisasi','irtamaController@deleteirtamaSosialisasi')->name('delete_irtama_sosialisasi');

				Route::post('/input_irtama_sosialisasi','irtamaController@inputirtamaSosialisasi');
				Route::get('/view','irtamaController@view');
			});

			Route::group(['prefix'=>'verifikasi'],function(){
				Route::match(['get','post'],'/irtama_verifikasi/{page?}','irtamaController@irtamaVerifikasi')->name('irtama_verifikasi');
				Route::get('/edit_irtama_verifikasi/{id}','irtamaController@editirtamaVerifikasi')->name('edit_irtama_verifikasi');
				Route::post('/update_irtama_verifikasi','irtamaController@updateirtamaVerifikasi');
				Route::get('/add_irtama_verifikasi','irtamaController@addirtamaVerifikasi')->name('add_irtama_verifikasi');
				Route::post('/input_irtama_verifikasi','irtamaController@inputirtamaVerifikasi');
				Route::post('/delete_irtama_verifikasi','irtamaController@deleteIrtamaVerifikasi')->name('delete_irtama_verifikasi');
				Route::get('/print_irtama_verifikasi','irtamaController@printPageirtamaVerifikasi');
				Route::get('/view','irtamaController@view');
			});

			Route::group(['prefix'=>'sop'],function(){

				Route::match(['get','post'],'/irtama_sop/{page?}','irtamaController@irtamaSop')->name('irtama_sop');
				Route::get('/edit_irtama_sop/{id}','irtamaController@editirtamaSop')->name('edit_irtama_sop');
				Route::post('/update_irtama_sop','irtamaController@updateirtamaSop');
				Route::get('/add_irtama_sop','irtamaController@addirtamaSop')->name('add_irtama_sop');
				Route::post('/input_irtama_sop','irtamaController@inputirtamaSop');
				Route::post('/delete_irtama_sop','irtamaController@deleteIrtamaSop')->name('delete_irtama_sop');
				Route::get('/print_irtama_sop','irtamaController@printPageirtamaSop');
				Route::get('/view','irtamaController@view');
			});

			Route::group(['prefix'=>'penegakan'],function(){

				Route::match(['get','post'],'/irtama_penegakan/{page?}','irtamaController@irtamaPenegakan')->name('irtama_penegakan');
				Route::get('/edit_irtama_penegakan/{id}','irtamaController@editirtamaPenegakan')->name('edit_irtama_penegakan');
				Route::post('/update_irtama_penegakan','irtamaController@updateirtamaPenegakan');
				Route::get('/add_irtama_penegakan','irtamaController@addirtamaPenegakan')->name('add_irtama_penegakan');
				Route::post('/input_irtama_penegakan','irtamaController@inputirtamaPenegakan');
				Route::get('/print_irtama_penegakan','irtamaController@printPageirtamaPenegakan');
				Route::get('/view','irtamaController@view');

			});

			Route::group(['prefix'=>'apel'],function(){

				Route::match(['get','post'],'/irtama_apel/{page?}','irtamaController@irtamaApel')->name('irtama_apel');
				Route::get('/edit_irtama_apel/{id}','irtamaController@editirtamaApel')->name('edit_irtama_apel');
				Route::post('/update_irtama_apel','irtamaController@updateirtamaApel');
				Route::get('/add_irtama_apel','irtamaController@addirtamaApel')->name('add_irtama_apel');
				Route::post('/input_irtama_apel','irtamaController@inputirtamaApel');
				Route::get('/print_irtama_apel','irtamaController@printPageirtamaApel');
				Route::get('/view','irtamaController@view');
			});


			Route::group(['prefix'=>'riktu'],function(){

				Route::match(['get', 'post'] ,'/irtama_riktu/{page?}','irtamaController@irtamaRiktu')->name('irtama_riktu');
				Route::get('/edit_irtama_riktu/{id}','irtamaController@editirtamaRiktu')->name('edit_irtama_riktu');
				Route::post('/update_irtama_riktu','irtamaController@updateirtamaRiktu');
				Route::get('/add_irtama_riktu','irtamaController@addirtamaRiktu')->name('add_irtama_riktu');
				Route::post('/input_irtama_riktu','irtamaController@inputirtamaRiktu');
				Route::post('/delete_irtama_riktu','irtamaController@deleteirtamaRiktu')->name('delete_irtama_riktu');
				// Route::get('/view','irtamaController@view');

			});

	});

	Route::group(['prefix'=>'balai_diklat'],function(){
			Route::group(['prefix'=>'pendidikan'],function(){
				Route::match(['get','post'],'/pendidikan_pelatihan/{page?}','pendidikanController@pendidikanPelatihan')->name('pendidikan_pelatihan');
				Route::get('/edit_pendidikan_pelatihan/{id}','pendidikanController@editpendidikanPelatihan')->name('edit_pendidikan_pelatihan');
				Route::get('/add_pendidikan_pelatihan','pendidikanController@addpendidikanPelatihan')->name('add_pendidikan_pelatihan');
				Route::post('/save_pendidikan_pelatihan','pendidikanController@addpendidikanPelatihan')->name('save_pendidikan_pelatihan');
				Route::post('/update_pendidikan_pelatihan','pendidikanController@updatependidikanPelatihan')->name('update_pendidikan_pelatihan');
				Route::post('/delete_pendidikan_pelatihan','pendidikanController@deletependidikanPelatihan')->name('delete_pendidikan_pelatihan');

				Route::get('/edit_peserta_pelatihan/{id}', 'pendidikanController@editPesertaPelatihan')->name('edit_peserta_pelatihan');
				Route::get('/add_peserta_pelatihan', 'pendidikanController@addPesertaPelatihan')->name('add_peserta_pelatihan');
				Route::post('/save_peserta_pelatihan', 'pendidikanController@addPesertaPelatihan')->name('save_peserta_pelatihan');
				Route::post('/update_peserta_pelatihan', 'pendidikanController@updatePesertaPelatihan')->name('update_peserta_pelatihan');
				Route::post('/delete_peserta_pelatihan', 'pendidikanController@deletePesertaPelatihan')->name('delete_peserta_pelatihan');
				Route::get('/index_peserta_pelatihan/{parent_id}/{page?}', 'pendidikanController@indexPesertaPelatihan')->name('index_peserta_pelatihan');

				Route::get('page_balai_diklat/{segment?}/{page?}','pendidikanController@printPage')->name('page_balai_diklat');

			});
	});

	Route::group(['prefix'=>'balai_lab'],function(){
			Route::group(['prefix'=>'pengujian'],function(){
				Route::match(['get','post'],'/berkas_sampel/{page?}','pengujianController@pengujianBahan')->name('berkas_sampel');
				Route::get('/edit_berkas_sampel/{id}','pengujianController@editpengujianBahan')->name('edit_berkas_sampel');
				Route::get('/add_berkas_sampel','pengujianController@addpengujianBahan')->name('add_berkas_sampel');
				Route::post('/save_berkas_sampel','pengujianController@addpengujianBahan')->name('save_berkas_sampel');
				Route::post('/update_berkas_sampel','pengujianController@updatePengujianBahan')->name('update_berkas_sampel');
				Route::post('/delete_berkas_sampel','pengujianController@deletePengujianBahan')->name('delete_berkas_sampel');
			});

			Route::get('print_page/{page?}','pengujianController@printPage')->name('print_balai_lab');
	});

	Route::group(['prefix'=>'puslitdatin'],function(){
		Route::match(['get','post'],'/call_center/{page?}','PuslidatinController@CallCenter')->name('call_center');
		Route::group(['prefix'=>'bidang_litbang'],function(){
			Route::match(['get','post'],'/survey/{page?}','PuslidatinController@survey')->name('survey');
			Route::get('/edit_survey/{id}','PuslidatinController@editSurvey')->name('edit_survey');
			Route::get('/add_survey','PuslidatinController@addSurvey')->name('add_survey');;
			Route::post('/save_survey','PuslidatinController@addSurvey');
			Route::post('/update_survey','PuslidatinController@updateSurvey');
			Route::post('/delete_survey','PuslidatinController@deleteSurvey')->name('delete_survey');

			Route::match(['get','post'],'/survey_narkoba/{page?}','PuslidatinController@surveyNarkoba')->name('survey_narkoba');
			Route::get('/edit_survey_narkoba/{id}','PuslidatinController@editSurveyNarkoba')->name('edit_survey_narkoba');
			Route::get('/add_survey_narkoba','PuslidatinController@addSurveyNarkoba')->name('add_survey_narkoba');;
			Route::post('/save_survey_narkoba','PuslidatinController@addSurveyNarkoba');
			Route::post('/update_survey_narkoba','PuslidatinController@updateSurveyNarkoba')->name('update_survey_narkoba');
			Route::post('/delete_survey_narkoba','PuslidatinController@deleteSurveyNarkoba')->name('delete_survey_narkoba');

			Route::match(['get','post'],'/survey_narkoba_ketergantungan/{page?}','PuslidatinController@surveyNarkobaKetergantungan')->name('survey_narkoba_ketergantungan');
			Route::get('/edit_survey_narkoba_ketergantungan/{id}','PuslidatinController@editSurveyNarkobaKetergantungan')->name('edit_survey_narkoba_ketergantungan');
			Route::get('/add_survey_narkoba_ketergantungan','PuslidatinController@addSurveyNarkobaKetergantungan')->name('add_survey_narkoba_ketergantungan');;
			Route::post('/save_survey_narkoba_ketergantungan','PuslidatinController@addSurveyNarkobaKetergantungan');
			Route::post('/update_survey_narkoba_ketergantungan','PuslidatinController@updateSurveyNarkobaKetergantungan')->name('update_survey_narkoba_ketergantungan');
			Route::post('/delete_survey_narkoba_ketergantungan','PuslidatinController@deleteSurveyNarkobaKetergantungan')->name('delete_survey_narkoba_ketergantungan');

			Route::match(['get','post'],'/riset_penyalahgunaan_narkoba/{page?}','PuslidatinController@risetPenyalahgunaanNarkoba')->name('riset_penyalahgunaan_narkoba');
			Route::get('/edit_riset_penyalahgunaan_narkoba/{id}','PuslidatinController@editRisetPenyalahgunaanNarkoba')->name('edit_riset_penyalahgunaan_narkoba');
			Route::get('/add_riset_penyalahgunaan_narkoba','PuslidatinController@addRisetPenyalahgunaanNarkoba')->name('add_riset_penyalahgunaan_narkoba');
			Route::post('/save_riset_penyalahgunaan_narkoba','PuslidatinController@addRisetPenyalahgunaanNarkoba');
			Route::post('/update_riset_penyalahgunaan_narkoba','PuslidatinController@updateRisetPenyalahgunaanNarkoba');
			Route::post('/delete_riset_penyalahgunaan_narkoba','PuslidatinController@deleteRisetPenyalahgunaanNarkoba')->name('delete_riset_penyalahgunaan_narkoba');

			Route::get('/penyalahgunasetahun_pakai/{page?}','PuslidatinController@PenyalahgunaSetahunPakai')->name('penyalahgunasetahun_pakai');
			Route::get('/edit_penyalahgunasetahun_pakai/{id}','PuslidatinController@editPenyalahgunaSetahunPakai')->name('edit_penyalahgunasetahun_pakai');;
			Route::get('/add_penyalahgunasetahun_pakai','PuslidatinController@addPenyalahgunaSetahunPakai')->name('add_penyalahgunasetahun_pakai');;
			Route::post('/save_penyalahgunasetahun_pakai','PuslidatinController@addPenyalahgunaSetahunPakai');
			Route::post('/update_penyalahgunasetahun_pakai','PuslidatinController@updatePenyalahgunaSetahunPakai');
			Route::post('/delete_penyalahgunasetahun_pakai','PuslidatinController@deletePenyalahgunaSetahunPakai');

			Route::get('/penyalahguna_teratur_pakai/{page?}','PuslidatinController@penyalahgunaTeraturPakai')->name('penyalahguna_teratur_pakai');
			Route::get('/edit_penyalahguna_teratur_pakai/{id}','PuslidatinController@editPenyalahgunaTeraturPakai')->name('edit_penyalahguna_teratur_pakai');
			Route::get('/add_penyalahguna_teratur_pakai','PuslidatinController@addPenyalahgunaTeraturPakai')->name('add_penyalahguna_teratur_pakai');
			Route::post('/save_penyalahguna_teratur_pakai','PuslidatinController@addPenyalahgunaTeraturPakai');
			Route::post('/update_penyalahguna_teratur_pakai','PuslidatinController@updatePenyalahgunaTeraturPakai');
			Route::post('/delete_penyalahguna_teratur_pakai','PuslidatinController@deletePenyalahgunaTeraturPakai')->name('delete_penyalahguna_teratur_pakai');


			Route::get('/penyalahgunaan_coba_pakai/{page?}','PuslidatinController@penyalahgunaanCobaPakai')->name('penyalahgunaan_coba_pakai');
			Route::get('/edit_penyalahgunaan_coba_pakai/{id}','PuslidatinController@editPenyalahgunaanCobaPakai')->name('edit_penyalahgunaan_coba_pakai');
			Route::get('/add_penyalahgunaan_coba_pakai','PuslidatinController@addPenyalahgunaanCobaPakai')->name('add_penyalahgunaan_coba_pakai');
			Route::post('/save_penyalahgunaan_coba_pakai','PuslidatinController@addPenyalahgunaanCobaPakai');
			Route::post('/update_penyalahgunaan_coba_pakai','PuslidatinController@updatePenyalahgunaanCobaPakai');
			Route::post('/delete_penyalahgunaan_coba_pakai','PuslidatinController@deletePenyalahgunaanCobaPakai')->name("delete_penyalahgunaan_coba_pakai");



			Route::get('/penyalahguna_pecandu_non_suntik/{page?}','PuslidatinController@penyalahgunaPecanduNonSuntik')->name('penyalahguna_pecandu_non_suntik');
			Route::get('/edit_penyalahguna_pecandu_non_suntik/{id}','PuslidatinController@editpenyalahgunaPecanduNonSuntik')->name('edit_penyalahguna_pecandu_non_suntik');
			Route::get('/add_penyalahguna_pecandu_non_suntik','PuslidatinController@addpenyalahgunaPecanduNonSuntik')->name('add_penyalahguna_pecandu_non_suntik');
			Route::post('/save_penyalahguna_pecandu_non_suntik','PuslidatinController@addpenyalahgunaPecanduNonSuntik');
			Route::post('/update_penyalahguna_pecandu_non_suntik','PuslidatinController@updatepenyalahgunaPecanduNonSuntik');
			Route::post('/delete_penyalahguna_pecandu_non_suntik','PuslidatinController@deletepenyalahgunaPecanduNonSuntik')->name("delete_penyalahguna_pecandu_non_suntik");

			Route::get('/penyalahguna_pecandu_suntik/{page?}','PuslidatinController@penyalahgunaPecanduSuntik')->name('penyalahguna_pecandu_suntik');
			Route::get('/edit_penyalahguna_pecandu_suntik/{id}','PuslidatinController@editpenyalahgunaPecanduSuntik')->name('edit_penyalahguna_pecandu_suntik');
			Route::get('/add_penyalahguna_pecandu_suntik','PuslidatinController@addpenyalahgunaPecanduSuntik')->name('add_penyalahguna_pecandu_suntik');
			Route::post('/save_penyalahguna_pecandu_suntik','PuslidatinController@addpenyalahgunaPecanduSuntik');
			Route::post('/update_penyalahguna_pecandu_suntik','PuslidatinController@updatepenyalahgunaPecanduSuntik');
			Route::post('/delete_penyalahguna_pecandu_suntik','PuslidatinController@deletepenyalahgunaPecanduSuntik')->name('delete_penyalahguna_pecandu_suntik');

			Route::get('/penyalahguna_setahun_pakai/{page?}','PuslidatinController@penyalahgunaSetahunPakai')->name('penyalahguna_setahun_pakai');
			Route::get('/edit_penyalahguna_setahun_pakai/{id}','PuslidatinController@editPenyalahgunaSetahunPakai')->name('edit_penyalahguna_setahun_pakai');
			Route::get('/add_penyalahguna_setahun_pakai','PuslidatinController@addPenyalahgunaSetahunPakai')->name('add_penyalahguna_setahun_pakai');
			Route::post('/save_penyalahguna_setahun_pakai','PuslidatinController@addPenyalahgunaSetahunPakai');
			Route::post('/update_penyalahguna_setahun_pakai','PuslidatinController@updatePenyalahgunaSetahunPakai');
			Route::post('/delete_penyalahguna_setahun_pakai','PuslidatinController@deletePenyalahgunaSetahunPakai')->name('delete_penyalahguna_setahun_pakai');

			Route::get('/data_penelitian_bnn/{page?}','PuslidatinController@dataPenelitianBNN')->name('data_penelitian_bnn');
			Route::get('/edit_data_penelitian_bnn/{id}','PuslidatinController@editDataPenelitianBNN')->name('edit_data_penelitian_bnn');
			Route::get('/add_data_penelitian_bnn','PuslidatinController@addDataPenelitianBNN')->name('add_data_penelitian_bnn');
			Route::post('/save_data_penelitian_bnn','PuslidatinController@addDataPenelitianBNN');
			Route::post('/update_data_penelitian_bnn','PuslidatinController@updateDataPenelitianBNN');
			Route::post('/delete_data_penelitian_bnn','PuslidatinController@deleteDataPenelitianBNN');



			Route::get('/print_page_puslitdatin/{segment?}/{page?}','PuslidatinController@printPage')->name('print_page_puslitdatin');
		});

		Route::group(['prefix'=>'bidang_tik'],function(){

			Route::match(['get','post'],'/pekerjaan_jaringan/{page?}','PuslidatinController@PekerjaanJaringan')->name('pekerjaan_jaringan');
			Route::get('/edit_pekerjaan_jaringan/{id}','PuslidatinController@editPekerjaanJaringan')->name('edit_pekerjaan_jaringan');
			Route::get('/add_pekerjaan_jaringan','PuslidatinController@addPekerjaanJaringan')->name('add_pekerjaan_jaringan');
			Route::post('/save_pekerjaan_jaringan','PuslidatinController@addPekerjaanJaringan');
			Route::post('/update_pekerjaan_jaringan','PuslidatinController@updatePekerjaanJaringan')->name("update_pekerjaan_jaringan");
			Route::post('/delete_pekerjaan_jaringan','PuslidatinController@deletePekerjaanJaringan')->name("delete_pekerjaan_jaringan");

			Route::match(['get','post'],'/pengecekan_jaringan/{page?}','PuslidatinController@PengecekanJaringan')->name('pengecekan_jaringan');
			Route::get('/edit_pengecekan_jaringan/{id}','PuslidatinController@editPengecekanJaringan')->name('edit_pengecekan_jaringan');
			Route::get('/add_pengecekan_jaringan','PuslidatinController@addPengecekanJaringan')->name('add_pengecekan_jaringan');
			Route::post('/save_pengecekan_jaringan','PuslidatinController@addPengecekanJaringan');
			Route::post('/update_pengecekan_jaringan','PuslidatinController@updatePengecekanJaringan')->name('update_pengecekan_jaringan');
			Route::post('/delete_pengecekan_jaringan','PuslidatinController@deletePengecekanJaringan')->name("delete_pengecekan_jaringan");

			Route::match(['get','post'],'/pengadaan_email/{page?}','PuslidatinController@PengadaanEmail')->name('pengadaan_email');
			Route::get('/edit_pengadaan_email/{id}','PuslidatinController@editPengadaanEmail')->name('edit_pengadaan_email');
			Route::get('/add_pengadaan_email','PuslidatinController@addPengadaanEmail')->name('add_pengadaan_email');
			Route::post('/save_pengadaan_email','PuslidatinController@addPengadaanEmail')->name('save_pengadaan_email');
			Route::post('/update_pengadaan_email','PuslidatinController@updatePengadaanEmail')->name('update_pengadaan_email');
			Route::post('/delete_pengadaan_email','PuslidatinController@deletePengadaanEmail')->name("delete_pengadaan_email");

			Route::get('/print_page/{segment?}/{page?}','PuslidatinController@printPage');

			//old//
			Route::match(['get','post'],'/informasi_melalui_contact_center/{page?}','PuslidatinController@informasiMelaluiContactCenter')->name('informasi_melalui_contact_center');
			Route::get('/edit_informasi_melalui_contact_center/{id}','PuslidatinController@editInformasiMelaluiContactCenter')->name('edit_informasi_melalui_contact_center');
			Route::get('/add_informasi_melalui_contact_center','PuslidatinController@addInformasiMelaluiContactCenter')->name('add_informasi_melalui_contact_center');
			Route::post('/save_informasi_melalui_contact_center','PuslidatinController@addInformasiMelaluiContactCenter');
			Route::post('/update_informasi_melalui_contact_center','PuslidatinController@updateInformasiMelaluiContactCenter');
			Route::post('/delete_informasi_melalui_contact_center','PuslidatinController@deleteInformasiMelaluiContactCenter')->name("delete_informasi_melalui_contact_center");

			Route::get('/tindak_lanjut_bnn_pusat/{page?}','PuslidatinController@tindakLanjutBNNPusat')->name('tindak_lanjut_bnn_pusat');
			Route::get('/edit_tindak_lanjut_bnn_pusat/{id}','PuslidatinController@editTindakLanjutBNNPusat')->name('edit_tindak_lanjut_bnn_pusat');
			Route::get('/add_tindak_lanjut_bnn_pusat','PuslidatinController@addTindakLanjutBNNPusat')->name('add_tindak_lanjut_bnn_pusat');
			Route::post('/save_tindak_lanjut_bnn_pusat','PuslidatinController@addTindakLanjutBNNPusat');
			Route::post('/update_tindak_lanjut_bnn_pusat','PuslidatinController@updateTindakLanjutBNNPusat');
			Route::post('/delete_tindak_lanjut_bnn_pusat','PuslidatinController@deleteTindakLanjutBNNPusat')->name('delete_tindak_lanjut_bnn_pusat');


			Route::get('/tindak_lanjut_bnn/{page?}','PuslidatinController@tindakLanjutBNNP')->name('tindak_lanjut_bnn');
			Route::get('/edit_tindak_lanjut_bnn/{id}','PuslidatinController@editTindakLanjutBNNP')->name('edit_tindak_lanjut_bnn');
			Route::get('/add_tindak_lanjut_bnn','PuslidatinController@addTindakLanjutBNNP')->name('add_tindak_lanjut_bnn');
			Route::post('/save_tindak_lanjut_bnn','PuslidatinController@addTindakLanjutBNNP');
			Route::post('/update_tindak_lanjut_bnn','PuslidatinController@updateTindakLanjutBNNP');
			Route::post('/delete_tindak_lanjut_bnn','PuslidatinController@deleteTindakLanjutBNNP')->name('delete_tindak_lanjut_bnn');

			Route::get('/print_page/{segment?}/{page?}','PuslidatinController@printPage');
		});
	});
	Route::group(['prefix'=>'settings'],function(){
		Route::get('/monitoring_nihil/{page?}','AdministratorController@monitoringNihil');
		Route::get('/user_management/','AdministratorController@userManagement');
		Route::get('/user_role/','AdministratorController@userRole');
	});

	Route::group(['prefix'=>'balai_besar'],function(){
		Route::group(['prefix'=>'magang'],function(){
			Route::match(['get','post'],'/data_magang/{page?}','balaiBesarController@magang')->name('data_magang');
			Route::get('/edit_magang/{id}','balaiBesarController@editMagang')->name('edit_magang');
			Route::get('/add_magang','balaiBesarController@addMagang')->name('add_magang');
			Route::post('/save_magang','balaiBesarController@addMagang')->name('save_magang');
			Route::post('/update_magang','balaiBesarController@updateMagang')->name('update_magang');
			Route::post('/delete_magang','balaiBesarController@deleteMagang')->name('delete_magang');

		});
		Route::get('print_balai_besar/{segment}/{page?}','balaiBesarController@printPage')->name('print_balai_besar');
	});

	Route::group(['prefix'=>'settama'],function(){
		Route::group(['prefix'=>'keuangan'],function(){
			// Route::get('/sekretariat_utama/{page?}','settamaController@sekretariatUtamaKeuangan')->name('settama_keuangan');
			Route::match(['get', 'post'], '/settama_umum/{page?}','settamaController@sekretariatUtamaKeuangan')->name('settama_keuangan');
			Route::get('/edit_sekretariat_utama/{id}','settamaController@editSekretariatUtamaKeuangan')->name('edit_settama_keuangan');
			Route::get('/add_sekretariat_utama','settamaController@addSekretariatUtamaKeuangan')->name('add_settama_keuangan');
			Route::post('/save_sekretariat_utama','settamaController@addSekretariatUtamaKeuangan')->name('save_settama_keuangan');
			Route::post('/update_sekretariat_utama','settamaController@updateSekretariatUtamaKeuangan')->name('update_settama_keuangan');
			Route::post('/delete_sekretariat_utama','settamaController@deleteSekretariatUtamaKeuangan')->name('delete_settama_keuangan');
			Route::get('print_settama/{segment}/{page?}','settamaController@printPageKeuangan')->name('print_settama_keuangan');
		});

		Route::group(['prefix'=>'umum'],function(){
			Route::match(['get', 'post'], '/settama_umum/{page?}','settamaController@sekretariatUtamaUmum')->name('settama_umum');
			// Route::get('/settama_umum/{page?}','settamaController@sekretariatUtamaUmum')->name('settama_umum');
			// Route::post('/settama_umum','settamaController@sekretariatUtamaUmum')->name('post_sekretariat_utama');
			Route::get('/edit_settama_umum/{id}','settamaController@editSekretariatUtamaUmum')->name('edit_settama_umum');
			Route::get('/add_sekretariat_utama','settamaController@addSekretariatUtamaUmum')->name('add_settama_umum');
			Route::post('/save_sekretariat_utama','settamaController@addSekretariatUtamaUmum')->name('save_settama_umum');
			Route::post('/update_sekretariat_utama','settamaController@updateSekretariatUtamaUmum')->name('update_settama_umum');
			Route::post('/delete_sekretariat_utama','settamaController@deleteSekretariatUtamaUmum')->name('delete_settama_umum');
			Route::get('print_settama/{segment}/{page?}','settamaController@printPageUmum')->name('print_settama_umum');
		});

		Route::group(['prefix'=>'perencanaan'],function(){
			// Route::get('/sekretariat_utama/{page?}','settamaController@sekretariatUtamaPerencanaan')->name('settama_perencanaan');
			Route::match(['get', 'post'], '/sekretariat_utama/{page?}','settamaController@sekretariatUtamaPerencanaan')->name('settama_perencanaan');
			Route::get('/edit_sekretariat_utama/{id}','settamaController@editSekretariatUtamaPerencanaan')->name('edit_settama_perencanaan');
			Route::get('/add_sekretariat_utama','settamaController@addSekretariatUtamaPerencanaan')->name('add_settama_perencanaan');
			Route::post('/save_sekretariat_utama','settamaController@addSekretariatUtamaPerencanaan')->name('save_settama_perencanaan');
			Route::post('/update_sekretariat_utama','settamaController@updateSekretariatUtamaPerencanaan')->name('update_settama_perencanaan');
			Route::post('/delete_sekretariat_utama','settamaController@deleteSekretariatUtamaPerencanaan')->name('delete_settama_perencanaan');
			Route::get('print_settama/{segment}/{page?}','settamaController@printPagePerencanaan')->name('print_settama_perencanaan');
		});

		Route::group(['prefix'=>'kepegawaian'],function(){
			// Route::get('/sekretariat_utama/{page?}','settamaController@sekretariatUtamaKepegawaian')->name('settama_kepegawaian');
			Route::match(['get', 'post'], '/sekretariat_utama/{page?}','settamaController@sekretariatUtamaKepegawaian')->name('settama_kepegawaian');
			Route::get('/edit_sekretariat_utama/{id}','settamaController@editSekretariatUtamaKepegawaian')->name('edit_settama_kepegawaian');
			Route::get('/add_sekretariat_utama','settamaController@addSekretariatUtamaKepegawaian')->name('add_settama_kepegawaian');
			Route::post('/save_sekretariat_utama','settamaController@addSekretariatUtamaKepegawaian')->name('save_settama_kepegawaian');
			Route::post('/update_sekretariat_utama','settamaController@updateSekretariatUtamaKepegawaian')->name('update_settama_kepegawaian');
			Route::post('/delete_sekretariat_utama','settamaController@deleteSekretariatUtamaKepegawaian')->name('delete_settama_kepegawaian');
			Route::get('print_settama/{segment}/{page?}','settamaController@printPageKepegawaian')->name('print_settama_kepegawaian');
		});


		// Route::get('/sekretariat_utama/{page?}','settamaController@sekretariatUtama')->name('sekretariat_utama');
		// Route::get('/edit_sekretariat_utama/{id}','settamaController@editSekretariatUtama')->name('edit_sekretariat_utama');
		// Route::get('/add_sekretariat_utama','settamaController@addSekretariatUtama')->name('add_sekretariat_utama');
		// Route::post('/save_sekretariat_utama','settamaController@addSekretariatUtama')->name('save_sekretariat_utama');
		// Route::post('/update_sekretariat_utama','settamaController@updateSekretariatUtama')->name('update_sekretariat_utama');
		// Route::post('/delete_sekretariat_utama','settamaController@deleteSekretariatUtama')->name('delete_sekretariat_utama');
		// Route::get('print_settama/{segment}/{page?}','settamaController@printPage')->name('print_settama');
	});

	Route::group(['prefix'=>'arahan'],function(){
		Route::group(['prefix'=>'pimpinan'],function(){
			Route::match(['get','post'],'/arahan_pimpinan/{page?}','arahanController@arahanPimpinan')->name('arahan_pimpinan');
			Route::get('/edit_arahan_pimpinan/{id}','arahanController@editarahanPimpinan')->name('edit_arahan_pimpinan');
			Route::get('/add_arahan_pimpinan','arahanController@addarahanPimpinan')->name('add_arahan_pimpinan');
			Route::post('/save_arahan_pimpinan','arahanController@addarahanPimpinan')->name('save_arahan_pimpinan');
			Route::post('/update_arahan_pimpinan','arahanController@updatearahanPimpinan')->name('update_arahan_pimpinan');
			Route::post('/delete_arahan_pimpinan','arahanController@deletearahanPimpinan')->name('delete_arahan_pimpinan');
		});
		Route::get('print_arahan_pimpinan/{page?}','arahanController@printPage')->name('print_arahan_pimpinan');
	});

	Route::group(['prefix'=>'master'],function(){
		Route::match(['get','post'],'/instansi/{page?}','MasterController@dataInstansi')->name('dataInstansi');
		Route::post('/save_instansi','MasterController@inputDataInstansi')->name('save_dataInstansi');
		Route::post('/update_instansi','MasterController@updateDataInstansi')->name('update_dataInstansi');
		Route::post('/delete_instansi','MasterController@deleteDataInstansi')->name('delete_dataInstansi');

		Route::match(['get','post'],'/propinsi/{page?}','MasterController@dataPropinsi')->name('dataPropinsi');
		Route::post('/save_propinsi','MasterController@inputDataPropinsi')->name('save_dataPropinsi');
		Route::post('/update_propinsi','MasterController@updateDataPropinsi')->name('update_dataPropinsi');
		Route::post('/delete_propinsi','MasterController@deleteDataPropinsi')->name('delete_dataPropinsi');

		Route::match(['get','post'],'/kota/{page?}','MasterController@dataKota')->name('dataKota');
		Route::post('/save_kota','MasterController@inputDataKota')->name('save_dataKota');
		Route::post('/update_kota','MasterController@updateDataKota')->name('update_dataKota');
		Route::post('/delete_kota','MasterController@deleteDataKota')->name('delete_dataKota');

		Route::match(['get','post'],'/jeniskasus/{page?}','MasterController@dataJeniskasus')->name('dataJeniskasus');
		Route::post('/save_jeniskasus','MasterController@inputDataJeniskasus')->name('save_dataJeniskasus');
		Route::post('/update_jeniskasus','MasterController@updateDataJeniskasus')->name('update_dataJeniskasus');
		Route::post('/delete_jeniskasus','MasterController@deleteDataJeniskasus')->name('delete_dataJeniskasus');

		Route::match(['get','post'],'/jenisbarbuk/{page?}','MasterController@dataJenisbarbuk')->name('dataJenisbarbuk');
		Route::post('/save_jenisbarbuk','MasterController@inputDataJenisbarbuk')->name('save_dataJenisbarbuk');
		Route::post('/update_jenisbarbuk','MasterController@updateDataJenisbarbuk')->name('update_dataJenisbarbuk');
		Route::post('/delete_jenisbarbuk','MasterController@deleteDataJenisbarbuk')->name('delete_dataJenisbarbuk');

		Route::match(['get','post'],'/barangbukti/{page?}','MasterController@dataBarangbukti')->name('dataBarangbukti');
		Route::post('/save_barangbukti','MasterController@inputDataBarangbukti')->name('save_dataBarangbukti');
		Route::post('/update_barangbukti','MasterController@updateDataBarangbukti')->name('update_dataBarangbukti');
		Route::post('/delete_barangbukti','MasterController@deleteDataBarangbukti')->name('delete_dataBarangbukti');

		Route::match(['get','post'],'/satuan/{page?}','MasterController@dataSatuan')->name('dataSatuan');
		Route::post('/save_satuan','MasterController@inputDataSatuan')->name('save_dataSatuan');
		Route::post('/update_satuan','MasterController@updateDataSatuan')->name('update_dataSatuan');
		Route::post('/delete_satuan','MasterController@deleteDataSatuan')->name('delete_dataSatuan');

		Route::match(['get','post'],'/mediaonline/{page?}','MasterController@dataMediaonline')->name('dataMediaonline');
		Route::post('/save_mediaonline','MasterController@inputDataMediaonline')->name('save_dataMediaonline');
		Route::post('/update_mediaonline','MasterController@updateDataMediaonline')->name('update_dataMediaonline');
		Route::post('/delete_mediaonline','MasterController@deleteDataMediaonline')->name('delete_dataMediaonline');

		Route::match(['get','post'],'/mediasosial/{page?}','MasterController@dataMediasosial')->name('dataMediasosial');
		Route::post('/save_mediasosial','MasterController@inputDataMediasosial')->name('save_dataMediasosial');
		Route::post('/update_mediasosial','MasterController@updateDataMediasosial')->name('update_dataMediasosial');
		Route::post('/delete_mediasosial','MasterController@deleteDataMediasosial')->name('delete_dataMediasosial');

		Route::match(['get','post'],'/mediacetak/{page?}','MasterController@dataMediacetak')->name('dataMediacetak');
		Route::post('/save_mediacetak','MasterController@inputDataMediacetak')->name('save_dataMediacetak');
		Route::post('/update_mediacetak','MasterController@updateDataMediacetak')->name('update_dataMediacetak');
		Route::post('/delete_mediacetak','MasterController@deleteDataMediacetak')->name('delete_dataMediacetak');

		Route::match(['get','post'],'/mediaruang/{page?}','MasterController@dataMediaruang')->name('dataMediaruang');
		Route::post('/save_mediaruang','MasterController@inputDataMediaruang')->name('save_dataMediaruang');
		Route::post('/update_mediaruang','MasterController@updateDataMediaruang')->name('update_dataMediaruang');
		Route::post('/delete_mediaruang','MasterController@deleteDataMediaruang')->name('delete_dataMediaruang');

		Route::match(['get','post'],'/bagian/{page?}','MasterController@dataBagian')->name('dataBagian');
		Route::post('/save_bagian','MasterController@inputDataBagian')->name('save_dataBagian');
		Route::post('/update_bagian','MasterController@updateDataBagian')->name('update_dataBagian');
		Route::post('/delete_bagian','MasterController@deleteDataBagian')->name('delete_dataBagian');

		Route::match(['get','post'],'/kegiatan/{page?}','MasterController@dataKegiatan')->name('dataKegiatan');
		Route::post('/save_kegiatan','MasterController@inputDataKegiatan')->name('save_dataKegiatan');
		Route::post('/update_kegiatan','MasterController@updateDataKegiatan')->name('update_dataKegiatan');
		Route::post('/delete_kegiatan','MasterController@deleteDataKegiatan')->name('delete_dataKegiatan');

		Route::match(['get','post'],'/komoditi/{page?}','MasterController@dataKomoditi')->name('dataKomoditi');
		Route::post('/save_komoditi','MasterController@inputDataKomoditi')->name('save_dataKomoditi');
		Route::post('/update_komoditi','MasterController@updateDataKomoditi')->name('update_dataKomoditi');
		Route::post('/delete_komoditi','MasterController@deleteDataKomoditi')->name('delete_dataKomoditi');

	});

	Route::group(['prefix'=>'user_management'],function(){
		Route::match(['get','post'],'/user/{page?}','UserManagementController@user')->name('user');
		Route::get('/add_user','UserManagementController@addUser')->name('add_user');
		Route::post('/change_nip','UserManagementController@updateNIP');
		Route::post('/reset_email','UserManagementController@resetEmail');
		Route::get('/reset_password','UserManagementController@resetPassword');
		Route::get('/edit_user/{id}','UserManagementController@editUser')->name('edit_user');
		Route::post('/input_user','UserManagementController@inputUser');
		Route::post('/update_user','UserManagementController@updateUser');
		Route::post('/delete_user','UserManagementController@deleteUser')->name('delete_user');
		Route::get('/print_user/{segment?}/{page?}','UserManagementController@printUser')->name("print_user");

		Route::match(['get','post'],'/group/{page?}','UserManagementController@dataGroup')->name('dataGroup');
		Route::get('/add_group','UserManagementController@adddataGroup')->name('add_dataGroup');
		Route::get('/edit_group/{id}','UserManagementController@editdataGroup')->name('edit_dataGroup');
		Route::post('/input_group','UserManagementController@inputdataGroup')->name('input_dataGroup');
		Route::post('/update_group','UserManagementController@updatedataGroup')->name('update_dataGroup');
		Route::post('/delete_group','UserManagementController@deletedataGroup')->name('delete_dataGroup');

		Route::match(['get','post'],'/loginlog/{page?}','UserManagementController@loginLog')->name('loginLog');
		Route::match(['get','post'],'/userlog/{page?}','UserManagementController@userLog')->name('userLog');
	});
	
	Route::group(['prefix'=>'omspan'],function(){
		Route::match(['get','post'],'/omspan','omspanController@omspan')->name('omspan');
	});

});
// // homecontroller
// Route::get('/home', 'homecontroller@index')->name('home');

// // AuthenticationController
// Route::get('/', 'AuthenticationController@login')->name('login');
// Route::get('/forgot_password', 'AuthenticationController@forgot_password')->name('forgot_password');
// Route::get('/set_password', 'AuthenticationController@set_password')->name('set_password');

// //usercontroller
// Route::get('/profile', 'UserController@profile')->name('profile');
// Route::get('/user_management', 'UserController@user_management')->name('user_management');
// Route::get('/user_management_add', 'UserController@user_management_add')->name('user_management_add');

// // //caseController
// Route::get('/kasus', 'caseController@kasus')->name('kasus');
// Route::get('/tambah_kasus', 'caseController@tambah_kasus')->name('tambah_kasus');
// Route::get('/edit_kasus', 'caseController@edit_kasus')->name('edit_kasus');
// Route::get('/detail_kasus', 'caseController@detail_kasus')->name('detail_kasus');
// Route::get('/Narkotika_Pemusnahan_ladangganja', 'caseController@Narkotika_Pemusnahan_ladangganja')->name('Narkotika_Pemusnahan_ladangganja');
// Route::get('/Narkotika_Pemusnahan_ladangganja_detail', 'caseController@Narkotika_Pemusnahan_ladangganja_detail')->name('Narkotika_Pemusnahan_ladangganja_detail');
// Route::get('/Narkotika_Pemusnahan_ladangganja_edit', 'caseController@Narkotika_Pemusnahan_ladangganja_edit')->name('Narkotika_Pemusnahan_ladangganja_edit');
// Route::get('/Narkotika_Pemusnahan_ladangganja_tambah', 'caseController@Narkotika_Pemusnahan_ladangganja_tambah')->name('Narkotika_Pemusnahan_ladangganja_tambah');
// Route::get('/Intel_Jaringan_Narkotika', 'caseController@Intel_Jaringan_Narkotika')->name('Intel_Jaringan_Narkotika');
// Route::get('/Intel_Jaringan_Narkotika_tambah', 'caseController@Intel_Jaringan_Narkotika_tambah')->name('Intel_Jaringan_Narkotika_tambah');
// Route::get('/Wastahti_Pemusnahan_Barangbukti', 'caseController@Wastahti_Pemusnahan_Barangbukti')->name('Wastahti_Pemusnahan_Barangbukti');
// Route::get('/Wastahti_Pemusnahan_Barangbukti_tambah', 'caseController@Wastahti_Pemusnahan_Barangbukti_tambah')->name('Wastahti_Pemusnahan_Barangbukti_tambah');
// Route::get('/Wastahti_Pendataan_Tahanan', 'caseController@Wastahti_Pendataan_Tahanan')->name('Wastahti_Pendataan_Tahanan');
// Route::get('/Wastahti_Pendataan_Tahanan_tambah', 'caseController@Wastahti_Pendataan_Tahanan_tambah')->name('Wastahti_Pendataan_Tahanan_tambah');
// Route::get('/Wastahti_Pendataan_Tahanan_edit', 'caseController@Wastahti_Pendataan_Tahanan_edit')->name('Wastahti_Pendataan_Tahanan_edit');
// Route::get('/Wastahti_Pendataan_Tahanan_detail', 'caseController@Wastahti_Pendataan_Tahanan_detail')->name('Wastahti_Pendataan_Tahanan_detail');




/* @author : Daniel Andi */
	Auth::routes();

/* @author : Daniel Andi */
//
//
// Route::get('/tes', 'latihancontroller@berantas');
